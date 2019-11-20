<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
        }
        table, table.border {
            border-collapse: collapse;
            /*font-size: 14px !important;*/
        }
        .table-bordered td, .table-bordered th,table.border, table.border th, table.border td {
            border: 1px solid #282828;
        }
        .underline{
            text-decoration: underline;
        }
        .text-center{
            text-align: center;
        }
        @page {
            footer: page-footer;
            header: page-header;
        }
        @media print {
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<?php use functions\OwnLibrary; use App\Http\Controllers\ImageResizeController; ?>
<body>
<htmlpageheader name="page-header">
    <p style="text-align: center;font-size: 14px;">RESTRICTED</p>
</htmlpageheader>
<table style="width: 100%;font-size: 14px;">
    <tr>
        <td style="width:38%;"></td>
        <td rowspan="8" style="width: 20%;">
            {{--<img class="navy-logo" style="height: 100px;margin-left: 50px;margin-top:-20px;" src="{{URL::to('/')}}/public/img/bd-navy.png">--}}
        </td>
        <td style="padding-left: 21px;">{!! (!empty($orgInfo->name)) ? $orgInfo->name : '' !!}</td>
    </tr>
    <tr>
        <td></td>
        <td style="padding-left: 21px;">Namapara, Khilkhet</td>
    </tr>
    <tr>
        <td></td>
        <td style="padding-left: 21px;">Dhaka-1229</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="padding-left: 21px;">Phone: 41095104-8 Ext: @if(!empty($approverName)) {{$approverName->contact_no}} @endif</td>
    </tr>
    <tr>
        <td></td>
        <td style="padding-left: 21px;">Fax: 41095103</td>
    </tr>
    <tr>
        <td></td>
        <td style="padding-left: 21px;">Email: oicnssd@navy.mil.bd</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>
            {{!empty($billForwarding->bill_forwarding_number) ? $billForwarding->bill_forwarding_number : $billForwarding->tender_number}}
        </td>
        <td></td>
        <td>{{!empty($billForwarding->bill_forwarding_date) ? date('d F Y',strtotime($billForwarding->bill_forwarding_date)) : ''}}</td>
    </tr>
</table>

<p style="line-height: 170%;text-transform: uppercase;text-align: justify;">
    <u>
        Bill Forwarding of
        {!! (!empty($tenderInfo->tender_title)) ? $tenderInfo->tender_title : '' !!}
        {{--@if(!empty($tenderInfo->demending)) {{'-'.$tenderInfo->demending}} @endif--}}
        </u>
</p>

<p>Ref:</p>

<p>a. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{!! (!empty($tenderInfo->nhq_ltr_no)) ? $tenderInfo->nhq_ltr_no : '' !!}  Date {!! (!empty($tenderInfo->nhq_ltr_date)) ? date('d F Y',strtotime($tenderInfo->nhq_ltr_date)) : '' !!}<br />
    b. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NSSD PO No. {{!empty($podata->po_number) ? $podata->po_number : ''}} Date {{!empty($podata->top_date) ? date('d F Y',strtotime($podata->top_date)) : ''}}.<br />
    c. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ !empty($demandToColQut->suppliernametext) ? $demandToColQut->suppliernametext : '' }} Bill no {{ !empty($billForwarding->bill_number) ? $billForwarding->bill_number : '' }}
    Date {{ !empty($billForwarding->bill_date) ? date('d F Y',strtotime($billForwarding->bill_date)) : '' }}.</p>



<p style="text-align: justify;line-height: 170%;margin-top: 20px;">
    1. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;In view of approval of Ref 'a', items have been delivered successfully by {{ !empty($demandToColQut->suppliernametext) ? ucwords($demandToColQut->suppliernametext) : '' }}.
    As such necessary documents has been sent for bill payment of  Taka {{ \functions\OwnLibrary::numberformat($demandToColQut->total) }}
    ( Taka {{\functions\OwnLibrary::numberTowords1($demandToColQut->total) }} Only )  of the delivered items.
</p>

