
@section('style')
    <style>
        #attribute_form{height: 100px;}
    </style>    
@endsection


<ul class="nav nav-tabs">
    <li><a href="{{ url('admin/products/'. Hashids::encode($product->id) .'/edit?tab=1') }}">Product Information</a></li>
    <li><a href="{{ url('admin/products/'. Hashids::encode($product->id) .'/edit?tab=2') }}">Store & Categories</a></li>
    <li class="active"><a href="{{ url('admin/products/'. Hashids::encode($product->id) .'/edit?tab=3') }}">Variants</a></li>
</ul>

<div class="tab-content">
    
    <!-- Vantiants -->
    <div class="tab-pane fade in active"> 
        <div class="row">            
            <div class="col-lg-12">
                <section class="panel">
                    <div class="panel-body">                                                                             

                        <div class="row"> 
                            
                            <div class="col-sm-6"> 

                                <section class="panel">
                                    <header class="panel-heading">
                                        Product Attributes
                                        <span class="pull-right">
                                            <a href="javascript:void(0)" class="btn btn-success btn-xs" id="reload_attributes" data-toggle="tooltip" title="Reload Attribute"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                            <a href="javascript:void(0)" class="btn btn-info btn-xs" id="add_attribute" data-toggle="tooltip" title="Add New Attribute"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                        </span>
                                    </header>
                                    <div class="panel-body">
                                        <table class="table  table-hover general-table" id="attributes_table">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                            </tr>
                                            </thead>
                                            <tbody> 
                                                
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Name</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </section> 
                            </div>
                            
                            <div class="col-sm-6">   
                                <section class="panel" id="variants_panel">
                                    <header class="panel-heading">
                                        Product Variants
                                        <span class="pull-right">
                                            <a href="javascript:void(0)" class="btn btn-success btn-xs" id="reload_variants" data-toggle="tooltip" title="Reload Variants"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                            <a href="javascript:void(0)" class="btn btn-info btn-xs" id="add_variant" data-toggle="tooltip" title="Add New Variant"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                        </span>
                                    </header>
                                    <div class="panel-body">
                                        <table class="table  table-hover general-table" id="variants_table">
                                            <thead>
                                            <tr>
                                                <th>Default</th>
                                                <th>Name</th>
                                                <th></th>
                                            </tr>
                                            </thead>

                                            <tbody> 
                                                <tr>
                                                    <td colspan="2"> Record not found</td>
                                                </tr>
                                            </tbody>

                                            <tfoot>
                                                <tr>
                                                    <th>Default</th>
                                                    <th>Name</th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </section>
                            </div>                                                                                                       
                        </div>                                                                                                       
                    </div>
                </section>
            </div>
        </div>
    </div>   
  
</div>

<!--Attribute Model-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="attribute_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">Add Attribute</h4>
            </div>
            <div class="modal-body">

                <form role="form" data-toggle="validator" data-disable = 'false' id = "attribute_form">
                    <input type="hidden" name="attribute_id" id="attribute_id" value="0" />
                    <div class="form-group">
                        {!! Form::label('attribute', 'Attribute', ['class' => 'control-label required-input']) !!}  <br/> 
                        {!! Form::select('attribute', getVariantsDropdown(), null, ['class' => 'form-control select2','required' => 'required']) !!}
                        <div class="help-block with-errors"></div> 
                    </div>                    
                    <button type="submit" class="btn btn-info pull-right">Submit</button>                    
                </form>
            </div>
        </div>
    </div>
</div>

<!--Variants Model-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="variant_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">Add Variant</h4>
            </div>
            <div class="modal-body">

                <form role="form" class="form-horizontal" data-toggle="validator" data-disable = 'false' id="variant_form">
                    <div class="row">
                        
                        <div id="variant_fields"></div> 
                        
                        <h5 class="col-lg-12"><b>Variant Cost & Price</b></h5>
                        <div class="form-group">
                            <div class="col-lg-offset-1 col-lg-11">
                                {!! Form::checkbox('is_main_price', 1, true,['id'=>'is_main_price']) !!} <b>Price same as main product</b>                    
                            </div>
                        </div>
                        
                        <row>
                            <div class="col-lg-6">
                                <div class="form-group is_main_price">
                                    {!! Form::label('cost', 'Cost Amount', ['class' => 'control-label col-lg-6 required-input']) !!}
                                    <div class="col-lg-8">                                    
                                        {!! Form::number('cost', null, ['class' => 'form-control','min' => '0']) !!}
                                        <div class="help-block with-errors"></div> 
                                    </div>                            
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group is_main_price">
                                    {!! Form::label('price', 'Sale Amount', ['class' => 'control-label col-lg-6 required-input']) !!}
                                    <div class="col-lg-8">
                                        {!! Form::number('price', null, ['class' => 'form-control','min' => '0']) !!}
                                    <div class="help-block with-errors"></div> 
                                    </div>                            
                                </div>
                            </div>
                        </row>
                        
                        <button type="submit" class="btn btn-info pull-right">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')

