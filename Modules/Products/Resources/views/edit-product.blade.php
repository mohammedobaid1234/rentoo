@extends('layouts.app')

@section('content')
    <div id="create-produt" class="pl-3">
        <div class="card mb-3 mt-2">
            <div class="card-header">
                <h5 class="mb-0">إضافة بيانات الصنف</h5>
            </div>
        </div>
        <form id="product-edit"  action="" method="post" class="form-horizontal">
            @csrf
            <div class="card mb-3">
                <h5 class="card-header">البيانات الأساسية</h5>
                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label> اسم الصنف  بالعربي(*) </label>
                                        <input class="form-control" name="name_ar" type="text" placeholder="اسم الصنف" >
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label> اسم الصنف  بالانجليزي(*) </label>
                                        <input class="form-control" name="name_en" type="text" placeholder="اسم الصنف">
                                    </div>
                                </div>
                            </div>
                   
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>الوصف بالعربي</label>
                                        <textarea class="form-control" name="description_ar" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label> الوصف بالانجليزي</label>
                                        <textarea class="form-control" name="description_en" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>السعر</label>
                                <input class="form-control" name="price" type="number" placeholder="السعر">
                            </div>
                            <div class="form-group">
                                <label for="" class="col-1 control-label">{{__('المورد')}}</label>
                                <div class="col-7">
                                    <select name="vendor_id" class="js-example-basic-single form-select form-control "  data-placeholder="اختار المورد" data-options_source="vendors">
                                        {{-- <option value="">{{__('Choose Vendor ...')}}</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{$vendor->id}}">{{$vendor->name}}</option> --}}
                                        {{-- @endforeach --}}
                                    </select>
                                    <p class="invalid-feedback"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <h5 class="card-header">بيانات المواصفات</h5>
                <div class="card-body bg-light">
                    <div class="card-body pt-0 pl-0 pr-0" id="product-attributes">
                        <div class="attributes">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <h5 class="card-header">بيانات التاجات</h5>
                <div class="card-body bg-light">
                    <div class="card-body pt-0 pl-0 pr-0" id="product-tags">
                        <div class="tags">
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="card mb-3">
                <h5 class="card-header"></h5>
                <div class="card-body bg-light">
                    <div class="card-body pt-0 pl-0 pr-0" id="product-attributes">
                        <input id="btn-submit-modal" value="{{__('إضافة')}}" hidden type="submit" class="btn btn-primary" >
                    </div>
                </div>
            </div> --}}
        
            
            <div class="form-group row">
                <div class="col-sm-offset-2 col-7">
                    <input id="btn-submit" value="{{__('إضافة')}}"  type="submit" class="btn btn-primary" >
                </div>
            </div>
        </form>
    </div>
@endsection
@section('modals')
    <div id="categories"></div>
   
@endsection

@section('javascript')
<script>$id = {{$item->id}}</script>
{{-- <script> $vendor_id = {{$item->vendor_id}}</script> --}}
{{-- <script> $attributes = {{$item->attributes}}</script>  --}}

<script>
    $(function(){
        //  GLOBALS.lists.categories($('#product-edit [data-options_source="categories"]'));
         GLOBALS.lists.vendors($('#product-edit [data-options_source="vendors"]'));
        
         $.get($("meta[name='BASE_URL']").attr("content") + "/products/product-info/" + $id, function(response, status){
            if($('#product-edit [name="vendor_id"]').find('option[value="' + response.vendor_id + '"]').length){
                   $('#product-edit [name="vendor_id"]').find('option[value="' + response.vendor_id + '"]').prop('selected', true).trigger('change');
           }
            $('#product-edit [name="name_ar"]').val(response.name.ar);
            $('#product-edit [name="name_en"]').val(response.name.en);
            $('#product-edit [name="price"]').val(response.price);
            $('#product-edit [name="address"]').val(response.address);
            $('#product-edit [name="description_ar"]').val(response.description.ar);
            $('#product-edit [name="description_en"]').val(response.description.en);
            $('#product-attributes .attributes').attr('data-attributes', JSON.stringify(response.attributes));
            $('#product-tags .tags').attr('data-tags', JSON.stringify(response.tags));

        });
        // else{
        //     $('#product-edit [name="category_id"]').append($("<option selected='selected'></option>").val($category_id).text(response.category.name));
        // }
    })
</script>
<script>
    imageRemoveAndAppeared('products', $id);
    myDropzone('products')
    $('.js-example-basic-single').select2();
  </script>
