<?php use App\Http\Controllers\ImageResizeController; ?>
@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Budget Code Wise Items</h3>
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
                        <h3>Budget Code Wise Items</h3>
                    </div>
                        <div class="panel-body">

                            <div class="row">

                            {{ Form::open(array('role' => 'form', 'url' => 'budget-code-wise-item', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'supplier-report')) }}

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
                                        <label for="email">Items Name:</label>
                                        <!-- <select class="form-control selectpicker" name="item_id" id="item_id"  data-live-search="true" data-size="15">
                                            <option value="">{!! 'All' !!}</option>
                                            @foreach($itemList as $il)
                                                <option value="{!! $il->id !!}" @if($il->id==$item_id) {{'selected'}} @endif>{!! $il->item_name !!}</option>
                                            @endforeach
                                        </select> -->
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


                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="col-md-12" style="padding-top: 22px;">
                                        <label for="email"></label>
                                        <button type="submit" class="btn btn-primary">{!! 'Search' !!}</button>
                                    </div>
                                </div>
                            </div>


                            {!!   Form::close() !!}

                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{url('budget-code-wise-item-excel-download?nsd_id='.$nsd_id.'&search_item_name='.$srchItmName.'&item_id='.$item_id.'&from='.$from.'&to='.$to.'&budget_cd_id='.$budget_cd_id.'&action=pdf')}}" class="btn btn-primary btn-xs" target="_blank"><i class="fa fa-print"></i> {{'Print'}}</a>
                                    <a href="{{url('budget-code-wise-item-excel-download?nsd_id='.$nsd_id.'&search_item_name='.$srchItmName.'&item_id='.$item_id.'&from='.$from.'&to='.$to.'&budget_cd_id='.$budget_cd_id.'&action=excel')}}" class="btn btn-success btn-xs"><i class="fa fa-download"></i> {{'Export Excel'}}</a><br>
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

                                        @if(empty($item_id)) {{'(All Item) '}}
                                        @else
                                        {{'('.$search_item_name->item_name.')'}}
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
                                    <th class="text-center" width="10%">{{'Budget Code'}}</th>
                                    <th class="text-center" width="10%">{{'Organization'}}</th>
                                    <th class="text-center" width="10%">{{'IMC NO'}}</th>
                                    <th class="text-center" width="15%">{{'ITEM NAME'}}</th>
                                    <th class="text-center" width="5%">{{'DENO'}}</th>
                                    <th class="text-center" width="5%">{{'QTY'}}</th>
                                    <th class="text-center" width="10%">{{'UNIT PRICE'}}</th>
                                    <th class="text-center" width="10%">{{'DISCOUNT AMOUNT'}}</th>
                                    <th class="text-center" width="10%">{{'TOTAL AMOUNT'}}</th>
                                    <th class="text-center" width="10%">{{'GRAND TOTAL'}}</th>

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

                                        function supply_nsd_name($nsd_id=null){
                                            $calName = \App\NsdName::where('id','=',$nsd_id)->value('name');
                                            return $calName;
                                        }

                                    ?>
                                    @foreach($suppliersrep as $sc)
                                        <tr>

                                            <?php
                                                if($a != $sc->budget_code){
                                                    $a = $sc->budget_code;

                                                    $ab = $suppliersrep->where('budget_code','=',$sc->budget_code)->count();
                                                    $inc = 1;
                                                    $grandTotal = $suppliersrep->where('budget_code','=',$sc->budget_code)->sum('total');
                                                    $GrandtotalAll+=$grandTotal;

                                                }

                                            ?>
                                            @if($inc==1)
                                                <td rowspan="{{$ab}}">{{++$sl}}</td>
                                                <td rowspan="{{$ab}}">
                                                    {{$sc->code}}&nbsp;
                                                </td>
                                            <?php //$inc++ ; ?>
                                            @endif
                                            <td>{{ supply_nsd_name($sc->nsd_id) }}</td>
                                            <td>{{$sc->imc_number}}</td>
                                            <td>{{$sc->item_name}}</td>
                                            <td>{{$sc->deno_name}}</td>
                                            <td class="text-center">{{ImageResizeController::custom_format($sc->quantity)}} <?php $quantity += $sc->quantity;?></td>
                                            <td class="text-center">@if(!empty($sc->curname)){!! '('.$sc->curname.')' !!} @endif {{ImageResizeController::custom_format($sc->unit_price)}}</td>
                                            <td class="text-center">@if(!empty($sc->curname)){!! '('.$sc->curname.')' !!} @endif {{ImageResizeController::custom_format($sc->discount_price)}}</td>
                                            <td class="text-center">{!! '(BDT)' !!} {{ImageResizeController::custom_format($sc->total)}}<?php $total += $sc->total;?></td>
                                            @if($inc==1)
                                                <td rowspan="{{$ab}}">
                                                    {!! '(BDT)' !!} {{ImageResizeController::custom_format($grandTotal)}}
                                                </td>
                                                <?php $inc++ ; ?>
                                            @endif

                                        </tr>
                                    @endforeach
                                    <tr>

                                        <td colspan="6" class="text-center"><b>Total</b></td>
                                        <td class="text-center"><b>{{ImageResizeController::custom_format($quantity+$preQuantity)}}</b></td>
                                        <td></td><td></td>
                                        <td class="text-center"><b>{!! '(BDT)' !!} {{ImageResizeController::custom_format($total+$preGrandTotal)}}</b></td>
                                        <td><b>{!! '(BDT)' !!} {{ImageResizeController::custom_format($GrandtotalAll+$preGrandTotal)}}</b></td>
                                    </tr>

                                @else
                                    <tr>
                                        <td colspan="13">{{'Empty Data'}}</td>
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
                    $('.dependinghideshow').val('');
                    $('.dependinghideshow').addClass('hidden');
                }
            });

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
            

        });

    </script>
@stop