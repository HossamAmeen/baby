@extends('layouts.app')

@section('title')
<title>{{ $vendor -> name }}</title>
@endsection

@section('content')

<section class="price">
	
        <div class="container">
            
             <h2>{{trans('site.productsummary')}}</h2><br>
             <div>
              <a href="{{ route('vendor_categories',['id' => $vendor -> id ]) }}" class="btn btn-info pull-left" style="margin-left: 10px;">{{trans('site.addnewproduct')}}</a>
              <a href="{{ route('vendororders',['id' => $vendor -> id ]) }}" class="btn btn-info pull-left" style="margin-left: 10px;">{{trans('site.orders')}}</a>
              <a href="{{ route('vendorbalance',['id' => $vendor -> id ]) }}" class="btn btn-info pull-left" style="margin-left: 10px;">{{trans('site.balance')}}</a>
              </div><br><br><br>
            <table class="tablerep newdatatable">
             
              <thead class="theadd">
                <tr class="trow">
                <th class="thead" scope="col">{{trans('home.code')}}</th>
                <th class="thead" scope="col">{{trans('home.title')}}</th>
                <th class="thead" scope="col">{{trans('home.brand')}}</th>
                <th class="thead" scope="col">{{trans('home.price')}}</th>
                <th class="thead" scope="col">{{trans('home.quantity')}}</th>
                 <th class="thead" scope="col">{{trans('home.status')}}</th>
                <th class="thead" scope="col">{{trans('site.update')}}</th>
                </tr>
              </thead>
              <tbody>
              @foreach($products as $product)
                <tr class="trow">
                  
                      <td class="tdata" data-label="{{trans('home.code')}}">
                          <div class="form-group" ><a href="{{ route('showproduct',['id' => $product -> id]) }}">{{ $product -> code }}</a></div>
                      </td>
                      
                      <td class="tdata" data-label="{{trans('home.title')}}">
                          <div class="form-group"><input type="text" class="form-control" value="{{ $product -> title }}" readonly></div>
                      </td>
                      
                      <td class="tdata" data-label="{{trans('home.brand')}}">
                          <div class="form-group"><input type="text" class="form-control" value="{{ $product -> brand -> name }}" readonly></div>
                      </td>
                      
                      <td class="tdata" data-label="{{trans('home.price')}}">
                          <div class="form-group"><input type="number" min="0" name="price" id="price_{{ $product -> id }}" class="form-control price" value="{{ $product -> price }}"></div>
                      </td>
                     
                      
                      <td class="tdata" data-label="{{trans('home.quantity')}}">
                          <div class="form-group"><input type="number" min="0" name="quantity" id="quantity_{{ $product -> id }}" class="form-control quantity" value="{{ $product -> stock}}"></div>
                      </td>
                       <td class="tdata tip" data-label="{{trans('home.status')}}">
                         <div class="tp">
                            <ul class="list-unstyled nomargin">
                            
                                <li class="pull-left">{{trans('site.vendorstatus')}}</li>
                                <li class="pull-right">
                                    @if($product -> vendor_status == 1)<span><i class="fa fa-check-circle-o  green" aria-hidden="true"></i> </span>
                                    @else<span > <i class="fa fa-times-circle-o  red" aria-hidden="true"></i> </span>@endif
                                </li>
                                <div class="clearfix"></div>
                                 <li class="pull-left">{{ trans('site.websitestatus') }}</li>
                                <li class="pull-right">
                                    @if($product -> status == 1)<span><i class="fa fa-check-circle-o  green" aria-hidden="true"></i> </span>
                                    @else<span > <i class="fa fa-times-circle-o  red" aria-hidden="true"></i> </span>@endif
                                </li>
                                <div class="clearfix"></div>
                                 <li class="pull-left">{{trans('site.quantity')}}</li>
                                <li class="pull-right">
                                    @if($product -> stock >= 1)<span><i class="fa fa-check-circle-o  green" aria-hidden="true"></i> </span>
                                    @else<span > <i class="fa fa-times-circle-o  red" aria-hidden="true"></i> </span>@endif
                                </li>
                                <div class="clearfix"></div>
                                
                            </ul>
                                 <div class="triangle triangle-5"></div>
                         </div>
                         @if($product -> status == 1 && $product -> vendor_status == 1 && $product -> stock >= 1)<span ><i class="fa fa-check-circle-o fa-2x green" aria-hidden="true"></i> </span>
                         @else<span > <i class="fa fa-times-circle-o fa-2x red" aria-hidden="true"></i> </span>@endif
                      </td>
                       <td class="tdata" data-label="{{trans('home.update')}}">
                          <div class="form-group">
                          	<button type="button" class="btn btn-success pull-right" style="margin-right: 10px;" onclick="editfunc({{ $product -> id }});">{{ trans('site.update') }}</button>
                          	<button type="button" class="btn btn-danger pull-right" style="margin-right: 10px;" onclick="deletefunc({{ $product -> id }});">{{ trans('home.delete') }}</button>
                          </div>
                      </td>
                       
                      
                </tr>
                @endforeach
              </tbody>
            </table><br><br>
                
            
        </div>
            
    </section>

@endsection

@section('script')

<script type="text/javascript">

 function editfunc(id){
	var price = document.getElementById('price_'+id).value;
	var quantity = document.getElementById('quantity_'+id).value;
	var data = new FormData();
	var token = "{{ csrf_token() }}";
	data.append('price', price);
	data.append('quantity', quantity);
	data.append('pro_id', id);
	data.append('_token',token);
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	     location.reload();
	    }
	  };
	  
	  xhttp.open("POST", "{{ url('vendor/edit/product') }}", true);
	  xhttp.send(data);
      }
      
      function deletefunc(id){
      	var token = "{{ csrf_token() }}";
	var data = new FormData();
	data.append('pro_id', id);
	data.append('_token',token);
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	     location.reload();
	    }
	  };
	  xhttp.open("POST", "{{ url('vendor/delete/product') }}", true);
	  xhttp.send(data);
		
      }

</script>


@endsection