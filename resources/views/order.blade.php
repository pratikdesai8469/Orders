<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Orders</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href="{{asset('css/style.bundle.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
        <!-- Styles -->
        <style>
            .import-form{
                font-size: 14px;
            }
            .span-form{
                font-size: 12px;
            }
            .filter-row{
                padding: 18px 27px;
            }
            .select2-container .select2-selection--single{
                height: 40px !important;
                border: 1px solid #e4e6ef !important;
            }
            .cash-value{
                font-weight: 900;
            }
            .import-form{
                padding: 14px 226px;
            }
        </style>
    </head>
    <body>
        <div class="row">
            <div class="col-lg-12">
                <!--begin::Card-->
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="card-title">
                            <h1>Order Management</h1>
                        </div>
                        <div class="import-form">
                            {{Form::open(['route'=>'order.import','method'=>'post','files'=>'true'])}}
                                <div class="row">
                                    <div class="col-md-7">
                                        {{Form::file('order_sheet',['accept'=>'.csv,.xls,.xlw,.xlsx','required'])}}
                                        <span class="span-form form-text text-muted ml-2">Choose Order Sheet</span>
                                        @if(Session::has('upload_error'))
                                            <span class="span-form form-error text-danger ml-2">{{Session::get('upload_error')}}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-2">
                                        {{Form::submit('Import',['class'=>'btn btn-sm btn-success'])}}
                                    </div>
                                </div>
                            {{Form::close()}}
                        </div>
                    </div>
                    @if($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>	
                            <strong>{{$message}}</strong>
                        </div>
                    @endif
                    {{Form::open(['class'=>'order-filter-form'])}}
                        <div class="row filter-row">
                            <div class="col-lg-3 col-md-9 col-sm-12">
                                <div class="input-group" id="kt_daterangepicker_2">
                                    {{Form::text('plan_exp_date','',['class'=>'form-control order-date','readonly','placeholder'=>'Please select order date'])}}
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="form-text text-muted ml-2">Please select order date</span>
                            </div>
                            <div class="col-md-3">
                                {{Form::select('product_name',$productName,'',['class'=>'form-control product-name searchpicker','multiple'=>'multiple'])}}
                                <span class="form-text text-muted ml-2">Please select product name</span>
                            </div>
                            <div class="col-md-1">
                                <a href="#" class="btn btn-primary order-filter-submit">Submit</a>
                            </div>
                        </div>
                    {{Form::close()}}
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
                            <thead>
                                <tr>
                                    <th>Order Id</th>
                                    <th>Pin Type</th>
                                    <th>Payment Type</th>
                                    <th>Customer Name</th>
                                    <th>Full Address</th>
                                    <th>Order Date</th>					
                                    <th>Price</th>					
                                    <th>Quantity</th>					
                                    <th>Product Name</th>					
                                </tr>
                            </thead>
                        </table>
                        <!--end: Datatable-->
                        <hr>
                        <div class="row mt-5">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                      <h5 class="card-title">Cash</h5>
                                      <h6 class="card-subtitle mb-2 text-muted">Total Amount : <span class="cash-amount cash-value">0₹</span></h6>
                                      <br>
                                      <h6 class="card-subtitle mb-2 text-muted">Total Percentage : <span class="cash-percentage cash-value">0%</span></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                      <h5 class="card-title">Cheque</h5>
                                      <h6 class="card-subtitle mb-2 text-muted">Total Amount : <span class="cheque-amount cash-value">0₹</span></h6>
                                      <br>
                                      <h6 class="card-subtitle mb-2 text-muted">Total Percentage : <span class="cheque-percentage cash-value">0%</span></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                      <h5 class="card-title">Online</h5>
                                      <h6 class="card-subtitle mb-2 text-muted">Total Amount : <span class="online-amount cash-value">0₹</span></h6>
                                      <br>
                                      <h6 class="card-subtitle mb-2 text-muted">Total Percentage : <span class="online-percentage cash-value">0%</span></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                      <h5 class="card-title">Payment Due</h5>
                                      <h6 class="card-subtitle mb-2 text-muted">Total Amount : <span class="paymentDue-amount cash-value">0₹</span></h6>
                                      <br>
                                      <h6 class="card-subtitle mb-2 text-muted">Total Percentage : <span class="paymentDue-percentage cash-value">0%</span></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                      <h5 class="card-title">Web Payment</h5>
                                      <h6 class="card-subtitle mb-2 text-muted">Total Amount : <span class="webPayment-amount cash-value">0₹</span></h6>
                                      <br>
                                      <h6 class="card-subtitle mb-2 text-muted">Total Percentage : <span class="webPayment-percentage cash-value">0%</span></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card-->
            </div>
        </div>
        <script src="{{asset('plugins/global/plugins.bundle.js')}}" type="text/javascript"></script>
        <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js//pages/crud/forms/widgets/bootstrap-daterangepicker.js?v=7.2.6') }}" type="text/javascript"></script>
        <script>
            var product_name = '';
            var date = '';
            var table = '';
            
            function generateDataTable(product_name,date)
            {
                table = $("#kt_datatable").DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                            url: '{!! route('order.data') !!}',
                            data: function(data) {data.product_name=product_name,data.date=date;},
                        },
                    name:'search',
                    drawCallback: function(data){
                        var length_select = $(".dataTables_length");
                        var select = $(".dataTables_length").find("select");
                        select.addClass("tablet__select");
                        var amountData = data.json.amount_data;
                        $.each(amountData,function(key,value){
                            $('.'+key+'-amount').text(value.amount+'₹');
                            $('.'+key+'-percentage').text(value.percentage+'%');
                        });
                    },
                    autoWidth: false,
                    columns: [
                        {data: 'order_id', name: 'order_id'},
                        {data: 'pin_type_id', name: 'pin_type_id'},
                        {data: 'payment_type', name: 'payment_type'},
                        {data: 'customer_name', name: 'customer_name'},
                        {data: 'full_address', name: 'full_address'},
                        {data: 'order_date', name: 'order_date'},
                        {data: 'price', name: 'price'},
                        {data: 'quantity', name: 'quantity'},
                        {data: 'product_name', name: 'product_name'},
                    ]
                });
            }
            
            jQuery(document).ready((function () {
                generateDataTable();
                jQuery(document).on('click','.order-filter-submit',function(){
                    table.destroy();
                    product_name = $('.product-name').val();
                    date = $('.order-date').val();
                    generateDataTable(product_name,date);
                });
                $('.searchpicker').select2();
            }));
        </script>
    </body>
</html>
