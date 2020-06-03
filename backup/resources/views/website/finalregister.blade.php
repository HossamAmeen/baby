@extends('layouts.app')
@section('content')
    <section class="course-detaill">
       <div class="container"> 
           <div class="title"><h4><strong>تفاصيل الكورس</strong></h4></div>
               <div class="table-course">
                   <table class="table">
                        
                        <tbody>
                        <tr>
                            <td><i class="fa fa-folder" aria-hidden="true"></i>اﻷسم بالغة العربية</td>
                            <td>{{ $add->name_ar }}</td>
                          </tr>
                          <tr>
                            <td><i class="fa fa-folder" aria-hidden="true"></i>اﻷسم رباعي بالغة اﻷنجليزية(كما يظهر في الشهادة)</td>
                            <td>{{ $add->name_en }}</td>
                          </tr>
                          <tr>
                            <td><i class="fa fa-folder" aria-hidden="true"></i>البريد اﻷكتروني</td>
                            <td>{{ $add->email }}</td>
                          </tr>
                          <tr>
                            <td><i class="fa fa-folder" aria-hidden="true"></i> رقم الهاتف/الجوال</td>
                            <td>{{ $add->phone }}</td>
                          </tr>
                          <tr>
                            <td><i class="fa fa-folder" aria-hidden="true"></i> دولة اﻷقامة</td>
                            <td>{{ $add->country }}</td>
                          </tr>
                          <tr>
                            <td><i class="fa fa-folder" aria-hidden="true"></i>المهنة/الوظيفة</td>
                            <td>{{ $add->job }}</td>
                          </tr>
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
                            <td><i class="fa fa-money" aria-hidden="true"></i>طريقة الدفع</td>
                            <td>{{ $add->Payment->name }}</td>
                          </tr>
                          <tr>
                            <td><i class="fa fa-money" aria-hidden="true"></i>اجمالي المصروفات</td>
                            <td>{{ $add->Course->price }} دولار</td>
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