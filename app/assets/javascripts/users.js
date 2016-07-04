var ready;
ready = function() {

  if ($('.registrations').length) {

    var geocoder;
    var map;
    var marker;

    var s = document.createElement("script");
    s.type = "text/javascript";
    s.src  = "https://maps.googleapis.com/maps/api/js?key=AIzaSyBzgRvMidyz6Ss0JfNX1F-caivPmDYT6i8&callback=gmap_draw";
    window.gmap_draw = function(){
      

      
      geocoder = new google.maps.Geocoder();
      
      var zip = document.getElementById('user_zipcode').value;
      var street = document.getElementById('user_street').value;
      var city = document.getElementById('user_city').value;
      var state = document.getElementById('user_state').value;

      console.log(zip);
      console.log(street);
      console.log(city);
      console.log(state);
      console.log([zip,street,city,state].join(","));

      geocoder.geocode( { 'address': [zip,street,city,state].join(",")}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          map = new google.maps.Map(document.getElementById('mapCanvas'), {
        zoom: 16,
                streetViewControl: false,
              mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                  mapTypeIds:[google.maps.MapTypeId.HYBRID, google.maps.MapTypeId.ROADMAP] 
        },
        center: results[0].geometry.location,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      });
          map.setCenter(results[0].geometry.location);
          marker = new google.maps.Marker({
              map: map,
              position: results[0].geometry.location,
              draggable: true,
              title: 'My Title'
          });
          updateMarkerPosition(results[0].geometry.location);
          geocodePosition(results[0].geometry.location);
            
          // Add dragging event listeners.
      google.maps.event.addListener(marker, 'dragstart', function() {
        updateMarkerAddress('Dragging...');
      });
          
      google.maps.event.addListener(marker, 'drag', function() {
        updateMarkerStatus('Dragging...');
        updateMarkerPosition(marker.getPosition());
      });
      
      google.maps.event.addListener(marker, 'dragend', function() {
        updateMarkerStatus('Drag ended');
        geocodePosition(marker.getPosition());
          map.panTo(marker.getPosition()); 
      });
      
      google.maps.event.addListener(map, 'click', function(e) {
        updateMarkerPosition(e.latLng);
        geocodePosition(marker.getPosition());
        marker.setPosition(e.latLng);
      map.panTo(marker.getPosition()); 
      }); 
      
        } else {
          alert('Geocode was not successful for the following reason: ' + status);
        }
      });


    };
    $("head").append(s);

    // $('#user_birthday').mask('00/00/0000');

    var maskBehavior = function (val) {
     return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
    options = {onKeyPress: function(val, e, field, options) {
     field.mask(maskBehavior.apply({}, arguments), options);
     }
    };
     
    $('#user_phone').mask(maskBehavior, options);

  



    

    function geocodePosition(pos) {
      geocoder.geocode({
        latLng: pos
      }, function(responses) {
        if (responses && responses.length > 0) {
          updateMarkerAddress(responses[0].formatted_address);
        } else {
          updateMarkerAddress('Cannot determine address at this location.');
        }
      });
    }

    function updateMarkerStatus(str) {
      document.getElementById('markerStatus').innerHTML = str;
    }

    function updateMarkerPosition(latLng) {
      document.getElementById('info').innerHTML = [
        latLng.lat(),
        latLng.lng()
      ].join(', ');
    }

    function updateMarkerAddress(str) {
      document.getElementById('address').innerHTML = str;
    }
  }
  
}



$(document).ready(ready);
$(document).on('page:load', ready);