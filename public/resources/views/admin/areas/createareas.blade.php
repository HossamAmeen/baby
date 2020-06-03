@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'areas.store', 'data-toggle'=>'validator']) !!}


  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" required >
  </div>

  <div class="form-group">
    <input type="number" step="0.01" class="form-control" placeholder="{{trans('home.shipping')}}" name="shipping" >
  </div>
  
  <div class="form-group">
		    
		    <select class="form-control" onchange="regions()" name="country_id" id="country_id" required>
		    <option></option>
		      @foreach($countries as $country)
		      	<option value="{{ $country -> id }}">{{ $country -> name }}</option>
		      @endforeach
		    </select>
		</div>
		
		<div class="form-group">
		    
		    <select class="form-control" name="region_id" id="region_id" required>
		      <option></option>
		    </select>
		</div>

  <div class="btns-bottom">
    <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
    <a href="{{ url('areas') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>

{!! Form::close() !!}


@endsection

@section('script')
<script>
$('#region_id').select2({
  'placeholder' : 'أختار المحافظة',
});
$('#country_id').select2({
  'placeholder' : 'أختار الدولة',
});

</script>  
<script type="text/javascript">

    
    //function regions();
    //function areas();
    function regions(){
	var selection = document.getElementById('country_id');
	var id = selection.options[selection.selectedIndex].value;
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	     document.getElementById("region_id").innerHTML = this.responseText;
	    }
	  };
	  xhttp.open("GET", "{{ url('country/regions') }}"+"/"+id, true);
	  xhttp.send();
		
      }

    $('#region_id').on('change', function(){
        var shipping = $(this).find('option:selected').attr('id');
        $('input[name=shipping]').val(shipping);
    });
</script>

  
@endsection