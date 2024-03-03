<div>

      <form wire:submit.prevent="onHoursCalculator">

        <div>
          <!-- Session Status -->
          <x-auth-session-status class="mb-4" :status="session('status')" />
                                      
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />
        </div>

          <div class="form-group mb-3">
            <div class="row g-2">
              <div class="col-12 col-md-6">
                <label class="form-label">{{ __('Enter your Start Time') }}</label>
                <input type="time" wire:model.defer="startTime" class="form-control" aria-label="{{ __('Start Time') }}" required>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">{{ __('Enter your End Time') }}</label>
                <input type="time" wire:model.defer="endTime" class="form-control" aria-label="{{ __('End Time') }}" required>
              </div>
            </div>
          </div>

        @if ($generalSettings->captcha_status && ($generalSettings->captcha_for_registered || !auth()->check()))
          <x-public.recaptcha />
        @endif
        
        <div class="form-group text-center">
            <button class="btn btn-info w-md-100" wire:loading.attr="disabled">
              <span>
                <div wire:loading.inline wire:target="onHoursCalculator">
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
                      <td class="fw-bold bg-light">{{ __('The difference in hours is:') }}</td>
                      <td class="w-50">{{ $data['hours'] }} {{ __('hour(s)') }} {{ $data['minutes'] }} {{ __('minute(s)') }}</td>
                    </tr>

                    <tr>
                      <td class="fw-bold bg-light">{{ __('The difference in minutes is:') }}</td>
                      <td class="w-50">{{ $data['total_minutes'] }} {{ __('minute(s)') }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
        @endif
      </form>
</div>