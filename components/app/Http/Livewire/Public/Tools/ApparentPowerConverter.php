<?php

namespace App\Http\Livewire\Public\Tools;

use Livewire\Component;
use App\Models\Admin\History;
use App\Classes\ApparentPowerConverterClass;
use DateTime, File;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use App\Rules\VerifyRecaptcha;
use App\Models\Admin\General;

class ApparentPowerConverter extends Component
{

    public $from_value;
    public $convert_from = 'volt';
    public $from_name;
    public $recaptcha; 
    public $generalSettings;
    public $data = [];

    public function mount()
    {
        $this->generalSettings = General::first();
    }
    
    public function render()
    {
        return view('livewire.public.tools.apparent-power-converter');
    }

    /**
     * -------------------------------------------------------------------------------
     *  onApparentPowerConverter
     * -------------------------------------------------------------------------------
    **/
    public function onApparentPowerConverter(){

        $validationRules = [
            'from_value'   => 'required|numeric|gt:0',
            'convert_from' => 'required'
        ];

        if ( $this->generalSettings->captcha_status && ($this->generalSettings->captcha_for_registered || !auth()->check()) ) {
            $validationRules['recaptcha'] = ['required', new VerifyRecaptcha];
        }

        $this->validate($validationRules);

        $this->data = null;

        try {

            $output = new ApparentPowerConverterClass();

            $this->data = $output->get_data( $this->from_value, $this->convert_from );
            
            $this->from_name = ucfirst($this->convert_from);

            $this->dispatchBrowserEvent('resetReCaptcha');

        } catch (\Exception $e) {

            $this->addError('error', __($e->getMessage()));
        }

        //Save History
        if ( !empty($this->data) ) {

            $history             = new History;
            $history->tool_name  = 'Apparent Power Converter';
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
        $this->from_value = 12;
    }

    /**
     * -------------------------------------------------------------------------------
     *  onReset
     * -------------------------------------------------------------------------------
    **/
    public function onReset()
    {
        $this->from_value = null;
        $this->data       = null;
    }
}
