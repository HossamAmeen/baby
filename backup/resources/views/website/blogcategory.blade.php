@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{ URL::to('resources/assets/resources/assets/front/css/easy-autocomplete.min.css') }}" />
<style>
	.hov a{
	text-decoration:none; 
	color:#000;
	}
	.hov a:hover{
	text-decoration:none;
	transition:0.5s ease;
	color:#2196F3;
	}
</style> 
@endsection
@section('meta')
  <meta name="description" content="{{ $blogcategory->meta_description }}">
  <meta name="keywords" content="{{ $blogcategory->meta_keywords }}">
@endsection
@section('content')
            
            <section class="cer-head">
            
              <div class="parallax-window" data-parallax="scroll" data-natural-width="1500" data-natural-height="1000" data-image-src="{{ url('uploads/configurationsite/source') }}/{{ $con->imageall }}">
                  <div>
                      <div class="overlay">
                          <div class="title">
                              <h2>{{ $blogcategory->title }}</h2>
                          </div>
                      </div>
                  </div>
               </div>
            <div class="container">
                <ol class="breadcrumb">
                  <li><a href="{{ url('/') }}">الرئيسية</a></li>
                  <li class="active">{{ $blogcategory->title }}</li>
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
                                     <p>عرض المقالات</p>
                                 </div>
                             </div>
                              <div class="col-lg-4">
                               
                              </div><!-- /.col-lg-6 -->
                            </div><!-- /.row -->
                        </div>
                        <div class="content-courses">
                                    <div class="row">
                                    @foreach($blogitem as $item)
                                        <div class="col-xs-12 col-sm-6 col-md-4" id="course_{{ $item->id }}">
                                            <div class="inner-content text-center">
                                                <div class="head">
                                                    <div class="img"><img src="{{ url('uploads/blogitem/resize200') }}/{{ $item->image }}" alt="..."></div>
                                                </div>
                                                 <div class="desc">
                                                     <h4 class="hov" ><a  href="{{ url($item->link) }}">{{ $item->title }}</a></h4>
                                                     
                                                     <span><p>{{ $item->date }}</p></span>
                                                  
                                                 </div>    
                                            </div>
                                        </div>
                                    @endforeach
                                    </div>
                                </div>

                                            </div>
               <div class="left-search">
                        <div class="col-md-3" style="background-color: #eee; padding: 20px;">
                              <div class="se-head text-center">
                                   <h3>البحث</h3>
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
                                    <hr class="soften-d">
                                    <div class="all-courses">
                                       <h3 class="borderr">كل الدورات</h3>
                                        <ul>
                                        @foreach($cats as $c)
                                            <li><a href="{{ url($c->link) }}">{{ $c->title }}</a></li>
                                        @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                    <hr class="soften-d">
                                    <div class="follow-twitter ">
                                   <div class="title "><h3 class="borderr">تابعنا علي تويتر</h3></div>
                                  <div class="text-center"><a href="https://twitter.com/intent/tweet?screen_name=GoldenMindsAc&ref_src=twsrc%5Etfw" class="twitter-mention-button" data-size="large" data-lang="ar" data-show-count="false">Tweet to @GoldenMindsAc</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
 </div>
                                  <hr class="soften-d"> 
                                </div>
                                <div class="twitter-tweets">
                                    <div class="title" style=" margin-bottom: 22px;"><h3 class="borderr">تغريدات تويتر</h3></div>
                                    <a class="twitter-timeline" data-lang="ar" data-height="450" data-theme="dark" href="https://twitter.com/GoldenMindsAc?ref_src=twsrc%5Etfw">Tweets by GoldenMindsAc</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

                                </div>
                            </div>
                        </div>
                
                    </div>


                </div>
                
                
                
                
                
                                
                                
                                
                                
                                
                                
            
</section>
@endsection
@section('script')
<script src="{{ URL::to('resources/assetsresources/assets/front/js/jquery.easy-autocomplete.min.js') }}"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>


$('.newsearch').on('input', function(){
    var title =  $(this).val();
    var ids = $('.ids').val();
        
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        $.ajax({
              url:" {{url('newsearch')}}",
              method:'POST',
              data:{title:title,ids:ids},
              success:function(data)
              {
                //var s = $('.h');
                //var ht = data ;
                for(var i=0;i<data[0].length;i++){
                    $("#course_"+ data[0][i]).hide();
                }
                for(var j=0;j<data[1].length;j++){
                    $("#course_"+ data[1][j]).show();
                }
                //s.html(ht);
              }
         });


});

Auto();


function Auto(){  
        var options = {

                url: function(phrase) {
                     return "{{url('/')}}/search-home/autocomplete";
                 },

                getValue: function(element) {
                    return element.value;
                    },

                    ajaxSettings: {
                    dataType: "json",
                    method: "GET",
                    data: {
                         dataType: "json"
                        }
                  },

                preparePostData: function(data) {
                    data.phrase = $(".q").val();
                    return data;
                    },

                requestDelay: 400

                };

                $(".q").easyAutocomplete(options);  
}
</script>
@endsection