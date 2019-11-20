<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LOCAL PURCHASE ORDER-{!! (!empty($tenderInfo->tender_title)) ? $tenderInfo->tender_title : '' !!}-BNS PROPERTY</title>
    <style>
        body{
           /* font-family: 'bangla', sans-serif;*/
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

<body>

        <table style="width: 100%;">
            <!-- <tbody style="width: 215.9mm;"> -->
                
                <tr>
                    <td style="width: 35%;"></td>
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
                    <td style=" font-size: 14px;">Phone: 41095104-8</td>
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
            </table>

            <?php
                function nsd_name($nsd_id=null){
                    $calName = \App\NsdName::where('id','=',$nsd_id)->value('name');
                    return $calName;
                }
            ?>

            <h3 style="text-align: center;">Item Details</h3>
            <br />

            <table class="border" style="text-align: left;width: 100%;">
                <tr>
                    <td style="padding: 5px;font-weight: bold;width: 24%;">IMC Number:</td>
                    <td style="padding: 5px;width: 24%;">{{$items->imc_number}}</td>
                    <td style="padding: 5px;font-weight: bold;width: 24%;">Item Name:</td>
                    <td style="padding: 5px;width: 24%;">{{$items->item_name}}</td>
                </tr>
                <tr>
                    <td style="padding: 5px;font-weight: bold;">Brand:</td>
                    <td style="padding: 5px;">{{$items->brand}}</td>
                    <td style="padding: 5px;font-weight: bold;">Model:</td>
                    <td style="padding: 5px;">{{$items->model_number}}</td>
                </tr>
                <tr>
                    <td style="padding: 5px;font-weight: bold;">Manufacturer's Name:</td>
                    <td style="padding: 5px;">{{$items->manufacturer_name}}</td>
                    <td style="padding: 5px;font-weight: bold;">Manufacturing Country:</td>
                    <td style="padding: 5px;">{{$items->manufacturing_country}}</td>
                </tr>
                <tr>
                    <td style="padding: 5px;font-weight: bold;">Country of Origin:</td>
                    <td style="padding: 5px;">{{$items->country_of_origin}}</td>
                    <td style="padding: 5px;font-weight: bold;">Part Number:</td>
                    <td style="padding: 5px;">{{$items->part_number}}</td>
                </tr>
                <tr>
                    <td style="padding: 5px;font-weight: bold;">Patt Number:</td>
                    <td style="padding: 5px;">{{$items->patt_number}}</td>
                    <td style="padding: 5px;font-weight: bold;">Item Additional Info:</td>
                    <td style="padding: 5px;">{{$items->addl_item_info}}</td>
                </tr>
                <tr>
                    <td style="padding: 5px;font-weight: bold;">Main Equipment Name:</td>
                    <td style="padding: 5px;">{{$items->main_equipment_name}}</td>
                    <td style="padding: 5px;font-weight: bold;">Main Equipment Brand:</td>
                    <td style="padding: 5px;">{{$items->main_equipment_brand}}</td>
                </tr>
                <tr>
                    <td style="padding: 5px;font-weight: bold;">Main Equipment Model:</td>
                    <td style="padding: 5px;">{{$items->main_equipment_model}}</td>
                    <td style="padding: 5px;font-weight: bold;">Main Equipment Additional Info:</td>
                    <td style="padding: 5px;">{{$items->main_equipment_additional_info}}</td>
                </tr>
                <tr>
                    <td style="padding: 5px;font-weight: bold;">Equivalent/Substitute Item:</td>
                    <td style="padding: 5px;">{{$items->substitute_item}}</td>
                    <td style="padding: 5px;font-weight: bold;">Shelf Life:</td>
                    <td style="padding: 5px;">{{$items->shelf_life}}</td>
                </tr>
                <tr>
                    <td style="padding: 5px;font-weight: bold;">Source Of Supply:</td>
                    <td style="padding: 5px;">{{$items->source_of_supply}}</td>
                    <td style="padding: 5px;font-weight: bold;">Item DENO:</td>
                    <td style="padding: 5px;">{{$items->denoName->name}}</td>
                </tr>
                <tr>
                    <td style="padding: 5px;font-weight: bold;">Item Category:</td>
                    <td style="padding: 5px;">{{$items->supplyCategoryName->name}}</td>
                    <td style="padding: 5px;font-weight: bold;">Item Acct Status:</td>
                    <td style="padding: 5px;">
                        @if($items->item_type == 1)
                            {!! 'Permanent Content' !!}
                        @elseif($items->item_type == 2) 
                            {!! 'Waste Content' !!}  
                        @elseif($items->item_type == 3) 
                            {!! 'Quasi Permanent Content' !!} 
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px;font-weight: bold;">Item Picture:</td>
                    <td style="padding: 5px;">
                        @if(!empty($items->item_picture))
                            <img src="{{ asset($items->item_picture) }}" style="width: 20%;">
                        @endif
                    </td>
                    <td style="padding: 5px;font-weight: bold;">Item Specification:</td>
                    <td style="padding: 5px;">
                        @if(!empty($items->item_specification))
                            <a href="{{ asset($items->item_specification) }}" class="text-danger" target="_blank" style="font-size: 16px;">Download</a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px;font-weight: bold;">Organization:</td>
                    <td style="padding: 5px;">
                        <ul>
                            <?php
                                $nsdids = explode(',',$items->nsd_id);
                                foreach ($nsdids as $nsd) {
                                    $vals = nsd_name($nsd);
                                    echo "<li>".$vals."</li>";
                                }
                            ?>
                        </ul>
                    </td>
                    <td style="padding: 5px;font-weight: bold;">Item's Type:</td>
                    <td style="padding: 5px;">
                        @if($items->item_type_r == 1)
                            Spare Parts
                        @elseif($items->item_type_r == 2)
                            Component
                        @elseif($items->item_type_r == 3)
                            Assembly
                        @elseif($items->item_type_r == 4)
                            Main Equipment
                        @else
                            Other
                        @endif

                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px;font-weight: bold;">status</td>
                    <td style="padding: 5px;">{{trans('english.STATUS')}}</td>
                    <td style="padding: 5px;font-weight: bold;">
                        @if(\Session::get("zoneAlise") == "bsd")
                            Strength:
                        @endif
                    </td>
                    <td style="padding: 5px;">
                        @if(\Session::get("zoneAlise") == "bsd")
                           {{$items->strength}}
                        @endif
                    </td>
                </tr>
            </table>

</body>
</html>