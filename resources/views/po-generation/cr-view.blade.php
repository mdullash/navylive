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
                    <h3>CR view</h3>
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
                        <h3>CR view</h3>
                    </div>
                        <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'cr-view-post', 'files'=> true, 'class' => '', 'id'=>'')) }}

                            <input type="hidden" name="demand_id" value="{!! $demandId !!}">
                            <input type="hidden" name="tenderId" value="{!! $tenderId !!}">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="requester">Supplier Name:<span class="text-danger">*</span></label>
                                    {!!  Form::text('supplier_name', $selectedSupplier->suppliernametext, array('id'=> 'supplier_name', 'class' => 'form-control', 'required', 'readonly')) !!}
                                </div>
                            </div>


                            <table class="table table-bordered table-hover table-striped middle-align">

                                <thead>
                                    <tr class="center">
                                        <th class="text-center">{!! 'Items' !!}</th>
                                        <th class="text-center">{{'Quantity'}}</th>
                                        <th class="text-center">{!! 'Status' !!}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if(!empty($qyeryResutl))
                                        @foreach($qyeryResutl as $qrl)

                                            <?php

                                                $totlAppQty  = 0;
                                                if(!empty($qrl->total_approved)){
                                                    $expTotlApp  = explode(',',$qrl->total_approved);
                                                    $totlAppQty  = array_sum($expTotlApp);
                                                }

                                                $remainIngQty = $qrl->quantity;
                                                if(!empty($qrl->total_approved)){ 
                                                    $remainIngQty = $remainIngQty - $totlAppQty;
                                                    
                                                }

 
                                            ?>

                                            <tr>
                                                <td>
                                                    <div class="form-group" >
                                                        <label class="control-label" for="status" style="display: none;"></label>
                                                        <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                            <input class="activity_1 activitycell" type="checkbox" id="cst_draft_sup_id{!! $qrl->id !!}" name="dmndtosupcotId[]" value="{!! $qrl->dmndtosupcotId !!}" @if(!empty($qrl->cr_receive_qty) || $qrl->inspection_sta==2) checked @endif>
                                                            <label for="cst_draft_sup_id{!! $qrl->id !!}">{!! $qrl->item_name !!}</label>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" class="form-control quantity" id="quantity" name="quantity[{!! $qrl->dmndtosupcotId !!}][]" value="<?php echo $remainIngQty; ?>" min="0">
                                                    </div>
                                                </td>

                                                <td>
                                                    
                                                    @if( !empty($qrl->cr_receive_qty) )
                                                        <span>Sent to inspection: {!! $qrl->cr_receive_qty !!};</span>
                                                    @endif
                                                    @if(!empty($totlAppQty))
                                                        <span>Total Approved Qty.:{!! $totlAppQty !!};</span>
                                                    @endif
                                                    <span>Remaining Qty.: {!! $remainIngQty !!};</span>

                                                    @if($qrl->inspection_sta==3)
                                                        <span class="btn-primary">Waitint for checking</span>
                                                    @endif
                                                    @if($qrl->inspection_sta==1) 
                                                        <span class="btn-success">Approved</span>
                                                    @endif
                                                    @if($qrl->inspection_sta==2) 
                                                        <span class="btn-danger">Rejected</span>
                                                    @endif
                                                </td>
                                                
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                
                            </table>
                            
                            
                                <div class="form-group">
                                <div class="col-md-12">
                                    <div>
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                <?php if( !empty(Session::get('acl')[34][23]) && !empty($demandInfo->po_status) ){ ?>
                                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">{!! 'Action' !!}</button>
                                <?php } ?>     
                                </div>
                            </div>

                            {!!   Form::close() !!}


                            
                            


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