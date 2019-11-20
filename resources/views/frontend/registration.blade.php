@extends('frontend.layouts.master')
@section('content')

@include('layouts.flash')

<!-- page-header -->
<div id="inner-page-header" class="page-header position-relative">
    <div class="container">
        <div class="row">
            <!-- page caption -->
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                <div class="page-caption">
                    <h1 class="page-title">Apply for Online Enrollment</h1>
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
                        <li class="breadcrumb-item"><a href="{!! URL::to('/').'/'.$a.$b !!}" class="breadcrumb-link">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Apply for Online Enrollment</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- page breadcrumb -->
</div>
<!-- /.page-header -->

<!--Step progress-->
<section id="stepProgress" class="mt40 mb40">
    <div class="container">
        <!-- stepProgress -->
        <div class="stepProgress">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <!-- stepProgressContent -->
                    <ul class="d-flex flex-wrap justify-content-between stepProgressContent">
                        <li class="list-inline-item position-relative stepBorderRight ">
                            <h3 class="mb-0">Step 1</h3>
                        </li>
                        <li class="list-inline-item position-relative stepBorderRight active">
                            <h3 class="mb-0">Step 2</h3>
                        </li>
                        <li class="list-inline-item position-relative stepBorderRight">
                            <h3 class="mb-0">Step 3</h3>
                        </li>
                        <li class="list-inline-item position-relative stepBorderRight">
                            <h3 class="mb-0">Step 4</h3>
                        </li>
                    </ul>
                </div><!--./row-->
            </div>
        </div><!--./stepProgress-->
    </div>
</section>
<!--./Step progress-->

