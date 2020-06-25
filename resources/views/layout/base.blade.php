<!DOCTYPE html>
<html lang="en">


<head>
        <meta charset="utf-8" />
        <title>mycustomer - Admin & Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="/backend/assets/images/favicon.ico">

        <!-- plugins -->
        <link href="/backend/assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css" />

        <!-- App css -->
    <link href="{{asset('backend/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('backend/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('backend/assets/css/app.min.css')}}" rel="stylesheet" type="text/css">



    <!-- Other Style CSS -->
        @yield('custom_css')


</head>

<body>

        <!-- Begin page -->
        <div id="wrapper">
            @include('partials.bsidebar')

    <!--====================  heaer area ====================-->
            @include('partials.bheader')
    <!--====================  End of heaer area  ====================-->
    <div class="content-page">
        <div class="content">

            @yield('content')

        </div>

    <!--====================  footer area ====================-->
    @include('partials.bfooter')
    <!--====================  End of footer area  ====================-->
    </div>



        </div>

    <!-- JS
============================================ -->

   <!-- Vendor js -->
   <script src="assets/js/vendor.min.js"></script>

   <!-- optional plugins -->
   <script src="/backend/assets/libs/moment/moment.min.js"></script>
   <script src="/backend/assets/libs/apexcharts/apexcharts.min.js"></script>
   <script src="/backend/assets/libs/flatpickr/flatpickr.min.js"></script>

   <!-- page js -->
   <script src="/backend/assets/js/pages/dashboard.init.js"></script>

   <!-- App js -->
   <script src="/backend/assets/js/app.min.js"></script>



    @yield('javascript')
</body>

</html>
