<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-135068478-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-135068478-1');
    </script>

    <!-- Google Tag Manager -->
    {{--<script>(function (w, d, s, l, i) {--}}
            {{--w[l] = w[l] || [];--}}
            {{--w[l].push({--}}
                {{--'gtm.start':--}}
                    {{--new Date().getTime(), event: 'gtm.js'--}}
            {{--});--}}
            {{--var f = d.getElementsByTagName(s)[0],--}}
                {{--j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';--}}
            {{--j.async = true;--}}
            {{--j.src =--}}
                {{--'https://www.googletagmanager.com/gtm.js?id=' + i + dl;--}}
            {{--f.parentNode.insertBefore(j, f);--}}
        {{--})(window, document, 'script', 'dataLayer', 'GTM-PGNDRHS');</script>--}}
    <!-- End Google Tag Manager -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')

    @yield('title')
    <link href="https://fonts.googleapis.com/css?family=Cairo" rel="stylesheet">
    <link rel="icon" href="{{ URL::to('uploads/configurationsite/resize200') }}/{{ $con->imageall }}">
    <link href="https://fonts.googleapis.com/css?family=Changa" rel="stylesheet">
{{--    <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/lightbox.min.css') }}"/>--}}
    <link href="https://fonts.googleapis.com/css?family=Lemonada" rel="stylesheet">
{{--    <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/select2.min.css') }}"/>--}}
{{--    <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/jquery.dataTables.css') }}"/>--}}
{{--    <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/dropzone.min.css') }}"/>--}}


    {{--<link rel="stylesheet"--}}
          {{--href="{{ URL::to('resources/assets/front/css/bootstrap-datetimepicker.min.css') }}"/>--}}
    {{--@if(App::isLocale('ar'))--}}
        {{--<link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/bootstrap.rtl.full.min.css') }}">--}}
    {{--@else--}}
        {{--<link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/bootstrap.css') }}">--}}
    {{--@endif--}}
{{--    <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/font-awesome.min.css') }}">--}}
    {{--<link rel="stylesheet" href="">--}}
{{--    <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/owl.theme.css') }}">--}}
    {{--<link href="{{ URL::to('resources/assets/front/css/slide.css') }}" rel="stylesheet">--}}
{{--    <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/bootstrap-select.min.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/dropzone.min.css') }}">--}}
    {{--<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">--}}
    @if(App::isLocale('ar'))
        <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/bootstrap.rtl.full.min.css') }}">
    @else
        <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/bootstrap.css') }}">
    @endif
    {{--<link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/style.css') }}">--}}
    {{--@if(App::isLocale('ar'))--}}
        {{--<link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/rtl.css') }}">--}}
    {{--@endif--}}
    @yield('style')
    <style>
        .slideout-menu {left: auto;}  .btn-hamburger {left: auto;right: 12px;}  .box {height: 1500px;}
    </style>



    <!-- Facebook Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '682963885454030');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=682963885454030&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->

    <!-- Onesignal Notification -->
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
        var OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
                appId: "622a0458-88ce-45d8-9de8-ba5407496e39",
                notifyButton: {
                    enable: true,
                },
            });
        });
    </script>
    <!-- /Onesignal Notification -->


    {{--<script id="mcjs">!function (c, h, i, m, p) {--}}
            {{--m = c.createElement(h), p = c.getElementsByTagName(h)[0], m.async = 1, m.src = i, p.parentNode.insertBefore(m, p)--}}
        {{--}(document, "script", "https://chimpstatic.com/mcjs-connected/js/users/7791969a25860dbe8fe29c850/da204f7ee0ce2c4e56abd28f4.js");</script>--}}
    {{--<script id="mcjs">!function (c, h, i, m, p) {--}}
            {{--m = c.createElement(h), p = c.getElementsByTagName(h)[0], m.async = 1, m.src = i, p.parentNode.insertBefore(m, p)--}}
        {{--}(document, "script", "https://chimpstatic.com/mcjs-connected/js/users/c17092699b6575d7b5dc41e3b/3c6a48c606036f7473e090de7.js");</script>--}}
</head>
<?php
$uri_path = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$uri_parts = explode('/', $uri_path);
$uri_tail = end($uri_parts);

$configuration = \App\Configurationsite::first();
?>
@if($uri_tail =="index.php") @php array_pop($uri_parts); $url = implode('/', $uri_parts); @endphp @section('script')
    <script type="text/javascript">
        window.location = "{{ URL::to('resources/assets/') }}";
    </script>
@stop @endif
<body id="app-layout">

<script>
    fbq('track', 'ViewContent');
</script>

<script>
    fbq('track', 'Search');
</script>

<script>
    fbq('track', 'AddToWishlist');
</script>

<script>
    fbq('track', 'AddToCart');
</script>

<script>
    fbq('track', 'InitiateCheckout');
</script>

<script>
    fbq('track', 'AddPaymentInfo');
</script>

<script>
    fbq('track', 'Subscribe');
</script>

<script>
    fbq('track', 'CompleteRegistration');
</script>

<script>
    fbq('track', 'Contact');
</script>

<script>
    fbq('track', 'FindLocation');
</script>

<script>
    fbq('track', 'CustomizeProduct');
</script>


<script>
    fbq('track', 'Lead');
</script>

<script>
    fbq('track', 'SubmitApplication');
</script>

<!-- Google Tag Manager (noscript) -->
{{--<noscript>--}}
    {{--<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PGNDRHS"--}}
            {{--height="0" width="0" style="display:none;visibility:hidden"></iframe>--}}
{{--</noscript>--}}
<!-- End Google Tag Manager (noscript) -->
<div class="row nomargin">
    <input type="checkbox" id="offcanvas-menu" class="toggle">
    <div class="con  hidden-md hidden-lg">
        <aside class="menu-container">
            <div class="menu-heading clearfix">
                <a href="{{url('/')}}">{{trans('site.home')}}</a>
                <label for="offcanvas-menu" class="close-btn pull-right">
                    <i class="fa fa-times"></i>
                </label>
            </div>
            <!--end menu-heading-->
            <nav class="slide-menu">
                <ul class="list-unstyled nomargin">
                    @foreach($categorynoparent as $cat)
                        @if($lan == 'ar')
                            <li class="unstyledd"><a href="#formreply_{{ $cat->id }}"
                                                     data-toggle="collapse">{{ $cat->title_ar }} <span
                                            class="pull-right"><i class="fa fa-caret-down"
                                                                  aria-hidden="true"></i></span></a></li>
                            <hr> @else
                            <li class="unstyledd"><a href="#formreply_{{ $cat->id }}"
                                                     data-toggle="collapse">{{ $cat->title }} <span
                                            class="pull-right"><i class="fa fa-caret-down"
                                                                  aria-hidden="true"></i></span></a></li>
                            <hr> @endif
                        <div class="child collapse" id="formreply_{{ $cat->id }}">
                            <ul>
                                @if($lan == 'ar')
                                    <li><a href="{{ url($cat->link) }}">{{ $cat->title_ar }}</a></li>
                                @else
                                    <li><a href="{{ url($cat->link) }}">{{ $cat->title }}</a></li>
                                @endif @foreach(App\Category::where('status',1)->where('parent',$cat->id)->get() as $keyk => $item) @if($lan == 'ar')
                                    <li><a href="{{ url($item->link) }}">{{ $item->title_ar }}</a></li>
                                @else
                                    <li><a href="{{ url($item->link) }}">{{ $item->title }}</a></li>
                                @endif @endforeach
                            </ul>
                        </div>
                    @endforeach
                    <li class="unstyledd"><a href="{{url('/offers')}}"
                                             class="deals-mobile"> {{trans('site.deals')}} </a></li>
                    <hr/>
                    @if(Auth::check())
                        <span class="wish wish-mob"><a href="{{ url('my-favorite') }}"> المفضلة</a></span>
                        <hr/>
                        <div class="wish-mob"><a class="undec" href="{{ url('logout') }}">{{ trans('site.logout') }}</a>
                        </div>
                    @else
                        {{--<div class="wish-mob"><a class="undec" href="{{ url('register') }}">{{ trans('site.register') }}</a>--}}
                        {{--</div> <hr />--}}
                        <div class="wish-mob"><a class="undec" href="{{ url('login') }}">{{ trans('site.login') }}</a>
                        </div>
                    @endif
                </ul>

            </nav>
            <!--end slide-menu -->
        </aside>
        <!--end menu-container-->
        <section class="content">
            <label for="offcanvas-menu" class="full-screen-close"></label>
            <div class="col-xs-2">
                <div class="menu">
                    <label for="offcanvas-menu" class="toggle-btn">
                        <i class="fa fa-bars"></i>
                    </label>
                </div>
            </div>
            <!--end menu-->
        </section>
        <!--end content-->
    </div>
</div>
<header>
    <div class="top">
        <div class="container">
            <div class="left hidden-xs hidden-sm">
                <ul class="list-unstyled">
                    <li><i class="fa fa-tag" aria-hidden="true"></i> <span>{{trans('site.best_price')}}</span></li>
                    <li><i class="fa fa-truck" aria-hidden="true"></i> <span>{{trans('site.free_speed')}}</span>
                    </li>
                </ul>
            </div>
            <span class="  hidden-md hidden-sm hidden-lg">
                        <a href="{{ url('/') }}">
                            <img class="img-responsive logo-mob lozad"
                                                            data-src="{{ URL::to('resources/assets/front/img/logo-mobile.png') }}">   </a>
                    </span>
            <div class="right">
                {{--
                <div class="blog in-block"><a class="undec" href="#"> Mums Community</a></div> --}}
                {{--@if($lan == 'ar')--}}
                    {{--<div class="language in-block"><a class="undec"--}}
                                                      {{--href="{{ URL::to('lang/en') }}">{{trans('site.english')}}</a>--}}
                    {{--</div>--}}
                {{--@else--}}
                    {{--<div class="language in-block"><a class="undec"--}}
                                                      {{--href="{{ URL::to('lang/ar') }}">{{trans('site.arabic')}}</a>--}}
                    {{--</div>--}}
                {{--@endif--}}
                <div class="currency in-block">
                    <div class="dropdown">
                        <button style="padding-top: 3px;" class="btn btn-default dropdown-toggle" type="button"
                                id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            {{--{{ trans('site.currency') }}--}}{{ Session::get('currencysymbol') }}
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            @foreach($currency as $cu)
                                @if($lan == 'ar')
                                    <li class="changecurrency" style="cursor: pointer;" data-code="{{ $cu->code }}">
                                        <a>( {{ $cu->symbol }} ) {{ $cu->name_ar }} </a></li>
                                @else
                                    <li class="changecurrency" style="cursor: pointer;" data-code="{{ $cu->code }}">
                                        <a>( {{ $cu->symbol }} ) {{ $cu->name }} </a>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                @if(Auth::check())
                    <div class="login in-block hidden-xs hidden-sm"><a class="undec"
                                                                       href="{{ url('logout') }}">{{ trans('site.logout') }}</a>
                    </div>
                @else
                    {{--<div class="login in-block hidden-xs hidden-sm"><a class="undec" href="{{ url('register') }}">{{ trans('site.register') }}</a>--}}
                    {{--</div>--}}
                    <div class="login in-block hidden-xs hidden-sm"><a class="undec"
                                                                       href="{{ url('login') }}">{{ trans('site.login') }}</a>
                    </div>
                @endif
                <div class="social in-block">
                    <span class="facebook"><a href="{{$configuration->facebook}}"><i
                                    class="fa fa-facebook"></i></a></span>
                    <span class="instagram"><a href="{{$configuration->instgram}}"><i
                                    class="fa fa-instagram"></i></a></span>
                </div>
            </div>
        </div>
    </div>
    <div class="search-bar hidden-xs">
        <div class="container">
            <div class="row ">
                <div class="col-md-3  col-sm-3 text-center">
                    {{--<a href="{{ url('/') }}">--}}
                    {{--<img class="img-responsive"--}}
                    {{--                   src="{{ URL::to('uploads/configurationsite/resize200') }}/{{ $con->logo }}"--}}
                    {{--title="Fashion Store" alt="Fashion Store"></a> -->--}}
                    @if($lan == 'ar')
                        <a href="{{ url('/') }}"> <img class="img-responsive lozad"
                                                       data-src="{{ URL::to('resources/assets/front/img/logo-ar.png') }}">
                        </a>
                    @elseif($lan == 'en')
                        <a href="{{ url('/') }}"> <img class="img-responsive lozad"
                                                       data-src="{{ URL::to('resources/assets/front/img/logo-eng.png') }}">
                        </a>
                    @endif
                </div>
                <div class=" col-md-6 col-sm-6">
                    <div class="search">
                        {!! Form::open(['method' => 'GET' , 'url' => 'search-home', 'data-toggle'=>'validator']) !!}
                        <div class="form-group in-search">
                            <input type="text" name="searchtext" placeholder="{{ trans('site.type_your_search') }}"
                                   @if (!empty($searchtext))
                                   value="{{$searchtext}}"
                                   @endif
                                   required class="form-control input-md" id="searchtext1">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"
                                                                             aria-hidden="true"></i></button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class=" col-md-3 col-sm-3">
                    <div class="icon">
                        @if(Auth::check())
                            <span class="wish"><a href="{{ url('my-favorite') }}"><i
                                            class="fa fa-heart-o fa-2x" aria-hidden="true"></i></a></span>
                            <span class="dropdown">
                        <button class="btn btn-default dropdown-toggle outline" type="button" id="dropdownMenu1"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <span class="fav"><i class="fa fa-user fa-2x" aria-hidden="true"></i></span>
                                <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu pull-right" aria-labelledby="dropdownMenu1">
                                    <li><a href="{{ url('change-account') }}">{{ trans('site.changeaccount') }}</a></li>
                                    @if(Auth::user()->delivery)
                                        <li><a href="{{ url('my-deliveries') }}">{{ trans('site.my_deliveries') }}</a></li>
                                    @endif
                                    <li><a href="{{ url('my-address') }}">{{ trans('site.myaddress') }}</a></li>
                                    <li><a href="{{ url('my-orders/0') }}">{{ trans('home.incompleted_orders') }}</a></li>
                                    <li><a href="{{ url('my-orders/1') }}">{{ trans('home.completed_orders') }}</a></li>
                                    @if(Auth::user()->vendor)
                                        <li><a href="{{ route('vendorproducts',['id' => Auth::user() -> id]) }}"
                                               target="_blank">{{ trans('site.vendorpanel') }}</a></li> @endif @if(Auth::user()->affilate)
                                        <li><a href="{{ route('affilatepanelproducts',['id' => Auth::user() -> id]) }}"
                                               target="_blank">{{ trans('site.affilatepanel') }}</a></li> @endif @if(Auth::user()->admin)
                                        <li><a href="{{ url('admin') }}"
                                               target="_blank">{{ trans('site.controlpanel') }}</a></li> @endif
                                </ul>
                                </span>
                        @endif
                        <span class="cart"><a href="{{ url('my-cart') }}"><i class="fa fa-shopping-cart fa-2x"
                                                                             aria-hidden="true"></i><div
                                        class="cart-con" id="cart-con2">{{ $cartcount }}</div></a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="search-bar visible-xs">
        <div class="container">
            <div class="row ">
                <div class="col-xs-7 text-center">
                    {{--<!--      <a href="{{ url('/') }}">--}}
                    {{--<img class="img-responsive"--}}
                    {{--src="{{ URL::to('uploads/configurationsite/resize200') }}/{{ $con->logo }}"--}}
                    {{--title="Fashion Store" alt="Fashion Store"></a> -->--}}
                    <a href="{{ url('/') }}">
                        <p class="logo-p-phone"> Baby & Mumuz</p>
                    </a>
                </div>
                <div class=" col-xs-5">
                    <div class="icon">
                        @if(Auth::check())
                            <span class="wish"><a href="{{ url('my-favorite') }}"><i
                                            class="fa fa-heart-o fa-2x" aria-hidden="true"></i></a></span>
                            <span class="dropdown">
                                            <button class="btn btn-default dropdown-toggle outline" type="button"
                                                    id="dropdownMenu1"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                <span class="fav"><i class="fa fa-user fa-2x"
                                                                     aria-hidden="true"></i></span>
                                                    <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu pull-right" aria-labelledby="dropdownMenu1">
                                            <li><a href="{{ url('change-account') }}">{{ trans('site.changeaccount') }}</a></li>
                                                @if(Auth::user()->delivery)
                                                    <li><a href="{{ url('my-deliveries') }}">{{ trans('site.my_deliveries') }}</a></li>
                                                @endif
                                                <li><a href="{{ url('my-address') }}">{{ trans('site.myaddress') }}</a></li>
                                            <li><a href="{{ url('my-orders/0') }}">{{ trans('home.incompleted_orders') }}</a></li>
                                            <li><a href="{{ url('my-orders/1') }}">{{ trans('home.completed_orders') }}</a></li>
                                                @if(Auth::user()->vendor)
                                                    <li><a href="{{ route('vendorproducts',['id' => Auth::user() -> id]) }}"
                                                           target="_blank">{{ trans('site.vendorpanel') }}</a></li> @endif @if(Auth::user()->affilate)
                                                    <li><a href="{{ route('affilatepanelproducts',['id' => Auth::user() -> id]) }}"
                                                           target="_blank">{{ trans('site.affilatepanel') }}</a></li> @endif @if(Auth::user()->admin)
                                                    <li><a href="{{ url('admin') }}"
                                                           target="_blank">{{ trans('site.controlpanel') }}</a></li> @endif
                                        </ul>
                                        </span>
                        @endif
                        <span class="cart"><a href="{{ url('my-cart') }}"><i class="fa fa-shopping-cart fa-2x"
                                                                             aria-hidden="true"></i><div
                                        class="cart-con" id="cart-con">{{ $cartcount }}</div></a></span>
                    </div>
                </div>
                <div class=" col-xs-12">
                    <div class="search">
                        {!! Form::open(['method' => 'GET' ,'url' => 'search-home', 'data-toggle'=>'validator']) !!}
                        <div class="form-group in-search">
                            <input type="text" name="searchtext"
                                   @if (!empty($searchtext))
                                   value="{{$searchtext}}"
                                   @endif
                                   placeholder="{{ trans('site.type_your_search') }}"
                                   required class="form-control input-md" id="searchtext">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"
                                                                             aria-hidden="true"></i></button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hidden-xs hidden-sm">
        <nav class="navbar  navbar-default nav1 nomargin">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav ">
                        @foreach($categorynoparent as $cat) @if(in_array($cat->id,$categoryparentids))
                            <li class="dropdown">
                                @if($lan == 'ar')
                                    <a href="{{ url($cat->link) }}" class="dropdown-toggle" role="button"
                                       aria-haspopup="true"
                                       aria-expanded="false">@if($lan == 'ar') {{ $cat->title_ar }} @else {{ $cat->title }} @endif  </a> @else
                                    <a href="{{ url($cat->link) }}" class="dropdown-toggle" role="button"
                                       aria-haspopup="true"
                                       aria-expanded="false">@if($lan == 'ar') {{ $cat->title_ar }} @else {{ $cat->title }} @endif</a> @endif
                                <ul class="dropdown-menu">
                                    <div class="col-md-3 nav-br">
                                        @foreach(App\Category::where('status',1)->where('parent',$cat->id)->get() as $keyk => $item) @if($lan == 'ar')
                                            <li>
                                                <a href="{{ url($item->link) }}">
                                                    <h4>@if($lan == 'ar') {{ $item->title_ar }} @else {{ $item->title }}@endif</h4>
                                                </a>
                                            </li>
                                            <li role="separator" class="divider"></li>
                                        @else
                                            <li>
                                                <a href="{{ url($item->link) }}">
                                                    <h4>@if($lan == 'ar') {{ $item->title_ar }} @else {{ $item->title }}@endif</h4>
                                                </a>
                                            </li>
                                            <li role="separator" class="divider"></li>
                                        @endif @endforeach

                                    </div>
                                    {{--
                                    <div class="col-md-3 nav-br">
                                        @foreach(App\Category::where('status',1)->where('parent',$cat->id)->orderBy('id','DESC')->take(2)->get() as $item) @if($lan == 'ar')
                                        <li>
                                            <a href="{{ url($item->link) }}">
                                                <h4>{{ $item->title_ar }}</h4>
                                            </a>
                                        </li>
                                        <li role="separator" class="divider"></li>
                                        @else
                                        <li>
                                            <a href="{{ url($item->link) }}">
                                                <h4>{{ $item->title }}</h4>
                                            </a>
                                        </li>
                                        <li role="separator" class="divider"></li>
                                        @endif @endforeach
                                    </div> --}}
                                    <div class="col-md-3">
                                        <div class="brands">
                                            <ul class="nopadding">
                                                @foreach($catbrands as $catbra) @if($catbra->category_id == $cat->id && $catbra->Brand-> image != '')
                                                    <li>
                                                        <a href="{{ route('brandproducts',['id' => $catbra->Brand->id,'name' => $catbra->Brand->name]) }}"><img
                                                                    data-src="{{ URL::to('uploads/brands/source') }}/{{ $catbra->Brand->image }}"
                                                                    class="lozad" alt="{{ $catbra->Brand->name}}"></a>
                                                    </li>
                                                @endif @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3">
                                        <li><a href="#"><img src="images/10-School-EN.jpg" alt="..."></a></li>
                                        </div>-->
                                </ul>
                                <div class="back-ground"></div>
                            </li>
                        @else @if($lan == 'ar')
                                <li><a href="{{ url($cat->link) }}" role="button">{{ $cat->title_ar }} </a></li>
                            @else
                                <li><a href="{{ url($cat->link) }}" role="button">{{ $cat->title }} </a></li>
                            @endif @endif @endforeach
                        <li class="deals-desktop">
                            <a href="{{url('/offers')}}">
                                {{trans('site.deals')}}
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>
    </div>
</header>
@yield('content')
</div>
<div style="display: none;" class="foot scrolled">
    <a href="{{ $con -> ads_link }}"><img class="lozad"
                                          data-src="{{ url('uploads/configurationsite/resize200') }}/{{ $con->ads_image}}" alt="ads_image"/></a>
    <a class="footclose" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
</div>
<footer>
    <div class="row nomargin">
        <div class="container-fluid">
            <div class="col-xs-12 col-md-4">
                <div class="logo">
                    @if($lan == 'ar')
                        <a href="{{ url('/') }}"> <img class="img-responsive lozad"
                                                       data-src="{{ URL::to('resources/assets/front/img/logo-ar.png') }}">
                        </a>
                    @elseif($lan == 'en')
                        <a href="{{ url('/') }}"> <img class="img-responsive lozad"
                                                       data-src="{{ URL::to('resources/assets/front/img/logo-eng.png') }}">
                        </a>
                    @endif
                </div>
                <div class="about-us">
                    <p class="white justfay">@if(App::getLocale() == 'ar'){!! $con->short_description !!} @else {!! $con->short_description_en !!} @endif</p>
                </div>
            </div>
            <div class="col-xs-12 col-md-2 hidden-xs hidden-sm">
                <div class="title white ">
                    <h3 class="margintop">{{ trans('site.general_links') }}</h3>
                </div>
                <ul class="list-unstyled">
                    @foreach($categorynoparent as $cat)
                        <li><a class="white"
                               href="{{ url($cat->link) }}">@if(App::getLocale() == 'ar'){{ $cat->title_ar }}@else{{ $cat->title}}@endif</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-xs-12 col-md-2 hidden-xs hidden-sm">
                <div class="title white">
                    <h3 class="margintop">{{ trans('site.user') }}</h3>
                </div>
                <ul class="list-unstyled">
                    @if(Auth::check())
                        <li><a class="white" href="{{ url('change-account') }}">{{ trans('site.changeaccount') }}</a>
                        </li>
                        @if(Auth::user()->delivery)
                            <li><a class="white" href="{{ url('my-deliveries') }}">{{ trans('site.my_deliveries') }}</a>
                            </li>
                        @endif
                        <li><a class="white" href="{{ url('my-address') }}">{{ trans('site.myaddress') }}</a></li>
                        <li><a class="white" href="{{ url('my-orders/1') }}">{{ trans('site.orders') }}</a></li>
                        @if(Auth::user()->vendor)
                            <li><a class="white" href="{{ route('vendorproducts',['id' => Auth::user() -> id]) }}"
                                   target="_blank">{{ trans('site.vendorpanel') }}</a>
                            </li> @endif @if(Auth::user()->affilate)
                            <li><a class="white"
                                   href="{{ route('affilatepanelproducts',['id' => Auth::user() -> id]) }}"
                                   target="_blank">{{ trans('site.affilatepanel') }}</a>
                            </li> @endif @if(Auth::user()->admin)
                            <li><a class="white" href="{{ url('admin') }}"
                                   target="_blank">{{ trans('site.controlpanel') }}</a></li> @endif @else
                        {{--<li><a class="white" href="{{ url('register') }}">{{ trans('site.register') }}</a></li>--}}
                        <li><a class="white" href="{{ url('login') }}">{{ trans('site.login') }}</a></li>
                    @endif
                </ul>
            </div>
            <div class="col-xs-12 col-md-3">
                <div class="title white hidden-xs">
                    <h3 class="margintop">{{ trans('site.contactus') }}</h3>
                </div>
            <!--  <div class="title white">
	                        <ul class="list-unstyled">
{{--	                            @foreach($pages as $page)--}}
            {{--                    <li><a class="white" href="{{ url('page/'.$page->id.'/'.$page->link) }}">{{ $page->title }}</a></li>--}}
            {{--@endforeach--}}
                    </ul>
                </div>  -->
                <div class="col-md-12 footer-a">
                    <a href="{{url('/contact-us')}}"> {{trans('site.contact_us')}} </a>
                </div>
                @foreach($pages as $page)
                    <div class="col-md-12 footer-a">
                        <a href="{{ url('page/'.$page->id.'/'.$page->link) }}">{{ $page->title }}</a>
                    </div>
                @endforeach
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-2 col-xs-2 footer-fa">
                            <a href="{{$configuration->facebook}}"> <i class="fa fa-facebook-square "></i> </a>
                        </div>
                        <div class="col-md-2  col-xs-2  footer-fa" style="    margin-right: -3px;">
                            <a href="{{$configuration->instgram}}"> <i class="fa fa-instagram"></i> </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-2 col-xs-2  footer-fa">
                            <span>    <i class="fa fa-handshake-o" aria-hidden="true"></i> </span>
                        </div>
                        <div class="col-md-2  col-xs-2  footer-fa">
                            <span>   <i class="fa fa-cc-visa" aria-hidden="true"></i> </span>
                        </div>
                        <div class="col-md-2  col-xs-2  footer-fa">
                            <span>   <i class="fa fa-cc-mastercard" aria-hidden="true"></i> </span>
                        </div>
                        <div class="col-md-2  col-xs-2  footer-fa">
                            <span>   <img style="width: 50px; height: 27px;margin-top: -6px" class="lozad"
                                          data-src="{{ URL::to('resources/assets/front/img/accept.jpg') }}"/> </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 bottom">
        <div class="container text-center">
            <address>© {{date('Y')}} baby and mums - Online Baby Shop . All Rights Reserved.</address>
        </div>
    </div>
</footer>
<div class="modal fade" id="myModalcart" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('site.cart') }}</h4>
            </div>
            <div class="modal-body">
                {{ trans('site.msg_cart') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('site.con_shop') }}</button>
                <a href="{{ url('my-cart') }}">
                    <button type="button" class="btn btn-primary">{{ trans('site.cart') }}</button>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalfav" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('site.favorite') }}</h4>
            </div>
            <div class="modal-body">
                {{ trans('site.msg_fav') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('site.close') }}</button>
                <a href="{{ url('my-favorite') }}">
                    <button type="button" class="btn btn-primary">{{ trans('site.favorite') }}</button>
                </a>
            </div>
        </div>
    </div>
</div>
{{--<script src="{{ URL::to('resources/assets/front/js/jquery-3.2.1.min.js') }}"></script>--}}
<script src="{{ URL::to('resources/assets/front/js/jquery-1.12.4.js') }}"></script>
<script defer src="{{ URL::to('resources/assets/front/js/sidebar.js') }}"></script>
<script src="{{ URL::to('resources/assets/back/js/jquery.dataTables.min.js') }}"></script>
<script defer src="{{ URL::to('resources/assets/front/js/owl.carousel.min.js') }}"></script>
<script defer src="{{ URL::to('resources/assets/front/js/bootstrap.min.js') }}"></script>
<script defer src="{{ URL::to('resources/assets/front/js/bootstrap-select.min.js') }}"></script>
<script defer src="{{ URL::to('resources/assets/front/js/parallax.min.js') }}"></script>
<script defer src="{{ URL::to('resources/assets/back/js/select2.full.min.js') }}"></script>
<script defer src="{{ URL::to('resources/assets/front/js/dropzone.min.js') }}"></script>
<script defer src="{{ URL::to('resources/assets/front/js/elevateZoom.min.js') }}"></script>
<script defer src="{{ URL::to('resources/assets/front/js/moment.min.js') }}"></script>
<script defer src="{{ URL::to('resources/assets/front/js/bootstrap-datetimepicker.min.js') }}"></script>
<script defer src="{{ URL::to('resources/assets/front/js/custom.js') }}"></script>

<script defer src="{{ URL::to('resources/assets/back/js/tinymce.min.js') }}"></script>
<script defer src="{{ URL::to('resources/assets/front/js/jquery-ui.js') }}"></script>
<script defer src="{{ URL::to('resources/assets/front/js/lozad.min.js') }}"></script>
@yield('script')
<!-- WhatsHelp.io widget -->
<script defer type="text/javascript">
    var cache = {}, lastXhr;let search = '';let input;function Auto(item) {if ($(item).hasClass('ui-autocomplete')) {$(item).autocomplete("destroy");}input = $(item);$(item).autocomplete({source: function (request, response) {var term = request.term;if (term in cache) {response(cache[term]);return;}lastXhr = $.getJSON('{{url('/search-home/autocomplete2')}}', request, function (data, status, xhr) {search = (typeof data.data.search !== "undefined") ? data.data.search : '';data = data.data.sources;if (xhr === lastXhr) {response(data);}});}, create: function () {$(this).data('ui-autocomplete')._renderItem = function (ul, item) {if (typeof item.title !== "undefined") {if (search === '') {return $('<li>').append('<a style="font-size: 18px;" href="{{url('/')}}/' + item.link + '">' + item.title + '</a>').append('</li>').appendTo(ul);} else {return $('<li>').append(search + '<a style="color: blue;" href="{{url('/')}}/' + item.link + '?search=' + input.val() + '">' + ' ' + item.title + '</a>').append('</li>').appendTo(ul);}} else if (typeof item.name !== "undefined") {return $('<li>').append('<a href="{{url('/search-home?searchtext=')}}' + item.name + '">' + item.name + '</a>').append('</li>').appendTo(ul);}};}});}(function () {$('#searchtext').on('keyup', function () {Auto(this, []);let search = $(this).val();Auto(this, response.data.sources);});$('#searchtext1').on('keyup', function () {Auto(this);});})();
</script>
<!-- /WhatsHelp.io widget -->
<script >

    function loadStyleSheet(src) {
        if(document.createStyleSheet) {
            document.createStyleSheet(src);
        }
        else {
            var styles = "@import url('" +src+ "');";
            var newSS=document.createElement('link');
            newSS.rel='stylesheet';
            newSS.href='data:text/css,'+ styles;
            document.getElementsByTagName("head")[0].appendChild(newSS);
        }
    }
    $(function () {const observer = lozad();observer.observe();
    loadStyleSheet('{{ URL::to('resources/assets/back/css/select2.min.css') }}');
    {{--@if(App::isLocale('ar'))--}}
        {{--loadStyleSheet('{{ URL::to('resources/assets/front/css/bootstrap.rtl.full.min.css') }}');--}}
    {{--@else--}}
        {{--loadStyleSheet('{{ URL::to('resources/assets/front/css/bootstrap.css') }}');--}}
    {{--@endif--}}
    loadStyleSheet('{{ URL::to('resources/assets/front/css/owl.carousel.css') }}');
    loadStyleSheet('{{ URL::to('resources/assets/front/css/style.css') }}');
    @if(App::isLocale('ar'))
    loadStyleSheet('{{ URL::to('resources/assets/front/css/rtl.css') }}');
    @endif

    });
    function setCookie(name, value, hours) {var expires = "";if (hours) {var date = new Date();date.setTime(date.getTime() + (hours * 30 * 60 * 1000));expires = "; expires=" + date.toUTCString();}document.cookie = name + "=" + (value || "") + expires + "; path=/";}function getCookie(name) {var nameEQ = name + "=";var ca = document.cookie.split(';');for (var i = 0; i < ca.length; i++) {var c = ca[i];while (c.charAt(0) == ' ') c = c.substring(1, c.length);if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);}return null;}if (getCookie('footer_banner') == 1) {$('.foot').css('display', 'none');} else {$('.foot').css('display', 'block');}$('.footclose').on('click', function () {setCookie('footer_banner', 1, 1);});$('.wishbtn').on('click', function () {var product_id = $(this).data('id');var btn = $(this);$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});$.ajax({url: " {{url('add/favorite')}}", method: 'POST', data: {product_id: product_id}, success: function (data) {btn.remove();}});});(function () {$('.newdatatable').DataTable();})();$('#myModalcart').on('click', function () {});
    $(function () {tinymce.init({
        mode: "specific_textareas",
        mode: "textareas",
        editor_selector: "area1",
        height: 500,
        fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
        theme: 'modern',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools jbimages'
        ],
        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages',
        toolbar2: 'ltr rtl | print preview media | forecolor backcolor emoticons | fontsizeselect',
        //image_advtab: true,
        templates: [{
            title: 'Test template 1',
            content: 'Test 1'
        },
            {
                title: 'Test template 2',
                content: 'Test 2'
            }
        ],
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css'
        ]
    });});
    $(".btn-hamburger").on("click", function () {$("#menu").css({"display": "block", "z-index": "1"});$(this).toggleClass("openmenu");$(".openmenu").on("click", function () {$("#menu").css({"display": "block", "z-index": "-1"});});});
    $('.changecurrency').on('click', function () {var code = $(this).data('code');var co = $(this);$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});$.ajax({url: " {{url('changecurrency')}}", method: 'POST', data: {code: code}, success: function (data) {location.reload();}});});
    $('.myfavorite').on('click', function () {var id = $(this).data('id');$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});$.ajax({url: " {{url('addfavorite')}}", method: 'POST', data: {id: id}, success: function (data) {}});});
    $('.removecart').on('click', function () {var id = $(this).data('id');$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});$.ajax({url: " {{url('removecart')}}", method: 'POST', data: {id: id}, success: function (data) {$('#tr_' + id + '').parent('div').css('display', 'none');$('#tr_' + id + '').parent('div').fadeOut('slow');$('#cart-co').html(data);$('.tr_' + id + '').parent('div').css('display', 'none');$('.tr_' + id + '').parent('div').fadeOut('slow');$('.tr_' + id + '').html('');if (data == 0) {$('.cart-shop').html('<li>The shopping cart is empty !!</li>');location.reload();}var count = parseInt($('#cart-con').html());count -= 1;$('#cart-con').html(count);$('#cart-con2').html(count);$('#cartCount').html('(' + count + ')');var lis = $('.totalproducts');var total = 0;for (i = 0; i < lis.length; i++) {console.log(lis[i]);if ($(lis[i]).parent('ul').parent('div').parent('div').parent('.tr_' + id + '').attr('class') === undefined) {var num = $.trim(lis[i].innerText);num = parseFloat(num.substr(0, num.indexOf(" ")).replace(",", ""));total += num;}}$('#display_total').html(total + ' {{Session::get('currencysymbol')}}');}});});
    $('.removecartsession').on('click', function () {var id = $(this).data('id');$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});$.ajax({url: " {{url('removecartsession')}}", method: 'POST', data: {id: id}, success: function (data) {$('#tr_' + id + '').parent('div').css('display', 'none');$('#tr_' + id + '').parent('div').fadeOut('slow');$('#cart-co').html(data);$('.tr_' + id + '').parent('div').css('display', 'none');$('.tr_' + id + '').parent('div').fadeOut('slow');$('.tr_' + id + '').html('');if (data == 0) {$('.cart-shop').html('<li>The shopping cart is empty !!</li>');}var count = parseInt($('#cart-con').html());count -= 1;$('#cart-con').html(count);$('#cart-con2').html(count);$('#cartCount').html('(' + count + ')');var lis = $('.totalproducts');var total = 0;for (i = 0; i < lis.length; i++) {if ($(lis[i]).parent('ul').parent('div').parent('div').parent('.tr_' + id + '').attr('class') === undefined) {var num = $.trim(lis[i].innerText);num = parseFloat(num.substr(0, num.indexOf(" ")).replace(",", ""));total += num;console.log(num);}}$('#display_total').html(total + ' {{Session::get('currencysymbol')}}');}});});
    $('.mycart').on('click', function () {var id = $(this).data('id');var count = $(this).data('count');$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});$.ajax({url: " {{url('addcart')}}", method: 'POST', data: {id: id, count: count}, success: function (data) {$('.reloadcart').html(data);setTimeout(function () {$('#myModalcart').modal('hide');}, 3000);var count = parseInt($('#cart-con').html());count += 1;$('#cart-con').html(count);$('#cart-con2').html(count);}});});
</script>
{{--// var options = {--}}
{{--//     facebook: "204903766659994", // Facebook page ID--}}
{{--//     call_to_action: "تواصل معنا", // Call to action--}}
{{--//     position: "right", // Position may be 'right' or 'left'--}}
{{--// };--}}
{{--// var proto = document.location.protocol,--}}
{{--//     host = "whatshelp.io",--}}
{{--//     url = proto + "//static." + host;--}}
{{--// var s = document.createElement('script');--}}
{{--// s.type = 'text/javascript';--}}
{{--// s.async = true;--}}
{{--// s.src = url + '/widget-send-button/js/init.js';--}}
{{--// s.onload = function () {--}}
{{--//     WhWidgetSendButton.init(host, proto, options);--}}
{{--// };--}}
{{--// var x = document.getElementsByTagName('script')[0];--}}
{{--// x.parentNode.insertBefore(s, x);--}}
{!! $con->footer_code !!}

<!-- Statcounter code -->
<script type="text/javascript">
    var sc_project=12211114;
    var sc_invisible=0;
    var sc_security="31796872";
    var scJsHost = "https://";
    document.write("<sc"+"ript type='text/javascript' src='" +
        scJsHost+
        "statcounter.com/counter/counter.js'></"+"script>");
</script>
<noscript><div class="statcounter"><a title="Web Analytics"
                                      href="https://statcounter.com/" target="_blank"><img
                    class="statcounter"
                    src="https://c.statcounter.com/12211114/0/31796872/0/"
                    alt="Web Analytics"></a></div></noscript>
<!-- /Statcounter Code -->
</body>
</html>