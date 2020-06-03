@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'slideshow.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}

  <div class="form-group">
    <label for="slideshow_photo">{{trans('home.slideshow_photo')}} :</label>
    <input type="file" class="form-control" name="slideshow_photo" id="slideshow_photo" required >
  </div>

 
  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.link')}}" name="slideshow_link" >
  </div>

  <div class="form-group select-group">
    <select class="form-control" name="status" id="status" required>
      <option></option>
      <option value="1">{{trans('home.yes')}}</option>
      <option value="0">{{trans('home.no')}}</option>
    </select>
  </div>

  <div class="btns-bottom">
    <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
    <a href="{{ url('slideshow') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
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