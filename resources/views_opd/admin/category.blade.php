@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-2 barCol">
      @include('layouts.sidebarAdmin')
    </div>

    <div class="col-sm-10">
        <div class="justify-content-center">
          
                <div class="card">
                    <div class="card-header">{{ __('Category') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('add-category') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="choose_category_id" class="col-md-4 col-form-label text-md-right">{{ __('Category') }}</label>

                                        <div class="col-md-6">
                                            <select id="choose_category_id" class="form-control">
                                                @if(count($categorys) == 0)
                                                    <option value="">No Category</option>
                                                @else
                                                    <option value="">Select any Category</option>
                                                    @foreach ($categorys as $key => $category)
                                                      <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                           
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="sub_category_id" class="col-md-4 col-form-label text-md-right">{{ __('Sub Category') }}</label>

                                        <div class="col-md-6">
                                            <select id="sub_category_id" class="form-control">
                                                <option value="">Select Category first</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                   
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="set_category_name" class="col-md-4 col-form-label text-md-right">{{ __('Value') }}</label>

                                        <div class="col-md-6">
                                            <input type="text" id="set_category_name" name="category_name" class="form-control" required autofocus>
                                            <span class="buttom-alert">Use ~ with value for stop showing to your customer</span>
                                            <select id="category_position" class="form-control">
                                                {!! $positions !!}
                                            </select>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="set_sub_category_name" class="col-md-4 col-form-label text-md-right">{{ __('Value') }}</label>

                                        <div class="col-md-6">
                                            <input type="text" id="set_sub_category_name" placeholder="Separate by , (comma)" class="form-control">
                                            <select class="form-control mt-2" id="set_sub_position"><option value="">Select Menu Position</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col" id="sub_cate_img"></div>
                                <div class="col">
                                    <div class="form-group row">
                                        <label for="set_sub_category_name" class="col-md-4 col-form-label text-md-right">{{ __('Image') }}</label>

                                        <div class="col-md-6">
                                            <input id="set_sub_category_img" type="file" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-3 text-center" id="cate_btn_first">
                                    <button type="submit" class="btn btn-primary" id="create_category">
                                        {{ __('Add Category') }}
                                    </button>
                                </div>
                                <div class="col-md-3 text-center" id="cate_btn_secend">
                                    
                                </div>

                                <div class="col-md-3 text-center" id="cate_btn_third">
                                    
                                </div>
                                <div class="col-md-3 text-center" id="cate_btn_forth">
                                    
                                </div>

                            </div>
                            
                            
                            <div class="col-md-12" id="cat_msg"></div>
                        </form>
                    </div>
                </div>
        </div>
    </div>

  </div>
</div>
@endsection

