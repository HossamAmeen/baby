@extends('layouts.app')

@section('title')
<title>{{ $vendor -> name }}</title>
@endsection
@section('style')
<style>
.mce-edit-area iframe {
    height: 150px !important;
}
</style>
@endsection
@section('content')

<section id="select-cat">
  <div class="container" id="main_container">
        <div class="row">
            <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs"> 
                
                <div class="tab-content" id="myTabContent"> 
                    <div class="tab-pane fade active in" role="tabpanel" id="dropdown3" aria-labelledby="dropdown3-tab"> 
                       <h4>{{ trans('site.pspc') }}</h4>
                        <div class="col-xs-12 col-md-4">
                            <h4><label for="category_id"></label></h4>
                            <hr>
                            <div class="scrollbar" id="style-15">
                                 <div class="force-overflow">
                                   <ul class="branch00 list-unstyled" style="overflow: scroll;height: 300px;">
                                   @foreach($categories as $category)
                                         <li class="main main0"><h4><a href="javasvript:" onclick="getsubcat({{ $category -> id }},0)">{{ $category -> title }}</a></h4></li>
                                   @endforeach      
                                    </ul>
                                  </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                                <div class="content" >
                                    <h4><label> </label></h4>
                                    <hr>
                                    <ul class="branch00 list-unstyled" id="sub_cat" style="overflow: scroll;height: 300px;">
                                            
                                    </ul>
                                </div>
                        </div>

                        <div class="col-xs-12 col-md-4">
                            <div class="content" >
                                <h4><label> </label></h4>
                                 <hr>
                               	<ul class="branch00 list-unstyled" id="sub_cat2" style="overflow: scroll;height: 300px;">
                                             
                                </ul>
                            </div>
                        </div>
                        <input type="hidden" name="selected_cat" id="selected_cat" value="$categories[0] -> id" />
                        <div class="form-group">
                            <button class="btn btn-info pull-right" onclick="submitdata();">{{trans('site.continue')}}</button><br>
                        </div>
                    </div> 
                    <div class="tab-pane fade" role="tabpanel" id="dropdown4" aria-labelledby="dropdown4-tab"> 
                        <p></p> 
                    </div> 
                    <div class="tab-pane fade" role="tabpanel" id="profile" aria-labelledby="profile-tab"> 
                        <p></p> 
                    </div> 
                    <div class="tab-pane" role="tabpanel" id="dropdown1" aria-labelledby="dropdown1-tab"> 
                        <p></p> 
                    </div> 
                    <div class="tab-pane fade" role="tabpanel" id="dropdown2" aria-labelledby="dropdown2-tab"> 
                        <p></p> 
                    </div> 
                </div> 
            </div>	
       </div>
	</div>
</section>


@endsection

@section('script')

<script type="text/javascript">

     function getsubcat(id,status){
	var data = new FormData();
	var token = "{{ csrf_token() }}";
	data.append('cat_id', id);
	data.append('status', status);
	data.append('_token',token);
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	    	if(!status)
	    	{
	    		document.getElementById('sub_cat').innerHTML = this.responseText;
	    		document.getElementById('sub_cat2').innerHTML='';
	    		document.getElementById('selected_cat').value = id;
	    		
	    	}
	    	else
	    	{
	    		document.getElementById('sub_cat2').innerHTML += this.responseText;
	    		document.getElementById('selected_cat').value = id;
	    	}
	     
	    }
	  };
	  
	  xhttp.open("POST", "{{ url('vendor/category/sub') }}", true);
	  xhttp.send(data);
      }
      function Tiny(){
       tinymce.init({
            mode : "specific_textareas",
            mode : "textareas",
            editor_selector : "area1",
            height: 500,
            fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
            theme: 'modern',
            plugins: [
              'advlist autolink lists link image charmap print preview hr anchor pagebreak',
              'searchreplace wordcount visualblocks visualchars code fullscreen',
              'insertdatetime media nonbreaking save table contextmenu directionality',
              'emoticons template paste textcolor colorpicker textpattern imagetools jbimages'
            ],
            toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages',
            toolbar2: 'ltr rtl | print preview media | forecolor backcolor emoticons | fontsizeselect',
            //image_advtab: true,
            templates: [
              { title: 'Test template 1', content: 'Test 1' },
              { title: 'Test template 2', content: 'Test 2' }
            ],
            content_css: [
              '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
              '//www.tinymce.com/css/codepen.min.css'
            ]
    });
        $('#status').select2({
	  'placeholder' : 'أختار الحالة',
	});
	$('#padge_id').select2({
	  'placeholder' : 'أختار padge',
	});
	$('#special').select2({
	  'placeholder' : 'أختار special',
	});
	$('#featured').select2({
	  'placeholder' : 'أختار featured',
	});
	$('#currency_id').select2({
	  'placeholder' : 'أختار العملة',
	});
	$('#category').select2({
	  'placeholder' : 'أختار القسم',
	});
	$('#radio_id').select2({
	  'placeholder' : 'أختار Radio',
	});
	$('#check_id').select2({
	  'placeholder' : 'أختار Check',
	});
	$('#lang').select2({
	  'placeholder' : 'أختار اللغة',
	});
	$('#brand').select2({
	  'placeholder' : 'إختر الماركة ',
	});

	$('.addradio').click(function() {
	      $(this).before('<div><div class="form-group"> <input type="text" class="form-control" name="radio_name[]" placeholder="{{trans("home.radio_name")}}" />  </div><div class="form-group"> <input type="text" class="form-control" name="radio_price[]" placeholder="{{trans("home.radio_price")}}" />  </div><span class="removeradio btn btn-danger">{{trans("home.delete")}}</span><hr style="height:1px;border:none;color:#333;background-color:#333;"></div>');
	     
	    });
	
	    $(document).on('click','.removeradio',function() {
	      $(this).parent('div').remove();
	    });
	$('.addcheck').click(function() {
	      $(this).before('<div><div class="form-group"> <input type="text" class="form-control" name="check_name[]" placeholder="{{trans("home.check_name")}}" />  </div><div class="form-group"> <input type="text" class="form-control" name="check_price[]" placeholder="{{trans("home.check_price")}}" />  </div><span class="removecheck btn btn-danger">{{trans("home.delete")}}</span><hr style="height:1px;border:none;color:#333;background-color:#333;"></div>');
	     
	    });
	
	    $(document).on('click','.removecheck',function() {
	      $(this).parent('div').remove();
	    });
    }
      
      
      function submitdata()
      {
      	var catid = document.getElementById('selected_cat').value;
      	var checkbox = document.getElementsByClassName('input_check');
      	var ids = new Array();
      	for (i = 0; i < checkbox.length; i++) {
	    if(checkbox[i].checked){
	    	ids.push(checkbox[i].value);
	    }
	}
	if(ids.length >0)
	{
	var data = new FormData();
	var token = "{{ csrf_token() }}";
	data.append('cat_id', catid);
	data.append('ids', ids);
	data.append('vendor_id',{{ $vendor -> id }} );
	data.append('_token',token);
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
		document.getElementById('main_container').innerHTML = this.responseText;
		Tiny();
	    }
	  };
	  
	  xhttp.open("POST", "{{ url('vendor/product/add') }}", true);
	  xhttp.send(data);
	  }
	  else
	  {
	  	alert('{{ trans('site.pspc') }}');
	  }
      }
      

</script>
 

@endsection