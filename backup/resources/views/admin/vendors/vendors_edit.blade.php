@extends('layouts.admin')

@section('content')

<ul class="nav nav-tabs">
    <li  class="active"><a href="{{ route('vendors.edit', $vendor->id) }}">{{trans('home.vendordata')}}</a></li>
    <li><a href="{{ route('vendor_orders',['id' => $vendor->id]) }}">{{trans('home.orders')}}</a></li>
    <li><a href="{{ route('vendor_drags',['id' => $vendor->id]) }}">{{trans('home.withdrows')}}</a></li>
  </ul><br>

{!! Form::open(['method'=>'PATCH','url' => 'vendors/'.$vendor->id]) !!}

	<div class="form-group select-group">
		<label>{{ trans('home.user') }}</label>
	    <select class="form-control" name="user" onchange="data()" id="user" required>
	      <option></option>
	      @foreach($users as $user)
	      <option value="{{ $user -> id }}" {{ ($user -> id == $vendor->user_id)?'selected':'' }}>{{ $user -> name }}</option>
	      @endforeach
	    </select>
	  </div>
	  
   <div id="user_data">
      <div class="form-group">
      	<label>{{trans('home.name')}}</label>
      <input type="text" class="form-control" value="{{ $vendor->name }}" name="name" id="name" required>
   </div>
      
      <div class="form-group">
      	<label>{{trans('home.email')}}</label>
        <input type="email" class="form-control" name="email" value="{{ $vendor->email }}" id="email" data-error="Please, enter a valid email" required>
        <div class="help-block with-errors"></div>
      </div>
      
      <div class="form-group">
      	<label>{{trans('home.phone')}}</label>
        <input type="text" class="form-control" value="{{ $vendor->phone }}" name="phone" id="phone" required>
      </div>
      
      <div class="form-group">
      	<label>{{trans('home.address')}}</label>
        <input type="text" class="form-control" value="{{ $vendor->address }}" name="address" id="address" required>
      </div>
    </div>
      
      <div class="form-group select-group">
      	<label>{{trans('site.commission')}} % </label>
        <input type="number" class="form-control" value="{{ $vendor->commission }}" name="commission" min="0" max="30" required>
      </div>
      
      <div class="form-group select-group">
      	<label>{{trans('site.balance')}}</label>
        <input type="number" class="form-control" value="{{ $vendor->balance}}" name="balance" readonly>
      </div>
      
      <div class="form-group select-group">
      	<label>{{trans('home.status')}}</label>
	    <select class="form-control" name="status" id="status" required>
	      <option></option>
	      <option value="1" {{($vendor->status == 1)?'selected':''}}>{{trans('home.yes')}}</option>
	      <option value="0" {{($vendor->status == 0)?'selected':''}}>{{trans('home.no')}}</option>
	    </select>
	  </div>
    
  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('vendors') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>
    {!! Form::close() !!}


@endsection

<script type="text/javascript">

function data(){
	var selection = document.getElementById('user');
	var id = selection.options[selection.selectedIndex].value;
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	     var data = JSON.parse(this.responseText);
	     //document.getElementById("user_data").innerHTML = data.name;
	     document.getElementById("name").value = data.name+"";
	     document.getElementById("email").value = data.email;
	     document.getElementById("phone").value = data.phone;
	     document.getElementById("address").value = data.address;
	    }
	  };
	  xhttp.open("GET", "{{ url('user/data') }}"+"/"+id, true);
	  xhttp.send();
		
      }
      
      
</script> 

@section('script')
<script>

$('#status').select2({
  'placeholder' : 'أختار الحالة',
});

$('#user').select2({
  'placeholder' : 'إختر المستخدم ',
});

$('#lang').select2({
  'placeholder' : 'أختار اللغة',
});

</script>   
@endsection