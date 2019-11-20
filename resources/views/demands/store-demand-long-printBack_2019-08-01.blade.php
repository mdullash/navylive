<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Demandnote</title>

    <style>
        body{
            font-family: 'bangla', sans-serif;
        }
        .container {
            max-width: 1200px;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
        table {
            border-collapse: collapse;
            font-size: 14px;
        }
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }
        .table-bordered {
            border: 1px solid #dee2e6;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }
        .table td, .table th {
            padding: .25rem;
            vertical-align: middle;
        }
        table p {
            margin: 0;
        }
        .sig {
            width: 100px;
            height: 100px;
            text-align: right;
            margin: auto;
            margin-right: 0;
        }

        p.sig img {
            width: 100%;
        }
        @page {
            footer: page-footer;
        }
    </style>

</head>
<body>

    <table style="width: 100%;">
        <tr><!--tr-->
            <td style="border: 0; text-align: center; width: 93%;"><p>(মূল)</p></td>
            <td style="border: 0;  border-bottom: 1px solid #dee2e6; width: 17%;"><p>এফ(এনএস)৫</p></td>
        </tr>
        <tr><!--tr-->
            <td style="border: 0;width: 93%;margin:0 0 20px 0;text-align:center;" ><h4 style="text-align:center; margin:0;font-size: 16px;">চাহিদাপত্র এফ (এনএস)-৫ (সিঙ্গেল লাইন-এর পরিবর্তে)</h4> </td>
            <td style="width: 17%; margin:0 0 20px 0;"><p>এস-১৩৪</p></td>
        </tr>
    </table>

    <table class="table table-bordered" style="text-align:center;">
        <tbody>
        <tr><!--tr-->
            <td colspan="3" style="border-right: 0;">
                <p>জাহাজ/নৌঘাঁটি {{$demands->demandeNameInDemand->name}}</p>
            </td>
            <td style="border-left: 0;"> <p>কোড</p></td>

            <td colspan="5" style="border-right: 0;">
                <p> <span>কন্ট্রোল রেজিস্টার নংঃ </span>
                    @if(!empty($demands->demand_no))
                        {{$demands->demand_no}}
                    @endif
                </p>
            </td>
            <td colspan="3" style="border-left: 0;"><p><span>তারিখ: </span> {{date('d-m-Y',strtotime($demands->when_needed))}}</p></td>

            <td colspan="2"><p>প্রায়োরিটি {{$demands->priority}}</p></td>
        </tr>
        <tr>
            <td colspan="3"><p>সামগ্রী সনাক্তকরন</p></td>
            <td><p>যন্ত্রাংশ প্রস্তুতকারক</p></td>
            <td colspan="5" style="border-right: 0;"><p>মডেল/টাইপ/মার্কা </p></td>
            <td colspan="3" style="border-left: 0;"><p>ক্রমিক/রেজিস্ট্রেশন নং</p></td>
            <td colspan="2"><p>পুস্তিকার সূত্র</p></td>
        </tr>
        <tr><!--tr-->
            <td rowspan="2"><p>ক্রমিক নং</p></td>
            <td rowspan="2"><p>শ্রেণী বিন্যাস</p></td>
            <td rowspan="2"><p>প্যাট/স্টক নং</p></td>
            <td rowspan="2"><p>বর্ণনা</p></td>
            <td rowspan="2"><p>একক</p></td>
            <td rowspan="2"><p>স্থা/ক্ষ</p></td>
            <td rowspan="2"><p>চলতি/সাময়িক</p></td>
            <td colspan="2"><p>পরিমান</p></td>
            <td rowspan="2"><p>কত চাই</p></td>
            <td><p>মূল্যায়ন</p></td>
            <td rowspan="2"><p>প্রণালী</p></td>
            <td><p>মন্তব্য</p></td>
            <td rowspan="2"><p>ইস্যু কন্ট্রোল সংকেত</p></td>
        </tr>
        <tr><!--tr-->
            <td><p>অনুমোদিত</p></td>
            <td><p>হাতে আছে</p></td>
            <td>
                <p>টাকা <span> পয়সা</span></p>
            </td>
            <td><p>ওয়ার্ক অর্ডার নং</p></td>
        </tr>
        @foreach($itemLists as $itemList)
            <tr><!--tr-->
                <td><p>{{$loop->iteration}}</p></td>
                <td>
                    <p>
                        @if(!empty($itemList->category_name))
                            {{$itemList->category_name}}
                        @endif
                    </p>
                </td>
                <td>
                    <p>
                        @if(!empty($itemList->item_patt_number))
                            {{$itemList->item_patt_number}}
                        @endif
                    </p>
                </td>
                <td>
                    <p>
                        @if(!empty($itemList->item_item_name))
                            {{$itemList->item_item_name}}
                        @endif

                        @if(!empty($itemList->item_manufacturer_name))
                            - {{$itemList->item_manufacturer_name}}
                        @endif
                    </p>
                </td>
                <td>
                    <p>
                        @if(!empty($itemList->deno_name))
                            {{$itemList->deno_name}}
                        @endif
                    </p>
                </td>
                <td><p></p></td>
                <td><p></p></td>
                <td><p></p></td>
                <td><p></p></td>
                <td>
                    <p>
                        @if(!empty($itemList->item_to_demand_unit))
                            {{$itemList->item_to_demand_unit}}
                        @endif
                    </p>
                </td>
                <td><p></p></td>
                <td><p></p></td>
                <td><p></p></td>
                <td><p></p></td>

            </tr>
        @endforeach
        </tbody>
    </table>

    <table style="width: 100%;">
        <tr style="text-align: left;"><!--tr-->
            <td style="border: 0; width:55%;"><p>কেন দরকার</p></td>
            <td style="border: 0; width:25%;"><p>ইস্যু কন্ট্রোল সংকেত</p></td>
            <td style="border: 0; width:8%;"></td>
        </tr>
        <tr style="text-align: left;"><!--tr-->
            <td  style="border: 0; width:55%;">
                <p><span>১। </span>ঘাটতি পূরণ/ত্রৈমাসিক চাহিদা</p>
                <p>(মন্তব্য কলামে কোয়ার্টার লিখুন)</p>
            </td>
            <td style="border: 0; width:25%;"><p><span>১। </span>অনুমোদিত হইল।</p></td>
            <td style="border: 0; width:8%;text-align:right;"><p>শুধুমাত্র ডকইয়ার্ডের জন্য</p></td>
        </tr>
        <tr style="text-align: left;"><!--tr-->
            <td  style="border: 0; width:55%;">
                <p><span>২। </span>সার্ভের পরিবর্তে হলে সার্ভে ভাউচার-এর মন্তব্যের কোঠায় লিখুন।</p>
            </td>
            <td  style="border: 0; width:25%;"><p><span>২। </span>বকেয়া বাতিল।</p></td>
        </tr>
        <tr><!--tr-->
            <td  style="border: 0; width:55%;"><p><span>৩। </span>অনুমোদিত পরিমাপের অতিরিক্ত হলে-অধিনায়কের মন্তব্যসহ দস্তখত।</p>
            </td>
            <td style="border: 0; width:25%;"><p><span>৩। </span>বাতিল।</p></td>
            <td rowspan="2" style="border: 0; width:10%;">
                <p class="sig"> দস্তখত
                    <span class="sign">
                        @if(!empty($approverInfo->digital_sign))
                            <img src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $approverInfo->digital_sign !!}">
                        @endif

                    </span>
                </p>
            </td>
        </tr>
        <tr style="text-align: left;"><!--tr-->
            <td  style="border: 0; width:55%;"><p><span>৪। </span>বিশেষ প্রয়োজনীয়তা (নৌ সদর দপ্তরের চিঠি)।</p>
            </td>
            <td style="border: 0; width:25%;"><p><span>৪। </span>নিরীক্ষণের জন্য টেকনিক্যাল</p></td>
            <!-- <td style="border: 0;width:55%;"><p>নাম................................</p></td> -->
        </tr>
        <tr style="text-align: left;"><!--tr-->
            <td style="border: 0;"><p><span>৫। </span>অন্যান্য। @if(!empty($demands->for_whom)) {{$demands->for_whom}} @endif</p></td>
            <td style="border: 0; width:25%;"></td>
            <td style="border: 0;">
                @if(!empty($approverInfo->first_name) || !empty($approverInfo->last_name))
                    <p>নাম: {!! $approverInfo->first_name.' '.$approverInfo->last_name  !!}</p>
                @else
                    <p>নাম................................</p>
                @endif

            </td>

            <td style="border: 0; text-align: right;">
                @if(!empty($approverInfo->designation))
                    <p>পদবী: {!! $approverInfo->designation !!}</p>
                @else
                    <p>পদবী................................</p>
                @endif

            </td>
        </tr>
        <tr style="text-align: left;"><!--tr-->
            <td style="border: 0; width: 25%;"><p><span>৬। </span>ওয়ার্ক অর্ডার। Auth:@if(!empty($demands->pattern_or_stock_no)) {{$demands->pattern_or_stock_no}} @endif</p></td>
        </tr>

        <tr style="text-align: left;"><!--tr-->
            <td style="border: 0;"><p>বিঃদ্রঃ- দয়াপূর্বক একই ক্লাস এবং গ্রুপের ১০ টি দ্রব্যের চাহিদা দাখিল করুন।</p></td>
        </tr>
        <tr style="text-align: left;"><!--tr-->
            <td style="border: 0;"><p>জিপিপি-প্রসেস শাখা-৪৯৫/১০/(এন)-০৮-১১-২০১০ ইং-২০০ বই।</p></td>
        </tr>
    </table>
    
    <htmlpagefooter name="page-footer">
        <table style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;" width="100%">
            <tbody>
            <tr>
                <td width="49%"><span style="font-weight: bold; font-style: italic;">{!! date('d-m-Y h:i') !!}</span></td>
                <td style="font-weight: bold; font-style: italic;" align="right" width="49%">Page {PAGENO} of {nbpg}</td>
            </tr>
            </tbody>
        </table>
    </htmlpagefooter>
</body>
</html>