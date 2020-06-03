@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'orders.store2', 'data-toggle'=>'validator', 'files'=>'true']) !!}

<div>
<h1>{{trans('home.user_data')}}</h1>
	<div class="form-group">
	    <label for="name">{{trans('home.name')}} :</label>
	    <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" required >
	</div>
	
	{{--<div class="form-group">
	    <label for="email">{{trans('home.email')}} :</label>
	    <input type="email" class="form-control" placeholder="{{trans('home.email')}}" name="email" required >
	</div>--}}
	
	<div class="form-group">
	    <label for="phone">{{trans('home.phone')}} :</label>
	    <input type="text" class="form-control" placeholder="{{trans('home.phone')}}" name="phone" required >
	</div>
	
	<div class="form-group select-group">
		    <label>{{trans('home.country')}}</label>
		    <select class="form-control" name="country" id="country" required>
		      <option></option>
		      @foreach($counteries as $countery)
		      	<option value="{{ $countery-> id }}">{{ $countery->name }}</option>
		      @endforeach
		    </select>
		</div>
		
	<div class="form-group select-group">
		    <label>{{trans('home.region')}}</label>
		    <select class="form-control" name="region" id="region" required>
		      <option></option>
		      @foreach($regions as $region)
		      	<option value="{{ $region -> id }}">{{ $region ->name }}</option>
		      @endforeach
		    </select>
		</div>
	<div class="form-group select-group">
		    <label>{{trans('home.area')}}</label>
		    <select class="form-control" name="area" id="area" required>
		      <option></option>
		      @foreach($areas as $area)
		      	<option value="{{ $area -> id }}">{{ $area ->name }}</option>
		      @endforeach
		    </select>
		</div>
	<div class="form-group">
	    <label for="address">{{trans('home.address')}} :</label>
	    <input type="text" class="form-control" placeholder="{{trans('home.address')}}" name="address" required >
	</div>
</div>

<div class="form-group select-group">
	    <label>{{trans('home.products')}}</label>
	    <select class="form-control" onchange="quantity()" name="select_product[]" id="select_product" required>
	      <option></option>
	      @foreach($products as $product)
	      	<option value="{{ $product -> id }}">{{ $product->title_ar }}</option>
	      @endforeach
	    </select>
	</div>
	
	<div class="form-group select-group">
	    <label>{{trans('home.paymethod')}}</label>
	    <select class="form-control" name="paymethod" id="paymethod" required>
	      <option></option>
	      @foreach($paymethods as $paymethod)
	      	<option value="{{ $paymethod -> id }}">{{ $paymethod ->name }}</option>
	      @endforeach
	    </select>
	</div>
	
	<div id="display_products">
	
	</div>

	
	<div class="btns-bottom">
	    <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
	    <a href="{{ url('orders') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
	</div>

{!! Form::close() !!}


@endsection

@section('script')

<script type="text/javascript">

     function quantity(){
	var selection = document.getElementById('select_product');
	var text = selection.options[selection.selectedIndex].text;
	var id = selection.options[selection.selectedIndex].value;
	var newdiv= document.createElement("div");
	var html = '<h3>'+text+'</h3>'+
		'<h5>{{trans('home.quantity')}}</h5>'+
		'<div class="input-append">'+
		'<input type="hidden" name="products[]" value="'+id+'">'+
		'<input class="span2" value="1" name="amounts[]" id="amount'+id+'" size="16" type="text" readonly>'+
		'<button type="button" class="btn" onclick="increase('+id+');"><b class="icon-minus">+++</b></button>'+
		'<button type="button" class="btn" onclick="decrease('+id+');"><b class="icon-plus">---</b></button></div>'+
		'<button type="button" id="btndelete" onclick="deleteelebt(this)" class="btn btn-danger">{{trans('home.delete')}}</button>';
	newdiv.innerHTML = html;
	document.getElementById('display_products').appendChild(newdiv);
		
		
    }
    
    function increase(id){
	var x=parseInt(document.getElementById("amount"+id).value);
	if((x >= 1) && (x < 10)){
		x++;
		document.getElementById("amount"+id).value=x;
	}

    }

    function decrease(id){
	var x=parseInt(document.getElementById("amount"+id).value);
	if((x > 1) && (x <= 10)){
		x--;
		document.getElementById("amount"+id).value=x;
	}
    }

    function deleteelebt(ob)
    {
	var container=document.getElementById('display_products');
	container.removeChild(ob.parentNode);
    }

</script>

<script>

$('#country').select2({
  'placeholder' : 'إختر الدولة ',
});
$('#region').select2({
  'placeholder' : 'إختر المحافظة ',
});
$('#area').select2({
  'placeholder' : 'إختر المنطقة ',
});
$('#select_product').select2({
  'placeholder' : 'إختر المنتج ',
});
$('#paymethod').select2({
  'placeholder' : 'إختر طريقة الدفع ',
});

</script>    
@endsection