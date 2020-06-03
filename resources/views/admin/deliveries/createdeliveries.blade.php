@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'deliveries.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}

  <div class="form-group select-group">
      {!! Form::select('user_id',(['' => ''])+$user,null,['id' => 'user_id','class' => 'form-control','required']) !!}
  </div>
  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" required >
  </div>
  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.phone')}}" name="phone" required >
  </div>
  <div class="form-group">
    <input type="email" class="form-control" placeholder="{{trans('home.email')}}" name="email" >
  </div>
  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.address')}}" name="address" required >
  </div>

  <div class="btns-bottom">
    <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
    <a href="{{ url('deliveries') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>

{!! Form::close() !!}

@endsection
@section('script')
<script>
$('#user_id').select2({
	placeholder : 'Select User',
});
</script>
@endsection