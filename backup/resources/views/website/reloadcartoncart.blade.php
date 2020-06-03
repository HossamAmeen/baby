@foreach($cart2 as $item)
            <tr class="tr_{{ $item->id }}">
            <td class="text-center"> <a @if($lan == 'ar') href="{{ url($item->Product->link) }}" @else href="{{ url($item->Product->link_en) }}" @endif><img src="{{ url('uploads/product/resize200') }}/{{ $item->Product->image }}" alt="{{ $item->Product->title }}" ></a> </td>
            <td class="text-left"><a @if($lan == 'ar') href="{{ url($item->Product->link) }}" @else href="{{ url($item->Product->link_en) }}" @endif>@if($lan == 'ar'){{ $item->Product->title_ar }} @else {{ $item->Product->title }} @endif</a><br>
                
                @foreach($optioncart as $item2)
                    @if($item2->cart_id == $item->id)
                        <small>{{ $item2->Option->type }}: {{ $item2->Option->option }}</small><br>
                    @endif
                @endforeach
            </td>
            <td class="text-left">{{ $item->Product->brand }}</td>
            <td class="text-left">
                <div class="input-group btn-block">
                <input type="text" name="quantity_{{ $item->id }}"  value="{{ $item->count }}" data-id="{{ $item->count }}"  class="form-control">
                    <span class="input-group-btn">
                        <button data-toggle="tooltip" title="" data-id="{{ $item->id }}" class="btn btn-primary quantity" data-original-title="Update"><i class="fa fa-refresh quantity"></i></button>
                        
                    </span>
                </div>
                @if($item->coupon_price)
                <div>{{ trans('site.coupon_price') }} :<span class="coupon_{{ $item->id }}">{{ $item->coupon_price }}</span> </div>
                @endif
             </td>
            <td class="text-right">@if($item->Product->discount) {{ $item->Product->price - $item->Product->discount }} @else {{ $item->Product->price }} @endif {{ $item->Product->Currency->symbol }}</td>
            @if($item->coupon_price)
            <td class="text-right">@if($item->Product->discount) {{ ((($item->Product->price - $item->Product->discount) + $item->optionprice ) *  $item->count) - $item->coupon_price  }}  @else {{ (($item->Product->price + $item->optionprice) * $item->count) - $item->coupon_price }}  @endif {{ $item->Product->Currency->symbol }}</td>
            @else
            <td class="text-right">@if($item->Product->discount) {{ (($item->Product->price - $item->Product->discount) + $item->optionprice ) *  $item->count  }}  @else {{ ($item->Product->price + $item->optionprice) * $item->count }}  @endif {{ $item->Product->Currency->symbol }}</td>
            @endif
            <td class="text-left">
                <div class="input-group btn-block">
                    <span class="input-group-btn">
                        <button type="button" data-toggle="tooltip" title="" class="btn btn-danger removecart" data-id="{{ $item->id }}"  data-original-title="Remove"><i class="fa fa-times-circle removecart"></i></button>
                    </span>
                </div>
                
             </td>
            </tr>
            @endforeach

<script>
$('.quantity').on('click', function(){
    var id =  $(this).data('id');
    var quantity =  $("input[name~='quantity_"+ id +"']").val();
    
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        $.ajax({
              url:" {{url('updatecart')}}",
              method:'POST',
              data:{id:id,quantity:quantity},
              success:function(data)
              {
                $('.tr_'+id).html('');  
                $('.tr_'+id).html(data);
                $('.counttop_'+id).html('x'+quantity);
                var pricebot = document.getElementsByClassName('pricebot_'+id)[0].innerHTML;
                $('.pricetop_'+id).html(pricebot);
                
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
               