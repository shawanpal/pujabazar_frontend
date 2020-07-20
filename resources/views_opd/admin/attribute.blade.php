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
                    <div class="card-header">{{ __('Attribute') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('attribute') }}">
                            @csrf
                            <div class="row">
                            <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="choose_attribute_id" class="col-md-4 col-form-label text-md-right">{{ __('Category') }}</label>

                                        <div class="col-md-6">
                                            <select id="att_category_id" class="form-control">
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
                                        <label for="choose_attribute_id" class="col-md-4 col-form-label text-md-right">{{ __('Sub Category') }}</label>

                                        <div class="col-md-6">
                                            <select id="att_sub_category_id" class="form-control">
                                                    <option value="">Select Category First</option>
                                            </select>
                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="choose_attribute_id" class="col-md-4 col-form-label text-md-right">{{ __('Attribute') }}</label>

                                        <div class="col-md-6">
                                            <select id="choose_attribute_id" class="form-control">
                                            <option value="">Select Subcategory first</option>
                                            </select>
                                           
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="term_id" class="col-md-4 col-form-label text-md-right">{{ __('Attribute Terms') }}</label>

                                        <div class="col-md-6">
                                            <select id="term_id" class="form-control">
                                                <option value="">Select Attribute first</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                   
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="set_attribute_name" class="col-md-4 col-form-label text-md-right">{{ __('Value') }}</label>

                                        <div class="col-md-6">
                                            <input id="set_attribute_name" type="text" class="form-control" required autofocus>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="set_term_name" class="col-md-4 col-form-label text-md-right">{{ __('Value') }}</label>

                                        <div class="col-md-6">
                                            <input id="set_term_name" type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-3 text-center" id="attr_btn_first">
                                    <button type="button" class="btn btn-primary" id="create_attribute">
                                        {{ __('Add Attribute') }}
                                    </button>
                                </div>
                                <div class="col-md-3 text-center" id="attr_btn_secend">
                                    
                                </div>

                                <div class="col-md-3 text-center" id="attr_btn_third">
                                    
                                </div>
                                <div class="col-md-3 text-center" id="attr_btn_forth">
                                    
                                </div>

                            </div>
                            
                            <div class="col-md-12" id="attr_msg"></div>
                        </form>
                    </div>
                </div>
        </div>
    </div>

  </div>
</div>
@endsection