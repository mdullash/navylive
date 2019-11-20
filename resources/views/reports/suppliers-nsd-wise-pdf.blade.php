<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>Supplier List</title>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<head>
    <style>
        body{
            /*font-family: 'bangla', sans-serif;*/
            font-size: 14px;

        }

        table td {
            font-size: 11px !important;
            padding: 5px;
            margin: 0;
        }
        table th {

            font-size: 11px !important;
            padding: 5px;
            margin: 0;
        }

        .help-split {
            display: inline-block;
            width: 30%;
        }
         .printable-page{
            font-size: 11px !important;
         }

    </style>
</head>

<body class="printable-page">
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading hbuilt">
                            <h4 class="text-center">Suppliers Report ( {!! date('d M y h:i A' ) !!})</h4>
                    </div>
                        <div class="panel-body">

                            <table class="table table-bordered table-hover table-striped middle-align" >
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center" width="10%">{{'Company Name'}}</th>
                                    <th class="text-center" width="10%">{{'Company Registration No.'}}</th>
                                    <th class="text-center" width="10%">{{'Mobile Number'}}</th>
                                    <th class="text-center" width="10%">{{'Supply Category'}}</th>
                                    <th class="text-center" width="10%">{{'TIN Number'}}</th>
                                    <th class="text-center" width="10%">{{'Trade License Number'}}</th>
                                    <th class="text-center" width="10%">{{'Organization'}}</th>
                                    
                                </tr>
                                </thead>
                                <tbody>

                                @if (!$suppliers->isEmpty())

                                    <?php
                                        $page = \Input::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page-1)*10;
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
                                                    //echo "<p>";
                                                    foreach ($catids as $ctds) {
                                                        $valsss = supply_cat_name($ctds);
                                                        echo "<li style='padding-left: 5px;'>".$valsss."</li>";
                                                    }
                                                    //echo "</p>";
                                                ?>
                                            </td>
                                            <td>{{$sc->tin_number}}</td>
                                            <td class="text-center">
                                                {{$sc->trade_license_number}}
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

