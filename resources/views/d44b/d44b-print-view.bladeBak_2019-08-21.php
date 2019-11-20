<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>D44B-<?php echo date('d-m-Y'); ?></title>


<head>
    <style>
        body{
        font-family: 'bangla', sans-serif;
        padding: 100px;
        font-size: 12px !important;
       }
       .logo-div{
           text-align: center;
           /* -webkit-filter: grayscale(100%);
            filter: grayscale(100%); */
       }
       /*.text-red{
           color: red;
       }
       .bg-red{
           background-color: red;
       }*/
        .navy-logo{
            width: 100px;
        }
        .underline{
            text-decoration: underline;
        }
        .text-center{
            text-align: center;
        }
        h3.text-center{
            margin: 5px 0;
        }
        table {
            border-collapse: collapse; 
            width: 100%;
            /*font-family: Arial, Helvetica, sans-serif;*/
            font-weight: 500;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid black;
        }
        th{
             font-weight: bold;
        }
        th,td{
            padding: 3px 10px;
        }
        td p{
            margin: 0 10px;
        }
        @page {
            /*header: page-header;*/
            footer: page-footer;
        }

    </style>
</head>

<body>
    <table style="width: 100%;">
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 100mm;"></td>
            <td style=" font-size: 14px; text-align: right;">In lieu of </td>
            <td><u>F(NS)-52</u><br>D-44B</td>
        </tr>
    </table><br>

    <table style="width: 100%;">
        <tr>
            <td style="text-align: center;"><h3><u>RECEIPT VOUCHER</u></h3></td>
        </tr>
    </table><br>

    <table style="width: 100%;">
        <tr>
            <td style="width: 48%; font-size: 14px;">D-44B No: {!! $d44bInfo->d44b_no !!}</td>
            <td style=" font-size: 14px; width: 48%; ">Date of Delivery: </td>
        </tr>
        <tr>
            <td style="width: 48%; font-size: 14px;">D-44B Date: {!! date('Y-m-d',strtotime($d44bInfo->d44b_date)) !!}</td>
            
            <td style=" font-size: 14px; ">Receive Date: {!! date('Y-m-d',strtotime($valuesFi->item_receive_date)) !!}</td>
        </tr>
        <tr>
            <td style="width: 48%; font-size: 14px;">PO No: {!! $poDatasInfo->po_number !!}</td>
            <td style="width: 48%; font-size: 14px;">Delay of Supply: </td>
        </tr>
        <tr>
            <td style="width: 48%; font-size: 14px;">PO Date: {!! date('Y-m-d',strtotime($poDatasInfo->top_date)) !!}</td>
            <td style="width: 48%; font-size: 14px;">Warehouse: </td>
        </tr>
    </table><br>

    <table class="table-bordered">
        <thead></thead>
        <tbody>
            <?php $sl = 1; ?>
            <tr>
                <th>Ser</th>
                <th>Item Name</th>
                <th>Deno</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Remarks</th>
            </tr>
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
    
    <table style="width: 100%;">
        <tr>
        @if(!empty($firstPerSon))
            <td style="padding: 15px 50px; width: 48%;">
                
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
            <td  style="padding: 15px 0; width: 48%;">
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
    <table style="width: 100%;">
        <tr>
            <td>
                Supplier:
            </td>
        </tr>
        <tr>
            <td>
                {!! $supplierNames->company_name !!}<br>
                {!! $supplierNames->trade_license_address !!}<br>
                {!! $supplierNames->mobile_number !!}<br>
                {!! $supplierNames->email !!}
            </td>
        </tr>
    </table><br>

    <htmlpagefooter name="page-footer">
        <table style="vertical-align: bottom; font-family: serif; color: #000000;" width="100%">
            <tbody>
            <tr>
                <td style=" font-style: italic; font-size: 14px;" align="left" width="31%;">Page {PAGENO} of {nbpg}</td>
                
                <td style=" font-style: italic; font-size: 14px;" align="right" width="31%;">{!! date('d-m-Y') !!}</td>
            </tr>
            </tbody>
        </table>
    </htmlpagefooter>
                                         
</body>
</html>

