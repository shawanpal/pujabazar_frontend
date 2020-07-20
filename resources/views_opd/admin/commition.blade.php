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
                    <div class="card-header">
                        <div class="row">
                        <div class="col">
                          <select class="form-control" id="seller_name">
                            @if(count($sellers)>0)
                            <option value="">Choose a Seller</option>
                              @foreach($sellers as $seller)
                                <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                              @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="col">
                          <input type="date" class="form-control" id="start_date">
                        </div>
                        <div class="col">
                          <input type="date" class="form-control" id="end_date">
                        </div>
                        <div class="col">
                          <button type="button" class="btn btn-primary" id="cal_comm">Calculate</button>
                        </div>
                        </div>
                    </div>

                    <div class="card-body" id="sellerCommition"></div>
                </div>
        </div>
    </div>

  </div>
</div>

{{--Show Order Invoice--}}
<div class="modal fade" id="orderInvoice">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="show_invoice">
        
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>   
{{--Show Order Invoice--}}
@endsection