<!-- couple-sign in -->
   <section class="couple-bg-image pb-5 sectionBg">
        <div class="couple-form">
            <div class="container">
                <div class="row ">
                    <div class="offset-xl-1 col-xl-10 offset-lg-1 col-lg-10 col-md-12 col-sm-12 col-12">
                        <!--st-tab-->
                        <div class="st-tab">                            
                            <div class="container">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">

                                    <div class="registerArea">
                                        <!-- form-heading-title -->
                                        <h3>Supplier's Basic Information</h3>
                                        <!-- /.form-heading-title -->
                                        <!-- register-form -->
                                        {{ Form::open(array('role' => 'form', 'url' => $a.$b.'front-registration1', 'files'=> true, 'class' => 'form-horizontal registration1', 'id'=>'registration1')) }}
                                            <!-- form -->

                                            <div class="row">
                                                <!-- Company Name-->
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input id="company_name" type="text" name="company_name" placeholder="Company Name" class="form-control" required>
                                                    </div>
                                                </div><!--/.col-->
                                                
                                                <!-- Registration Number-->
                                                <!--<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">-->
                                                    <!--<div class="form-group service-form-group">-->
                                                        <!--<label class="control-label sr-only"></label>-->
                                                        <!--<input id="cRegNo" type="text" placeholder="Company Registration Number" class="form-control" required readonly="readonly">-->
                                                    <!--</div>-->
                                                <!--</div>&lt;!&ndash;/.col&ndash;&gt;                                                -->
                                                <!-- Head Office Area-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                    <div class="headOffice regFormTitle mt-2">
                                                        <h4>Head Office:</h4>
                                                        <!-- Head Office Telephone-->
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <input type="text" name="mobile_number" placeholder="Mobile No." class="form-control" required>
                                                        </div>
                                                        <!-- Head Office Fax-->
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <input type="text" name="fax" id="fax" placeholder="Fax" class="form-control" required>
                                                        </div>
                                                        <!-- Head Office email-->
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <input type="email" name="email" placeholder="Email" class="form-control" required>
                                                        </div>
                                                        <!-- Head Office Address-->
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <textarea name="head_office_address" class="form-control" id="head_office_address" placeholder="Address" required></textarea>
                                                        </div>

                                                        <!-- IncometaxNo -->
                                                        <!--<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">-->
                                                        <h4>Tax Information:</h4>
                                                            <div class="form-group">
                                                                <label class="control-label sr-only"></label>
                                                                <input type="text" name="tin_number" placeholder="Incometax Identification Number" class="form-control" required>
                                                            </div>
                                                            <!-- IncometaxCopy -->
                                                            <div class="TINcopy">
                                                                <img src="" id="incomeTaxCopy" style="display: none;" />
                                                                <div class="upload_files">
                                                                    <input type="file" name="tin_certificate" accept="image/png, image/jpeg,image/jpg" class="incomeTaxImg" id="inputFile3" required>
                                                                    <label>Upload TIN Certificate (jpg/png)</label>
                                                                </div>
                                                            </div>
                                                        <!--</div>-->
                                                    </div><!--/.Head Office Area--->

                                                </div><!--/.col-->
                                                <!-- Branch Office Address-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 regFormTitle branchAddress mt-2">

                                                    <!-- Bank Account Area-->
                                                    <div class="bankAccount regFormTitle mt-2">
                                                        <h4>Bank Account Information:</h4>
                                                        <!-- Bank Account Number-->
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <input type="text" name="bank_account_number" placeholder="Account Number" class="form-control" id="bank_account_number" required>
                                                        </div>
                                                        <!-- Bank Name-->
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <input type="text" name="bank_name" placeholder="Bank Name" class="form-control" required>
                                                        </div>
                                                        <!-- Bank Address-->
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <textarea name="address" class="form-control" placeholder="Address" required></textarea>
                                                        </div>
                                                        <!-- Relationship-->
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <input type="text" name="rltn_w_acc_holder" id="rltn_w_acc_holder" placeholder="Relationship with the bank account holder" class="form-control" required>
                                                        </div>
                                                        <h4>Branch Office (If any):</h4>
                                                        <!--Branch Address-->
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <textarea name="branch_office_address" class="form-control" id="branch_office_address" placeholder="Branch Address"></textarea>
                                                        </div>
                                                    </div><!--/.bankAccount Area--->
                                                </div><!--/.col-->
                                                <!--introducer Area-->
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <div class="introducerArea">
                                                        <h4>Information of 2 prestigious person to known your company:</h4>
                                                        <div class="row">
                                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                                <b>1</b>
                                                            </div>
                                                            <!--Introducer Name-->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <input type="text" name="intr_name[]" placeholder="Introducer Name" class="form-control" required>
                                                                </div>
                                                            </div><!--col-->
                                                            <!--Introducer Designation-->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <input type="text" name="intr_designation[]" placeholder="Introducer Designation" class="form-control" required>
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- Introducer Address-->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <textarea name="intr_address[]" class="form-control" placeholder="Introducer Address" required></textarea>
                                                                </div>
                                                            </div><!--col-->
                                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                                                <!--Introducer Signature-->
                                                                <img src="" id="profile_img_tag" style="display: none;" />
                                                                <div class="upload_files">
                                                                    <input type="file" name="testimonial[]" accept="image/png, image/jpeg,image/jpg" class="profileImg" id="inputFile1" required>
                                                                    <label for="studentSig">Testimonial (jpg/png)</label>
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
                                                                    <input type="text" name="intr_name[]" placeholder="Introducer Name" class="form-control" required>
                                                                </div>
                                                            </div><!--col-->
                                                            <!--Introducer Designation-->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <input type="text" name="intr_designation[]" placeholder="Introducer Designation" class="form-control" required>
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- Introducer Address-->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <textarea name="intr_address[]" class="form-control" placeholder="Introducer Address" required></textarea>
                                                                </div>
                                                            </div><!--col-->
                                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                                                <!--Introducer Signature-->
                                                                <img src="" id="profile_img_tag" style="display: none;" />
                                                                <div class="upload_files">
                                                                    <input type="file" name="testimonial[]"accept="image/png, image/jpeg,image/jpg" class="profileImg" id="inputFile1" required>
                                                                    <label for="studentSig">Testimonial (jpg/png)</label>
                                                                </div>
                                                            </div><!--col-->
                                                        </div>
                                                        <!--<button type="submit" name="addMoreBtn" class="btn btn-primary addMoreBtn mb-2">Add More</button>-->

                                                    </div><!--/.introducerArea-->
                                                </div>
                                                <!--Current supplier area-->
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <div class="introducerArea">
                                                        <h4>Currently registered as supplier at :</h4>
                                                        <div class="row curRegSuppRem" id="forCloneCurRegisAsSupplier0">
                                                            <!--Name-->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <input type="text" name="cur_reg_supplier_name[]" placeholder="Compnay Name" class="form-control">
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- Address-->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <textarea name="cur_reg_supplier_address[]" class="form-control" placeholder="Address"></textarea>
                                                                </div>
                                                            </div><!--col-->

                                                        </div>

                                                        <button type="button" name="addMoreBtn" id="crntlyRegAsSuppAddMrBtn" class="btn btn-primary addMoreBtn mb-2">Add More</button>

                                                    </div><!--/.introducerArea-->
                                                </div>
                                                <!-- defaulterArea left-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                    <h5>Is the company declared as defaulter before? </h5>
                                                </div>
                                                <!--defaulterArea-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                    <div class="defaulterArea d-flex">
                                                        <div class="form-group">
                                                            <label class="radio_container col-md-12">Yes
                                                                <input type="radio" name="defaulter_before" value="Yes" required>
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div><!--form-group-->
                                                        <div class="form-group">
                                                            <label class="radio_container col-md-12">No
                                                                <input type="radio" name="defaulter_before" value="No">
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div><!--form-group-->
                                                    </div>
                                                </div>
                                                <!-- company act left-->
                                                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                                                    <h5>Is the company registered under factory, company or partnership act? </h5>
                                                </div>
                                                <!--companyActArea-->
                                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                                                    <div class="companyActArea d-flex">
                                                        <div class="form-group">
                                                            <label class="radio_container col-md-12">Yes
                                                                <input type="radio" name="company_partnership_act" value="Yes" required>
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div><!--form-group-->
                                                        <div class="form-group">
                                                            <label class="radio_container col-md-12">No
                                                                <input type="radio" name="company_partnership_act" value="No">
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div><!--form-group-->
                                                    </div>
                                                </div>
                                                <!-- defaulterArea left-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                    <h5>Company registered as ? </h5>
                                                </div>
                                                <!--defaulterArea-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                    <div class="defaulterArea d-flex">
                                                        <div class="form-group">
                                                            <label class="radio_container col-md-12">Sole proprietorship
                                                                <input type="radio" name="registered_as" class="soleProship" value="Yes">
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div><!--form-group-->
                                                        <div class="form-group">
                                                            <label class="radio_container col-md-12">Limited company
                                                                <input type="radio" name="registered_as" class="soleProship" value="No">
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div><!--form-group-->
                                                    </div>
                                                </div>
                                                <!-- Sole propietorship -->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" id="des_of_sole_prtship_div">
                                                    <div class="form-group solePropietorship">
                                                        <label class="control-label sr-only"></label>
                                                        <textarea name="des_of_sole_prtship" id="des_of_sole_prtship" class="form-control" placeholder="Description of Sole Propietorship "></textarea>
                                                    </div>
                                                </div><!--col-->
                                                 <!--Partners Information-->
                                                 <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 hidden" id="partnersIfLimtdComDiv">
                                                    <div class="partnershipArea">
                                                        <h4>Description of directors (If it is limited company):</h4>
                                                        <div class="row" id="descriptionOfDirectorsDiv0">
                                                            <!--Partners Name-->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <input type="text" name="partners_name[]" placeholder="Name" class="form-control partners_name">
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- Partners propietorship -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <textarea name="partners_address[]" class="form-control partners_address" placeholder="Designation"></textarea>
                                                                </div>
                                                            </div><!--col-->
                                                        </div>
                                                        <button type="button" name="addMoreBtn" id="descriptionOfDirectorsBtn" class="btn btn-primary addMoreBtn mb-2">Add More</button>
                                                    </div>
                                                </div><!--/.Partners Information-->
                                                 <!--Authorised Parsonnel Information-->
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <div class="partnershipArea">
                                                        <h4>Authorised Person for Signature:</h4>
                                                        <div class="row" id="authorisedPersonDiv0">
                                                            <!--Partners Name-->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <input type="text" name="auth_prsn_name[]" placeholder="Authorised person Name" class="form-control" required>
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- Partners propietorship -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <input type="text" name="auth_prsn_designation[]" placeholder="Designation" class="form-control" required>
                                                                </div>
                                                            </div><!--col-->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="upload_files">
                                                                    <input type="file" name="bangla_signature[]" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" required>
                                                                    <label>Bangla Signature (jpg/png)</label>
                                                                </div>
                                                            </div><!--col-->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="upload_files">
                                                                    <input type="file" name="english_signature[]" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" required>
                                                                    <label>English Signature (jpg/png)</label>
                                                                </div>
                                                            </div><!--col-->
                                                        </div>
                                                        <button type="button" name="addMoreBtn" id="authorisedPersonBtn" class="btn btn-primary addMoreBtn mb-2">Add More</button>
                                                    </div>
                                                </div><!--/.Authorised Parsonnel Information-->

                                                <!--Fro admin panel-->
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <div class="partnershipArea">
                                                        <h4>Documents of your company:</h4>
                                                        <div class="row">
                                                            <!--Vat Registration Number-->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <input type="text" name="vat_registration_number" placeholder="Vat Registration Number" class="form-control" required>
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- NID Number -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <input type="text" name="nid_number" placeholder="NID Number" class="form-control" required>
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- Trade License Number  -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <input type="text" name="trade_license_number" placeholder="Trade License Number " class="form-control" required>
                                                                </div>
                                                            </div><!--col-->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <textarea name="trade_license_address" class="form-control" placeholder="Trade License Address"  required></textarea>
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- BSTI Certification -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <input type="text" name="bsti_certification" placeholder="BSTI Certification" class="form-control">
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- ISO Certification  -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <input type="text" name="iso_certification" placeholder="ISO Certification" class="form-control">
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- Naval Locations -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="form-group goodsSuply">
                                                                    <select class="wide mb20 nice-select" name="registered_nsd_id" required>
                                                                        <option value="">Naval Locations</option>
                                                                        @foreach($navalLocations as $nl)
                                                                            <option value="{!! $nl->id !!}">{!! $nl->name !!}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- Category of goods supply -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                                <div class="form-group goodsSuply">
                                                                    <select class="wide mb20 nice-select" name="supply_cat_id" required>
                                                                        <option value="">Category of goods supply</option>
                                                                        @foreach($categories as $ctg)
                                                                            <option value="{!! $ctg->id !!}">{!! $ctg->name !!}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div><!--Category of goods supply-->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="upload_files">
                                                                    <input type="file" name="profile_pic" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" required>
                                                                    <label>Company Logo (jpg/png)</label>
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- Category of goods supply -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="upload_files">
                                                                    <input type="file" name="attested_photo" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" required>
                                                                    <label>Authorized person passport size photo (jpg/png)</label>
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- Attested trade license -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="upload_files">
                                                                    <input type="file" name="attested_trade_lic" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" required>
                                                                    <label>Attested trade license (jpg/png)</label>
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- NID card photocopy -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="upload_files">
                                                                    <input type="file" name="attested_nid_photocopy" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" required>
                                                                    <label>Authorized person NID (jpg/png)</label>
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- Attested VAT registration certificate -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="upload_files">
                                                                    <input type="file" name="att_vat_reg_cert" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" required>
                                                                    <label>Attested VAT registration certificate (jpg/png)</label>
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- Attested character certificate -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="upload_files">
                                                                    <input type="file" name="attested_char_cert" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" required>
                                                                    <label>Authorized person character certificate (jpg/png)</label>
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- Attested VAT return last certificate -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="upload_files">
                                                                    <input type="file" name="att_vat_return_last_cert" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" required>
                                                                    <label>Attested last VAT return certificate (jpg/png)</label>
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- Attested last educational certificate -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="upload_files">
                                                                    <input type="file" name="att_edu_cert" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" required>
                                                                    <label>Attested educational certificate (jpg/png)</label>
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- Attested last educational certificate -->
                                                            {{--<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">--}}
                                                                {{--<div class="upload_files">--}}
                                                                    {{--<input type="file" name="att_edu_cert" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" required>--}}
                                                                    {{--<label>Attested educational certificate</label>--}}
                                                                {{--</div>--}}
                                                            {{--</div><!--col-->--}}
                                                            <!-- Last six months bank statement -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="upload_files">
                                                                    <input type="file" name="lst_six_mnth_bnk_sttmnt" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" required>
                                                                    <label>Last six months bank statement (jpg/png)</label>
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- Bank solvency certificate -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="upload_files">
                                                                    <input type="file" name="bnk_solvency_certi" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4">
                                                                    <label>Bank solvency certificate (jpg/png)</label>
                                                                </div>
                                                            </div><!--col-->
                                                            <!-- Affidavit in non-judicial stamp of Taka 300 for first class magistrate / notary public -->
                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                <div class="upload_files">
                                                                    <input type="file" name="non_judicial_stamp" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4">
                                                                    <label>Affidavit by first class magistrate/notary public in non-judicial stamp of Taka 300 (jpg/png)</label>
                                                                </div>
                                                            </div><!--col-->

                                                        </div>

                                                    </div>
                                                </div><!--/.Authorised Parsonnel Information-->

                                                <!-- Button -->
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-right">
                                                    <button type="submit" name="" id="first_submit" class="nextBtn btn mt-3">Next &#8250</button>
                                                </div>
                                            </div><!--row-->
                                        {!!   Form::close() !!}
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

<script rel="javascript" src="{!!url('public/js/bootstrapValidator.min.js') !!}"></script>
<script rel="javascript" src="{{ url('public/frontend/js/custom.js') }}"></script>
    <script type="text/javascript">

        $(document).ready(function(){
            var count = 0;
            $(document).on('click','#crntlyRegAsSuppAddMrBtn',function(){
                function a(){
                    return ++count;
                }
                $("#forCloneCurRegisAsSupplier"+count).after('<div class="row curRegSuppRem" id="forCloneCurRegisAsSupplier'+a()+'"><div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12"><div class="form-group"><label class="control-label sr-only"></label><input type="text" name="cur_reg_supplier_name[]" placeholder="Company Name" class="form-control"></div></div><div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12"><div class="form-group"><label class="control-label sr-only"></label><textarea name="cur_reg_supplier_address[]" class="form-control" placeholder="Address"></textarea></div></div></div>');

            });

            var count1 = 0;
            $(document).on('click','#descriptionOfDirectorsBtn',function(){
                function b(){
                    return ++count1;
                }
                $("#descriptionOfDirectorsDiv"+count1).after('<div class="row" id="descriptionOfDirectorsDiv'+b()+'"><div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12"><div class="form-group"><label class="control-label sr-only"></label><input type="text" name="partners_name[]" placeholder="Name" class="form-control partners_name"></div></div><div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12"><div class="form-group"><label class="control-label sr-only"></label><textarea name="partners_address[]" class="form-control partners_address" placeholder="Designation"></textarea></div></div></div>');

            });

            var count2 = 0;
            $(document).on('click','#authorisedPersonBtn',function(){
                function c(){
                    return ++count2;
                }
                $("#authorisedPersonDiv"+count2).after('<div class="row" id="authorisedPersonDiv'+c()+'"><div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12"><div class="form-group"><label class="control-label sr-only"></label><input type="text" name="auth_prsn_name[]" placeholder="Authorised person Name" class="form-control" required></div></div><div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12"><div class="form-group"><label class="control-label sr-only"></label><input type="text" name="auth_prsn_designation[]" placeholder="Designation" class="form-control" required></div></div><div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12"><div class="upload_files"><input type="file" name="bangla_signature[]" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" required><label>Bangla Signature (jpg/png)</label></div></div><div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12"><div class="upload_files"><input type="file" name="english_signature[]" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" required><label>English Signature (jpg/png)</label></div></div></div>');

            });

            $("#des_of_sole_prtship_div").hide();
            $("#partnersIfLimtdComDiv").hide();
            $('input[type=radio][name=registered_as]').change(function() {

                if (this.value == 'Yes') {
                    $("#des_of_sole_prtship").val('');
                    $("#des_of_sole_prtship_div").show();
                }else{
                    $("#des_of_sole_prtship").val('');
                    $("#des_of_sole_prtship_div").hide();
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

@stop