@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'product-option.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}

  

  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.option')}}" name="option" required >
  </div>

  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.price')}}" name="price" >
  </div>

  <div class="form-group select-group">
    <select class="form-control" name="type" id="type" required>
      <option></option>
      <option value="check">{{trans('home.check')}}</option>
      <option value="radio">{{trans('home.radio')}}</option>
    </select>
  </div>

<div class="form-group select-group">
    <select class="form-control" name="status" id="status" required>
      <option></option>
      <option value="1">{{trans('home.yes')}}</option>
      <option value="0">{{trans('home.no')}}</option>
    </select>
  </div>

  <div class="btns-bottom">
    <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
    <a href="{{ url('product-option') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>

{!! Form::close() !!}


@endsection

@section('script')
<script>
$('#status').select2({
  'placeholder' : 'أختار الحالة',
});
$('#type').select2({
  'placeholder' : 'أختار النوع',
});
</script>    
@endsection