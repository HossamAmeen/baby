@extends('layouts.app')
@section('title')
<title> Error 404  </title>
@endsection
@section('style')
<style>
   #error .content{
        margin-top: 12%;
        text-align: center;
    }
   #error h1{
        font-size: 6rem;
        color: #702a98;
    }
    #error h2{
        font-size: 3rem;
        color: #636363;
    }
    #error p{
        font-size: 3.5rem;
        color: gray;
        margin-bottom: 25px;
    }
    #error a{
        font-size: 1.8rem;
        color: #fff;
        background-color: #702a98;
        padding: 10px 20px;
        border-radius: 10px;
        text-transform: capitalize;
    }  
</style>
@endsection
@section('content')

<section id="error">
<div class="container">
    <div class="col-xs-12 col-md-6">
            <div class="content">
                <div class="title">
                    <h1>خطأ!</h1>
                </div>
                <div class="error-t">
                    <h2>خطأ 404</h2>
                </div>
                <div class="">
                    <p>لا نستطيع إيجاد الصفحة التى تبحث عنها.</p>
                </div>
                <div class="">
                    <a href="{{ url('/') }}">العودة إلى الرئيسية</a>
                </div>
            </div>
        </div>
    <div class="col-xs-12 col-md-6">
        <img style="margin-top: 15px;" src="{{ url('/resources/assets/front/img/baby-error.png')}}">
    </div>
</div>
</section>
@endsection


