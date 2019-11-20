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
                    <h3>CST view</h3>
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
                        <h3>CST view</h3>
                    </div>
                        <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'draft-cst-post', 'files'=> true, 'class' => '', 'id'=>'')) }}
                            
                            <input type="hidden" name="demandId" value="{!! $demandId !!}">

                            <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Items Details'}}</th>
                                    <th class="text-center">{{'Machinery / Manufacturer'}}</th>
                                    <th class="text-center">{{'Deno'}}</th>
                                    <th class="text-center">{{'Quantity'}}</th>
                                    <?php $sls = 0; ?>
                                    @if(!empty($supplierResult))
                                        @foreach($supplierResult as $sr)
                                            <th class="text-center" colspan="6">{!! OwnLibrary::numToOrdinalWord(++$sls) .' Lowest'!!}</th>
                                        @endforeach
                                    @endif
                                    
                                </tr>
                                </thead>
                                <tbody>
                                    
                                    @if(!empty($supArray)) 
                                        <tr>
                                            <td></td><td></td><td></td><td></td><td></td>
                                            @foreach($supArray as $sui)
                                            <?php $devideData = explode('?',$sui); ?>
                                                <td colspan="6">
                                                    <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                        <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                            <input class="activity_1 activitycell" type="checkbox" id="cst_draft_sup_id{!! $devideData[0] !!}" name="cst_draft_sup_id[]" value="{!! $devideData[1] !!}" @if(in_array($devideData[1], $supplierAllreadySelected)) checked @endif>
                                                            <label for="cst_draft_sup_id{!! $devideData[0] !!}">{!! $devideData[0] !!}</label>
                                                        </div>
                                                    </div>
                                                    
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endif
                                    @if(!empty($targetArray))
                                        
                                        @foreach($targetArray as $ta)
                                        
                                            <tr>
                                                @foreach($ta['items'] as $itm)
                                                    <td>{!! 1 !!}</td>
                                                    <td>{!! $itm->item_name !!}</td>
                                                    <td>{!! $itm->manufacturing_country !!}</td>
                                                    <td>{!! $itm->denoName !!}</td>
                                                    <td>{!! $itm->unit !!}</td>
                                                @endforeach
                                                <?php //echo "<pre>"; print_r($ta['supi'][0]); exit;?>
                                                @foreach($ta['supi'] as $sp)
                                                        <td>{!! '@TK' !!}</td>
                                                        <td>{!! ImageResizeController::custom_format($sp[0]->unit_price) !!}</td>
                                                        <td>{!! 'x' !!}</td>
                                                        <td>{!! '=' !!}</td>
                                                        <td>{!! $itm->unit !!}</td>
                                                        <td>{!! ImageResizeController::custom_format(($sp[0]->unit_price-$sp[0]->discount_amount)*$itm->unit) !!}</td>
                                                       
                                                @endforeach
                                                
                                            </tr>   
                                        @endforeach

                                        @if(!empty($supTotalAmountArray)) 
                                            <tr>
                                                <td></td><td></td><td></td><td></td><td></td>
                                                @foreach($supTotalAmountArray as $sta)
                                                    <td colspan="6" style="text-align: right;">{!! ImageResizeController::custom_format($sta) !!}</td>
                                                @endforeach
                                            </tr>
                                        @endif

                                        @if(!empty($supWiComArray)) 
                                        <tr>
                                            <td></td><td></td><td></td><td></td><td></td>
                                            @foreach($supWiComArray as $swca)
                                            <?php $devideData2 = explode('?',$swca); ?>
                                                <td colspan="6">
                                                    <input type="hidden" name="suppId[]" value="{!! $devideData2[1] !!}">
                                                    <textarea class="form-control" name="comment[]" rows="2" placeholder="Comment">{!! $devideData2[0] !!}</textarea>
                                                </td>
                                            @endforeach
                                        </tr>
                                        @endif

                                    @endif
                                    
                                </tbody>
                            </table><!---/table-responsive-->
                            </div>

                                <div class="form-group">
                                <div class="col-md-12">
                                    <div>
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                <?php if(!empty(Session::get('acl')[34][19]) && !empty($demand->tender_quation_collection) && empty($demand->cst_supplier_select)){ ?>
                                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">{!! 'Generate Draft CST' !!}</button>
                                <?php } ?>     
                                </div>
                            </div>

                            {!!   Form::close() !!}


                            <div class="row">
                                <div class="col-md-12">
                                    <b>Cost Summary</b><br><br>
                                </div>
                                <?php $slso = 1; //echo "<pre>"; print_r($supArray); exit; ?>
                                @if(!empty($supArray))
                                    @foreach($supArray as $key => $sui)
                                        <?php $devideData = explode('?',$sui); ?>
                                        <div class="col-md-12">
                                           <p style="margin-bottom: 0px;">
                                                <b> {!! $slso++.'. '. $devideData[0] !!}</b>
                                                <?php $devideData2Com = explode('?',$supWiComArray[$key]); ?>
                                                <p style="margin-left: 15px;">
                                                    {!! nl2br($devideData2Com[0]) !!}
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