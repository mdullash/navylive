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

                            <input type="hidden" name="poDtsId" value="{!! $poDtsId !!}">
                            <input type="hidden" name="tenderId" value="{!! $tenderId !!}">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="requester">Item Receive Date:<span class="text-danger">*</span></label>
                                    {!!  Form::text('item_receive_date', date('Y-m-d'), array('id'=> 'item_receive_date', 'class' => 'form-control datapicker2', 'required', 'readonly')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="requester">CR Number:<span class="text-danger">*</span></label>
                                    {!!  Form::text('cr_number', old('cr_number',$poDatasInfo->tender_number.'.'), array('id'=> 'cr_number', 'class' => 'form-control', 'required')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="requester">Date:<span class="text-danger">*</span></label>
                                    {!!  Form::text('date', date('Y-m-d'), array('id'=> 'date', 'class' => 'form-control datapicker2', 'required', 'readonly')) !!}
                                </div>
                            </div>

                            @if(!empty($selectedSupplier))
                                @foreach($selectedSupplier as $ssup)
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="requester">Supplier Name:<span class="text-danger">*</span></label>
                                            {!!  Form::text('supplier_name', $ssup->suppliernametext, array('id'=> 'supplier_name', 'class' => 'form-control', 'required', 'readonly')) !!}
                                        </div>
                                    </div>


                                    <table class="table table-bordered table-hover table-striped middle-align">

                                        <thead>
                                            <tr class="center">
                                                <th class="text-center">{!! 'Items' !!}</th>
                                                <th class="text-center">{{ 'Quantity' }}</th>
                                                <th class="text-center">{{ 'Approve Quantity' }}</th>
                                                <th class="text-center">{{ 'Remaining Quantity' }}</th>
                                                <th class="text-center">{{ 'Quantity Receive' }}</th>
                                                <th class="text-center">{!! 'Status' !!}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @if(!empty($qyeryResutl[$ssup->id]))
                                                @foreach($qyeryResutl[$ssup->id] as $qrl)

                                                    <?php

                                                        $totlAppQty  = 0;
                                                        if(!empty($qrl->total_approved)){
                                                            $expTotlApp  = explode(',',$qrl->total_approved);
                                                            $totlAppQty  = array_sum($expTotlApp);
                                                        }

                                                        if(!empty($demandToTenInfo->head_ofc_apvl_status)){
                                                            $remainIngQty = $qrl->itm_to_sup_nhq_app_qty;
                                                        }else{
                                                            $remainIngQty = $qrl->quoted_quantity;
                                                        }
                                                        
                                                        if(!empty($qrl->total_approved)){ 
                                                            $remainIngQty = $remainIngQty - $totlAppQty;
                                                            
                                                        }

         
                                                    ?>

                                                    <tr>
                                                        <td>
                                                            @if($seagMent !=2)
                                                            <div class="form-group" >
                                                                <label class="control-label" for="status" style="display: none;"></label>
                                                                <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                                    <input class="activity_1 activitycell" type="checkbox" id="cst_draft_sup_id{!! $qrl->id !!}" name="dmndtosupcotId[]" value="{!! $qrl->dmndtosupcotId !!}" {{--@if(!empty($qrl->cr_receive_qty) || $qrl->inspection_sta==2) checked @endif --}}>
                                                                    <label for="cst_draft_sup_id{!! $qrl->id !!}">{!! $qrl->item_name !!}</label>
                                                                </div>
                                                            </div>
                                                            @else
                                                                {!! $qrl->item_name !!}
                                                            @endif
                                                        </td>

                                                        <td>
                                                            <div class="form-group">
                                                                <input type="number" class="form-control quantity" id="quantity" name="quantity[{!! $qrl->dmndtosupcotId !!}][]" value="<?php if(!empty($demandToTenInfo->head_ofc_apvl_status)){ echo $qrl->itm_to_sup_nhq_app_qty; }else{echo $qrl->quoted_quantity ;} ?>" readonly="">
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="form-group">
                                                                <input type="number" class="form-control total_appove_qty" id="total_appove_qty" name="total_appove_qty[{!! $qrl->dmndtosupcotId !!}][]" value="<?php echo $qrl->total_approved; ?>" min="0" readonly>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="form-group">
                                                                <input type="number" class="form-control remain_quantity" id="remain_quantity" name="remain_quantity[{!! $qrl->dmndtosupcotId !!}][]" value="<?php echo $remainIngQty; ?>" min="0" readonly>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="form-group">
                                                                <input type="number" class="form-control quantity" id="quantity" name="cr_receive_qty[]" value="{!! $qrl->cr_receive_qty !!}" min="0">
                                                            </div>
                                                        </td>

                                                        <td>
                                                            
                                                            <!-- @if( !empty($qrl->cr_receive_qty) )
                                                                <span>Sent to inspection: {!! $qrl->cr_receive_qty !!};</span>
                                                            @endif
                                                            @if(!empty($totlAppQty))
                                                                <span>Total Approved Qty.:{!! $totlAppQty !!};</span>
                                                            @endif
                                                            <span>Remaining Qty.: {!! $remainIngQty !!};</span> -->

                                                            @if($qrl->inspection_sta==4)
                                                                <span class="btn-primary">Waiting for inspaction checking</span>
                                                            @endif
                                                            @if($qrl->inspection_sta==1) 
                                                                <!-- <span class="btn-success">Approved <br> </span> -->
                                                                <b style="color: green;">
                                                                     Last approve by inspection: 
                                                                    {!! $qrl->last_inspiction_approve !!}
                                                                </b>
                                                            @endif
                                                            @if($qrl->inspection_sta==2) 
                                                                <!-- <span class="btn-danger">Rejected <br></span> -->
                                                                <b style="color: red;">
                                                                    Last reject by inspection:
                                                                    {!! $qrl->last_inspiction_approve !!}
                                                                </b>
                                                            @endif
                                                            @if($qrl->inspection_sta==3) 
                                                                <!-- <span class="btn-warning">Accepted with PR <br> </span> -->
                                                                <b style="color: black;">
                                                                    Last approve with PR by inspection:
                                                                    {!! $qrl->last_inspiction_approve !!}
                                                                    . Last approve with {!! $qrl->price_deduction !!} PR by inspection
                                                                </b>
                                                            @endif
                                                        </td>
                                                        
                                                    </tr>
                                                @endforeach
                                            @endif

                                        </tbody>
                                        
                                    </table>
                                @endforeach <!--end ot first if condition -->
                            @endif <!--end ot first if condition -->
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="info">Enclosure:</label>
                                    <textarea name="info" class="form-control" rows="3" style="width: 100%;" id="info">
                                        @if(!empty($terms_conditions))
                                        @foreach($terms_conditions as $terms_condition)
                                        {!! $terms_condition->descriptions !!}
                                        @endforeach
                                        @endif
                                    </textarea>
                                </div>
                            </div>

                            <!-- Tender Evaluation Start =======
                            ============================= -->
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped middle-align">
                                        <thead>
                                        <tr class="center">
                                            <th class="text-center" style="min-width: 35px;">SL#</th>
                                            <th class="text-center" style="min-width: 250px;">{{'Supplier Name'}}</th>
                                            @if(!empty($evaluCiterias))
                                                @foreach($evaluCiterias as $evc)
                                                    <th class="text-center">{!! $evc->title !!}</th>
                                                @endforeach
                                            @endif
                                        </tr>
                                        </thead>

                                        <tbody>
                                            <?php $slsSp = 1; $forid = 1; ?>
                                            @foreach($selectedSupplier as $sp)
                                                <tr>
                                                    <td>{!! $slsSp++ !!}</td>
                                                    <td>{!! $sp->suppliernametext !!} <input type="hidden" name="dem_to_col_quo_id[]" value="{!! $sp->id !!}" ></td>
                                                    
                                                    @if(!empty($evaluCiterias))
                                                        @foreach($evaluCiterias as $evc)
                                                                <?php 
                                                                    $check = $alreadyMarked->where('supplier_id','=',$sp->supplier_name)->where('evalu_citeria_id','=',$evc->id)->first();
                                                                ?>
                                                            <td>
                                                                <ul class="likes">                   
                                                                    <li> 
                                                                        <input type="radio" name="citeria_{!! $evc->id.'_'.$sp->id !!}" id="inlineRadio{!! $evc->id.$forid !!}" value="0" @if(!empty($check) && $check->marks==0) checked @endif required> 
                                                                        <label class="form-check-label" for="inlineRadio{!! $evc->id.$forid++ !!}">N A</label>
                                                                    </li>
                                                                    <li>
                                                                        <input type="radio" name="citeria_{!! $evc->id.'_'.$sp->id !!}" id="inlineRadio{!! $evc->id.$forid !!}" value="1" @if(!empty($check) && $check->marks==1) checked @endif>                        
                                                                        <label class="form-check-label" for="inlineRadio{!! $evc->id.$forid++ !!}">1</label>
                                                                    </li>
                                                                    <li>
                                                                        <input type="radio" name="citeria_{!! $evc->id.'_'.$sp->id !!}" id="inlineRadio{!! $evc->id.$forid !!}" value="2" @if(!empty($check) && $check->marks==2) checked @endif> 
                                                                        <label class="form-check-label" for="inlineRadio{!! $evc->id.$forid++ !!}">2</label>
                                                                    </li>
                                                                    <li>
                                                                        <input type="radio" name="citeria_{!! $evc->id.'_'.$sp->id !!}" id="inlineRadio{!! $evc->id.$forid !!}" value="3" @if(!empty($check) && $check->marks==3) checked @endif> 
                                                                        <label class="form-check-label" for="inlineRadio{!! $evc->id.$forid++ !!}">3</label>
                                                                    </li>
                                                                    <li>
                                                                        <input type="radio" name="citeria_{!! $evc->id.'_'.$sp->id !!}" id="inlineRadio{!! $evc->id.$forid !!}" value="4" @if(!empty($check) && $check->marks==4) checked @endif> 
                                                                        <label class="form-check-label" for="inlineRadio{!! $evc->id.$forid++ !!}">4</label>
                                                                    </li>
                                                                    <li>
                                                                        <input type="radio" name="citeria_{!! $evc->id.'_'.$sp->id !!}" id="inlineRadio{!! $evc->id.$forid !!}" value="5" @if(!empty($check) && $check->marks==5) checked @endif> 
                                                                        <label class="form-check-label" for="inlineRadio{!! $evc->id.$forid++ !!}">5</label>
                                                                    </li>
                                                                    <li>
                                                                        <input type="radio" name="citeria_{!! $evc->id.'_'.$sp->id !!}" id="inlineRadio{!! $evc->id.$forid !!}" value="6" @if(!empty($check) && $check->marks==6) checked @endif> 
                                                                        <label class="form-check-label" for="inlineRadio{!! $evc->id.$forid++ !!}">6</label>
                                                                    </li>
                                                                    <li>
                                                                        <input type="radio" name="citeria_{!! $evc->id.'_'.$sp->id !!}" id="inlineRadio{!! $evc->id.$forid !!}" value="7" @if(!empty($check) && $check->marks==7) checked @endif> 
                                                                        <label class="form-check-label" for="inlineRadio{!! $evc->id.$forid++ !!}">7</label>
                                                                    </li>
                                                                    <li>
                                                                        <input type="radio" name="citeria_{!! $evc->id.'_'.$sp->id !!}" id="inlineRadio{!! $evc->id.$forid !!}" value="8" @if(!empty($check) && $check->marks==8) checked @endif> 
                                                                        <label class="form-check-label" for="inlineRadio{!! $evc->id.$forid++ !!}">8</label>
                                                                    </li>
                                                                    <li>
                                                                        <input type="radio" name="citeria_{!! $evc->id.'_'.$sp->id !!}" id="inlineRadio{!! $evc->id.$forid !!}" value="9" @if(!empty($check) && $check->marks==9) checked @endif> 
                                                                        <label class="form-check-label" for="inlineRadio{!! $evc->id.$forid++ !!}">9</label>
                                                                    </li>
                                                                    <li>
                                                                        <input type="radio" name="citeria_{!! $evc->id.'_'.$sp->id !!}" id="inlineRadio{!! $evc->id.$forid !!}" value="10" @if(!empty($check) && $check->marks==10) checked @endif> 
                                                                        <label class="form-check-label" for="inlineRadio{!! $evc->id.$forid++ !!}">10</label>
                                                                    </li>
                                                                </ul>
                                                                @if($evc->comment==1)
                                                                    <p style="padding-top: 5px;">
                                                                        <textarea class="form-control" name="citeria_comment_{!! $evc->id.'_'.$sp->id !!}" rows="1" placeholder="Comment">@if(!empty($check) && !empty($check->citeria_comment)) {!! $check->citeria_comment !!} @endif</textarea>
                                                                    </p>
                                                                @endif
                                                            </td>
                                                            
                                                        @endforeach
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Tender Evaluation end =========
                                ===========================-->
                                
                                <div class="form-group">
                                <div class="col-md-12">
                                    <div>
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                <?php if( !empty(Session::get('acl')[34][23]) && $seagMent !=2 ){ ?>
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

    <script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            CKEDITOR.replace( 'info', {
            enterMode: CKEDITOR.ENTER_BR
        } );

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