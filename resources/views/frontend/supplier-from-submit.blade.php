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
                            <li class="breadcrumb-item"><a href="{!! URL::to($a.$b.'login') !!}" class="breadcrumb-link">Supplier Login</a></li>
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

                    @if (Auth::guard('supplier')->check())
                        <div class="col-lg-3 col-md-3 col-3">
                            @include('frontend/homeinc/menu')
                        </div>
                    @endif

                    <div class="col-lg-9 col-md-9 col-sm-12 col-9">
                        <!--st-tab-->
                        <div class="st-tab">
                            <div class="container">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">

                                        <div class="loginArea">
                                            <!-- form-heading-title -->

                                                @if (Auth::guard('supplier')->check())

                                                    {{ Form::model($editId, array('url' => '0/0/supplier-form-update', 'method' => 'PUT', 'files'=> true, 'class' => 'form form-horizontal validate-form supplier_info_tab', 'id' => 'suppliers')) }}

                                                    <!-- Nav tabs -->
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li role="presentation" class="active"><a href="#company-info" aria-controls="company-info" role="tab" data-toggle="tab">Company Information</a></li>
                                                            <li role="presentation"><a href="#bank_info" aria-controls="bank_info" role="tab" data-toggle="tab">Bank Information</a></li>
                                                            <li role="presentation"><a href="#introduction" aria-controls="introduction" role="tab" data-toggle="tab">Introduction Area</a></li>
                                                            <li role="presentation"><a href="#current_supplier" aria-controls="current_supplier" role="tab" data-toggle="tab">Current Supplier Area</a></li>
                                                            <li role="presentation"><a href="#company-act-area" aria-controls="company-act-area" role="tab" data-toggle="tab">Company Act Area</a></li>
                                                            <li role="presentation"><a href="#partners" aria-controls="partners" role="tab" data-toggle="tab">Partners</a></li>
                                                            <li role="presentation"><a href="#documennt" aria-controls="documennt" role="tab" data-toggle="tab">Documents of Company</a></li>
                                                        </ul>

                                                        <!-- Tab panes -->
                                                        <div class="tab-content">
                                                            <div role="tabpanel" class="tab-pane active" id="company-info">
                                                                <div class="col-md-12 col-sm-12 col-md-12">

                                                                    <div class="headOffice regFormTitle mt-2">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Company Name</label>
                                                                                    <input id="company_name" type="text" name="company_name" placeholder="Company Name" value="{!! $editId->company_name !!}" class="form-control" >
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <h4>Head Office:</h4>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <!-- Head Office Telephone-->
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="mobile_number" placeholder="Mobile No." class="form-control" value="{!! $editId->mobile_number !!}" >
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <!-- Head Office Fax-->
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="fax" id="fax" placeholder="Fax" class="form-control" value="{!! $editId->fax !!}" >
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <!-- Head Office email-->
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="email" name="email" placeholder="Email" class="form-control" value="{!! $editId->email !!}" >
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <!-- Head Office Address-->
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <textarea name="head_office_address" class="form-control" id="head_office_address"  placeholder="Address" >{!! $editId->head_office_address !!}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <h4>Tax Information:</h4>
                                                                        <div class="row">
                                                                            <!-- IncometaxNo -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="tin_number" placeholder="Incometax Identification Number" value="{!! $editId->tin_number !!}" class="form-control" >
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
                                                                                <!-- IncometaxCopy -->
                                                                                <div class="TINcopy">
                                                                                    <img src="" id="incomeTaxCopy" style="display: none;" />
                                                                                    <div class="upload_files">
                                                                                        <input type="file" name="tin_certificate" accept="image/png, image/jpeg,image/jpg" class="incomeTaxImg" id="inputFile3" >
                                                                                        <label>Upload TIN Certificate (jpg/png)</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div><!--/.Head Office Area--->

                                                                </div><!--/.col-->

                                                                <div class="col-md-12 remove appendRewRow firstRow" id="firstRow">

                                                                    <span><b></b></span><br>

                                                                    <div class="col-md-3" style="padding-right: 25px;">
                                                                        <div class="form-group">
                                                                            <label for="name">Name:</label>
                                                                            <input type="text" class="form-control unit" id="name" name="name[]" placeholder="Name" >
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-3" style="padding-right: 25px;">
                                                                        <div class="form-group">
                                                                            <label for="designation">Designation:</label>
                                                                            <input type="designation" class="form-control unit" id="designation" name="designation[]" placeholder="Designation" >
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-3" style="padding-right: 25px;">
                                                                        <div class="form-group">
                                                                            <label for="mobile_number">Mobile Number:</label>
                                                                            <input type="mobile_number" class="form-control unit" id="mobile_number" name="mobile_number1[]" placeholder="Mobile Number" >
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="barcode_number">Barcode Number:</label>
                                                                            <input type="barcode_number" class="form-control unit" id="barcode_number" name="barcode_number1[]" placeholder="Barcode Number" >
                                                                        </div>
                                                                    </div>

                                                                    <div style="clear: both !important;"><hr style="border-top: 1px solid #999999;"></div>

                                                                </div>

                                                                <div class="col-md-12">
                                                                    <div class="col-md-3"></div><div class="col-md-3"></div><div class="col-md-3"></div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group pull-right">
                                                                            <button class="btn btn-info" id="addNewRow" type="button" title="Add New"><i class="icon-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                            <div role="tabpanel" class="tab-pane" id="bank_info">
                                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 regFormTitle branchAddress mt-2">

                                                                    <!-- Bank Account Area-->
                                                                    <div class="bankAccount regFormTitle mt-2">
                                                                        <h4>Bank Account Information:</h4>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <!-- Bank Account Number-->
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="bank_account_number" placeholder="Account Number" class="form-control" id="bank_account_number" value="{!! $editId->bank_account_number !!}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <!-- Bank Name-->
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="bank_name" placeholder="Bank Name" class="form-control" value="{!! $editId->bank_name_and_branch !!}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <!-- Relationship-->
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="rltn_w_acc_holder" id="rltn_w_acc_holder" placeholder="Relationship with the bank account holder" class="form-control" value="{!! $editId->rltn_w_acc_holder !!}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <!-- Bank Address-->
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <textarea name="address" class="form-control" placeholder="Address" >{!! $editId->branch_office_address !!}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <h4>Branch Office (If any):</h4>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <!--Branch Address-->
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <textarea name="branch_office_address" class="form-control" id="branch_office_address" placeholder="Branch Address"> {!! $editId->branch_office_address !!} </textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div><!--/.bankAccount Area--->
                                                                </div><!--/.col-->
                                                            </div>
                                                            <div role="tabpanel" class="tab-pane" id="introduction">
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
                                                                                    <input type="text" name="intr_name[]" placeholder="Introducer Name" class="form-control" value="@if($editId->intr_name != null){!! json_decode($editId->intr_name)[0] !!} @endif" >
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!--Introducer Designation-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="intr_designation[]" placeholder="Introducer Designation" class="form-control" @if($editId->intr_designation != null){!! json_decode($editId->intr_designation)[0] !!} @endif >
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Introducer Address-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <textarea name="intr_address[]" class="form-control" placeholder="Introducer Address" > @if($editId->intr_address != null){!! json_decode($editId->intr_address )[0] !!} @endif </textarea>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                                                                <!--Introducer Signature-->
                                                                                <img src="" id="profile_img_tag" style="display: none;" />
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="testimonial[]" accept="image/png, image/jpeg,image/jpg" class="profileImg" id="inputFile1" >
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

                                                                                    <input type="text" name="intr_name[]" placeholder="Introducer Name" class="form-control" @if($editId->intr_name  != null){!! json_decode($editId->intr_name)[1] !!} @endif >
                                                                                </div>
                                                                            </div><!--col-->

                                                                            <!--Introducer Designation-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="intr_designation[]" placeholder="Introducer Designation" class="form-control" @if($editId->intr_designation != null){!! json_decode($editId->intr_designation)[1] !!} @endif >
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Introducer Address-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <textarea name="intr_address[]" class="form-control" placeholder="Introducer Address" >@if($editId->intr_address != null){!! json_decode($editId->intr_address)[1] !!} @endif</textarea>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                                                                <!--Introducer Signature-->
                                                                                <img src="" id="profile_img_tag" style="display: none;" />
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="testimonial[]"accept="image/png, image/jpeg,image/jpg" class="profileImg" id="inputFile1" >
                                                                                    <label for="studentSig">Testimonial (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                        </div>
                                                                        <!--<button type="submit" name="addMoreBtn" class="btn btn-primary addMoreBtn mb-2">Add More</button>-->

                                                                    </div><!--/.introducerArea-->
                                                                </div>
                                                            </div>
                                                            <div role="tabpanel" class="tab-pane" id="current_supplier">
                                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                                    <div class="introducerArea" style="padding-bottom: 30px;">
                                                                        <h4>Currently registered as supplier at :</h4>
                                                                        <div class="curRegSuppRem" id="forCloneCurRegisAsSupplier0">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label sr-only"></label>
                                                                                        <input type="text" name="cur_reg_supplier_name[]" placeholder="Compnay Name" class="form-control">
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
                                                            <div role="tabpanel" class="tab-pane" id="company-act-area">
                                                                <!-- defaulterArea left-->
                                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                                    <h5>Is the company declared as defaulter before? </h5>
                                                                </div>
                                                                <!--defaulterArea-->
                                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                                    <div class="defaulterArea d-flex">
                                                                        <div class="form-group">
                                                                            <label class="radio_container col-md-12">Yes
                                                                                <input type="radio" name="defaulter_before" value="Yes" @if($editId->defaulter_before == 'Yes') checked="checked" @endif>
                                                                                <span class="checkmark"></span>
                                                                            </label>
                                                                        </div><!--form-group-->
                                                                        <div class="form-group">
                                                                            <label class="radio_container col-md-12">No
                                                                                <input type="radio" name="defaulter_before" value="No" @if($editId->defaulter_before == 'No') checked="checked" @endif>
                                                                                <span class="checkmark"></span>
                                                                            </label>
                                                                        </div><!--form-group-->
                                                                    </div>
                                                                </div>
                                                                <!-- company act left-->
                                                                <div class="col-xl-9 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                    <h5>Is the company registered under factory, company or partnership act? </h5>
                                                                </div>
                                                                <!--companyActArea-->
                                                                <div class="col-xl-3 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                    <div class="companyActArea d-flex">
                                                                        <div class="form-group">
                                                                            <label class="radio_container col-md-12">Yes
                                                                                <input type="radio" name="company_partnership_act" value="Yes" @if($editId->company_partnership_act == 'Yes') checked="checked" @endif >
                                                                                <span class="checkmark"></span>
                                                                            </label>
                                                                        </div><!--form-group-->
                                                                        <div class="form-group">
                                                                            <label class="radio_container col-md-12">No
                                                                                <input type="radio" name="company_partnership_act" value="No" @if($editId->company_partnership_act == 'No') checked="checked" @endif>
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
                                                                                <input type="radio" name="registered_as" class="soleProship" value="Yes" @if($editId->registered_as == 'Yes') checked="checked" @endif>
                                                                                <span class="checkmark"></span>
                                                                            </label>
                                                                        </div><!--form-group-->
                                                                        <div class="form-group">
                                                                            <label class="radio_container col-md-12">Limited company
                                                                                <input type="radio" name="registered_as" class="soleProship" value="No" @if($editId->registered_as == 'No') checked="checked" @endif>
                                                                                <span class="checkmark"></span>
                                                                            </label>
                                                                        </div><!--form-group-->
                                                                    </div>
                                                                </div>
                                                                <!-- Sole propietorship -->
                                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" id="des_of_sole_prtship_div">
                                                                    <div class="form-group solePropietorship">
                                                                        <label class="control-label sr-only"></label>
                                                                        <textarea name="des_of_sole_prtship" id="des_of_sole_prtship" class="form-control" placeholder="Description of Sole Propietorship ">{!! $editId->des_of_sole_prtship !!}</textarea>
                                                                    </div>
                                                                </div><!--col-->

                                                            </div>
                                                            <div role="tabpanel" class="tab-pane" id="partners">
                                                                <!--Partners Information-->
                                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" id="partnersIfLimtdComDiv">

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
                                                                <!--Authorised Parsonnel Information-->

                                                            </div>
                                                            <div role="tabpanel" class="tab-pane" id="documennt">
                                                                <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12">
                                                                    <div class="partnershipArea">
                                                                        <h4>Documents of your company:</h4>
                                                                        <div class="row">
                                                                            <!--Vat Registration Number-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="vat_registration_number" placeholder="Vat Registration Number" class="form-control" value="{!! $editId->vat_registration_number  !!}" >
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- NID Number -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="nid_number" placeholder="NID Number" class="form-control" value="{!! $editId->nid_number  !!}" >
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Trade License Number  -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="trade_license_number" placeholder="Trade License Number " class="form-control" value="{!! $editId->trade_license_number  !!}"  >
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <textarea name="trade_license_address" class="form-control" placeholder="Trade License Address"  >{!! $editId->trade_license_address  !!}</textarea>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- BSTI Certification -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="bsti_certification" placeholder="BSTI Certification" class="form-control" value="{!! $editId->bsti_certification  !!}">
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- ISO Certification  -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label sr-only"></label>
                                                                                    <input type="text" name="iso_certification" placeholder="ISO Certification" class="form-control" value="{!! $editId->iso_certification  !!}">
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Naval Locations -->

                                                                            <!-- Category of goods supply -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="profile_pic" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" >
                                                                                    <label>Company Logo (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Category of goods supply -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="attested_photo" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" >
                                                                                    <label>Authorized person passport size photo (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Attested trade license -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="attested_trade_lic" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" >
                                                                                    <label>Attested trade license (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- NID card photocopy -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="attested_nid_photocopy" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" >
                                                                                    <label>Authorized person NID (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Attested VAT registration certificate -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="att_vat_reg_cert" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" >
                                                                                    <label>Attested VAT registration certificate (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Attested character certificate -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="attested_char_cert" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" >
                                                                                    <label>Authorized person character certificate (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Attested VAT return last certificate -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="att_vat_return_last_cert" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" >
                                                                                    <label>Attested last VAT return certificate (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Attested last educational certificate -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="att_edu_cert" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" >
                                                                                    <label>Attested educational certificate (jpg/png)</label>
                                                                                </div>
                                                                            </div><!--col-->
                                                                            <!-- Attested last educational certificate -->
                                                                        {{--<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">--}}
                                                                        {{--<div class="upload_files">--}}
                                                                        {{--<input type="file" name="att_edu_cert" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" >--}}
                                                                        {{--<label>Attested educational certificate</label>--}}
                                                                        {{--</div>--}}
                                                                        {{--</div><!--col-->--}}
                                                                        <!-- Last six months bank statement -->
                                                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                                                <div class="upload_files">
                                                                                    <input type="file" name="lst_six_mnth_bnk_sttmnt" accept="image/png, image/jpeg,image/jpg" class="authorSig" id="inputFile4" >
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

                                                            </div>
                                                        </div>



                                                        <div class="form-group">

                                                            <div class="col-md-12">

                                                                <button type="submit" class="btn btn-primary pull-right">{!!trans('english.SAVE')!!}</button>

                                                            </div>

                                                        </div>


                                                        <!-- <div class="hr-line-dashed"></div> -->
                                                        {!!   Form::close() !!}

                                                @endif


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

      <script rel="javascript" src="{{ url('public/frontend/js/custom.js') }}"></script>
      <script type="text/javascript">

          $(document).ready(function(){

              var i = 0;
              var sl = 2;
              $(document).on('click','#addNewRow',function(){
                  $( "body" ).find( ".firstRow" ).eq( i ).after( ' <div class="col-md-12 remove appendRewRow firstRow" id="firstRow">\n' +
                      '\n' +
                      '                            <span><b></b></span><br>\n' +
                      '\n' +
                      '                            <div class="col-md-3" style="padding-right: 25px;">\n' +
                      '                                <div class="form-group">\n' +
                      '                                    <label for="name">Name:</label>\n' +
                      '                                    <input type="text" class="form-control name" id="name" name="name[]" placeholder="Name" >\n' +
                      '                                </div>\n' +
                      '                            </div>\n' +
                      '\n' +
                      '                            <div class="col-md-3" style="padding-right: 25px;">\n' +
                      '                                <div class="form-group">\n' +
                      '                                    <label for="designation">Designation:</label>\n' +
                      '                                    <input type="designation" class="form-control designation" id="designation" name="designation[]" placeholder="Designation" >\n' +
                      '                                </div>\n' +
                      '                            </div>\n' +
                      '\n' +
                      '                            <div class="col-md-3" style="padding-right: 25px;">\n' +
                      '                                <div class="form-group">\n' +
                      '                                    <label for="mobile_number">Mobile Number:</label>\n' +
                      '                                    <input type="mobile_number" class="form-control mobile_number" id="mobile_number" name="mobile_number1[]" placeholder="Mobile Number" >\n' +
                      '                                </div>\n' +
                      '                            </div>\n' +
                      '\n' +
                      '                            <div class="col-md-2">\n' +
                      '                                <div class="form-group">\n' +
                      '                                    <label for="barcode_number">Barcode Number:</label>\n' +
                      '                                    <input type="barcode_number" class="form-control barcode_number" id="barcode_number" name="barcode_number1[]" placeholder="Barcode Number" >\n' +
                      '                                </div>\n' +
                      '                            </div>\n' +
                      '\n' +
                      '                            <div class="col-md-1" style="margin-top: 25px;"><button class="btn btn-danger removeRow" id="" type="button" data-placement="top" data-rel="tooltip" data-original-title="Remove"><i class="fa fa-trash"></i></button></div> <div style="clear: both !important;"><hr style="border-top: 1px solid #999999;"></div>\n' +
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
                  $("#forCloneCurRegisAsSupplier"+count).after('<div class="row curRegSuppRem" id="forCloneCurRegisAsSupplier'+a()+'"><div class="col-md-6 col-sm-12 col-12"><div class="form-group"><label class="control-label sr-only"></label><input type="text" name="cur_reg_supplier_name[]" placeholder="Company Name" class="form-control"></div></div><div class="col-md-6  col-12"><div class="form-group"><label class="control-label sr-only"></label><textarea name="cur_reg_supplier_address[]" class="form-control" placeholder="Address"></textarea></div></div></div>');

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