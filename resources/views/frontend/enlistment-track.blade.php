@extends('frontend.layouts.master')
@section('css')
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

    <!-- Necessary Css files-->
    <link rel="stylesheet" href="{!! asset('public/frontend/navy_timeline/assets/css/flaticon.css') !!}">
    <!-- main css -->
    <link rel="stylesheet" href="{!! asset('public/frontend/navy_timeline/assets/css/navy_timeline.css') !!}">
    <style>
        .st-tab .loginArea {
            padding: 0 !important;
        }
    </style>
@stop

@section('content')

      @include('layouts.flash')

    <!-- page-header -->
    <div id="inner-page-header" class="page-header position-relative">
        <div class="container">
            <div class="row">
                <!-- page caption -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                    <div class="page-caption">
                        <h1 class="page-title">Enlistment Track</h1>
                    </div>
                </div>
                <!-- /.page caption -->
            </div>
        </div>
        <!-- page caption -->
        <div class="page-breadcrumb position-relative">
            <div class="container">
                <div class="row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{!! URL::to($a.$b.'login') !!}" class="breadcrumb-link">Supplier Login</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Enlistment Track</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- page breadcrumb -->
    </div>
    <!-- /.page-header -->

    <!-- couple-sign in -->
    <section class="couple-bg-image pb-5 sectionBg">
        <div class="couple-form">
            <div class="container">
                <div class="row ">

                    @if (Auth::guard('supplier')->check())
                        <div class="col-lg-3 col-md-3 col-3">
                            @include('frontend/homeinc/menu')
                        </div>
                    @endif

                    <div class="col-lg-9 col-md-9 col-sm-12 col-9">
                        <!--st-tab-->
                        <div class="st-tab">
                            <div class="container">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">

                                        <div class="loginArea">
                                            <!-- form-heading-title -->

                                                @if (Auth::guard('supplier')->check())

                                                <div class="navy_timeline">

                                                        <ul class="text-center navy_timeline_area d-flex justify-content-center align-items-center">
                                                            <li class="position-relative"><img src="{!! asset('public/frontend/navy_timeline/assets/images/human.png') !!}" alt="" class="img-fluid"></li>
                                                            <li class="position-relative"><p class="@if ( $enlistment!=0) btn-success @else btn-gray @endif">Form Purchase</p></li>
                                                            <li class="position-relative"><p class="@if ( $sell_form!=0) btn-success @else btn-gray @endif">Data Submit</p> </li>
                                                            <li class="position-relative"><p class="@if ( $npm_dni_approval!=0) btn-success @else btn-gray @endif">Verification</p> </li>
                                                            <li class="position-relative"><p class="@if ( $dns_approval!=0) btn-success @else btn-gray @endif">DNS Verification</p> </li>
                                                            <li class="position-relative"><p class="@if ( $approved!=0) btn-success @else btn-gray @endif"> <i class="flaticon-shop"></i></p></li>
                                                        </ul>

                                                </div>

                                                @endif


                                                </div><!--row-->

                                            <!--/.form -->
                                        </div><!--/.loginArea-->

                                </div>
                            </div>
                        </div><!--/.st-tab-->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.couple-sign up -->
    </section>
@stop