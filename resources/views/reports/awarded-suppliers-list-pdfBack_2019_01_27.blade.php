<?php use App\Http\Controllers\ImageResizeController; ?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>R</title>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<head>
    <style>
        .help-split {
            display: inline-block;
            width: 30%;
        }
         .printable-page{
            font-size: 10px;
         }
    </style>
</head>

<body class="printable-page">
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading hbuilt">
                            <h3 class="text-center">Awarded Suppliers List ( {!! date('d M y h:i A' ) !!})</h3>
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

                                        @if(empty($sup_id)) {{'(All Suppliers) '}}
                                        @else
                                        {{'('.$search_supplier_name->company_name.')'}}
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
                                    <th style="vertical-align: middle;" width="10%">{{'NAME OF SUPPLIER'}}</th>
                                    <th class="text-center" width="5%">{{'Tender Title'}}</th>
                                    <th class="text-center" width="5%">{{'Date'}}</th>
                                    <th class="text-center" width="5%">{{'PO / Contract No'}}</th>
                                    <th class="text-center" width="10%">{{'Organization'}}</th>
                                    <th class="text-center" width="5%">{{'IMC NO'}}</th>
                                    <th class="text-center" width="5%">{{'ITEM NAME'}}</th>
                                    <th class="text-center" width="5%">{{'DENO'}}</th>
                                    <th class="text-center" width="5%">{{'QTY'}}</th>
                                    <th class="text-center" width="10%">{{'UNIT PRICE'}}</th>
                                    <th class="text-center" width="10%">{{'DISCOUNT AMOUNT'}}</th>
                                    <th class="text-center" width="10%">{{'TOTAL AMOUNT'}}</th>
                                    <th class="text-center" width="10%">{{'GRAND TOTAL'}}</th>

                                </tr>
                                </thead>
                                <tbody>

                                @if (count($suppliersrep)>0)

                                    <?php
                                        $page = \Input::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page-1)*50;
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
                                                if($a != $sc->company_name){
                                                    $a = $sc->company_name;

                                                    $ab = $suppliersrep->where('company_name','=',$sc->company_name)->count();
                                                    $inc = 1;
                                                    $grandTotal = $suppliersrep->where('company_name','=',$sc->company_name)->sum('total');
                                                    $GrandtotalAll+=$grandTotal;

                                                }

                                            ?>
                                            @if($inc==1)
                                                <td rowspan="{{$ab}}" style="vertical-align: middle;">{{++$sl}}</td>
                                                <td rowspan="{{$ab}}" style="vertical-align: middle;">        
                                                    {{$sc->company_name}}
                                                </td>
                                            <?php //$inc++ ; ?>
                                            @endif

                                            <td>{{$sc->tender_title}}</td>
                                            <td>{{date('d-m-Y',strtotime($sc->tender_opening_date))}}</td>
                                            <td>{{$sc->po_number}}</td>
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

                                        <td colspan="9" class="text-center"><b>Total</b></td>
                                        <td class="text-center"><b>{{ImageResizeController::custom_format($quantity)}}</b></td>
                                        <td></td><td></td>
                                        <td class="text-center"><b>{!! '(BDT)' !!} {{ImageResizeController::custom_format($total)}}</b></td>
                                        <td><b>{!! '(BDT)' !!} {{ImageResizeController::custom_format($GrandtotalAll)}}</b></td>
                                    </tr>

                                @else
                                    <tr>
                                        <td colspan="14">{{'Empty Data'}}</td>
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
    