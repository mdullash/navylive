<?php
$currentControllerName = Request::segment(1);
$currentControllerName1 = Request::segment(2);
$action = Route::currentRouteAction();
$aclList = Session::get('acl');
$currentPath = Request::path();

?>

<aside id="menu">
    <div id="navigation">
        <div class="profile-picture">
            <a href="<?php echo e(URL::to('dashboard')); ?>">
                <?php if(isset(Auth::user()->photo)): ?>
                    <img class="img-circle m-b" width="76" height="76" src="<?php echo e(URL::to('/')); ?>/public/uploads/thumbnail/<?php echo e(Auth::user()->photo); ?>">
                <?php else: ?>
                    <img class="img-circle m-b" width="76" height="76" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png">
                <?php endif; ?>
            </a>

            <div class="stats-label text-color">
                <span class="font-extra-bold font-uppercase"><?php echo e(Auth::user()->first_name.' '. Auth::user()->last_name); ?></span>

                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                        <small class="text-muted"><b class="caret"></b></small>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="<?php echo e(URL::to('users/profile/')); ?>"><?php echo e(trans('english.PROFILE')); ?></a></li>
                        <li><a href="<?php echo e(URL::to('users/cpself/')); ?>"><?php echo e(trans('english.CHANGE_PASSWORD')); ?></a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo e(URL::to('logout')); ?>"><?php echo e(trans('english.SIGN_OUT')); ?></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <ul class="nav" id="side-menu">


            <li <?php $current = ($currentControllerName == 'dashboard') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                <a href="<?php echo URL::to('dashboard'); ?>"><span class="nav-label">Dashboard</span></a>
            </li>


            <?php if (!empty($aclList[3][1]) || !empty($aclList[1][1]) || !empty($aclList[2][1])  || !empty($aclList[6][1])) { ?>
            <li <?php $current = ($currentControllerName == 'role' || $currentControllerName == 'roleacl' || $currentControllerName == 'useracl' || $currentControllerName == 'modulelist' || $currentControllerName == 'activitylist' || $currentControllerName == 'users') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                <a href="#"><span class="nav-label"><?php echo e(trans('english.ACCESS_CONTROL')); ?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li <?php $current = ( $currentControllerName == 'users') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                        <a href="<?php echo e(URL::to('users')); ?>"><span class="nav-label"><?php echo e(trans('english.USER_MANAGEMENT')); ?></a>

                    </li>
                    <?php if (!empty($aclList[1][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'role') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('role')); ?>"><?php echo e(trans('english.ROLE_MANAGEMENT')); ?></a></li>
                    <?php } ?>
                    <?php if (!empty($aclList[2][1])) { ?>

                    <li <?php $current = ($currentControllerName == 'roleacl') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('roleacl')); ?>"><?php echo e(trans('english.ROLE_ACCESS_CONTROL')); ?></a></li>

                    <?php }   ?>

                    <?php if (!empty($aclList[6][1])) { ?>

                    <li <?php $current = ($currentControllerName == 'useracl') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('useracl')); ?>"><?php echo e(trans('english.USER_ACCESS_CONTROL')); ?></a></li>
                    <?php } ?>

                </ul>
            </li>
            <?php } ?>

               <?php if(\Session::get("zoneAlise") == "bsd"){ ?>
            <?php if (!empty($aclList[38][1]) ) { ?>
            <li <?php $current = ($currentControllerName == 'tender' || $currentControllerName == 'strength-calculation') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                <a href="#"><span class="nav-label">Strength Calculation</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">

                    <?php if (!empty($aclList[38][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'strength-calculation') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('strength-calculation')); ?>">Strength</a></li>
                    <?php }   ?>

                </ul>
            </li>
            <?php } ?>
            <?php } ?>

            <?php if (!empty($aclList[34][1]) || !empty($aclList[34][2]) || !empty($aclList[34][16]) || !empty($aclList[34][17]) ) { ?>
                <li <?php $current = ($currentControllerName == 'demand' || $currentControllerName == 'demand-details' || $currentControllerName == 'demand-group') ? 'active' : ''; ?> class="<?php echo $current; ?> hidden">
                    <a href="<?php echo URL::to('demand'); ?>"><span class="nav-label">Procurement Management</span></a>
                </li>
            <?php } ?>

                
	        <?php if (!empty($aclList[34][1]) || !empty($aclList[34][2]) ) { ?>
            <li <?php $current = ($currentControllerName == 'demand' || ($currentControllerName == 'demand-details' && \Session::get('demandDetailPageFromRoute')=='demand-pending') || $currentControllerName == 'demand-group' || $currentControllerName == 'demand-pending' || $currentControllerName == 'demand-get-approve') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                <a href="#"><span class="nav-label"><?php echo e('Demand Management'); ?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">

			        <?php if ( !empty($aclList[34][1]) || !empty($aclList[34][2]) || !empty($aclList[34][12]) ) { ?>
                    <li <?php $current = ($currentControllerName == 'demand' || ($currentControllerName == 'demand-details' && \Session::get('demandDetailPageFromRoute')=='demand-pending') || $currentControllerName == 'demand-group' || $currentControllerName == 'demand-pending' || $currentControllerName == 'demand-get-approve') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                    <!-- <a href="<?php echo URL::to('demand'); ?>"><span class="nav-label">Demands</span></a> -->
                        <a href="<?php echo URL::to('demand-pending/1'); ?>"><span class="nav-label">Demands</span></a>
                    </li>
			        <?php } ?>
                </ul>
            </li>
	        <?php } ?>
                

            
	        <?php if ( !empty($aclList[34][16])  || !empty($aclList[34][23]) || !empty($aclList[34][28]) || !empty($aclList[34][35]) || !empty($aclList[53][35]) ) { ?>
            <li <?php $current = ($currentControllerName == 'group-check-acc' || ($currentControllerName == 'demand-details' && \Session::get('demandDetailPageFromRoute')=='group-check-acc') || $currentControllerName == 'direct-item-dmnd/create' || $currentControllerName == 'cr-view-acc' || $currentControllerName == 'cr-section' || $currentControllerName == 'v44-voucher-view-acc'|| $currentControllerName == 'issue') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                <a href="#"><span class="nav-label"><?php echo e('warehouse Management'); ?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">

			        <?php if ( !empty($aclList[34][16]) || !empty($aclList[34][28]) ) { ?>
                    <li <?php $current = ($currentControllerName == 'group-check-acc' || $currentControllerName == 'direct-item-dmnd/create' || ($currentControllerName == 'demand-details' && \Session::get('demandDetailPageFromRoute')=='group-check-acc') ) ? 'active' : ''; ?> class="<?php echo $current; ?>">
                        <a href="<?php echo URL::to('group-check-acc/2'); ?>"><span class="nav-label">Group</span></a>
                    </li>
			        <?php } ?>

			        <?php if ( !empty($aclList[34][23]) ) { ?>
                    <li <?php $current = ($currentControllerName == 'cr-view-acc' || $currentControllerName == 'cr-section') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                        <a href="<?php echo URL::to('cr-view-acc/1'); ?>"><span class="nav-label">Central Receive</span></a>
                    </li>
			        <?php } ?>

			        <?php if ( !empty($aclList[34][35]) ) { ?>
                    <li <?php $current = ($currentControllerName == 'v44-voucher-view-acc') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                        <a href="<?php echo URL::to('v44-voucher-view-acc/1'); ?>"><span class="nav-label">D44B voucher</span></a>
                    </li>
			        <?php } ?>

                        <?php if ( !empty($aclList[53][1]) ) { ?>
                        <li <?php $current = ($currentControllerName == 'issue') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                            <a href="<?php echo URL::to('issue/1'); ?>"><span class="nav-label">Issue</span></a>
                        </li>
                        <?php } ?>

                </ul>
            </li>
	        <?php } ?>
            

            <?php if ( !empty($aclList[34][17]) || !empty($aclList[34][18]) || !empty($aclList[34][19]) || !empty($aclList[34][20]) || !empty($aclList[34][21]) || !empty($aclList[34][22]) || !empty($aclList[34][25]) || !empty($aclList[35][1]) || !empty($aclList[34][12]) || !empty($aclList[34][26]) || !empty($aclList[34][29]) || !empty($aclList[42][1]) || !empty($aclList[54][1]) || !empty($aclList[54][1]) || !empty($aclList[55][1])  ) { ?>
            <li <?php $current = ($currentControllerName == 'floating-tender-acc' || $currentControllerName == 'collection-quotation-acc' || $currentControllerName == 'cst-view-acc' || $currentControllerName == 'draft-cst-view-acc' || $currentControllerName == 'hdq-approval-acc' || $currentControllerName == 'po-generation-acc' || $currentControllerName == 'headquarte-approval' || $currentControllerName == 'schedule-all' || $currentControllerName == 'schedule-create' || $currentControllerName == 'floating-tender' || $currentControllerName == 'create-collection-quotation' || $currentControllerName == 'cst-view' || $currentControllerName == 'draft-cst-view' || $currentControllerName == 'po-generate-view' || $currentControllerName == 'retender-view-acc' || $currentControllerName == 'retender-create' || $currentControllerName == 'nil-return' || $currentControllerName == 'nil-return-create' || $currentControllerName == 'bill' ||$currentControllerName == 'cst-forwarding') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                <a href="#"><span class="nav-label"><?php echo e('Procurement Management'); ?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">

                   <?php if ( !empty($aclList[34][17]) || !empty($aclList[34][26]) || !empty($aclList[34][29]) ) { ?>
                        <li <?php $current = ($currentControllerName == 'floating-tender-acc' || $currentControllerName == 'floating-tender' || $currentControllerName == 'retender-view-acc') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                            <a href="<?php echo URL::to('floating-tender-acc/1'); ?>"><span class="nav-label">Tender</span></a>
                        </li>
                    <?php } ?>
                    <?php /* if ( !empty($aclList[34][29]) ) { ?>
                    <li <?php $current = ($currentControllerName == 'retender-view-acc' || $currentControllerName == 'retender-create') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                        <a href="{!! URL::to('retender-view-acc/1') !!}"><span class="nav-label">Retender</span></a>
                    </li>
                    <?php } */?>
                    <?php if (!empty($aclList[35][1]) ) { ?>
                        <li <?php $current = ($currentControllerName == 'schedule-all' || $currentControllerName == 'schedule-create') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                            <a href="<?php echo URL::to('schedule-all'); ?>"><span class="nav-label">Participant</span></a>
                        </li>
                    <?php } ?>
                    <?php if ( !empty($aclList[34][18]) || !empty($aclList[34][27]) ) { ?>
                        <li <?php $current = ($currentControllerName == 'collection-quotation-acc' || $currentControllerName == 'create-collection-quotation') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                            <a href="<?php echo URL::to('collection-quotation-acc/1'); ?>"><span class="nav-label">Quotation Collection </span></a>
                        </li>
                    <?php } ?>
                    <?php if ( !empty($aclList[34][19]) ) { ?>
                        <li <?php $current = ($currentControllerName == 'cst-view-acc' || $currentControllerName == 'cst-view') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                            <a href="<?php echo URL::to('cst-view-acc/5'); ?>"><span class="nav-label">CST</span></a>
                        </li>
                    <?php } ?>
                    <?php if ( !empty($aclList[42][1]) ) { ?>
                        <li <?php $current = ($currentControllerName == 'nil-return' || $currentControllerName == 'nil-return-create') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                            <a href="<?php echo URL::to('nil-return/1'); ?>"><span class="nav-label">Nil Return</span></a>
                        </li>
                    <?php } ?>
                    <?php if ( !empty($aclList[54][1]) ) { ?>
                        <li <?php $current = ($currentControllerName == 'cst-forwarding') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                            <a href="<?php echo URL::to('/cst-forwarding/pending'); ?>"><span class="nav-label">CST Forwarding</span></a>
                        </li>
                    <?php } ?>
                    <?php if ( !empty($aclList[34][20]) ) { ?>
                        <li <?php $current = ($currentControllerName == 'draft-cst-view-acc' || $currentControllerName == 'draft-cst-view') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                            <a href="<?php echo URL::to('draft-cst-view-acc/1'); ?>"><span class="nav-label">NSSD Approval</span></a>
                        </li>
                    <?php } ?>
                    <?php if ( !empty($aclList[34][21]) ) { ?>
                        <li <?php $current = ($currentControllerName == 'hdq-approval-acc' || $currentControllerName == 'headquarte-approval' ) ? 'active' : ''; ?> class="<?php echo $current; ?>">
                            <a href="<?php echo URL::to('hdq-approval-acc/1'); ?>"><span class="nav-label">NHQ Approval</span></a>
                        </li>
                    <?php } ?>
                    <?php if ( !empty($aclList[34][22]) ) { ?>
                        <li <?php $current = ($currentControllerName == 'po-generation-acc' || $currentControllerName == 'po-generate-view') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                            <a href="<?php echo URL::to('po-generation-acc/1'); ?>"><span class="nav-label">Purchase Order</span></a>
                        </li>
                    <?php } ?>

                       <?php /* if ( !empty($aclList[34][35]) ) { ?>
                       <li <?php $current = ($currentControllerName == 'bill') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                           <a href="{!! URL::to('bill/1') !!}"><span class="nav-label">BILL</span></a>
                       </li>
                       <?php } */?>

                       <?php if ( !empty($aclList[55][1]) ) { ?>
                       <li <?php $current = ($currentControllerName == 'bill') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                           <a href="<?php echo URL::to('/bill/pending'); ?>"><span class="nav-label">BILL</span></a>
                       </li>
                       <?php } ?>
                </ul>
            </li>
            <?php } ?>

            
	        <?php if ( !empty($aclList[34][24]) ) { ?>
            <li <?php $current = ($currentControllerName == 'inspection-view-acc' || $currentControllerName == 'inspection-section') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                <a href="#"><span class="nav-label"><?php echo e('Inspection Management'); ?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">

	                <?php if ( !empty($aclList[34][24]) ) { ?>
                    <li <?php $current = ($currentControllerName == 'inspection-view-acc' || $currentControllerName == 'inspection-section') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                        <a href="<?php echo URL::to('inspection-view-acc/1'); ?>"><span class="nav-label">Inspection</span></a>
                    </li>
	                <?php } ?>
                </ul>
            </li>
	        <?php } ?>
            

            <?php if ( !empty($aclList[35][1]) || !empty($aclList[35][2]) ) { ?>
                <!-- <li <?php $current = ($currentControllerName == 'lp-section') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                    <a href="<?php echo URL::to('lp-section'); ?>"><span class="nav-label">LP Section</span></a>
                </li> -->
            <?php } ?>

            <?php if (!empty($aclList[12][1]) ) { ?>
            <li <?php $current = ($currentControllerName == 'suppliers') ||  ($currentControllerName1 == 'enlistment') ||($currentControllerName1 == 'dni-npm-approval') ||  ($currentControllerName1 == 'sells-form')||  ($currentControllerName1 == 'waiting-for-clarence') ||  ($currentControllerName1 == 'dni') ||  ($currentControllerName1 == 'npm') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                <a href="#"><span class="nav-label"><?php echo e('Supplier Enlistment Management'); ?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">


                        <?php if (!empty($aclList[46][1])) { ?>
                        <li <?php $current = ($currentControllerName1 == 'enlistment') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('suppliers/enlistment/index/pending')); ?>">Enlistment</a></li>
                        <?php }   ?>


                        <?php if (!empty($aclList[47][1])) { ?>
                        <li <?php $current = ($currentControllerName1 == 'sells-form') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('suppliers/sells-form')); ?>">Sell Form</a></li>
                        <?php }   ?>



                        <?php if (!empty($aclList[48][1])) { ?>
                        <li <?php $current = ($currentControllerName1 == 'waiting-for-clarence') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('suppliers/waiting-for-clarence/index/pending')); ?>">Forwarding For Clearance</a></li>
                        <?php }   ?>


                        <?php if (!empty($aclList[49][1])) { ?>
                        <li <?php $current = ($currentControllerName1 == 'dni') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('suppliers/dni/index/pending')); ?>">DNI Clearance</a></li>
                        <?php }   ?>

                        <?php if (!empty($aclList[50][1])) { ?>
                        <li <?php $current = ($currentControllerName1 == 'npm') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('suppliers/npm/index/pending')); ?>">NPM Verification</a></li>
                        <?php }   ?>


                        <?php if (!empty($aclList[51][1])) { ?>
                        <li <?php $current = ($currentControllerName1 == 'dni-npm-approval') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('suppliers/dni-npm-approval')); ?>">Forwarding For Approval</a></li>
                        <?php }   ?>

                        <?php if (!empty($aclList[56][1])) { ?>
                        <li <?php $current = ($currentControllerName1 == 'supplier-approval') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('suppliers/supplier-approval')); ?>">DNS Approval</a></li>
                        <?php }   ?>


                            <?php if (!empty($aclList[57][1])) { ?>
                            <li <?php $current = ($currentControllerName1 == 'renew-supplier') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('suppliers/renew-supplier')); ?>">Renew Supplier</a></li>
                            <?php }   ?>

                            <?php if (!empty($aclList[58][1])) { ?>
                            <li <?php $current = ($currentControllerName1 == 'id-card-purchase') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('suppliers/id-card-purchase')); ?>">ID Card Purchase</a></li>
                            <?php }   ?>


                            <?php if (!empty($aclList[12][1])) { ?>
                            <li <?php $current = ($currentControllerName == 'suppliers') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('suppliers/suppliers')); ?>"><?php echo e(trans('english.SUPPLIERS')); ?></a></li>
                            <?php }   ?>


                </ul>
            </li>
            <?php } ?>

