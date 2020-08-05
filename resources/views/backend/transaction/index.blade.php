@extends('layout.base')
@section("custom_css")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('/backend/assets/css/store_list.css') }}">
    <link rel="stylesheet" href="{{ asset('/backend/assets/css/transac.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
@stop

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="mb-0 d-flex justify-content-between align-items-center page-title">
                <div class="h6"><i data-feather="file-text" class="icon-dual"></i> Transaction Center</div>
                @if(Cookie::get('user_role') != 'store_assistant')
                    <a href="#" class="btn btn-primary float-right" data-toggle="modal"
                       data-target="#addTransactionModal">
                        New &nbsp;<i class="fa fa-plus my-float"></i>
                    </a>
                    @include('partials.modal.addTransaction')
                @endif
            </div>

            @include('partials.alert.message')
            <div id="transaction_js">
                {{-- These are also found in the alert.message partial. I had to repeat it for the sake of JS see showAlertMessage() below--}}
            </div>

            <div class="card mt-0">
                <div class="card-header">
                    <div class="btn-group dropdown">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
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
                </div>
                <div class="card-body">
                    <div class="table-responsive table-data">
                        <table id="transactionTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Store</th>
                                <th>Amount</th>
                                <th>Interest</th>
                                <th>Total Amount</th>
                                <th>Type</th>
                                <th>Due</th>
                                <th>Created</th>
                                <th>Status</th>
                                <th style="display: none">Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($transactions as $index => $transaction )
                                @php
                                    $currency = isset($transaction->store_admin_ref->currencyPreference) ?
                                                    $transaction->store_admin_ref->currencyPreference : null;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if(is_super_admin())
                                            <a class=""
                                               href="{{ route('store.show', $transaction->store_ref_id->_id) }}">
                                                {{ $transaction->store_ref_id->store_name }}
                                            </a>
                                        @elseif(is_store_admin())
                                            <a class="" href="{{ route('store.show', $transaction->store_ref_id) }}">
                                                {{ $transaction->store_name }}
                                            </a>
                                        @else
                                            {{ $transaction->store_name }}
                                        @endif
                                    </td>
                                    <td>{{ format_money($transaction->amount, $currency) }}</td>
                                    <td>{{ $transaction->interest }} %</td>
                                    <td>{{ format_money($transaction->total_amount, $currency) }} </td>
                                    <td>{{ $transaction->type }}</td>

                                    <td>
                                        @isset($transaction->expected_pay_date)
                                            @if ( \Carbon\Carbon::parse($transaction->expected_pay_date)->isPast())
                                                <span class="badge badge-soft-danger">{{ \Carbon\Carbon::parse($transaction->expected_pay_date)->diffForhumans() }}</span>
                                            @else
                                                @if (\Carbon\Carbon::parse($transaction->expected_pay_date)->isToday())
                                                    <span class="badge badge-soft-warning">{{ \Carbon\Carbon::parse($transaction->expected_pay_date)->diffForhumans() }}</span>
                                                @endif
                                                <span class="badge badge-soft-success">{{ \Carbon\Carbon::parse($transaction->expected_pay_date)->diffForhumans() }}</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td> {{ \Carbon\Carbon::parse($transaction->createdAt)->diffForhumans() }}</td>
                                    <td>
                                        <label class="switch">
                                            @if(Cookie::get('user_role') == 'store_admin')
                                                <input class="togBtn" type="checkbox" id="togBtn"
                                                       {{ $transaction->status == true ? 'checked' : '' }} data-id="{{ $transaction->_id }}"
                                                       data-store="{{ $transaction->store_ref_id }}"
                                                       data-customer="{{ $transaction->customer_ref_id}}">
                                            @elseif(Cookie::get('user_role') != 'super_admin')
                                                <input class="togBtn" type="checkbox" id="togBtn"
                                                       {{ $transaction->status == true ? 'checked' : '' }} data-id="{{ $transaction->_id }}"
                                                       data-store="{{ $transaction->store_ref_id->_id }}"
                                                       data-customer="{{ $transaction->customer_ref_id->_id}}">
                                            @else
                                                <input type="checkbox" id="togBtn"
                                                       {{ $transaction->status == true ? 'checked' : '' }} disabled>
                                            @endif

                                            <div class="slider round">
                                                <span class="on">Paid</span><span class="off">Pending</span>
                                            </div>
                                        </label>
                                        <div id="statusSpiner"
                                             class="spinner-border spinner-border-sm text-primary d-none" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </label>
                                    <div id="statusSpiner" class="spinner-border spinner-border-sm text-primary d-none" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </td>
                                <td style="display: none">{{ $transaction->status == true ? 'paid' : 'pending' }}</td>

                                <td>
                                    @if($transaction->customer_ref_id != null)
                                    @if(is_super_admin())
                                    <a class="btn btn-info btn-small py-1 px-2" href="{{ route('transaction.show', $transaction->_id.'-'.$transaction->store_ref_id->_id.'-' . $transaction->customer_ref_id->_id) }}">
                                        View More
                                    </a>
                                    @else
                                    <a class="btn btn-info btn-small py-1 px-2" href="{{ route('transaction.show', $transaction->_id.'-'.$transaction->store_ref->_id.'-'.$transaction->customer_ref->_id) }}">
                                        View More
                                    </a>
                                    @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("javascript")

    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="/backend/assets/build/js/intlTelInput.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript"
            src="https://cdn.datatables.net/v/bs4/jq-3.3.1/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-html5-1.6.2/datatables.min.js">
    </script>
    <script src="{{ asset('/backend/assets/js/textCounter.js')}}"></script>
    <script src="{{ asset('/backend/assets/js/toggleStatus.js')}}"></script>


