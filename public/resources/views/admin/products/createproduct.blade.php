@extends('layouts.admin')

@section('content')


    {!! Form::open(['id' => 'productFOrm', 'route' => 'products.store', 'data-toggle'=>'validator', 'files'=>'true']) !!}


    <div class="row">
        <div class="col-md-6">
        </div>
        <div class="col-md-6">
            <input type="button" class="btn btn-primary" id="addTitle" value="إضافة"/>
        </div>
    </div>
    <hr/>

    <div id="product_titles">

    </div>

    {{--<div class="form-group">--}}
        {{--<input type="text" class="form-control" required placeholder="{{trans('home.link')}}" name="link">--}}
    {{--</div>--}}
    {{--<div class="form-group">--}}
        {{--<input type="text" class="form-control" required placeholder="رابط إنجليزى" name="link_en">--}}
    {{--</div>--}}

    {{--<div class="form-group">--}}
    {{--<input type="number" value="1" min="0" class="form-control" placeholder="{{trans('home.in_stock')}}" name="stock" >--}}
    {{--</div>--}}

    <div class="form-group">
        <input type="number" value="1" min="1" max="50" class="form-control" placeholder="{{trans('home.affilate')}}"
               name="affilate">
    </div>

    <div class="form-group">
        <input type="text" class="form-control" placeholder="{{trans('home.price')}}" name="price">
    </div>

    <div class="form-group">
        <input type="text" class="form-control" placeholder="{{trans('home.buying_price')}}" name="buying_price">
    </div>

    <div class="form-group">
        <input type="text" class="form-control" placeholder="{{trans('home.discount')}}" name="discount">
    </div>

    <div class="form-group">
        <input type="text" class="form-control" placeholder="{{trans('home.shipping')}}" name="shipping">
    </div>

    <div class="form-group">
        <input type="text" class="form-control" placeholder="{{trans('home.weight')}}" name="weight">
    </div>

    <div class="form-group">
        <input type="number" value="1" min="1" class="form-control" placeholder="{{trans('home.max_quantity')}}"
               name="max_quantity">
    </div>

    <div class="form-group">
        <input type="number" value="1" min="1" class="form-control" placeholder="{{trans('home.min_quantity')}}"
               name="min_quantity">
    </div>

    <div class="form-group">
        <label for="category">{{trans('home.category')}} :</label>
        <select class="form-control" name="category[]" id="category" required multiple>
            <option></option>
            @foreach($category as $cat)
                <option value="{{ $cat->id }}">{{ $cat->title}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group select-group">
        <label for="artist">{{trans('home.brand')}} :</label>
        <select class="form-control" name="brand" id="brand" required>
            <option></option>
            @foreach($brands as $brand)
                <option value="{{ $brand -> id }}">{{ $brand->name }}</option>
            @endforeach
        </select>
    </div>




    <div class="form-group select-group">
        <label for="artist">{{trans('home.currency')}} :</label>
        {!! Form::select('currency_id',$currency,null,['id' => 'currency_id','class' => 'form-control','required']) !!}
    </div>

    <div class="form-group select-group">
        <label for="status">{{trans('home.status')}} :</label>
        <select class="form-control" name="status" id="status" required>
            <option value="1" selected>{{trans('home.yes')}}</option>
            <option value="0">{{trans('home.no')}}</option>
        </select>
    </div>

    <div class="form-group select-group">
        <label for="featured">{{trans('home.featured')}} :</label>
        <select class="form-control" name="featured" id="featured" required>
            <option value="1" selected>{{trans('home.yes')}}</option>
            <option value="0">{{trans('home.no')}}</option>
        </select>
    </div>


    <div class="form-group select-group">
        <label for="artist">{{trans('home.vendor')}} :</label>
        <select class="form-control" name="vendor" id="vendor">
            @foreach($vendors as $vendor)
                <option value="{{ $vendor -> id }}">{{ $vendor ->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group select-group">
        <label for="vendor_status">{{trans('home.vendor_status')}} :</label>
        <select class="form-control" name="vendor_status" id="vendor_status">
            <option value="1" selected>{{trans('home.yes')}}</option>
            <option value="0">{{trans('home.no')}}</option>
        </select>
    </div>




    <hr>
    <span>{{trans('home.radio')}} :</span>
    <div>
        <div>
            {{-- <div class="form-group">
                <input type="text" class="form-control" name="radio_name[]" placeholder="{{trans('home.radio_name')}}" />
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="radio_price[]" placeholder="{{trans('home.radio_price')}}" />
            </div>
                <span class="removeradio btn btn-danger">{{trans("home.delete")}}</span>--}}
            <hr style="height:1px;border:none;color:#333;background-color:#333;">
        </div>
        <div>
            <span class="addradio btn btn-success">{{trans("home.add")}}</span>
        </div>
    </div>
    <hr>
    <span>{{trans('home.check')}} :</span>
    <div>
        <div>
            {{-- <div class="form-group">
                <input type="text" class="form-control" name="check_name[]" placeholder="{{trans('home.check_name')}}" />
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="check_price[]" placeholder="{{trans('home.check_price')}}" />
            </div>
                <span class="removecheck btn btn-danger">{{trans("home.delete")}}</span>
                --}}
            <hr style="height:1px;border:none;color:#333;background-color:#333;">
        </div>
        <div>
            <span class="addcheck btn btn-success">{{trans("home.add")}}</span>
        </div>
    </div>



    <div class="form-group">
        <label for="photo">{{trans('home.main_photo')}} :</label>
        <input type="file" class="form-control" name="photo" id="photo" required>
    </div>

    <div class="form-group">
        <label for="photos">{{trans('home.multi_photo')}} :</label>
        <input type="file" class="form-control" name="photos[]" id="photos" multiple>
    </div>

    <div class="form-group">
        <label for="short_description">{{trans('home.short_description')}} {{ trans('home.ar') }} :</label>
        <textarea class="form-control area1" name="short_description" id="short_description"></textarea>
    </div>

    <div class="form-group">
        <label for="description">{{trans('home.description')}} {{ trans('home.ar') }} :</label>
        <textarea class="form-control area1" name="description" id="description"></textarea>
    </div>

    <div class="form-group">
        <label for="short_description">{{trans('home.short_description')}} {{ trans('home.en') }} :</label>
        <textarea class="form-control" name="short_desc_en" id="short_desc_en"></textarea>
    </div>

    <div class="form-group">
        <label for="description">{{trans('home.description')}} {{ trans('home.en') }} :</label>
        <textarea class="form-control area1" name="desc_en" id="desc_en"></textarea>
    </div>

    <div class="form-group">
        <label for="description">{{trans('home.comment')}} :</label>
        <textarea class="form-control" name="comment" id="comment"></textarea>
    </div>

    <div class="form-group">
        <label for="meta_key">{{trans('home.meta_keywords')}} :</label>
        <textarea class="form-control" name="meta_key" id="meta_key"></textarea>
    </div>

    <div class="form-group">
        <label for="meta_desc">{{trans('home.meta_description')}} :</label>
        <textarea class="form-control" name="meta_desc" id="meta_desc"></textarea>
    </div>


    <div class="form-group">
        <label for="meta_key_en">كلمات مفتاحية إنجليزى :</label>
        <textarea class="form-control" name="meta_key_en" id="meta_key_en"></textarea>
    </div>

    <div class="form-group">
        <label for="meta_desc_en">وصف الميتا إنجليزى :</label>
        <textarea class="form-control" name="meta_desc_en" id="meta_desc_en"></textarea>
    </div>

    <div class="btns-bottom">
        <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
        <a href="{{ url('products') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
    </div>

    {!! Form::close() !!}


@endsection

@section('script')
    <script>
        let index = 0;
        let wrongCodes = [];

        $(function () {
            addTitle();

            $('#addTitle').on('click', function () {
                addTitle();
            });

            $('button[type=submit]').on('click', function (e) {
                if ($('div').hasClass('has-error has-danger')) {
                    e.preventDefault();
                    if (!$('button[type=submit]').hasClass('disabled')) {
                        $('button[type=submit]').addClass('disabled');
                    }
                }
            });

        });


        function addTitle() {
            let html = '<div class="form-group">' +
                '            <select class="form-control" id="color_' + index + '">' +
                '                <option readonly value="">إختر لون</option>';
            @foreach($colors as $color)
                html += '<option value="{{$color->name}}">{{$color->name}}</option>';
            @endforeach
                html += '            </select>' +
                '        </div>' +

                '        <div class="form-group">' +
                '            <input type="text" class="form-control" id="title_' + index + '" placeholder="{{trans('home.title')}}" name="title[]" required>' +
                '        </div>' +

                '        <div class="form-group">' +
                '            <input type="text" class="form-control" id="title_ar_' + index + '" placeholder="{{trans('home.title_ar')}}" name="title_ar[]"' +
                '                   required>' +
                '        </div>' +

                '        <div class="form-group">' +
                '            <input type="text" class="form-control" onkeyup="onkeyupcode(this);" placeholder="{{trans('home.code')}}" name="code[]" required>' +
                '        </div>' +
                '        <hr />';

            $('#product_titles').append(html);
            let color = $('#color_' + index);
            color.select2({
                'placeholder': 'أختار اللون',
                width: '100%'
            });
            color.on('change', function () {
                let selectedColor = $(this).find('option:selected').val();
                let id = $(this).attr('id');
                let index = id.substr(6, 1);
                let titleSelector = $('#title_' + index);
                let titleArSelector = $('#title_ar_' + index);
                titleSelector.val(titleSelector.val() + selectedColor + '.');
                titleArSelector.val(titleArSelector.val() + selectedColor + '.');
            });
            index++;
        }

        function onkeyupcode(item) {
            let code = $(item).val();
            if (code.length >= 4) {
                let form = new FormData();
                form.append('code', code);
                form.append('create', 1);
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
                        if (!$('button[type=submit]').hasClass('disabled') &&
                            response.data && response.data.id) {
                            $('button[type=submit]').addClass('disabled');
                            if (!$(item).parent('div').hasClass('has-error has-danger')) {
                                $(item).parent('div').addClass('has-error has-danger');
                            }
                        } else {
                            $('button[type=submit]').removeClass('disabled');
                            $(item).parent('div').removeClass('has-error has-danger');
                        }
                    }
                });
            }
        }

        $('#status').select2({
            'placeholder': 'أختار الحالة',
        });
        $('#padge_id').select2({
            'placeholder': 'أختار padge',
        });
        $('#special').select2({
            'placeholder': 'أختار special',
        });
        $('#featured').select2({
            'placeholder': 'أختار featured',
        });
        $('#currency_id').select2({
            'placeholder': 'أختار العملة',
        });
        $('#category').select2({
            'placeholder': 'أختار القسم',
        });
        $('#radio_id').select2({
            'placeholder': 'أختار Radio',
        });
        $('#check_id').select2({
            'placeholder': 'أختار Check',
        });
        $('#lang').select2({
            'placeholder': 'أختار اللغة',
        });
        $('#brand').select2({
            'placeholder': 'إختر الماركة ',
        });

        $('#vendor').select2({
            'placeholder': 'إختر المورد ',
        });
        $('#vendor_status').select2({
            'placeholder': 'إختر حالة المورد ',
        });

        $('.addradio').click(function () {
            $(this).before('<div><div class="form-group"> <input type="text" class="form-control" name="radio_name[]" placeholder="{{trans("home.radio_name")}}" />  </div><div class="form-group"> <input type="text" class="form-control" name="radio_price[]" placeholder="{{trans("home.radio_price")}}" />  </div><span class="removeradio btn btn-danger">{{trans("home.delete")}}</span><hr style="height:1px;border:none;color:#333;background-color:#333;"></div>');

        });

        $(document).on('click', '.removeradio', function () {
            $(this).parent('div').remove();
        });
        $('.addcheck').click(function () {
            $(this).before('<div><div class="form-group"> <input type="text" class="form-control" name="check_name[]" placeholder="{{trans("home.check_name")}}" />  </div><div class="form-group"> <input type="text" class="form-control" name="check_price[]" placeholder="{{trans("home.check_price")}}" />  </div><span class="removecheck btn btn-danger">{{trans("home.delete")}}</span><hr style="height:1px;border:none;color:#333;background-color:#333;"></div>');

        });

        $(document).on('click', '.removecheck', function () {
            $(this).parent('div').remove();
        });
    </script>
@endsection