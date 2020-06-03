@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'roles.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}


      <div class="form-group">
        <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" required>
      </div>
      <div class="form-group">
        <input type="text" class="form-control" placeholder="{{trans('home.display_name')}}" name="display_name" >
      </div>
       <div class="form-group">
        <input type="text" class="form-control" placeholder="{{trans('home.description')}}" name="description" >
      </div>
      
      
  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">Submit</button>
      <a href="{{ url('roles') }}" id="back" class="btn btn-default">Back</a>
  </div>
    {!! Form::close() !!}


@endsection
