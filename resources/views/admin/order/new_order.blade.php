@extends('layouts.admin')
@section('content')

        <div class="row">
            <div class="col-lg-12">
                <h1>{{trans('home.store_orders')}}</h1>
            </div>
            <div class="col-lg-12">
                <table class="table table-striped table-bordered table-hover" id="datatable6">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{trans('home.date')}}</th>
                        <th>{{trans('home.status_date')}}</th>
                        <th>{{trans('home.name')}}</th>
                        <th>{{trans('home.address')}}</th>
                        <th>{{trans('home.phone')}}</th>
                        <th>{{trans('home.products')}}</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th>الشحن</th>
                        <th>{{trans('home.total')}}</th>
                        <th>مندوب</th>
                        <th>أدمن</th>
                        <th>أدمن إضافي</th>
                        <th>ملاحظات</th>
                        <th>الحالة</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('#datatable6').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{url('/store/orders/finished/data')}}',
                columns: [
                    {data: 'number', name: 'number'},
                    {data: 'date', name: 'date' , sortable : true, searchable: false},
                    {data: 'status_date', name: 'status_date' , sortable : true, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'address', name: 'address' , searchable: false},
                    {data: 'phone', name: 'phone'},
                    {data: 'products', name: 'products'},
                    {data: 'quantities', name: 'quantities' , searchable: false},
                    {data: 'price', name: 'price' , searchable: false},
                    {data: 'shipping_price', name: 'shipping_price' , searchable: false},
                    {data: 'total', name: 'total' , searchable: true},
                    {data: 'delivery', name: 'delivery' , searchable: false},
                    {data: 'admin', name: 'admin' , searchable: false},
                    {data: 'admin_name', name: 'admin_name' , searchable: false},
                    {data: 'notes', name: 'notes' , searchable: true},
                    {data: 'status', name: 'status' , searchable: false},
                ],
                order: [[1, 'desc']]
            });
        });



    </script>
@endsection