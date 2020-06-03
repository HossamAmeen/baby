@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'countries.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}

  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" required >
  </div>

  <div class="btns-bottom">
    <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
    <a href="{{ url('countries') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>

{!! Form::close() !!}


@endsection