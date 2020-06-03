@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'product-option/'.$option->id]) !!}



	  <div class="form-group">
        <label for="option">{{trans('home.option')}} :</label>
        <input type="text" class="form-control" value="{{ $option->option }}" name="option" id="option"  >
      </div>

       <div class="form-group">
        <label for="price">{{trans('home.price')}} :</label>
        <input type="text" class="form-control" value="{{ $option->price }}" name="price" id="price"  >
      </div>


      <div class="form-group select-group">
        <label for="type">{{trans('home.type')}} :</label>
        <select class="form-control" name="type" id="type" required>
          <option></option>
          <option @if($option->type == 'check') selected @endif value="check">{{trans('home.check')}}</option>
          <option @if($option->type == 'radio') selected @endif value="radio">{{trans('home.radio')}}</option>
        </select>
      </div>
     
      <div class="form-group select-group">
        <label for="status">{{trans('home.status')}} :</label>
        <select class="form-control" name="status" id="status" required>
          <option></option>
          <option @if($option->status == '1') selected @endif value="1">{{trans('home.yes')}}</option>
          <option @if($option->status == '0') selected @endif value="0">{{trans('home.no')}}</option>
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