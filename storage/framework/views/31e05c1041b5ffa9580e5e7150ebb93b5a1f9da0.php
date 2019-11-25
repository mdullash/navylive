<div class="row">
    <div class="col-sm-6 col-md-6">

        <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Company Name :<span class="text-danger">*</span></label>
            <div class="col-md-7">
                <?php echo Form::text('company_name', old('company_name'), array('id'=> 'company_name', 'class' => 'form-control')); ?>

            </div>
        </div>



        <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Mobile Number :<span class="text-danger">*</span></label>
            <div class="col-md-7">
                <?php echo Form::text('mobile_number', old('mobile_number'), array('id'=> 'mobile_number', 'class' => 'form-control')); ?>

            </div>
        </div>



        <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Email :<span class="text-danger">*</span></label>
            <div class="col-md-7">
                <?php echo Form::text('email', old('email'), array('id'=> 'email', 'class' => 'form-control','required')); ?>

            </div>
        </div>










    </div>

    <div class="col-sm-6 col-md-6">

        <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Organization :<span class="text-danger">*</span></label>
            <div class="col-md-7">
                <?php $selectedNsd = isset($enlistment) ? explode(',',$enlistment->registered_nsd_id):null ?>
                <select class="form-control selectpicker" name="registered_nsd_id[]" id="registered_nsd_id"  data-live-search="true" multiple="multiple">
                    <option value="" disabled=""><?php echo '- Select -'; ?></option>
                    <?php $__currentLoopData = $nsdNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo $nn->id; ?>" <?php if( $nn->id==1): ?> <?php echo 'selected'; ?> <?php endif; ?> <?php if(isset($enlistment)): ?> <?php $__currentLoopData = $selectedNsd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if( $nn->id==$sn): ?> <?php echo 'selected'; ?> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>> <?php echo $nn->name; ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>



        <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Password :<span class="text-danger">*</span></label>
            <div class="col-md-7">
                <?php echo Form::password('password', array('id'=> 'password', 'class' => 'form-control', 'autocomplete' => 'off','required')); ?>

            </div>
        </div>

        <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Application File :</label>
            <div class="col-md-7">
                <?php echo Form::file('application_file', array('id'=> 'application_file', 'class' => 'form-control')); ?>

            </div>
        </div>

        <div class="form-group" style="display: none;"><label class="control-label col-md-5 no-padding-right" for="status">Status :<span class="text-danger">*</span></label>
            <div class="col-md-7">
                <?php echo e(Form::select('status', array('2' => trans('english.INACTIVE'), '1' => trans('english.ACTIVE')), old('status'), array('class' => 'form-control selectpicker', 'id' => 'status'))); ?>

            </div>
        </div>

    </div>

</div>