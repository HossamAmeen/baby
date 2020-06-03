@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'category.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}


  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.title')}}" name="title" required >
  </div>
  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.title_ar')}}" name="title_ar" required >
  </div>
  
  <div class="form-group select-group">
      {!! Form::select('brands[]',(['' => ''])+$brands,null,['id' => 'brands','class' => 'form-control','multiple']) !!}
  </div>

  <div class="form-group select-group">
      {!! Form::select('parent',(['' => ''])+$parent,null,['id' => 'parent','class' => 'form-control']) !!}
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
    <a href="{{ url('category') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>

{!! Form::close() !!}


@endsection

@section('script')
<script>
$('#status').select2({
  'placeholder' : 'أختار الحالة',
});
$('#brands').select2({
  'placeholder' : 'أختار الماركة',
});
$('#lang').select2({
  'placeholder' : 'أختار اللغة',
});
$('#parent').select2({
  'placeholder' : 'أختار اﻷب',
});
</script>    
@endsection