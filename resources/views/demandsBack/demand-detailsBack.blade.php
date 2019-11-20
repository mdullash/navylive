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
                                            <td>{!! $demand->requester !!}</td>
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
                                            <td>@if($demand->permanent_or_waste_content == 1){!! 'Permanent Content' !!} @else {!! 'Waste Content' !!} @endif</td>
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
                                            <td><b>{!! 'Destination Place' !!}</b></td>
                                            <td>{!! $demand->navalocation_name->name !!}</td>
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
                                        {{--<tr>--}}
                                            {{--<td><b>{!! 'Necessary Amount' !!}</b></td>--}}
                                            {{--<td>{!! $demand->necessary_amount !!}</td>--}}
                                            {{--<td><b>{!! 'Allowed' !!}</b></td>--}}
                                            {{--<td>{!! $demand->allowed !!}</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<td><b>{!! 'Rest Amount' !!}</b></td>--}}
                                            {{--<td>{!! $demand->rest_amount !!}</td>--}}
                                            {{--<td><b>{!! 'Given Quantity' !!}</b></td>--}}
                                            {{--<td>{!! $demand->given_quantity !!}</td>--}}
                                        {{--</tr>--}}
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
                            
                            <b>Status: </b>
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <tbody>
                                    @if (!empty($demand))
                                        <tr>
                                            <td><b>{!! 'Present Status: ' !!}</b></td>
                                            <td colspan="3">
                                                @if(empty($demand->group_status))

                                                    <button class="btn btn-primary btn-xs">Waiting for checking</button>

                                                    @elseif($demand->group_status == 1)
                                                    <button class="btn btn-success btn-xs">Done</button>

                                                    @elseif($demand->group_status == 2 && empty($demand->tender_floating))
                                                        @if(empty($demand->transfer_status))
                                                            <button class="btn btn-warning btn-xs">Waiting for {!! $demand->transferOrgName->name."'s response" !!}</button>

                                                            @else($demand->group_status == 2 && empty($demand->tender_floating))
                                                            <button class="btn btn-warning btn-xs"> {!! "Waiting for floating" !!}</button>
                                                        @endif

                                                    @elseif(!empty($demand->tender_floating) && empty($demand->tender_quation_collection))
                                                        <button class="btn btn-warning btn-xs"> {!! "Waiting for collection quotation" !!}</button>

                                                    @elseif(!empty($demand->tender_quation_collection) && empty($demand->cst_draft_status))
                                                        <button class="btn btn-warning btn-xs"> {!! "Waiting for draft CST" !!}</button>

                                                    @elseif(!empty($demand->cst_draft_status) && empty($demand->cst_supplier_select))
                                                        <button class="btn btn-warning btn-xs"> {!! "Waiting for select winner" !!}</button>
                                                        
                                                    @elseif(!empty($demand->cst_supplier_select) && empty($demand->lp_section_status))
                                                        <button class="btn btn-warning btn-xs"> {!! "In LP section" !!}</button>          
                                                        
                                                @endif
                                            </td>
                                        </tr>
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
                                    </thead>
                                    <tbody>

                                        @foreach($itemtodemand as $itdm)

                                            <tr>
                                                <td>{!! $itdm->item_name !!}</td>
                                                <td>{!! $itdm->item_model !!}</td>
                                                <td>{!! $itdm->serial_imc_no !!}</td>
                                                <td>{!! $itdm->categoryname !!}</td>
                                                <td>{!! $itdm->unit !!}</td>
                                                <td>{!! $itdm->currency_rate !!}</td>
                                                <td>{!! $itdm->unit_price !!}</td>
                                                <td>{!! $itdm->sub_total !!}</td>
                                            </tr>

                                        @endforeach

                                    </tbody>
                                </table><!---/table-responsive-->
                            @endif

                            <?php if(!empty(Session::get('acl')[34][3]) && empty($demand->group_status)){ ?>

                                <a class="btn btn-primary btn-xs" href="{{ URL::to('demand-group/' . $demand->id) }}" title="Edit Demand" >
                                    <i class="icon-edit"> Edit Demand</i>
                                </a>

                            <?php } ?>    

                            <?php if(!empty(Session::get('acl')[34][16])){ ?>
                                
                                <div class="row">

                                    {{ Form::open(array('role' => 'form', 'url' => 'post-only-group-status-change', 'files'=> true, 'class' => '', 'id'=>'demands')) }}
                                        <input type="hidden" name="demand_id" value="{!! $demand->id !!}">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="sutotal_price">Approve Demand :</label>
                                                {{ Form::select('group_status', array('1' => 'In Stock', '2' => 'Not In Stock'), $demand->group_status , array('class' => 'form-control selectpicker', 'id' => 'group_status')) }}
                                            </div>
                                        </div>

                                        @if(!empty(Auth::user()->nsd_bsd) && in_array(3,explode(',', Auth::user()->nsd_bsd)))

                                            <div class="col-md-3 @if(empty($demand->transfer_to)) hidden @endif" id="transfer_to_div">
                                                <div class="form-group">
                                                    <label for="transfer_to">Transfer To :</label>
                                                    {{ Form::select('transfer_to', $destinationPlaces, $demand->transfer_to, array('class' => 'form-control selectpicker', 'id' => 'transfer_to')) }}
                                                </div>
                                            </div>

                                            <div class="col-md-3  @if(empty($demand->transfer_to)) hidden @endif" id="transfer_status_div">
                                                <div class="form-group">
                                                    <label for="transfer_to">Transfer Status :</label>
                                                    {{ Form::select('transfer_status', array('' => 'Waiting for approve','1' => 'In Stock', '2' => 'Not In Stock'), $demand->transfer_status, array('class' => 'form-control selectpicker', 'id' => 'transfer_status')) }}
                                                </div>
                                            </div>

                                        @endif

                                        <div class="col-md-3" style="padding-top: 23px;">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">{!!trans('english.SAVE')!!}</button>
                                            </div>
                                        </div>

                                    {!!   Form::close() !!}

                                </div> {{-- end group approve form --}}

                            <?php } ?>
                        
                        <!-- LP section work start -->
                        <div class="row">
                            
                            <div class="col-md-12">
                                <?php if(!empty(Session::get('acl')[34][17]) && $demand->group_status==2 && empty($demand->tender_quation_collection)){ ?>
                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('floating-tender/create/'.$demand->id) }}" title="Create Tender" target="_blank">
                                        <i class="icon-edit">Float Tender</i>
                                    </a>
                                <?php } ?> 

                                <?php //if(!empty(Session::get('acl')[34][18]) && !empty($demand->tender_floating) && empty($demand->cst_draft_status)){ 
                                    if(!empty(Session::get('acl')[34][18]) && !empty($demand->tender_floating)){ ?>
                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('create-collection-quotation/'.$demand->id) }}" title="Create Tender" target="_blank">
                                        <i class="icon-edit"> Add Collection Quotation</i>
                                    </a>
                                <?php } ?> 

                                <?php //if(!empty(Session::get('acl')[34][19]) && !empty($demand->tender_quation_collection) && empty($demand->cst_supplier_select)){ 
                                if(!empty(Session::get('acl')[34][19]) && !empty($demand->tender_quation_collection)){ ?>
                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('cst-view/'.$demand->id) }}" title="CST view" target="_blank">
                                        <i class="icon-eye-open"> CST view</i>
                                    </a>
                                <?php } ?> 

                                <?php //if(!empty(Session::get('acl')[34][20]) && !empty($demand->cst_draft_status) && empty($demand->lp_section_status)){ 
                                if(!empty(Session::get('acl')[34][20]) && !empty($demand->cst_draft_status) ){ ?>
                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('draft-cst-view/'.$demand->id) }}" title="CST view" target="_blank">
                                        <i class="icon-eye-open"> CST draft</i>
                                    </a>
                                <?php } ?>

                                <?php if(!empty(Session::get('acl')[34][20]) && !empty($demand->lp_section_status) && $demand->lp_section_status ==2 && $demand->head_ofc_apvl_status == 2 ){ ?>
                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('headquarte-approval/'.$demand->id) }}" title="HQ Approval">
                                        <i class="icon-eye-open"> HQ Approve</i>
                                    </a>
                                <?php } ?>

                                <?php if(!empty(Session::get('acl')[34][20]) && !empty($demand->cst_supplier_select) && $demand->lp_section_status==1 && ($demand->head_ofc_apvl_status==1 || $demand->head_ofc_apvl_status=='')  ){ ?>
                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('po-generate-view/'.$demand->id) }}" title="CST view" target="_blank">
                                        <i class="icon-eye-open"> Generate PO</i>
                                    </a>
                                <?php } ?>
  

                            </div>
                              
                        </div>
                         
                        
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