@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'padges/'.$padge->id]) !!}

    <div class="form-group">
        <label for="title">{{trans('home.title')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.title')}}" name="title" value="{{$padge->title}}" id="title" required >
    </div>
  
  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('padges') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>
    {!! Form::close() !!}


@endsection