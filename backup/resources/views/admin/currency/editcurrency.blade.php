@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'currencies/'.$currency->id]) !!}


      <div class="form-group">
      <label for="name">{{trans('home.name')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" value="{{$currency->name}}" id="name" >
      </div>
      <div class="form-group">
      <label for="name_ar">{{trans('home.name_ar')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.name_ar')}}" name="name_ar" value="{{$currency->name_ar}}" id="name_ar" >
      </div>
      <div class="form-group">
      <label for="code">{{trans('home.code')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.code')}}" name="code" value="{{$currency->code}}" id="code" >
      </div>
      <div class="form-group">
      <label for="symbole">{{trans('home.symbole')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.symbole')}}" name="symbole" value="{{$currency->symbol}}" id="symbole" >
      </div>
     
      <div class="form-group select-group">
        <label for="status">{{trans('home.status')}} :</label>
        <select class="form-control" name="status" id="status" required>
          <option></option>
          <option @if($currency->status == '1') selected @endif value="1">{{trans('home.yes')}}</option>
          <option @if($currency->status == '0') selected @endif value="0">{{trans('home.no')}}</option>
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