@extends('layouts.app')
@section('content')
<section class="cer-head">
               <div class="parallax-window" data-parallax="scroll" data-natural-width="1500" data-natural-height="1000" data-image-src="image/Young-students-outside-with-male-teacher.jpg">
                  <div>
                      <div class="overlay">
                      <div class="title">
                      <h2>التحقق من الشهادات</h2>
                      </div>
                  </div>
                  </div>
               </div>
        </section>
    <section class="payment">
        <div class="container ">
           <div class="title"><h2>سعداء بتسجيلكم</h2></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="reg-description">
                        <h4><i class="fa fa-barcode" aria-hidden="true"></i> كود التسجيل :<span> {{ $add->id }}</span></h4>
                        <p>{!! $responsivetext->finalregister_txt !!}</p>
                    </div>
                </div>
                <div class="col-md-offset-1 col-md-5">
                    <div class="pay">
                        <button class="btn btn-info btn-block " id="pay-cr">
                        <i class="fa fa-cc-visa" aria-hidden="true"></i> الدفع من خلال الفيزا
                        </button>
                    </div>
                </div>
                <div class=" col-md-5">
                     <div class="pay">
                        <button class="btn btn-success btn-block" id="pay-other">
                               <i class="fa fa-money" aria-hidden="true"></i> وسائل دفع أخري
                        </button>
                    </div>
                </div>
                <div class=" col-md-offset-1 col-md-11">
                     <div class="pay">
                        <a href="{{ url('show-course') }}/{{ $add->Course->link }}"><button class="btn btn-warning btn-block">الرجوع الي صفحة الكورس"{{ $add->Course->title }}"</button></a>
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    <div class="credit-pay">
                        <div class="pay-img">
                            <img class="img-responsive" src="image/payment2.jpg" alt="..">
                        </div>
                        <div class="pay-btn">
                            <button class="btn btn-info "><a href="#"> <i class="fa fa-arrow-left" aria-hidden="true"></i> اضغط هنا </a></button>
                        </div>
                        <div class="">
                            <p>ان عمليه الدفع مؤمنه</p>
                        </div>
                    </div>
                    
                    
                    <div class="payment-method text-center">
                          <div class="row newrow" >
                          
                          <input type="hidden" class="reg_id" name="reg_id" value="{{ $add->id }}" />
                          @foreach($payment as $pay)
                              <div class="col-sm-offset-4 col-sm-8 col-md-offset-0 col-md-4">
                              <div class="img"><img class="img-responsive" src="{{ url('uploads/payment/resize200') }}/{{ $pay->image }}" alt="..."></div>
                                <div class="checkbox">
                                    <label><input type="radio" name="payment_anthor" class="payid" value="{{ $pay->id }}">{{ $pay->name }}</label>
                                </div>
                              </div>
                          @endforeach
                          </div>
                          <a class="btn btn-info" id="payment"> <i class="fa fa-angle-left" aria-hidden="true"></i> <i class="fa fa-angle-left" aria-hidden="true"></i> انهاء اجرائات التسجيل</a>
                          
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    
    <section class="course-detaill">
       <div class="container"> 
           <div class="title"><h4><strong>تفاصيل الكورس</strong></h4></div>
               <div class="table-course">
                   <table class="table">
                        
                        <tbody>
                          <tr>
                            <td><i class="fa fa-folder" aria-hidden="true"></i> تاريخ التسجيل</td>
                            <td>{{ $add->created_at }}</td>
                          </tr>
                          <tr>
                            <td><i class="fa fa-clock-o" aria-hidden="true"></i> البرنامج التدريبي</td>
                            <td>{{ $add->Course->title }}</td>
                          </tr>
                          <tr>
                            <td><i class="fa fa-calendar" aria-hidden="true"></i> عدد الساعات</td>
                            <td>{{ $add->Course->duration }}</td>
                          </tr>
                          <tr>
                            <td><i class="fa fa-map" aria-hidden="true"></i> تاريخ بداية الدورة</td>
                            <td>{{ $add->Course->date }}</td>
                          </tr>
                          <tr>
                            <td><i class="fa fa-money" aria-hidden="true"></i> مكان انعقاد الدورة</td>
                            <td>{{ $add->Course->location }}</td>
                          </tr>
                          <tr>
                            <td><i class="fa fa-barcode" aria-hidden="true"></i> مصروفات التسجيل</td>
                            <td>{{ $add->Course->price }} دولار</td>
                          </tr>
                          <tr>
                            <td><i class="fa fa-money" aria-hidden="true"></i> كود التسجيل</td>
                            <td>{{ $add->id }}</td>
                          </tr>
                          <tr>
                            <td><i class="fa fa-money" aria-hidden="true"></i>اجمالي المصروفات</td>
                            <td>{{ intval($add->Course->price) }} دولار</td>
                          </tr>
                        </tbody>
                  </table>
               </div>
               <div class="call-us text-center">
                   <strong> <big><p> <i class="fa fa-phone" aria-hidden="true"></i> للتواصل مع قسم المبيعات : {{ $con->sales_phone }}</p></big></strong> 
                </div>
       </div>
    </section>    
@endsection
@section('script')
<script>
$('#payment').on('click', function(){
    var id =  $('.payid').val();
    var regid =  $('.reg_id').val();

            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        $.ajax({
              url:" {{url('finish-register-anthor')}}",
              method:'POST',
              data:{id:id,regid:regid},
              success:function(data)
              {
                var s = $('.newrow');
                var ht = '' ;
                  ht += '<p> وسيلة الدفع :'+ data[0] +'</p>';
                  ht += '<p> التفاصيل '+ data[1] +'</p>';
                  
                s.html(ht);
                $('#payment').hide();
                $('#pay-cr').hide();
                $('#pay-other').hide();
              }
         });
        


});
</script>
@endsection