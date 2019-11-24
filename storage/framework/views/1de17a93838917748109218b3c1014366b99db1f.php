<style type="text/css">
    / Tab Navigation /
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

    / Tab Content /
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
                    <h3>Manual Tenders</h3>
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

                            <h3>Manual Tenders

                                <div class="pull-right">
                                   <a class="btn btn-info btn-effect-ripple" href="<?php echo e(URL::to('direct-item-dmnd-create')); ?>"><i class="fa fa-plus"></i> Create Tender</a>
                                </div>


                              <?php if(!empty(Session::get('acl')[13][2])){ ?>
                                <div class="pull-right">
                                    <a class="btn btn-warning btn-effect-ripple" href="<?php echo e(URL::to('manual-tender/create')); ?>"><i class="fa fa-plus"></i> Create Manual Tender</a>
                                </div>
                                <?php } ?>
                            </h3>
                    </div>
                        <div class="panel-body">

                            <div class="row">

                                <?php echo e(Form::open(array('role' => 'form', 'url' => 'manual-tender/view', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'supplier-report'))); ?>


                                <div class="col-md-6">
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="company_mobile">Tender title/ Tender number: </label>
                                            <?php echo Form::text('key', $key, array('id'=> 'key', 'class' => 'form-control', 'autocomplete'=> 'off')); ?>

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
                                        <div class="col-md-12" style="padding-top: 22px;">
                                            <label for="email"></label>
                                            <button type="submit" class="btn btn-primary"><?php echo 'Search'; ?></button>
                                        </div>
                                    </div>
                                </div>

                                <?php echo Form::close(); ?>


                            </div>

                            <ul class="nav nav-tabs">


                                <?php if(!empty(Session::get('acl')[34][17])){ ?>
                                <li ><a href="<?php echo e(URL::to('floating-tender-acc/1')); ?>"> Pending</a></li>

                                <?php if(!empty(Session::get('acl')[34][29])){ ?>
                                <li><a href="<?php echo e(URL::to('retender-view-acc/1')); ?>"> Retender</a></li>
                                <?php } ?>

                                <?php if(!empty(Session::get('acl')[34][26]) || !empty(Session::get('acl')[34][17])){ ?>
                                <li><a href="<?php echo e(URL::to('floating-tender-acc/3')); ?>"> Waiting for Approve</a></li>
                                <?php } ?>

                                <li ><a href="<?php echo e(URL::to('floating-tender-acc/2')); ?>"> Approved</a></li>

                                <li <?php if(\Request::segment(1)=='manual-tender'): ?>class="active" <?php endif; ?>><a href="<?php echo e(URL::to('manual-tender/view')); ?>"> Manual Tender</a></li>


                                
                                <?php } ?>
                            </ul>


                            <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center" width="10%"><?php echo e('Tender Title/Name'); ?></th>
                                    <th class="text-center"><?php echo e('Tender Number'); ?></th>

                                    <th class="text-center" width=""><?php echo e('Tender Opening Date'); ?></th>
                                    <th class="text-center" width=""><?php echo e('Tender Group'); ?></th>

                                    <!-- <th class="text-center" width=""><?php echo e('Tender Open'); ?></th> -->

                                    <th class="text-center" width=""><?php echo e('Tender Type'); ?></th>


                                    <th class="text-center" width=""><?php echo e('Specification'); ?></th>
                                    <th class="text-center" width=""><?php echo e('Notice'); ?></th>
                                    <th class="text-center"><?php echo e(trans('english.STATUS')); ?></th>
                                    <?php if(!empty(Session::get('acl')[13][3]) || !empty(Session::get('acl')[13][4])){ ?>
                                    <th class="text-center"> <?php echo trans('english.ACTION'); ?>

                                    </th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>

                                <?php if(!$tenders->isEmpty()): ?>

                                    <?php
                                        $page = \Input::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page-1)*10;
                                        $l = 1;
                                    ?>
                                    <?php $__currentLoopData = $tenders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(++$sl); ?></td>
                                            <td><?php echo e($sc->tender_title); ?></td>
                                            <td><?php echo e($sc->tender_number); ?></td>

                                            <td><?php echo e(date('d-m-Y',strtotime($sc->tender_opening_date))); ?></td>
                                            <td><?php echo e($sc->supplyCategoryName->name); ?></td>

                                            <!-- <td>
                                                <?php if(!empty($sc->open_tender)): ?>
                                                    <?php echo 'Yes'; ?>

                                                <?php else: ?>
                                                    <?php echo 'No'; ?>

                                                <?php endif; ?>
                                            </td> -->

                                            <td>
                                                <?php if($sc->tender_type == 1): ?> <?php echo 'LTM- Limited Tender Method'; ?>

                                                <?php elseif($sc->tender_type == 2): ?> <?php echo 'OTM- Open Tender Method'; ?>

                                                <?php elseif($sc->tender_type == 3): ?> <?php echo 'RTM- Restricted Tender Method'; ?>

                                                <?php elseif($sc->tender_type == 4): ?> <?php echo 'Spot Tender'; ?>

                                                <?php elseif($sc->tender_type == 5): ?> <?php echo 'DPM- Direct Purchase Method'; ?>

                                                <?php endif; ?>
                                            </td>

                                            <td style="text-align: center;">
                                                <?php if(!empty($sc->specification)): ?>
                                                    <a href="<?php echo e(url('tender/specification-pdf/'.encrypt($sc->id))); ?>" target="_blank"><img width="30" height="30" src="<?php echo e(URL::to('/')); ?>/public/uploads/gallery/pdf_icon.png"></a>
                                                <?php endif; ?>
                                                <?php if(!empty($sc->specification_doc)): ?>
                                                    &nbsp;&nbsp;<a href="<?php echo e(url('tender/specification-doc/'.encrypt($sc->id))); ?>" target="_blank"><img width="30" height="30" src="<?php echo e(URL::to('/')); ?>/public/uploads/gallery/word-icon.png"></a>
                                                <?php endif; ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <?php if(!empty($sc->notice)): ?>
                                                    <a href="<?php echo e(url('tender/notice-pdf/'.encrypt($sc->id))); ?>" target="_blank"><img width="30" height="30" src="<?php echo e(URL::to('/')); ?>/public/uploads/gallery/pdf_icon.png"></a>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($sc->status_id == '1'): ?>
                                                    <span class="label label-success"><?php echo 'Published'; ?></span>
                                                <?php else: ?>
                                                    <span class="label label-warning"><?php echo 'Unpublished'; ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <?php if(!empty(Session::get('acl')[13][3]) || !empty(Session::get('acl')[13][4])){ ?>
                                            <td class="action-center">
                                                <div class="text-center">


                                                    <?php if(!empty(Session::get('acl')[13][3])){ ?>
                                                    <a class="btn btn-primary btn-xs" href="<?php echo e(URL::to('manual-tender/edit/' . $sc->id)); ?>" title="Edit">
                                                        <i class="icon-edit"></i>
                                                    </a>
                                                    <?php } ?>

                                                    <?php if(!empty(Session::get('acl')[13][4])){?>
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
                                        <td colspan="16"><?php echo e('Empty Data'); ?></td>
                                    </tr>
                                <?php endif; ?>

                                </tbody>
                            </table><!---/table-responsive-->
                            </div>
                            <?php echo e($tenders->links()); ?>


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
                var url='<?php echo URL::to('manual-tender/destroy'); ?>'+'/'+id;
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