@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/select2.min.css') }}" />
@endsection
@section('content') 

<header>
                <section class="step-indicator">
                    <div class="step step1 active">
                        <div class="step-icon">1</div>
                        <p>{{ trans('site.delivery') }}</p>
                    </div>
                    <div class="indicator-line active"></div>
                    <div class="step step2">
                        <div class="step-icon">2</div>
                        <p>{{ trans('site.payment') }}</p>
                    </div>
                    <div class="indicator-line"></div>
                    <div class="step step3">
                        <div class="step-icon">3</div>
                        <p>{{ trans('site.confirmation') }}</p>
                    </div>
                </section>
       </header>
<section id="shipping-pay">
        <div class="container">
             
            <div class="row">
               
                <div class="col-xs-12 col-md-8 payment_methods">
                    <div class="n-add pull-left">
                        <a href="{{ url('addaddress') }}"><button class="btn btn-primary">{{ trans('site.new_address') }}</button></a>
                    </div>
                    <div class="clear-fix"></div>
                          
                    
                    <div class="shipping-info">
                        <select name="address" id="address_id" class="form-control">
                            @foreach($address as $k => $ad)
                                <option @if($k== 0) selected @endif  value="{{ $ad->id }}">{{ $ad->address }}</option>
                            @endforeach
                    
                        </select>
                    </div>
                    
                    <button class="btn btn-success pull-right delivery"><strong>{{trans('site.continue')}}</strong></button>
                
                </div>
                <div class="col-xs-12 col-md-4" id="sidediv" style="display: none;">
                    <h3><strong>{{ trans('site.cart') }}</strong></h3>
                    <div class="sh-cart">
                       @php $totalproducts = 0;
                       @endphp
                            <div class="row">
                            @foreach ($ordercart as $cart)
                             <div class="product">
                                <div class="col-xs-3"><div class="img"><img src="{{ url('uploads/product/resize200') }}/{{ $cart->Product->image }}" alt="..."></div></div>
                                <div class="col-xs-9">
                                    <div class="row">
                                        <div class="col-xs-12"> <div class="title"><h4 class="nomargin">@if($lan == 'ar'){{ $cart->Product->title_ar }} @else {{ $cart->Product->title }} @endif</h4></div></div>
                                        
                                        <div class="col-xs-12"> <div class="price">
                                            @if($cart->coupon_price)
                                            	@php $xx = 0; @endphp
                                                @if($cart->Product->discount) @if(($xx=(((($cart->Product->price - $cart->Product->discount) + $cart->optionprice ) *  $cart->count) - $cart->coupon_price)* Session::get('currencychange')) > 0) {{ $xx }} @else  0  @endif 
                                                @else @if(($xx=((($cart->Product->price + $cart->optionprice) * $cart->count) - $cart->coupon_price)* Session::get('currencychange'))  > 0) {{ $xx }} @else  0  @endif 
                                                @endif {{ Session::get('currencysymbol') }}
                                            @else
                                                @if($cart->Product->discount) @if(($xx=((($cart->Product->price - $cart->Product->discount) + $cart->optionprice ) *  $cart->count)* Session::get('currencychange')) > 0) {{ $xx }} @else  0  @endif 
                                                @else @if(($xx=(($cart->Product->price + $cart->optionprice) * $cart->count)* Session::get('currencychange'))  > 0) {{ $xx }} @else  0  @endif  
                                                @endif {{ Session::get('currencysymbol') }}
                                            @endif
                                            
                                            @php
                                            if($cart->coupon_price){
                                                if($cart->Product->discount){ 
                                                	$totalproducts += (((($cart->Product->price - $cart->Product->discount) + $cart->optionprice ) *  $cart->count) - $cart->coupon_price);
                                                }
                                                else {
                                                	$totalproducts += ((($cart->Product->price + $cart->optionprice) * $cart->count) - $cart->coupon_price);
                                                }
                                                Session::get('currencysymbol');
                                             }
                                             
                                            else{
                                                if($cart->Product->discount) {
                                                	$totalproducts +=((($cart->Product->price - $cart->Product->discount) + $cart->optionprice ) *  $cart->count);
                                                	}
                                                else {
                                                	$totalproducts += (($cart->Product->price + $cart->optionprice) * $cart->count);
                                                	} 
                                                Session::get('currencysymbol');
                                                }
                                            @endphp
                                        </div></div>
                                        <div class="col-xs-12"><div class="Qun"><h5>{{ trans('site.quantity') }}:{{$cart->count}}</h5></div></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="shipping-status">
                    	<h4 class="nomargin">
                            <span>{{trans('site.shipping_time')}}</span>
                            <span class="products-price pull-right">{{ $ad->region->shiping_time}} </span>
                        <hr />
                        </h4>
                    
                        <h4 class="nomargin">
                            <span>{{trans('site.total_products')}}</span>
                            <span class="products-price pull-right">{{ $totalproducts* Session::get('currencychange') }} </span><span class="currency pull-right">{{ Session::get('currencysymbol') }}</span>
                        <hr />
                        </h4>
                        
                        @if(\Session::has('coupon_price'))
                        <h4 class="nomargin">
                            <span>{{trans('site.coupon_price')}}</span>
                            <span class="products-price pull-right"> - {{ session('coupon_price') }} </span><span class="currency pull-right"> {{ Session::get('currencysymbol') }}</span>
                        </h4>
                        <hr />
                        @endif
                        
                        
                        
                        <h4 class="nomargin" id="shipping_price" style="display: none;">
                            <span>{{trans('site.shipping_price')}}</span>
                            <span class="shipping-price pull-right">0 </span><span class="currency pull-right"> {{ Session::get('currencysymbol') }}</span>
                        <hr />
                        </h4>
                         
                        <h4 class="nomargin" id="payment_type" style="display: none;">
                            <span>{{trans('site.payment_price')}}</span>
                            <span class="payment-type pull-right">0 </span><span class="currency pull-right"> {{ Session::get('currencysymbol') }}</span>
                        </h4> 
                    </div>
                    
                    <div class="total-price" id="total_price" style="display: none;">
                        <h4 class="nomargin"><strong> {{ trans('site.total') }} </strong><span class="tlt-price pull-right">20 </span><span class="currency pull-right"> {{ Session::get('currencysymbol') }}</span></h4>
                    </div>
                  
                </div>
           
            </div>
            
        </div>
        </section>


    {{--  
    <div class="buttons clearfix">
        {{--  <div class="pull-right"><a href="{{ url('order-details') }}" class="btn btn-primary">Continue Shopping</a></div>  
        <div class="pull-right"><a href="{{ url('order-details') }}" class="btn btn-primary">{{ trans('site.continue_order') }} </a></div>
    </div>  --}}
@endsection
@section('script')
<script src="{{ URL::to('resources/assets/back/js/select2.full.min.js') }}"></script>
<script>
$('#address_id').select2({
  'placeholder' : 'أختار العنوان',
});

$('.ad-btn').on('click', function(){
     $(".pay").attr('id',"collapse-payment-method");
     $(".pay").attr('aria-expanded',"true");
     $(".pay").addClass('in');
});
$('.delivery').on('click', function(){
    $(".step2").addClass('active');
    $(".indicator-line:first").removeClass('active');
    $(".indicator-line:last").addClass('active');
    $(".step1").removeClass('active');
    $(".step1 .step-icon, .indicator-line:first").css('background-color','greenyellow');
    $(".step1 p").css('color','greenyellow');
    var addressid = $('#address_id').val();
	var totalpro = {{$totalproducts}};
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
          $.ajax({
              url:" {{url('getpayment')}}",
              method:'POST',
              data:{addressid:addressid,totalpro:totalpro},
              success:function(data)
              {
                $('.payment_methods').html(data[0]);
                $('#shipping_price').css('display','block');
                $('#sidediv').css('display','block');
                $('.shipping-price').html(data[1]);
                var sta = {{ (Session::has('coupon_price'))?1:0 }};
                if(sta != 0){
                	var total = (totalpro + data[2] + $("input[type=radio]:checked").data('price') - {{ (Session::has('coupon_price'))?Session::get('coupon_price'):0 }} )* {{Session::get('currencychange')}};
                }
                else{
                	var total = (totalpro + data[2] + $("input[type=radio]:checked").data('price'))* {{Session::get('currencychange')}};
                }
                
                $('#total_price').css('display','block');
                $('.tlt-price').html(total);
                D(totalpro,data[2]);
                  $('#confirmBuyingBTN').click(function () {
                      agree = $('#agreecb:checked').val();
                      if(agree === 'on') {
                          $(this).css('opacity', '.65')
                          $(this).css('cursor', 'not-allowed')
                      }
                  });
              }
         });


});

function D(t,s){
$('#payment_type').css('display','block');
$('.payment-type').html($("input[type=radio]:checked").data('price'));

$("input[type=radio]").on( "change", function(){
	if($(this).prop("checked")){
		$('#payment_type').css('display','block');
		$('.payment-type').html($("input[type=radio]:checked").data('price'));
		var total = ((t + s)+ $("input[type=radio]:checked").data('price'))* {{Session::get('currencychange')}};
		$('.tlt-price').html(total);
	}
	var comment = $('#x').val();
    	console.log(comment);
});

$('.placeorder').on('click', function(){
var x = $('#agreecb:checked').val();
if(x){
	$('.loading').css('display','');
        $('.loading').css('display','inline-block');
    var paymentid =  $('.nomargin:checked').val();
    var comment = $('#x').val();
    //console.log(comment);
    //alert(comment);
    var data = new FormData();
    data.append( 'paymentid', paymentid);
    data.append( 'comment', comment);
    $(".step3").addClass('active');
    $(".indicator-line:last").removeClass('active');
    $(".step2 .step-icon, .indicator-line:last").css('background-color','greenyellow');
    $(".step2 p").css('color','greenyellow');    
    
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        $.ajax({
              url:" {{url('order-details')}}",
              method:'POST',
              contentType: false,
      	      processData: false,
              data:data,
              success:function(data)
              {
                  
                  $('.payment_methods').html(data[0]);
                  $(".step3 .step-icon").css('background-color','greenyellow');
                  $(".step3 p").css('color','greenyellow'); 
                   
                  (function (global) {

	if(typeof (global) === "undefined")
	{
		throw new Error("window is undefined");
	}

    var _hash = "!";
    var noBackPlease = function () {
        global.location.href += "#";

		// making sure we have the fruit available for juice....
		// 50 milliseconds for just once do not cost much (^__^)
        global.setTimeout(function () {
            global.location.href += "!";
        }, 50);
    };
	
	// Earlier we had setInerval here....
    global.onhashchange = function () {
        if (global.location.hash !== _hash) {
            global.location.hash = _hash;
        }
    };

    global.onload = function () {
        
		noBackPlease();

		// disables backspace on page except on input fields and textarea..
		document.body.onkeydown = function (e) {
            var elm = e.target.nodeName.toLowerCase();
            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
                e.preventDefault();
            }
            // stopping event bubbling up the DOM tree..
            e.stopPropagation();
        };
		
    };

})(window);
              }
});
}else{
alert('Checked {{ trans('site.shipping_msg') }}');
}
});

}
</script> 
@endsection 
  