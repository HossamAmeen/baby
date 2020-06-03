@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'order-status.store', 'data-toggle'=>'validator']) !!}


    <div class="form-group select-group">
        <select class="form-control" name="status" id="status" required>
            <option></option>
            <option value="pending">{{trans('home.pending')}}</option>
            <option value="confirmed">{{trans('home.confirmed')}}</option>
            <option value="shipped">{{trans('home.shipped')}}</option>
            <option value="delivered">{{trans('home.delivered')}}</option>
            <option value="cancelled">{{trans('home.cancelled')}}</option>
            <option value="refundded">{{trans('home.refundded')}}</option>
        </select>
    </div>
     
      
  <div class="btns-bottom">
      <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
      <a href="{{ url('order-status') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>
    {!! Form::close() !!}


@endsection
@section('script')
<script>
    $('#status').select2({
        placeholder: 'أختار الحالة'
    });
</script>
@endsection

