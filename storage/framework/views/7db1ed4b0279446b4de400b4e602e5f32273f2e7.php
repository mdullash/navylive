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
                    <h3>
                        Float Tender
                    </h3>
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
                        <h3>
                            Float Tender


                            <?php if($demandDetailPageFromRoute=='floating-tender-acc'): ?>
                                &nbsp; &nbsp; &nbsp;    <a class="btn btn-info btn-effect-ripple pull-right" href="<?php echo e(URL::to('direct-item-dmnd-create')); ?>"><i class="fa fa-plus"></i> Create Tender</a>
                            <?php endif; ?>

                            <?php if(!empty(Session::get('acl')[13][2])){ ?>
                            <div class="pull-right">
                                <a class="btn btn-warning btn-effect-ripple" href="<?php echo e(URL::to('manual-tender/create')); ?>"><i class="fa fa-plus"></i> Create Manual Tender</a>
                            </div>
                            <?php } ?>



                        </h3>
                    </div>
                        <div class="panel-body">
                            
                        <!-- Tab section -->
                        <ul class="nav nav-tabs">
                            <?php
                                $segOne = \Request::segment(1);
                                $segTwo = \Request::segment(2);

                                $searchFormSubUrl = $segOne.'/'.$segTwo;
                            ?>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                    <?php echo e(Form::open(array('role' => 'form', 'url' => $searchFormSubUrl, 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'schedule-all'))); ?>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="requester">Demanding: </label>
                                            <select class="form-control selectpicker requester" name="requester" id="requester"  data-live-search="true">
                                                <option value=""><?php echo '- Select -'; ?></option>
                                                <?php $__currentLoopData = $demandeNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dmdn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo $dmdn->id; ?>" <?php if($demande==$dmdn->id): ?> selected <?php endif; ?>><?php echo $dmdn->name; ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="email">Demand No: </label>
                                                <?php echo Form::text('demand_no', $demand_no, array('id'=> 'demand_no', 'class' => 'form-control', 'autocomplete'=> 'off')); ?>

                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="email">Tender No: </label>
                                                <?php echo Form::text('tender_no', '', array('id'=> 'tender_no', 'class' => 'form-control', 'autocomplete'=> 'off')); ?>

                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="email">From: </label>
                                                <?php echo Form::text('from', $from, array('id'=> 'from', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off','readonly')); ?>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="email">To: </label>
                                                <?php echo Form::text('todate', $todate, array('id'=> 'todate', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off','readonly')); ?>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-md-12" style="padding-top: 18px;">
                                                <label for="submit"></label>
                                                <button type="submit" class="form-control btn btn-primary"><?php echo 'Search'; ?></button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php echo Form::close(); ?>

                                    </div>
                                </div>
                                

                                    <?php if(!empty(Session::get('acl')[34][17])){ ?>
                                    <li <?php if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==1): ?>class="active" <?php endif; ?>><a href="<?php echo e(URL::to('floating-tender-acc/1')); ?>"> Pending</a></li>

                                    <?php if(!empty(Session::get('acl')[34][29])){ ?>
                                    <li <?php if($demandDetailPageFromRoute=='retender-view-acc' && $segTwo==1): ?>class="active" <?php endif; ?>><a href="<?php echo e(URL::to('retender-view-acc/1')); ?>"> Retender</a></li>
                                    <?php } ?>

                                    <?php if(!empty(Session::get('acl')[34][26]) || !empty(Session::get('acl')[34][17])){ ?>
                                    <li <?php if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3): ?>class="active" <?php endif; ?>><a href="<?php echo e(URL::to('floating-tender-acc/3')); ?>"> Waiting for Approve</a></li>
                                    <?php } ?>

                                    <li <?php if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==2): ?>class="active" <?php endif; ?>><a href="<?php echo e(URL::to('floating-tender-acc/2')); ?>"> Approved</a></li>

                                    <li <?php if($segOne=='manual-tender'): ?>class="active" <?php endif; ?>><a href="<?php echo e(URL::to('manual-tender/view')); ?>"> Manual Tender</a></li>


                                
                                    <?php } ?>
                            </ul>
                            
                            <?php //if(!empty(Session::get('acl')[34][1])){ ?>
                            <div class="table-responsive">
                                <?php if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3 ): ?>
                                    <?php echo e(Form::open(array('role' => 'form', 'url' => 'approve-multiple-tender', 'files'=> true, 'class' => '', 'id'=>'demands'))); ?>

                                <?php endif; ?>
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <?php if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3 ): ?>
                                        <th class="text-center">
                                            <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                    <input class="activity_1 activitycell" type="checkbox" id="all_check" name="" value="">
                                                    <label for=""></label>
                                                </div>
                                            </div>
                                        </th>
                                    <?php endif; ?>
                                    <th class="text-center"><?php echo e('Demanding'); ?></th>
                                    <th class="text-center"><?php echo e('Demand No'); ?></th>
                                    <th class="text-center"><?php echo e('Items & Quantity'); ?></th>
                                    <th class="text-center"><?php echo e('Tender Number'); ?></th>
                                    <th class="text-center"><?php echo e('Total Quantity'); ?></th>
                                    <?php if(!empty(Session::get('acl')[34][3]) || !empty(Session::get('acl')[34][4])){ ?>
                                    <th class="text-center"> <?php echo trans('english.ACTION'); ?>

                                    </th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>

                                <?php if(!$demands->isEmpty()): ?>

                                    <?php
                                        $page = \Input::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page-1)*10;
                                        $l = 1;
                                    ?>

                                    <?php $__currentLoopData = $demands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(++$sl); ?></td>

                                            <?php if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3 ): ?>
                                                <td>
                                                    <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                        <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                            <input class="activity_1 activitycell selectedTender" type="checkbox" id="" name="selectedTender[]" value="<?php echo $sc->id.'&'.$sc->tenderId; ?>">
                                                            <label for=""></label>
                                                        </div>
                                                    </div>
                                                </td>
                                            <?php endif; ?>

                                            <?php if($demandDetailPageFromRoute=='collection-quotation-acc' || ($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3 || $segTwo==4)
                                             || $demandDetailPageFromRoute=='cst-view-acc' || $demandDetailPageFromRoute=='draft-cst-view-acc'
                                              || $demandDetailPageFromRoute=='hdq-approval-acc' || $demandDetailPageFromRoute=='po-generation-acc'
                                              || $demandDetailPageFromRoute=='cr-view-acc'  || $demandDetailPageFromRoute=='inspection-view-acc'
                                               || $demandDetailPageFromRoute=='v44-voucher-view-acc' || $demandDetailPageFromRoute=='retender-view-acc'): ?>
                                                <td>
                                                    <?php if(!empty($sc->requester)): ?>

                                                        <?php 
                                                            $reuisters = explode(',', $sc->requester); 
                                                            $reuisters = array_unique($reuisters);
                                                        ?>
                                                        <?php $__currentLoopData = $reuisters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php echo \App\Http\Controllers\SelectLprController::requestename($req).'; '; ?>

                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                    <?php endif; ?>
                                                </td>
                                            <?php else: ?>
                                                <td>
                                                    <?php if(!empty($sc->requester)): ?>

                                                        <?php 
                                                            $reuisters = explode(',', $sc->requester); 
                                                            $reuisters = array_unique($reuisters);
                                                        ?>
                                                        <?php $__currentLoopData = $reuisters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php echo \App\Http\Controllers\SelectLprController::requestename($req).'; '; ?>

                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                    <?php endif; ?>
                                                </td>
                                            <?php endif; ?>
                                            <td style="word-break: break-all;"><?php echo $sc->demand_no; ?></td>
                                            <td>
                                                <?php 
                                                    if(count($sc->itemsToDemand)<1 && isset($sc->tenderId)){
                                                        $sc->itemsToDemand = \App\ItemToDemand::where('tender_no','=',$sc->tenderId)->where('lpr_id','=',$sc->id)->get();
                                                    }
                                                    $remComma = 1;
                                                    $num_of_items = count($sc->itemsToDemand->unique('item_name'));         
                                                ?>
                                                <?php if(!empty($sc->itemsToDemand) && count($sc->itemsToDemand->unique('item_name')) > 0): ?>
                                                    <?php $__currentLoopData = $sc->itemsToDemand->unique('item_name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ke => $itmsf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $deno = \App\Deno::find($itmsf->deno_id);
                                                        ?>
                                                        <?php echo $itmsf->item_name; ?>

                                                        (
                                                            <?php if(!empty($deno->name)): ?>
                                                                <?php echo e($deno->name); ?>

                                                            <?php endif; ?>
                                                            
                                                            <?php if(!empty($itmsf->unit)): ?>
                                                                <?php echo $itmsf->unit; ?>

                                                            <?php endif; ?>
                                                           )
                                                        <?php if($num_of_items > $remComma): ?>
                                                            <?php echo '; '; ?>

                                                        <?php endif; ?>
                                                        <?php $remComma++; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $sc->tender_number; ?></td>
                                            <td><?php echo $sc->total_unit; ?></td>

                                            <td class="action-center">
                                                <div class="text-center">

                                                    <?php if(!empty(Session::get('acl')[34][26]) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 3){ ?>
                                                        
                                                        <a class="btn btn-success btn-xs showModal" href="javascript:void(0)" title="Approve Tender" attr-demandid-updateflds="<?php echo $sc->id.'&2&'.$sc->tenderId; ?>">
                                                            <i class="icon-check"> </i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][17]) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 2 && empty($sc->tender_quation_collection)){ ?>
                                                        <a class="btn btn-warning btn-xs" href="<?php echo e(URL::to('direct-item-dmnd-edit/' . $sc->tenderId)); ?>" title="Edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                     <?php } ?>
                                                     <?php if(!empty(Session::get('acl')[34][17]) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 3){ ?>
                                                        <a class="btn btn-warning btn-xs" href="<?php echo e(URL::to('direct-item-dmnd-edit/' . $sc->tenderId)); ?>" title="Edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][17]) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 4){ ?>
                                                        
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][17]) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 1 ){ ?>
                                                        <a class="btn btn-success btn-xs" href="<?php echo e(URL::to('floating-tender/create/'.$sc->id)); ?>" title="Create Tender">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if((!empty(Session::get('acl')[34][26]) || !empty(Session::get('acl')[34][17])) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 3 ){ ?>
                                                            <a class="btn btn-primary btn-xs" href="<?php echo e(URL::to('floating-tender-get-view/'.$sc->tenderId.'&1')); ?>" title="View Tender" target="_blank">
                                                                    <i class="icon-eye-open"></i>
                                                                </a>
                                                    <?php } ?>
                                                    <?php if( $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 2 ){ ?>
                                                                <a class="btn btn-primary btn-xs" href="<?php echo e(URL::to('floating-tender-get-view/'.$sc->tenderId.'&2')); ?>" title="View Tender" target="_blank">
                                                                    <i class="icon-print"> </i>
                                                                </a>
                                                    <?php } ?>
                                                    
                                                    <!-- End Newly added ===============================
                                                    =========================================== -->

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
                            <?php if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3 ): ?>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><?php echo 'Approve Tenders'; ?></button>
                                </div>
                                <?php echo Form::close(); ?>

                            <?php endif; ?>
                            </div>
                            
                        <?php 
                            $segments = \Request::segments();
                            $routeName = $segments[0].(isset($segments[1]) ? '/'.$segments[1] : '');

                        ?>
                        <?php if($routeName !='group-check-acc/3' ): ?>
                            <?php echo e($demands->appends(Request::except('page'))->links()); ?>

                        <?php endif; ?> 

                        </div>
                    </div>
                </div>

            </div>

    </div>


    <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4><span class="glyphicon glyphicon-lock"></span> Approved</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <?php echo e(Form::open(array('role' => 'form', 'url' => 'demand-pending-post', 'files'=> true, 'class' => 'demand-pending-post', 'id'=>'demand-pending-post'))); ?>

                <input type="hidden" name="demandId" id="demandId" value="">
                <input type="hidden" name="updateFilelds" id="updateFilelds" value="">
                <input type="hidden" name="tenderId" id="tenderId" value="">


                <div class="form-group"><label class="control-label" for="status">Status :<span class="text-danger">*</span></label>
                    <?php echo e(Form::select('demand_approval', array('1' => 'Approved', '2' =>'Reject'), '', array('class' => 'form-control selectpicker', 'id' => 'demand_approval','required'))); ?>

                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary pull-right"><?php echo 'Action'; ?></button>
                </div>
                            
          <?php echo Form::close(); ?>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
          
        </div>
      </div>
      
    </div>
</div> 


<script type="text/javascript">
    $(document).ready(function(){

        $(document).on('click','#all_check',function(){
            if(this.checked){
                $('.selectedTender').each(function(){
                    this.checked = true;
                });
            }else{
                 $('.selectedTender').each(function(){
                    this.checked = false;
                });
            }
        });

        $(document).on('click','.showModal',function(){
            var attrVlues = $(this).attr('attr-demandid-updateflds');
            var result = attrVlues.split('&');
            $("#demandId").val('');
            $("#updateFilelds").val('');
            $("#tenderId").val('');

            $("#demandId").val(result[0]);
            $("#updateFilelds").val(result[1]);
            $("#tenderId").val(result[2]);

            $('#myModal').modal('show');
        });
        

        /*For Delete Department*/
        $(".exbtovdelete").click(function (e) {
            e.preventDefault();
            
            var id = this.id; 
            var url='<?php echo URL::to('demand-delete'); ?>'+'/'+id;
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