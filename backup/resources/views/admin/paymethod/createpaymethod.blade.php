@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'paymethods.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}

  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" required >

  </div>

<div class="form-group">
  <input type="text" class="form-control" placeholder="{{trans('home.name_en')}}" name="name_en" required >
</div>

  <div class="form-group">
    <input type="number" min="0" class="form-control" placeholder="{{trans('home.price')}}" name="price" required >
  </div>

  <div class="form-group">
    <label for="photo">{{trans('home.photo')}} :</label>
    <input type="file" class="form-control" name="photo" id="photo" >
  </div>

  <div class="form-group select-group">
    <select class="form-control" name="status" id="status" required>
      <option></option>
      <option value="1">{{trans('home.yes')}}</option>
      <option value="0">{{trans('home.no')}}</option>
    </select>
  </div>

  <div class="form-group">
    <label for="details">{{trans('home.details')}} :</label>
    <textarea class="form-control area1" name="details" placeholder="{{trans('home.details')}}" ></textarea>
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