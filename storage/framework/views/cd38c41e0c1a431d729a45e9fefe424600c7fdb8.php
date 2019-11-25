<style type="text/css">
    /*/ Tab Navigation /*/
    .nav-tabs {
        margin: 0;
        padding: 0;
        border: 0;
    }
    .nav-tabs > li > a {
        background: #f2f2f2;
        border-radius: 0;
        box-shadow: inset 0 -8px 7px -9px rgba(0,0,0,.4),-2px -2px 5px -2px rgba(0,0,0,.4);
    }
    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover {
        background: #F5F5F5;
        box-shadow: inset 0 0 0 0 rgba(0,0,0,.4),-2px -3px 5px -2px rgba(0,0,0,.4);
    }

    /*/ Tab Content /*/
    .tab-pane {
        background: #F5F5F5;
        box-shadow: 0 0 4px rgba(0,0,0,.4);
        border-radius: 0;
        text-align: center;
        padding: 10px;
        clear: bottom;

    }
</style>
<?php $__env->startSection('content'); ?>
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Forwarding For Clearance</h3>
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

                        <h3>Forwarding For Clearance</h3>
                    </div>
                    <div class="panel-body">

                        <div class="row">

                            <?php echo e(Form::open(array('role' => 'form', 'url' => 'suppliers/waiting-for-clarence/index/'.$status, 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'supplier-report'))); ?>


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
                                        <label for="company_mobile">Com. Name / Mob. No </label>
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
                        <div class="row">
                        <ul class="nav nav-tabs" style="margin-bottom: 20px;">
                            <?php
                            $segment=Request::segment(4);
                            $segment3=Request::segment(3);
                            $segment2=Request::segment(2);

                            ?>
                            <li <?php if($segment=='pending'): ?>class="active" <?php endif; ?>><a href="<?php echo e(URL::to('suppliers/waiting-for-clarence/index/pending')); ?>">Pending</a></li>
                            <li <?php if($segment3=='waiting-for-approve'): ?> class="active" <?php endif; ?>><a href="<?php echo e(URL::to('suppliers/waiting-for-clarence/waiting-for-approve')); ?>">Waiting for approve</a></li>
                            <li <?php if($segment3=='approved'): ?> class="active" <?php endif; ?>><a href="<?php echo e(URL::to('suppliers/waiting-for-clarence/approved')); ?>">Approved</a></li>

                        </ul>
                        </div>

                        <?php echo e(Form::open(array('role' => 'form', 'url' => 'suppliers/waiting-for-clarence/store', 'files'=> true, 'class' => '', 'id'=>''))); ?>



                        <div class="row">

                            <div class="col-md-6">
                                <div class="row paddingClass">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="requester" class="col-md-12 text-left">Date:<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <?php echo Form::text('date', date('Y-m-d'), array('id'=> 'top_date', 'class' => 'form-control datapicker2', 'required', 'readonly')); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row paddingClass">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="po_number" class="col-md-12">Letter No:<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control col-md-4" name="letter_no"  id="letter_no" value="" required="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="row paddingClass">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="inclusser"><?php echo 'Enclosure'; ?><span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-12">
                                                <textarea type="text" name="encloser" class="form-control" id="encloser"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row paddingClass">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="info"><?php echo 'Info'; ?><span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-12">
                                           <textarea type="text" name="info" class="form-control" id="info"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- <div class="hr-line-dashed"></div> -->

                        <div class="section">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align" style="margin-top: 20px;">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">
                                        <div class="checkbox checkbox-success" style="margin-top: 13px;">
                                            <input  type="checkbox" id="checkAll" name="is_contract_with" value="1">
                                            <label for=""></label>
                                        </div>
                                    </th>
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center"><?php echo e('Company Name'); ?></th>
                                    <th class="text-center" width=""><?php echo e('Mobile Number'); ?></th>
                                    <th class="text-center"><?php echo e(trans('Application')); ?></th>
                                    <th class="text-center"><?php echo e(trans('english.STATUS')); ?></th>
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
                                            <td>
                                                <div class="checkbox checkbox-success" style="; text-align: right;">
                                                    <input class="activity_1 activitycell" type="checkbox" id="supplier<?php echo $sc->id; ?>" name="suppliers[]" value="<?php echo $sc->id; ?>">
                                                    <label for="supplier<?php echo $sc->id; ?>"></label>
                                                </div>
                                            </td>
                                            <td><?php echo e(++$sl); ?></td>
                                            <td><?php echo e($sc->company_name); ?></td>
                                            <td><?php echo e($sc->mobile_number); ?></td>
                                            <td style="text-align: center"> <?php if($sc->attested_application != null): ?> <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#<?php echo $sc->id; ?>view_pdf">
                                                  <i class="fa fa-eye" title="View Document"></i>
                                                </button> <?php endif; ?> </td>
                                            <td class="text-center">
                                                    <span class="label label-warning"><?php echo ucfirst($status); ?></span>
                                            </td>

                                        </tr>





                                        <!-- Modal -->
                                        <div class="modal fade" id="<?php echo $sc->id; ?>view_pdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <object data="<?php echo asset('public/uploads/supplier_application_file/'. $sc->attested_application); ?>" type="application/pdf" width="100%" height="100%">

                                                        </object>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="6"><?php echo e('Empty Data'); ?></td>
                                    </tr>
                                <?php endif; ?>

                                </tbody>
                            </table><!---/table-responsive-->
                        </div>

                                <div class="col-md-12 ">
                                    <a href="<?php echo e(URL::previous()); ?>" class="btn btn-default cancel pull-right" ><?php echo trans('english.CANCEL'); ?></a>
                                    <?php if(!empty(Session::get('acl')[48][2])){ ?>
                                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;"><?php echo 'Send to clearance'; ?></button>
                                     <?php } ?>

                            </div>
                        </div>
                        <?php echo Form::close(); ?>



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
                var url='<?php echo URL::to('suppliers/enlistment/destroy'); ?>'+'/'+id;
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
                var url='<?php echo URL::to('suppliers/enlistment/approved'); ?>'+'/'+id;
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
                var url='<?php echo URL::to('suppliers/enlistment/rejected'); ?>'+'/'+id;
                bootbox.confirm("Are you sure?", function (result) {
                    if (result) {
                        var _url = $("#_url").val();
                        window.location.href = url;
                    }
                });
            });

        });



    </script>


    <script type="text/javascript">
        $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>