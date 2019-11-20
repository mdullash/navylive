<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
            <?php
            $quizBook=\App\Settings::find(1);

            ?>
        <!-- Page title -->
        <title>{!! $quizBook->site_title !!}</title>
         <link rel="shortcut icon" href="{!! asset($quizBook->favicon) !!}">
        <!-- Vendor styles -->
        <link rel="stylesheet" href="{{ url('public/vendor/fontawesome/css/font-awesome.css') }}">
        <link rel="stylesheet" href="{{ url('public/vendor/metisMenu/dist/metisMenu.css') }}">
        <link rel="stylesheet" href="{{ url('public/vendor/animate.css/animate.css') }}">
        <link rel="stylesheet" href=" {{ url('public/vendor/bootstrap/dist/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ url('public/vendor/sweetalert/lib/sweet-alert.css') }}">
        <link rel="stylesheet" href="{{ url('public/vendor/select2-3.5.2/select2.css') }}">
        <link rel="stylesheet" href="{{ url('public/vendor/select2-bootstrap/select2-bootstrap.css')}}">
        <link rel="stylesheet" href="{{ url('public/vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css') }}">
        <link rel="stylesheet" href="{{ url('public/vendor/summernote/dist/summernote.css') }}">
        <link rel="stylesheet" href="{{ url('public/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css') }}">
        <link rel="stylesheet" href="{{ url('public/vendor/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}">
        <link rel="stylesheet" href="{{ url('public/vendor/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
        <link rel="stylesheet" href="{{ url('public/styles/style.css') }}">
        <link rel="stylesheet" href=" {{ url('public/dist/css/lightbox.css') }}">
        <link rel="stylesheet" href="{{ url('public/vendor/toastr/build/toastr.min.css')}}">
        <link rel="stylesheet" href=" {{ url('public/css/custom.css')}}">
        <link rel="stylesheet" href="{{ url('public/css/print.css')}}">
        <link rel="stylesheet" href="{{ url('public/css/font/font.css')}}">
        <link rel="stylesheet" href="{{ url('public/css/bootstrap-select.css')}}">
        <link rel="stylesheet" href="{{ url('public/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{!! url('public/css/bootstrapValidator.min.css') !!}">
        <link rel="stylesheet" href="{!! url('public/css/issl_main.css') !!}">

        @yield('styles')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        </script>


















        <!---js-->

        <script rel="javascript" src=" {{ url('public/vendor/jquery/dist/jquery.min.js') }}"></script>

    </head>

    <body>

        <!-- Simple splash screen-->
        <?php
        $quizBook=\App\Settings::find(1);

        ?>
        <!-- <div class="splash"> <div class="color-line"></div><div class="splash-title"><img src="{!! asset($quizBook->logo) !!}" class="" width="64" height="64" /><h1 class="mm-group-text"><b>{!! $quizBook->site_title !!}</b></h1><p></p> </div> </div> -->

        <!-- Loading Transition  -->
        <div id="spinningSquaresG1">
            <div class="loader-title position-relative d-flex justify-content-center align-items-end">
                <img src="{!! asset($quizBook->logo) !!}" >
            </div>
            <div id="spinningSquaresG2" class="position-relative d-flex justify-content-center align-items-start">
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

        <!-- Header -->
        @include('includes.header')

        <!----------------- Main Menu Call -------------------->
        @include('includes.mainMenu')
        <div id="wrapper">
            <!----------------- Container Call -------------------->
            @yield('content')
            <footer class="footer">
                <span class="pull-right">
                    Powered By <a href="http://www.issl.com.bd" target="_blank">Impel Service &amp; Solutions Limited</a>
                </span>
                Copyright &copy; {!! $quizBook->copy_right !!}
            </footer>
        </div>

        <!-- Vendor scripts -->
        <script rel="javascript" src=" {{ url('public/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
        <script rel="javascript" src=" {{ url('public/vendor/slimScroll/jquery.slimscroll.min.js') }}"></script>
        <script rel="javascript" src=" {{ url('public/dist/js/lightbox-plus-jquery.min.js') }}"></script>
        <script rel="javascript" src=" {{ url('public/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script rel="javascript" src="   {{ url('public/js/bootstrap-select.js') }}"></script>
        <script rel="javascript" src=" {{ url('public/vendor/jquery-flot/jquery.flot.js') }}"></script>
        <script rel="javascript" src="{{ url('public/vendor/jquery-flot/jquery.flot.resize.js') }}"></script>
        <script rel="javascript" src="{{ url('public/vendor/jquery-flot/jquery.flot.pie.js') }}"></script>
        <script rel="javascript" src=" {{ url('public/vendor/flot.curvedlines/curvedLines.js') }}"></script>
        <script rel="javascript" src=" {{ url('public/vendor/jquery.flot.spline/index.js') }}"></script>
        <script rel="javascript" src=" {{ url('public/vendor/metisMenu/dist/metisMenu.min.js') }}"></script>
        <script rel="javascript" src=" {{ url('public/vendor/iCheck/icheck.min.js') }}"></script>
        <script rel="javascript" src=" {{ url('public/vendor/peity/jquery.peity.min.js') }}"></script>
        <script rel="javascript" src=" {{ url('public/vendor/sparkline/index.js') }}"></script>
        <script rel="javascript" src=" {{ url('public/vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
        <script rel="javascript" src="{{ url('public/vendor/select2-3.5.2/select2.min.js') }}"></script>
        <script rel="javascript" src=" {{ url('public/vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js') }}"></script>
        <script rel="javascript" src="{{ url('public/vendor/summernote/dist/summernote.min.js') }}"></script>
        <script rel="javascript" src=" {{ url('public/vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
        <script rel="javascript" src="  {{ url('public/vendor/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
        <script rel="javascript" src="   {{ url('public/vendor/toastr/build/toastr.min.js') }}"></script>
        <script rel="javascript" src="  {{ url('public/scripts/homer.js') }}"></script>
        <script rel="javascript" src="    {{ url('public/scripts/charts.js') }}"></script>
        <script rel="javascript" src="{!! url('public/js/bootstrapValidator.min.js') !!}"></script>
        <script rel="javascript" src="   {{ url('public/js/custom.js') }}"></script>
        <script rel="javascript" src="   {{ url('public/js/jquery-ui-timepicker-addon.js') }}"></script>
        <script rel="javascript" src="   {{ url('public/js/jquery.scrollUp.min.js') }}"></script>
        {!! Html::script("public/js/bootbox.min.js")!!}

        @yield('js')
        <script type="text/javascript">
            $(window).on('load', function () {

                $('.selectpicker').selectpicker({
                    'selectedText': 'cat'
                });

                // $('.selectpicker').selectpicker('hide');
            });
        </script>


        <script>

            $(function () {
                /**
                 * Flot charts data and options
                 */
                var data1 = [[0, 509], [1, 48], [2, 40], [3, 36], [4, 40], [5, 60], [6, 50], [7, 51]];
                var data2 = [[0, 56], [1, 49], [2, 41], [3, 38], [4, 46], [5, 67], [6, 57], [7, 59]];

                var chartUsersOptions = {
                    series: {
                        splines: {
                            show: true,
                            tension: 0.4,
                            lineWidth: 1,
                            fill: 0.4
                        },
                    },
                    grid: {
                        tickColor: "#f0f0f0",
                        borderWidth: 1,
                        borderColor: 'f0f0f0',
                        color: '#6a6c6f'
                    },
                    colors: ["#62cb31", "#efefef"],
                };

                $.plot($("#flot-line-chart"), [data1, data2], chartUsersOptions);

                /**
                 * Flot charts 2 data and options
                 */
                var chartIncomeData = [
                    {
                        label: "line",
                        data: [[1, 10], [2, 26], [3, 16], [4, 36], [5, 32], [6, 51]]
                    }
                ];

                var chartIncomeOptions = {
                    series: {
                        lines: {
                            show: true,
                            lineWidth: 0,
                            fill: true,
                            fillColor: "#64cc34"
                        }
                    },
                    colors: ["#62cb31"],
                    grid: {
                        show: false
                    },
                    legend: {
                        show: false
                    }
                };

                $.plot($("#flot-income-chart"), chartIncomeData, chartIncomeOptions);

            });
            function remove_date(e) {
                var id = e;
                $("#" + id).val('');
            }

            //Preloader
            $(window).on ('load', function (){
                $("#spinningSquaresG1").delay(2000).fadeOut(500);
                $(".inTurnBlurringTextG").on('click',function() {
                    $("#spinningSquaresG1").fadeOut(500);
                });

            });
        </script>
    </body>

</html>