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
                padding: 5px;
                margin: 0;
                text-align: center;
            }
        table th {
                padding: 5px;
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
                            <h3 class="text-center">@if(array_sum($supTotalAmountArray) > 40000) PRELIMINARY @endif COMPARATIVE STATEMENT- NSSD DHAKA</h3>
                            <h3 class="text-center">{!! $tender->tender_number!!} Date: @if(!empty($tender->created_at)) {!! date('d F Y', strtotime($tender->created_at)) !!} @endif</h3>
                    </div>
                        <div class="panel-body">

                           <table class="table table-bordered table-striped" style="width: 100%;">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Items Details'}}</th>
                                    <!-- <th class="text-center">{{'Machinery / Manufacturer'}}</th> -->
                                    <th class="text-center">{{'Deno'}}</th>
                                    <th class="text-center">{{'Quantity'}}</th>
                                    <th class="text-center">{{'Remark'}}</th>
                                    <?php $sls = 0; ?>
                                    @if(!empty($supplierResult))
                                        @foreach($supplierResult as $sr)
                                            <th class="text-center" colspan="6">{!! OwnLibrary::numToOrdinalWord(++$sls) .' Lowest'!!}</th>
                                        @endforeach
                                    @endif
                                    
                                </tr>
                                </thead>
                                <tbody>
                                    <?php $sl=1; ?>
                                    @if(!empty($supArray)) 
                                        <tr>
                                            <td></td><td></td><td></td><td></td><td></td><!-- <td></td> -->
                                            @foreach($supArray as $sui)
                                            <?php $devideData = explode('?',$sui); ?>
                                                <td colspan="6">
                                                    <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                        <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                            <input class="activity_1 activitycell" type="checkbox" id="cst_draft_sup_id{!! $devideData[0] !!}" name="cst_draft_sup_id[]" value="{!! $devideData[1] !!}" @if(in_array($devideData[1], $supplierAllreadySelected)) checked @endif>
                                                            <label for="cst_draft_sup_id{!! $devideData[0] !!}">{!! $devideData[0] !!}</label>
                                                        </div>
                                                    </div>
                                                    
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endif
                                    @if(!empty($targetArray))
                                        
                                        @foreach($targetArray as $ta)
                                        
                                            <tr>
                                                @foreach($ta['items'] as $itm)
                                                    <td>{!! $sl++ !!}</td>
                                                    <td>{!! $itm->item_name !!}</td>
                                                    <!-- <td>{!! $itm->manufacturing_country !!}</td> -->
                                                    <td>{!! $itm->denoName !!}</td>
                                                    <td>{!! $itm->unit !!}</td>
                                                    <td>
                                                        @if(!empty($itm->previsouSuppName)) {!! 'Sup: '.$itm->previsouSuppName !!} @endif
                                                        @if(!empty($itm->previsouUnitPrice)) {!! ' UP: '.$itm->previsouUnitPrice !!} @endif
                                                        @if(!empty($itm->previousDates)) {!! ' Date: '.$itm->previousDates !!} @endif
                                                    </td>
                                                @endforeach
                                                <?php //echo "<pre>"; print_r($ta['supi'][0]); exit;?>
                                                @foreach($ta['supi'] as $sp)
                                                    @if(count($sp)>0)
                                                        <!-- <td>L.P.P {!! $sp[0]->last_unti_price !!}</td> -->
                                                        <td>{!! '@TK' !!}</td>
                                                        <td>{!! ImageResizeController::custom_format($sp[0]->unit_price) !!}</td>
                                                        <td>{!! 'x' !!}</td>
                                                        <td>{!! '=' !!}</td>
                                                        <td>{!! $itm->unit !!}</td>
                                                        <td>{!! ImageResizeController::custom_format(($sp[0]->unit_price-$sp[0]->discount_amount)*$itm->unit) !!}</td>
                                                    @else
                                                        <td colspan="6">Not participated</td>
                                                    @endif   
                                                @endforeach
                                                
                                            </tr>   
                                        @endforeach

                                        @if(!empty($supTotalAmountArray)) 
                                            <tr>
                                                <td></td><td></td><td></td><td></td><td></td><!-- <td></td> -->
                                                @foreach($supTotalAmountArray as $sta)
                                                    <td colspan="6" style="text-align: right;">{!! ImageResizeController::custom_format($sta) !!}</td>
                                                @endforeach
                                            </tr>
                                        @endif
                        

                                    @endif
                                    
                                </tbody>
                            </table><!---/table-responsive-->

                            <br>
                            Remarks: <br>                                     
                            A.  Certified that the quotations for the item have been invited from the enlisted suppliers/workshops and 25 in no firms submitted quotations.<br> 
                            @if(array_sum($supTotalAmountArray) < 40001)
                                B. The offer of "NK Trade International" lowest bidder is recommended for purchase order.  <br>
                            @endif

                            <br>
                            <table>
                                <tr>
                                    <td>
                                        M K UDDIN <br>
                                        MCPO(L) <br>
                                        Member <br>
                                        @if(array_sum($supTotalAmountArray) > 40000)
                                            Tender Opening Committee
                                            @else
                                            Tender Evaluation Committee
                                        @endif
                                    </td>
                                    <td>
                                        M MAJIBUR RAHMAN <br>
                                        H S Lt BN <br>
                                        Member <br>
                                        @if(array_sum($supTotalAmountArray) > 40000)
                                            Tender Opening Committee
                                            @else
                                            Tender Evaluation Committee
                                        @endif
                                    </td>
                                    <td>
                                        KH. ZAKIR HOSSAIN <br>
                                        Lt Cdr BN <br>
                                        President <br>
                                        @if(array_sum($supTotalAmountArray) > 40000)
                                            Tender Opening Committee
                                            @else
                                            Tender Evaluation Committee
                                        @endif
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

    </div>
</body>
</html>

