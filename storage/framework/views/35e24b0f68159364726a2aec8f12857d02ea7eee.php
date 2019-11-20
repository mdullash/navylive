<?php $__env->startSection('content'); ?>

    <!-- page-header -->
    <div id="inner-page-header" class="page-header position-relative">
        <div class="container">
            <div class="row">
                <!-- page caption -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                    <div class="page-caption">
                        <h1 class="page-title">Tender List</h1>
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
                            <li class="breadcrumb-item active text-white" aria-current="page">Tender List</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- page breadcrumb -->
    </div>
    <!-- /.page-header -->
    <!-- tender-list-content -->
    <div class="tenderListContent content sectionBg navySelect">
        <div class="container">
            <div class="row">


                <!-- sidebar-section -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="filter-form">
                        
                        <?php echo e(Form::open(array('role' => 'form', 'url' => $a.$b.'front-tender', 'files'=> true, 'method'=>'get', 'class' => 'form-row'))); ?>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <h3 class="widget-title">filter</h3>
                        </div>
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-3">
                            <!-- select -->
                            <select class="wide nice-select" name="category">
                                <option value="">All</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo $ct->id; ?>" <?php if($ct->id == Input::get('category') ): ?>) <?php echo 'selected'; ?> <?php endif; ?>><?php echo $ct->name; ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-2">
                            <input type="text" name="from" id="contractDate" class="form-control mb-3 weddingdate" value="<?php echo Input::get('from'); ?>" placeholder="From" autocomplete="off">
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-2">
                            <input type="text" name="to" id="regDate" class="form-control mb-3 weddingdate" value="<?php echo Input::get('to'); ?>" placeholder="To"  autocomplete="off">
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-2">
                        <!-- <textarea name="key" id="" class="form-control mb-3"><?php echo Input::get('key'); ?></textarea> -->
                            <input type="text" name="key" id="" class="form-control mb-3" value="<?php echo Input::get('key'); ?>" placeholder="Tender title/ Tender no.">
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-2">
                            <button class="btn btn-default btn-block" type="submit">Search</button>
                        </div>
                        <?php echo Form::close(); ?>

                        
                    </div>
                </div><!---col-4-->
                <!-- /.sidebar-section -->

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <!-- tenderTableArea -->

                    <?php $sl = 1; ?>
                    <?php $__currentLoopData = $categoriess; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($cts->id != 8): ?>
                            
                            <div class="tenderTableArea">
                                <h4 class="text-center"><?php echo $cts->name." Tender"; ?></h4>

                                <table id="tenderTable" class="table table-bordered table-striped table-hover dt-responsive tenderTable" style="width:100%;">
                                    <!-- thead -->
                                    <thead>
                                    <tr>
                                        <th scope="col">Sl</th>
                                        <th scope="col">Tender No.</th>
                                        <th scope="col">Tender Title</th>
                                        <th scope="col">QTY</th>
                                        <th scope="col">Opening Date</th>
                                        <th scope="col">Spec</th>
                                        <th scope="col">Notice</th>
                                    </tr>
                                    </thead>
                                    <!-- tbody -->
                                    <tbody>
                                    <!-- tr -->
                                    <?php if(count($recent_tenders[$cts->name])> 0): ?>
                                        <?php $__currentLoopData = $recent_tenders[$cts->name]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                            <tr style="<?php if(date('d.m.Y',strtotime($rt->tender_opening_date)) == date('d.m.Y')): ?> color:red !important; <?php endif; ?>">
                                                <th scope="row" class="<?php if($rt->tender_type ==2): ?> openBadge <?php endif; ?>">
                                                    <?php echo $sl++; ?>

                                                </th>
                                                <td class="<?php if($rt->open_tender==1): ?> newBadge <?php endif; ?>  <?php if($rt->new_tender): ?> newBadge    <?php endif; ?>">

                                                    

                                                    <?php echo $rt->tender_number; ?>

                                                </td>
                                                <td>
                                                    <p class="text-uppercase"><?php echo $rt->tender_title; ?></p>
                                                </td>
                                                <td>
                                                    <p class="text-uppercase"><?php echo $rt->quantity; ?> <?php echo $rt->deno; ?></p>
                                                </td>
                                                <td class="<?php if($rt->re_approve): ?> updated <?php endif; ?>">
                                                    <p class="text-uppercase"><?php echo date('d.m.Y',strtotime($rt->tender_opening_date)); ?></p>
                                                </td>
                                                <td class="text-center">
                                            <span>
                                                <?php if(!empty($rt->specification)): ?>
                                                    <a href="<?php echo e(url('front-specification-pdf/'.$a.$b.base64_encode($rt->id))); ?>" target="_blank" class="pdf_icon"><i class="far fa-file-pdf"></i></a>
                                                <?php endif; ?>
                                                <?php if(!empty($rt->specification_doc)): ?>
                                                    &nbsp;&nbsp;<a href="<?php echo e(url('front-specification-doc/'.$a.$b.base64_encode($rt->id))); ?>" target="_blank" class="docx_icon"><i class="fa fa-file-word"></i></a>
                                                <?php endif; ?>
                                            </span>
                                                </td>
                                                <td class="text-center " >
                                            <span>
                                                <?php if(!empty($rt->notice)): ?>
                                                    <a href="<?php echo e(url('front-notice-pdf/'.$a.$b.base64_encode($rt->id))); ?>" target="_blank" class="pdf_icon"><i class="far fa-file-pdf"></i></a>
                                                <?php endif; ?>
                                            </span>
                                                </td>
                                            </tr>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>

                                    </tbody>
                                </table>
                            </div><!--./tenderTableArea-->
                            

                            <?php echo $recent_tenders[$cts->name]->appends(\Input::except('page'))->render(); ?>

                            <?php $sl = 1; ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div><!--/.col-8-->


            </div>  <!--./row-->
        </div>
    </div>
    <!-- /.tender-list-content -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>