<script type="text/javascript">
    var token = $('meta[name="csrf-token"]').attr('content');
    var baseUrl = "{{url('admin/products')}}"; 
    
    $(document).ready(function(){       
        
         get_product_attributes();       
         get_product_variants();
        // --------------------- attribute code -------------- 

        $(document).on('click','#add_attribute',function() { 
            $("#attribute_model .modal-title").text("Add Attribute");
            $("#attribute_id").val(0);
            $('.select2-container--default').css('width','350px');
            $('#attribute_model').modal('show');
        }); 
        
        $(document).on('click','.btn-edit',function() { 
            $("#attribute_model .modal-title").text("Update Attribute");
            $("#attribute_id").val($(this).data("id"));
            $('.select2-container--default').css('width','350px');
            $("#attribute").select2("val",$(this).data("variant_id"));
            $('#attribute_model').modal('show');
        }); 
        
        $('#attribute_form').validator().on('submit', function (e) {

            if(e.isDefaultPrevented()) {
                   
            }else{
               e.preventDefault();
               var attribute_id = $("#attribute").val();
               
               $.ajax({
                 url : baseUrl + '/create-product-attribute',
                 type: 'post',
                 data: {'_token': token,'product_id':{{ $product->id }},'attribute_id':attribute_id},
                 success: function (result) {
                    $('#attribute_model').modal('hide');
                    toastr.success("Attribute created!");
                    get_product_attributes();
                 }
                 
                });
            }
        });                
        
        //reload attributes
        $(document).on('click','#reload_attributes',function() { 
            get_product_attributes()
        });
        
        // remove attribute
        $(document).on("click", ".btn-delete", function ()
        {   
            var attribute_id = $(this).data('id');
            
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this attribute!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
              },
              function(isConfirm){
                if (isConfirm) {
                    if(attribute_id>0){
                       $.ajax({
                        url: baseUrl + '/remove-product-attribute/'+attribute_id,
                        type: 'delete',
                        success: function (result) {
                            get_product_attributes(); 
                            swal.close();
                        }
                        }); 
                    }else{
                        get_product_attributes();
                        swal.close();
                    }

                } else {
                swal.close();
                toastr.info("Your record is safe :)");
              }
            });    
  
        });
                                                           
        
        // --------------------- variants code -------------- 

        $(document).on('click','#add_variant',function() { 
            $("#variant_model .modal-title").text("Add Variant");
            $("#cost").val('');
            $("#price").val('');
            $("#is_main_price").prop('checked',true);
            $(".is_main_price").hide();
            
                $("#variant_form").LoadingOverlay("show");
                $.ajax({
                    url : baseUrl + '/get-product-attributes/{{ $product->id }}',
                    type: 'get',
                    success: function (result) {    
                      $("#variant_fields").html('<h5 class="col-lg-12"><b>Product Attributes</b></h5><input type="hidden" name="total_attributes" id="total_attributes" value="0" />');

                      var product_attributes = result.product_attributes;

                      $.each(product_attributes,function(index, value){
                          index = index+1;
                          $("#total_attributes").val(parseInt($("#total_attributes").val())+1);
                          $("#variant_fields").append('\
                            <div class="form-group">\
                                <label class="control-label col-lg-2 required-input">'+ value.variant.name +'</label>\
                                <input type="hidden" name="attribute-id-'+ index +'" class="form-control" value="'+ value.variant_id +'" required>\
                                <div class="col-lg-8"><input type="text" name="attribute-'+ index +'" class="form-control" required>\
                                <div class="help-block with-errors"></div></div>\
                            </div>\
                            ');
                      });                            

                      $("#variant_form").LoadingOverlay("hide");
                      $('#variant_form').validator('update'); 
                    }

                   });
            
            $('#variant_model').modal('show');
        }); 
        
        $(document).on('click','#is_main_price',function() {             
            if ($(this).is(':checked')) {
                $(".is_main_price").hide();                
                $("#price").prop("required",false);
                $("#cost").prop("required",false);
            }else{
                $(".is_main_price").show();
                $("#price").prop("required",true);
                $("#cost").prop("required",true);
            } 
            
            $('#variant_form').validator('update'); 
        });
        
        $('#variant_form').validator().on('submit', function (e) {

            if(e.isDefaultPrevented()) {
                   
            }else{
               e.preventDefault();
               var data_serialize = $("#variant_form").serialize();
               
               $("#variant_form").LoadingOverlay("show");
               $.ajax({
                 url : baseUrl + '/create-product-variant',
                 type: 'post',
                 data: {'_token': token,'product_id':{{ $product->id }},'attribute_data':data_serialize},
                 success: function (result) {
                    $("#variant_form").LoadingOverlay("hide");
                    $('#variant_model').modal('hide');
                    toastr.success("Variant created!");
                    get_product_variants();
                 }
                 
                });
            }
        });
        
        //set default
        $(document).on('click','.is_default',function() { 
            
            $("#variants_table").LoadingOverlay("show");
            
            var product_id = $(this).val();
            var value = 0;
            
            if ($(this).is(':checked')) {
                value = 1;   
                $('.is_default').prop('checked', false);   
                $(this).prop('checked', true); 
            }
            
            $.ajax({
                url : baseUrl + '/set-product-as-default',
                type: 'post',
                data: {'_token': token,'product_id':product_id,'value':value},
                success: function (result) {
                    $("#variants_table").LoadingOverlay("hide");
                    get_product_variants();
                }

               });
        }); 
        
        //reload variants
        $(document).on('click','#reload_variants',function() { 
            get_product_variants()
        }); 
        
        // remove attribute
        $(document).on("click", ".btn-delete", function ()
        {   
            var attribute_id = $(this).data('id');
            
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this attribute!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
              },
              function(isConfirm){
                if (isConfirm) {
                    if(attribute_id>0){
                       $.ajax({
                        url: baseUrl + '/remove-product-attribute/'+attribute_id,
                        type: 'delete',
                        success: function (result) {
                            get_product_attributes(); 
                            swal.close();
                        }
                        }); 
                    }else{
                        get_product_attributes();
                        swal.close();
                    }

                } else {
                swal.close();
                toastr.info("Your record is safe :)");
              }
            });    
  
        });
                                                          
        });  
        
    function get_product_attributes()
    {
        $("#attributes_table").LoadingOverlay("show");
        $("#attributes_table tbody").html('<tr><td> Record not found</td></tr>');
        $.ajax({
            url : baseUrl + '/get-product-attributes/{{ $product->id }}',
            type: 'get',
            success: function (result) {    
                
              var product_attributes = result.product_attributes;
              
              if(product_attributes.length > 0)
                  $("#attributes_table tbody").html('');
              
              $.each(product_attributes,function(index, value){

                    $("#attributes_table tbody").append('\
                    <tr>\
                        <td width="420px">'+ value.variant.name +'</td>\
                    </tr>\
                    ');
              });                            
              
              $("#attributes_table").LoadingOverlay("hide");
            }

           });
    }
    
    function get_product_variants()
    {
        $("#variants_table").LoadingOverlay("show");
        $("#variants_table tbody").html('<tr><td colspan="3"> Record not found</td></tr>');
        $.ajax({
            url : baseUrl + '/get-product-variants/{{ $product->id }}',
            type: 'get',
            success: function (result) {                  
               
              var product_variants = result.product_variants;
              
              if(product_variants.length > 0)
                  $("#variants_table tbody").html('');
              
              $.each(product_variants,function(index, value){
                  var checked="";
                  if(value.is_default == 1)
                      checked="checked"; 
                  
                  $("#variants_table tbody").append('\
                    <tr>\
                        <td><input type="checkbox" class="is_default" value="'+ value.id +'" '+ checked +' /></td>\
                        <td width="420px">'+ value.name +'</td>\
                        <td><a href="'+ baseUrl +'/edit/'+ value.id +'" class="text-info btn-variant-edit" data-toggle="tooltip" title="Edit Product Variants" data-id="'+ value.encoded_id +'" ><i class="fa fa-pencil"></i></a></td>\
                    </tr>\
                    ');
              });                            
              
              $("#variants_table").LoadingOverlay("hide");
            }

           });
    }
    
</script>
@endsection


<!--<a href="javascript:void(0)" class="text-info btn-edit" data-toggle="tooltip" title="Edit Product Attribute" data-id="'+ value.id +'" data-variant_id="'+ value.variant_id +'"><i class="fa fa-pencil"></i></a>\
                            <a href="javascript:void(0)" class="text-danger btn-delete" data-toggle="tooltip" title="Delete Product Attribute" data-id="'+ value.id +'"><i class="fa fa-trash"></i></a>-->
