@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'pages.store', 'data-toggle'=>'validator']) !!}


    <div class="form-group">
        <label for="title">{{trans('home.title')}} :</label>
        <input type="text" class="form-control" name="title" id="title" placeholder="{{trans('home.title')}}">
    </div>
    
    <div class="form-group">
        <label for="title">{{trans('home.link')}} :</label>
        <input type="text" class="form-control" name="link" id="link" placeholder="{{trans('home.link')}}" >
    </div>
    
    <div class="form-group select-group">
        <label for="lang">{{trans('home.lang')}} :</label>
        <select class="form-control" name="lang" id="lang">
           <option value="en">{{trans('home.en')}}</option>
      	   <option value="ar">{{trans('home.ar')}}</option>
        </select>
    </div>
    
    <div class="form-group">
      <label for="text">{{trans('home.content')}} :</label>
      <textarea class="form-control area1" name="text" id="text"></textarea>
    </div>
     
      
    <div class="btns-bottom">
        <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
        <a href="{{ url('pages') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
    </div>
    {!! Form::close() !!}


@endsection

@section('script')
$('#lang').select2({
  'placeholder' : 'أختار اللغة',
});
@endsection
