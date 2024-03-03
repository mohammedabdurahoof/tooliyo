<div>

      <form wire:submit.prevent="onIpAddressLookup">

        <div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
                                        
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
        </div>

            <div class="form-group mb-3">
                <label class="form-label">{{ __('IP Address') }}</label>
                <div class="input-group input-group-flat">
                    <input type="text" id="input" class="form-control" wire:model.defer="ip" placeholder="{{ __('Enter IP Address here...') }}" required />
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
                <button class="btn btn-info w-md-100" wire:loading.attr="disabled">
                    <span>
                        <div wire:loading.inline wire:target="onIpAddressLookup">
                            <x-loading />
                        </div>
                        <span>{{ __('Lookup') }}</span>
                    </span>
                </button>
            </div>

        @if ( !empty($data) )
			<div class="mt-3">
				<iframe id="gmap" src="https://maps.google.com/maps?ll={{ $data['lat'] }},{{ $data['lon'] }}&amp;z=13&amp;output=embed"></iframe>
				<div class="table-responsive" wire:loading.remove wire:target="onIpAddressLookup">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<td>{{ __('IP Address') }}</td>
								<td class="text-danger fw-bold">{{ $data['query'] }}</td>
							</tr>
							<tr>
								<td>{{ __('Location') }}</td>
								<td>{{ $data['country'] . ' (' . $data['countryCode'] .') ' . ', ' . $data['city'] }}</td>
							</tr>

							<tr>
								<td>{{ __('Region') }}</td>
								<td>{{ $data['regionName'] }}</td>
							</tr>

							<tr>
								<td>{{ __('City') }}</td>
								<td>{{ $data['city'] }}</td>
							</tr>

							<tr>
								<td>{{ __('Latitude') }}</td>
								<td>{{ $data['lat'] }}</td>
							</tr>

							<tr>
								<td>{{ __('Longitude') }}</td>
								<td>{{ $data['lon'] }}</td>
							</tr>

							<tr>
								<td>{{ __('Time zone') }}</td>
								<td>{{ $data['timezone'] }}</td>
							</tr>

							<tr>
								<td>{{ __('Currency code') }}</td>
								<td>{{ $data['currency'] }}</td>
							</tr>

							<tr>
								<td>{{ __('Zip') }}</td>
								<td>{{ ($data['zip']) ? $data['zip'] : __('N/a') }}</td>
							</tr>

							<tr>
								<td>{{ __('ISP') }}</td>
								<td>{{ $data['isp'] . ' (' . $data['as'] . ')' }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
        @endif

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
	              @this.set('ip', ''); // Update Livewire state
	              setPasteIcon();
	            } else {
	              // Paste action
	              navigator.clipboard.readText().then(function(clipText) {
	                @this.set('ip', clipText);
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