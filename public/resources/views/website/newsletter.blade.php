@extends('layouts.admin')

@section('content')

    <div class="btns-top">
        <button type="button"   class="btn btn-danger delete-newsletter">{{trans('home.delete')}}</button>
    </div>
        
    <table  class="table table-striped table-bordered table-hover" id="datatable">
        <thead>
            <th><input type="checkbox" id="checkAll"/></th>
            <th>{{trans('home.name')}}</th>
            <th>{{trans('home.email')}}</th>
            <th>{{trans('home.phone')}}</th>
            <th>{{trans('home.date')}}</th>
        </thead>
        <tbody>
            @foreach ($newsletter as $x)
                <tr id="{{$x->id}}">
                    <td> <input type="checkbox" name="checkbox"  value="{{$x->id}}" /> </td>
                    <td>{{ $x->name }}</td>
                    <td>{{ $x->email }}</td>
                    <td>{{ $x->phone }}</td>
                    <td>{{ $x->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>   

@endsection
@section('script')
<script>
$(document).ready(function(){
      $('.delete-newsletter').click(function(){

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
                          url:"delete-newsletter",
                          type:'POST',
                          data:{id:id},
                          success:function()
                          {
                               for(var i=0; i<id.length; i++)
                               {
                                    $('tr#'+id[i]+'').css('background-color', '#ccc');
                                    $('tr#'+id[i]+'').fadeOut('slow');
                                    $('input:checkbox').removeAttr('checked');
                               }
                          }
                     });
                }


      });
 });
 </script>

@endsection