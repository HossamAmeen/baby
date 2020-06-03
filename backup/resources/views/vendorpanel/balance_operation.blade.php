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
               <strong>{{ trans('site.makewithdrow') }} :</strong> 
                </a>
              </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
                    <div class="row">
                    
                            <div class="col-xs-12 col-sm-4 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('site.mybalance')}} :</label></h5>
                                    <input class="form-control" type="text" value="{{ $vendor -> balance }}" name="vendor_balace" id="vendor_balace" readonly/>
                                    
                                 </div>
                            </div>
                            @if($vendor -> balance >= 50)
                            {!! Form::open(['route' => 'vendordrag', 'data-toggle'=>'validator']) !!}
                            <div class="col-xs-12 col-sm-4">
                                 <div class="form-group">
                                    <h5><label for="">{{ trans('site.withdrowamount') }}</label></h5>
                                    <input type="number" name="drag" class="form-control" step="50" value="50" min="50" max="{{ $vendor -> balance }}" required>
                                    <input type="hidden" value="{{ $vendor -> id }}" name="vendor_id" id="vendor_id" />
                                    <input type="hidden" value="{{ $vendor -> balance }}" name="vendor_balance" id="vendor_balance" />
                                </div>
                            </div>  
                            
                            
                            <div class="col-xs-12 col-sm-4 ">
                                 <div class="form-group">
                                 <br><br>
                                    <button type="submit" class="btn btn-block btn-info">{{trans('site.sendwithdrowrequest')}}</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                            @else
                            
                            <div class="col-xs-12 col-sm-8 ">
                                 <div class="form-group">
                                    <h5><label for="">{{trans('site.message1')}}</label></h5> 
                                 </div>
                            </div>
                            @endif
                    </div>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                 <strong>{{trans('site.withdrowoperations')}} :</strong> 
                </a>
              </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
            
            <section class="price" style="margin:10px;">
	        <div>
	            
	            <table class="tablerep newdatatable" style="width: 98%;">
	              <caption>{{trans('site.withdrowoperations')}}</caption>
	              <thead class="theadd">
	                <tr class="trow">
	                  <th class="thead" scope="col">{{ trans('site.operationcode') }}</th>
	                  <th class="thead" scope="col">{{ trans('site.operationdate') }}</th>
	                  <th class="thead" scope="col">{{ trans('site.agreement') }}</th>
	                  <th class="thead" scope="col">{{ trans('site.withdrowamount') }}</th>
	                  <th class="thead" scope="col">{{ trans('site.remain') }}</th>
	                  
	                </tr>
	              </thead>
	              <tbody>
	              @foreach($drags as $drag)
	                <tr class="trow">
	                  
	                      <td class="tdata" data-label="{{ trans('site.operationcode') }}">
	                          <div class="form-group"><label>{{ $drag -> id }}</label></div>
	                      </td>
	                      <td class="tdata" data-label="{{ trans('site.operationdate') }}">
	                          <div class="form-group"><label>{{ $drag -> date }}</label></div>
	                      </td>
	                      <td class="tdata" data-label="{{ trans('site.agreement') }}">
	                          <div class="form-group"><label>{{ ($drag -> status == 1)?'Yes':'No' }}</label></div>
	                      </td>
	                      <td class="tdata" data-label="{{ trans('site.withdrowamount') }}">
	                          <div class="form-group"><label>{{ $drag -> drag_amount }}</label></div>
	                      </td>
	                      <td class="tdata" data-label="{{ trans('site.remain') }}">
	                        <div class="form-group"><label>{{ $drag -> remain }}</label></div>
	                     </td>
	                      
	                    
	                </tr>
	                @endforeach
	              </tbody>
	            </table>
	                
	        </div>
	            
	    </section>
            
            </div>
            
            <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading3">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="false" aria-controls="collapse3">
                 <strong>{{ trans('site.deliveredorder') }} :</strong> 
                </a>
              </h4>
            </div>
            <div id="collapse3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3">
            
            <section class="price" style="margin:10px;">
	        <div>
	            
	            <table class="tablerep newdatatable" style="width: 98%;">
	              <caption>{{ trans('site.deliveredorder') }}</caption>
	              <thead class="theadd">
	                <tr class="trow">
	                  <th class="thead" scope="col">{{ trans('home.number') }}</th>
	                  <th class="thead" scope="col">{{ trans('home.status') }}</th>
	                  <th class="thead" scope="col">{{ trans('home.total') }}</th>
	                  <th class="thead" scope="col">{{ trans('home.date') }}</th>
	                  
	                </tr>
	              </thead>
	              <tbody>
	              @php
	              
	              for($i = 0; $i < count($order_date); $i++){
	              @endphp
	                <tr class="trow">
	                  
	                      <td class="tdata" data-label="{{ trans('home.number') }}">
	                          <div class="form-group"><label>{{ $orders[$i] -> number }}</label></div>
	                      </td>
	                      <td class="tdata" data-label="{{ trans('home.status') }}">
	                          <div class="form-group"><label>{{ App\OrderStatus::find($orders[$i]->status_id) -> name}}</label></div>
	                      </td>
	                      <td class="tdata" data-label="{{ trans('home.total') }}">
	                          <div class="form-group"><label>{{ $orders[$i] -> total - ($orders[$i] -> total * ($vendor -> commission /100)) }}</label></div>
	                      </td>
	                      <td class="tdata" data-label="{{ trans('home.date') }}">
	                          <div class="form-group"><label>{{ $order_date[$i] }}</label></div>
	                      </td>
	                    
	                </tr>
	              @php
	              }
	              @endphp
	              </tbody>
	            </table>
	                
	        </div>
	            
	    </section>
            
            </div>
          </div>
        </div>
        </div>
    </section>

@endsection

@section('script')

<script type="text/javascript">


</script>


@endsection