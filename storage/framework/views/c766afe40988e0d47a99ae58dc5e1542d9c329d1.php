<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Page title -->
        <?php
        $quizBook=\App\Settings::find(1);

        ?>
        <title><?php echo $quizBook->site_title; ?></title>
        <link rel="shortcut icon" href="<?php echo asset($quizBook->favicon); ?>">
        <link rel="stylesheet" href="<?php echo e(url('public/vendor/fontawesome/css/font-awesome.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(url('public/vendor/metisMenu/dist/metisMenu.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(url('public/vendor/animate.css/animate.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(url('public/vendor/bootstrap/dist/css/bootstrap.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(url('public/fonts/pe-fa fa-7-stroke/css/pe-fa fa-7-stroke.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(url('public/fonts/pe-fa fa-7-stroke/css/helper.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(url('public/styles/style.css')); ?>">







    </head>
    <body class="blank" style="background: url(<?php echo e(asset('/public/img/admin_bg.jpg')); ?>) no-repeat center center !important; background-size: 100% 100% !important;">

        <!-- Loading Transition  -->
        <div id="spinningSquaresG1">
            <div class="loader-title position-relative d-flex justify-content-center align-items-end">
                <img src="<?php echo asset($quizBook->logo); ?>" class="" width="64" height="64">
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
        

        <div class="login-container l-panel">
            <div class="row">
                <div class="col-md-12 text-center login-logo">
                    <img src="<?php echo asset($quizBook->logo); ?>" width="" height="120" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="hpanel">
                        <div class="panel-body">

                            <?php if(Session::has('error')): ?>
                                <div class='alert alert-danger alert-dismissable'>
                                   <a class="close" data-dismiss="alert" href="#">&times;</a>
                                   <i class='icon-remove-sign'></i>
                                  <?php echo e(Session::get('error')); ?>

                                </div>
                            <?php endif; ?>
                            <?php echo Form::open(array('url' => 'login', 'class' => 'validate-form', 'autocomplete'=>'off')); ?>


                            <div class="form-group">
                                <label class="control-label" for="username"><?php echo e(trans('english.USERNAME')); ?></label>
                                <input type="text" id="login-username" name="username" class="form-control" placeholder="Username" autocomplete="off">

                            </div>
                            <div class="form-group">
                                <label class="control-label" for="password"><?php echo e(trans('english.PASSWORD')); ?></label>
                                <input type="password" id="login-password" name="password" class="form-control" placeholder="Your password..">
                                
                            </div>
                            <button class="btn btn-success btn-block"><?php echo e(trans('english.SIGN_IN')); ?></button>
                            <?php echo Form::close(); ?>

                        </div>
                        <!--                        <div class="panel-footer text-center">
                                                    Foregot password
                                                </div>-->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center copy">
                    Copyright &copy; <?php echo date('Y'); ?> <a href="http://issl.com.bd/" target="_blank">ISSL</a>
                </div>
            </div>
        </div>


        <!-- Vendor scripts -->
        <script rel="javascript" src=" <?php echo e(url('public/vendor/jquery/dist/jquery.min.js')); ?>"></script>
        <script rel="javascript" src="  <?php echo e(url('public/vendor/jquery-ui/jquery-ui.min.js')); ?>"></script>
        <script rel="javascript" src="  <?php echo e(url('public/vendor/slimScroll/jquery.slimscroll.min.js')); ?>"></script>
        <script rel="javascript" src="   <?php echo e(url('public/vendor/bootstrap/dist/js/bootstrap.min.js')); ?>"></script>
        <script rel="javascript" src="  <?php echo e(url('public/vendor/metisMenu/dist/metisMenu.min.js')); ?>"></script>
        <script rel="javascript" src="   <?php echo e(url('public/vendor/bootstrap/dist/js/bootstrap.min.js')); ?>"></script>
        <script rel="javascript" src=" <?php echo e(url('public/vendor/iCheck/icheck.min.js')); ?>"></script>
        <script rel="javascript" src="   <?php echo e(url('public/vendor/sparkline/index.js')); ?>"></script>
        <script rel="javascript" src=" <?php echo e(url('public/scripts/homer.js')); ?>"></script>

        <script>
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
<style>
    .copy a:hover{
        text-decoration: underline;
    }
    .copy a:visited {
        color: green;
    }
</style>






