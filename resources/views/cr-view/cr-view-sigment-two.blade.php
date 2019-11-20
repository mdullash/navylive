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

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="requester">Item Receive Date:<span class="text-danger">*</span></label>
                                    {!!  Form::text('item_receive_date', date('Y-m-d'), array('id'=> 'item_receive_date', 'class' => 'form-control datapicker2', 'required', 'readonly')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="requester">CR Number:<span class="text-danger">*</span></label>
                                    {!!  Form::text('cr_number', old('cr_number',$valuesFi->cr_number), array('id'=> 'cr_number', 'class' => 'form-control', 'required')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="requester">Date:<span class="text-danger">*</span></label>
                                    {!!  Form::text('date', date('Y-m-d'), array('id'=> 'date', 'class' => 'form-control datapicker2', 'required', 'readonly')) !!}
                                </div>
                            </div>

                            
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="requester">Supplier Name:<span class="text-danger">*</span></label>
                                            {!!  Form::text('supplier_name', $supplierName, array('id'=> 'supplier_name', 'class' => 'form-control', 'required', 'readonly')) !!}
                                        </div>
                                    </div>


                                    <table class="table table-bordered table-hover table-striped middle-align">

                                        <thead>
                                            <tr class="center">
                                                <th class="text-center">{!! 'Items' !!}</th>
                                                <th class="text-center">{{ 'Quantity' }}</th>
                                                <th class="text-center">{{ 'Quantity Receive' }}</th>
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
                                                            
                                                            {!! $qrl->item_name !!}
                                                            
                                                        </td>

                                                        <td>
                                                            <div class="form-group">
                                                                <input type="number" class="form-control quantity" id="quantity" name="quantity[{!! $qrl->dmndtosupcotId !!}][]" value="<?php if(!empty($demandToTenInfo->head_ofc_apvl_status)){ echo $qrl->itm_to_sup_nhq_app_qty; }else{echo $qrl->quoted_quantity ;} ?>" readonly="">
                                                            </div>
                                                        </td>

                                                        <!--  -->

                                                        <td>
                                                            <div class="form-group">
                                                                <input type="number" class="form-control quantity" id="quantity" name="cr_receive_qty[]" value="{!! $qrl->demand_cr_to_item_cr_receive_qty !!}" min="0" readonly="">
                                                            </div>
                                                        </td>

                                                        <td>
                                                            

                                                            @if($qrl->demand_cr_to_item_inspection_sta==4)
                                                                <span class="btn-primary">Waiting for inspaction checking</span>
                                                            @endif
                                                            @if($qrl->demand_cr_to_item_inspection_sta==1) 
                                                                <!-- <span class="btn-success">Approved <br> </span> -->
                                                                <b style="color: green;">
                                                                     Approve by inspection: 
                                                                    {!! $qrl->demand_cr_to_item_cr_receive_qty !!}
                                                                </b>
                                                            @endif
                                                            @if($qrl->demand_cr_to_item_inspection_sta==2) 
                                                                <!-- <span class="btn-danger">Rejected <br></span> -->
                                                                <b style="color: red;">
                                                                    Reject by inspection:
                                                                    {!! $qrl->demand_cr_to_item_cr_receive_qty !!}
                                                                </b>
                                                            @endif
                                                            @if($qrl->demand_cr_to_item_inspection_sta==3) 
                                                                <!-- <span class="btn-warning">Accepted with PR <br> </span> -->
                                                                <b style="color: black;">
                                                                    Last approve with PR by inspection:
                                                                    {!! $qrl->demand_cr_to_item_cr_receive_qty !!}
                                                                    . Last approve with {!! $qrl->cr_item_price_deduction !!} PR by inspection
                                                                </b>
                                                            @endif
                                                        </td>
                                                        
                                                    </tr>
                                                @endforeach
                                            @endif

                                        </tbody>
                                        
                                    </table>
                                
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="info">Enclosure:</label>
                                    <textarea name="info" class="form-control" rows="3" style="width: 100%;" id="info">
                                        @if(!empty($valuesFi))
                                            {!! $valuesFi->info !!}
                                        @endif
                                    </textarea>
                                </div>
                            </div>
                                <div class="form-group">
                                <div class="col-md-12">
                                    <div>
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                 
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