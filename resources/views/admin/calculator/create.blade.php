@extends('admin.layouts.app')

@section('content')

<section id="main-content" >
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li class="active">Calculator</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
        
        {!! Form::open(['url' => 'admin/calculator', 'data-toggle' => 'validator', 'data-disable' => 'false', 'class' => 'form-horizontal', 'files' => true]) !!}

            <div class="row">            
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">Calculator</header>
                        <div class="panel-body">
                            <div class="position-center">                    

                                <div class="form-group">
                                    {!! Form::label('qty', 'Qty', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                    <div class="col-lg-9 col-md-9">
                                        {!! Form::number('qty', 1, ['class' => 'form-control','placeholder' => 'Qty','required' => 'required', 'onBlur' => 'calculate()']) !!}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>                                                                               
                                <div class="form-group">
                                    {!! Form::label('cost_price', 'Cost Price', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                    <div class="col-lg-9 col-md-9">
                                        {!! Form::number('cost_price', 5, ['class' => 'form-control','placeholder' => 'Cost Price','required' => 'required','step'=>"0.01", 'onBlur' => 'calculate()']) !!}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>                                                                               
                                <div class="form-group">
                                    {!! Form::label('item_cost_price_exc_vat', 'Item Cost Price (Exc VAT)', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                    <div class="col-lg-9 col-md-9">
                                        {!! Form::number('item_cost_price_exc_vat', 5, ['class' => 'form-control','placeholder' => 'Item Cost Price (Exc VAT)','required' => 'required','step'=>"0.01", 'onBlur' => 'calculate()']) !!}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    {!! Form::label('item_cost_price_inc_vat', 'Item Cost Price (Inv VAT)', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                    <div class="col-lg-9 col-md-9">
                                        {!! Form::number('item_cost_price_inc_vat', 6, ['class' => 'form-control','placeholder' => 'Item Cost Price (Inv VAT)','required' => 'required','step'=>"0.01", 'onBlur' => 'calculate()']) !!}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    {!! Form::label('courier', 'Courier', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                    <div class="col-lg-9 col-md-9">
                                        <select id="courier" class="form-control" onChange="calculate()">
                                            <option value="0">Select Courier</option>
                                            @foreach($couriers as $courier)
                                            <option value="{{ $courier->charges }}" >{{ $courier->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('postage_price', 'Postage Cost', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                    <div class="col-lg-9 col-md-9">
                                        {!! Form::number('postage_price', 0, ['class' => 'form-control','placeholder' => 'Postage Cost','required' => 'required','step'=>"0.01", 'onBlur' => 'calculate()']) !!}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('buy_it_now_price', 'BUY It Now Price', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                    <div class="col-lg-9 col-md-9">
                                        {!! Form::number('buy_it_now_price', 14.99, ['class' => 'form-control','placeholder' => 'BUY It Now Price','required' => 'required','step'=>"0.01", 'onBlur' => 'calculate()']) !!}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('p_and_p_charges', 'P&P Charges', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                    <div class="col-lg-9 col-md-9">
                                        {!! Form::number('p_and_p_charges', 0, ['class' => 'form-control','placeholder' => 'P&P Charges','required' => 'required','step'=>"0.01", 'onBlur' => 'calculate()']) !!}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('total_selling', 'Total Selling', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                    <div class="col-lg-9 col-md-9">
                                        {!! Form::number('total_selling', 14.99, ['class' => 'form-control','placeholder' => 'Total Selling','required' => 'required','step'=>"0.01", 'onBlur' => 'calculate()']) !!}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('paypal_fee', 'Paypal Fees', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                    <div class="col-lg-9 col-md-9">
                                        {!! Form::number('paypal_fee', 0, ['class' => 'form-control','placeholder' => 'Paypal Fees','required' => 'required','step'=>"0.01", 'onBlur' => 'calculate()']) !!}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('hidden_expense', 'Hidden Expenses', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                    <div class="col-lg-9 col-md-9">
                                        {!! Form::number('hidden_expense', 0.15, ['class' => 'form-control','placeholder' => 'Hidden Expenses','required' => 'required','step'=>"0.01", 'onBlur' => 'calculate()']) !!}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('total_expense', 'Total Expense', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                    <div class="col-lg-9 col-md-9">
                                        {!! Form::number('total_expense', 0, ['class' => 'form-control','placeholder' => 'Total Expense','required' => 'required','step'=>"0.01", 'onBlur' => 'calculate()']) !!}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('net_profit', 'Net Profit', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                    <div class="col-lg-9 col-md-9">
                                        {!! Form::number('net_profit', null, ['class' => 'form-control','placeholder' => 'Net Profit','required' => 'required','step'=>"0.01", 'onBlur' => 'calculate()']) !!}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('net_profit_percentage', 'Profit(%)', ['class' => 'col-lg-3 col-sm-3 control-label required-input']) !!}
                                    <div class="col-lg-9 col-md-9">
                                        {!! Form::number('net_profit_percentage', null, ['class' => 'form-control','placeholder' => 'Profit(%)','required' => 'required','step'=>"0.01", 'onBlur' => 'calculate()']) !!}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
            </div>

        {!! Form::close() !!}
            
    </section>
</section>


@endsection
@section('scripts')
<script type="text/javascript">
    
    function updateCourierAmount(val){
        $("#postage_price").val(val);
    }
    function calculate(){
        var qty = $("#qty").val();
        var price = $("#cost_price").val();
        var price_exc_var = qty*price;
        $("#item_cost_price_exc_vat").val(price_exc_var.toFixed(2));
        $("#item_cost_price_inc_vat").val((price_exc_var+(price_exc_var/5)).toFixed(2));
        var item_cost_price_inc_vat = $("#item_cost_price_inc_vat").val();
        var postage_price = $("#courier option:selected").val();
        var postage_price_vat = (postage_price/100) * 20;
        $("#postage_price").val((parseFloat(postage_price)+parseFloat(postage_price_vat)).toFixed(2));
        var postage_price = $("#postage_price").val();
        var buy_it_now = $("#buy_it_now_price").val();
        var p_and_p_charges = $("#p_and_p_charges").val();
        var total_selling = parseFloat(buy_it_now) + parseFloat(p_and_p_charges);
        $("#total_selling").val(total_selling.toFixed(2));
        var total_selling = $("#total_selling").val();
//        var ebay_fee = (buy_it_now/100)*10;
//        $("#ebay_fee").val(ebay_fee);
        $("#paypal_fee").val(((total_selling/100)*5).toFixed(2));
        var paypal_fee = $("#paypal_fee").val();
        var hidden_expense = $("#hidden_expense").val();
        
        var total_expense = parseFloat(item_cost_price_inc_vat)+parseFloat(postage_price)+parseFloat(paypal_fee)+parseFloat(hidden_expense);
        $("#total_expense").val(total_expense.toFixed(2));
        var total_profit = total_selling - $("#total_expense").val();
        $("#net_profit").val(total_profit.toFixed(2));
        $("#net_profit_percentage").val(((total_selling-total_expense)/total_selling*100).toFixed(2));
    }
    calculate();
    
    $(document).ready(function(){  
        
    });
</script>
@endsection