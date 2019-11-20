<?php 
    use functions\OwnLibrary; 
    use App\Http\Controllers\ImageResizeController;
?>
@extends('layouts.default')
<style type="text/css">

    p {
        margin: 0 0 0 !important;
    }

</style>
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>NSSD Approval</h3>
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
                        <h3>NSSD Approval</h3>
                    </div>
                        <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'select-supplier-cst-post', 'files'=> true, 'class' => '', 'id'=>'')) }}
                            
                            <input type="hidden" name="demandToLprId" value="{!! $demandToLprId !!}">
                            <input type="hidden" name="tenderId" value="{!! $tenderId !!}">
                            <input type="hidden" name="tenderNature" value="2">
                            <div style="margin-bottom: 10px;">
                                 <a href="{{ url('draft-cst-view-print/'.$demandToLprId.'&'.$tenderId) }}" class="btn btn-primary" target="_blank">Print PDF</a>
                                 <a href="{{ url('draft-cst-view-excel/'.$demandToLprId.'&'.$tenderId) }}" class="btn btn-default">Excel</a>
                            </div>

                            <p class="text-center">@if(!empty($suppliersInf)) @if($suppliersInf[0]->total > $orgInfo->purchase_limit) PRELIMINARY @endif @endif COMPARATIVE STATEMENT- NSSD DHAKA <br>
                            {!! $tender->tender_number!!} Date: @if(!empty($tender->created_at)) {!! date('d F Y', strtotime($tender->created_at)) !!} @endif</p>
                            
                            <?php 
                                $arSlmen = 0; 
                                $forfistSupp = 1; 
                                $colSpanWithAlt     = 4;
                                $colSpanWithOutAlt  = 2;
                                if($sampelQtyChck>0){
                                    $colSpanWithAlt = 5;
                                    $colSpanWithOutAlt = 3;
                                }
                            ?>
                            @if(!empty($mainArray))       
                                @foreach($mainArray as $key => $ta)
                                <?php 
                                    $allo=1; 
                                ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped middle-align">
                                        <thead>
                                        <tr class="center">
                                            <th class="text-center" width="2%">SL#</th>
                                            <th class="text-center" width="5%">Lot Name</th>
                                            <th class="text-center" width="15%">{{'Items Details'}}</th>
                                            <th class="text-center hidden">{{'Machinery / Manufacturer'}}</th>
                                            <th class="text-center" width="5%">{{'Deno'}}</th>
                                            <th class="text-center" width="5%">{{'Quantity'}}</th>
                                            <th class="text-center" width="13%">{{'Last Purchase Info'}}</th>
                                            <?php $sls = 1; ?>
                                            @if(!empty($supplierResultFir[$key]))
                                                @foreach($supplierResultFir[$key] as $sr)
                                                    <th class="text-center" colspan="@if(empty($sr->altr_total_price)) {!! $colSpanWithOutAlt !!} @else {!! $colSpanWithAlt !!} @endif" width="20">
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
                                            <?php $sl=1; ?>
                                            @if(!empty($supArray[$key])) 
                                                <tr>
                                                    <td colspan="6"></td>
                                                    @foreach($supArray[$key] as $sui) 
                                                    <?php $devideData = explode('?',$sui); ?>
                                                        <td @if(empty($devideData[2])) colspan="{!! $colSpanWithOutAlt !!}" @else colspan="{!! $colSpanWithAlt !!}" class="text-center" @endif style="text-align: center;">
                                                            <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                                <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                                    <input class="activity_1 activitycell placeorder{!! $allo++ !!} @if($forfistSupp==1){!!'supplier'!!}@endif {!! 'supplierallse'.$devideData[6] !!} forcheck downsupplier" type="checkbox" id="cst_draft_sup_id{!! $devideData[1].'&'.$devideData[4].'&'.$devideData[5] !!}" name="cst_draft_sup_id[]" value="{!! $devideData[1].'&'.$devideData[4].'&'.$devideData[5] !!}" @if($devideData[3]==1) checked @endif>
                                                                    <label for="cst_draft_sup_id{!! $devideData[1].'&'.$devideData[4].'&'.$devideData[5] !!}">{!! $devideData[0] !!}</label>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endif

                                            @if(!empty($supArray[$key])) 
                                                <tr style="background-color: #f9f9f9;">
                                                    <td colspan="6" style="border-top: none;"></td>
                                                    @foreach($supArray[$key] as $suiii) 
                                                    <?php $devideData4 = explode('?',$suiii); ?>
                                                        @if(empty($devideData4[2]))
                                                            <td colspan="{!! $colSpanWithOutAlt !!}" style="border-top: none;"></td>
                                                        @else 
                                                            <td colspan="2" style="text-align: center;">Main Offer</td>
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
                                                    <td colspan="6"></td>
                                                    @foreach($supArray[$key] as $suittt) 
                                                    <?php $devideDataddd = explode('?',$suittt); ?>
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

                                            <?php $mn = 0; ?>
                                            @foreach($ta as $tas)
                                            <?php //echo "<pre>"; print_r(count($mainArray[$key])); exit; ?>
                                            <tr>
                                                @if($mn == 0)
                                                    <td rowspan="{!! count($mainArray[$key]) !!}">{!! $sl !!}</td>
                                                    <td rowspan="{!! count($mainArray[$key]) !!}">{!! $key !!}</td>
                                                @endif
                                                @foreach($tas['items'] as $itm)
                                                <td>
                                                    {!! $itm->item_name !!}
                                                    @if(isset($itm->manufacturer_name) && !empty($itm->manufacturer_name))
                                                            <p>Manufacturer's Name: {{$itm->manufacturer_name}}</p>
                                                        @endif
                                                        @if(isset($itm->manufacturing_country) && !empty($itm->manufacturing_country))
                                                            <p>Manufacturing Country: {{$itm->manufacturing_country}}</p>
                                                        @endif
                                                        @if(isset($itm->country_of_origin) && !empty($itm->country_of_origin))
                                                            <p>Country of Origin: {{$itm->country_of_origin}}</p>
                                                        @endif
                                                        @if(isset($itm->model_number) && !empty($itm->model_number))
                                                            <p>Model No: {{$itm->model_number}}</p>
                                                        @endif
                                                        @if(isset($itm->part_number) && !empty($itm->part_number))
                                                            <p>Part No: {{$itm->part_number}}</p>
                                                        @endif
                                                        @if(isset($itm->patt_number) && !empty($itm->patt_number))
                                                            <p>Patt No: {{$itm->patt_number}}</p>
                                                        @endif
                                                        @if(isset($itm->addl_item_info) && !empty($itm->addl_item_info))
                                                            <p>Addl Item Info: {{$itm->addl_item_info}}</p>
                                                        @endif
                                                        
                                                    @if(!empty($itm->main_equipment_name) || !empty($itm->main_equipment_brand) || !empty($itm->main_equipment_model)|| !empty($itm->main_equipment_additional_info))

                                                        <hr style="border: 1px solid black;" />
                                                        <h4 style="text-decoration: underline">Main Equipment Information:</h4>
                                                        @if(isset($itm->main_equipment_name) && !empty($itm->main_equipment_name))
                                                            <p>Name: {{$itm->main_equipment_name}}</p>
                                                        @endif
                                                        @if(isset($itm->main_equipment_brand) && !empty($itm->main_equipment_brand))
                                                            <p>Brand: {{$itm->main_equipment_brand}}</p>
                                                        @endif
                                                        @if(isset($itm->main_equipment_model) && !empty($itm->main_equipment_model))
                                                            <p>Model: {{$itm->main_equipment_model}}</p>
                                                        @endif
                                                        @if(isset($itm->main_equipment_additional_info) && !empty($itm->main_equipment_additional_info))
                                                            <p>Additional Info: {{$itm->main_equipment_additional_info}}</p>
                                                        @endif

                                                    @endif
                                                </td>
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

                                                @foreach($tas['supi'] as $sp)
                                                    @if(count($sp)>0 && !empty($sp[0]->unit_price) && !empty($sp[0]->quoted_quantity))
                                                        
                                                        <td>{!! ImageResizeController::custom_format($sp[0]->unit_price) !!}</td>
                                                        <td>{!! ImageResizeController::custom_format($sp[0]->unit_price*$sp[0]->quoted_quantity) !!}</td>
                                                        @else
                                                            <td colspan="2">Not participated</td>
                                                        @endif

                                                        @if(!empty($sp[0]->alternative_unit_price))
                                                            <td>{!! ImageResizeController::custom_format($sp[0]->alternative_unit_price) !!}</td>
                                                            <td>{!! ImageResizeController::custom_format($sp[0]->alternative_unit_price*$sp[0]->alternative_quoted_quantity) !!}</td>
                                                        @endif
                                                        @if($sampelQtyChck>0)
                                                        <td>{!! ImageResizeController::custom_format($sp[0]->sample_qty) !!}</td>
                                                        @endif
                                                <?php $mn++; ?>
                                                @endforeach
                                            </tr>  
                                            @endforeach
                                            <?php //echo "<pre>"; print_r($ta['supi'][0]); exit;?>
                                            
                                            @if(!empty($supTotalAmountArray)) 
                                                <tr>
                                                    <td colspan="6"></td><td class="hidden"></td>
                                                    @foreach($supTotalAmountArray[$key] as $sta)
                                                    <?php $devideData3 = explode('?',$sta); ?>
                                                        <td colspan="2" style="text-align: right;">{!! ImageResizeController::custom_format($devideData3[0]) !!}</td>
                                                        @if(!empty($devideData3[1])) 
                                                            <td colspan="2" style="text-align: right;">{!! ImageResizeController::custom_format($devideData3[1]) !!}</td>
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
                                <?php $arSlmen = 0; $sl++; $forfistSupp++;?>
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
                                <?php if(!empty(Session::get('acl')[34][19]) && !empty($tender->tender_quation_collection) && empty($tender->cst_supplier_select)){ ?>
                                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">{!! 'Generate Draft CST' !!}</button>
                                <?php } ?>     
                                </div>
                            </div>

                            {!!   Form::close() !!}


                            <div class="row" style="padding-left: 13px;">
                                <div class="col-md-12">
                                    <b>Remarks</b><br><br>
                                </div>
                            </div>
                            <div class="row">
                                <?php 
                                    $slso = 1;
                                ?>
                                @if(!empty($suppliersInfForComment))
                                    @foreach($suppliersInfForComment as $key => $sui)
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

    </div>

    <script type="text/javascript">
        $(document).ready(function(){

            $(document).on('change','.lowtoheigh',function(){
            //$('.lowtoheigh').change(function() {
                var classNme  = $(this).attr('class').split(' ');
                var mainClass = classNme[3];
                var updateCla = classNme[2];
                
                if($(this).is(":checked")){
                    if(mainClass){
                        if($('.'+mainClass).is(":checked")){
                            $('.'+updateCla).each(function(){ //iterate all listed checkbox items
                                if(!this.disabled){
                                    $(this).prop('checked', true);
                                }
                            });
                            disabledFromOrder(updateCla,1);
                        }
                    }
                }

                if($(this).is(":not(:checked)")){
                    disabledFromOrder(updateCla,2);
                    if(mainClass){
                        if($('.'+mainClass).is(":checked")){
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
                            disabledFromSupplier(updateCla1,1);
                        }
                    }
                }

                if($(this).is(":not(:checked)")){
                    disabledFromSupplier(updateCla1,2);
                    if(mainClass1){
                        if($('.'+mainClass1).is(":checked")){
                            $('.'+updateCla1).each(function(){ //iterate all listed checkbox items
                                $(this).prop('checked', false);
                            });
                        }
                    }
                }

            });

            // $('.downsupplier').change(function() {
            //     var classNme1  = $(this).attr('class').split(' ');
            //     var mainClass1 = 'downsupplier';
            //     var updateCla1 = classNme1[4];

            //     if($(this).is(":checked")){
            //         if(mainClass1){
            //             if($('.'+mainClass1).is(":checked")){
            //                 $('.'+updateCla1).each(function(){ //iterate all listed checkbox items
            //                     $(this).prop('checked', true);
            //                 });
            //                 disabledFromSupplier(updateCla1,1);
            //             }
            //         }
            //     }

            //     if($(this).is(":not(:checked)")){
            //         disabledFromSupplier(updateCla1,2);
            //         if(mainClass1){
            //             if($('.'+mainClass1).is(":checked")){
            //                 $('.'+updateCla1).each(function(){ //iterate all listed checkbox items
            //                     $(this).prop('checked', false);
            //                 });
            //             }
            //         }
            //     }

            // });

            function disabledFromOrder (nottodisable,action){
                $(".forcheck").each(function(){
                    if(action==1){
                        var classNme3  = $(this).attr('class').split(' ');
                        var disabledCl = classNme3[2];
                        if(disabledCl!=nottodisable && $(this).is(":not(:checked)") ){
                            $(this).attr('disabled', 'disabled');
                        }
                    }
                    if(action==2){
                        if($('.'+nottodisable).filter(':checked').length > 1){
                            $(this).removeAttr('disabled');
                        }
                        
                    }
                      
                });
            }

            function disabledFromSupplier (nottodisable,action){
                $(".forcheck").each(function(){
                    if(action==1){
                        var classNme3  = $(this).attr('class').split(' ');
                        var disabledCl = classNme3[2];
                        if(disabledCl!=nottodisable && $(this).is(":not(:checked)") ){
                            $(this).attr('disabled', 'disabled');
                        }
                    }
                    if(action==2){
                        if($('.'+nottodisable).filter(':checked').length > 0){
                            $(this).removeAttr('disabled');
                        }
                        
                    }
                      
                });
            }

            // $('.supplier').change(function() {
            //     var classNme = $(this).attr('class').split(' ');
            //     classNme = classNme[2];

            //     if($('input.'+classNme).filter(':checked').length == 1)
            //         $('input.'+classNme+':not(:checked)').attr('disabled', 'disabled');
            //     else
            //         $('input.'+classNme).removeAttr('disabled');

            //     $('.'+classNme).change(function(){
            //         if($('input.'+classNme).filter(':checked').length == 1)
            //             $('input.'+classNme+':not(:checked)').attr('disabled', 'disabled');
            //         else
            //             $('input.'+classNme).removeAttr('disabled');
            //     });
                
            // });

          
        });
    </script>
@stop