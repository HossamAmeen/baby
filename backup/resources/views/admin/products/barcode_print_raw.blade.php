<div class="parent">
    <div>
        <div style="display: block;text-align: center;">
            <h6 style="text-align: center"><b>{{$product->code}}</b></h6>
        </div>
        {{--<div class="barcode_img">--}}
        {{--@php--}}
        {{--echo \Milon\Barcode\DNS1D::getBarcodeSVG($product->code, 'EAN13' , 3 , 33) ;--}}
        {{--@endphp--}}
        <img class="barcode_img"
             src="data:image/png;base64,{{\Milon\Barcode\DNS1D::getBarcodePNG($product->code, 'C39' , 3, 33)}}">
        {{--</div>--}}
        <div class="part-2">
            <span id="barcode">{{mb_substr($product->title_ar , 0 , 25 , 'utf-8')}}</span>
            <div style="text-align: center;display: block;">
                @if(intval($product->discount) === 0)
                    <h6>{{$product->price}} LE</h6>
                @else
                   <bdi> <span style="text-decoration: line-through;">{{$product->price + $product->discount}} LE</span>
                    <span><b>{{$product->price}}</b> LE</span> </bdi>
                @endif
            </div>
        </div>
    </div>
</div>