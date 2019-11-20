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
                                        <select class="form-control selectpicker" name="ten_title" id="ten_title"  data-live-search="true">
                                            <option value="">{!! 'All' !!}</option>
                                            @foreach($tenderList as $sp)
                                                <option value="{!! $sp->tender_title !!}" @if($sp->tender_title==$ten_title) {{'selected'}} @endif>{!! $sp->tender_title !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">Tender Number:</label>
                                        <select class="form-control selectpicker" name="ten_number" id="ten_number"  data-live-search="true">
                                            <option value="">{!! 'All' !!}</option>
                                            @foreach($tenderList as $sp)
                                                <option value="{!! $sp->tender_number !!}" @if($sp->tender_number==$ten_number) {{'selected'}} @endif>{!! $sp->tender_number !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">PO Number:</label>
                                        <select class="form-control selectpicker" name="po_number" id="po_number"  data-live-search="true">
                                            <option value="">{!! 'All' !!}</option>
                                            @foreach($tenderList as $sp)
                                                <option value="{!! $sp->po_number !!}" @if($sp->po_number==$po_number) {{'selected'}} @endif>{!! $sp->po_number !!}</option>
                                            @endforeach
                                        </select>
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
                                    {{--<th class="text-center" width="">{{'PO NO'}}</th>--}}
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
                                    $sl = ($page-1)*10;
                                    $l = 1;
                                    $quantity = 0;
                                    $total = 0;
                                    $GrandtotalAll = 0;
                                    $a = null;

                                    ?>
                                    @foreach($suppliersrep as $sc)
                                        <tr>

                                            <td>{{$l++}}</td>
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
                                            <!-- {{--<td>{{$sc->po_number}}</td>--}} -->
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

                                        <td colspan="6" class="text-center"><b>Total</b></td>
                                        <td class="text-center"><b>{{ImageResizeController::custom_format($quantity)}}</b></td>
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

        });
    </script>
@stop