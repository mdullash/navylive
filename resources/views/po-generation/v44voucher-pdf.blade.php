<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>R</title>
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
        /*.help-split {
            display: inline-block;
            width: 30%;
        }
         .printable-page{
            font-size: 10px;
         }*/

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

                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th>পণ্যের নাম</th>
                                    <th>পরিমাণ</th>
                                    <th>তারিখ</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php $total = 0; ?>
                                    @if (!$inspectedItems->isEmpty())

                                        @foreach($inspectedItems as $sc)
                                        <tr> 
                                            <td>{!! $sc->item_name !!} </td>
                                            <td>{!! $sc->approve_qty !!}</td>
                                            <td>{!! date('d/m/Y',strtotime($sc->approve_date)) !!}</td>
                                        </tr>    
                                        @endforeach
                                    @endif
                                    
                                </tbody>
                            </table><!---/table-responsive-->
                             
                        </div>
                    </div>
                </div>
            </div>

    </div>
</body>
</html>

