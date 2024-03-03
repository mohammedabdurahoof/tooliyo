<div>
      <form wire:submit.prevent="onVttToSrt">

        <div class="image-container mb-3">

            <div>
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                                            
                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" />
            </div>
            
            <div class="image-wrapper {{ ($convertType == 'remoteURL') ? 'show-remote-box' : '' }}">

                <div class="local-image-box dropzone d-flex flex-column p-3">
                    <div class="d-flex mt-auto mx-auto w-75">
                      <div class="row w-100">
                          <div class="col">
                            <div class="form-group">
                              <input type="file" class="form-control" wire:model.defer="local_file" accept=".vtt" {{ $convertType == 'localFile' ? 'required' : '' }}>
                            </div>
                          </div>
                          <p class="mt-3 text-center">{{ __('Maximum upload file size') }}: {{ \App\Models\Admin\General::first()->file_size }} {{ __(' MB') }}</p>
                      </div>
                    </div>

                    <div class="d-flex mt-auto flex-end">
                        <small class="ms-auto badge bg-cyan-lt cursor-pointer" wire:click="onConvertType('remoteURL')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5" /><path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5" /></svg>
                            {{ __('Use Remote URL') }}
                        </small>
                    </div>
                </div>

                <div class="remote-box d-flex flex-column">
                      <div class="d-flex mt-auto mx-auto w-75">
                        <div class="row w-100">
                            <div class="col">
                                <div class="input-group input-group-flat">
                                    <input type="text" id="remote_url" class="form-control" wire:model.defer="remote_url" placeholder="https://..." {{ $convertType == 'remoteURL' ? 'required' : '' }}>
                                    <span class="input-group-text">
                                        <div id="paste" class="cursor-pointer" title="{{ __('Paste') }}" data-bs-original-title="{{ __('Paste') }}" data-bs-toggle="tooltip" wire:ignore>
                                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><rect x="9" y="3" width="6" height="4" rx="2" /></svg>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                      </div>

                      <div class="d-flex mt-auto flex-end">
                          <small class="ms-auto badge bg-cyan-lt cursor-pointer" wire:click="onConvertType('localFile')">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="3" y1="19" x2="21" y2="19" /><rect x="5" y="6" width="14" height="10" rx="1" /></svg>
                              {{ __('Upload from device') }}
                          </small>
                      </div>
					  </div>

            </div>
        </div>

            @if ($generalSettings->captcha_status && ($generalSettings->captcha_for_registered || !auth()->check()))
              <x-public.recaptcha />
            @endif

          <div class="form-group text-center">
              <button class="btn btn-info w-md-100" wire:loading.attr="disabled">
                <span>
                  <div wire:loading.inline wire:target="onVttToSrt">
                    <x-loading />
                  </div>
                  <span>{{ __('Convert') }}</span>
                </span>
              </button>
          </div>
      </form>

        <div class="modal fade" id="modalPreviewDownloadFile" tabindex="-1" role="dialog" aria-labelledby="modalPreviewDownloadFile" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 36 36" class="icon me-1 my-auto"><path fill="#A4D06D" d="M16.688 25.728l-6.61-6.61 2.152-2.152 3.902 3.902 6.08-9.758 2.583 1.61"></path><path fill="#A4D06D" d="M18 35.875C8.144 35.875.125 27.855.125 18S8.145.125 18 .125 35.875 8.145 35.875 18 27.855 35.875 18 35.875zm0-33.468C9.402 2.407 2.407 9.402 2.407 18c0 8.598 6.995 15.593 15.593 15.593 8.598 0 15.593-6.995 15.593-15.593 0-8.598-6.995-15.593-15.593-15.593z"></path></svg>
                    <span>{{ __('Save your subtitle') }}</span>
                  </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>

                <div class="modal-body">
                    <div class="form-group text-center mx-auto">
                        <a class="btn btn-success download-subtitle">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><polyline points="7 11 12 16 17 11" /><line x1="12" y1="4" x2="12" y2="16" /></svg>
                            {{ __('Download Subtitle') }}
                        </a>
                    </div>

                    <p>
                        <pre class="preview-subtitle"></pre>
                    </p>
                    <p>{{ __('Note: This is a preview only. Click the "Download Subtitle" button for the final subtitle.') }}</p>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>

              </div>
            </div>
        </div>
		
		<script>
		(function( $ ) {
		  "use strict";

			document.addEventListener('livewire:load', function () {

        var el      = document.getElementById('paste');
        var input   = document.getElementById('remote_url');
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
            @this.set('remote_url', ''); // Update Livewire state
            setPasteIcon();
          } else {
            // Paste action
            navigator.clipboard.readText().then(function(clipText) {
              @this.set('remote_url', clipText);
              setClearIcon();
            }).catch(function() {
              // Handle error if needed
            });
          }
        });

        // Handle changes to the input field
        input.addEventListener('input', checkInputValue);
				
				jQuery('input#remote_url').change(function() { 
				  window.livewire.emit('onSetRemoteURL', this.value)
				});

				/**
				 * -------------------------------------------------------------------------------
				 *  Download subtitle
				 * -------------------------------------------------------------------------------
				**/
				jQuery('a.download-subtitle').click(function(e) { 
					e.preventDefault();
					const link   = document.createElement('a');
					var filename =  jQuery(this).attr('data-filename');
					link.href    = jQuery(this).attr('href');
					link.setAttribute('download', filename);
					document.body.appendChild(link);
					link.click();
					link.parentNode.removeChild(link);
				});

				/**
				 * -------------------------------------------------------------------------------
				 *  showModal
				 * -------------------------------------------------------------------------------
				**/
				window.addEventListener('showModal', event => {
					jQuery('.download-subtitle').attr( 'href', event.detail.url );
					jQuery('.download-subtitle').attr('data-filename', event.detail.fileName);
					jQuery.get(event.detail.url, function(data) {
					   jQuery('.preview-subtitle').html(data);
					});
					jQuery('#' + event.detail.id).modal('show');
				});

			});

		})( jQuery );
		</script>
</div>