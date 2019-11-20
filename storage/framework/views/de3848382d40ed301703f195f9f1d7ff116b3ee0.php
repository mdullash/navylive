<!-- Upcoming Deadline-section -->
<div id="upcoming_deadline" class="pdb0 navySectionBg">
    <div class="container">
        <div class="row space-medium">
            <div class="offset-xl-2 col-xl-8 offset-lg-2 col-lg-8 col-md-12 col-sm-12 col-12">
                <!-- section title start-->
                <div class="section-title text-center">
                    <h2 class="mb10">Upcoming Deadline</h2>
                    <!--  <p>Tender submission date are very close, You may check if you can perticipate.</p>-->
                 </div><!-- /.section title-->
            </div>
        </div><!--row-->
        <!-- upcoming_deadlineContent start -->
        <div class="upcoming_deadlineContent content">
            <div class="container">

                <div class="tenderTableArea">

                        <?php $sl = 1; ?>


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

                            <?php $__currentLoopData = $date_line_tenders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr>
                                    <th scope="row" class="<?php if($rt->tender_type ==2): ?> openBadge <?php endif; ?>"><?php echo $sl++; ?></th>
                                    <td class="<?php if($rt->open_tender==1): ?> newBadge <?php endif; ?>  <?php if($rt->new_tender): ?> newBadge    <?php endif; ?>">
                                        <p>
                                            <a href="<?php if(!empty($rt->specification)): ?> <?php echo e(url('front-specification-pdf/'.$a.$b.base64_encode($rt->id))); ?> <?php else: ?> <?php echo e('javascript:void(0)'); ?> <?php endif; ?>" <?php if(!empty($rt->specification)): ?> target="_blank" <?php endif; ?> class="title"><?php echo $rt->tender_number; ?></a>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-uppercase"><?php echo $rt->tender_title; ?></p>
                                    </td>
                                    <td>
                                        <p class="text-uppercase"><?php echo $rt->quantity; ?> <?php echo $rt->deno; ?></p>
                                    </td>
                                    <td>
                                        <p class="text-uppercase" class="<?php if($rt->re_approve): ?> updated <?php endif; ?>"><?php echo date('d.m.Y',strtotime($rt->tender_opening_date)); ?></p>
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
                                    <td class="text-center">
                                        <span>
                                            <?php if(!empty($rt->notice)): ?>
                                                <a href="<?php echo e(url('front-notice-pdf/'.$a.$b.base64_encode($rt->id))); ?>" target="_blank" class="pdf_icon"><i class="far fa-file-pdf"></i></a>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                    </div><!--./tenderTableArea-->

            </div>
        </div><!-- /.recent_tender end -->
    </div>
</div>
<!-- /. Upcoming Deadline-section -->
