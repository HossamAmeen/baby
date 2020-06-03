@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'sponsors/'.$spon->id,'files'=>'true']) !!}



	    <div class="form-group">
        <label for="title">{{trans('home.title')}} :</label>
        <input type="text" class="form-control" name="title" value="{{ $spon->title }}" id="title"  >
      </div>
      
      <div class="form-group">
        <span>{{trans('home.imageshow')}} :</span> <img src="{{url('\uploads\sponsor\resize200')}}\{{$spon->image}}" width="150" height="150">
      </div>
      <div class="form-group">
        <label for="photo">{{trans('home.photo')}} :</label>
        <input type="file" class="form-control" name="photo" id="photo" @if(!$spon->image) required @endif >
      </div>
     

  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('sponsors') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>
    {!! Form::close() !!}


@endsection