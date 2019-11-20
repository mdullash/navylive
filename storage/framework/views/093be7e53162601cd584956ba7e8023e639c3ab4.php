<?php


$ses_msg = Session::has('success');
if (!empty($ses_msg)) {
    ?>
    <div class="alert alert-success alert-dismissable" style="width:100%">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <p><i class="fa fa-bell-o fa-fw"></i> <?php echo Session::get('success'); ?></p>
    </div>
    <?php
}


$ses_msg = Session::has('successCoupon');
if (!empty($ses_msg)) {
    ?>
    <div class="alert alert-success alert-dismissable" style="width:100%">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <p><i class="fa fa-bell-o fa-fw"></i> <?php echo Session::get('successCoupon'); ?></p>
    </div>
    <?php
}

//

$ses_msg = Session::has('successSubscribe');
if (!empty($ses_msg)) {
?>
<div class="alert alert-success alert-dismissable" style="width:100%">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    <p><i class="fa fa-bell-o fa-fw"></i> <?php echo Session::get('successSubscribe'); ?></p>
</div>
<?php
}//


$ses_msg = Session::has('errorSubscribe');

if (!empty($ses_msg)) {
?>
<div class="alert alert-danger alert-dismissable" style="width:100%">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    <p><i class="fa fa-bell-o fa-fw"></i> <?php echo Session::get('errorSubscribe'); ?></p>
</div>
<?php
   }
$ses_msg = Session::has('error');
if (!empty($ses_msg)) {
    ?>
    <div class="alert alert-danger alert-dismissable" style="width:100%">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <p><i class="fa fa-bell-o fa-fw"></i> <?php echo Session::get('error'); ?></p>
    </div>
<?php
}

$ses_msg = Session::has('warning');
if (!empty($ses_msg)) {
    ?>
    <div class="alert alert-danger alert-dismissable" style="width:100%">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <p><i class="fa fa-bell-o fa-fw"></i> <?php echo Session::get('warning'); ?></p>
    </div>
<?php
}

$ses_msg = Session::has('failedCoupon');

if (!empty($ses_msg)) {
?>
<div class="alert alert-danger alert-dismissable" style="width:100%">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    <p><i class="fa fa-bell-o fa-fw"></i> <?php echo Session::get('failedCoupon'); ?></p>
</div>
<?php


}// ?>





<?php if($errors->any()){ ?>

<ul class="alert alert-danger alert-dismissable" style="width:100%">
    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close"><i  class="fa fa-times" aria-hidden="true"></i></a>
    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <p><?php echo e($error); ?></p>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php } ?>
