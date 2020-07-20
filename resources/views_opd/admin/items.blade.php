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
                    <div class="card-header">{{ __('Package Items') }}</div>

                    <div class="card-body">
                        <form method="POST" action="">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <label for="department_id">{{ __('Department') }}</label>
                                            <select id="department_id" name="id" class="form-control">
                                                @if(count($departments) == 0)
                                                    <option value="">No department</option>
                                                @else
                                                    <option value="">Choose...</option>
                                                    @foreach ($departments as $key => $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="dp-name">{{ __('Name') }}</label>
                                            <input type="text" id="dp-name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col text-left" id="dep_btn_first">
                                        <button type="button" class="btn btn-primary" id="create_dep">Add Department</button>
                                        </div>
                                        <div class="col text-right" id="dep_btn_secend"></div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <label for="item_id">{{ __('Item') }}</label>
                                            <select id="item_id" name="id" class="form-control">
                                                
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="name">{{ __('Name') }}</label>
                                            <input type="text" id="name" class="form-control">
                                        </div>
                                        <div class="col">
                                            <label for="price">{{ __('Price') }}</label>
                                            <input type="text" id="price" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <label for="sw">{{ __('Size/Weight') }}</label>
                                            <input type="text" id="sw" class="form-control">
                                        </div>
                                        <div class="col">
                                            <label for="sw">{{ __('Unit') }}</label>
                                            <select id="sw_unit" class="form-control">
                                                <option value="">Unit</option>
                                                <optgroup label="Size Unit">
                                                    <option value="m">m</option>
                                                    <option value="cm">cm</option>
                                                    <option value="ft">ft</option>
                                                    <option value="Inch">Inch</option>
                                                </optgroup>
                                                <optgroup label="Weight Unit">
                                                    <option value="mg">mg</option>
                                                    <option value="gm">gm</option>
                                                    <option value="kg">kg</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="qty">{{ __('Quantity') }}</label>
                                            <input type="text" id="qty" class="form-control">
                                        </div>
                                        <div class="col">
                                            <label for="q_unit">{{ __('Unit') }}</label>
                                            <select id="q_unit" class="form-control">
                                                <option value="">Unit</option>
                                                <option value="pac">pac</option>
                                                    <option value="pcs">pcs</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col text-left" id="itm_btn_first"></div>
                                        <div class="col text-right" id="itm_btn_secend"></div>
                                    </div>
                                </div>
                            </div>                            

                            <div class="col-md-12 mt-3" id="item_msg"></div>
                        </form>
                    </div>
                </div>
        </div>
    </div>

  </div>
</div>
@endsection
