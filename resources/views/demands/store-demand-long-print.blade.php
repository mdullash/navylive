<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>চাহিদাপত্র এফ (এনএস)-৫ (সিঙ্গেল লাইন-এর পরিবর্তে)</title>

    <style>
        body{
            font-family: 'bangla', sans-serif;
            font-size: 16px;
        }
        table,td{
            border: 1px solid black;
        }
        table{
            border-collapse: collapse;
        }
        td{
            padding: 2px;
        }
        @page {
            footer: page-footer;
        }
    </style>

</head>

<body>

<table style="border: none;width: 100%;">
    <tr>
        <td colspan="2" style="border: none;text-align: center;">
            (মূল)
        </td>
    </tr>
    <tr>
        <td  style="border: none;text-align: center;width: 320mm;font-size: 20px;" rowspan="2">
            <h2>চাহিদাপত্র এফ (এনএস)-৫ (সিঙ্গেল লাইন-এর পরিবর্তে)</h2>
        </td>
        <td  style="border: none;border-bottom: 1px solid black;">
            এফ(এনএস)৫
        </td>
    </tr>
    <tr>
        <td style="border: none;">
            এস-১৩৪
        </td>
    </tr>
</table>

<table style="width: 100%;">
    <tr>
        <td colspan="4">
            &nbsp;&nbsp;জাহাজ/নৌঘাঁটি: {{$demands->demandeNameInDemand->name}}
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            কোড  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; @if(!empty($demands->for_whom)) {{$demands->for_whom}} @endif
        </td>
        <td colspan="8">
            &nbsp;&nbsp;কন্ট্রোল রেজিস্টার নং: @if(!empty($demands->demand_no)) {{$demands->demand_no}} @endif
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            তারিখ: @if(!empty($demands->when_needed)) {{date('d-m-Y',strtotime($demands->when_needed))}} @endif
        </td>
        <td colspan="2" style="text-align: center">
            প্রায়োরিটি: {{$demands->priority}}
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center;">
            সামগ্রী সনাক্তকরন
        </td>
        <td style="text-align: center;">
            যন্ত্রাংশ প্রস্তুতকারক
        </td>
        <td colspan="8">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            মডেল/টাইপ/মার্কা
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            ক্রমিক/রেজিস্ট্রেশন নং
        </td>
        <td colspan="2" style="text-align: center;">
            পুস্তিকার সূত্র
        </td>
    </tr>
    <tr>
        <td rowspan="2" style="text-align:center;">ক্রমিক নং</td>
        <td rowspan="2" style="text-align:center;">শ্রেণী বিন্যাস</td>
        <td rowspan="2" style="text-align:center;">প্যাট/স্টক নং</td>
        <td rowspan="2" style="text-align:center;">বর্ণনা</td>
        <td rowspan="2" style="text-align:center;">একক</td>
        <td rowspan="2" style="text-align:center;">স্থা/ক্ষ</td>
        <td rowspan="2" style="text-align:center;">চলতি/সাময়িক</td>
        <td colspan="2" style="text-align:center;">পরিমান</td>
        <td rowspan="2" style="text-align:center;">কত চাই</td>
        <td style="text-align:center;">মূল্যায়ন</td>
        <td rowspan="2" style="text-align:center;">প্রণালী</td>
        <td style="text-align: center;">মন্তব্য</td>
        <td style="text-align: center;">ইস্যু কন্ট্রোল সংকেত</td>
    </tr>
    <tr>
        <td style="text-align: center;">অনুমোদিত</td>
        <td style="text-align: center;">হাতে আছে</td>
        <td style="text-align: center;">টাকা &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; পয়সা</td>
        <td style="text-align: center;">ওয়ার্ক অর্ডার নং</td>
    </tr>
    @foreach($itemLists as $itemList)
        <tr><!--tr-->
            <td style="text-align: center;vertical-align: middle;"><p>{{$loop->iteration}}</p></td>
            <td style="text-align: center;vertical-align: middle;">

                    @if(!empty($itemList->category_name))
                        {{$itemList->category_name}}
                    @endif

            </td>
            <td style="text-align: center;vertical-align: middle;">

                    @if(!empty($itemList->item_patt_number))
                        {{$itemList->item_patt_number}}
                    @endif

            </td>
            <td style="vertical-align: middle;">

                    @if(!empty($itemList->item_item_name))
                        {{$itemList->item_item_name}}
                    @endif

                    @if(!empty($itemList->item_manufacturer_name))
                        - {{$itemList->item_manufacturer_name}}
                    @endif

            </td>
            <td style="text-align: center;vertical-align: middle;">

                    @if(!empty($itemList->deno_name))
                        {{$itemList->deno_name}}
                    @endif

            </td>
            <td style="text-align: center;vertical-align: middle;"></td>
            <td style="text-align: center;vertical-align: middle;"></td>
            <td style="text-align: center;vertical-align: middle;">{{ $itemList->po_approved_quantity }}</td>
            <td style="text-align: center;vertical-align: middle;"></td>
            <td style="text-align: center;vertical-align: middle;">

                    @if(!empty($itemList->item_to_demand_unit))
                        {{$itemList->item_to_demand_unit}}
                    @endif

            </td>
            <td style="text-align: center;vertical-align: middle;"></td>
            <td style="text-align: center;vertical-align: middle;"></td>
            <td style="text-align: center;vertical-align: middle;"></td>
            <td style="text-align: center;vertical-align: middle;"></td>

        </tr>
    @endforeach
