<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>Sell Form</title>
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

         @page  {
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
                            <h4 class="text-center" >Enlistment form</h4>
                            <h6 class="text-center" >Collection received</h6>
                    </div>
                        <div style="margin-top:5px" >

                            <table style="width: 100%;">

                                <tbody>

                                <?php if(!empty($sheCdInfo)): ?>

                                    <tr>
                                        <td>Company:</td>
                                        <td><?php echo $sheCdInfo->company_name; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Phone:</td>
                                        <td><?php echo $sheCdInfo->mobile_number; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Email:</td>
                                        <td><?php echo $sheCdInfo->email; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Password:</td>
                                        <td><?php echo $sheCdInfo->password; ?></td>
                                    </tr>


                                    <tr>
                                        <td>NSD Phone:</td>
                                        <td><?php echo $nssdPhone; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Purchase Date:</td>
                                        <td><?php echo date('d-m-Y',strtotime($sheCdInfo->created_at)); ?></td>
                                    </tr>

                                 <?php endif; ?>
                                </tbody>
                            </table><!---/table-responsive-->

                            <div style="margin: 0px 0px 0 0px; font-size:10px; ">
                                <span style="margin: 1px 0;">This username & password validity is 30 days.</span><br>
                                <span style="text-align: right;margin: 1px 0;">Please fill up the form carefully and submit to NSD Dhaka.</span>
                            </div>
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
                <td width="49%" align="right" style="font-size: 8px;"><span style="font-weight: bold; font-style: italic;"><?php echo date('d-m-Y'); ?></span></td>
            </tr>
            </tbody>
        </table>
    </htmlpagefooter>
</body>
</html>

