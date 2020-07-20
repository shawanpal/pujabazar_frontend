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
                    <div class="card-header">{{ __('Sellers') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('seller') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label for="choose_seller_id" class="col-md-4 col-form-label text-md-right">{{ __('Seller') }}</label>

                                        <div class="col">
                                            <select id="choose_seller_id" class="form-control">
                                                @if(count($sellers) == 0)
                                                    <option value="">No Seller</option>
                                                @else
                                                    <option value="">Select any seller</option>
                                                    @foreach ($sellers as $key => $seller)
                                                      <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                                                    @endforeach
                                                @endif
                                                
                                            </select>
                                           
                                        </div>
                                    </div>
                                </div>

                               
                                   
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label for="set_seller_name" class="col-md-4 col-form-label text-md-right">{{ __('Nmae') }}</label>

                                        <div class="col">
                                            <input id="set_seller_name" type="text" class="form-control" required autofocus>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label for="set_seller_level" class="col-md-4 col-form-label text-md-right">{{ __('Level') }}</label>

                                        <div class="col">
                                            <select id="set_seller_level" class="form-control">
                                                @if(count($levels) == 0)
                                                    <option value="">No level</option>
                                                @else
                                                    <option value="">Select any level</option>
                                                    @foreach ($levels as $key => $level)
                                                      <option value="{{ $level->id }}">{{ $level->name }}</option>
                                                    @endforeach
                                                @endif
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="row">
                                <div class="col"></div>

                                <div class="col">
                                    <label for="set_seller_address" class="col-form-label">{{ __('Address') }}</label>
                                    <textarea class="form-control" rows="3" id="set_seller_address"></textarea>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col text-right" id="seller_btn_first">
                                    <button type="button" class="btn btn-primary" id="create_seller">
                                        {{ __('Add Seller') }}
                                    </button>
                                </div>
                                

                            </div>
                            
                            
                            <div class="col-md-12" id="seler_msg"></div>
                        </form>
                    </div>
                </div>
        </div>
    </div>

  </div>
</div>
@endsection
