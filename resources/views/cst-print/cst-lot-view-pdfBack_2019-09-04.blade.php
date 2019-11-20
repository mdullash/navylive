<?php
use functions\OwnLibrary;
use App\Http\Controllers\ImageResizeController;
?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>CST Lot view pdf {!! date('Y-m-d') !!}</title>

<head>
    <style>
        @media print {
          
        body{
            font-family: 'bangla', sans-serif;
            margin: 0;
            padding: 0;
            }
        table{
                width: 100% !important;
                font-size: 12px;
                border-collapse: collapse;
            }
        table,th,td{
            border: 1px solid black;
            font-size: 14px;
        }
        table td {
                padding: 3px;
                margin: 0;
                /*text-align: center;*/
            }
        table th {
                padding: 3px;
                margin: 0;
                text-align: center;
            }
        .panel-heading h3{
            font-size: 14px;
            margin-bottom: 5px;
            margin-top: 5px;
            text-align: center;
        }
        }
    </style>
</head>

<body class="printable-page">
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading hbuilt">
                            <h3 class="text-center">@if(!empty($suppliersInf)) @if($suppliersInf[0]->total > $orgInfo->purchase_limit) PRELIMINARY @endif @endif COMPARATIVE STATEMENT- NSSD DHAKA</h3>
                            <h3 class="text-center">{!! $tender->tender_number!!} Date: @if(!empty($tender->created_at)) {!! date('d F Y', strtotime($tender->created_at)) !!} @endif</h3>
                    </div>
                        <div class="panel-body">

                            <?php $arSlmen = 0; $sl=1;?>
                            @if(!empty($mainArray))       
                                @foreach($mainArray as $key => $ta) 

                                <?php
                                    $totalSuppCount = count($supplierResultFir[$key]);
                                    $inEachTable    = 5;
                                    $totalTable     = ceil($totalSuppCount/$inEachTable);
                                    $xyz            = 1;
                                    $startRange     = 0;
                                    $endRange       = $inEachTable;
                                    $sls = 0;
                                    $width          = ($inEachTable>=$totalSuppCount) ? ceil(900/$totalSuppCount) : ceil(900/$inEachTable);

                                    $colSpanWithAlt     = 4;
                                    $colSpanWithOutAlt  = 2;
                                    if($sampelQtyChck>0){
                                        $colSpanWithAlt = 5;
                                        $colSpanWithOutAlt = 3;
                                    }
                                ?>

                                @while($totalTable >= $xyz)
                                <div class="table-responsive">
                                    @if($xyz == 1)
                                    <table style="border:none;">
                                        <tr><td style="text-align: center; font-size: 16px; border-bottom: 1px solid white !important;">{!! $key !!}</td></tr>
                                    </table>
                                    @endif
                                    <table class="" autosize="1" style="width: 100%; @if($totalTable>1 && $xyz != $totalTable) margin-bottom: 0 !important; @endif @if($xyz != 1) border-left: 1px solid white; border-bottom: 1px solid white; border-top: 1px solid white; @endif">
                                        <thead>
                                        <tr class="center">
                                            <th class="text-center" style="width: 5px; @if($xyz>1) color: white; border: 1px solid white !important; @endif">SL#</th>
                                            <!-- <th class="text-center" style="width: 50px; @if($xyz>1) color: white; border: 1px solid white !important; @endif">Lot Name</th> -->
                                            <th class="text-center" style="width: 150px; @if($xyz>1) color: white; border: 1px solid white !important; @endif">{{'Items Details'}}</th>
                                            <th class="text-center" style="width: 50px; @if($xyz>1) color: white; border: 1px solid white !important; @endif">{{'Deno'}}</th>
                                            <th class="text-center" style="width: 50px; @if($xyz>1) color: white; border: 1px solid white !important; @endif">{{'Quantity'}}</th>
                                            <th class="text-center" style="width: 145px; @if($xyz>1) color: white; border: 1px solid white !important; @endif">{{'Remark'}}</th>

                                            @if(!empty($supplierResultFir[$key]))
                                                @foreach($supplierResultFir[$key]->slice($startRange, $endRange) as $sr)
                                                    <th class="text-center" colspan="@if(empty($sr->altr_total_price)) {!! $colSpanWithOutAlt !!} @else {!! $colSpanWithAlt !!} @endif" style="width: {!! $width.'px' !!};@if($xyz>1) border-top: 1px solid white !important; @endif">{!! OwnLibrary::numToOrdinalWord(++$sls) .' Lowest'!!}</th>
                                                @endforeach
                                            @endif
                                        </tr>
                                        </thead>

                                        <tbody>
                                            <?php //$sl=1; ?>
                                            @if(!empty($supArray[$key])) 
                                                <tr>
                                                    <td colspan="5" style="border-top: none; border-bottom: none;@if($xyz>1) border: 1px solid white !important; @endif"></td>
                                                    @foreach(array_slice($supArray[$key], $startRange, $endRange) as $sui) 
                                                    <?php $devideData = explode('?',$sui); ?>
                                                        <td @if(empty($devideData[2])) colspan="{!! $colSpanWithOutAlt !!}" @else colspan="{!! $colSpanWithAlt !!}" @endif style="text-align: center;border-bottom: 1px solid white;">
                                                            {!! $devideData[0] !!}
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endif

                                            @if(!empty($supArray[$key])) 
                                                <tr style="background-color: #f9f9f9;">
                                                    <td colspan="5" style="border-top: none; border-bottom: none;@if($xyz>1) border: 1px solid white !important; @endif"></td>
                                                    @foreach(array_slice($supArray[$key], $startRange, $endRange) as $suiii) 
                                                    <?php $devideData4 = explode('?',$suiii); ?>
                                                        @if(empty($devideData4[2]))
                                                            <td colspan="{!! $colSpanWithOutAlt !!}" style="border-top: none;"></td>
                                                        @else 
                                                            <td colspan="2" style="text-align: center;">Main Offer</td>
                                                            <td colspan="{!! $colSpanWithOutAlt !!}" style="text-align: center;">Alternative Offer</td>
                                                        @endif
                                                        
                                                    @endforeach
                                                </tr>        
                                            @endif

                                            @if(!empty($supArray[$key])) 
                                                <tr>
                                                    
                                                    <td colspan="5" style="border-top: none; border-bottom: none;@if($xyz>1) border: 1px solid white !important; @endif"></td>
                                                    
                                                    @foreach(array_slice($supArray[$key], $startRange, $endRange) as $suittt) 
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
                                                        <td style="@if($xyz>1)color: white; border: 1px solid white !important; @endif" rowspan="{!! count($mainArray[$key]) !!}">{!! $sl !!}</td>
                                                        <!-- <td style="@if($xyz>1)color: white; border: 1px solid white !important; @endif" rowspan="{!! count($mainArray[$key]) !!}">{!! $key !!}</td> -->
                                                    @endif
                                                    @foreach($tas['items'] as $itm)
                                                        <td style="@if($xyz>1)color: white; border: 1px solid white !important; @endif">
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
                                                        <td style="@if($xyz>1)color: white; border: 1px solid white !important; @endif">{!! $itm->denoName !!}</td>
                                                        <td style="@if($xyz>1)color: white; border: 1px solid white !important; @endif">{!! $itm->unit !!}</td>
                                                        <td style="@if($xyz>1)color: white; border: 1px solid white !important; @endif">
                                                            @if(!empty($itm->previsouSuppName)) {!! 'Sup: '.$itm->previsouSuppName !!} @endif
                                                            @if(!empty($itm->previsouUnitPrice)) {!! ' UP: '.$itm->previsouUnitPrice !!} @endif
                                                            @if(!empty($itm->previousDates)) {!! ' Date: '.$itm->previousDates !!} @endif
                                                        </td>
                                                    @endforeach
                                                

                                                @foreach(array_slice($tas['supi'], $startRange, $endRange) as $sp)
                                                    @if(count($sp)>0)
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
                                                    <td colspan="5" style="border-top: none; border-bottom: none;@if($xyz>1) border: 1px solid white !important; @endif"></td>
                                                    @foreach(array_slice($supTotalAmountArray[$key],$startRange, $endRange) as $sta)
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
                                <?php $xyz++; $startRange += $endRange; //$endRange+=$inEachTable;?>
                                @endwhile

                                <?php $arSlmen = 0; $sl++; ?>
                                @endforeach
                            @endif

                            <br>
                            Remarks: <br>
                            <ol type="A">
                                <li>
                                    Certified that the quotations for the item have been invited from the enlisted suppliers/workshops and {!! count($suppliersInf) !!} in no firms submitted quotations.
                                </li>
                                <?php
                                    $selectedPoSup = $suppliersInf->where('recommended_as_po','=',1)->first(); 
                                ?>
                                @if(empty($selectedPoSup))
                                    @if($suppliersInf[0]->total <= $orgInfo->purchase_limit)
                                    <li> The offer of "@if(!empty($suppliersInf)) {!! $suppliersInf[0]->suppliernametext !!} @endif" lowest bidder is recommended for purchase order.</li>
                                    @endif
                                @else
                                    @if($selectedPoSup->total <= $orgInfo->purchase_limit)
                                    <li> The offer of " {!! $selectedPoSup->suppliernametext !!}" @if($selectedPoSup->id == $suppliersInf[0]->id) lowest bidder @endif is recommended for purchase order.</li>
                                    @endif
                                @endif

                                 @if(!empty($suppliersInf))
                                    @foreach($suppliersInf as $sr)
                                            <li style="text-align: left; font-weight: bolder;">
                                                @if(!empty($sr->comment_on_cst) || !empty($sr->comnt_on_col_qut_supplier))
                                                   <b> {!! $sr->suppliernametext !!}</b> - @if(!empty($sr->comment_on_cst)) {{ $sr->comment_on_cst }} @else {!! $sr->comnt_on_col_qut_supplier !!} @endif
                                                @endif
                                            </li>
                                    @endforeach
                                @endif
                            </ol>                                       


                            <table style="width: 100%;border: none;">
                                <tr>
                                    @if(!empty($firstApprovalInfo))
                                        <td style="text-align: left;width: 32%;border: none;">
                                            @if(!empty($firstApprovalInfo->digital_sign))
                                                <div>
                                                    <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $firstApprovalInfo->digital_sign !!}" width="10%">
                                                </div>
                                            @else
                                                <br><br><br>   
                                            @endif
                                            {!! $firstApprovalInfo->first_name.' '.$firstApprovalInfo->last_name  !!}<br>
                                            {!! $firstApprovalInfo->rank !!}<br>
                                            Member <br />
                                            @if(!empty($tender->send_to_nhq))
                                                Tender
                                                @if($tender->send_to_nhq==1)
                                                 Evaluation
                                                 @else
                                                 Opening
                                                @endif
                                                Committee
                                            @endif
                                            {{-- {!! $firstApprovalInfo->designation !!}<br> --}}
                                        </td>
                                    @endif

                                    @if(!empty($seconApprovalInfo))
                                        <td style="text-align: left;width: 32%;padding-left: 100px;border: none;">
                                            @if(!empty($seconApprovalInfo->digital_sign))
                                                <div>
                                                    <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $seconApprovalInfo->digital_sign !!}" width="10%">
                                                </div>
                                            @else
                                                <br><br><br>   
                                            @endif
                                            {!! $seconApprovalInfo->first_name.' '.$seconApprovalInfo->last_name  !!}<br>
                                            {!! $seconApprovalInfo->rank !!}<br>
                                            Member <br />
                                             @if(!empty($tender->send_to_nhq))
                                                Tender
                                                @if($tender->send_to_nhq==1)
                                                 Evaluation
                                                 @else
                                                 Opening
                                                @endif
                                                Committee
                                            @endif
                                            {{-- {!! $seconApprovalInfo->designation !!}<br> --}}
                                        </td>
                                    @endif

                                    @if(!empty($thirdApprovalInfo))
                                        <td style="text-align: left;width: 32%;padding-left: 150px;border: none;">
                                            @if(!empty($thirdApprovalInfo->digital_sign))
                                                <div>
                                                    <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $thirdApprovalInfo->digital_sign !!}" width="10%">
                                                </div>
                                            @else
                                                <br><br><br>   
                                            @endif
                                            {!! $thirdApprovalInfo->first_name.' '.$thirdApprovalInfo->last_name  !!}<br>
                                            {!! $thirdApprovalInfo->rank !!}<br>
                                            President
                                            <br />
                                             @if(!empty($tender->send_to_nhq))
                                                Tender
                                                @if($tender->send_to_nhq==1)
                                                 Evaluation
                                                 @else
                                                 Opening
                                                @endif
                                                Committee
                                            @endif
                                            {{-- {!! $thirdApprovalInfo->designation !!}<br> --}}
                                        </td>
                                    @endif
                                </tr>
                            </table>

                        </div>
                </div>
            </div>

        </div>
    </div>
<?php //exit; ?>
</body>
</html>
