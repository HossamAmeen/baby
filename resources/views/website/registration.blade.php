@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{ URL::to('resources/assets/back/css/select2.min.css') }}" />
@endsection
@section('content')
<section class="cer-head">
               <div class="parallax-window" data-parallax="scroll" data-natural-width="1500" data-natural-height="1000" data-image-src="{{ url('uploads/configurationsite/source') }}/{{ $con->imageall }}">
                  <div>
                      <div class="overlay">
                      <div class="title">
                      <h2>التسجيل في الدورات التدريبية</h2>
                      </div>
                  </div>
                  </div>
               </div>
            
        </section>
        <section class="register">
           <div class="container">
                <div class="title" style="text-align:center ;">
                    <h2 class="l-height">{!! $responsivetext->register1 !!}</h2>
                </div>
                <div class="des" style="text-align:center ;">
                    <p>{!! $responsivetext->register2 !!}</p>
                </div>
                {!! Form::open(['url' => 'postregistration','class'=>'form-group']) !!}
                <div class="pick">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                           <div class="form-group">
                              <label for="Select">اختر القسم</label>
                                <select class="form-control cat" dir= "rtl" name="cat" required>
                                    <option></option>
                                @foreach($category as $k => $item)
                                    <option value="{{$k}}">{{ $item }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>                    
                        <div class="col-xs-12 col-md-6">
                           <div class="form-group">
                              <label for="Select">اختر الدورة التدريبية</label>
                                <select class="form-control course" dir= "rtl" name="course" required>
                                 <option></option>
                                </select>
                            </div>                                    
                        </div>
                    </div>
                </div>
                
                <div class="inner-re">
                    <div class="row">
                        <div class="col-md-8">
                            <p><strong>  {!! $responsivetext->register3 !!}</strong><a href="{{ url('contactus') }}">اضغط هنا</a></p>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-info" type="submit">الخطوه التاليه</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </section>
    
@endsection
@section('script')
<script src="{{ URL::to('resources/assets/back/js/select2.full.min.js') }}"></script>
<script>
$('.cat').select2({
    placeholder: 'اختر  القسم',
});
$('.course').select2({
    placeholder: 'اختر الدورة التدريبية',
});  

$('.cat').on('change', function(){
    var cat =  $(this).val();

            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        $.ajax({
              url:" {{url('changecategory')}}",
              method:'POST',
              data:{cat:cat},
              success:function(data)
              {
                var s = $('.course');
                var html = '' ;
                html +='<option></option>';
                for (key in data) {
                    if (data.hasOwnProperty(key)) {
                        html += '<option value="'+ key +'">'+ data[key] +'</option>';
                    }
                }
                
                  s.html(html);
              }
         });
        
});
</script>
    
@endsection