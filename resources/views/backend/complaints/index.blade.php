@extends('layout.base')
@section("custom_css")

<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="css/app.min.css" rel="stylesheet" type="text/css" />
<link href="/backend/assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="/backend/assets/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="/backend/assets/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="/backend/assets/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@stop



@section('content')
<div class="card" style="margin-top: 10px;">
    <div id="wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                
                    <div class="card-body">
                    <div>
                    @if( \Session::has('success'))
                        <div class="alert alert-success">
                            {!! \Session::get('success') !!}
                        </div>
                    @endif
                    </div>
                        <h4 class="header-title mt-0 mb-1">Complaints Submitted</h4>
                        <p class="sub-header">
                            This is the list of all complaints submitted:
                        </p>
                        @isset($complaints)
                        @if (count($complaints) > 0)
                                                    <table id="basic-datatable" class="table dt-responsive">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th style="min-height: 7000;">Message</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>                         
                            @foreach($complaints as $response)
                                <tr>
                                    <td>{{ $response->_id}}</td>
                                    <td>{{ $response->name}}</td>
                                    <td>{{ $response->email}}</td>
                                    <td>{{ $response->message}}
                                    </td>
                                    <td>{{ $response->status}}</td>
                                    <td>{{ $response->date}}</td>
                                    <td>
                                    <form action="{{ route('complaint.destroy', $response->_id) }}" method="POST">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button class="btn btn-danger">Delete</button>
                                    </form></td>
                                    <!-- <td><a href="{{ route('complaint.destroy', $response->_id) }}" class="btn btn-danger "> -->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <p>You don't have any complaint in your log</p>
                        @endif
                        @endisset


                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row-->
    </div>
    <!-- content -->


    <!-- <div class="row"> -->
    <!-- <div class="col-12"> -->



    <script type="text/javascript">
        < script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" >

    </script>

    </script>
    @endsection


    @section("javascript")
    <script src="/backend/assets/js/vendor.min.js"></script>

    <!-- datatable js -->
    <script src="/backend/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="/backend/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="/backend/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="/backend/assets/libs/datatables/responsive.bootstrap4.min.js"></script>

    <script src="/backend/assets/libs/datatables/dataTables.buttons.min.js"></script>
    <script src="/backend/assets/libs/datatables/buttons.bootstrap4.min.js"></script>
    <script src="/backend/assets/libs/datatables/buttons.html5.min.js"></script>
    <script src="/backend/assets/libs/datatables/buttons.flash.min.js"></script>
    <script src="/backend/assets/libs/datatables/buttons.print.min.js"></script>

    <script src="/backend/assets/libs/datatables/dataTables.keyTable.min.js"></script>
    <script src="/backend/assets/libs/datatables/dataTables.select.min.js"></script>

    <!-- Datatables init -->
    <script src="/backend/assets/js/pages/datatables.init.js"></script>

    <!-- App js -->
    <script src="/backend/assets/js/app.min.js"></script>
    <script type="text/javascript">
        // $(document).ready(function () {
        //     $("button").click(function () {

        //         $.ajax({
        //             type: 'GET',
        //             url: '/backend/1123?id=<?php // echo ($person['
        //             _id ']); ?>&&status=<?php // echo ($person['
        //             status ']); ?>&&message=<?php //echo ($person['
        //             message ']); ?>',
        //             success: function (data) {
        //                 alert(data);
        //                 $("p").text(data);

        //             }
        //         });
        //     });
        // });

        // $(document).ready(function () {
        //     $("button1").click(function () {

        //         $.ajax({
        //             type: 'GET',
        //             url: '/backend/2f4k7e34o?id=<?php //echo ($person['
        //             _id ']); ?>',
        //             success: function (data) {
        //                 alert(data);
        //                 $("p").text(data);

        //             }
        //         });
        //     });
        // });

    </script>


    @stop
