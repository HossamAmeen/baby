@extends('layouts.app')
@section('content')
 <section id="account">
           <div class="container">
               <div class="row">
                   <div class="col-xs-12">
           {!! Form::open(['method'=>'POST','url'=>'createaddress']) !!}
          
                <div class="title form-title ">
                    <h2 class="purpel background"><span> {{ trans('site.your_address')  }}  </span></h2>
                </div>
           
                <div class="col-sm-12">
                    <div class="col-sm-6">
                       <div class="form-group">
                        <label  class="label-after">{{ trans('site.name') }} <i class="fa fa-star" aria-hidden="true"></i></label>
                        <input type="text" name="add_name"  placeholder="{{ trans('site.name') }}" required  class="form-control">
                    </div>
                <div class="form-group">
		    <label class="label-after">{{trans('home.country')}} <i class="fa fa-star" aria-hidden="true"></i></label>
		    <select class="form-control" onchange="regions()" name="country" id="country" required>
		    <option></option>
		      @foreach($countries as $country)
		      	<option value="{{ $country -> id }}">{{ $country -> name }}</option>
		      @endforeach
		    </select>
		</div>
		
		<div class="form-group">
		    <label class="label-after">{{trans('home.region')}} <i class="fa fa-star" aria-hidden="true"></i></label>
		    <select class="form-control" onchange="areas()" name="region" id="region" required>
		      <option></option>
		    </select>
		</div>
		
		<div class="form-group">
		    <label class="label-after">{{trans('home.area')}} <i class="fa fa-star" aria-hidden="true"></i></label>
		    <select class="form-control" name="area" id="area" required>
			<option></option>
		    </select>
		</div>
                       <div class="form-group">
                        <label class="label-after">{{ trans('site.address') }} <i class="fa fa-star" aria-hidden="true"></i></label>
                        
                        <input type="text" name="add_address"   placeholder="{{ trans('site.address') }}" required  class="form-control address">
                        
                    </div>
                 
                    
                    <div class="form-group">
                        <label class="label-after">{{ trans('site.phone') }} <i class="fa fa-star" aria-hidden="true"></i></label>
                        <input type="text" name="add_phone" placeholder="{{ trans('site.phone') }}" required pattern="[0-9]{9,15}" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>{{ trans('site.another_phone') }}</label>
                        <input type="text" name="another_phone" placeholder="{{ trans('site.another_phone') }}" pattern="[0-9]{9,15}" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label class="label-after">{{ trans('site.latitude') }} </label>
                        
                        <input type="text" name="add_lat" readonly placeholder="{{ trans('site.latitude') }}"  class="form-control lat">
                       
                    </div>
                    <div class="form-group">
                        <label class="label-after">{{ trans('site.longitude') }} </label>
                        
                        <input type="text" name="add_long" readonly placeholder="{{ trans('site.longitude') }}"  class="form-control long">
                        
                    </div>
                    
                    </div>
                         <div class="col-sm-6">
                  
                    <div class="form-group">
                        
                        <div id="map-canvas" style="  height: 647px;"></div>
                    </div>
                
                    </div>
                </div>
                
                
              
                <div class="buttons clearfix">
                    <div style="text-align: center;">
                        <input type="submit" value="{{ trans('site.submit') }}" class="btn btn-success send-btn">
                    </div>
                </div>
                
            </form>
      
</div>      
</div>
</div>
</section>  

@endsection
@section('script')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB2DM4_HwOA3s6WsWcyhRt5Q_NO9CoxZpU&callback=initMap" async defer></script>

<script type="text/javascript">

    
    //function regions();
    //function areas();
    function regions(){
	var selection = document.getElementById('country');
	var id = selection.options[selection.selectedIndex].value;
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	     document.getElementById("region").innerHTML = this.responseText;
	    }
	  };
	  xhttp.open("GET", "{{ url('country/regions') }}"+"/"+id, true);
	  xhttp.send();
		
      }
      
      function areas(){
	var selection = document.getElementById('region');
	var id = selection.options[selection.selectedIndex].value;
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	     document.getElementById("area").innerHTML = this.responseText;
	    }
	  };
	  xhttp.open("GET", "{{ url('region/areas') }}"+"/"+id, true);
	  xhttp.send();
		
      }

</script>

<script>
    function initMap() {
            var uluru = {lat: 30.0096523304429, lng: 31.22744746506214};
            var myOptions = {
                zoom: 10,
                center: new google.maps.LatLng(30.0096523304429, 31.22744746506214)
            },
            map = new google.maps.Map(document.getElementById('map-canvas'), myOptions),
            marker = new google.maps.Marker({
                position: uluru,
                map: map,
            }),
            infowindow = new google.maps.InfoWindow;
            map.addListener('click', function(e) {
                map.setCenter(e.latLng);
                marker.setPosition(e.latLng);
                infowindow.setContent("Latitude: " + e.latLng.lat() +
                "<br>" + "Longitude: " + e.latLng.lng());
                infowindow.open(map, marker);
                var s = $('.lat').val(e.latLng.lat());
                var ss = $('.long').val(e.latLng.lng());
            });
            var geocoder = new google.maps.Geocoder();

            google.maps.event.addListener(map, 'click', function(event) {
            geocoder.geocode({
                'latLng': event.latLng
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    var s = $('.address').val(results[0].formatted_address);
                }
                }
            });
            });
        }
        
        
</script>
@endsection
            