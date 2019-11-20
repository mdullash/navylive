<?php
use functions\OwnLibrary;
use App\Http\Controllers\ImageResizeController;
?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>CST</title>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<head>
    <style>
        @media print {
          html, body {
            width: 210mm;
            height: 297mm;
          }
        body{
            font-family: 'bangla', sans-serif;
            margin: 0;
            padding: 0;
            }
        table{
                width: 100% !important;
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
    </style>
</head>

<body class="printable-page">
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading hbuilt">
                            <h3 class="text-center">D44B view</h3>
                    </div>
                            
                        <div class="panel-body">
                        
                           <table class="table table-bordered table-striped" style="width: 100%;">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" style="width: 5px; @if($xyz>1) color: white; border: 1px solid white !important; @endif">SL#</th>
                                    <th class="text-center" style="width: 150px; @if($xyz>1) color: white; border: 1px solid white !important; @endif">{{'Items Details'}}</th>
                                    <th class="text-center" style="width: 50px; @if($xyz>1) color: white; border: 1px solid white !important; @endif">{{'Deno'}}</th>
                                    <th class="text-center" style="width: 50px; @if($xyz>1) color: white; border: 1px solid white !important; @endif">{{'Quantity'}}</th>
                                    <th class="text-center" style="width: 195px; @if($xyz>1) color: white; border: 1px solid white !important; @endif">{{'Last Purchase Info'}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php $sl=1; ?>
                                    @if(!empty($supArray)) 
                                        <tr>
                                            <td colspan="5" style=" @if($xyz>1) color: white; border: 1px solid white !important; @endif"></td>
                                            @foreach(array_slice($supArray, $startRange, $endRange) as $sui)
                                            <?php $devideData = explode('?',$sui); ?>
                                                <td @if(empty($devideData[2])) colspan="2" @else colspan="4" class="text-center" @endif>
                                                    {!! $devideData[0] !!}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endif
                    
                                    @if(!empty($supArray))
                                        <tr style="">
                                            <td colspan="5" style="border-top: 1px solid white; @if($xyz>1) color: white; border: 1px solid white !important; @endif"></td>
                                            @foreach(array_slice($supArray, $startRange, $endRange) as $suiii) 
                                            <?php $devideData4 = explode('?',$suiii); ?>
                                                @if(empty($devideData4[2]))
                                                    <td colspan="2" style="border-top: 1px solid white;"></td>
                                                @else 
                                                    <td colspan="2" style="text-align: center;">Main Offer</td>
                                                    <td colspan="2" style="text-align: center;">Alternative Offer</td>
                                                @endif
                                                
                                            @endforeach
                                        </tr>        
                                    @endif

                                     @if(!empty($supArray))
                                        <tr>
                                            <td colspan="5" style="border-top: 1px solid white; @if($xyz>1) color: white; border: 1px solid white !important; @endif"></td>
                                            @foreach(array_slice($supArray, $startRange, $endRange) as $suittt) 
                                            <?php $devideDataddd = explode('?',$suittt); ?>
                                                    <td>Unit Price</td>
                                                    <td>Total Price</td>
                                                @if(!empty($devideDataddd[2]))
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
                                                    <td style=" @if($xyz>1) color: white; border: 1px solid white !important; @endif">{!! $itm->item_name !!}</td>
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
                                                        <td>{!! ImageResizeController::custom_format($sp[0]->unit_price) !!}</td>
                                                        <td>{!! ImageResizeController::custom_format($sp[0]->unit_price*$sp[0]->quoted_quantity) !!}</td>
                                                    @else
                                                        <td colspan="2">Not participated</td>
                                                    @endif
                                                    @if(!empty($sp[0]->alternative_unit_price))
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
                                                <td colspan="2" style="text-align: right;"> @if(!empty($devideData3[0])) {!! ImageResizeController::custom_format($devideData3[0]) !!} @endif
                                                </td>
                                                    @if(!empty($devideData3[1])) 
                                                        <td colspan="2" style="text-align: right;">{!! ImageResizeController::custom_format($devideData3[1]) !!}</td>
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
                            A.  Certified that the quotations for the item have been invited from the enlisted suppliers/workshops and {!! count($supplierResult) !!} in no firms submitted quotations.<br>
                            @if($supplierResult[0]->total <= $orgInfo->purchase_limit)
                                B. The offer of "@if(!empty($supplierResult)) {!! $supplierResult[0]->suppliernametext !!} @endif" lowest bidder is recommended for purchase order.  <br>
                            @endif 

                            <br>
                            <table width="100%">
                                <tr>
                                    <td style="text-align: left;">Remarks: </td>
                                </tr>
                                <?php 
                                    $slso = 1;
                                ?>
                                @if(!empty($supplierResult))
                                    @foreach($supplierResult as $sr)
                                        <tr>
                                            <td style="text-align: left; font-weight: bolder;"><b> {!! $slso++.'. '. $sr->suppliernametext !!}</b></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">&nbsp;&nbsp;{!! $sr->comment_on_cst !!}</td>
                                        </tr>
                                    @endforeach
                                @endif

                            </table>
                            <br>
                            <table width="100%">
                                <tr>
                                    @if(!empty($firstApprovalInfo))
                                        <td style="text-align: left;">
                                            @if(!empty($firstApprovalInfo->digital_sign))
                                                <div>
                                                    <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $firstApprovalInfo->digital_sign !!}" width="10%">
                                                </div>
                                            @else
                                                <br><br><br>   
                                            @endif
                                            {!! $firstApprovalInfo->first_name.' '.$firstApprovalInfo->last_name  !!}<br>
                                            {!! $firstApprovalInfo->rank !!}<br>
                                            {!! $firstApprovalInfo->designation !!}<br>
                                        </td>
                                    @endif

                                    @if(!empty($seconApprovalInfo))
                                        <td style="text-align: left;">
                                            @if(!empty($seconApprovalInfo->digital_sign))
                                                <div>
                                                    <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $seconApprovalInfo->digital_sign !!}" width="10%">
                                                </div>
                                            @else
                                                <br><br><br>   
                                            @endif
                                            {!! $seconApprovalInfo->first_name.' '.$seconApprovalInfo->last_name  !!}<br>
                                            {!! $seconApprovalInfo->rank !!}<br>
                                            {!! $seconApprovalInfo->designation !!}<br>
                                        </td>
                                    @endif

                                    @if(!empty($thirdApprovalInfo))
                                        <td style="text-align: left;">
                                            @if(!empty($thirdApprovalInfo->digital_sign))
                                                <div>
                                                    <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $thirdApprovalInfo->digital_sign !!}" width="10%">
                                                </div>
                                            @else
                                                <br><br><br>   
                                            @endif
                                            {!! $thirdApprovalInfo->first_name.' '.$thirdApprovalInfo->last_name  !!}<br>
                                            {!! $thirdApprovalInfo->rank !!}<br>
                                            {!! $thirdApprovalInfo->designation !!}<br>
                                        </td>
                                    @endif
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

    </div><?php //exit; ?>
</body>
</html>

