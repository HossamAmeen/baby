@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'saveseo', 'data-toggle'=>'validator', 'files'=>'true']) !!}

<div class="form-group">
  <label for="meta_keywords">{{trans('home.meta_keywords')}} :</label>
  <textarea required class="form-control" name="meta_keywords" id="meta_keywords" >@if($offer){{ $offer->meta_keywords }}@endif</textarea>
</div>

<div class="form-group">
  <label for="meta_description">{{trans('home.meta_description')}} :</label>
  <textarea required class="form-control" name="meta_description" id="meta_description" >@if($offer){{ $offer->meta_description }}@endif</textarea>
</div>

  <div class="btns-bottom">
    <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
    <a href="{{ url('paymethods') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>

{!! Form::close() !!}


@endsection

@section('script')

@endsection