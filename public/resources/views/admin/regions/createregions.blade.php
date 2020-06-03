@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'regions.store', 'data-toggle'=>'validator']) !!}


  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" required >
  </div>

  <div class="form-group">
    <input type="number" min="1" step="0.01" class="form-control" placeholder="{{trans('home.shipping')}}" name="shipping" required >
  </div>
  
  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.shipping')}} {{trans('home.time')}}" name="shiping_time" required >
  </div>


  <div class="form-group select-group">
      {!! Form::select('country_id',(['' => ''])+$country,null,['id' => 'country_id','class' => 'form-control','required']) !!}
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