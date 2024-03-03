<?php

namespace App\Http\Livewire\Admin\Pages\Translations;

use Livewire\Component;
use App\Models\Admin\Page;
use Artesaos\SEOTools\Facades\SEOMeta;
use App\Models\Admin\Languages;
use DateTime;

class Create extends Component
{
    public $page_title;
    public $title;
    public $subtitle;
    public $short_description;
    public $description;
    public $pageId;
    public $currentLocale;
    public $page_type, $slug;
    public $sitename_status = 1;
    public $robots_meta = 1;

    public function mount($page_id, $locale)
    {
        $this->pageId        = $page_id;
        $this->currentLocale = $locale;
        $this->lang_name     = Languages::where('code', $locale)->first()->name;
        $this->slug          = Page::where('id', $page_id)->first()->slug;
        $this->page_type     = Page::where('id', $page_id)->first()->type;
    }

    public function render()
    {

        //Meta
        $title = __('Create Page Translation') . ' ' . env('APP_SEPARATOR') . ' ' . env('APP_NAME');
        SEOMeta::setTitle($title);

        return view('livewire.admin.pages.translations.create')->layout('layouts.admin', [
            'breadcrumbs' => [
                ['title' => __( 'Admin' ), 'url' => route('admin.dashboard.index')],
                ['title' => __( 'Pages' ), 'url' => route('admin.pages.index')],
                ['title' => __( 'Translations' ), 'url' => route('admin.pages.translations.index', ['page_id' => $this->pageId])],
                ['title' => __( 'Create' ), 'url' => null]
            ]
        ]);
    }

    /**
     * -------------------------------------------------------------------------------
     *  resetInputFields
     * -------------------------------------------------------------------------------
    **/
    private function resetInputFields()
    {
		$this->reset(['sitename_status', 'robots_meta', 'page_title', 'title', 'subtitle', 'short_description', 'description']);
    }

    /**
     * -------------------------------------------------------------------------------
     *  onCreatePageTranslation
     * -------------------------------------------------------------------------------
    **/
    public function onCreatePageTranslation()
    {
        $this->validate([
            'title' => 'required'
        ]);

        try {

            $page = Page::findOrFail($this->pageId);

            if ( $page->hasTranslation($this->currentLocale) ) {

                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => __('This language has been translated. You can edit it on the translation page!') ]);
            }
            else {

                $page->translateOrNew($this->currentLocale)->page_title        = !empty($this->page_title) ? strip_tags($this->page_title) : '';
                $page->translateOrNew($this->currentLocale)->title             = !empty($this->title) ? strip_tags($this->title) : '';
                $page->translateOrNew($this->currentLocale)->subtitle          = !empty($this->subtitle) ? strip_tags($this->subtitle) : '';
                $page->translateOrNew($this->currentLocale)->short_description = !empty($this->short_description) ? strip_tags($this->short_description) : '';
                $page->translateOrNew($this->currentLocale)->description       = !empty($this->description) ? $this->description : '';
                $page->translateOrNew($this->currentLocale)->sitename_status   = $this->sitename_status;
                $page->translateOrNew($this->currentLocale)->robots_meta       = $this->robots_meta;
                $page->translateOrNew($this->currentLocale)->created_at        = new DateTime();
                $page->save();

                $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => __('Data created successfully!') ]);
            }

            $this->resetInputFields();
        
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => __($e->getMessage()) ]);
        }

    }

}
