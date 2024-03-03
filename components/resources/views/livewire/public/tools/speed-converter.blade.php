<div>

      <form wire:submit.prevent="onSpeedConverter">

        <div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
                                        
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
        </div>

        <div class="form-group mb-3 ">
            <label class="form-label">{{ __('Value') }}</label>
            <input type="number" wire:model.defer="from_value" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label class="form-label">{{ __('Convert From :from to Others', ['from' => ucfirst(str_replace('_', ' ', $convert_from)) ]) }}</label>
            <select wire:model.defer="convert_from" class="form-control form-select">
                <option value="meter_per_second">{{ __('Meter per second (m/s)') }}</option>
                <option value="kilometre_per_hour">{{ __('Kilometre per hour (km/h)') }}</option>
                <option value="mile_per_hour">{{ __('Mile per hour (m/h)') }}</option>
                <option value="knot">{{ __('Knot (kt, kn)') }}</option>
                <option value="foot_per_hour">{{ __('Foot per hour (ft/h)') }}</option>
                <option value="foot_per_minute">{{ __('Foot per minute (ft/min)') }}</option>
                <option value="foot_per_second">{{ __('Foot per second (ft/s)') }}</option>
            </select>
        </div>

        @if ($generalSettings->captcha_status && ($generalSettings->captcha_for_registered || !auth()->check()))
          <x-public.recaptcha />
        @endif
        
        <div class="form-group text-center">
            <button class="btn btn-info w-md-100 mb-1 mb-md-0" wire:loading.attr="disabled">
              <span>
                <div wire:loading.inline wire:target="onSpeedConverter">
                  <x-loading />
                </div>
                <span>{{ __('Calculate') }}</span>
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

        @if ( !empty($data) )
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped table-hover">
                    <tbody>
                        @foreach ($data as $key => $value)
                            <tr>
                                <td class="fw-bold">{{ ucfirst(str_replace('_', ' ', $from_name)) }} {{ __('to') }} {{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                <td>{{ $value }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

      </form>
</div>