<script>
    $("input#btn-submit").on('click', function(event){
        event.preventDefault();
        var $this =  $(this).closest('form');
        var buttonText = $this.find('button:submit').text();
        var attributes = [];
        $this.find('.attributes [name]').each(function(){
            if($(this).is('input')){
                if($.trim($(this).val()) !== ''){
                    attributes.push({id: $(this).attr('name'), value: $.trim($(this).val())});
                }
            }
            if($(this).find('option:selected').text() !== ''){
                if($(this).is('select')){
                    attributes.push({id: $(this).attr('name'), value: $.trim($(this).find('option:selected').text())});
                }
            }
        });

        data = {
            _token: $("meta[name='csrf-token']").attr("content"),
            name_ar: $.trim($this.find("input[name='name_ar']").val()),
            name_en: $.trim($this.find("input[name='name_en']").val()),
            description_ar: $.trim($this.find("textarea[name='description_ar']").val()),
            description_en: $.trim($this.find("textarea[name='description_en']").val()),
            vendor_id: $.trim($this.find("select[name='vendor_id']").val()),
            price: $.trim($this.find("input[name='price']").val()),
            tags: $.trim($this.find("select[name='tags[]']").val()),
            attributes: attributes,
        }
        $this.find("button:submit").attr('disabled', true);
        $this.find("button:submit").html('<span class="fas fa-spinner" data-fa-transform="shrink-3"></span>');

        $.ajax({
        url: $("meta[name='BASE_URL']").attr("content") + '/products/' + $id,
        type: 'PUT',
        data:data
        })
        .done(function(response) {
            successfullyResponse(response)
        })
        .fail(function (response) {
            http.fail(response.responseJSON, true);
        })
        .always(function () {
            $this.find("button:submit").attr('disabled', false);
            $this.find("button:submit").html(buttonText);
        });
        
});


</script>

<script>
    $("#product-edit [name='vendor_id']").on('change', function () {
        if($.trim(this.value) === ""){
            return;
        }
        getAttributes(this.value);
        getTags(this.value);
    });
     function getAttributes(vendor_id){
        $.get($("meta[name='BASE_URL']").attr("content") + "/products/attributes/categories/" + vendor_id , function(response){

            $('#product-attributes .attributes').html("");

            var attributes = "";

            $(response).each(function(key, record){
                if(key === 0){
                    attributes += '<div class="row">';
                }
                if(key % 4 === 0){
                    attributes += '</div>';
                    attributes += '<div class="row">';
                }

                attributes += '<div class="col">';
                attributes += '     <div class="form-group">';
                attributes += '         <label for="attribute_' + key + '">' + this.type.name['ar'] + '</label>';

                if(this.type.list.length){
                    attributes += '<select class="form-control" id="attribute_' + key + '" name="' + this.type.id + '">';
                        attributes += "<option value=''></option>";
                    $(this.type.list).each(function(){
                        attributes += "<option value='" + this.id + "'>" + this.name + "</option>";
                    });
                    attributes += '</select>';
                }else{
                    attributes += '<input type="text" class="form-control" id="attribute_' + key + '" name="' + this.type.id + '" placeholder="' + (this.type.name['ar'] !== null ? this.type.name['ar']  : "") + '">';
                }
                attributes += '     </div>';
                attributes += '</div>';

                if(key % 4 !== 0 && (key + 1) === response.length){
                    attributes += '</div>';
                }
            });

            $('#product-attributes .attributes').html(attributes);

            var data_attributes =  JSON.parse($('#product-attributes .attributes').attr('data-attributes'));
            $(data_attributes).each(function(key, attribute){
                $('#product-attributes .attributes').find('input[name="' +  attribute.type_id + '"]').val(attribute.value);
                $('#product-attributes .attributes').find('select[name="' +  attribute.type_id + '"] option').filter(function() {
                    return $(this).text() == attribute.value;
                }).prop("selected", true);
            });
        });
    }

    function getTags(category_id){
        $.get($("meta[name='BASE_URL']").attr("content") + "/products/tags/categories/" + category_id , function(response){

            $('#product-tags .tags').html("");

            var tags = "";
            tags += '<div class="row">';
            tags += '<div class="col">';
            tags += '     <div class="form-group">';
            tags += '         <label for="tags">التاجات</label>';
            tags += '<select class="form-control js-example-basic-multiple" multiple="multiple"  id="tags_" name="tags[]">';
                tags += "<option value=''></option>";+
            $(response).each(function(){
                tags += "<option value='" + this.id + "' >" + this.name.ar + "</option>";
            });
            tags += '</select>';
            tags += '     </div>';
            tags += '</div>';
            $('#product-tags .tags').html(tags);
            $('.js-example-basic-multiple').select2();

            var data_tags =  JSON.parse($('#product-tags .tags').attr('data-tags'));
            $tags = [];
            data_tags.forEach(element => {
                $tags.push(element.tag_id)
            });
            $.each($tags, function(i,e){
                if( $("select[name='tags[]'] option[value='" + e + "']")){
                    $option = $("select[name='tags[]'] option[value='" + e + "']");
                    var $newOption = $("<option selected='selected'></option>").val(e).text($option.text())
                    $("select[name='tags[]']").append($newOption).trigger('change');
                    $("select[name='tags[]'] option[value='" + e + "']").trigger("change");
                    $option.remove();

                }
            });

            
        });
    }
</script>
@endsection 





