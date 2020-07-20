@extends('layouts.main')
@section('contents')
<div class="catagory">
    <div class="container">
        @if($return == '')
            <div class="col-md-12">
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Sorry!</strong> We can't find any Content.
                </div>
            </div>
        @else
        {!! $return !!}
        @endif
    </div>
</div>
      
@endsection