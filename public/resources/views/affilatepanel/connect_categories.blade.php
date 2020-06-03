@extends('layouts.app')

@section('title')
<title>{{ $affilate -> name }}</title>
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
                       <input type="hidden" name="affilate_id" id="affilate_id" value="{{ $affilate -> id }}" />
                        <div class="col-xs-12 col-md-12">
                            <h4><label for="category_id"></label></h4>
                            <hr>
                            <div class="scrollbar" id="style-15">
                                 <div class="force-overflow">
                                   <ul class="branch00 list-unstyled" style="overflow: scroll;height: 300px;">
                                   @foreach($categories as $category)
                                   	<li class="main main0"><h4><input class="input_check" type="checkbox" name="category_ids[]" value="{{ $category->id }}">{{ $category -> title }}</h4></li>
                                   @endforeach      
                                    </ul>
                                  </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button class="btn btn-info pull-right" onclick="submitdata()">{{trans('site.continue')}}</button><br>
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

      function submitdata()
      {
      	var affilate_id = document.getElementById('affilate_id').value;
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
		data.append('affilate_id', affilate_id );
		data.append('ids', ids);
		data.append('_token',token);
		var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
		    
			window.location.replace("{{ route('affilatepanelproducts',['id' => Auth::user()->id]) }}");
			
		    }
		  };
		  
		  xhttp.open("POST", "{{ url('affilate/category/connect') }}", true);
		  xhttp.send(data);
	  }
	  else
	  {
	  	alert('{{ trans('site.pspc') }}');
	  }
      }
      

</script>
 

@endsection