@extends('frontend.layouts.master')
@section('content')
<style>
    table th,table td{
        text-align: left !important;
        padding-left: 5px;
        vertical-align: middle !important;
    }
</style>
    @include('layouts.flash')

    <!-- page-header -->
    <div id="inner-page-header" class="page-header position-relative">
        <div class="container">
            <div class="row">
                <!-- page caption -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                    <div class="page-caption">
                        <h1 class="page-title">Profile</h1>
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

                            <li class="breadcrumb-item active text-white" aria-current="page">Profile</li>
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
            <div class="container-fluid">
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



                                        <div class="" style="text-align: center;background-color: #fff;padding: 10px;">
                                            <!-- form-heading-title -->
                                            @if (Auth::guard('supplier')->check())


                                     <table class="table table-bordered table-hover" style="background-color: #fff;">
                                        <thead>
                                        <?php
                                            function supply_cat_name($cat_id=null){
                                                $calName = \App\SupplyCategory::where('id','=',$cat_id)->value('name');
                                                return $calName;
                                            }

                                            function supply_nsd_name($nsd_id=null){
                                                $calName = \App\NsdName::where('id','=',$nsd_id)->value('name');
                                                return $calName;
                                            }

                                            $nsd_n_link = '';
                                            if($suppliers->registered_nsd_id == 1){
                                                $nsd_n_link = "http://202.74.244.138";
                                            }
                                            if($suppliers->registered_nsd_id == 2){
                                                $nsd_n_link = "http://sims.navy.mil.bd/nsd/nsd_chattagram";
                                            }
                                            if($suppliers->registered_nsd_id == 3){
                                                $nsd_n_link = "http://sims.navy.mil.bd/nsd/nsd_khulna";
                                            }
                                            if($suppliers->registered_nsd_id == 4){
                                                $nsd_n_link = "http://sims.navy.mil.bd/nsd/dgdp";
                                            }
                                            if($suppliers->registered_nsd_id == 5){
                                                $nsd_n_link = "http://sims.navy.mil.bd/bsd/bsd_dhaka";
                                            }
                                            if($suppliers->registered_nsd_id == 6){
                                                $nsd_n_link = "http://sims.navy.mil.bd/bsd/bsd_chattagram";
                                            }
                                            if($suppliers->registered_nsd_id == 7){
                                                $nsd_n_link = "http://sims.navy.mil.bd/bsd/bsd_khulna";
                                            }

                                        ?>
                                        </thead>
                                               <tbody>
                                        <tr>
                                            <td colspan="4" style="text-align: center !important;">
                                            @if(!empty($suppliers->profile_pic))
                                                <img width="150" height="120" src="{!! $nsd_n_link !!}/public/uploads/supplier_profile/{{$suppliers->profile_pic}}">
                                            @else
                                                 <img src="{{URL::to('/')}}/public/upload/systemSettings/0AMYVmrii1fFAoa4lD8R.png" alt="" style="-webkit-filter: grayscale(100%); filter: grayscale(100%); width: 100; height: 80;">   
                                            @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">{{'Company Name'}}</th>
                                            <td colspan="2">{{$suppliers->company_name}}</td>

                                            {{-- <th>{{'Company Reg. Number '}}</th>
                                            <td>{{$suppliers->company_regi_number_nsd}}</td> --}}
                                        </tr>
                                        <tr>
                                            <th>{{'Company Reg. Number '}}</th>
                                            <td>{{$suppliers->company_regi_number_nsd}}</td>

                                            <th>{{'Barcode Number'}}</th>
                                            <td>{{$suppliers->barcode_number}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{'Mobile Number '}}</th>
                                            <td>{{$suppliers->mobile_number}}</td>

                                            <th>{{'Supply Category'}}</th>
                                            <td>
                                                <?php
                                                $catids = explode(',',$suppliers->supply_cat_id);
                                                foreach ($catids as $ctds) {
                                                    $valsss = supply_cat_name($ctds);
                                                    echo "<li>".$valsss."</li>";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{'Fax'}}</th>
                                            <td>{{$suppliers->fax}}</td>

                                            <th>{{'Head Office Address'}}</th>
                                            <td>{{$suppliers->head_office_address}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{'Vat Registration Number '}}</th>
                                            <td>{{$suppliers->vat_registration_number}}</td>

                                            <th>{{'TIN Number'}}</th>
                                            <td>{{$suppliers->tin_number}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{'Email '}}</th>
                                            <td>{{$suppliers->email}}</td>

                                            <th>{{'NID Number'}}</th>
                                            <td>{{$suppliers->nid_number}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{'Trade License Number '}}</th>
                                            <td>{{$suppliers->trade_license_number}}</td>

                                            <th>{{'Trade License Address'}}</th>
                                            <td>{{$suppliers->trade_license_address}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{'BSTI Certification'}}</th>
                                            <td>{{$suppliers->bsti_certification}}</td>

                                            <th>{{'ISO Certification'}}</th>
                                            <td>{{$suppliers->iso_certification}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{'Company Bank Account Name'}}</th>
                                            <td>{{$suppliers->company_bank_account_name}}</td>

                                            <th>{{'Bank Account Number'}}</th>
                                            <td>{{$suppliers->bank_account_number}}</td>

                                        </tr>
                                        <tr>
                                            <th>{{'Bank Name & Branch '}}</th>
                                            <td>{{$suppliers->bank_name_and_branch}}</td>

                                            <th>{{'Date Of Enrollment'}}</th>
                                            <td>{{date('Y-m-d',strtotime($suppliers->date_of_enrollment))}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{'Relation With Account Holder'}}</th>
                                            <td>{{$suppliers->rltn_w_acc_holder}}</td>

                                            <th>{{'Branch Office Address'}}</th>
                                            <td>{{$suppliers->branch_office_address}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{'Introducer Name'}}</th>
                                            <td>
                                                <?php
                                                $intr_name = json_decode($suppliers->intr_name);
                                                if(!empty($intr_name))
                                                foreach ($intr_name as $inn) {
                                                    echo "<li>".$inn."</li>";
                                                }
                                                ?>
                                            </td>

                                            <th>{{'Introducer Designation'}}</th>
                                            <td>
                                                <?php
                                                $intr_designation = json_decode($suppliers->intr_designation);
                                                if(!empty($intr_designation))
                                                foreach ($intr_designation as $inndg) {
                                                    echo "<li>".$inndg."</li>";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{'Introducer Address'}}</th>
                                            <td>
                                                <?php
                                                $intr_address = json_decode($suppliers->intr_address);
                                                if(!empty($intr_address))
                                                foreach ($intr_address as $innad) {
                                                    echo "<li>".$innad."</li>";
                                                }
                                                ?>
                                            </td>

                                            <th></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th>{{'Currently Registered as Supplier Name'}}</th>
                                            <td>
                                                <?php
                                                $cur_reg_supplier_name = json_decode($suppliers->cur_reg_supplier_name);
                                                if(!empty($cur_reg_supplier_name))
                                                foreach ($cur_reg_supplier_name as $curreg) {
                                                    echo "<li>".$curreg."</li>";
                                                }
                                                ?>
                                            </td>

                                            <th>{{'Currently Registered as Supplier Address'}}</th>
                                            <td>
                                                <?php
                                                $cur_reg_supplier_address = json_decode($suppliers->cur_reg_supplier_address);
                                                if(!empty($cur_reg_supplier_address))
                                                foreach ($cur_reg_supplier_address as $curregad) {
                                                    echo "<li>".$curregad."</li>";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{'Company Declared as Defaulter Before?'}}</th>
                                            <td>{{$suppliers->defaulter_before}}</td>

                                            <th>{{'Company registered under factory act, company act or partnership act?'}}</th>
                                            <td>{{$suppliers->company_partnership_act}}</td>

                                        </tr>
                                        <tr>
                                            <th>{{'Company registered as'}}</th>
                                            <td>
                                                @if($suppliers->registered_as=='Yes')
                                                    {{'Sole proprietorship'}}
                                                @endif
                                                @if($suppliers->registered_as=='No')
                                                    {{'Limited company'}}
                                                @endif
                                            </td>
                                            @if($suppliers->registered_as=='Yes')
                                                <th>{{'Description of Sole Propietorship'}}</th>
                                                <td>{{$suppliers->des_of_sole_prtship}}</td>
                                            @endif
                                            @if($suppliers->registered_as=='No')
                                                <th>{{'Partners'}}</th>
                                                <td>
                                                    <div>
                                                        <b>Partners Name</b><br>
                                                        <?php
                                                        $partners_name = json_decode($suppliers->partners_name);
                                                        if(!empty($partners_name))
                                                        foreach ($partners_name as $ptn) {
                                                            echo "<li>".$ptn."</li>";
                                                        }
                                                        ?>
                                                    </div>
                                                    <div>
                                                        <b>Partners Designation</b><br>
                                                        <?php
                                                        $partners_address = json_decode($suppliers->partners_address);
                                                        if(!empty($partners_address))
                                                        foreach ($partners_address as $ptndes) {
                                                            echo "<li>".$ptndes."</li>";
                                                        }
                                                        ?>
                                                    </div>

                                                </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>{{'Authorised Person Name'}}</th>
                                            <td>
                                                <?php
                                                $auth_prsn_name = json_decode($suppliers->auth_prsn_name);
                                                if(!empty($auth_prsn_name))
                                                foreach ($auth_prsn_name as $autperna) {
                                                    echo "<li>".$autperna."</li>";
                                                }
                                                ?>
                                            </td>

                                            <th>{{'Authorised Person Designation'}}</th>
                                            <td>
                                                <?php
                                                $auth_prsn_designation = json_decode($suppliers->auth_prsn_designation);
                                                if(!empty($auth_prsn_designation))
                                                foreach ($auth_prsn_designation as $autperdes) {
                                                    echo "<li>".$autperdes."</li>";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{'Organization'}}</th>
                                            <td>
                                                <?php
                                                $nsdids = explode(',',$suppliers->registered_nsd_id);
                                                if(!empty($nsdids))
                                                foreach ($nsdids as $nsd) {
                                                    $valssss = supply_nsd_name($nsd);
                                                    echo "<li>".$valssss."</li>";
                                                }
                                                ?>
                                            </td>

                                            <th>{{'Status'}}</th>
                                            <td>
                                                @if ($suppliers->status_id == '1')
                                                    <span class="label label-success">{{trans('english.ACTIVE')}}</span>
                                                @endif
                                                @if($suppliers->status_id == '2')
                                                    <span class="label label-warning">{{trans('english.INACTIVE')}}</span>
                                                @endif
                                                @if($suppliers->status_id == '3')
                                                    <span class="label label-info">{{'Pending'}}</span>
                                                @endif
                                                @if($suppliers->status_id == '4')
                                                    <span class="label label-danger">{{'Rejected'}}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{'Company Logo'}}</th>
                                            <td>
                                                @if(!empty($suppliers->profile_pic))
                                                <img width="100" height="80" src="{!! $nsd_n_link !!}/public/uploads/supplier_profile/{{$suppliers->profile_pic}}">
                                                @endif
                                            </td>

                                            <th>{{'TIN Certificate'}}</th>

                                            <td>
                                                @if(!empty($suppliers->tin_certificate))
                                                <img width="100" height="80" src="{!! $nsd_n_link !!}/public/uploads/supplier_profile/{{$suppliers->tin_certificate}}">
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{'Testimonial'}}</th>
                                            <td>
                                                <?php
                                                $testimonial = json_decode($suppliers->testimonial);
                                                if(!empty($testimonial))
                                                foreach ($testimonial as $tmn) { ?>
                                                 @if(!empty($tmn))
                                                <img width="100" height="80" src="{!! $nsd_n_link !!}/public/uploads/supplier_profile/{{$tmn}}">
                                                @endif
                                                <?php  }
                                                ?>
                                            </td>

                                            <th></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th>{{'Bangla Signature'}}</th>
                                            <td>
                                                <?php
                                                $bangla_signature = json_decode($suppliers->bangla_signature);
                                                if(!empty($bangla_signature))
                                                foreach ($bangla_signature as $bsg) { ?>
                                                <img width="100" height="80" src="{!! $nsd_n_link !!}/public/uploads/supplier_profile/{{$bsg}}">
                                                <?php  }
                                                ?>
                                            </td>
                                            <th>{{'English Signature'}}</th>
                                            <td>
                                                <?php
                                                $english_signature = json_decode($suppliers->english_signature);
                                                if(!empty($english_signature))
                                                foreach ($english_signature as $esg) { ?>
                                                <img width="100" height="80" src="{!! $nsd_n_link !!}/public/uploads/supplier_profile/{{$esg}}">
                                                <?php  }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{'Attested Photo'}}</th>
                                            <td>
                                                 @if(!empty($suppliers->attested_photo))
                                                <img width="100" height="80" src="{!! $nsd_n_link !!}/public/uploads/supplier_profile/{{$suppliers->attested_photo}}">
                                                @endif
                                            </td>

                                            <th>{{'Attested trade license'}}</th>
                                            <td>
                                                @if(!empty($suppliers->attested_trade_lic))
                                                <img width="100" height="80" src="{!! $nsd_n_link !!}/public/uploads/supplier_profile/{{$suppliers->attested_trade_lic}}">
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{'Attested NID card photocopy'}}</th>
                                            <td>
                                                @if(!empty($suppliers->attested_nid_photocopy))
                                                <img width="100" height="80" src="{!! $nsd_n_link !!}/public/uploads/supplier_profile/{{$suppliers->attested_nid_photocopy}}">
                                                @endif
                                            </td>

                                            <th>{{'Attested character certificate'}}</th>
                                            <td>
                                                @if(!empty($suppliers->attested_char_cert))
                                                <img width="100" height="80" src="{!! $nsd_n_link !!}/public/uploads/supplier_profile/{{$suppliers->attested_char_cert}}">
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{'Attested VAT registration certificate'}}</th>
                                            <td>
                                                @if(!empty($suppliers->att_vat_reg_cert))
                                                <img width="100" height="80" src="{!! $nsd_n_link !!}/public/uploads/supplier_profile/{{$suppliers->att_vat_reg_cert}}">
                                                @endif
                                            </td>

                                            <th>{{'Attested VAT return last certificate'}}</th>
                                            <td>
                                                @if(!empty($suppliers->att_vat_return_last_cert))
                                                <img width="100" height="80" src="{!! $nsd_n_link !!}/public/uploads/supplier_profile/{{$suppliers->att_vat_return_last_cert}}">
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{'Attested educational certificate'}}</th>
                                            <td>
                                                @if(!empty($suppliers->att_edu_cert))
                                                <img width="100" height="80" src="{!! $nsd_n_link !!}/public/uploads/supplier_profile/{{$suppliers->att_edu_cert}}">
                                                @endif
                                            </td>

                                            <th>{{'Last six months bank statement'}}</th>
                                            <td>
                                                 @if(!empty($suppliers->lst_six_mnth_bnk_sttmnt))
                                                <img width="100" height="80" src="{!! $nsd_n_link !!}/public/uploads/supplier_profile/{{$suppliers->lst_six_mnth_bnk_sttmnt}}">
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{'Bank solvency certificate'}}</th>
                                            <td>
                                                 @if(!empty($suppliers->bnk_solvency_certi))
                                                <img width="100" height="80" src="{!! $nsd_n_link !!}/public/uploads/supplier_profile/{{$suppliers->bnk_solvency_certi}}">
                                                @endif
                                            </td>

                                            <th>{{'Affidavit in non-judicial stamp'}}</th>
                                            <td>
                                                @if(!empty($suppliers->non_judicial_stamp))
                                                <img width="100" height="80" src="{!! $nsd_n_link !!}/public/uploads/supplier_profile/{{$suppliers->non_judicial_stamp}}">
                                                @endif
                                            </td>
                                        </tr>

                                        </tbody>       
                                        </table>

                                              @endif
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
@stop