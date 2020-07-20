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
                        <div class="col">{{ __('All Orders') }}</div>
                        <div class="col">
                          <input class="form-control" id="myInput" type="text" placeholder="Search..">
                        </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($orders != '')
                            <table class="table table-bordered table-sm">
                            <thead>
                              <tr>
                                <th>Invoice Id</th>
                                <th>Create Date</th>
                                <th>Payment id</th>
                                <th>Payment Status</th>
                                <th>Shipping Status</th>
                                <th>Invoice Total</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody id="all_order">
                                @csrf
                                {!! $orders !!}
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert">&times;</button>
                          Order is not available yet.
                        </div>
                    @endif

                    </div>
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
        <button type="button" class="btn btn-success" id="print_me">Print</button>
      </div>
    </div>
  </div>
</div>
{{--Show Order Invoice--}}
@endsection
