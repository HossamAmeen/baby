@extends('layouts.app')
@section('content')
<section id="account">
           <div class="container">
               <div class="row">
                   <div class="col-xs-12">

{!! Form::open(['method'=>'POST','url'=>"editaddress/". $address->id ]) !!}
                <fieldset>
                <legend>{{ trans('site.your_address')  }}</legend>
                <div class="col-sm-12">
                
                <div class="form-group">
		    <label>{{trans('home.country')}}</label>
		    <select class="form-control" onchange="regions({{ $address->id }})" name="country" id="country" required>
		      @foreach($countries as $country)
		      	<option value="{{ $country -> id }}" {{ ($country -> id == $address->country_id)?'selected':'' }}>{{ $country -> name }}</option>
		      @endforeach
		    </select>
		</div>
		
		<div class="form-group">
		    <label>{{trans('home.region')}}</label>
		    <select class="form-control" onchange="areas({{ $address->id }})" name="region" id="region" required>
			@foreach($regions as $region)
		      	<option value="{{ $region -> id }}" {{ ($region -> id == $address->region_id)?'selected':'' }}>{{ $region -> name }}</option>
		      @endforeach
		    </select>
		</div>
		
		<div class="form-group">
		    <label>{{trans('home.area')}}</label>
		    <select class="form-control" name="area" id="area" required>
			@foreach($areas as $area)
		      	<option value="{{ $area -> id }}" {{ ($area -> id == $address->area_id)?'selected':'' }}>{{ $area -> name }}</option>
		      @endforeach
		    </select>
		</div>
                
                   <div class="form-group">
                        <label>{{ trans('site.name') }}</label>
                        <input type="text" name="add_name" value="{{ $address->name }}" placeholder="{{ trans('site.name') }}" required  class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>{{ trans('site.phone') }}</label>
                        <input type="text" name="add_phone" value="{{ $address->phone }}" pattern="[0-9]{9,15}" required  class="form-control"> 
                    </div>
                    
                    <div class="form-group">
                        <label>{{ trans('site.another_phone') }}</label>
                        <input type="text" name="another_phone" value="{{ $address->phone2 }}" pattern="[0-9]{9,15}" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        
                        <div id="map-canvas" style="height: 500px;"></div>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('site.address') }}</label>
                        
                        <input type="text" name="add_address" value="{{ $address->address }}" placeholder="{{ trans('site.address') }}" required  class="form-control address">
                        
                    </div>
                    <div class="form-group">
                        <label>{{ trans('site.latitude') }}</label>
                        
                        <input type="text" name="add_lat" value="{{ $address->lat }}" readonly placeholder="{{ trans('site.latitude') }}" required  class="form-control lat">
                        
                    </div>
                    <div class="form-group">
                        <label>{{ trans('site.longitude') }}</label>
                        
                        <input type="text" name="add_long" value="{{ $address->long }}" readonly placeholder="{{ trans('site.longitude') }}" required  class="form-control long">
                        
                    </div>
                    
                </div>
                </fieldset>
                <div class="buttons clearfix">
                    <div style="text-align: center;">
                        <input type="submit" value="{{ trans('site.update') }}" class="btn btn-primary">
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

    
    
    function regions(add){
	var selection = document.getElementById('country');
	var id = selection.options[selection.selectedIndex].value;
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	     document.getElementById("region").innerHTML = this.responseText;
	    }
	  };
	  xhttp.open("GET", "{{ url('country/regions') }}"+"/"+id+"/"+add, true);
	  xhttp.send();
		
      }
      
      function areas(add){
	var selection = document.getElementById('region');
	var id = selection.options[selection.selectedIndex].value;
	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	     document.getElementById("area").innerHTML = this.responseText;
	    }
	  };
	  xhttp.open("GET", "{{ url('region/areas') }}"+"/"+id+"/"+add, true);
	  xhttp.send();
		
      }

</script>

<script>


    function initMap() {
            var uluru = {lat: {{ $address->lat }}, lng: {{ $address->long }} };
            var myOptions = {
                zoom: 15,
                center: new google.maps.LatLng({{ $address->lat }}, {{ $address->long }})
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
            