@extends('admin.layouts.app')

@section('style')
    <style>
        table tbody tr{cursor: pointer;}
    </style>
@endsection

@section('content')
<section id="main-content" >
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li class="active">Stock Chart</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>                
        
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Stock Chart 
                        
                        {!! getStoreDropdownHtml() !!}
                    </header>
                </section>
            </div>
        </div>
        
        <div class="row">                                               
            <div class="col-md-6">
                <div class="mini-stat clearfix">
                    <span class="mini-stat-icon green"><i class="fa fa-shopping-cart"></i></span>
                    <div class="mini-stat-info">
                        <span>{{ $report['total_products'] }}</span>
                        Total Products
                    </div>
                </div>    
            </div>
            <div class="col-md-6">
                <div class="mini-stat clearfix">
                    <span class="mini-stat-icon orange"><i class="fa fa-at"></i></span>
                    <div class="mini-stat-info">
                        <span>{{ $report['total_product_quantity'] }}</span>
                        Total Quantity
                    </div>
                </div> 
            </div>
        </div> 
        
        @if($report['total_product_price']>0)
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <div class="panel-body">                                                
                        <div id="stock_chart" ></div>
                    </div>
                </section>
            </div>
        </div>   
        @endif
        
    </section>
</section>

@endsection


@section('scripts')
<script src="//code.highcharts.com/highcharts.js"></script>
<script src="//code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        
       $(document).on('change', '#store_reports', function(){
          window.location.href = '{{ url("admin/reports/stock") }}'+'/'+ this.value; 
       }); 
       
        @if($report['total_product_price']>0) 
            Highcharts.setOptions({ lang: { thousandsSep: ',' } });
            Highcharts.chart('stock_chart', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {text: ''},
                credits: {enabled: false},
                exporting: { enabled: false },
                tooltip: {pointFormat: '<b>{point.y:,.2f}</b> ( {point.percentage:.2f}% )'},
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.y:,.2f}',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        }
                    }
                },
                series: [{
                    name: '',
                    colorByPoint: true,
                    data: [
                        {name: 'Stock Value by Price ',y: {{ $report['total_product_price'] }} },
                        {name: 'Stock Value by Cost',y: {{ $report['total_product_cost'] }} },
                        {name: 'Profit Estimate',y: {{ $report['profit_estimate'] }} }
                    ]
                }]
            });    
        @endif    
    });            
</script>
@endsection                            
