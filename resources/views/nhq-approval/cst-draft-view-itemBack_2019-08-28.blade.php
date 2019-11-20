<?php 
    use functions\OwnLibrary; 
    use App\Http\Controllers\ImageResizeController;
?>
@extends('layouts.default')

<style type="text/css">
    .input_design{
        padding: 6px 1px !important;
    }
    .for-vertical-align .btn-group.bootstrap-select.form-control {
        margin-bottom: 0;
    }
</style>
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>NHQ CST view</h3>
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
                        <h3>NHQ CST view</h3>
                    </div>
                        <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'post-headquarte-approval', 'files'=> true, 'class' => '', 'id'=>'')) }}
                            
                            <input type="hidden" name="demandToLprId" value="{!! $demandToLprId !!}">
                            <input type="hidden" name="tenderId" value="{!! $tenderId !!}">
                            <input type="hidden" name="tenderNature" value="1">
                            <div style="margin-bottom: 10px;">
                                 <a href="{{ url('nhq-cst-view-print/'.$demandToLprId.'&'.$tenderId) }}" class="btn btn-primary" target="_blank">Print PDF</a>
                                 <a href="{{ url('draft-cst-view-excel/'.$demandToLprId.'&'.$tenderId) }}" class="btn btn-default">Excel</a>
                            </div>

                            <p class="text-center">@if(!empty($suppliersInf)) @if($suppliersInf[0]->total > $orgInfo->purchase_limit) PRELIMINARY @endif @endif COMPARATIVE STATEMENT- NSSD DHAKA <br>
                            {!! $tender->tender_number!!} Date: @if(!empty($tender->created_at)) {!! date('d F Y', strtotime($tender->created_at)) !!} @endif</p>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="requester">NHQ Letter Number:<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nhq_app_ltr_number" name="nhq_app_ltr_number" value="{!! $mainTenderInfo->nhq_app_ltr_number !!}" required="">
                                    </div>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <div class="form-group for-vertical-align">
                                        <select class="form-control selectpicker" name="" id="status_change">
                                            <option value="1">{!!  'Approve All' !!}</option>
                                            <option value="2">{!!  'Retender All' !!}</option>
                                            <option value="3">{!!  'Reject All' !!}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <?php $tblThe = 1; $offerUni = 1; $forlebel=1;?>

                            @if(!empty($targetArray))       
                                @foreach($targetArray as $key => $ta) 
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped middle-align">
                                        <thead>
                                        <tr class="center">
                                            
                                            <th class="text-center" width="2%">SL#</th>
                                            <th class="text-center" width="15%">{{'Items Details'}}</th>
                                            <th class="text-center hidden">{{'Machinery / Manufacturer'}}</th>
                                            <th class="text-center" width="5%">{{'Deno'}}</th>
                                            <th class="text-center" width="5%">{{'Quantity'}}</th>
                                            <th class="text-center" width="6%">{{'Qty Order'}}</th>
                                            <th class="text-center" width="13%">{{'Last Purchase Info'}}</th>
                                            <?php $sls = 0; ?>
                                            @if(!empty($supplierResult[$key]))
                                                @foreach($supplierResult[$key] as $sr)
                                                    <th class="text-center" colspan="@if(empty($sr->alternative_unit_price)) 3 @else 5 @endif" width="20">{!! OwnLibrary::numToOrdinalWord(++$sls) .' Lowest'!!}</th>
                                                @endforeach
                                            @endif
                                            <th width="10%" class="text-center">{{'Status'}}</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                            <?php $sl=1; ?>
                                            @if(!empty($supArray[$key])) 
                                                <tr>
                                                    <td colspan="6"></td>
                                                    @foreach($supArray[$key] as $sui) 
                                                    <?php $devideData = explode('?',$sui); ?>
                                                        <td @if(empty($devideData[2])) colspan="3" @else colspan="5" class="text-center" @endif style="text-align: center;">
                                                            <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                                <div class=" checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                                    <input class="activity_1 activitycell hidden" type="checkbox" id="cst_draft_sup_id{!! $devideData[0].$ta['items'][0]->id !!}" name="cst_draft_sup_id[]" value="{!! $devideData[1] !!}" @if($devideData[4]==1) checked @endif>
                                                                    <label for="cst_draft_sup_id{!! $devideData[0].$ta['items'][0]->id !!}">{!! $devideData[0] !!}</label>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    @endforeach
                                                    <td></td>
                                                </tr>
                                            @endif

                                            @if(!empty($supArray[$key])) 
                                                <tr style="background-color: #f9f9f9;">
                                                    <td colspan="6" style="border-top: none;"></td>
                                                    @foreach($supArray[$key] as $suiii) 
                                                    <?php $devideData4 = explode('?',$suiii); ?>
                                                        @if(empty($devideData4[2]))
                                                            <td colspan="3" style="border-top: none;"></td>
                                                        @else 
                                                            <td colspan="3" style="text-align: center;">
                                                            <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                                <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                                    <input class="activity_1 activitycell offer mainofferoralternaive{!! $offerUni !!}" type="checkbox" id="mainofferoralternaive{!! $forlebel !!}" name="mainoralteroff[]" value="1" checked="">
                                                                    <label for="mainofferoralternaive{!! $forlebel !!}">{!!' Main Offer'!!}</label>
                                                                </div>
                                                            </div>
                                                            <!-- Main Offer -->
                                                            </td>
                                                            <?php $forlebel++; ?>
                                                            <td colspan="2" style="text-align: center;">
                                                                <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                                    <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                                        <input class="activity_1 activitycell offer mainofferoralternaive{!! $offerUni !!}" type="checkbox" id="mainofferoralternaive{!! $forlebel !!}" name="mainoralteroff[]" value="2">
                                                                        <label for="mainofferoralternaive{!! $forlebel !!}">{!!'Alternative Offer'!!}</label>
                                                                    </div>
                                                                </div>

                                                                @foreach($ta['items'] as $spss)
                                                                    <input type="hidden" name="supanditemid[]" value="{!! $spss->id.'&'.$devideData4[1] !!}">
                                                                @endforeach


                                                                <!-- Alternative Offer --></td>
                                                        @endif
                                                        <?php $offerUni++; $forlebel++;  ?>
                                                    @endforeach
                                                    <td style="border-top: none;"></td>
                                                </tr>        
                                            @endif

                                            @if(!empty($supArray[$key])) 
                                                <tr>
                                                    <td colspan="6"></td>
                                                    @foreach($supArray[$key] as $suittt) 
                                                    <?php $devideDataddd = explode('?',$suittt); ?>
                                                            <td></td>
                                                            <td>Unit Price</td>
                                                            <td>Total Price</td>
                                                        @if(!empty($devideDataddd[2]))
                                                            <td>Unit Price</td>
                                                            <td>Total Price</td>
                                                        @endif
                                                        
                                                    @endforeach
                                                    <td></td>
                                                </tr>
                                            @endif

                                        <?php //dd($ta['supi']); ?>

                                            <tr>
                                                @foreach($ta['items'] as $itm)
                                                    <td>{!! $sl++ !!}</td>
                                                    <td>{!! $itm->item_name !!}</td>
                                                    <td class="hidden">{!! $itm->manufacturing_country !!}</td>
                                                    <td>{!! $itm->denoName !!}</td>
                                                    <td>{!! $itm->unit !!}</td>
                                                    <td>
                                                        <div class="form-group" style="margin-bottom: 0">
                                                            <input type="number" class="form-control input_design" id="issue_control_stamp" name="nhq_app_qty[{!! $itm->itm_to_dn_id !!}]" value="{!! $itm->unit !!}" style="width: 100%;" min="1" max="{!! $itm->unit !!}">
                                                            <input type="hidden" name="item_to_dmn_id[]" value="{!! $itm->itm_to_dn_id !!}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if(!empty($itm->previsouSuppName)) {!! 'Sup: '.$itm->previsouSuppName !!} @endif
                                                        @if(!empty($itm->previsouUnitPrice)) {!! ' UP: '.$itm->previsouUnitPrice !!} @endif
                                                        @if(!empty($itm->previousDates)) {!! ' Date: '.$itm->previousDates !!} @endif

                                                        @if(empty($itm->previsouSuppName) && empty($itm->previsouUnitPrice) && empty($itm->previousDates))
                                                            NA
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <?php //echo "<pre>"; print_r($ta['supi'][0]); exit;?>
                                                
                                                @foreach($ta['supi'] as $sp)
                                                    @if(count($sp)>0)
                                                        <!-- <td>L.P.P {!! $sp[0]->last_unti_price !!}</td> -->
                                                        <td>
                                                            <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                                <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                                    <input class="activity_1 activitycell unique lotwisesupp{!! $itm->id !!}" type="checkbox" id="nhq_app{!! $sp[0]->id !!}" name="item_ids[]" value="{!! $sp[0]->id.'&'.$sp[0]->dmn_to_cal_qut_id !!}" @if($sp[0]->select_as_winner==1) checked @endif>
                                                                    <label for="nhq_app{!! $sp[0]->id !!}"></label>
                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td>{!! ImageResizeController::custom_format($sp[0]->unit_price) !!}</td>
                                                        <td>{!! ImageResizeController::custom_format($sp[0]->unit_price*$sp[0]->quoted_quantity) !!}</td>
                                                        @else
                                                            <td colspan="3">Not participated</td>
                                                        @endif

                                                        @if(!empty($sp[0]->alternative_unit_price))
                                                            <td>{!! ImageResizeController::custom_format($sp[0]->alternative_unit_price) !!}</td>
                                                            <td>{!! ImageResizeController::custom_format($sp[0]->alternative_unit_price*$sp[0]->alternative_quoted_quantity) !!}</td>
                                                        @endif

                                                @endforeach
                                                <td>
                                                    <div class="form-group for-vertical-align" style="margin-bottom: 0;">
                                                        <select class="form-control selectpicker status" name="nhq_app_status[]" id="status">
                                                            <option value="1" @if($ta['items'][0]->nhq_app_status==1) selected @endif>{!!  'Approve' !!}</option>
                                                            <option value="2" @if($ta['items'][0]->nhq_app_status==2) selected @endif>{!!  'Retender' !!}</option>
                                                            <option value="3" @if($ta['items'][0]->nhq_app_status==3) selected @endif>{!!  'Reject' !!}</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                
                                            </tr>
                                            @if(!empty($supTotalAmountArray)) 
                                                <tr>
                                                    <td colspan="6"></td>
                                                    @foreach($supTotalAmountArray[$key] as $sta)
                                                    <?php $devideData3 = explode('?',$sta); ?>
                                                        <td colspan="3" style="text-align: right;">{!! ImageResizeController::custom_format($devideData3[0]) !!}</td>
                                                        @if(!empty($devideData3[1])) 
                                                            <td colspan="2" style="text-align: right;">{!! ImageResizeController::custom_format($devideData3[1]) !!}</td>
                                                        @endif
                                                    @endforeach
                                                    <td></td>
                                                </tr>
                                            @endif 
                                            
                                        </tbody>
                                       
                                    </table><!---/table-responsive-->
                                </div>
                                <?php $tblThe++; ?>
                                @endforeach
                            @endif

                            <!-- Start Comment Table =======
                            ============================= -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped middle-align">
                                            <thead>
                                            <tr class="center">
                                                <th class="text-center" width="2%">SL#</th>
                                                <th class="text-center" width="30">{{'Supplier Name'}}</th>
                                                <th class="text-center" width="10%">{{'Total'}}</th>
                                                <th class="text-center" width="8%">{{'Discount'}}</th>
                                                <th class="text-center" width="10%">{{'Grand Total'}}</th>
                                                <th class="text-center" width="40%">{{'Comment'}}</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                                <?php $slsSp = 1; ?>
                                                @foreach($suppliersInf as $sp)
                                                    <tr>
                                                        <td>{!! $slsSp++ !!}</td>
                                                        <td>{!! $sp->suppliernametext !!} <input type="hidden" name="dem_to_col_quo_id[]" value="{!! $sp->id !!}" ></td>
                                                        <td>
                                                            <div class="form-group" style="margin-bottom: 0">
                                                                <input type="number" class="form-control input_design com_total" id="issue_control_stamp" name="" value="{!! $sp->total !!}" style="width: 100%;" readonly="">
                                                            </div>
                                                        </td>
                                                        <td class="">
                                                            <div class="form-group" style="margin-bottom: 0">
                                                                <input type="number" class="form-control input_design discount" id="" name="discount[]" value="{!! $sp->discount_amount !!}" style="width: 100%;" min="0" max="{!! $sp->total !!}">
                                                            </div>
                                                        </td>
                                                        <td class="">
                                                            <div class="form-group" style="margin-bottom: 0">
                                                                <input type="number" class="form-control input_design grand_total" id="" name="grand_total[]" value="{!! $sp->total-$sp->discount_amount !!}" style="width: 100%;" readonly="">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control" name="comment[]" rows="1" placeholder="Comment">{!! $sp->comment_on_cst !!}</textarea>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                </div>
                            </div>
                            <!-- End Comment Table =========
                                ===========================-->

                                <div class="form-group">
                                <div class="col-md-12">
                                    <div>
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                <?php //if(!empty(Session::get('acl')[34][20]) && !empty($tender->cst_draft_status) && (empty($tender->lp_section_status) || $tender->lp_section_status==2 ) ){ ?>
                                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">{!! 'Submit' !!}</button>
                                <?php //} ?>     
                                </div>
                            </div>

                            {!!   Form::close() !!}

                        </div>


                        <div class="row" style="padding-left: 13px;">
                            <div class="col-md-12">
                                <b>Remarks</b><br><br>
                            </div>
                        </div>
                            <?php 
                                $slso = 1;
                            ?>
                        <div class="row">
                            @if(!empty($suppliersInf))
                                @foreach($suppliersInf as $key => $sui)
                                    <div class="col-md-12" style="padding-left: 27px;">
                                       <p style="margin-bottom: 0px;">
                                            <b> {!! $slso++.'. '. $sui->suppliernametext !!}</b>
                                            <p style="margin-left: 15px;">
                                                {!! nl2br($sui->comment_on_cst) !!}
                                            </p>
                                            
                                       </p><br>
                                        
                                    </div>
                                @endforeach
                            @endif 
                        </div>

                        

                    </div>
                </div>
            </div>

    </div>
    
    <script type="text/javascript">
        $(document).ready(function(){

            $('.unique').change(function() {
                var classNme = $(this).attr('class').split(' ');
                classNme = classNme[3];

                if($('input.'+classNme).filter(':checked').length == 1)
                    $('input.'+classNme+':not(:checked)').attr('disabled', 'disabled');
                else
                    $('input.'+classNme).removeAttr('disabled');

                $('.'+classNme).change(function(){
                    if($('input.'+classNme).filter(':checked').length == 1)
                        $('input.'+classNme+':not(:checked)').attr('disabled', 'disabled');
                    else
                        $('input.'+classNme).removeAttr('disabled');
                });
                
            });

            $('.offer').change(function() {
                var classNme1 = $(this).attr('class').split(' ');
                classNme1 = classNme1[3];

                if($('input.'+classNme1).filter(':checked').length == 1){
                    $('input.'+classNme1).prop('checked', false);
                    $(this).prop('checked', true);
                    //$('input.'+classNme1+':not(:checked)').attr('disabled', 'disabled');
                }else{ 
                    $('input.'+classNme1).prop('checked', true);
                    //$('input.'+classNme1+':not(:checked)').attr('disabled', 'disabled');
                    $(this).prop('checked', false);
                }
                    
                // $('.'+classNme1).change(function(){
                //     if($('input.'+classNme1).filter(':checked').length == 1){
                //         $('input.'+classNme1+':not(:checked)').attr('disabled', 'disabled');
                //     }else{
                //         $('input.'+classNme1).removeAttr('disabled');
                //     }
                // });
                
            });

            $(document).on('change','#status_change',function(){
                var statusVal = $(this).val();
                $(".status").val(statusVal).selectpicker('refresh');
            });

            $(document).on('input','.discount',function(){
                var discountVal = $(this).val();
                var totalVal = $(this).closest("tr").find('.com_total').val();
                var grandTotal = totalVal-discountVal;
                var totalVal = $(this).closest("tr").find('.grand_total').val(grandTotal);
            });

            
          

        });
    </script>
@stop