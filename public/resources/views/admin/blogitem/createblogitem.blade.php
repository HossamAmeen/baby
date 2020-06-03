@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'blogitem.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}


  <div class="form-group select-group">
    <label for="bolgcategory_id">{{trans('home.blogcategory')}} :</label>
    {!! Form::select('bolgcategory_id',(['' => '']) + $blogcategory,null,['id' => 'bolgcategory_id','class' => 'form-control','required']) !!}
  </div>   

  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.title')}}" name="title" required >
  </div>
   <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.title_ar')}}" name="title_ar" required >
  </div>

  <div class="form-group">
    <input type="text" class="form-control" placeholder="الكاتب" name="date" >
  </div>

  <div class="form-group">
    <label for="photo">{{trans('home.photo')}} :</label>
    <input type="file" class="form-control" name="photo" id="photo"  >
  </div>

  <div class="form-group">
      <label for="text">{{trans('home.text')}} :</label>
      <textarea class="form-control area1" name="text" placeholder="{{trans('home.text')}}" ></textarea>
  </div> 
  <div class="form-group">
      <label for="text_ar">{{trans('home.text_ar')}} :</label>
      <textarea class="form-control area1" name="text_ar" placeholder="{{trans('home.text_ar')}}" ></textarea>
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