<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>D44B-<?php echo date('d-m-Y'); ?></title>


<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        body{
            font-family: "Roboto", Helvetica, Arial, sans-serif;
            font-size: 14px;
        }
        .borderless td, .borderless th {
            border: none;
        }
        table td,table th{
            padding: 3px !important;
        }
    </style>
</head>

<body class="container">
    <table class="table">
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-right" style="width: 79%;padding-top: 10px !important;">In lieu of </td>
            <td class="text-left"><u>F(NS)-52</u><br>D-44B</td>
        </tr>
    </table><br>

    <h4 class="text-center" style=""><u>RECEIPT VOUCHER</u></h4>


    <table class="table borderless">
        <tr>
            <td style="width: 79%;">D-44B No: {!! $d44bInfo->d44b_no !!}</td>
            <td>Date of Delivery: </td>
        </tr>
        <tr>
            <td>D-44B Date: {!! date('Y-m-d',strtotime($d44bInfo->d44b_date)) !!}</td>
            <td>Receive Date: {!! date('Y-m-d',strtotime($valuesFi->item_receive_date)) !!}</td>
        </tr>
        <tr>
            <td>PO No: {!! $poDatasInfo->po_number !!}</td>
            <td>Delay of Supply: </td>
        </tr>
        <tr>
            <td>PO Date: {!! date('Y-m-d',strtotime($poDatasInfo->top_date)) !!}</td>
            <td>Warehouse: </td>
        </tr>
    </table>
    <br>

    <table class="table table-hover table-bordered">
        <thead>
        <tr class="text-center">
            <th>Ser</th>
            <th>Item Name</th>
            <th>Deno</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Remarks</th>
        </tr>
        </thead>
        <tbody>
            <?php $sl = 1; ?>
            @if(!empty($qyeryResutl))
                @foreach($qyeryResutl as $qrl)
                    <tr>
                        <td>{!! $sl++ !!}</td>
                        <td>
                            {!! $qrl->item_name !!}
                        </td>
                        <td>
                            {!! $qrl->denoName !!}
                        </td>
                        <td>
                            {!! $qrl->demand_cr_to_item_cr_receive_qty !!}
                        </td>
                        <td>
                            {!! $qrl->unit_price !!}
                        </td>
                        <td>
                            {!! $qrl->d44b_comment !!}
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table><br><br>
    
    <table class="table borderless">
        <tr >
        @if(!empty($firstPerSon))
            <td style="width: 79%">
                
                    @if(!empty($firstPerSon->digital_sign))
                        <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $firstPerSon->digital_sign !!}" style="width: 8%;"><br>
                    @endif
                        {!! $firstPerSon->first_name.' '.$firstPerSon->last_name  !!}<br>
                        {!! $firstPerSon->rank !!}<br>
                        {!! $firstPerSon->designation !!}<br>
                        {!! $organizationName !!}<br>
            </td>
        @endif
        @if(!empty($sechondPerSon))
            <td >
                @if(!empty($sechondPerSon->digital_sign))
                        <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $sechondPerSon->digital_sign !!}" style="width: 8%;"><br>
                    @endif
                        {!! $sechondPerSon->first_name.' '.$sechondPerSon->last_name  !!}<br>
                        {!! $sechondPerSon->rank !!}<br>
                        {!! $sechondPerSon->designation !!}<br>
                        {!! $organizationName !!}<br>
            </td>
        @endif
    </tr>
    </table><br><br>
    <table>
        <tr>
            <td >
                Supplier:
            </td>
        </tr>
        <tr>
            <td >
                {!! $supplierNames->company_name !!}<br>
                {!! $supplierNames->trade_license_address !!}<br>
                {!! $supplierNames->mobile_number !!}<br>
                {!! $supplierNames->email !!}
            </td>
        </tr>
    </table>
</body>
</html>

