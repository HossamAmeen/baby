@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'blogcategory/'.$blogcategory->id]) !!}


      <div class="form-group">
      <label for="title">{{trans('home.title')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.title')}}" name="title" value="{{$blogcategory->title}}" id="title" >
      </div>
      <div class="form-group">
      <label for="link">{{trans('home.link')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.link')}}" name="link" value="{{$blogcategory->link}}" id="link" >
      </div>

      <div class="form-group">
      <label for="date">{{trans('home.date')}} :</label>
        <input type="text" class="form-control date" value="{{$blogcategory->date}}" placeholder="{{trans('home.date')}}" name="date" required >
      </div>
     
      <div class="form-group select-group">
        <label for="status">{{trans('home.status')}} :</label>
        <select class="form-control" name="status" id="status" required>
          <option></option>
          <option @if($blogcategory->status == '1') selected @endif value="1">{{trans('home.yes')}}</option>
          <option @if($blogcategory->status == '0') selected @endif value="0">{{trans('home.no')}}</option>
        </select>
      </div>
      
      <div class="form-group">
      <label for="meta_keywords">{{trans('home.meta_keywords')}} :</label>
      <textarea class="form-control " name="meta_keywords" id="meta_keywords" >{!! $blogcategory->meta_keywords !!}</textarea>
    </div>

    <div class="form-group">
      <label for="meta_description">{{trans('home.meta_description')}} :</label>
      <textarea class="form-control " name="meta_description" id="meta_description" >{!! $blogcategory->meta_description !!}</textarea>
    </div>
  
  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('blogcategory') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>
    {!! Form::close() !!}


@endsection
@section('script')
<script>
$('#status').select2({
  'placeholder' : 'أختار الحالة',
});
$(function() {
    $( ".date" ).datepicker({
     dateFormat: "yy-mm-dd"
    });
});
</script>    
@endsection