<p style="text-align: justify;line-height: 170%;margin-top: 20px;">2. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Information regarding delivery of item is given below:
</p>
<p style="text-align: justify;line-height: 170%;margin-top: 20px;padding-left: 50px;">
    a. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Schedule of delivery: {{date('d F Y',$deliveryDate)}} <br />
    b. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Delivered on: {{ date('d F Y',$deliveredOn) }} <br />
    c. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Delay in Delivery: {{ $delayDelivery }} <br />
    d. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date of acceptance: {{ $acceptanceDate }}
</p>

<p style="text-align: justify;line-height: 170%;margin-top: 20px;">
    3. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LD is recommended as per FO 01/2013 Para 36 due to delay in delivery.
</p>


<table style="width: 100%;">

    <tr>
        <td style="width:38%;"></td>
        <td style="width: 20%;"></td>
        <td style="padding-left: 43px;">
            @if(!empty($approverName))
                @if(!empty($approverName->digital_sign))
                    <img src="{{url('public/uploads/digital_sign/'.$approverName->digital_sign)}}" style="width: 120px;height: 60px;"/>
                @endif
            @endif
        </td>
    </tr>
    <tr>
        <td style="width:38%;"></td>
        <td style="width: 20%;"></td>
        <td style="padding-left: 43px;">
            @if(!empty($approverName))
                {!! $approverName->first_name.' '.$approverName->last_name !!}
            @endif
        </td>
    </tr>
    <tr>
        <td style="width:38%;"></td>
        <td style="width: 20%;"></td>
        <td style="padding-left: 43px;">
            @if(!empty($approverName))
                {!! !empty($billForwarding->approved_by_rank) ? $billForwarding->approved_by_rank : $approverName->rank !!}
            @endif
        </td>
    </tr>
    <tr>
        <td style="width:38%;"></td>
        <td style="width: 20%;"></td>
        <td style="padding-left: 43px;">
            @if(!empty($approverName))
                {{--{!! $approverName->designation !!}--}}
                For Officer in Charge
            @endif
        </td>
    </tr>
</table>

<table>
    @if(!empty($billForwarding->enclosure))
    <tr>
        <td style="width:38%;">Enclosure:</td>
        <td style="width: 20%;"></td>
        <td></td>
    </tr>
        <tr >
            <td style="width:38%;height: 18px;"></td>
            <td style="width: 20%;"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">
				<?php echo nl2br($billForwarding->enclosure);?>
            </td>
        </tr>
    @endif
    <tr >
        <td style="width:38%;height: 20px;"></td>
        <td style="width: 20%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="width:38%;">Distribution:</td>
        <td style="width: 20%;"></td>
        <td></td>
    </tr>
    @if(!empty($billForwarding->distribution))
        <tr >
            <td style="width:38%;height: 18px;"></td>
            <td style="width: 20%;"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">
				<?php echo nl2br($billForwarding->distribution);?>
            </td>
        </tr>
    @endif
    <tr >
        <td style="width:38%;height: 20px;"></td>
        <td style="width: 20%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="width:38%;">External:</td>
        <td style="width: 20%;"></td>
        <td></td>
    </tr>
    @if(!empty($billForwarding->external))
        <tr >
            <td style="width:38%;height: 18px;"></td>
            <td style="width: 20%;"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">
				<?php echo nl2br($billForwarding->external);?>
            </td>
        </tr>
    @endif
    <tr >
        <td style="width:38%;height: 20px;"></td>
        <td style="width: 20%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="width:38%;">Action:</td>
        <td style="width: 20%;"></td>
        <td></td>
    </tr>
    @if(!empty($billForwarding->action))
        <tr >
            <td style="width:38%;height: 18px;"></td>
            <td style="width: 20%;"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">
				<?php echo nl2br($billForwarding->action);?>
            </td>
        </tr>
    @endif
    <tr >
        <td style="width:38%;height: 20px;"></td>
        <td style="width: 20%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="width:38%;">Information:</td>
        <td style="width: 20%;"></td>
        <td></td>
    </tr>

    @if(!empty($billForwarding->information))
        <tr >
            <td style="width:38%;height: 18px;"></td>
            <td style="width: 20%;"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">
				<?php echo nl2br($billForwarding->information);?>
            </td>
        </tr>
    @endif
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