<li <?php $current = ($currentControllerName == 'supplier-chat-list' || $currentControllerName == 'supplier-chat') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                <a href="#"><span class="nav-label"><?php echo e('Supplier Conversation'); ?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li <?php $current = ($currentControllerName == 'supplier-chat-list' || $currentControllerName == 'supplier-chat') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('supplier-chat-list')); ?>"><?php echo e('Supplier Conversation'); ?></a></li>
                </ul>
            </li>

            <?php if (!empty($aclList[14][1]) ) { ?>
            <li <?php $current = ($currentControllerName == 'item') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                <a href="#"><span class="nav-label"><?php echo e('Item Management'); ?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">

                    <?php if (!empty($aclList[14][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'item') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('item/view')); ?>"><?php echo e(trans('english.ITEM')); ?></a></li>
                    <?php }   ?>

                </ul>
            </li>
            <?php } ?>


            <?php if (!empty($aclList[13][1]) ) { ?>
            <li <?php $current = ($currentControllerName == 'tender' || $currentControllerName == 'itemtotender') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                <a href="#"><span class="nav-label"><?php echo e(trans('english.TENDER'). ' Management'); ?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">

                    <?php if (!empty($aclList[13][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'tender') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('tender/view')); ?>"><?php echo e(trans('english.TENDER')); ?></a></li>
                    <?php }   ?>

                </ul>
            </li>
            <?php } ?>

            <?php if (!empty($aclList[17][1]) || !empty($aclList[18][1]) || !empty($aclList[19][1]) || !empty($aclList[20][1]) || !empty($aclList[52][1]) ) { ?>
            <li <?php $current = ($currentControllerName == 'tender-participates' || $currentControllerName == 'awarded-supplier-list' || $currentControllerName == 'cat-pro-supplier-list' || $currentControllerName == 'supplier-report' || $currentControllerName == 'budget-code-wise-item'|| $currentControllerName == 'tender-track') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                <a href="#"><span class="nav-label"><?php echo e(trans('english.REPORTS')); ?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">

                    <?php if (!empty($aclList[20][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'supplier-report') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('supplier-report')); ?>"><?php echo e('Supplier Report'); ?></a></li>
                    <?php }   ?>


                    <?php if (!empty($aclList[18][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'awarded-supplier-list') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('awarded-supplier-list')); ?>"><?php echo e('Awarded Suppliers List'); ?></a></li>
                    <?php }   ?>

                    <?php if (!empty($aclList[17][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'tender-participates') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('tender-participates')); ?>"><?php echo e('Tender Participate'); ?></a></li>
                    <?php }   ?>

                    <?php if (!empty($aclList[19][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'cat-pro-supplier-list') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('cat-pro-supplier-list')); ?>"><?php echo e('Category Wise Supplier List'); ?></a></li>
                    <?php }   ?>

                    <?php if (!empty($aclList[30][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'budget-code-wise-item') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('budget-code-wise-item')); ?>"><?php echo e('Budget Code Wise Item'); ?></a></li>
                    <?php }   ?>

                    <?php if (!empty($aclList[52][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'tender-track') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('tender-track')); ?>"><?php echo e('Tender Tracking'); ?></a></li>
                    <?php }   ?>

                </ul>
            </li>
            <?php } ?>

            <?php if (!empty($aclList[43][1]) || !empty($aclList[44][1]) || !empty($aclList[45][1])) { ?>
            <li <?php $current = ($currentControllerName == 'evaluated-tender' || $currentControllerName == 'evaluated-tender-quaterly' || $currentControllerName == 'yearly-performance-evaluation') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                <a href="#"><span class="nav-label"><?php echo 'EVALUATION'; ?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <?php if (!empty($aclList[43][1])) { ?>
                        <li <?php $current = ($currentControllerName == 'evaluated-tender') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('evaluated-tender')); ?>"><?php echo e('Evaluated Tender'); ?></a></li>
                    <?php }   ?>
                    <?php if (!empty($aclList[44][1])) { ?>
                        <li <?php $current = ($currentControllerName == 'evaluated-tender-quaterly') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('evaluated-tender-quaterly')); ?>"><?php echo e('Evaluated Tender Quaterly'); ?></a></li>
                    <?php }   ?>
                    <?php if (!empty($aclList[45][1])) { ?>
                        <li <?php $current = ($currentControllerName == 'yearly-performance-evaluation') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('yearly-performance-evaluation')); ?>"><?php echo e('Evaluated Tender Yearly'); ?></a></li>
                    <?php }   ?>
                </ul>
            </li>
            <?php } ?>

            <?php if (!empty($aclList[10][1]) || !empty($aclList[11][1]) || !empty($aclList[15][1]) || !empty($aclList[28][1]) || !empty($aclList[31][1]) || !empty($aclList[32][1]) || !empty($aclList[33][1]) || !empty($aclList[21][1]) || !empty($aclList[36][1]) || !empty($aclList[29][1]) || !empty($aclList[37][1]) || !empty($aclList[39][1]) || !empty($aclList[40][1]) || !empty($aclList[40][1]) ) { ?>
            <li <?php $current = ($currentControllerName == 'sup_cat' || $currentControllerName == 'group_name' || $currentControllerName == 'reg_nsd' || $currentControllerName == 'deno' || $currentControllerName == 'zone' || $currentControllerName == 'budget_code' || $currentControllerName == 'currency' || $currentControllerName == 'demande' || $currentControllerName == 'upload-file' || $currentControllerName == 'terms-conditions' || $currentControllerName == 'terms-conditions-category' || $currentControllerName == 'evaluation-point' || $currentControllerName == 'evaluation-position' || $currentControllerName == 'evaluation-criteria') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                <a href="#"><span class="nav-label"><?php echo e(trans('english.ADMINISTRATION')); ?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <?php if (!empty($aclList[10][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'sup_cat') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('sup_cat/supplier_category')); ?>"><?php echo e(trans('english.SUPPLY_CATEGORY')); ?></a></li>
                    <?php } ?>

                    <?php if (!empty($aclList[33][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'group_name') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('group_name')); ?>"><?php echo e('Group Name'); ?></a></li>
                    <?php } ?>

                    <?php if (!empty($aclList[15][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'deno') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('deno/view')); ?>"><?php echo e('Denotation'); ?></a></li>
                    <?php }   ?>

                    <?php if (!empty($aclList[28][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'budget_code') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('budget_code/view')); ?>"><?php echo e('Budget Code'); ?></a></li>
                    <?php }   ?>

                    <?php if (!empty($aclList[31][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'currency') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('currency/view')); ?>"><?php echo e('Currency Setup'); ?></a></li>
                    <?php }   ?>

                    <?php if (!empty($aclList[36][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'demande') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('demande/view')); ?>"><?php echo e('Demande Name'); ?></a></li>
                    <?php }   ?>

                    <?php if (!empty($aclList[21][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'zone') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('zone/view')); ?>"><?php echo e('Zone'); ?></a></li>
                    <?php }   ?>

                    <?php if (!empty($aclList[11][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'reg_nsd') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('reg_nsd/registred_nsd_name')); ?>"><?php echo e('Organization'); ?></a></li>
                    <?php }   ?>

                    <?php if (!empty($aclList[32][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'upload-file') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('upload-file/file')); ?>"><?php echo e('Upload File'); ?></a></li>
                    <?php }   ?>

	                    <?php if (!empty($aclList[37][1])) { ?>
                        <li <?php $current = ($currentControllerName == 'terms-conditions-category') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('terms-conditions-category')); ?>"><?php echo e('Terms and Conditions Category'); ?></a></li>
	                    <?php }   ?>

	                    <?php if (!empty($aclList[29][1])) { ?>
                        <li <?php $current = ($currentControllerName == 'terms-conditions') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('terms-conditions')); ?>"><?php echo e('Terms and conditions'); ?></a></li>
	                    <?php }   ?>

                    <?php if (!empty($aclList[39][1])) { ?>
                        <li <?php $current = ($currentControllerName == 'evaluation-point') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('evaluation-point')); ?>"><?php echo e('Evaluation Point Table'); ?></a></li>
                    <?php } ?>
                    <?php if (!empty($aclList[40][1])) { ?>
                        <li <?php $current = ($currentControllerName == 'evaluation-position') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('evaluation-position')); ?>"><?php echo e('Evaluation Position'); ?></a></li>
                    <?php } ?>
                    <?php if (!empty($aclList[41][1])) { ?>
                        <li <?php $current = ($currentControllerName == 'evaluation-criteria') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('evaluation-criteria')); ?>"><?php echo e('Evaluation Criteria'); ?></a></li>
                    <?php } ?>

                </ul>
            </li>
            <?php } ?>

            <?php if (!empty($aclList[22][1]) || !empty($aclList[23][1]) || !empty($aclList[24][1]) || !empty($aclList[25][1]) ) { ?>
            <li <?php $current = ($currentControllerName == 'excel') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                <a href="#"><span class="nav-label"><?php echo e('Historical Data Input'); ?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <?php if (!empty($aclList[22][2])) { ?>
                    <li <?php $current = (Request::segment(2) == 'suppliers') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('excel/suppliers')); ?>"><?php echo e('Suppliers'); ?></a></li>
                    <?php } ?>

                    <?php if (!empty($aclList[23][2])) { ?>
                    <li <?php $current = (Request::segment(2) == 'items') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('excel/items')); ?>"><?php echo e('Items'); ?></a></li>
                    <?php }   ?>

                    <?php if (!empty($aclList[24][2])) { ?>
                    <li <?php $current = (Request::segment(2) == 'tenders') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('excel/tenders')); ?>"><?php echo e('Tenders'); ?></a></li>
                    <?php }   ?>

                    <?php if (!empty($aclList[25][2])) { ?>
                    <li <?php $current = (Request::segment(2) == 'itemtotenders') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('excel/itemtotenders')); ?>"><?php echo e('Item to Tenders'); ?></a></li>
                    <?php }   ?>

                </ul>
            </li>
            <?php } ?>

            <?php if (!empty($aclList[26][1])) { ?>
                <li <?php $current = ($currentControllerName == 'notice') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                    <a href="<?php echo URL::to('notice/view'); ?>"><span class="nav-label">Notice</span></a>
                </li>
            <?php } ?>

            <?php if (!empty($aclList[27][1])) { ?>
            <li <?php $current = ($currentControllerName == 'contact' || $currentControllerName == 'cns-image-edit') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                <a href="#"><span class="nav-label"><?php echo e('Wab Admin'); ?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">

                    <?php if (!empty($aclList[27][2])) { ?>
                    <li <?php $current = ($currentControllerName == 'contact') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('contact/view')); ?>"><?php echo e('Contact'); ?></a></li>

                    <li <?php $current = ($currentControllerName == 'cns-image-edit') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('/cns-image-edit')); ?>"><?php echo e('CNS Image'); ?></a></li>
                    <?php } ?>

                </ul>
            </li>
            <?php } ?>

            <?php if (!empty($aclList[7][1]) || !empty($aclList[8][1])|| !empty($aclList[9][3])|| !empty($aclList[43][3])|| !empty($aclList[44][3])|| !empty($aclList[31][3])|| !empty($aclList[45][1])|| !empty($aclList[41][1])|| !empty($aclList[42][1])|| !empty($aclList[46][1])|| !empty($aclList[48][1])|| !empty($aclList[50][1]) || !empty($aclList[53][1])) { ?>
            <li <?php $current = ($currentControllerName == 'module' || $currentControllerName == 'activity'|| $currentControllerName == 'systemSettings'|| $currentControllerName == 'deliveryTime'|| $currentControllerName == 'banners'|| $currentControllerName == 'menus'|| $currentControllerName == 'foodMeasurment'|| $currentControllerName == 'socialIcon'|| $currentControllerName == 'paymentSettings'|| $currentControllerName == 'coupon'|| $currentControllerName == 'create'|| $currentControllerName == 'edit'|| $currentControllerName == 'cuisine'|| $currentControllerName == 'giftbox') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                <a href="#"><span class="nav-label">Settings</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">

                    <?php if (!empty($aclList[7][1])) { ?>
                    <li <?php $current = ($currentControllerName == 'module') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('module')); ?>">Module Management</a></li>
                    <?php } ?>
                    <?php if (!empty($aclList[8][1])) { ?>

                    <li <?php $current = ($currentControllerName == 'activity') ? 'active' : ''; ?> class="<?php echo $current; ?>"><a href="<?php echo e(URL::to('activity')); ?>">Activity Management</a></li>

                    <?php }   ?>


                    <?php if (!empty($aclList[9][3])) { ?>

                    <li <?php $current = ($currentControllerName == 'systemSettings') ? 'active' : ''; ?> class="<?php echo $current; ?>">
                        <a href="<?php echo URL::to('systemSettings'); ?>"><span class="nav-label">System Settings</span></a>
                    </li>
                    <?php } ?>


                </ul>
            </li>
            <?php } ?>





        </ul>
    </div>
</aside>
