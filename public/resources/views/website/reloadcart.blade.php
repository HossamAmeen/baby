@if(Auth::check())
<button class="btn btn-default dropdown-toggle outline" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> 
    <div class="fav"><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i><span id="cart-co">{{ count($cart) }}</span></div>
</button>
<ul class="dropdown-menu pull-right cart-shop" aria-labelledby="dropdownMenu1">
    <li>
        <table class="table table-striped">
            <tbody id="cartoutcart">
                @foreach($cart as $item)
                <tr id="tr_{{ $item->id }}">
                    <td class="text-center"><a @if($lan == 'ar') href="{{ url($item->Product->link) }}" @else href="{{ url($item->Product->link_en) }}" @endif><img src="{{ url('uploads/product/resize200') }}/{{ $item->Product->image }}" alt="{{ $item->Product->title }}" ></a> </td>
                    <td class="text-left"><a @if($lan == 'ar') href="{{ url($item->Product->link) }}" @else href="{{ url($item->Product->link_en) }}" @endif>@if($lan == 'ar') {{ $item->Product->title_ar }} @else {{ $item->Product->title }} @endif</a></td>
                    <td class="text-right">x {{ $item->count }}</td>
                    <td class="text-right">@if($item->Product->discount) {{ ((($item->Product->price - $item->Product->discount) + $item->optionprice) * $item->count) - $item->coupon_price }} @else {{ (($item->Product->price + $item->optionprice) * $item->count) - $item->coupon_price  }} @endif {{ $item->Product->Currency->symbol }}</td>
                    <td class="text-center"><button type="button" id="bb" data-id="{{ $item->id }}" title="Remove" class="btn btn-danger btn-xs removecart"><i class="fa fa-times removecart"></i></button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
         <a href="{{ url('my-cart') }}" ><button type="button" id="bb" title="My Cart" class="btn btn-danger btn-xs">{{ trans('site.cart') }}</button></a>
    </li>
</ul>
@else

<button class="btn btn-default dropdown-toggle outline" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> 
    <div class="fav"><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i><span id="cart-co">{{ count($cart) }}</span></div>
</button>
<ul class="dropdown-menu pull-right cart-shop" aria-labelledby="dropdownMenu1">
    <li>
        <table class="table table-striped">
            <tbody id="cartoutcart">
                @foreach($cart as $item)
                <tr id="tr_{{ $item->id }}">
                    <td class="text-center"><a @if($lan == 'ar') href="{{ url($item->Product->link) }}" @else href="{{ url($item->Product->link_en) }}" @endif><img src="{{ url('uploads/product/resize200') }}/{{ $item->Product->image }}" alt="{{ $item->Product->title }}" ></a> </td>
                    <td class="text-left"><a @if($lan == 'ar') href="{{ url($item->Product->link) }}" @else href="{{ url($item->Product->link_en) }}" @endif>@if($lan == 'ar') {{ $item->Product->title_ar }} @else {{ $item->Product->title }} @endif</a></td>
                    <td class="text-right">x {{ $item->count }}</td>
                    <td class="text-right">@if($item->Product->discount) {{ ((($item->Product->price - $item->Product->discount) + $item->optionprice) * $item->count) - $item->coupon_price }} @else {{ (($item->Product->price + $item->optionprice) * $item->count) - $item->coupon_price  }} @endif {{ $item->Product->Currency->symbol }}</td>
                    <td class="text-center"><button type="button" id="bb" data-id="{{ $item->id }}" title="Remove" class="btn btn-danger btn-xs removecartsession"><i class="fa fa-times removecartsession"></i></button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
         <a href="{{ url('my-cart') }}" ><button type="button" id="bb" title="My Cart" class="btn btn-danger btn-xs">{{ trans('site.cart') }}</button></a>
    </li>
</ul>

@endif

<script>
$('.removecartsession').on('click', function(){
    var id =  $(this).data('id');
        
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        $.ajax({
              url:" {{url('removecartsession')}}",
              method:'POST',
              data:{id:id},
              success:function(data)
              {
                  $('#tr_'+id+'').css('background-color', '#ccc');
                  $('#tr_'+id+'').fadeOut('slow');
                  $('#cart-co').html(data);
                  $('.tr_'+id+'').css('background-color', '#ccc');
                  $('.tr_'+id+'').fadeOut('slow');
                  if(data == 0){
                  $('.cart-shop').html('<li>The shopping cart is empty !!</li>');  
                  }
              }
         });
});
$('.removecart').on('click', function(){
    var id =  $(this).data('id');
        
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        $.ajax({
              url:" {{url('removecart')}}",
              method:'POST',
              data:{id:id},
              success:function(data)
              {
                  $('#tr_'+id+'').css('background-color', '#ccc');
                  $('#tr_'+id+'').fadeOut('slow');
                  $('#cart-co').html(data);
                  $('.tr_'+id+'').css('background-color', '#ccc');
                  $('.tr_'+id+'').fadeOut('slow');
                  if(data == 0){
                  $('.cart-shop').html('<li>The shopping cart is empty !!</li>');  
                  }
              }
         });
});
</script>