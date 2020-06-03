@extends('layouts.app')
@section('meta')
    <title>{{trans('site.cart')}}</title>
@endsection
@section('content')
    <section id="shopping-cart">
        <div class="container">
            <div class="row">

                @if(count($cart)>0)
                    <div class="col-xs-12">
                        <div class="title"><h3><strong>{{trans('site.cart')}} <span id="cartCount">({{count($cart)}}
                                        )</span></strong></h3></div>
                    </div>

                    <div class="col-xs-12">
                        <div class="row">
                            <div class="cart-item hidden-xs hidden-sm">
                                <div class=" col-md-offset-2 col-md-2">{{ trans('site.product_name') }}</div>
                                <div class=" col-md-offset-2 col-md-2">{{ trans('site.unit_price') }}</div>
                                <div class=" col-md-1">{{ trans('site.quantity') }}</div>
                                <div class=" col-md-2 center">{{ trans('site.total') }}</div>
                            </div>
                            <!----- lamiaa ----->
                            @php $totalproducts = 0; @endphp

                            @foreach($cart as $item)
                                <div class="row">
                                    <div class="col-md-12 my-cart-block tr_{{ $item->id }}">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                            <img class="img-responsive"
                                                 src="{{ url('uploads/product/resize200') }}/{{ $item->Product->image }}">
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-3 col-xs-8">

                                            <a @if($lan == 'ar') href="{{ url($item->Product->link) }}" @else href="{{ url($item->Product->link_en) }}" @endif>
                                                <p class="p-desc-cart">
                                                    @if($lan == 'ar'){{ $item->Product->title_ar }} @else {{ $item->Product->title }} @endif
                                                </p>
                                            </a>
                                            <p class="p2-desc-cart">
                                                <ul class="cart-list list-unstyled">
                                                    <li>{{ trans('site.brand') }} :{{ $item->Product->brand->name }}</li>
                                                    <li>
                                                        @if(Auth::check())
                                                            @foreach($optioncart as $item2)
                                                                @if($item2->cart_id == $item->id)
                                                                    <li>{{ $item2->Option->option }}</li>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <?php $radioids = array_filter(explode(" ", $item->optionradio));

                                                            $checkids = array_filter(explode(" ", $item->optioncheck));
                                                            ?>
                                                            @foreach($options as $item3)

                                                                @if($radioids && in_array($item3->id,$radioids))
                                                                    <li>{{ $item->OptionRadio->option }}</li>
                                                                @endif
                                                                @if($checkids && in_array($item3->id,$checkids))
                                                                    <li>{{ $item->OptionCheck->option }}</li>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </li>
                                                </ul>
                                            </p>
                                        </div>
                                        <div class="col-lg-2 col-md-2  col-sm-2 col-xs-6">

                                            <p class="p-price-cart p-price-cart2">
                                                @php
                                                    if($item->Product->discount){
                                                    $totalpro = number_format(($item->Product->price - $item->Product->discount)* Session::get('currencychange'),2);
                                                    echo $totalpro;
                                                    }
                                                    else{
                                                    $totalpro = number_format($item->Product->price* Session::get('currencychange'),2);
                                                    echo $totalpro;
                                                    }
                                                @endphp
                                                {{ Session::get('currencysymbol') }}
                                            </p>
                                        </div>
                                        <div class="col-lg-1 col-md-1  col-sm-2  col-xs-6">
                                            <div class="form-group">
                                                <select class="form-control textinput" name="quantity_{{ $item->id }}"
                                                        id="quantity_{{ $item->id }}" data-id="{{ $item->id }}">
                                                    @php
                                                        for($i=1;$i<=$item->Product->stock;$i++){
                                                    @endphp
                                                    <option value="{{ $i }}" {{($item->count == $i)?'selected':''}}>{{ $i }}</option>
                                                    @php
                                                        }
                                                    @endphp
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2  col-xs-6">
                                            @if($item->coupon_price)
                                                <div class="pr-info">{{ trans('site.coupon_price') }} : <span
                                                            class="coupon_{{ $item->id }}">{{ $item->coupon_price }}</span>
                                                </div>
                                            @endif
                                            <p class="@if($item->coupon_price) p2-price-cart @else p-price-cart @endif center price-cart-mobile totalproducts">
                                                @if($item->coupon_price)
                                                    @php $xx = 0; @endphp
                                                    @if($item->Product->discount) @if(($xx=(((($item->Product->price - $item->Product->discount) + $item->optionprice ) *  $item->count) - $item->coupon_price)* Session::get('currencychange')) > 0) {{ number_format($xx,2) }} @php $totalproducts += $xx; @endphp @else
                                                        0  @endif
                                                    @else  @if($xx=((($item->Product->price + $item->optionprice) * $item->count) - $item->coupon_price)* Session::get('currencychange') > 0) {{ number_format($xx,2) }} @php $totalproducts += $xx; @endphp @else
                                                        0  @endif
                                                    @endif {{ Session::get('currencysymbol') }}
                                                @else
                                                    @if($item->Product->discount) @if(($xx=((($item->Product->price - $item->Product->discount) + $item->optionprice ) *  $item->count)* Session::get('currencychange')) > 0) {{ number_format($xx,2) }} @php $totalproducts += $xx; @endphp @else
                                                        0  @endif
                                                    @else @if(($xx=(($item->Product->price + $item->optionprice) * $item->count)* Session::get('currencychange')) > 0) {{ number_format($xx,2) }} @php $totalproducts += $xx; @endphp @else
                                                        0  @endif
                                                    @endif {{ Session::get('currencysymbol') }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-lg-1 col-md-1  col-sm-1 col-xs-6">
                                            {{--<button type="button" data-toggle="tooltip" title="" class="btn  removecart mg-top" data-id="191" data-original-title="Remove"><i class="fa fa-trash fa-2x" aria-hidden="true"></i></button>--}}
                                            @if(Auth::check())
                                                <button type="button" data-toggle="tooltip" title=""
                                                        class="btn  removecart mg-top" data-id="{{ $item->id }}"
                                                        data-original-title="Remove"><i class="fa fa-trash fa-2x"
                                                                                        aria-hidden="true"></i></button>
                                            @else
                                                <button type="button" data-toggle="tooltip" title=""
                                                        class="btn  removecartsession mg-top" data-id="{{ $item->id }}"
                                                        data-original-title="Remove"><i class="fa fa-trash fa-2x"
                                                                                        aria-hidden="true"></i></button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        <!----- lamiaa ----->


                        </div>

                    </div>







                    @if(\Session::has('success'))
                        <div class="alert alert-success alert-block col-xs-12">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong class="text-center">{{ session()->pull('success', 'default') }}</strong>
                        </div>
                    @endif

                    @if(\Session::has('error'))
                        <div class="alert alert-danger alert-block col-xs-12">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong class="text-center">{{ session()->pull('error', 'default') }}</strong>
                        </div>
                    @endif
                    @if(Auth::check())
                        {!! Form::open(['url' => 'addcoupon', 'data-toggle'=>'validator']) !!}
                        <label class=" control-label">{{ trans('site.placecoupon') }}</label>
                        <div class="input-group">
                            <input type="hidden" value="{{ $totalproducts }}" name="total"/>
                            <input type="text" name="coupon" placeholder="{{ trans('site.placecoupon') }}"
                                   id="input-coupon" class="form-control" required>
                            <span class="input-group-btn">
						          	<button style="background-color: #eee;" type="submit" class="btn btn-default">{{ trans('site.add_coupon') }}</button>
						                </span>
                        </div>
                        {!! Form::close() !!}
                    @else
                        <span>{{trans('site.please_login')}}</span>
                    @endif


                    <div class="couponmsg"></div>


                    <div class="col-xs-12 col-md-4 nopadding"><h3>{{ trans('site.total') }} : <span
                                    id="display_total">{{ number_format($totalproducts - session('coupon_price',0),2) }} {{ Session::get('currencysymbol') }} </span>
                        </h3></div>
                    <div class="col-xs-12 col-md-offset-4 col-md-4 nopadding"><a href="{{ url('continue-order') }}">
                            <button class="btn btn-success btn-block check">
                                <strong>{{ trans('site.continue_shopping') }}</strong></button>
                        </a></div>
                @else
                    <div class="col-xs-12">

                        <div class="row text-center">
                            <div class="img "><img class="img-responsive shopping-img"
                                                   src="{{ url('/resources/assets/front/img/cartEmpty.png') }}"></div>
                            <h4>{{ trans('site.the_cart_empty') }}</h4>
                            <a href="{{ url('/') }}" class="btn btn-success">اكمل التسوق</a>
                        </div>
                    </div>
                @endif

            </div>


        </div>
    </section>




@endsection
@section('script')
    <script src="{{ URL::to('resources/assets/front/js/jquery.number.min.js')}}"></script>
    <script type="text/javascript">
        function increase(id, maxqu) {
            var x = parseInt(document.getElementById("quantity" + id).value);
            if ((x >= 1) && (x < maxqu)) {
                x++;
                document.getElementById("quantity" + id).value = x;
            }

        }

        function decrease(id, maxqu) {
            var x = parseInt(document.getElementById("quantity" + id).value);
            if ((x > 1) && (x <= maxqu)) {
                x--;
                document.getElementById("quantity" + id).value = x;
            }
        }

    </script>
    <script>
        $('.textinput').on('change', function () {
            var id = $(this).data('id');
            var quantity = $('#quantity_' + id + ' option:selected').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: " {{url('updatecart')}}",
                method: 'POST',
                data: {id: id, quantity: quantity},
                success: function (data) {
                    $('.tr_' + id).html('');
                    $('.tr_' + id).html(data);
                    $('.counttop_' + id).html('x' + quantity);

                    var lis = $('.totalproducts');
                    var total = 0;
                    for (i = 0; i < lis.length; i++) {
                        var num = $.trim(lis[i].innerText);
                        num = parseFloat(num.substr(0, num.indexOf(" ")).replace(",", ""));
                        total += num;
                    }

                    $('#display_total').html($.number(total,2) + ' {{Session::get('currencysymbol')}}');


                }
            });


        });

    </script>
@endsection   