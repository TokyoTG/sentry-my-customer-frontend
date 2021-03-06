@extends('layout.base')
@section("custom_css")
<link href="/backend/assets/build/css/intlTelInput.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('/backend/assets/css/transac.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css">
<link rel="stylesheet" href="{{asset('backend/assets/css/store_list.css')}}">
<link href="/backend/assets/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="/backend/assets/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="/backend/assets/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
@stop


@php
$storeData = $response['storeData'];
$transactions = $response['transactions'];
$currency = isset($storeData->store_admin_ref->currencyPreference) ?
                $storeData->store_admin_ref->currencyPreference : null;
@endphp

@php
$totalDept = 0;
$total_interest = 0;
$total_Revenue = 0;
$total_interestRevenue = 0;
$total_Receivables = 0;
$total_interestReceivables = 0;
@endphp

@foreach ($response['storeData']->customers as $transactions)

@foreach ($transactions->transactions as $index => $transaction)

@php
//get for all debts
if ($transaction->type == "debt") {
$eachDept = $transaction->amount;
$totalDept += $eachDept;
$each_interest = $transaction->interest;
$total_interest += $each_interest;
}

//get for all revenues
if ($transaction->type == "paid") {
$eachRevenue = $transaction->amount;
$total_Revenue += $eachRevenue;
$each_interestRevenue = $transaction->interest;
$total_interestRevenue += $each_interestRevenue;
}

//get for all Receivables
if ($transaction->type == "receivables") {
$eachReceivables = $transaction->amount;
$total_Receivables += $eachReceivables;
$each_interestReceivables = $transaction->interest;
$total_interestReceivables += $each_interestReceivables;
}

@endphp
@endforeach
@endforeach


@section('content')

<!-- Start Content-->
@include('partials.alert.message')
<div id="transaction_js">
    {{-- These are also found in the alert.message partial. I had to repeat it for the sake of JS see showAlertMessage() below--}}
</div>
<div class="row page-title">



    <div class="col-md-12">
        @if(Cookie::get('user_role') == 'store_admin')
        <nav aria-label="breadcrumb" class="float-right mt-1">


            <a data-toggle="modal" data-target="#storeDelete" href="" class="btn btn-danger float-right">
                Delete
            </a>
            <a href="{{ route('store.edit', $storeData->_id) }}" class="mr-3 btn btn-primary float-right">
                Edit Store
            </a>
            @endif
            {{-- Modal for delete Store --}}
            <div class="modal fade" id="storeDelete" tabindex="-1" role="dialog" aria-labelledby="storeDeleteLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="storeDeleteLabel">Delete Transaction</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form class="form-horizontal" method="POST" action="{{ route('store.destroy', $storeData->_id) }}">
                            <div class="modal-body">
                                @csrf
                                @method('DELETE')
                                <h6>Are you sure you want to delete this Store</h6>
                            </div>
                            <div class="modal-footer">
                                <div class="">
                                    <button type="submit" class="btn btn-primary mr-3" data-dismiss="modal"><i data-feather="x"></i>
                                        Close</button>
                                    <button type="submit" class="btn btn-danger"><i data-feather="trash-2"></i>
                                        Delete</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <h4 class="mt-2">My Store</h4>
    </div>
</div>

