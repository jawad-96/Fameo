@extends('admin.layouts.app')

@section('style')
<style>
    .mini-stat{background: #f7f7f7;}
</style>
@endsection
@section('content')
<section id="main-content" >
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li class="active"><i class="fa fa-home"></i> Dashboard</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
        
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Retail Dashboard
                        <span class="pull-right">
                            <div id="reportrange" class="pull-right report-range">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span></span> <b class="caret"></b>
                            </div>
                        </span>
                        <!-- {!! getStoreDropdownHtml() !!} -->
                    </header>

                    @if($products->count() > 0)
                        <div class="row" style="margin-top:10px;">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="alert alert-info alert-dismissible">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>These products are out of stock
                                        <a style="margin-right: 20px;" href="{{ url('admin/send-stock-email') }}" class="btn btn-info pull-right">Send Email</a>
                                    </strong>
                                    <ul>
                                        @foreach($products as $product)
                                        <li>{{ $product }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="panel-body retail-dashboard">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mini-stat clearfix">
                                    <span class="mini-stat-icon pink"><i class="fa fa-money"></i></span>
                                    <div class="mini-stat-info" data-toggle="tooltip" title="Total income from products sold">
                                        <span id="total_income">0</span>
                                        REVENUE
                                    </div>
                                </div>
                            </div>  
                            <div class="col-md-3">
                                <div class="mini-stat clearfix">
                                    <span class="mini-stat-icon orange"><i class="fa fa-shopping-cart"></i></span>
                                    <div class="mini-stat-info" data-toggle="tooltip" title="Number of sales in this time period">
                                        <span id="total_sales">0</span>
                                        SALE COUNT
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mini-stat clearfix">
                                    <span class="mini-stat-icon tar"><i class="fa fa-tag"></i></span>
                                    <div class="mini-stat-info" data-toggle="tooltip" title="Number of unique registered customers served in a time period">
                                        <span id="total_customers">0</span>
                                        CUSTOMER COUNT
                                    </div>
                                </div>
                            </div>                           
                            <div class="col-md-3">
                                <div class="mini-stat clearfix">
                                    <span class="mini-stat-icon green"><i class="fa fa-money"></i></span>
                                    <div class="mini-stat-info" data-toggle="tooltip" title="Total revenue less the cost of goods sold">
                                        <span id="total_profit">0</span>
                                        GROSS PROFIT
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mini-stat clearfix">
                                    <span class="mini-stat-icon pink"><i class="fa fa-money"></i></span>
                                    <div class="mini-stat-info" data-toggle="tooltip" title="Total amount discounted for this time period">
                                        <span id="total_discount">0</span>
                                        DISCOUNT
                                    </div>
                                </div>
                            </div>  
                            <div class="col-md-3">
                                <div class="mini-stat clearfix">
                                    <span class="mini-stat-icon orange">%</span>
                                    <div class="mini-stat-info" data-toggle="tooltip" title="Total amount discounted for this time period">
                                        <span id="discount_percentage">0</span>
                                        DISCOUNT % 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mini-stat clearfix">
                                    <span class="mini-stat-icon tar"><i class="fa fa-shopping-cart"></i></span>
                                    <div class="mini-stat-info" data-toggle="tooltip" title="Average amounts per sale">
                                        <span id="basket_value">0</span>
                                        AVERAGE BASKET VALUE
                                    </div>
                                </div>
                            </div>                           
                            <div class="col-md-3">
                                <div class="mini-stat clearfix">
                                    <span class="mini-stat-icon green"><i class="fa fa-at"></i></span>
                                    <div class="mini-stat-info" data-toggle="tooltip" title="Average number of products per sale">
                                        <span id="basket_size">0</span>
                                        AVERAGE BASKET SIZE
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>   
         
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Overall Summary
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ url('admin/stores') }}">
                                    <div class="mini-stat clearfix">
                                        <span class="mini-stat-icon orange"><i class="fa fa-home"></i></span>
                                        <div class="mini-stat-info">
                                            <span>{{ totalStores() }}</span>
                                            Stores
                                        </div>
                                    </div>
                                </a>    
                            </div>
                            <div class="col-md-3">
                                <a href="{{ url('admin/products') }}">
                                    <div class="mini-stat clearfix">
                                        <span class="mini-stat-icon green"><i class="fa fa-shopping-cart"></i></span>
                                        <div class="mini-stat-info">
                                            <span>{{ totalProducts() }}</span>
                                            Products
                                        </div>
                                    </div>
                                </a>    
                            </div>
                            <div class="col-md-3">
                                <a href="{{ url('admin/orders') }}">
                                    <div class="mini-stat clearfix">
                                        <span class="mini-stat-icon pink"><i class="fa fa-file-text"></i></span>
                                        <div class="mini-stat-info">
                                            <span>{{ totalSales() }}</span>
                                            Sales
                                        </div>
                                    </div>
                                </a>    
                            </div>
                            <div class="col-md-3">
                                <a href="{{ url('admin/categories') }}">
                                    <div class="mini-stat clearfix">
                                        <span class="mini-stat-icon tar"><i class="fa fa-sitemap"></i></span>
                                        <div class="mini-stat-info">
                                            <span>{{ totalCategories() }}</span>
                                            Categories
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ url('admin/suppliers') }}">
                                    <div class="mini-stat clearfix">
                                        <span class="mini-stat-icon tar"><i class="fa fa-users"></i></span>
                                        <div class="mini-stat-info">
                                            <span>{{ totalSuppliers() }}</span>
                                            Suppliers
                                        </div>
                                    </div>
                                </a>    
                            </div>
                            <div class="col-md-3">
                                <a href="{{ url('admin/brands') }}">
                                    <div class="mini-stat clearfix">
                                        <span class="mini-stat-icon tar"><i class="fa fa-bitcoin"></i></span>
                                        <div class="mini-stat-info">
                                            <span>{{ totalBrands() }}</span>
                                            Brands
                                        </div>
                                    </div>
                                </a>
                            </div>                            
                            <div class="col-md-3">
                                <a href="{{ url('admin/customers') }}">
                                    <div class="mini-stat clearfix">
                                        <span class="mini-stat-icon pink"><i class="fa fa-users"></i></span>
                                        <div class="mini-stat-info">
                                            <span>{{ registeredUsers() }}</span>
                                            Customers
                                        </div>
                                    </div>
                                </a>    
                            </div>
                             <div class="col-md-3">
                                <a href="{{ url('admin/courier-assignment') }}">
                                    <div class="mini-stat clearfix">
                                        <span class="mini-stat-icon pink"><i class="fa fa-users"></i></span>
                                        <div class="mini-stat-info">
                                            <span>{{ getAdminPendingRequests() }}</span>
                                            Pending Requests
                                        </div>
                                    </div>
                                </a>    
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>       
    </section>
