@extends('frontend.layouts.master')
@section('content')

    @include('layouts.flash')

    <!-- page-header -->
    <div id="inner-page-header" class="page-header position-relative">
        <div class="container">
            <div class="row">
                <!-- page caption -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                    <div class="page-caption">
                        <h1 class="page-title">Supplier Login</h1>
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
                            <li class="breadcrumb-item"><a href="{!! URL::to('/').'/'.$a.$b !!}" class="breadcrumb-link">Home</a></li>

                            <li class="breadcrumb-item active text-white" aria-current="page">Supplier Login</li>
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
                        <ul class="list-group">
                            <li class="list-group-item @if(Request::segment(3)=='dashboard')active @endif" ><a href="{!! URL::to($a.$b.'dashboard') !!}" @if(Request::segment(3)=='dashboard') style="color:#fff;" @endif>Dashboard</a></li>
                            <li class="list-group-item @if(Request::segment(3)=='enlistment-track')active @endif" ><a href="{!! URL::to($a.$b.'enlistment-track') !!}" @if(Request::segment(3)=='enlistment-track') style="color:#fff;" @endif>Enlistment Track</a></li>
                            <li class="list-group-item @if(Request::segment(3)=='tender-participant-status')active @endif"><a href="{!! URL::to($a.$b.'tender-participant-status') !!}" @if(Request::segment(3)=='tender-participant-status') style="color:#fff;" @endif>Tender Participant Status</a></li>
                            <li class="list-group-item @if(Request::segment(3)=='evaluation-report')active @endif" ><a href="{!! URL::to($a.$b.'evaluation-report') !!}" @if(Request::segment(3)=='evaluation-report') style="color:#fff;" @endif>Evaluation Report</a></li>
                            <li class="list-group-item"><a href="{!! url($a.$b.'logout') !!}">Logout</a></li>
                        </ul>
                    </div>
                    @endif

                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <!--st-tab-->
                        <div class="st-tab">
                            <div class="container">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 offset-md-3 offset-xl-3 offset-lg-3">



                                        <div class="loginArea">
                                            <!-- form-heading-title -->

                                            @if (Auth::guard('supplier')->check())
                                                You are logged in.
                                            @else
                                                <h3>Supplier Login</h3>
                                                <!-- /.form-heading-title -->
                                                <!-- register-form -->
                                            {{ Form::open(array('role' => 'form', 'url' => $a.$b.'supplier-login', 'files'=> true, 'class' => 'form-horizontal registration1', 'id'=>'registration1')) }}
                                            <!-- form -->

                                                <div class="row">
                                                    <!-- Company Name-->
                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                                                        <!-- Text input-->
                                                        <div class="form-group">
                                                            <label class="control-label sr-only" for="usermail"></label>
                                                            <input id="usermail" type="email" name="email" placeholder="Email Address" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <!-- Text input-->
                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                                                        <div class="form-group service-form-group">
                                                            <label class="control-label sr-only" for="passwordlogin"></label>
                                                            <input id="passwordlogin" type="password" name="password" placeholder="Password" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <!--  Buttons -->
                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                                                        <button type="submit" name="singlebutton" class="btn btn-default mt-3">Login</button>
                                                    </div>
                                                    <!-- Button -->
                                                    @endif
                                                </div><!--row-->
                                            {!!   Form::close() !!}
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