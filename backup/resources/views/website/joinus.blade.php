@extends('layouts.app')
@section('content')
        <section class="cer-head">
                <div class="parallax-window" data-parallax="scroll" data-natural-width="1500" data-natural-height="1000" data-image-src="{{ url('uploads/configurationsite/source') }}/{{ $con->imageall }}">
                    <div>
                        <div class="overlay">
                        <div class="title">
                        <h2>انضم الينا</h2>
                        </div>
                    </div>
                    </div>
                </div>
        </section>
        <section class="join-us">
               <div class="container">
               <div class="title text-center">
                   <h2>{{ $responsivetext->join_title }}</h2>
                   <h4>اكادميه جولدن ميند للتدريب </h4>
               </div>
               <div class="row">
                   <div class="col-md-6">
                       <div class="inner-right">
                                    <div class="desc">
                                        <p> {!! $responsivetext->join_txt !!}</p>
                                    </div>
                                    <div class="img">
                                        <img class="img-responsive" src="{{ url('uploads/join/source') }}/{{ $responsivetext->join_img }}" alt="...">
                                    </div>
                        </div>
                   </div>
                   <div class="col-md-6">
                        <div class="inner-left">
                            <div class="inner-left-content">
                                <div class="row">
                                   <div class="title">
                                       <div class="col-md-8">
                                            <h4>{{ $responsivetext->join_form1 }}</h4>
                                            <h5>{{ $responsivetext->join_form2 }}</h5>
                                        </div>
                                        <div class="col-md-4">
                                           <div class="pull-right"><i class="fa fa-user fa-4x" aria-hidden="true"></i></div>
                                        </div>
                                   </div>
                                        {!! Form::open(['url' => 'postjoinus', 'data-toggle'=>'validator', 'files'=>'true']) !!}
                                            <div class="col-xs-12 ">
                                                 <div class="form-group">
                                                        <input type="text" class="form-control" id="name" name="name" placeholder="الاسم كاملا" required>
                                                  </div>
                                              </div>
                                              <div class="col-xs-12 ">
                                                  <div class="form-group">
                                                        <input type="text" class="form-control" name="number" id="number" placeholder="رقم الهاتف" required>
                                                  </div>
                                              </div>
                                              <div class="col-xs-12 ">
                                                  <div class="form-group">
                                                        <input type="email" class="form-control" name="email" id="email" placeholder="البريد الالكترونى" required>
                                                  </div>
                                              </div>

                                              <div class="col-xs-12 ">
                                                  <div class="form-group">
                                                        <textarea class="form-control" name="major" placeholder="التخصص" required></textarea>
                                                  </div>
                                              </div>
                                              <div class="col-xs-12 ">
                                                  <div class="form-group">
                                                        <textarea class="form-control" name="message" placeholder="المجالات المقدمة"></textarea>
                                                  </div>
                                              </div>
                                              <div class="col-xs-12 ">
                                                  <div class="form-group">
                                                        <input type="file"  name="attach"  >
                                                  </div>
                                              </div>
                                              <div class="col-xs-12 ">
                                                   <button type="submit" class="btn btn-info btn-block">انضم الينا</button>
                                              </div>
                                    {!! Form::close() !!}
                                </div>
                                @if(session::has('join-us'))
                                    <div class="alert alert-success"><strong>{{ Session::get('join-us') }}</strong></div>
                                    {{ Session::forget('join-us') }}
                                @endif
                                
                            </div>
                        </div>
                   </div>
               </div>
                
                
            </div>
        </section>
@endsection