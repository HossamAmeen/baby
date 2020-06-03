@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'slideshowimages/'.$si->id,'files'=>'true']) !!}


<!--
      <div class="form-group">
      <label for="slideshowimage_title">{{trans('home.slideshowimage_title')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.slideshowimage_title')}}" name="slideshowimage_title" value="{{$si->title}}" id="slideshowimage_title"  >
      </div>
-->
     
       <div class="form-group">
      <label for="slideshowimage_link">{{trans('home.slideshowimage_link')}} :</label>
        <input type="text" class="form-control" value="{{$si->link}}" placeholder="{{trans('home.slideshowimage_link')}}" name="slideshowimage_link" >
      </div> 
      <div class="form-group">
        <span>{{trans('home.imageshow')}} :</span> <img src="{{url('\uploads\slideshow\resize200')}}\{{$si->name}}" width="150" height="150">
      </div>
      <div class="form-group">
        <label for="slideshow_photo">{{trans('home.slideshow_photo')}} :</label>
        <input type="file" class="form-control" name="slideshow_photo" id="slideshow_photo">
      </div>

<!--
      <div class="form-group">
     <label for="slideshowimage_text">{{trans('home.slideshowimage_text')}} :</label>
        <textarea class="form-control area1" name="slideshowimage_text" id="slideshowimage_text" >{!! $si->text !!}</textarea>
     </div>
-->
       <div class="form-group select-group">
       <label for="slideshow">{{trans('home.slideshow')}} :</label>
        {!! Form::select('slideshow_id',($s),$si->slideshow_id,['class'=>'form-control']) !!}
      </div>
      <div class="form-group select-group">
      <label for="project">{{trans('home.project')}} :</label>
        {!! Form::select('project_id',(['0' => 'Select a Project'] + $pro),$si->project_id,['class'=>'form-control']) !!}
      </div>

       <?php
          echo '
            <div class="form-group select-group">
            <label for="published">'.trans('home.published').':</label>
            <select class="form-control" name="published" id="published">';
            if ($si->published == 1) {
              echo '
                <option value="1" selected>'.trans('home.yes').'</option>
                <option value="0">'.trans('home.no').'</option>';
            }else{
                echo '
                <option value="1">'.trans('home.yes').'</option>
                <option value="0" selected>'.trans('home.no').'</option>';
              }
            
            echo '
            </select>
        </div> ';
        ?>
     

  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('slideshowimages') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>
    {!! Form::close() !!}


@endsection
