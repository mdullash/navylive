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
                    Manual Tender
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
                        Update Manual Tender
                    </div>
                    <div class="panel-body">

                    <?php echo e(Form::model($editId, array('route' => array('manual-tender.update', $editId->id), 'method' => 'PUT', 'files'=> true, 'class' => 'form validate-form tender', 'id' => 'tender'))); ?>


                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Tender Title :<span class="text-danger">*</span></label>
                                    <?php echo Form::text('tender_title', old('tender_title'), array('id'=> 'tender_title', 'class' => 'form-control')); ?>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Tender Number :<span class="text-danger">*</span></label>
                                    <?php echo Form::text('tender_number', old('tender_number'), array('id'=> 'tender_number', 'class' => 'form-control')); ?>

                                </div>
                            </div>

                            
                                
                                    
                                
                            

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Approval Letter Number :</label>
                                    <?php echo Form::text('approval_letter_number', old('approval_letter_number'), array('id'=> 'approval_letter_number', 'class' => 'form-control')); ?>

                                </div>
                            </div>



                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Tender Opening Date :<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control datapicker2" name="tender_opening_date" id="tender_opening_date" value="<?php echo date('Y-m-d',strtotime($editId->tender_opening_date)); ?>">
                                </div>
                            </div>




                            <!-- <div class="col-md-4">
                                <div class="form-group"><label class="control-label " for="valid_date_to">Valid Date To :<span class="text-danger">*</span></label>
                                    <?php echo Form::text('valid_date_to', old('valid_date_to'), array('id'=> 'valid_date_to', 'class' => 'form-control datapicker2','readonly')); ?>

                                </div>
                            </div> -->

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="tender_type">Tender Type :<span class="text-danger">*</span></label>
                                    <?php echo e(Form::select('tender_type', array('' => '- Select -', '1' => 'LTM- Limited Tender Method', '2' => 'OTM- Open Tender Method', '3' => 'RTM- Restricted Tender Method', '4' => 'Spot Tender', '5' => 'DPM- Direct Purchase Method'), old('tender_type'), array('class' => 'form-control selectpicker', 'id' => 'tender_type'))); ?>

                                </div>
                            </div>



                            
                                
                                    
                                
                            

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="status">Tender Category :<span class="text-danger">*</span></label>
                                    <select class="form-control selectpicker" name="tender_cat_id" id="tender_cat_id"  data-live-search="true">
                                        <option value=""><?php echo '- Select -'; ?></option>
                                        <?php $__currentLoopData = $supplyCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo $sc->id; ?>" <?php if($editId->tender_cat_id==$sc->id): ?> <?php echo e('selected'); ?> <?php endif; ?>><?php echo $sc->name; ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Organization:<span class="text-danger">*</span></label>
                                    <select class="form-control selectpicker" name="nsd_id" id="nsd_id"  data-live-search="true">
                                        <option value=""><?php echo '- Select -'; ?></option>
                                        <?php $__currentLoopData = $nsdNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo $nn->id; ?>" <?php if($editId->nsd_id==$nn->id): ?> <?php echo e('selected'); ?> <?php endif; ?>><?php echo $nn->name; ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>


                                <div class="col-md-4">
                                    <div class="form-group"><label class="control-label " for="stall_id">Quantity:</label>
                                        <?php echo Form::text('quantity', old('quantity'), array('id'=> 'quantity', 'class' => 'form-control')); ?>

                                    </div>
                                </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Specification :</label>
                                    <?php echo Form::file('specification[]', array('id'=> 'specification', 'class' => 'form-control', 'multiple', 'accept' => '.pdf,.doc,.docx')); ?>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Notice PDF :<span class="text-danger">*</span></label>
                                    <?php echo Form::file('notice', array('id'=> 'notice', 'class' => 'form-control', 'accept' => '.pdf')); ?>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="status">Status :<span class="text-danger">*</span></label>
                                    <?php echo e(Form::select('status', array('1' => 'Published', '2' => 'Unpublished'), $editId->status_id, array('class' => 'form-control selectpicker', 'id' => 'status'))); ?>

                                </div>
                            </div>

                            <!-- <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="open_tender"></label>
                                    <div class="checkbox checkbox-success">
                                        <input class="activity_1 activitycell" type="checkbox" id="open_tender" name="open_tender" value="1" <?php if($editId->open_tender==1): ?> checked="true" <?php endif; ?>>
                                        <label for="open_tender">Open Tender</label>
                                    </div>
                                </div>
                            </div> -->


                            <div class="form-group">
                                <div class="col-md-11 col-sm-offset-1">
                                    <a href="<?php echo e(URL::previous()); ?>" class="btn btn-default cancel pull-right" style="padding-right: 5px;"><?php echo trans('english.CANCEL'); ?></a>

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


    <script type="text/javascript">
        $(document).ready(function(){



        });
    </script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>