@extends('layouts.admin')

@section('content')

    <div class="row">
        <table class="table table-bordered" id="products-table">
            <thead>
            <tr>
                <th>رقم الشكوى</th>
                <th>إسم العميل</th>
                <th>رقم الهاتف</th>
                <th>رقم الطلب</th>
                <th>الشكوى</th>
                <th>الملاحظات</th>
                <th>الحالة</th>
                <th>التاريخ</th>
                <th>الأدمن</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

@stop

@push('scripts')
    <script>
        let ids = [];
        $(function () {
            loadTable();

        });


        function loadTable() {
            $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{url('/complaints/report/data')}}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'phone', name: 'phone'},
                    {data: 'order_id', name: 'order_id'},
                    {data: 'message', name: 'message'},
                    {data: 'notes', name: 'notes'},
                    {data: 'status', name: 'status'},
                    {data: 'date', name: 'date'},
                    {data: 'admin', name: 'admin'},
                ],
                order: [[0, 'desc']]
            });
        }

    </script>
@endpush