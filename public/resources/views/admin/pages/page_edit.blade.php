@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'pages/'.$page->id]) !!}

    <div class="form-group">
        <label for="title">{{trans('home.title')}} :</label>
        <input type="text" class="form-control" name="title" value="{{$page->title}}" id="title" required >
    </div>
    
    <div class="form-group">
        <label for="title">{{trans('home.link')}} :</label>
        <input type="text" class="form-control" value="{{$page->link}}" name="link" id="link"  >
    </div>
    
    <div class="form-group select-group">
        <label for="lang">{{trans('home.lang')}} :</label>
        <select class="form-control" name="lang" id="lang">
           <option value="en" {{ ($page->lang == 'en')?'selected':'' }}>{{trans('home.en')}}</option>
      	   <option value="ar" {{ ($page->lang == 'ar')?'selected':'' }}>{{trans('home.ar')}}</option>
        </select>
    </div>
    
    <div class="form-group">
      <label for="text">{{trans('home.content')}} :</label>
      <textarea class="form-control area1" name="text" id="text" >{{ $page->text }}</textarea>
    </div>
  
  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('pages') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>
    {!! Form::close() !!}


@endsection

@section('script')
$('#lang').select2({
  'placeholder' : 'أختار اللغة',
});
@endsection