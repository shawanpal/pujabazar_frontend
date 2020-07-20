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
                        <div class="col">{{ __('All Packages') }}</div>
                        <div class="col">
                        <input class="form-control" id="pac-search" type="text" placeholder="Code or Name">
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-outline-primary float-right" id="add-package">{{ __('Add Package') }}</button>
                        </div>
                        </div>
                    </div>

                    <div class="card-body">
                            <table class="table table-bordered table-sm" id="all-package-table">
                                <thead>
                                  <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>S.Category</th>
                                    <!-- <th>Samogri</th> -->
                                    <th>States</th>
                                    <!-- <th>Stock</th>
                                    <th>Quality</th>
                                    <th>Price</th>
                                    <th>Discount</th> -->
                                    <th>Details</th>
                                    <!-- <th>Updated</th> -->
                                    <th>Edit</th>
                                    <th>Delete</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>        
                            <ul class="pagination pagination-sm float-right">
                
                            </ul>                       
                    </div>
                </div>
        </div>
    </div>

  </div>
</div>
{{--Package Modal Form--}}
<div class="modal fade" id="package-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Set Your Product</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form method="POST" action="" enctype='multipart/form-data'>
            @csrf
                <div class="modal-body">
                
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn-sbm">{{ __('Add New Package') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{--Package Modal Form--}}
@endsection
