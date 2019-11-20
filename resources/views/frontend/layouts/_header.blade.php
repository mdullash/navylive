<?php
  $navystg = \App\Settings::find(1);
?>
<body>

    <!-- Loading Transition -->
    <div id="spinningSquaresG1">
        <div class="loader-title">
            <img src="{!! asset($navystg->logo) !!}">
        </div>
        <div id="spinningSquaresG2">

            <div id="fountainTextG">
                <div id="fountainTextG_1" class="fountainTextG">B</div>
                <div id="fountainTextG_2" class="fountainTextG">A</div>
                <div id="fountainTextG_3" class="fountainTextG">N</div>
                <div id="fountainTextG_4" class="fountainTextG">G</div>
                <div id="fountainTextG_5" class="fountainTextG">L</div>
                <div id="fountainTextG_6" class="fountainTextG">A</div>
                <div id="fountainTextG_7" class="fountainTextG">D</div>
                <div id="fountainTextG_8" class="fountainTextG">E</div>
                <div id="fountainTextG_9" class="fountainTextG">S</div>
                <div id="fountainTextG_10" class="fountainTextG">H &nbsp;</div>


                <div id="fountainTextG_11" class="fountainTextG">N</div>
                <div id="fountainTextG_12" class="fountainTextG">A</div>
                <div id="fountainTextG_13" class="fountainTextG">V</div>
                <div id="fountainTextG_14" class="fountainTextG">Y &nbsp;</div>



                <div id="fountainTextG_15" class="fountainTextG">P</div>
                <div id="fountainTextG_16" class="fountainTextG">R</div>
                <div id="fountainTextG_17" class="fountainTextG">O</div>
                <div id="fountainTextG_18" class="fountainTextG">C</div>
                <div id="fountainTextG_19" class="fountainTextG">U</div>
                <div id="fountainTextG_20" class="fountainTextG">R</div>
                <div id="fountainTextG_21" class="fountainTextG">E</div>
                <div id="fountainTextG_21" class="fountainTextG">M</div>
                <div id="fountainTextG_21" class="fountainTextG">E</div>
                <div id="fountainTextG_21" class="fountainTextG">N</div>
                <div id="fountainTextG_21" class="fountainTextG">T&nbsp;</div>

                <div id="fountainTextG_21" class="fountainTextG">&&nbsp;</div>

                <div id="fountainTextG_15" class="fountainTextG">S</div>
                <div id="fountainTextG_16" class="fountainTextG">U</div>
                <div id="fountainTextG_17" class="fountainTextG">P</div>
                <div id="fountainTextG_18" class="fountainTextG">P</div>
                <div id="fountainTextG_19" class="fountainTextG">L</div>
                <div id="fountainTextG_20" class="fountainTextG">I</div>
                <div id="fountainTextG_21" class="fountainTextG">E</div>
                <div id="fountainTextG_22" class="fountainTextG">R &nbsp;</div>


                <div id="fountainTextG_23" class="fountainTextG">M</div>
                <div id="fountainTextG_24" class="fountainTextG">A</div>
                <div id="fountainTextG_25" class="fountainTextG">N</div>
                <div id="fountainTextG_26" class="fountainTextG">A</div>
                <div id="fountainTextG_27" class="fountainTextG">G</div>
                <div id="fountainTextG_28" class="fountainTextG">E</div>
                <div id="fountainTextG_29" class="fountainTextG">M</div>
                <div id="fountainTextG_30" class="fountainTextG">E</div>
                <div id="fountainTextG_31" class="fountainTextG">N</div>
                <div id="fountainTextG_32" class="fountainTextG">T &nbsp;</div>

                <div id="fountainTextG_33" class="fountainTextG">S</div>
                <div id="fountainTextG_34" class="fountainTextG">Y</div>
                <div id="fountainTextG_35" class="fountainTextG">S</div>
                <div id="fountainTextG_36" class="fountainTextG">T</div>
                <div id="fountainTextG_37" class="fountainTextG">E</div>
                <div id="fountainTextG_38" class="fountainTextG">M</div>
            </div>
        </div>
    </div>
    <!--Preloader End-->

