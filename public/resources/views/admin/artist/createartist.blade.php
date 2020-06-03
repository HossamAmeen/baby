@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'artist.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}

 

  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" required >
  </div>
  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.facebook')}}" name="facebook"  >
  </div>
  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.twitter')}}" name="twitter"  >
  </div>
  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.google')}}" name="google"  >
  </div>
  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.pinterest')}}" name="pinterest"  >
  </div>
  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.instagram')}}" name="instagram"  >
  </div>


  <div class="form-group">
    <label for="photo">{{trans('home.photo')}} :</label>
    <input type="file" class="form-control" name="photo" id="photo"  >
  </div>


  <div class="form-group select-group">
    <select class="form-control" name="status" id="status" required>
      <option></option>
      <option value="1">{{trans('home.yes')}}</option>
      <option value="0">{{trans('home.no')}}</option>
    </select>
  </div>
  <div class="form-group">
      <label for="meta_keywords">{{trans('home.meta_keywords')}} :</label>
      <textarea class="form-control " name="meta_keywords" id="meta_keywords" ></textarea>
    </div>

    <div class="form-group">
      <label for="meta_description">{{trans('home.meta_description')}} :</label>
      <textarea class="form-control " name="meta_description" id="meta_description" ></textarea>
    </div>

  <div class="btns-bottom">
    <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
    <a href="{{ url('artist') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>

{!! Form::close() !!}


@endsection
@section('script')
<script>
$('#status').select2({
  'placeholder' : 'أختار الحالة',
});
</script>    
@endsection