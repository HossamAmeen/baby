@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'paymethods/'.$pay->id,'files'=>'true']) !!}

    <div class="form-group">
        <label for="name">{{trans('home.name')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" value="{{$pay->name}}" id="name" required >
    </div>

<div class="form-group">
    <label for="name">{{trans('home.name_en')}} :</label>
    <input type="text" class="form-control" placeholder="{{trans('home.name_en')}}" name="name_en" value="{{$pay->name_en}}" id="name" required >
</div>

    <div class="form-group">
      <label for="price">{{trans('home.price')}} :</label>
    <input type="number" min="0" class="form-control" placeholder="{{trans('home.price')}}" value="{{$pay->price}}" name="price" required >
  </div>

  <div class="form-group">
    <span>{{trans('home.imageshow')}} :</span> <img src="{{url('\uploads\payment\resize200')}}\{{$pay->image}}" width="150" height="150">
  </div>
  <div class="form-group">
    <label for="photo">{{trans('home.photo')}} :</label>
    <input type="file" class="form-control" name="photo" id="photo"  >
  </div>
     
  <div class="form-group select-group">
    <label for="status">{{trans('home.status')}} :</label>
    <select class="form-control" name="status" id="status" required>
        <option></option>
        <option @if($pay->status == '1') selected @endif value="1">{{trans('home.yes')}}</option>
        <option @if($pay->status == '0') selected @endif value="0">{{trans('home.no')}}</option>
    </select>
  </div>

  <div class="form-group">
    <label for="details">{{trans('home.details')}} :</label>
    <textarea class="form-control area1" name="details" placeholder="{{trans('home.details')}}" >{!! $pay->details !!}</textarea>
  </div>
  
  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('paymethods') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>
    {!! Form::close() !!}


@endsection
@section('script')
<script>
$('#status').select2({
  'placeholder' : 'أختار الحالة',
});
</script>    
@endsection