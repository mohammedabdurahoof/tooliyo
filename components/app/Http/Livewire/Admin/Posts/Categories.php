<?php

namespace App\Http\Livewire\Admin\Posts;

use Livewire\Component;
use Auth;
use DateTime;
use App\Models\Admin\PostCategory;
use App\Models\Admin\Page;
use GrahamCampbell\Security\Facades\Security;
use Artesaos\SEOTools\Facades\SEOMeta;

class Categories extends Component
{

    public $title;
    public $category_name;
    public $description;
    public $align = 'start';
    public $background = 'bg-white';
    public $categories = [];
    public $cateID;

    protected $listeners = ['onUpdateCategory'];

    public function mount()
    {
        $this->categories = PostCategory::get()->toArray();
    }

    public function render()
    {
        //Meta
        $title = __('Tool Categories') . ' ' . env('APP_SEPARATOR') . ' ' . env('APP_NAME');
        SEOMeta::setTitle($title);

        return view('livewire.admin.posts.categories')->layout('layouts.admin', [
            'breadcrumbs' => [
                ['title' => __( 'Admin' ), 'url' => route('admin.dashboard.index')],
                ['title' => __( 'Categories' ), 'url' => route('admin.posts.categories.index')]
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
        $this->reset(['category_name']);
    }

    /**
     * -------------------------------------------------------------------------------
     *  addNewCategory
     * -------------------------------------------------------------------------------
    **/
    public function addNewCategory()
    {
        try {

            if ( $this->cateID != null ) {

                $cate                = PostCategory::findOrFail($this->cateID);
                $cate->category_name = Security::clean( strip_tags($this->category_name) );
                $cate->updated_at    = new DateTime();
                $cate->save();

                $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => __('Data updated successfully!')]);

            } else{

                $cate                = new PostCategory;
                $cate->category_name = Security::clean( strip_tags($this->category_name) );
                $cate->created_at    = new DateTime();
                $cate->save();
                $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => __('Data created successfully!')]);
            }

            $this->mount();
            $this->render();
            $this->resetInputFields();
        
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => __($e->getMessage()) ]);
        }
    }

    /**
     * -------------------------------------------------------------------------------
     *  editCategory
     * -------------------------------------------------------------------------------
    **/
    public function editCategory($id)
    {
        try {

            $this->cateID      = $id;
            $cate              = PostCategory::findOrFail($id);
            $this->category_name       = Security::clean( strip_tags($cate->category_name) );

        } catch (\Exception $e) {
           $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => __($e->getMessage()) ]);
        }

    }

    /**
     * -------------------------------------------------------------------------------
     *  removeCategory
     * -------------------------------------------------------------------------------
    **/
    public function removeCategory($id)
    {

        try {
            $cate = PostCategory::findOrFail($id);

            $cate->delete($id);
            return redirect()->route('admin.posts.categories.index');

        } catch (\Exception $e) {
           $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => __($e->getMessage()) ]);
        }

    }

    /**
     * -------------------------------------------------------------------------------
     *  parseJsonArray
     * -------------------------------------------------------------------------------
    **/
    public function parseJsonArray($jsonArray, $parentID = 0) {

      $return = array();

      foreach ($jsonArray as $subArray) {

        $returnSubSubArray = array();

        $return[] = array('id' => $subArray['id']);

        $return = array_merge($return, $returnSubSubArray);
      }
      return $return;
    }

    /**
     * -------------------------------------------------------------------------------
     *  onUpdateCategory
     * -------------------------------------------------------------------------------
    **/
    public function onUpdateCategory($data)
    {

        try {

            $data = $this->parseJsonArray($data);

            $i = 0;

            foreach ($data as $row) {

                $i++;
                $cate             = PostCategory::findOrFail($row['id']);
                $cate->sort       = $i;
                $cate->updated_at = new DateTime();
                $cate->save();
            }
            
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => __('Data updated successfully!')]);
            $this->mount();

        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => __($e->getMessage()) ]);
        }
        
    }

}