</table>

<table style="width: 100%;border: none;">
    <tr>
        <td style="border: none;">কেন দরকার</td>
        <td style="border: none;">ইস্যু কন্ট্রোল সংকেত</td>
        <td style="border: none;"></td>
    </tr>
    <tr>
        <td style="border: none;">
            <span>১। </span> ঘাটতি পূরণ/ত্রৈমাসিক চাহিদা <br />
            <span style="color: white;">১। </span>(মন্তব্য কলামে কোয়ার্টার লিখুন)
        </td>
        <td style="border: none;">১। অনুমোদিত হইল।</td>
        <td style="border: none;text-align: right">শুধুমাত্র ডকইয়ার্ডের জন্য</td>
    </tr>
    <tr>
        <td style="border: none;">
            ২। সার্ভের পরিবর্তে হলে সার্ভে ভাউচার-এর মন্তব্যের কোঠায় লিখুন।
        </td>
        <td style="border: none;">২। </span>বকেয়া বাতিল।</td>
        <td style="border: none;text-align: right"></td>
    </tr>
    <tr>
        <td style="border: none;">
            ৩। অনুমোদিত পরিমাপের অতিরিক্ত হলে-অধিনায়কের মন্তব্যসহ দস্তখত।
        </td>
        <td style="border: none;">৩। বাতিল।</td>
        <td style="border: none;text-align: right">

            @if(!empty($approverInfo->digital_sign))
                <p><img src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $approverInfo->digital_sign !!}"></p>
                <p>দস্তখত &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                @else
                দস্তখত...................................
            @endif
        </td>
    </tr>
    <tr>
        <td style="border: none;">
            ৪। বিশেষ প্রয়োজনীয়তা (নৌ সদর দপ্তরের চিঠি)।
        </td>
        <td style="border: none;">৪। নিরীক্ষণের জন্য টেকনিক্যাল</td>
        <td style="border: none;text-align: right"></td>
    </tr>
    <tr>
        <td style="border: none;">
            ৫। অন্যান্য।
        </td>
        <td style="border: none;"></td>
        <td style="border: none;text-align: right">
            @if(!empty($approverInfo->first_name) || !empty($approverInfo->last_name))
                নাম: {!! $approverInfo->first_name.' '.$approverInfo->last_name  !!}
            @else
                নাম................................
            @endif
            @if(!empty($approverInfo->designation))
                পদবী: {!! $approverInfo->designation !!}
                @else
                পদবী................................
                @endif
        </td>
    </tr>
    <tr>
        <td style="border: none;">
            ৬। ওয়ার্ক অর্ডার। Authority No:@if(!empty($demands->pattern_or_stock_no)) {{$demands->pattern_or_stock_no}} @endif  &nbsp; &nbsp; @if(!empty($demands->reference_date)) Authority Date: {{   date("Y-m-d",strtotime($demands->reference_date)) }} @endif <br />
            <span style="color: white;">৬।</span> বিঃদ্রঃ- দয়াপূর্বক একই ক্লাস এবং গ্রুপের ১০ টি দ্রব্যের চাহিদা দাখিল করুন। <br /><br />
            <span style="color: white;">৬।</span> জিপিপি-প্রসেস শাখা-৪৯৫/১০/(এন)-০৮-১১-২০১০ ইং-২০০ বই।
        </td>
        <td style="border: none;"></td>
        <td style="border: none;text-align: right"></td>
    </tr>
</table>

<htmlpagefooter name="page-footer">
    <table style="vertical-align: bottom;font-weight: bold; font-style: italic;border: none;" width="100%">
        <tr>
            <td style="font-style: italic;border: none;font-family: Arial, Helvetica, sans-serif;font-size: 12px;" width="49%"><span style="font-weight: bold; font-style: italic;">{!! date('d-m-Y h:i') !!}</span></td>
            <td style="font-weight: bold; font-style: italic;border: none;font-family: Arial, Helvetica, sans-serif;font-size: 12px;" align="right" width="49%">Page {PAGENO} of {nbpg}</td>
        </tr>
    </table>
</htmlpagefooter>
</body>
</html>