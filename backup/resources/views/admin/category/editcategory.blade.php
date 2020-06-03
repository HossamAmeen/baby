@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'category/'.$category->id,'files'=>'true']) !!}


      <div class="form-group">
        <label for="title">{{trans('home.title')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.title')}}" name="title" value="{{$category->title}}" id="title" >
      </div>

      <div class="form-group">
        <label for="title_ar">{{trans('home.title_ar')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.title_ar')}}" name="title_ar" value="{{$category->title_ar}}" id="title_ar" >
      </div>

      <div class="form-group">
        <label for="link">{{trans('home.link')}} :</label>
        <input type="text" class="form-control"  placeholder="{{trans('home.link')}}" name="link"  value="{{$category->link}}" id="link" >
      </div>
      
      <div class="form-group select-group">
      <label for="brands">{{trans('home.brands')}} :</label>
       <select class="form-control" name="brands[]" id="brands" multiple>
          <option></option>
          @foreach($brands as $k => $x)
          <option @if(in_array($k, $catbrands)) selected @endif value="{{$k}}">{{$x}}</option>
          @endforeach
        </select>
      
     </div>

      <div class="form-group select-group">
        <label for="parent">{{trans('home.parent')}} :</label>
        {!! Form::select('parent',(['' => ''])+$parent,$category->parent,['id' => 'parent','class' => 'form-control']) !!}
      </div>
     
      <div class="form-group select-group">
        <label for="status">{{trans('home.status')}} :</label>
        <select class="form-control" name="status" id="status" required>
          <option></option>
          <option @if($category->status == '1') selected @endif value="1">{{trans('home.yes')}}</option>
          <option @if($category->status == '0') selected @endif value="0">{{trans('home.no')}}</option>
        </select>
      </div>
      
    <div class="form-group">
      <label for="meta_keywords">{{trans('home.meta_keywords')}} :</label>
      <textarea class="form-control " name="meta_keywords" id="meta_keywords" >{!! $category->meta_keywords !!}</textarea>
    </div>

    <div class="form-group">
      <label for="meta_description">{{trans('home.meta_description')}} :</label>
      <textarea class="form-control " name="meta_description" id="meta_description" >{!! $category->meta_description !!}</textarea>
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
$('#parent').select2({
  'placeholder' : 'أختار اﻷب',
});
$('#lang').select2({
  'placeholder' : 'أختار اللغة',
});
$('#brands').select2({
  'placeholder' : 'أختار الماركة',
});
</script>    
@endsection