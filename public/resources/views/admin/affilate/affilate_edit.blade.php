@extends('layouts.admin')

@section('content')

<ul class="nav nav-tabs">
    <li  class="active"><a href="{{ route('affilates.edit', $affilate ->id) }}">{{trans('home.affilatedata')}}</a></li>
    <li><a href="{{ route('affilate_orders',['id' => $affilate ->id]) }}">{{trans('home.orders')}}</a></li>
    <li><a href="{{ route('affilate_drags',['id' => $affilate ->id]) }}">{{trans('home.withdrows')}}</a></li>
    <li><a href="{{ route('affilate_products',['id' => $affilate ->id]) }}">{{trans('home.products')}}</a></li>
    <li><a href="{{ route('affilate_categories',['id' => $affilate ->id]) }}">{{trans('home.categories')}}</a></li>
  </ul><br>

{!! Form::open(['method'=>'PATCH','url' => 'affilates/'.$affilate ->id]) !!}

	<div class="form-group select-group">
	<label>{{trans('home.user')}}</label>
	    <select class="form-control" name="user" onchange="data()" id="user" required>
	      <option></option>
	      @foreach($users as $user)
	      <option value="{{ $user -> id }}" {{ ($user -> id == $affilate ->user_id)?'selected':'' }}>{{ $user -> name }}</option>
	      @endforeach
	    </select>
	  </div>
	  
   <div id="user_data">
      <div class="form-group">
      	<label>{{trans('home.name')}}</label>
      <input type="text" class="form-control" value="{{ $affilate ->name }}" name="name" id="name" required>
   </div>
      
      <div class="form-group">
      	<label>{{trans('home.email')}}</label>
        <input type="email" class="form-control" name="email" value="{{ $affilate ->email }}" id="email" data-error="Please, enter a valid email" required>
        <div class="help-block with-errors"></div>
      </div>
      
      <div class="form-group">
      	<label>{{trans('home.phone')}}</label>
        <input type="text" class="form-control" value="{{ $affilate ->phone }}" name="phone" id="phone" required>
      </div>
      
      <div class="form-group">
      	<label>{{trans('home.address')}}</label>
        <input type="text" class="form-control" value="{{ $affilate ->address }}" name="address" id="address" required>
      </div>
    </div>
      
     
      <div class="form-group select-group">
      	<label>{{trans('site.balance')}}</label>
        <input type="number" class="form-control" value="{{ $affilate ->balance}}" name="balance" readonly>
      </div>
      
      <div class="form-group select-group">
      	<label>{{trans('home.status')}}</label>
	    <select class="form-control" name="status" id="status" required>
	      <option></option>
	      <option value="1" {{($affilate ->status == 1)?'selected':''}}>{{trans('home.yes')}}</option>
	      <option value="0" {{($affilate ->status == 0)?'selected':''}}>{{trans('home.no')}}</option>
	    </select>
	  </div>
    
  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('affilates') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
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