@extends('layouts.app')

@section('content')
    <div id="datatable"></div>
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'registrations'
                },
                formatters: {
                    status: function (row, column) {
                     return  row.status == 1 ?  "<label class='badge badge-primary ml-2'>بانتظار الرد</label>" : row.status == 2 ?"<label class='badge badge-primary ml-2'>مقبول</label>" :"<label class='badge badge-danger ml-2'>مرفوض</label>"  ; 
                    },
                    operations: function(row, column){
                        // var operations = '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '"data-toggle="modal" data-target="#exampleModal><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';

                        operations =   `
                            <button type="button" class="btn btn-falcon-primary btn-sm mr-2" data-id= ${row.id } data-toggle="modal" data-target="#exampleModal">
                                تغير الحالة
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">تغير الحالة</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">الحالة</label>
                                            <select id="multiple2" class="form-control"
                                                    name="status_id" style="width:400px !important">
                                                <option value="2">
                                                   مقبول
                                                </option>
                                                <option value="3" >
                                                    مرفوض
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                    <button type="button" class="btn btn-primary" data-action="registrations-changeStatus">حفظ</button>
                                </div>
                                </div>
                            </div>
                            </div>
                        `;
                        
                         
                        return operations;
                    }
                }
            });
        }); 

       setTimeout(() => {
        $('button[data-action="registrations-changeStatus"]').click(function (e) { 
            e.preventDefault();
            $id= $(this).parent().parent().parent().parent().siblings('button').attr('data-id');
            $status = $(this).parent().siblings('.modal-body').find('select').val();
          
        data = {
        _token: $("meta[name='csrf-token']").attr("content"),
        status: $status
        };
        $.post($("meta[name='BASE_URL']").attr("content") + "/registrations/changeStatus/" + $id, data,
        function (response, status) {
            http.success({ 'message': response.message });
        })
        .fail(function (response) {
            http.fail(response.responseJSON, true);
        })
            
        });
       }, 1000);
    </script>
@endsection