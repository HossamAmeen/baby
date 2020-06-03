@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'sponsors.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}


      <div class="form-group">
        <label for="photos">{{trans('home.photos')}} :</label>
        <input type="file" class="form-control" name="photos[]" id="photos" multiple>
      </div>
     
      
  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('sponsors') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>
    {!! Form::close() !!}


@endsection

