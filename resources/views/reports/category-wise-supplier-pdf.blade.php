<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>Category Wise Supplier Report</title>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<head>
    <style>
        body{
            /*font-family: 'bangla', sans-serif;*/
            font-size: 12pt;
        }

        table td {
            font-size: 12pt !important;
            padding: 5px;
            margin: 0;
        }
        table th {

            font-size: 12pt !important;
            padding: 5px;
            margin: 0;
        }

        .help-split {
            display: inline-block;
            width: 30%;
        }
         .printable-page{
            font-size: 12pt !important;
         }
    </style>
</head>

<body class="printable-page">
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading hbuilt">
                            <h4 class="text-center">Category & Item Wise Supplier ( {!! date('d M y h:i A' ) !!})</h4>
                    </div>
                        <div class="panel-body">

                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center" width="20%">{{'Company Name'}}</th>
                                    <th class="text-center" width="15%">{{'Company Registration No.'}}</th>
                                    <th class="text-center" width="10%">{{'Mobile Number'}}</th>
                                    <th class="text-center" width="20%">{{'Supply Category'}}</th>
                                    <th class="text-center" width="15%">{{'Organization'}}</th>
                                    <th class="text-center" width="15%">{{'TIN Number'}}</th>
                                    
                                </tr>
                                </thead>
                                <tbody>

                                    @if (!$suppliers->isEmpty())

                                    <?php
                                        
                                        $sl = 0;
                                        $l = 1;

                                        function supply_cat_name($cat_id=null){
                                            $calName = \App\SupplyCategory::where('id','=',$cat_id)->value('name');
                                            return $calName;
                                        }

                                        function supply_nsd_name($nsd_id=null){
                                            $calName = \App\NsdName::where('id','=',$nsd_id)->value('name');
                                            return $calName;
                                        }
                                    ?>
                                    @foreach($suppliers as $sc)
                                        <tr> 
                                            <td>{{++$sl}}</td>
                                            <td>{{$sc->company_name}}</td>
                                            <td>{{$sc->company_regi_number_nsd}}</td>
                                            <td>{{$sc->mobile_number}}</td>
                                            {{-- <td>{{$sc->supplyCategoryName->name}}</td> --}}
                                            <td>
                                                <?php 
                                                    $catids = explode(',',$sc->supply_cat_id);
                                                    foreach ($catids as $ctds) {
                                                        $valsss = supply_cat_name($ctds);
                                                        echo "<li style='padding-left: 5px;'>".$valsss."</li>";
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $nsdids = explode(',',$sc->registered_nsd_id);
                                                    foreach ($nsdids as $nsd) {
                                                        $valssss = supply_nsd_name($nsd);
                                                        echo "<li style='padding-left: 5px;'>".$valssss."</li>";
                                                    }
                                                ?>
                                            </td>
                                            <td>{{$sc->tin_number}}</td>
                                            
                                        </tr>
                                    @endforeach
                                
                                @else
                                    <tr>
                                        <td colspan="7">{{'Empty Data'}}</td>
                                    </tr>
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