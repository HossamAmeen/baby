@extends('layouts.admin')
@section('content')

{!! Form::open(['method'=>'PATCH','url' => 'configurationsite/'.$con->id,'files'=>'true']) !!}
	
    <div class="form-group">
      <label for="configurationsite_name">{{trans('home.site_name')}} :</label>
      <input type="text" class="form-control" placeholder="{{trans('home.site_name')}}" value="{{$con->name}}" name="name" required>
    </div>

<div class="form-group">
    <label for="configurationsite_name">{{trans('home.mini_order')}} :</label>
    <input type="number" min="0" step="1" class="form-control" placeholder="{{trans('home.mini_order')}}" value="{{$con->min_order}}" name="min_order" required>
</div>
    
    <div class="form-group">
        <label for="short_description">{{trans('home.short_description')}} :</label>
        <textarea class="form-control area1" name="short_description" id="short_description" >{!! $con->short_description !!}</textarea>
    </div>

    <div class="form-group">
        <label for="short_description_en">{{trans('home.short_description')}} :</label>
        <textarea class="form-control area1" name="short_description_en" id="short_description_en" >{!! $con->short_description_en !!}</textarea>
    </div>

    @if($con->logo)
    <div class="form-group">
      <span>{{trans('home.imageshow')}} :</span> <img src="{{url('uploads/configurationsite/resize200/')}}/{{$con->logo}}" width="150" height="150">
    </div>
    @endif
    <div class="form-group">
      <label for="configurationsite_photo">{{trans('home.site_photo')}} :</label>
      <input type="file" class="form-control" name="photo" id="photo">
    </div>
    
    @if($con->imageall)
    <div class="form-group">
      <span>{{trans('home.imageshow')}} :</span> <img src="{{url('uploads/configurationsite/resize200/')}}/{{$con->imageall}}" width="150" height="150">
    </div>
    @endif
    <div class="form-group">
      <label>{{ trans('home.icon_image') }} :</label>
      <input type="file" class="form-control" name="photoall" id="photoall">
    </div>

    <div class="form-group">
      <label for="configurationsite_address">{{trans('home.site_address')}} :</label>
      <input type="text" class="form-control" placeholder="{{trans('home.site_address')}}" value="{{$con->address}}" name="address" >
    </div>

    <div class="form-group">
      <label for="email">{{trans('home.email')}} :</label>
      <input type="email" class="form-control" placeholder="{{trans('home.email')}}" value="{{$con->email}}" name="email" >
    </div>
    
    
    <div class="form-group">
      <label for="email">{{trans('home.emailforincome')}} :</label>
      <input type="email" class="form-control" placeholder="{{trans('home.email')}}" value="{{$con->email_ads}}" name="email_ads" >
    </div>

    <div class="form-group">
      <label for="fax">{{trans('home.fax')}} :</label>
      <input type="text" class="form-control" placeholder="{{trans('home.fax')}}" value="{{$con->fax}}" name="fax" >
    </div>

    <div class="form-group">
      <label for="configurationsite_telephone">{{trans('home.site_telephone')}} :</label>
      <input type="text" class="form-control" placeholder="{{trans('home.configurationsite_telephone')}}" value="{{$con->phone}}" name="phone" >
    </div>



    <div class="form-group">
      <label for="facebook">{{trans('home.facebook')}} :</label>
      <input type="text" class="form-control" placeholder="{{trans('home.facebook')}}" value="{{$con->facebook}}" name="facebook" >
    </div>

    <div class="form-group">
      <label for="twitter">{{trans('home.twitter')}} :</label>
      <input type="text" class="form-control" placeholder="{{trans('home.twitter')}}" value="{{$con->twitter}}" name="twitter" >
    </div>

    <div class="form-group">
      <label for="linkedin">{{trans('home.linkedin')}} :</label>
      <input type="text" class="form-control" placeholder="{{trans('home.linkedin')}}" value="{{$con->linkedin}}" name="linkedin" >
    </div>

    <div class="form-group">
      <label for="googleplus">{{trans('home.googleplus')}} :</label>
      <input type="text" class="form-control" placeholder="{{trans('home.googleplus')}}" value="{{$con->googleplus}}" name="googleplus" >
    </div>

    <div class="form-group">
      <label for="youtube">{{trans('home.youtube')}} :</label>
      <input type="text" class="form-control" placeholder="{{trans('home.youtube')}}" value="{{$con->youtube}}" name="youtube" >
    </div>

    <div class="form-group">
      <label for="instgram">{{trans('home.instgram')}} :</label>
      <input type="text" class="form-control" placeholder="{{trans('home.instgram')}}" value="{{$con->instgram}}" name="instgram" >
    </div>

    <div class="form-group">
      <label for="pinterest">{{trans('home.pinterest')}} :</label>
      <input type="text" class="form-control" placeholder="{{trans('home.pinterest')}}" value="{{$con->pinterest}}" name="pinterest" >
    </div>

    <div class="form-group">
      <label for="vimeo">{{trans('home.vimeo')}} :</label>
      <input type="text" class="form-control" placeholder="{{trans('home.vimeo')}}" value="{{$con->vimeo}}" name="vimeo" >
    </div>



    <div class="form-group select-group">
        <label for="lang">{{trans('home.lang')}} :</label>
        <select class="form-control" name="lang" id="lang" required>
          <option></option>
          <option @if($con->default_lang == 'en') selected @endif value="en">{{trans('home.en')}}</option>
          <option @if($con->default_lang == 'ar') selected @endif value="ar">{{trans('home.ar')}}</option>
        </select>
      </div>


    <div class="form-group">
      <label for="header_code">{{trans('home.header_code')}} :</label>
      <textarea class="form-control" name="header_code" placeholder="{{trans('home.header_code')}}" >{!! $con->header_code !!}</textarea>
    </div>
    
    <div class="form-group">
      <label for="footer_code">{{trans('home.footer_code')}} :</label>
      <textarea class="form-control" name="footer_code" placeholder="{{trans('home.footer_code')}}" >{!! $con->footer_code !!}</textarea>
    </div>
    
    @if($con->ads_image)
    <div class="form-group">
      <span>{{trans('home.imageshow')}} :</span> <img src="{{url('uploads/configurationsite/resize200/')}}/{{$con->ads_image}}" width="150" height="150" alt="bbb">
    </div>
    @endif
    <div class="form-group">
      <label>{{ trans('home.ads_image') }} :</label>
      <input type="file" class="form-control" name="ads_image" id="ads_image">
    </div>
    
    <div class="form-group">
      <label for="ads_link">{{trans('home.ads_link')}} :</label>
      <input type="url" class="form-control" placeholder="{{trans('home.ads_link')}}" value="{{$con->ads_link}}" name="ads_link" required>
    </div>

    <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('configurationsite') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
    </div>
{!! Form::close() !!}

@endsection
@section('script')
<script>
$('#lang').select2({
  'placeholder' : 'أختار اللغة',
});
</script>
@endsection

