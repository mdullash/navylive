<?php $__env->startSection('content'); ?>

<div class="small-header transition animated fadeIn">
    <div class="hpanel">
        <div class="panel-body">
            <h2 class="font-light m-b-xs">
                <?php echo trans('english.USER_MANAGEMENT'); ?>

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
                   <?php  if(!empty(Session::get('acl')[3][2])){ ?>
                    <div class="pull-right">
                        <a class="btn btn-info btn-effect-ripple" href="<?php echo URL::to('users/create'); ?>"><i class="fa fa-plus"></i> <?php echo trans('english.CREATE_A_USER'); ?></a>
                    </div>
                   <?php } ?>
                    <h4><?php echo trans('english.USER_MANAGEMENT'); ?></h4>
                </div>
                <div class="panel-body">
                    <?php echo Form::open(array('role' => 'form', 'url' => 'users/filter', 'class' => 'form-horizontal', 'id' => 'accountHeadCreate')); ?>

                    <div class="form-group col-md-4">
                        <div class="form-group">
                            <div class="col-md-12">
                                <?php echo Form::text('username', old('username'), array('id'=> 'username', 'class' => 'form-control','placeholder'=>'Username')); ?>

                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <div class="col-md-12">
                            <?php echo Form::select('userRole', $userRole, old('userRole'), array('class' => 'form-control js-source-states', 'id' => 'userRole')); ?>

                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <div class="col-md-12">
                            <?php echo Form::select('status',['0'=>'Select Status','1'=>'Active','2'=>'Inactive'], old('userRole'), array('class' => 'form-control js-source-states', 'id' => 'userRole')); ?>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-5">
                            <button type="submit" class="btn btn-primary"> <?php echo trans('english.FILTER'); ?></button>
                        </div>
                    </div>

                    <?php echo Form::close(); ?>


                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped middle-align">
                            <thead>
                                <tr class="center">
                                    <th><?php echo trans('english.SL_NO'); ?></th>
                                    <th><?php echo trans('english.USERNAME'); ?></th>
                                    <th><?php echo trans('english.USER_ROLE'); ?></th>
                                    <th>Full name</th>

                                    <th class='text-center'>Email</th>
                                    <th class='text-center'>Contact</th>
                                    
                                    <th class='text-center'>Zones</th>
                                    <th class='text-center'>Organization</th>
                                    <th class='text-center'>Categories</th>
                                    <th class='text-center'>Rank</th>
                                    <th class='text-center'>Designation</th>
                                    <th><?php echo trans('english.STATUS'); ?></th>
                                    <?php  if(!empty(Session::get('acl')[3][3]) || !empty(Session::get('acl')[3][4]) || !empty(Session::get('acl')[3][7])){ ?>
                                    <th class='text-center'><?php echo trans('english.ACTION'); ?></th>
                                    <?php  } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!$usersArr->isEmpty()): ?>
                                <?php
                                    $sl=1;

                                    function categoriesName($id){
                                     $supplyCatName = \App\SupplyCategory::where('id',$id)->value('name');
                                     return $supplyCatName;
                                    }
                                ?>
                                <?php $__currentLoopData = $usersArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr class="contain-center">
                                    <td><?php echo $sl++; ?></td>
                                    <td><?php echo $value->username; ?></td>
                                    <td><?php echo $value->Role['name']; ?></td>
                                    <td><?php echo ucfirst($value->first_name); ?> <?php echo ucfirst($value->last_name); ?></td>
                                    <td><?php echo $value->email; ?></td>
                                    <td><?php echo $value->contact_no; ?></td>
                                    
                                    <td>
                                        <?php
                                        $zoneids = explode(',',$value->zones);
                                        foreach ($zoneids as $zids) {
                                            $valsss = \App\Http\Controllers\RegistredNsdNameController::zone_name($zids);
                                            echo "<li>".$valsss."</li>";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $nsdbsdids = explode(',',$value->nsd_bsd);
                                        foreach ($nsdbsdids as $nbids) {
                                            $valsss = \App\Http\Controllers\UsersController::nsd_bsd_name($nbids);
                                            echo "<li>".$valsss."</li>";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $suppCatIds = explode(',',$value->categories_id);
                                        foreach ($suppCatIds as $sci) {
                                            $valsssss = categoriesName($sci);
                                            echo "<li>".$valsssss."</li>";
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $value->rank; ?></td>
                                    <td><?php echo $value->designation; ?></td>
                                    <td>
                                        <?php if($value->status_id == '1'): ?>
                                        <span class="label label-success"><?php echo trans('english.ACTIVE'); ?></span>
                                        <?php else: ?>
                                        <span class="label label-warning"><?php echo trans('english.INACTIVE'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <?php  if(!empty(Session::get('acl')[3][3]) || !empty(Session::get('acl')[3][4]) || !empty(Session::get('acl')[3][5]) || !empty(Session::get('acl')[3][7])){ ?>
                                    <td class="action-center">
                                        <div class='text-center'>
                                            <?php echo Form::open(array('url' => 'users/' . $value->id)); ?>

                                            <?php echo Form::hidden('_method', 'DELETE'); ?>


                                            <?php  if(!empty(Session::get('acl')[3][11])){ ?>
                                            <a class='btn btn-info btn-xs' href="<?php echo URL::to('users/activate/' . $value->id ); ?><?php if(isset($param)): ?><?php echo '/'.$param; ?> <?php endif; ?>" data-rel="tooltip" title="<?php if($value->status_id == '1'): ?> Inactivate <?php else: ?> Activate <?php endif; ?>" data-placement="top">
                                                <?php if($value->status_id == '1'): ?>
                                                <i class='fa fa-remove'></i>
                                                <?php else: ?>
                                                <i class='fa fa-circle-o'></i>
                                                <?php endif; ?>
                                            </a>
                                            <?php  } ?>
                                            <?php  if(!empty(Session::get('acl')[3][3])){ ?>
                                            <a class='btn btn-primary btn-xs' href="<?php echo URL::to('users/' . $value->id . '/edit'); ?>">
                                                <i class='fa fa-pencil'></i>
                                            </a>
                                            <?php  } ?>
                                            <?php  if(!empty(Session::get('acl')[3][7])){ ?>
                                            <a href="<?php echo URL::to('users/cp/' . $value->id); ?><?php if(isset($param)): ?><?php echo '/'.$param; ?> <?php endif; ?>" data-original-title="Change Password">
                                                <span class="btn btn-success btn-xs"> 
                                                    <i class="fa fa-key"></i>
                                                </span>
                                            </a>
                                            <?php  } ?>
                                            <?php  if(!empty(Session::get('acl')[3][4])){ ?>
                                            <button class="btn btn-danger btn-xs delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                                <i class='fa fa-trash'></i>
                                            </button>
                                            <?php } ?>

                                            <?php echo Form::close(); ?>

                                        </div>
                                    </td>
                                    <?php  } ?>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="9"><?php echo trans('english.EMPTY_DATA'); ?></td>
                                </tr>
                                <?php endif; ?> 
                            </tbody>
                        </table><!---/table-responsive-->
                        <?php echo $usersArr->render(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>