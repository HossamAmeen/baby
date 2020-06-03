@extends('layouts.admin')


@section('content')
    <div class="row">
        <div class="col-md-6">
            <label>عدد التكرار</label>
            <input type="number" min="1" step="1" value="1" id="count" name="count" class="form-control"/>
        </div>
        <div class="form-group col-md-6">
            <label>كود المنتج</label>
            <input type="text" id="number" name="number" class="form-control"
                   placeholder="كود المنتج"/>
        </div>
    </div>
    <div id="section" class="row" style="display: none;">
        <div class="col-md-6">
            <label>السعر</label>
            <input type="number" readonly id="price" name="price" class="form-control"/>
        </div>
        <div class="form-group col-md-6">
            <label>إسم المنتج</label>
            <input type="text" id="title" name="title" class="form-control"
                   />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <button type="button" onclick="addProduct()" class="btn btn-primary">إضافة</button>
            <button type="button" onclick="print()" class="btn btn-success">طباعة</button>
            <button type="button" onclick="reset()" class="btn btn-danger">حذف</button>
        </div>
        <div class="col-md-6">

        </div>
    </div>


    <div class="row">
        <div id="print">

        </div>
    </div>
@stop


@section('script')
    <script src="{{ URL::to('resources/assets/back/js/printThis.js') }}"></script>
    <script>

        $(function () {
            $('#number').keydown(function (e) {
                if(e.keyCode === 13){
                    addProduct(1);
                }
            });
        });

        function addProduct(show = null) {
            let code = $('#number').val();
            if(code.length >= 4) {
                let form = new FormData();
                form.append('code', code);
                form.append('bar_code', '1');
                if(show === 1){
                    form.append('show' , '1');
                }else {
                    form.append('new_title' , $('#title').val());
                }
                $.ajax({
                    url: '{{url('/get/product/details')}}',
                    method: 'POST',
                    data: form,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    success: function (response) {
                        if(show === 1){
                            $('#section').show();
                            $('#title').val(response.data.title_ar);
                            $('#price').val(response.data.price);
                        }else {
                            let count = parseInt($('#count').val());
                            for (let index = 0; index < count; index++) {
                                $('#print').append(response);
                            }
                            $('#hide').hide();
                            $('#title').val('');
                            $('#price').val('');
                            $('#number').val('');
                            $('#count').val(1);
                        }
                    }
                });
            }
        }

        function print() {
            $('#print').printThis({
                importCSS: false,
                loadCSS: [
                    "{{ URL::to('resources/assets/print/css/print.css')}}"
                ]
            });
        }
        
        function reset() {
            $('#hide').hide();
            $('#title').val('');
            $('#price').val('');
            $('#number').val('');
            $('#count').val(1);
            $('#print').html('');
        }
    </script>
@endsection




@section('style')
    <link rel="stylesheet" href="{{ URL::to('resources/assets/print/css/print.css')}}">
@endsection