@php $totalproducts = 0; @endphp
@foreach($cart1 as $item)

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
@endforeach



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
    $('.removecart').on('click', function () {
        var id = $(this).data('id');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: " {{url('removecart')}}",
            method: 'POST',
            data: {id: id},
            success: function (data) {
                $('#tr_' + id + '').css('background-color', '#ccc');
                $('#tr_' + id + '').fadeOut('slow');
                $('#cart-co').html(data);
                $('.tr_' + id + '').css('background-color', '#ccc');
                $('.tr_' + id + '').fadeOut('slow');
                if (data == 0) {
                    $('.cart-shop').html('<li>The shopping cart is empty !!</li>');
                }
            }
        });


    });
    $('.removecartsession').on('click', function () {
        var id = $(this).data('id');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: " {{url('removecartsession')}}",
            method: 'POST',
            data: {id: id},
            success: function (data) {
                $('#tr_' + id + '').css('background-color', '#ccc');
                $('#tr_' + id + '').fadeOut('slow');
                $('#cart-co').html(data);
                $('.tr_' + id + '').css('background-color', '#ccc');
                $('.tr_' + id + '').fadeOut('slow');
                if (data == 0) {
                    $('.cart-shop').html('<li>The shopping cart is empty !!</li>');
                }
            }
        });
    });
</script>                