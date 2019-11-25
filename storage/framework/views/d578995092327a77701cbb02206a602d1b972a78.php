<style type="text/css">

.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}
</style>

<?php $__env->startSection('content'); ?>
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    Enlistment Management
                </h2>
            </div>
            <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    </div>

    <div class="content animate-panel">
        <div class="row">
            <div class="col-sm-6 col-md-12">
                <div class="hpanel">
                    <div class="panel-heading sub-title">
                        Create Enlistment
                    </div>
                    <div class="panel-body">
                        <?php echo e(Form::open(array('role' => 'form', 'url' => 'suppliers/enlistment', 'files'=> true, 'class' => 'form-horizontal enlistment', 'id'=>'enlistment'))); ?>




                         <?php echo $__env->make('suppliers.enlistment._form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>


                       
                        <div class="form-group">
                            <div class="col-md-7 col-sm-offset-5">
                                <a href="<?php echo e(URL::previous()); ?>" class="btn btn-default cancel pull-right" ><?php echo trans('english.CANCEL'); ?></a>
                                
                                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('english.SAVE'); ?></button>
                                
                            </div>
                        </div>
    
                    <!-- <div class="hr-line-dashed"></div> -->
                        <?php echo Form::close(); ?>


                    </div>
                </div>
            </div>
        </div>
    </div>
    



<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>