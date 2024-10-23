@section('style')
    <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
    <link href="{{ asset('css/gijgo.min.css') }}" rel="stylesheet">
    <style>

    </style>
@endsection

@php($current_tab = app('request')->input('tab'))
@switch($current_tab)
    @case(1)
        @php($tab1 = 'active')
        @php($tab2 = '')
        @php($tab3 = '')
    @break

    @case(2)
        @php($tab1 = '')
        @php($tab2 = 'active')
        @php($tab3 = '')
    @break

    @case(3)
        @php($tab1 = '')
        @php($tab2 = '')
        @php($tab3 = 'active')
    @break

    @default
        @php($tab1 = 'active')
        @php($tab2 = '')
        @php($tab3 = '')
@endswitch

<ul class="nav nav-tabs">
    @if (!isset($submitButtonText))
        <li class="{{ $tab1 }}"><a href="{{ url('admin/products/create') }}">Product Information</a></li>
    @else
        <li class="{{ $tab1 }}"><a
                href="{{ url('admin/products/' . Hashids::encode($product->id) . '/edit?tab=1') }}">Product
                Information</a>
        </li>
        <li class="{{ $tab2 }}"><a
                href="{{ url('admin/products/' . Hashids::encode($product->id) . '/edit?tab=2') }}">Store &
                Categories</a>
        </li>

        @if ($product->is_variants == 1)
            <li><a href="{{ url('admin/products/' . Hashids::encode($product->id) . '/edit?tab=3') }}">Variants</a></li>
        @endif
    @endif
</ul>

