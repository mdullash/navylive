<?php use App\Http\Controllers\ImageResizeController; ?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>R</title>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<head>
    <style>
        body{
            /*font-family: 'bangla', sans-serif;*/
            font-size: 12pt;
        }

        table td {
            font-size: 12pt !important;
            padding: 5px;
            margin: 0;
        }
        table th {

            font-size: 12pt !important;
            padding: 5px;
            margin: 0;
        }

        .help-split {
            display: inline-block;
            width: 30%;
        }
         .printable-page{
            font-size: 12pt !important;
         }
    </style>
</head>

<body class="printable-page">
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading hbuilt">
                            <h3 class="text-center">Budget Code Wise Items ( {!! date('d M y h:i A' ) !!})</h3>
                    </div>
                        <div class="panel-body">

                            <div class="row">
                                <div class="text-center">
                                    <b>
                                        STATISTIC OF SUPPLIERS PO
                                        @if(empty($nsd_id)) {{'(All Organizations) '}}
                                        @else
                                        {{'('.$search_nsd_name->name.')'}}
                                        @endif

                                        @if(empty($item_id)) {{'(All Item) '}}
                                        @else
                                        {{'('.$search_item_name->item_name.')'}}
                                        @endif

                                        @if(!empty($from)) {!! ' '. date('d M y',strtotime($from)) !!}
                                        @else
                                        {{'Beginning'}}
                                        @endif

                                        @if(!empty($to)) TO {!! date('d M y',strtotime($to)) !!}
                                        @else TO {!! ' '. date('d M y') !!}
                                        @endif
                                    </b>
                                </div>
                            </div><br>

                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%" style="vertical-align: middle;">SL#</th>
                                    <th class="text-center" width="" style="vertical-align: middle;">{{'Budget Code'}}</th>
                                    <th class="text-center" width="">{{'Organization'}}</th>
                                    <th class="text-center" width="">{{'IMC NO'}}</th>
                                    <th class="text-center" width="">{{'ITEM NAME'}}</th>
                                    <th class="text-center" width="">{{'DENO'}}</th>
                                    <th class="text-center" width="">{{'QTY'}}</th>
                                    <th class="text-center" width="">{{'UNIT PRICE'}}</th>
                                    <th class="text-center" width="">{{'DISCOUNT AMOUNT'}}</th>
                                    <th class="text-center" width="">{{'TOTAL AMOUNT'}}</th>
                                    <th class="text-center" width="">{{'GRAND TOTAL'}}</th>

                                </tr>
                                </thead>
                                <tbody>

                                @if (count($suppliersrep)>0)

                                    <?php
                                        $sl = 0;
                                        $l = 1;
                                        $quantity = 0;
                                        $total = 0;
                                        $GrandtotalAll = 0;
                                        $a = null;

                                        function supply_nsd_name($nsd_id=null){
                                            $calName = \App\NsdName::where('id','=',$nsd_id)->value('name');
                                            return $calName;
                                        }

                                    ?>
                                    @foreach($suppliersrep as $sc)
                                        <tr>

                                            <?php
                                                if($a != $sc->budget_code){
                                                    $a = $sc->budget_code;

                                                    $ab = $suppliersrep->where('budget_code','=',$sc->budget_code)->count();
                                                    $inc = 1;
                                                    $grandTotal = $suppliersrep->where('budget_code','=',$sc->budget_code)->sum('total');
                                                    $GrandtotalAll+=$grandTotal;

                                                }

                                            ?>
                                            @if($inc==1)
                                                <td rowspan="{{$ab}}" style="vertical-align: middle;">{{$l++}}</td>
                                                <td rowspan="{{$ab}}" style="vertical-align: middle;">
                                                    {{$sc->code}}&nbsp;
                                                </td>
                                            <?php //$inc++ ; ?>
                                            @endif
                                            <td>{{ supply_nsd_name($sc->nsd_id) }}</td>
                                            <td>{{$sc->imc_number}}</td>
                                            <td>{{$sc->item_name}}</td>
                                            <td>{{$sc->deno_name}}</td>
                                            <td class="text-center">{{ImageResizeController::custom_format($sc->quantity)}} <?php $quantity += $sc->quantity;?></td>
                                            <td class="text-center">@if(!empty($sc->curname)){!! '('.$sc->curname.')' !!} @endif {{ImageResizeController::custom_format($sc->unit_price)}}</td>
                                            <td class="text-center">@if(!empty($sc->curname)){!! '('.$sc->curname.')' !!} @endif {{ImageResizeController::custom_format($sc->discount_price)}}</td>
                                            <td class="text-center">{!! '(BDT)' !!} {{ImageResizeController::custom_format($sc->total)}}<?php $total += $sc->total;?></td>
                                            @if($inc==1)
                                                <td rowspan="{{$ab}}">
                                                    {!! '(BDT)' !!} {{ImageResizeController::custom_format($grandTotal)}}
                                                </td>
                                                <?php $inc++ ; ?>
                                            @endif

                                        </tr>
                                    @endforeach
                                    <tr>

                                        <td colspan="6" class="text-center"><b>Total</b></td>
                                        <td class="text-center"><b>{{ImageResizeController::custom_format($quantity)}}</b></td>
                                        <td></td><td></td>
                                        <td class="text-center"><b>{!! '(BDT)' !!} {{ImageResizeController::custom_format($total)}}</b></td>
                                        <td><b>{!! '(BDT)' !!} {{ImageResizeController::custom_format($GrandtotalAll)}}</b></td>
                                    </tr>

                                @else
                                    <tr>
                                        <td colspan="13">{{'Empty Data'}}</td>
                                    </tr>
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
    