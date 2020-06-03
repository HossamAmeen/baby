@extends('layouts.app')
@section('title')
<title> {{ trans('site.register') }} </title>
@endsection
@section('style')
     <style>
        #login-register{
      padding: 1% 0%;  
      background-color: #f9f9f9;
}
#login-register .content{
    padding: 30px;
    padding-bottom: 84px;
    background-color: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    -o-box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    -moz-box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
}
#login-register .register,#login-register .login {
    margin: 36px 0px;
}
#login-register .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
    color: #555;
    cursor: default;
    background-color: #f7f7f7;
    border-radius: 0px;
}

    </style>
@endsection
@section('content')
    <!--=============== Start Of Reg Page ===============-->
<section id="login-register">
            <div class="container">
                <div class="row">
                    <br>
                    <div class="col-md-3"></div>
                    <div class="col-xs-12 col-md-6">
                        <div class="content">
                          <ul class="nav nav-tabs">
                            <li class="col-md-6 col-xs-6"><a data-toggle="tab" href="#home"><div class="title bink"><h4><strong>{{trans('site.login')}}</strong></h4></div></a></li>
                            <li class="active col-md-6 col-xs-6"><a data-toggle="tab" href="#menu1"><div class="title bink"><h4><strong>{{trans('site.register')}}</strong></h4></div></a></li>
                          </ul>
                          <div class="tab-content">
                            <div id="home" class="tab-pane fade">
                                <div class="login">
                             
                                  <h2 class="text-login">{{trans('site.login')}}</h2>
                                  <p class="text-center text-muted ">{{trans('site.choose_method')}}</p>
<ul class="social-icons icon-circle icon-rotate list-unstyled list-inline">
    <div class=" head4">
        <li> <a href="{{ url('auth/facebook') }}" target="_blank"><i class="fa fa-facebook"></i> </a></li>
        <li> <a href="{{ url('auth/google') }}"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
    </div>
</ul>
                                 <h4 class="text-login">{{trans('site.or_or')}}</h4>
                                 <p class="text-center text-muted ">{{trans('site.login_email')}}</p>
                                  @if ($errors->has('email_phone'))
                                      <div class="alert alert-danger">
                                          <strong>{{ $errors->first('email_phone') }}</strong>
                                      </div>
                                  @endif
                                      <form role="form" data-toggle="validator" method="POST" action="{{ url('/login') }}" >
                                        {{ csrf_field() }}
                                        <div class="row">   
                                            <div class="col-xs-12 ">
                                                <div class="form-group has-feedback">
                                                
                                                     <div class="col-xs-12">
                                                    <input  class=" form-control " placeholder="{{ trans('site.enter_your_email_address_or_phone') }}" type="text" required id="input-email"  class="form-control" value="{{ old('login') }}" name="login"><br />
                                                    </div>
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>
                                              <div class="col-xs-12 ">
                                                <div class="form-group has-feedback">
                                                 
                                                     <div class="col-xs-12">
                                                    <input   class=" form-control " type="password" data-error="The password that you 've entered is incorrect" data-minlength="6" id="inputPassword" class="form-control" name="password" required placeholder="{{ trans('site.please_enter_your_password') }}">
                                                    </div>
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>
                                        </div>
                                     
                                        <div class="col-xs-12 text-center login-btn">
                                            <button class="btn btn-primary ">{{trans('site.login')}}</button>
                             
                                          
                                        </div>
                                           <div class="form-group col-xs-12 text-center forget-div">
                                            <a href="{{ url('/password/reset') }}">{{trans('site.forget_your_password?')}}</a>
                                        </div>
              
                                    </form>
                                </div>
                            </div>
                            <div id="menu1" class="tab-pane fade in active">   
                                
                                <div class="register">
                                                                 <h2 class="text-login">{{trans('site.register')}}</h2>
                                  <p class="text-center text-muted ">{{trans('site.signup_choose_method')}}</p>
<ul class="social-icons icon-circle icon-rotate list-unstyled list-inline">
    <div class=" head4">
        <li> <a href="{{ url('auth/facebook') }}" target="_blank"><i class="fa fa-facebook"></i> </a></li>
        <li> <a href="{{ url('auth/google') }}"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
    </div>
