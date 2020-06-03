@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'contactus/'.$contact->id]) !!}

    <div class="form-group">
        <label for="name">{{trans('home.name')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" value="{{$contact->name}}" id="name" required >
    </div>

    <div class="form-group">
        <label for="email">{{trans('home.email')}} :</label>
        <input type="email" class="form-control" placeholder="{{trans('home.email')}}" value="{{ $contact->email }}" name="email" required >
    </div>

    <div class="form-group">
        <label for="country">{{trans('home.country')}} :</label>
        <input type="text" class="form-control" value="{{ $contact->country }}" placeholder="{{trans('home.country')}}" name="country" required >
    </div>

    <div class="form-group">
        <label for="phone">{{trans('home.phone')}} :</label>  
        <input type="text" class="form-control" placeholder="{{trans('home.phone')}}" value="{{ $contact->phone }}" name="phone" required >
    </div>

    <div class="form-group">
        <label for="message">{{trans('home.message')}} :</label>
        <textarea class="form-control" name="message" placeholder="{{trans('home.message')}}" >{!! $contact->message !!}</textarea>
    </div>
  
  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('contactus') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>
    {!! Form::close() !!}


@endsection