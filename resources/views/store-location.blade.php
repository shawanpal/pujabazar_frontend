@extends('layout.app')
@section('content')
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-inner">
                    <ul class="bread-list">
                        <li>
                            <a href="{{url('/')}}">Home<i class="ti-arrow-right"></i></a>
                        </li>
                        <li class="active">
                            <a>Store Location</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table table-hover table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">State</th>
                        <th scope="col">Phone No</th>
                        <th scope="col">Address</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($locations as $location)
                        <tr>
                            <td>{{$location->name}}</td>
                            <td>{{$location->phone}}</td>
                            <td>{{$location->address}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection