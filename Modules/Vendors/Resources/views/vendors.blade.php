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
@endsection