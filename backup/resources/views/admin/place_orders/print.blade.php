<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Baby Mumz</title>
    <!--Favicon-->
    <link rel="shortcut icon" href="">
    <!--Bootstrap Minified-->
    <link rel="stylesheet" href="{{ URL::to('resources/assets/print/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ URL::to('resources/assets/print/css/bootstrap-rtl.min.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Righteous&display=swap" rel="stylesheet">
    <!--FontAwesome Styles-->
    <link rel="stylesheet" href="{{ URL::to('resources/assets/print/css/all.css')}}">
    <!--Animate.css-->
    <link rel="stylesheet" href="{{ URL::to('resources/assets/print/css/animate.min.css')}}">
    <!--Scroll-->
    <link rel="stylesheet" href="{{ URL::to('resources/assets/print/css/jquery.mCustomScrollbar.min.css')}}">
    <!--Owl Carousel-->
    <link rel="stylesheet" href="{{ URL::to('resources/assets/print/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{ URL::to('resources/assets/print/css/owl.theme.default.min.css')}}">
    <!--Main Style-->
    <link rel="stylesheet" href="{{ URL::to('resources/assets/print/css/style.css')}}">
    <!--For English-->
{{--    <!--<link rel="stylesheet" href="{{ URL::to('resources/assets/print/css/style-en.css')}}">-->--}}
<!--Media Style-->
    <link rel="stylesheet" href="{{ URL::to('resources/assets/print/css/media.css')}}">
    <!--[if lt IE 9]>
    <script src="{{ URL::to('resources/assets/print/js/vendor/html5-3.6-respond-1.4.2.min.js')}}"></script>
    <![endif]-->

</head>

<body class="preload">
<!--Start Preloader Section-->
<div class="screen">
    <div class="loader">
        <!--Preloader HTML-->
    </div>
</div>
<div id="print" class="paper-wrapper">
    <!--        <div class="container">-->
    <div class=" r-row logo">
        <div class=" text-center d-block">
            <h4>Baby</h4>
            <div class="img-cont">
                <img width="60" src="{{ URL::to('resources/assets/print/imgs/logo%20png.png')}}" alt="">
            </div>
            <h4>Mummz</h4>
        </div>
    </div>
    <div class="row r-row">
        <div class="col-xs-4 ">
            <h5>
                Reciet:
                @if(intval($order[0]->paymethod) === 1)
                    cash
                @else
                    visa
                @endif
            </h5>
        </div>
        <div class=" col-xs-8 ">
            <h5>Cacher: {{\App\User::where(['id' => $order[0]->admin_id])->first()['name']}}</h5>
        </div>
    </div>
    <div class="row r-row">
        <div class="col-sm-4 col-xs-4">
            <h5>Date: {{date('y/m/d' , strtotime($order[0]->created_at))}}</h5>
        </div>
        <div class="col-sm-4 col-xs-4">
            <h5>Order: {{$order[0]->number}}</h5>
        </div>
    </div>
    <div class="row r-row">
        <div class="col-sm-4 col-xs-4">
            <h5>Time: {{date('h:i a' , strtotime($order[0]->created_at))}}</h5>
        </div>

    </div>


    <div class="row r-row">
        <div class="col-sm-3 col-xs-3 ">
            <h3>Product</h3>
        </div>
        <div class="col-sm-3 col-xs-3">
            <h3>Price</h3>
        </div>
        <div class="col-sm-3 col-xs-3">
            <h3>Quantity</h3>
        </div>
        <div class="col-sm-3 col-xs-3">
            <h3>Total</h3>
        </div>
    </div>
    <hr>
    @foreach($order as $x)
        @php
            $product = \App\Product::where(['id' => $x->product_id])->first();
        @endphp
        <div class="row r-row">
            <div class="col-sm-12 col-xs-12">
                <h4>{{$product->title_ar}}</h4>
            </div>

        </div>
        <div class="row r-row">
            <div class="col-sm-3 col-xs-3">
                <h5>{{$product->code}}</h5>
            </div>
            <div class="col-sm-3 col-xs-3">
                <h6>
                    @if($product->discount !== 0)
                        {{$product->price - $product->discount}}
                    @else
                        {{$product->price}}
                    @endif
                </h6>
            </div>
            <div class="col-sm-3 col-xs-3">
                <h6>{{$x->quantity}}</h6>
            </div>
            <div class="col-sm-3 col-xs-3">
                <h6>
                    @if($product->discount !== 0)
                        {{ ( $product->price - $product->discount ) * $x->quantity }}
                    @else
                        {{$product->price * $x->quantity}}
                    @endif
                </h6>
            </div>
        </div>
        <hr>
    @endforeach

    <div class="row r-row">
        <div class="col-xs-12">
            <h3>Discount: {{$order[0]->discount}}</h3>
        </div>
        <div class="col-xs-12">
            <h3>Total: {{$order[0]->final_total}}</h3>
        </div>
    </div>

    <div class="row r-row">
        <div class="col-sm-12 text-center">
            <h4>Web: www.babymumz.com</h4>
        </div>
    </div>
    <div class="row r-row">
        <div class="col-sm-12 text-center">
            <p>
                Tel:0244062346 Fb:bm.babymums- Inet:baby:mumz
            </p>
            <p>
                Address: 44 Salah Eldin Square - Masr Al Jadidah
            </p>
            <p>
                الارتجاع خلال 14 يوم بشرط عدم فتح او استخدام المنتج
            </p>

        </div>
    </div>
    <!--        </div>-->
</div>


<!--Jquery Minified JS-->
<script src="{{ URL::to('resources/assets/print/js/jquery-1.12.4.min.js')}}"></script>
{{--<!--Owl Carousel JS-->--}}
{{--<script src="{{ URL::to('resources/assets/print/js/owl.carousel.min.js')}}"></script>--}}

{{--<script src="{{ URL::to('resources/assets/print/js/wow.min.js')}}"></script>--}}
{{--<!--Bootstrap Minified JS-->--}}
{{--<script src="{{ URL::to('resources/assets/print/js/bootstrap.min.js')}}"></script>--}}
{{--<!--ScrollBar-->--}}
{{--<script src="{{ URL::to('resources/assets/print/js/jquery-smoothscroll.js')}}"></script>--}}
{{--<script src="{{ URL::to('resources/assets/print/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>--}}
{{--<!--Main Script-->--}}
{{--<script src="{{ URL::to('resources/assets/print/js/script.js')}}"></script>--}}
{{--<script src="{{ URL::to('resources/assets/back/js/printThis.js') }}"></script>--}}
<script>
    $(function () {

        window.print();
        // window.close();
    })
</script>


</body>

</html>
