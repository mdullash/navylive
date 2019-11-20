<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title></title>


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
            <td style="">D-44B No:</td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 65mm;"></td>
            <td style=" font-size: 14px; ">Date of Delivery: 525447</td>
        </tr>
        <tr>
            <td style="">D-44B Date:</td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 65mm;"></td>
            <td style=" font-size: 14px; ">Receive Date: 525447</td>
        </tr>
        <tr>
            <td style="">PO No:</td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 65mm;"></td>
            <td style=" font-size: 14px; ">Delay of Supply: 525447</td>
        </tr>
        <tr>
            <td style="">PO Date:</td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 65mm;"></td>
            <td style=" font-size: 14px; ">Warehouse: 525447</td>
        </tr>
    </table><br>

    <table class="table-bordered">
        <thead></thead>
        <tbody>
            <tr>
                <th colspan="5" class="text-center">INVITATION FOR TENDERS (IFT)</th>
            </tr>
            <tr>
                <td>1.</td>
                <td colspan="2">Ministry/Division</td>
                <td colspan="2">Ministry of Defence</td>
            </tr>
            <tr>
                <td>2.</td>
                <td colspan="2">Agency</td>
                <td colspan="2">Bangladesh Navy</td>
            </tr>
            <tr>
                <td>3.</td>
                <td colspan="2">Procuring Entity Name</td>
                <td colspan="2">NSSD Dhaka</td>
            </tr>
            <tr>
                <td>4.</td>
                <td colspan="2">Procuring Entity Code</td>
                <td colspan="2">NA</td>
            </tr>
            <tr>
                <td>5.</td>
                <td colspan="2">Procuring Entity District</td>
                <td colspan="2">Dhaka</td>
            </tr>
            <tr>
                <td>6.</td>
                <td colspan="2">Invitation for</td>
                <td colspan="2">@if(!empty($tenderInfoForPdf->invitation_for)) {!! $tenderInfoForPdf->invitation_for !!} @else {!! 'NA' !!} @endif</td>
            </tr>
            <tr>
                <td>7.</td>
                <td colspan="2">Invitation Ref No</td>
                <td colspan="2">@if(!empty($tenderInfoForPdf->tender_number)) {!! $tenderInfoForPdf->tender_number !!} @else {!! 'NA' !!} @endif</td>
            </tr>
            <tr>
                <td>8.</td>
                <td colspan="2">Date</td>
                <td colspan="2">@if(!empty($tenderInfoForPdf->valid_date_from)) {!! date('d F Y', strtotime($tenderInfoForPdf->valid_date_from)) !!} @else {!! 'NA' !!} @endif</td>
            </tr>
            <tr>
                <th colspan="5">KEY INFORMATION</th>
            </tr>
            
            
            
        </tbody>
    </table>
    
    

    

    
    
    
                                         
</body>
</html>

