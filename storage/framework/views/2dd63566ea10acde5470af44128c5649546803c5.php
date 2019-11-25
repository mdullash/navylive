<?php
    $navystg = \App\Settings::find(1);
?>
<!-- footer-section -->
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                <!-- footer-widget -->
                <div class="footer-widget">
                    <h3 class="widget-title">
                        We are
                    </h3>
                    <p class="mb10" style="line-height: 1.5;text-align: justify;">The Bangladesh Navy (বাংলাদেশ নৌবাহিনী) is the naval warfare branch of the Bangladesh Armed Forces and the defence of important harbours, military bases and economic zones.</p>
                </div>
            </div>
            <!-- /.footer-widget -->
            <!-- footer-widget -->
            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-6 col-12">
                <div class="footer-widget">
                    <h3 class="widget-title">
                        Contact Address
                    </h3>
                    <p style="line-height: 1.5;text-align: justify;">Naval Store Sub Depot Dhaka,
                        <br> Naval Unit Khilkhet
                        <br>Namapara, Khilkhet
                        <br>Dhaka-1229
                    </p>

                </div>
            </div>
            <!-- /.footer-widget -->
            <!-- footer-widget -->
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="footer-widget">
                    <h3 class="widget-title">
                        About Navy
                    </h3>
                    <ul class="listnone"  >
                        <!-- <li><a href="#">About us</a></li> -->
                        <li><a href="<?php echo e(URL::to($a.$b.'front-contact-us/')); ?>">Contact us</a></li>
                        <!-- <li><a href="#">Faq</a></li>
                        <li><a href="#">Pricing Plan</a></li>
                        <li><a href="#">Meet The Team</a></li> -->
                        <li><a href="http://mail.navy.mil.bd/" target="_blank">Access Email</a>
                        <li><a href="<?php echo url('psimstv'); ?>" target="_blank">Display</a>
                    </ul>
                </div>
            </div>
            <!-- /.footer-widget -->

        </div>
    </div>
</div>
<!-- tiny-footer-section -->
<div class="tiny-footer">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <p>© <?php echo date('Y'); ?> Bangladesh Navy. All Rights Reserved.</p>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 text-right f_powered">
                <p>Powered By <a href="http://issl.com.bd/" target="_blank"><strong>Impel Service &amp; Solutions Limited</strong></a></p>
            </div>
        </div>
    </div>
</div>
<!-- /.tiny-footer-section -->

<?php echo $__env->yieldContent('footer-js'); ?>
</body>

</html>
