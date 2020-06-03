@extends('layouts.admin')

@section('content')

    <div class="form-group col-md-6">
        <label for="to">إسم المخزن :</label>
        <select class="form-control" name="to" id="to" required>
            <option value="0" selected> المخزن الرئيسي</option>
            <option value="1">المخزن الفرعى</option>
            <option value="2">المحل</option>
        </select>
    </div>

    <div class="form-group col-md-6">
        <label>كود المنتج</label>
        <input type="text" id="number" name="number" class="form-control"
               placeholder="كود المنتج"/>
    </div>


    <div id="displaycontent">
        <table>
            <thead>
            <tr>
                <th>كود</th>
                <th>الكمية</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <div style="margin-top: 15px;" class="row btns-bottom">
        <button id="printbtn" class="btn btn-default">{{trans('home.print')}}</button>

        <button id="inventory" class="btn btn-primary">جرد</button>
        <button id="do_inventory" class="btn btn-success" disabled>تطبيق جرد الباركود</button>

        <button id="trash" class="btn btn-danger">جرد جديد</button>
    </div>

@endsection


@section('script')
    <script src="{{URL::to('resources/assets/back/js/jquery.loader.js')}}"></script>
    <script src="{{URL::to('resources/assets/back/js/sweet-alert.min.js')}}"></script>
    <script>
        let products = [];
        $('#to').select2({
            'placeholder': 'أختار',
        });

        $('#number').keyup(function (e) {
            if (e.keyCode === 13) {
                let number = $('#number').val();
                if (number.length >= 4) {
                    $('#to').attr('disabled', true);

                    let productIndex = products.findIndex( (product) => {
                        return product.code === number;
                    });
                    if (productIndex === -1){
                        products.push({
                            code: number,
                            quantity: 1
                        });
                        addRow(products, products.length - 1);
                    }else{
                        products[productIndex].quantity = products[productIndex].quantity + 1;
                        updateRow(products, productIndex);
                    }
                    $('#number').val('');
                }
            }
        });

        $('#inventory').on( 'click' ,function (e) {
            e.preventDefault();
            if(products.length > 0){
                let productsJson = JSON.stringify(products);
                $.loader({
                    className: "blue-with-image-2",
                    content: ''
                });
                $('#number').attr('readonly' , true);
                let stock = $('#to').find('option:selected').val();
                let form = new FormData();
                form.append('products', productsJson);
                form.append('stock', stock);
                $.ajax({
                    url: '{{url('/inventory/report/inventory')}}',
                    method: 'POST',
                    data: form,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    success: function (response) {
                        $('#inventory').attr('disabled' , true);
                        $('#do_inventory').attr('disabled' , false);
                        $('#displaycontent').html(response);
                        $('#inventory_table').dataTable();
                        $.loader('close');
                    },
                    error: function () {
                        $.loader('close');
                    }
                });
            }
        });

        $('#do_inventory').on( 'click' ,function (e) {
            e.preventDefault();
            swal({
                title: 'جرد',
                text: 'هل أنت متأكد ؟',
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: 'نعم',
                cancelButtonText: 'لا',
                closeOnConfirm: false
            }, function () {
                if(products.length > 0){
                    let productsJson = JSON.stringify(products);
                    $.loader({
                        className: "blue-with-image-2",
                        content: ''
                    });
                    swal.close();
                    let stock = $('#to').find('option:selected').val();
                    let form = new FormData();
                    form.append('products', productsJson);
                    form.append('stock', stock);
                    $.ajax({
                        url: '{{url('/inventory/report/apply')}}',
                        method: 'POST',
                        data: form,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        success: function (response) {
                            $('#inventory').attr('disabled' , true);
                            $('#do_inventory').attr('disabled' , true);
                            $('#displaycontent').html(response);
                            $('#inventory_table').dataTable();
                            $.loader('close');
                            products = [];
                        },
                        error: function () {
                            $.loader('close');
                        }
                    });
                }
            });

        });

        $('#trash').on('click' , function (e) {
            e.preventDefault();
            $('#inventory').attr('disabled' , false);
            $('#do_inventory').attr('disabled' , true);
            products = [];
            $('#number').attr('readonly' , false);
            $('#to').attr('disabled', false);
            $('#displaycontent').html('<table>' +
                '            <thead>' +
                '            <tr>' +
                '                <th>كود</th>' +
                '                <th>الكمية</th>' +
                '            </tr>' +
                '            </thead>' +
                '            <tbody>' +
                '' +
                '            </tbody>' +
                '        </table>');
        });

        function addRow(products, position) {
            let tr = '<tr>' +
                     '<td>' + products[position].code +'</td>'+
                     '<td>' + products[position].quantity +'</td>'+
                     '</tr>';
            console.log(tr);
            $('table tbody').append(tr);
        }

        function updateRow(products, position) {
            let tr =
                '<td>' + products[position].code +'</td>'+
                '<td>' + products[position].quantity +'</td>';

            $('table tbody tr').eq(position).html(tr);
        }


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
    <link href="{{URL::to('resources/assets/back/css/sweet-alert.css')}}" rel="stylesheet" type="text/css"/>
@stop