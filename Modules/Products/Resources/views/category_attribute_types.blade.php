@extends('layouts.app')

@section('content')
    <div id="datatable"></div>
@endsection

@section('modals')
    <div id="category_attribute_type"></div>
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'category_attribute_types'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "category_attribute_type-create",
                            classes: {
                                button: "btn btn-falcon-default btn-sm",
                                icon: "fas fa-plus"
                            }
                        }
                    ]
                },
                formatters: {
                    attributes: function (row, column) {
                        var value = '';

                        $(row.attribute.list).each(function(key, type){
                         return   value += "<label class='badge badge-primary ml-2'>" + type.name + "</label>";
                        });

                        return (value == '' ? "-" : value);
                    },
                    category_name: function (row, column) {
                      return  row.category.name.ar + '['+ row.category.name.en + ']';
                    },
                    attribute_name: function (row, column) {
                        return  row.attribute.name.ar + '['+ row.attribute.name.en + ']';
                    },
                    operations: function(row, column){
                        var operations = '';

                        // operations += '<button class="btn btn-falcon-default btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="category_attribute_type-show"><span class="fas fa-eye" data-fa-transform="shrink-3"></span></button>';
                        operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="category_attribute_type-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                         
                        return operations;
                    }
                }
            });
         
            $('button[data-action="category_attribute_type-create"]').click(function () {
                if($('button[data-action ="add_row"]').length === 0){
                    $('input[name="list_name[]"]').closest('.row').parent().after(`<button type="text" name="add_row" placeholder="" class="btn btn-primary" autocomplete="off" data-action ="add_row"><i class="fa fa-plus" aria-hidden="true"></i></button>`)
                }
               
                $('button[data-action ="add_row"]').click(function (e) {
                    e.preventDefault();
                    $('input[name="list_name[]"]:last-of-type').after(`<input type="text" name="list_name[]" placeholder="" class="form-control mt-2" autocomplete="off" data-operations-update-active="true">`)

                })
            });

           
            $('#category_attribute_type').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'category_attribute_types'
                }
            });

            $('#category_attribute_type').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#category_attribute_type').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });
        });
      
    </script>
    <script>
       setTimeout(() => {
        $('button[data-action="category_attribute_type-update"]').on('click',function () {
                $id = $(this).attr('data-id')
                $('button[name="add_row"]').parent().remove();
                
                $('select[data-options_source="type_of_vendors"]').closest('.row').parent().append(`<div class='row'><button type="text" name="add_row" placeholder="" class="btn btn-primary" autocomplete="off" data-action ="add_row"><i class="fa fa-plus" aria-hidden="true"></i></button></div>`)
               
                $('button[data-action ="add_row"]').click(function (e) {
                    e.preventDefault();
                    $('button[name="add_row"]').closest('.row').append(`<input type="text" name="list_name[]" placeholder="" class="form-control mt-2" autocomplete="off" data-operations-update-active="true">`)
                });
                list($id);

                function list ($id) {
                    $this = $('button[data-action ="add_row"]').closest('.row');
                    console.log($this);
                    $.get($("meta[name='BASE_URL']").attr("content") + '/category_attribute_types/list/' + $id, '',
                    function (data, textStatus, jqXHR) {
                        if(data){
                            data.forEach(element => {
                                $this.append(`
                                <div class="col-sm-10 appends" style='margin-bottom: 10px;'>
                                    <input type="text" class="form-control " name="list_name[]" value="${element}" >
                                    </div>`);
                                }); 
                            } 
                            $('input[name="list_name[]"]:first').addClass('invisible')
                        },
                        
                    );
                }
                // console.log($('input[name="list_name[]"]:first'));
               
            });
       }, 1000);
    </script>
@endsection