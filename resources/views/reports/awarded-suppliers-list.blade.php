<?php use App\Http\Controllers\ImageResizeController; ?>
@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Awarded Suppliers List</h3>
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
                        <h3>Awarded Suppliers List  @if(Input::get('onlysup') == 'onlysup')<button class="btn-primary" onclick="window.close()">Back</button> @endif</h3>
                    </div>
                        <div class="panel-body">
                           
                            <div class="row @if(Input::get('onlysup') == 'onlysup') hidden @endif">

                            {{ Form::open(array('role' => 'form', 'url' => 'awarded-supplier-list', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'supplier-report')) }}

                             <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">Organization:</label>
                                        <select class="form-control selectpicker" name="nsd_id" id="nsd_id"  data-live-search="true">
                                            <option value="">{!! 'All' !!}</option>
                                            @foreach($nsdNames as $nn)
                                                <option value="{!! $nn->id !!}" @if($nn->id==$nsd_id) {{'selected'}} @endif>{!! $nn->name !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">Supplier Name:</label>
                                        {{--<select class="form-control selectpicker" name="sup_id" id="sup_id"  data-live-search="true">--}}
                                            {{--<option value="">{!! 'All' !!}</option>--}}
                                            {{--@foreach($suppliersName as $sp)--}}
                                                {{--<option value="{!! $sp->id !!}" @if($sp->id==$sup_id) {{'selected'}} @endif>{!! $sp->company_name !!}</option>--}}
                                            {{--@endforeach--}}
                                        {{--</select>--}}
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
                                        <label for="email">Item Name/Tender Title:</label>
                                        <select class="form-control selectpicker" name="search_by_cat" id="search_by_cat">
                                            <option value="">{!! 'All' !!}</option>
                                            <option value="1" @if($search_by_cat==1) {!! 'selected' !!} @endif>{!! 'Item Name' !!}</option>
                                            <option value="2" @if($search_by_cat==2) {!! 'selected' !!} @endif>{!! 'Tender Title' !!}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 @if($search_by_cat==2) hidden @endif" id="itemDivHide">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">Items Name:</label>
                                        {{--<select class="form-control selectpicker" name="item_id" id="item_id"  data-live-search="true" data-size="15">--}}
                                            {{--<option value="">{!! 'All' !!}</option>--}}
                                            {{--@foreach($itemList as $il)--}}
                                                {{--<option value="{!! $il->id !!}" @if($il->id==$item_id) {{'selected'}} @endif>{!! $il->item_name !!}</option>--}}
                                            {{--@endforeach--}}
                                        {{--</select>--}}

                                        <?php $srchItmName = !empty($search_item_name) ? $search_item_name->item_name : ''; ?>
                                        {!!  Form::text('search_item_name', $srchItmName, array('id'=> 'search_item_name', 'class' => 'form-control search_item_name', 'autocomplete'=> 'off', 'placeholder'=>'Search Item ...')) !!}
                                        <input type="hidden" id="item_id" name="item_id" value="{!! $item_id !!}">
                                        <div class="form-group col-xs-12 col-sm-12 col-md-6 search_itmem_name_div" id="search_itmem_name_div" style="display: none; display: block; position: absolute; left: 16px;"></div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">Budget Code:</label>
                                        <select class="form-control selectpicker" name="budget_cd_id" id="budget_cd_id"  data-live-search="true" data-size="15">
                                            <option value="">{!! 'All' !!}</option>
                                            @foreach($budget_codes as $bc)
                                                <option value="{!! $bc->id !!}" @if($bc->id==$budget_cd_id) {{'selected'}} @endif>{!! $bc->code !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">From: </label>
                                        {!!  Form::text('from', $from, array('id'=> 'from', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">To:</label>
                                        {!!  Form::text('to', $to, array('id'=> 'to', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">Grand Total Range:</label>
                                        <select class="form-control selectpicker" name="grand_total_select_filter" id="grand_total_select_filter"  data-live-search="true">
                                            <option value="">{!! 'All' !!}</option>
                                                <option value="{!! '1,1100000' !!}" @if($grand_total_select_filter=='1,1100000') {{'selected'}} @endif>{!! 'Under 11,00,000' !!}</option>
                                                <option value="{!! '1100000,2000000' !!}" @if($grand_total_select_filter=='1100000,2000000') {{'selected'}} @endif>{!! '11,00,000 - 20,00,000' !!}</option>
                                                <option value="{!! '2100000,3000000' !!}" @if($grand_total_select_filter=='2100000,3000000') {{'selected'}} @endif>{!! '21,00,000 - 30,00,000' !!}</option>
                                                <option value="{!! '3100000,4000000' !!}" @if($grand_total_select_filter=='3100000,4000000') {{'selected'}} @endif>{!! '31,00,000 - 40,00,000' !!}</option>
                                                <option value="{!! '4100000,5000000' !!}" @if($grand_total_select_filter=='4100000,5000000') {{'selected'}} @endif>{!! '41,00,000 - 50,00,000' !!}</option>
                                                <option value="{!! '5100000,6000000' !!}" @if($grand_total_select_filter=='5100000,6000000') {{'selected'}} @endif>{!! '51,00,000 - 60,00,000' !!}</option>
                                                <option value="{!! '6100000,7000000' !!}" @if($grand_total_select_filter=='6100000,7000000') {{'selected'}} @endif>{!! '61,00,000 - 70,00,000' !!}</option>
                                                <option value="{!! '7100000,8000000' !!}" @if($grand_total_select_filter=='7100000,8000000') {{'selected'}} @endif>{!! '71,00,000 - 80,00,000' !!}</option>
                                                <option value="{!! '8100000,9000000' !!}" @if($grand_total_select_filter=='8100000,9000000') {{'selected'}} @endif>{!! '81,00,000 - 90,00,000' !!}</option>
                                                <option value="{!! '9100000,10000000' !!}" @if($grand_total_select_filter=='9100000,10000000') {{'selected'}} @endif>{!! '91,00,000 - 1,00,00,000' !!}</option>
                                                <option value="{!! 100 !!}" @if($grand_total_select_filter==100) {{'selected'}} @endif>{!! 'Other' !!}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 @if(empty($range_start)) hidden @endif dependinghideshow">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">Grand Total Start: </label>
                                        {!!  Form::text('range_start', $range_start, array('id'=> 'range_start', 'class' => 'form-control', 'autocomplete'=> 'off')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 @if(empty($range_end)) hidden @endif dependinghideshow">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">Grand Total End: </label>
                                        {!!  Form::text('range_end', $range_end, array('id'=> 'range_end', 'class' => 'form-control', 'autocomplete'=> 'off')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="col-md-12" id="searchBtnDiv" @if($search_by_cat==2) style="padding-top: 22px;" @endif>
                                        <label for="email"></label>
                                        <button type="submit" class="btn btn-primary">{!! 'Search' !!}</button>
                                    </div>
                                </div>
                            </div>
                                  

                            {!!   Form::close() !!}    

                            </div>
                           
                        <!-- end of search row     -->

                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{url('awarded-supplier-list-excel-download?nsd_id='.$nsd_id.'&search_supplier_id='.$supName.'&sup_id='.$sup_id.'&search_item_name='.$srchItmName.'&item_id='.$item_id.'&from='.$from.'&to='.$to.'&budget_cd_id='.$budget_cd_id.'&grand_total_select_filter='.$grand_total_select_filter.'&range_start='.$range_start.'&range_end='.$range_end.'&search_by_cat='.$search_by_cat.'&action=pdf')}}" class="btn btn-primary btn-xs" target="_blank"><i class="fa fa-print"></i> {{'Print'}}</a>
                                    <a href="{{url('awarded-supplier-list-excel-download?nsd_id='.$nsd_id.'&search_supplier_id='.$supName.'&sup_id='.$sup_id.'&search_item_name='.$srchItmName.'&item_id='.$item_id.'&from='.$from.'&to='.$to.'&budget_cd_id='.$budget_cd_id.'&grand_total_select_filter='.$grand_total_select_filter.'&range_start='.$range_start.'&range_end='.$range_end.'&search_by_cat='.$search_by_cat.'&action=excel')}}" class="btn btn-success btn-xs"><i class="fa fa-download"></i> {{'Export Excel'}}</a><br>
                                </div>
                            </div>

                            <div class="row">
                                <div class="text-center">
                                    <b>
                                        STATISTIC OF SUPPLIERS PO 
                                        @if(empty($nsd_id)) {{'(All Organizations) '}}
                                        @else 
                                        {{'('.$search_nsd_name->name.')'}}
                                        @endif

                                        @if(empty($sup_id)) {{'(All Suppliers) '}}
                                        @else 
                                        {{'('.$search_supplier_name->company_name.')'}}
                                        @endif

                                        @if(!empty($from)) {!! ' '. date('d M y',strtotime($from)) !!} 
                                        @else
                                        {{'Beginning'}}
                                        @endif  

                                        @if(!empty($to)) TO {!! date('d M y',strtotime($to)) !!} 
                                        @else TO {!! ' '. date('d M y') !!} 
                                        @endif
                                    </b>
                                </div>
                            </div><br>
                            
                            <div class="table-responsive">
                                
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'NAME OF SUPPLIER'}}</th>
                                    <th class="text-center">{{'Tender Title'}}</th>
                                    <th class="text-center" style="min-width: 80px;">{{'Date'}}</th>
                                    <th class="text-center" width="">{{'PO / Contract No'}}</th>
                                    <th class="text-center" width="">{{'Organization'}}</th>
                                    @if($search_by_cat!=2)
                                    <th class="text-center" width="">{{'IMC NO'}}</th>
                                    <th class="text-center" width="">{{'ITEM NAME'}}</th>
                                    <th class="text-center" width="">{{'DENO'}}</th>
                                    <th class="text-center" width="">{{'QTY'}}</th>
                                    <th class="text-center" width="">{{'UNIT PRICE'}}</th>
                                    @endif
                                    <th class="text-center" width="">{{'DISCOUNT AMOUNT'}}</th>
                                    <th class="text-center" width="">{{'TOTAL AMOUNT'}}</th>
                                    <th class="text-center" width="">{{'GRAND TOTAL'}}</th>
                                    
                                </tr>
                                </thead>
                                <tbody>

                                @if (count($suppliersrep)>0)

                                    <?php
                                        $page = \Input::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page-1)*50;
                                        $l = 1;
                                        $quantity = 0;
                                        $total = 0;
                                        $GrandtotalAll = 0;
                                        $a = null;
                                        $b = null;
                                        $incb = 0;
                                        $discount_in_type = 0;
                                        $total_in_type = 0;

                                        function supply_nsd_name($nsd_id=null){
                                            $calName = \App\NsdName::where('id','=',$nsd_id)->value('name');
                                            return $calName;
                                        }
                                       
                                    ?>
                                    @foreach($suppliersrep as $sc)
                                        <tr>
                                            
                                            <td>{{++$sl}} {{$sc->quantitysss}}</td>
                                            <?php
                                                if($a != $sc->company_name){
                                                    $a = $sc->company_name;

                                                    $ab = $suppliersrep->where('company_name','=',$sc->company_name)->count();
                                                    $inc = 1;
                                                    $grandTotal = $suppliersrep->where('company_name','=',$sc->company_name)->sum('total');
                                                    $GrandtotalAll+=$grandTotal;
                                                      
                                                }

                                            ?>
                                            @if($inc==1)
                                                <td rowspan="{{$ab}}">
                                                    <!-- <a class="" href="{{ URL::to('/suppliers/view/'. $sc->supplier_id) }}" title="view" target="_blank">
                                                        {{$sc->company_name}}
                                                    </a> -->
                                                    <a class="" href="{{ URL::to('/awarded-supplier-list?sup_id='. $sc->supplier_id.'&onlysup=onlysup') }}" title="view" target="_blank">
                                                        {{$sc->company_name}}
                                                    </a> &nbsp; &nbsp;
                                                    <a class="" href="{{ URL::to('/suppliers/view/'. $sc->supplier_id) }}" title="view" target="_blank">
                                                        <i class="icon-eye-open"></i>
                                                    </a>
                                                </td>
                                            <?php //$inc++ ; ?>
                                            @endif
                                            
                                            
                                            @if($search_by_cat==2)
                                                <?php

                                                    if($b != $sc->po_number){
                                                        $b = $sc->po_number;

                                                        $abc = $suppliersrep->where('po_number','=',$sc->po_number)->where('company_name','=',$sc->company_name)->count();
                                                        $incb = 1;
                                                        $discount_in_type = $suppliersrep->where('po_number','=',$sc->po_number)->where('company_name','=',$sc->company_name)->sum('discount_price');
                                                        $total_in_type = $suppliersrep->where('po_number','=',$sc->po_number)->where('company_name','=',$sc->company_name)->sum('total');
                                                        
                                                    }
                                                     


                                                ?>
                                                
                                                @if($incb==1)
                                                    
                                                    <td rowspan="{{$abc}}">{{$sc->tender_title}} &nbsp; <a class="" href="{{ URL::to('/awarded-supplier-list?sup_id='. $sc->supplier_id.'&onlysup=onlysup') }}" title="view" target="_blank"><i class="icon-eye-open"></i>
                                                    </a> </td>
                                                    {{-- <td rowspan="{{$abc}}">{{date('d-m-Y',strtotime($sc->tender_opening_date))}}</td> --}}

                                                    <td rowspan="{{$abc}}">@if($sc->tender_opening_date != '1970-01-01 00:00:00' && !empty($sc->tender_opening_date)) {{date('d-m-Y',strtotime($sc->tender_opening_date))}} @endif</td>

                                                    <td rowspan="{{$abc}}">

                                                        {{$sc->po_number}}

                                                    </td>
                                                    <td rowspan="{{$abc}}">{{ supply_nsd_name($sc->nsd_id) }}</td>

                                                @endif

                                            @else
                                            
                                                <td>{{$sc->tender_title}}</td>
                                                {{-- <td>{{date('d-m-Y',strtotime($sc->tender_opening_date))}}</td> --}}
                                                <td>@if($sc->tender_opening_date != '1970-01-01 00:00:00' && !empty($sc->tender_opening_date)) {{date('d-m-Y',strtotime($sc->tender_opening_date))}} @endif</td>
                                                <td>{{$sc->po_number}}</td>  
                                                <td>{{ supply_nsd_name($sc->nsd_id) }}</td>  

                                            @endif  <!-- if search is by tender title -->
                                            
                                            @if($search_by_cat!=2)
                                                <td>{{$sc->imc_number}}</td>
                                                <td>{{$sc->item_name}}</td>
                                                <td>{{$sc->deno_name}}</td>
                                                <td class="text-center">{{ImageResizeController::custom_format($sc->quantity)}} <?php $quantity += $sc->quantity;?></td>
                                                <td class="text-center">@if(!empty($sc->curname)){!! '('.$sc->curname.')' !!} @endif {{ImageResizeController::custom_format($sc->unit_price)}}</td>
                                            @endif
                                            
                                            @if($search_by_cat==2)
                                                @if($incb==1)
                                                    <td rowspan="{{$abc}}" class="text-center">@if(!empty($sc->curname)){!! '('.$sc->curname.')' !!} @endif {{ImageResizeController::custom_format($discount_in_type)}}</td>
                                                    <td rowspan="{{$abc}}" class="text-center">{!! '(BDT)' !!} {{ImageResizeController::custom_format($total_in_type)}}<?php $total += $total_in_type;?></td>
                                                @endif
                                            @else
                                                
                                                <td class="text-center">@if(!empty($sc->curname)){!! '('.$sc->curname.')' !!} @endif {{ImageResizeController::custom_format($sc->discount_price)}}</td>
                                                <td class="text-center">{!! '(BDT)' !!} {{ImageResizeController::custom_format($sc->total)}}<?php $total += $sc->total;?></td>

                                            @endif
                                            
                                            
                                            @if($incb==1)
                                                <?php $incb++ ; ?>
                                            @endif    

                                            @if($inc==1)
                                                <td rowspan="{{$ab}}">
                                                    {!! '(BDT)' !!} {{ImageResizeController::custom_format($grandTotal)}}
                                                </td>
                                                <?php $inc++ ; ?>
                                            @endif

                                            

                                        </tr>
                                    @endforeach
                                    <tr>
                                        
                                        <td colspan="@if($search_by_cat!=2) 9 @else 7 @endif" class="text-center"><b>Total</b></td>

                                        @if($search_by_cat!=2)
                                            <td class="text-center"><b>{{ImageResizeController::custom_format($quantity+$preQuantity)}}</b></td>
                                            <td></td><td></td>
                                        @endif
                                        
                                        <td class="text-center"><b>{!! '(BDT)' !!} {{ImageResizeController::custom_format($total+$preGrandTotal)}}</b></td>
                                        <td><b>{!! '(BDT)' !!} {{ImageResizeController::custom_format($GrandtotalAll+$preGrandTotal)}}</b></td>
                                    </tr>
                                
                                @else
                                    <tr>
                                        <td colspan="@if($search_by_cat!=2) 14 @else 9 @endif">{{'Empty Data'}}</td>
                                    </tr>
                                @endif
                                    
                                </tbody>
                            </table><!---/table-responsive-->
                            
                            </div>
                            
                            @if (count($suppliersrep)>0)
                                {!! $suppliersrep->appends(\Input::except('page'))->render() !!}
                                {{-- {{ $suppliersrep->links()}} --}}
                            @endif
                            

                        </div>
                    </div>
                </div>
            </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function(){

            $(document).on('change','#nsd_id',function(){
                var nsd_id = this.value;
                var csrf     = "<?php echo csrf_token(); ?>";

                $.ajax({
                  type: 'post',
                  url: 'nsd-wise-supplier',
                  data: { _token: csrf, nsd_id:nsd_id},
                  //dataType: 'json',
                  success: function( _response ){
                    //alert(JSON.stringify(_response));
                if(_response!==''){
                    $("#sup_id").empty();
                    $('#sup_id').selectpicker('refresh');
                    $("#sup_id").append(_response['nsdwisesupplier']);
                    $('#sup_id').selectpicker('refresh');
                }

                },
                error: function(_response){
                  alert("error");
                }

              });/*End Ajax*/

            });


            $(document).on('change','#grand_total_select_filter',function(){
                if(this.value == 100){
                    $('.dependinghideshow').removeClass('hidden');
                }else{
                    $('#range_start').val('');
                    $('#range_end').val('');
                    $('.dependinghideshow').val('');
                    $('.dependinghideshow').addClass('hidden');
                }
            });


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

            // Item Search======================================================
            $('.search_item_name').keyup(function() {
                var query = $(this).val();

                if(query == ''){ $('#item_id').val(''); $('.search_itmem_name_div').fadeOut();}

                if (query != '') {
                    var _token     = "<?php echo csrf_token(); ?>";
                    $.ajax({
                        url: "awarded-rep-item-name-live-search",
                        method: "POST",
                        data: {query: query, _token: _token},
                        success: function (data) {
                            $('.search_itmem_name_div').fadeIn();
                            $('.search_itmem_name_div').html(data);
                        }
                    });
                }
            });

            $(document).on('click', '.searchItemName', function () {
                $('.search_itmem_name_div').fadeOut();
                $('#search_item_name').val('');
                $('#item_id').val('');
                $('#search_item_name').val($(this).text());
                $('#item_id').val($(this).attr("value"));

            });
            // End item search =============================================
            
            $(document).on('change', '#search_by_cat', function () {

            	if($(this).val()==2){
            		$("#item_id").val('');
            		$("#search_item_name").val('');
            		$("#itemDivHide").addClass('hidden');
            		$("#searchBtnDiv").css('padding-top','22px');
            	}else{
            		$("#item_id").val('');
            		$("#search_item_name").val('');
            		$("#itemDivHide").removeClass('hidden');
            		$("#searchBtnDiv").css('padding-top','0px');
            	}
            });
            
            

        });

    </script>
@stop