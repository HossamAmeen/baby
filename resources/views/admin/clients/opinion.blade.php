@extends('layouts.admin')

@section('content')
    <form>
        <div class="row">
            <div class="form-group col-md-6">
                <label>رقم الهاتف</label>
                <input type="text" id="phone" name="phone" class="form-control"
                       placeholder="رقم الهاتف"/>
            </div>
        </div>

        <div class="row">
            <div class="form-group">
                <label>رقم الطلب</label>
                <input type="text" id="orderNumber" name="orderNumber" class="form-control"
                       placeholder="رقم الطلب"/>
            </div>
        </div>
    </form>

    <hr/>
    <div id="tableDiv">
        <div class="row">
            <table>
                <thead>
                <th>كود الصنف</th>
                <th>إسم الصنف</th>
                <th>الرأى</th>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <hr/>
    <form id="saveOpinion">
        <div id="pinions">
            <div class="form-group">
                <label>خدمة الشحن</label>
                <select id="charge_opinion">
                    <option readonly="">إختر</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </div>

            <div class="form-group">
                <label>خدمة العملاء</label>
                <select id="customer_service_opinion">
                    <option readonly="">إختر</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </div>

            <div class="form-group">
                <label> هل هتكررى التعامل معانا او هتنصحى زمايلك بالتعامل معانا ؟</label>
                <select id="again_opinion">
                    <option readonly="">إختر</option>
                    <option value="yes">نعم</option>
                    <option value="no">لا</option>
                </select>
            </div>

            <div class="form-group">
                <label>الإيجابيات</label>
                <textarea id="positives" name="positives" class="form-control"
                          placeholder="الإيجابيات"></textarea>
            </div>

            <div class="form-group">
                <label>السلبيات</label>
                <textarea id="negatives" name="negatives" class="form-control"
                          placeholder="السلبيات"></textarea>
            </div>

            <div class="form-group">
                <label>التعليق</label>
                <textarea id="comment" name="comment" class="form-control"
                          placeholder="التعليق"></textarea>
            </div>

            <div class="form-group">
                <label>ملاحظات</label>
                <textarea id="notes" name="notes" class="form-control"
                          placeholder="ملاحظات"></textarea>
            </div>

            <div class="btns-bottom">
                <button id="submitBtn" type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script src="{{URL::to('resources/assets/back/js/jquery.loader.js')}}"></script>
    <script type="text/javascript">
        let productsCount = 0;
        let currentOrderNumber = null;
        let productsOpinion = [];
        $(function () {

            $('#phone').keyup(function (e) {
                if (e.keyCode === 13) {
                    let phone = $('#phone').val();
                    let form = new FormData();
                    form.append('phone', phone);

                    $.ajax({
                        url: '{{url('/get/phone/orders')}}',
                        method: 'POST',
                        data: form,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        success: function (response) {

                        }
                    });
                }
            });

            $('#orderNumber').keyup(function (e) {
                if (e.keyCode === 13) {
                    $.loader({
                        className: "blue-with-image-2",
                        content: ''
                    });
                    let orderNumber = $('#orderNumber').val();
                    let form = new FormData();
                    form.append('orderNumber', orderNumber);

                    $.ajax({
                        url: '{{url('/get/order/details')}}',
                        method: 'POST',
                        data: form,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        success: function (response) {
                            productsCount = response.products.length;
                            currentOrderNumber = orderNumber;
                            productsOpinion = [];
                            $('#orderNumber').val('');

                            createOrderRows(response.products);
                            $.loader('close');
                        },
                        error: function () {
                            $.loader('close');
                        }
                    });
                }
            });

            $('#saveOpinion').on('submit' , function (e) {
                if(productsOpinion.length === productsCount && currentOrderNumber !== null){
                    $.loader({
                        className: "blue-with-image-2",
                        content: ''
                    });

                    let chargeOpinion = $('#charge_opinion').find('option:selected').val();
                    let customerServiceOpinion = $('#customer_service_opinion').find('option:selected').val();
                    let againOpinion = $('#again_opinion').find('option:selected').val();

                    let positives = $('#positives').val();
                    let negatives = $('#negatives').val();
                    let comment = $('#comment').val();
                    let notes = $('#notes').val();

                    let form = new FormData();
                    form.append('orderNumber', currentOrderNumber);
                    form.append('productsOpinion', JSON.stringify(productsOpinion));

                    form.append('chargeOpinion', chargeOpinion);
                    form.append('customerServiceOpinion', customerServiceOpinion);
                    form.append('againOpinion', againOpinion);

                    form.append('positives', positives);
                    form.append('negatives', negatives);
                    form.append('comment', comment);
                    form.append('notes', notes);

                    $.ajax({
                        url: '{{url('/client/opinion/save')}}',
                        method: 'POST',
                        data: form,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        success: function (response) {
                            productsCount = 0;
                            currentOrderNumber = null;
                            productsOpinion = [];
                            $('#orderNumber').val('');
                            $('table tbody').html('');

                            $('#charge_opinion').val('');
                            $('#customer_service_opinion').val('');
                            $('#again_opinion').val('');
                            $('#positives').val('');
                            $('#negatives').val('');
                            $('#comment').val('');
                            $('#notes').val('');

                            $.loader('close');
                        },
                        error: function () {
                            $.loader('close');
                        }
                    });
                }
            });

        });


        function createOrderRows(orderProducts) {
            let html = '';
            orderProducts.forEach((product) => {
                html += '<tr>' +
                    '<td>' + product.code + '</td>' +
                    '<td>' + product.title_ar + '</td>' +
                    '<td><select onchange="changeProductOpinion(this , ' + product.id + ')">' +
                    '<option readonly="">إختر</option>' +
                    '<option value="1">1</option>' +
                    '<option value="2">2</option>' +
                    '<option value="3">3</option>' +
                    '<option value="4">4</option>' +
                    '<option value="5">5</option>' +
                    '<option value="6">6</option>' +
                    '<option value="7">7</option>' +
                    '<option value="8">8</option>' +
                    '<option value="9">9</option>' +
                    '<option value="10">10</option>' +
                    '</select></td>' +
                    '</tr>';
            });
            $('table tbody').html(html);
        }

        function changeProductOpinion(selectItem, productId) {
            let opinion = $(selectItem).find('option:selected').val();
            let index = productsOpinion.findIndex(function (product) {
                return parseInt(product.id) === parseInt(productId)
            });
            let productPojo = {
                id: productId,
                opinion: opinion
            };
            if (index !== -1) {
                productsOpinion[index] = productPojo;
            } else {
                productsOpinion.push(productPojo);
            }
        }

    </script>
@endsection


@section('style')
    <link href="{{URL::to('resources/assets/back/css/jquery.loader.css')}}" rel="stylesheet"/>
@stop