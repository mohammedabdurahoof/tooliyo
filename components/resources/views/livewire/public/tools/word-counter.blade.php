<div>

      <form wire:submit.prevent="onWordCounter">

            <div>
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                                            
                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" />
            </div>
        
            <div class="form-group mb-3">
                <button type="button" class="btn w-md-100">
                    <span>{{ __('Words') }}</span> 
                    <span class="badge bg-warning ms-2">{{ ( $data ) ? $data['words'] : 0 }}</span>
                </button>

                <button type="button" class="btn w-md-100">
                    <span>{{ __('Characters') }}</span>
                    <span class="badge bg-success ms-2">{{ ( $data ) ? $data['characters'] : 0 }}</span>
                </button>

                <button type="button" class="btn w-md-100">
                    <span>{{ __('Characters (with spaces)') }}</span>
                    <span class="badge bg-primary ms-2">{{ ( $data ) ? $data['characters_with_spaces'] : 0 }}</span>
                </button>

                <button type="button" class="btn w-md-100">
                    <span>{{ __('Paragraphs') }}</span>
                    <span class="badge bg-danger ms-2">{{ ( $data ) ? $data['paragraphs'] : 0 }}</span>
                </button>
            </div>

            <div class="form-group position-relative mb-3">
                <textarea id="input" class="form-control" wire:model.defer="text" rows="10" placeholder="{{ __('Paste your content here...') }}" required></textarea>

              <div id="paste" class="btn btn-icon cursor-pointer position-absolute top-0 end-0 m-2" title="{{ __('Paste') }}" data-bs-original-title="{{ __('Paste') }}" data-bs-toggle="tooltip" wire:ignore>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><rect x="9" y="3" width="6" height="4" rx="2" /></svg>
              </div>
            </div>

            @if ($generalSettings->captcha_status && ($generalSettings->captcha_for_registered || !auth()->check()))
              <x-public.recaptcha />
            @endif
            
            <div class="form-group text-center">
                <button class="btn btn-info w-md-100 mb-1 mb-md-0" wire:loading.attr="disabled">
                  <span>
                    <div wire:loading.inline wire:target="onWordCounter">
                      <x-loading />
                    </div>
                    <span>{{ __('Count') }}</span>
                  </span>
                </button>

                <button class="btn btn-lime w-md-100 mb-1 mb-md-0" wire:click.prevent="onSample" wire:loading.attr="disabled">
                  <span>
                    <div wire:loading.inline wire:target="onSample">
                      <x-loading />
                    </div>
                    <span>{{ __('Sample') }}</span>
                  </span>
                </button>

                <button class="btn btn-warning w-md-100" wire:click.prevent="onReset" wire:loading.attr="disabled">
                  <span>
                    <div wire:loading.inline wire:target="onReset">
                      <x-loading />
                    </div>
                    <span>{{ __('Reset') }}</span>
                  </span>
                </button>
            </div>

      </form>

  <script>
  (function( $ ) {
    "use strict";

    document.addEventListener('livewire:load', function () {

      var el      = document.getElementById('paste');
      var input   = document.getElementById('input');
      var tooltip = new bootstrap.Tooltip(el);

      var pasteIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"></path><rect x="9" y="3" width="6" height="4" rx="2"></rect></svg>';
      var clearIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"></path> <line x1="4" y1="7" x2="20" y2="7"></line> <line x1="10" y1="11" x2="10" y2="17"></line> <line x1="14" y1="11" x2="14" y2="17"></line> <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path> <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path> </svg>';

      function setPasteIcon() {
        el.innerHTML = pasteIcon;
        tooltip.dispose();
        el.setAttribute('title', '{{ __('Paste') }}');
        el.classList.add('btn-cyan');
        el.classList.remove('btn-danger');
        tooltip = new bootstrap.Tooltip(el);
      }

      function setClearIcon() {
        el.innerHTML = clearIcon;
        tooltip.dispose();
        el.setAttribute('title', '{{ __('Clear') }}');
        el.classList.add('btn-danger');
        el.classList.remove('btn-cyan');
        tooltip = new bootstrap.Tooltip(el);
      }

      function checkInputValue() {
        if (input.value) {
          setClearIcon();
        } else {
          setPasteIcon();
        }
      }

      checkInputValue(); // Initial check in case there's a value already

      // Handle click on the icon
      el.addEventListener('click', function() {
        if (el.innerHTML === clearIcon) {
          // Clear action
          @this.set('text', ''); // Update Livewire state
          setPasteIcon();
        } else {
          // Paste action
          navigator.clipboard.readText().then(function(clipText) {
            @this.set('text', clipText);
            setClearIcon();
          }).catch(function() {
            // Handle error if needed
          });
        }
      });

      // Handle changes to the input field
      input.addEventListener('input', checkInputValue);

    });

  })( jQuery );
  </script>
</div>