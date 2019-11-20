@extends('layouts.default')

<style>
    .hpanel.panel-collapse > .panel-heading, .hpanel .hbuilt{
        background: rgba(255, 255, 255, 0.64) !important;
    }
</style>

@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Suppliers</h3>
                </h2>
            </div>
            @include('layouts.flash')
        </div>
    </div>
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading hbuilt">
                        <h3>Suppliers</h3>
                    </div>

                        <div class="panel-body">

                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#supplierInf" aria-controls="supplierInf" role="tab" data-toggle="tab">Supplier Information</a></li>
                                <li role="presentation"><a href="#supplerPerInfo" aria-controls="officeTime" role="tab" data-toggle="tab">Supplier Personal Information</a></li>
                                <li role="presentation"><a href="#supplerTenderInfo" aria-controls="officeTime" role="tab" data-toggle="tab">Supplier Tender Information</a></li>
                            </ul>

                            <div class="tab-content panel">

                                {{--supplierInf tab ================================================================--}}
                                <div role="tabpanel" class="tab-pane active" id="supplierInf">
                                    <table class="table table-bordered table-hover table-striped middle-align">
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
                                        ?>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="4">
                                            @if(!empty($suppliers->profile_pic))
                                                <img width="150" height="120" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$suppliers->profile_pic}}">
                                            @else
                                                 <img src="{{URL::to('/')}}/public/upload/systemSettings/0AMYVmrii1fFAoa4lD8R.png" alt="" style="-webkit-filter: grayscale(100%); filter: grayscale(100%); width: 100; height: 80;">   
                                            @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{'Company Name'}}</th>
                                            <td>{{$suppliers->company_name}}</td>

                                            <th>{{'Company Reg. Number '}}</th>
                                            <td>{{$suppliers->company_regi_number_nsd}}</td>
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
                                            <td><img width="100" height="80" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$suppliers->profile_pic}}"></td>

                                            <th>{{'TIN Certificate'}}</th>
                                            <td><img width="100" height="80" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$suppliers->tin_certificate}}"></td>
                                        </tr>
                                        <tr>
                                            <th>{{'Testimonial'}}</th>
                                            <td>
                                                <?php
                                                $testimonial = json_decode($suppliers->testimonial);
                                                if(!empty($testimonial))
                                                foreach ($testimonial as $tmn) { ?>
                                                <img width="100" height="80" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$tmn}}">
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
                                                <img width="100" height="80" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$bsg}}">
                                                <?php  }
                                                ?>
                                            </td>
                                            <th>{{'English Signature'}}</th>
                                            <td>
                                                <?php
                                                $english_signature = json_decode($suppliers->english_signature);
                                                if(!empty($english_signature))
                                                foreach ($english_signature as $esg) { ?>
                                                <img width="100" height="80" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$esg}}">
                                                <?php  }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{'Attested Photo'}}</th>
                                            <td><img width="100" height="80" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$suppliers->attested_photo}}"></td>

                                            <th>{{'Attested trade license'}}</th>
                                            <td><img width="100" height="80" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$suppliers->attested_trade_lic}}"></td>
                                        </tr>
                                        <tr>
                                            <th>{{'Attested NID card photocopy'}}</th>
                                            <td><img width="100" height="80" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$suppliers->attested_nid_photocopy}}"></td>

                                            <th>{{'Attested character certificate'}}</th>
                                            <td><img width="100" height="80" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$suppliers->attested_char_cert}}"></td>
                                        </tr>
                                        <tr>
                                            <th>{{'Attested VAT registration certificate'}}</th>
                                            <td><img width="100" height="80" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$suppliers->att_vat_reg_cert}}"></td>

                                            <th>{{'Attested VAT return last certificate'}}</th>
                                            <td><img width="100" height="80" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$suppliers->att_vat_return_last_cert}}"></td>
                                        </tr>
                                        <tr>
                                            <th>{{'Attested educational certificate'}}</th>
                                            <td><img width="100" height="80" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$suppliers->att_edu_cert}}"></td>

                                            <th>{{'Last six months bank statement'}}</th>
                                            <td><img width="100" height="80" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$suppliers->lst_six_mnth_bnk_sttmnt}}"></td>
                                        </tr>
                                        <tr>
                                            <th>{{'Bank solvency certificate'}}</th>
                                            <td><img width="100" height="80" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$suppliers->bnk_solvency_certi}}"></td>

                                            <th>{{'Affidavit in non-judicial stamp'}}</th>
                                            <td><img width="100" height="80" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$suppliers->non_judicial_stamp}}"></td>
                                        </tr>

                                        </tbody>
                                    </table><!---/table-responsive-->
                                </div>
                                {{--supplerPerInfo tab=================================================================--}}
                                <div role="tabpanel" class="tab-pane" id="supplerPerInfo">
                                    <h5>Supplier Basic Information</h5>
                                    @if(!empty($supplierPersonalInfo))
                                        <table class="table table-bordered table-hover table-striped middle-align">

                                            <tbody>
                                            <tr>
                                                <th>{{'Full Name'}}</th>
                                                <td>{{$supplierPersonalInfo->full_name}}</td>

                                                <th>{{"Father's Name "}}</th>
                                                <td>{{$supplierPersonalInfo->father_name}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Father's NID"}}</th>
                                                <td>{{$supplierPersonalInfo->father_nid}}</td>

                                                <th>{{"Mothers's Name "}}</th>
                                                <td>{{$supplierPersonalInfo->mother_name}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Mothers's NID"}}</th>
                                                <td>{{$supplierPersonalInfo->mother_nid}}</td>

                                                <th>{{"Permanent Address "}}</th>
                                                <td>{{$supplierPersonalInfo->permanent_address}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Present Address"}}</th>
                                                <td>{{$supplierPersonalInfo->present_address}}</td>

                                                <th>{{"Birth Place "}}</th>
                                                <td>{{$supplierPersonalInfo->birth_place}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Birth Date"}}</th>
                                                <td>{{date('d-m-Y',strtotime($supplierPersonalInfo->birth_date))}}</td>

                                                <th>{{"Height "}}</th>
                                                <td>{{$supplierPersonalInfo->height}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Weight"}}</th>
                                                <td>{{$supplierPersonalInfo->weight}}</td>

                                                <th>{{"Color "}}</th>
                                                <td>{{$supplierPersonalInfo->color}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Eye Color"}}</th>
                                                <td>{{$supplierPersonalInfo->eye_color}}</td>

                                                <th>{{"Identification Mark "}}</th>
                                                <td>{{$supplierPersonalInfo->identification_mark}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Religion"}}</th>
                                                <td>{{$supplierPersonalInfo->religion}}</td>

                                                <th>{{"Nationality "}}</th>
                                                <td>{{$supplierPersonalInfo->nationality}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Organization"}}</th>
                                                <td>{{$supplierPersonalInfo->organization}}</td>

                                                <th>{{"Rank In Organization "}}</th>
                                                <td>{{$supplierPersonalInfo->rank_in_organization}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Business Start Date"}}</th>
                                                <td>{{date('d-m-Y',strtotime($supplierPersonalInfo->business_start_date))}}</td>

                                                <th>{{"Organization Name "}}</th>
                                                <td>{{$supplierPersonalInfo->organization_name}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Organization Branch"}}</th>
                                                <td>{{date('d-m-Y',strtotime($supplierPersonalInfo->organization_branch))}}</td>

                                                <th>{{"Membership Number "}}</th>
                                                <td>{{$supplierPersonalInfo->membership_number}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Membership Contact Date"}}</th>
                                                <td>{{date('d-m-Y',strtotime($supplierPersonalInfo->membrsip_cont_date))}}</td>
                                                <th>{{"Date Of Registry "}}</th>
                                                <td>{{date('d-m-Y',strtotime($supplierPersonalInfo->date_of_registry))}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Join Date of Present Occupation"}}</th>
                                                <td>{{date('d-m-Y',strtotime($supplierPersonalInfo->jn_date_of_prsnt_ocuptn))}}</td>
                                                <th>{{"Description Of Present Occupation "}}</th>
                                                <td>{{$supplierPersonalInfo->des_of_pre_occu}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Offence"}}</th>
                                                <td>{{$supplierPersonalInfo->offence}}</td>
                                                <th>{{"Offence Date "}}</th>
                                                <td>{{date('d-m-Y',strtotime($supplierPersonalInfo->offence_date))}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Offence Place"}}</th>
                                                <td>{{$supplierPersonalInfo->offence_place}}</td>
                                                <th>{{"Description of Offence "}}</th>
                                                <td>{{$supplierPersonalInfo->des_of_offence}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Spouse Full Name"}}</th>
                                                <td>{{$supplierPersonalInfo->spouse_full_name}}</td>
                                                <th>{{"Spouse NID "}}</th>
                                                <td>{{$supplierPersonalInfo->spouse_nid}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Spouse Father's Name"}}</th>
                                                <td>{{$supplierPersonalInfo->spouse_father_name}}</td>
                                                <th>{{"Spouse Father's NID "}}</th>
                                                <td>{{$supplierPersonalInfo->spouse_father_nid}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Spouse Mother's Name"}}</th>
                                                <td>{{$supplierPersonalInfo->spouse_mother_name}}</td>
                                                <th>{{"Spouse Mother's NID "}}</th>
                                                <td>{{$supplierPersonalInfo->spouse_mother_nid}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Spouse Permanent Address"}}</th>
                                                <td>{{$supplierPersonalInfo->spouse_per_address}}</td>
                                                <th>{{"Spouse Present Address "}}</th>
                                                <td>{{$supplierPersonalInfo->spouse_pre_address}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Spouse Birth Place"}}</th>
                                                <td>{{$supplierPersonalInfo->spouse_birth_place}}</td>
                                                <th>{{"Spouse Birth Date "}}</th>
                                                <td>{{date('d-m-Y',strtotime($supplierPersonalInfo->spouse_birth_date))}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Spouse Nationality"}}</th>
                                                <td>{{$supplierPersonalInfo->spouse_nationality}}</td>
                                                <th>{{"Spouse Occupation "}}</th>
                                                <td>{{$supplierPersonalInfo->spouse_occupation}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{"Applicant Signature"}}</th>
                                                <td>
                                                    <img width="100" height="80" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$supplierPersonalInfo->applicant_signature}}">
                                                </td>
                                                <th>{{"Applicant Seal"}}</th>
                                                <td>
                                                    <img width="100" height="80" src="{{URL::to('/')}}/public/uploads/supplier_profile/{{$supplierPersonalInfo->applicant_seal}}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{"Signature Place"}}</th>
                                                <td>{{$supplierPersonalInfo->signature_place}}</td>
                                                <th>{{"Signature Date "}}</th>
                                                <td>{{date('d-m-Y',strtotime($supplierPersonalInfo->signature_date))}}</td>
                                            </tr>


                                            </tbody>
                                        </table><!---/table-responsive-->
                                    @endif
                                </div>

                                {{--supplerTenderInfo tab=================================================================--}}
                                <div role="tabpanel" class="tab-pane" id="supplerTenderInfo">
                                    <h5>Tenders win by {!! $suppliers->company_name !!}</h5>
                                    <table class="table table-bordered table-hover table-striped middle-align">

                                        <thead>
                                        <th>{!! 'Tender Name' !!}</th>
                                        <th>{!! 'PO Number' !!}</th>
                                        <th>{!! 'Tender Number' !!}</th>
                                        <th>{!! 'Opening Date' !!}</th>
                                        <th>{!! 'Dade Line' !!}</th>
                                        <th>{!! 'NSD/BSD Name' !!}</th>
                                        </thead>

                                        <tbody>
                                        @if (!$winning_tenders->isEmpty())
                                            @foreach($winning_tenders as $wt)
                                                <tr>
                                                    <td>{!! $wt->tender_title !!}</td>
                                                    <td>{!! $wt->po_number !!}</td>
                                                    <td>{!! $wt->tender_number !!}</td>
                                                    <td>{!! date('Y-m-d', strtotime($wt->tender_opening_date)) !!}</td>
                                                    <td>{!! date('Y-m-d', strtotime($wt->date_line)) !!}</td>
                                                    <td>{!! $wt->nsdName->name !!}</td>
                                                </tr>

                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7">{{'Item not found'}}</td>
                                            </tr>
                                        @endif
                                        </tbody>

                                    </table>
                                    {!! $winning_tenders->appends(\Input::except('page'))->render() !!}

                                    <h5>Items Provided by {!! $suppliers->company_name !!}</h5>
                                    <table class="table table-bordered table-hover table-striped middle-align">

                                        <thead>
                                        <th>{!! 'Item Name' !!}</th>
                                        <th>{!! 'Purchase Date' !!}</th>
                                        <th>{!! 'Delivery Date' !!}</th>
                                        <th>{!! 'Quantity' !!}</th>
                                        <th>{!! 'Unit Price' !!}</th>
                                        <th>{!! 'Discount' !!}</th>
                                        <th>{!! 'Total' !!}</th>
                                        </thead>

                                        <tbody>
                                        @if (!$supplier_to_items->isEmpty())
                                            @foreach($supplier_to_items as $sti)
                                                <tr>
                                                    <td>{!! $sti->item_name !!}</td>
                                                    <td>{!! date('Y-m-d', strtotime($sti->purchase_date)) !!}</td>
                                                    <td>{!! date('Y-m-d', strtotime($sti->delivery_date)) !!}</td>
                                                    <td>{!! number_format($sti->quantity, 2).' '.$sti->itme_deno !!}</td>
                                                    <td>{!! number_format($sti->unit_price, 2) !!}</td>
                                                    <td>{!! number_format($sti->discount_price, 2) !!}</td>
                                                    <td>{!! number_format($sti->total, 2) !!}</td>
                                                </tr>

                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7">{{'Item not found'}}</td>
                                            </tr>
                                        @endif
                                        </tbody>

                                    </table>
                                    {!! $supplier_to_items->appends(\Input::except('page'))->render() !!}
                                </div>

                            </div>


                        </div>

                    </div>
                </div>
            </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function(){

            /*For Delete Department*/
            $(".exbtovdelete").click(function (e) {
                e.preventDefault();
                
                var id = this.id; 
                var url='{!! URL::to('suppliers/suppliers/destroy') !!}'+'/'+id;
                bootbox.confirm("Are you sure?", function (result) {
                    if (result) {
                        var _url = $("#_url").val();
                        window.location.href = url;
                    }
                });
            });

        });
    </script>
@stop