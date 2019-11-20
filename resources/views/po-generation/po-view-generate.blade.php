<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LOCAL PURCHASE ORDER-{!! (!empty($tenderInfo->tender_title)) ? $tenderInfo->tender_title : '' !!}-BNS PROPERTY</title>
    <link rel="stylesheet" href="{{asset("public/vendor/bootstrap/dist/css/bootstrap.css")}}">
    <style>
        body{
            font-family: 'bangla', sans-serif;
            width: 50%;
            margin: 20px auto 50px auto;
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
        table.border, table.border th, table.border td
    </style>

    
</head>
<?php use functions\OwnLibrary; use App\Http\Controllers\ImageResizeController; ?>
<body>
{{--{{dd($demandToCollQut)}}--}}

    <htmlpageheader name="page-header">
       <p style="text-align: center;font-size: 14px;">RESTRICTED</p>
    </htmlpageheader>

    @foreach($demandToCollQut as $dtcq)
        <table style="width: 100%;">
            <!-- <tbody style="width: 215.9mm;"> -->
                
                <tr>
                    <td style=""></td>
                    <td colspan="3" rowspan="8">
                        <img class="navy-logo" style="height: 100px;margin-left: 50px;margin-top:-20px;" src="{{URL::to('/')}}/public/img/bd-navy.png">
                    </td>
                    <td style="width: 20mm;"></td>
                    <td style=" font-size: 14px;">{!! (!empty($orgInfo->name)) ? $orgInfo->name : '' !!}</td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style="width: 20mm;"></td>
                    <td style=" font-size: 14px;">Namapara, Khilkhet</td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style="width: 20mm;"></td>
                    <td style=" font-size: 14px;">Dhaka-1219</td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style="width: 20mm;"></td>
                    <td style=""></td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style="width: 20mm;"></td>
                    <td style=""></td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style="width: 20mm;"></td>
                    <td style=" font-size: 14px;">Phone: 41095104-8 Ext: @if(!empty($podataInfo->poApprovalName->contact_no)) {{$podataInfo->poApprovalName->contact_no}} @endif</td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style="width: 20mm;"></td>
                    <td style=" font-size: 14px;">Fax: 41095103</td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style="width: 20mm;"></td>
                    <td style=" font-size: 14px;">Email: oicnssd@navy.mil.bd</td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=""></td>
                    <td style="width: 20mm;"></td>
                    <td style=""></td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=""></td>
                    <td style="width: 20mm;"></td>
                    <td style=""></td>
                </tr>
                <tr>
                    <td style="font-size: 14px;">{!! (!empty($podataInfo->po_number)) ? $podataInfo->po_number : '' !!}</td>
                    <td style=""></td>
                    <td style=""></td>
                    <td ></td>
                    <td style="font-size: 14px; text-align: right; width: 20mm;">{!! (!empty($podataInfo->top_date)) ? date('d',strtotime($podataInfo->top_date)) : '' !!}</td>
                    <td style="font-size: 14px;">{!! (!empty($podataInfo->top_date)) ? date('F Y',strtotime($podataInfo->top_date)) : '' !!}</td>                   
                </tr>
                <tr style="height: 2mm;"></tr>
            </table>
            <br>
            <table>
                <tr>
                    <td colspan="3" style=" font-size: 14px; font-weight: bold;"><h3 style="text-decoration: underline;">LOCAL PURCHASE ORDER-{!! (!empty($tenderInfo->tender_title)) ? $tenderInfo->tender_title : '' !!}@if(!empty($tenderInfo->demending)) {{'-'.$tenderInfo->demending}} @endif</h3></td>
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
                @if(empty($demandToLprInfo->head_ofc_apvl_status))
                <tr>
                    <td colspan="3" style=" font-size: 14px;">A .&nbsp;&nbsp;&nbsp;Tender Reference Number. {!! (!empty($tenderInfo->nhq_ltr_no)) ? $tenderInfo->nhq_ltr_no : '' !!}. Tender Reference Date {!! (!empty($tenderInfo->reference_date)) ? date('d F Y',strtotime($tenderInfo->reference_date)) : '' !!}.</td>
                </tr>
                @endif
                <tr>
                    <td colspan="3" style=" font-size: 14px;">@if(empty($demandToLprInfo->head_ofc_apvl_status)) B @else A @endif.&nbsp;&nbsp;&nbsp;{!! (!empty($orgInfo->name)) ? $orgInfo->name : '' !!} tender no. {!! (!empty($tenderInfo->tender_number)) ? $tenderInfo->tender_number : '' !!} . Date {!! (!empty($tenderInfo->valid_date_from)) ? date('d F Y',strtotime($tenderInfo->valid_date_from)) : '' !!}.</td>
                </tr>
                @if(!empty($demandToLprInfo->head_ofc_apvl_status))
                <tr>
                    <td colspan="3" style=" font-size: 14px;">B .&nbsp;&nbsp;&nbsp;Naval Headquarters Letter no. {!! (!empty($tenderInfo->nhq_app_ltr_number)) ? $tenderInfo->nhq_app_ltr_number : '' !!} . Date {!! (!empty($tenderInfo->nhq_ltr_date)) ? date('d F Y',strtotime($tenderInfo->nhq_ltr_date)) : '' !!}.</td>
                </tr>
                @endif
            </table>
            <br>
            <table>
                <tr>
                    <?php
                        $totalAmountss = 0;
                        $unit_toss = 0;
                        $itemCount = 0;
                        foreach($selectedSupItemInfo[$dtcq->id] as $ssii){
                            if(!empty($demandToTenInfo->head_ofc_apvl_status))
                            {
                                $unit_toss = $ssii->itm_to_sup_nhq_app_qty;
                            }
                            else{
                                $unit_toss = $ssii->quoted_quantity;
                            }
                            $totalAmountss += $unit_toss*$ssii->unit_price;

                            $itemCount++;
                        }

                        $totalAmountAfterDiscount = $totalAmountss;

                    ?>
                    @if(!empty($demandToLprInfo->head_ofc_apvl_status))
                    <td colspan="3" style=" font-size: 15px;text-align: justify;"><span>1 .</span>&nbsp;&nbsp;In light of approval <span style="">
                            at</span> ref 'b', above mentioned item <span style=" ">
                            @if(array_sum(array_map("count", $selectedSupItemInfo)) <=1) is @else are @endif</span>
                        accepted by Bangladesh Navy as per demand
                        @if(!empty($tenderInfo->specification) || !empty($tenderInfo->specification_doc)) and specification @endif.
                        You are hereby awarded the @if($tenderInfo->invitation_for == "Purchase of Goods" || $tenderInfo->invitation_for == "Exchange") purchase @else work @endif order of Taka {!! ImageResizeController::custom_format($totalAmountAfterDiscount) !!}
                        ({!! OwnLibrary::numberTowords($totalAmountAfterDiscount) !!})
                        <span style="">{!! (!empty($podataInfo->import_duties)) ? $podataInfo->import_duties.' import duties' : '' !!}.
                        </span> As such you are requested to supply the item to <span style="">
                            {!! (!empty($podataInfo->supply_to)) ? $podataInfo->supply_to : '' !!}</span>
                        by complying the following conditions mentioned below:</td>
                        @else
                            <td colspan="3" style=" font-size: 15px;text-align: justify;"><span>1 .</span>&nbsp;&nbsp;Considering <span style="">
                            </span> ref 'B', tenders above mentioned items have been approved in princible by NSSD Dhaka complying with quotation of tender.
                                You are hereby awarded the @if($tenderInfo->invitation_for == "Purchase of Goods" || $tenderInfo->invitation_for == "Exchange") purchase @else work @endif order of Taka {!! ImageResizeController::custom_format($totalAmountAfterDiscount) !!}
                                ({!! OwnLibrary::numberTowords($totalAmountAfterDiscount) !!})
                                <span style="">{!! (!empty($podataInfo->import_duties)) ? $podataInfo->import_duties.' import duties' : '' !!}.
                        </span> As such you are requested to supply the item to <span style="">
                            {!! (!empty($podataInfo->supply_to)) ? $podataInfo->supply_to : '' !!}</span>
                                by complying the following conditions mentioned below:</td>
                        @endif

                    </tr>
                <tr style="height: 2mm;"></tr>
            <!-- </tbody> -->
        </table>
        <br>
@if($podataInfo->is_enclosure == 1)

        <table class="border"  style="width: 100%;margin-left: 25px;">
            <!-- <tbody  style="width: 215.9mm;"> -->
                <tr>
                    <td style="border: 0;  font-size: 14px;"></td>
                    <td style="font-size: 14px;">Ser</td>
                    <td style="font-size: 14px;">Description</td>
                    <td style="font-size: 14px;" class="text-center">T/Price (TK)</td>
                </tr>
                <?php 
                    $sl = 1; 
                    $totalAmount = 0;
	        $selectedSupItemInfo[$dtcq->id];
                ?>
                <tr>
                    <td style="border: 0"></td>
                    <td style="font-size: 14px;">{!! $sl++ !!}</td>
                    <td style="font-size: 14px;">{!! $tenderInfo->tender_title !!} <br>
                        <span style="font-size: 14px;">( {{$itemCount}} Line Item As Per Attach List )</span>
                    </td>
                    <td style="font-size: 14px;" class="text-center">{!! ImageResizeController::custom_format($totalAmountss) !!}</td>
                </tr>
                <!-- <tr>
                    <td style="width: 5mm; border: 0"></td>
                    <td style="width: 10mm;">&nbsp; 2</td>
                    <td style="width: 72mm;">&nbsp; Spare Parts Details mention in para 16</td>
                    <td>&nbsp; No</td>
                    <td>&nbsp; 02</td>
                    <td>&nbsp; 14,53,500/00</td>
                    <td>&nbsp; 29,07,000/00</td>
                </tr> -->
                <tr>
                    <td style="font-size: 14px; border: 0"></td>
                    <td colspan="2" style="text-align: right;">{!! ucfirst(OwnLibrary::numberTowords($totalAmountss)) !!}</td>
                    <td style="font-size: 14px;" class="text-center">{!! ImageResizeController::custom_format($totalAmountss) !!}</td>
                </tr>
                @if(!empty($dtcq->discount_amount))       
                <tr>
                    <td style="border: 0"></td>
                    <td colspan="2" style="text-align: right;">Price Reduction/Discount (-)</td>
                    <td style="font-size: 14px;" class="text-center">{!! ImageResizeController::custom_format($dtcq->discount_amount) !!}</td>
                </tr> 
                @endif
                <tr>
                    <td style="font-size: 14px; border: 0"></td>
                    <td colspan="2" style="text-align: right; font-size: 14px;">Grand total {!! OwnLibrary::numberTowords($totalAmountss-$dtcq->discount_amount) !!}</td>
                    <td style="font-size: 14px;text-align: center;" class="text-center">{!! ImageResizeController::custom_format($totalAmountss-$dtcq->discount_amount) !!}</td>
                </tr>  
                <tr style="height: 4mm;"></tr>
            <!-- </tbody> -->
        </table>
        @else
    <table class="border"  style="width: 100%;margin-left: 24px;">
        <tr>
            <td style="border: 0;  font-size: 14px;"></td>
            <td style="font-size: 14px;">Ser</td>
            <td style="font-size: 14px;">Description</td>
            <td style="font-size: 14px;" class="text-center">Deno&nbsp;</td>
            <td style="font-size: 14px;" class="text-center">Qty</td>
            <td style="font-size: 14px;" class="text-center">Unit Price(TK)</td>
            <td style="font-size: 14px;" class="text-center">T/Price (TK)</td>
        </tr>
		<?php
		$sl = 1;
		$totalAmount = 0;
		?>
        @foreach($selectedSupItemInfo[$dtcq->id] as $ssii)
            <tr>
                <td style="border: 0"></td>
                <td style="font-size: 14px;">{!! $sl++ !!}</td>
                <td style="font-size: 14px;">
                    {!! $ssii->item_item_name !!}
                        @if(!empty($ssii->item_model_number))<br>&nbsp;Model: {!! $ssii->item_model_number !!} @endif
                        @if(!empty($ssii->item_brand))<br />&nbsp;Brand: {!! $ssii->item_brand !!} @endif
                </td>
                <td style="font-size: 14px;" class="text-center">{!! $ssii->deno_name !!}</td>
                <td style="font-size: 14px;" class="text-center">&nbsp;
					<?php $unit_to = 0; ?>
                    @if(!empty($demandToTenInfo->head_ofc_apvl_status))

                        {!! $ssii->itm_to_sup_nhq_app_qty !!}
						<?php $unit_to = $ssii->itm_to_sup_nhq_app_qty; ?>
                    @else
                        {!! $ssii->quoted_quantity !!}
						<?php $unit_to = $ssii->quoted_quantity; ?>
                    @endif
                </td>
                <td style="font-size: 14px;" class="text-center">&nbsp;
					<?php $uniPrice = 0; ?>
                    @if($ssii->select_alternavtive_offer == 1)
                        {!! ImageResizeController::custom_format($ssii->alternative_unit_price) !!}
						<?php $uniPrice = $ssii->alternative_unit_price; ?>
                    @else
                        {!! ImageResizeController::custom_format($ssii->unit_price) !!}
						<?php $uniPrice = $ssii->unit_price; ?>
                    @endif
                </td>
                <td style="font-size: 14px;" class="text-center">{!! ImageResizeController::custom_format($unit_to*$uniPrice) !!}
					<?php $totalAmount += $unit_to*$uniPrice; ?>
                </td>
            </tr>
        @endforeach
    <!-- <tr>
                    <td style="width: 5mm; border: 0"></td>
                    <td style="width: 10mm;">&nbsp; 2</td>
                    <td style="width: 72mm;">&nbsp; Spare Parts Details mention in para 16</td>
                    <td>&nbsp; No</td>
                    <td>&nbsp; 02</td>
                    <td>&nbsp; 14,53,500/00</td>
                    <td>&nbsp; 29,07,000/00</td>
                </tr> -->
        <tr>
            <td style="font-size: 14px; border: 0"></td>
            <td colspan="5" style="text-align: right;">{!! ucfirst(OwnLibrary::numberTowords($totalAmountss)) !!} &nbsp;</td>
            <td style="font-size: 14px;" class="text-center">{!! ImageResizeController::custom_format($totalAmount) !!}</td>
        </tr>
        @if(!empty($dtcq->discount_amount))
            <tr>
                <td style="border: 0"></td>
                <td colspan="5" style="text-align: right;">&nbsp; Price Reduction/Discount (-) &nbsp;</td>
                <td style="font-size: 14px;" class="text-center">{!! ImageResizeController::custom_format($dtcq->discount_amount) !!}</td>
            </tr>
        @endif
        <tr>
            <td style="font-size: 14px; border: 0"></td>
            <td colspan="5" style="text-align: right; font-size: 14px;">&nbsp; Grand total {!! OwnLibrary::numberTowords($totalAmount-$dtcq->discount_amount) !!}</td>
            <td style="font-size: 14px;" class="text-center">{!! ImageResizeController::custom_format($totalAmount-$dtcq->discount_amount) !!}</td>
        </tr>
        <tr style="height: 4mm;"></tr>
        <!-- </tbody> -->
    </table>
    @endif

        <br>
        <table  style="width: 100%;">
            <!-- <tbody  style="width: 215.9mm;"> -->
                <tr>
                    <td style="font-size: 14px;"></td>                
                    <td style=" font-size: 14px;">Conditions:</td>            
                </tr> 
                <tr style="font-size: 14px;"></tr>
                <?php 
                        $termsConditions = $podataInfo->terms_conditions;
                        if(!empty($termsConditions)){
                            $termsConditions = explode('<br>', $termsConditions); 
                        }
                        $sln = 'a';
                ?>
                @if(!empty($termsConditions))
                    @foreach($termsConditions as $tc)
                    <tr>
                        <td style="font-size: 14px;"></td>
                        <td> <?php echo $sln++;?>. &nbsp;&nbsp;&nbsp; {!! $tc !!}.</td>
                    </tr>
                    @endforeach
                @endif

                <tr><td style=""></td><td> </td></tr>
                <tr><td style=""></td><td> </td></tr>
                <tr><td style=""></td><td> </td></tr>
                <tr><td style=""></td><td> </td></tr>
                <tr><td style=""></td><td> </td></tr>
                <tr><td style=""></td><td> </td></tr>
                <tr><td style=""></td><td> </td></tr>
                <tr>
                    <td style="font-size: 14px;">2.</td>
                    <td style="font-size: 14px;">
                        @if($podataInfo->is_contract_with == 1)
                            Contract for purchase enclosed herewith.
                            @else
                            This @if($tenderInfo->invitation_for == "Purchase of Goods" || $tenderInfo->invitation_for == "Exchange") purchase @else work @endif order will be consider as a contract for purchase.
                            @endif
                    </td>
                </tr>
                <tr style="height: 10mm;"></tr>
            <!-- </tbody> -->
        </table>
        <br>
        <table  style="width: 100%;">
            <!-- <tbody  style="width: 215.9mm;"> -->
                <tr>
                    <td style="width: 60%;"></td>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=""></td>
                    <td style="width: 20mm;"></td>
                    <td style="">

                        @if(!empty($podataInfo->poApprovalName->digital_sign))
                            <img src="{{url('public/uploads/digital_sign/'.$podataInfo->poApprovalName->digital_sign)}}" style="width: 100px;height: 50px;"/>
                            @endif
                    </td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=""></td>
                    <td ></td>
                    <td style="font-size: 14px;">
                        @if(!empty($podataInfo->poApprovalName))
                            {!! $podataInfo->poApprovalName->first_name.' '.$podataInfo->poApprovalName->last_name !!}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=""></td>
                    <td></td>
                    <td style="font-size: 14px;">
                        @if(!empty($podataInfo->poApprovalName))
                            {!! $podataInfo->poApprovalName->rank !!}
                        @endif
                    </td>
                </tr>                
                <tr>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=""></td>
                    <td ></td>
                    <td style="font-size: 14px;">
                        @if(!empty($podataInfo->poApprovalName))
                            {!! $podataInfo->poApprovalName->designation !!}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="height: 2mm" colspan="6"></td>
                </tr>
                @if(!empty($podataInfo->inclusser))
                <tr>
                    <td colspan="6" style=" font-size: 14px;"> Enclosure:</td>
                </tr>
                @endif
                <tr>
                    <td style="height: 2mm" colspan="6"></td>
                </tr>
                @if(!empty($podataInfo->inclusser))
                <tr>
                    <td colspan="6" style=" font-size: 14px;"> 1.&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;{!! nl2br($podataInfo->inclusser) !!}</td>
                </tr>
                @endif
                <tr>
                    <td style="height: 2mm" colspan="6"></td>
                </tr>
                <tr>
                    <td colspan="6" style="font-size: 14px;">Distribution:</td>
                </tr>
                <tr>
                    <td colspan="6" style="font-size: 14px;">Ext:</td>
                </tr>
                <tr>
                    <td colspan="6" style="font-size: 14px;">Action:</td>
                </tr>
                <tr>
                    <td style="height: 2mm;" colspan="6"></td>
                </tr>  
                <tr>
                    <?php $suppInfo = \App\Supplier::find($dtcq->supplier_name); ?>
                    <td colspan="6" style="font-size: 14px;">
                        {!! $suppInfo->company_name !!}<br>
                        {!! $suppInfo->trade_license_address !!}<br>
                        {!! $suppInfo->mobile_number !!}<br>
                        {!! $suppInfo->email !!}
                    </td>
                </tr>
                <tr style="height: 2mm;">
                    <td colspan="6"></td>
                </tr> 
                <tr>
                    <?php $suppInfo = \App\Supplier::find($dtcq->supplier_name); ?>
                    <td colspan="6" style="font-size: 14px;">Info:</td>
                </tr>
                <tr>
                    <td colspan="6">@if(!empty($podataInfo->info)) <span style="white-space: pre;"><?php echo nl2br($podataInfo->info);?> </span> @endif</td>
                </tr>
                
            <!-- </tbody> -->
        </table>
    @endforeach

    @if($podataInfo->is_enclosure == 1)

        <div class="page-break"></div>
        <br />
        <h2 class="underline text-center">Enclosure</h2>
        Tender Title: {!! $tenderInfo->tender_title !!}
        <table class="border"  style="width: 100%;margin-left: 24px;">
            <!-- <tbody  style="width: 215.9mm;"> -->
            <tr>
                <td style="border: 0;  font-size: 14px;"></td>
                <td style="font-size: 14px;">Ser</td>
                <td style="font-size: 14px;">Description</td>
                <td style="font-size: 14px;" class="text-center">Deno&nbsp;</td>
                <td style="font-size: 14px;" class="text-center">Qty</td>
                <td style="font-size: 14px;" class="text-center">Unit Price(TK)</td>
                <td style="font-size: 14px;" class="text-center">T/Price (TK)</td>
            </tr>
	        <?php
	        $sl = 1;
	        $totalAmount = 0;
	        ?>
            @foreach($selectedSupItemInfo[$dtcq->id] as $ssii)
                <tr>
                    <td style="border: 0"></td>
                    <td style="font-size: 14px;">{!! $sl++ !!}</td>
                    <td style="font-size: 14px;">
                        {!! $ssii->item_item_name !!}
                        @if(!empty($ssii->item_model_number))<br>&nbsp;Model: {!! $ssii->item_model_number !!} @endif
                        @if(!empty($ssii->item_brand))<br />&nbsp;Brand: {!! $ssii->item_brand !!} @endif
                    </td>
                    <td style="font-size: 14px;" class="text-center">{!! $ssii->deno_name !!}</td>
                    <td style="font-size: 14px;" class="text-center">&nbsp;
				        <?php $unit_to = 0; ?>
                        @if(!empty($demandToTenInfo->head_ofc_apvl_status))
                            {!! $ssii->itm_to_sup_nhq_app_qty !!}
					        <?php $unit_to = $ssii->itm_to_sup_nhq_app_qty; ?>
                        @else
                            {!! $ssii->quoted_quantity !!}
					        <?php $unit_to = $ssii->quoted_quantity; ?>
                        @endif
                    </td>
                    <td style="font-size: 14px;" class="text-center">&nbsp;
				        <?php $uniPrice = 0; ?>
                        @if($ssii->select_alternavtive_offer == 1)
                            {!! ImageResizeController::custom_format($ssii->alternative_unit_price) !!}
					        <?php $uniPrice = $ssii->alternative_unit_price; ?>
                        @else
                            {!! ImageResizeController::custom_format($ssii->unit_price) !!}
					        <?php $uniPrice = $ssii->unit_price; ?>
                        @endif
                    </td>
                    <td style="font-size: 14px;" class="text-center">{!! ImageResizeController::custom_format($unit_to*$uniPrice) !!}
				        <?php $totalAmount += $unit_to*$uniPrice; ?>
                    </td>
                </tr>
            @endforeach
        <!-- <tr>
                    <td style="width: 5mm; border: 0"></td>
                    <td style="width: 10mm;">&nbsp; 2</td>
                    <td style="width: 72mm;">&nbsp; Spare Parts Details mention in para 16</td>
                    <td>&nbsp; No</td>
                    <td>&nbsp; 02</td>
                    <td>&nbsp; 14,53,500/00</td>
                    <td>&nbsp; 29,07,000/00</td>
                </tr> -->
            <tr>
                <td style="font-size: 14px; border: 0"></td>
                <td colspan="5" style="text-align: right;">{!! ucfirst(OwnLibrary::numberTowords($totalAmountss)) !!} &nbsp;</td>
                <td style="font-size: 14px;" class="text-center">{!! ImageResizeController::custom_format($totalAmount) !!}</td>
            </tr>
            @if(!empty($dtcq->discount_amount))
                <tr>
                    <td style="border: 0"></td>
                    <td colspan="5" style="text-align: right;">&nbsp; Price Reduction/Discount (-) &nbsp;</td>
                    <td style="font-size: 14px;" class="text-center">{!! ImageResizeController::custom_format($dtcq->discount_amount) !!}</td>
                </tr>
            @endif
            <tr>
                <td style="font-size: 14px; border: 0"></td>
                <td colspan="5" style="text-align: right; font-size: 14px;">&nbsp; Grand total {!! OwnLibrary::numberTowords($totalAmount-$dtcq->discount_amount) !!}</td>
                <td style="font-size: 14px;" class="text-center">{!! ImageResizeController::custom_format($totalAmount-$dtcq->discount_amount) !!}</td>
            </tr>
            <tr style="height: 4mm;"></tr>
            <!-- </tbody> -->
        </table>
        <br />
        <br />
        <table  style="width: 100%;">
            <!-- <tbody  style="width: 215.9mm;"> -->
            <tr>
                <td style=""></td>
                <td style=""></td>
                <td style=""></td>
                <td style=""></td>
                <td style="width: 20mm;"></td>
                <td style="">
                    @if(!empty($podataInfo->poApprovalName->digital_sign))
                        <img src="{{url('public/uploads/digital_sign/'.$podataInfo->poApprovalName->digital_sign)}}" style="width: 100px;height: 50px;"/>
                    @endif
                </td>
            </tr>
            <tr>
                <td style=""></td>
                <td style=""></td>
                <td style=""></td>
                <td style=""></td>
                <td ></td>
                <td style="font-size: 14px;">
                    @if(!empty($podataInfo->poApprovalName))
                        {!! $podataInfo->poApprovalName->first_name.' '.$podataInfo->poApprovalName->last_name !!}
                    @endif
                </td>
            </tr>
            <tr>
                <td style=""></td>
                <td style=""></td>
                <td style=""></td>
                <td style=""></td>
                <td></td>
                <td style="font-size: 14px;">
                    @if(!empty($podataInfo->poApprovalName))
                        {!! $podataInfo->poApprovalName->rank !!}
                    @endif
                </td>
            </tr>
            <tr>
                <td style=""></td>
                <td style=""></td>
                <td style=""></td>
                <td style=""></td>
                <td ></td>
                <td style="font-size: 14px;">
                    @if(!empty($podataInfo->poApprovalName))
                        {!! $podataInfo->poApprovalName->designation !!}
                     @endif
                </td>
            </tr>
            <tr>
                <td style="height: 2mm" colspan="6"></td>
            </tr>
        </table>
        @endif

@if($podataInfo->is_contract_with == 1)
    {{--if page are contracted--}}
    <pagebreak resetpagenum="1" pagenumstyle="1" suppress="off" />

<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
    <h1 style="text-align: center;font-size: 44px;">চুক্তিপত্র</h1>

    <br /><br /><br />
    <table style="width:100%;">
        <tr>
            <td align="center">
                <img class="navy-logo" style="height: 100px;width: 120px;" src="{{URL::to('/')}}/public/img/bd-navy.png">
            </td>
        </tr>
    </table>

    <br /><br /><br />
    <h2 align="center">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার<br />
        প্রতিরক্ষা মন্ত্রনালয়<br />
        এনএসএসডি ঢাকা<br />
        নামাপাড়া,খিলক্ষেত,ঢাকা-১২২৯</h2>

    <br /><br /><br />
    <h3 align="center" style="font-size: 16px;"><span style="font-size: 20px;">সামগ্রীর নাম : </span> {{$tenderInfo->tender_title}}</h3><br />
    <h3 align="center"><span style="font-size: 20px;">সরবরাহকারী  : </span> {{!empty($suppInfo->company_name) ? $suppInfo->company_name : ''}}</h3>
    <h3 align="center">{{!empty($suppInfo->trade_license_address) ? $suppInfo->trade_license_address : ''}}</h3><br />
    <h3 align="center"><span style="font-size: 20px;">চুক্তিপত্র নং : </span> <u>{{!empty($podataInfo->po_number) ?$podataInfo->po_number : ''}}</u></h3>

    <h3 align="center"><span style="font-size: 20px;"><span style="font-size: 20px;">তারিখ</span> <u>{!! (!empty($podataInfo->top_date)) ? date('d F Y',strtotime($podataInfo->top_date)) : '' !!}</u></h3>


    <br /><br /><br />
    <p style="text-align: center;font-size: 44px;margin-bottom: 15px;">চুক্তিপত্র</p>
    <p align="center" style="font-size: 18px;">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার<br />
        প্রতিরক্ষা মন্ত্রনালয়<br />
        এনএসএসডি ঢাকা<br />
        নামাপাড়া,খিলক্ষেত,ঢাকা-১২২৯</p>,<br />
    <h3 align="center"><span style="font-size: 20px;">চুক্তিপত্র নং : </span> <u>{{!empty($podataInfo->po_number) ?$podataInfo->po_number : ''}}</u></h3>

    <h3 align="center"><span style="font-size: 20px;"><span style="font-size: 20px;">তারিখ</span> <u>{!! (!empty($podataInfo->top_date)) ? date('d F Y',strtotime($podataInfo->top_date)) : '' !!}</u></h3>

    <br />
    <p style="font-size: 18px;margin-left: 40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        এই চুক্তি <span style="font-size: 14px;">
            {!! (!empty($podataInfo->top_date)) ? date('Y',strtotime($podataInfo->top_date)) : '' !!}</span>
        সালের <span style="font-size: 14px;">{!! (!empty($podataInfo->top_date)) ? date('F',strtotime($podataInfo->top_date)) : '' !!}</span>
        মাসের অদ্য <span style="font-size: 14px;">{!! (!empty($podataInfo->top_date)) ? date('jS',strtotime($podataInfo->top_date)) : '' !!}</span>
        দিবস এক পক্ষ ভারপ্রাপ্ত কর্মকর্তা, নৌ-উপভাণ্ডার ঢাকা, নামাপাড়া খিলখেত, ঢাকা- ১২২৯ এবং তাহার অনুমোদিত অফিসারবৃন্দ (অতঃপর এই চুক্তি পত্রে ক্রেতা হিসাবে উল্লেখিত) ৷</p>
    <br />
    <p style="font-size: 18px;margin-left: 40px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        অপর পক্ষ ঠিকানা : (অতঃপর এই চুক্তিপত্রে সরবরাহকারী হিসাবে উল্লেখিত ) এতদ্বারা চুক্তিবদ্ধ হলেন যে, অপর পৃষ্ঠায় বর্ণিত তফসিল এবং এতে উল্লেখিত মূল্য
        বর্তমান চূক্তিপত্রে ও এফ ও -১/২০১৩ এর উল্লেখিত শর্তসাপেক্ষে ক্রেতা মালামাল ক্রয় করবেন এবং সরবরাহকারী তা বিক্রয় করবেন। </p>
    <br /><br /><br /><br /><br /><br /><br /><br /><br />

    @if($dtcq->total > $orgInfo->purchase_limit)
        <p style="text-align: center;font-size: 18px;"><u>এই চুক্তি সম্পাদনে নৌ বাহিনী সদর দপ্তরের অনুমোদন</u></p>
        <p style="font-size: 18px;margin-left: 40px;">প্রাধিকারঃ</p>
        <p style="font-size: 18px;margin-left: 40px;">ক। &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            নৌ সদর পত্র নং {!! (!empty($tenderInfo->nhq_app_ltr_number)) ? $tenderInfo->nhq_app_ltr_number : '' !!}
            তারিখ  <span style="font-size: 14px;">{!! (!empty($tenderInfo->nhq_ltr_date)) ? date('d F Y',strtotime($tenderInfo->nhq_ltr_date)) : '' !!}</span></p>
        @endif

    <table  style="width: 100%;">
        <!-- <tbody  style="width: 215.9mm;"> -->
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 20mm;"></td>
            <td style="">
                @if(!empty($podataInfo->poCheckerName->digital_sign))
                    <img src="{{url('public/uploads/digital_sign/'.$podataInfo->poCheckerName->digital_sign)}}" style="width: 100px;height: 50px;"/>
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poCheckerName))
                {!! $podataInfo->poCheckerName->first_name.' '.$podataInfo->poCheckerName->last_name !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poCheckerName))
                {!! $podataInfo->poCheckerName->rank !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poCheckerName))
                {!! $podataInfo->poCheckerName->designation !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                Local Purchase Officer
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                 {!! (!empty($orgInfo->name)) ? $orgInfo->name : '' !!}
            </td>
        </tr>
        <tr>
            <td style="height: 2mm" colspan="6"></td>
        </tr>
    </table>

    <br /><br /><br /><br />

    <p style="font-size: 18px;margin-left: 40px;font-weight: 700">চুক্তিপত্র নং : <span style="font-size: 14px;"> <u>{{!empty($podataInfo->po_number) ?$podataInfo->po_number : ''}}</u></span></p>

    <p style="font-size: 18px;margin-left: 40px;font-weight: 700">তারিখ <span style="font-size: 14px;"><u>{!! (!empty($podataInfo->top_date)) ? date('d F Y',strtotime($podataInfo->top_date)) : '' !!}</u></span></p>

    <p style="font-size: 18px;margin-left: 40px;">এর অধীনে যেসব মালামাল সরবরাহ করা হবে তার তফসিল :</p>

    <table class="border"  style="width: 100%;margin-left: 40px;">
        <!-- <tbody  style="width: 215.9mm;"> -->
        <tr>
            <td style="border: 0;  font-size: 14px;"></td>
            <td style="font-size: 14px;">&nbsp;Ser</td>
            <td style="font-size: 14px;">&nbsp; Description</td>
            <td style="font-size: 14px;">&nbsp;&nbsp;&nbsp;Deno&nbsp;</td>
            <td style="font-size: 14px;">&nbsp;&nbsp;&nbsp;Qty</td>
            <td style="font-size: 14px;">&nbsp;&nbsp;&nbsp;Unit Price(TK)</td>
            <td style="font-size: 14px;">&nbsp;&nbsp;&nbsp;T/Price (TK)</td>
        </tr>
	    <?php
	    $sl = 1;
	    $totalAmount = 0;
	    ?>
        @foreach($selectedSupItemInfo[$dtcq->id] as $ssii)
            <tr>
                <td style="border: 0"></td>
                <td style="font-size: 14px;">&nbsp; {!! $sl++ !!}</td>
                <td style="font-size: 14px;">&nbsp;
                    {!! $ssii->item_item_name !!}
                        @if(!empty($ssii->item_model_number))<br>&nbsp;Model: {!! $ssii->item_model_number !!} @endif
                        @if(!empty($ssii->item_brand))<br />&nbsp;Brand: {!! $ssii->item_brand !!} @endif
                </td>
                <td style="font-size: 14px;">&nbsp; {!! $ssii->deno_name !!}</td>
                <td style="font-size: 14px;">&nbsp;
				    <?php $unit_to = 0; ?>
                    @if(!empty($demandToTenInfo->head_ofc_apvl_status))
                        {!! $ssii->itm_to_sup_nhq_app_qty !!}
					    <?php $unit_to = $ssii->itm_to_sup_nhq_app_qty; ?>
                    @else
                        {!! $ssii->quoted_quantity !!}
					    <?php $unit_to = $ssii->quoted_quantity; ?>
                    @endif
                </td>
                <td style="font-size: 14px;">&nbsp;
				    <?php $uniPrice = 0; ?>
                    @if($ssii->select_alternavtive_offer == 1)
                        {!! ImageResizeController::custom_format($ssii->alternative_unit_price) !!}
					    <?php $uniPrice = $ssii->alternative_unit_price; ?>
                    @else
                        {!! ImageResizeController::custom_format($ssii->unit_price) !!}
					    <?php $uniPrice = $ssii->unit_price; ?>
                    @endif
                </td>
                <td style="font-size: 14px;">&nbsp;
                    {!! ImageResizeController::custom_format($unit_to*$uniPrice) !!}
				    <?php $totalAmount += $unit_to*$uniPrice; ?>
                </td>
            </tr>
        @endforeach
        <tr>
            <td style="font-size: 14px; border: 0"></td>
            <td colspan="5" style="text-align: right;">&nbsp; Total Taka &nbsp;</td>
            <td style="font-size: 14px;">&nbsp; {!! ImageResizeController::custom_format($totalAmount) !!}</td>
        </tr>
        <tr style="height: 4mm;"></tr>
        <!-- </tbody> -->
    </table>
 <p style="margin-left: 40px;font-weight: bold;font-size: 18px;"><u><strong>বিশেষ শর্তাবলী <span style="font-size: 14px;">(SPECIAL CONDITION)</span></strong></u></p>

 <p style="margin-left: 40px;font-size: 18px;">ক । &nbsp;&nbsp;&nbsp;&nbsp; চুক্তিকৃত সমস্ত সামগ্রী চুক্তিপত্রের সাথে এনেক্স -বি হিসাবে সংযুক্ত চুক্তির বির্নিদেশ
     <span style="font-size: 14px;">(Contract Specification)</span> মোতাবেক বাংলাদেশ নৌবাহিনীর অনূকুলে
     @if ($tenderInfo->invitation_for == "Purchase of Goods" || $tenderInfo->invitation_for == "Exchange")
         সরবরাহ
     @else
         কার্য
     @endif
     সম্পন্ন করতে হবে । </p>
<?php
    $deliveryDays = !empty($tenderInfo->delivery_date) ? (int) $tenderInfo->delivery_date : 0;
    $deliveryDaysAdd = date('d F Y', strtotime($podataInfo->top_date. ' + '.$deliveryDays.' days'));
?>
    <p style="margin-left: 40px;font-size: 18px;">খ । &nbsp;&nbsp;&nbsp;&nbsp; চুক্তিকৃত সামগ্রী চুক্তি স্বাক্ষরের পর হতে <span style="font-size: 14px;">{{$deliveryDaysAdd}}</span> তারিখের মধ্যে পরিদর্শন কর্মকর্তা কর্তৃক
      পরিদর্শন করত:
        @if ($tenderInfo->invitation_for == "Purchase of Goods" || $tenderInfo->invitation_for == "Exchange")
            সরবরাহ
        @else
            কার্য সম্পাদন
        @endif

        করতে হবে। সুম্পূর্ণ
        @if ($tenderInfo->invitation_for == "Purchase of Goods" || $tenderInfo->invitation_for == "Exchange")
            মালামাল সরবরাহ
        @else
            কার্য
        @endif
        সম্পন্ন হওয়ার পরই বিল পরিশোধ করা হবে ৷</p>

    <p style="margin-left: 40px;font-size: 18px;">গ ৷ &nbsp;&nbsp;&nbsp;&nbsp; চুক্তিকৃত মালামালের মূল্য আমদানি শুল্ক ব্যতিত এবং প্রযোজ্য ট্যাক্স ও ভ্যাটসহ
        চূড়ান্ত করা হয়েছে ৷ বিল পরিশোধের সময় প্রযোজ্য ট্যাক্স ও ভ্যাট এসএফসি(নৌ) কর্তৃক বিধি মোতাবেক কর্তন করা হবে ৷</p>

    <p style="margin-left: 40px;font-size: 18px;">ঘ ৷ &nbsp;&nbsp;&nbsp;&nbsp; আংশিক

        @if ($tenderInfo->invitation_for == "Purchase of Goods" || $tenderInfo->invitation_for == "Exchange")
            মালামাল সরবরাহ
        @else
            কার্য
        @endif

        এবং আংশিক বিল পরিশোধ গ্রহনযোগ্য হবে না ৷</p>

    <p style="margin-left: 40px;font-size: 18px;">ঙ ৷ &nbsp;&nbsp;&nbsp;&nbsp; সমস্ত মালামাল সরবরাহকারীর নিজ দায়িত্বে ও নিজ খরচে গ্রহীতার নিকট পোছাইতে হবে।</p>

    <p style="margin-left: 40px;font-size: 18px;">চ ৷ &nbsp;&nbsp;&nbsp;&nbsp;
        @if ($tenderInfo->invitation_for == "Purchase of Goods" || $tenderInfo->invitation_for == "Exchange")
            সরবরাহের
        @else
            কার্যের
        @endif

        পর কোন  আইটেম ব্যবহারের  অনুপযোগী পাওয়া গেলে  সরবরাহকারীকে নিজ ও নিজ দায়িত্বে খরচ উক্ত আইটেম পরিবর্তন করে দিতে হবে ।</p>

    <p style="margin-left: 40px;font-size: 18px;">ছ ৷ &nbsp;&nbsp;&nbsp;&nbsp; সামগ্রী সমূহ

        @if ($tenderInfo->invitation_for == "Purchase of Goods" || $tenderInfo->invitation_for == "Exchange")
            আসল, নতুন এবং
        @else
            কার্যের পর
        @endif
         ব্যবহার উপযোগী হতে হবে।</p>

    <p style="margin-left: 40px;font-size: 18px;">জ ৷ &nbsp;&nbsp;&nbsp;&nbsp; দরপত্রে উল্লেখিত শর্তাবলী সমূহ চুক্তিপত্রের শর্তাবলী হিসেবে গণ্য হবে ।  যদি দরপত্র এবং চুক্তিপত্রের একই
        শর্তাবলী পরস্পর বিরোধী/ভিন্নতর হয় সেক্ষেত্রে চুক্তিপত্রের শর্তাবলী চূড়ান্ত বলে গণ্য হবে ।</p>
    <table  style="width: 100%;">
        <!-- <tbody  style="width: 215.9mm;"> -->
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 20mm;"></td>
            <td style="">
                @if(!empty($podataInfo->poCheckerName->digital_sign))
                    <img src="{{url('public/uploads/digital_sign/'.$podataInfo->poCheckerName->digital_sign)}}" style="width: 100px;height: 50px;"/>
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poCheckerName))
                {!! $podataInfo->poCheckerName->first_name.' '.$podataInfo->poCheckerName->last_name !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poCheckerName))
                {!! $podataInfo->poCheckerName->rank !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poCheckerName))
                {!! $podataInfo->poCheckerName->designation !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                Local Purchase Officer
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                 {!! (!empty($orgInfo->name)) ? $orgInfo->name : '' !!}
            </td>
        </tr>
        <tr>
            <td style="height: 2mm" colspan="6"></td>
        </tr>
    </table>

    <h4 style="margin-left: 40px;font-size: 18px;"><u><strong>শর্তাবলী</strong></u></h4>
<?php
        $itemList = array();
    foreach($selectedSupItemInfo[$dtcq->id] as $ssii){
	    $itemListFak = \App\Item::find($ssii->real_item_id);
	    array_push($itemList, $itemListFak);
    }
    $countryNameArray = array_column($itemList, 'manufacturing_country');
    $countryName      = implode(',',$countryNameArray);
?>
    <table style="width: 100%;margin-left: 40px;">
        <tr>
            <td style="font-size: 18px;vertical-align: top;">১।  মালামালের চাহিদা কারীর নাম</td>
            <td style="vertical-align: top;padding: 0 10px;">:</td>
            <td style="font-size: 14px;vertical-align: top;">
                {{$tenderInfo->demending}}
            </td>
        </tr>
        <tr>
        <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td style="font-size: 18px;vertical-align: top;">২ । চাহিদা পত্র নং তারিখ</td>
            <td style="vertical-align: top;padding: 0 10px;">:</td>
            <td style="font-size: 18px;vert-align: top;">
                প্রযোজ্য নয়।
            </td>
        </tr>
        <tr>
        <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td style="font-size: 18px;vertical-align: top;">৩।  যে খাতে খরচ লেখা হবে </td>
            <td style="vertical-align: top;padding: 0 10px;">:</td>
            <td style="font-size: 14px;vertical-align: top">
            	@if(!empty($budgetCodeS))
                    @foreach($budgetCodeS as $budgetCode)
                        {{ $budgetCode->code." ($budgetCode->description)" }}
                        @if(count($budgetCodeS) > 1 && $loop->last != true)
                            ,
                        @endif
                    @endforeach 
                @endif .
            </td>
        </tr>
        <tr>
        <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td style="font-size: 18px;vertical-align: top">৪ । চালান গ্রহীতা </td>
            <td style="vertical-align: top;padding: 0 10px;">:</td>
            <td style="font-size: 14px;vertical-align: top;">{{!empty($podataInfo->supply_to) ? $podataInfo->supply_to : ""}}</td>
        </tr>
        <tr>
        <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td style="font-size: 18px;vertical-align: top">৫। মালামাল
                @if ($tenderInfo->invitation_for == "Purchase of Goods" || $tenderInfo->invitation_for == "Exchange")
                    সরবরাহের
                @else
                    কার্যের
                @endif
                 শর্তাবলী ও তারিখ </td>
            <td style="vertical-align: top;padding: 0 10px;">:</td>
            <td style="font-size: 14px;vertical-align: top;">{{$deliveryDaysAdd}}<span style="font-size: 18px;"> তারিখের মধ্যে  সরবরাহ করতে হবে</span></td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td style="font-size: 18px;vertical-align: top">৬। প্রস্তুতকারীর/সরবরাহকারীর নাম ও পণ্যচিহ্ন</td>
            <td style="vertical-align: top;padding: 0 10px;">:</td>
            <td style="font-size: 14px;vertical-align: top">{{!empty($suppInfo->company_name) ? $suppInfo->company_name : ''}},
                {{!empty($suppInfo->trade_license_address) ? $suppInfo->trade_license_address : ''}}
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td style="font-size: 18px;vertical-align: top">৭।
                @if ($tenderInfo->invitation_for == "Purchase of Goods" || $tenderInfo->invitation_for == "Exchange")
                    মালামাল প্রস্তুতকারক
                @else
                    কার্য সম্পাদনের
                @endif
                 দেশ </td>
            <td style="vertical-align: top;padding: 0 10px;">:</td>
            <td style="font-size: 18px;vertical-align: top">{{ !empty($countryName)? $countryName:"প্রযোজ্য নয়।" }}</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td style="font-size: 18px;vertical-align: top">৮।  প্রেরণ/জাহাজে পরিবহন সংক্রান্ত নির্দেশ </td>
            <td style="vertical-align: top;padding: 0 10px;">:</td>
            <td style="font-size: 14px;vertical-align: top">As Per Conditions.</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td style="font-size: 18px;vertical-align: top">৯ । প্যাককরন ও চিহ্নিতকরণ ইত্যাদি </td>
            <td style="vertical-align: top;padding: 0 10px;">:</td>
            <td style="font-size: 18px;vertical-align: top"> আমদানিকৃত আইটেমের গায়ে অবশ্যই বাংলাদেশ নৌবাহিনী <span style="font-size: 14px;">(BD Navy)</span> কথাটি বড় অক্ষরে লিখতে হবে</td>
        </tr>
    </table>
    <p style="margin-left: 40px;font-size: 18px;">১০।  পরিদর্শন : পরিদর্শনের জন্য নিম্ন বর্ণিত পদ্ধতি অনুসরন করা হবে :</p>
    <p style="margin-left: 80px;font-size: 18px;">ক ৷ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;পরিদর্শন কর্তৃপক্ষ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;<span style="font-size: 14px;">{{!empty($podataInfo->inspection_Authority) ? $podataInfo->inspection_Authority : ""}}</span></p>
    <p style="margin-left: 80px;font-size: 18px;">খ । &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;পরিদর্শনের স্থান &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;<span style="font-size: 14px;">{{!empty($podataInfo->supply_to) ? $podataInfo->supply_to : ""}}</span></p>
    <p style="margin-left: 80px;font-size: 18px;text-align: justify;">গ ৷ &nbsp;&nbsp;&nbsp;অনুমোদিত পরিদর্শন কর্তৃপক্ষ চুক্তিপত্রের তফসিলের চাহিদা অনুযায়ী মালামাল পরীক্ষা করে দেখবেন ৷ পরিদর্শন কর্তৃপক্ষের
        সিদ্ধান্ত <!--, প্রয়োজন ক্ষেত্রে নৌ সদর দপ্তর কর্তৃক সমর্থিত হলে,--> চূড়ান্ত বলে গণ্য হবে ৷ <!--কোনমত পার্থক্য দেখা দিলে সরবরাকারী পরিদর্শন কর্তৃপক্ষের  সিদ্ধান্তের ১৪ দিনের মধ্যে
        "সহকারী নৌ প্রধান (লজিষ্টিক) এর নিকট আপিল করতে পারবে" সহকারী নৌ প্রধান (লজিষ্টিক) এর সিদ্ধান্ত চূড়ান্ত ও
        সর্বশেষ বলে গণ্য হবে ৷ --></p>
    <p style="margin-left: 80px;font-size: 18px;text-align: justify;">ঘ ৷ &nbsp;&nbsp;&nbsp; সরবরাহকারী এই প্রকার পরিদর্শন কাজের সকল ব্যয় বহন করবেন
        এবং অতিরিক্ত অর্থ দাবী না করে পরিদর্শন করে পরিদর্শন কাজে পরিদর্শক কর্তৃক অত্যাবশক বলে বিবেচিত যাবতীয় সামগ্রী যন্ত্রপাতি, শ্রমিক এবং সহায়তা প্রদান ইত্যাদির ব্যবস্থা করবেন।
        পরীক্ষা কাজের জন্য পরিদর্শক যে স্থানে যেসব জিনিসের প্রয়োজন বোধ করবেন সরবরাহকারী তা বিনামূল্যে সরবরাহ করবেন।  ল্যাবরেটরির পরীক্ষার ব্যয় এবং ল্যাবরেটরিতে নমুনা
        সরবরাহ বাবদ ব্যয় ও ডিপি-৩৫ এর ১২(ক) ও (খ) উপ-ধারা <span style="font-size: 14px;">(Clause)</span> অনুযায়ী বহন
        করবেন ৷</p>
    <table style="margin-left: 40px;font-size: 18px;">
        <tr>
            <td style="width: 40px;vertical-align: top">১১ ।</td>
            <td> <u><strong>অর্থ পরিশোধের শর্তাবলী ৷</strong></u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;যথানিয়মে গ্রহণযোগ্যতার সনদপত্রসহ বিল পেশ
                করা হলে <span style="font-size: 14px;">{{!empty($suppInfo->company_name) ? $suppInfo->company_name : ''}}, {{!empty($suppInfo->trade_license_address) ? $suppInfo->trade_license_address : ''}}</span> কে মোট মূল্যের শতকরা একশত ভাগ জ্যেষ্ঠ অর্থ নিয়ন্ত্রক (নৌ) , ঢাকা কর্তৃক পরিশোধ করা হবে ৷ প্ৰদেয় বিল সরবরাহকারী কর্তৃক একটি সহগামী পত্রের মাধ্যমে
                <span style="font-size: 14px;">OIC NSSD</span> এর বরাবর সরাসরি দাখিল করতে হবে ৷ শতভাগ মালামাল সরবরাহ এবং সিআইএনএস কর্তৃক গ্রহণযোগ্যতা সাপেক্ষে পেমেন্ট/বিল পরিশোধ করা হবে। </td>
        </tr>
    </table>
    <table  style="width: 100%;">
        <!-- <tbody  style="width: 215.9mm;"> -->
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 20mm;"></td>
            <td style="">
                @if(!empty($podataInfo->poCheckerName->digital_sign))
                    <img src="{{url('public/uploads/digital_sign/'.$podataInfo->poCheckerName->digital_sign)}}" style="width: 100px;height: 50px;"/>
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poCheckerName))
                {!! $podataInfo->poCheckerName->first_name.' '.$podataInfo->poCheckerName->last_name !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poCheckerName))
                {!! $podataInfo->poCheckerName->rank !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poCheckerName))
                {!! $podataInfo->poCheckerName->designation !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                Local Purchase Officer
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                 {!! (!empty($orgInfo->name)) ? $orgInfo->name : '' !!}
            </td>
        </tr>
        <tr>
            <td style="height: 2mm" colspan="6"></td>
        </tr>
    </table>

<br />
    <table style="margin-left: 40px;font-size: 18px;">
        <tr>
            <td style="width: 40px;vertical-align: top">১২ ।</td>
            <td style="text-align: justify;"> <u><strong>সরবরাহের তারিখ/মেয়াদ উত্তীর্ণ হওয়ার পরে পরিদর্শন ৷</strong></u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;পরিদর্শন কর্তৃক
                সরবরাহের তারিখ উত্তীর্ণ হওয়ার পর কেবল সেসব ক্ষেত্রে মালামাল পরিদর্শনের কাজ অব্যাহত রাখবেন যেখানে পণ্য উৎপাদনের কাজ চলছে, তবে শর্ত থেকে যে মালামাল
                গুণগতভাবে উচ্চমান সম্পন্ন ও গ্রহণযোগ্য হতে হবে, এর ফলে অবশ্য জরিমানাসহ বা জরিমানা ছাড়া সরবরাহের সময় বর্ধিত করার ব্যাপারে ক্রেতার অধিকার ক্ষুন্ন হবে না।</td>
        </tr>
    </table>
    <br />
    <table style="margin-left: 40px;font-size: 18px;">
        <tr>
            <td style="width: 40px;vertical-align: top">১৩ ।</td>
            <td> <u><strong>মূল্য হ্রাস ।</strong></u></td>
        </tr>
        <tr>
            <td style="width: 40px;vertical-align: top"></td>
            <td style="text-align: justify;font-size: 18px;"> (ক)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;পরিদর্শনের জন্য সরবরাহকারী কর্তৃক পেশকৃত নমুনা নির্ধারিত মান এনেক্স-বি অনুযায়ী না হলে পরিদর্শক সরবরাহকারীকে পরিদর্শনের জন্য নতুন করে মাল গুদামজাত
                করার জন্য বলতে পারবেন, অথবা ব্যত্যয় করে পেশকৃত মাল হ্রাসকৃত মূল্যে গ্রহণের জন্য প্রস্তাব করতে পারবেন। সংশ্লিষ্ট মূল্য হ্রাস পরিদর্শন কর্তৃপক্ষ কর্তৃক স্থিরীকৃত হবে
                এবং সরবরাহকারীর এতে সম্মতি থাকতে হবে ৷ সরবরাহকারী কর্তৃক এই মূল্য হ্রাস গ্রহণযোগ্য হলে সংশ্লিষ্ট গ্রহণযোগ্যতার  সনদপত্রে নিম্নরূপ পৃষ্ঠাঙ্কিত হতে হবে ।
                <br />
                <br />
                "উপযুক্ত কর্তৃপক্ষের  চূড়ান্ত অনুমোদন /সিদ্ধান্ত সাপেক্ষে..........................................................................
                <br />
                <br />
                তারিখের.......................................................................নং ব্যত্যয় ফরমে  সুপারিশ অনুযায়ী ব্যত্যয় করে নির্দিষ্ট হ্রাসসহ গৃহীত"
                <br />
                <br />
                (খ) যদি সরবরাহকারী এনেক্স-বি/অনুমোদিত নমুনা অনুযায়ী নতুন করে মালামাল সরবরাহ করতে চান তবে তাকে নিয়ম অনুযায়ী নতুন মাল সরবরাহে যে বিলম্বে হতে পারে তার জন্য আর্থিক ক্ষতিপূরণ দিতে হবে ৷
                <br />
                <br />
                (গ) তবে মূল্য হ্রাসের বিষয়টি চূড়ান্তভাবে ক্রেতা কর্তৃক স্থিরকৃত এবং যেখানে প্রয়োজন হয়, চুক্তিপত্রে সংশ্লিষ্ট আনুষ্ঠানিক সংশোধন না হওয়া পর্যন্ত সরবরাহকারী সেসব মালের জন্য বিল দাখিল বা পেশ করা যাবে না।
        </tr>
    </table>
    <br />
    <table style="margin-left: 40px;font-size: 18px;">
        <tr>
            <td style="width: 40px;vertical-align: top">১৪ ।</td>
            <td style="text-align: justify;"> <u><strong>নির্ধারিত সময়ের মধ্যে
                        @if ($tenderInfo->invitation_for == "Purchase of Goods" || $tenderInfo->invitation_for == "Exchange")
                            মালামাল সরবরাহে
                        @else
                            কার্য সম্পাদনে
                        @endif

                        ব্যর্থতা ।</strong></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;চুক্তিপত্রে উল্লেখিত সময়ের মধ্যে এবং এনেক্স-বি অনুযায়ী মালামাল সরবরাহ করতে
                ব্যর্থ হলে ভারপ্রাপ্ত কর্মকর্তা, এনএসএসডি ঢাকা কর্তৃক চুক্তিপত্র বাতিল করার এবং সরবরাহকারীর সাথে কোনো প্রকার যোগাযোগ ব্যতিরেকে সরবরাহকারীর ঝুকি ও নিরাপত্তা
                ব্যয়ের বিনিময়ে মালামাল ক্রয় করার অধিকার থাকবে। সরবরাহকারীর জামানত বাতিল করা ছাড়াও এই অতিরিক্ত ব্যবস্থা গ্রহণ করা যেতে পারে।  চুক্তি অনুযায়ী যে মালামাল
                সরবরাহ করতে ঠিকাদার ব্যর্থ হয়েছেন, তাহা নতুন করে ক্রয় করতে যদি সরকারকে অতিরিক্ত অৰ্থ ব্যয় করতে হয় তাহা হলে সরবরাহকারী সেই অৰ্থ প্রদান করতে বাধ্য
                থাকিবেন।</td>
        </tr>
    </table>
    <br />
    <table style="margin-left: 40px;font-size: 18px;">
        <tr>
            <td style="width: 40px;vertical-align: top">১৫ ।</td>
            <td style="text-align: justify;"> <u><strong>নির্ভরপত্র/জামিন।</strong></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 14px;">(Warrantee)</span> চুক্তির ক্রোড়পত্র এনেক্স-বি মোতাবেক হতে হবে।</td>
        </tr>
    </table>
    <br />
    <table style="margin-left: 40px;font-size: 18px;">
        <tr>
            <td style="width: 40px;vertical-align: top">১৬ ।</td>
            <td style="text-align: justify;"> <u><strong>পূরণীয় আর্থিক ক্ষতি ।</strong></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                শর্তানুযায়ী সরবরাহকারী মালামাল সরবরাহে ব্যর্থজনিত কারনে ভারপ্রাপ্ত কর্মকর্তা, এনএসএসডি সরবরাহ সময় সীমার অতিরিক্ত প্রত্যেক মালামাল বা তার অংশ বিশেষের
                জন্য অসর্বরাহকৃত মালামালের মূল্যের শতকরা ২% হরে কিন্তু ১% হারের কম নয়, পূরণীয় আর্থিক ক্ষতি নির্ধারণ করতে পারবেন । তবে শর্ত এইযে মোট পূরণীয় আর্থিক
                ক্ষতির পরিমাণ কোন অবস্থাবেই সর্বমোট চক্তিমূল্যের শতকরা ১০% এর বেশী হতে পারবে না এবং জেষ্ঠ্য অর্থ নিয়ন্ত্রক (নৌ) কর্তৃক উৎসহতে আদায় করতে হবে।
            </td>
        </tr>
    </table>
    <br />
    <table style="margin-left: 40px;font-size: 18px;">
        <tr>
            <td style="width: 40px;vertical-align: top">১৭ ।</td>
            <td style="text-align: justify;"> <u><strong>নির্ধারিত সময়ের মধ্যে
                        @if ($tenderInfo->invitation_for == "Purchase of Goods" || $tenderInfo->invitation_for == "Exchange")
                            মালামাল সরবরাহে
                        @else
                            কার্য সম্পাদনে
                        @endif
                        ব্যর্থতা ।</strong></u> চুক্তিপত্রে উল্লেখিত সময়ের মধ্যে এবং এনেক্স-বি অনুযায়ী মালামাল সরবরাহ করতে ব্যর্থ হলে ভারপ্রাপ্ত কর্মকর্তা, এনএসএসডি ঢাকা কর্তৃক চুক্তিপত্র বাতিল
                করার এবং সরবরাহকারীর সাথে কোনো প্রকার যোগাযোগ ব্যতিরেকে সরবরাহকারীর ঝুকি ও নিরাপত্তা ব্যয়ের বিনিময়ে মালামাল ক্রয় করার অধিকার থাকবে। সরবরাহকারীর
                জামানত বাতিল করা ছাড়াও এই অতিরিক্ত ব্যবস্থা গ্রহণ করা যেতে পারে।  চুক্তি অনুযায়ী যে মালামাল সরবরাহ করতে ঠিকাদার ব্যর্থ হয়েছেন, তাহা নতুন করে ক্রয় করতে
                যদি সরকারকে অতিরিক্ত অৰ্থ ব্যয় করতে হয় তাহা হলে সরবরাহকারী সেই অৰ্থ প্রদান করবেন। মালামাল সরবরাহে সময় বর্ধিত সংক্রান্ত কোন আবেদন গ্রহন করা হবে না।</td>
        </tr>
    </table>
    <table  style="width: 100%;">
        <!-- <tbody  style="width: 215.9mm;"> -->
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 20mm;"></td>
            <td style="">
                @if(!empty($podataInfo->poCheckerName->digital_sign))
                    <img src="{{url('public/uploads/digital_sign/'.$podataInfo->poCheckerName->digital_sign)}}" style="width: 100px;height: 50px;"/>
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poCheckerName))
                {!! $podataInfo->poCheckerName->first_name.' '.$podataInfo->poCheckerName->last_name !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poCheckerName))
                {!! $podataInfo->poCheckerName->rank !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poCheckerName))
                {!! $podataInfo->poCheckerName->designation !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                Local Purchase Officer
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                 {!! (!empty($orgInfo->name)) ? $orgInfo->name : '' !!}
            </td>
        </tr>
        <tr>
            <td style="height: 2mm" colspan="6"></td>
        </tr>
    </table>

    <br />
    <table style="margin-left: 40px;font-size: 18px;">
        <tr>
            <td style="width: 40px;vertical-align: top">১৭ ।</td>
            <td style="text-align: justify;">ক্রোড়পত্র ৷ নিম্নলিখিত ক্রোড়পত্র অন্তর্ভুক্ত করা হলো ৷</td>
        </tr>
    </table>
    <br /><br />
    <table class="border" style="margin-left: 80px;font-size: 18px;width: 100%;">
        <tr>
            <td style="text-align: center;width: 100px;">ক্রমিক</td>
            <td style="text-align: center;">সূচিপত্র</td>
            <td style="text-align: center;">নথিপত্রের বিষয়বস্তু</td>
            <td style="text-align: center;">পৃষ্ঠা সংখ্যা</td>
        </tr>
        <tr>
            <td style="width: 40px;vertical-align: top;padding-left: 10px;">১ ।</td>
            <td style="text-align: center;">এনেক্স-এ</td>
            <td>আর্থিক সংশ্লেষ</td>
            <td style="text-align: center;">____পাতা</td>
        </tr>
        <tr>
            <td style="width: 40px;vertical-align: top;padding-left: 10px;">২ ।</td>
            <td style="text-align: center;">এনেক্স-বি</td>
            <td>চুক্তি বির্নিদেশ</td>
            <td style="text-align: center;">____পাতা</td>
        </tr>
    </table>
    <br /><br />
    <table style="margin-left: 100px;font-size: 14px;width: 100%;">
        <tr>
            <td style="vertical-align: top;width: 60%;">For and On Behalf of Supplier</td>
            <td>For and on behalf of,<br />Bangladesh Navy</td>
        </tr>
    </table>

    <br /><br /><br /><br /><br /><br /><br /><br /><br />
    <table style="margin-left: 100px;font-size: 14px;width: 100%;">
        <tr>
            <td style="vertical-align: top;width: 60%;"></td>
            <td >
                @if(!empty($podataInfo->poApprovalName->digital_sign))
                    <img src="{{url('public/uploads/digital_sign/'.$podataInfo->poApprovalName->digital_sign)}}" style="width: 100px;height: 50px;"/>
                @endif
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;width: 60%;"></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poApprovalName))
                    {!! $podataInfo->poApprovalName->first_name.' '.$podataInfo->poApprovalName->last_name !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;width: 60%;"></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poApprovalName))
                    {!! $podataInfo->poApprovalName->rank !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;width: 60%;"></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poApprovalName))
                    {!! $podataInfo->poApprovalName->designation !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;width: 60%;">Dated: {!! (!empty($podataInfo->top_date)) ? date('d F Y',strtotime($podataInfo->top_date)) : '' !!}</td>
            <td >Dated: {!! (!empty($podataInfo->top_date)) ? date('d F Y',strtotime($podataInfo->top_date)) : '' !!}</td>
        </tr>
        <tr>
            <td><br /></td>
            <td><br /></td>
        </tr>
        <tr>
            <td><u><strong>Witness-1</strong></u></td>
            <td><u><strong>Witness-1</strong></u></td>
        </tr>
    </table>
<table  style="width: 100%;">
        <!-- <tbody  style="width: 215.9mm;"> -->
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style="width: 20mm;"></td>
            <td style="">
                @if(!empty($podataInfo->poCheckerName->digital_sign))
                    <img src="{{url('public/uploads/digital_sign/'.$podataInfo->poCheckerName->digital_sign)}}" style="width: 100px;height: 50px;"/>
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poCheckerName))
                {!! $podataInfo->poCheckerName->first_name.' '.$podataInfo->poCheckerName->last_name !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poCheckerName))
                {!! $podataInfo->poCheckerName->rank !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                @if(!empty($podataInfo->poCheckerName))
                {!! $podataInfo->poCheckerName->designation !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                Local Purchase Officer
            </td>
        </tr>
        <tr>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td style=""></td>
            <td ></td>
            <td style="font-size: 14px;">
                 {!! (!empty($orgInfo->name)) ? $orgInfo->name : '' !!}
            </td>
        </tr>
        <tr>
            <td style="height: 2mm" colspan="6"></td>
        </tr>
    </table>

    <br />
    <table style="width: 100%;font-size: 14px;">
        <tr>
            <td style="width: 35%;"></td>
            <td><u>ANNEX – A</u></td>
        </tr>
        <tr>
            <td style="width: 35%;"></td>
            <td></td>
        </tr>
        <tr>
            <td style="width: 35%;"></td>
            <td></td>
        </tr>
        <tr>
            <td style="width: 35%;"></td>
            <td><u>NSSD CONTRACT NO: {{!empty($podataInfo->po_number) ?$podataInfo->po_number : ''}} </u></td>
        </tr>
        <tr>
            <td style="width: 35%;"></td>
            <td></td>
        </tr>
        <tr>
            <td style="width: 35%;"></td>
            <td></td>
        </tr>
        <tr>
            <td style="width: 35%;"></td>
            <td><u>DATED: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{!! (!empty($podataInfo->top_date)) ? date('d F Y',strtotime($podataInfo->top_date)) : '' !!}</u></td>
        </tr>
    </table>


    <P style="text-align: center;font-size: 16px;"><u>Financial Analysis</u></P>
    <br /><br />
    <p style="margin-left: 40px;">SUPPLIER : {{!empty($suppInfo->company_name) ? $suppInfo->company_name : ''}}</p>
    <p style="margin-left: 40px;">Name of Item: {!! (!empty($tenderInfo->tender_title)) ? $tenderInfo->tender_title : '' !!}</p>

    <table class="border"  style="width: 100%;margin-left: 80px;">
        <!-- <tbody  style="width: 215.9mm;"> -->
        <tr>
            <td style="border: 0;  font-size: 14px;"></td>
            <td style="font-size: 14px;">&nbsp;Ser</td>
            <td style="font-size: 14px;">&nbsp; Description</td>
            <td style="font-size: 14px;text-align: center">Deno&nbsp;</td>
            <td style="font-size: 14px;text-align: center">Qty</td>
            <td style="font-size: 14px;text-align: center">Unit Price(TK)</td>
            <td style="font-size: 14px;text-align: center">T/Price (TK)</td>
        </tr>
	    <?php
	    $sl = 1;
	    $totalAmount = 0;
	    ?>
        @foreach($selectedSupItemInfo[$dtcq->id] as $ssii)
            <tr>
                <td style="border: 0"></td>
                <td style="font-size: 14px;">{!! $sl++ !!}</td>
                <td style="font-size: 14px;">&nbsp;
                    {!! $ssii->item_item_name !!}
                        @if(!empty($ssii->item_model_number))<br>&nbsp;Model: {!! $ssii->item_model_number !!} @endif
                        @if(!empty($ssii->item_brand))<br />&nbsp;Brand: {!! $ssii->item_brand !!} @endif
                </td>
                <td style="font-size: 14px;text-align: center">{!! $ssii->deno_name !!}</td>
                <td style="font-size: 14px;text-align: center">
				    <?php $unit_to = 0; ?>
                    @if(!empty($demandToTenInfo->head_ofc_apvl_status))
                        {!! $ssii->itm_to_sup_nhq_app_qty !!}
					    <?php $unit_to = $ssii->itm_to_sup_nhq_app_qty; ?>
                    @else
                        {!! $ssii->quoted_quantity !!}
					    <?php $unit_to = $ssii->quoted_quantity; ?>
                    @endif
                </td>
                <td style="font-size: 14px;text-align: center">
				    <?php $uniPrice = 0; ?>
                    @if($ssii->select_alternavtive_offer == 1)
                        {!! ImageResizeController::custom_format($ssii->alternative_unit_price) !!}
					    <?php $uniPrice = $ssii->alternative_unit_price; ?>
                    @else
                        {!! ImageResizeController::custom_format($ssii->unit_price) !!}
					    <?php $uniPrice = $ssii->unit_price; ?>
                    @endif
                </td>
                <td style="font-size: 14px;text-align: center">
                    {!! ImageResizeController::custom_format($unit_to*$uniPrice) !!}
				    <?php $totalAmount += $unit_to*$uniPrice; ?>
                </td>
            </tr>
        @endforeach
        <tr>
            <td style="font-size: 14px; border: 0"></td>
            <td colspan="5" style="text-align: right;">{!! OwnLibrary::numberTowords($totalAmount) !!} &nbsp;</td>
            <td style="font-size: 14px;text-align: center">{!! ImageResizeController::custom_format($totalAmount) !!}</td>
        </tr>
        @if(!empty($dtcq->discount_amount))
            <tr>
                <td style="border: 0"></td>
                <td colspan="5" style="text-align: right;">&nbsp; Price Reduction/Discount (-) &nbsp;</td>
                <td style="font-size: 14px;text-align: center">{!! ImageResizeController::custom_format($dtcq->discount_amount) !!}</td>
            </tr>
        @endif
        <tr>
            <td style="font-size: 14px; border: 0"></td>
            <td colspan="5" style="text-align: right; font-size: 14px;">&nbsp; Grand total {!! OwnLibrary::numberTowords($totalAmount-$dtcq->discount_amount) !!} &nbsp;</td>
            <td style="font-size: 14px;text-align: center">{!! ImageResizeController::custom_format($totalAmount-$dtcq->discount_amount) !!} &nbsp;</td>
        </tr>
        <tr style="height: 4mm;"></tr>
        <!-- </tbody> -->
    </table>

    <p style="margin-left: 80px;">Grand Total In Words: {!! ucwords(OwnLibrary::numberTowords($totalAmount-$dtcq->discount_amount)) !!}</p>
    <br /><br /><br /><br /><br /><br /><br /><br /><br />
<table style="margin-left: 100px;font-size: 14px;width: 100%;">
    <tr>
        <td style="vertical-align: top;width: 60%;"></td>
        <td >
            @if(!empty($podataInfo->poApprovalName->digital_sign))
                <img src="{{url('public/uploads/digital_sign/'.$podataInfo->poApprovalName->digital_sign)}}" style="width: 100px;height: 50px;"/>
            @endif
        </td>
    </tr>
    <tr>
        <td style="vertical-align: top;width: 60%;"></td>
        <td style="font-size: 14px;">
            @if(!empty($podataInfo->poApprovalName))
                {!! $podataInfo->poApprovalName->first_name.' '.$podataInfo->poApprovalName->last_name !!}
            @endif
        </td>
    </tr>
    <tr>
        <td style="vertical-align: top;width: 60%;"></td>
        <td style="font-size: 14px;">
            @if(!empty($podataInfo->poApprovalName))
                {!! $podataInfo->poApprovalName->rank !!}
            @endif
        </td>
    </tr>
    <tr>
        <td style="vertical-align: top;width: 60%;"></td>
        <td style="font-size: 14px;">
            @if(!empty($podataInfo->poApprovalName))
                {!! $podataInfo->poApprovalName->designation !!}
            @endif
        </td>
    </tr>
    <tr>
        <td style="vertical-align: top;width: 60%;">Dated: {!! (!empty($podataInfo->top_date)) ? date('d F Y',strtotime($podataInfo->top_date)) : '' !!}</td>
        <td >Dated: {!! (!empty($podataInfo->top_date)) ? date('d F Y',strtotime($podataInfo->top_date)) : '' !!}</td>
    </tr>
    <tr>
        <td><br /></td>
        <td><br /></td>
    </tr>
    <tr>
        <td><u><strong{{-- >Witness-1 --}}</strong></u></td>
        <td><u><strong>{{-- Witness-1 --}}</strong></u></td>
    </tr>
</table>
@endif
<?php
 $approved = 1;
 $reject = 2;
 $url = NULL;
 $url2 = NULL;
 $apBtn = NULL;
 if($tabNum == 3)
 	{
 		$url = 'po-check-view/';
 		$url2 = 'view-po-generation-check-approved-reject/';
        $apBtn = 'Check';
    }
    else
    	{
		    $url = 'po-approve-view/';
		    $url2 = 'view-po-generation-approve-approved-reject/';
            $apBtn = 'Approved';
        }
?>
<div style="margin-top: 30px;">
<?php if(!empty(Session::get('acl')[34][30]) || !empty(Session::get('acl')[34][31]) ){ ?>
    <a class="btn btn-warning" href="{{URL::to($url.$podataId.'&'.$tenderId) }}" title="Edit">
        <i class="glyphicon glyphicon-edit"></i> Edit
    </a>
    <a class="btn btn-success" href="{{url($url2.'?podataId='.$podataId.'&tenderId='.$tenderId.'&status='.$approved)}}" title="Approved">
        <i class="glyphicon glyphicon-ok"></i> {{ $apBtn }}
    </a>

    <a class="btn btn-danger" href="{{url($url2.'?podataId='.$podataId.'&tenderId='.$tenderId.'&status='.$reject)}}" title="Reject">
        <i class="glyphicon glyphicon-remove"></i> Reject
    </a>
    <?php } ?>
    <a class="btn btn-primary" href="{{ URL::to('print-po-generation/'.$podataId.'&'.$tenderId) }}" target="_blank">
        <i class="glyphicon glyphicon-print"></i> Print
    </a>
</div>

</body>
</html>