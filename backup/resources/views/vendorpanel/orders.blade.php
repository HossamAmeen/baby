@extends('layouts.app')

@section('title')
<title>{{ $vendor -> name }}</title>
@endsection

@section('content')

<section id="product-info">
      <div class="container">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
               <strong>{{ trans('site.orderreport') }} :</strong> 
                </a>
              </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
                    <div class="row">
                        <input type="hidden" name="vendor_id" id="vendor_id" value="{{ $vendor -> id }}" />
                            <div class="col-xs-12 col-sm-6 ">
                            
                            <div class="form-group">
                            <h5><label>{{trans('home.startdate')}} :</label></h5>
	                            <div class='input-group date' id='datetimepicker2'>
	                                <input type='text' class="form-control" placeholder="{{ trans('home.startdate') }}" name="startdate" id="startdate" required/>
	                                <span class="input-group-addon">
	                                    <span ><i class="fa fa-calendar" aria-hidden="true"></i></span>
	                                </span>
	                            </div>
	                        </div>
                                 
                            </div>  
                            <div class="col-xs-12 col-sm-6 ">
                                 
                                    
                                    <div class="form-group">
                                    <h5><label for="">{{trans('home.enddate')}} :</label></h5>
		                            <div class='input-group date' id='datetimepicker1'>
		                                <input type='text' class="form-control"  value="{{ date('Y-m-d') }}" name="enddate" id="enddate" required/>
		                                <span class="input-group-addon">
		                                    <span ><i class="fa fa-calendar" aria-hidden="true"></i></span>
		                                </span>
		                            </div>
		                        </div>
                                    
                                    
                                
                            </div> 
                            
                           <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('home.status')}}:</label></h5>
                                    <select class="form-control" name="status" id="status">
				      <option></option>
				      @foreach($status as $statu)
				      <option value="{{ $statu -> id }}">{{ $statu -> name }}</option>
				      @endforeach
				    </select>
                                </div>
                            </div>
                             <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <button id="displaybtn" class="btn btn-block btn-info">{{trans('home.display')}}</button>
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-sm-6 ">
                                 <div class="form-group">
                                    <button id="printbtn" class="btn btn-block btn-info">{{trans('home.print')}}</button>
                                </div>
                            </div>
                          
                    </div>
              </div>
            </div>
          </div>
          </div>
        </div>
        </div>
    </section>
    
    <div id="displaycontent">
  
  </div>
  <br><br><br>

@endsection

@section('script')
<script>
$('#status').select2({
  'placeholder' : 'أختار الحالة',
});
$('#lang').select2({
  'placeholder' : 'أختار اللغة',
});
$('#delivery').select2({
  'placeholder' : 'إختر الموصل',
});

 
    $('#displaybtn').click(function(){
    	var $startdate=$('#startdate').val();
    	var $enddate=$('#enddate').val();
    	var $status=$('#status option:selected').val();
    	
    	var $vendor_id = $('#vendor_id').val();
    	if($startdate != '' && $enddate != ''){
    		if($startdate <= $enddate){
    			
    			$.ajaxSetup({
		        headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        }
		        });
		        $.ajax({
		              url:" {{url('vendor/orders/report')}}",
		              method:'POST',
		              data:{startdate:$startdate,enddate:$enddate,status:$status,vendor_id:$vendor_id},
		              success:function(data)
		              {
		                $('#displaycontent').html(data);
		                reload();
		              }
		        });
    		
    		}
    		else{
    			alert("uncorrect choice of dates");
    		}
    	}
    	else{
    		alert("choice start date and end date");
    	}
    
    });
    
    function reload()
    {
    	
        $('.newdatatable').DataTable();
        
    }
    
    $('#printbtn').click(function(){
    
    	    var mywindow = window.open('', 'PRINT', 'height=400,width=600');
	    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
	    mywindow.document.write('</head><body >');
	    mywindow.document.write('<h1>' + document.title  + '</h1>');
	    mywindow.document.write(document.getElementById('displaycontent').innerHTML);
	    mywindow.document.write('</body></html>');
	
	    mywindow.document.close(); // necessary for IE >= 10
	    mywindow.focus(); // necessary for IE >= 10*/
	
	    mywindow.print();
	    mywindow.close();
	
	    return true;
    });
</script>


@endsection