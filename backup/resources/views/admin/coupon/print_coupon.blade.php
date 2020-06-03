@extends('layouts.admin')

@section('content')

    <div id="print2">
        <div class="row">
            @foreach($coupons as $coupon)
                <div class="col-md-12 coupon-block">
                    <div class="col-md-12 coupon">
                        <div class="title-coupon text-center">
                            <h2> VOUCHER</h2>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-7 col-sm-8 col-xs-7" style="    margin-top: 57px;
">
                            <div class=" row coupon-details">
                                <table class="table " style="margin-bottom: 0px; margin-top:1px;">


                                    <tbody>
                                    <tr>
                                        <td><p><span>{{trans('home.voucher_name')}} : </span></p></td>
                                        <td><p> {{$coupon->name}} </p></td>
                                        <td><p><span> {{trans('home.voucher_code')}} : </span></p></td>
                                        <td><p> {{$coupon->code}}</p></td>
                                    </tr>
                                    <tr>
                                        <td><p><span> {{trans('home.voucher_date')}} : </span></p></td>
                                        <td><p> {{date('Y-m-d' , strtotime($coupon->created_at))}}</p></td>

                                        <td><p><span>{{trans('home.voucher_expire_date')}} : </span></p></td>
                                        <td><p> {{$coupon->expire_date}} </p></td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                            <div class="row">
                                <p class="coupon-p"> {{trans('home.voucher_buy_more')}}
                                    <span> {{$coupon->mini_order}} </span> {{trans('home.voucher_pound')}} </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4  col-xs-5">
                            <img class="img-coupon-logo" src="{{ URL::to('resources/assets/front/img/gift.png') }}">
                            @if($coupon->value > 0)
                                <p class="coupon-text"> {{$coupon->value}} </p>
                                <p class="coupon-text-2"> LE </p>
                            @else
                                <p class="coupon-text"> {{$coupon->percent}} </p>
                                <p class="coupon-text-2"> % </p>
                            @endif
                        </div>
                        <div class="row  coupon-p2-left">

                            <div class="col-md-4 col-sm-4 co-img-left">
                                <img class="img-responsive"
                                     src="{{ URL::to('resources/assets/front/img/co-logo.png') }}">
                            </div>
                            <div class="col-md-8 col-sm-8">
                            </div>
                        </div>
                        <div class="col-md-12 coupon-p2-left">
                            <p class="coupon-p2">
                                {{trans('home.vouchr_replacment_text')}}
                            </p>
                        </div>
                    </div>
                    <table class="table coupon-block-p" style="margin-bottom: 0px; margin-top:1px;">
                        <tbody>
                        <tr>
                            <td><p class="">
                                    <a href="#">
                                        <i class="fa fa-facebook-square" aria-hidden="true">
                                        </i>{{$setting->facebook}}
                                    </a>
                                </p></td>
                            <td><p class=""><a href="#"> <i class="fa fa-globe" aria-hidden="true"></i>https://babymumz.com
                                    </a>
                                </p></td>
                            <td>   @if(\Session::get('lang') == 'ar') {!! $setting->short_description !!} @else {!! $setting->short_description_en !!} @endif  </td>
                        </tr>
                        </tbody>
                    </table>


                    <img class="img-responsive img-flower" src="{{ URL::to('resources/assets/front/img/flower.png') }}">
                </div>
            @endforeach
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <button class="ui basic button" id="print">{{trans('home.print')}}</button>
        </div>
    </div>

@stop

@section('script')
    <script src="{{ URL::to('resources/assets/back/js/printThis.js') }}"></script>
    <script>
        $("#print").click(function () {
            $("#print2").printThis({
                importCSS: true,            // import page CSS
                importStyle: true,         // import style tags
                printContainer: true,
                printDelay: 333,
                header: null,
                formValues: true
            });
        });
    </script>

@stop