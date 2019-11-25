<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php
$quizBook=\App\Settings::find(1);

?>
<!-- Page title -->
    <title><?php echo $quizBook->site_title; ?></title>
    <link rel="shortcut icon" href="<?php echo asset($quizBook->favicon); ?>">
    <!-- Vendor styles -->
    <link rel="stylesheet" href="<?php echo e(url('public/vendor/fontawesome/css/font-awesome.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('public/vendor/metisMenu/dist/metisMenu.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('public/vendor/animate.css/animate.css')); ?>">
    <link rel="stylesheet" href=" <?php echo e(url('public/vendor/bootstrap/dist/css/bootstrap.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('public/styles/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('public/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css')); ?>">

    <link rel="stylesheet" href=" <?php echo e(url('public/css/custom.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('public/css/print.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('public/css/font-awesome.min.css')); ?>">

    <?php echo $__env->yieldContent('styles'); ?>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    </script>


    <script rel="javascript" src=" <?php echo e(url('public/vendor/jquery/dist/jquery.min.js')); ?>"></script>

    <style>
        /*.hpanel .panel-body{*/
            /*background: rgba(255, 255, 255, 0.64) !important;*/
        /*}*/
        /*.header-link{*/
            /*padding: 23px 26px 17px 26px !important;*/
        /*}*/

    </style>

</head>

<body style="background: url(<?php echo e(asset('/public/img/dashboard_bg.jpg')); ?>) no-repeat center center !important; background-size: 100% 100% !important;">

<?php
$quizBook=\App\Settings::find(1);

?>
<!-- <div class="splash"> <div class="color-line"></div><div class="splash-title"><img src="<?php echo asset($quizBook->logo); ?>" class="rotating123" width="64" height="64" /><h1 class="mm-group-text"><b><?php echo $quizBook->site_title; ?></b></h1><p></p> </div> </div>
 -->

<!-- Loading Transition  -->
        <div id="spinningSquaresG1">
            <div class="loader-title position-relative d-flex justify-content-center align-items-end">
                <img src="<?php echo asset($quizBook->logo); ?>" >
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
<?php echo $__env->make('includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12 text-center">

                <p class="col-md-offset-1">
                    <!--Better Customer Experience-->
                </p>
            </div>
        </div>
    </div>
    <div class="content animate-panel">
        <div class="row">
            <div class="{--col-md-offset-1--} col-md-10 col-xs-12">

                <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4 ">
                        <a href="<?php echo e(URL::to('dashboard/'.base64_encode($zn->id))); ?>">
                            <div class="hpanel dashboard-box" style="background-image: url(<?php echo e(asset('/public/img/'.$zn->icon)); ?>); background-repeat: no-repeat; background-size: 100%;">
                                <div class="panel-body file-body" style="background: none;">
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-6">
                                            <br><br><br><br>
                                            
                                            
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-6">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer text-left">
                                    <b><?php echo $zn->info; ?></b>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


            </div>

        </div>

    </div>

    <script type="text/javascript">

        var safeColors = ['11','33','66','99','cc','ee'];//["#00e64d","#ff80aa","#990099","#30DDBC","#ff8533"];
        var rand = function() {
            return Math.floor(Math.random()*6);
        };
        var randomColor = function() {
            var r = safeColors[rand()];
            var g = safeColors[rand()];
            var b = safeColors[rand()];
            return "#"+r+g+b;
            //return r;
        };

        $(document).ready(function(){
            $('.hpanel .panel-body').each(function() {
                //$(this).css('background',randomColor());
            });
        });

        //Preloader
        $(window).on ('load', function (){
            $("#spinningSquaresG1").delay(2000).fadeOut(500);
            $(".inTurnBlurringTextG").on('click',function() {
                $("#spinningSquaresG1").fadeOut(500);
            });

        });

    </script>

    <!-- Vendor scripts -->
    <script rel="javascript" src=" <?php echo e(url('public/vendor/jquery-ui/jquery-ui.min.js')); ?>"></script>
    <script rel="javascript" src=" <?php echo e(url('public/vendor/bootstrap/dist/js/bootstrap.min.js')); ?>"></script>
    <script rel="javascript" src=" <?php echo e(url('public/vendor/metisMenu/dist/metisMenu.min.js')); ?>"></script>
    <script rel="javascript" src=" <?php echo e(url('public/vendor/iCheck/icheck.min.js')); ?>"></script>
    <script rel="javascript" src="  <?php echo e(url('public/scripts/homer.js')); ?>"></script>



</body>

</html>