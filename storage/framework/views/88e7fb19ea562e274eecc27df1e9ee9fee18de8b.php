<?php $__env->startSection('content'); ?>

    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <!-- page-header -->
    <div id="inner-page-header" class="page-header position-relative">
        <div class="container">
            <div class="row">
                <!-- page caption -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                    <div class="page-caption">
                        <h1 class="page-title">PO Winners</h1>
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
                            <li class="breadcrumb-item active text-white" aria-current="page">PO Winners</li>
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

              <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="filter-form">
                        
                        <?php echo e(Form::open(array('role' => 'form', 'url' => $a.$b.'front-po-winner', 'files'=> true, 'method'=>'get', 'class' => 'form-row'))); ?>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h3 class="widget-title">filter</h3>
                            </div>


                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-4">
                                <!-- <textarea name="key" id="" class="form-control mb-3"></textarea> -->
                                <input type="text" name="tender_number" id="" class="form-control mb-3" value="<?php echo Input::get('tender_number'); ?>" placeholder="Tender Number">
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-4">
                                <!-- select -->
                                <select class="wide nice-select" name="suppliers">
                                    <option value="">Suplier Name</option>
                                    <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo $ct->id; ?>" <?php if($ct->id == Input::get('suppliers') ): ?>) <?php echo 'selected'; ?> <?php endif; ?>><?php echo $ct->company_name; ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-4">
                                <button class="btn btn-default btn-block" type="submit">Search</button>
                            </div>
                        <?php echo Form::close(); ?>

                        
                    </div>
              </div>




                <div class="row ">

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <!--st-tab-->
                        <div class="st-tab">
                            <div class="container">

                                        <div class="" style="background-color: #fff;">
                                            <!-- form-heading-title -->

                                                <table id="tenderTable" class="table table-bordered table-striped table-hover dt-responsive tenderTable" style="width:100%;">
                                                      <thead>
                                                        <tr>
                                                            <th style="vertical-align: middle;">Ser</th>
                                                            <th style="vertical-align: middle;">Tender Title</th>
                                                            <th style="vertical-align: middle;">Tender Number</th>
                                                            <th style="vertical-align: middle;">PO Date</th>
                                                            <th style="vertical-align: middle;">PO Winner</th>
                                                        </tr>
                                                      </thead>
                                                      <tbody>
                                                        <?php if(!empty($top100Pos)): ?>
                                                            <?php
                                                                 $i = ($top100Pos->currentpage()-1)* $top100Pos->perpage() + 1;
                                                            ?>
                                                            <?php $__currentLoopData = $top100Pos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $top100Po): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                  <tr>
                                                                      <td><?php echo e($i); ?></td>
                                                                      <td>
                                                                           <?php echo e(!empty($top100Po->tender_title) ?
                                                                            $top100Po->tender_title : ""); ?>

                                                                        </td>
                                                                      <td>
                                                                          <?php echo e(!empty($top100Po->tender_number) ?
                                                                            $top100Po->tender_number : ""); ?>

                                                                      </td>
                                                                      <td>
                                                                          <?php echo e(!empty($top100Po->poApprovedDate) ?
                                                                            date("d F, Y",strtotime($top100Po->poApprovedDate)) : ""); ?>

                                                                      </td>
                                                                    <td>
                                                                      <?php echo e(!empty($top100Po->suppliernametext) ?
                                                                            $top100Po->suppliernametext : ""); ?>

                                                                    </td>
                                                                  </tr>
                                                                  <?php $i++ ?>
                                                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                      </tbody>
                                                    </table>
                                                    <?php echo e($top100Pos->links()); ?>

                                                </div><!--row-->

                                            <!--/.form -->
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