@extends('layouts.app')
@section('content')
<section>
    <div class="container">
        <h2>دورات متخصصة للشركات</h2>
        <div class="pah text-center">
            <p><strong>{!! $responsivetext->company_txt !!}</strong></p>
        </div>
    </div> 
</section>
            
<section class="form">
    <div class="container"><br>
    @if(session::has('company_register'))
    <div>
   
    <div class="alert alert-success" style="text-align: center;"><strong>{{ Session::get('company_register') }}</strong></div>
    {{ session::forget('company_register') }}
    </div>
    @endif
        <div class="row">
            {!! Form::open(['url'=>'postspecificcourse','class'=>'form-group']) !!}
            <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                    <label for="name">اﻷسم كاملا</label>
                    <input type="text" class="form-control" required placeholder="اﻷسم كاملا" name="company_full_name" id="name">
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                    <label for="number">أسم الشركة</label>
                    <input type="text" class="form-control" required placeholder="أسم الشركة" name="company_name" id="number">
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                    <label for="number">رقم التليفون / الهاتف</label>
                    <input type="text" class="form-control" required placeholder="رقم التليفون / الهاتف" name="company_phone" id="number">
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                    <label for="email">البريد اﻷلكتروني</label>
                    <input type="email" class="form-control" required placeholder="البريد اﻷلكتروني" name="company_email" id="email">
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                    <label for="country">الدوله</label>
                    <input type="text" class="form-control" required placeholder="الدوله" name="company_country" id="country">
                    </div>
                </div>
                <div class="col-xs-12 ">
                    <div class="form-group">
                    <label for="message">رسالتك</label>
                    <textarea class="form-control" placeholder="رسالتك" name="company_message"></textarea>
                    </div>
                </div>
                <div class="col-xs-offset-3 col-xs-6">
                <button type="submit" class="btn btn-info btn-block">{{ trans('home.submit') }}</button>
                </div>
            </form>
            </div>
        </div>
</section>          
@endsection