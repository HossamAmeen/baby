@extends('layouts.app')
@section('content')
<section class="cer-head">
        
    <div class="parallax-window" data-parallax="scroll" data-natural-width="1500" data-natural-height="1000" data-image-src="{{ url('uploads/configurationsite/source') }}/{{ $con->imageall }}">
            <div class="overlay">
            <div class="title">
            <h2>التحقق من الشهادات</h2>
            </div>
        </div>
        </div>
    </div>
    
</section>
<section class="verify-crt">
    <div class="container">
        <div class="row">
        @if($responsivetext->certificate_txt)
            		<div class="col-xs-12">
                          <div class="pah">
                              <p><strong>{!! $responsivetext->certificate_txt !!}</strong></p>
                          </div>
                       </div>
                       @endif
            <div class="col-xs-12">
            <hr>
                <div class="ver-form">
                   
                        <div class="col-xs-12 col-md-offset-2 col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control xz" id="number" placeholder="رقم الشهاده">
                            </div>
                        
                            <div class=" col-xs-12">
                                <button class="btn btn-info btn-block cert">تحقق</button>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-offset-2 col-md-8 result">
                        
                        </div>
                    
                </div>   
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script>
$('.cert').on('click', function(){
    var number = $('#number').val();
        
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        $.ajax({
              url:" {{url('check-certificate')}}",
              method:'POST',
              data:{number:number},
              success:function(response)
              {
                var h =$('.result');
                var v = '';
                v += '<div style="text-align: center;">';
                if(response[0]){
                    
                    v += '<br><p>أسم المتدرب :'+ response[0].name +'</p><p>أسم الكورس :'+ response[1] +'</p><p> رقم الشهادة :'+ response[0].Serial +'</p>';
                    v += '<a href="{{ url('uploads/certificate/source') }}/'+ response[0].image +'" target="_blank"><img src="{{url('uploads/certificate/resize200')}}/'+ response[0].image +'" /><a><br>';
                }else{
                    v += 'تأكد من رقم الشهادة';
                }
                    v+= '</div>';
                h.html(v);
              }
         });


});
$('.xz').keypress(function(e) {
        if(e.which == 13) {
            
            var number = $('#number').val();
        
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        $.ajax({
              url:" {{url('check-certificate')}}",
              method:'POST',
              data:{number:number},
              success:function(response)
              {
                var h =$('.result');
                var v = '';
                v += '<div style="text-align: center;">';
                if(response[0]){
                    
                  v += '<br><p>أسم المتدرب :'+ response[0].name +'</p><p>أسم الكورس :'+ response[1] +'</p><p> رقم الشهادة :'+ response[0].Serial +'</p>';
                    v += '<a href="{{ url('uploads/certificate/source') }}/'+ response[0].image +'" target="_blank"><img src="{{url('uploads/certificate/resize200')}}/'+ response[0].image +'" /><a><br>';
                }else{
                    v += 'تأكد من رقم الشهادة';
                }
                    v+= '</div>';
                h.html(v);
              }
         });
        }
    });
</script>
@endsection