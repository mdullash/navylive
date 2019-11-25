<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(url('public/css/bootstrap-select.css')); ?>">
    <link href="<?php echo e(asset('public/chosenmultiselect/docsupport/prism.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('public/chosenmultiselect/chosen.css')); ?>" rel="stylesheet">

    <style>

        .chosen-container-multi .chosen-choices{
            width: 100% !important;
            padding: 10px !important;
            box-shadow: none !important;
            border: 1px solid #e2e2e2 !important;
            background-image: none !important;
        }
        .chosen-container .chosen-drop{
            width: 100% !important;
        }
        .chosen-container-multi .chosen-choices li.search-field input[type="text"] {
            padding-left: 25px !important;
            font-size: 85% !important;
        }
        .couple-form .st-tab .nav-item .nav-link {
            border: 1px solid #e6e6e6;
            margin-right: 0px;
            background-color: #1c3586;
            color: #ffffff;
        }
        .st-tab .nav-tabs .nav-link.active,
        .st-tab .nav-tabs .nav-link:focus, .nav-tabs .nav-link:hover{
            color: #ffffff;
            background-color: #ef3f2e;
            border-color: #ebebeb #ebebeb #fff #ebebeb;
        }
        .st-tab .nav-item .nav-link {
            padding: 10px;
        }
        .st-tab .loginArea {
            padding: 40px 55px 65px;
        }
    </style>
    <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

      <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <!-- page-header -->
    <div id="inner-page-header" class="page-header position-relative">
        <div class="container">
            <div class="row">
                <!-- page caption -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                    <div class="page-caption">
                        <h1 class="page-title">Supplier From Submit</h1>
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
                            <li class="breadcrumb-item active text-white" aria-current="page">Supplier From Submit</li>
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
                            <div class="container">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">

                                        <div class="loginArea">
                                            <!-- form-heading-title -->

                                                <?php if(Auth::guard('supplier')->check()): ?>

                                                    <?php echo e(Form::model($editId, array('url' => '0/0/supplier-form-update', 'method' => 'POST', 'files'=> true, 'class' => 'form form-horizontal validate-form supplier_info_tab', 'id' => 'suppliers'))); ?>


                                                        <ul class="nav nav-tabs"  role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" id="company-tab" data-toggle="tab" href="#company-info" role="tab" aria-controls="#company-info" aria-selected="true">Company Information</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="introduction-tab" data-toggle="tab" href="#introduction" role="tab" aria-controls="introduction" aria-selected="false">Introductory Information</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank_info" role="tab" aria-controls="bank_info" aria-selected="false">Owner/Authorized Person Information</a>
                                                            </li>

                                                            <li class="nav-item">
                                                                <a class="nav-link" id="current_supplier-tab" data-toggle="tab" href="#current_supplier" role="tab" aria-controls="current_supplier" aria-selected="false">Spouse Information</a>
                                                            </li>

                                                        </ul>

                                                        <!-- Tab panes -->
                                                        <div class="tab-content">
                                                            <div class="tab-pane fade show active" id="company-info" role="tabpanel" aria-labelledby="company-tab">
                                                            
                                                                <div class="col-md-12 col-sm-12 col-md-12">

                                                                    <div class="headOffice regFormTitle mt-2">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Company Name (English)</label>
                                                                                    <input id="company_name" type="text" name="company_name" placeholder="Company Name (English)" value="<?php echo $editId->company_name; ?>" class="form-control" >
                                                                                </div>
                                                                            </div>

                                                                             <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Company Name (Bengali)</label>
                                                                                    <input id="company_name_bng" type="text" name="company_name_bng" placeholder="Company Name (Bengali)" value="<?php echo $editId->company_name_bng; ?>" class="form-control" >
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <h4>Head Office:</h4>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <!-- Head Office Telephone-->
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="mobile_number" placeholder="Mobile No." class="form-control" value="<?php echo $editId->mobile_number; ?>" >
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <!-- Head Office Fax-->
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="fax" id="fax" placeholder="Fax" class="form-control" value="<?php echo $editId->fax; ?>" >
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <!-- Head Office email-->
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="email" name="email" placeholder="Email" class="form-control" value="<?php echo $editId->email; ?>" >
                                                                                </div>
                                                                            </div>


                                                                            <div class="col-md-6">
                                                                                <select class="form-control chosen-select" name="supply_cat_id[]" id="supply_cat_id"   multiple="multiple" tabindex="4">
                                                                                    <option value="" disabled=""><?php echo '- Select -'; ?></option>
                                                                                    <?php $__currentLoopData = $supplyCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <option value="<?php echo $sc->id; ?>"><?php echo $sc->description; ?></option>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                </select>
                                                                            </div>


                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <!-- Head Office Address-->
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <textarea name="head_office_address" class="form-control" id="head_office_address"  placeholder="Address" ><?php echo $editId->head_office_address; ?></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <!--Branch Address-->
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <textarea name="branch_office_address" class="form-control" id="branch_office_address" placeholder="Branch Office (If any)"><?php echo $editId->branch_office_address; ?></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="">

                                                                            <!-- Bank Account Area-->
                                                                            <div class="bankAccount regFormTitle mt-2">
                                                                                <h4>Bank Account Information:</h4>
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <!-- Bank Account Number-->
                                                                                        <div class="form-group">
                                                                                            <label class="control-label sr-only"></label>
                                                                                            <input type="text" name="bank_account_number" placeholder="Account Number" class="form-control" id="bank_account_number" value="<?php echo $editId->bank_account_number; ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <!-- Bank Name-->
                                                                                        <div class="form-group">
                                                                                            <label class="control-label sr-only"></label>
                                                                                            <input type="text" name="bank_name" placeholder="Bank Name" class="form-control" value="<?php echo $editId->bank_name_and_branch; ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <!-- Relationship-->
                                                                                        <div class="form-group">
                                                                                            <label class="control-label sr-only"></label>
                                                                                            <input type="text" name="rltn_w_acc_holder" id="rltn_w_acc_holder" placeholder="Relationship with the A/C holder" class="form-control" value="<?php echo $editId->rltn_w_acc_holder; ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <!-- Bank Address-->
                                                                                        <div class="form-group">
                                                                                            <label class="control-label sr-only"></label>
                                                                                            <textarea name="address" class="form-control" placeholder="Address" ><?php echo $editId->branch_office_address; ?></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            </div><!--/.bankAccount Area--->
                                                                        </div><!--/.col-->

                                                                        <h4>Tax Information:</h4>
                                                                        <div class="row">
                                                                            <!-- IncometaxNo -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="tin_number" placeholder="Incometax Identification Number" value="<?php echo $editId->tin_number; ?>" class="form-control" >
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
                                                                                <!-- IncometaxCopy -->
                                                                                <div class="TINcopy">
                                                                                    <img src="" id="incomeTaxCopy" style="display: none;" />
                                                                                    <div class="upload_files">
                                                                                        <input type="file" name="tin_certificate" accept="image/png, image/jpeg,image/jpg" class="incomeTaxImg form-control" id="inputFile3" >
                                                                                        <label>Upload TIN Certificate (jpg/png)</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div><!--/.Head Office Area--->

                                                                </div><!--/.col-->

                                                                <div class="col-md-12 remove appendRewRow firstRow" id="firstRow">

                                                                    <span><b></b></span><br>
                                                                    <h4>Authorized Representatives (Max 2):</h4>
                                                                    <div class="row">
                                                                        <div class="col-md-6" style="padding-right: 25px;">
                                                                            <div class="form-group">

                                                                                <input type="text" class="form-control unit" id="name" name="name[]" placeholder="Name" >
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6" style="padding-right: 25px;">
                                                                            <div class="form-group">

                                                                                <input type="designation" class="form-control unit" id="designation" name="designation[]" placeholder="Designation" >
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6" style="padding-right: 25px;">
                                                                            <div class="form-group">

                                                                                <input type="mobile_number" class="form-control unit" id="mobile_number" name="mobile_number1[]" placeholder="Mobile Number" >
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
                                                                                <!-- IncometaxCopy -->
                                                                                <div class="TINcopy">
                                                                                    <img src="" id="authorized_person_signature" style="display: none;" />
                                                                                    <div class="upload_files">
                                                                                        <input type="file" name="authorized_person_signature[]" accept="image/png, image/jpeg,image/jpg" class="form-control" id="inputFile3" >
                                                                                        <label>Upload Signature (jpg/png)</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        <div style="clear: both !important;"><hr style="border-top: 1px solid #999999;"></div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <div class="col-md-3"></div><div class="col-md-3"></div><div class="col-md-3"></div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <button class="btn btn-info" id="addNewRow" type="button" title="Add New"><i class="icon-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- defaulterArea left-->
                                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                                    <h5>Is the company declared as blacklisted or suspended  before by BD Navy? </h5>
                                                                </div>
                                                                <!--defaulterArea-->
                                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                                    <div class="d-flex">
                                                                        <div class="form-group">
                                                                            <label class="radio_container col-md-12">Yes
                                                                                <input type="radio" name="defaulter_before" value="Yes" <?php if($editId->defaulter_before == 'Yes'): ?> checked="checked" <?php endif; ?>>
                                                                                <span class="checkmark"></span>
                                                                            </label>
                                                                        </div><!--form-group-->
                                                                        <div class="form-group">
                                                                            <label class="radio_container col-md-12">No
                                                                                <input type="radio" name="defaulter_before" value="No" <?php if($editId->defaulter_before == 'No'): ?> checked="checked" <?php endif; ?>>
                                                                                <span class="checkmark"></span>
                                                                            </label>
                                                                        </div><!--form-group-->
                                                                    </div>
                                                                </div>
                                                                <!-- company act left-->
                                                                <div class="col-md-12 col-sm-12 col-12">
                                                                    <h5>Is the company registered under factory, company or partnership act? </h5>
                                                                </div>
                                                                <!--companyActArea-->
                                                                <div class="col-md-12 col-sm-12 col-12">
                                                                    <div class="companyActArea d-flex">
                                                                        <div class="form-group">
                                                                            <label class="radio_container col-md-12">Yes
                                                                                <input type="radio" name="company_partnership_act" value="Yes" <?php if($editId->company_partnership_act == 'Yes'): ?> checked="checked" <?php endif; ?> >
                                                                                <span class="checkmark"></span>
                                                                            </label>
                                                                        </div><!--form-group-->
                                                                        <div class="form-group">
                                                                            <label class="radio_container col-md-12">No
                                                                                <input type="radio" name="company_partnership_act" value="No" <?php if($editId->company_partnership_act == 'No'): ?> checked="checked" <?php endif; ?>>
                                                                                <span class="checkmark"></span>
                                                                            </label>
                                                                        </div><!--form-group-->
                                                                    </div>
                                                                </div>
                                                                <!-- defaulterArea left-->
                                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                                    <h5>Company registered as</h5>
                                                                </div>
                                                                <!--defaulterArea-->
                                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                                    <div class="d-flex">
                                                                        <div class="form-group">
                                                                            <label class="radio_container col-md-12">Sole proprietorship
                                                                                <input type="radio" name="registered_as" class="soleProship" value="Yes" <?php if($editId->registered_as == 'Yes'): ?> checked="checked" <?php endif; ?>>
                                                                                <span class="checkmark"></span>
                                                                            </label>
                                                                        </div><!--form-group-->
                                                                        <div class="form-group">
                                                                            <label class="radio_container col-md-12">Limited company
                                                                                <input type="radio" name="registered_as" class="soleProship" value="No" <?php if($editId->registered_as == 'No'): ?> checked="checked" <?php endif; ?>>
                                                                                <span class="checkmark"></span>
                                                                            </label>
                                                                        </div><!--form-group-->
                                                                    </div>
                                                                </div>
                                                                <!-- Sole propietorship -->
                                                                <div class="col-md-12 col-sm-12 col-12" id="des_of_sole_prtship_div">
                                                                    <div class="form-group solePropietorship">
                                                                        <label class="control-label sr-only"></label>
                                                                        <textarea name="des_of_sole_prtship" id="des_of_sole_prtship" class="form-control" placeholder="Description of Sole Propietorship "><?php echo $editId->des_of_sole_prtship; ?></textarea>
                                                                    </div>
                                                                </div><!--col-->
                                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" id="partnersIfLimtdComDiv" style="display: none;">

                                                                    <div class="partnershipArea" style="padding-bottom: 30px;">
                                                                        <h4>Description of directors (If it is limited company):</h4>
                                                                        <div id="descriptionOfDirectorsDiv0">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label sr-only"></label>
                                                                                        <input type="text" name="partners_name[]" placeholder="Name" class="form-control partners_name">
                                                                                    </div>
                                                                                </div><!--col-->
                                                                                <!-- Partners propietorship -->
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label sr-only"></label>
                                                                                        <input name="partners_address[]" class="form-control partners_address" placeholder="Designation">
                                                                                    </div>
                                                                                </div>
                                                                                <!--col-->
                                                                            </div>

                                                                        </div>
                                                                        <button type="button" name="addMoreBtn" id="descriptionOfDirectorsBtn" class="btn btn-primary addMoreBtn mb-2">Add More</button>
                                                                    </div>
                                                                </div><!--/.Partners Information-->

                                                                <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12">
                                                                    <div class="partnershipArea">
                                                                        <h4>Documents of your company:</h4>
                                                                        <div class="row">
                                                                            <!--Vat Registration Number-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="vat_registration_number" placeholder="Vat Registration Number" class="form-control" value="<?php echo $editId->vat_registration_number; ?>" >
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- NID Number -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="nid_number" placeholder="NID Number" class="form-control" value="<?php echo $editId->nid_number; ?>" >
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Trade License Number  -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="trade_license_number" placeholder="Trade License Number " class="form-control" value="<?php echo $editId->trade_license_number; ?>"  >
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <textarea name="trade_license_address" class="form-control" placeholder="Trade License Address"  ><?php echo $editId->trade_license_address; ?></textarea>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- BSTI Certification -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="bsti_certification" placeholder="BSTI Certification" class="form-control" value="<?php echo $editId->bsti_certification; ?>">
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- ISO Certification  -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="iso_certification" placeholder="ISO Certification" class="form-control" value="<?php echo $editId->iso_certification; ?>">
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Naval Locations -->

                                                                            <!-- Category of goods supply -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="profile_pic" accept="image/png, image/jpeg,image/jpg" class="authorSig form-control" id="inputFile4" >
                                                                                    <label>Company Logo (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Category of goods supply -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="attested_photo" accept="image/png, image/jpeg,image/jpg" class="authorSig form-control" id="inputFile4" >
                                                                                    <label>Authorized person passport size photo (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Attested trade license -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="attested_trade_lic" accept="image/png, image/jpeg,image/jpg" class="authorSig form-control" id="inputFile4" >
                                                                                    <label>Attested trade license (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- NID card photocopy -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="attested_nid_photocopy" accept="image/png, image/jpeg,image/jpg" class="authorSig form-control" id="inputFile4" >
                                                                                    <label>Authorized person NID (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Attested VAT registration certificate -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="att_vat_reg_cert" accept="image/png, image/jpeg,image/jpg" class="authorSig form-control" id="inputFile4" >
                                                                                    <label>Attested VAT registration certificate (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Attested character certificate -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="attested_char_cert" accept="image/png, image/jpeg,image/jpg" class="authorSig form-control" id="inputFile4" >
                                                                                    <label>Authorized person character certificate (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Attested VAT return last certificate -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="att_vat_return_last_cert" accept="image/png, image/jpeg,image/jpg" class="authorSig form-control" id="inputFile4" >
                                                                                    <label>Attested last VAT return certificate (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Attested last educational certificate -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="att_edu_cert" accept="image/png, image/jpeg,image/jpg" class="authorSig form-control" id="inputFile4" >
                                                                                    <label>Attested educational certificate (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Attested last educational certificate -->
                                                                        
                                                                        
                                                                        
                                                                        
                                                                        
                                                                        
                                                                        <!-- Last six months bank statement -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="lst_six_mnth_bnk_sttmnt" accept="image/png, image/jpeg,image/jpg" class="authorSig form-control" id="inputFile4" >
                                                                                    <label>Last six months bank statement (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Bank solvency certificate -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="bnk_solvency_certi" accept="image/png, image/jpeg,image/jpg" class="authorSig form-control" id="inputFile4">
                                                                                    <label>Bank solvency certificate (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Affidavit in non-judicial stamp of Taka 300 for first class magistrate / notary public -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="non_judicial_stamp" accept="image/png, image/jpeg,image/jpg" class="authorSig form-control" id="inputFile4">
                                                                                    <label>Affidavit by first class magistrate/notary public in non-judicial stamp of Taka 300 (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->

                                                                        </div>

                                                                    </div>
                                                                </div><!--/.Authorised Parsonnel Information-->

                                                            </div>
                                                            <div role="tabpanel" class="tab-pane" id="bank_info"aria-labelledby="bank-tab" >
                                                            </div>
                                                            <div role="tabpanel" class="tab-pane" id="introduction" aria-labelledby="introduction-tab">
                                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                                    <div class="introducerArea">
                                                                        <h4>Information of 2 prestigious local person to known your company:</h4>
                                                                        <div class="row">
                                                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                                                <b>1</b>
                                                                            </div>
                                                                            <!--Introducer Name-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="intr_name[]" placeholder="Introducer Name" class="form-control" value="<?php if($editId->intr_name != null): ?><?php echo json_decode($editId->intr_name)[0]; ?> <?php endif; ?>" >
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!--Introducer Designation-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="intr_designation[]" placeholder="Introducer Designation" class="form-control" <?php if($editId->intr_designation != null): ?><?php echo json_decode($editId->intr_designation)[0]; ?> <?php endif; ?> >
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Introducer Address-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <textarea name="intr_address[]" class="form-control" placeholder="Introducer Address" ><?php if($editId->intr_address != null): ?><?php echo json_decode($editId->intr_address )[0]; ?> <?php endif; ?></textarea>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                                                                <!--Introducer Signature-->
                                                                                <img src="" id="profile_img_tag" style="display: none;" />
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="testimonial[]" accept="image/png, image/jpeg,image/jpg" class="form-control profileImg" id="inputFile1" >
                                                                                    <label for="studentSig">Certificate (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!--<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">-->
                                                                            <!--&lt;!&ndash;Introducer Seal&ndash;&gt;-->
                                                                            <!--<img src="" id="profile-img-tag2" style="display: none;" />-->
                                                                            <!--<div class="upload_files">-->
                                                                            <!--<input type="file" accept="image/png, image/jpeg,image/jpg" class="profileImg2" id="inputFile2">-->
                                                                            <!--<label for="studentSig">Seal</label>-->
                                                                            <!--</div>-->
                                                                            <!--</div>&lt;!&ndash;col&ndash;&gt;                                                           -->
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                                                <b>2</b>
                                                                            </div>
                                                                            <!--Introducer Name-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>

                                                                                    <input type="text" name="intr_name[]" placeholder="Introducer Name" class="form-control" <?php if($editId->intr_name  != null): ?><?php echo json_decode($editId->intr_name)[1]; ?> <?php endif; ?> >
                                                                                </div>
                                                                            </div><!--col-->

                                                                            <!--Introducer Designation-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="intr_designation[]" placeholder="Introducer Designation" class="form-control" <?php if($editId->intr_designation != null): ?><?php echo json_decode($editId->intr_designation)[1]; ?> <?php endif; ?> >
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Introducer Address-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <textarea name="intr_address[]" class="form-control" placeholder="Introducer Address" ><?php if($editId->intr_address != null): ?><?php echo json_decode($editId->intr_address)[1]; ?> <?php endif; ?></textarea>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                                                                <!--Introducer Signature-->
                                                                                <img src="" id="profile_img_tag" style="display: none;" />
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="testimonial[]"accept="image/png, image/jpeg,image/jpg" class="form-control profileImg" id="inputFile1" >
                                                                                    <label for="studentSig">Certificate (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                        </div>
                                                                        <!--<button type="submit" name="addMoreBtn" class="btn btn-primary addMoreBtn mb-2">Add More</button>-->

                                                                    </div><!--/.introducerArea-->
                                                                </div>
                                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                                    <div class="introducerArea" style="padding-bottom: 30px;">
                                                                        <h4>Currently enlisted as supplier at:</h4>
                                                                        <div class="curRegSuppRem" id="forCloneCurRegisAsSupplier0">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label sr-only"></label>
                                                                                        <input type="text" name="cur_reg_supplier_name[]" placeholder="Organization Name" class="form-control">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label sr-only"></label>
                                                                                        <textarea name="cur_reg_supplier_address[]" class="form-control" placeholder="Address"></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>

                                                                        <button type="button" name="addMoreBtn" id="crntlyRegAsSuppAddMrBtn" class="btn btn-primary addMoreBtn mb-2">Add More</button>

                                                                    </div><!--/.introducerArea-->
                                                                </div>

                                                            </div>
                                                            <div role="tabpanel" class="tab-pane" id="current_supplier" aria-labelledby="current_supplier-tab">
                                                            </div>
                                                            <div role="tabpanel" class="tab-pane" id="company-act-area"aria-labelledby="company-act-area-tab">
                                                                </div>
                                                            <div role="tabpanel" class="tab-pane" id="partners" aria-labelledby="partners-tab">
                                                                <!--Partners Information-->

                                                                <!--Authorised Parsonnel Information-->

                                                            </div>
                                                        </div>



                                                        <div class="form-group">

                                                            <div class="col-md-12">

                                                                <button type="submit" class="btn btn-primary pull-right" style=" margin-top: 12px;"><?php echo trans('english.SAVE'); ?></button>

                                                            </div>

                                                        </div>


                                                        <!-- <div class="hr-line-dashed"></div> -->
                                                        <?php echo Form::close(); ?>


                                                <?php endif; ?>


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
      <script src="<?php echo e(asset('public/chosenmultiselect/chosen.jquery.js')); ?>"></script>
      <script src="<?php echo e(asset('public/chosenmultiselect/docsupport/prism.js')); ?>"></script>
      <script src="<?php echo e(asset('public/chosenmultiselect/docsupport/init.js')); ?>"></script>
      <script rel="javascript" src="<?php echo e(url('public/frontend/js/custom.js')); ?>"></script>

      <script type="text/javascript">

          $(document).ready(function(){

              var i = 0;
              var sl = 2;
              $(document).on('click','#addNewRow',function(){
                  $( "body" ).find( ".firstRow" ).eq( i ).after( ' <div class="col-md-12 remove appendRewRow firstRow" id="firstRow">\n' +
                      '\n' +
                      '                            <span><b></b></span><br>\n' +
                      '\n' +
                       '<div class="row">'+
                      '                            <div class="col-md-6" style="padding-right: 25px;">\n' +
                      '                                <div class="form-group">\n' +
                      '                                    <input type="text" class="form-control name" id="name" name="name[]" placeholder="Name" >\n' +
                      '                                </div>\n' +
                      '                            </div>\n' +
                      '\n' +
                      '                            <div class="col-md-6" style="padding-right: 25px;">\n' +
                      '                                <div class="form-group">\n' +
                      '                                    <input type="designation" class="form-control designation" id="designation" name="designation[]" placeholder="Designation" >\n' +
                      '                                </div>\n' +
                      '                            </div>\n' +
                      '\n' +
                      '                            <div class="col-md-6" style="padding-right: 25px;">\n' +
                      '                                <div class="form-group">\n' +
                      '                                    <input type="mobile_number" class="form-control mobile_number" id="mobile_number" name="mobile_number1[]" placeholder="Mobile Number" >\n' +
                      '                                </div>\n' +
                      '                            </div>\n' +
                      '\n' +
                      '                            <div class="col-md-6" style="padding-right: 25px;">\n' +
                      '                                <div class="upload_files">\n' +
                      '                                    <input type="file" class="form-control mobile_number" id="authorized_person_signature" name="authorized_person_signature[]" placeholder="Upload Signature (jpg/png)" >\n<label>Upload Signature (jpg/png)</label>' +
                      '                                </div>\n' +
                      '                            </div>\n' +

                      '</div>'+
                      '\n' +
                      '                            <div class="col-md-1" style="margin-top: 25px; margin-bottom: 10px;"><button class="btn btn-danger removeRow" id="" type="button" data-placement="top" data-rel="tooltip" data-original-title="Remove"><i class="fa fa-trash"></i></button></div> <div style="clear: both !important;"><hr style="border-top: 1px solid #999999;"></div>\n' +
                      '\n' +
                      '                        </div>' );

                  i++;
                  sl++;

              });

              $(document).on("click",".removeRow",function(){
                  $(this).closest('.remove').remove();
                  i = i-1;

                  $("#total_value").val(sumOfTotalPrice());
                  $("#total_unit").val(sumOfTotalUnit());

              });


              var count = 0;
              $(document).on('click','#crntlyRegAsSuppAddMrBtn',function(){
                  function a(){
                      return ++count;
                  }
                  $("#forCloneCurRegisAsSupplier"+count).after('<div class="row curRegSuppRem" id="forCloneCurRegisAsSupplier'+a()+'"><div class="col-md-6 col-sm-12 col-12"><div class="form-group"><label class="control-label sr-only"></label><input type="text" name="cur_reg_supplier_name[]" placeholder="Organization Name" class="form-control"></div></div><div class="col-md-6  col-12"><div class="form-group"><label class="control-label sr-only"></label><textarea name="cur_reg_supplier_address[]" class="form-control" placeholder="Address"></textarea></div></div></div>');

              });

              var count1 = 0;
              $(document).on('click','#descriptionOfDirectorsBtn',function(){
                  function b(){
                      return ++count1;
                  }
                  $("#descriptionOfDirectorsDiv"+count1).after('<div class="row" id="descriptionOfDirectorsDiv'+b()+'"><div class="col-xl-6 col-lg-6 col-md-6 col-12"><div class="form-group"><label class="control-label sr-only"></label><input type="text" name="partners_name[]" placeholder="Name" class="form-control partners_name"></div></div><div class="col-xl-6 col-lg-6 col-md-6 col-12"><div class="form-group"><label class="control-label sr-only"></label><input name="partners_address[]" class="form-control partners_address" placeholder="Designation"></div></div></div>');

              });

              var count2 = 0;
              $(document).on('click','#authorisedPersonBtn',function(){
                  function c(){
                      return ++count2;
                  }
                  $("#authorisedPersonDiv"+count2).after('<div class="row" id="authorisedPersonDiv'+c()+'"><div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12"><div class="form-group"><label class="control-label sr-only"></label><input type="text" name="auth_prsn_name[]" placeholder="Authorised person Name" class="form-control" ></div></div><div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12"><div class="form-group"><label class="control-label sr-only"></label><input type="text" name="auth_prsn_designation[]" placeholder="Designation" class="form-control" ></div></div><div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12"><div class="upload_files"><input type="file" name="bangla_signature[]" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" ><label>Bangla Signature (jpg/png)</label></div></div><div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12"><div class="upload_files"><input type="file" name="english_signature[]" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" ><label>English Signature (jpg/png)</label></div></div></div>');

              });

              $("#des_of_sole_prtship_div").hide();

              $('input[type=radio][name=registered_as]').change(function() {

                  if (this.value == 'Yes') {
                      $("#des_of_sole_prtship").val('');
                      $("#des_of_sole_prtship_div").show();
                  }else{
                      $("#des_of_sole_prtship").val('');
                      $("#partnersIfLimtdComDiv").hide();
                  }
                  if (this.value == 'No') {
                      $(".partners_name").val('');
                      $(".partners_address").val('');
                      $("#partnersIfLimtdComDiv").show();
                  }else{
                      $(".partners_name").val('');
                      $(".partners_address").val('');
                      $("#partnersIfLimtdComDiv").hide();
                  }
              });

              // $(document).on('click','#first_submit',function(){
              //     if($("#registered_nsd_id").val()==''){
              //         $("#registered_nsd_id").after('<span style="color:red;">This field is requred</span>');
              //     }
              // });

          });
      </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>