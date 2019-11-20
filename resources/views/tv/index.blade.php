<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>TV</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,300i,400,400i,500,500i,700,700i,900,900i"
          rel="stylesheet">


    <!-- Necessary Css files-->
    <link rel="stylesheet" href="{{ asset('public/tv/assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('public/tv/assets/css/slick.css')}}">
    <link rel="stylesheet" href="{{ asset('public/tv/assets/css/slick-theme.css')}}">
    <link rel="stylesheet" href="{{ asset('public/tv/assets/css/bootstrap.min.css')}}">
    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('public/tv/assets/css/main.css')}}">
    <!-- Responsive css -->
    <link rel="stylesheet" href="{{ asset('public/tv/assets/css/responsive.css')}}">

</head>
<body style="background-color: #1c3586; overflow-y:hidden;">
<div class="fullscreen_slider">
    <div class="container-fluid">
        <div class="Navy_fullscreen_slider">

            @if (!$opening_tenders->isEmpty())
             <?php
                $tender_count=count($opening_tenders)/5;

                $i =0;
                ?>
            @for($k=0;$k<ceil($tender_count);$k++)
            <div class="fullscreen_slider_content">
                <div class="fullscreen_slider_txt text-center text-uppercase">
                    <h2>Date: <span>{!! date('Y-m-d') !!}</span></h2>
                   <h2> Opening Tenders </h2>
                </div>
                <div class="table-responsive">

                        <table class="table table-bordered">
                            <thead>
                            <tr class="center">
                                <th class="text-center" width="5%">SL#</th>
                                <th class="text-center">{{'Tender Title'}}</th>
                                <th class="text-center">{{'Tender Number' }}</th>
                                <th class="text-center">{{'Quantity' }}</th>
                                <th class="text-center">{{'Opening Date'}}</th>

                            </tr>
                            </thead>
                            <tbody>

                                <?php

                                $l = $i;
                                $limit=5;
                                ?>
                                @foreach(array_slice($opening_tenders->toArray(), $i, $limit) as $rt)

                                    <tr>
                                        <td class="text-center">
                                            {!! $l=$l+1 !!}
                                        </td>

                                        <td>
                                            {!! $rt->tender_title !!}
                                        </td>
                                        <td>
                                            {!! $rt->tender_number !!}
                                        </td>
                                        <td>
                                            {!! $rt->quantity !!}   {!! $rt->deno !!}
                                        </td>
                                        <td>
                                            {!! date('d.m.Y',strtotime($rt->tender_opening_date)) !!}
                                        </td>
                                    </tr>
                                  <?php
                                  $i=$i+1;
                                  ?>
                                @endforeach


                            </tbody>
                        </table>

                </div><!--/.table-responsive-->
            </div><!--/.fullscreen_slider_content-->

            @endfor
            @endif


                @if (!$po->isEmpty())

                    <?php
                    $tender_count=count($po)/5;

                    $i =0;
                    ?>
                    @for($k=0;$k<ceil($tender_count);$k++)

                    <div class="fullscreen_slider_content">
                        <div class="fullscreen_slider_txt text-center text-uppercase">
                            <h2>Date: <span>{!! date('Y-m-d') !!}</span></h2>
                             <h2> Purchase Orders  (Last 7 Days)</h2>
                        </div>
                        <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr class="center">
                                        <th class="text-center" width="5%">SL#</th>
                                        <th class="text-center">{{'Tender Title'}}</th>
                                        <th class="text-center">{{'Tender Number'}}</th>
                                        <th class="text-center">{{'PO Number'}}</th>
                                        <th class="text-center">{{'Total Quantity'}}</th>

                                    </tr>
                                    </thead>
                                    <tbody>


                                    <?php

                                    $l = $i;
                                    $limit=5;
                                    ?>
                                        @foreach(array_slice($po->toArray(), $i, $limit) as $sc)

                                            <tr>
                                                <td class="text-center">
                                                    {!! $l=$l+1 !!}
                                                </td>
                                                <td>{!! $sc['tender_title'] !!}</td>
                                                <td>{!! $sc['tender_number'] !!}</td>
                                                <td>{!! $sc['po_number'] !!}</td>
                                                <td>{!! $sc['quantity'] !!}</td>
                                            </tr>
                                            <?php
                                            $i=$i+1;
                                            ?>
                                        @endforeach


                                    </tbody>
                                </table>

                        </div><!--/.table-responsive-->
                    </div><!--/.fullscreen_slider_content-->
                        @endfor
                @endif

                @if (!$billing->isEmpty())
                    <?php
                    $tender_count=count($billing)/5;

                    $i =0;
                    ?>
                        @for($k=0;$k<ceil($tender_count);$k++)

                        <div class="fullscreen_slider_content">
                        <div class="fullscreen_slider_txt text-center text-uppercase">
                            <h2>Date: <span>{!! date('Y-m-d') !!}</span></h2>
                           <h2> Billing Tenders  (Last 7 Days)</h2>
                        </div>
                        <div class="table-responsive">

                                <table class="table table-bordered">
                                    <thead>
                                    <tr class="center">
                                        <th class="text-center" width="5%">SL#</th>
                                        <th class="text-center">{{'CR Number'}}</th>
                                        <th class="text-center">{{'Tender Title'}}</th>
                                        <th class="text-center">{{'Supplier Name'}}</th>
                                        <th class="text-center">{{'Generate Date'}}</th>
                                        <th class="text-center">{{'Total Quantity'}}</th>

                                    </tr>
                                    </thead>
                                    <tbody>


                                    <?php
                                    $l = $i;
                                    $limit=5;
                                    ?>
                                        @foreach(array_slice($billing->toArray(), $i, $limit) as $sc)

                                            <tr>
                                                <td class="text-center">
                                                    {!! $l=$l+1 !!}
                                                </td>
                                                <td>{!! $sc['cr_number'] !!}</td>
                                                <td>{!! $sc['tender_title'] !!}</td>
                                                <td>{!! $sc['company_name'] !!}</td>
                                                <td>@if(!empty($sc['top_date'])) {!! date('Y-m-d', strtotime($sc['top_date'])) !!} @endif</td>
                                                <td>{!! $sc['cr_receive_qty'] !!}</td>
                                            </tr>
                                            <?php
                                            $i=$i+1;
                                            ?>
                                        @endforeach


                                    </tbody>
                                </table>



                        </div><!--/.table-responsive-->
                    </div><!--/.fullscreen_slider_content-->
                        @endfor
                @endif

        </div>
    </div>
</div>
<!-- Footer start -->
<footer class="footer position-absolute">
    <div class="container text-center">
        <p>Powered By <a href="http://issl.com.bd/" target="_blank"><strong>Impel Service &amp; Solutions Limited</strong></a></p>
    </div>
</footer><!-- footer-->
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js ')}}, then Bootstrap JS -->
<script src="{{ asset('public/tv/assets/js/jquery.min.js ')}}"></script>
<script src="{{ asset('public/tv/assets/js/popper.min.js ')}}" ></script>
<script src="{{ asset('public/tv/assets/js/bootstrap.min.js ')}}"></script>
<script type="text/javascript" src="{{ asset('public/tv/assets/js/wow.min.js ')}}"></script>
<script type="text/javascript" src="{{ asset('public/tv/assets/js/slick.min.js ')}}"></script>


<script>
    $(document).ready(function(){
        "use strict";

        //WOW Js
        new WOW().init();


        $('.Navy_fullscreen_slider').slick({
            autoplay:true,
            autoplaySpeed: 7000,
            arrows: false,
            speed: 500,
            fade: true,
        });
    });

</script>

</body>
</html>
