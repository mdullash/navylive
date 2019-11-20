<?php 
    use functions\OwnLibrary; 
    use App\Http\Controllers\ImageResizeController;
?>
@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Draft CST view</h3>
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
                        <h3>Draft CST view</h3>
                    </div>
                        <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'draft-cst-post', 'files'=> true, 'class' => '', 'id'=>'')) }}
                            
                            <input type="hidden" name="demandId" value="{!! $demandId !!}">
                            <input type="hidden" name="tenderId" value="{!! $tenderId !!}">
                            <div style="margin-bottom: 10px;">
                                 <a href="{{ url('cst-view-print/'.$demandId.'&'.$tenderId) }}" class="btn btn-primary">Print PDF</a>
                                 <a href="{{ url('cst-view-excel/'.$demandId.'&'.$tenderId) }}" class="btn btn-default">Excel</a>
                            </div>

                            <p class="text-center">@if(array_sum($supTotalAmountArray) > 40000) PRELIMINARY @endif COMPARATIVE STATEMENT- NSSD DHAKA <br>
                            {!! $tender->tender_number!!} Date: @if(!empty($tender->created_at)) {!! date('d F Y', strtotime($tender->created_at)) !!} @endif</p>
                            
                            <?php $arSlmen = 0; ?>
                            @if(!empty($mainArray))       
                                @foreach($mainArray as $key => $ta) 
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped middle-align">
                                        <thead>
                                        <tr class="center">
                                            <th class="text-center" width="2%">SL#</th>
                                            <th class="text-center" width="5%">Lot Name</th>
                                            <th class="text-center" width="15%">{{'Items Details'}}</th>
                                            <th class="text-center hidden">{{'Machinery / Manufacturer'}}</th>
                                            <th class="text-center" width="5%">{{'Deno'}}</th>
                                            <th class="text-center" width="5%">{{'Quantity'}}</th>
                                            <th class="text-center" width="13%">{{'Remark'}}</th>
                                            <?php $sls = 0; ?>
                                            @if(!empty($supplierResultFir[$key]))
                                                @foreach($supplierResultFir[$key] as $sr)
                                                    <th class="text-center" colspan="@if(empty($sr->altr_total_price)) 6 @else 12 @endif" width="20">{!! OwnLibrary::numToOrdinalWord(++$sls) .' Lowest'!!}</th>
                                                @endforeach
                                            @endif
                                        </tr>
                                        </thead>

                                        <tbody>
                                            <?php $sl=1; ?>
                                            @if(!empty($supArray[$key])) 
                                                <tr>
                                                    <td colspan="6"></td>
                                                    @foreach($supArray[$key] as $sui) 
                                                    <?php $devideData = explode('?',$sui); ?>
                                                        <td @if(empty($devideData[2])) colspan="6" @else colspan="12" class="text-center" @endif>
                                                            <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                                <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                                    <input class="activity_1 activitycell" type="checkbox" id="cst_draft_sup_id{!! $devideData[0] !!}" name="cst_draft_sup_id[]" value="{!! $devideData[1] !!}" @if($devideData[3]==1) checked @endif>
                                                                    <label for="cst_draft_sup_id{!! $devideData[0] !!}">{!! $devideData[0] !!}</label>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endif

                                            @if(!empty($supArray[$key])) 
                                                <tr style="background-color: #f9f9f9;">
                                                    <td colspan="6" style="border-top: none;"></td>
                                                    @foreach($supArray[$key] as $suiii) 
                                                    <?php $devideData4 = explode('?',$suiii); ?>
                                                        @if(empty($devideData4[2]))
                                                            <td colspan="6" style="border-top: none;"></td>
                                                        @else 
                                                            <td colspan="6">Main Offer</td>
                                                            <td colspan="6">Alternative Offer</td>
                                                        @endif
                                                        
                                                    @endforeach
                                                </tr>        
                                            @endif

                                            <?php $mn = 0; ?>
                                            @foreach($ta as $tas)
                                            <tr>
                                                @if($mn == 0)
                                                    <td rowspan="{!! count($tas) !!}">{!! $sl !!}</td>
                                                    <td rowspan="{!! count($tas) !!}">{!! $key !!}</td>
                                                @endif
                                                @foreach($tas['items'] as $itm)
                                                <td>{!! $itm->item_name !!}</td>
                                                <td class="hidden">{!! $itm->manufacturing_country !!}</td>
                                                <td>{!! $itm->denoName !!}</td>
                                                <td>{!! $itm->unit !!}</td>
                                                <td>
                                                    @if(!empty($itm->previsouSuppName)) {!! 'Sup: '.$itm->previsouSuppName !!} @endif
                                                    @if(!empty($itm->previsouUnitPrice)) {!! ' UP: '.$itm->previsouUnitPrice !!} @endif
                                                    @if(!empty($itm->previousDates)) {!! ' Date: '.$itm->previousDates !!} @endif
                                                </td>
                                                @endforeach

                                                @foreach($tas['supi'] as $sp)
                                                    @if(count($sp)>0)
                                                        
                                                        <td>{!! '@TK' !!}</td>
                                                        <td>{!! ImageResizeController::custom_format($sp[0]->unit_price) !!}</td>
                                                        <td>{!! 'x' !!}</td>
                                                        <td>{!! '=' !!}</td>
                                                        <td>{!! $sp[0]->quoted_quantity !!}</td>
                                                        <td>{!! ImageResizeController::custom_format($sp[0]->unit_price*$sp[0]->quoted_quantity) !!}</td>
                                                        @else
                                                            <td colspan="6">Not participated</td>
                                                        @endif

                                                        @if(!empty($sp[0]->alternative_unit_price))
                                                            <td>{!! '@TK' !!}</td>
                                                            <td>{!! ImageResizeController::custom_format($sp[0]->alternative_unit_price) !!}</td>
                                                            <td>{!! 'x' !!}</td>
                                                            <td>{!! '=' !!}</td>
                                                            <td>{!! $sp[0]->alternative_quoted_quantity !!}</td>
                                                            <td>{!! ImageResizeController::custom_format($sp[0]->alternative_unit_price*$sp[0]->alternative_quoted_quantity) !!}</td>
                                                        @endif
                                                <?php $mn++; ?>
                                                @endforeach
                                            </tr>  
                                            @endforeach
                                            <?php //echo "<pre>"; print_r($ta['supi'][0]); exit;?>
                                            
                                            @if(!empty($supTotalAmountArray)) 
                                                <tr>
                                                    <td colspan="6"></td><td class="hidden"></td>
                                                    @foreach($supTotalAmountArray[$key] as $sta)
                                                    <?php $devideData3 = explode('?',$sta); ?>
                                                        <td colspan="6" style="text-align: right;">{!! ImageResizeController::custom_format($devideData3[0]) !!}</td>
                                                        @if(!empty($devideData3[1])) 
                                                            <td colspan="6" style="text-align: right;">{!! ImageResizeController::custom_format($devideData3[1]) !!}</td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            @endif

                                            @if(!empty($supWiComArray[$key])) 
                                            <tr>
                                                <td colspan="6"></td><td class="hidden"></td>
                                                @foreach($supWiComArray[$key] as $swca)
                                                <?php $devideData2 = explode('?',$swca); ?>
                                                    <td colspan="@if(empty($devideData2[2])) 6 @else 12 @endif">
                                                        <input type="hidden" name="suppId[]" value="{!! $devideData2[3] !!}">
                                                        <textarea class="form-control" name="comment[{!! $devideData2[1].'&'.$devideData2[3] !!}]" rows="1" placeholder="Comment">{!! $devideData2[0] !!}</textarea>
                                                    </td>
                                                @endforeach
                                            </tr>
                                            @endif  
                                                
                                            
                                        </tbody>
                                       
                                    </table><!---/table-responsive-->
                                </div>
                                <?php $arSlmen = 0; $sl++; ?>
                                @endforeach
                            @endif

                                <div class="form-group">
                                <div class="col-md-12">
                                    <div>
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                <?php if(!empty(Session::get('acl')[34][19]) && !empty($tender->tender_quation_collection) && empty($tender->cst_supplier_select)){ ?>
                                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">{!! 'Generate Draft CST' !!}</button>
                                <?php } ?>     
                                </div>
                            </div>

                            {!!   Form::close() !!}


                            <div class="row">
                                <div class="col-md-12">
                                    <b>Remarks</b><br><br>
                                </div>
                                <?php 
                                    $slso = 1;
                                ?>
                                @if(!empty($suppliersInf))
                                    @foreach($suppliersInf as $key => $sui)
                                        <div class="col-md-12">
                                           <p style="margin-bottom: 0px;">
                                                <b> {!! $slso++.'. '. $sui->suppliernametext !!}</b>
                                                <p style="margin-left: 15px;">
                                                    {!! nl2br($sui->comment_on_cst) !!}
                                                </p>
                                                
                                           </p><br>
                                            
                                        </div>
                                    @endforeach
                                @endif

                                
                            </div>
                            


                        </div>

                        

                    </div>
                </div>
            </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function(){

            /*For Delete Department*/
            $(".exbtovdelete").click(function (e) {
                e.preventDefault();
                
                var id = this.id; 
                var url='{!! URL::to('/item/destroy') !!}'+'/'+id;
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