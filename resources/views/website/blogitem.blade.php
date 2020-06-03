@extends('layouts.app')
@section('style')
    <style>
        .about-us{
            margin: 40px 0px;
        }
    </style>
@endsection
@section('meta')
  <meta name="description" content="{{ $item->meta_description }}">
  <meta name="keywords" content="{{ $item->meta_keywords }}">
@endsection
@section('content')

<div id="blog-content" >
    <h1 class="headingpage"></h1>

    <div class="row">
        <div class="col-md-5 col-xs-12">
            <div class="webi-blog-image">
                <img src="{{ url('uploads/blogitem/source') }}/{{ $item->image }}" alt="{{ $item->title }}" title="{{ $item->title }}" class="img-thumbnail">
            </div>
        </div>
        <div class="col-md-7 col-xs-12">            
            <div class="view-comment">
                <div class="" style="margin-bottom: 10px;">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="name"><strong>{{ $item->title }}</strong></div>
                            <div class="date">{{ date('l jS F Y',strtotime($item->created_at)) }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-12">                 
                                {!! $item->text !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>                                   
        </div>
    </div>
</div>

@endsection
            