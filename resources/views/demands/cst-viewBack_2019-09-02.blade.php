<?php 
    use functions\OwnLibrary; 
    use App\Http\Controllers\ImageResizeController;
?>
@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>CST</h3>
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
                        <h3>CST</h3>
                    </div>
                        <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'draft-cst-post', 'files'=> true, 'class' => '', 'id'=>'')) }}
                            
                            <input type="hidden" name="demandToLprId" value="{!! $demandToLprId !!}">
                            <input type="hidden" name="tenderId" value="{!! $tenderId !!}">
                            <input type="hidden" name="stateNo" value="{!! $stateNo !!}">
                            <div style="margin-bottom: 10px;">
                                 <a href="{{ url('cst-view-print/'.$demandToLprId.'&'.$tenderId) }}" class="btn btn-primary" target="_blank">Print PDF</a>
                                 <a href="{{ url('cst-view-excel/'.$demandToLprId.'&'.$tenderId) }}" class="btn btn-default">Excel</a>
                            </div>

                            <p class="text-center">@if(!empty($suppliersInf)) @if($suppliersInf[0]->total > $orgInfo->purchase_limit) PRELIMINARY @endif @endif COMPARATIVE STATEMENT- NSSD DHAKA <br>
                            {!! $tender->tender_number!!} Date: @if(!empty($tender->created_at)) {!! date('d F Y', strtotime($tender->created_at)) !!} @endif</p>
                            <?php 
                                $sl  =1; 
                                $colSpanWithAlt     = 5;
                                $colSpanWithOutAlt  = 3;
                                if($sampelQtyChck>0){
                                    $colSpanWithAlt = 6;
                                    $colSpanWithOutAlt = 4;
                                }
                            ?>
                            @if(!empty($targetArray))       
                                @foreach($targetArray as $key => $ta)
                                <?php 
                                    $allo=1; 
                                ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped middle-align">
                                        <thead>
                                        <tr class="center">
                                            <th class="text-center" width="2%">SL#</th>
                                            <th class="text-center" width="15%">{{'Items Details'}}</th>
                                            <th class="text-center hidden">{{'Machinery / Manufacturer'}}</th>
                                            <th class="text-center" width="5%">{{'Deno'}}</th>
                                            <th class="text-center" width="5%">{{'Quantity'}}</th>
                                            <th class="text-center" width="13%">{{'Last Purchase Info'}}</th>
                                            <?php $sls = 1; ?>
                                            @if(!empty($supplierResult[$key]))
                                                @foreach($supplierResult[$key] as $sr)
                                                    <th class="text-center" colspan="@if(empty($sr->alternative_unit_price)) {!! $colSpanWithOutAlt !!} @else {!! $colSpanWithAlt !!} @endif" width="20">
                                                    <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                        <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                            <input class="activity_1 activitycell placeorder{!! $sls !!} lowtoheigh" type="checkbox" id="placeorder{!! $sls !!}" name="placeorder{!! $sls !!}" value="">
                                                            <label for="placeorder{!! $sls !!}">{!! OwnLibrary::numToOrdinalWord($sls++) .' Lowest'!!}</label>
                                                        </div>
                                                    </div>
                                                    {{-- {!! OwnLibrary::numToOrdinalWord(++$sls) .' Lowest'!!} --}}</th>
                                                @endforeach
                                            @endif
                                        </tr>
                                        </thead>

                                        <tbody>
                                            
                                            @if(!empty($supArray[$key])) 
                                                <tr>
                                                    <td colspan="5"></td>
                                                    @foreach($supArray[$key] as $sui) 
                                                    <?php $devideData = explode('?',$sui); ?>
                                                        <td @if(empty($devideData[2])) colspan="{!! $colSpanWithOutAlt !!}" @else colspan="{!! $colSpanWithAlt !!}" class="text-center" @endif style="text-align: center;">
                                                            <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                                <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                                    <input class="activity_1 activitycell emni supplier {!! 'supplierallse'.$devideData[4] !!}" type="checkbox" id="cst_draft_sup_id{!! $devideData[0].$ta['items'][0]->id !!}" name="cst_draft_sup_id[]" value="{!! $devideData[1] !!}" @if($devideData[3]==1) checked @endif>
                                                                    <label for="cst_draft_sup_id{!! $devideData[0].$ta['items'][0]->id !!}">{!! $devideData[0] !!}</label>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endif

                                            @if(!empty($supArray[$key])) 
                                                <tr style="background-color: #f9f9f9;">
                                                    <td colspan="5" style="border-top: none;"></td>
                                                    @foreach($supArray[$key] as $suiii) 
                                                    <?php $devideData4 = explode('?',$suiii); ?>
                                                        @if(empty($devideData4[2]))
                                                            <td colspan="{!! $colSpanWithOutAlt !!}" style="border-top: none;"></td>
                                                        @else 
                                                            <td colspan="3" style="text-align: center;">Main Offer</td>
                                                            <td colspan="2" style="text-align: center;">Alternative Offer</td>
                                                            @if($sampelQtyChck>0)
                                                            <td></td>
                                                            @endif

                                                        @endif
                                                        
                                                    @endforeach
                                                </tr>        
                                            @endif

                                            @if(!empty($supArray[$key])) 
                                                <tr>
                                                    <td colspan="5"></td>
                                                    @foreach($supArray[$key] as $suittt) 
                                                    <?php $devideDataddd = explode('?',$suittt); ?>
                                                            <td></td>
                                                            <td>Unit Price</td>
                                                            <td>Total Price</td>
                                                        @if(!empty($devideDataddd[2]))
                                                            <td>Unit Price</td>
                                                            <td>Total Price</td>
                                                        @endif
                                                            @if($sampelQtyChck>0)
                                                            <td>Sample Qty</td>
                                                        @endif
                                                    @endforeach
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
                                                    @if(count($sp)>0 && !empty($sp[0]->unit_price) && !empty($sp[0]->quoted_quantity))
                                                        <!-- <td>L.P.P {!! $sp[0]->last_unti_price !!}</td> -->

                                                        <td>
                                                            <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                                <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                                    <input class="activity_1 activitycell placeorder{!! $allo++ !!} {!! 'supplierallse'.$sp[0]->supplier_id !!}" type="checkbox" id="nhq_app{!! $sp[0]->id !!}" name="item_ids[]" value="{!! $sp[0]->id.'&'.$sp[0]->dmn_to_cal_qut_id !!}" @if($sp[0]->select_as_draft_cst==1) checked @endif>
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
                                                        @if($sampelQtyChck>0)
                                                        <td>{!! ImageResizeController::custom_format($sp[0]->sample_qty) !!}</td>
                                                        @endif

                                                @endforeach
                                            </tr>
                                            @if(!empty($supTotalAmountArray)) 
                                                <tr>
                                                    <td colspan="5"></td><td class="hidden"></td>
                                                    @foreach($supTotalAmountArray[$key] as $sta)
                                                    <?php $devideData3 = explode('?',$sta); ?>
                                                        <td colspan="3" style="text-align: right;">@if(!empty($devideData3[0])) {!! ImageResizeController::custom_format($devideData3[0]) !!} @endif</td>
                                                        @if(!empty($devideData3[1])) 
                                                            <td colspan="2" style="text-align: right;">@if(!empty($devideData3[0])) {!! ImageResizeController::custom_format($devideData3[1]) !!} @endif</td>
                                                        @endif
                                                        @if($sampelQtyChck>0)
                                                        <td></td>
                                                        @endif
                                                    @endforeach
                                                    
                                                </tr>
                                            @endif 
                                                
                                            
                                        </tbody>
                                       
                                    </table><!---/table-responsive-->
                                </div>
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
                                                <th class="text-center hidden" width="8%">{{'Discount'}}</th>
                                                <th class="text-center hidden" width="10%">{{'Grand Total'}}</th>
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
                                                        <td class="hidden">
                                                            <div class="form-group" style="margin-bottom: 0">
                                                                <input type="number" class="form-control input_design discount" id="" name="discount[]" value="{!! $sp->discount_amount !!}" style="width: 100%;" min="0" max="{!! $sp->total !!}">
                                                            </div>
                                                        </td>
                                                        <td class="hidden">
                                                            <div class="form-group" style="margin-bottom: 0">
                                                                <input type="number" class="form-control input_design grand_total" id="" name="grand_total[]" value="{!! $sp->total-$sp->discount_amount !!}" style="width: 100%;" readonly="">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control" name="comment[]" rows="1" placeholder="Comment">{!! $sp->comnt_on_col_qut_supplier !!}</textarea>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                </div>
                            </div>
                            <!-- End Comment Table =========
                                ===========================-->
                            
                            <div class="row" style="padding-top: 40px;">
                                <div class="col-md-12">
                                    <div class="col-md-5">
                                        
                                        <div class="form-group " style=""><label class="control-label" for="status" style="display: none;"></label>
                                            <div class="checkbox checkbox-success" style="margin-top: 30px;">
                                                <input class="activity_1 activitycell" type="checkbox" id="checked" name="checked[]" value="" checked>
                                                <label for="checked"> Checked</label>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="priority">Send to NHQ:</label>
                                            <select class="form-control selectpicker" name="send_to_nhq" id="send_to_nhq" required="">
                                                <option value="">{!! '- Select -' !!}</option>
                                                <option value="1" @if(empty($tender->send_to_nhq)) @if(!empty($suppliersInf)) @if($suppliersInf[0]->total <= $orgInfo->purchase_limit) selected @endif @endif @else @if($tender->send_to_nhq==1) selected @endif @endif >{!! 'Send To NSSD' !!}</option>
                                                <option value="2" @if(empty($tender->send_to_nhq)) @if(!empty($suppliersInf)) @if($suppliersInf[0]->total > $orgInfo->purchase_limit) selected @endif @endif @else @if($tender->send_to_nhq==2) selected @endif @endif>{!! 'Need NHQ Approval' !!}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4" style="padding-top: 25px;">
                                        <div class="form-group">
                                            <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                        <?php if(!empty(Session::get('acl')[34][19]) && !empty($tender->tender_quation_collection) && empty($tender->cst_supplier_select)){ ?>
                                            <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">{!! 'Generate CST' !!}</button>
                                        <?php } ?>     
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {!!   Form::close() !!}

                            <div class="col-md-1">
                                {{ Form::open(array('role' => 'form', 'url' => 'post-cst-retender-reject', 'files'=> true, 'class' => 'retenderForm', 'id'=>'')) }}

                                <input type="hidden" name="demandToLprId" value="{!! $demandToLprId !!}">
                                <input type="hidden" name="tenderId" value="{!! $tenderId !!}">
                                <input type="hidden" name="stateNo" value="{!! $stateNo !!}">
                                <input type="hidden" name="wheretoredirect" value="1">
                                <input type="hidden" name="tender_action" value="2">
                                    <div class="form-group">
                                    <?php if(!empty(Session::get('acl')[34][19]) ){ ?>
                                        <button type="submit" class="btn btn-primary" style="margin-right: 5px;">{!! 'Retender' !!}</button>
                                    <?php } ?>     
                                    </div>
                                    
                                {!!   Form::close() !!}
                            </div>

                            <div class="col-md-1">
                                {{ Form::open(array('role' => 'form', 'url' => 'post-cst-retender-reject', 'files'=> true, 'class' => 'retenderForm', 'id'=>'')) }}

                                <input type="hidden" name="demandToLprId" value="{!! $demandToLprId !!}">
                                <input type="hidden" name="tenderId" value="{!! $tenderId !!}">
                                <input type="hidden" name="stateNo" value="{!! $stateNo !!}">
                                <input type="hidden" name="wheretoredirect" value="1">
                                <input type="hidden" name="tender_action" value="3">
                                    <div class="form-group">
                                    <?php if(!empty(Session::get('acl')[34][19]) ){ ?>
                                        <button type="submit" class="btn btn-primary" style="margin-right: 5px;">{!! 'Reject' !!}</button>
                                    <?php } ?>     
                                    </div>
                                    
                                {!!   Form::close() !!}
                            </div>

                            <div class="row" style="padding-left: 13px;">
                                <div class="col-md-12">
                                    <b>Remarks</b><br><br>
                                </div>
                            </div>
                            <div class="row">
                                <?php 
                                    $slso = 1;
                                ?>
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

            $('.lowtoheigh').change(function() {
                var classNme  = $(this).attr('class').split(' ');
                var mainClass = classNme[3];
                var updateCla = classNme[2];
                
                if($(this).is(":checked")){
                    if(mainClass){
                        if($('.'+mainClass).is(":checked")){
                            $('.'+updateCla).each(function(){ //iterate all listed checkbox items
                                $(this).prop('checked', true);
                            });
                        }
                    }
                }

                if($(this).is(":not(:checked)")){
                    if(mainClass){
                        if($('.'+mainClass).is(":not(:checked)")){
                            $('.'+updateCla).each(function(){ //iterate all listed checkbox items
                                $(this).prop('checked', false);
                            });
                        }
                    }
                }
                
            });

            $('.supplier').change(function() {
                var classNme1  = $(this).attr('class').split(' ');
                var mainClass1 = 'supplier';
                var updateCla1 = classNme1[4];

                if($(this).is(":checked")){
                    if(mainClass1){
                        if($('.'+mainClass1).is(":checked")){
                            $('.'+updateCla1).each(function(){ //iterate all listed checkbox items
                                $(this).prop('checked', true);
                            });
                        }
                    }
                }

                if($(this).is(":not(:checked)")){
                    if(mainClass1){
                        if($('.'+mainClass1).is(":not(:checked)")){
                            $('.'+updateCla1).each(function(){ //iterate all listed checkbox items
                                $(this).prop('checked', false);
                            });
                        }
                    }
                }

            });

            $('.retenderForm').on('submit', function() {
                if(confirm('Do you really want to submit?')) {
                    return true;
                }
                return false;
            });

        });
    </script>
@stop