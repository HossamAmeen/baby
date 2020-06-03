@extends('layouts.admin')

@section('content')

    <div class="form-group select-group">
        <label>{{trans('home.startdate')}} :</label>
        <input type="text" class="form-control date" placeholder="{{ trans('home.startdate') }}" name="startdate"
               id="startdate" required>
    </div>

    <div class="form-group select-group">
        <label>{{trans('home.enddate')}} :</label>
        <input type="text" class="form-control date" value="{{ date('Y-m-d') }}" name="enddate" id="enddate" required>
    </div>

    <div class="form-group select-group">
        <label>المبيعات :</label>
        <select class="form-control" name="ordered" id="ordered">
            <option value="1">كل المبيعات</option>
            <option value="2">الأكثر مبيعا</option>
            <option value="3">الأقل مبيعا</option>
        </select>
    </div>

    <div class="form-group select-group">
        <label>مكان الأوردر :</label>
        <select class="form-control" name="stock" id="stock">
            <option value="" readonly="">إختر</option>
            <option value="1">صفحة</option>
            <option value="2">محل</option>
            <option value="3">موقع</option>
        </select>
    </div>

    <div class="row" id="add_per" style="display: none">
        <span>عدا إذن الإضافة</span>
        <div class="row">
            <div class="form-group select-group">
                <label>من :</label>
                <input type="text" class="form-control date"  name="addStartDate"
                       id="addStartDate" required>
            </div>

            <div class="form-group select-group">
                <label>إلى :</label>
                <input type="text" class="form-control date" name="addEndDate" id="addEndDate" required>
            </div>
        </div>
    </div>



    <div class="btns-bottom">
        <button id="displaybtn" class="btn btn-default">{{trans('home.display')}}</button>
        <button id="printbtn" class="btn btn-default">{{trans('home.print')}}</button>
    </div>

    <div id="displaycontent">

    </div>

@endsection

@section('script')
    <script src="{{URL::to('resources/assets/back/js/jquery.loader.js')}}"></script>
    <script>
        $('#ordered').select2();
        $('#stock').select2();

        $('#ordered').on('change' , function () {
            let ordered = $(this).find('option:selected').val();
            if(parseInt(ordered) === 3){
                $('#add_per').show();
            }else{
                $('#add_per').hide();
            }
        })

        $(".date").datepicker({
            dateFormat: "yy-mm-dd"
        });

        $('#displaybtn').click(function () {

            var $startdate = $('#startdate').val();
            var $enddate = $('#enddate').val();
            var ordered = $('#ordered option:selected').val();
            var stock = $('#stock option:selected').val();

            var addStartDate = $('#addStartDate').val();
            var addEndDate = $('#addEndDate').val();

            if ($startdate !== '' && $enddate !== '') {
                if ($startdate <= $enddate) {
                    $.loader({
                        className: "blue-with-image-2",
                        content: ''
                    });
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: " {{url('/products/report/data')}}",
                        method: 'POST',
                        data: {
                            startdate: $startdate,
                            enddate: $enddate,
                            ordered: ordered,
                            stock: stock,
                            addEndDate: addEndDate,
                            addStartDate: addStartDate
                        },
                        success: function (data) {
                            $('#displaycontent').html(data);
                            $('#productsReportdatatable').DataTable();
                            $.loader('close');
                        },
                        error: function () {
                            $.loader('close');
                        }
                    });

                }
                else {
                    alert("uncorrect choice of dates");
                }
            }
            else {
                alert("choice start date and end date");
            }

        });

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
    </script>


@endsection

@section('style')
    <link href="{{URL::to('resources/assets/back/css/jquery.loader.css')}}" rel="stylesheet"/>
@stop