
<div class="row">            
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">Add Adjustment</header>
            <div class="panel-body">
                <div class="position-center">                    
                  
                 <div class="form-group {{ $errors->has('store_id') ? 'has-error' : ''}}">
                    {!! Form::label('store_id', 'Store Name', ['class' => 'col-md-3 control-label required-input']) !!}
                    <div class="col-md-9">
                        {!! Form::select('store_id', getStoresDropdown(),null, ['class' => 'form-control select2','required' => 'required']) !!}
                        {!! $errors->first('parent_id', '<p class="help-block">:message</p>') !!}
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                 
                <div class="form-group {{ $errors->has('product_id') ? 'has-error' : ''}}">
                    {!! Form::label('product_id', 'Product', ['class' => 'col-md-3 control-label required-input']) !!}
                    <div class="col-md-9">                                                 
                        {!! Form::select('product_id', [], null, ['class' => 'form-control select2','required' => 'required']) !!}                        
                        {!! $errors->first('product_id', '<p class="help-block">:message</p>') !!}
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                
                <div class="form-group {{ $errors->has('stock_type') ? 'has-error' : ''}}">
                    {!! Form::label('stock_type', 'Type', ['class' => 'col-md-3 control-label required-input']) !!}
                    <div class="col-md-9">                                                 
                        {!! Form::select('stock_type', ["1" => "IN", "2" => "OUT"], null, ['class' => 'form-control select2','required' => 'required']) !!}                        
                        {!! $errors->first('stock_type', '<p class="help-block">:message</p>') !!}
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                    
                  <div class="form-group {{ $errors->has('quantity') ? 'has-error' : ''}}">
                    {!! Form::label('quantity', 'Quantity', ['class' => 'col-md-3 control-label required-input']) !!}
                    <div class="col-md-9">
                        {!! Form::number('quantity', null, ['class' => 'form-control','placeholder'=>'Quantity','min' => '0','required' => 'required']) !!}
                        {!! $errors->first('quantity', '<p class="help-block">:message</p>') !!}
                        <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  
                  <div class="form-group {{ $errors->has('note') ? 'has-error' : ''}}">
                    {!! Form::label('note', 'Note', ['class' => 'col-md-3 control-label']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('note', null, ['class' => 'form-control','placeholder'=>'Note','rows' => '3']) !!}
                        {!! $errors->first('note', '<p class="help-block">:message</p>') !!}
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

    var product_select = $('#product_id');  
    var store_id='';
    
    $(document).ready(function(){                
        
        var store_select = $('#store_id');  
        
        
        // store_select.select2();
        
        // product_select.select2({
        //     placeholder: "Please select store first",                                
        //   });
         
        store_select.change(function(){  
            store_id = this.value;
            get_products(this.value);
        }); 
          
    });
    
function get_products(store_id=""){

    if(store_id == ''){           
           product_select.select2('destroy').empty().select2({ placeholder: "Please select store first" });
    }else{
        
         product_select.select2({
              minimumInputLength: 3,
             
            placeholder: "Please select store first",           
            ajax: {
              url: "{{url('admin/get-store-products')}}",
              dataType: 'json',
              data: function (params) {
      var query = {
        search: params.term,
        store_id:store_id,
      
      }

      // Query parameters will be ?search=[term]&type=public
      return query;
    },
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



