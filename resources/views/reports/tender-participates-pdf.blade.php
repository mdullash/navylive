<?php use App\Http\Controllers\ImageResizeController; ?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>Tender Participates Report</title>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
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
                            <h4 class="text-center">Tender Participates ( {!! date('d M y h:i A' ) !!})</h4>
                    </div>
                        <div class="panel-body">

                            <div class="row">
                                <div class="text-center">
                                    <b>
                                        AWARDED SUPPLIERS LIST
                                        @if(empty($nsd_id)) {{'(All Organizations) '}}
                                        @else 
                                        {{'('.$search_nsd_name->name.')'}}
                                        @endif
                                        @if(!empty($ten_title)) {!! 'Tender Name ('.  $ten_title .')' !!}
                                        @else 
                                        {{'(All Tender)'}}
                                        @endif  
                                    </b>
                                </div>
                            </div><br>
                            
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center" width="20%">{{'NAME OF SUPPLIER'}}</th>
                                    <th class="text-center" width="15%">{{'Tender Title'}}</th>
                                    <th class="text-center" width="10%">{{'Organization'}}</th>
                                    <th class="text-center" width="10%">{{'IMC NO'}}</th>
                                    <th class="text-center" width="20%">{{'ITEM NAME'}}</th>
                                    <th class="text-center" width="10%">{{'DENO'}}</th>
                                    <th class="text-center" width="10%">{{'QTY'}}</th>

                                </tr>
                                </thead>
                                <tbody>

                                @if (count($suppliersrep)>0)

                                    <?php
                                    $page = \Input::get('page');
                                    $page = empty($page) ? 1 : $page;
                                    $sl = ($page-1)*10;
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
                                                <td rowspan="{{$ab}}" style="vertical-align: middle;">{{$l++}}</td>
                                                <td rowspan="{{$ab}}" style="vertical-align: middle;">
                                                    {{$sc->company_name}}
                                                </td>
                                                <?php $inc++ ; ?>
                                            @endif

                                            <td>{{$sc->tender_title}}</td>
                                            <td>{{ supply_nsd_name($sc->nsd_id) }}</td>
                                            <td>{{$sc->imc_number}}</td>
                                            <td>{{$sc->item_name}}</td>
                                            <td>{{$sc->deno_name}}</td>
                                            <td class="text-center">{{ImageResizeController::custom_format($sc->quantity)}} <?php $quantity += $sc->quantity;?></td>

                                        </tr>
                                    @endforeach
                                    <tr>

                                        <td colspan="7" class="text-center"><b>Total</b></td>
                                        <td class="text-center"><b>{{ImageResizeController::custom_format($quantity)}}</b></td>
                                        
                                    </tr>

                                @else
                                    <tr>
                                        <td colspan="12">{{'Empty Data'}}</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table><!---/table-responsive-->
                        </div>

                        </div>
                    </div>
                </div>
            </div>

    </div>

</body>
</html>    