@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'blogitem/'.$blogitem->id, 'files'=>'true']) !!}




      <div class="form-group select-group">
       <label for="bolgcategory_id">{{trans('home.blogcategory')}} :</label>
       {!! Form::select('blogcategory_id',(['' => '']) + $blogcategory,$blogitem->blogcategory_id,['id' => 'bolgcategory_id','class' => 'form-control','required']) !!}
      </div> 

      <div class="form-group">
      <label for="title">{{trans('home.title')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.title')}}" name="title" value="{{$blogitem->title}}" id="title" >
      </div>
      <div class="form-group">
      <label for="title_ar">{{trans('home.title_ar')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.title_ar')}}" name="title_ar" value="{{$blogitem->title_ar}}" id="title_ar" >
      </div>
      <div class="form-group">
      <label for="link">{{trans('home.link')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.link')}}" name="link"  value="{{$blogitem->link}}" id="link" >
      </div>

      <div class="form-group">
      <label for="date">الكاتب :</label>
        <input type="text" class="form-control" value="{{$blogitem->date}}" placeholder="الكاتب" name="date" >
      </div>
      @if($blogitem->image)
      <div class="form-group">
        <span>{{trans('home.imageshow')}} :</span> <img src="{{url('\uploads\blogitem\resize200')}}\{{$blogitem->image}}" width="150" height="150">
      </div>
      @endif
      <div class="form-group">
       <label for="photo">{{trans('home.photo')}} :</label>
       <input type="file" class="form-control" name="photo" id="photo"  >
      </div>

      <div class="form-group">
        <label for="text">{{trans('home.text')}} :</label>
        <textarea class="form-control area1" name="text" placeholder="{{trans('home.text')}}" >{!! $blogitem->text !!}</textarea>
      </div>
      
      <div class="form-group">
        <label for="text_ar">{{trans('home.text_ar')}} :</label>
        <textarea class="form-control area1" name="text_ar" placeholder="{{trans('home.text_ar')}}" >{!! $blogitem->text_ar !!}</textarea>
      </div>
     
      <div class="form-group select-group">
        <label for="status">{{trans('home.status')}} :</label>
        <select class="form-control" name="status" id="status" required>
          <option></option>
          <option @if($blogitem->status == '1') selected @endif value="1">{{trans('home.yes')}}</option>
          <option @if($blogitem->status == '0') selected @endif value="0">{{trans('home.no')}}</option>
        </select>
      </div>
      
      <div class="form-group">
      <label for="meta_keywords">{{trans('home.meta_keywords')}} :</label>
      <textarea class="form-control " name="meta_keywords" id="meta_keywords" >{!! $blogitem->meta_keywords !!}</textarea>
    </div>

    <div class="form-group">
      <label for="meta_description">{{trans('home.meta_description')}} :</label>
      <textarea class="form-control " name="meta_description" id="meta_description" >{!! $blogitem->meta_description !!}</textarea>
    </div>
  
  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('blogitem') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>
    {!! Form::close() !!}


@endsection
@section('script')
<script>
$('#status').select2({
  'placeholder' : 'أختار الحالة',
});
$('#bolgcategory_id').select2({
  'placeholder' : 'أختار القسم',
});
$(function() {
    $( ".date" ).datepicker({
     dateFormat: "yy-mm-dd"
    });
});
</script>    
@endsection