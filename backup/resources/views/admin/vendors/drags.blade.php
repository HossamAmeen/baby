@extends('layouts.admin')

@section('content')


<ul class="nav nav-tabs">
    <li><a href="{{ route('vendors.edit', $vendor->id) }}">{{trans('home.vendordata')}}</a></li>
    <li><a href="{{ route('vendor_orders',['id' => $vendor->id]) }}">{{trans('home.orders')}}</a></li>
    <li class="active"><a href="{{ route('vendor_drags',['id' => $vendor->id]) }}">{{trans('home.withdrows')}}</a></li>
  </ul>

  <div class="btns-top">
  
  <button type="button" name="btn_agree" id="btn_agree" class="btn btn-primary">{{trans('home.agree')}}/{{trans('home.disagree')}}</button>
  </div>
    
      <table  class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
          <th><input type="checkbox" id="checkAll"/></th>
          <th>{{trans('home.id')}}</th>
          <th>{{trans('home.date')}}</th>
          <th>{{trans('home.status')}}</th>
          <th>{{trans('site.withdrowamount')}}</th>
          <th>{{trans('site.remain')}}</th>
          
          
        </thead>
        <tbody>
          @foreach ($drags as $drag)
              <tr id="{{$drag->id}}">
                <td><input type="checkbox" name="checkbox"  value="{{$drag->id}}" /> </td>
                <td> {{ $drag->id }}</td>
                <td>{{ $drag->date }}</td>
                <td> {{ $drag->status }}</td>
                <td>{{ $drag->drag_amount }}</td>
                <td>{{ $drag->remain}}</td>
              </tr>
          @endforeach
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
                          url:"{{ url('vendor/drag/status') }}"+"/"+id,
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