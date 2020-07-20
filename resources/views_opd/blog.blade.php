@extends('layouts.main')
@section('contents')
@foreach($blogs as $blog)
    @php
    $date = date('F d, Y', strtotime($blog->published));
    $image = asset('images/'.$blog->image);
    $heading = $blog->heading;
    $content = $blog->content;
    @endphp
@endforeach
    <div id="post-header" class="page-header">
        <div class="background-img" style="background-image: url('{{ $image }}');"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <div class="post-meta">
                        {{--<a class="post-category cat-2" href="category.html">JavaScript</a>--}}
                        <span class="post-date">{{ $date }}</span>
                    </div>
                    <h1>{{ $heading }}</h1>
                </div>
            </div>
        </div>
    </div>
<div class="container mt-3 mb-2">
    <div class="row">
        <div class="col-sm-8">
            {!! $content !!}
        </div>
        <div class="col-sm-4 mt-3">
        @foreach($allBlogs as $blg)
        <div class="post post-widget">
            <a class="post-img" href="{{ route('blg').'/'.$blg->url }}"><img src="{{ asset('images/'.$blg->image) }}" alt=""></a>
            <div class="post-body">
                <h3 class="post-title"><a href="{{ route('blg').'/'.$blg->url }}">{{ $blg->heading }}</a></h3>
            </div>
        </div>
        @endforeach
        </div>
    </div>
</div>



@endsection
