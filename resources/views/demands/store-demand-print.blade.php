<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>স্টোর ডিমান্ড নোট</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">


    <style media="print">
        body{
            font-family: 'bangla', sans-serif;
            font-size: 14px;
        }
        table,td{
            border: 1px solid black;
        }
        table{
            border-collapse: collapse;
        }
        td{
            padding: 4px;
        }
        @page {
            /*header: page-header;*/
            footer: page-footer;
            margin-top: 8mm;
            margin-bottom: 4mm;
            margin-left: 8mm;
            margin-right: 8mm;
        }

    </style>

</head>
<body >


<table style="border: none;">
    <tr>
        <td  style="border: none;width: 85mm">
            <p style="border-bottom: 1px solid black;">এস ( এন এস ) - ৪</p>
            <p>এস-১৩৪ (পুরাতন)</p>
        </td>
        <td  style="border: none;">
            <h1>স্টোর ডিমান্ড নোট</h1>
        </td>
        <td  style="border: none;"></td>
    </tr>
</table>
{{--<div style="width: 100%;">--}}
    {{--<div style="display: inline;width: 20%;">--}}
    {{--<p>এস ( এন এস ) - ৪</p>--}}
    {{--<p>এস-১৩৪ (পুরাতন)</p>--}}
    {{--</div>--}}
    {{--<div style="display:inline;width: 78%;">--}}
        {{--<h2 style="text-align: center;">স্টোর ডিমান্ড নোট</h2>--}}
    {{--</div>--}}
{{--</div>--}}


