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
@if (!empty($demandsInfo))
                                        <tr>
                                            <td width="25%"><b>{!! 'Demanding' !!}</b></td>
                                            <td width="25%">
                                                @if(!empty($demandsInfo))
                                                    @foreach($demandsInfo->unique('demandingName') as $dif)
                                                        @if(!empty($dif->demandingName))
                                                            {!! $dif->demandingName.' ;' !!}
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td width="25%"><b>{!! 'Recurring' !!}</b></td>
                                            <td width="25%">
                                                @if(!empty($demandsInfo))
                                                    @foreach($demandsInfo->unique('recurring_casual_or_not') as $dif)
                                                        @if($dif->recurring_casual_or_not == 1){!! 'Casual'.'; ' !!} @else {!! 'Formal'.'; ' !!} @endif
                                                    @endforeach
                                                @endif
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Demand No' !!}</b></td>
                                            <td>
                                                @if(!empty($demandsInfo))
                                                    @foreach($demandsInfo->unique('demand_no') as $dif)
                                                        @if(!empty($dif->demand_no))
                                                            {!! $dif->demand_no.' ;' !!}
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td><b>{!! 'Priority' !!}</b></td>
                                            <td>
                                                @if(!empty($demandsInfo))
                                                    @foreach($demandsInfo->unique('priority') as $dif)
                                                        @if(!empty($dif->priority))
                                                            {!! $dif->priority.' ;' !!}
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Item Type' !!}</b></td>
                                            <td>
                                                @if(!empty($demandsInfo))
                                                    @foreach($demandsInfo->unique('permanent_or_waste_content') as $dif)
                                                        @if($dif->permanent_or_waste_content == 1){!! 'Permanent Content'.'; ' !!} @elseif($dif->permanent_or_waste_content == 2) {!! 'Waste Content' !!} @else {!! 'Quasi Permanent Content'.'; ' !!} @endif
                                                    @endforeach
                                                @endif
                                                
                                            </td>
                                            <td><b>{!! 'When Needed' !!}</b></td>
                                            <td>
                                                @if(!empty($demandsInfo))
                                                    @foreach($demandsInfo->unique('when_needed') as $dif)
                                                        @if(!empty($dif->when_needed)){!! date('Y-m-d',strtotime($dif->when_needed)).'; ' !!} @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Posted Date' !!}</b></td>
                                            <td>
                                                @if(!empty($demandsInfo))
                                                    @foreach($demandsInfo->unique('posted_date') as $dif)
                                                        @if(!empty($dif->posted_date)){!! date('Y-m-d',strtotime($dif->posted_date)).'; ' !!} @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td><b>{!! 'Provided Date' !!}</b></td>
                                            <td>
                                                @if(!empty($demandsInfo))
                                                    @foreach($demandsInfo->unique('provided_date') as $dif)
                                                        @if(!empty($dif->provided_date)){!! date('Y-m-d',strtotime($dif->provided_date)).'; ' !!} @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Delivery Place' !!}</b></td>
                                            <td> </td>
                                            <td><b>{!! 'For Whom' !!}</b></td>
                                            <td>
                                                @if(!empty($demandsInfo))
                                                    @foreach($demandsInfo->unique('for_whom') as $dif)
                                                        @if(!empty($dif->for_whom)){!! $dif->for_whom.'; ' !!} @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Pattern Stock No' !!}</b></td>
                                            <td>
                                                @if(!empty($demandsInfo))
                                                    @foreach($demandsInfo->unique('pattern_or_stock_no') as $dif)
                                                        @if(!empty($dif->pattern_or_stock_no)){!! $dif->pattern_or_stock_no.'; ' !!} @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td><b>{!! 'Products Details' !!}</b></td>
                                            <td>
                                                <?php 
                                                    $remComma = 1;
                                                    $num_of_items = count($itemtodemand);
                                                    $totalUnit = 0;
                                                    $totalprice = 0;        
                                                ?>
                                                @if(!empty($itemtodemand) && count($itemtodemand) > 0)
                                                    @foreach($itemtodemand as $ke => $itmsf)
                                                        <?php $remComma++; $totalUnit+=$itmsf->unit; $totalprice+=($itmsf->unit*$itmsf->unit_price); ?>
                                                    @endforeach

                                                    @foreach($itemtodemand->unique('item_name') as $ke => $itmsf)
                                                        {!! $itmsf->item_name !!}
                                                        @if($num_of_items > $remComma)
                                                            {!! '; ' !!}
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{!! 'Total Quantity' !!}</b></td>
                                            <td>{!! $totalUnit !!}</td>
                                            <td><b>{!! 'Total Amount' !!}</b></td>
                                            <td>{!! $totalprice !!}</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table><!---/table-responsive-->
                            <br>

                            @if(!$itemtodemand->isEmpty())
                                <b>Items</b>
                                <table class="table table-bordered table-hover table-striped middle-align">
                                    <thead>
                                        <th>Item Name</th>
                                        <th>Model / Type / Mark</th>
                                        <th>Serial / Registration</th>
                                        <th>Group</th>
                                        <th>In Stock</th>
                                        <th>Not In Stock</th>
                                        <th>Quantity</th>
                                        <th>Anticipated Qty</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody>

                                        @foreach($itemtodemand as $itdm)

                                            <tr>
                                                <td>{!! $itdm->item_name !!}</td>
                                                <td>{!! $itdm->item_model !!}</td>
                                                <td>{!! $itdm->serial_imc_no !!}</td>
                                                <td>{!! $itdm->categoryname !!}</td>
                                                <td>{!! $itdm->in_stock !!}</td>
                                                <td>{!! $itdm->not_in_stock !!}</td>
                                                <td>{!! $itdm->total_unit !!}</td>
                                                <td>{!! $itdm->anticipited_qty !!}</td>
                                                
                                                <td>
                                                    <?php if($itdm->current_status == 101){ ?>
                                                        <span class=" btn-success btn-xs">Product in stock</span>
                                                    <?php } elseif($itdm->current_status == 1){ ?>
                                                        <span class="btn-primary btn-xs">Waiting for demand approve</span>
                                                    <?php } elseif($itdm->current_status == 2 && $itdm->demand_appv_status == 2){ ?>
                                                        <span class="btn-warning btn-xs">Demand rejected by approval team</span>

                                                    <!-- For new group status===================================================================     -->
                                                    <?php } elseif($itdm->current_status == 2 && $itdm->demand_appv_status == 1 && empty($itdm->first_group_status) ) { ?>
                                                        <span class="btn-primary btn-xs">Waiting for group in charge check</span>
                                                    <?php } elseif($itdm->current_status == 3 && $itdm->first_group_status == 1 ) { ?>
                                                        <span class=" btn-success btn-xs">Product in stock</span>
                                                    <?php } elseif($itdm->current_status == 3 && $itdm->first_group_status == 2  && empty($itdm->group_status) && empty($itdm->transfer_to) && empty($itdm->transfer_status) && $itdm->plr_status==2) { ?>
                                                        <span class=" btn-warning btn-xs">Canceled By {!! 'Group in charge' !!}</span>
                                                    <?php } elseif($itdm->current_status == 3 && $itdm->first_group_status == 2  && empty($itdm->group_status) && empty($itdm->transfer_to) && empty($itdm->transfer_status) && $itdm->plr_status==1) { ?>
                                                        <span class=" btn-warning btn-xs">Hold By {!! 'Group in charge' !!}</span>
                                                    <?php } elseif($itdm->current_status == 3 && $itdm->first_group_status == 2  && empty($itdm->group_status) && !empty($itdm->transfer_to) && $itdm->transfer_status==2 && $itdm->plr_status==2) { ?>
                                                        <span class=" btn-warning btn-xs">Canceled By {!! $itdm->organizationName !!}</span>
                                                    <?php } elseif($itdm->current_status == 3 && $itdm->first_group_status == 2  && empty($itdm->group_status) && !empty($itdm->transfer_to) && empty($itdm->transfer_status)) { ?>
                                                        <span class=" btn-warning btn-xs">Waiting for {!! $itdm->organizationName."'s response" !!}</span>
                                                    <?php } elseif($itdm->current_status == 3 && $itdm->first_group_status == 2  && empty($itdm->tender_floating) && !empty($itdm->transfer_to) && $itdm->transfer_status==2 && $itdm->plr_status==1) { ?>
                                                        <span class=" btn-warning btn-xs">Hold By {!! $itdm->organizationName !!}</span>

                                                    <!-- End for new group status=======
                                                    ====================================     -->    
                                                    <?php } elseif($itdm->current_status == 3 && $itdm->demand_appv_status == 1 && $itdm->plr_status==3 && !empty($itdm->first_group_status) && empty($itdm->group_status) ) { ?>
                                                        <span class="btn-primary btn-xs">Waiting for group OIC check</span>
                                                    <?php } elseif( $itdm->plr_status==2 && empty($itdm->tender_floating) && !empty($itdm->first_group_status) && $itdm->current_status == 3 && !empty($itdm->group_status)) { ?>
                                                        <span class=" btn-warning btn-xs">Canceled By Group OIC</span>
                                                    <!-- Starat floting tender status =======     -->
                                                    <?php } elseif($itdm->current_status == 3 && $itdm->demand_appv_status == 1 && $itdm->group_status==2 && empty($itdm->float_tender_app_status) && !empty($itdm->group_status) ) { ?>
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

                            
                                
                            <div class="row">
                                <div class="col-md-12">
                                    {{ Form::open(array('role' => 'form', 'url' => 'post-only-group-status-change', 'files'=> true, 'class' => '', 'id'=>'demands')) }}

                                            <input type="hidden" name="sigment" value="1">
                                            
                                            <input type="hidden" name="demand_to_lpr_id" value="{!! $demandtolpr->id !!}">
                                            <input type="hidden" name="demand_id" value="{!! $demandtolpr->demand_ids !!}">
                                        

                                            <div class="col-md-3 " id="transfer_to_div">
                                                <div class="form-group">
                                                    <label for="transfer_to">Status :</label>
                                                    {{ Form::select('group_status', array('3' => 'Approve', '2' => 'Reject'), '' , array('class' => 'form-control selectpicker', 'id' => 'group_status')) }}
                                                </div>
                                            </div>

                                            

                                        <div class="col-md-1" style="padding-top: 23px;">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">{!!trans('english.SAVE')!!}</button>
                                            </div>
                                        </div>

                                    {!!   Form::close() !!}

                                </div> {{-- end group approve form --}}
                            </div>

                            <a href="{{URL::previous()}}" class="btn btn-primary cancel pull-right" >
                                    <i class="icon-long-arrow-left" style="font-weight: bolder;"></i> {!! 'Back' !!}
                                </a>
                                
                        </div>
                    </div>
                </div>
            </div>

    </div>

@stop