@extends('layouts.admin')

@section('content')

@if(\Session::has('success'))
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>
        <strong class="text-center">{{ session()->pull('success', 'default') }}</strong>
</div>
@endif

@if(\Session::has('error'))
<div class="alert alert-danger alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>
  <strong class="text-center">{{ session()->pull('error', 'default') }}</strong>
</div>
@endif


{!! Form::open(['route' => 'orders.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}


	<div id="display_customer">
		<div class="form-group select-group">
		    <label>{{trans('home.customer')}}</label>
		    <select class="form-control" onchange="addresses()" name="customer" id="customer" required>
		      <option></option>
		      @foreach($users as $user)
		      	<option value="{{ $user -> id }}">{{ $user->name }}</option>
		      @endforeach
		    </select>
		</div>
		
		<div class="form-group">
		    <label>{{trans('home.address')}}</label>
		    <select class="form-control" name="address" id="address">
		      <option></option>
		      
		    </select>
		</div>
	</div><br>
	
	
	
	<div class="form-group select-group">
	    <label>{{trans('home.products')}}</label>
	    <select class="form-control" onchange="quantity()" name="select_product" id="select_product" required>
	      <option></option>
	      @foreach($products as $product)
	      	<option value="{{ $product -> id }}">{{ $product->title_ar }}</option>
	      @endforeach
	    </select>
	</div>
	
	<div class="form-group select-group">
	    <label>{{trans('home.paymethod')}}</label>
	    <select class="form-control" name="paymethod" id="paymethod" >
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

     function addresses(){
	var selection = document.getElementById('customer');
	var id = selection.options[selection.selectedIndex].value;
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	     document.getElementById("address").innerHTML = this.responseText;
	    }
	  };
	  xhttp.open("GET", "{{ url('user/address') }}"+"/"+id, true);
	  xhttp.send();
		
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



$('#customer').select2({
  'placeholder' : 'إختر العميل ',
});
$('#address').select2({
  'placeholder' : 'إختر العنوان ',
});
$('#select_product').select2({
  'placeholder' : 'إختر المنتج ',
});
$('#paymethod').select2({
  'placeholder' : 'إختر طريقة الدفع ',
});


</script>    
@endsection