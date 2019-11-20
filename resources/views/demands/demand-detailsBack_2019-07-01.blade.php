<?php
    use App\Http\Controllers\DemandController;
?>
@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Demand Detail</h3>
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
                        <h3>Demand Detail</h3>
                    </div>
                        <div class="panel-body">

                            <table class="table table-bordered table-hover table-striped middle-align">

                                <tbody>

                                    @if (!empty($demand))
                                        <tr>
                                            <td><b>{!! 'Demanding' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->requester))
                                                    {!! $demand->demandeNameInDemand->name !!}
                                                @endif
                                            </td>
                                            <td><b>{!! 'Recurring' !!}</b></td>
                                            <td>@if($demand->requester == 1){!! 'Casual' !!} @else {!! 'Formal' !!} @endif</td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Demand No' !!}</b></td>
                                            <td>{!! $demand->demand_no !!}</td>
                                            <td><b>{!! 'Priority' !!}</b></td>
                                            <td>{!! $demand->priority !!}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Item Type' !!}</b></td>
                                            <td>@if($demand->permanent_or_waste_content == 1){!! 'Permanent Content' !!} @elseif($demand->permanent_or_waste_content == 2) {!! 'Waste Content' !!} @else {!! 'Quasi Permanent Content' !!} @endif</td>
                                            <td><b>{!! 'When Needed' !!}</b></td>
                                            <td>@if(!empty($demand->when_needed)){!! date('Y-m-d',strtotime($demand->when_needed)) !!} @endif</td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Posted Date' !!}</b></td>
                                            <td>@if(!empty($demand->posted_date)){!! date('Y-m-d',strtotime($demand->posted_date)) !!} @endif</td>
                                            <td><b>{!! 'Provided Date' !!}</b></td>
                                            <td>@if(!empty($demand->provided_date)){!! date('Y-m-d',strtotime($demand->provided_date)) !!} @endif</td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Delivery Place' !!}</b></td>
                                            <td> @if(!empty($demand->place_to_send)) {!! $demand->navalocation_name->name !!} @endif</td>
                                            {{--<td>{!! $demand->place_to_send !!}</td>--}}
                                            <td><b>{!! 'For Whom' !!}</b></td>
                                            <td>{!! $demand->for_whom !!}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Pattern Stock No' !!}</b></td>
                                            <td>{!! $demand->pattern_or_stock_no !!}</td>
                                            <td><b>{!! 'Products Details' !!}</b></td>
                                            <td>{!! $demand->product_detailsetc !!}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Total Unit' !!}</b></td>
                                            <td>{!! $demand->total_unit !!}</td>
                                            <td><b>{!! 'Total Amount' !!}</b></td>
                                            <td>{!! $demand->total_value !!}</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table><!---/table-responsive-->
                            <br>
                            
                            <b>Items</b>
                            @if(!$itemtodemand->isEmpty())
                                <table class="table table-bordered table-hover table-striped middle-align">
                                    <thead>
                                        <th>Item Name</th>
                                        <th>Model / Type / Mark</th>
                                        <th>Serial / Registration</th>
                                        <th>Group</th>
                                        <th>Unit</th>
                                        <th>Currency Rates</th>
                                        <th>Unit Price</th>
                                        <th>Subtotal</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody>

                                        @foreach($itemtodemand as $itdm)

                                            <tr>
                                                <td>{!! $itdm->item_name !!}</td>
                                                <td>{!! $itdm->item_model !!}</td>
                                                <td>{!! $itdm->serial_imc_no !!}</td>
                                                <td>{!! $itdm->categoryname !!}</td>
                                                <td>{!! $itdm->total_unit !!}</td>
                                                <td>{!! $itdm->currency_rate !!}</td>
                                                <td>{!! $itdm->unit_price !!}</td>
                                                <td>{!! $itdm->sub_total !!}</td>
                                                <td>
                                                    <?php if($itdm->current_status == 101){ ?>
                                                        <span class=" btn-success btn-xs">Product in stock</span>
                                                    <?php } elseif($itdm->current_status == 1){ ?>
                                                        <span class="btn-primary btn-xs">Waiting for demand approve</span>
                                                    <?php } elseif($itdm->current_status == 2 && $itdm->demand_appv_status == 2){ ?>
                                                        <span class="btn-warning btn-xs">Demand rejected by approval team</span>
                                                    <?php } elseif($itdm->current_status == 2 && $itdm->demand_appv_status == 1 && empty($itdm->group_status) ) { ?>
                                                        <span class="btn-primary btn-xs">Waiting for group check</span>
                                                    <?php } elseif($itdm->current_status == 3 && $itdm->group_status == 1 ) { ?>
                                                        <span class=" btn-success btn-xs">Product in stock</span>
                                                    <!-- Newly added by lemon=================     -->
                                                        

                                                    <?php } elseif($itdm->current_status == 3 && $itdm->group_status == 2  && empty($itdm->tender_floating) && empty($itdm->transfer_to) && empty($itdm->transfer_status) && $itdm->plr_status==2) { ?>
                                                        <span class=" btn-warning btn-xs">Canceled By {!! 'Group in charge' !!}</span>
                                                    <?php } elseif($itdm->current_status == 3 && $itdm->group_status == 2  && empty($itdm->tender_floating) && empty($itdm->transfer_to) && empty($itdm->transfer_status) && $itdm->plr_status==1) { ?>
                                                        <span class=" btn-warning btn-xs">Hold By {!! 'Group in charge' !!}</span>

                                                    <!-- New newly added ========================= -->
                                                    <?php } elseif($itdm->current_status == 3 && $itdm->group_status == 2  && empty($itdm->tender_floating) && !empty($itdm->transfer_to) && $itdm->transfer_status==2 && $itdm->plr_status==2) { ?>
                                                        <span class=" btn-warning btn-xs">Canceled By {!! $itdm->organizationName !!}</span>
                                                    <?php } elseif($itdm->current_status == 3 && $itdm->group_status == 2  && empty($itdm->tender_floating) && !empty($itdm->transfer_to) && empty($itdm->transfer_status)) { ?>
                                                        <span class=" btn-warning btn-xs">Waiting for {!! $itdm->organizationName."'s response" !!}</span>
                                                    <?php } elseif($itdm->current_status == 3 && $itdm->group_status == 2  && empty($itdm->tender_floating) && !empty($itdm->transfer_to) && $itdm->transfer_status==2 && $itdm->plr_status==1) { ?>
                                                        <span class=" btn-warning btn-xs">Hold By {!! $itdm->organizationName !!}</span>
                                                    <?php } elseif($itdm->current_status == 3 && $itdm->demand_appv_status == 1  && empty($itdm->float_tender_app_status) ) { ?>
                                                        <span class=" btn-warning btn-xs"> {!! "Waiting for floating" !!}</span>
                                                    <?php } elseif( $itdm->group_status == 2 && !empty($itdm->tender_floating) && ($itdm->current_status == 4 || ($itdm->current_status == 5) && $itdm->float_tender_app_status==2) && empty($itdm->coll_quat_app_status) ){ ?>
                                                    @if(empty($itdm->float_tender_app_status))
                                                        <span class=" btn-warning btn-xs"> {!! "Waiting for floating authorised approval" !!}</span>
                                                    @endif
                                                    @if($itdm->float_tender_app_status==2)
                                                        <span class=" btn-warning btn-xs"> {!! "Rejected floating tender by authorised" !!}</span>
                                                    @endif
                                                    <?php } elseif($itdm->current_status == 5 && !empty($itdm->float_tender_app_status) && empty($itdm->coll_quat_app_status) ){ ?>
                                                        <span class=" btn-warning btn-xs"> {!! "Waiting for collection quotation" !!}</span>
                                                    <?php } elseif( !empty($itdm->tender_quation_collection) && ($itdm->current_status == 6 || ($itdm->current_status == 7 && $itdm->coll_quat_app_status==2)) && empty($itdm->cst_draft_status)){ ?>
                                                    @if(empty($itdm->coll_quat_app_status))
                                                        <span class=" btn-warning btn-xs"> {!! "Collection Quat. authorised approval" !!}</span>
                                                    @endif
                                                    @if($itdm->coll_quat_app_status==2)
                                                        <span class=" btn-warning btn-xs"> {!! "Rejected Collection Quat. by authorised" !!}</span>
                                                    @endif
                                                    <?php } elseif(!empty($itdm->coll_quat_app_status) && empty($itdm->cst_draft_status) && $itdm->current_status == 7){ ?>
                                                        <span class=" btn-warning btn-xs"> {!! "Waiting for generate CST" !!}</span>
                                                    <?php } elseif(!empty($itdm->cst_draft_status) && empty($itdm->cst_supplier_select) && $itdm->current_status == 8){ ?>
                                                        <span class=" btn-warning btn-xs"> {!! "Waiting for select winner" !!}</span>
                                                    <?php } elseif(!empty($itdm->cst_supplier_select) && empty($itdm->lp_section_status) && $itdm->current_status == 9){ ?>
                                                        <span class=" btn-warning btn-xs"> {!! "In LP section" !!}</span>
                                                    <?php } elseif(!empty($itdm->lp_section_status) && $itdm->lp_section_status ==2 && $itdm->head_ofc_apvl_status == 2 && $itdm->current_status == 9){ ?>
                                                        <span class=" btn-warning btn-xs"> {!! "Waiting for HQ Approval" !!}</span>
                                                    <?php } elseif(!empty($itdm->lp_section_status) && $itdm->lp_section_status ==2 && $itdm->head_ofc_apvl_status == 3 && $itdm->current_status == 10){ ?>
                                                        <span class=" btn-warning btn-xs"> {!! "Reject By Headquarter" !!}</span>
                                                    <?php } elseif(!empty($itdm->cst_supplier_select) && $itdm->lp_section_status ==1 && empty($itdm->po_status) && ($itdm->head_ofc_apvl_status==1 || $itdm->head_ofc_apvl_status=='') && ($itdm->current_status == 9 || $itdm->current_status == 10) ){ ?>
                                                        <span class=" btn-warning btn-xs"> {!! "Generate PO" !!}</span>
                                                    <?php } elseif(!empty($itdm->po_status) && empty($itdm->cr_status) && $itdm->current_status == 11 ){ ?>
                                                        <span class=" btn-warning btn-xs"> {!! "In CR section" !!}</span>
                                                    <?php } elseif(!empty($itdm->cr_status) && empty($itdm->inspection_status)  ){ ?>
                                                        <span class=" btn-warning btn-xs"> {!! "Waiting for inspection" !!}</span>
                                                    <?php } elseif(!empty($itdm->inspection_status) && empty($itdm->final_status)  ){ ?>
                                                        <span class=" btn-warning btn-xs"> {!! "D44B voucher" !!}</span>
                                                    <?php } ?>

                                                </td>
                                            </tr>

                                        @endforeach

                                    </tbody>
                                </table><!---/table-responsive-->
                            @endif

                            <?php if(!empty(Session::get('acl')[34][3]) && empty($demand->group_status) && $routenNameComeOfThePge=='demand'){ ?>

                                <a class="btn btn-primary btn-xs" href="{{ URL::to('demand-group/' . $demand->id) }}" title="Edit Demand" >
                                    <i class="icon-edit"> Edit Demand</i>
                                </a>

                            <?php } ?>    

                            <?php if((!empty(Session::get('acl')[34][16]) || !empty(Session::get('acl')[34][25])) && ($routenNameComeOfThePge=='group-check-acc' || $routenNameComeOfThePge=='v44-voucher-view-acc') ){ ?>
                                
                                <div class="row">

                                    {{ Form::open(array('role' => 'form', 'url' => 'post-only-group-status-change', 'files'=> true, 'class' => '', 'id'=>'demands')) }}


                                        <!-- Start of group item stock and not in stock change  
                                        =======================================================================
                                        =======================================================================
                                        =======================================================================
                                        -->  
                                            <?php if((!empty(Session::get('acl')[34][16]) || !empty(Session::get('acl')[34][25])) && ($routenNameComeOfThePge=='group-check-acc') ){ ?>

                                                @if(!$itemtodemandappv->isEmpty())
                                                    <br>
                                                    <b>Items</b>
                                                    <table class="table table-bordered table-hover table-striped middle-align">
                                                        <thead>
                                                            <th>Item Name</th>
                                                            <th>Model / Type / Mark</th>
                                                            <th>Group</th>
                                                            <th>Unit</th>
                                                            <th>In Stock</th>
                                                            <th>Not In Stock</th>
                                                        </thead>
                                                        <tbody>

                                                            @foreach($itemtodemandappv as $itdmap)
                                                                <input type="hidden" name="item_to_demand[]" value="{!! $itdmap->id !!}">
                                                                <tr>
                                                                    <td>{!! $itdmap->item_name !!}</td>
                                                                    <td>{!! $itdmap->item_model !!}</td>
                                                                    <td>{!! $itdmap->categoryname !!}</td>
                                                                    <td>
                                                                        {!! $itdmap->total_unit !!}
                                                                        <input type="hidden" class="form-control unit" id="" name="unit[]" value="{!! $itdmap->total_unit !!}" placeholder="">
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group" style="margin-bottom: 0px !important;">
                                                                            <input type="number" class="form-control in_stock" id="" name="in_stock[]" value="{!! ($itdmap->in_stock===NULL) ? 0 : $itdmap->in_stock !!}" placeholder="" required="" @if($itdmap->in_stock!=NULL) readonly @endif min="0">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group" style="margin-bottom: 0px !important;">
                                                                            <input type="number" class="form-control not_in_stock" id="" name="not_in_stock[]" value="{!! empty($itdmap->not_in_stock) ? $itdmap->total_unit : $itdmap->not_in_stock !!}" placeholder="" readonly="" required="" min="0">
                                                                        </div>
                                                                    </td>
                                                                </tr>

                                                            @endforeach

                                                        </tbody>
                                                    </table><!---/table-responsive-->
                                                @endif
                                            
                                            <?php } ?>
                                        <!-- End of group item stock and not in stock change  
                                            ==================================================================
                                            ==================================================================
                                            ==================================================================
                                        -->

                                        <input type="hidden" name="demand_id" value="{!! $demand->id !!}">
                                        <div class="col-md-3 hidden">
                                            <div class="form-group">
                                                <label for="sutotal_price">Approve Demand :</label>
                                                {{ Form::select('group_status', array('2' => 'Not In Stock', '1' => 'In Stock'), $demand->group_status , array('class' => 'form-control selectpicker', 'id' => 'group_status')) }}
                                            </div>
                                        </div>

                                        {{-- @if(!empty(Auth::user()->nsd_bsd) && in_array(3,explode(',', Auth::user()->nsd_bsd))) --}}

                                            <div class="col-md-3 " id="transfer_to_div">
                                                <div class="form-group">
                                                    <label for="transfer_to">Communicate To :</label>
                                                    {{ Form::select('transfer_to', $destinationPlaces, $demand->transfer_to, array('class' => 'form-control selectpicker', 'id' => 'transfer_to')) }}
                                                </div>
                                            </div>

                                            <div class="col-md-2  @if(empty($demand->transfer_to)) hidden @endif" id="transfer_status_div">
                                                <div class="form-group">
                                                    <label for="transfer_status">Transfer Status :</label>
                                                    {{ Form::select('transfer_status', array('' => 'Waiting for approve','1' => 'In Stock', '2' => 'Not In Stock'), $demand->transfer_status, array('class' => 'form-control selectpicker', 'id' => 'transfer_status')) }}
                                                </div>
                                            </div>

                                            <!-- <div class="col-md-3 @if($demand->transfer_status !=2 || empty($demand->plr_status)) hidden @endif" id="plr_div"> -->
                                            <div class="col-md-3" id="plr_div">    
                                                <div class="form-group">
                                                    <label for="plr_status">Local Purchase Requisition :</label>
                                                    {{ Form::select('plr_status', array(1 => 'Hold','2' => 'Cancel', '3' => 'Send To LP'), $demand->plr_status, array('class' => 'form-control selectpicker', 'id' => 'plr_status')) }}
                                                </div>
                                            </div>

                                        {{-- @endif --}}

                                        <div class="col-md-1" style="padding-top: 23px;">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">{!!trans('english.SAVE')!!}</button>
                                            </div>
                                        </div>

                                    {!!   Form::close() !!}

                                </div> {{-- end group approve form --}}

                                
                                    @if($demand->inspection_status==1 && count($inspectedItems)>0 )
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('v44voucher-pdf-view/'.$demand->id) }}" title="D44Bvoucher" >
                                                        <i class="icon-eye-open"> D44B voucher</i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                            <?php } ?>
                        
                        <!-- LP section work start -->
                        <div class="row">
                            
                            <div class="col-md-12">
                                <?php if(!empty(Session::get('acl')[34][17]) && $demand->group_status==2 && empty($demand->tender_quation_collection) && $routenNameComeOfThePge=='floating-tender-acc' ){ ?>
                                    {{--<a class="btn btn-primary btn-xs" href="{{ URL::to('floating-tender/create/'.$demand->id) }}" title="Create Tender">
                                        <i class="icon-edit">Float Tender</i>
                                    </a>--}}
                                <?php } ?> 

                                <?php //if(!empty(Session::get('acl')[34][18]) && !empty($demand->tender_floating) && empty($demand->cst_draft_status)){ 
                                    if(!empty(Session::get('acl')[34][18]) && !empty($demand->tender_floating) && $routenNameComeOfThePge=='collection-quotation-acc' ){ ?>
                                    {{--<a class="btn btn-primary btn-xs" href="{{ URL::to('create-collection-quotation/'.$demand->id) }}" title="Create Tender">
                                        <i class="icon-edit"> Add Collection Quotation</i>
                                    </a>--}}
                                <?php } ?> 

                                <?php //if(!empty(Session::get('acl')[34][19]) && !empty($demand->tender_quation_collection) && empty($demand->cst_supplier_select)){ 
                                if(!empty(Session::get('acl')[34][19]) && !empty($demand->tender_quation_collection) && $routenNameComeOfThePge=='cst-view-acc'){ ?>
                                    {{--<a class="btn btn-primary btn-xs" href="{{ URL::to('cst-view/'.$demand->id) }}" title="CST view">
                                        <i class="icon-eye-open"> CST view</i>
                                    </a>--}}
                                <?php } ?> 

                                <?php //if(!empty(Session::get('acl')[34][20]) && !empty($demand->cst_draft_status) && empty($demand->lp_section_status)){ 
                                if(!empty(Session::get('acl')[34][20]) && !empty($demand->cst_draft_status)  && $routenNameComeOfThePge=='draft-cst-view-acc'){ ?>
                                    {{--<a class="btn btn-primary btn-xs" href="{{ URL::to('draft-cst-view/'.$demand->id) }}" title="CST view">
                                        <i class="icon-eye-open"> CST draft</i>
                                    </a>--}}
                                <?php } ?>

                                <?php if(!empty(Session::get('acl')[34][21]) && !empty($demand->lp_section_status) && $demand->lp_section_status ==2 && ($demand->head_ofc_apvl_status == 2 || $demand->head_ofc_apvl_status == 3)  && $routenNameComeOfThePge=='hdq-approval-acc' ){ ?>
                                    {{--<a class="btn btn-primary btn-xs" href="{{ URL::to('headquarte-approval/'.$demand->id) }}" title="HQ Approval">
                                        <i class="icon-eye-open"> HQ Approve</i>
                                    </a>--}}
                                <?php } ?>

                                <?php if(!empty(Session::get('acl')[34][22]) && !empty($demand->cst_supplier_select) && $demand->lp_section_status==1 && ($demand->head_ofc_apvl_status==1 || $demand->head_ofc_apvl_status=='') && $routenNameComeOfThePge=='po-generation-acc' ){ ?>
                                    {{--<a class="btn btn-primary btn-xs" href="{{ URL::to('po-generate-view/'.$demand->id) }}" title="Generate PO" >
                                        <i class="icon-eye-open"> Generate PO</i>
                                    </a>--}}
                                <?php } ?>

                                <?php if(!empty(Session::get('acl')[34][23]) && !empty($demand->lp_section_status) && $demand->po_status==1 && $routenNameComeOfThePge=='cr-view-acc' ){ ?>
                                    {{--<a class="btn btn-primary btn-xs" href="{{ URL::to('cr-section/'.$demand->id) }}" title="CR view">
                                        <i class="icon-eye-open"> CR</i>
                                    </a>--}}
                                <?php } ?>

                                <?php if(!empty(Session::get('acl')[34][24]) && !empty($demand->po_status) && $demand->cr_status==1 && $routenNameComeOfThePge=='inspection-view-acc'){ ?>
                                    {{--<a class="btn btn-primary btn-xs" href="{{ URL::to('inspection-section/'.$demand->id) }}" title="Inspection">
                                        <i class="icon-eye-open"> Inspection</i>--}}
                                    </a>
                                <?php } ?>
  

                            </div>
                              
                        </div>
                         
                         <br>
                            <b>Status: </b>
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <tbody>
                                    @if (!empty($demand))

                                        <tr>
                                            <td><b>{!! 'Group Check By' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->group_status))
                                                    {!! DemandController::checkErName($demand->group_status) !!}
                                                @endif
                                            </td>
                                            <td><b>{!! 'Group Check Date' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->group_status_check_date))
                                                    {!! date('d-m-Y H:i:s',strtotime($demand->group_status_check_date)) !!}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Transfer To' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->transfer_to ))
                                                    {!! $demand->transferOrgName->name !!}
                                                @endif
                                            </td>
                                            <td><b>{!! 'Transfer Date' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->transfer_date))
                                                    {!! date('d-m-Y H:i:s',strtotime($demand->transfer_date)) !!}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Tender Float By' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->tender_floating_by ))
                                                    {!! DemandController::checkErName($demand->tender_floating_by) !!}
                                                @endif
                                            </td>
                                            <td><b>{!! 'Tender Float Date' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->tender_floating_date))
                                                    {!! date('d-m-Y H:i:s',strtotime($demand->tender_floating_date)) !!}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Add Collection Quation By' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->tender_quation_collection_by ))
                                                    {!! DemandController::checkErName($demand->tender_quation_collection_by) !!}
                                                @endif
                                            </td>
                                            <td><b>{!! 'Collection Quation Date' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->tender_quation_collection_date))
                                                    {!! date('d-m-Y H:i:s',strtotime($demand->tender_quation_collection_date)) !!}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'CST Draft By' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->cst_draft_status_by ))
                                                    {!! DemandController::checkErName($demand->cst_draft_status_by) !!}
                                                @endif
                                            </td>
                                            <td><b>{!! 'CST Draft Date' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->cst_draft_status_date))
                                                    {!! date('d-m-Y H:i:s',strtotime($demand->cst_draft_status_date)) !!}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Supplier Select By' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->cst_supplier_select_by ))
                                                    {!! DemandController::checkErName($demand->cst_supplier_select_by) !!}
                                                @endif
                                            </td>
                                            <td><b>{!! 'Supplier Select Date' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->cst_supplier_select_date))
                                                    {!! date('d-m-Y H:i:s',strtotime($demand->cst_supplier_select_date)) !!}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Head Office Approval By' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->head_ofc_apvl_by ))
                                                    {!! DemandController::checkErName($demand->head_ofc_apvl_by) !!}
                                                @endif
                                            </td>
                                            <td><b>{!! 'Head Office Approvalt Date' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->head_ofc_apvl_date))
                                                    {!! date('d-m-Y H:i:s',strtotime($demand->head_ofc_apvl_date)) !!}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'PO Generated By' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->po_generate_by ))
                                                    {!! DemandController::checkErName($demand->po_generate_by) !!}
                                                @endif
                                            </td>
                                            <td><b>{!! 'PO Generated Date' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->po_generate_date))
                                                    {!! date('d-m-Y H:i:s',strtotime($demand->po_generate_date)) !!}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'CR Check By' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->cr_check_by ))
                                                    {!! DemandController::checkErName($demand->cr_check_by) !!}
                                                @endif
                                            </td>
                                            <td><b>{!! 'CR Check Date' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->cr_check_date))
                                                    {!! date('d-m-Y H:i:s',strtotime($demand->cr_check_date)) !!}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Inspection By' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->inspection_by ))
                                                    {!! DemandController::checkErName($demand->inspection_by) !!}
                                                @endif
                                            </td>
                                            <td><b>{!! 'Inspection Date' !!}</b></td>
                                            <td>
                                                @if(!empty($demand->inspection_date))
                                                    {!! date('d-m-Y H:i:s',strtotime($demand->inspection_date)) !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table><!---/table-responsive-->
                        
                        <a href="{{URL::previous()}}" class="btn btn-primary cancel pull-right" >
                            <i class="icon-long-arrow-left" style="font-weight: bolder;"></i> {!! 'Back' !!}
                        </a>
                                
                        </div>
                    </div>
                </div>
            </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function(){

            $(document).on('change','#group_status',function(){
                var thisVal = $(this).val();

                if(thisVal==2){
                    $('#transfer_to').val('').selectpicker('refresh');
                    $('#transfer_status').val('').selectpicker('refresh');

                    $("#transfer_to_div").removeClass('hidden');


                }else{
                    $('#transfer_to').val('').selectpicker('refresh');
                    $("#transfer_to_div").addClass('hidden');
                    $('#transfer_status').val('').selectpicker('refresh');
                    $("#transfer_status_div").addClass('hidden');

                }
            });

            $(document).on('change','#transfer_status',function(){
                var thisValC = $(this).val();
                if(thisValC==2){
                    $("#plr_div").removeClass('hidden');
                }else{
                    $("#plr_div").addClass('hidden');
                }
            });

            $(document).on('change','#group_status',function(){
                var thisValCc = $(this).val();
                if(thisValCc==1){
                    $("#plr_div").addClass('hidden');
                }
            });

            $(document).on('change','#transfer_to',function(){
                var thisVal = $(this).val();

                if(thisVal!=''){
                    $('#transfer_status').val('').selectpicker('refresh');

                    $("#transfer_status_div").removeClass('hidden');
                }else{
                    $('#transfer_status').val('').selectpicker('refresh');
                    $("#transfer_status_div").addClass('hidden');
                }

            });

            $(document).on('input','.in_stock',function(){
                var unit    = $(this).closest("tr").find('.unit').val();
                var inStock = $(this).val();
                var notInstock = unit - inStock;
                $(this).closest("tr").find('.not_in_stock').val(notInstock);
            });
            

            // ========================================================================================================
            // ========================================================================================================

            /*For Delete Department*/
            $(".exbtovdelete").click(function (e) {
                e.preventDefault();
                
                var id = this.id; 
                var url='{!! URL::to('/item/destroy') !!}'+'/'+id;
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