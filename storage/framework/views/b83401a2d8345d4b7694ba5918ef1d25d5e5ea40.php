<ul class="list-group">
    <li class="list-group-item <?php if(Request::segment(3)=='dashboard'): ?>active <?php endif; ?>" >
        <a href="<?php echo URL::to($a.$b.'dashboard'); ?>" <?php if(Request::segment(3)=='dashboard'): ?> style="color:#fff;" <?php endif; ?>>Dashboard</a>
    </li>
    <li class="list-group-item <?php if(Request::segment(3)=='enlistment-track'): ?>active <?php endif; ?>" >
        <a href="<?php echo URL::to($a.$b.'enlistment-track'); ?>" <?php if(Request::segment(3)=='enlistment-track'): ?> style="color:#fff;" <?php endif; ?>>Enlistment Track</a>
    </li>

    <li class="list-group-item <?php if(Request::segment(3)=='supplier-form-submit'): ?>active <?php endif; ?>" >
        <a href="<?php echo URL::to($a.$b.'supplier-form-submit'); ?>" <?php if(Request::segment(3)=='supplier-form-submit'): ?> style="color:#fff;" <?php endif; ?>>Form Submit</a>
    </li>

    <?php if($approved !=0): ?>
    <li class="list-group-item <?php if(Request::segment(3)=='tender-participant-status'): ?>active <?php endif; ?>">
        <a href="<?php echo URL::to($a.$b.'tender-participant-status'); ?>" <?php if(Request::segment(3)=='tender-participant-status'): ?> style="color:#fff;" <?php endif; ?>>Tender Participant Status</a>
    </li>
    <li class="list-group-item <?php if(Request::segment(3)=='evaluation-report'): ?>active <?php endif; ?>" >
        <a href="<?php echo URL::to($a.$b.'evaluation-report'); ?>" <?php if(Request::segment(3)=='evaluation-report'): ?> style="color:#fff;" <?php endif; ?>>Evaluation Report</a>
    </li>
    <li class="list-group-item <?php if(Request::segment(3)=='supplier-chat'): ?>active <?php endif; ?>" >
        <a href="<?php echo URL::to($a.$b.'supplier-chat'); ?>" <?php if(Request::segment(3)=='supplier-chat'): ?> style="color:#fff;" <?php endif; ?>>Inbox</a>
    </li>
    <?php endif; ?>
    <li class="list-group-item <?php if(Request::segment(3)=='supplier-profile'): ?>active <?php endif; ?>" >
        <a href="<?php echo URL::to($a.$b.'supplier-profile'); ?>" <?php if(Request::segment(3)=='supplier-profile'): ?> style="color:#fff;" <?php endif; ?>>Profile</a>
    </li>
    <li class="list-group-item <?php if(Request::segment(3)=='supplier-change-password'): ?>active <?php endif; ?>" >
        <a href="<?php echo URL::to($a.$b.'supplier-change-password'); ?>" <?php if(Request::segment(3)=='supplier-change-password'): ?> style="color:#fff;" <?php endif; ?>>Change Password</a>
    </li>
    <li class="list-group-item">
        <a href="<?php echo url($a.$b.'logout'); ?>">Logout</a>
    </li>
</ul>
