<?php $__env->startSection('content'); ?>

    <!-- page-header -->
    <div id="inner-page-header" class="page-header position-relative">
        <div class="container">
            <div class="row">
                <!-- page caption -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                    <div class="page-caption">
                        <h1 class="page-title">Supplier List</h1>
                    </div>
                </div>
                <!-- /.page caption -->
            </div>
        </div>
        <!-- page caption -->
        <div class="page-breadcrumb position-relative">
            <div class="container">
                <div class="row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo URL::to('/').'/'.$a.$b; ?>" class="breadcrumb-link">Home</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Supplier List</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- page breadcrumb -->
    </div>
    <!-- /.page-header -->
    <!-- supplier-list-content -->
    <div class="supplierListContent content sectionBg">
        <div class="container">

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="filter-form">
                        
                        <?php echo e(Form::open(array('role' => 'form', 'url' => $a.$b.'front-supplier', 'files'=> true, 'method'=>'get', 'class' => 'form-row'))); ?>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h3 class="widget-title">filter</h3>
                            </div>  
                            
                                
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                
                            
                            
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-8">
                                <!-- <textarea name="key" id="" class="form-control mb-3"></textarea> -->
                                <input type="text" name="key" id="" class="form-control mb-3" value="<?php echo Input::get('key'); ?>" placeholder="Company Name / Supplier name">
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-4">
                                <button class="btn btn-default btn-block" type="submit">Search</button>
                            </div>
                        <?php echo Form::close(); ?>

                        
                    </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="suplier_list_area">

                        <table id="tenderTable" class="table table-bordered table-striped table-hover dt-responsive tenderTable" style="width:100%;">
                            <!-- thead -->
                            <thead>
                                <tr>
                                    <th scope="col">Sl</th>
                                    <th scope="col">Logo</th>
                                    <th scope="col">Company Name</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Owner</th>
                                    <th scope="col">Representative</th>
                                </tr>
                            </thead>
                            <!-- tbody -->
                            <tbody>
                                <!-- tr -->
                                <?php 
                                    $sl = 1; 

                                ?>
                                <?php if(!empty($suppliers)): ?>

                                    <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                           

                                            $nsd_n_link = '';
                                            if($sp->registered_nsd_id == 1){
                                                $nsd_n_link = "";
                                            }
                                            if($sp->registered_nsd_id == 2){
                                                $nsd_n_link = "http://sims.navy.mil.bd/nsd/nsd_chattagram";
                                            }
                                            if($sp->registered_nsd_id == 3){
                                                $nsd_n_link = "http://sims.navy.mil.bd/nsd/nsd_khulna";
                                            }
                                            if($sp->registered_nsd_id == 4){
                                                $nsd_n_link = "http://sims.navy.mil.bd/nsd/dgdp";
                                            }
                                            if($sp->registered_nsd_id == 5){
                                                $nsd_n_link = "http://sims.navy.mil.bd/bsd/bsd_dhaka";
                                            }
                                            if($sp->registered_nsd_id == 6){
                                                $nsd_n_link = "http://sims.navy.mil.bd/bsd/bsd_chattagram";
                                            }
                                            if($sp->registered_nsd_id == 7){
                                                $nsd_n_link = "http://sims.navy.mil.bd/bsd/bsd_khulna";
                                            }
                                        ?>

                                        <tr>
                                            <th scope="row"><?php echo $sl++; ?></th>
                                            <td>
                                                <div class="vendor-img">                                       
                                                    <?php if(!empty($sp->profile_pic)): ?>
                                                        <a href="#"><img src="<?php echo e(URL::to('/')); ?>/public/uploads/supplier_profile/<?php echo e($sp->profile_pic); ?>" alt="" class="img-fluid"></a>
                                                    <?php else: ?>
                                                        <a href="#"><img src="<?php echo e(URL::to('/')); ?>/public/upload/systemSettings/0AMYVmrii1fFAoa4lD8R.png" alt="" class="img-fluid mb20" style="-webkit-filter: grayscale(100%); filter: grayscale(100%); width: 60%;"></a>
                                                    <?php endif; ?>

                                                </div> <!-- /.Vendor img -->
                                            </td>
                                            <td>
                                                <p class="text-uppercase"><?php echo $sp->company_name; ?></p>
                                            </td>
                                            <td>
                                                <p class="vendor-address"><?php echo $sp->head_office_address; ?></p>
                                            </td>
                                            <td>
                                                <p class="vendor-address"><?php echo $sp->company_regi_number_nsd; ?></p>
                                            </td>
                                            <td>
                                                <p class="vendor-address"> <?php echo $sp->company_regi_number_nsd; ?></p>
                                            </td>
                                        </tr>
                                        
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>        
                            </tbody>
                        </table>

                        <?php echo $suppliers->appends(\Input::except('page'))->render(); ?>

                    </div><!--suplier_list_area-->
                </div><!--/.col-8-->
                <!-- sidebar-section -->
                <!--this-->
                </div><!---col-4-->
                <!-- /.sidebar-section -->
            </div>  <!--./row-->          
        </div>
    </div>
    <!-- /.supplier-list-content -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>