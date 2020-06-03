@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'image-home.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}


      <div class="form-group">
        <label for="title">{{trans('home.title')}} :</label>
        <input type="text" class="form-control" name="title" id="title"  >
      </div>
      
      <div class="form-group">
        <label for="link">{{trans('home.link')}} :</label>
        <input type="text" class="form-control" name="link" id="link"  >
      </div> 

      <div class="form-group">
        <label for="photo">{{trans('home.photo')}} :</label>
        <input type="file" class="form-control" name="photo" id="photo" required>
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
      <a href="{{ url('image-home') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
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
