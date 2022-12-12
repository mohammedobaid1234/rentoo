@extends('layouts.app')

@section('content')
    <div id="datatable"></div>
@endsection

@section('modals')
    <div id="Products"></div>
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'Products'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "product-create",
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

                        // operations += '<button class="btn btn-falcon-default btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="Products-show"><span class="fas fa-eye" data-fa-transform="shrink-3"></span></button>';
                        operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="product-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                         
                        return operations;
                    }
                }
            });
            setTimeout(() => {
                $('button[data-action="product-update"]').click(function () {  
                    $id = $(this).attr('data-id');
                    window.location = $("meta[name='BASE_URL']").attr("content") + "/Products/" + $id + '/edit';
                })
            }, 1000);
            $('#Products').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'Products'
                }
            });

            $('#Products').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#Products').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });
            $('button[data-action="product-create"]').click(function () {  
                window.location = $("meta[name='BASE_URL']").attr("content") + "/Products/create";
            })
           
        });
        
    </script>
@endsection