<!-- header-top -->
    <div class="header-top position-relative">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2 col-2 d-xl-block d-lg-block d-md-block p-0 d-none575">
                    <div class="header-text d-flex justify-content-center position-relative">
                        <p class="welcome-text">Flash Notice</p>
                    </div>
                </div>
                <!--news_right-->
                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8 col-9">
                    <div class="news_right">
                        <div class="panel panel-default">
                            <div class="panel-body">

                                <style>
                                    .navy_news_update ul li{
                                        padding: 0 10px;
                                        line-height: 35px;
                                        font-size: 16px;
                                    }
                                </style>
                                <div class="navy_news_update">
                                    <ul>
                                        @foreach($importantNotices as $itc)
                                        <li>
                                            <a href="@if(!empty($itc->upload_file)) {{url('front-notice-brd-pdf/'.base64_encode($itc->id))}} @else {{'javascript:void(0)'}} @endif" target="_blank" class="text-white">{!! $itc->title !!} &nbsp;&nbsp;* *</a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>


                            </div>
                        </div>
                    </div><!--news_right-->
                </div>
				
                <!--header-border-right-->
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2 col-3 p-0">
                    <div class="header-border-right d-flex position-relative">
                        <nav class="navbar navbar-light justify-content-center">                        
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle welcome-text" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {!! $navallocation->name !!} <br> <span><i class="fas fa-angle-down"></i></span>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        @if(!empty($organizationsHead))
                                            @foreach($organizationsHead as $oh)
                                                <!-- <a class="dropdown-item" href="{{URL::to('/').'/'.$oh->zoneAlise.'/'.$oh->alise}}">{!! $oh->name !!}</a> -->
                                                <a class="dropdown-item" href="{!! $oh->external_link !!}" target="_blank">{!! $oh->name !!}</a>
                                            @endforeach
                                        @endif 
                                    </div>
                                </li>
                            </ul>
                        </nav>
                    </div> <!--./header-border-right-->                  
                </div>

            </div>
        </div><!--/.container-->
    </div>
    <!--/.header-top -->

<!-- header -->
    <div class="header">
        <div class="container">
            <div class="row">
                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-3 col-3 tiny12">
                    <!-- header-logo -->
                    <div class="header-logo">
                         <a href="{!! URL::to('/').'/'.$a.$b !!}"><img src="{!! asset($navystg->logo) !!}" alt="Wedding Vendor & Supplier Directory HTML Template "></a>
                    </div>
                    <!-- /.header-logo -->
                </div>
                <div class="col-xl-10 col-lg-10 col-md-9 col-sm-9 col-9 tiny12">
                        <!-- navigations -->
                    <div id="navigation">
                        <ul>
                            <li><a href="{{URL::to('/').'/'.$a.$b}}">Home</a></li>
                            <li><a href="{{URL::to($a.$b.'front-tender/')}}">Tender List</a></li>
                            <li><a href="{{URL::to($a.$b.'front-general-notice/')}}">General Notice</a></li>
                            <li><a href="{{URL::to($a.$b.'front-supplier/')}}">Enlisted Supplier</a></li>
                            <li><a href="{{URL::to($a.$b.'front-po-winner/')}}">PO Awarded</a></li>
                            <li><a href="{!! URL::to($a.$b.'enlistment-track') !!}" >Enlistment Tracking</a></li>
                            <li>
                                <div class="header-btn d-flex">

                                    <a href="{{URL::to($a.$b.'terms-condiition/')}}" class="btn btn-primary btn-sm">Apply for Online Enrollment</a>
                                    @if (Auth::guard('supplier')->check())
                                        <a href="{!! URL::to($a.$b.'dashboard') !!}" class="btn btn-primary btn-sm">My Profile</a>
                                    @else
                                        <a href="{!! url($a.$b.'login') !!}" class="btn btn-primary btn-sm">Login</a>
                                    @endif

                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- /.navigations -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.header -->