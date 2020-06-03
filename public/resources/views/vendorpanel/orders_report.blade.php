<section class="price">
        <div class="container">
            <table class="tablerep newdatatable">
              <caption>{{ trans('site.orderreport') }}</caption>
              <thead class="theadd">
                <tr class="trow">
                  <th class="thead" scope="col">{{trans('home.number')}}</th>
                  <th class="thead" scope="col">{{trans('home.user')}}</th>
                  <th class="thead" scope="col">{{trans('home.products')}}</th>
                  <th class="thead" scope="col">{{trans('home.status')}}</th>
                  <th class="thead" scope="col">{{trans('home.quantity')}}</th>
                  <th class="thead" scope="col">{{trans('home.total')}}</th>
                </tr>
              </thead>
              <tbody>
              @php
	          $totalshipping=0;
	          $total=0;
	          
	          foreach ($orders as $order){
	          	
	          	//$totalshipping+=$order->totalshipping;
	          	$total+=$order->total;
	        @endphp
                <tr class="trow">
                  
                      <td class="tdata" data-label="{{trans('home.number')}}">
                          <div class="form-group"><label>{{ $order->number}}</label></div>
                      </td>
                      <td class="tdata" data-label="{{trans('home.user')}}">
                          <div class="form-group"><label>{{ App\User::find($order -> user_id) -> name}}</label></div>
                      </td>
                      <td class="tdata" data-label="{{trans('home.products')}}">
                          <div class="form-group"><label>
                          @php
	                 $products = explode(",",$order->products);
	                 foreach ($products as $product){
	                 $pro = App\Product::find((int)$product);
	                 
	                 @endphp
	                 
	                 @if($pro) {{ $pro -> title }} @else Product Deleted @endif<br/>
	                 @php
	                 }
	                @endphp
                          </label></div>
                      </td>
                      <td class="tdata" data-label="{{trans('home.status')}}">
                          <div class="form-group"><label>{{ App\OrderStatus::find($order->status_id) -> name}}</label></div>
                      </td>
                      <td class="tdata" data-label="{{trans('home.quantity')}}">
                        <div class="form-group"><label>
                        @php
	                 $quantities = explode(",",$order->quantities);
	                 foreach ($quantities as $quantity){
	                 @endphp
	                 
	                 {{ $quantity }}<br/>
	                 @php
	                 }
	                @endphp</label></div>
                     </td>
                      <td class="tdata" data-label="{{trans('home.total')}}">
                       <div class="form-group"><label>{{ $order->total - ($order->total * (App\Vendor::find($vendor_id) -> commission /100))}}</label></div>
                      </td>
                    
                </tr>
                
                @php    
          }
         @endphp
              </tbody>
            </table>
        </div>
            
    </section>