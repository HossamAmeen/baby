@extends('layouts.app')
@section('style')

@endsection
@section('meta')
  <meta name="description" content="{{ $artist->meta_description }}">
  <meta name="keywords" content="{{ $artist->meta_keywords }}">
@endsection
@section('content')

       
   <div id="artist">
   <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="art-info">
                    <div class="art-name"><h2>{{ $artist->name }}</h2></div>
                    <div class="sub-info"><p></p></div> 
                     <div id="social">
                        @if($artist->facebook)<a class="facebookBtn smGlobalBtn" href="{{ $artist->facebook }}" ></a>@endif
                        @if($artist->twitter)<a class="twitterBtn smGlobalBtn" href="{{ $artist->twitter }}" ></a>@endif
                        @if($artist->google)<a class="googleplusBtn smGlobalBtn" href="{{ $artist->google }}" ></a>@endif
                        @if($artist->pinterest)<a class="pinterestBtn smGlobalBtn" href="{{ $artist->pinterest }}" ></a>@endif
                        @if($artist->instagram)<a class="tumblrBtn smGlobalBtn" href="{{ $artist->instagram }}" ></a>@endif
    
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
            <div class="image"><img src="{{ url('uploads/artist/resize800') }}/{{ $artist->image }}" alt="..."></div>
            </div>
            <div class="col-xs-12 col-md-4 hidden-xs hidden-sm">
                <div class="left-name">
                <div class="name"><h3>{{ $artist->name }}</h3></div>
                <div class="social">
                    <div class="insta">
                       <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i> abeer1234</a>
                    </div>
                    <div class="website"><a href="#">| www.aber.com</a></div>
                </div>
                </div>
            </div>
        </div>
        </div>
  
<div id="cate-content">
    <hr>
    <div class="head-content">
        <div class="row">
            <div class="col-xs-5 col-md-4 col-lg-2">
                <div class="btn-group">
                    <a href="#" id="list" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-th-list">
                    </span></a> 
                        <a href="#" id="grid" class="btn btn-default btn-sm"><span
                        class="glyphicon glyphicon-th"></span></a>
                </div>
            </div>
        </div>
    </div>
    
    <hr>
    <div id="products" class="row list-group">
    @foreach($product as $item)
        <div class="item  col-xs-12 col-md-6 col-lg-4">
            <div class="product ">
                <div class="img">
                    <a @if($lan == 'ar') href="{{ url($item->link) }}" @else href="{{ url($item->link_en) }}" @endif><img class="img-responsive" src="{{ url('uploads/product/resize800') }}/{{ $item->image }}" alt="{{ $item->title }}"></a>
                </div>
                <div class="caption">
                    <h4><a @if($lan == 'ar') href="{{ url($item->link) }}" @else href="{{ url($item->link_en) }}" @endif>@if($lan == 'ar') {{ $item->title_ar }} @else {{ $item->title }} @endif</a></h4>
                    <p class="par">{!! $item->short_description !!}</p>
                    <p class="price" style="float:left">
                        @if($item->discount)<span class="price-new">{{ $item->price - $item->discount }} {{ $item->Currency->symbol }}  </span>  <span class="price-old">{{ $item->price }} {{ $item->Currency->symbol }}</span> @else <span class="price-new">{{ $item->price }} {{ $item->Currency->symbol }}  </span>  @endif
                    </p>
                
                    <div class="rating" style="text-align:right">          
                        <div class="rating">
                            <span class="inline"><input id="input-21d" @if(array_key_exists($item->id, $rating)) value="{{$rating[$item->id]}}" @else value="0" @endif  type="text" class="rating" data-min=0 data-max=5 data-step=1 data-size="sm"></span>
                        </div>
                    </div>
                </div>
                <div class="button-group " style="text-align:center">
                    <button type="button" ><i class="fa fa-compress"></i></button>
                    <button type="button" class="mycart" data-count="1" data-id="{{ $item->id }}" ><i class="fa fa-shopping-bag"></i></button>
                    <button type="button" class="myfavorite" data-id="{{ $item->id }}" ><i class="fa fa-heart-o"></i></button>
                </div>
            </div>
        </div>
    @endforeach
        
    </div>
    <div class="row pagi">
        <div class="col-xs-12 text-center page-result">{{$product->links()}}</div>
    </div>      
</div>            
           
@endsection