</section>
      
@endsection


@section('scripts')
<script type="text/javascript">
    
    $(document).ready(function () {
        
    var start = moment().subtract(6, 'days');
    var end = moment();

    function cb(from_date, end_date) {
        
        $(".retail-dashboard").LoadingOverlay("show");
        
        start = from_date;
        end = end_date;
        var store_id = $('#store_reports').val();
        
        $.ajax({
            url :  '{{ url("admin/reports/retail-dashboard") }}',
            type: 'post',
            data: 'store_id='+store_id+'&from_date='+start.format('YYYY/MM/DD')+'&to_date='+end.format('YYYY/MM/DD'),
            //data: {'from_date':start, 'to_date': end},
            success: function (result) { 
                console.log(result);
                var data = result.result;
                $('#total_income').text(data.total_income);
                $('#total_sales').text(data.total_sales);
                $('#total_customers').text(data.total_customers);
                $('#total_profit').text(data.total_profit);
                $('#total_discount').text(data.total_discount);
                $('#discount_percentage').text(data.discount_percentage);
                $('#basket_value').text(data.basket_value);
                $('#basket_size').text(data.basket_size); 
        
                $(".retail-dashboard").LoadingOverlay("hide");
               
            }
        });
        
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        
    }
    
    $(document).on('change', '#store_reports', function(){
        cb(start, end)
     });
    
    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

    });
    
</script>
@endsection                            

