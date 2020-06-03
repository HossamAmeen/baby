@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/lightbox.css') }}" />
 <style>
      
      


.port-image
{
    width: 100%;
}

.gallery_product
{
    margin-bottom: 30px;
}


    </style>
@endsection
@section('meta')
  <meta name="description" content="{{ $m->meta_description }}">
  <meta name="keywords" content="{{ $m->meta_keywords }}">
@endsection
@section('content')
<section class="about-us">
               <div class="container">
                   <div class="row">
                          <h2 style="text-align: center;">{{ trans('home.gallery') }}<h2>
                          <hr class="soften-d">
                      	   @foreach($gallery as $g)
		            <div class="gallery_product col-lg-2 col-md-3 col-sm-4 col-xs-6 ">
		                <a href="{{url('\uploads\gallery\source')}}\{{$g->image}}" data-lightbox="image-1" data-title="{{ $g->title }}">
		     <img style="width: 100%; max-height: 223px;min-height: 223px"src="{{url('\uploads\gallery\resize200')}}\{{$g->image}}" class="img-responsive">
		                </a>
		            </div>
		            @endforeach
                   </div>
               </div>
           </section>
@endsection
@section('script')
<script type="text/javascript" src="{{ URL::to('resources/assets/front/js/lightbox.min.js')}}"></script>
@endsection
