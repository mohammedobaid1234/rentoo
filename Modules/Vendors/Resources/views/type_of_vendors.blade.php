@extends('layouts.app')

@section('content')
    <div id="datatable"></div>
@endsection

@section('modals')
    <div id="type_of_vendor"></div>
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'type_of_vendors'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "type_of_vendor-create",
                            classes: {
                                button: "btn btn-falcon-default btn-sm",
                                icon: "fas fa-plus"
                            }
                        }
                    ]
                },
                formatters: {
                    name_ar: function (row, column) {
                      return  row.name['ar'] ;
                    },
                    name_en: function (row, column) {
                      return  row.name['en'] ;
                    },
                    operations: function(row, column){
                        var operations = '';

                        // operations += '<button class="btn btn-falcon-default btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="type_of_vendor-show"><span class="fas fa-eye" data-fa-transform="shrink-3"></span></button>';
                        operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="type_of_vendor-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                         
                        return operations;
                    }
                }
            });
          
            $('#type_of_vendor').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'type_of_vendors'
                }
            });

            $('#type_of_vendor').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#type_of_vendor').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });
        });
        
    </script>
@endsection