<div class="tab-content">
    @if (!empty($tab1))
        <div id="home" class="tab-pane fade in {{ $tab1 }}">
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <div class="panel-body">

                            <div class="row">
                                <div class="form-group col-md-4 {{ $errors->has('code') ? 'has-error' : '' }}">
                                    {!! Form::label('code', 'Product Code', ['class' => 'control-label required-input']) !!}
                                    <div class="input-group">
                                        {!! Form::text('code', null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Product Code',
                                            'required' => 'required',
                                        ]) !!}
                                        <span class="input-group-addon pointer" id="genrate_random_number"><i
                                                class="fa fa-random"></i></span>
                                    </div>
                                    <!--                            <span class="help-block">You can scan your barcode and select the correct symbology below.</span>-->
                                    {!! $errors->first('code', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-4 {{ $errors->has('ean_number') ? 'has-error' : '' }}">
                                    {!! Form::label('ean_number', 'EAN Number', ['class' => 'control-label required-input']) !!}
                                    {!! Form::text('ean_number', null, [
                                        'class' => 'form-control',
                                        'placeholder' => 'EAN Number',
                                        'required' => 'required',
                                    ]) !!}
                                    {!! $errors->first('ean_number', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-4 {{ $errors->has('name') ? 'has-error' : '' }}">
                                    {!! Form::label('name', 'Product Name', ['class' => 'control-label required-input']) !!}
                                    {!! Form::text('name', null, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Product Name',
                                        'required' => 'required',
                                    ]) !!}
                                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="row modifier_select">
                                <div class="form-group col-md-3" style="margin-left: 18px;" id="is_variants_text">
                                    {!! Form::checkbox('is_variants', 1, null, ['id' => 'is_variants']) !!} <b>This product has variants</b>
                                </div>
                            </div>

                            <div class="row modifier_select">

                                <div class="form-group col-md-4 {{ $errors->has('prefix') ? 'has-error' : '' }}">
                                    {!! Form::label('prefix', 'Category Prefix', ['class' => 'control-label']) !!}
                                    {!! Form::select('prefix', $categories, null, ['class' => 'form-control select2']) !!}
                                    {!! $errors->first('prefix', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div
                                    class="form-group variant_checked col-md-4 {{ $errors->has('sku') ? 'has-error' : '' }}">
                                    {!! Form::label('sku', 'SKU', ['class' => 'control-label required-input']) !!}
                                    <div class="input-group">
                                        {!! Form::text('sku', null, ['class' => 'form-control', 'placeholder' => 'SKU', 'required' => 'required']) !!}
                                        <span class="input-group-addon pointer" id="genrate_random_number_sku"><i
                                                class="fa fa-random"></i></span>
                                    </div>
                                    {!! $errors->first('sku', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group  col-md-4 {{ $errors->has('tax_rate_id') ? 'has-error' : '' }}">
                                    {!! Form::label('tax_rate_id', 'Product Tax', ['class' => 'control-label required-input']) !!}
                                    @if (isset($product))
                                        {!! Form::select('tax_rate_id', getTaxRatesDropdown(), null, [
                                            'class' => 'form-control select2',
                                            'required' => 'required',
                                        ]) !!}
                                    @else
                                        {!! Form::select('tax_rate_id', getTaxRatesDropdown(), null, [
                                            'class' => 'form-control select2',
                                            'required' => 'required',
                                        ]) !!}
                                    @endif
                                    {!! $errors->first('tax_rate_id', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>

                            </div>

                            <div class="row variant_checked">
                                <div class="form-group col-md-4 {{ $errors->has('supplier_id') ? 'has-error' : '' }}">
                                    {!! Form::label('supplier_id', 'Supplier Name', ['class' => 'control-label']) !!}
                                    {!! Form::select('supplier_id', getSuppliersDropdown(), null, ['class' => 'form-control select2']) !!}
                                    {!! $errors->first('supplier_id', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>


                                <div
                                    class="form-group col-md-4 {{ $errors->has('discount_type') ? 'has-error' : '' }}">
                                    {!! Form::label('discount_type', 'Discount Type', ['class' => 'control-label']) !!}
                                    {!! Form::select('discount_type', ['0' => 'Select Discount Type', '1' => 'Percentage', '2' => 'Fixed'], null, [
                                        'class' => 'form-control select2',
                                    ]) !!}
                                    {!! $errors->first('discount_type', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-4 {{ $errors->has('discount') ? 'has-error' : '' }}">
                                    {!! Form::label('discount', 'Max Discount', ['class' => 'control-label']) !!}
                                    {!! Form::number('discount', null, ['class' => 'form-control', 'placeholder' => 'Max Discount', 'min' => '0']) !!}
                                    {!! $errors->first('discount', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>

                            </div>

                            <div class="row">
                                <div
                                    class="form-group col-md-4 hide_cost {{ $errors->has('cost') ? 'has-error' : '' }}">
                                    {!! Form::label('cost', 'Product Cost', ['class' => 'control-label required-input']) !!}
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button">£</button>
                                        </span>
                                        {!! Form::number('cost', null, [
                                            'class' => 'form-control',
                                            'required' => 'required',
                                            'placeholder' => 'Product Cost',
                                            'step' => 'any',
                                        ]) !!}
                                    </div>
                                    {!! $errors->first('cost', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div
                                    class="form-group col-md-4 hide_price {{ $errors->has('price') ? 'has-error' : '' }}">
                                    {!! Form::label('price', 'Product Price', ['class' => 'control-label required-input']) !!}
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button">£</button>
                                        </span>
                                        {!! Form::number('price', null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Product Price',
                                            'min' => 0,
                                            'step' => 'any',
                                            'required' => 'required',
                                        ]) !!}
                                    </div>
                                    {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="form-group col-md-4 {{ $errors->has('brand_id') ? 'has-error' : '' }}">
                                    {!! Form::label('brand_id', 'Brand Name', ['class' => 'control-label']) !!}
                                    {!! Form::select('brand_id', $brands, null, ['class' => 'form-control select2']) !!}
                                    {!! $errors->first('brand_id', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-4 {{ $errors->has('shipping_id') ? 'has-error' : '' }}">
                                    {!! Form::label('shipping_id', 'Shipping Charges', ['class' => 'control-label required-input']) !!}
                                    {!! Form::select('shipping_id', $shippings, null, ['class' => 'form-control select2', 'required' => 'required']) !!}
                                    {!! $errors->first('shipping_id', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-4 {{ $errors->has('new_arrivals') ? 'has-error' : '' }}">
                                    {!! Form::label('new_arrivals', 'New Arrivals', ['class' => 'control-label required-input']) !!}
                                    {!! Form::select('new_arrivals', ['1' => 'Yes', '0' => 'No'], null, [
                                        'class' => 'form-control select2',
                                        'required' => 'required',
                                    ]) !!}
                                    {!! $errors->first('new_arrivals', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>

                            </div>


                            <div class="row">
                                <div
                                    class="form-group col-md-4 {{ $errors->has('barcode_symbology') ? 'has-error' : '' }}">
                                    {!! Form::label('barcode_symbology', 'Barcode Symbology', ['class' => 'control-label required-input']) !!}
                                    {!! Form::select(
                                        'barcode_symbology',
                                        [
                                            'code25' => 'Code25',
                                            'code39' => 'Code39',
                                            'code128' => 'Code128',
                                            'ean8' => 'EAN8',
                                            'ean13' => 'EAN13',
                                            'upca' => 'UPC-A',
                                            'upce' => 'UPC-E',
                                        ],
                                        null,
                                        ['class' => 'form-control select2', 'required' => 'required'],
                                    ) !!}
                                    {!! $errors->first('barcode_symbology', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-4 {{ $errors->has('is_featured') ? 'has-error' : '' }}">
                                    {!! Form::label('is_featured', 'Is Featured', ['class' => 'control-label required-input']) !!}
                                    {!! Form::select('is_featured', ['1' => 'Yes', '0' => 'No'], null, [
                                        'class' => 'form-control select2',
                                        'required' => 'required',
                                    ]) !!}
                                    {!! $errors->first('is_featured', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-4 {{ $errors->has('is_hot') ? 'has-error' : '' }}">
                                    {!! Form::label('is_hot', 'Is Hot', ['class' => 'control-label required-input']) !!}
                                    {!! Form::select('is_hot', ['1' => 'Yes', '0' => 'No'], null, [
                                        'class' => 'form-control select2',
                                        'required' => 'required',
                                    ]) !!}
                                    {!! $errors->first('is_hot', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    {!! Form::checkbox('view_retailer', 1, @$product->view_retailer) !!} <b>Visible For Retailer</b>
                                </div>
                                <div class="form-group col-md-4">
                                    {!! Form::checkbox('view_dropshipper', 1, @$product->view_dropshipper) !!} <b>Visible For Dropshipper</b>
                                </div>
                                <div class="form-group col-md-4">
                                    {!! Form::checkbox('view_wholesaler', 1, @$product->view_wholesaler) !!} <b>Visible For Wholesaler</b>
                                </div>
                            </div>

                            <div
                                class="form-group col-md-12 modifier_select {{ $errors->has('tags') ? 'has-error' : '' }}">
                                {!! Form::label('tags', 'Tags', ['class' => 'control-label ']) !!}
                                {!! Form::textarea('tags', isset($product) ? $product->product_tags->pluck('name')->implode(',') : '', [
                                    'class' => 'form-control',
                                ]) !!}
                                {!! $errors->first('tags', '<p class="help-block">:message</p>') !!}
                                <div class="help-block with-errors"></div>
                            </div>



                            <div class="form-group {{ $errors->has('meta_title') ? 'has-error' : '' }}">

                                {!! Form::label('meta_title', 'Meta Title', ['class' => 'col-md-3 control-label']) !!}

                                <div class="col-md-9">

                                    {!! Form::textarea('meta_title', null, ['class' => 'form-control', 'rows' => '3']) !!}

                                    {!! $errors->first('meta_title', '<p class="help-block">:message</p>') !!}

                                    <div class="help-block with-errors"></div>

                                </div>

                            </div>



                            <div class="form-group {{ $errors->has('meta_description') ? 'has-error' : '' }}">

                                {!! Form::label('meta_description', 'Meta Description', ['class' => 'col-md-3 control-label']) !!}

                                <div class="col-md-9">

                                    {!! Form::textarea('meta_description', null, ['class' => 'form-control', 'rows' => '3']) !!}

                                    {!! $errors->first('meta_description', '<p class="help-block">:message</p>') !!}

                                    <div class="help-block with-errors"></div>

                                </div>

                            </div>
                            <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">

                                {!! Form::label('slug', 'Slug', ['class' => 'col-md-3 control-label']) !!}

                                <div class="col-md-9">

                                    {!! Form::textarea('slug', null, ['class' => 'form-control', 'rows' => '3']) !!}

                                    {!! $errors->first('slug', '<p class="help-block">:message</p>') !!}

                                    <div class="help-block with-errors"></div>

                                </div>

                            </div>

                            <div class="row modifier_select">
                                <div class="form-group col-md-12">
                                    {!! Form::label('images', 'Product Images', ['class' => 'control-label']) !!}
                                    <div class="dropzone {{ isset($product) ? 'dz-started' : '' }}"
                                        id="my-awesome-dropzone">
                                        @include('admin.products.imagelist')
                                    </div>
                                    <input id="total_images" name="product_images"
                                        value="{{ isset($product) ? $product->product_images->count() : '' }}"
                                        style="display:none;" type="text">
                                    <div class="help-block with-errors"style="margin-left: 10px;"></div>
                                </div>
                            </div>

                            <div class="row modifier_select">
                                <div class="form-group col-md-12 {{ $errors->has('detail') ? 'has-error' : '' }}">
                                    {!! Form::label('detail', 'Product Details', ['class' => 'control-label']) !!}
                                    {!! Form::textarea('detail', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('detail', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>

                                <!--                                  <div class="form-group col-md-6 {{ $errors->has('invoice_detail') ? 'has-error' : '' }}">
                                    {!! Form::label('invoice_detail', 'Product Details for Invoice', ['class' => 'control-label']) !!}
                                        {!! Form::textarea('invoice_detail', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('invoice_detail', '<p class="help-block">:message</p>') !!}
                                        <div class="help-block with-errors"></div>
                                  </div> -->
                            </div>

                            <div class="row modifier_select">
                                <div class="form-group col-md-6 {{ $errors->has('full_detail') ? 'has-error' : '' }}">
                                    {!! Form::label('full_detail', 'Product Full Details', ['class' => 'control-label']) !!}
                                    {!! Form::textarea('full_detail', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('full_detail', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div
                                    class="form-group col-md-6 {{ $errors->has('tecnical_specs') ? 'has-error' : '' }}">
                                    {!! Form::label('tecnical_specs', 'Tecnical Specs', ['class' => 'control-label']) !!}
                                    {!! Form::textarea('tecnical_specs', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('tecnical_specs', '<p class="help-block">:message</p>') !!}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    {!! Form::submit(isset($product) ? 'Save' : 'Save & Next', ['class' => 'btn btn-info pull-right']) !!}
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    @endif

    @if (isset($submitButtonText))
        @if (!empty($tab2))
            <!-- Store and Categories-->
            <div class="tab-pane fade in {{ $tab2 }}">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="panel-body">

                                <div class="row">
                                    <div
                                        class="form-group col-md-6 {{ $errors->has('store_category_ids') ? 'has-error' : '' }}">
                                        {!! Form::label('store_category_ids', 'Stores & Categories', ['class' => 'control-label required-input']) !!}
                                        <div id="store_category_tree"></div>
                                        <input type="text" name="store_category_ids" id="checkedIds" required
                                            style="display:none;" />
                                        {!! $errors->first('store_category_ids', '<p class="help-block">:message</p>') !!}
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        {!! Form::label('store_quantity', 'Stores Quantity', ['class' => 'control-label required-input']) !!}
                                        <div id="store_quantity" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    {!! Form::submit('Save', ['class' => 'btn btn-info pull-right']) !!}
                                </div>
                            </div>
                    </div>
                    </section>
                </div>
            </div>
</div>
@endif
@endif
</div>

@section('scripts')
    <!--<script type="text/javascript" src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>-->
    <script src="//cdn.ckeditor.com/4.8.0/full/ckeditor.js"></script>
    @if (!empty($tab1))
        <script type="text/javascript" src="{{ asset('js/dropzone.js') }}"></script>
    @endif
    <script type="text/javascript" src="{{ asset('js/gijgo.min.js') }}"></script>
    <script type="text/javascript">
        var token = $('meta[name="csrf-token"]').attr('content');
        var baseUrl = "{{ url('admin/products') }}";
        var category_select = $('#category_id');

        $(document).ready(function() {

            $('#discount_type').change();

            @if (@$product)
                var url = "{{ url('admin/get-all-store-categories') }}" + '/' + {{ $product->id }}
            @else
                var url = "{{ url('admin/get-all-store-categories') }}";
            @endif

            @if (@$product && !empty($tab1))
                show_hide_fields(1);
            @else
                show_hide_fields(1);
            @endif

            @if (@$product && !empty($tab2))
                var tree = $('#store_category_tree').tree({
                    primaryKey: 'data_id',
                    uiLibrary: 'bootstrap',
                    dataSource: url,
                    icons: {
                        expand: '<i class="glyphicon glyphicon-circle-arrow-right"></i>',
                        collapse: '<i class="glyphicon glyphicon-circle-arrow-down"></i>'
                    },
                    checkboxes: true,
                    border: true,
                    showIcon: true,
                });

                tree.on('checkboxChange', function(e, $node, record, state) {

                    var checkedIds = tree.getCheckedNodes();

                    $("#checkedIds").val(checkedIds);
                    $("#store_quantity").html('');

                    $.each(checkedIds, function(index, value) {

                        var id_type = value.split('-');
                        if (id_type[0] == 'store') {
                            var data = tree.getDataById(value);

                            if (data.name) {
                                $("#store_quantity").append('<div class="form-group">\
                                    <label class="control-label required-input">' + data.text + '</label>\
                                    <input type="number" name="store_quantity_' + data.data_id + '" class="form-control" min="0" required />\
                                    <div class="help-block with-errors"></div>\
                                   </div>');

                                set_store_products(tree, 50);
                                $('#product_form').validator('update');
                            }

                        }
                    });

                });

                set_store_products(tree, 2000);
            @endif

            //        $("#product_form").validator({
            //            disable: true,
            //            custom: {
            //                "checkedIds": function(el) {
            //                    if(el.val() != ""){
            //                        var ids = el.val().split(',');
            //                        var test = 0;
            //                        $.each(ids,function(index, value){
            //                           var id_type = value.split('-');
            //                           if(id_type[0] == 'category' || id_type[0] == 'subcategory'){
            //                               test = 1;
            //                           }
            //                        });
            //
            //                        setTimeout(function(){
            //                            if(test == 0){
            //                                return 'Please select store and atleast one category.';
            //                            }
            //                        }, 1000);
            //                    }
            //                }
            //            },
            //        });

            $('#tags').tagsInput({
                width: 'auto'
            });

            $("#genrate_random_number").click(function() {
                var random_number = generateRandomNumber(6);
                $("#code").val(random_number);
                $("#code").blur();
                return false;
            });

            $("#genrate_random_number_sku").click(function() {
                var random_number = generateRandomNumber(4);
                var prefix = $("#prefix option:selected").val();

                var sku = $('#sku');
                if (sku.val() == "") {
                    if (prefix != "") {
                        sku.val(prefix + '-' + random_number);
                    } else {
                        sku.val(random_number);
                    }
                } else {
                    var skus = sku.val().split('-');
                    if (skus.length == 1) {
                        if (prefix != "") {
                            sku.val(prefix + '-' + random_number);
                        } else {
                            sku.val(random_number);
                        }

                    } else {

                        sku.val(skus[0] + '-' + random_number);

                    }
                }

                //$("#sku").val(random_number);
                $("#sku").blur();
                return false;
            });


            $('#discount_type').change(function() {
                var text = "Max Discount";
                if (this.value == 1) {
                    text = "Max Discount (%)";
                } else if (this.value == 2) {
                    text = "Max Discount (Fixed)";
                }

                $("#discount").parent(".form-group").find("label").text(text);
                $("#discount").attr("placeholder", text);
            });

            $('#prefix').change(function() {
                var prefix = this.value;

                var sku = $('#sku');
                if (sku.val() == "") {
                    sku.val(prefix + '-');
                } else {
                    var skus = sku.val().split('-');
                    if (prefix == "") {
                        if (skus.length == 1) {
                            sku.val(skus[0]);
                        } else {
                            sku.val(skus[1]);
                        }
                    } else {
                        if (skus.length == 1) {
                            sku.val(prefix + '-' + skus[0]);
                        } else {
                            sku.val(prefix + '-' + skus[1]);
                        }
                    }

                }

                sku.focus();
            });




            // --------------------- variants code --------------

            // $(document).on('click','#is_variants',function() {
            //     if ($(this).is(':checked')) {
            //         $("#is_modifier").prop('checked',false);
            //         show_hide_fields(2);
            //     }else{
            //         show_hide_fields(1);
            //     }
            // });


            @if (!empty($tab1))
                Dropzone.autoDiscover = false;
                $(document).ready(function() {
                    var myDropzone = new Dropzone("div#my-awesome-dropzone", {
                        url: baseUrl + "/store-image",
                        paramName: "file",
                        maxFilesize: 2,
                        init: function() {
                            var self = this;
                            // config
                            self.options.addRemoveLinks = true;
                            self.options.dictRemoveFile = "Remove";
                            // bind events

                            /*
                             * Success file upload
                             */
                            self.on("success", function(file, response) {
                                console.log(response);
                                if (response) {
                                    $('#my-awesome-dropzone').append(
                                        '<input type="hidden" name="image_ids[]" class="image_ids" id="img_' +
                                        response.id + '" value="' + response.id +
                                        '"/>');
                                    file.previewElement.classList.add("dz-" + response
                                        .id);

                                    $('.dz-' + response.id).append(
                                        '<span class="default-image"><input class="default_image" id="default_image_' +
                                        response.id + '" data-id="' + response.id +
                                        '" type="checkbox" data-toggle="tooltip" title="Set image as default"></span>'
                                    );
                                    $('.dz-' + response.id).find('.dz-image img').attr(
                                        'src', response.image_url);
                                }


                                file.serverId = response.id;

                                var total_images = $("#total_images").val();

                                if (total_images == "")
                                    $("#total_images").val(1);
                                else
                                    $("#total_images").val(parseInt(total_images) + 1);
                            });

                            self.on("error", function(file, message) {
                                console.log(message);
                                $(file.previewElement).addClass("dz-error").find(
                                    '.dz-error-message').text(message.message);
                            });

                            /*
                             * On delete file
                             */
                            self.on("removedfile", function(file) {
                                $.ajax({
                                    url: baseUrl + '/delete-image/' + file
                                        .serverId,
                                    type: 'get',
                                    data: {
                                        '_token': token
                                    },
                                    success: function(result) {
                                        var total_images = $(
                                            "#total_images").val();
                                        if (total_images == 1)
                                            $("#total_images").val("");
                                        else
                                            $("#total_images").val(parseInt(
                                                total_images) - 1);
                                    }
                                });
                            });
                        },
                        params: {
                            _token: token
                        }
                    });

                });


                $(document).on('click', '.default_image', function() {

                    var image_id = $(this).data('id');
                    var image_ids = [];

                    $("#my-awesome-dropzone .dz-preview").each(function() {
                        image_ids.push($(this).find('.default-image input').data('id'));
                    });

                    if ($(this).is(':checked')) {
                        $('.default_image').prop('checked', false);
                        $(this).prop('checked', true);
                        var checked = 1;
                    } else {
                        $('.default_image').prop('checked', false);
                        var checked = 0;
                    }

                    $.ajax({
                        url: baseUrl + '/set-default-image',
                        type: 'post',
                        data: {
                            '_token': token,
                            'image_id': image_id,
                            'image_ids': image_ids,
                            'checked': checked
                        },
                        success: function(result) {

                        }

                    });

                });
            @endif

        });

        function show_hide_fields(type) {
            if (type == 1) { // Show
                $(".modifier_select").show();
                // if($("#is_variants").is(':checked')){
                //     $(".variant_checked").hide();
                //     $("#sku").removeAttr("required","required");
                // }else{
                //     $(".variant_checked").show();
                //     $("#sku").attr("required","required");
                // }

                $("#cost").attr("required", "required");
                $("#price").attr("required", "required");
                $("#cost").parent('.hide_cost').show();
                $("#price").parent('.hide_price').show();


            } else if (type == 2) { // Hide
                $(".modifier_select").show();
                $(".variant_checked").hide();
                $("#sku").removeAttr("required", "required");
                $("#cost").removeAttr("required", "required");
                $("#price").removeAttr("required", "required");
            }
        }

        @if (@$product && !empty($tab2))
            function set_store_products(tree, timeout) {
                setTimeout(function() {
                    @foreach ($product->store_products as $store)
                        @if ($store->quantity > 0)
                            tree.expand(tree.getNodeById('store-{{ $store->store_id }}'));
                            $("input[name=store_quantity_store-{{ $store->store_id }}]").val(
                                {{ $store->quantity }});
                        @endif
                    @endforeach

                    @foreach ($product->category_products as $category)
                        @if ($category->category->parent_id == 0)
                            tree.expand(tree.getNodeById('category-{{ $category->category_id }}'));
                        @else
                            tree.expand(tree.getNodeById('subcategory-{{ $category->category_id }}'));
                        @endif
                    @endforeach

                }, timeout);
            }
        @endif

        function remove_uploaded_file(imageId) {
            $.ajax({
                url: baseUrl + '/delete-image/' + imageId,
                type: 'get',
                success: function(result) {
                    $('.dz-' + imageId).remove();

                    var total_images = $("#total_images").val();
                    if (total_images == 1)
                        $("#total_images").val("");
                    else
                        $("#total_images").val(parseInt(total_images) - 1);
                }
            });
        }

        @if (!empty($tab1))
            $(document).ready(function() {
                CKEDITOR.replace('detail', {
                    removePlugins: 'elementspath,magicline',
                    resize_enabled: false,
                    allowedContent: true,
                    enterMode: CKEDITOR.ENTER_BR,
                    shiftEnterMode: CKEDITOR.ENTER_BR,
                    // toolbar: [
                    //     [ 'Bold','-','Italic','-','Underline'],
                    // ],
                });

                //        CKEDITOR.replace( 'invoice_detail',{
                //            removePlugins: 'elementspath,magicline',
                //            resize_enabled: false,
                //            allowedContent: true,
                //            enterMode: CKEDITOR.ENTER_BR,
                //            shiftEnterMode: CKEDITOR.ENTER_BR,
                //            // toolbar: [
                //            //     [ 'Bold','-','Italic','-','Underline','-','Styles','-','Format','-','Font','-','Font Size'],
                //            // ],
                //        });

                CKEDITOR.replace('full_detail', {
                    removePlugins: 'elementspath,magicline',
                    resize_enabled: false,
                    allowedContent: true,
                    enterMode: CKEDITOR.ENTER_BR,
                    shiftEnterMode: CKEDITOR.ENTER_BR,
                    // toolbar: [
                    //     [ 'Source','-','Image','-','Bold','-','Italic','-','Underline'],
                    // ],
                });
                CKEDITOR.replace('tecnical_specs', {
                    removePlugins: 'elementspath,magicline',
                    resize_enabled: false,
                    allowedContent: true,
                    enterMode: CKEDITOR.ENTER_BR,
                    shiftEnterMode: CKEDITOR.ENTER_BR,
                    // toolbar: [
                    //     [ 'Source','-','Image','-','Bold','-','Italic','-','Underline'],
                    // ],
                });
            });
        @endif
    </script>
@endsection