<div class="row mb-4">
    <div class="col-xl-4">
        <div class="card bg-soft-primary">
            <div>
                <div class="row">
                    <div class="col-7">
                        <div class="text-primary p-3">
                            <h5 class="text-primary" id="store-name">{{ ucfirst($storeData->store_name) }}</h5>
                            
                            <ul class="pl-3 mb-0">
                                <li class="py-1">Assistants: {{count( $storeData->assistants )}}</li>
                                <li class="py-1">Customers: {{count( $storeData->customers )}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-5 align-self-end">
                        <img src="/backend/assets/images/profile-img.png" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-8">

        <div class="row">
            <div class="col-sm-4"><a href="{{ route('store_revenue', $storeData->_id) }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <p class="text-muted font-weight-medium">Revenue</p>
                                    <h4 class="mb-0">{{ format_money($total_Revenue, $currency) }}


                                </div>

                                <div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="uil-atm-card font-size-14"></i>
                                    </span>
                                </div>
                            </div>
                            <br>
                            <div class="d-flex">
                                <span class="badge badge-soft-primary font-size-12"> {{ $total_interestRevenue }}%
                                </span> <span class="ml-2 text-truncate text-primary">From previous Month</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-4"><a href="{{ route('store_receivable', $storeData->_id) }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <p class="text-muted font-weight-medium">Receivables</p>
                                    <h4 class="mb-0">{{ format_money($total_Receivables, $currency) }}
                                </div>

                                <div class="avatar-sm align-self-center mini-stat-icon rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="uil-atm-card font-size-14"></i>
                                    </span>
                                </div>
                            </div>
                            <br>
                            <div class="d-flex">
                                <span class="badge badge-soft-primary font-size-12">
                                    {{ $total_interestReceivables }}% </span> <span class="ml-2 text-truncate text-primary">From previous period</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-4">
                <div class="card"><a href="{{ route('store_debt', $storeData->_id) }}">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <p class="text-muted font-weight-medium">Debt</p>
                                    <h4 class="mb-0">{{ format_money($totalDept, $currency) }}

                                </div>

                                <div class="avatar-sm align-self-center mini-stat-icon rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="uil-atm-card font-size-14"></i>
                                    </span>
                                </div>
                            </div>
                            <br>
                            <div class="d-flex">
                                <span class="badge badge-soft-primary font-size-12">{{ $total_interest }}%</span>
                                <span class="ml-2 text-truncate text-primary">From previous Month</span>
                            </div>
                        </div>
                </div>
            </div></a>
        </div>
    </div>
    <!-- end row -->
</div>
</div>

<div class="row mb-4">
    <div class="col-lg-4">
        <div class="card">

            <div class="card-body pl-3 pr-3 padup">
                <div class="text-center">
                    <h6>Choose Business Card</h6>
                </div>


                <div class="row" id="gallery" data-toggle="modal" data-target="#exampleModal">
                    <div class="col-6 col-md-4 col-lg-12">
                        <img class="w-100" src="{{asset('backend/assets/images/card_v2.PNG')}}" data-target=”#carouselExamples” data-slide-to="0">
                    </div>
                    <div class="col-6 col-md-4 col-lg-12">
                        <img class="w-100" src="{{asset('backend/assets/images/card_vv1.PNG')}}" data-target=”#carouselExamples” data-slide-to="1">
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-4 float-sm-left">Transaction Overview {{date('Y')}}</h6>
                <div class="clearfix"></div>
                <div id="transactionchart"></div>
            </div>
        </div>

    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-body">
                                <div class="btn-group float-right">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class='uil uil-file-alt mr-1'></i>Export
                                        <i class="icon"><span data-feather="chevron-down"></span></i></button>
                                    <div class="dropdown-menu">
                                        <button id="ExportReporttoExcel" class="dropdown-item notify-item">
                                            <i data-feather="file" class="icon-dual icon-xs mr-2"></i>
                                            <span>Excel</span>
                                        </button>
                                        <button id="ExportReporttoPdf" class="dropdown-item notify-item">
                                            <i data-feather="file" class="icon-dual icon-xs mr-2"></i>
                                            <span>PDF</span>
                                        </button>
                                    </div>
                                </div>
                                <h4 class="card-title">{{ ucfirst($storeData->store_name) }} Transaction Overview</h4>
                                <br>
                                <div class="table-responsive table-data">
                                        <table id="transactionTable" class="table table-striped table-bordered" style="width:100%">

                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Customer Name </th>
                                                <th>Phone Number </th>
                                                <th>Transaction Type</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th style="display: none">Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($response['storeData']->customers as $transactions)
                                            @foreach ($transactions->transactions as $index => $transaction)
                                            <tr>
                                                <td>{{ $number++ }}</td>
                                                <th>{{$transactions->name}}<span class="co-name">
                                                </th>
                                                <td class="font-light">{{$transactions->phone_number}}</td>
                                                <td>{{$transaction->type}}</td>
                                                <td>{{format_money($transaction->amount, $currency, $currency)}}</td>
                                                <td>
                                                    <label class="switch">
                                                        @if(Cookie::get('user_role') != 'store_assistant') disabled
                                                        <input class="togBtn" type="checkbox" id="togBtn" {{ $transaction->status == true ? 'checked' : '' }} data-id="{{ $transaction->_id }}" data-store="{{ $transaction->store_ref_id }}" data-customer="{{ $transaction->customer_ref_id}}">
                                                        @else
                                                        <input type="checkbox" id="togBtn" {{ $transaction->status == true ? 'checked' : '' }} disabled>
                                                        @endif
                                                        <div class="slider round">
                                                            <span class="on">Paid</span><span class="off">Pending</span>
                                                        </div>
                                                    </label>
                                                    <div id="statusSpiner" class="spinner-border spinner-border-sm text-primary d-none" role="status">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </td>
                                                <td style="display: none">{{ $transaction->status == true ? 'paid' : 'pending' }}</td>
                                                
                                                <td> <a href="{{ route('transaction.show', $transaction->_id.'-'.$transaction->store_ref_id.'-'.$transaction->customer_ref_id) }}" class="btn btn-primary waves-effect waves-light"> View</a>
                                                </td>
                                            </tr>

                                            @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
</div>

<div class="modal fade " tabindex="-1" role="dialog" id="downloadModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose Format</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('download', $storeData->_id)}}" method="post" id="download-form">
                    @csrf
                    <input type="hidden" name="version" class="version">
                    <input type="hidden" name="type">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="format" id="exampleRadios1" value="image" checked>
                        <label class="form-check-label" for="exampleRadios1">
                            Image Format
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="format" id="exampleRadios2" value="pdf">
                        <label class="form-check-label" for="exampleRadios2">
                            PDF Format
                        </label>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button id="download" class="btn btn-success mr-2">
                    <i class="far mr-2 fa-card">
                    </i>Download</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-download-options">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1 role=" dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Available Cards</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Carousel markup goes in the modal body -->

                <div id="carouselExamples" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active" data-version="v2">
                            <img class="d-block w-100" src="{{asset('backend/assets/images/card_v2.PNG')}}">
                        </div>
                        <div class="carousel-item" data-version="v1">
                            <img class="d-block w-100" src="{{asset('backend/assets/images/card_vv1.PNG')}}">
                        </div>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExamples" role="button" data-slide="prev">
                    <span aria-hidden="true" class="text-dark"><i class="fa fa-chevron-left"></i></span>
                    <span class="sr-only" class="text-dark">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExamples" role="button" data-slide="next">
                    <span aria-hidden="true" class="text-dark"><i class="fa fa-chevron-right"></i></span>
                    <span class="sr-only" class="text-dark">Next</span>
                </a>
            </div>
        </div>
        <div class="modal-footer" style="display: none">
          <button type="button" class="btn btn-secondary"  data-dismiss="modal" id="close-light-box">Close</button>
        </div>

    </div>
    <div class="text-center padup">
        <form action="{{route('preview', $storeData->_id)}}" method="post" id="preview-form">
            @csrf
            <input type="hidden" name="version" class="version">
        </form>
        <button data-toggle="modal" data-target="#downloadModal" id="first_download_button" class="btn btn-success mr-2">
            <i class="far mr-2 fa-card">
            </i>Download</button>
        <button id="preview" class="btn btn-primary mr-2">
            <i class="far mr-2 fa-card"></i>
            Preview</button>
    </div>
</div>

@endsection

@section("javascript")
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="/backend/assets/build/js/intlTelInput.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.3.1/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-html5-1.6.2/datatables.min.js">
</script>
<script src="{{ asset('/backend/assets/js/textCounter.js')}}"></script>
<script src="{{ asset('/backend/assets/js/toggleStatus.js')}}"></script>

<script>
   
    $(document).ready(function() {
        let store_name = $("#store-name").text().trim();
        var export_filename = `${store_name} Transactions`;
        $('#transactionTable').DataTable({
            dom: 'frtipB'
            , buttons: [{
                extend: 'excel'
                , className: 'd-none'
                , title: export_filename
            , }, {
                extend: 'pdf'
                , className: 'd-none'
                , title: export_filename
                , extension: '.pdf'
                , exportOptions: {
                    columns: [0, 1, 2, 3, 4,6]
                }
            }]
        });
        $("#ExportReporttoExcel").on("click", function() {
            $('.buttons-excel').trigger('click');
        });
        $("#ExportReporttoPdf").on("click", function() {

            $('.buttons-pdf').trigger('click');
        });
    });
</script>

<script>
    jQuery(function($) {
        const token = "{{Cookie::get('api_token')}}"
        const host = "{{ env('API_URL', 'https://dev.api.customerpay.me') }}";

        $('.togBtn').change(function() {
            $(this).attr("disabled", true);
            $('#statusSpiner').removeClass('d-none');

            var id = $(this).data('id');
            var store = $(this).data('store');
            let _status = $(this).is(':checked') ? 1 : 0;
            let _customer_id = $(this).data('customer');

            $.ajax({
                url: `${host}/transaction/update/${id}`,
                headers: {
                    'x-access-token': token
                },
                data: {
                    store_id: store,
                    status: _status,
                    customer_id: _customer_id,
                },
                type: 'PATCH',
            }).done(response => {
                if (response.success != true) {
                    $(this).prop("checked", !this.checked);
                    $('#error').show();
                    //alert("Oops! something went wrong.");
                    showAlertMessage('danger', 'Oops! something went wrong');
                }
                //alert("Operation Successful.");
                showAlertMessage('success', 'Operation Successful.');
                $(this).removeAttr("disabled")
                $('#statusSpiner').addClass('d-none');
            }).fail(e => {
                $(this).removeAttr("disabled")
                $(this).prop("checked", !this.checked);
                $('#statusSpiner').addClass('d-none');
                showAlertMessage('danger', 'Oops! something went wrong');
               // alert("Oops! something went wrong.");
            });
        });

        function removeAlertMessage() {
            setTimeout(function () {
                $(".alert").remove();
            }, 2000);
        }

        function showAlertMessage(type, message) {
            const alertMessage = ' <div id="transaction_js_alert" class="alert alert-' + type + ' show" role="alert">\n' +
                '                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                '                        <span aria-hidden="true" class="">&times;</span>\n' +
                '                    </button>\n' +
                '                    <strong class="">' + message + '</strong>\n' +
                '                </div>';
            $("#transaction_js").html(alertMessage);
            removeAlertMessage();
        }
    });


    $("#first_download_button").click(function() {
        $("#close-light-box").click();
    })


    $('#preview').click(function() {
        let activeSlide = $(".carousel-item.active");

        let version = activeSlide.data('version');

        $(".version").val(version);
        $("#preview-form").submit();
        // console.log();
    })


    $('#download').click(function() {

        let activeSlide = $(".carousel-item.active");

        let version = activeSlide.data('version');

        $(".version").val(version);
        $("#download-form").submit();
        $("#close-download-options").click();
        // console.log();
    })
</script>

<script>
    $(document).ready(function() {

        // start of transaction charts

        var options = {

            series: [{
                name: 'Transaction',
                data: {{json_encode($chart)}},
            }],
            chart: {
                height: 350,
                type: 'line',
            },
            stroke: {
                width: 7,
                curve: 'smooth'
            },
            xaxis: {
                type: 'text',
                categories: ['JAN', 'FEB', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUG',
                    'SEPT', 'OCT', 'NOV', 'DEC'
                ],
            },


            title: {
                text: '',
                align: 'left',
                style: {
                    fontSize: "16px",
                    color: '#666'
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    gradientToColors: ['#FDD835'],
                    shadeIntensity: 1,
                    type: 'horizontal',
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100, 100, 100]
                },
            },
            markers: {
                size: 4,
                colors: ["#FFA41B"],
                strokeColors: "#fff",
                strokeWidth: 2,
                hover: {
                    size: 7,
                }
            },
            yaxis: {

                title: {
                    text: "{{ ucfirst($storeData->store_name) }}'s Amount",
                },
            }
        };

        var chart = new ApexCharts(document.querySelector("#transactionchart"), options);
        chart.render();


    });
</script>
@stop