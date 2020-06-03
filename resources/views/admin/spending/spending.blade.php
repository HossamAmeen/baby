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

    <div class="btns-bottom">
        <button id="displaybtn" class="btn btn-default">{{trans('home.display')}}</button>

        <a href="{{url('/spending/add')}}" class="btn btn-primary">إضافة</a>
    </div>

    <div id="displaycontent">
        <table class="table table-striped table-bordered table-hover" id="datatable">
            <thead>
            <tr>
                <th>التاريخ</th>
                <th>المبلغ</th>
                <th>مصاريف</th>
                <th>ملاحظات</th>
            </tr>
            <tbody>
            </tbody>
        </table>
    </div>

<div class="row">
   <bdi>
       <span>إجمالى المصاريف الأساسيه :</span>
       <span id="mainSpendings">{{$mainSpending}}</span>
   </bdi>
</div>
    <div class="row">
        <bdi>
            <span>جمالى المصاريف الفرعية :</span>
            <span id="subSpendings">{{$subSpending}}</span>
        </bdi>
    </div>
    <div class="row">
        <bdi>
            <span>الإجمالى :</span>
            <span id="total">{{$spendings}}</span>
        </bdi>
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

        $('#displaybtn').on('click',function (e) {
            load();
            var $startdate = $('#startdate').val();
            var $enddate = $('#enddate').val();

            if ($startdate != '' && $enddate != '') {
                if ($startdate <= $enddate) {
                    let data = {
                        startdate: $startdate,
                        enddate: $enddate,
                    };
                    $.ajax({
                        url: '{{url('/spending')}}?'+$.param(data) + '&json=1',
                        method: 'GET',
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        success: function (response) {
                            $('#total').text(response.data.spendings);
                            $('#subSpendings').text(response.data.subSpending);
                            $('#mainSpendings').text(response.data.mainSpending);

                        }
                    });
                }
            }
        });


        function load() {
            var $startdate = $('#startdate').val();
            var $enddate = $('#enddate').val();

            if ($startdate != '' && $enddate != '') {
                if ($startdate <= $enddate) {
                    let data = {
                        startdate: $startdate,
                        enddate: $enddate,
                    };
                    if ($.fn.DataTable.isDataTable('#datatable')) {
                        $('#datatable').DataTable().destroy();
                    }
                    $('#datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: '{{url('/spending/data')}}?' + $.param(data),
                        columns: [
                            {data: 'date', name: 'date'},
                            {data: 'amount', name: 'amount'},
                            {data: 'spending', name: 'spending'},
                            {data: 'notes', name: 'notes'},
                        ],
                    });
                }
            }
        }


    </script>

@endsection