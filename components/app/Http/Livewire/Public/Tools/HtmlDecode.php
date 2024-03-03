<?php

namespace App\Http\Livewire\Public\Tools;

use Livewire\Component;
use App\Models\Admin\History;
use Illuminate\Support\Facades\Http;
use App\Classes\HtmlDecodeClass;
use DateTime, File;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use App\Rules\VerifyRecaptcha;
use App\Models\Admin\General;

class HtmlDecode extends Component
{
    public $code;
    public $data = [];
    public $recaptcha; 
    public $generalSettings;

    public function mount()
    {
        $this->generalSettings = General::first();
    }
    
    public function render()
    {
        return view('livewire.public.tools.html-decode');
    }

    /**
     * -------------------------------------------------------------------------------
     *  onHtmlDecode
     * -------------------------------------------------------------------------------
    **/
    public function onHtmlDecode(){

        $validationRules = [
            'code' => 'required'
        ];

        if ( $this->generalSettings->captcha_status && ($this->generalSettings->captcha_for_registered || !auth()->check()) ) {
            $validationRules['recaptcha'] = ['required', new VerifyRecaptcha];
        }

        $this->validate($validationRules);

        $this->data = null;

        try {

            $output = new HtmlDecodeClass();

            $this->data = $output->get_data( $this->code );

            $this->dispatchBrowserEvent('resetReCaptcha');

        } catch (\Exception $e) {

            $this->addError('error', __($e->getMessage()));
        }

        //Save History
        if ( !empty($this->data) ) {

            $history             = new History;
            $history->tool_name  = 'HTML Decode';
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
        $this->code = <<<EOT
&lt;!DOCTYPE html&gt;
&lt;html lang=&quot;en&quot;&gt;
    &lt;head&gt;
        &lt;meta charset=&quot;UTF-8&quot; /&gt;
        &lt;meta name=&quot;viewport&quot; content=&quot;width=device-width, initial-scale=1.0&quot; /&gt;
        &lt;title&gt;Personal Profile&lt;/title&gt;
    &lt;/head&gt;
    &lt;body&gt;
        &lt;header&gt;
            &lt;h1&gt;Jane Doe&lt;/h1&gt;
            &lt;p&gt;Web Developer&lt;/p&gt;
        &lt;/header&gt;

        &lt;main&gt;
            &lt;section&gt;
                &lt;h2&gt;About Me&lt;/h2&gt;
                &lt;p&gt;Hello! I am Jane, a passionate web developer with over 5 years of experience. I love creating responsive and user-friendly websites.&lt;/p&gt;
            &lt;/section&gt;
        &lt;/main&gt;

        &lt;footer&gt;
            &lt;p&gt;Thank you for visiting my profile!&lt;/p&gt;
        &lt;/footer&gt;
    &lt;/body&gt;
&lt;/html&gt;
EOT;
    }

    /**
     * -------------------------------------------------------------------------------
     *  onReset
     * -------------------------------------------------------------------------------
    **/
    public function onReset()
    {
        $this->code  = null;
        $this->data  = [];
    }
}
