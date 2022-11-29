@extends('layouts.app')

@section('content')
    <div id="datatable"></div>
@endsection

@section('modals')
    <div id="times_label"></div>
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'times_label'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "times_label-create",
                            classes: {
                                button: "btn btn-falcon-default btn-sm",
                                icon: "fas fa-plus"
                            }
                        }
                    ]
                },
                
                formatters: {
                    type_of_vendor: function (row, column) {
                        var value = '';

                        $(row.times).each(function(key, type){
                            console.log(type);
                         return   value += "<label class='badge badge-primary ml-2'>" + type.type_of_vendor.name.ar + "</label>";
                        });

                        return (value == '' ? "-" : value);
                    },
                    name_ar: function (row, column) {
                      return  row.label['ar'] ;
                    },
                    name_en: function (row, column) {
                      return  row.label['en'] ;
                    },
                    operations: function(row, column){
                        var operations = '';

                        // operations += '<button class="btn btn-falcon-default btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="times_label-show"><span class="fas fa-eye" data-fa-transform="shrink-3"></span></button>';
                        operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="times_label-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                         
                        return operations;
                    }
                }
            });
          
            $('#times_label').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'times_label'
                }
            });

            $('#times_label').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#times_label').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });
        });
        
    </script>
@endsection