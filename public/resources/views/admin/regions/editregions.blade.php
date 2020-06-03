@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'regions/'.$region->id]) !!}


      <div class="form-group">
      <label for="name">{{trans('home.name')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" value="{{$region->name}}" id="name" >
      </div>

        <div class="form-group">
            <label for="shipping">{{trans('home.shipping')}} :</label>
            <input type="number" min="1" step="0.01" class="form-control" placeholder="{{trans('home.shipping')}}" value="{{$region->shipping}}" name="shipping" required >
        </div>
        
        <div class="form-group">
	    <input type="text" class="form-control" placeholder="{{trans('home.shipping')}} {{trans('home.time')}}" value="{{$region->shiping_time }}" name="shiping_time" required >
	  </div>


    <div class="form-group select-group">
        <label for="country_id">{{trans('home.country')}} :</label>
        {!! Form::select('country_id',(['' => ''])+$country,$region->country_id,['id' => 'country_id','class' => 'form-control','required']) !!}
    </div>
       
      

      
  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('regions') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>
    {!! Form::close() !!}


@endsection
@section('script')
<script>
$('#country_id').select2({
  'placeholder' : 'أختار الدولة',
});
</script>    
@endsection