</ul>
                                 <h4 class="text-login">{{trans('site.or_or')}}</h4>
                                 <p class="text-center text-muted ">{{trans('site.signup_email')}}</p>
                                  @if ($errors->has('name'))
                                      <div class="alert alert-danger">
                                          <strong>{{ $errors->first('name') }}</strong>
                                      </div>
                                  @endif
                                  @if ($errors->has('email'))
                                      <div class="alert alert-danger">
                                          <strong>{{ $errors->first('email') }}</strong>
                                      </div>
                                  @endif
                                  @if ($errors->has('phone'))
                                      <div class="alert alert-danger">
                                          <strong>{{ $errors->first('phone') }}</strong>
                                      </div>
                                  @endif
                                  @if ($errors->has('password'))
                                      <div class="alert alert-danger">
                                          <strong>{{ $errors->first('password') }}</strong>
                                      </div>
                                  @endif
                                  @if ($errors->has('password_confirmation'))
                                      <div class="alert alert-danger">
                                          <strong>{{ $errors->first('password_confirmation') }}</strong>
                                      </div>
                                  @endif
                                      <form  role="form" method="POST" data-toggle="validator" action="{{ url('user/register') }}">
                                        {{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="form-group has-feedback">
                                                    <input type="text" id="input-firstname" class="form-control" name="name" value="{{ old('name') }}" placeholder="{{ trans('site.full_name') }}" required="">
                                                </div>
                                            </div>  
                                            <div class="col-xs-12 no_email_input">
                                                <div class="form-group has-feedback">
                                                    <input type="email" class="form-control" name="email" value="{{old('email')}}" placeholder="{{ trans('site.enter_your_email_address') }}" required>
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="form-group has-feedback">
                                                    <input type="text" id="input-phone" class="form-control" pattern="[0-9]{9,15}" name="phone" value="{{ old('phone') }}" placeholder="{{ trans('site.phone') }}" required>
                                                </div>
                                            </div>  
                                            <div class="col-xs-12 ">
                                                <div class="form-group has-feedback">
                                                    <input type="password" data-minlength="6" id="inputPass" class="form-control" name="password" placeholder="{{ trans('site.please_enter_your_password') }}" required="">
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>
                                              <div class="col-xs-12 ">
                                                <div class="form-group has-feedback">
                                                    <input id="inputPasswordCon" class="form-control" type="password"  name="password_confirmation" placeholder="{{ trans('site.please_enter_your_confirmation_password') }}" required="">
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>
                                          <!--  <div class="col-xs-12 ">
                                            <div class="form-group">
                                                <label class="col-sm-3  col-xs-4  control-label subscribe-label">Subscribe</label>
                                                <div class="col-sm-9 col-xs-8">               
                                                    <label class="radio-inline">
                                                        <input type="radio" name="newsletter" value="1">
                                                    Yes</label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="newsletter" value="0" checked="checked">
                                                    No</label>
                                                </div>
                                            </div>
                                          </div> -->
                                          
                                              <div class="col-xs-12 ">
                                            <div class="form-group">
                                               <input type="checkbox" name="agreed" class="">
                                                <label class=" control-label subscribe-label">  <a href="{{url('/page/1/privacy_policy_'.App::getLocale())}}">{{trans('site.agree_on')}} {{trans('site.privacy_policy')}}</a> </label>
        
                                            </div>
                                          </div>
                                          
                                        </div>
                                        <div class="col-xs-12 text-center login-btn ">
                                            <button type="submit" class="btn btn-primary">{{trans('site.register')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>     
        </section>
    <!--=============== End Of Reg Page ===============-->
@endsection
@section('script')
    <script>
        $(".no_email").click(function(){
            $(".no_email_input").hide('slow');
            $(".no_email").hide('slow');
            $(".have_email").hide('slow');  
        });
        $(".have_email").click(function(){
            $(".no_email").hide('slow');
            $(".have_email").hide('slow');  
        });
    </script>
@endsection


