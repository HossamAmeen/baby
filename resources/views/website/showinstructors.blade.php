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
                    <h2>فريق محاضرين ومدربين</h2>
                </div>
            </div>
        </div>
    </div>
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}">الرئيسية</a></li>
        <li class="active">فريق محاضرين ومدربين</li>
    </ol>
</section>
<section class="container">
    <div class="page-header text-center">
        <h2 style="
    line-height: 38px;
">
            <strong >{!! $responsivetext->instructor_txt1 !!}</strong><br>
            <small>{!!  $responsivetext->instructor_txt2 !!}  </small>
        </h2>
        <a href="{{ url('join-us') }}"><button class="btn btn-info">انضم الينا</button></a>
    </div>
    <div class="row active-with-click">
        @foreach($instructors as $item)
            <div class="col-md-3 col-sm-6 col-xs-12">
                    <article class="material-card Light-Blue">
                    <h2>
                    <span ><a style="text-decoration: none;color: #fff; "href="{{url('show-instructor')}}/{{ $item->link }}" >{{ $item->name }}</a></span>
                    <strong>
                   
                    {{ $item->position }}
                    </strong>
                    </h2>
                        <div class="mc-content">
                            <div class="img-container">
              <a href="{{url('show-instructor')}}/{{ $item->link }}"><img class="img-responsive" src="{{ url('uploads/instructor/resize200/') }}/{{ $item->image }}" alt="..."></a>
                            </div>
                            <div class="mc-description">
                            <?php
                                $content = strip_tags($item->text);
                                $len = strlen($content);
                                if($len >200){
                                    $pos=strpos($content, ' ', 200);
                                    echo '<p>'.substr($content,0,$pos ).'</p>';
                                }else{
                                    echo '<p>'.$content.'</p>';
                                }
                           ?>
                           <a href="{{url('show-instructor')}}/{{ $item->link }}" >{{trans('site.readmore')}}</a>
                            </div>
                        </div>
                        <a class="mc-btn-action">
                            <i class="fa fa-bars"></i>
                        </a>
                       <!-- <div class="mc-footer">
                            {{-- <h4> Social </h4>
                            <a class="fa fa-fw fa-facebook"    href="#"></a>
                            <a class="fa fa-fw fa-twitter"     href="#"></a>
                            <a class="fa fa-fw fa-linkedin"    href="#"></a>
                            <a class="fa fa-fw fa-google-plus" href="#"></a> --}}
                        </div>-->
                    </article>
                </div>
        @endforeach
    </div>
</section>
@endsection
@section('script')
    <script src="{{ URL::to('resources/assets/front/js/jquery.material-cards.min.js')}}"></script>
@endsection