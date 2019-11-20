<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>পিও</title>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<head>
    <style>
        body{
            font-family: 'bangla', sans-serif;
        }
        table td {
            padding: 5px;
            margin: 0;
        }
        table th {
            padding: 5px;
            margin: 0;
        }

    </style>
</head>

<body class="printable-page">
    <div class="content animate-panel">
        <div class="row">
            <div class="col-md-12">
                <div class="hpanel">
                    <div class="panel-heading hbuilt">
                            <h3 class="text-center">Report ( {!! date('d M y h:i A' ) !!})</h3>
                    </div>
                    
                        <div class="panel-body">

                            <table class="">
                                <thead>
                                
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            পিও নং: {!! $po_number !!}
                                        </td>
                                        <td></td>
                                        <td colspan="5">
                                            তারিখ: {!! $date !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8">
                                            মেসার্স: {!! $supplier_name !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8">
                                            আপনার দরপত্র নং <b>{!! $dorportho_no !!}</b> তারিখ <b>{!! $date !!}</b> অনুযায়ী নিম্ন লিখিত সামগ্রীগুলি আগামী <b>{!! $delivery_date !!}</b> তারিখের মধ্যে নেভাল ষ্টোর ডিপো খুলনায় পৌছাতে অনুরোধ করা যাচ্ছে।
                                        </td>
                                    </tr>
                                   
                                </tbody>
                            </table><!---/table-responsive-->
                            
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="">চাহিদা নং ও তারিখ</th>
                                    <th class="text-center">প্যাটার্ণ নং</th>
                                    <th class="text-center" width="">বর্ণনা</th>
                                    <th class="text-center" width="">প্রস্তুকারক</th>
                                    <th class="text-center" width="">পরিমাপক</th>
                                    <th class="text-center" width="">পরিমান</th>
                                    <th class="text-center">হার</th>
                                    <th class="text-center">মোট টাকা</th>
                                    
                                </tr>
                                </thead>
                                <tbody>
                                    <?php $total = 0; ?>
                                    @if (!$qyeryResutl->isEmpty())

                                        @foreach($qyeryResutl as $sc)
                                        <tr> 
                                            <td>{!! $demandInfo->id !!} {!! date('d/m/Y',strtotime($demandInfo->posted_date)) !!}</td>
                                            <td>{!! $demandInfo->pattern_or_stock_no !!}</td>
                                            <td>{!! $demandInfo->product_detailsetc !!}</td>
                                            <td>{!! $selectedSupplier->suppliernametext !!}</td>
                                            <td>{!! $sc->denoName !!}</td>
                                            <td>{!! $sc->quantity !!}</td>
                                            <td>{!! $sc->unit_price-$sc->discount_amount-$sc->final_doscount_amount !!}</td>
                                            <td>{!! $total += ($sc->unit_price-$sc->discount_amount-$sc->final_doscount_amount) *$sc->quantity  !!}</td>
                                        </tr>    
                                        @endforeach
                                    @endif
                                    <tr>
                                        <td colspan="6"></td>
                                        <td>
                                            Total Tk.= 
                                        </td>
                                        <td>
                                            <b>{!! $total !!}</b>
                                        </td>
                                    </tr>
                                </tbody>
                            </table><!---/table-responsive-->
                             
                        </div>
                    </div>
                </div>
            </div>

    </div>
</body>
</html>

