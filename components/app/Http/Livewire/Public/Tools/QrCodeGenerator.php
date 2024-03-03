<?php

namespace App\Http\Livewire\Public\Tools;

use Livewire\Component;
use App\Models\Admin\History;
use Illuminate\Support\Facades\Http;
use App\Classes\QrCodeGeneratorClass;
use Livewire\WithFileUploads;
use DateTime, File;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use App\Models\Admin\General;
use App\Rules\VerifyRecaptcha;
use Illuminate\Support\Facades\Storage;

class QrCodeGenerator extends Component
{
    use WithFileUploads;

    protected $listeners = ['onSetRemoteURL'];
    public $convertType = 'localImage';
    public $text;
    public $image_size = 300;
    public $custom_logo = false;
    public $remote_url;
    public $local_image;
    public $logo_size = 50;
    public $data = [];
    public $recaptcha; 
    public $generalSettings;

    public function mount()
    {
        $this->generalSettings = General::first();
    }
    
    public function render()
    {
        return view('livewire.public.tools.qr-code-generator');
    }

    /**
     * -------------------------------------------------------------------------------
     *  onSetRemoteURL
     * -------------------------------------------------------------------------------
    **/
    public function onSetRemoteURL($value)
    {
      $this->remote_url = $value;
    }

    /**
     * -------------------------------------------------------------------------------
     *  onConvertType
     * -------------------------------------------------------------------------------
    **/
    public function onConvertType( $type ){
        $this->convertType = $type;
    }

    /**
     * -------------------------------------------------------------------------------
     *  getValidationRulesByConvertType
     * -------------------------------------------------------------------------------
    **/
    public function getValidationRulesByConvertType()
    {
        $baseRules = [
            'text'       => 'required',
            'logo_size'  => 'required|numeric|min:10|max:1000',
            'image_size' => 'required|numeric|min:50|max:6000',
        ];

        $convertTypeRules = [
            'remoteURL' => [
                'remote_url' => 'required|url',
            ],
            'localImage' => [
                'local_image' => 'required|image|file|max:' . (1024 * General::first()->file_size),
            ],
        ];

        return array_merge($baseRules, $convertTypeRules[$this->convertType] ?? []);
    }

    /**
     * -------------------------------------------------------------------------------
     *  qrCodeWithLogo
     * -------------------------------------------------------------------------------
    **/
    private function qrCodeWithLogo(){

        $validationRules = $this->getValidationRulesByConvertType();

        if ( $this->generalSettings->captcha_status && ($this->generalSettings->captcha_for_registered || !auth()->check()) ) {
            $validationRules['recaptcha'] = ['required', new VerifyRecaptcha];
        }
        
        $this->validate($validationRules);

        $this->data = null;

        try {

            $output = new QrCodeGeneratorClass();

            if ( $this->convertType == 'remoteURL') {
			
				$fileName = pathinfo($this->remote_url, PATHINFO_BASENAME);            

				$fileNameTemp = time() . '.' . pathinfo( $this->remote_url, PATHINFO_EXTENSION);

				Storage::disk('local')->put('livewire-tmp/' . $fileNameTemp, Http::get($this->remote_url) );
					
                $this->logo_url = asset('components/storage/app/livewire-tmp/' . $fileNameTemp);
            }
            else {
			
                $temp_path = $this->local_image->store('livewire-tmp');
				
                $this->logo_url = asset('components/storage/app/' . $temp_path);
            }

            if ( pathinfo( $this->logo_url, PATHINFO_EXTENSION) == 'jpg' || pathinfo( $this->logo_url, PATHINFO_EXTENSION) == 'jpeg' || pathinfo( $this->logo_url, PATHINFO_EXTENSION) == 'png' || pathinfo( $this->logo_url, PATHINFO_EXTENSION) == 'bmp' || pathinfo( $this->logo_url, PATHINFO_EXTENSION) == 'webp' ) {

                $this->data = $output->get_data( $this->text, $this->image_size, $this->logo_size, $this->logo_url );

            } else $this->addError('error', __('The image must be a file of type: .jpg, .jpeg, .png, .bmp, .webp.'));            

            $this->dispatchBrowserEvent('resetReCaptcha');

        } catch (\Exception $e) {

            $this->addError('error', __($e->getMessage()));
        }

    }

    /**
     * -------------------------------------------------------------------------------
     *  qrCodeWithoutLogo
     * -------------------------------------------------------------------------------
    **/
    private function qrCodeWithoutLogo(){

        $validationRules = [
            'text'       => 'required',
            'image_size' => 'required|numeric|min:50|max:6000'
        ];

        if ( $this->generalSettings->captcha_status && ($this->generalSettings->captcha_for_registered || !auth()->check()) ) {
            $validationRules['recaptcha'] = ['required', new VerifyRecaptcha];
        }
        
        $this->validate($validationRules);

        $this->data = null;

        try {

            $output = new QrCodeGeneratorClass();

            $this->data = $output->get_data( $this->text, $this->image_size, '', '' );

            $this->dispatchBrowserEvent('resetReCaptcha');

        } catch (\Exception $e) {

            $this->addError('error', __($e->getMessage()));
        }

    }

    /**
     * -------------------------------------------------------------------------------
     *  onQrCodeGenerator
     * -------------------------------------------------------------------------------
    **/
    public function onQrCodeGenerator(){


        if ( $this->custom_logo == true ) $this->qrCodeWithLogo();

        else $this->qrCodeWithoutLogo();

        //Save History
        if ( !empty($this->data) ) {

            $history             = new History;
            $history->tool_name  = 'QR Code Generator';
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
    public function onSample() {

        $this->text = 'Hello World!';
    
    }

    /**
     * -------------------------------------------------------------------------------
     *  onReset
     * -------------------------------------------------------------------------------
    **/
    public function onReset()
    {
        $this->text = null;
        $this->data = [];
    }
}
