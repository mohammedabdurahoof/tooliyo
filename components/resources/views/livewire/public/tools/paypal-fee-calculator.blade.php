<div>

      <form wire:submit.prevent="onPaypalFeeCalculator">

        <div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
                                        
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
        </div>

        <div class="form-group mb-3 ">
            <label class="form-label">{{ __('Enter An Amount (USD)') }}</label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" step="any" wire:model.defer="amount" class="form-control" required>
          </div>
        </div>

        <div class="form-group mb-3 ">
            <label class="form-label">{{ __('Fee Rate') }}</label>
            <div>
                <select class="form-control form-select" wire:model.defer="fee">
                    <optgroup label="Domestic">
                        <option value="2.9||.30">2.9% + $.30 (via online transaction)</option>
                        <option value="2.7||.30">2.7% + $.30 (via store location)</option>
                        <option value="2.2||.30">2.2% + $.30 (Nonprofit)</option>
                        <option value="5||.05">5% + $.05 (Micropayment)</option>
                    </optgroup>
                    <optgroup label="International">
                        <option value="4.4||.30">4.4% + $.30 (via online transaction)</option>
                        <option value="4.2||.30">4.2% + $.30 (via store location)</option>
                        <option value="3.7||.30">3.7% + $.30 (Nonprofit)</option>
                        <option value="6.5||.05">6.5% + $.05 (Micropayment)</option>
                    </optgroup>
                    <optgroup label="Mobile Card Reader">
                        <option value="2.7||.00">2.7% (swiped &amp; check-in transactions)</option>
                        <option value="3.5||.15">3.5% + $.15 (keyed or scanned transactions)</option>
                        <option value="5.7||.00">4.2% (International +1.5% cross-border fee)</option>
                    </optgroup>
                    <optgroup label="Virtual Terminal">
                        <option value="3.1||.30">3.1% + $.30 (Domestic)</option>
                        <option value="4.6||.30">4.6% + $.30 (Cross-border)</option>
                    </optgroup>
                </select>
            </div>
        </div>

        @if ($generalSettings->captcha_status && ($generalSettings->captcha_for_registered || !auth()->check()))
          <x-public.recaptcha />
        @endif
        
        <div class="form-group text-center">
            <button class="btn btn-info w-md-100 mb-1 mb-md-0" wire:loading.attr="disabled">
              <span>
                <div wire:loading.inline wire:target="onPaypalFeeCalculator">
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

            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                              <tr>
                                <th>{{ __('Total fees') }}</th>
                                <th>{{ __('You will receive') }}</th>
                                <th>{{ __('You should ask for') }}</th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>${{ $data['total_fee'] }}</td>
                                    <td>${{ $data['recieve_fee'] }}</td>
                                    <td>${{ $data['ask_for'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

      </form>
</div>