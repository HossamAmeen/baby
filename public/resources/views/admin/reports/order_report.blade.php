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

    <div class="form-group">
        <label>{{trans('home.status_date')}} :</label>
        <input type="checkbox" name="status_date" id="status_date" required>
    </div>
    <br/>

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
        <label>{{trans('home.delivery')}} :</label>
        <select class="form-control" name="delivery" id="delivery">
            <option></option>
            @foreach($deliveries as $delivery)
                <option value="{{ $delivery -> id }}">{{ $delivery -> name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group select-group">
        <label>{{trans('home.products')}} :</label>
        <select class="form-control" name="product" id="product">
            <option></option>
            @foreach($products as $product)
                <option value="{{ $product -> id }}">{{ $product->code.' - '.$product -> title_ar }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group select-group">
        <label>{{trans('home.users')}} :</label>
        <select class="form-control" name="user" id="user">
            <option></option>
            @foreach($users as $user)
                <option value="{{ $user -> id }}">{{ $user -> name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group select-group">
        <label>طريقة الدفع :</label>
        <select class="form-control" name="pay_method" id="pay_method">
            <option></option>
            @foreach($methods as $method)
                <option value="{{ $method -> id }}">{{ $method -> name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group select-group">
        <label>أصل الطلب :</label>
        <select class="form-control" name="ordered" id="ordered">
            <option value="0">عام</option>
            <option value="1">موقع</option>
            <option value="2">محل</option>
            <option value="3">الصفحة من المحل</option>
            <option value="4">الصفحة</option>
        </select>
    </div>

    <div class="btns-bottom">
        <button id="displaybtn" class="btn btn-default">{{trans('home.display')}}</button>
        <button id="printbtn" class="btn btn-default">{{trans('home.print')}}</button>
    </div>

    <div id="displaycontent">

    </div>

@endsection


@section('script')
    <script>
        $('#status').select2({
            'placeholder': 'أختار الحالة',
        });
        $('#lang').select2({
            'placeholder': 'أختار اللغة',
        });
        $('#delivery').select2({
            'placeholder': 'إختر الموصل',
        });
        $('#product').select2({
            'placeholder': 'إختر المنتج',
        });
        $('#user').select2({
            'placeholder': 'إختر العميل ',
        });
        $('#pay_method').select2({
            'placeholder': 'إختر طريقة الدفع ',
        });

        $('#ordered').select2({
            'placeholder': 'إختر أصل الطلب ',
        });


        $(".date").datepicker({
            dateFormat: "yy-mm-dd"
        });

        $('#displaybtn').click(function () {

            var isChecked = $('#status_date:checked').val();

            if (isChecked === 'on')
                statusDate = 1;
            else
                statusDate = 0;

            var $startdate = $('#startdate').val();
            var $enddate = $('#enddate').val();
            var $status = $('#status option:selected').val();
            var $delivery = $('#delivery option:selected').val();
            var $user = $('#user option:selected').val();
            var $product = $('#product option:selected').val();
            var ordered = $('#ordered option:selected').val();
            var paymethod = $('#pay_method option:selected').val();
            if ($startdate != '' && $enddate != '') {
                if ($startdate <= $enddate) {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: " {{url('displayorders')}}",
                        method: 'POST',
                        data: {
                            startdate: $startdate,
                            enddate: $enddate,
                            statusDate: statusDate,
                            status: $status,
                            delivery: $delivery,
                            user: $user,
                            product: $product,
                            paymethod: paymethod,
                            ordered:ordered
                        },
                        success: function (data) {
                            $('#displaycontent').html(data);
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