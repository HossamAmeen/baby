@extends('layouts.admin')

@section('content')


{!! Form::open(['method'=>'PATCH','url' => 'coupon/'.$coupon->id,'data-toggle'=>'validator']) !!}


    <div class="form-group">
        <label for="name">{{trans('home.name')}} :</label>
        <input type="text" class="form-control" value="{{ $coupon->name }}" placeholder="{{trans('home.name')}}" name="name" required >
    </div>

    <div class="form-group">
        <label for="code">{{trans('home.code')}} :</label>
        <input type="text" class="form-control" value="{{ $coupon->code }}" placeholder="{{trans('home.code')}}" name="code" required >
    </div>
    
    <div class="form-group">
        <label for="code">{{trans('home.mini_order')}} :</label>
        <input type="number" step="1" min="0" class="form-control" value="{{ $coupon->mini_order }}" placeholder="{{trans('home.mini_order')}}" name="mini_order" required >
    </div>

    <div class="form-group">
        <label for="expire_date">{{trans('home.expire_date')}} :</label>
        <input type="text" class="form-control date" value="{{ $coupon->expire_date }}" placeholder="{{ trans('home.expire_date') }}" name="expire_date" >
    </div>

    <div class="form-group">
        <label for="value">{{trans('home.value')}} :</label>
        <input type="number" step="1" min="0" class="form-control" value="{{ $coupon->value }}" placeholder="{{ trans('home.value') }}" name="value" >
    </div>

    <div class="form-group">
        <label for="percent">{{trans('home.percent')}} :</label>
        <input type="number" step="1" min="0" class="form-control" value="{{ $coupon->percent }}" placeholder="{{ trans('home.percent') }}" name="percent" >
    </div>

    <div class="form-group select-group">
        <label for="type">{{trans('home.type')}} :</label>
        <select class="form-control" name="type" id="type" required>
            <option></option>
            <option @if($coupon->type == 'product') selected @endif value="product">{{trans('home.product')}}</option>
            <option @if($coupon->type == 'user') selected @endif value="user">{{trans('home.user')}}</option>
            <option @if($coupon->type == 'general') selected @endif value="general">{{trans('home.general')}}</option>
            <option @if($coupon->type == 'shipping') selected @endif value="shipping">{{trans('home.region')}}</option>
            <option @if($coupon->type == 'category') selected @endif value="category">{{trans('home.category')}}</option>
        </select>
    </div>
    @if($coupon->type == 'user')
    <label for="user_id">{{trans('home.user')}} :</label>
    <div class="form-group select-group typecoupon">
    	<select class="form-control" name="user_id[]" id="user_id" multiple>
            <option></option>
            @foreach($user as $x)
            <option value="{{ $x -> id }}"
            @foreach($couponuser as $y)
            @if($y == $x -> id)
            selected
            @endif
            @endforeach
            >{{ $x -> name }}</option>
            @endforeach
        </select>
    </div>
    @elseif($coupon->type == 'product')
    <label for="region_id">{{trans('home.product')}} :</label>
    <div class="form-group select-group typecoupon">
    <select class="form-control" name="product_id[]" id="product_id" multiple>
            <option></option>
            @foreach($product as $x)
            <option value="{{ $x -> id }}"
            @foreach($couponproduct as $y)
            @if($y == $x -> id)
            selected
            @endif
            @endforeach
            >{{ $x -> title_ar }}</option>
            @endforeach
        </select>
    </div>
    @elseif($coupon->type == 'product')
        <label for="region_id">{{trans('home.product')}} :</label>
        <div class="form-group select-group typecoupon">
            <select class="form-control" name="count" id="count" >
                @foreach($c as $b)
                    <option @if($b['id'] == $coupon->count) selected @endif value="{{$b['id']}}">{{$b['name']}}</option>
                @endforeach
            </select>
        </div>
    @elseif($coupon->type == 'category')
    <label for="category_id">{{trans('home.category')}} :</label>
    <div class="form-group select-group typecoupon">
    <select class="form-control" name="category_id[]" id="category_id" multiple>
            <option></option>
            @foreach($categories as $x)
            <option value="{{ $x -> id }}"
            @foreach($couponcategory as $y)
            @if($y == $x -> id)
            selected
            @endif
            @endforeach
            >{{ $x -> title }}</option>
            @endforeach
        </select>
    </div>
    @elseif($coupon->type == 'shipping')
    <label for="product_id">{{trans('home.regions')}} :</label>
    <div class="form-group select-group typecoupon">
    <select class="form-control" name="region_id[]" id="region_id" multiple>
            <option></option>
            @foreach($artist as $x)
            <option value="{{ $x -> id }}"
            @foreach($couponartist as $y)
            @if($y == $x -> id)
            selected
            @endif
            @endforeach
            >{{ $x -> name }}</option>
            @endforeach
        </select>
    </div>
    @else
    
    @endif
    <div class="btns-bottom">
        <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
        <a href="{{ url('coupon') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
    </div>
    {!! Form::close() !!}


@endsection
@section('script')
<script>
$('#user_id').select2({
  'placeholder' : 'أختار المستخدم',
});

$('#product_id').select2({
  'placeholder' : 'أختار المنتج',
});
$('#region_id').select2({
      'placeholder' : '{{ trans('home.regions') }}',
    });
$('#type').select2({
  'placeholder' : 'أختار Type',
});

$('#category_id').select2({
      'placeholder' : '{{ trans('home.category') }}',
    });
$(function() {
    $( ".date" ).datepicker({
     dateFormat: "yy-mm-dd"
    });
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
  }
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