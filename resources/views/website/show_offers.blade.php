@extends('layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/star-rating.css') }}" media="all"  type="text/css"/>
@endsection
@section('meta')

        <title>{{trans('site.deals')}}</title>

    <meta name="description" content="@if($offerSeo) {{$offerSeo->meta_description}} @endif">
    <meta name="keywords" content="@if($offerSeo) {{$offerSeo->meta_keywords}} @endif">
@endsection
@section('content')


    <section id="product">
        <div class="container">
            <div class="row">
                <div class="col-xs-12  ">
                    <div class="inner-pro">
                        <div class="row">
                            <ol class="breadcrumb">
                                <li><a href="{{ url('/') }}">{{ trans('site.home') }}</a></li>
                                <li class="active">
                                    {{trans('site.deals')}}
                                </li>
                            </ol>
                            @if(count($deals) > 0)
                                @foreach($deals as $item)
                                    <div class="col-md-3 col-sm-4 col-xs-6 productload proudct1 product">
                                        <div class="card">
                                            <div class="col-md-12">
                                                <div class="pro-image">
                                                    <a @if($lan == 'ar') href="{{ url($item->link) }}" @else href="{{ url($item->link_en) }}" @endif>
                                                        <img class="card-img-top img-responsive"
                                                             src="{{ url('uploads/product/resize800') }}/{{ $item->image }}"
                                                             alt="@if($lan == 'ar'){{ str_replace(' ' , '-' , $item->title_ar) }} @else {{ str_replace(' ' , '-' , $item->title) }} @endif"
                                                             title="@if($lan == 'ar'){{ $item->title_ar }} @else {{ $item->title }} @endif"
                                                             >
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <h4 class="card-title"><a @if($lan == 'ar') href="{{ url($item->link) }}" @else href="{{ url($item->link_en) }}" @endif
                                                                          title="@if($lan == 'ar'){{ $item->title_ar }} @else {{ $item->title }} @endif">
                                                        @if($lan == 'ar')  {{ mb_substr($item->title_ar,0,40) }} @if(strlen($item->title_ar) >40)
                                                            ... @endif @else  {{ mb_substr($item->title,0,40) }} @if(strlen($item->title) >40)
                                                            ... @endif @endif</a></h4>
                                                @if($item->discount > 0)
                                                    <div class="new-price"><span
                                                                class="currency"><strong> {{ number_format(($item->price - $item->discount)* Session::get('currencychange'),2) }}</strong>{{ Session::get('currencysymbol') }}</span>
                                                    </div>
                                                    <div class="old-price">{{ number_format($item->price * Session::get('currencychange'),2) }}
                                                        <span class="currency">{{ Session::get('currencysymbol') }}</span></div>
                                                @else
                                                    <div class="new-price"><span
                                                                class="currency"><strong> {{ number_format(($item->price)* Session::get('currencychange'),2) }}</strong>{{ Session::get('currencysymbol') }}</span>
                                                    </div>
                                                    <div class="old-price2"></div>
                                                @endif
                                                <a href="#" data-toggle="modal" data-target="#myModalcart"
                                                   class="mycart btn btn-success btn-block" data-count="1" data-id="{{ $item->id }}"><i
                                                            class="fa fa-shopping-cart"></i> {{ trans('site.add_to_card') }}</a>

                                                @if(Auth::user() && Auth::user()->isFavorite($item->id))
                                                    <div class="wish">
                                                        <button data-toggle="modal" data-target="#myModalfav" data-id="{{ $item->id }}" class="wishbtn" ><i class="fa fa-heart fa-2x" aria-hidden="true"></i></button>
                                                    </div>@endif



                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            @else
                                <img src="{{ url('uploads/soon.png') }}" />
                            @endif
                        </div>
                        {{$deals->links()}}
                    </div>
                </div>
            </div>
        </div>
    </section>



@endsection
@section('script')
    <script src="{{ URL::to('resources/assets/front/js/star-rating.js') }}" type="text/javascript"></script>
@endsection