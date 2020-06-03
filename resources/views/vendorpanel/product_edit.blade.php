@extends('layouts.app')

@section('title')
<title>{{ $vendor -> name }}</title>
@endsection

@section('content')

{!! Form::open(['method'=>'POST','url' => 'vendor/product/update/'.$product->id,'files'=>'true']) !!}

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
               <strong>Information about the product:</strong> 
                </a>
              </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
                    <div class="row">
                        
                        <input type="hidden" name="vendor_id" value="{{ $vendor -> id }}" />
                            <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.title')}} : </label></h5>
                                    <input type="text" class="form-control" value="{{ $product->title }}" name="title" required >
                                </div>
                            </div>  
                               <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.title_ar')}} : </label></h5>
                                    <input type="text" class="form-control" value="{{ $product->title_ar }}" name="title_ar" required >
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.code')}} : </label></h5>
                                    <input type="text" class="form-control" value="{{ $product->code }}" name="code" required>
                                </div>
                            </div> 
                            
                            <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.in_stock')}} : </label></h5>
                                    <input type="number" min="0" class="form-control" value="{{ $product->stock }}" name="stock" required>
                                </div>
                            </div> 
                            
                            <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.price')}} : </label></h5>
                                    <input type="text" class="form-control" value="{{ $product->price }}" name="price" required>
                                </div>
                            </div> 

                            
                            <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.category')}} : </label></h5>
                                    <select class="form-control" name="category[]" id="category" required multiple>
				            <option></option>
				             @foreach($category as $cat)
				            <option value="{{ $cat -> id }}"
				            @foreach($catids as $catid)
				            	@if($cat->id == $catid) selected @endif
				            @endforeach
				            >{{ $cat->title}}</option>
				            @endforeach
				        </select>
                                </div>
                            </div>  
                            
                            <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.brand')}} : </label></h5>
                                    <select class="form-control" name="brand" id="brand" required>
				            <option></option>
				            @foreach($brands as $brand)
				            <option value="{{ $brand -> id }}" @if($brand->id == $product->brand_id) selected @endif>{{ $brand->name }}</option>
				            @endforeach
				        </select>
                                </div>
                            </div>
                            
                            
                            
                            
                            
                            <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.status')}} : </label></h5>
                                    <select class="form-control" name="status" id="status" required>
				      <option></option>
				      <option @if($product->vendor_status == '1') selected @endif value="1">{{trans('home.yes')}}</option>
            			      <option @if($product->vendor_status == '0') selected @endif value="0">{{trans('home.no')}}</option>
				    </select>
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-sm-6 ">
                            	<div class="form-group">
			        	<span>{{trans('home.imageshow')}} :</span> <img src="{{url('\uploads\product\resize200')}}\{{$product->image}}" width="150" height="150">
			    	</div>
                            
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.main_photo')}} : </label></h5>
                                    <input type="file" class="form-control" name="photo" id="photo">
                                </div>
                            </div> 
                            
                            
                            <div class="col-xs-12 col-sm-6 ">
                 
                 	@foreach($img as $mg)
         
		         <div class="pro_image">
		             <button type="button" class="close delete_one" data-id="{{ $mg->id }}" data-dismiss="modal">&times;</button>
		            <img src="{{ URL::to('uploads/product/resize200/')}}/{!! $mg->image !!}" width="150" height="150">
		          </div>
		         
		    @endforeach
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.multi_photo')}} : </label></h5>
                                    <input type="file" class="form-control" name="photos[]" id="photos" multiple >
                                </div>
                            </div> 
                            

                    </div>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                 <strong>More details about products:</strong> 
                </a>
              </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
              <div class="panel-body">
              
              <div class="col-xs-12 col-sm-6 ">
                 <div class="form-group">
                    <h5><label for="">{{trans('home.discount')}} : </label></h5>
                    <input type="number" min="0" value="0" class="form-control" value="{{ $product->discount }}" name="discount">
                  </div>
               </div>
                            
                            <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.weight')}} : </label></h5>
                                    <input type="text" class="form-control" value="{{ $product->weight }}" name="weight">
                                </div>
                            </div>
                            
              <div class="col-xs-12 col-sm-4">
                 <div class="form-group">
                    <h5><label for="">{{trans('home.max_quantity')}} : </label></h5>
                    <input type="number" min="0" class="form-control" value="{{ $product->max_quantity }}" name="max_quantity" >
                 </div>
               </div>
                            
               <div class="col-xs-12 col-sm-4">
                  <div class="form-group">
                     <h5><label for="">{{trans('home.min_quantity')}} : </label></h5>
                     <input type="number" min="0" class="form-control" value="{{ $product->min_quantity }}" name="min_quantity" >
                  </div>
               </div> 
               
                
               
               <div class="col-xs-12 col-sm-4">
                                 <div class="form-group ">
                                    <h5><label for="">{{trans('home.padge')}} : </label></h5>
                                    <select class="form-control" name="padge_id" id="padge_id">
				            <option></option>
				            @foreach($padge as $pad)
				            	<option value="{{ $pad -> id }}" {{ ($pad -> id == $product->padge_id)?'selected':'' }}>{{ $pad -> title }}</option>
				            @endforeach
				        </select>
                                    
                                </div>
                            </div>
                            
                    <hr>
		    <span>{{trans('home.radio')}} :</span>
		<div>
		    @foreach($radio as $item)
		        <div>
		            <div class="form-group">
		                <input type="text" class="form-control" name="radio_name[]" value="{{ $item->option }}" placeholder="{{trans('home.radio_name')}}" /> 
		            </div>
		            <div class="form-group">
		                <input type="text" class="form-control" name="radio_price[]" value="{{ $item->price }}" placeholder="{{trans('home.radio_price')}}" /> 
		            </div> 
		            <span class="removeradio btn btn-danger">{{trans("home.delete")}}</span>
		            <hr style="height:1px;border:none;color:#333;background-color:#333;">
		        </div>
		    @endforeach    
		    <div>
		        <span class="addradio btn btn-success">{{trans("home.add")}}</span>
		    </div>
		</div>
		<hr>
		<span>{{trans('home.check')}} :</span>
		<div>
		    @foreach($check as $item)
		        <div>
		            <div class="form-group">
		                <input type="text" class="form-control" name="check_name[]" value="{{ $item->option }}" placeholder="{{trans('home.check_name')}}" /> 
		            </div>
		            <div class="form-group">
		                <input type="text" class="form-control" name="check_price[]" value="{{ $item->price }}" placeholder="{{trans('home.check_price')}}" /> 
		            </div> 
		            <span class="removecheck btn btn-danger">{{trans("home.delete")}}</span>
		                
		            <hr style="height:1px;border:none;color:#333;background-color:#333;">
		        </div>
		    @endforeach    
		    <div>
		        <span class="addcheck btn btn-success">{{trans("home.add")}}</span>
		    </div>
		</div>
			
			
                            
                            
              
                  <div class="en-desc">
                    <label for="">{{trans('home.short_description')}} :</label>
                    <textarea class="form-control area1" name="short_description" id="short_description" >{!! $product->short_description !!}</textarea>
                  </div>
                  
                   <hr>
                   
                   <div class="ar-desc">
                    <label for="">{{trans('home.description')}} :</label>
                    <textarea class="form-control area1" name="description" id="description" >{!! $product->description !!}</textarea>
                  </div>
                  
                  <hr>
                   
                   <div class="ar-desc">
                    <label for="">{{trans('site.comments')}} :</label>
                    <textarea class="form-control area1" name="comment" id="comment" >{!! $product->comment !!}</textarea>
                  </div>

              </div>

            </div>
          </div>
        </div>
        
        <div class="btns-bottom">
	    <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
	    
	  </div>
	
	{!! Form::close() !!}
	
@endsection
	
	
@section('script')

<script>


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
	    
	    $(document).ready(function(){
    $('.delete_one').on('click', function(){

        var id  = $(this).data("id");
        
        
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
            $.ajax({
                url:" {{ URL::to('deleteoneimage')}}/"+ id,
                method:'POST',
                data:{id:id},
                success:function()
                {
                location.reload();
                }
            });
                


    });
});

</script>


@endsection




