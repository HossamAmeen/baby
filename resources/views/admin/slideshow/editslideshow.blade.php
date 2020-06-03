@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'slideshow/'.$s->id,'files'=>'true']) !!}


      <div class="form-group">
        <span>{{trans('home.imageshow')}} :</span> <img src="{{url('\uploads\slideshow\resize200')}}\{{$s->image}}" width="150" height="150">
      </div>
      <div class="form-group">
        <label for="slideshow_photo">{{trans('home.slideshow_photo')}} :</label>
        <input type="file" class="form-control" name="slideshow_photo" id="slideshow_photo" @if(!$s->image) required @endif >
      </div>

      <div class="form-group">
      <label for="slideshow_link">{{trans('home.link')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.slideshow_link')}}" name="slideshow_link" value="{{$s->link}}" id="slideshow_link" >
      </div>
     
      <div class="form-group select-group">
        <label for="status">{{trans('home.status')}} :</label>
        <select class="form-control" name="status" id="status" required>
          <option></option>
          <option @if($s->status == '1') selected @endif value="1">{{trans('home.yes')}}</option>
          <option @if($s->status == '0') selected @endif value="0">{{trans('home.no')}}</option>
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