<?php
$groupChange = '';
$itemIdChange = '';
$count = count($demands);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Group wise print</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <style>
        .help-split {
            display: inline-block;
            width: 30%;
        }
        .printable-page{
            font-size: 10px;
        }
        table thead tr th{
            text-align: center;
            vertical-align: middle !important;
        }
        .custom-table{
            width: 100%;
        }
        .custom-table th{
            width: 33%;
            padding: 0px 0 10px 0;
            font-size: 12px;
        }
        table td {
            padding: 5px;
            margin: 0;
        }
        table th {
            padding: 5px;
            margin: 0;
        }
        .page-break {
            page-break-after: always;
        }

        @page {
            /*header: page-header;*/
            footer: page-footer;
        }
    </style>
</head>

<body class="printable-page">

<htmlpagefooter name="page-footer">
    <table style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;" width="100%">
        <tbody>
            <tr>
                <td width="49%"><span style="font-weight: bold; font-style: italic;">{!! date('d-m-Y h:i') !!}</span></td>
                <td style="font-weight: bold; font-style: italic;" align="right" width="49%">Page {PAGENO} of {nbpg}</td>
            </tr>
        </tbody>
    </table>
</htmlpagefooter>

<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading hbuilt">
                    <h3 style="text-align: center;">Naval Stores Sub Depot, Dhaka</h3>
                </div>
                <div class="panel-body">
                    @foreach($demands as $demand)
                        @if($groupChange != $demand->item_to_demand_group_name)
                            @if($loop->iteration != 1)
                                </tbody>
                                </table><!---/table-responsive-->
                    <div class="page-break"></div>
                                @endif
                    <div>
                        <table class="custom-table">
                            <tr>
                                <th>LPR No: </th>
                                <th>Group: {{$demand->supplycategories_name}}</th>
                                <th> Remarks: </th>
                            </tr>
                            <tr>
                                <th>Date: <!-- {{date('d-m-Y',strtotime($demand->item_to_demand_created_at))}} --></th>
                                <th>Priority: {{$demand->demands_priority}} </th>
                                <th></th>
                            </tr>
                        </table>
                    </div>
                    <table class="table table-bordered table-hover table-striped middle-align">
                        <thead>
                        <tr class="center">
                            <th rowspan="2">SL NO</th>
                            <th rowspan="2">Name of Item</th>
                            <th rowspan="2">Deno</th>
                            <th rowspan="2">Stock Qty</th>
                            <th rowspan="2">Required Qty</th>
                            <th rowspan="2">Manufacturer Name</th>
                            <th rowspan="2">Model</th>
                            <th colspan="5">Last Purchase</th>
                            <th colspan="4">Demand Info</th>
                        </tr>

                        <tr  class="center">
                            <th>Unit Price</th>
                            <th>Purchase Order Date</th>
                            <th>Po No</th>
                            <th>Supplier Name</th>
                            <th>Qty</th>
                            <th>Demand No</th>
                            <th>Date</th>
                            <th>Demand For</th>
                            <th>Demand Authority</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=1; ?>
                        @endif
                        @if($itemIdChange != $demand->items_id)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$demand->item_item_name}}</td>
                            <td>{{$demand->deno_name}}</td>
                            <td>{{$demand->item_to_demand_in_stock}}</td>
                            <td>{{$demand->item_to_demand_total_unit}}</td>
                            <td>{{$demand->item_manufacturing_country}}</td>
                            <td>{{$demand->item_model_number}}</td>
                            <td>
                                @if(!empty($demand->itemtotender_unit_price))
                                    {{$demand->itemtotender_unit_price}}
                                    @else
                                    {{$demand->item_unit_price}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($demand->tenders_purchase_order_date))
                                    {{date('d-m-Y',strtotime($demand->tenders_purchase_order_date))}}
                                @endif
                            </td>
                            <td>{{$demand->tenders_po_number}}</td>
                            <td>{{$demand->suppliers_company_name}}</td>
                            <td>{{$demand->itemtotender_quantity}}</td>
                            <td>{{$demand->demands_demand_no}}</td>
                            <td>{{date('d-m-Y',strtotime($demand->demands_Date))}}</td>
                            <td>{{$demand->demande_name_name}}</td>
                            <td>{{$demand->demands_demand_authority}}</td>
                        </tr>
                        @endif
                        <?php
	                    $groupChange = $demand->item_to_demand_group_name;
	                    $itemIdChange = $demand->items_id;
	                    $i++;
                        ?>
                    @if($loop->last)
                        </tbody>
                    </table><!---/table-responsive-->
                    @endif
                        @endforeach

                    <br><br><br>
                    @if(!empty($firstApprovalInfo) || !empty($secondApprovalInfo))
                    <table class="table table-bordered table-hover table-striped middle-align" style="width: 70%; margin-left: 17%;">
                        <tbody>
                            <tr>
                                
                                <td style="">
                                    @if(!empty($firstApprovalInfo))
                                        @if(!empty($firstApprovalInfo->digital_sign))
                                            <div class="logo-div" style="float: center !important;">
                                                <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $firstApprovalInfo->digital_sign !!}">
                                            </div>
                                        @endif
                                        <p style="margin: 0px;">{!! $firstApprovalInfo->first_name.' '.$firstApprovalInfo->last_name  !!}</p>
                                        <p style="margin: 0px;">{!! $firstApprovalInfo->rank !!}</p>
                                        <p style="margin: 0px;">{!! $firstApprovalInfo->designation !!}</p>
                                        <p style="margin: 0px;">{!! $organizationName !!}</p>
                                    @endif
                                </td>
                                <td style="">
                                    @if(!empty($secondApprovalInfo))
                                        @if(!empty($secondApprovalInfo->digital_sign))
                                            <div class="logo-div" style="float: center !important;">
                                                <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $secondApprovalInfo->digital_sign !!}">
                                            </div>
                                        @endif
                                        <p style="margin: 0px;">{!! $secondApprovalInfo->first_name.' '.$secondApprovalInfo->last_name  !!}</p>
                                        <p style="margin: 0px;">{!! $secondApprovalInfo->rank !!}</p>
                                        <p style="margin: 0px;">{!! $secondApprovalInfo->designation !!}</p>
                                        <p style="margin: 0px;">{!! $organizationName !!}</p>
                                    @endif
                                </td>
                                    
                            </tr>
                        </tbody>
                    </table>
                    @endif

                </div>
            </div>
        </div>
    </div>

</div>
</body>
</html>
