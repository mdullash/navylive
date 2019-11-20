<!DOCTYPE html>
<html lang="en">

<?php
    $navystg = \App\Settings::find(1);
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bangladesh Navy</title>

    <!-- Bootstrap -->
    <link href="{!! asset('public/frontend/css/bootstrap.min.css')!!}" rel="stylesheet">

    <link href="{!! asset('public/frontend/css/dataTables.bootstrap4.min.css')!!}" rel="stylesheet">
    <link href="{!! asset('public/frontend/css/responsive.bootstrap4.min.css')!!}" rel="stylesheet">
    <link href="{!! asset('public/frontend/css/fixedHeader.bootstrap4.min.css')!!}" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

    <link rel="stylesheet" href="{{ url('public/vendor/fontawesome/css/font-awesome.css') }}">
    
    <!-- FontAwesome icon -->
    <link href="{!! asset('public/frontend/fontawesome/css/fontawesome-all.css')!!}" rel="stylesheet">
    <!-- Fontello icon -->
    <link href="{!! asset('public/frontend/fontello/css/fontello.css')!!}" rel="stylesheet">
    <!-- OwlCarosuel CSS -->
    <link href="{!! asset('public/frontend/css/owl.carousel.css')!!}" type="text/css" rel="stylesheet">
    <link href="{!! asset('public/frontend/css/owl.theme.default.css')!!}" type="text/css" rel="stylesheet">
    <link href="{!! asset('public/frontend/css/jquery-ui.css')!!}" type="text/css" rel="stylesheet">
    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/x-icon" href="{!! asset($navystg->favicon) !!}">
    <!-- Style CSS -->
    <link href="{!! asset('public/frontend/css/jquery.jConveyorTicker.min.css')!!}" rel="stylesheet">
    <link href="{!! asset('public/frontend/css/style.css')!!}" rel="stylesheet">
    <link href="{!! asset('public/css/issl_main.css')!!}" rel="stylesheet">
    <link href="{!! asset('public/frontend/css/responsive.css')!!}" rel="stylesheet">


    <link rel="stylesheet" href="{!! url('public/css/bootstrapValidator.min.css') !!}">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>-->
    @yield('css')



    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <!-- /.tiny-footer-section -->
    <a href="javascript:" id="return-to-top"><i class="fa fa-angle-up"></i></a>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{!! asset('public/frontend/js/jquery.min.js')!!}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{!! asset('public/frontend/js/bootstrap.min.js')!!}"></script>
    <!-- Bootstrap Datatable-->
    <script src="{!! asset('public/frontend/js/jquery.dataTables.min.js')!!}"></script>
    <script src="{!! asset('public/frontend/js/dataTables.bootstrap4.min.js')!!}"></script>
    <script src="{!! asset('public/frontend/js/dataTables.fixedHeader.min.js')!!}"></script>
    <script src="{!! asset('public/frontend/js/datatables.min.js')!!}"></script>
    <script src="{!! asset('public/frontend/js/dataTables.responsive.min.js')!!}"></script>
    <script src="{!! asset('public/frontend/js/responsive.bootstrap4.min.js')!!}"></script>

    <script src="{!! asset('public/frontend/js/menumaker.min.js')!!}"></script>
    <script src="{!! asset('public/frontend/js/jquery-ui.js')!!}"></script>
    <!-- owl-carousel js -->
    <script src="{!! asset('public/frontend/js/owl.carousel.min.js')!!}"></script>
    <!-- nice-select js -->
    <!-- <script src="{!! asset('public/frontend/js/jquery.nice-select.min.js')!!}"></script> -->
    <script src="{!! asset('public/frontend/js/fastclick.js')!!}"></script>
    <!-- <script src="{!! asset('public/frontend/js/jquery.tickerNews.min.js')!!}"></script> -->
    <script src="{!! asset('public/frontend/js/jquery.jConveyorTicker.min.js')!!}"></script>
    <script src="{!! asset('public/js/jquery.scrollUp.min.js')!!}"></script>

    <script src="{!! asset('public/frontend/js/custom-script.js')!!}"></script>
    <script src="{!! asset('public/frontend/js/return-to-top.js')!!}"></script>

    @yield('js')
    <![endif]-->
</head>

