@extends('layouts.app')

@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Card title</h5>
    <select class="form-select" aria-label="Default select example">
      <option selected>Open this select menu</option>
      @foreach ($vendors as $item)
        <option value="{{$item->id}}">{{$item->company_name}}</option>
      @endforeach
    </select>
    <div id="map" style="width: 100%; height: 400px; margin-top : 10px"></div>
  </div>
</div>


@endsection

@section('modals')
    <div id="type_of_vendor"></div>
@endsection

@section('javascript')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<script>let vendor_icon ='/public/themes/Falcon/v2.8.0/googleIcon/icons8-shop-32.png'</script>
<script>let $vendorLocations = @json($vendorLocations)</script>
<script>
  // console.log($vendorLocations);
   let markers = [];
    initMap();
    // console.log(vendor_icon);
    function initMap() {
      const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
        center: { lat: 31.469868, lng: 34.388081 },
      });
      $vendorLocations.forEach(element => {
        // console.log(element);
        var marker = new google.maps.Marker({
            position:JSON.parse(element),
            map: map,
            icon: vendor_icon,
        });
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
        // var marker = new google.maps.Marker({
        //   position:mapsMouseEvent.latLng,
        //   map: map,
        // });
        marker.setPosition(mapsMouseEvent.latLng);
        // $location =  JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2)
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

   

</script>

@endsection