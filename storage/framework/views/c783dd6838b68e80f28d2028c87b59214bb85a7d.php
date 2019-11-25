<?php $__env->startSection('content'); ?>
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Suppliers</h3>
                </h2>
            </div>
            <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    </div>
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading hbuilt">
                        <?php if(!empty(Session::get('acl')[12][2])){ ?>
                        <div class="pull-right">
                            <a class="btn btn-info btn-effect-ripple" href="<?php echo e(URL::to('suppliers/suppliers/create')); ?>"><i class="fa fa-plus"></i> Add Suppliers</a>
                        </div>
                        <?php } ?>
                            <h3>Suppliers</h3>
                    </div>
                        <div class="panel-body">

                            <div class="row">

                                <?php echo e(Form::open(array('role' => 'form', 'url' => 'suppliers/suppliers', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'supplier-report'))); ?>


                                 <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="email">Organization: </label>
                                            <select class="form-control selectpicker" name="nsd_id" id="nsd_id"  data-live-search="true">
                                                <option value=""><?php echo '- Select -'; ?></option>
                                                <?php $__currentLoopData = $nsdNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo $nn->id; ?>" <?php if($nn->id==$nsd_id): ?> <?php echo e('selected'); ?> <?php endif; ?>><?php echo $nn->name; ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="company_mobile">Com. Name / Mob. No / Reg. No: </label>
                                            <?php echo Form::text('company_mobile', $company_mobile, array('id'=> 'company_mobile', 'class' => 'form-control', 'autocomplete'=> 'off')); ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="email">From: </label>
                                            <?php echo Form::text('from', $from, array('id'=> 'from', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off')); ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="email">To:</label>
                                            <?php echo Form::text('to', $to, array('id'=> 'to', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off')); ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="col-md-12" style="">
                                            <label for="email"></label>
                                            <button type="submit" class="btn btn-primary"><?php echo 'Search'; ?></button>
                                        </div>
                                    </div>
                                </div>
                                      
                                <?php echo Form::close(); ?>    

                            </div>

                            <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center"><?php echo e('Company Name'); ?></th>
                                    <th class="text-center" width=""><?php echo e('Company Registration No.'); ?></th>
                                    <th class="text-center" width=""><?php echo e('Mobile Number'); ?></th>
                                    <th class="text-center" width=""><?php echo e('Supply Category'); ?></th>
                                    <th class="text-center" width=""><?php echo e('TIN Number'); ?></th>
                                    <th class="text-center"><?php echo e(trans('english.STATUS')); ?></th>
                                    <?php if(!empty(Session::get('acl')[12][3]) || !empty(Session::get('acl')[12][4])){ ?>
                                    <th class="text-center"> <?php echo trans('english.ACTION'); ?>

                                    </th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>

                                <?php if(!$suppliers->isEmpty()): ?>

                                    <?php
                                        $page = \Input::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page-1)*10;
                                        $l = 1;

                                        function supply_cat_name($cat_id=null){
                                            $calName = \App\SupplyCategory::where('id','=',$cat_id)->value('name');
                                            return $calName;
                                        }
                                    ?>
                                    <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr> 
                                            <td><?php echo e(++$sl); ?></td>
                                            <td><?php echo e($sc->company_name); ?></td>
                                            <td><?php echo e($sc->company_regi_number_nsd); ?></td>
                                            <td><?php echo e($sc->mobile_number); ?></td>
                                            
                                            <td>
                                                <?php 
                                                    $catids = explode(',',$sc->supply_cat_id);
                                                    foreach ($catids as $ctds) {
                                                        $valsss = supply_cat_name($ctds);
                                                        echo "<li>".$valsss."</li>";
                                                    }
                                                ?>
                                            </td>
                                            <td><?php echo e($sc->tin_number); ?></td>
                                            <td class="text-center">
                                                <?php if($sc->status_id == '1'): ?>
                                                    <span class="label label-success"><?php echo e(trans('english.ACTIVE')); ?></span>
                                                <?php endif; ?>
                                                <?php if($sc->status_id == '2'): ?>
                                                    <span class="label label-warning"><?php echo e(trans('english.INACTIVE')); ?></span>
                                                <?php endif; ?>
                                                <?php if($sc->status_id == '3'): ?>
                                                    <span class="label label-info"><?php echo e('Pending'); ?></span>
                                                <?php endif; ?>
                                                <?php if($sc->status_id == '4'): ?>
                                                    <span class="label label-danger"><?php echo e('Rejected'); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <?php if(!empty(Session::get('acl')[12][3]) || !empty(Session::get('acl')[12][4])){ ?>
                                            <td class="action-center">
                                                <div class="text-center">

                                                    <?php if(!empty(Session::get('acl')[12][12])){ ?>
                                                    <?php if($sc->status_id == '3'): ?>
                                                        <a class="btn btn-info btn-xs approve" id="<?php echo e($sc->id); ?>" href="<?php echo e(URL::to('suppliers/suppliers/approve' . $sc->id )); ?>" title="Approve">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                        <a class="btn btn-warning btn-xs rejecte" id="<?php echo e($sc->id); ?>" href="<?php echo e(URL::to('suppliers/suppliers/rejecte' . $sc->id )); ?>" title="Reject">
                                                            <i class="fa fa-ban"></i>
                                                        </a>
                                                    <?php endif; ?>


                                                    <?php } ?>

                                                    <?php if(!empty(Session::get('acl')[12][3])){ ?>
                                                    <a class="btn btn-primary btn-xs" href="<?php echo e(URL::to('suppliers/suppliers/' . $sc->id . '/edit')); ?>" title="Edit">
                                                        <i class="icon-edit"></i>
                                                    </a>
                                                    <?php } ?>

                                                    <?php if(!empty(Session::get('acl')[12][1])){ ?>
                                                    <a class="btn btn-primary btn-xs" href="<?php echo e(URL::to('/suppliers/view/'. $sc->id)); ?>" title="view" target="_blank">
                                                        <i class="icon-eye-open"></i>
                                                    </a>
                                                    <?php } ?>

                                                    <?php if(!empty(Session::get('acl')[12][4])){?>
                                                    <button class="exbtovdelete btn btn-danger btn-xs" id="<?php echo e($sc->id); ?>" type="button" data-placement="top" data-rel="tooltip" data-original-title="Delete" title="Delete">
                                                        <i class='fa fa-trash'></i>
                                                    </button>
                                                   <?php }?>

                                                </div>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8"><?php echo e('Empty Data'); ?></td>
                                    </tr>
                                <?php endif; ?>
                                    
                                </tbody>
                            </table><!---/table-responsive-->
                            </div>
                            
                            <?php echo $suppliers->appends(\Input::except('page'))->render(); ?>


                        </div>
                    </div>
                </div>
            </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function(){

            /*For Delete Department*/
            $(".exbtovdelete").click(function (e) {
                e.preventDefault();
                
                var id = this.id; 
                var url='<?php echo URL::to('suppliers/suppliers/destroy'); ?>'+'/'+id;
                bootbox.confirm("Are you sure?", function (result) {
                    if (result) {
                        var _url = $("#_url").val();
                        window.location.href = url;
                    }
                });
            });

            /*For Approve supplier*/
            $(".approve").click(function (e) {
                e.preventDefault();

                var id = this.id;
                var url='<?php echo URL::to('suppliers/suppliers/approve'); ?>'+'/'+id;
                bootbox.confirm("Are you sure?", function (result) {
                    if (result) {
                        var _url = $("#_url").val();
                        window.location.href = url;
                    }
                });
            });

            /*For rejecte supplier*/
            $(".rejecte").click(function (e) {
                e.preventDefault();

                var id = this.id;
                var url='<?php echo URL::to('suppliers/suppliers/rejecte'); ?>'+'/'+id;
                bootbox.confirm("Are you sure?", function (result) {
                    if (result) {
                        var _url = $("#_url").val();
                        window.location.href = url;
                    }
                });
            });

        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>