@extends('admin.layouts.app')

@section('style')
    <style>
        td span.details-control {
            background: url(../images/details_open.png) no-repeat center center;
            cursor: pointer;
            width: 18px;
            padding: 12px;
        }

        tr.shown td span.details-control {
            background: url(../images/details_close.png) no-repeat center center;
        }

        .dataTables_length {
            float: left;
        }

        .dt-buttons {
            float: right;
            margin: 14px 0 0 0px;
        }

        div.dataTables_processing {
            top: 55%;
        }
    </style>
@endsection

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-md-12">
                    <!--breadcrumbs start -->
                    <ul class="breadcrumb">
                        <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                        <li class="active">Orders</li>
                    </ul>
                    <!--breadcrumbs end -->
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Orders
                            <span class="pull-right">

                            </span>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <h5>User <span class="text-danger"></span></h5>
                                    <div class="controls">
                                        <select name="user_id" id="user_id" class="form-control select2 " value="">
                                            <option value="">Select user</option>
                                            @foreach ($data as $val)
                                                <option value="{{ $val->id }}">{{ $val->email }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <h5>Start Date <span class="text-danger"></span></h5>
                                    <div class="controls">
                                        <input type="date" name="start_date" id="start_date"
                                            class="form-control datepicker-autoclose" placeholder="Please select start date"
                                            value="">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <h5>End Date <span class="text-danger"></span></h5>
                                    <div class="controls">
                                        <input type="date" name="end_date" id="end_date"
                                            class="form-control datepicker-autoclose" placeholder="Please select end date"
                                            value="">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="text-left" style="
margin-left: 15px;
">
                                    <button type="text" id="btnFiterSubmitSearch" class="btn btn-info">Submit
                                    </button>
                                </div>
                            </div>
                        </header>
                        <div class="panel-body">
                            <table id="datatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Invoice #</th>
                                        <th>Product Amount</th>
                                        <th>Product Vat</th>
                                        <th>Total Product Amount</th>
                                        <th>Courier Amount</th>
                                        <th>Courier Vat</th>
                                        <th>Total Courier</th>
                                        <th>Grand Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="odd">
                                        <td valign="top" colspan="6" class="dataTables_empty">No data available in
                                            table
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Date</th>
                                        <th>Invoice #</th>
                                        <th>Product Amount</th>
                                        <th>Product Vat</th>
                                        <th>Total Product Amount</th>
                                        <th> Courier Amount</th>
                                        <th>Courier Vat</th>
                                        <th>Total Courier</th>
                                        <th>Grand Total</th>
                                        <th>Status</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="//cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script>
        var upload_url = '{{ asset('uploads') }}';

        var url = window.location.href;

        var table;
        var customerName;
        var companyName;
        var customerPhone;
        var customerEmail;
        var customerAddress;
        var pdfMessageTop;

        $(document).ready(function() {


            $('#btnFiterSubmitSearch').click(function() {
                $('#datatable').DataTable().draw(true);
            });
            table = $('#datatable').DataTable({
                dom: 'lBfrtip',
                buttons: [{
                    text: '<span data-toggle="tooltip" title="Export CSV"><i class="fa fa-lg fa-file-text-o"></i> CSV</span>',
                    extend: 'csv',
                    className: 'btn btn-sm btn-round btn-success',
                    title: 'Purchase History',
                    extension: '.csv',
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                }, {
                    text: '<span data-toggle="tooltip" title="Print"><i class="fa fa-lg fa-print"></i> Print</span>',
                    extend: 'print',
                    title: '',
                    className: 'btn btn-sm btn-round btn-info',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    },
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend('<div class="invoice-to" style="with:1000px;">\
                                                    <div class="" style="width: 50%; float:left;">\
                                                        <h4></h4>\
                                                        <p>\
                                                            <b>Company Name:</b> Badray ltd<br>\
                                                            <b>Phone:</b> 0141 3280103<br>\
                                                            <b>Email:</b> aqsinternational@badrayltd.co.uk<br>\
                                                            <b>Address:</b> 4 Gordon Avenue G52 4TG<br/> Hillington Glasgow</p>\
                                                    </div>\
                                                    <div class="" style="width: 50%; float:right;">\
                                                        <h4></h4>\
                                                        <p>\
                                                            <b>Name:</b> ' + customerName + '<br>\
                                                            <b>Shop Name:</b> ' + companyName + '<br>\
                                                            <b>Phone:</b> ' + customerPhone + '<br>\
                                                            <b>Email:</b> ' + customerEmail + '<br>\
                                                            <b>Address:</b><span style="font-size:10px;"> ' +
                                customerAddress + '</span></p>\
                                                    </div>\
                                                    </div><br/><br/><br/>');

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }, {
                    extend: 'pdfHtml5',
                    text: '<span data-toggle="tooltip" title="Export PDF"><i class="fa fa-lg fa-file-pdf-o"></i> PDF</span>',
                    className: 'btn btn-sm btn-round btn-danger',
                    title: 'Purchase History',
                    extension: '.pdf',
                    footer: true,
                    header: true,
                    //messageTop: pdfMessageTop, 
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    },
                    customize: function(doc) {
                        doc.content.splice(1, 0, {
                            //text: 'Admin Details:                                                                 ' +
                              //  'Customer Details:'
                        });
                        doc.content.splice(2, 0, {
                            text: pdfMessageTop
                        });
                        doc.content.splice(3, 0, {
                            text: '_____________________________________________________________________________________________________________'
                        });

                        doc['header'] = (function(page, pages) {

                            if (page == 1) {
                                return {
                                    columns: [{
                                            image: "data:image/jpeg;base64,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", // image here working on decode base64
                                            width: 100
                                        },
                                        //'This is your left footer column',
                                        {
                                            // This is the right column
                                        //    top: '10px',
                                          //  alignment: 'right',
                                           // text: `Invoice No: INV-` + Math.floor( 1000 + Math.random() * 9000)
                                        }
                                    ],
                                    margin: [10, 10, 10, 10]
                                }
                            }
                        });
                    }
                }],
                processing: true,
                serverSide: true,
                ordering: true,
                responsive: true,
                pageLength: -1,
                ajax: {
                    url: url,
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.user_id = $('#user_id option:selected').val()
                    }
                },

                columns: [
                    {
                        data: 'date',
                        width: "16%"
                    },
                    {
                        data: 'paypal_id'
                    },
                    {
                        data: 'sum_amount'
                    },
                    {
                        data: 'product_vat'
                    },
                    {
                        data: 'total_product_amount'
                    },
                    {
                        data: 'courier_amount'
                    },
                    {
                        data: 'courier_vat'
                    },
                    {
                        data: 'total_vat_amount'
                    },
                    {
                        data: 'grand_total'
                    },
                    {
                        data: 'tran_status'
                    }
                ],
                order: [],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Remove the formatting to get integer data for summation
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i ===
                            'number' ? i : 0;
                    };

                    var productAmount = 0;
                    var productVat = 0;
                    var totalProductAmount = 0;
                    var courierAmount = 0;
                    var courierVat = 0;
                    var totalCourier = 0;
                    var pageTotal = 0;

                    var arr = new Array();
                    $.each(data, function(key, obj) {

                        $.each(obj, function(k, value) {
                            if (k == 'user') {
                                customerName = value.first_name + ' ' + value.last_name;
                                companyName = value.company_name;
                                customerPhone = value.phone;
                                customerEmail = value.email;
                                customerAddress = value.address;

                                pdfMessageTop =
                                    'Company Name: Badray ltd                                          Name: ' +
                                    customerName +
                                    '                                                                                              ' +
                                    'Phone: 0141 3280103                                                   Shop Name: ' +
                                    companyName +
                                    '                                                                          Email: aqsinternational@badrayltd.co.uk' +
                                    '                  Phone: ' + customerPhone +
                                    '                                                                     Address: 4 Gordon Avenue G52 4TG' +
                                    '                          Email: ' +
                                    customerEmail +
                                    '                                             Hillington Glasgow' +
                                    '                                                         Address: ' +
                                    customerAddress;
                                return false;
                            }
                        });
                        
                        if (obj.is_refunded != 1) {
                            productAmount = productAmount + intVal(obj.sum_amount);
                            productVat = productVat + intVal(obj.product_vat);
                            totalProductAmount = totalProductAmount + intVal(obj.total_product_amount);
                            courierAmount = courierAmount + intVal(obj.courier_amount);
                            courierVat = courierVat + intVal(obj.courier_vat);
                            totalCourier = totalCourier + intVal(obj.total_vat_amount);
                            pageTotal = pageTotal + intVal(obj.grand_total);
                        }
                    });

                    // Update footer
                    $(api.column(2).footer()).html('<b>Total Amount: £' + productAmount.toFixed(2) + '</b>');
                    $(api.column(3).footer()).html('<b>Total Vat: £' + productVat.toFixed(2) + '</b>');
                    $(api.column(4).footer()).html('<b>Total Product Amount: £' + totalProductAmount.toFixed(2) + '</b>');
                    $(api.column(5).footer()).html('<b>Total Courier Amount: £' + courierAmount.toFixed(2) + '</b>');
                    $(api.column(6).footer()).html('<b>Total Courier VAT: £' + courierVat.toFixed(2) + '</b>');
                    $(api.column(7).footer()).html('<b>Total Courier: £' + totalCourier.toFixed(2) + '</b>');
                    $(api.column(8).footer()).html('<b>Total: £' + pageTotal.toFixed(2) + '</b>');
                },
            });
        })

        function toFixedNoRound (number, prec = 1) {
            const factor = Math.pow(10, prec);
            return Math.floor(number * factor) / factor;
        }
        /*child*/
    </script>
@endsection
