<?php

namespace App\Http\Livewire\Public\Tools;

use Livewire\Component;
use App\Models\Admin\History;
use Illuminate\Support\Facades\Http;
use App\Classes\QrCodeDecoderClass;
use Livewire\WithFileUploads;
use DateTime, File;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use App\Models\Admin\General;
use App\Rules\VerifyRecaptcha;
use Illuminate\Support\Facades\Storage;

class QrCodeDecoder extends Component
{

    use WithFileUploads;

    protected $listeners = ['onSetRemoteURL'];
    public $convertType = 'localImage';
    public $remote_url;
    public $local_image;
    public $data = [];
    public $recaptcha; 
    public $generalSettings;

    public function mount()
    {
        $this->generalSettings = General::first();
    }
    
    public function render()
    {
        return view('livewire.public.tools.qr-code-decoder');
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
     *  getValidationRules
     * -------------------------------------------------------------------------------
    **/
    public function getValidationRules()
    {
        $baseValidationRules = $this->convertType === 'remoteURL'
            ? ['remote_url' => 'required|url']
            : ['local_image' => 'required|mimes:jpg,jpeg,png|file|max:' . (1024 * General::first()->file_size)];

        if ( $this->generalSettings->captcha_status && ($this->generalSettings->captcha_for_registered || !auth()->check()) ) {
            $baseValidationRules['recaptcha'] = ['required', new VerifyRecaptcha];
        }

        return $baseValidationRules;
    }

    /**
     * -------------------------------------------------------------------------------
     *  onQrCodeDecoder
     * -------------------------------------------------------------------------------
    **/
    public function onQrCodeDecoder(){

        $validationRules = $this->getValidationRules();

        $this->validate($validationRules);

        $this->data = null;

        try {

            $output = new QrCodeDecoderClass();

            if ( $this->convertType == 'remoteURL') {

				$fileName = pathinfo($this->remote_url, PATHINFO_BASENAME);            

                $fileNameTemp = time() . '.' . pathinfo( $this->remote_url, PATHINFO_EXTENSION);

                Storage::disk('local')->put('livewire-tmp/' . $fileNameTemp, Http::get($this->remote_url) );

                $temp_url = storage_path('app/livewire-tmp/' . $fileNameTemp);
            }
            else {

                $fileNameTemp = $this->local_image->store('livewire-tmp');

                $temp_url = storage_path('app/' . $fileNameTemp);
            }

            if ( pathinfo( $temp_url, PATHINFO_EXTENSION) == 'jpg' || pathinfo( $temp_url, PATHINFO_EXTENSION) == 'jpeg' || pathinfo( $temp_url, PATHINFO_EXTENSION) == 'png' ) {

                $this->data = $output->get_data( $temp_url );

            } else $this->addError('error', __('The image must be a file of type: .jpg, .jpeg, .png.'));   

            $this->dispatchBrowserEvent('resetReCaptcha');

        } catch (\Exception $e) {

            $this->addError('error', __($e->getMessage()));
        }

        //Save History
        if ( !empty($this->data) ) {

            $history             = new History;
            $history->tool_name  = 'QR Code Decoder';
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
