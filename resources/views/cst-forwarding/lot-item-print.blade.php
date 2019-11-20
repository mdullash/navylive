<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body{
            font-family: 'bangla', sans-serif;
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
        <td rowspan="8" style="width: 30%;">
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
            {{!empty($cstForwarding->cst_forwarding_number) ? $cstForwarding->cst_forwarding_number : $cstForwarding->tender_number}}
        </td>
        <td></td>
        <td>{{!empty($cstForwarding->cst_forwarding_date) ? date('d F Y',strtotime($cstForwarding->cst_forwarding_date)) : ''}}</td>
    </tr>
</table>

<p style="line-height: 170%;text-transform: uppercase;text-align: justify;">
    <u>
        FORWARDING OF quotation and comparative statement Local purchase of supply of
        {!! (!empty($tenderInfo->tender_title)) ? $tenderInfo->tender_title : '' !!}
        @if(!empty($tenderInfo->demending)) {{'-'.$tenderInfo->demending}} @endif
    </u>
</p>

<p>Ref:</p>

<p>a. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ !empty($tenderInfo->nhq_ltr_no) ? $tenderInfo->nhq_ltr_no : '' }} Date {{ !empty($tenderInfo->reference_date) ? date('d F Y',strtotime($tenderInfo->reference_date)) : '' }}.</p>

    <?php
        $tenderType = '';

        if ($tenderInfo->tender_type == 1)
        {
            $tenderType = 'Limited Tender';
        }
        else if ($tenderInfo->tender_type == 2)
        {
            $tenderType =  'Open Tender';
        }
        else if ($tenderInfo->tender_type == 4)
        {
            $tenderType =  'Spot Tender';
        }
        else if ($tenderInfo->tender_type == 5)
        {
            $tenderType =  'Direct Purchase';
        }
        else
        {
            $tenderType =  'Short Tender';
        }

        if ($qutationTenderCount > 1)
        {
            $qut = 'quotations';
        }
        else
        {
            $qut = 'quotation';
        }

        if ($NotSelectAsDraftCount > 1)
        {
            $qut2 = 'quotations';
        }
        else
        {
            $qut2 = 'quotation';
        }
    ?>

<p style="text-align: justify;line-height: 170%;margin-top: 30px;">
    1. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;In light of Ref ‘a’ {{$tenderType}}  has been floated for the above mentioned item and open on {{date('d F Y',strtotime($tenderInfo->tender_opening_date))}} and
    {{ ($qutationTenderCount > 0) ? \functions\OwnLibrary::numberTowords1($qutationTenderCount) : "no"  }} {{$qut}} {{ ($qutationTenderCount > 1) ? "have" : "has"  }} been found for LOT-{{$lotCount}}.
    @if($NotSelectAsDraftCount > 0)
        {{ ($NotSelectAsDraftCount > 0) ? ucwords(\functions\OwnLibrary::numberTowords1($NotSelectAsDraftCount)) : "No"  }} {{$qut2}} cancelled
        due to {{$NotSelectAsDraftSupplier}} {{ ($NotSelectAsDraftCount > 0) ? "have" : "has"  }} been cancelled due to not fulfilling tender requirements.
    @endif
</p>

<p style="text-align: justify;line-height: 170%;margin-top: 30px;">2. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Therefore, {!! ($NotSelectAsDraftSupplier > 0) ? "correct" : "" !!} quotation and comparative statement is forwarded for necessary action.
</p>


<table style="width: 100%;">

    <tr>
        <td style="width:38%;"></td>
        <td style="width: 30%;"></td>
        <td style="padding-left: 43px;">
            @if(!empty($approverName))
                @if(!empty($approverName->digital_sign))
                    <img src="{{url('public/uploads/digital_sign/'.$approverName->digital_sign)}}" style="width: 130px;height: 60px;"/>
                @endif
            @endif
        </td>
    </tr>
    <tr>
        <td style="width:38%;"></td>
        <td style="width: 30%;"></td>
        <td style="padding-left: 43px;">
            @if(!empty($approverName))
                {!! $approverName->first_name.' '.$approverName->last_name !!}
            @endif
        </td>
    </tr>
    <tr>
        <td style="width:38%;"></td>
        <td style="width: 30%;"></td>
        <td style="padding-left: 43px;">
            @if(!empty($approverName))
                {!! !empty($cstForwarding->approved_by_rank) ? $cstForwarding->approved_by_rank : $approverName->rank !!}
            @endif
        </td>
    </tr>
    <tr>
        <td style="width:38%;"></td>
        <td style="width: 30%;"></td>
        <td style="padding-left: 43px;">
            @if(!empty($approverName))
                {{--{!! $approverName->designation !!}--}}
                For Officer in Charge
            @endif
        </td>
    </tr>
</table>

<table>
    <tr>
        <td style="width:38%;">Enclosure:</td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>

    @if(!empty($cstForwarding->enclosure))
        <tr >
            <td style="width:38%;height: 20px;"></td>
            <td style="width: 30%;"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">
				<?php echo nl2br($cstForwarding->enclosure);?>
            </td>
        </tr>
    @endif
    <tr >
        <td style="width:38%;height: 20px;"></td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="width:38%;height: 20px;">Distribution:</td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    @if(!empty($cstForwarding->distribution))
        <tr >
            <td style="width:38%;height: 20px;"></td>
            <td style="width: 30%;"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">
				<?php echo nl2br($cstForwarding->distribution);?>
            </td>
        </tr>
    @endif
    <tr >
        <td style="width:38%;height: 20px;"></td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="width:38%;height: 20px;">External:</td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    @if(!empty($cstForwarding->external))
        <tr >
            <td style="width:38%;height: 20px;"></td>
            <td style="width: 30%;"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">
				<?php echo nl2br($cstForwarding->external);?>
            </td>
        </tr>
    @endif
    <tr >
        <td style="width:38%;height: 20px;"></td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="width:38%;height: 20px;">Action:</td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    @if(!empty($cstForwarding->action))
        <tr >
            <td style="width:38%;height: 20px;"></td>
            <td style="width: 30%;"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">
				<?php echo nl2br($cstForwarding->action);?>
            </td>
        </tr>
    @endif
    <tr >
        <td style="width:38%;height: 20px;"></td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="width:38%;height: 20px;">Information:</td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>

    @if(!empty($cstForwarding->information))
        <tr >
            <td style="width:38%;height: 20px;"></td>
            <td style="width: 30%;"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">
				<?php echo nl2br($cstForwarding->information);?>
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