@extends('frontend.layouts.master')
@section('content')

    @include('layouts.flash')

    <!-- page-header -->
    <div id="inner-page-header" class="page-header position-relative">
        <div class="container">
            <div class="row">
                <!-- page caption -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                    <div class="page-caption">
                        <h1 class="page-title">Evaluation Report</h1>
                    </div>
                </div>
                <!-- /.page caption -->
            </div>
        </div>
        <!-- page caption -->
        <div class="page-breadcrumb position-relative">
            <div class="container">
                <div class="row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{!! URL::to($a.$b.'login') !!}" class="breadcrumb-link">Supplier Login</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Evaluation Report</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- page breadcrumb -->
    </div>
    <!-- /.page-header -->

    <!-- couple-sign in -->
    <section class="couple-bg-image pb-5 sectionBg">
        <div class="couple-form">
            <div class="container-fluid">
                <div class="row ">

                    @if (Auth::guard('supplier')->check())

                        <div class="col-lg-3 col-md-3 col-3">
                            @include('frontend/homeinc/menu')
                        </div>
                    @endif

                   <div class="col-lg-9 col-md-9 col-sm-12 col-9">
                        <!--st-tab-->
                        <div class="st-tab">
                            <div class="container-fluid">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">



                                        <div class="">
                                            <!-- form-heading-title -->

                                            @if (Auth::guard('supplier')->check())
                                              
                                                <table class="table table-bordered table-hover" style="background-color: #fff;display: block;overflow-x: auto;">
                                                      <thead>
                                                        <tr class="center">
                                                            <th class="text-center" width="5%">SL#</th>
                                                            <th class="text-center">{!! 'Tender Number' !!}</th>
                                                            <th class="text-center">{!! 'Proper submission of detailed informative document' !!}</th>
                                                            <th class="text-center">{!! 'Reply of Clarification and Queries' !!}}</th>
                                                            <th class="text-center">{!! 'Regularity of supply item (Chance of Grace Period)' !!}</th>
                                                            <th class="text-center">{!! 'Quotation Withdrawn/ Failure to deliver item' !!}</th>
                                                            <th class="text-center">{!! 'Quality of supplied item' !!}}</th>
                                                            <th class="text-center">{!! 'Warranty service after delivery' !!}</th>
                                                            <th class="text-center">{!! 'Reliability' !!}</th>
                                                            <th class="text-center">{!! 'Quoted price (Always High Price/Reasonable Price)' !!}</th>
                                                            <th class="text-center">{!! 'Behavior and cooperation of Supplier/ Representative' !!}</th>
                                                            <th class="text-center">{!! 'Others Remark' !!}</th>
                                                            <th class="text-center">{!! 'Performance Evaluation' !!}</th>
                                                        </tr>
                                                      </thead>
                                                      <tbody>

                                @if (!$queryResult->isEmpty())
                                    <?php
                                        $page = \Input::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page-1)*50;
                                        $l = 1;
                                        function pointTableName ($data, $val=null){
                                            $data = $data->where('lower_point_limit','<=',$val)
                                                            ->where('higher_point_limit','>=',$val)
                                                            ->first();
                                            if(!empty($data)){
                                                return $data->title;
                                            }else{
                                                return '';
                                            }
                                            
                                        }
                                    ?>
                                    @foreach($queryResult as $ctd)
                                        <?php 
                                            $val   = '';
                                            $total = 0;
                                            $count = 0;
                                        ?>
                                        <tr>
                                            <td>{{++$sl}}</td>
                                            <td>{!! $ctd->tender_number !!}</td>
                                            <td>
                                                <?php $val = ''; ?>
                                                @if(!empty($ctd->c1) && !empty($ctd->c1c)) 
                                                    <?php $val = $ctd->c1/$ctd->c1c; ?> 
                                                    @else 
                                                    <?php $val = $ctd->c1; ?>
                                                @endif
                                                {!! pointTableName($pointTableDatas, $val) !!}
                                                @if(!empty($val))
                                                    <?php $total += $val; $count++;?>

                                                @endif
                                                @if(!empty($ctd->c1cm))
                                                    {!! ' ( '.$ctd->c1cm.' ) '  !!}
                                                @endif
                                            </td>
                                            <td>
                                                <?php $val = ''; ?>
                                                @if(!empty($ctd->c2) && !empty($ctd->c2c))
                                                    <?php $val = $ctd->c2/$ctd->c2c; ?> 
                                                    @else 
                                                    <?php $val = $ctd->c2; ?>
                                                @endif
                                                {!! pointTableName($pointTableDatas, $val) !!}
                                                @if(!empty($val))
                                                    <?php $total += $val; $count++; ?>
                                                @endif
                                                @if(!empty($ctd->c2cm))
                                                    {!! ' ( '.$ctd->c2cm.' ) '  !!}
                                                @endif
                                            </td>
                                            <td>
                                                <?php $val = ''; ?>
                                                @if(!empty($ctd->c3) && !empty($ctd->c3c))
                                                    <?php $val = $ctd->c3/$ctd->c3c; ?> 
                                                    @else 
                                                    <?php $val = $ctd->c3; ?>
                                                @endif
                                                {!! pointTableName($pointTableDatas, $val) !!}
                                                @if(!empty($val))
                                                    <?php $total += $val; $count++; ?>
                                                @endif
                                                @if(!empty($ctd->c3cm))
                                                    {!! ' ( '.$ctd->c3cm.' ) '  !!}
                                                @endif
                                            </td>
                                            <td>
                                                <?php $val = ''; ?>
                                                @if(!empty($ctd->c4) && !empty($ctd->c4c))
                                                    <?php $val = $ctd->c4/$ctd->c4c; ?> 
                                                    @else 
                                                    <?php $val = $ctd->c4; ?>
                                                @endif
                                                {!! pointTableName($pointTableDatas, $val) !!}
                                                @if(!empty($val))
                                                    <?php $total += $val; $count++; ?>
                                                @endif
                                                @if(!empty($ctd->c4cm))
                                                    {!! ' ( '.$ctd->c4cm.' ) '  !!}
                                                @endif
                                            </td>
                                            <td>
                                                <?php $val = ''; ?>
                                                @if(!empty($ctd->c5) && !empty($ctd->c5c))
                                                    <?php $val = $ctd->c5/$ctd->c5c; ?> 
                                                    @else 
                                                    <?php $val = $ctd->c5; ?>
                                                @endif
                                                {!! pointTableName($pointTableDatas, $val) !!}
                                                @if(!empty($val))
                                                    <?php $total += $val; $count++; ?>
                                                @endif
                                                @if(!empty($ctd->c5cm))
                                                    {!! ' ( '.$ctd->c5cm.' ) '  !!}
                                                @endif
                                            </td>
                                            <td>
                                                <?php $val = ''; ?>
                                                @if(!empty($ctd->c6) && !empty($ctd->c6c))
                                                    <?php $val = $ctd->c6/$ctd->c6c; ?> 
                                                    @else 
                                                    <?php $val = $ctd->c6; ?>
                                                @endif
                                                {!! pointTableName($pointTableDatas, $val) !!}
                                                @if(!empty($val))
                                                    <?php $total += $val; $count++; ?>
                                                @endif
                                                @if(!empty($ctd->c6cm))
                                                    {!! ' ( '.$ctd->c6cm.' ) '  !!}
                                                @endif
                                            </td>
                                            <td>
                                                <?php $val = ''; ?>
                                                @if(!empty($ctd->c7) && !empty($ctd->c7c))
                                                    <?php $val = $ctd->c7/$ctd->c7c; ?> 
                                                    @else 
                                                    <?php $val = $ctd->c7; ?>
                                                @endif
                                                {!! pointTableName($pointTableDatas, $val) !!}
                                                @if(!empty($val))
                                                    <?php $total += $val; $count++; ?>
                                                @endif
                                                @if(!empty($ctd->c7cm))
                                                    {!! ' ( '.$ctd->c7cm.' ) '  !!}
                                                @endif
                                            </td>
                                            <td>
                                                <?php $val = ''; ?>
                                                @if(!empty($ctd->c8) && !empty($ctd->c8c))
                                                    <?php $val = $ctd->c8/$ctd->c8c; ?> 
                                                    @else 
                                                    <?php $val = $ctd->c8; ?>
                                                @endif
                                                {!! pointTableName($pointTableDatas, $val) !!}
                                                @if(!empty($val))
                                                    <?php $total += $val; $count++; ?>
                                                @endif
                                                @if(!empty($ctd->c8cm))
                                                    {!! ' ( '.$ctd->c8cm.' ) '  !!}
                                                @endif
                                            <td>
                                                <?php $val = ''; ?>
                                                @if(!empty($ctd->c9) && !empty($ctd->c9c))
                                                    <?php $val = $ctd->c9/$ctd->c9c; ?> 
                                                    @else 
                                                    <?php $val = $ctd->c9; ?>
                                                @endif
                                                {!! pointTableName($pointTableDatas, $val) !!}
                                                @if(!empty($val))
                                                    <?php $total += $val; $count++; ?>
                                                @endif
                                                @if(!empty($ctd->c9cm))
                                                    {!! ' ( '.$ctd->c9cm.' ) '  !!}
                                                @endif
                                            </td>
                                            <td>
                                                <?php $val = ''; ?>
                                                @if(!empty($ctd->c10) && !empty($ctd->c10c))
                                                    <?php $val = $ctd->c10/$ctd->c10c; ?> 
                                                    @else 
                                                    <?php $val = $ctd->c10; ?>
                                                @endif
                                                {!! pointTableName($pointTableDatas, $val) !!}
                                                @if(!empty($val))
                                                    <?php $total += $val; $count++; ?>
                                                @endif
                                                @if(!empty($ctd->c10cm))
                                                    {!! ' ( '.$ctd->c10cm.' ) '  !!}
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($count) && !empty($total))

                                                    <?php  $divVal = number_format((float)$total/$count, 1, '.', '');?>
                                                    {!! pointTableName($pointTableDatas, $divVal) !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="13">{{'Empty Data'}}</td>
                                    </tr>
                                @endif
                                    
                                </tbody>
                                                    </table>

                                                     {!! $queryResult->appends(\Input::except('page'))->render() !!}

                                               @endif
                                                </div><!--row-->

                                            <!--/.form -->
                                        </div><!--/.loginArea-->

                                </div>
                            </div>
                        </div><!--/.st-tab-->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.couple-sign up -->
    </section>
@stop