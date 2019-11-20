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

        table, th, td {
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
<htmlpagefooter name="page-footer">
    <table style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic; border: none;" width="100%">
        <tbody>
            <tr>
                <td width="49%" style="border: none !important;"><span style="font-weight: bold; font-style: italic; border: none !important;">{!! date('d-m-Y h:i') !!}</span></td>
                <td style="font-weight: bold; font-style: italic; border: none !important;" align="right" width="49%">Page {PAGENO} of {nbpg}</td>
            </tr>
        </tbody>
    </table>
</htmlpagefooter>

   <div class="logo-div">
        <img class="navy-logo" src="{{URL::to('/')}}/public/img/bd-navy.png">
   </div>
   <h2 class="underline text-center">Naval Stores Sub Depot Dhaka<br /> <span class="bg-red"> 
        @if($tenderInfoForPdf->tender_type==1)
            {!! 'Limited Tender'  !!}
        @endif
        @if($tenderInfoForPdf->tender_type==2)
            {!! 'Open Tender'  !!}
        @endif
        @if($tenderInfoForPdf->tender_type==3)
            {!! 'Restricted Tender'  !!}
        @endif
        @if($tenderInfoForPdf->tender_type==4)
            {!! 'Spot Tender'  !!}
        @endif
        @if($tenderInfoForPdf->tender_type==5)
            {!! 'Direct Purchase'  !!}
        @endif
       @if($tenderInfoForPdf->tender_type==6)
           {!! 'Short Tender'  !!}
       @endif
        
   </h2>
    <table>
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
            <tr>
                <td>9.</td>
                <td colspan="2">Procuremment method</td>
                <td colspan="2">
                    @if($tenderInfoForPdf->tender_type==1)
                        {!! 'Limited Tender Method'  !!}
                    @endif
                    @if($tenderInfoForPdf->tender_type==2)
                        {!! 'Open Tender Method'  !!}
                    @endif
                    @if($tenderInfoForPdf->tender_type==3)
                        {!! 'Restricted Tender Method'  !!}
                    @endif
                    @if($tenderInfoForPdf->tender_type==4)
                        {!! 'Spot Tender Method'  !!}
                    @endif
                    @if($tenderInfoForPdf->tender_type==5)
                        {!! 'Direct Purchase Method'  !!}
                    @endif
                    @if($tenderInfoForPdf->tender_type==6)
                        {!! 'Short Tender'  !!}
                    @endif
                </td>
            </tr>
            <tr>
                <th colspan="5">FUNDING INFORMATION</th>
            </tr>
            <tr>
                <td>10.</td>
                <td colspan="2">Budget and Source of Funds</td>
                <td colspan="2">GoB</td>
            </tr>
            <tr>
                <td>11.</td>
                <td colspan="2">Development Partners (if Applicable)</td>
                <td colspan="2"><span class="bg-red">@if(!empty($tenderInfoForPdf->development_partners)) {!! $tenderInfoForPdf->development_partners !!} @else {!! 'NA' !!} @endif</span></td>
            </tr>
            <tr>
                <th colspan="5">PARTICULAR INFORMTION</th>
            </tr>
            <tr>
                <td>12.</td>
                <td colspan="2">Project/Programme Code (if Applicable)</td>
                <td colspan="2"><span class="bg-red">@if(!empty($tenderInfoForPdf->proj_prog_code)) {!! $tenderInfoForPdf->proj_prog_code  !!} @else {!! 'NA' !!} @endif</span></td>
            </tr>
            <tr>
                <td>13.</td>
                <td colspan="2">Project/Programme Code (if Applicable)</td>
                <td colspan="2"><span class="bg-red">@if(!empty($tenderInfoForPdf->proj_prog_code)) {!! $tenderInfoForPdf->proj_prog_code  !!} @else {!! 'NA' !!} @endif</span></td>
            </tr>
            <tr>
                <td>14.</td>
                <td colspan="2">Tender Package No</td>
                <td colspan="2"><span class="bg-red">@if(!empty($tenderInfoForPdf->tender_package_no)) {!! $tenderInfoForPdf->tender_package_no  !!} @else {!! 'NA' !!} @endif</span></td>
            </tr>
            <tr>
                <td>15.</td>
                <td colspan="2">Tender Package Name</td>
                <td colspan="2"><span class="bg-red">@if(!empty($tenderInfoForPdf->tender_package_name)) {!! $tenderInfoForPdf->tender_package_name  !!} @else {!! 'NA' !!} @endif</span></td>
            </tr>
            <tr>
                <td>16.</td>
                <td colspan="2">Tender Publication Date</td>
                <td colspan="2"><span class="bg-red">@if(!empty($tenderInfoForPdf->valid_date_from)) {!! date('d F Y', strtotime($tenderInfoForPdf->valid_date_from))  !!} @else {!! 'NA' !!} @endif</span></td>
            </tr>
            <tr>
                <td>17.</td>
                <td colspan="2">Tender Last Selling Date</td>
                <td colspan="2"><Span class="bg-red">@if(!empty($tenderInfoForPdf->tender_opening_date)) {!! date('d F Y', strtotime($tenderInfoForPdf->tender_opening_date)). ' & Time 1200 hrs'  !!} @else {!! 'NA' !!} @endif</Span></td>
            </tr>
            <tr>
                <td>18.</td>
                <td colspan="2">Tender Submission Date and Time</td>
                <td colspan="2"><Span class="bg-red">@if(!empty($tenderInfoForPdf->tender_opening_date)) {!! date('d F Y', strtotime($tenderInfoForPdf->tender_opening_date)). ' & Time 1200 hrs'  !!} @else {!! 'NA' !!} @endif</Span></td>
            </tr>
            <tr>
                <td>19.</td>
                <td colspan="2">Tender Opening Date and Time</td>
                <td colspan="2"><span class="bg-red">@if(!empty($tenderInfoForPdf->tender_opening_date)) {!! date('d F Y', strtotime($tenderInfoForPdf->tender_opening_date)). ' & Time 1200 hrs'  !!} @else {!! 'NA' !!} @endif</span></td>
            </tr>
            <tr>
                <td>20.</td>
                <td colspan="2">
                    <p>Name & Address of the office (s):</p>
                    <p>-Selling Tender Document (Principle)</p>
                    <p>-Selling Tender Document (Others)</p>
                    <p>-Receiving Tender Document</p>
                    <p>- Opening Tender Document</p>
                </td>
                <td colspan="2">
                    <p>Officer- In-charge</p>
                    <p>Naval Stores Sub Depot Dhaka</p>
                    <p>Namapara Khilkhet, Dhaka 1229</p>
                </td>
            </tr>
            <tr>
                <td>21.</td>
                <td colspan="2">Place/Date/Time of Pre-Tender Meeting</td>
                <td colspan="2"><span class="bg-red">@if(!empty($tenderInfoForPdf->pre_tender_meeting)) {!! date('d F Y', strtotime($tenderInfoForPdf->pre_tender_meeting))  !!} @else {!! 'NA' !!} @endif</span></td>
            </tr>
            <tr>
                <th colspan="5">INFORMATION FOR TENDER</th>
            </tr>
            <tr>
                <td>22.</td>
                <td colspan="2">Eligibility of Tender</td>
                <td colspan="2">
                    <p><span class="bg-red">@if(!empty($tenderInfoForPdf->eligibility_of_tender)) {!! $tenderInfoForPdf->eligibility_of_tender  !!} @else {!! 'NA' !!} @endif</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td>23.</td>
                <td colspan="2">Brief Description of Goods </td>
                <td colspan="2">
                    <p>
                        <span class="bg-red">@if(!empty($tenderInfoForPdf->tender_description)) {!! $tenderInfoForPdf->tender_description  !!} @else {!! 'NA' !!} @endif</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td>24.</td>
                <td colspan="2">Brief Description of Related Services</td>
                <td colspan="2">NA</td>
            </tr>
            <tr>
                <td>25.</td>
                <td colspan="2">Price of Tender Document (Tk)</td>
                <td colspan="2">NA</td>
            </tr>
            <tr>
                <td>26.</td>
                <td colspan="2">Name of Official Inviting Tender</td>
                <td colspan="2"><span class="bg-red">@if(!empty($tenderInfoForPdf->name_of_offi_invit_ten)) {!! $tenderInfoForPdf->name_of_offi_invit_ten  !!} @else {!! 'NA' !!} @endif</span></td>
            </tr>
            <tr>
                <td>27.</td>
                <td colspan="2">Designation of Official inviting Tender</td>
                <td colspan="2"><span class="bg-red">@if(!empty($tenderInfoForPdf->desg_of_offi_invit_ten)) {!! $tenderInfoForPdf->desg_of_offi_invit_ten  !!} @else {!! 'NA' !!} @endif</span></td>
            </tr>
            <tr>
                <td>28.</td>
                <td colspan="2">Address of Official Inviting Tender</td>
                <td colspan="2">
                    <p>Officer- In-charge</p>
                    <p>Naval Stores Sub Depot Dhaka</p>
                    <p> Namapara Khilkhet, Dhaka 1229</p>
                </td>
            </tr>
            <tr>
                <td rowspan="2" >29.</td>
                <td rowspan="2" >Contact details of Official</td>
                <th>Tel. No.</th>
                <th>Fax. No.</th>
                <th>E-mail</th>
            </tr>
            <tr>
                <td class="text-center">41095104-8/4036</td>
                <td class="text-center">41095103</td>
                <td class="text-center">oic@bnnssddhk.org.bd</td>
            </tr>
            <tr>
                <td>30</td>
                <td colspan="4">The procuring entity reserves the right to reject all or any Tenders. </td>
            </tr>
            <tr>
                <td>31</td>
                <td colspan="3">Ref. No. – <span class="bg-red">@if(!empty($tenderInfoForPdf->nhq_ltr_no)) {!! $tenderInfoForPdf->nhq_ltr_no  !!} @else {!! 'NA' !!} @endif</span></td>
                <td>Ref. Date – <span class="bg-red">@if(!empty($tenderInfoForPdf->reference_date)) {!! date('d F Y', strtotime($tenderInfoForPdf->reference_date))  !!} @else {!! 'NA' !!} @endif</span></td>
            </tr>
            <tr>
                <td>32</td>
                <td colspan="4">Due to unavoidable circumstance if the tender can’t be received or opened in the schedule
                    date the same will be shifted on the next working day.</td>
            </tr>
        </tbody>
    </table><formfeed>
    
    <?php $srl = 1; ?>
    @if( $tenderInfoForPdf->tender_nature == 1)
        <br>
        33. Description of Item: 
        <table>
            <thead>
                <tr>
                    <th>Ser</th>
                    <th>Item</th>
                    {{--<th>Group</th>--}}
                    <th>Deno</th>
                    <th>Qty</th>
                    <th>Location</th>
                    <th style="width: 11%;">Delivery time after issue of w/order</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($itemsInfoDesc as $itnat1)
                    <tr>
                        <td>{!! $srl++ !!}</td>
                        <td>
                            @if(isset($itnat1->item_name) && !empty($itnat1->item_name))
                                <p>{{$itnat1->item_name}}</p>
                            @endif

                                @if(isset($itnat1->manufacturer_name) && !empty($itnat1->manufacturer_name))
                                    <p>Manufacturer's Name: {{$itnat1->manufacturer_name}}</p>
                                @endif
                                @if(isset($itnat1->manufacturing_country) && !empty($itnat1->manufacturing_country))
                                    <p>Manufacturing Country: {{$itnat1->manufacturing_country}}</p>
                                @endif
                                @if(isset($itnat1->country_of_origin) && !empty($itnat1->country_of_origin))
                                    <p>Country of Origin: {{$itnat1->country_of_origin}}</p>
                                @endif
                                @if(isset($itnat1->model_number) && !empty($itnat1->model_number))
                                    <p>Model No: {{$itnat1->model_number}}</p>
                                @endif
                                @if(isset($itnat1->part_number) && !empty($itnat1->part_number))
                                    <p>Part No: {{$itnat1->part_number}}</p>
                                @endif
                                @if(isset($itnat1->patt_number) && !empty($itnat1->patt_number))
                                    <p>Patt No: {{$itnat1->patt_number}}</p>
                                @endif
                                @if(isset($itnat1->addl_item_info) && !empty($itnat1->addl_item_info))
                                    <p>Addl Item Info: {{$itnat1->addl_item_info}}</p>
                                @endif
                                @if(!empty($itnat1->main_equipment_name) || !empty($itnat1->main_equipment_brand) || !empty($itnat1->main_equipment_model)|| !empty($itnat1->main_equipment_additional_info))
                                <hr style="border: 1px solid black;" />
                            <h4 style="text-decoration: underline">Main Equipment Information:</h4>
                                @if(isset($itnat1->main_equipment_name) && !empty($itnat1->main_equipment_name))
                                    <p>Name: {{$itnat1->main_equipment_name}}</p>
                                @endif
                                @if(isset($itnat1->main_equipment_brand) && !empty($itnat1->main_equipment_brand))
                                    <p>Brand: {{$itnat1->main_equipment_brand}}</p>
                                @endif
                                @if(isset($itnat1->main_equipment_model) && !empty($itnat1->main_equipment_model))
                                    <p>Model: {{$itnat1->main_equipment_model}}</p>
                                @endif
                                @if(isset($itnat1->main_equipment_additional_info) && !empty($itnat1->main_equipment_additional_info))
                                    <p>Additional Info: {{$itnat1->main_equipment_additional_info}}</p>
                                @endif

@endif
                        </td>
                        {{--<td>{!! $itnat1->supplycategories_name !!}</td>--}}
                        <td>{!! $itnat1->deno_deno_name !!}</td>
                        <td>{!! $itnat1->item_to_demand_unit !!}</td>
                        <td>{!! $itnat1->location !!}</td>
                        <td> @if(!empty($itnat1->tender_delivery_date)){!! $itnat1->tender_delivery_date !!} @endif</td>
                        <td>{!! $itnat1->tender_remarks !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

     @if( $tenderInfoForPdf->tender_nature == 2)
        <br>
        33. Description of Item: 
        <table>
            <thead>
                <tr>
                    <th>Ser</th>
                    <th>Item</th>
                    {{--<th>Group</th>--}}
                    <th>Deno</th>
                    <th>Qty</th>
                    <th>Location</th>
                    <th style="width: 11%;">Delivery time after issue of w/order</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lotItemArray as $key => $value)
                    <tr>
                        <td colspan="10">{!! $key !!}</td>
                    </tr>
                    <?php $rowCou = 1 ; ?>
                    @foreach ($value as $key => $vl)
                        <tr>
                            <td>{!! $srl++ !!}</td>
                            <td>
                                @if(isset($vl['item_name']) && !empty($vl['item_name']))
                                    <p>{{$vl['item_name']}}</p>
                                @endif

                                @if(isset($vl['manufacturer_name']) && !empty($vl['manufacturer_name']))
                                    <p>Manufacturer's Name: {{$vl['manufacturer_name']}}</p>
                                @endif
                                @if(isset($vl['manufacturing_country']) && !empty($vl['manufacturing_country']))
                                    <p>Manufacturing Country: {{$vl['manufacturing_country']}}</p>
                                @endif
                                @if(isset($vl['country_of_origin']) && !empty($vl['country_of_origin']))
                                    <p>Country of Origin: {{$vl['country_of_origin']}}</p>
                                @endif
                                @if(isset($vl['model_number']) && !empty($vl['model_number']))
                                    <p>Model No: {{$vl['model_number']}}</p>
                                @endif
                                @if(isset($vl['part_number']) && !empty($vl['part_number']))
                                    <p>Part No: {{$vl['part_number']}}</p>
                                @endif
                                @if(isset($vl['patt_number']) && !empty($vl['patt_number']))
                                    <p>Patt No: {{$vl['patt_number']}}</p>
                                @endif
                                @if(isset($vl['addl_item_info']) && !empty($vl['addl_item_info']))
                                    <p>Addl Item Info: {{$vl['addl_item_info']}}</p>
                                @endif
                                    @if(!empty($vl['main_equipment_name']) || !empty($vl['main_equipment_brand']) || !empty($vl['main_equipment_model']) || !empty($vl['main_equipment_additional_info']))
                                    <hr style="border: 1px solid black;" />
                                    <h4 style="text-decoration: underline">Main Equipment Information:</h4>
                                    @if(isset($vl['main_equipment_name']) && !empty($vl['main_equipment_name']))
                                        <p>Name: {{$vl['main_equipment_name']}}</p>
                                    @endif

                                    @if(isset($vl['main_equipment_brand']) && !empty($vl['main_equipment_brand']))
                                        <p>Brand: {{$vl['main_equipment_brand']}}</p>
                                    @endif
                                    @if(isset($vl['main_equipment_model']) && !empty($vl['main_equipment_model']))
                                        <p>Model: {{$vl['main_equipment_model']}}</p>
                                    @endif
                                    @if(isset($vl['main_equipment_additional_info']) && !empty($vl['main_equipment_additional_info']))
                                        <p>Additional Info: {{$vl['main_equipment_additional_info']}}</p>
                                    @endif
                                        @endif
                            </td>
                            {{--<td>{!! $vl['supplycategories_name'] !!}</td>--}}
                            <td>{!! $vl['deno_deno_name'] !!}</td>
                            <td>{!! $vl['item_to_demand_unit'] !!}</td>
                            <td>{!! $vl['location'] !!}</td>
                            @if(count($value)==1)
                                <td>@if(!empty($vl->tender_delivery_date)){!!  $vl->tender_delivery_date !!} @endif</td>
                                <td>{!! $vl->tender_remarks !!}</td>
                            @endif
                            @if(count($value) > 1 && $rowCou==1)
                                <td rowspan="{!! count($value) !!}">@if(!empty($vl->tender_delivery_date)){!!  $vl->tender_delivery_date !!} @endif</td>
                                <td rowspan="{!! count($value) !!}">{!! $vl->tender_remarks !!}</td>
                            @endif
                        </tr>
                        <?php $rowCou++ ; ?>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif

    <div style="font-size: 16px;">
        <p>নিম্নলিখিত শর্ত সাপেক্ষে দরপত্র দাখিল করতে হবে:</p>
        <p>{!! $tenderInfoForPdf->tender_terms_conditions  !!}</p>
    </div>
    <br>
    
    @if(!empty($appUserInfo))
        <div style="margin: auto; float: right; margin-right: 15px; width: 25%;">
            @if(!empty($appUserInfo->digital_sign))
                <div>
                    <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $appUserInfo->digital_sign !!}">
                </div>
            @else
                <br><br><br>   
            @endif
            <p style="margin: 0px;">{!! $appUserInfo->first_name.' '.$appUserInfo->last_name  !!}</p>
            <p style="margin: 0px;">{!! $appUserInfo->rank !!}</p>
            <p style="margin: 0px;">{!! $appUserInfo->designation !!}</p>
            <p style="margin: 0px;">{!! $organizationName !!}</p>
        </div>
    @endif
                                         
</body>
</html>

