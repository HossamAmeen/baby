@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'brands/'.$s->id,'files'=>'true']) !!}


     <div class="form-group">
      <label for="name">{{trans('home.name')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" value="{{$s->name}}" id="name" >
      </div>

      <div class="form-group">
        <span>{{trans('home.imageshow')}} :</span> <img src="{{url('\uploads\brands\resize200')}}\{{$s->image}}" width="150">
      </div>
      <div class="form-group">
        <label for="photo">{{trans('home.photo')}} :</label>
        <input type="file" class="form-control" name="photo" id="photo">
      </div>

<div class="form-group">
    <label for="meta_keywords">{{trans('home.meta_keywords')}} :</label>
    <textarea required class="form-control" name="meta_keywords" id="meta_keywords" >@if($s){{ $s->meta_keywords }}@endif</textarea>
</div>

<div class="form-group">
    <label for="meta_description">{{trans('home.meta_description')}} :</label>
    <textarea required class="form-control" name="meta_description" id="meta_description" >@if($s){{ $s->meta_description }}@endif</textarea>
</div>

      
  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('brands') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>
    {!! Form::close() !!}


@endsection
