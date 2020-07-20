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
                        <div class="col">{{ __('All Acccess') }}</div>
                        <div class="col">
                          <input class="form-control" id="myInput" type="text" placeholder="Search..">
                        </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($access != '')
                            <table class="table table-bordered table-sm">
                            <thead>
                              <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Create At</th>
                                <th>Current Roll</th>
                              </tr>
                            </thead>
                            <tbody id="all_order">
                                {!! $access !!}
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert">&times;</button>
                          User is not available yet.
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
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
{{--Show Order Invoice--}}
@endsection
