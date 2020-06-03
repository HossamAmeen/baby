@extends('layouts.admin')

@section('content')


{!! Form::open(['route' => 'coupon.store', 'data-toggle'=>'validator']) !!}



  <div class="form-group">
    <input type="text" class="form-control" placeholder="{{trans('home.name')}}" name="name" required >
  </div>

  <div class="form-group">
    <input type="text" class="form-control" value="{{$code}}" placeholder="{{trans('home.code')}}" name="code" required >
  </div>
  
  <div class="form-group">
    <input type="number" step="1" min="0" class="form-control" placeholder="{{trans('home.mini_order')}}" name="mini_order" required >
  </div>

  <div class="form-group">
    <input type="text" class="form-control date" placeholder="{{ trans('home.expire_date') }}" name="expire_date" >
  </div>

  <div class="form-group">
    <input type="number" step="1" min="0" class="form-control" placeholder="{{ trans('home.value') }}" name="value" >
  </div>

  <div class="form-group">
    <input type="number" step="1" min="0" class="form-control" placeholder="{{ trans('home.percent') }}" name="percent" >
  </div>

  <div class="form-group select-group">
    <select class="form-control" name="type" id="type" required>
      <option></option>
      <option value="product">{{trans('home.product')}}</option>
      <option value="user">{{trans('home.user')}}</option>
      <option value="general">{{trans('home.general')}}</option>
      <option value="shipping">{{trans('home.region')}}</option>
      <option value="category">{{trans('home.category')}}</option>
    </select>
  </div>

   <div class="form-group select-group typecoupon">
   </div>

  <div class="btns-bottom">
    <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
    <a href="{{ url('coupon') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
  </div>

{!! Form::close() !!}


@endsection

@section('script')
<script>
$('#type').select2({
  'placeholder' : 'أختار Type',
});
$(function() {
  function Type(){
    $('#user_id').select2({
      'placeholder' : 'أختار المستخدم',
    });
    $('#product_id').select2({
      'placeholder' : 'أختار المنتج',
    });
    $('#region_id').select2({
      'placeholder' : '{{ trans('home.regions') }}',
    });
    $('#category_id').select2({
      'placeholder' : '{{ trans('home.category') }}',
    });

      $('#count').select2({
      });
  }
    $( ".date" ).datepicker({
     dateFormat: "yy-mm-dd"
    });
    $('#type').on('change', function(){
    var type =  $(this).val();
    
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        $.ajax({
              url:" {{url('changetypecoupon')}}",
              method:'POST',
              data:{type:type},
              success:function(data)
              {
                $('.typecoupon').html(data);
                Type();
              }
         });


    });
});
</script>    
@endsection