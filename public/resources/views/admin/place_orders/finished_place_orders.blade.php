@extends('layouts.admin')

@section('content')
    {{--<div class="btns-top">--}}
        {{--<a class="btn btn-primary" href="{{ URL::to('place_orders/create') }}">إضافة فاتورة المبيعات</a>--}}
        {{--<a class="btn btn-warning" href="{{ url('/place_order/return') }}">إضافة مرتجع مبيعات</a>--}}
        {{--<button type="button" id="btn_finish" class="btn btn-danger">تم التسليم</button>--}}
    {{--</div>--}}

    <div class="btns-top">
        <form id="search">
            <div class="row">
                <div class="form-group col-md-3">
                </div>
                <div class="form-group col-md-3">
                    <br/>
                    <input type="submit" class="btn btn-warning" value="بحث">
                </div>
                <div class="form-group col-md-3">
                    <label for="to">إلى :</label>
                    <input class="form-control" type="date" name="to"/>
                </div>
                <div class="form-group col-md-3">
                    <label for="to"> من :</label>
                    <input class="form-control" type="date" name="from"/>
                </div>
            </div>
        </form>
    </div>
    <div class="row">
        <table width="100%" class="table table-bordered" id="products-table">
            <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>نوع الطلب</th>
                <th>تاريخ الطلب</th>
                <th>إسم العميل</th>
                <th>رقم العميل</th>
                <th>المنتجات</th>
                <th>السعر</th>
                <th>الكمية</th>
                <th>الخصم</th>
                <th>الإجمالي</th>
                <th>طريقة الدفع</th>
                <th>أدمن</th>
                <th>ملاحظات</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <hr/>

    <div class="row">
        <div class="col-md-12">
            <bdi> <span>
                   <span>إجمالى الفواتير</span>: <span id="saleOrdersTotal">{{$saleOrdersTotal}}</span>
            </span></bdi>
        </div>
        <div class="col-md-12">
            <bdi> <span>
                   <span>إجمالى المرتجعات</span>: <span id="returnOrdersTotal">{{$returnOrdersTotal}}</span>
            </span></bdi>
        </div>
        <div class="col-md-12">
            <bdi> <span>
                   <span>الفرق </span>: <span id="total">{{$total}}</span>
            </span></bdi>
        </div>
    </div>

@stop

@push('scripts')
    <script>
        $(function () {

            loadTable();

            $('#search').on('submit', function (e) {
                e.preventDefault();
                if ($.fn.DataTable.isDataTable('#products-table')) {
                    $('#products-table').DataTable().destroy();
                }
                loadTable('?' + $(this).serialize());
                $.ajax({
                    url: '{{url('/place_order/finished')}}',
                    method: 'GET',
                    data: $(this).serialize() + '&json=1',
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    success: function (response) {
                        $('#saleOrdersTotal').text(response.data.saleOrdersTotal);
                        $('#returnOrdersTotal').text(response.data.returnOrdersTotal);
                        $('#total').text(response.data.total);
                    }
                });
            });
        });

        function loadTable(query = null) {
            $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{url('/place/order/finished/data')}}' + ((query !== null) ? query : ''),
                columns: [
                    {data: 'number', name: 'number'},
                    {data: 'type', name: 'type', orderable: false, searchable: false},
                    {data: 'date', name: 'date' , searchable: false},
                    {data: 'customer_name', name: 'customer_name', orderable: false, searchable: true},
                    {data: 'customer_phone', name: 'customer_phone', orderable: false, searchable: true},
                    {data: 'products', name: 'products'},
                    {data: 'price', name: 'price' , searchable: false},
                    {data: 'quantities', name: 'quantities' , searchable: false},
                    {data: 'discount', name: 'discount' , searchable: false},
                    {data: 'total_price', name: 'total_price'},
                    {data: 'paymethod', name: 'paymethod' , searchable: false},
                    {data: 'admin', name: 'admin' , searchable: false},
                    {data: 'notes', name: 'notes' , searchable: false},
                ],
                order: [[1, 'desc']]
            });
        }

    </script>
@endpush