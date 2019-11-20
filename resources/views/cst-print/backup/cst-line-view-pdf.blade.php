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
        table {page-break-inside: avoid;}
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

                          
                            <!-- <p class="text-center">@if(array_sum($supTotalAmountArray) > 40000) PRELIMINARY @endif COMPARATIVE STATEMENT- NSSD DHAKA <br>
                            {!! $tender->tender_number!!} Date: @if(!empty($tender->created_at)) {!! date('d F Y', strtotime($tender->created_at)) !!} @endif</p> -->
                            <?php $sl=1; ?>
                            @if(!empty($targetArray))       
                                @foreach($targetArray as $key => $ta)

                                <!-- Newly added alalal  -->
                                <?php
                                    $totalSuppCount = count($supplierResult[$key]);
                                    $inEachTable    = 5;
                                    $totalTable     = ceil($totalSuppCount/$inEachTable);
                                    $xyz            = 1;
                                    $startRange     = 0;
                                    $endRange       = $inEachTable;
                                    $sls = 0;
                                ?>

                                @while($totalTable >= $xyz)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped middle-align" autosize="1" style=" @if($totalTable>1 && $xyz != $totalTable) margin-bottom: 0 !important; @endif @if($xyz != 1) border-left: none; border-bottom: none; @endif" >
                                        <thead>
                                        <tr class="center">
                                            @if($xyz<2)
                                                <th class="text-center" style="width: 5px;">SL#</th>
                                                <th class="text-center" style="width: 150px;">{{'Items Details'}}</th>
                                                <th class="text-center" style="width: 50px;">{{'Deno'}}</th>
                                                <th class="text-center" style="width: 50px;">{{'Quantity'}}</th>
                                                <th class="text-center" style="width: 195px;">{{'Remark'}}</th>
                                            @else
                                                <th class="text-center" colspan="4" style="border-bottom: none; border-left: none; background-color: white; border-top: 1px solid white; width: 450px;"></th>
                                            @endif

                                            <?php //$sls = 0; ?>
                                            @if(!empty($supplierResult[$key]))
                                                @foreach($supplierResult[$key]->slice($startRange, $endRange) as $sr)
                                                    <th class="text-center" style="" colspan="@if(empty($sr->alternative_unit_price)) 2 @else 4 @endif">{!! OwnLibrary::numToOrdinalWord(++$sls) .' Lowest'!!}</th>
                                                @endforeach
                                            @endif
                                        </tr>
                                        </thead>

                                        <tbody>
                                            
                                            @if(!empty($supArray[$key])) 
                                                <tr>
                                                    @if($xyz<2)
                                                        <td colspan="5" style="border-bottom: 1px solid white;"></td>
                                                    @else
                                                        <td colspan="4" style="border-bottom: 1px solid white; border-top: none; border-left: none; background-color: white;"></td>
                                                    @endif
                                                    @foreach(array_slice($supArray[$key], $startRange, $endRange) as $sui) 
                                                    <?php $devideData = explode('?',$sui); ?>
                                                        <td @if(empty($devideData[2])) colspan="2" @else colspan="4" class="text-center" @endif style="text-align: center; border-bottom: 1px solid white;">
                                                            <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                                <div class=" checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                                    <input class="activity_1 activitycell hidden" type="checkbox" id="cst_draft_sup_id{!! $devideData[0].$ta['items'][0]->id !!}" name="cst_draft_sup_id[]" value="{!! $devideData[1] !!}" @if($devideData[3]==1) checked @endif>
                                                                    <label for="cst_draft_sup_id{!! $devideData[0].$ta['items'][0]->id !!}">{!! $devideData[0] !!}</label>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endif

                                            @if(!empty($supArray[$key])) 
                                                <tr style="background-color: #f9f9f9;">
                                                    @if($xyz<2)
                                                        <td colspan="5" style="border-top: none;"></td>
                                                    @else
                                                        <td colspan="4" style="border-top: none; border-bottom: none; border-left: none; background-color: white;"></td>
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
                                                        <td colspan="5" style="border-top: none;"></td>
                                                    @else
                                                        <td colspan="4" style="border-top: none; border-bottom: none; border-left: none; background-color: white;"></td>
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

                                        <?php //dd($ta['supi']); ?>

                                            <tr>
                                                @foreach($ta['items'] as $itm)
                                                    @if($xyz<2)
                                                        <td>{!! $sl++ !!}</td>
                                                        <td>{!! $itm->item_name !!}</td>
                                                        <td>{!! $itm->denoName !!}</td>
                                                        <td>{!! $itm->unit !!}</td>
                                                        <td>
                                                            @if(!empty($itm->previsouSuppName)) {!! 'Sup: '.$itm->previsouSuppName !!} @endif
                                                            @if(!empty($itm->previsouUnitPrice)) {!! ' UP: '.$itm->previsouUnitPrice !!} @endif
                                                            @if(!empty($itm->previousDates)) {!! ' Date: '.$itm->previousDates !!} @endif
                                                        </td>
                                                    @else
                                                        <td colspan="4" style="border-top: none; border-bottom: none; border-left: none; background-color: white;"></td>
                                                    @endif
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
                                            @if(!empty($supTotalAmountArray)) 
                                                <tr>
                                                    @if($xyz<2)
                                                        <td colspan="5"></td>
                                                    @else
                                                        <td colspan="4" style="border-top: none; border-bottom: none; border-left: none; background-color: white;"></td>
                                                    @endif
                                                    @foreach(array_slice($supTotalAmountArray[$key],$startRange, $endRange) as $sta)
                                                    <?php $devideData3 = explode('?',$sta); ?>
                                                        <td colspan="2" style="text-align: right;">@if(!empty($devideData3[0])) {!! ImageResizeController::custom_format($devideData3[0]) !!} @endif</td>
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
                                <!-- newly added -->

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