<script>
    $(document).ready(function() {
        var export_filename = 'MycustomerTransactions';
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
                    columns: [0, 1, 2, 3, 4, 5, 6,9]
                }
            }]
        });
        $("#ExportReporttoExcel").on("click", function() {
            $('.buttons-excel').trigger('click');
        });
        $("#ExportReporttoPdf").on("click", function() {
            $('.buttons-pdf').trigger('click');
        });
    })
    </script>

    <script>
        jQuery(function ($) {
            const token = "{{Cookie::get('api_token')}}"
            const host = "{{ env('API_URL', 'https://dev.api.customerpay.me') }}";

            $('select[name="store"]').on('change', function () {
                var storeID = $(this).val();
                var host = "{{ env('API_URL', 'https://dev.api.customerpay.me') }}";

                if (storeID) {
                    $('select[name="customer"]').empty();
                    jQuery.ajax({
                        url: host + "/store/" + encodeURI(storeID)
                        , type: "GET"
                        , dataType: "json"
                        , contentType: 'json'
                        , headers: {
                            'x-access-token': token
                        }
                        , success: function (data) {
                            var new_data = data.data.store.customers;
                            var i;
                            new_data.forEach(customer => {
                                $('select[name="customer"]').append('<option value="' +
                                    customer._id + '">' +
                                    customer.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('select[name="store"]').empty();
                }
            });

            $('.togBtn').change(function () {
                $(this).attr("disabled", true);

                var id = $(this).data('id');
                var spiner = $(this).parent("td").find("#statusSpiner");
                var store = $(this).data('store');
                let _status = $(this).is(':checked') ? 1 : 0;
                let _customer_id = $(this).data('customer');

                $('#statusSpiner').removeClass('d-none');

                $.ajax({
                    url: `${host}/transaction/update/${id}`
                    , headers: {
                        'x-access-token': token
                    }
                    , data: {
                        store_id: store
                        , status: _status
                        , customer_id: _customer_id
                        ,
                    }
                    , type: 'PATCH'
                    ,
                }).done(response => {
                    if (response.success != true) {
                        $(this).prop("checked", !this.checked);
                        alert("Oops! something went wrong.");
                    }
                    // alert("Operation Successful.");
                    showAlertMessage('success', 'Operation successful');
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


    </script>
    {{-- @if ( Cookie::get('is_first_time_user') == true) --}}
    <script>
        var transaction_intro_shown = localStorage.getItem('transaction_intro_shown');

        if (!transaction_intro_shown) {

            const tour = new Shepherd.Tour({
                defaults: {
                    classes: "shepherd-theme-arrows"
                }
            });

            tour.addStep("step", {
                text: "Welcome to Transaction Page, here you can record and track your transactions"
                , buttons: [{
                    text: "Next"
                    , action: tour.next
                }]
            });

            // tour.addStep("step2", {
            //     text: "First thing you do is create a store",
            //     attachTo: { element: ".second", on: "right" },
            //     buttons: [
            //         {
            //             text: "Next",
            //             action: tour.next
            //         }
            //     ],
            //     beforeShowPromise: function() {
            //         document.body.className += ' sidebar-enable';
            //         document.getElementById('sidebar-menu').style.height = 'auto';
            //     },
            // });
            tour.start();
            localStorage.setItem('transaction_intro_shown', 1);
        }

    </script>
    {{-- @endif --}}
@stop
