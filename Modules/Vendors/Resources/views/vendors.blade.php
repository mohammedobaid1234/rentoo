@extends('layouts.app')

@section('content')
    <div id="datatable"></div>
@endsection

@section('modals')
    <div id="vendor"></div>
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'vendors'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "vendor-create",
                            classes: {
                                button: "btn btn-falcon-default btn-sm",
                                icon: "fas fa-plus"
                            }
                        }
                    ]
                },
                formatters: {
                    locationAddress: function (row, column) {
                    //   $data=  getLocationFromLatAndLong(JSON.parse(row.location).lat?? 34.620745, JSON.parse(row.location).long ?? 34.620745) ;
                        return row.user.address
                    },
                    times: function (row, column) {
                      return  row.starting_time + '-'+ row.closing_time ;
                    },
                    status: function (row, column) {
                     return  row.status == 1 ?  "<label class='badge badge-primary ml-2'>فعال</label>" : "<label class='badge badge-danger ml-2'>مجمد</label>"  ; 
                    },
                    operations: function(row, column){
                        var operations = '';

                        // operations += '<button class="btn btn-falcon-default btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="vendor-show"><span class="fas fa-eye" data-fa-transform="shrink-3"></span></button>';
                        operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="vendor-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                         
                        return operations;
                    }
                }
            });
            setTimeout(() => {
                $('button[data-action="vendor-update"]').click(function () {  
                    $id = $(this).attr('data-id');
                    $.get($("meta[name='BASE_URL']").attr("content") + '/vendors/getVendorLocation/' + $id, '',
                        function (data, textStatus, jqXHR) {
                            $btn = $('#vendor .modal-body').append(`
                                <div class="row">
                                    <div class="col-12">
                                        <div id="map" style="width: 100%; height: 400px; margin-top : 10px"></div>
                                        <input name="location" value="${data.location}" hidden />
                                    </div>
                                </div>
                            `);
                            if(data){
                                console.log(data.location);
                                data.location === null ?  initMap() :  initMap(JSON.parse(data.location).lat, JSON.parse(data.location).lng)
                            } 
                        },
                        
                    );
                    // console.log($('#vendor .modal-body'));
                    // initMap()
                })
                $('button[data-action="vendor-create"]').click(function () {  
                    $('#vendor .modal-body').append(`
                        <div class="row">
                            <div class="col-12">
                                <div id="map" style="width: 100%; height: 400px; margin-top : 10px"></div>
                                <input name="location" value="" hidden />
                            </div>
                        </div>
                    `);
                    initMap();
                })
            }, 1000);
            $('#vendor').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'vendors'
                }
            });

            $('#vendor').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#vendor').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });
        });
        function getLocationFromLatAndLong($lat  ,  $long , $lang = 'en'){
            $location= $lat+ '%2C' + $long;
            $finalLoaction = '';
            $.get("https://api.opencagedata.com/geocode/v1/json", {
                    q: $location,
                    key: "6b85e2270825413a95e7ee5916383fb3",
                    language:$lang,
                    pretty: '1',
                },
                function (data, textStatus, jqXHR) {
                    $result = data??'غير متوفر'
                    $finalLoaction = $result.results ?  $result.results[0].formatted :'No Exsit Format Address' ;

                    return $finalLoaction;
                    console.log($finalLoaction);
                },
            );
        }
    </script>
    <script>
        setTimeout(() => {
            $('input[name="starting_time"]').flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });
            $('input[name="closing_time"]').flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });
        }, 1000);
    </script>
    <script>
        // initMap();
        
        function initMap(lat =31.469868, lng =  34.388081) {
             const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: { lat:lat , lng: lng },
             });
            var marker = new google.maps.Marker({
              position:{ lat:lat , lng: lng },
              map: map,
            });
            map.addListener("click", (mapsMouseEvent) => {
            marker.setPosition(mapsMouseEvent.latLng);
            $('input[name="location"]').val(JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2) );
            console.log($('input[name="location"]').val());
            // $location =  JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2)
            });
  
        }

        window.initMap = initMap;
    
       
    
    </script>
@endsection