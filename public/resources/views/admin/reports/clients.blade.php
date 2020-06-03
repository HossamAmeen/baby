@extends('layouts.admin')

@section('content')

    <div class="form-group select-group">
        <label>{{trans('home.startdate')}} :</label>
        <input type="text" class="form-control date" value="{{ date('Y-m-d') }}"
               placeholder="{{ trans('home.startdate') }}" name="startdate"
               id="startdate" required>
    </div>

    <div class="form-group select-group">
        <label>{{trans('home.enddate')}} :</label>
        <input type="text" class="form-control date" value="{{ date('Y-m-d') }}" name="enddate" id="enddate" required>
    </div>
    <div class="form-group select-group">
        <label>{{trans('home.status')}} :</label>
        <select class="form-control" name="status" id="status">
            <option></option>
            @foreach($status as $statu)
                <option value="{{ $statu -> id }}">{{ $statu -> name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group select-group">
        <label>أصل الطلب :</label>
        <select class="form-control" name="ordered" id="ordered">
            <option value="0">عام</option>
            <option value="1">موقع</option>
            <option value="2">محل</option>
            <option value="3">الصفحة</option>
        </select>
    </div>

    <div class="form-group select-group">
        <label>الأعمدة :</label>
        <select class="form-control" name="columns" id="columns">
            <option value="all">الكل</option>
            <option value="name">إسم العميل</option>
            <option value="phone">تليفون العميل</option>
            <option value="address">عنوان</option>
            <option value="email">البريد الإلكترونى</option>
            <option value="ordersCount">عدد الطلبات</option>
        </select>
    </div>

    <div class="form-group select-group">
        <label>السعر من :</label>
        <input type="number" class="form-control" min="0"
               placeholder="السعر من" name="fromPrice"
               id="fromPrice" required>
    </div>

    <div class="form-group select-group">
        <label>السعر إلى :</label>
        <input type="number" class="form-control" min="0"
               placeholder="السعر إلى" name="toPrice"
               id="toPrice" required>
    </div>

    <div class="btns-bottom">
        <button id="displaybtn" class="btn btn-default">{{trans('home.display')}}</button>
        <button id="exportbtn" class="btn btn-default">إستخراج</button>
    </div>

    <div id="displaycontent">
        <table class="table table-striped table-bordered table-hover" id="datatable">
            <thead>
            <tr>
                <th>إسم العميل</th>
                <th>تليفون العميل</th>
                <th>عنوان</th>
                <th>البريد الإلكترونى</th>
                <th>عدد الطلبات</th>
            </tr>
            <tbody>
            </tbody>
        </table>
    </div>


@endsection


@section('script')
    <script>

        $('#status').select2({
            placeholder: 'إختر الحالة'
        });
        $('#ordered').select2();
        $('#columns').select2();
        $(".date").datepicker({
            dateFormat: "yy-mm-dd"
        });

        load();

        $('#printbtn').click(function () {

            var mywindow = window.open('', 'PRINT', 'height=400,width=600');
            mywindow.document.write('<html><head><title>' + document.title + '</title>');
            mywindow.document.write('</head><body >');
            mywindow.document.write('<h1>' + document.title + '</h1>');
            mywindow.document.write(document.getElementById('displaycontent').innerHTML);
            mywindow.document.write('</body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/

            mywindow.print();
            mywindow.close();

            return true;
        });

        $('#exportbtn').on('click',function (e) {
            var $startdate = $('#startdate').val();
            var $enddate = $('#enddate').val();
            var $status = $('#status option:selected').val();
            var ordered = $('#ordered option:selected').val();
            var columns = $('#columns option:selected').val();
            var fromPrice = $('#fromPrice').val();
            var toPrice = $('#toPrice').val();
            if ($startdate != '' && $enddate != '') {
                if ($startdate <= $enddate) {
                    let data = {
                        startdate: $startdate,
                        enddate: $enddate,
                        status: $status,
                        ordered: ordered,
                        columns: columns,
                        export: 1,
                        fromPrice: fromPrice,
                        toPrice: toPrice
                    };
                    window.location.href = "{{url('/get/clients/data')}}?" + $.param(data);
                }
            }
        });

        $('#displaybtn').on('click',function (e) {
            load();
        });


        function load() {
            var $startdate = $('#startdate').val();
            var $enddate = $('#enddate').val();
            var fromPrice = $('#fromPrice').val();
            var toPrice = $('#toPrice').val();
            var $status = $('#status option:selected').val();
            var ordered = $('#ordered option:selected').val();
            if ($startdate != '' && $enddate != '') {
                if ($startdate <= $enddate) {
                    let data = {
                        startdate: $startdate,
                        enddate: $enddate,
                        status: $status,
                        ordered: ordered,
                        fromPrice: fromPrice,
                        toPrice : toPrice
                    };
                    if ($.fn.DataTable.isDataTable('#datatable')) {
                        $('#datatable').DataTable().destroy();
                    }
                    $('#datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: '{{url('/get/clients/data')}}?' + $.param(data),
                        columns: [
                            {data: 'name', name: 'name'},
                            {data: 'phone', name: 'phone'},
                            {data: 'address', name: 'address'},
                            {data: 'email', name: 'email'},
                            {data: 'ordersCount', name: 'ordersCount'},
                        ],
                    });
                }
            }
        }


    </script>

@endsection