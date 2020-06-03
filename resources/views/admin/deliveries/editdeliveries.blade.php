@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'deliveries/'.$delivery->id]) !!}

    <div class="form-group select-group">
      <label for="user">{{trans('home.user')}} :</label>
      {!! Form::select('user_id',(['' => ''])+$user,$delivery->user_id,['id' => 'user_id','class' => 'form-control','required']) !!}
    </div>
    <div class="form-group">
        <label for="name">{{trans('home.name')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" value="{{$delivery->name}}" id="name" >
    </div>
    <div class="form-group">
        <label for="phone">{{trans('home.phone')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.phone')}}" name="phone" value="{{$delivery->phone}}" id="phone" >
    </div>
    <div class="form-group">
        <label for="email">{{trans('home.email')}} :</label>
        <input type="email" class="form-control" placeholder="{{trans('home.email')}}" name="email" value="{{$delivery->email}}" id="email" >
    </div>
    <div class="form-group">
        <label for="address">{{trans('home.address')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.address')}}" name="address" value="{{$delivery->address}}" id="address" >
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
 placeholder: 'Select User',
});
</script>
@endsection