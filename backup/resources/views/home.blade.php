@extends('layouts.app') @section('title')
    <title>تسوقي أفضل منتجات الاطفال في مصر | بيبي مامز.كوم</title>
@endsection
@section('meta')
    <meta name="keywords" content="">
    <meta itemprop="name" content="تسوقي أفضل منتجات الاطفال في مصر | بيبي مامز.كوم">
    <meta itemprop="description" content="بيبي مامز متجر واحد لجميع مستلزمات طفلك | تسوقي أفضل منتجات الاطفال حديثي الولادة وصولاً إلى الاطفال الصغار بأرخص سعر وأسرع شحن في مصر">
    <meta itemprop="image" content="https://babymumz.com/resources/assets/front/img/logo-ar.png">

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="https://babymumz.com">
    <meta property="og:type" content="website">
    <meta property="og:title" content="تسوقي أفضل منتجات الاطفال في مصر | بيبي مامز.كوم">
    <meta property="og:description" content="بيبي مامز متجر واحد لجميع مستلزمات طفلك | تسوقي أفضل منتجات الاطفال حديثي الولادة وصولاً إلى الاطفال الصغار بأرخص سعر وأسرع شحن في مصر">
    <meta property="og:image" content="https://babymumz.com/resources/assets/front/img/logo-ar.png">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="تسوقي أفضل منتجات الاطفال في مصر | بيبي مامز.كوم">
    <meta name="twitter:description" content="بيبي مامز متجر واحد لجميع مستلزمات طفلك | تسوقي أفضل منتجات الاطفال حديثي الولادة وصولاً إلى الاطفال الصغار بأرخص سعر وأسرع شحن في مصر">
    <meta name="twitter:image" content="https://babymumz.com/resources/assets/front/img/logo-ar.png">

