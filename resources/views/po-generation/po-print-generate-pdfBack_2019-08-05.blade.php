<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Resticted</title>
    <style>
        body{
            font-family: serif; 
        }       
        table {
            border-collapse: collapse;
            /*font-size: 14px !important;*/
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #282828;
        }
    </style>

    
</head>
<?php use functions\OwnLibrary; ?>
<body>
    @foreach($demandToCollQut as $dtcq)
        <table style="width: 100%;">
            <!-- <tbody style="width: 215.9mm;"> -->
                <tr>
                    <td style="font-size: 14px;"></td>
                    <td style="font-size: 14px">RESTICTED</td>
                    <td style=""></td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=" font-size: 14px;">{!! (!empty($orgInfo->name)) ? $orgInfo->name : '' !!}</td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=" font-size: 14px;">Namapara, Khilkhet</td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=" font-size: 14px;">Dhaka-1219</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=" font-size: 14px;">Phone: 41095104-8 Ext: 4000</td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=" font-size: 14px;">Fax: 41095103</td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style=""></td>
                    <td style=" font-size: 14px;">Email: oicnssd@navy.mil.bd</td>
                </tr>
                <tr>
                    <td style="font-size: 14px;">{!! (!empty($podataInfo->tender_number)) ? $podataInfo->tender_number : '' !!}</td>
                    <td style=""></td>
                    <td style="font-size: 14px;">{!! (!empty($podataInfo->top_date)) ? date('F Y',strtotime($podataInfo->top_date)) : '' !!}</td>                   
                </tr>
                <tr style="height: 2mm;"></tr>
            </table>
            <br>
            <table>
                <tr>
                    <td colspan="3" style="font-weight: bold; font-size: 14px;">LOCAL PURCHASE ORDER-{!! (!empty($tenderInfo->tender_title)) ? $tenderInfo->tender_title : '' !!}-BNS PROPERTY</td>
                </tr>
                <tr style="height: 2mm;"></tr>
                <tr>
                    <td colspan="3" style=" font-size: 14px;">Ref:</td>
                </tr>
                <tr style="height: 4mm;"></tr>
                @if(empty($demandToLprInfo->head_ofc_apvl_status))
                <tr>
                    <td colspan="3" style=" font-size: 14px;">A.&nbsp;&nbsp;&nbsp;&nbsp; <span style="font-weight: bold; text-decoration: underline;">Reference Number. {!! (!empty($tenderInfo->nhq_ltr_no)) ? $tenderInfo->nhq_ltr_no : '' !!}.</span></td>
                </tr>
                @endif
                <tr>
                    <td colspan="3" style=" font-size: 14px;">@if(empty($demandToLprInfo->head_ofc_apvl_status)) B @else A @endif.&nbsp;&nbsp;&nbsp; <span style="font-weight: bold; text-decoration: underline;">{!! (!empty($orgInfo->name)) ? $orgInfo->name : '' !!} tender no.{!! (!empty($podataInfo->extends_tender_number)) ? $podataInfo->extends_tender_number : '' !!} Date {!! (!empty($tenderInfo->valid_date_from)) ? date('d F Y',strtotime($tenderInfo->valid_date_from)) : '' !!}.</span></td>
                </tr>
                @if(!empty($demandToLprInfo->head_ofc_apvl_status))
                <tr>
                    <td colspan="3" style=" font-size: 14px;">B.&nbsp;&nbsp;&nbsp; <span style="font-weight: bold; text-decoration: underline;">Naval Headquarters Letter no. {!! (!empty($podataInfo->headquarters_letter_no)) ? $podataInfo->headquarters_letter_no : '' !!} Date {!! (!empty($tenderInfo->valid_date_from)) ? date('d F Y',strtotime($tenderInfo->valid_date_from)) : '' !!}.</span></td>
                </tr>
                @endif
            </table>
            <br>
            <table>
                <tr>
                    <?php $totalAmountAfterDiscount = ($dtcq->total-$dtcq->discount_amount); ?>
                    <td colspan="3" style=" font-size: 15px;"><span>1.</span>&nbsp;&nbsp;&nbsp;&nbsp; In light of approval <span style="font-weight: bold; text-decoration: underline;">vide</span> ref 'b', above mentioned item <span style="font-weight: bold; text-decoration: underline;">@if(array_sum(array_map("count", $selectedSupItemInfo)) <=1) is @else are @endif</span> accepted by Bangladesh Navy as per demand @if(!empty($tenderInfo->specification) || !empty($tenderInfo->specification_doc)) and specification @endif. You are hereby awarded the work order of Taka {!! $totalAmountAfterDiscount !!} ({!! OwnLibrary::numberTowords($totalAmountAfterDiscount) !!}) <span style="font-weight: bold; text-decoration: underline;">{!! (!empty($podataInfo->import_duties)) ? $podataInfo->import_duties : '' !!} import duties.</span> In this context you are requested to supply the item to <span style="font-weight: bold; text-decoration: underline;">{!! (!empty($podataInfo->supply_to)) ? $podataInfo->supply_to : '' !!}</span> by complying the following conditions mentioned below:</td>
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
                    <td style="font-size: 14px;">&nbsp;{!! $ssii->item_item_name !!} <br>
                        @if(!empty($ssii->item_model_number)) &nbsp;Band/Model: {!! $ssii->item_model_number !!} @endif
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
                    <td style="font-size: 14px;">&nbsp; {!! $ssii->unit_price !!} </td>
                    <td style="font-size: 14px;">&nbsp; 
                        {!! $unit_to*$ssii->unit_price !!}
                        <?php $totalAmount += $unit_to*$ssii->unit_price; ?>
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
                    <td colspan="5" style="text-align: right;">&nbsp; {!! OwnLibrary::numberTowords($totalAmount) !!} &nbsp;</td>
                    <td style="font-size: 14px;">&nbsp; {!! $totalAmount !!}</td>                
                </tr>       
                <tr>
                    <td style="border: 0"></td>
                    <td colspan="5" style="text-align: right;">&nbsp; Price Reduction/Discount (-) &nbsp;</td>
                    <td style="font-size: 14px;">&nbsp; {!! $dtcq->discount_amount !!}</td>                
                </tr> 
                <tr>
                    <td style="font-size: 14px; border: 0"></td>
                    <td colspan="5" style="text-align: right; font-size: 14px;">&nbsp; Grand total {!! OwnLibrary::numberTowords($totalAmount-$dtcq->discount_amount) !!} &nbsp;</td>
                    <td style="font-size: 14px;">&nbsp; {!! $totalAmount-$dtcq->discount_amount !!} &nbsp;</td>                
                </tr>  
                <tr style="height: 4mm;"></tr>
            <!-- </tbody> -->
        </table>
        <br>
        <table  style="width: 100%;">
            <!-- <tbody  style="width: 215.9mm;"> -->
                <tr>
                    <td style="font-size: 14px;"></td>                
                    <td style="font-weight: bold; text-decoration: underline; font-size: 14px;">Conditions:</td>            
                </tr> 
                <tr style="font-size: 14px;"></tr>
                <?php 
                        $termsConditions = $podataInfo->terms_conditions;
                        if(!empty($termsConditions)){
                            $termsConditions = explode('<br>', $termsConditions); 
                        }
                        $sln = 1;
                ?>
                @if(!empty($termsConditions))
                    @foreach($termsConditions as $tc)
                    <tr>
                        <td style="font-size: 14px;"></td>
                        <td> {!! chr(64 + $sln++) !!}. &nbsp;&nbsp;&nbsp; {!! $tc !!}.</td>
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
                    <td style="font-size: 14px;">This Work order will be consider as a contract for purchase.</td>
                </tr>
                <tr style="height: 10mm;"></tr>
            <!-- </tbody> -->
        </table>
        <br>
        <table  style="width: 100%;">
            <!-- <tbody  style="width: 215.9mm;"> -->
                <tr>
                    <td style="font-size: 14px;"></td>
                    <td style="font-size: 14px;"></td>
                    <td style="font-size: 14px;">{!! $podataInfo->poApprovalName->first_name.' '.$podataInfo->poApprovalName->last_name !!}</td>
                </tr>
                <tr>
                    <td style="font-size: 14px;"></td>
                    <td style="font-size: 14px;"></td>
                    <td style="font-size: 14px;">{!! $podataInfo->poApprovalName->rank !!}</td>
                </tr>                
                <tr>
                    <td style="font-size: 14px;"></td>
                    <td style="font-size: 14px;"></td>
                    <td style="font-size: 14px;">{!! $podataInfo->poApprovalName->designation !!}</td>
                </tr> 
                <tr>
                    <td colspan="3" style="font-weight: bold; text-decoration: underline; font-size: 14px;">Enclosure:</td>
                </tr>
                <tr style="height: 2mm;"></tr>  
                <tr>
                    <td colspan="3" style="font-weight: bold; text-decoration: underline; font-size: 14px;">1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Contract Specification -01(One) page. </td>
                </tr>
                <tr style="height: 2mm;"></tr> 
                <tr>
                    <td colspan="3" style="font-size: 14px;">Distribution:</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-size: 14px;">Ext:</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-size: 14px;">Action:</td>
                </tr>
                <tr style="height: 2mm;"></tr>  
                <tr>
                    <td colspan="3" style="font-weight: bold; text-decoration: underline; font-size: 14px;"> Trade Point</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold; text-decoration: underline; font-size: 14px;">Government of Defense Supplier</td>
                </tr>
                <tr style="height: 2mm;"></tr> 
                <tr>
                    <?php $suppInfo = \App\Supplier::find($dtcq->supplier_name); ?>
                    <td colspan="3" style="font-size: 14px;">Info:<br>
                        {!! $suppInfo->company_name !!}<br>
                        {!! $suppInfo->trade_license_address !!}<br>
                        {!! $suppInfo->mobile_number !!}<br>
                        {!! $suppInfo->email !!}
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 14px;"></td>
                    <td style="font-size: 14px;">RESTICTED</td>
                    <td style="font-size: 14px;"></td>
                </tr> 

            </tbody>
        </table>
    @endforeach



    
</body>
</html>