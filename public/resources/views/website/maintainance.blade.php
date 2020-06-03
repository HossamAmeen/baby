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
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-PGNDRHS');</script>
    <!-- End Google Tag Manager -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"> @yield('meta') @yield('title')
    <link href="https://fonts.googleapis.com/css?family=Cairo" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Changa" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/lightbox.min.css') }}"/>
    <link href="https://fonts.googleapis.com/css?family=Lemonada" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/select2.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/jquery.dataTables.css') }}"/>
    <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/dropzone.min.css') }}"/>
    <link rel="stylesheet"
          href="{{ URL::to('resources/assets/front/css/bootstrap-datetimepicker.min.css') }}"/> @if(App::isLocale('ar'))
        <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/bootstrap.rtl.full.min.css') }}"> @else
        <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/bootstrap.css') }}"> @endif
    <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/owl.theme.css') }}">
    <link href="{{ URL::to('resources/assets/front/css/slide.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/dropzone.min.css') }}">

    <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/style.css') }}"> @if(App::isLocale('ar'))


        <link rel="stylesheet" href="{{ URL::to('resources/assets/front/css/rtl.css') }}"> @endif @yield('style')
    <style>
        .slideout-menu {
            left: auto;
        }

        .btn-hamburger {
            left: auto;
            right: 12px;
        }

        .box {
            height: 1500px;
        }
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
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=682963885454030&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->
    <script id="mcjs">!function (c, h, i, m, p) {
            m = c.createElement(h), p = c.getElementsByTagName(h)[0], m.async = 1, m.src = i, p.parentNode.insertBefore(m, p)
        }(document, "script", "https://chimpstatic.com/mcjs-connected/js/users/7791969a25860dbe8fe29c850/da204f7ee0ce2c4e56abd28f4.js");</script>
    <script id="mcjs">!function (c, h, i, m, p) {
            m = c.createElement(h), p = c.getElementsByTagName(h)[0], m.async = 1, m.src = i, p.parentNode.insertBefore(m, p)
        }(document, "script", "https://chimpstatic.com/mcjs-connected/js/users/c17092699b6575d7b5dc41e3b/3c6a48c606036f7473e090de7.js");</script>
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
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PGNDRHS"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
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
<div class="container">
    <div class="row ">
        <div class="col-xs-7 text-center">
            <a href="{{ url('/') }}">
                <p class="logo-p-phone"> Baby & Mumuz</p>
            </a>
        </div>
    </div>
    <div class="row">
        <h3>
            تم الآن إيقاف العمل بالموقع للصيانة وسيتم العمل مرة أخرى فى خلال ساعات
        </h3><br/>
        <h3>
            تحياتنا لكم جميعا
        </h3>
    </div>
</div>
<div style="background-color: #6fc9be; height: 100%">

</div>
<footer style="    display: block;
    position: absolute;
    padding: unset;
    left: 0;
    bottom: 0;
    width: 100%;">
    <div class="col-xs-12 bottom">
        <div class="container text-center">
            <address>© {{date('Y')}} baby and mums - Online Baby Shop . All Rights Reserved.</address>
        </div>
    </div>
</footer>
<script src="{{ URL::to('resources/assets/front/js/jquery-3.2.1.min.js') }}"></script>

</body>
</html>