<?php $__env->startSection('content'); ?>
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Sell Form Management</h3>
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
                        <?php if(!empty(Session::get('acl')[47][2])){ ?>
                        <div class="pull-right">
                            <a class="btn btn-info btn-effect-ripple" href="<?php echo e(URL::to('suppliers/sells-form/create')); ?>"><i class="fa fa-plus"></i> Add New</a>
                        </div>
                        <?php } ?>
                        <h3>Sell Form</h3>
                    </div>
                    <div class="panel-body">

                        <div class="row">

                            <?php echo e(Form::open(array('role' => 'form', 'url' => 'supplier/sell-form/index', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'schedule-all'))); ?>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">Mobile Number: </label>
                                        <?php echo Form::text('mobile_number', $mobile_number, array('id'=> 'mobile_number', 'class' => 'form-control', 'autocomplete'=> 'off','placeholder' => 'Mobile No.')); ?>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">From: </label>
                                        <?php echo Form::text('from', $from, array('id'=> 'from', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off','readonly')); ?>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">To: </label>
                                        <?php echo Form::text('todate', $todate, array('id'=> 'todate', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off','readonly')); ?>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="col-md-12" style="padding-top: 5px;">
                                        <label for="submit"></label>
                                        <button type="submit" class="form-control btn btn-primary"><?php echo 'Search'; ?></button>
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
                                    <th class="text-center"><?php echo e('Mobile Number'); ?></th>
                                    <th class="text-center"><?php echo e('Email'); ?></th>
                                    <th class="text-center"><?php echo e('Amount'); ?></th>
                                    <?php if(!empty(Session::get('acl')[47][2]) || !empty(Session::get('acl')[47][1])){ ?>
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

                                    ?>
                                    <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(++$sl); ?></td>
                                            <td><?php if(!empty($sc->company_name)): ?> <?php echo $sc->company_name; ?> <?php endif; ?></td>
                                            <td><?php if(!empty($sc->mobile_number)): ?> <?php echo $sc->mobile_number; ?> <?php endif; ?></td>
                                            <td><?php if(!empty($sc->email )): ?> <?php echo $sc->email; ?> <?php endif; ?></td>
                                            <td><?php if(!empty($sc->amount )): ?> <?php echo $sc->amount; ?> <?php endif; ?></td>
                                            <td class="action-center">
                                                <div class="text-center">

                                                    <?php if(!empty(Session::get('acl')[47][2])){ ?>
                                                    <a class="btn btn-primary btn-xs" href="<?php echo e(URL::to('suppliers/sells-from/print-sells-from/' . $sc->id)); ?>" title="Print" target="_blank">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                    <?php } ?>

                                                    <?php if(!empty(Session::get('acl')[47][4])){?>
                                                    <button class="exbtovdelete btn btn-danger btn-xs" id="<?php echo e($sc->id); ?>" type="button" data-placement="top" data-rel="tooltip" data-original-title="Delete" title="Delete">
                                                        <i class='fa fa-trash'></i>
                                                    </button>
                                                    <?php }?>

                                                </div>
                                            </td>

                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="7"><?php echo e('Empty Data'); ?></td>
                                    </tr>
                                <?php endif; ?>

                                </tbody>
                            </table><!---/table-responsive-->
                        </div>

                        <?php echo e($suppliers->links()); ?>


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
                var url='<?php echo URL::to('/suppliers/sells-from/destroy'); ?>'+'/'+id;
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