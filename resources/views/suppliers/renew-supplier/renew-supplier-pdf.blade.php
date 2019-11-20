<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>Renew Supplier</title>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<head>
    <style>
        body{
            /*font-family: 'bangla', sans-serif;*/
            font-size: 12px;
        }

        table td {
            font-size: 12px !important;
            padding: 3px 3px;
            margin: 0;
        }
        table th {

            font-size: 12px !important;
            padding: 3px 3px;
            margin: 0;
        }
        .help-split {
            display: inline-block;
            width: 30%;
        }
         .printable-page{
            font-size: 10px;
         }

         @page {
            footer: page-footer;
             margin: 5mm 3mm 5mm 5mm;
        }

    </style>
</head>

<body class="printable-page">
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading hbuilt">
                            <p class="text-center" style="margin: 0;font-weight: bolder;font-size: 20px;text-align: center;border-bottom: 2px solid black;"> Supplier Renew Copy</p>
                    </div>
                        <div class="panel-body">

                            <table class="table middle-align" style="width: 100%;">
                                
                                <tbody>

                                @if (!empty($sheCdInfo))

                                    <tr>
                                        <td>Company:</td>
                                        <td>{!! $sheCdInfo->company_name !!}</td>
                                    </tr>

                                    <tr>
                                        <td>Supplier Name:</td>
                                        <td>{!! $sheCdInfo->supplier_name !!}</td>
                                    </tr>

                                    <tr>
                                        <td>Phone:</td>
                                        <td>{!! $sheCdInfo->mobile_number !!}</td>
                                    </tr>

                                    <tr>
                                        <td>Email:</td>
                                        <td>{!! $sheCdInfo->email !!}</td>
                                    </tr>

                                    <tr>
                                        <td>NSD Phone:</td>
                                        <td>{!! $nssdPhone !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Purchase Date:</td>
                                        <td>{!! date('d-m-Y',strtotime($sheCdInfo->created_at)) !!}</td>
                                    </tr>
                            
                                 @endif   
                                </tbody>
                            </table><!---/table-responsive-->


                            <div style="margin: 10px 5px 0 10px">
                                <p style="text-align: right;margin: 2px 0;">........................</p>
                                <p style="text-align: right;margin: 2px 0;">Signature</p>
                            </div>

                            <div style="margin-bottom: 20px">
                                <p style="text-align: center;font-size: 11px;">Powered by Impel Service & Solutions Limited</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

    </div>
    <htmlpagefooter name="page-footer">
        <table style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;" width="100%">
            <tbody>
            <tr>
                <td style="font-weight: bold; font-style: italic; font-size: 8px;" align="left" width="49%;">Page {PAGENO} of {nbpg}</td>
                <td width="49%" align="right" style="font-size: 8px;"><span style="font-weight: bold; font-style: italic;">{!! date('d-m-Y') !!}</span></td>
            </tr>
            </tbody>
        </table>
    </htmlpagefooter>
</body>
</html>

