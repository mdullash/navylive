<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CR</title>
    <style>       
        table {
            border-collapse: collapse;
            font-size: 14px !important;
            font-family: 'bangla', sans-serif;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #282828;
        }
        @page {
            footer: page-footer;
            header: page-header;
        }
    </style>

    
</head>
<body>
    <htmlpageheader name="page-header">
       <p style="text-align: center;font-size: 14px;">RESTRICTED</p>
    </htmlpageheader>

    <table style="width: 100%;">
    <!-- <tbody style="width: 215.9mm;"> -->
        
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 65mm;"></td>
            <td style=" font-size: 14px;">{!! (!empty($orgInfo->name)) ? $orgInfo->name : '' !!}</td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 65mm;"></td>
            <td style=" font-size: 14px;">Namapara, Khilkhet</td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 65mm;"></td>
            <td style=" font-size: 14px;">Dhaka-1219</td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 65mm;"></td>
            <td style=""></td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 65mm;"></td>
            <td style=""></td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 65mm;"></td>
            <td style=" font-size: 14px;">Phone: 41095104-8 Ext: 4000</td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 65mm;"></td>
            <td style=" font-size: 14px;">Fax: 41095103</td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 65mm;"></td>
            <td style=" font-size: 14px;">Email: oicnssd@navy.mil.bd</td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 65mm;"></td>
            <td style=""></td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 65mm;"></td>
            <td style=""></td>
        </tr>
        <tr>
            <td style="font-size: 14px;">{!! (!empty($crNumberInfo->cr_number)) ? $crNumberInfo->cr_number : '' !!}</td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px; text-align: right; width: 65mm;">{!! (!empty($crNumberInfo->item_receive_date)) ? date('d',strtotime($crNumberInfo->item_receive_date)) : '' !!}</td>
            <td style="font-size: 14px;">{!! (!empty($crNumberInfo->item_receive_date)) ? date('F Y',strtotime($crNumberInfo->item_receive_date)) : '' !!}</td>                   
        </tr>
        <tr style="height: 2mm;"></tr>
    </table>
    <br>
    <table>
        <tr>
            <td colspan="3" style=" font-size: 14px; font-weight: bold;"><span style="border-bottom: 1px solid black;">REQUEST FOR INSPECTION OF STORES/ITEMS RECEIVED AGAINST WORK ORDER</span></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td colspan="3" style=" font-size: 14px;">Ref:</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr style="height: 4mm;"></tr>
        
        <tr>
            <td colspan="3" style=" font-size: 14px;">A .&nbsp;&nbsp;{!! (!empty($orgInfo->name)) ? $orgInfo->name : '' !!} Purchase Order No. {!! (!empty($podataInfo->po_number)) ? $podataInfo->po_number : '' !!} Date {!! (!empty($podataInfo->top_date)) ? date('d F Y',strtotime($podataInfo->top_date)) : '' !!}.</td>
        </tr>
        
    </table>
    <br>
    <table>
        <tr>
            <td colspan="3" style=" font-size: 14px; text-align: justify;"><span>1.</span>&nbsp;&nbsp; Following items have been received at NSSD Dhaka against reference ‘B' and ready for inspection at CR Section.</td>
        </tr>
        <tr style="height: 2mm;"></tr>
    <!-- </tbody> -->
    </table>
    <br>

    <table  style="width: 100%;" class="table-bordered">
    <!-- <tbody  style="width: 215.9mm;"> -->
        <tr>
            <td style="border: 0;  font-size: 14px;"></td>
            <td style="font-size: 14px;">&nbsp;Ser</td>
            <td style="font-size: 14px;">&nbsp; Description</td>
            <td style="font-size: 14px;">&nbsp;&nbsp;&nbsp;Deno&nbsp;</td>
            <td style="font-size: 14px;">&nbsp;&nbsp;&nbsp;Qty</td>
        </tr>
        <?php 
            $sl = 1; 
            $totalAmount = 0;
        ?>
        @foreach($qyeryResutl as $ssii)
        <tr>
            <td style="border: 0"></td>
            <td style="font-size: 14px;">&nbsp; {!! $sl++ !!}</td>
            <td style="font-size: 14px;">&nbsp;{!! $ssii->item_name !!} <br>@if(!empty($ssii->item_model_number)) &nbsp;Band/Model: {!! $ssii->item_model_number !!} @endif
            </td>
            <td style="font-size: 14px;">&nbsp;{!! $ssii->denoName !!} </td>
            <td style="font-size: 14px;">&nbsp;
                {!! $ssii->cr_receive_qty !!}  
            </td>
            
        </tr>
        @endforeach       
        
    </table>
    <br>
    <table  style="width: 100%;">
        <tbody  style="width: 215.9mm;">
             
            <tr>
                <td>2.</td>
                <td>It is therefore requested to complete inspection at the earliest.</td>
            </tr>
            <tr style="height: 10mm;"></tr>
        </tbody>
    </table>
    <br>

    <table  style="width: 100%;">
    <!-- <tbody  style="width: 215.9mm;"> -->
        <tr>
            <td style="font-size: 14px;color: white;">{!! (!empty($crNumberInfo->cr_number)) ? $crNumberInfo->cr_number : '' !!}</td>
            <td></td>
            <td></td>
            <td></td>
            <td style="width: 65mm;"></td>
            <td style="text-align: left;">{!! \Auth::user()->first_name.' '.\Auth::user()->last_name !!}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="width: 65mm;"></td>
            <td>{!! \Auth::user()->rank !!}</td>
        </tr>                
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="width: 65mm;"></td>
            <td>{!! \Auth::user()->designation !!}</td>
        </tr>
    </table>
    <table  style="width: 100%;margin-bottom: 15px;">
        <tr>
            <td colspan="3" style="font-weight: bold; font-size: 14px;">Enclosure:</td>
        </tr>
    </table>

    <table  style="width: 100%;">
        <tr>
            <td style="font-size: 14px;">{!! $crNumberInfo->info !!}</td>
        </tr>
        <tr style="height: 2mm;"></tr>
    </table>
    <br>
    <table>
        <tr>
            <td colspan="3" style="font-size: 14px;">Distribution: </td>
        </tr>
    </table>
    <table  style="width: 100%;">
        <tr>
            <td colspan="3" style="font-size: 14px;">External: </td>
        </tr>
    </table>
    <table  style="width: 100%;">
        <tr>
            <td colspan="3" style="font-size: 14px;">Action: </td>
        </tr>
    </table>
    <br>
    <table  style="width: 100%;">
        <tr>
            <td colspan="3" style="font-size: 14px;">Assistant Chief Inspector Naval Stores<br>Namapara, Khilkhet<br>Dhaka-1229 </td>
        </tr>
    <!-- </tbody> -->
    </table>



    <htmlpagefooter name="page-footer">
        <table style="vertical-align: bottom; font-family: serif; color: #000000;" width="100%">
            <tbody>
            <tr>
                <td style=" font-style: italic; font-size: 14px;" align="left" width="31%;">Page {PAGENO} of {nbpg}</td>
                <td style="font-size: 14px;" align="center" width="31%;">RESTRICTED</td>
                <td style=" font-style: italic; font-size: 14px;" align="right" width="31%;">{!! date('d-m-Y') !!}</td>
            </tr>
            </tbody>
        </table>
    </htmlpagefooter>
    
</body>
</html>