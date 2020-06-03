@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'currencies.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}


  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" required >
  </div>
  
  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.name_ar')}}" name="name_ar" required >
  </div>

  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.code')}}" name="code" required >
  </div>

  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.symbole')}}" name="symbole" >
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
    <a href="{{ url('currencies') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
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