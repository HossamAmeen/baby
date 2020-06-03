@extends('layouts.app')

@section('title')
<title>{{ $affilate -> name }}</title>
@endsection

@section('content')

<section id="product-info">
      <div class="container">
      <br>
             <div>
              <a href="{{ route('connect_product',['id' => $affilate -> id ]) }}" class="btn btn-info pull-left" style="margin-right:5px">{{trans('site.cwnp')}}</a>
              <a href="{{ route('connect_category',['id' => $affilate -> id ]) }}" class="btn btn-info pull-left" style="margin-right:5px">{{trans('site.cwnc')}}</a>
              <a href="{{ route('affilatepanelorders',['id' => $affilate -> id ]) }}" class="btn btn-info pull-left" style="margin-right:5px">{{trans('site.orders')}}</a>
              <a href="{{ route('affilatebalance',['id' => $affilate -> id ]) }}" class="btn btn-info pull-left" style="margin-right:5px">{{trans('site.balance')}}</a>
              </div><br><br>
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
                                    <input class="form-control" type="text" value="{{ $affilate -> balance }}" name="affilate_balace" id="affilate_balace" readonly/>
                                    
                                 </div>
                            </div>
                            @if($affilate -> balance >= 50)
                            {!! Form::open(['route' => 'affilatedrag', 'data-toggle'=>'validator']) !!}
                            <div class="col-xs-12 col-sm-4">
                                 <div class="form-group">
                                    <h5><label for="">{{ trans('site.withdrowamount') }}</label></h5>
                                    <input type="number" name="drag" class="form-control" step="50" value="50" min="50" max="{{ $affilate -> balance }}" required>
                                    <input type="hidden" value="{{ $affilate -> id }}" name="affilate_id" id="affilate_id" />
                                    <input type="hidden" value="{{ $affilate -> balance }}" name="affilate_balance" id="affilate_balance" />
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
                                    <br><h3><label for="">{{trans('site.message1')}}</label></h3> 
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
            	   <div class="panel-body">         
	            <table class="tablerep newdatatable" style="width: 100%;">
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
	              @if(count($withdrows))
	              @foreach($withdrows as $withdrow)
	                <tr class="trow">
	                  
	                      <td class="tdata" data-label="{{ trans('site.operationcode') }}">
	                          <div class="form-group"><label>{{ $withdrow -> id }}</label></div>
	                      </td>
	                      <td class="tdata" data-label="{{ trans('site.operationdate') }}">
	                          <div class="form-group"><label>{{ $withdrow -> date }}</label></div>
	                      </td>
	                      <td class="tdata" data-label="{{ trans('site.agreement') }}">
	                          <div class="form-group"><label>{{ ($withdrow -> status == 1)?'Yes':'No' }}</label></div>
	                      </td>
	                      <td class="tdata" data-label="{{ trans('site.withdrowamount') }}">
	                          <div class="form-group"><label>{{ $withdrow -> drag_amount }}</label></div>
	                      </td>
	                      <td class="tdata" data-label="{{ trans('site.remain') }}">
	                        <div class="form-group"><label>{{ $withdrow -> remain }}</label></div>
	                     </td>
	                      
	                    
	                </tr>
	                @endforeach
	                @endif
	              </tbody>
	            </table>
	          </div>                  
            </div>
            
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
            
            <div class="panel-body">
	            <table class="tablerep newdatatable" style="width: 100%;">
	              <caption>{{ trans('site.deliveredorder') }}</caption>
	              <thead class="theadd">
	                <tr class="trow">
	                  <th class="thead" scope="col">{{trans('home.number')}}</th>
	                  <th class="thead" scope="col">{{trans('home.status')}}</th>
	                  <th class="thead" scope="col">{{trans('site.commission')}}</th>
	                  <th class="thead" scope="col">{{trans('home.date')}}</th>
	                  
	                </tr>
	              </thead>
	              <tbody>
	              @php
	              
	              foreach($orders as $order){
	              @endphp
	                <tr class="trow">
	                  @php
	                  $affilate_order = \DB::table('affilate_orders') -> where('affilate_id',$affilate -> id)->where('order_id',$order -> id)->first();
	                  @endphp
	                      <td class="tdata" data-label="{{trans('home.number')}}">
	                          <div class="form-group"><label>{{ $order -> number }}</label></div>
	                      </td>
	                      <td class="tdata" data-label="{{trans('home.status')}}">
	                          <div class="form-group"><label>{{ App\OrderStatus::find($order->status_id) -> name}}</label></div>
	                      </td>
	                      <td class="tdata" data-label="{{trans('site.commission')}}">
	                          <div class="form-group"><label>{{ $affilate_order -> commission }}</label></div>
	                      </td>
	                      <td class="tdata" data-label="{{trans('home.date')}}">
	                          <div class="form-group"><label>{{ $affilate_order -> date }}</label></div>
	                      </td>
	                    
	                </tr>
	              @php
	              }
	              @endphp
	              </tbody>
	            </table>
	                
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