@extends('layouts.admin')

@section('content')


    {!! Form::open(['id' => 'formSubmit','data-toggle'=>'validator', 'files'=>'true']) !!}

    <div id="display_customer">

        <div class="row">
            <div class="form-group col-md-6">
            </div>
            <div class="form-group col-md-6">
                <label>رقم الطلب</label>
                <input type="text" id="number" name="number" class="form-control"
                       placeholder="رقم الطلب"/>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
            </div>
            <div class="form-group col-md-3">
                <br/>
                <input type="button" id="getOrder" value="إبحث" class="form-control btn btn-primary"/>
            </div>
        </div>
    </div>
    <br>

    <hr/>
    <div id="tableDiv">

    </div>
    <hr/>


    <div class="btns-bottom">
        <button id="submitBtn" type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
    </div>

    {!! Form::close() !!}


@endsection

@section('script')
    <script src="{{URL::to('resources/assets/back/js/jquery.loader.js')}}"></script>
    <script type="text/javascript">
        let productsIds = [];
        let quantities = [];

        $(function () {

            $('#getOrder').on('click' ,function (e) {
                let number = $('#number').val();
                if (number.length >= 4) {
                    $.loader({
                        className: "blue-with-image-2",
                        content: ''
                    });
                    let form = new FormData();
                    form.append('number', number);
                    $.ajax({
                        url: '{{url('/get/place_order')}}',
                        method: 'POST',
                        data: form,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        success: function (response) {
                            $('#tableDiv').html(response);
                            $.loader('close');
                        },
                        error: function () {
                            $.loader('close');
                        }
                    });
                }
            });

            $('#formSubmit').on('submit', function (e) {
                e.preventDefault();
                saveOrder();
            });

        });


        function saveOrder() {
            $.loader({
                className: "blue-with-image-2",
                content: ''
            });

            let form = new FormData();
            console.log(productsIds);
            form.append('productsIds[]', productsIds);
            form.append('quantities[]', quantities);

            $.ajax({
                url: '{{url('/place_order/return/save')}}',
                method: 'POST',
                data: form,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                success: function (response) {
                    $('#tableDiv').html('');
                    productsIds = [];
                    quantities = [];
                    $('#number').val('');
                    $.loader('close');
                },
                error: function () {
                    $.loader('close');
                }
            });
        }


        function changeQuantity(item, id) {
            let max = $(item).attr('max');
            let value = $(item).val();
            if(parseInt(value) > parseInt(max)){
                $(item).val(max);
            }

            let productIndex = productsIds.indexOf(id);
            if (productIndex !== -1)
                quantities[productIndex] = value;
        }

        function onchangeProduct(item, id) {
            if ($(item).is(':checked')) {
                productsIds.push(id);
                quantities.push($('#amount_' + id).val());
                $(item).parent('td').parent('tr').css('background', '#dedede');
            } else {
                let productIndex = productsIds.indexOf(id);
                if (productIndex !== -1) {
                    productsIds.splice(productIndex, 1);
                    quantities.splice(productIndex, 1);
                    $(item).parent('td').parent('tr').css('background', 'white');
                }
            }
        }

    </script>

@endsection


@section('style')
    <link href="{{URL::to('resources/assets/back/css/jquery.loader.css')}}" rel="stylesheet"/>
@stop