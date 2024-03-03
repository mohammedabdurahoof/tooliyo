<?php

namespace App\Http\Livewire\Public\Tools;

use Livewire\Component;
use App\Models\Admin\History;
use App\Classes\MonthCalculatorClass;
use DateTime, File;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use Carbon\Carbon;
use App\Rules\VerifyRecaptcha;
use App\Models\Admin\General;

class MonthCalculator extends Component
{

    public $startDate;
    public $endDate;
    public $recaptcha; 
    public $generalSettings;
    public $data = [];

    public function mount()
    {
        $this->generalSettings = General::first();
    }
    
    public function render()
    {
        return view('livewire.public.tools.month-calculator');
    }

    /**
     * -------------------------------------------------------------------------------
     *  onMonthCalculator
     * -------------------------------------------------------------------------------
    **/
    public function onMonthCalculator(){

        $validationRules = [
            'startDate' => 'required',
            'endDate'   => 'required'
        ];

        if ( $this->generalSettings->captcha_status && ($this->generalSettings->captcha_for_registered || !auth()->check()) ) {
            $validationRules['recaptcha'] = ['required', new VerifyRecaptcha];
        }

        $this->validate($validationRules);

        $this->data = null;

        try {

            $output = new MonthCalculatorClass();

            $this->data = $output->get_data( $this->startDate, $this->endDate );

            $this->dispatchBrowserEvent('resetReCaptcha');

        } catch (\Exception $e) {

            $this->addError('error', __($e->getMessage()));
        }

        //Save History
        if ( !empty($this->data) ) {

            $history             = new History;
            $history->tool_name  = 'Month Calculator';
            $history->client_ip  = request()->ip();

            require app_path('Classes/geoip2.phar');

            $reader = new Reader( app_path('Classes/GeoLite2-City.mmdb') );

            try {

                $record           = $reader->city( request()->ip() );

                $history->flag    = strtolower( $record->country->isoCode );
                
                $history->country = strip_tags( $record->country->name );

            } catch (AddressNotFoundException $e) {

            }

            $history->created_at = new DateTime();
            $history->save();
        }

    }
}
