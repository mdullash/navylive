
<?php echo $__env->make('frontend.layouts._head', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('frontend.layouts._header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <?php echo $__env->yieldContent('content'); ?>

<?php echo $__env->make('frontend.layouts._footer', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

