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

                            <?php if(( !empty(Session::get('acl')[34][28])) && $routenNameComeOfThePge=='group-check-acc' ){ ?>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                    {{ Form::open(array('role' => 'form', 'url' => 'insert-select-as-lpr', 'files'=> true, 'class' => '', 'id'=>'demands')) }}


                                        <!-- Start of group item stock and not in stock change  
                                        =======================================================================
                                        =======================================================================
                                        =======================================================================
                                        -->  
                                            <input type="hidden" name="sigment" value="3">
                                            <?php if( (!empty(Session::get('acl')[34][16]) || !empty(Session::get('acl')[34][28]) ) && ($routenNameComeOfThePge=='group-check-acc') ){ ?>

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
                                                                            <input type="number" class="form-control in_stock" id="" name="in_stock[]" value="{!! ($itdmap->in_stock===NULL) ? 0 : $itdmap->in_stock !!}" placeholder="" required="" @if( !empty($itdmap->group_status) ) readonly @endif min="0">
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
                                            
                                        <!-- End of group item stock and not in stock change  
                                            ==================================================================
                                            ==================================================================
                                            ==================================================================
                                        -->

                                        <div class="row">
                                            <div class="col-md-12">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="lrp_number">LPR Number<span class="text-danger"></span></label>
                                                        <input type="text" class="form-control" id="lrp_number" name="lrp_number" placeholder="LPR Number" value="">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="lpr_date">LPR Date<span class="text-danger"></span></label>
                                                        <input type="text" class="form-control datapicker2" id="lpr_date" name="lpr_date" placeholder="LPR Date" value="" readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="sample_number">Sample Number<span class="text-danger"></span></label>
                                                        <input type="text" class="form-control" id="sample_number" name="sample_number" placeholder="Sample Number" value="">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="group_remarks">Remarks<span class="text-danger"></span></label>
                                                        <textarea class="form-control" name="group_remarks" rows="1"></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <input type="hidden" name="demand_id" value="{!! htmlspecialchars(json_encode($demandIds)) !!}">
                                        <div class="col-md-3 hidden">
                                            <div class="form-group">
                                                <label for="sutotal_price">Approve Demand :</label>
                                                {{ Form::select('group_status', array('2' => 'Not In Stock', '1' => 'In Stock'), '' , array('class' => 'form-control selectpicker', 'id' => 'group_status')) }}
                                            </div>
                                        </div>

                                        <div class="col-md-3 " id="transfer_to_div">
                                            <div class="form-group">
                                                <label for="transfer_to">Communicate To :</label>
                                                {{ Form::select('transfer_to', $destinationPlaces, '', array('class' => 'form-control selectpicker', 'id' => 'transfer_to')) }}
                                            </div>
                                        </div>

                                            <div class="col-md-2 hidden" id="transfer_status_div">
                                                <div class="form-group">
                                                    <label for="transfer_status">Transfer Status :</label>
                                                    {{ Form::select('transfer_status', array('' => 'Waiting for approve','1' => 'In Stock', '2' => 'Not In Stock'), '', array('class' => 'form-control selectpicker', 'id' => 'transfer_status')) }}
                                                </div>
                                            </div>

                                            <div class="col-md-3" id="plr_div">    
                                                <div class="form-group">
                                                    <label for="plr_status">Local Purchase Requisition :</label>
                                                    {{ Form::select('plr_status', array(1 => 'Hold','2' => 'Cancel', '3' => 'Send To LP'), '', array('class' => 'form-control selectpicker', 'id' => 'plr_status')) }}
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
                                <?php } ?>
                                
                                    

                            <?php } ?>
                        
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