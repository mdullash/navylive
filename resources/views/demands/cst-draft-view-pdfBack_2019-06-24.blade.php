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
                            <h3 class="text-center">Comparing Description of Navy Storehouse Dhaka</h3>
                            <h3 class="text-center">The Price List Submitted By The Supplier Organization</h3>
                    </div>
                        <div class="panel-body">

                          <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Items Details'}}</th>
                                    <th class="text-center">{{'Machinery / Manufacturer'}}</th>
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
                                    
                                    @if(!empty($supArray)) 
                                        <tr>
                                            <td></td><td></td><td></td><td></td><td></td><td></td>
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
                                                    <td>{!! 1 !!}</td>
                                                    <td>{!! $itm->item_name !!}</td>
                                                    <td>{!! $itm->manufacturing_country !!}</td>
                                                    <td>{!! $itm->denoName !!}</td>
                                                    <td>{!! $itm->unit !!}</td>
                                                    <td>{!! 'Sup: '.$itm->previsouSuppName.' UP: '.$itm->previsouUnitPrice.' Date: '.$itm->previousDates !!}</td>
                                                @endforeach
                                                <?php //echo "<pre>"; print_r($ta['supi'][0]); exit;?>
                                                @foreach($ta['supi'] as $sp)
                                                        <td>{!! '@TK' !!}</td>
                                                        <td>{!! ImageResizeController::custom_format($sp[0]->unit_price) !!}</td>
                                                        <td>{!! 'x' !!}</td>
                                                        <td>{!! '=' !!}</td>
                                                        <td>{!! $itm->unit !!}</td>
                                                        <td>{!! ImageResizeController::custom_format(($sp[0]->unit_price-$sp[0]->discount_amount)*$itm->unit) !!}</td>
                                                       
                                                @endforeach
                                                
                                            </tr>   
                                        @endforeach

                                        @if(!empty($supTotalAmountArray)) 
                                            <tr>
                                                <td></td><td></td><td></td><td></td><td></td><td></td>
                                                @foreach($supTotalAmountArray as $sta)
                                                    <td colspan="6" style="text-align: right;">{!! ImageResizeController::custom_format($sta) !!}</td>
                                                @endforeach
                                            </tr>
                                        @endif

                                    @endif
                                    
                                </tbody>
                            </table><!---/table-responsive-->

                        </div>
                    </div>
                </div>
            </div>

    </div>
</body>
</html>

