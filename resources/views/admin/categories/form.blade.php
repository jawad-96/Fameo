
<div class="row">            
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">{{ isset($submitButtonText) ? $submitButtonText : 'Create' }} Category</header>
            <div class="panel-body">
                <div class="position-center">                    

                 <div class="form-group {{ $errors->has('store_id') ? 'has-error' : ''}}">
                    {!! Form::label('store_id', 'Store Name', ['class' => 'col-md-3 control-label required-input']) !!}
                    <div class="col-md-9">
                        {!! Form::select('store_id', getStoresDropdown(),null, ['class' => 'select2 form-control','required' => 'required']) !!}
                        {!! $errors->first('parent_id', '<p class="help-block">:message</p>') !!}
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                 
                <div class="form-group {{ $errors->has('parent_id') ? 'has-error' : ''}}">
                    {!! Form::label('parent_id', 'Category Name', ['class' => 'col-md-3 control-label required-input']) !!}
                    <div class="col-md-9">                                                 
                        {!! Form::select('parent_id', $categories, null, ['class' => 'select2 form-control']) !!}                        
                        {!! $errors->first('parent_id', '<p class="help-block">:message</p>') !!}
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                
                <div class="form-group {{ $errors->has('prefix') ? 'has-error' : ''}}">
                    {!! Form::label('prefix', 'Prefix', ['class' => 'col-md-3 control-label required-input']) !!}
                    <div class="col-md-9">
                        {!! Form::text('prefix', null, ['class' => 'form-control','placeholder'=>'Prefix','required' => 'required']) !!}
                        {!! $errors->first('prefix', '<p class="help-block">:message</p>') !!}
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                  <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                    {!! Form::label('name', 'Name', ['class' => 'col-md-3 control-label required-input']) !!}
                    <div class="col-md-9">
                        {!! Form::text('name', null, ['class' => 'form-control','placeholder'=>'Name','required' => 'required']) !!}
                        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        <div class="help-block with-errors"></div>
                    </div>
                  </div>

                  <div class="form-group {{ $errors->has('category_image') ? 'has-error' : ''}}">
                        @if(isset($submitButtonText))
                            {!! Form::label('category_image', 'Category Image', ['class' => 'col-lg-3 col-sm-3 control-label']) !!}                        
                        @else
                            {!! Form::label('category_image', 'Category Image', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}                        
                        @endif
                        <div class="col-md-9">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail" style="max-width: 200px; max-height: 150px;">
                                    <img src="{{ checkImage('categories/'. @$category->image) }}" alt="" />                                    
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                <div>
                                    <span class="btn btn-white btn-file">
                                    <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>
                                    <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                                    <input type="file" class="default" name="category_image" accept="image/*" />
                                    </span>
                                    <a href="#" class="btn btn-info fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a>
                                </div>
                                {!! $errors->first('category_image', '<p class="help-block">:message</p>') !!}
                                <div class="help-block with-errors"></div>
                            </div>                        
                        </div>
                    </div>                                                                                
                    
                    <div class="form-group {{ $errors->has('ordering') ? 'has-error' : ''}}">
                        {!! Form::label('ordering', 'Display', ['class' => 'col-md-3 control-label required-input']) !!}
                        <div class="col-md-9">
                            {!! Form::number('ordering', null, ['class' => 'form-control','placeholder'=>'Display Order','min'=>0]) !!}
                            {!! $errors->first('ordering', '<p class="help-block">:message</p>') !!}
                            <div class="help-block with-errors"></div>
                        </div>
                      </div>

                  <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-info pull-right']) !!}
                        </div>
                    </div>  
                    
                </div>
            </div>
        </section>

    </div>
    
</div>

@section('scripts')
<script type="text/javascript">
    
    var category_select = $('#parent_id');  
    var default_data = [{ id: 0, text: 'Root Category' }];
    
    $(document).ready(function(){                
        
        @if(@$category)
            setTimeout(function(){ 
                $('#store_id').trigger('change');
            }, 1000);
        @endif

        $(document).on("change","#store_id",function(){
            $('#parent_id').val(null).trigger('change');
            var id = $(this).val();
            var el = $("#select2-parent_id-container");
            if(id!=""){
                el.LoadingOverlay("show");
                $.ajax({
                  type: 'get',
                  url: "{{url('admin/get-store-categories')}}"+'/'+id,
                  dataType: "json",
                  complete:function (res,statis) {
                       if(res.status==200){
                        $.each(res.responseJSON.results, function(id,response){                            
                            var newOption = new Option(response.name, response.id, false, false);
                            $('#parent_id').append(newOption);
                        });

                        @if(@$category)
                        setTimeout(function(){
                            $('#parent_id').val({{ @$category->parent_id }}).trigger('change');     
                        }, 500);
                        @endif

                       }else{
                            $('#parent_id').val(null).trigger('change');
                            $('#parent_id').append(new Option('Root Category', 0, false, false));
                       }
                       
                        el.LoadingOverlay("hide"); 
                  },
                    error: function (request, status, error) {
                       el.LoadingOverlay("hide"); 
                    } 
                  });
            }else{
                $('#parent_id').val(null).trigger('change');
                $('#parent_id').append(new Option('Root Category', 0, false, false));
            }
        

        });

        //var store_select = $('#store_id');          
        
        //store_select.select2();        
        //category_select.select2();
         
        // store_select.change(function(){
        //     get_store_categories(this.value);
        // }); 
        
        @if(@$category)
            console.log('sdf');
           // store_select.change();            
        @endif    
        
    });
  
    function get_store_categories(store_id=""){
        console.log(store_id);
        if(store_id == ''){           
               category_select.select2('destroy').empty().select2({ data:default_data  });
        }else{

             category_select.select2({  
                data:default_data,  
                ajax: {
                  url: "{{url('admin/get-store-categories')}}"+'/'+store_id,
                  dataType: 'json',
                  processResults: function (result) {
                    return {
                        results: result.results
                      };                                
              }
                }          
              });          
         }
    }
</script>
@endsection



