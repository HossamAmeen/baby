@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'countries/'.$country->id]) !!}

    <div class="form-group">
        <label for="name">{{trans('home.name')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" value="{{$country->name}}" id="name" >
    </div>
       
    <div class="btns-bottom">
        <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
        <a href="{{ url('countries') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
    </div>

{!! Form::close() !!}


@endsection