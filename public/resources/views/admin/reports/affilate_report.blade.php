@extends('layouts.admin')

@section('content')

<div class="form-group select-group">
    <label>{{trans('home.startdate')}} :</label>
    <input type="text" class="form-control date" placeholder="{{ trans('home.startdate') }}" name="startdate" id="startdate" required>
  </div>
  
  <div class="form-group select-group">
    <label>{{trans('home.enddate')}} :</label>
    <input type="text" class="form-control date" value="{{ date('Y-m-d') }}" name="enddate" id="enddate" required>
  </div>
  <br/>
  
  <div class="form-group select-group">
  <label>{{trans('home.status')}} :</label>
    <select class="form-control" name="status" id="status">
      <option></option>
      @foreach($status as $statu)
      <option value="{{ $statu -> id }}">{{ $statu -> name }}</option>
      @endforeach
    </select>
  </div>
  
  <div class="form-group select-group">
  <label>{{trans('home.affilate')}} :</label>
    <select class="form-control" name="affilate" id="affilate">
      <option></option>
      @foreach($affilates as $affilate)
      <option value="{{ $affilate -> id }}">{{ $affilate -> name }}</option>
      @endforeach
    </select>
  </div>
  
  
  <div class="btns-bottom">
    <button id="displaybtn" class="btn btn-default">{{trans('home.display')}}</button>
    <button id="printbtn" class="btn btn-default">{{trans('home.print')}}</button>
  </div>
  
  <div id="displaycontent">
  
  </div>

@endsection


@section('script')
<script>
$('#status').select2({
  'placeholder' : 'أختار الحالة',
});
$('#affilate').select2({
  'placeholder' : '{{trans('home.affilate')}}',
});


 $( ".date" ).datepicker({
     dateFormat: "yy-mm-dd"
    });
 
    $('#displaybtn').click(function(){
    	var $startdate=$('#startdate').val();
    	var $enddate=$('#enddate').val();
    	var $status=$('#status option:selected').val();
    	var $affilate=$('#affilate option:selected').val();
    	
    	if($startdate != '' && $enddate != ''){
    		if($startdate <= $enddate){
    			
    			$.ajaxSetup({
		        headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        }
		        });
		        $.ajax({
		              url:" {{url('affilate/report/display')}}",
		              method:'POST',
		              data:{startdate:$startdate,enddate:$enddate,status:$status,affilate:$affilate},
		              success:function(data)
		              {
		                $('#displaycontent').html(data);
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