@endsection
@section('content')
    <section id="main-slider">
        <div class="container">
        {{--<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">--}}
        {{--<!-- Indicators -->--}}
        {{--<ol class="carousel-indicators">--}}
        {{--@foreach($slider as $k => $s)--}}
        {{--<li data-target="#carousel-example-generic" data-slide-to="{{ $k }}"--}}
        {{--@if($k==0 ) class="active" @endif></li>--}}
        {{--@endforeach--}}
        {{--</ol>--}}
        {{--<!-- Wrapper for slides -->--}}
        {{--<div class="carousel-inner" role="listbox">--}}
        {{--@foreach($slider as $k => $s)--}}
        {{--<div class="item @if($k == 0) active @endif">--}}
        {{--@if($s->link)--}}
        {{--<a href="{{ url($s->link) }}"><img--}}
        {{--src="{{ url('uploads/slideshow/resize1200') }}/{{ $s->image }}"--}}
        {{--alt=""></a> @else--}}
        {{--<img src="{{ url('uploads/slideshow/resize1200') }}/{{ $s->image }}" alt=""> @endif--}}
        {{--</div>--}}
        {{--@endforeach--}}
        {{--</div>--}}
        {{--<a class="left carousel-control hidden-lg hidden-md" href="#carousel-example-generic" role="button" data-slide="prev">--}}
        {{--<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>--}}
        {{--<span class="sr-only">السابق </span>--}}
        {{--</a>--}}
        {{--<a class="right carousel-control hidden-lg hidden-md" href="#carousel-example-generic" role="button" data-slide="next">--}}
        {{--<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>--}}
        {{--<span class="sr-only">Nextالتالى </span>--}}
        {{--</a>--}}
        {{--</div>--}}
        <!-- Swiper -->
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    @foreach($slider as $k => $s)
                        <div class="swiper-slide item @if($k == 0) active @endif">
                            @if($s->link)
                                <a href="{{ url($s->link) }}"><img
                                            class="lozad" data-src="{{ url('uploads/slideshow/resize1200') }}/{{ $s->image }}"
                                            alt=""></a> @else
                                <img class="lozad" data-src="{{ url('uploads/slideshow/resize1200') }}/{{ $s->image }}" alt=""> @endif
                        </div>
                    @endforeach
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>
    @if(count($deals)>0)
        <section class="deals">
            <div class="container">
                <div class="title">
                    <h2 class="purpel background"><span> {{ trans('site.deals') }} </span></h2>
                    {{--
                    <h4><a href="#">View all </a> <i class="fa fa-angle-right" aria-hidden="true"></i></h4> --}}
                </div>
                <div id="carousal">
                    <div id="owl-sp" class="owl-carousel owl-theme">
                        @foreach($deals as $fea)
                            <div class="item product  text-center">
                                <a @if($lan == 'ar') href="{{ url($fea->link) }}" @else href="{{ url($fea->link_en) }}" @endif >
                                    <div class="image">
                                        <img class="lozad"
                                             data-src="{{ url('uploads/product/resize200') }}/{{ $fea->image }}"
                                             alt="@if($lan == 'ar'){{ str_replace(' ' , '-' , $fea->title_ar) }} @else {{ str_replace(' ' , '-' , $fea->title) }} @endif">
                                    </div>
                                </a>
                                <div class="desc">
                                    <div class="proudct-name"><h5><a @if($lan == 'ar') href="{{ url($fea->link) }}" @else href="{{ url($fea->link_en) }}" @endif>
                                                @if($lan == 'ar')  {{ mb_substr($fea->title_ar,0,40) }} @if(strlen($fea->title_ar) >40) ... @endif @else  {{ mb_substr($fea->title,0,40) }} @if(strlen($fea->title) >40) ... @endif @endif</a></h5></div>
                                    @if($fea->discount > 0)
                                        <div class="new-price"><span class="currency"><strong> {{ number_format(($fea->price - $fea->discount)* Session::get('currencychange'),2) }}</strong>{{ Session::get('currencysymbol') }}</span></div>
                                        <div class="old-price">{{ number_format($fea->price * Session::get('currencychange'),2) }} <span class="currency">{{ Session::get('currencysymbol') }}</span></div>
                                    @else
                                        <div class="new-price"><span class="currency"><strong> {{ number_format(($fea->price)* Session::get('currencychange'),2) }}</strong>{{ Session::get('currencysymbol') }}</span></div>
                                        <div class="old-price"></div>
                                    @endif

                                </div>
                                <div class="add">
                                    <button type="button" data-toggle="modal" data-target="#myModalcart" class="mycart btn btn-success btn-block" data-count="1" data-id="{{ $fea->id }}" ><i class="fa fa-shopping-cart"></i> {{ trans('site.add_to_card') }}</button>
                                </div>

                                @if(Auth::user() && Auth::user()->isFavorite($fea->id))
                                    <div class="wish">
                                        <button data-id="{{ $fea->id }}" class="wishbtn" ><i class="fa fa-heart fa-2x" aria-hidden="true"></i></button>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
    <section class="selected-product ">
        <input type="hidden" name="step" id="step" value="1"/>
        <div class="container">
            <div class="row nomargin " id="dispaly_products">
                <div class="title ">
                    <h2 class="purpel background"><span> {{ trans('site.products') }} </span></h2>
                </div>
                @foreach($producthome as $kitem => $item)
                    <div class="col-md-3 col-sm-4 col-xs-6 productload proudct1 product">
                        <div class="card">
                            <div class="col-md-12">
                                <div class="pro-image">
                                    <a @if($lan == 'ar') href="{{ url($item->link) }}" @else href="{{ url($item->link_en) }}" @endif>
                                        <img class="card-img-top img-responsive lozad"
                                             data-src="{{ url('uploads/product/resize200') }}/{{ $item->image }}"
                                             alt="@if($lan == 'ar'){{ str_replace(' ' , '-' , $item->title_ar) }} @else {{ str_replace(' ' , '-' , $item->title) }} @endif"
                                             title="@if($lan == 'ar'){{ $item->title_ar }} @else {{ $item->title }} @endif">
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
                                    <div class="old-price"></div>
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
                    <input type="hidden" class="idproduct" value="{{ $item->id }}"/>
                @endforeach
            </div>
            <div class="col-xs-12 col-md-12 text-center" id="load_more_img">
                <img class="lozad"  data-src="{{ url('resources/assets/front/img/circle-loading-gif.gif') }}"/>
            </div>
            <div class="col-xs-12 col-md-12 text-center removemore" id="load_more_div">
                <button class="btn btn-deafult select  more">{{ trans('site.load_more') }}</button>
            </div>
            <hr class="removemore">
        </div>
    </section>
    <section class="sponsor ">
        <div class="container">
            <hr>
            <div class="row">
                <div class="title text-center purpel">
                    <h4>{{ trans('site.widest_brands') }}</h4>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 text-center">
                <div id="carousal">
                    <div id="owl-sp2" class="owl-carousel owl-theme">
                        @foreach ($brands as $brand) @if($brand -> image != '')
                            <div class="item text-center">
                                <div class="image">
                                    <a href="{{ route('brandproducts',['id' => $brand->id,'name' => $brand->name]) }}">
                                        <img class="lozad" data-src="{{ url('uploads/brands/resize200') }}/{{ $brand->image }}"
                                             alt="{{ $brand->name }}"/>
                                    </a>
                                </div>
                            </div>
                        @endif @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')


    {{--<link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/swiper.min.css')}}">--}}
    <script defer src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script defer src="{{ URL::to('resources/assets/front/js/jquery.number.min.js')}}"></script>
    <script src="{{ URL::to('resources/assets/front/js/swiper.min.js')}}"></script>
    <script defer>
        var stop = 1;$('#load_more_div').hide();var swiper = new Swiper('.swiper-container', {pagination: {el: '.swiper-pagination', clickable: true,}, autoplay: {delay: 2500, disableOnInteraction: false,}});
        $(window).scroll(function () {var step = parseInt($('#step').val());if (($(window).scrollTop() + $(window).height()) > ($(document).height() - 400) && step <= 3 && stop) {stop = 0;$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});var ids = [];$('.idproduct').each(function (i) {ids[i] = $(this).val();});$.ajax({url: " {{url('load_more/products')}}", method: 'POST', data: {step: step, id: ids}, success: function (data) {if (data == "") {$('#step').val(4);$('#dispaly_products').append('<p>No More Posts</p>');} else {step += 1;if (step > 3) {$('#load_more_img').html('');$('#load_more_div').show();}$('#step').val(step);$('#dispaly_products').append(data);stop = 1;}}});}});
        $('.more').on('click', function () {var id = [];$('.idproduct').each(function (i) {id[i] = $(this).val();});$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});$.ajax({url: " {{url('getmore')}}", method: 'POST', data: {id: id}, success: function (data) {var html = '';for (var j = 0; j < data[0].length; j++) {html += '<div class="col-md-3 col-sm-4 col-xs-6 productload">';html += '<div class="card">' + '<div class="col-md-12"><div class="pro-image">';html += '<a href="{{ url('/') }}/' + ((data[1] == 'ar') ? data[0][j].link: data[0][j].link_en) + '" ><img class="card-img-top img-responsive" src="{{ url('uploads/product/resize200') }}/' + data[0][j].image + '" alt="' + ((data[1] == 'ar') ? data[0][j].title_ar.replace(' ' , '-') : data[0][j].title.replace(' ' , '-')) + '"></a>' + '</div></div>';html += '<div class="card-body">' + '<h4 class="card-title"><a href="{{ url('') }}/' + ((data[1] == 'ar') ? data[0][j].link: data[0][j].link_en) + '">';if (data[1] == 'ar') {html += data[0][j].title_ar.substr(0, 40);if (data[0][j].title_ar.length > 40) {html += '...'}} else {html += data[0][j].title.substr(0, 40);if (data[0][j].title.length > 40) {html += '...'}}html += '</a></h4>';if (data[0][j].discount != 0.00) {html += '<div class="new-price"><span' + '        class="currency"><strong>' + $.number(((data[0][j].price - data[0][j].discount)) * parseFloat('{{ Session::get('currencychange') }}'), 2) + '</strong>{{ Session::get('currencysymbol') }}</span>' + '            </div>' + '            <div class="old-price">' + $.number((data[0][j].price) * parseFloat('{{ Session::get('currencychange') }}'), 2) + '                <span class="currency">{{ Session::get('currencysymbol') }}</span></div>';} else {html += '            <div class="new-price"><span\n' + '        class="currency"><strong>' + $.number((data[0][j].price) * parseFloat('{{ Session::get('currencychange') }}'), 2) + '</strong>{{ Session::get('currencysymbol') }}</span>' + '            </div>' + '            <div class="old-price"></div>';}html += '                <a href="#" data-toggle="modal" data-target="#myModalcart"' + '        class="mycart btn btn-success btn-block" data-count="1" data-id="' + data[0][j].id + '"><i' + '        class="fa fa-shopping-cart"></i> {{ trans('site.add_to_card') }}</a>' + '            </div></div></div></div><input type="hidden" class="idproduct" value="' + data[0][j].id + '"/>';             }$('.productload:last').after(html);if (data[2] == 0) {$('.removemore').remove()}}});});
    </script>
@endsection