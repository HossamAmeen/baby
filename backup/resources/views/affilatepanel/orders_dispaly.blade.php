            <table class="tablerep newdatatable">
              <caption>{{ trans('site.orderreport') }}</caption>
              <thead class="theadd">
                <tr class="trow">
                  <th class="thead" scope="col">{{trans('home.number')}}</th>
                  <th class="thead" scope="col">{{trans('home.products')}}</th>
                  <th class="thead" scope="col">{{trans('home.status')}}</th>
                  <th class="thead" scope="col">{{trans('home.quantity')}}</th>
                  <th class="thead" scope="col">{{trans('home.price')}}</th>
                  <th class="thead" scope="col">{{trans('site.commission')}}</th>
                </tr>
              </thead>
              <tbody>
              @php
	          $totalshipping=0;
	          $total=0;
	          
	          foreach ($orders as $index => $order){
	          	
	          	//$totalshipping+=$order->totalshipping;
	          	//$total+=$order->total;
	        @endphp
                <tr class="trow">
                  
                      <td class="tdata" data-label="{{trans('home.number')}}">
                          <div class="form-group"><label>{{ $order->number}}</label></div>
                      </td>
                     
                      <td class="tdata" data-label="{{trans('home.products')}}">
                          <div class="form-group"><label>
	                 @if($order -> Product) {{ $order -> Product -> title }} @else Product Deleted @endif<br/>
                          </label></div>
                      </td>
                      <td class="tdata" data-label="{{trans('home.status')}}">
                          <div class="form-group"><label>{{ $order->Status -> name}}</label></div>
                      </td>
                      
                      <td class="tdata" data-label="{{trans('home.quantity')}}">
                        <div class="form-group"><label>
	                 {{ $order->quantity }}<br/>
	                 </label></div>
                     </td>
                     
                     <td class="tdata" data-label="{{trans('home.price')}}">
                        <div class="form-group"><label>
	                 {{ $order->one_price}}<br/>
	                 </label></div>
                     </td>
                     
                      <td class="tdata" data-label="{{trans('home.commission')}}">
                       <div class="form-group"><label>{{ $order-> affilate_order->commission }}</label></div>
                      </td>
                    
                </tr>
                
                @php    
          }
         @endphp
              </tbody>
            </table>
        