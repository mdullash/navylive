<?php
use functions\OwnLibrary;
use App\Http\Controllers\ImageResizeController;
?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>Draft CST</title>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

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
                            <h3 class="text-center">@if(!empty($suppliersInf)) @if($suppliersInf[0]->total > $orgInfo->purchase_limit) PRELIMINARY @endif @endif COMPARATIVE STATEMENT- NSSD DHAKA</h3>
                            <h3 class="text-center">{!! $tender->tender_number!!} Date: @if(!empty($tender->created_at)) {!! date('d F Y', strtotime($tender->created_at)) !!} @endif</h3>
                    </div>
                        <div class="panel-body">

                            <?php $arSlmen = 0; ?>
                            @if(!empty($mainArray))       
                                @foreach($mainArray as $key => $ta) 

                                <?php
                                    $totalSuppCount = count($supplierResultFir[$key]);
                                    $inEachTable    = 1;
                                    $totalTable     = ceil($totalSuppCount/$inEachTable);
                                    $xyz            = 1;
                                    $startRange     = 0;
                                    $endRange       = $inEachTable;
                                    $sls = 0;
                                ?>

                                @while($totalTable >= $xyz)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped middle-align" autosize="1" style=" @if($totalTable>1 && $xyz != $totalTable) margin-bottom: 0 !important; @endif @if($xyz != 1) border-left: none; border-bottom: none; @endif">
                                        <thead>
                                        <tr class="center">
                                            @if($xyz<2)
                                                <th class="text-center" style="width: 5px;">SL#</th>
                                                <th class="text-center" style="width: 50px;">Lot Name</th>
                                                <th class="text-center" style="width: 150px;">{{'Items Details'}}</th>
                                                <th class="text-center" style="width: 50px;">{{'Deno'}}</th>
                                                <th class="text-center" style="width: 50px;">{{'Quantity'}}</th>
                                                <th class="text-center" style="width: 145px;">{{'Remark'}}</th>
                                            @else
                                                <th class="text-center" colspan="5" style="border-bottom: none; border-left: none; background-color: white; border-top: 1px solid white; width: 450px;"></th>
                                            @endif
                                            <?php //echo "<pre>"; print_r($supplierResultFir[$key]->slice(1, 1)); exit; ?>
                                            @if(!empty($supplierResultFir[$key]))
                                                @foreach($supplierResultFir[$key]->slice($startRange, $endRange) as $sr)
                                                    <th class="text-center" colspan="@if(empty($sr->altr_total_price)) 2 @else 4 @endif" width="20">{!! OwnLibrary::numToOrdinalWord(++$sls) .' Lowest'!!}</th>
                                                @endforeach
                                            @endif
                                        </tr>
                                        </thead>

                                        <tbody>
                                            <?php $sl=1; ?>
                                            @if(!empty($supArray[$key])) 
                                                <tr>
                                                    @if($xyz<2)
                                                        <td colspan="6" style="border-bottom: 1px solid white;"></td>
                                                    @else
                                                        <td colspan="5" style="border-bottom: 1px solid white; border-top: none; border-left: none; background-color: white;"></td>
                                                    @endif
                                                    @foreach(array_slice($supArray[$key], $startRange, $endRange) as $sui) 
                                                    <?php $devideData = explode('?',$sui); ?>
                                                        <td @if(empty($devideData[2])) colspan="2" @else colspan="4" class="text-center" @endif style="text-align: center;border-bottom: 1px solid white;">
                                                            <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                                <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                                    <input class="activity_1 activitycell" type="checkbox" id="cst_draft_sup_id{!! $devideData[1].'&'.$devideData[4].'&'.$devideData[5] !!}" name="cst_draft_sup_id[]" value="{!! $devideData[1].'&'.$devideData[4].'&'.$devideData[5] !!}" @if($devideData[3]==1) checked @endif>
                                                                    <label for="cst_draft_sup_id{!! $devideData[1].'&'.$devideData[4].'&'.$devideData[5] !!}">{!! $devideData[0] !!}</label>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endif

                                            @if(!empty($supArray[$key])) 
                                                <tr style="background-color: #f9f9f9;">
                                                    @if($xyz<2)
                                                        <td colspan="6" style="border-top: none;"></td>
                                                    @else
                                                        <td colspan="5" style="border-top: none; border-bottom: none; border-left: none; background-color: white;"></td>
                                                    @endif
                                                    @foreach(array_slice($supArray[$key], $startRange, $endRange) as $suiii) 
                                                    <?php $devideData4 = explode('?',$suiii); ?>
                                                        @if(empty($devideData4[2]))
                                                            <td colspan="2" style="border-top: none;"></td>
                                                        @else 
                                                            <td colspan="2" style="text-align: center;">Main Offer</td>
                                                            <td colspan="2" style="text-align: center;">Alternative Offer</td>
                                                        @endif
                                                        
                                                    @endforeach
                                                </tr>        
                                            @endif

                                            @if(!empty($supArray[$key])) 
                                                <tr>
                                                    @if($xyz<2)
                                                        <td colspan="6" style="border-top: none;"></td>
                                                    @else
                                                        <td colspan="5" style="border-top: none; border-bottom: none; border-left: none; background-color: white;"></td>
                                                    @endif
                                                    @foreach(array_slice($supArray[$key], $startRange, $endRange) as $suittt) 
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

                                            <?php $mn = 0; ?>
                                            @foreach($ta as $tas)
                                            <?php //echo "<pre>"; print_r(count($mainArray[$key])); exit; ?>
                                            <tr>
                                                @if($xyz<2)
                                                    @if($mn == 0)
                                                        <td rowspan="{!! count($mainArray[$key]) !!}">{!! $sl !!}</td>
                                                        <td rowspan="{!! count($mainArray[$key]) !!}">{!! $key !!}</td>
                                                    @endif
                                                    @foreach($tas['items'] as $itm)
                                                        <td>{!! $itm->item_name !!}</td>
                                                        <td>{!! $itm->denoName !!}</td>
                                                        <td>{!! $itm->unit !!}</td>
                                                        <td>
                                                            @if(!empty($itm->previsouSuppName)) {!! 'Sup: '.$itm->previsouSuppName !!} @endif
                                                            @if(!empty($itm->previsouUnitPrice)) {!! ' UP: '.$itm->previsouUnitPrice !!} @endif
                                                            @if(!empty($itm->previousDates)) {!! ' Date: '.$itm->previousDates !!} @endif
                                                        </td>
                                                    @endforeach
                                                @else
                                                    <td colspan="5" style="border-top: none; border-bottom: none; border-left: none; background-color: white;"></td>
                                                @endif 

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
                                                <?php $mn++; ?>
                                                @endforeach
                                            </tr>  
                                            @endforeach
                                            <?php //echo "<pre>"; print_r($ta['supi'][0]); exit;?>
                                            
                                            @if(!empty($supTotalAmountArray)) 
                                                <tr>
                                                    @if($xyz<2)
                                                        <td colspan="6" style="border-top: none;"></td>
                                                    @else
                                                        <td colspan="5" style="border-top: none; border-bottom: none; border-left: none; background-color: white;"></td>
                                                    @endif
                                                    @foreach(array_slice($supTotalAmountArray[$key],$startRange, $endRange) as $sta)
                                                    <?php $devideData3 = explode('?',$sta); ?>
                                                        <td colspan="2" style="text-align: right;">{!! ImageResizeController::custom_format($devideData3[0]) !!}</td>
                                                        @if(!empty($devideData3[1])) 
                                                            <td colspan="2" style="text-align: right;">{!! ImageResizeController::custom_format($devideData3[1]) !!}</td>
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

                            Remarks: <br>                                     
                            A.  Certified that the quotations for the item have been invited from the enlisted suppliers/workshops and {!! count($suppliersInf) !!} in no firms submitted quotations.<br>
                            @if($suppliersInf[0]->total <= $orgInfo->purchase_limit)
                                B. The offer of "@if(!empty($suppliersInf)) {!! $suppliersInf[0]->suppliernametext !!} @endif" lowest bidder is recommended for purchase order.  <br>
                            @endif                                          


                            <br>
                            <table>
                                <tr>
                                    <td>
                                        KAZI AMDADUL HAQUE <br>
                                        MCPO(L) <br>
                                        Member <br>
                                        @if(array_sum($supTotalAmountArray) > 40000)
                                            Tender Opening Committee
                                            @else
                                            Tender Evaluation Committee
                                        @endif
                                    </td>
                                    <td>
                                        SAIMA SHAHID FARIHA <br>
                                        LT BN <br>
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
<?php //exit; ?>
</body>
</html>

