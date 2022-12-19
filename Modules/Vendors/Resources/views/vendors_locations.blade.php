@extends('layouts.app')

@section('content')
<div id="map" style="width: 500px; height: 500px;"></div>


@endsection

@section('modals')
    <div id="type_of_vendor"></div>
@endsection

@section('javascript')
<script>
    initMap();
    
    function initMap() {
      const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        center: { lat: 31.469868, lng: 34.388081 },
      });
      // var marker = new google.maps.Marker({
      //     position: { lat: 31.469868, lng: 34.388081 },
      //     map: map,
      // });
      // let infoWindow = new google.maps.InfoWindow({
      //   content: "Click the map to get Lat/Lng!",
      //   position: { lat: 31.469868, lng: 34.388081 },
      // });
      // infoWindow.open(map);
  // Configure the click listener.
      map.addListener("click", (mapsMouseEvent) => {
        // Close the current InfoWindow.
        // infoWindow.close();
        // Create a new InfoWindow.
        // infoWindow = new google.maps.InfoWindow({
        //   position: mapsMouseEvent.latLng,
        // });
        var marker = new google.maps.Marker({
          position:JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2),
          map: map,
        });
        console.log(JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2));
        // marker.setPosition(mapsMouseEvent.latlng);
        // infoWindow.setContent(
        //   JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2)
        // );
        // infoWindow.open(map);
      });
      // map.addListener("click", (e) => {
      //   placeMarkerAndPanTo(e.latLng, map);
      // });
    }
    function changeMarker(){
      var marker = new google.maps.Marker({
            position: { lat: 31.469868, lng: 34.388081 },
            map: map,
      });
    }

    // function placeMarkerAndPanTo(latLng, map) {
      // new google.maps.Marker({
      //   position: latLng,
      //   map: map,
      // });
    //   map.panTo(latLng);
    // }

    window.initMap = initMap;

    function getLatLng(address) {
    axios.get('https://maps.googleapis.com/maps/api/geocode/json', {
        params: {
        address: address,
        key: 'AIzaSyAojCdQjz5W8nFiXQvxg9gmoFGCs_PK35Q'
        }
    })
    .then(function(response) {
        var lat = response.data.results[0].geometry.location.lat;
        var lng = response.data.results[0].geometry.location.lng;
        console.log(lat, lng);
    })
    .catch(function(error) {
        console.log(error);
    });
    }

  </script>
@endsection