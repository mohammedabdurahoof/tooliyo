<div>   
    <div class="row">
        <div class="col-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">

                    <form wire:submit.prevent="addNewCategory">
					
						<div class="alert-message">
						  <!-- Session Status -->
						  <x-auth-session-status class="mb-4" :status="session('status')" />
													  
						  <!-- Validation Errors -->
						  <x-auth-validation-errors class="mb-4" :errors="$errors" />
						</div>
			
                        <div class="form-group mb-3">
                            <label class="form-label">{{ __('Category Name') }}</label>
                            <input class="form-control" type="text" wire:model.defer="category_name">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-info float-end" wire:loading.attr="disabled">
                                <span>
                                    <div wire:loading.inline wire:target="addNewCategory">
                                        <x-loading />
                                    </div>
                                    <span>{{ __('Add New Category') }}</span>
                                </span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div id="nestable" class="dd mw-100">

                        @php
                            function get_categories($categories, $class = 'dd-list') {

                                $html = '<ol class="'.$class.'">';

                                foreach($categories as $key => $value) {
                                    $html .= '<li class="dd-item" data-id="'.$value['id'].'">
                                                    <div class="float-end btn-handle">
                                                        <button class="badge bg-primary border-0" wire:click.prevent="editCategory('.$value['id'].')">'.__('Edit').'</button>
                                                        <button class="badge bg-danger border-0" wire:click.prevent="removeCategory('.$value['id'].')">'.__('Delete').'</button>
                                                    </div>

                                                    <div class="dd-handle">
                                                        <h4>'.$value['category_name'].'</h4>
                                                    </div>';

                                    $html .'</li>';
                                }

                                $html .= '</ol>';

                                return $html;
                            }

                            echo get_categories($categories);

                        @endphp
                    </div>

                    <div class="col-auto">
                        <button class="btn btn-primary float-end mt-3" id="onUpdateCategory" wire:loading.attr="disabled">
                          <span>
                            <div wire:loading wire.emit.target="onUpdateCategory">
                              <x-loading />
                            </div>
                            <span>{{ __('Save Changes') }}</span>
                          </span>
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Nestable -->
    <script src="{{ asset('assets/js/jquery.nestable.min.js') }}"></script>
    <link type="text/css" href="{{ asset('assets/css/jquery.nestable.min.css') }}" rel="stylesheet">
        
    <style>
    .dd .btn-handle {
        transform: translate(-10%, 50%);
    }

    .btn-handle button{
        cursor: pointer;
    }

    .dd .dd-handle .url {
        font-weight: 400;
        margin-left: 10px;
    }

    .dd-handle{
        background-color: #fff;
        background-clip: border-box;
        border: 0 solid rgba(0,0,0,.125);
        box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
        height: 100%;
        padding: 14px 25px;
        cursor: move;
    }

    .dd-item>button.dd-collapse:before {
        content: '';
    }
    </style>
    <script>
    (function( $ ) {
        "use strict";

        jQuery(document).ready(function(){

            jQuery('#nestable').nestable({ serialize: true, maxDepth: 1 });

            jQuery('#onUpdateCategory').click(function(e){
                e.preventDefault();
                var data = jQuery('#nestable').nestable('serialize');
                window.livewire.emit('onUpdateCategory', data)

            });

        });

    })( jQuery );
    </script>

</div>