@extends('frontend.layouts.master')
@section('content')

    <!-- header -->
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
                        <li class="list-inline-item position-relative stepBorderRight">
                            <h3 class="mb-0">Step 2</h3>
                        </li>
                        <li class="list-inline-item position-relative stepBorderRight active">
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
                                        <h3>Supplier's Personal Information</h3>
                                        <!-- /.form-heading-title -->
                                        <!-- register-form -->
                                        {{ Form::open(array('role' => 'form', 'url' => $a.$b.'front-registration2', 'files'=> true, 'class' => 'form-horizontal registration2', 'id'=>'registration2')) }}
                                            <!-- form -->
                                            <input type="hidden" name="id" value="{!! \Session::get('newly_created_supplier_id') !!}">
                                            <div class="row">
                                                <!-- Suplier Name-->
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input type="text" name="full_name" placeholder="Full Name" class="form-control" required>
                                                    </div>
                                                </div><!--/.col-->
                                                <!-- Father's Name-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input type="text" name="father_name" placeholder="Father's Name" class="form-control" required>
                                                    </div>
                                                </div><!--/.col-->                                                
                                                <!-- Father's NID-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input type="text" name="father_nid" placeholder="Father's NID Number" class="form-control" required>
                                                    </div>
                                                </div><!--/.col-->                                                
                                                <!-- Mother's Name-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input type="text" name="mother_name" placeholder="Mother's Name" class="form-control" required>
                                                    </div>
                                                </div><!--/.col-->                                                
                                                <!-- Father's NID-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input type="text" name="mother_nid" placeholder="Mother's NID Number" class="form-control" required>
                                                    </div>
                                                </div><!--/.col-->                                               
                                                <!-- Permanent Address-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">                                                  
                                                    <div class="form-group">
                                                         <label class="control-label sr-only"></label>
                                                        <textarea name="permanent_address" class="form-control" placeholder="Permanent Address" required=""></textarea>
                                                    </div> 
                                                </div><!--/.col-->     
                                                <!-- Present Address-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">                                                  
                                                    <div class="form-group">
                                                         <label class="control-label sr-only"></label>
                                                        <textarea name="present_address" class="form-control" placeholder="Present Address" required=""></textarea>
                                                    </div> 
                                                </div><!--/.col--> 
                                                <!-- Birth Place-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input type="text" name="birth_place" placeholder="Birth Place" class="form-control" required>
                                                    </div>
                                                </div><!--/.col-->  
                                                <!-- Birth Date-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input type="text" name="birth_date" id="birthDate" placeholder="Birth Date" class="form-control input-md weddingdate" required="">
                                                        <div class="venue-form-calendar"><i class="far fa-calendar-alt"></i></div>
                                                    </div>
                                                </div><!--/.col-->  
                                                <!-- Height-->
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <input type="text" name="height" placeholder="Height" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div><!--/.col-->  
                                                <!-- Weight-->
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <input type="text" name="weight" placeholder="Weight" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div><!--/.col-->  
                                                <!-- Color-->
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <input type="text" name="color" placeholder="Color" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div><!--/.col-->  
                                                <!--Eye Color-->
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <input type="text" name="eye_color" placeholder="Eye Color" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div><!--/.col-->  
                                                <!--Identification Mark-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <input type="text" name="identification_mark" placeholder="Identification Mark" class="form-control">
                                                        </div>
                                                    </div>
                                                </div><!--/.col-->  
                                                <!--Religion-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <input type="text" name="religion" placeholder="Religion" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div><!--/.col-->  
                                                <!--Nationality-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <input type="text" name="nationality" placeholder="Nationality" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div><!--/.col-->  
                                                <!--Organization-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <input type="text" name="organization" placeholder="Organization" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div><!--/.col-->  
                                                <!--Rank in Organization-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label class="control-label sr-only"></label>
                                                            <input type="text" name="rank_in_organization" placeholder="Rank in Organization" class="form-control">
                                                        </div>
                                                    </div>
                                                </div><!--/.col-->  
                                                <!-- Business Start Date-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input name="business_start_date" id="businessStartDate" type="text" placeholder="Business Start Date" class="form-control input-md weddingdate" required="">
                                                        <div class="venue-form-calendar"><i class="far fa-calendar-alt"></i></div>
                                                    </div>
                                                </div><!--/.col-->
                                                <!--Applicant's Signature-->
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                                    <img src="" id="applicant-img-tag" style="display: none;" />
                                                    <div class="upload_files">
                                                        <input type="file" name="applicant_signature" accept="image/png, image/jpeg,image/jpg" class="applicantImg" id="applicantInputFile1" required>
                                                        <label for="">Applicant's Signature (jpg/png)</label>
                                                    </div>
                                                </div><!--col-->
                                                <!--Applicant's Seal-->
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                                    <img src="" id="applicant-img-tag2" style="display: none;" />
                                                    <div class="upload_files">
                                                        <input type="file" name="applicant_seal" accept="image/png, image/jpeg,image/jpg" class="applicantImg2" id="applicantInputFile2" required="">
                                                        <label for="">Applicant's Seal (jpg/png)</label>
                                                    </div>
                                                </div><!--col-->

                                                <!-- Membership and Previous occupation-->
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 membership mt-2">
                                            <h5>Spouse Information: </h5>
                                            <div class="row">
                                                <!-- Spouse Information Start-->
                                                <!-- Spouse Name-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input type="text" name="spouse_full_name" placeholder="Spouse Full Name" class="form-control" required>
                                                    </div>
                                                </div><!--/.col-->
                                                <!-- Spouse NID-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input type="text" name="spouse_nid" placeholder="Spouse NID Number" class="form-control" required>
                                                    </div>
                                                </div><!--/.col-->
                                                <!--Spouse Father's Name-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input type="text" name="spouse_father_name" placeholder="Spouse Father's Name" class="form-control" required>
                                                    </div>
                                                </div><!--/.col-->
                                                <!--Spouse Father's NID-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input type="text" name="spouse_father_nid" placeholder="Spouse Father's NID Number" class="form-control" required>
                                                    </div>
                                                </div><!--/.col-->
                                                <!--Spouse Mother's Name-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input type="text" name="spouse_mother_name" placeholder="Spouse Mother's Name" class="form-control" required>
                                                    </div>
                                                </div><!--/.col-->
                                                <!-- Spouse Mother's NID-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input type="text" name="spouse_mother_nid" placeholder="Spouse Mother's NID Number" class="form-control" required>
                                                    </div>
                                                </div><!--/.col-->
                                                <!--Spouse Permanent Address-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <textarea name="spouse_per_address" class="form-control" placeholder="Spouse Permanent Address"></textarea>
                                                    </div>
                                                </div><!--/.col-->
                                                <!--Spouse Present Address-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <textarea name="spouse_pre_address" class="form-control" placeholder="Spouse Present Address"></textarea>
                                                    </div>
                                                </div><!--/.col-->
                                                <!--Spouse Birth Place-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input type="text" name="spouse_birth_place" placeholder="Spouse Birth Place" class="form-control" required>
                                                    </div>
                                                </div><!--/.col-->
                                                <!--Spouse Birth Date-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input name="spouse_birth_date" id="spouseBirthDate" type="text" placeholder="Spouse Birth Date" class="form-control input-md weddingdate" required="">
                                                        <div class="venue-form-calendar"><i class="far fa-calendar-alt"></i></div>
                                                    </div>
                                                </div><!--/.col-->
                                                <!-- Spouse Nationality-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input type="text" name="spouse_nationality" placeholder="Spouse Nationality" class="form-control" required>
                                                    </div>
                                                </div><!--/.col-->
                                                <!-- Spouse Occupation-->
                                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                    <div class="form-group">
                                                        <label class="control-label sr-only"></label>
                                                        <input type="text" name="spouse_occupation" placeholder="Spouse Occupation" class="form-control" required>
                                                    </div>
                                                </div><!--/.col-->
                                                <!--Signature Place-->
                                                {{--<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">--}}
                                                    {{--<div class="form-group">--}}
                                                        {{--<label class="control-label sr-only"></label>--}}
                                                        {{--<input type="text" name="signature_place" placeholder="Signature Place" class="form-control" required>--}}
                                                    {{--</div>--}}
                                                {{--</div><!--/.col-->--}}
                                                <!--Signature Date-->
                                                {{--<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">--}}
                                                    {{--<div class="form-group">--}}
                                                        {{--<label class="control-label sr-only"></label>--}}
                                                        {{--<input name="signature_date" id="weddingdate" type="text" placeholder="Signature Date" class="form-control input-md weddingdate" required="">--}}
                                                        {{--<div class="venue-form-calendar"><i class="far fa-calendar-alt"></i></div>--}}
                                                    {{--</div>--}}
                                                {{--</div><!--/.col-->--}}

                                                </div>
                                            </div>

                                                <!-- Membership and Previous occupation-->
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 membership mt-2">
                                                    <h5>Association/Social/Cultural/Political organization Membership: </h5>
                                                    <div class="row">
                                                         <!--Organization Name-->
                                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <input type="text" name="organization_name" placeholder=" Organization Name" class="form-control" required>
                                                                </div>
                                                            </div>
                                                        </div><!--/.col--> 
                                                        <!--Organization Branch-->
                                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <input type="text" name="organization_branch" placeholder="Organization Branch" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div><!--/.col--> 
                                                        <!--Membership Number-->
                                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <input type="text" name="membership_number" placeholder="Membership Number" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div><!--/.col--> 
                                                        <!--Membership Contract Date-->
                                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                            <div class="form-group">
                                                                <label class="control-label sr-only"></label>
                                                                <input name="membrsip_cont_date" id="contractDate" type="text" placeholder="Membership Contract Date" class="form-control input-md weddingdate" required="">
                                                                <div class="venue-form-calendar"><i class="far fa-calendar-alt"></i></div>
                                                            </div>
                                                        </div><!--/.col--> 
                                                        <!--Date of Registry-->
                                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                            <div class="form-group">
                                                                <label class="control-label sr-only"></label>
                                                                <input name="date_of_registry" id="regDate" type="text" placeholder="Date of Registry" class="form-control input-md weddingdate" required="">
                                                                <div class="venue-form-calendar"><i class="far fa-calendar-alt"></i></div>
                                                            </div>                                                      
                                                            <!--Joining date of present occupation-->
                                                            <div class="form-group">
                                                                <label class="control-label sr-only"></label>
                                                                <input name="jn_date_of_prsnt_ocuptn" type="text" id="joinDate" placeholder="Joining date of present occupation" class="form-control input-md weddingdate" required="">
                                                                <div class="venue-form-calendar"><i class="far fa-calendar-alt"></i></div>
                                                            </div>
                                                        </div><!--/.col--> 
                                                        <!--  Previous Occupation-->
                                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">                                                  
                                                            <div class="form-group">
                                                                <label class="control-label sr-only"></label>
                                                                <textarea name="des_of_pre_occu" class="form-control" placeholder="Description of Previous Occupation (Last)"></textarea>
                                                            </div> 
                                                        </div><!--/.col--> 
                                                    </div><!--/.row-->                                                    
                                                </div><!--/.col-->  

                                                <!-- Description if accused under any court-->
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 membership mt-2">
                                                    <h5>Description if accused under any court:  </h5>
                                                    <div class="row">
                                                         <!--Offence-->
                                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <input type="text" name="offence" placeholder="Offence" class="form-control" required>
                                                                </div>
                                                            </div>
                                                        </div><!--/.col--> 
                                                        <!--Offence Date-->
                                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                            <div class="form-group">
                                                                <label class="control-label sr-only"></label>
                                                                <input name="offence_date" id="offenceDate" type="text" placeholder="Offence Date" class="form-control input-md weddingdate" required="">
                                                                <div class="venue-form-calendar"><i class="far fa-calendar-alt"></i></div>
                                                            </div>
                                                        </div><!--/.col--> 
                                                         <!--Offence Place-->
                                                         <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <label class="control-label sr-only"></label>
                                                                    <input type="text" name="offence_place" placeholder="Offence Place" class="form-control" required>
                                                                </div>
                                                            </div>
                                                        </div><!--/.col--> 
                                                        <!--  Offence Description-->
                                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">                                                  
                                                            <div class="form-group">
                                                                <label class="control-label sr-only"></label>
                                                                <textarea name="des_of_offence" class="form-control" placeholder="Description of Offence"></textarea>
                                                            </div> 
                                                        </div><!--/.col--> 
                                                    </div><!--/.row-->                                                    
                                                </div><!--/.col-->  

                                                <!-- Button -->
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                                                    <button type="submit" name="singlebutton" class="btn btn-default mt-3">Sign up</button>
                                                </div>
                                            </div><!--row-->
                                        {!!   Form::close() !!}
                                        <!--/.form -->
                                        {{--<p class="mt-2 text-center"> Have you subscribed? <a href="login.html" class="wizard-form-small-text"> Login</a></p>--}}
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

    <script type="text/javascript">
        $(document).ready(function(){

        });
    </script>

    @stop