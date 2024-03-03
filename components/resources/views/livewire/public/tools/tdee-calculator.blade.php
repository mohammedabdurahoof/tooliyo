<div>
    <form wire:submit.prevent="onTdeeCalculator">
        <div class="form-group mb-3">
            <div class="row">
                <label class="col-3 col-form-label">{{ __('Select Unit') }}</label>
                <div class="col">
                    <select class="form-select" wire:model="unit">
                        <option value="us">{{ __('US Imperial') }}</option>
                        <option value="metric">{{ __('Metric') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <div class="row">
                <label class="col-3 col-form-label">{{ __('Age') }}</label>
                <div class="col">
                    <div class="input-group">
                        <input type="number" class="form-control" wire:model="age" aria-label="{{ __('Age') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <div class="row">
                <label class="col-3 col-form-label pt-0">{{ __('Gender') }}</label>
                <div class="col">
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" value="male" wire:model="gender">
                        <span class="form-check-label">{{ __('Male') }}</span>
                    </label>

                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" value="female" wire:model="gender">
                        <span class="form-check-label">{{ __('Female') }}</span>
                    </label>
                </div>
            </div>
        </div>

        @if( $unit == 'metric')
            <div class="form-group mb-3">
                <div class="row">
                    <label class="col-3 col-form-label">{{ __('Height') }}</label>
                    <div class="col">
                        <div class="input-group">
                            <input type="number" class="form-control" wire:model="height_metric" aria-label="{{ __('Height Metric') }}">
                            <span class="input-group-text">{{ __('cm') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <div class="row">
                    <label class="col-3 col-form-label">{{ __('Weight') }}</label>
                    <div class="col">
                        <div class="input-group">
                            <input type="number" class="form-control" wire:model="weight_metric" aria-label="{{ __('Weight Metric') }}">
                            <span class="input-group-text">{{ __('kg') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if( $unit == 'us')
            <div class="form-group mb-3">
                <div class="row">
                    <label class="col-3 col-form-label">{{ __('Height') }}</label>
                    <div class="col">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="input-group">
                                    <input type="number" class="form-control" wire:model="height_us_feet" aria-label="{{ __('Height US Feet') }}">
                                    <span class="input-group-text">{{ __('feet') }}</span>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group">
                                    <input type="number" class="form-control" wire:model="height_us_inch" aria-label="{{ __('Height US Inch') }}">
                                    <span class="input-group-text">{{ __('inches') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <div class="row">
                    <label class="col-3 col-form-label">{{ __('Weight') }}</label>
                    <div class="col">
                        <div class="input-group">
                            <input type="number" class="form-control" wire:model="weight_us" aria-label="{{ __('Weight US') }}">
                            <span class="input-group-text">{{ __('pounds') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="form-group mb-3">
            <div class="row">
                <label class="col-3 col-form-label">{{ __('Activity Level') }}</label>
                <div class="col">
                    <select class="form-select" wire:model="level">
                        <option value="1">{{ __('Basal Metabolic Rate (BMR)') }}</option>
                        <option value="1.2">{{ __('Sedentary: little or no exercise') }}</option>
                        <option value="1.375">{{ __('Light: exercise 1-3 times/week') }}</option>
                        <option value="1.465">{{ __('Moderate: exercise 4-5 times/week') }}</option>
                        <option value="1.55">{{ __('Active: daily exercise or intense exercise 3-4 times/week') }}</option>
                        <option value="1.725">{{ __('Very Active: intense exercise 6-7 times/week') }}</option>
                        <option value="1.9">{{ __('Extra Active: very intense exercise daily, or physical job') }}</option>
                    </select>
                </div>
            </div>
        </div>

        @if ($generalSettings->captcha_status && ($generalSettings->captcha_for_registered || !auth()->check()))
          <x-public.recaptcha />
        @endif

        <div class="form-group text-center">
            <button class="btn btn-info w-md-100 mb-1 mb-md-0" wire:loading.attr="disabled">
              <span>
                <div wire:loading.inline wire:target="onTdeeCalculator">
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
    </form>
    
    @if($data)
        <div class="row mt-3">
            <div class="col-12">
                <table class="table table-bordered table-striped table-hover text-center mb-0">
                    <thead>
                      <tr>
                        <th>{{ __('Calories needed per day to maintain your current body weight') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                        <td class="fw-bold">{{ $data['tdee'] }}</td>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
