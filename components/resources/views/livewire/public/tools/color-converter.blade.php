<div>

      <form wire:submit.prevent="onColorConverter">

        <div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
                                        
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
        </div>
    
        <div class="row">
            <div class="col-12 col-md-6">

                <div class="form-group mb-3">
                    <label class="form-label">{{ __('Enter your Color') }}</label>
                    <div class="input-group input-group-flat">
                        <input type="text" id="input" class="form-control" wire:model.defer="color" required>
                        <span class="input-group-text">
                            <div id="paste" class="cursor-pointer" title="{{ __('Paste') }}" data-bs-original-title="{{ __('Paste') }}" data-bs-toggle="tooltip" wire:ignore>
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><rect x="9" y="3" width="6" height="4" rx="2" /></svg>
                            </div>
                        </span>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-auto">
                        <a class="btn">{{ __('RGB') }}</a>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ ($data) ? $data['rgb'] : '' }}" readonly />
                            <a onclick="copyToClipboard(this)" class="btn btn-icon cursor-pointer" title="{{ __('Copy') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Copy') }}">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"></path> <rect x="8" y="8" width="12" height="12" rx="2"></rect> <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"></path> </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-auto">
                        <a class="btn">{{ __('HEX') }}</a>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ ($data) ? $data['hex'] : '' }}" readonly />
                            <a onclick="copyToClipboard(this)" class="btn btn-icon cursor-pointer" title="{{ __('Copy') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Copy') }}">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"></path> <rect x="8" y="8" width="12" height="12" rx="2"></rect> <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"></path> </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-auto">
                        <a class="btn">{{ __('HSL') }}</a>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ ($data) ? $data['hsl'] : '' }}" readonly />
                            <a onclick="copyToClipboard(this)" class="btn btn-icon cursor-pointer" title="{{ __('Copy') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Copy') }}">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"></path> <rect x="8" y="8" width="12" height="12" rx="2"></rect> <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"></path> </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-auto">
                        <a class="btn">{{ __('HSV') }}</a>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ ($data) ? $data['hsv'] : '' }}" readonly />
                            <a onclick="copyToClipboard(this)" class="btn btn-icon cursor-pointer" title="{{ __('Copy') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Copy') }}">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"></path> <rect x="8" y="8" width="12" height="12" rx="2"></rect> <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"></path> </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-auto">
                        <a class="btn">{{ __('CMYK') }}</a>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ ($data) ? $data['cmyk'] : '' }}" readonly />
                            <a onclick="copyToClipboard(this)" class="btn btn-icon cursor-pointer" title="{{ __('Copy') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Copy') }}">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"></path> <rect x="8" y="8" width="12" height="12" rx="2"></rect> <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"></path> </svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-12 col-md-6">
                <div class="border" style="height: 325px; background-color: {{ ($data) ? $data['rgb'] : '' }};"></div>
            </div>
        </div>

        @if ($generalSettings->captcha_status && ($generalSettings->captcha_for_registered || !auth()->check()))
          <x-public.recaptcha />
        @endif
        
        <div class="form-group text-center">
            <button class="btn btn-info w-md-100 mb-1 mb-md-0" wire:loading.attr="disabled">
              <span>
                <div wire:loading.inline wire:target="onColorConverter">
                  <x-loading />
                </div>
                <span>{{ __('Convert') }}</span>
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
          function copyToClipboard(element) {
              var text = element.parentElement.querySelector('input');
              text.select();
              document.execCommand("copy");
          }
      </script>

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
                    el.classList.remove('text-danger');
                    tooltip = new bootstrap.Tooltip(el);
                  }

                  function setClearIcon() {
                    el.innerHTML = clearIcon;
                    tooltip.dispose();
                    el.setAttribute('title', '{{ __('Clear') }}');
                    el.classList.add('text-danger');
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
                      @this.set('color', ''); // Update Livewire state
                      setPasteIcon();
                    } else {
                      // Paste action
                      navigator.clipboard.readText().then(function(clipText) {
                        @this.set('color', clipText);
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
