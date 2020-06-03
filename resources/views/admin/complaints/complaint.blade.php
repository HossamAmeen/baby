@extends('layouts.admin')

@section('content')

    <div class="btns-top">
        <a class="btn btn-primary" href="{{ url('/complaints/add') }}" >{{trans('home.add')}}</a>
        <button type="button" name="process_btn" id="process_btn" class="btn btn-warning">يتم الحل</button>
        <button type="button" name="solve_btn" id="solve_btn" class="btn btn-success">تم الحل</button>
    </div>
    <div class="row">
        <table class="table table-bordered" id="products-table">
            <thead>
            <tr>
                <th>رقم الشكوى</th>
                <th>#</th>
                <th>إسم العميل</th>
                <th>رقم الهاتف</th>
                <th>رقم الطلب</th>
                <th>الشكوى</th>
                <th>الملاحظات</th>
                <th>الحالة</th>
                <th>الأدمن</th>
                <th>التاريخ</th>
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

            $('#process_btn').on('click' , function (e) {
                e.preventDefault();
                if (ids.length > 0) {
                    let form = new FormData();
                    form.append('ids[]', ids);

                    $.ajax({
                        url: '{{url('/complaints/process')}}',
                        method: 'POST',
                        data: form,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        success: function (response) {
                            $('#products-table').DataTable().ajax.reload(null, false);
                            ids = [];
                        }
                    });
                }
            });

            $('#solve_btn').on('click' , function (e) {
                e.preventDefault();
                if (ids.length > 0) {
                    let form = new FormData();
                    form.append('ids[]', ids);

                    $.ajax({
                        url: '{{url('/complaints/solve')}}',
                        method: 'POST',
                        data: form,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        success: function (response) {
                            $('#products-table').DataTable().ajax.reload(null, false);
                            ids = [];
                        }
                    });
                }
            });
        });

        function onchangeSelected(item, id) {
            if ($(item).is(':checked')) {
                ids.push(id);
            }else{
                let index = ids.findIndex(function (itemId) {
                    return parseInt(itemId) === parseInt(id);
                });
                if(index !== -1){
                    ids.slice(index , 1);
                }
            }
        }

        function loadTable() {
            $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{url('/complaints/data')}}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'input', name: 'input', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'phone', name: 'phone'},
                    {data: 'order_id', name: 'order_id'},
                    {data: 'message', name: 'message'},
                    {data: 'notes', name: 'notes'},
                    {data: 'status', name: 'status'},
                    {data: 'admin', name: 'admin'},
                    {data: 'date' , name:'date'}
                ],
                order: [[0, 'desc']]
            });
        }

    </script>
@endpush