<table style="width: 100%;">
    <tr>
        <td colspan="2">এস-১৩৪ পণ্যের চাহিদাপত্র</td>
        <td colspan="3" rowspan="2" style="vertical-align: top;">
            <p>২। ইস্যু নিয়ন্ত্রন স্ট্যাম্প</p>
            @if(!empty($approverInfo))
                @if(!empty($approverInfo->digital_sign))
                    <span>
                                <img class="navy-logo" src="{{URL::to('/')}}/public/uploads/digital_sign/{!! $approverInfo->digital_sign !!}" width="20%" height="10%">
                            </span>
                    <br>
                @endif
                {!! $approverInfo->first_name.' '.$approverInfo->last_name  !!}<br>
                {!! $approverInfo->rank !!}<br>
                {!! $approverInfo->designation !!}
            @endif
        </td>
        <td colspan="3" rowspan="2" style="vertical-align: top;">
            <span>
               <span>৩।</span> <input type="radio" name="radio" @if($demands->recurring_casual_or_not == 1) checked="checked" @endif> আবর্তক নৈমিত্তিক <br />
            </span>
            <span>
               <span style="color: white;">৩।</span> <input style="margin-left: 20px;" type="radio" name="radio" @if($demands->recurring_casual_or_not == 2) checked="checked" @endif> অনাবর্তক অনৈমিত্তিক
            </span>
        </td>
        <td colspan="5">
            ৪। চাহিদা নং:
            @if(!empty($demands->demand_no))
                {{$demands->demand_no}}
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="2">
            ১। চাহিদাকারী: {{$demands->demandeNameInDemand->name}}
        </td>
        <td colspan="2">
            ৫। প্রায়রিটি: {{$demands->priority}}
        </td>
        <td colspan="3">
            ৬। কবে নাগাদ দরকার:
        </td>
    </tr>
    <tr>
        <td colspan="5">
            ৭। অধিনায়ক অফিসার<br />নৌ-সামগ্রী উপভাণ্ডার, ঢাকা:
        </td>
        <td colspan="3" rowspan="3" style="vertical-align: top;">

	        <?php
	        $remComma = 1;
	        $num_of_items = count($demands->itemsToDemand);
	        ?>
            @if(!empty($demands->itemsToDemand) && count($demands->itemsToDemand) > 0)
                @foreach($demands->itemsToDemand as $ke => $itmsf)
			        <?php
			        $item_patt = \App\Item::find($itmsf->item_id);
			        ?>
                    @if(!empty($item_patt->item_type))

                    @endif
                    <span>
                        <span>৮।</span><input type="radio" name="radio" @if($item_patt->item_type == 1) checked="checked" @endif> স্থায়ী সামগ্রী
                    </span><br>
                    <span>
                        <span style="color: white;">৮।</span><input type="radio" name="radio" @if($item_patt->item_type == 3) checked="checked" @endif>আধা ক্ষয়যোগ্য সামগ্রী
                    </span><br>
                    <span>
                        <span style="color: white;">৮।</span><input type="radio" name="radio" @if($item_patt->item_type == 2) checked="checked" @endif> ক্ষয়যোগ্য সামগ্রী
                    </span>
			        <?php
			        //			                    $quantity = $quantity+ $itmsf->unit;
			        $remComma++;
			        ?>
                @endforeach
            @endif
        </td>
        <td colspan="2" rowspan="3">
            ৯। পোস্টেড<br /> <br/> <br/>
            <p>দস্তখত ও তারিখ</p>
        </td>
        <td colspan="3" rowspan="3">
            ১০। প্রদান করা গেল <br />

            <?php
            $remComma = 1;
            $num_of_items = count($demands->itemsToDemand);
            $quantity = 0;
            ?>

            @if(!empty($demands->itemsToDemand) && count($demands->itemsToDemand) > 0)
                @foreach($demands->itemsToDemand as $ke => $itmsf)
                    <p>
                        <?php
                        $issueDatas=\App\IssueDatas::where('id',$itmsf->issue_id)->first();
                        ?>
                      @if($issueDatas!=null) {!! $issueDatas->issue_date !!} @endif
                      @if(isset($issueDatas['issuedName'])) {!! $issueDatas['issuedName']->name !!} @endif

                    </p>

                @endforeach
            @endif

            <br/>
            <p>দস্তখত ও তারিখ</p>
        </td>
    </tr>
    <tr>
        <td colspan="5">
           ১১। প্রেরণের স্থান: NSSD Dhaka
        </td>
    </tr>
    <tr>
        <td colspan="5">
            ১২। কার জন্য/ কার প্রয়োজনে রণতরী বা ঘাঁটির নামকরুন @if(!empty($demands->for_whom))
                {{$demands->for_whom}}
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="2" style="vertical-align: top;">
            ১৩। সামগ্রীর প্রান্তিক সনাক্তকরন
        </td>
        <td colspan="3" style="vertical-align: top;">
            (ক) যন্ত্রপাতি এবং প্রস্তুতকারক
	        <?php
	        $remComma = 1;
	        $num_of_items = count($demands->itemsToDemand);
	        ?>
            @if(!empty($demands->itemsToDemand) && count($demands->itemsToDemand) > 0)
                @foreach($demands->itemsToDemand as $ke => $itmsf)
			        <?php
			        $manufacturer = \App\Item::find($itmsf->item_id);
			        ?>
                    @if(!empty($manufacturer->manufacturer_name))
                        <P> {{$manufacturer->manufacturer_name}} </P>
                    @endif

			        <?php
			        //			                    $quantity = $quantity+ $itmsf->unit;
			        $remComma++;
			        ?>
                @endforeach
            @endif
        </td>
        <td colspan="2" style="vertical-align: top;">
            (খ)মডেল/টাইপ/মার্ক
	        <?php
	        $remComma = 1;
	        $num_of_items = count($demands->itemsToDemand);
	        ?>
            @if(!empty($demands->itemsToDemand) && count($demands->itemsToDemand) > 0)
                @foreach($demands->itemsToDemand as $ke => $itmsf)
			        <?php
			        $manufacturer = \App\Item::find($itmsf->item_id);
			        ?>
                    @if(!empty($manufacturer->model_number))
                        <P> {{$manufacturer->model_number}} </P>
                    @endif

			        <?php
			        //			                    $quantity = $quantity+ $itmsf->unit;
			        $remComma++;
			        ?>
                @endforeach
            @endif
        </td>
        <td colspan="3" style="vertical-align: top;">
            (গ) ক্রমিক নং/রেজিঃ নং
	        <?php
	        $remComma = 1;
	        $num_of_items = count($demands->itemsToDemand);
	        ?>
            @if(!empty($demands->itemsToDemand) && count($demands->itemsToDemand) > 0)
                @foreach($demands->itemsToDemand as $ke => $itmsf)
			        <?php
			        $manufacturer = \App\Item::find($itmsf->item_id);
			        ?>
                    @if(!empty($manufacturer->imc_number))
                        <P> {{$manufacturer->imc_number}} </P>
                    @endif

			        <?php
			        //			                    $quantity = $quantity+ $itmsf->unit;
			        $remComma++;
			        ?>
                @endforeach
            @endif
        </td>
        <td colspan="3" style="vertical-align: top;text-align: center;">
            (ঘ) প্রকাশন/শ্রেণী বিন্যাস
	        <?php
	        $remComma = 1;
	        $num_of_items = count($demands->itemsToDemand);
	        ?>
            @if(!empty($demands->itemsToDemand) && count($demands->itemsToDemand) > 0)
                @foreach($demands->itemsToDemand as $ke => $itmsf)
			        <?php
			        $item_cat = \App\Item::find($itmsf->item_id);
			        ?>
                    @if(!empty($item_cat->item_cat_id))
				        <?php
				        $category_name = \App\SupplyCategory::find($item_cat->item_cat_id);
				        ?>
                        @if(!empty($category_name->name))
                            <p> {{$category_name->name}}</p>

                        @endif
                    @endif

			        <?php
			        //			                    $quantity = $quantity+ $itmsf->unit;
			        $remComma++;
			        ?>
                @endforeach
            @endif
        </td>
    </tr>
    <tr>
        <td rowspan="2" style="vertical-align: top;">
            ১8। প্যাটার্ন স্টক নং
        </td>
        <td colspan="2" rowspan="2" style="vertical-align: top;">
            ১৫। পণ্যের পূর্ণ বিবরণ ইত্যাদি
        </td>
        <td colspan="2" align="center">
            চাহিদা
        </td>
        <td colspan="2">
            ১৮। পরিমাণ
        </td>
        <td rowspan="2"  style="vertical-align: top;text-align: center;">
            ১৯। কত দেয়া গেল
        </td>
        <td colspan="2" rowspan="2" style="vertical-align: top;">
            <p>২০। হার<p><br />
            টাকা &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;পয়সা
        </td>
        <td colspan="3" rowspan="3">
            <p>২১। সর্বমোট মূল্য</p>
            <p>&nbsp;&nbsp;&nbsp;(১) মূল্য</p>
            <p>&nbsp;&nbsp;&nbsp;(২) বহিঃ শুল্ক</p>
            <p>&nbsp;&nbsp;&nbsp;(৩) বিক্রয় কর</p>
            <p>&nbsp;&nbsp;&nbsp;(৪)মুদ্রার হার</p>
            <p>&nbsp;&nbsp;&nbsp;মোট - </p>
        </td>
    </tr>
    <tr>
        <td style="text-align: center;vertical-align: middle;">১৬ <br />ইউনিট</td>
        <td style="text-align: center;vertical-align: middle;">১৭ <br />এলাউড</td>
        <td style="text-align: center;vertical-align: middle;">বাকী</td>
        <td style="text-align: center;vertical-align: middle;">প্রয়োজনীয়</td>
    </tr>
    <tr>
        <td rowspan="3" style="text-align: center;vertical-align: middle;">
	        <?php
	        $remComma = 1;
	        $num_of_items = count($demands->itemsToDemand);
	        ?>
            @if(!empty($demands->itemsToDemand) && count($demands->itemsToDemand) > 0)
                @foreach($demands->itemsToDemand as $ke => $itmsf)
			        <?php
			        $item_patt = \App\Item::find($itmsf->item_id);
			        ?>
                    @if(!empty($item_patt->patt_number))
                        {{$item_patt->patt_number}}
                        @if($num_of_items > $remComma)
                            {!! '; ' !!}
                        @endif
                    @endif

			        <?php
			        //			                    $quantity = $quantity+ $itmsf->unit;
			        $remComma++;
			        ?>
                @endforeach
            @endif
        </td>
        <td rowspan="3" colspan="2" style="vertical-align: middle;">
	        <?php
	        $remComma = 1;
	        $num_of_items = count($demands->itemsToDemand);
	        $quantity = 0;
	        ?>
            @if(!empty($demands->itemsToDemand) && count($demands->itemsToDemand) > 0)
                @foreach($demands->itemsToDemand as $ke => $itmsf)
                    <p>{!! $itmsf->item_name !!}</p>
			        <?php
			        $quantity = $quantity+ $itmsf->unit;
			        $remComma++;
			        ?>
                @endforeach
            @endif
        </td>
        <td rowspan="3" style="text-align: center;vertical-align: middle;">
	        <?php
	        $remComma = 1;
	        $num_of_items = count($demands->itemsToDemand);
	        ?>
            @if(!empty($demands->itemsToDemand) && count($demands->itemsToDemand) > 0)
                @foreach($demands->itemsToDemand as $ke => $itmsf)
			        <?php
			        $item_patt = \App\Item::find($itmsf->item_id);
			        ?>
                    @if(!empty($item_patt->item_deno))
				        <?php
				        $deno_name = \App\Deno::find($item_patt->item_deno);
				        ?>
                        @if(!empty($deno_name->name))
                            <p> {{$deno_name->name}}</p>

                        @endif
                    @endif

			        <?php
			        //			                    $quantity = $quantity+ $itmsf->unit;
			        $remComma++;
			        ?>
                @endforeach
            @endif
        </td>
        <td rowspan="3">

        </td>
        <td rowspan="2">



        </td>
        <td rowspan="2" style="text-align: center;vertical-align: middle;">{{ $quantity }}</td>
        <td rowspan="2">
            <?php
            $remComma = 1;
            $num_of_items = count($demands->itemsToDemand);
            ?>
            @if(!empty($demands->itemsToDemand) && count($demands->itemsToDemand) > 0)
                @foreach($demands->itemsToDemand as $ke => $itmsf)
                    <p>{!! $itmsf->po_approved_quantity !!}</p>
                    <?php
                    $remComma++;
                    ?>
                @endforeach
            @endif
        </td>
        <td colspan="2"></td>
    </tr>
    <tr>
       <td colspan="5" style="text-align: center;">
           ব্যায়ের খাত
       </td>
    </tr>
    <tr>
        <td colspan="3" rowspan="3">
            ২৩। ১৯ নং কলাম মোতাবেক সামগ্রীর প্রাপ্তি স্বীকার করিলাম<br><br><br>
            <?php
            $remComma = 1;
            $num_of_items = count($demands->itemsToDemand);
            $quantity = 0;
            ?>

            @if(!empty($demands->itemsToDemand) && count($demands->itemsToDemand) > 0)
                @foreach($demands->itemsToDemand as $ke => $itmsf)
                    <p>
                        <?php
                        $issueDatas=\App\IssueDatas::where('id',$itmsf->issue_id)->first();
                        ?>
                        @if($issueDatas!=null) {!! $issueDatas->received_by !!} @endif

                    </p>
                    সামগ্রী গ্রহিতার সাক্ষর<br>

                       @if($issueDatas!=null) <p> {!! $issueDatas->date !!}  </p> @endif

                    তারিখ.........................
                @endforeach
            @endif


        </td>
        <td colspan="2">
            জাহাজ /<br>পরিবেশক কেন্দ্র
        </td>
        <td>শাখা মূল্যায়ন</td>
        <td>মূল্যায়ন</td>
        <td>টাইপ</td>
    </tr>
    <tr>
        <td colspan="5" rowspan="3">
            ২২। আমি এই মর্মে প্রত্যায়ন করিতেছি যে, এই চাহিদাপত্রে যা দাবি করা হইয়াছে সেগুলি (পর পৃষ্ঠায় বর্ণিত) অনুমোদিত/অননুমোদিত।
            অথরিটি নাম্বার: @if(!empty($demands->pattern_or_stock_no)) {{$demands->pattern_or_stock_no}} @endif
            তারিখ: @if(!empty($demands->reference_date)) {{date('d-m-Y',strtotime($demands->reference_date))}} @endif <br /><br /> <br />
            দস্তখত:..............................................................................................<br /><br />
            তারিখ:.........................................পদবী:..............................................
        </td>
        <td colspan="2"></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="5">
            <p>হারকৃত..........................&nbsp;&nbsp;&nbsp;পরীক্ষিত</p>
            <p>মূল্যায়িত........................&nbsp;&nbsp;&nbsp;পরীক্ষিত</p>
        </td>
    </tr>
    <tr>
        <td colspan="8">
            উপরোল্লিখিত সামগ্রী চালান দেওয়ার জন্য গাইট বাঁধা হইয়াছে<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            ভাণ্ডার রক্ষক
        </td>
    </tr>
</table>
<p style="margin-top: 2px;">বি এন সি পি পি - ৫২৯ (এস)</p>
{{-- <htmlpagefooter name="page-footer">
    <table style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic; border: none; width: 100%;">
        <tbody>
        <tr>
            <td width="49%" style="border: none;"><span style="font-weight: bold; font-style: italic;">{!! date('d-m-Y h:i') !!}</span></td>
            <td style="border: none; font-weight: bold; font-style: italic;" align="right" width="49%">Page {PAGENO} of {nbpg}</td>
        </tr>
        </tbody>
    </table>
</htmlpagefooter> --}}

</body>
</html>
