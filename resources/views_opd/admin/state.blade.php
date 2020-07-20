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
                    <div class="card-header">{{ __('All States') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('state') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label for="choose_state_id" class="col-md-4 col-form-label text-md-right">{{ __('State') }}</label>

                                        <div class="col">
                                            <select id="choose_state_id" class="form-control">
                                                @if(count($states) == 0)
                                                    <option value="">No State</option>
                                                @else
                                                    <option value="">Select any State</option>
                                                    @foreach ($states as $key => $state)
                                                      <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                    @endforeach
                                                @endif
                                                
                                            </select>
                                           
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label for="set_state_name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                        <div class="col">
                                            <input id="set_state_name" type="text" class="form-control" required autofocus>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label for="set_state_phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>

                                        <div class="col">
                                            <input id="set_state_phone" type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="row">
                                <div class="col"></div>

                                <div class="col">
                                    <label for="set_state_address" class="col-form-label">{{ __('Address') }}</label>
                                    <textarea class="form-control" rows="3" id="set_state_address"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mt-1">
                                <div class="col-md-3 text-center" id="state_btn_first">
                                    <button type="button" class="btn btn-primary" id="create_state">
                                        {{ __('Add State') }}
                                    </button>
                                </div>
                                <div class="col-md-6"></div>

                                <div class="col-md-3 text-center" id="state_btn_secend">
                                    
                                </div>

                            </div>
                            
                            <div class="col-md-12" id="state_msg"></div>
                        </form>
                    </div>
                </div>
        </div>
    </div>

  </div>
</div>


@endsection
