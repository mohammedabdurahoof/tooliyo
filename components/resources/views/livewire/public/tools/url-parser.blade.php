<div>
      <form wire:submit.prevent="onUrlParser">

        <div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
                                        
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
        </div>

            <div class="form-group mb-3">
                <label class="form-label">{{ __('Enter a website URL') }}</label>
                <div class="input-group input-group-flat">
                    <input type="text" id="input" class="form-control" wire:model.defer="link" required />
                    <span class="input-group-text">
                        <div id="paste" class="cursor-pointer" title="{{ __('Paste') }}" data-bs-original-title="{{ __('Paste') }}" data-bs-toggle="tooltip" wire:ignore>
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><rect x="9" y="3" width="6" height="4" rx="2" /></svg>
                        </div>
                    </span>
                </div>
            </div>

            @if ($generalSettings->captcha_status && ($generalSettings->captcha_for_registered || !auth()->check()))
              <x-public.recaptcha />
            @endif

            <div class="form-group text-center">
                <button class="btn btn-info w-md-100 mb-1 mb-md-0" wire:loading.attr="disabled">
                  <span>
                    <div wire:loading.inline wire:target="onUrlParser">
                      <x-loading />
                    </div>
                    <span>{{ __('Start') }}</span>
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

      @if ( !empty($data) )
            <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped table-hover">
                        <tbody>
                            @if ($data['scheme'])
                                <tr>
                                    <td class="fw-bold">{{ __('Scheme') }}</td>
                                    <td>{{ $data['scheme'] }}</td>
                                </tr>
                            @endif

                            @if ($data['protocol'])
                                <tr>
                                    <td class="fw-bold">{{ __('Protocol') }}</td>
                                    <td>{{ $data['protocol'] }}</td>
                                </tr>
                            @endif

                            @if ($data['authority'])
                                <tr>
                                    <td class="fw-bold">{{ __('Authority') }}</td>
                                    <td>{{ $data['authority'] }}</td>
                                </tr>
                            @endif

                            @if ($data['host'])
                                <tr>
                                    <td class="fw-bold">{{ __('Host') }}</td>
                                    <td>{{ $data['host'] }}</td>
                                </tr>
                            @endif

                            @if ($data['hostname'])
                                <tr>
                                    <td class="fw-bold">{{ __('Hostname') }}</td>
                                    <td>{{ $data['hostname'] }}</td>
                                </tr>
                            @endif

                            @if ($data['subdomain'])
                                <tr>
                                    <td class="fw-bold">{{ __('Subdomain') }}</td>
                                    <td>{{ $data['subdomain'] }}</td>
                                </tr>
                            @endif

                            @if ($data['domain'])
                                <tr>
                                    <td class="fw-bold">{{ __('Domain') }}</td>
                                    <td>{{ $data['domain'] }}</td>
                                </tr>
                            @endif

                            @if ($data['tld'])
                                <tr>
                                    <td class="fw-bold">{{ __('TLD') }}</td>
                                    <td>{{ $data['tld'] }}</td>
                                </tr>
                            @endif

                            @if ($data['resource'])
                                <tr>
                                    <td class="fw-bold">{{ __('Resource') }}</td>
                                    <td>{{ $data['resource'] }}</td>
                                </tr>
                            @endif

                            @if ($data['directory'])
                                <tr>
                                    <td class="fw-bold">{{ __('Directory') }}</td>
                                    <td>{{ $data['directory'] }}</td>
                                </tr>
                            @endif

                            @if ($data['path'])
                                <tr>
                                    <td class="fw-bold">{{ __('Path') }}</td>
                                    <td>{{ $data['path'] }}</td>
                                </tr>
                            @endif

                            @if ($data['file_name'])
                                <tr>
                                    <td class="fw-bold">{{ __('File name') }}</td>
                                    <td>{{ $data['file_name'] }}</td>
                                </tr>
                            @endif

                            @if ($data['file_suffix'])
                                <tr>
                                    <td class="fw-bold">{{ __('File suffix') }}</td>
                                    <td>{{ $data['file_suffix'] }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
            </div>
      @endif

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
                      @this.set('link', ''); // Update Livewire state
                      setPasteIcon();
                    } else {
                      // Paste action
                      navigator.clipboard.readText().then(function(clipText) {
                        @this.set('link', clipText);
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