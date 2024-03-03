<div>

      <form wire:submit.prevent="onMonthCalculator">

        <div>
          <!-- Session Status -->
          <x-auth-session-status class="mb-4" :status="session('status')" />
                                      
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />
        </div>

          <div class="form-group mb-3">
            <div class="row g-2">
              <div class="col-12 col-md-6">
                <label class="form-label">{{ __('Enter your Start Date') }}</label>
                <div class="input-icon">
                  <input class="form-control" id="datepicker-startDate" placeholder="{{ __('Select a date') }}" wire:model.defer="startDate" autocomplete="off" required>
                  <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><rect x="4" y="5" width="16" height="16" rx="2"></rect><line x1="16" y1="3" x2="16" y2="7"></line><line x1="8" y1="3" x2="8" y2="7"></line><line x1="4" y1="11" x2="20" y2="11"></line><line x1="11" y1="15" x2="12" y2="15"></line><line x1="12" y1="15" x2="12" y2="18"></line></svg>
                  </span>
                </div>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">{{ __('Enter your End Date') }}</label>
                <div class="input-icon">
                  <input class="form-control" id="datepicker-endDate" placeholder="{{ __('Select a date') }}" wire:model.defer="endDate" autocomplete="off" required>
                  <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><rect x="4" y="5" width="16" height="16" rx="2"></rect><line x1="16" y1="3" x2="16" y2="7"></line><line x1="8" y1="3" x2="8" y2="7"></line><line x1="4" y1="11" x2="20" y2="11"></line><line x1="11" y1="15" x2="12" y2="15"></line><line x1="12" y1="15" x2="12" y2="18"></line></svg>
                  </span>
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
                <div wire:loading.inline wire:target="onMonthCalculator">
                  <x-loading />
                </div>
                <span>{{ __('Calculate') }}</span>
              </span>
            </button>
        </div>

        @if ( !empty($data) )
              <div class="table-responsive mt-3" v-if="this.data">
                <table class="table table-bordered table-hover">
                  <tbody>
                    <tr>
                      <td class="fw-bold bg-light">{{ __('The difference in months is:') }}</td>
                      <td class="w-50">{{ $data['months'] }} {{ __('month(s)') }} {{ $data['days'] }} {{ __('day(s)') }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
        @endif
      </form>

    <script src="{{ asset('assets/js/litepicker.js') }}" defer></script>
    <link type="text/css" href="{{ asset('assets/css/vendors.'.localization()->getCurrentLocaleDirection().'.min.css') }}" rel="stylesheet">

    <script>
        document.addEventListener('livewire:load', function () {

            //startDatePicker
            const startDatePicker = new Litepicker({ 
                element: document.getElementById('datepicker-startDate'),
                format: 'YYYY-MM-DD',
                buttonText: {
                  previousMonth: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="15 6 9 12 15 18" /></svg>`,
                  nextMonth: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 6 15 12 9 18" /></svg>`,
                }
            });

            startDatePicker.on("selected", function () {
                @this.set('startDate', jQuery('#datepicker-startDate').val());
            });

            //endDatePicker
            const endDatePicker = new Litepicker({ 
                element: document.getElementById('datepicker-endDate'),
                format: 'YYYY-MM-DD',
                buttonText: {
                  previousMonth: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="15 6 9 12 15 18" /></svg>`,
                  nextMonth: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 6 15 12 9 18" /></svg>`,
                }
            });

            endDatePicker.on("selected", function () {
                @this.set('endDate', jQuery('#datepicker-endDate').val());
            });
        });
    </script>
</div>