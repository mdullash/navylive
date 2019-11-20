@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Yearly Performance evaluation</h3>
                </h2>
            </div>
            @include('layouts.flash')
        </div>
    </div>
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading hbuilt">
                            <h3>Yearly Performance evaluation</h3>
                    </div>
                        <div class="panel-body">

                            <div class="row">
                                <div class="col-md-12">
                                {{ Form::open(array('role' => 'form', 'url' => 'yearly-performance-evaluation', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'schedule-all')) }}

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="email">Supplier Name:</label>
                                            
                                            <?php $supName = !empty($search_supplier_name) ? $search_supplier_name->company_name : ''; ?>
                                            {!!  Form::text('search_supplier_id', $supName, array('id'=> 'search_supplier_id', 'class' => 'form-control search_supplier_id', 'autocomplete'=> 'off', 'placeholder'=>'Search Supplier ...')) !!}
                                            <input type="hidden" id="sup_id" name="sup_id" value="{!! $sup_id !!}">
                                            <div class="form-group col-xs-12 col-sm-12 col-md-6 search_supplier_id_div" id="search_supplier_id_div" style="display: none; display: block; position: absolute; left: 16px;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="email">Tender Number:</label>
                                            <?php $serchTenderNumber = !empty($serchTenderNumber) ? $serchTenderNumber->tender_number : ''; ?>
                                            {!!  Form::text('search_tender_number', $serchTenderNumber, array('id'=> 'search_tender_number', 'class' => 'form-control search_tender_number', 'autocomplete'=> 'off', 'placeholder'=>'Search tender number ...')) !!}
                                            <input type="hidden" id="ten_number" name="ten_number" value="{!! $ten_number !!}">
                                            <div class="form-group col-xs-12 col-sm-12 col-md-6 search_tender_number_div" id="search_tender_number_div" style="display: none; display: block; position: absolute; left: 16px;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="email">Year: </label>
                                            {!!  Form::text('year', $year, array('id'=> 'year', 'class' => 'form-control', 'autocomplete'=> 'off','readonly')) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="col-md-12" style="padding-top: 5px;">
                                            <label for="submit"></label>
                                            <button type="submit" class="form-control btn btn-primary">{!! 'Search' !!}</button>
                                        </div>
                                    </div>
                                </div>
                                {!!   Form::close() !!}
                                </div>
                            </div>
                            {{--Search End =======================================--}}

                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{!! 'Name of Supplier' !!}</th>
                                    <th class="text-center">{!! 'No of Participate of Tender' !!}</th>
                                    <th class="text-center">{!! 'Participate Percentage of Total Tender' !!}</th>
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
                                            <td>{!! $ctd->company_name !!}</td>
                                            <td>{!! $ctd->nop !!}</td>
                                            <td>{!! round(($ctd->nop*100)/$totalTender, 3) .' %' !!}</td>
                                            <td>
                                                <?php $val = ''; ?>
                                                @if(!empty($ctd->c1) && !empty($ctd->c1c)) 
                                                    <?php $val = $ctd->c1/$ctd->c1c; ?> 
                                                    @else 
                                                    <?php $val = $ctd->c1; ?>
                                                @endif
                                                {!! pointTableName($pointTableDatas, $val) !!}
                                                @if(!empty($val))
                                                    <?php $total += $val; $count++; ?>
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
                                                    <?php $divVal = $total/$count; ?>
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
                            </table><!---/table-responsive-->
                            {!! $queryResult->appends(\Input::except('page'))->render() !!}

                        </div>
                    </div>
                </div>
            </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function(){

            // Supplier Search=============================
            $('.search_supplier_id').keyup(function() {
                var query = $(this).val();

                if(query == ''){ $('#sup_id').val('');  $('.search_supplier_id_div').fadeOut();}

                if (query != '') {
                    var _token     = "<?php echo csrf_token(); ?>";
                    $.ajax({
                        url: "awarded-rep-supplier-name-live-search",
                        method: "POST",
                        data: {query: query, _token: _token},
                        success: function (data) {
                            $('.search_supplier_id_div').fadeIn();
                            $('.search_supplier_id_div').html(data);
                        }
                    });
                }
            });

            $(document).on('click', '.searchSuppName', function () {
                   $('.search_supplier_id_div').fadeOut();
                    $('#search_supplier_id').val('');
                    $('#sup_id').val('');
                    $('#search_supplier_id').val($(this).text());
                    $('#sup_id').val($(this).attr("value"));

            });
            // End supplier search =============================================

            // Tender number  Search=============================
            $('.search_tender_number').keyup(function() {
                var query = $(this).val();

                if(query == ''){ $('#ten_number').val(''); $('.search_tender_number_div').fadeOut();}

                if (query != '') {
                    var _token     = "<?php echo csrf_token(); ?>";
                    $.ajax({
                        url: "tender-perticipate-tender-number-live-search",
                        method: "POST",
                        data: {query: query, _token: _token},
                        success: function (data) {
                            $('.search_tender_number_div').fadeIn();
                            $('.search_tender_number_div').html(data);
                        }
                    });
                }
            });

            $(document).on('click', '.searchTenderNumber', function () {
                $('.search_tender_number_div').fadeOut();
                $('#search_tender_number').val('');
                $('#ten_number').val('');
                $('#search_tender_number').val($(this).text());
                $('#ten_number').val($(this).attr("value"));

            });
            // End tender number search =============================================

            $('#year').datepicker({
                format: "yyyy",
                viewMode: "years", 
                minViewMode: "years"
            });

            /*For Delete Department*/
            $(".exbtovdelete").click(function (e) {
                e.preventDefault();
                
                var id = this.id; 
                var url='{!! URL::to('zone/destroy') !!}'+'/'+id;
                bootbox.confirm("Are you sure?", function (result) {
                    if (result) {
                        var _url = $("#_url").val();
                        window.location.href = url;
                    }
                });
            });

        });
    </script>
@stop