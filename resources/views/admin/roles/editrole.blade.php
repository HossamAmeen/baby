@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'roles/'.$role->id,'files'=>'true']) !!}

      <div class="form-group">
      <label for="name">{{trans('home.name')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.name')}}" id="name" value="{{$role->name}}" name="name" required>
      </div>
      <div class="form-group">
      <label for="display_name">{{trans('home.display_name')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.display_name')}}" id="display_name" value="{{$role->display_name}}" name="display_name" >
      </div>
      <div class="form-group">
      <label for="description">{{trans('home.description')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.description')}}" id="description" value="{{$role->description}}" name="description" >
      </div>
      
    

  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">Submit</button>
      <a href="{{ url('roles') }}" id="back" class="btn btn-default">Back</a>
  </div>
    {!! Form::close() !!}


@endsection
