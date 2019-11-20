<!-- hero-section -->
<div class="hero-section position-relative">
    <div class="container">
        <div class="row">
            <div class="offset-xl-1 col-xl-10 offset-lg-1 col-lg-10 col-md-12 col-sm-12 col-12">
                <!-- search-block -->
                <div class="">
                    <div class="text-center search-head">
                        <h1 class="search-head-title"><?php echo $navallocation->name; ?></h1>

                    </div> <!-- /.search-block -->
                    <!-- search-form -->
                    <div class="search-form">
                        
                        <?php echo e(Form::open(array('role' => 'form', 'url' => $a.$b.'front-tender', 'files'=> true, 'method'=>'get', 'class' => 'form-row'))); ?>

                            
                                
                                
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                
                            
                            
                            <div class="col-xl-4 offset-xl-1 offset-md-1 col-md-4 col-sm-12 col-12 mt-4">
                                <!-- select -->
                                <select class="wide nice-select" name="category">
                                    <option value="">All</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo $ct->id; ?>"><?php echo $ct->name; ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <!-- textarea -->
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 mt-4">
                                <!-- <textarea name="key" id="" class="form-control"></textarea> -->
                                <input type="text" name="key" class="form-control" placeholder="Tender Name">
                            </div>
                            <!-- button -->
                            <div class="col-xl-2 col-lg-3 col-md-3 col-sm-12 col-12 mt-4">
                                <button class="btn btn-default btn-block" type="submit">Search</button>
                            </div>
                        <?php echo Form::close(); ?>

                        
                    </div>
                    <!-- /.search-form -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.hero-section -->