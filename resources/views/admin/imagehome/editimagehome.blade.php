@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'image-home/'.$image->id,'files'=>'true']) !!}



	    <div class="form-group">
        <label for="title">{{trans('home.title')}} :</label>
        <input type="text" class="form-control" name="title" value="{{ $image->title }}" id="title"  >
      </div>
      <div class="form-group">
        <label for="link">{{trans('home.link')}} :</label>
        <input type="text" class="form-control" name="link" value="{{ $image->link }}" id="link"  >
      </div>
      <div class="form-group">
        <span>{{trans('home.imageshow')}} :</span> <img src="{{url('\uploads\imagehome\resize200')}}\{{$image->image}}" width="150" height="150">
      </div>
      <div class="form-group">
        <label for="photo">{{trans('home.photo')}} :</label>
        <input type="file" class="form-control" name="photo" id="photo" @if(!$image->image) required @endif >
      </div>
     
      <div class="form-group select-group">
        <label for="status">{{trans('home.status')}} :</label>
        <select class="form-control" name="status" id="status" required>
          <option></option>
          <option @if($image->status == '1') selected @endif value="1">{{trans('home.yes')}}</option>
          <option @if($image->status == '0') selected @endif value="0">{{trans('home.no')}}</option>
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