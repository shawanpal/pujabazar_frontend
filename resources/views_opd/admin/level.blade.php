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
                    <div class="card-header">{{ __('Levels') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('level') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label for="choose_level_id" class="col-md-4 col-form-label text-md-right">{{ __('Level') }}</label>

                                        <div class="col">
                                            <select id="choose_level_id" class="form-control">
                                                @if(count($levels) == 0)
                                                    <option value="">No Level</option>
                                                @else
                                                    <option value="">Select any Level</option>
                                                    @foreach ($levels as $key => $level)
                                                      <option value="{{ $level->id }}">{{ $level->name }}</option>
                                                    @endforeach
                                                @endif
                                                
                                            </select>
                                           
                                        </div>
                                    </div>
                                </div>

                               
                                   
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label for="set_level_name" class="col-md-4 col-form-label text-md-right">{{ __('Nmae') }}</label>

                                        <div class="col">
                                            <input id="set_level_name" type="text" class="form-control" required autofocus>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label for="set_level_commission" class="col-md-4 col-form-label text-md-right">{{ __('Commission') }}</label>

                                        <div class="col">
                                            <input id="set_level_commission" type="text" class="form-control" required autofocus placeholder="add % of commition">
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="row">
                                <div class="col-md-8"></div>
                            
                                <div class="col-md-4 text-center" id="lavle_btn_first">
                                    <button type="button" class="btn btn-primary" id="create_lavle">
                                        {{ __('Add Lavle') }}
                                    </button>
                                    {{--<button type="button" class="btn btn-primary" id="save_lavle">
                                        {{ __('Save Lavle') }}
                                    </button>

                                    <button type="button" class="btn btn-danger" id="delete_lavle">
                                        {{ __('Delete Lavle') }}
                                    </button>--}}
                                </div>
                                
                            </div>
                            
                            <div class="col-md-12" id="level_msg"></div>
                        </form>
                    </div>
                </div>
        </div>
    </div>

  </div>
</div>
@endsection
