@extends('layouts.admin')

@section('content')

<div class="btns-top">
  <a class="btn btn-primary" href="{{ URL::to('products/create') }}" >{{trans('home.add')}}</a>
  {{--<button type="button" name="btn_delete" id="btn_delete1" class="btn btn-danger">{{trans('home.delete')}}</button>--}}
  <button type="button" name="btn_active" id="btn_active" class="btn btn-primary">{{trans('home.published')}}/{{trans('home.unpublished')}}</button>
  <!-- Check All: <input type="checkbox" id="checkAll"/> -->
  </div>
    <table class="table table-bordered" id="products-table">
        <thead>
            <tr>

                <th><input type="checkbox" id="checkAll"/></th>
                <th>ID</th>
                <th>{{trans('home.code')}}</th>
                <th>{{trans('home.title')}}</th>
                <th>{{trans('home.image')}}</th>
                <th>{{trans('home.brand')}}</th>
                <th>{{trans('home.status')}}</th>
                <th>{{trans('home.price')}}</th>
                <th>{{trans('home.in_stock')}}</th>
                <th>{{trans('home.ordered')}}</th>
                <th>{{trans('home.delivered')}}</th>
                
            </tr>
        </thead>
    </table>
@stop

@push('scripts')
<script>
$(function() {
    $('#products-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('datatables.data') !!}',
        columns: [
            {data: 'check', name: 'check',orderable: false, searchable: false},
            {data: 'id', name: 'id'},
            {data: 'code', name: 'code'},
            {data: 'title_ar', name: 'title_ar'},
            {data: 'image', name: 'image',orderable: false, searchable: false},
            {data: 'brand_id', name: 'brand'},
            {data: 'status', name: 'products.status'},
            {data: 'price', name: 'price'},
            {data: 'stock', name: 'stock'}, 
            {data: 'ordered', name: 'ordered'},
            {data: 'delivered', name: 'delivered'},
        ],
        order: [[1, 'desc']]
    });
});

 $(document).ready(function() {
            $('#btn_delete1').click(function() {

                var id = [];
               
                $(':checkbox:checked').each(function(i) {
                    id[i] = $(this).val();
                });
                if (id.length === 0) //tell you if the array is empty
                {
                    alert("Please Select atleast one checkbox");
                } else {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "products/" + id,
                        type: 'DELETE',
                        data: {
                            id: id
                        },
                        success: function() {
				location.reload();
                        }
                    });
                }

            });
        });
</script>
@endpush