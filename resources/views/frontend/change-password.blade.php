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
                        <h1 class="page-title">Change Password</h1>
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

                            <li class="breadcrumb-item active text-white" aria-current="page">Change Password</li>
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



                                        <div class="" style="background-color: #fff; padding: 10px;">
                                            <!-- form-heading-title -->
                                            @if (Auth::guard('supplier')->check())
                                                <form id="form-change-password" role="form" method="POST" action="{{ url($a.$b.'supplier-change-password') }}" novalidate class="form-horizontal">

                                                  <div class="col-md-12">

                                                    <div class="row">
                                                        <label for="current-password" class="col-md-4 control-label">Current Password</label>
                                                    <div class="col-md-8">
                                                      <div class="form-group">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}"> 
                                                        <input type="password" class="form-control" id="current-password" name="current_password" placeholder="Current Password" required>
                                                      </div>
                                                    </div>

                                                    <label for="password" class="col-md-4 control-label">New Password</label>
                                                    <div class="col-md-8">
                                                      <div class="form-group">
                                                        <input type="password" class="form-control" id="password" name="new_password" placeholder="New Password" required>
                                                      </div>
                                                    </div>

                                                    <label for="password_confirmation" class="col-md-4 control-label">Re-enter Password</label>
                                                    <div class="col-md-8">
                                                      <div class="form-group">
                                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Re-enter Password" required>
                                                      </div>
                                                    </div>

                                                    <div class="col-md-12" style="text-align: right;">
                                                      <button type="submit" class="btn btn-primary">Submit</button>
                                                  </div>

                                                    </div>

                                                  </div>
                                                </form>
                                              @endif
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