

{!! Form::open(['route' => 'saveproduct', 'data-toggle'=>'validator', 'files'=>'true']) !!}

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
                        
                        <input type="hidden" name="vendor_id" value="{{ $vendor_id }}" />
                            <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.title')}} : </label></h5>
                                    <input type="text" class="form-control" placeholder="{{trans('home.title')}}" name="title" required >
                                </div>
                            </div>  
                               <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.title_ar')}} : </label></h5>
                                    <input type="text" class="form-control" placeholder="{{trans('home.title_ar')}}" name="title_ar" required >
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.code')}} : </label></h5>
                                    <input type="text" class="form-control" placeholder="{{trans('home.code')}}" name="code" required>
                                </div>
                            </div> 
                            
                            <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.in_stock')}} : </label></h5>
                                    <input type="number" min="0" class="form-control" placeholder="{{trans('home.in_stock')}}" name="stock" required>
                                </div>
                            </div> 
                            
                            <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.price')}} : </label></h5>
                                    <input type="text" class="form-control" placeholder="{{trans('home.price')}}" name="price" required>
                                </div>
                            </div> 

                            
                            <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.category')}} : </label></h5>
                                    <select class="form-control" name="category[]" id="category" required multiple>
				            <option></option>
				            @foreach($category as $cat)
				            <option value="{{ $cat->id }}" selected>{{ $cat->title}}</option>
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
				            <option value="{{ $brand -> id }}">{{ $brand->name }}</option>
				            @endforeach
				        </select>
                                </div>
                            </div>
                            
                            
                            
                            
                            
                            <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.status')}} : </label></h5>
                                    <select class="form-control" name="status" id="status" required>
				      <option></option>
				      <option value="1">{{trans('home.yes')}}</option>
				      <option value="0">{{trans('home.no')}}</option>
				    </select>
                                </div>
                            </div>

                            
                            <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.main_photo')}} : </label></h5>
                                    <input type="file" class="form-control" name="photo" id="photo" required >
                                </div>
                            </div> 
                            
                            <div class="col-xs-12 col-sm-6 ">
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
                    <input type="number" min="0" value="0" class="form-control" placeholder="{{trans('home.discount')}}" name="discount">
                  </div>
               </div>  
                            <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.weight')}} : </label></h5>
                                    <input type="text" class="form-control" placeholder="{{trans('home.weight')}}" name="weight">
                                </div>
                            </div>
                            
              <div class="col-xs-12 col-sm-4 ">
                 <div class="form-group">
                    <h5><label for="">{{trans('home.max_quantity')}} : </label></h5>
                    <input type="number" min="0" class="form-control" placeholder="{{trans('home.max_quantity')}}" name="max_quantity" >
                 </div>
               </div>
                            
               <div class="col-xs-12 col-sm-4 ">
                  <div class="form-group">
                     <h5><label for="">{{trans('home.min_quantity')}} : </label></h5>
                     <input type="number" min="0" class="form-control" placeholder="{{trans('home.min_quantity')}}" name="min_quantity" >
                  </div>
               </div> 
               
                
               
               		<div class="col-xs-12 col-sm-4 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.padge')}} : </label></h5>
                                    <select class="form-control" name="padge_id" id="padge_id">
				            <option></option>
				            @foreach($padge as $pad)
				            	<option value="{{ $pad -> id }}">{{ $pad -> title }}</option>
				            @endforeach
				        </select>
                                    
                                </div>
                            </div> 
                            
                    <hr>
			<span>{{trans('home.radio')}} :</span>
			<div>
			    <div>
			        {{-- <div class="form-group">
			            <input type="text" class="form-control" name="radio_name[]" placeholder="{{trans('home.radio_name')}}" /> 
			        </div>
			        <div class="form-group">
			            <input type="text" class="form-control" name="radio_price[]" placeholder="{{trans('home.radio_price')}}" /> 
			        </div> 
			            <span class="removeradio btn btn-danger">{{trans("home.delete")}}</span>--}}
			            <hr style="height:1px;border:none;color:#333;background-color:#333;">
			    </div>
			    <div>
			        <span class="addradio btn btn-success">{{trans("home.add")}}</span>
			    </div>
			</div>
			<hr>
			<span>{{ trans('home.check') }} :</span>
			<div>
			    <div>
			        {{-- <div class="form-group">
			            <input type="text" class="form-control" name="check_name[]" placeholder="{{trans('home.check_name')}}" /> 
			        </div>
			        <div class="form-group">
			            <input type="text" class="form-control" name="check_price[]" placeholder="{{trans('home.check_price')}}" /> 
			        </div> 
			            <span class="removecheck btn btn-danger">{{trans("home.delete")}}</span>
			            --}}
			            <hr style="height:1px;border:none;color:#333;background-color:#333;">
			    </div>
			    <div>
			        <span class="addcheck btn btn-success">{{trans("home.add")}}</span>
			    </div>
			</div>
			
			
                            
                            
              
                  <div class="en-desc">
                    <label for="">{{trans('home.short_description')}} :</label>
                    <textarea class="form-control area1" name="short_description" id="short_description" ></textarea>
                  </div>
                   <hr>
                   <div class="ar-desc">
                    <label for="">{{trans('home.description')}} :</label>
                    <textarea class="form-control area1" name="description" id="description" ></textarea>
                  </div>

              </div>

            </div>
          </div>
        </div>
        
        <div class="btns-bottom">
	    <button type="submit" class="btn btn-default">{{trans('home.submit')}}</button>
	    
	  </div>
	
	{!! Form::close() !!}