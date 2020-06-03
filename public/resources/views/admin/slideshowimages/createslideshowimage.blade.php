@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'slideshowimages.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}


<!--
      <div class="form-group">
        <input type="text" class="form-control" placeholder="{{trans('home.slideshowimage_title')}}" name="slideshowimage_title" required>
      </div>
-->
       <div class="form-group">
        <input type="text" class="form-control" placeholder="{{trans('home.slideshowimage_link')}}" name="slideshowimage_link" >
      </div> 
      <div class="form-group">
        <label for="slideshow_photo">{{trans('home.slideshow_photo')}} :</label>
        <input type="file" class="form-control" name="slideshow_photo" id="slideshow_photo">
      </div>

<!--
      <div class="form-group">
     <label for="slideshowimage_text">{{trans('home.slideshowimage_text')}} :</label>
        <textarea class="form-control area1" name="slideshowimage_text" id="slideshowimage_text" ></textarea>
     </div>
-->
      
       <div class="form-group select-group">
        {!! Form::select('slideshow_id',($s),null,['class'=>'form-control']) !!}
      </div>
      <div class="form-group select-group">
        {!! Form::select('project_id',(['0' => 'Select a Project'] + $pro),null,['class'=>'form-control']) !!}
      </div>
      
     
      <div class="form-group select-group">
      <label for="published">{{trans('home.published')}}:</label>
      <select class="form-control" name="published" id="published">
       <option value="1">{{trans('home.yes')}}</option>
        <option value="0">{{trans('home.no')}}</option>
      </select>
      </div>
     
      
      
  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('slideshowimages') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>
    {!! Form::close() !!}


@endsection

