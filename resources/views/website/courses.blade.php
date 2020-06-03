@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{ URL::to('resources/assets/resources/assets/front/css/easy-autocomplete.min.css') }}" />
@endsection
@section('content')
            
            <section class="cer-head">
            
               <div class="parallax-window" data-parallax="scroll" data-natural-width="1500" data-natural-height="600" data-image-src="{{ url('uploads/1.jpg') }}">
                  <div>
                      <div class="overlay">
                          <div class="title">
                              <h2>كل الكورسات</h2>
                          </div>
                      </div>
                  </div>
               </div>
            <div class="container">
                <ol class="breadcrumb">
                  <li><a href="{{ url('/') }}">الرئيسية</a></li>
                  <li class="active">كل الكورسات</li>
                </ol>
                <hr>    
            </div>
        </section>
        <section class="result-page">
                  <div class="container">
                    <div class="row">
                        <div class="col-md-9">
                        <div class="search">
                            <div class="row">
                             <div class="col-lg-8">
                                 <div class="result">
                                     <p>عرض الدورات</p>
                                 </div>
                             </div>
                              <div class="col-lg-4">
                                <div class="input-group">
                                  <input type="text" class="form-control newsearch" placeholder="Search for...">
                                </div><!-- /input-group -->
                              </div><!-- /.col-lg-6 -->
                            </div><!-- /.row -->
                        </div>
                        <div class="content-courses">
<input value="{{$ids}}" class="ids" type="hidden" />
                                    <div class="row">
                                    @foreach($courses as $item)
                                        <div class="col-xs-12 col-sm-6 col-md-4" id="course_{{ $item->id }}">
                                            <div class="inner-content text-center">
                                                <div class="head">
                                                    <div class="img"><img src="{{ url('uploads/course/resize200') }}/{{ $item->image }}" alt="..."></div>
                                                </div>
                                                 <div class="desc">
                                                     <h2><a href="{{ url($item->link) }}">{{ $item->title }}</a></h2>
                                                     <span><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                                     <span><p>يبدا في {{ $item->date }}</p></span>
                                                     <hr>
                                                     <div class="inner-desc">
                                                           <div class="row">
                                                               <div class="col-lg-6">
                                                                    <span><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $item->duration }}</span>
                                                                    <span><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $item->location }}</span>
                                                               </div>
                                                               <div class="col-lg-6"> <span class="price"><p class="orange">{{ $item->price }}دولار</p></span></div>
                                                           </div> 
                                                     </div> 
                                                 </div>    
                                            </div>
                                        </div>
                                    @endforeach
                                    </div>
                                </div>

                                            </div>
                    <div class="left-search">
                        <div class="col-md-3">
                                <div class="se-head text-center">
                                   <h2>البحث</h2>
                                    <p>{{ $responsivetext->home_search_title }}</p>
                                </div>
                                    <div class="input-group">
                                    {!! Form::open(['method' => 'GET' ,'url' => 'search-home']) !!}
                                      <input type="text" class="form-control q" name="searchtext" placeholder="{{ $responsivetext->home_search_txt }}">
                                      <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                      </span>
                                    {!! Form::close() !!}
                                    </div><!-- /input-group -->
                                    @if(count($cats)>0)
                                    <div class="all-courses">
                                       <h2>كل الدورات</h2>
                                        <ul>
                                        @foreach($cats as $c)
                                            <li><a href="{{ url($c->link) }}">{{ $c->title }}</a></li>
                                        @endforeach
                                        </ul>
                                    </div>
                                    @endif
                            </div>
                        </div>
                    </div>


                </div>
            
</section>
@endsection