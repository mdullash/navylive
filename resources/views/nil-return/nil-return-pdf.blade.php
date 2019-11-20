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
            {{!empty($nilReturn->nil_number) ? $nilReturn->nil_number : ''}}
        </td>
        <td></td>
        <td>{{!empty($nilReturn->nil_date) ? date('d F Y',strtotime($nilReturn->nil_date)) : ''}}</td>
    </tr>
</table>

<p style="line-height: 170%;text-transform: uppercase;text-align: justify;">
    <u>
        FORWARDING OF NIL RETURN LOCAL PURCHASE OF
        {!! (!empty($tenderFirst->tender_title)) ? $tenderFirst->tender_title : '' !!}
        @if(!empty($tenderFirst->demending)) {{'-'.$tenderFirst->demending}} @endif
    </u>
</p>

<p>Ref:</p>

<p>a. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;{{ !empty($tenderFirst->nhq_ltr_no) ? $tenderFirst->nhq_ltr_no : '' }} Date {{ !empty($tenderFirst->reference_date) ? date('d F Y',strtotime($tenderFirst->reference_date)) : '' }}.</p>

<p style="text-align: justify;line-height: 170%;margin-top: 30px;">1. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php
	$tenderTypeFirst = '';
	$tenderTypeLatest = '';
	$qutFirst = '';
	$qutLatest = '';

//	tender First

     if ($tenderFirst->tender_type == 1)
     	{
	        $tenderTypeFirst = 'Limited Tender';
        }
        else if ($tenderFirst->tender_type == 2)
        {
	        $tenderTypeFirst =  'Open Tender';
        }
        else if ($tenderFirst->tender_type == 4)
        {
	        $tenderTypeFirst =  'Spot Tender';
        }
        else if ($tenderFirst->tender_type == 5)
        {
	        $tenderTypeFirst =  'Direct Purchase';
        }
        else
        {
	        $tenderTypeFirst =  'Short Tender';
        }

    //        tender latest
        if ($tendertLatest->tender_type == 1)
        {
	        $tenderTypeLatest = 'Limited Tender';
        }
        else if ($tendertLatest->tender_type == 2)
        {
	        $tenderTypeLatest =  'Open Tender';
        }
        else if ($tendertLatest->tender_type == 4)
        {
	        $tenderTypeLatest =  'Spot Tender';
        }
        else if ($tendertLatest->tender_type == 5)
        {
	        $tenderTypeLatest =  'Direct Purchase';
        }
        else
        {
	        $tenderTypeLatest =  'Short Tender';
        }

        if ($qutationFirstTenderCount > 1)
        	{
		        $qutFirst = 'quotations';
            }
            else
            {
	            $qutFirst = 'quotation';
            }

            if ($qutationLatestTenderCount > 1)
            {
	            $qutLatest = 'quotations';
            }
            else
            {
	            $qutLatest = 'quotation';
            }
    ?>
    In light of Ref ‘a’ {{$tenderTypeFirst}} has been floated for the above mentioned item and open on {{date('d F Y',strtotime($tenderFirst->tender_opening_date))}} and
    {{ ($qutationFirstTenderCount > 0) ? \functions\OwnLibrary::numberTowords1($qutationFirstTenderCount) : "no"  }} {{$qutFirst}} {{ ($qutationFirstTenderCount > 1) ? "have" : "has"  }} been found.
    @if($qutationFirstTenderCount > 0)
    Received quotation from {{$supplierNameFirst}} has been cancelled due to not fulfilling tender requirements.
    @endif
    Later on {{$tenderTypeLatest}} again floated and open on {{$retenderDates}} consecutively and
    {{ ($qutationLatestTenderCount > 0) ? \functions\OwnLibrary::numberTowords1($qutationLatestTenderCount) : "no" }} {{$qutLatest}} {{ ($qutationLatestTenderCount > 1) ? "have" : "has" }}  have been found.
    @if($qutationLatestTenderCount > 0)
    Received quotation from {{$supplierNameLatest}} has been cancelled due to not fulfilling tender requirements.
    @endif
</p>

<p style="text-align: justify;line-height: 170%;margin-top: 30px;">2. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Therefore, Nil return is forwarded for necessary action.
</p>
<br /><br /><br /><br /><br />

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
                {!! $approverName->rank !!}
            @endif
        </td>
    </tr>
    <tr>
        <td style="width:38%;"></td>
        <td style="width: 30%;"></td>
        <td style="padding-left: 43px;">
            @if(!empty($approverName))
                {!! $approverName->designation !!}
            @endif
        </td>
    </tr>
</table>

<table>
    <tr>
        <td style="width:38%;">Distribution:</td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    @if(!empty($nilReturn->distribution))
        <tr>
            <td colspan="3">
                <?php echo nl2br($nilReturn->distribution);?>
            </td>
        </tr>
    @endif
    <tr>
        <td style="width:38%;"></td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="width:38%;"></td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="width:38%;"></td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="width:38%;">Ext:</td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    @if(!empty($nilReturn->ext))
        <tr>
            <td colspan="3">
				<?php echo nl2br($nilReturn->ext);?>
            </td>
        </tr>
    @endif
    <tr>
        <td style="width:38%;"></td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="width:38%;"></td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="width:38%;"></td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="width:38%;">Action:</td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    @if(!empty($nilReturn->action))
        <tr>
            <td colspan="3">
				<?php echo nl2br($nilReturn->action);?>
            </td>
        </tr>
    @endif
    <tr>
        <td style="width:38%;"></td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="width:38%;"></td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="width:38%;"></td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style="width:38%;">Info:</td>
        <td style="width: 30%;"></td>
        <td></td>
    </tr>

    @if(!empty($nilReturn->info))
        <tr>
            <td colspan="3">
				<?php echo nl2br($nilReturn->info);?>
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