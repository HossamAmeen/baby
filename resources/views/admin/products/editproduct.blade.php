@extends('layouts.admin')

@section('content')


    {!! Form::open(['method'=>'PATCH','url' => 'products/'.$product->id,'files'=>'true']) !!}


    <div class="form-group">
        <label for="title">{{trans('home.title')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.title')}}" value="{{ $product->title }}"
               name="title" required>
    </div>

    <div class="form-group">
        <label for="title_ar">{{trans('home.title_ar')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.title_ar')}}"
               value="{{ $product->title_ar }}" name="title_ar" required>
    </div>


    <div class="form-group">
        <label for="code">{{trans('home.code')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.code')}}" value="{{ $product->code }}"
               name="code">
    </div>

    <div class="form-group">
        <label for="link">{{trans('home.link')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.link')}}"
               value="{{ $product->link }}" name="link">
    </div>

    <div class="form-group">
        <label for="link_en">رابط إنجليزى :</label>
        <input type="text" class="form-control" placeholder="رابط إنجليزى"
               value="{{ $product->link_en }}" name="link_en">
    </div>

    {{--<div class="form-group">--}}
        {{--<label for="in_stock">{{trans('home.in_stock')}} :</label>--}}
        {{--<input type="number" min="0" class="form-control" placeholder="{{trans('home.in_stock')}}"--}}
               {{--value="{{ $product->stock }}" name="stock">--}}
    {{--</div>--}}

    {{--<div class="form-group">--}}
        {{--<label for="in_stock">{{trans('home.store_stock_count')}} :</label>--}}
        {{--<input type="number" min="0" class="form-control" placeholder="{{trans('home.store_stock')}}"--}}
               {{--value="{{ $product->store_stock}}" name="store_stock">--}}
    {{--</div>--}}

    <div class="form-group">
        <label for="affilate">{{trans('home.affilate')}} :</label>
        <input type="number" min="1" max="50" class="form-control" value="{{$product->affilate}}" name="affilate">
    </div>

    <div class="form-group">
        <label for="price">{{trans('home.price')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.price')}}" value="{{ $product->price }}"
               name="price">
    </div>

    <div class="form-group">
        <label for="discount">{{trans('home.discount')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.discount')}}"
               value="{{ $product->discount }}" name="discount">
    </div>

    <div class="form-group">
        <label for="shipping">{{trans('home.shipping')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.shipping')}}"
               value="{{ $product->shipping }}" name="shipping">
    </div>

    <div class="form-group">
        <input type="text" class="form-control" placeholder="{{trans('home.buying_price')}}" name="buying_price">
    </div>

    <div class="form-group">
        <label for="weight">{{trans('home.weight')}} :</label>
        <input type="text" class="form-control" placeholder="{{trans('home.weight')}}" value="{{ $product->weight }}"
               name="weight">
    </div>

    <div class="form-group">
        <label for="max_quantity">{{trans('home.max_quantity')}} :</label>
        <input type="number" min="1" class="form-control" placeholder="{{trans('home.max_quantity')}}"
               value="{{ $product->max_quantity }}" name="max_quantity">
    </div>

    <div class="form-group">
        <label for="min_quantity">{{trans('home.min_quantity')}} :</label>
        <input type="number" min="1" class="form-control" placeholder="{{trans('home.min_quantity')}}"
               value="{{ $product->min_quantity }}" name="min_quantity">
    </div>

    <div class="form-group">
        <label for="category">{{trans('home.category')}} :</label>
        <select class="form-control" name="category[]" id="category" multiple required>
            <option></option>
            @foreach($category as $cat)
                <option value="{{ $cat -> id }}"
                        @foreach($catids as $catid)
                        @if($cat->id == $catid) selected @endif
                        @endforeach
                >{{ $cat->title}}</option>
            @endforeach
        </select>
    </div>


    <div class="form-group select-group">
        <label for="artist">{{trans('home.brand')}} :</label>
        <select class="form-control" name="brand" id="brand" required>
            <option></option>
            @foreach($brands as $brand)
                <option @if($brand->id == $product->brand_id) selected
                        @endif value="{{ $brand -> id }}">{{ $brand->name }}</option>
            @endforeach
        </select>
    </div>



    <div class="form-group select-group">
        <label for="currency">{{trans('home.currency')}} :</label>
        {!! Form::select('currency_id',[''=>''] + $currency,$product->currency_id,['id' => 'currency_id','class' => 'form-control','required']) !!}
    </div>

    <div class="form-group select-group">
        <label for="status">{{trans('home.status')}} :</label>
        <select class="form-control" name="status" id="status" required>
            <option></option>
            <option @if($product->status == '1') selected @endif value="1">{{trans('home.yes')}}</option>
            <option @if($product->status == '0') selected @endif value="0">{{trans('home.no')}}</option>
        </select>
    </div>

    <div class="form-group select-group">
        <label for="featured">{{trans('home.featured')}} :</label>
        <select class="form-control" name="featured" id="featured" required>
            <option></option>
            <option @if($product->featured == '1') selected @endif value="1">{{trans('home.yes')}}</option>
            <option @if($product->featured == '0') selected @endif value="0">{{trans('home.no')}}</option>
        </select>
    </div>



    <div class="form-group select-group">
        <label for="artist">{{trans('home.vendor')}} :</label>
        <select class="form-control" name="vendor" id="vendor">
            <option></option>
            @foreach($vendors as $vendor)
                <option value="{{ $vendor -> id }}" {{ ($vendor -> id == $product->vendor_id)?'selected':'' }}>{{ $vendor ->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group select-group">
        <label for="vendor_status">{{trans('home.vendor_status')}} :</label>
        <select class="form-control" name="vendor_status" id="vendor_status" required>
            <option></option>
            <option @if($product->vendor_status == '1') selected @endif value="1">{{trans('home.yes')}}</option>
            <option @if($product->vendor_status == '0') selected @endif value="0">{{trans('home.no')}}</option>
        </select>
    </div>



    <hr>
    <span>{{trans('home.radio')}} :</span>
    <div>
        @foreach($radio as $item)
            <div>
                <div class="form-group">
                    <input type="text" class="form-control" name="radio_name[]" value="{{ $item->option }}"
                           placeholder="{{trans('home.radio_name')}}"/>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="radio_price[]" value="{{ $item->price }}"
                           placeholder="{{trans('home.radio_price')}}"/>
                </div>
                <span class="removeradio btn btn-danger">{{trans("home.delete")}}</span>
                <hr style="height:1px;border:none;color:#333;background-color:#333;">
            </div>
        @endforeach
        <div>
            <span class="addradio btn btn-success">{{trans("home.add")}}</span>
        </div>
    </div>
    <hr>
    <span>{{trans('home.check')}} :</span>
    <div>
        @foreach($check as $item)
            <div>
                <div class="form-group">
                    <input type="text" class="form-control" name="check_name[]" value="{{ $item->option }}"
                           placeholder="{{trans('home.check_name')}}"/>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="check_price[]" value="{{ $item->price }}"
                           placeholder="{{trans('home.check_price')}}"/>
                </div>
                <span class="removecheck btn btn-danger">{{trans("home.delete")}}</span>

                <hr style="height:1px;border:none;color:#333;background-color:#333;">
            </div>
        @endforeach
        <div>
            <span class="addcheck btn btn-success">{{trans("home.add")}}</span>
        </div>
    </div>

    <div class="form-group">
        <span>{{trans('home.imageshow')}} :</span> <img src="{{url('\uploads\product\resize200')}}\{{$product->image}}"
                                                        width="150" height="150">
    </div>

    <div class="form-group">
        <label for="photo">{{trans('home.main_photo')}} :</label>
        <input type="file" class="form-control" name="photo" id="photo" @if($product->image == '') required @endif >
    </div>
    @foreach($img as $mg)

        <div class="pro_image">
            <button type="button" class="close delete_one" data-id="{{ $mg->id }}" data-dismiss="modal">&times;</button>
            <img src="{{ URL::to('uploads/product/resize200/')}}/{!! $mg->image !!}" width="150" height="150">
        </div>

    @endforeach

    <div class="form-group">
        <label for="photos">{{trans('home.multi_photo')}} :</label>
        <input type="file" class="form-control" name="photos[]" id="photos" multiple>
    </div>

    <div class="form-group">
        <label for="short_description">{{trans('home.short_description')}} {{ trans('home.ar') }} :</label>
        <textarea class="form-control" name="short_description"
                  id="short_description">{!! $product->short_description !!}</textarea>
    </div>

    <div class="form-group">
        <label for="description">{{trans('home.description')}} {{ trans('home.ar') }} :</label>
        <textarea class="form-control area1" name="description"
                  id="description">{!! $product->description !!}</textarea>
    </div>

    <div class="form-group">
        <label for="short_description">{{trans('home.short_description')}} {{ trans('home.en') }} :</label>
        <textarea class="form-control" name="short_desc_en" id="short_desc_en">{!! $product->short_desc_en!!}</textarea>
    </div>

    <div class="form-group">
        <label for="description">{{trans('home.description')}} {{ trans('home.en') }} :</label>
        <textarea class="form-control area1" name="desc_en" id="desc_en">{!! $product->desc_en!!}</textarea>
    </div>

    <div class="form-group">
        <label for="description">{{trans('home.comment')}} :</label>
        <textarea class="form-control" name="comment" id="comment">{{ $product->comment }}</textarea>
    </div>

    <div class="form-group">
        <label for="meta_key">{{trans('home.meta_keywords')}} :</label>
        <textarea class="form-control" name="meta_key" id="meta_key">{{ $product->meta_key }}</textarea>
    </div>

    <div class="form-group">
        <label for="meta_desc">{{trans('home.meta_description')}} :</label>
        <textarea class="form-control" name="meta_desc" id="meta_desc">{{ $product->meta_desc }}</textarea>
    </div>

    <div class="form-group">
        <label for="meta_key_en">كلمات مفتاحية إنجليزى :</label>
        <textarea class="form-control" name="meta_key_en" id="meta_key_en">{{ $product->meta_key_en }}</textarea>
    </div>

    <div class="form-group">
        <label for="meta_desc_en">وصف الميتا إنجليزى :</label>
        <textarea class="form-control" name="meta_desc_en" id="meta_desc_en"><{{ $product->meta_desc_en }}/textarea>
    </div>

    <div class="btns-bottom">
        <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
        <a href="{{ url('products') }}" id="back" class="btn btn-default">{{trans('home.back')}}</a>
    </div>

    {!! Form::close() !!}


@endsection

@section('script')
    <script>
        $('#status').select2({
            'placeholder': 'أختار الحالة',
        });
        $('#currency_id').select2({
            'placeholder': 'أختار العملة',
        });
        $('#padge_id').select2({
            'placeholder': 'أختار padge',
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
        $('#special').select2({
            'placeholder': 'أختار special',
        });
        $('#featured').select2({
            'placeholder': 'أختار featured',
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


        tinymce.init({
            mode: "specific_textareas",
            mode: "textareas",
            editor_selector: "area1",
            height: 500,
            fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
            theme: 'modern',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools jbimages'
            ],
            toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages',
            toolbar2: 'ltr rtl | print preview media | forecolor backcolor emoticons | fontsizeselect',
            //image_advtab: true,
            templates: [
                {title: 'Test template 1', content: 'Test 1'},
                {title: 'Test template 2', content: 'Test 2'}
            ],
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'
            ]
        });


        $(document).ready(function () {
            $('.delete_one').on('click', function () {

                var id = $(this).data("id");


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: " {{ URL::to('deleteoneimage')}}/" + id,
                    method: 'POST',
                    data: {id: id},
                    success: function () {
                        location.reload();
                    }
                });


            });
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