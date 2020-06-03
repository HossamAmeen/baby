@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{ URL::to('resources/assets/resources/assets/front/css/material-cards.css') }}" />
@endsection
@section('content')
    <section class="cer-head">
               <div class="parallax-window" data-parallax="scroll" data-natural-width="1500" data-natural-height="1000" data-image-src="{{ url('uploads/configurationsite/source') }}/{{ $con->imageall }}">
                  <div>
                      <div class="overlay">
                      <div class="title">
                      <h2> المحاضر </h2>
                      </div>
                  </div>
                  </div>
               </div>
        </section>
        <section class="instructor">
           <div class="lec-title"><h2> المحاضر:{{ $instructor->name }}</h2></div>
           <div class="container">
               <hr>
               <div class="row">
                   <div class="col-md-3">
                        <div class="right-inner">
                           <div class="row active-with-click">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <article class="material-card Light-Blue">
                                        <h2>
                                            <span> {{ $instructor->name }} </span>
                                            <strong>
                                                <i class="fa fa-fw fa-star"></i>
                                                 {{ $instructor->position }} 
                                            </strong>
                                        </h2>
                                        <div class="mc-content">
                                            <div class="img-container">
                                                  <img class="img-responsive" src="{{ url('uploads/instructor/resize200/') }}/{{ $instructor->image }}" alt="...">
                                            </div>
                                        </div>
                                    </article>
                                </div>
                           </div>
                       </div>
                   </div>
                   <div class="col-md-9">
                       <div class="left-inner">
                            <div class="inst-name"><h3><strong>{{ $instructor->name }}</strong></h3></div>
                            <div class="inst-career"><h4>{{ $instructor->title }}</h4></div>
                            <hr>
                            <div class="inst-specialty"><h4>{{ $instructor->position }}</h4></div>
                            <hr>
                            <div class="inst-cv">
                                {!! $instructor->text !!}
                            </div>
                            <hr>
                            @if(count($courseinstructor)>0)
                            <div class="inst-course">
                                <h3>البرامج التدريبيه</h3>
                            </div>
                            <hr>
                          <div class="table-course">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th><i class="fa fa-book" aria-hidden="true"></i> الدوره التدريبيه</th>
                                    <th><i class="fa fa-clock-o" aria-hidden="true"></i> عدد الساعات</th>
                                    <th><i class="fa fa-clock-o" aria-hidden="true"></i>السعر</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($courseinstructor as $item)
                                    <tr>
                                        <td><a href="{{ url($item->Course->link) }}">{{ $item->Course->title }}</a></td>
                                        <td> {{ $item->Course->duration }}</td>
                                        <td> {{ round($item->Course->price) }} {{ $item->Course->coin }}</td>
                                        
                                    </tr>
                                @endforeach
                                
                                
                                </tbody>
                            </table>
                        </div>
                        @endif
                       </div> 
                   </div>
               </div>
           </div>
        </section>
@endsection
@section('script')
    <script src="{{ URL::to('resources/assets/front/js/jquery.material-cards.min.js')}}"></script>
@endsection