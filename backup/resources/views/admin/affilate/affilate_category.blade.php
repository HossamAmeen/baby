@extends('layouts.admin')

@section('content')


<ul class="nav nav-tabs">
    <li><a href="{{ route('affilates.edit', $affilate ->id) }}">{{trans('home.affilatedata')}}</a></li>
    <li><a href="{{ route('affilate_orders',['id' => $affilate ->id]) }}">{{trans('home.orders')}}</a></li>
    <li><a href="{{ route('affilate_drags',['id' => $affilate ->id]) }}">{{trans('home.withdrows')}}</a></li>
    <li><a href="{{ route('affilate_products',['id' => $affilate ->id]) }}">{{trans('home.products')}}</a></li>
    <li class="active"><a href="{{ route('affilate_categories',['id' => $affilate ->id]) }}">{{trans('home.categories')}}</a></li>
  </ul><br>

  
    
      <table  class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
          <th>{{trans('home.title')}}</th>
          <th>{{trans('home.link')}}</th>
          <th>{{trans('site.expire_date')}}</th>
          <th>{{trans('site.visits')}}</th>
          <th>{{trans('site.orderscount')}}</th>
          
          
        </thead>
        <tbody>
          @if(count($categories))
	          @foreach($categories as $category)
	              <tr>
	                <td>@if($category -> category) {{$category -> category -> title }} @endif</td>
	                <td>{{ $category -> link }}</td>
	                <td> {{ $category -> expire_date }}</td>
	                <td>{{ $category -> visits }}</td>
	                <td>{{ $category -> orders }}</td>
	              </tr>
	          @endforeach
          @endif
        </tbody>
      </table>
   
    

@endsection

@section('script')

<script>

$(document).ready(function(){
      $('#btn_agree').click(function(){
                var id = [];
                $(':checkbox:checked').each(function(i){
                     id[i] = $(this).val();
                });
                if(id.length === 0) //tell you if the array is empty
                {
                     alert("Please Select atleast one checkbox");
                }
                else
                {
                    $.ajaxSetup({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                        });
                    $.ajax({
                          url:"{{ url('affilate/drag/status') }}"+"/"+id,
                          method:'POST',
                          data:{id:id},
                          success:function()
                          {
                            $('input:checkbox').removeAttr('checked');
                            location.reload();
                          }
                     });
                }


      });
      
      });

</script>

@endsection