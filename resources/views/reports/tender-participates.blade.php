<?php use App\Http\Controllers\ImageResizeController; ?>
@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Tender Participates</h3>
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
                        <h3>Tender Participates</h3>
                    </div>
                        <div class="panel-body">
                            
                            <div class="row">

                            {{ Form::open(array('role' => 'form', 'url' => 'tender-participates', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'supplier-report')) }}

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
                                        <label for="email">Tender Name:</label>
                                        {{--{!!Form::select('ten_title', $tenderList, $ten_title, array('class' => 'form-control selectpicker', 'id' => 'ten_title', 'data-live-search' => 'true'))!!}--}}
                                        <?php $serchTenderName = !empty($serchTenderName) ? $serchTenderName->tender_title : ''; ?>
                                        {!!  Form::text('search_tender_name', $serchTenderName, array('id'=> 'search_tender_name', 'class' => 'form-control search_tender_name', 'autocomplete'=> 'off', 'placeholder'=>'Search tender name ...')) !!}
                                        <input type="hidden" id="ten_title" name="ten_title" value="{!! $ten_title !!}">
                                        <div class="form-group col-xs-12 col-sm-12 col-md-6 search_tender_name_div" id="search_tender_name_div" style="display: none; display: block; position: absolute; left: 16px;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">Tender Number:</label>
                                        {{--{!!Form::select('ten_number', $tender_numbers, $ten_number, array('class' => 'form-control selectpicker', 'id' => 'ten_number', 'data-live-search' => 'true'))!!}--}}
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
                                        <label for="email">PO Number:</label>
                                        {{--{!!Form::select('po_number', $po_numbers, $po_number, array('class' => 'form-control selectpicker', 'id' => 'po_number', 'data-live-search' => 'true'))!!}--}}
                                        <?php $serchTenderPo = !empty($serchTenderPo) ? $serchTenderPo->po_number : ''; ?>
                                        {!!  Form::text('search_tender_po', $serchTenderPo, array('id'=> 'search_tender_po', 'class' => 'form-control search_tender_po', 'autocomplete'=> 'off', 'placeholder'=>'Search tender PO number ...')) !!}
                                        <input type="hidden" id="po_number" name="po_number" value="{!! $po_number !!}">
                                        <div class="form-group col-xs-12 col-sm-12 col-md-6 search_tender_po_div" id="search_tender_po_div" style="display: none; display: block; position: absolute; left: 16px;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="col-md-12" style="">
                                        <label for="email"></label>
                                        <button type="submit" class="btn btn-primary">{!! 'Search' !!}</button>
                                    </div>
                                </div>
                            </div>
                                  

                            {!!   Form::close() !!}    

                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{url('tender-participates-excel-download?nsd_id='.$nsd_id.'&search_tender_name='.$serchTenderName.'&ten_title='.$ten_title.'&search_tender_number='.$serchTenderNumber.'&ten_number='.$ten_number.'&search_tender_po='.$serchTenderPo.'&po_number='.$po_number.'&action=pdf')}}" class="btn btn-primary btn-xs" target="_blank"><i class="fa fa-print"></i> {{'Print'}}</a>
                                    <a href="{{url('tender-participates-excel-download?nsd_id='.$nsd_id.'&search_tender_name='.$serchTenderName.'&ten_title='.$ten_title.'&search_tender_number='.$serchTenderNumber.'&ten_number='.$ten_number.'&search_tender_po='.$serchTenderPo.'&po_number='.$po_number.'&action=excel')}}" class="btn btn-success btn-xs"><i class="fa fa-download"></i> {{'Export Excel'}}</a><br>
                                </div>
                            </div>

                            <div class="row">
                                <div class="text-center">
                                    <b>
                                        AWARDED SUPPLIERS LIST
                                        @if(empty($nsd_id)) {{'(All Organizations) '}}
                                        @else 
                                        {{'('.$search_nsd_name->name.')'}}
                                        @endif
                                        @if(!empty($ten_title)) {!! 'Tender Name ('.  $ten_title .')' !!}
                                        @else 
                                        {{'(All Tender)'}}
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
                                    <th class="text-center" width="">{{'Organization'}}</th>
                                    <th class="text-center" width="">{{'IMC NO'}}</th>
                                    <th class="text-center" width="">{{'ITEM NAME'}}</th>
                                    <th class="text-center" width="">{{'DENO'}}</th>
                                    <th class="text-center" width="">{{'QTY'}}</th>
                                    {{--<th class="text-center" width="">{{'UNIT PRICE'}}</th>--}}
                                    {{--<th class="text-center" width="">{{'DISCOUNT AMOUNT'}}</th>--}}
                                    {{--<th class="text-center" width="">{{'TOTAL AMOUNT'}}</th>--}}
                                    {{--<th class="text-center" width="">{{'GRAND TOTAL'}}</th>--}}

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

                                            <td>{{++$sl}}</td>
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
                                                    <a class="" href="{{ URL::to('/suppliers/view/'. $sc->supplier_id) }}" title="view" target="_blank">
                                                        {{$sc->company_name}}
                                                    </a>

                                                </td>
                                                <?php $inc++ ; ?>
                                            @endif

                                            <td>{{$sc->tender_title}}</td>
                                            <td>{{ supply_nsd_name($sc->nsd_id) }}</td>
                                            <td>{{$sc->imc_number}}</td>
                                            <td>{{$sc->item_name}}</td>
                                            <td>{{$sc->deno_name}}</td>
                                            <td class="text-center">{{ImageResizeController::custom_format($sc->quantity)}} <?php $quantity += $sc->quantity;?></td>
                                            <!-- <td class="text-center">{{ImageResizeController::custom_format($sc->unit_price)}}</td>
                                            <td class="text-center">{{ImageResizeController::custom_format($sc->discount_price)}}</td>
                                            <td class="text-center">{{ImageResizeController::custom_format($sc->total)}} --><!-- <?php // $total += $sc->total;?></td>
                                            @if($inc==1)
                                                <td rowspan="{{$ab}}">
                                                    <b>{{ImageResizeController::custom_format($grandTotal)}}</b>
                                                </td>
                                                <?php //$inc++ ; ?>
                                            @endif -->

                                        </tr>
                                    @endforeach
                                    <tr>

                                        <td colspan="7" class="text-center"><b>Total</b></td>
                                        <td class="text-center"><b>{{ImageResizeController::custom_format($quantity+$preQuantity)}}</b></td>
                                        <!-- <td></td><td></td> -->
                                        <!-- <td class="text-center"><b>{{ImageResizeController::custom_format($total)}}</b></td>
                                        <td><b>{{ImageResizeController::custom_format($GrandtotalAll)}}</b></td> -->
                                    </tr>

                                @else
                                    <tr>
                                        <td colspan="12">{{'Empty Data'}}</td>
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
                  url: 'nsd-wise-tender',
                  data: { _token: csrf, nsd_id:nsd_id},
                  //dataType: 'json',
                  success: function( _response ){
                    //alert(JSON.stringify(_response));
                if(_response!==''){
                    $("#ten_id").empty();
                    $('#ten_id').selectpicker('refresh');
                    $("#ten_id").append(_response['nsdwisetender']);
                    $('#ten_id').selectpicker('refresh');
                }

                },
                error: function(_response){
                  alert("error");
                }

              });/*End Ajax*/

            });

            // Tender Name  Search=============================
            $('.search_tender_name').keyup(function() {
                var query = $(this).val();

                if(query == ''){ $('#ten_title').val(''); $('.search_tender_name_div').fadeOut();}

                if (query != '') {
                    var _token     = "<?php echo csrf_token(); ?>";
                    $.ajax({
                        url: "tender-perticipate-tender-name-live-search",
                        method: "POST",
                        data: {query: query, _token: _token},
                        success: function (data) {
                            $('.search_tender_name_div').fadeIn();
                            $('.search_tender_name_div').html(data);
                        }
                    });
                }
            });

            $(document).on('click', '.searchTenderName', function () {
                $('.search_tender_name_div').fadeOut();
                $('#search_tender_name').val('');
                $('#ten_title').val('');
                $('#search_tender_name').val($(this).text());
                $('#ten_title').val($(this).attr("value"));

            });
            // End tender name search =============================================

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

            // Tender PO  Search=============================
            $('.search_tender_po').keyup(function() {
                var query = $(this).val();

                if(query == ''){ $('#po_number').val(''); $('.search_tender_po_div').fadeOut();}

                if (query != '') {
                    var _token     = "<?php echo csrf_token(); ?>";
                    $.ajax({
                        url: "tender-perticipate-tender-po-live-search",
                        method: "POST",
                        data: {query: query, _token: _token},
                        success: function (data) {
                            $('.search_tender_po_div').fadeIn();
                            $('.search_tender_po_div').html(data);
                        }
                    });
                }
            });

            $(document).on('click', '.searchTenderPo', function () {
                $('.search_tender_po_div').fadeOut();
                $('#search_tender_po').val('');
                $('#po_number').val('');
                $('#search_tender_po').val($(this).text());
                $('#po_number').val($(this).attr("value"));

            });
            // End tender po search =============================================

        });
    </script>
@stop