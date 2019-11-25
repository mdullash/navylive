<?php $__env->startSection('content'); ?>

    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <!-- page-header -->
    <div id="inner-page-header" class="page-header position-relative">
        <div class="container">
            <div class="row">
                <!-- page caption -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                    <div class="page-caption">
                        <h1 class="page-title">Dashboard</h1>
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
                            <li class="breadcrumb-item"><a href="<?php echo URL::to($a.$b.'login'); ?>" class="breadcrumb-link">Supplier Login</a></li>

                            <li class="breadcrumb-item active text-white" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- page breadcrumb -->
    </div>
    <!-- /.page-header -->

    <!-- couple-sign in -->
    <section class="couple-bg-image pb-5 sectionBg">
        <div class="couple-form">
            <div class="container">
                <div class="row ">

                    <?php if(Auth::guard('supplier')->check()): ?>

                        <div class="col-lg-3 col-md-3 col-3">
                            <?php echo $__env->make('frontend/homeinc/menu', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        </div>
                    <?php endif; ?>

                    <div class="col-lg-9 col-md-9 col-sm-12 col-9">
                        <!--st-tab-->
                        <div class="st-tab">
                            <div class="container-fluid">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">



                                        <div class="" style="text-align: center;background-color: #fff;padding: 10px;">
                                            <!-- form-heading-title -->
                                            <?php if(Auth::guard('supplier')->check()): ?>

                                                <h3><?php echo Auth::guard('supplier')->user()->company_name; ?></h3>
                                                <p><?php echo Auth::guard('supplier')->user()->email; ?></p>
                                                <p>
                                                 <?php
                                                    function supply_cat_name($cat_id=null){
                                                        $calName = \App\SupplyCategory::where('id','=',$cat_id)->value('name');
                                                        return $calName;
                                                    }

                                                    $catids = explode(',',Auth::guard('supplier')->user()->supply_cat_id);
                                                    foreach ($catids as $key=>$ctds) {

                                                         $i=$key+1;
                                                         if ($i < count($catids)){
                                                             $comma=',';
                                                         }else{
                                                             $comma=' ';
                                                         }

                                                        $valsss = supply_cat_name($ctds);
                                                        echo "".$valsss.$comma." ";
                                                    }
                                                    ?>
                                                </p>
                                                <!-- /.form-heading-title -->
                                                <!-- register-form -->


                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="card bg-primary">
                                                            <div class="card-body text-center">
                                                              <h4 class="card-text text-white">Tender Participation</h4>
                                                               <h1 class="card-text text-white" style="font-size: 40px;">
                                                                    <?php echo e(!empty($SupplierTenderAttendCount) ?
                                                                        $SupplierTenderAttendCount : 0); ?>

                                                               </h1>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="card bg-success">
                                                            <div class="card-body text-center">
                                                              <h4 class="card-text text-white">Tender Win</h4>
                                                               <h1 class="card-text text-white" style="font-size: 40px;">
                                                                    <?php echo e(!empty($SupplierTenderWinCount) ?
                                                                        $SupplierTenderWinCount[0]->tender_win : 0); ?>

                                                                </h1>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    

                                                </div>

                                              <?php endif; ?>
                                        </div><!--/.loginArea-->

                                </div>
                            </div>
                        </div><!--/.st-tab-->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.couple-sign up -->
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>