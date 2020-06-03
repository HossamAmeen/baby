@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'padges.store', 'data-toggle'=>'validator']) !!}


    <div class="form-group">
        <label for="title">{{trans('home.title')}} :</label>
        <input type="text" class="form-control" name="title" id="title"  >
    </div>
     
      
    <div class="btns-bottom">
        <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
        <a href="{{ url('padges') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
    </div>
    {!! Form::close() !!}


@endsection
