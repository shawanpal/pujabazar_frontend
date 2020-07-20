@extends('layouts.main')
@section('contents')
<div class="catagory">
    <div class="container">
        @if(count($blogs) == 0)
            <div class="col-md-12">
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Sorry!</strong> We can't find any Blog.
                </div>
            </div>
        @else
        <div class="row">
        @foreach($blogs as $blog)
        @php
        $date = date('F d, Y', strtotime($blog->published));
        @endphp
            <div class="col-md-6">
                <div class="post post-thumb">
                    <a class="post-img" href="{{ route('blg').'/'.$blog->url }}"><img src="{{ asset('images/'.$blog->image) }}" alt=""></a>
                    <div class="post-body">
                        <div class="post-meta">
                            {{--<a class="post-category cat-2" href="category.html">JavaScript</a>--}}
                            <span class="post-date">{{ $date }}</span>
                        </div>
                        <h3 class="post-title"><a href="{{ route('blg').'/'.$blog->url }}">{{ $blog->heading }}</a></h3>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
        @endif
    </div>
</div>
      
@endsection