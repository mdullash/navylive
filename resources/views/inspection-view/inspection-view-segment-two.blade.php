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
                    <h3>Inspection view</h3>
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
                        <h3>Inspection view</h3>
                    </div>
                        <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'post-inspection', 'files'=> true, 'class' => '', 'id'=>'')) }}

                            <input type="hidden" name="poDtsId" value="{!! $poDtsId !!}">
                            <input type="hidden" name="tenderId" value="{!! $tenderId !!}">
                            <input type="hidden" name="dmnPoToCrid" value="{!! $valuesFi->id !!}">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="requester">Inspection Date:<span class="text-danger">*</span></label>
                                    {!!  Form::text('inspection_date', date('Y-m-d',strtotime($inspectedDate->inspection_date)), array('id'=> 'inspection_date', 'class' => 'form-control datapicker2', 'required', 'readonly')) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="requester">Supplier Name:<span class="text-danger">*</span></label>
                                    {!!  Form::text('supplier_name', $supplierName, array('id'=> 'supplier_name', 'class' => 'form-control', 'required', 'readonly')) !!}
                                </div>
                            </div>

                                <table class="table table-bordered table-hover table-striped middle-align">

                                <thead>
                                    <tr class="center">
                                        <th class="text-center">{!! 'Item Name' !!}</th>
                                        <th class="text-center">{{ 'Quantity' }}</th>
                                        <th class="text-center">{!! 'Comment' !!}</th>
                                        <th class="text-center" width="20%">{!! 'Action' !!}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if(!empty($qyeryResutl))
                                        @foreach($qyeryResutl as $qrl)

                                            <input type="hidden" name="all_sup[]" value="{!! $qrl->dmndtosupcotId !!}">
                                            <tr>
                                                <td>
                                                    {!! $qrl->item_name !!}
                                                </td>
                                                <?php
                                                    $quanTityTo = '';
                                                    if($seagMent==1){
                                                        $quanTityTo = $qrl->cr_receive_qty;
                                                    }else{
                                                        $quanTityTo = $qrl->total_approved;
                                                    }
                                                ?>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" class="form-control quantity" id="quantity" name="quantity[{!! $qrl->dmndtosupcotId !!}][]" value="{!! $quanTityTo !!}" min="0" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <textarea class="form-control" name="inspection_comment[{!! $qrl->dmndtosupcotId !!}][]" rows="1">@if($seagMent ==2) {!! $qrl->inspection_com_sksks !!} @endif</textarea>
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="form-control selectpicker changeDrop" name="dmndtosupcotId[{!! $qrl->dmndtosupcotId.'&'.$qrl->demand_cr_to_item_id !!}]" id=""  data-live-search="true">
                                                        <option value="1" @if($qrl->inspection_sta==1) selected @endif>{!! 'Approve' !!}</option>
                                                        <option value="3" @if($qrl->inspection_sta==3) selected @endif>{!! 'Accepted with PR' !!}</option>
                                                        <option value="2" @if($qrl->inspection_sta==2) selected @endif>{!! 'Reject' !!}</option>
                                                    </select>
                                                    <div class="form-group @if($qrl->inspection_sta !=3) hidden @endif pr3">
                                                        <label for="requester">Price Deduction:<span class="text-danger">*</span></label>
                                                        <input type="text" name="pr[{!! $qrl->dmndtosupcotId !!}][]" class="form-control pr3val" value="{!! $qrl->price_deduction !!}">
                                                    </div>
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

            //$(":checkbox").bind("click", false);
            
            $(".activitycell").change(function(){
                 var checked = $(this).is(':checked'); // Checkbox state
                 var lastClass = $(this).attr("class").split(' ').pop();
                 if(checked){

                   $('.'+lastClass).each(function() {
                      $('input.'+lastClass+':checked').prop("checked", false);
                   });
                   $(this).prop("checked", true);

                 }

                 var valueOfSta = $(this).val(); 
                 if($(this).is(':checked') && valueOfSta==3){
                    $(this).closest("td").find('.pr3').removeClass('hidden');
                 }else{
                    $(this).closest("td").find('.pr3val').val('');
                    $(this).closest("td").find('.pr3').addClass('hidden');
                 }
             
              });

        });
    </script>
@stop