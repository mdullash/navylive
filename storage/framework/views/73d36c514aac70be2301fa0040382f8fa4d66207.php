<style>
    .hpanel .panel-body{
        background: rgba(255, 255, 255, 0.64) !important;
    }
    .header-link{
        padding: 23px 26px 17px 26px !important;
    }
    .hpanel .panel-footer{
        border: none !important;
        color: white !important;
    }
</style>

<?php $__env->startSection('content'); ?>
    <div class="content animate-panel" style="padding: 25px 40px 0px 40px;">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2>
                   <?php echo $title->site_title; ?>

                </h2>

                <p class="">
                    <!--Better Customer Experience-->
                    <?php
                        $zone_desc = \App\Zone::where('id','=',\Session::get('zoneId'))->value('info');
                    ?>
                    <h5><?php echo $zone_desc; ?></h5>
                </p>
            </div>
        </div>
    </div>
    <div class="content animate-panel">
        <div class="row">
            <div class="col-md-offset-1 col-md-10 col-xs-12 text-center">
                <?php if (!empty(Session::get('acl')[3][1])) { ?>
                <div class="col-md-3 ">
                    <a href="<?php echo e(URL::to('users')); ?>">
                        <div class="hpanel dashboard-box">
                            <div class="panel-footer">
                                <?php echo e(trans('english.USER_MANAGEMENT')); ?> <?php echo '('.$user.')'; ?>

                            </div>
                        </div>
                    </a>

                </div>
                <?php } ?>

                <?php if (!empty(Session::get('acl')[12][1])) { ?>
                <div class="col-md-3 ">
                    <a href="<?php echo e(URL::to('suppliers/suppliers')); ?>">
                        <div class="hpanel dashboard-box">
                            <div class="panel-footer">
                                <?php echo e(trans('english.SUPPLIERS')); ?>  <?php echo '('.$suppliers.')'; ?>

                            </div>
                        </div>
                    </a>

                </div>
                <?php } ?>

                <?php if (!empty(Session::get('acl')[14][1])) { ?>
                <div class="col-md-3 ">
                    <a href="<?php echo e(URL::to('item/view')); ?>">
                        <div class="hpanel dashboard-box">
                            <div class="panel-footer">
                                <?php echo e(trans('english.ITEM')); ?><?php echo e('s'); ?> <?php echo '('.$items.')'; ?>

                            </div>
                        </div>
                    </a>

                </div>
                <?php } ?>

                <?php if (!empty(Session::get('acl')[13][1])) { ?>
                <div class="col-md-3 ">
                    <a href="<?php echo e(URL::to('tender/view')); ?>">
                        <div class="hpanel dashboard-box">
                            <div class="panel-footer">
                                <?php echo e(trans('english.TENDER')); ?><?php echo e('s'); ?> <?php echo '('.$tenders.')'; ?>

                            </div>
                        </div>
                    </a>

                </div>
                <?php } ?>




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
            $('.hpanel .panel-footer').each(function() {
                $(this).css('background',randomColor());
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

    <script type="text/javascript">
        function productinfo() {
            $.ajax({
                url: "<?php echo e(URL::to('dashboards/productinfo')); ?>",
                type: "POST",
                //data: {'work_order_id': workOrderId, 'product_id': editId, 'project_id': projectId, 'site_office_id': siteOfficeId},
                dataType: 'html',
                cache: false
            }).done(function (data) {
//            var result = $.parseJSON(data);
//
            });
        }
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>