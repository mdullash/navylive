<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Home</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">


    <style media="print">
        /* General Css */
        * {
            padding: 0;
            margin: 0;
        }
        * {
            box-sizing: border-box;
        }
        body{
            /*max-width: 1920px;*/
            /*margin: auto;*/
            font-family: 'bangla', sans-serif;
            font-size: 16px;
        }
        .wrapper{
            overflow: hidden;
        }
        ul {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        p {
            margin-bottom: 0;
        }

        .btn.focus, .btn:focus {
            outline: 0;
            box-shadow: none;
        }
        /*From Bootstrap*/
        .container {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
        @media (min-width: 1200px){
            .container {
                max-width: 1140px;
            }
        }
        .my-5 {
            margin-top: 3rem!important;
            margin-bottom: 3rem!important;
        }
        .h2, h2 {
            font-size: 2rem;
            font-family: bangla;
            font-weight: 500;
            line-height: 1.2;
            color: inherit;
        }
        table {
            border-collapse: collapse;
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
            padding: .30rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        @media (min-width: 768px){
            .col-md-12 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 100%;
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
        .text-center {
            text-align: center!important;
        }
        p {
            margin-top: 0;
        }
        .form-control {
            display: block;
            width: 100%;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        label {
            display: inline-block;
            margin-bottom: .5rem;
        }
        .d-flex {
            display: -webkit-box!important;
            display: -ms-flexbox!important;
            display: flex!important;
        }
        .form-inline {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            /* -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-flow: row wrap;
            flex-flow: row wrap;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center; */
        }
        @media (min-width: 576px){
            .form-inline .form-control {
                display: inline-block;
                width: auto;
                vertical-align: middle;
            }
            .form-inline label {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                justify-content: center;
                margin-bottom: 0;
            }
        }


        /*================================
                    Table
        ================================== */

        /*Colum Checkbox (Col No: 3 and 8 )*/
        .colNo {
            float: left;
            margin-right: 10px;
        }
        .thirdchkArea,
        .eighthchkArea {
            display: flex;
            flex-direction: column;
        }
        .thirdchkArea li,
        .eighthchkArea li {
            display: flex;
        }
        /* Colum Checkbox (Col No: 3 and 8 ) */
        .thirdColChkArea ,.eighthColChkArea{
            display: flex;
        }
        .defaulterArea .form-group {
            margin-bottom: 0;
        }
        .radio_container {
            text-align: left;
            /*padding-left: 85px;*/
            position: relative;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            padding-top: 2px;
        }
        /*.radio_container input {*/
            /*position: absolute;*/
            /*opacity: 1;*/
            /*cursor: pointer;*/
        /*}*/
        .checkmark {
            position: absolute;
            top: 0;
            left: 0px;
            height: 40px;
            width: 40px;
            background-color: transparent;
            border: 1px solid #ddd;
            border-radius: 50%;
        }
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }
        .radio_container .checkmark:after {
            content: "";
            font-weight: 900;
            color: #231F20;
            width: 15px;
            height: 15px;
            background: #353132;
            border-radius: 50%;
            left: 30%;
            top: 30%;
        }
        .radio_container input:checked ~ .checkmark:after {
            display: block;
        }

        /*Colum Checkbox (Col No: 9 and 10 )*/
        .colNine{
            width: 130px;
            height: 160px;
        }
        .colNineSigDate,
        .colTenSigDate {
            position: relative;
            top: 95px;
            text-align: center;
        }
        /* Colum 16, 17 */
        .demand {
            text-align: center;
        }
        /* Colum 21 */
        .totalPrice {
            padding-left: 28px;
            margin-top: 5px;
        }

        /*Colum 22 declaration*/
        .declaration{
            overflow: hidden;
        }
        .sigDate {
            float: left;
            display: flex;
        }
        .designation {
            display: flex;
        }
        .signatureArea {
            margin-top: 110px;
        }
        .signature label,
        .ackDate label ,
        .sigDate label ,
        .designation label {
            margin-bottom: 0;
            margin-top: 25px;
            line-height: 1;
        }
        .ackDate label {
            margin-top: 20px;
        }
        .signature input.form-control,
        .ackDate input.form-control,
        .designation input.form-control,
        .sigDate input.form-control {
            padding: 0 .75rem;
            border: 0;
            border-bottom: 1px dashed #ced4da;
        }
        .signature {
            margin: 25px 0;
        }
        .form-control:focus {
            box-shadow: none;
        }
        .ackSignature {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .ackSignature input[type="file"] {
            width: 73%;
            margin: auto;
        }

        /* stKepperSig */
        .stKepperSig {
            float: right;
            text-align: center;
            width: 180px;
        }

        /*dashed-border*/
        .dashed-border {
            width: 100%;
            border-bottom: 1px dashed #ced4da;
            margin: 0 8px 5px;
        }
        
        @page {
            /*header: page-header;*/
            footer: page-footer;
        }

    </style>

</head>
<body >

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

<div class="container my-5">
    <h2 style="text-align:center; margin-bottom:20px;">স্টোর ডিমান্ড নোট</h2>
    <form action="#">
        <!-- Table Start -->
        <table class="table table-bordered">
            <tbody>
            <!--tr -->
            <tr>
                <td colspan="2"> <p>এস-১৩৪ পন্যের চাহিদাপত্র</p> </td>
                <td colspan="2" rowspan="2"><p><span>২। </span>ইস্যু নিয়ন্ত্রন স্ট্যাম্প</p>
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
                <!-- thirdColChkArea Start-->
                <td colspan="3" rowspan="2" style="position:relative;">
                    <div class="thirdColChkArea">
                        <p class="colNo" style="display: inline-block"><span>৩। </span></p>
                        <div class="defaulterArea" style="display: inline-block">
                            <div class="form-group">
                                <label class="radio_container col-md-12">
                                     <input type="radio" name="radio" @if($demands->recurring_casual_or_not == 1) checked="checked" @endif> আবর্তক নৈমিত্তিক
                                </label>
                            </div><!--form-group-->
                            <div class="form-group">
                                <label class="radio_container col-md-12">
                                     <input type="radio" name="radio" @if($demands->recurring_casual_or_not == 2) checked="checked" @endif> আবর্তক নৈমিত্তিক
                                </label>
                            </div><!--form-group-->
                        </div>
                    </div>
                </td>  <!-- thirdColChkArea End-->
                <td colspan="4"><p><span>৪। </span>চাহিদা নং: {{$demands->demand_no}}</p></td>
            </tr><!--/tr-->

            <!--tr-->
            <tr>
                <td colspan="2"><p><span>১। </span>চাহিদাকারী: {{$demands->demandeNameInDemand->name}}</p></td>
                <td colspan="1"><p><span>৫। </span>প্রায়রিটি: {{$demands->priority}}</p></td>
                <td colspan="3"><p><span>৬। </span>কবে নগদ দরকার: {{date('d-m-Y',strtotime($demands->when_needed))}}</p></td>
            </tr><!--/tr-->

            <!--tr-->
            <tr>
                <td colspan="4">
                    <p><span>৭। </span> অধিনায়ক অফিসার, নৌ-সামগ্রী উপভাণ্ডার, ঢাকা: {{$demands->for_whom}}</p>
                </td>

                <!-- eighthColChkArea Start-->
                <td rowspan="3" colspan="3">
                    <div class="eighthColChkArea">
                        <p  class="colNo"><span>৮। </span></p>
                        <div class="defaulterArea">
                            <div class="form-group">
                                <label class="radio_container col-md-12"> <input type="radio" name="radio"> স্থায়ী সামগ্রী

                                </label>
                            </div><!--form-group-->
                            <div class="form-group">
                                <label class="radio_container col-md-12"> <input type="radio" name="radio"> প্রায় স্থায়ী সামগ্রী

                                </label>
                            </div><!--form-group-->
                            <div class="form-group">
                                <label class="radio_container col-md-12"><input type="radio" name="radio"> ক্ষয়যোগ্য সামগ্রী

                                </label>
                            </div><!--form-group-->
                        </div>
                    </div>
                </td><!--/.eighthColChkArea End-->

                <td rowspan="3" colspan="1" class="colNine">
                    <p><span>৯। </span> পোস্টেড </p>
                    <p class="colNineSigDate">দস্তখত ও তারিখ</p>
                </td>
                <td rowspan="3" colspan="3">
                    <p><span>১০। </span> প্রদান করা গেল</p>
                    <p class="colTenSigDate">দস্তখত ও তারিখ</p>
                </td>
            </tr><!--/tr-->

            <!--tr-->
            <tr>
                <td colspan="4"><p><span>১১। </span> প্রেরণের স্থান: @if($demands->place_to_send) {{$demands->navalocation_name->name}} @endif</p></td>
            </tr><!--/tr-->

            <!--tr-->
            <tr>
                <td colspan="4">
                    <p><span>১২। </span> কার জন্য/ কার প্রয়োজনে রণতরী বা ঘাঁটির নামকরণ</p>
                    <p>
                        @if(!empty($demands->for_whom))
                            {{$demands->for_whom}}
                            @endif
                    </p>
                </td>
            </tr><!--/tr-->

            <!--tr-->
            <tr>
                <td colspan="2"><p><span>১৩। </span>সামগ্রীর প্রান্তিক সনাক্তকরন</p></td>
                <td colspan="2"><p>
                        <span>(ক)</span> যন্ত্রপাতি এবং প্রস্তুতকারক</p>

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
                <td colspan="2">
                    <p><span>(খ)</span> মডেল/টাইপ/মার্ক</p>
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
                <td colspan="2">
                    <p><span>(গ)</span> ক্রমিক নং/রেজিঃ নং</p>
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
                <td colspan="3">
                    <p><span>(ঘ)</span> প্রকাশন/শ্রেণী বিন্যাস</p>
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
            </tr><!--/tr-->

            <!--tr-->
            <tr>
                <td rowspan="2">
                    <p><span>১8। </span> প্যাটার্ন স্টক নং</p>
                    <p>
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
                    </p>
                </td>
                <td rowspan="2">
                    <p><span>১৫। </span> পণ্যের পূর্ণ বিবরণ ইত্যাদি</p>

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
                <td colspan="2">
                    <p class="demand">চাহিদা</p>
                </td>
                <td colspan="2" class="quantity">
                    <p><span>১৮। </span> পরিমাণ</p>

                </td>
                <td rowspan="2"><p><span>১৯। </span> কত দেয়া গেল</p></td>
                <td rowspan="2"><p><span>২০। </span> হার <br> টাকা পয়সা</p></td>
                <td rowspan="3" colspan="3">
                    <p><span>২১। </span> সর্বমোট মূল্য</p>
                    <ul class="totalPrice">
                        <li>
                            <p><span>(১)</span> মূল্য</p>
                        </li>
                        <li>
                            <p><span>(২)</span> বহিঃ শুল্ক</p>
                        </li>
                        <li>
                            <p><span>(৩)</span> বিক্রয় কর</p>
                        </li>
                        <li>
                            <p><span>(৪)</span> মুদ্রার হার</p>
                        </li>
                        <li>
                            <p>মোট - </p>
                        </li>
                    </ul>
                </td>
            </tr><!--/tr-->
            <!--tr-->
            <tr>
                <td class="text-center">
                    <p><span>১৬ </span> </p>
                    <p>ইউনিট</p>
                </td>
                <td class="text-center">
                    <p><span>১৭ </span> </p>
                    <p>এলাউড</p>
                </td>
                <td><p><span>বাকী</span></p></td>
                <td>
                    <p><span>প্রয়োজনীয়</span></p>

                </td>
            </tr><!--/tr-->
            <!--tr-->
            <tr>
                <td rowspan="2"><p><span> </span></p></td>
                <td rowspan="2"><p><span></span></p></td>
                <td rowspan="2">

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
                <td rowspan="2"><p><span></span></p></td>
                <td rowspan="2"><p><span></span></p></td>
                <td rowspan="2"><p class="q-value">{{ $quantity }}</td>
                <td rowspan="2"><p><span></span></p></td>
                <td></td>
            </tr><!--/tr-->
            <!--tr-->
            <tr>
                <td colspan="4" style="text-align:center;"><p><span>ব্যায়ের খাত </span></p></td>
            </tr><!--/tr-->
            <!--tr-->
            <tr>
                <!-- declaration Start-->
                <td colspan="4" rowspan="4">
                    <div class="declaration">
                        <p class="declarationTxt"><span>২২। </span> আমি এই মর্মে প্রত্যায়ন করিতেছি যে, এই চাহিদাপত্রে যা দাবি করা হইয়াছে সেগুলি (পর পৃষ্ঠায় বর্ণিত) অনুমোদিত/অননুমোদিত।</p>
                        <div class="signatureArea">
                            <div class="signature">
                                <div class="d-flex">
                                    <p>দস্তখত </p><Span class="dashed-border"></Span>
                                </div>
                            </div> <!--/.signature-->
                            <div class="diclarationBtm d-flex"> <!--diclarationBtm-->
                                <div class="sigDate" style="width: 50%;">
                                    <p>তারিখ </p><Span class="dashed-border"></Span>
                                </div>
                                <div class="designation" style="width: 50%;">
                                    <p>পদবী </p><Span class="dashed-border"></Span>
                                </div>
                            </div>  <!--/.diclarationBtm-->
                        </div><!--signatureArea-->
                    </div> <!--/.declaration-->
                </td><!-- declaration End-->

                <!-- acknowledge Start-->
                <td colspan="3" rowspan="3">
                    <p><span>২৩। </span> ১৯ নং কলাম মোতাবেক সামগ্রীর প্রাপ্তি স্বীকার করিলাম</p>
                    <div class="ackSignature">
                        <p style="border-top: 1px dashed #ced4da;">সামগ্রী গ্রহিতার সাক্ষর </p></Span>
                    </div>
                    <div class="ackDate form-inline"style="width: 100%;">
                        <p>তারিখ </p><Span class="dashed-border"></Span>
                    </div>
                </td><!-- acknowledge End-->
                <td><p><span>জাহাজ/ পরিবেশক কেন্দ্র</span></p></td>
                <td><p><span>শাখা মূল্যায়ন</span></p></td>
                <td><p><span>মূল্যায়ন</span></p></td>
                <td><p><span>টাইপ</span></p></td>
            </tr><!--/tr-->
            <!--tr-->
            <tr>
                <td><p><span> </span></p></td>
                <td><p><span></span></p></td>
                <td><p><span></span></p></td>
                <td><p><span></span></p></td>
            </tr><!--/tr-->
            <!--tr-->
            <tr>
                <td colspan="4">
                    <div class="harkrito">
                        <p>হারকৃত  ----------------------- পরীক্ষিত</p>
                        <p>মূল্যায়িত ----------------------- পরীক্ষিত</p>
                    </div>
                </td>
            </tr><!--/tr-->
            <!--tr-->
            <tr>
                <td colspan="7" class="">
                    <p class="stKepperTxt">উপরোল্লিখিত সামগ্রী চালান দেওয়ার জন্য গাইট বাঁধা হইয়াছে</p>
                    <div class="stKepperSig">
                        <p style="border-top: 1px dashed #ced4da;">ভাণ্ডার রক্ষক </p></Span>
                        <!-- <input type="file">
                        <p>ভাণ্ডার রক্ষক</p> -->
                    </div>
                </td>
            </tr><!--/tr-->
            </tbody>
        </table>
        <!-- Table End -->
    </form>

</div><!--/.container-->
</body>
</html>
