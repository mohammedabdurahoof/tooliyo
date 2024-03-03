<?php

namespace App\Http\Livewire\Public\Tools;

use Livewire\Component;
use App\Models\Admin\History;
use App\Classes\TdeeCalculatorClass;
use DateTime;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use App\Rules\VerifyRecaptcha;
use App\Models\Admin\General;

class TdeeCalculator extends Component
{
    public $age            = 25;
    public $gender         = 'male';
    public $unit           = 'metric';
    public $height_metric  = 175;
    public $weight_metric  = 70;
    public $height_us_feet = 5;
    public $height_us_inch = 10;
    public $weight_us      = 160;
    public $level          = 1.465;
    public $data = [];
    public $recaptcha; 
    public $generalSettings;

    public function mount()
    {
        $this->generalSettings = General::first();
    }

    public function render()
    {
        return view('livewire.public.tools.tdee-calculator');
    }

    /**
     * -------------------------------------------------------------------------------
     *  onTdeeCalculator
     * -------------------------------------------------------------------------------
    **/
    public function onTdeeCalculator(){

        $validationRules = [
            'age'            => 'required|numeric|min:0',
            'gender'         => 'required|in:male,female',
            'unit'           => 'required|in:us,metric',
            'height_metric'  => 'required_if:unit,metric|nullable|numeric|min:0',
            'weight_metric'  => 'required_if:unit,metric|nullable|numeric|min:0',
            'height_us_feet' => 'required_if:unit,us|nullable|numeric|min:0',
            'height_us_inch' => 'required_if:unit,us|nullable|numeric|min:0',
            'weight_us'      => 'required_if:unit,us|nullable|numeric|min:0',
            'level'          => 'required|numeric|min:0'
        ];

        if ( $this->generalSettings->captcha_status && ($this->generalSettings->captcha_for_registered || !auth()->check()) ) {
            $validationRules['recaptcha'] = ['required', new VerifyRecaptcha];
        }

        $this->validate($validationRules);

        $this->data = null;

        try {

            $output = new TdeeCalculatorClass();

            $this->data = $output->get_data($this->age, $this->gender, $this->unit, $this->height_metric, $this->weight_metric, $this->height_us_feet, $this->height_us_inch, $this->weight_us, $this->level);
          
            $this->dispatchBrowserEvent('resetReCaptcha');

        } catch (\Exception $e) {

            $this->addError('error', __($e->getMessage()));
        }

        //Save History
        if ( !empty($this->data) ) {

            $history             = new History;
            $history->tool_name  = 'TDEE Calculator';
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

    /**
     * -------------------------------------------------------------------------------
     *  onSample
     * -------------------------------------------------------------------------------
    **/
    public function onSample()
    {
        $this->age            = 25;
        $this->gender         = 'male';
        $this->unit           = 'metric';
        $this->height_metric  = 175;
        $this->weight_metric  = 70;
        $this->height_us_feet = 5;
        $this->height_us_inch = 10;
        $this->weight_us      = 160;
        $this->level          = 1.465;
    }

    /**
     * -------------------------------------------------------------------------------
     *  onReset
     * -------------------------------------------------------------------------------
    **/
    public function onReset()
    {
        $this->age            = null;
        $this->gender         = 'male';
        $this->unit           = 'metric';
        $this->height_metric  = null;
        $this->weight_metric  = null;
        $this->height_us_feet = null;
        $this->height_us_inch = null;
        $this->weight_us      = null;
        $this->level          = null;
        $this->data = [];
    }
}
