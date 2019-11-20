<?php
use functions\OwnLibrary;
use App\Http\Controllers\ImageResizeController;
?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>@if(!empty($supplierResult)) @if($supplierResult[0]->total > $orgInfo->purchase_limit) PRELIMINARY @endif @endif COMPARATIVE STATEMENT- NSSD DHAKA</title>

<head>
    <style>
        @media print {
        body{
            font-family: 'bangla', sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            }
        table{
                width: 100% !important;
                border-collapse: collapse;
            }
        table,th,td{
                border: 1px solid black;
                font-size: 14px;
            }
        table td {
                padding: 3px;
                margin: 0;
                text-align: center;
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
            }
        }
        @page {
            header: page-header;
            footer: page-footer;
            margin-top: 100px;
            margin-bottom: 350px;
        }

        @media print {
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>

<body class="printable-page">

    <htmlpageheader name="page-header">
        <div class="panel-heading">
                <h3 style="text-align: center;">@if(!empty($supplierResult)) @if($supplierResult[0]->total > $orgInfo->purchase_limit) PRELIMINARY @endif @endif COMPARATIVE STATEMENT- {!! $orgInfo->name !!}</h3>
                <h3 style="text-align: center;">{!! $tender->tender_number!!} Date: @if(!empty($mainTenderInfo->valid_date_from)) {!! date('d F Y', strtotime($mainTenderInfo->valid_date_from)) !!} @endif</h3>
        </div>
    </htmlpageheader>

    <htmlpagefooter name="page-footer">
        <table style="width: 100%;border: none;margin-bottom: 30px;">
                               <tr>
                                    @if(!empty($firstApprovalInfo))
                                        <td style="text-align: left;width: 32%;border: none;">
                                            @if(!empty($firstApprovalInfo->digital_sign))
                                                <div>
                                                    <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $firstApprovalInfo->digital_sign !!}" style="height: 80px; width: 80px;">
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
                                        </td>
                                    @endif

                                    @if(!empty($seconApprovalInfo))
                                        <td style="text-align: left;width: 32%;padding-left: 100px;border: none;">
                                            @if(!empty($seconApprovalInfo->digital_sign))
                                                <div>
                                                    <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $seconApprovalInfo->digital_sign !!}" style="height: 80px; width: 80px;">
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
                                        </td>
                                    @endif

                                    @if(!empty($thirdApprovalInfo))
                                        <td style="text-align: left;width: 32%;padding-left: 150px;border: none;">
                                            @if(!empty($thirdApprovalInfo->digital_sign))
                                                <div>
                                                    <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $thirdApprovalInfo->digital_sign !!}" style="height: 80px; width: 80px;">
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
                                        </td>
                                    @endif
                                </tr>
                                <tr>
                                    <td style="text-align: left;width: 32%;border: none;">
                                    </td>
                                    
                                    @if(!empty($draftcstApverInfo))
                                        <td style="text-align: left;width: 32%;padding-left: 100px;border: none;">
                                            @if(!empty($draftcstApverInfo->digital_sign))
                                                <p>APPROVED</p>
                                                <div>
                                                    <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $draftcstApverInfo->digital_sign !!}" style="height: 80px; width: 80px;">
                                                </div>
                                            @else
                                                <br><br><br>
                                                <p>APPROVED</p>
                                            @endif
                                            {!! $draftcstApverInfo->first_name.' '.$draftcstApverInfo->last_name  !!}<br>
                                            {!! $draftcstApverInfo->rank !!}
                                            <br>
                                            {!! 'Officer-In-Charge' !!}
                                            <br />
                                            {!! 'NSSD Dhakaaa' !!}
                                        </td>
                                    @endif
                                </tr>
                            </table>
        <table style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic; border: none;" width="100%">
            <tbody>
                <tr>
                    <td width="49%" style="border: none !important; text-align: left;"><span style="font-weight: bold; font-style: italic; border: none !important;">{!! date('d-m-Y h:i') !!}</span></td>
                    <td style="font-weight: bold; font-style: italic; border: none !important;" align="right" width="49%">Page {PAGENO} of {nbpg}</td>
                </tr>
            </tbody>
        </table>
    </htmlpagefooter>
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    {{-- <div class="panel-heading hbuilt">
                            <h3 style="text-align: center;">@if(!empty($supplierResult)) @if($supplierResult[0]->total > $orgInfo->purchase_limit) PRELIMINARY @endif @endif COMPARATIVE STATEMENT- {!! $orgInfo->name !!}</h3>
                            <h3 style="text-align: center;">{!! $tender->tender_number!!} Date: @if(!empty($mainTenderInfo->valid_date_from)) {!! date('d F Y', strtotime($mainTenderInfo->valid_date_from)) !!} @endif</h3>
                    </div> --}}
                            
                        <?php
                            $totalSuppCount = count($supplierResult);
                            $inEachTable    = 5;
                            $totalTable     = ceil($totalSuppCount/$inEachTable);
                            $xyz            = 1;
                            $startRange     = 0;
                            $endRange       = $inEachTable;
                            $sls = 0;

                            $a   =0;
                            $colSpanWithAlt     = 4;
                            $colSpanWithOutAlt  = 2;
                            $smploneortwo       = 0;
                            if($sampelQtyChck>0){
                                $smploneortwo   += 1;
                            }
                            $colSpanWithAlt = 4+$smploneortwo;
                            $colSpanWithOutAlt = 2+$smploneortwo;
                        ?>
                        
                        <div class="panel-body">
                        @while($totalTable >= $xyz)
                           <table style="width: 100%; @if($totalTable>1 && $xyz != $totalTable) margin-bottom: 0 !important; @endif @if($xyz != 1) border-left: 1px solid white; border-bottom: 1px solid white; border-top: 1px solid white; @endif">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" style="width: 5px; @if($xyz>1) color: white; border: 1px solid white !important; @endif">SL#</th>
                                    <th class="text-center" style="width: 150px; @if($xyz>1) color: white; border: 1px solid white !important; @endif">{{'Items Details'}}</th>
                                    <th class="text-center" style="width: 50px; @if($xyz>1) color: white; border: 1px solid white !important; @endif">{{'Deno'}}</th>
                                    <th class="text-center" style="width: 50px; @if($xyz>1) color: white; border: 1px solid white !important; @endif">{{'Quantity'}}</th>
                                    <th class="text-center" style="width: 195px; @if($xyz>1) color: white; border: 1px solid white !important; @endif">{{'Last Purchase Info'}}</th>
                                    <?php //$sls = 0; ?>
                                    @if(!empty($supplierResult))
                                        @foreach($supplierResult->slice($startRange, $endRange) as $sr)
                                            <th class="text-center" colspan="@if(empty($sr->alternative_total)) {!! $colSpanWithOutAlt !!} @else <?php if($altSampelQtyChck>0) $a=1 ;?> {!! $colSpanWithAlt+$a !!} @endif">{!! OwnLibrary::numToOrdinalWord(++$sls) .' Lowest'!!}</th>
                                        @endforeach
                                    @endif
                                    
                                </tr>
                                </thead>
                                <tbody>
                                    <?php $sl=1; ?>
                                    @if(!empty($supArray)) 
                                        <tr>
                                            <td colspan="5" style="border: none;"></td>
                                            @foreach(array_slice($supArray, $startRange, $endRange) as $sui)
                                            <?php $devideData = explode('?',$sui); ?>
                                                <td @if(empty($devideData[2])) colspan="{!! $colSpanWithOutAlt !!}" @else <?php if($altSampelQtyChck>0) $a=1 ;?> colspan="{!! $colSpanWithAlt+$a !!}" class="text-center" @endif style="text-align: center;border-bottom: 1px solid white;">
                                                    {!! $devideData[0] !!}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endif
                    
                                    @if(!empty($supArray))
                                        <tr style="">
                                            <td colspan="5" style="border: none;"></td>
                                            @foreach(array_slice($supArray, $startRange, $endRange) as $suiii) 
                                            <?php $devideData4 = explode('?',$suiii); ?>
                                                @if(empty($devideData4[2]))
                                                    <td colspan="{!! $colSpanWithOutAlt !!}" style="border-top: 1px solid white;"></td>
                                                @else 
                                                    <td colspan="@if($sampelQtyChck>0) 3 @else 2 @endif" style="text-align: center;">Main Offer</td>
                                                    <td colspan="@if($altSampelQtyChck>0) 3 @else 2 @endif" style="text-align: center;">Alternative Offer</td>
                                                @endif
                                                
                                            @endforeach
                                        </tr>        
                                    @endif

                                     @if(!empty($supArray))
                                        <tr>
                                            <td colspan="5" style="border: none;"></td>
                                            @foreach(array_slice($supArray, $startRange, $endRange) as $suittt) 
                                            <?php $devideDataddd = explode('?',$suittt); ?>
                                                    @if($sampelQtyChck>0)
                                                        <td>Sample Qty</td>
                                                    @endif
                                                    <td>Unit Price</td>
                                                    <td>Total Price</td>
                                                @if(!empty($devideDataddd[2]))
                                                    @if($altSampelQtyChck>0)
                                                        <td>Sample Qty</td>
                                                    @endif
                                                    <td>Unit Price</td>
                                                    <td>Total Price</td>
                                                @endif
                                                
                                            @endforeach
                                        </tr>
                                    @endif

                                    @if(!empty($targetArray))
                                        @foreach($targetArray as $ta)
                                            <tr>
                                                @foreach($ta['items'] as $itm)
                                                    <td style=" @if($xyz>1) color: white; border: 1px solid white !important; @endif">{!! $sl++ !!}</td>
                                                    <td style="text-align:left; @if($xyz>1) color: white; border: 1px solid white !important; @endif">
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
                                                        @if(isset($itm->brand) && !empty($itm->brand))
                                                            <p>Brand: {{$itm->brand}}</p>
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
                                                    <td style=" @if($xyz>1) color: white; border: 1px solid white !important; @endif">{!! $itm->denoName !!}</td>
                                                    <td style=" @if($xyz>1) color: white; border: 1px solid white !important; @endif">{!! $itm->unit !!}</td>
                                                    <td style=" @if($xyz>1) color: white; border: 1px solid white !important; @endif">
                                                        @if(!empty($itm->previsouSuppName)) {!! 'Sup: '.$itm->previsouSuppName !!} @endif
                                                        @if(!empty($itm->previsouUnitPrice)) {!! ' UP: '.$itm->previsouUnitPrice !!} @endif
                                                        @if(!empty($itm->previousDates)) {!! ' Date: '.$itm->previousDates !!} @endif

                                                        @if(empty($itm->previsouSuppName) && empty($itm->previsouUnitPrice) && empty($itm->previousDates))
                                                            NA
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <?php //echo "<pre>"; print_r($ta['supi'][0]); exit;?>
                                                @foreach(array_slice($ta['supi'], $startRange, $endRange) as $sp)
                                                    @if(count($sp)>0 && !empty($sp[0]->unit_price) && !empty($sp[0]->quoted_quantity))
                                                        @if($sampelQtyChck>0)
                                                            <td>{!! $sp[0]->sample_qty !!}</td>
                                                        @endif  
                                                        <td>{!! ImageResizeController::custom_format($sp[0]->unit_price) !!}</td>
                                                        <td>{!! ImageResizeController::custom_format($sp[0]->unit_price*$sp[0]->quoted_quantity) !!}</td>
                                                    @else
                                                        <td colspan="2">Not participated</td>
                                                    @endif
                                                    @if(!empty($sp[0]->alternative_unit_price))
                                                        @if($altSampelQtyChck>0)
                                                            <td>{!! ImageResizeController::custom_format($sp[0]->alt_sample_qty) !!}</td>
                                                        @endif
                                                        <td>{!! ImageResizeController::custom_format($sp[0]->alternative_unit_price) !!}</td>
                                                        <td>{!! ImageResizeController::custom_format($sp[0]->alternative_unit_price*$sp[0]->alternative_quoted_quantity) !!}</td>
                                                    @endif 
                                                    
                                                @endforeach
                                                
                                            </tr>   
                                        @endforeach

                                        @if(!empty($supTotalAmountArray)) 
                                            <tr>
                                                <td colspan="5" style="@if($xyz>1) color: white; border: 1px solid white !important; border-bottom: 1px solid white; @endif"></td>
                                                @foreach(array_slice($supTotalAmountArray,$startRange, $endRange) as $sta)
                                                <?php $devideData3 = explode('?',$sta); ?>
                                                <td colspan="@if($sampelQtyChck>0) 3 @else 2 @endif" style="text-align: right;"> @if(!empty($devideData3[0])) {!! ImageResizeController::custom_format($devideData3[0]) !!} @endif
                                                </td>
                                                    @if(!empty($devideData3[1])) 
                                                        <td colspan="@if($altSampelQtyChck>0) 3 @else 2 @endif" style="text-align: right;">{!! ImageResizeController::custom_format($devideData3[1]) !!}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endif
                        

                                    @endif
                                    
                                </tbody>
                            </table><!---/table-responsive-->
                            <?php $xyz++; $startRange += $endRange; ?>
                            @endwhile

                            <br>
                            Remarks: <br>                                     
                            <ol type="A">
                                <li>
                                    Certified that the quotations for the item have been invited from the enlisted suppliers/workshops and {!! count($supplierResult) !!} in no firms submitted quotations.
                                </li>
                                <?php
                                    $selectedPoSup = $supplierResult->where('recommended_as_po','=',1)->first(); 
                                ?>
                                @if(!empty($selectedPoSup))
                                    @if($selectedPoSup->recommended_as_po != 0)
                                        @if(empty($selectedPoSup))
                                            @if($supplierResult[0]->total <= $orgInfo->purchase_limit)
                                            <li> The offer of "@if(!empty($supplierResult)) {!! $supplierResult[0]->suppliernametext !!} @endif" lowest bidder is recommended for purchase order.</li>
                                            @endif
                                        @else
                                            @if($selectedPoSup->total <= $orgInfo->purchase_limit)
                                            <li> The offer of " {!! $selectedPoSup->suppliernametext !!}" @if($selectedPoSup->id == $supplierResult[0]->id) lowest bidder @endif is recommended for purchase order.</li>
                                            @endif
                                        @endif
                                    @endif
                                @endif

                                 @if(!empty($suppliersInfForComment))
                                    @foreach($suppliersInfForComment as $sr)
                                        @if(!empty($sr->comment_on_cst))
                                        <li style="text-align: left; font-weight: bolder;">
                                           <b> {!! $sr->suppliernametext !!}</b> - {{ $sr->comment_on_cst }}
                                        </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ol> 

                            {{-- <table style="width: 100%;border: none;">
                               <tr>
                                    @if(!empty($firstApprovalInfo))
                                        <td style="text-align: left;width: 32%;border: none;">
                                            @if(!empty($firstApprovalInfo->digital_sign))
                                                <div>
                                                    <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $firstApprovalInfo->digital_sign !!}" style="height: 80px; width: 80px;">
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
                                        </td>
                                    @endif

                                    @if(!empty($seconApprovalInfo))
                                        <td style="text-align: left;width: 32%;padding-left: 100px;border: none;">
                                            @if(!empty($seconApprovalInfo->digital_sign))
                                                <div>
                                                    <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $seconApprovalInfo->digital_sign !!}" style="height: 80px; width: 80px;">
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
                                        </td>
                                    @endif

                                    @if(!empty($thirdApprovalInfo))
                                        <td style="text-align: left;width: 32%;padding-left: 150px;border: none;">
                                            @if(!empty($thirdApprovalInfo->digital_sign))
                                                <div>
                                                    <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $thirdApprovalInfo->digital_sign !!}" style="height: 80px; width: 80px;">
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
                                        </td>
                                    @endif
                                </tr>
                                <tr>
                                    <td style="text-align: left;width: 32%;border: none;">
                                    </td>
                                    
                                    @if(!empty($draftcstApverInfo))
                                        <td style="text-align: left;width: 32%;padding-left: 100px;border: none;">
                                            @if(!empty($draftcstApverInfo->digital_sign))
                                                <p>APPROVED</p>
                                                <div>
                                                    <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $draftcstApverInfo->digital_sign !!}" style="height: 80px; width: 80px;">
                                                </div>
                                            @else
                                                <br><br><br>
                                                <p>APPROVED</p>
                                            @endif
                                            {!! $draftcstApverInfo->first_name.' '.$draftcstApverInfo->last_name  !!}<br>
                                            {!! $draftcstApverInfo->rank !!}
                                            <br>
                                            {!! 'Officer-In-Charge' !!}
                                            <br />
                                            {!! 'NSSD Dhaka' !!}
                                        </td>
                                    @endif
                                </tr>
                            </table> --}}

                        </div>
                    </div>
                </div>
            </div>

    </div><?php //exit; ?>
</body>
</html>

