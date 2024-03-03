<?php

namespace App\Http\Livewire\Public\Tools;

use Livewire\Component;
use App\Models\Admin\History;
use Illuminate\Support\Facades\Http;
use Livewire\WithFileUploads;
use DateTime;
use App\Classes\JpgToIcoClass;
use App\Models\Admin\General;
use Image;
use Illuminate\Support\Facades\Storage;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use App\Rules\VerifyRecaptcha;

class JpgToIco extends Component
{
    use WithFileUploads;

    protected $listeners = ['onSetRemoteURL'];
    public $convertType = 'localImage';
    public $remote_url;
    public $local_image;
    public $data = [];
    public $icon_size = 32;
    public $recaptcha; 
    public $generalSettings;

    public function mount()
    {
        $this->generalSettings = General::first();
    }
    
    public function render()
    {
        return view('livewire.public.tools.jpg-to-ico');
    }

    public function onSetRemoteURL($value)
    {
      $this->remote_url = $value;
    }

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
     *  onJpgToIco
     * -------------------------------------------------------------------------------
    **/
    public function onJpgToIco(){

        $validationRules = $this->getValidationRules();

        $this->validate($validationRules);

        $this->data = null;

        try {

                $output = new JpgToIcoClass();

                if ( $this->convertType == 'remoteURL') {

                    $fileName = pathinfo($this->remote_url, PATHINFO_BASENAME);            

                    $fileNameTemp = time() . '.' . pathinfo( $this->remote_url, PATHINFO_EXTENSION);

                    Storage::disk('local')->put('livewire-tmp/' . $fileNameTemp, Http::get($this->remote_url) );
           
                    $temp_url = asset('components/storage/app/livewire-tmp/' . $fileNameTemp);
                }
                else {
                    
                    $fileNameTemp = $this->local_image->store('livewire-tmp');

                    $temp_url = asset('components/storage/app') . '/' . $fileNameTemp;
                }
  
                if ( pathinfo($temp_url, PATHINFO_EXTENSION) == 'jpg' || pathinfo($temp_url, PATHINFO_EXTENSION) == 'jpeg' || pathinfo($temp_url, PATHINFO_EXTENSION) == 'png') {

                    $this->data = $output->get_data( $temp_url, $this->icon_size );

                } else $this->addError('error', __('The image must be a file of type: jpg, jpeg.'));

                $this->dispatchBrowserEvent('resetReCaptcha');

        } catch (\Exception $e) {

            $this->addError('error', __($e->getMessage()));
        }

        //Save History
        $history             = new History;
        $history->tool_name  = 'JPG to ICO';
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

    /**
     * -------------------------------------------------------------------------------
     *  encode
     * -------------------------------------------------------------------------------
    **/
    function encode($pData)
    {
        $encryption_key = 'themeluxurydotcom';

        $encryption_iv = '9999999999999999';

        $ciphering = "AES-256-CTR"; 
          
        $encryption = openssl_encrypt($pData, $ciphering, $encryption_key, 0, $encryption_iv);

        return $encryption;
    }

}
