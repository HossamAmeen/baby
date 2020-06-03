@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'artist/'.$artist->id,'files'=> true]) !!}


      <div class="form-group">
      <label for="name">{{trans('home.name')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" value="{{$artist->name}}" id="name" >
      </div>
      
	<div class="form-group">
	<label for="facebook">{{trans('home.facebook')}} :</label>
	<input type="text" class="form-control" placeholder="{{trans('home.facebook')}}" value="{{$artist->facebook}}" name="facebook"  >
	</div>
	<div class="form-group">
	<label for="twitter">{{trans('home.twitter')}} :</label>
	<input type="text" class="form-control" placeholder="{{trans('home.twitter')}}" value="{{$artist->twitter}}" name="twitter"  >
	</div>
	<div class="form-group">
	<label for="google">{{trans('home.google')}} :</label>
	<input type="text" class="form-control" placeholder="{{trans('home.google')}}" value="{{$artist->google}}" name="google"  >
	</div>
	<div class="form-group">
	<label for="pinterest">{{trans('home.pinterest')}} :</label>
	<input type="text" class="form-control" placeholder="{{trans('home.pinterest')}}" value="{{$artist->pinterest}}" name="pinterest"  >
	</div>
	<div class="form-group">
	<label for="instagram">{{trans('home.instagram')}} :</label>
	<input type="text" class="form-control" placeholder="{{trans('home.instagram')}}" value="{{$artist->instagram}}" name="instagram"  >
	</div>
  
      <div class="form-group">
      <label for="link">{{trans('home.link')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.link')}}" name="link" value="{{$artist->link}}" id="link" >
      </div>
      @if($artist->image)
      <div class="form-group">
        <span>{{trans('home.imageshow')}} :</span> <img src="{{url('\uploads\artist\resize200')}}\{{$artist->image}}" width="150" height="150">
      </div>
      @endif
      <div class="form-group">
       <label for="photo">{{trans('home.photo')}} :</label>
       <input type="file" class="form-control" name="photo" id="photo"  >
      </div>
     
      <div class="form-group select-group">
        <label for="status">{{trans('home.status')}} :</label>
        <select class="form-control" name="status" id="status" required>
          <option></option>
          <option @if($artist->status == '1') selected @endif value="1">{{trans('home.yes')}}</option>
          <option @if($artist->status == '0') selected @endif value="0">{{trans('home.no')}}</option>
        </select>
      </div>

      <div class="form-group select-group">
       <label for="user_id">{{trans('home.user')}} :</label>
       {!! Form::select('user_id',(['' => '']) + $user,$artist->user_id,['id' => 'user_id','class' => 'form-control','required']) !!}
      </div> 
      
      <div class="form-group">
      <label for="meta_keywords">{{trans('home.meta_keywords')}} :</label>
      <textarea class="form-control " name="meta_keywords" id="meta_keywords" >{!! $artist->meta_keywords !!}</textarea>
    </div>

    <div class="form-group">
      <label for="meta_description">{{trans('home.meta_description')}} :</label>
      <textarea class="form-control " name="meta_description" id="meta_description" >{!! $artist->meta_description !!}</textarea>
    </div>
  
  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('artist') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>
    {!! Form::close() !!}


@endsection
@section('script')
<script>
$('#status').select2({
  'placeholder' : 'أختار الحالة',
});
$('#user_id').select2({
  'placeholder' : 'أختار المستخدم',
});
</script>    
@endsection