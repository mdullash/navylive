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

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="requester">Inspection Date:<span class="text-danger">*</span></label>
                                    {!!  Form::text('inspection_date', date('Y-m-d'), array('id'=> 'inspection_date', 'class' => 'form-control datapicker2', 'required', 'readonly')) !!}
                                </div>
                            </div>

                            @if(!empty($selectedSupplier))
                                @foreach($selectedSupplier as $ssup)


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="requester">Supplier Name:<span class="text-danger">*</span></label>
                                    {!!  Form::text('supplier_name', $ssup->suppliernametext, array('id'=> 'supplier_name', 'class' => 'form-control', 'required', 'readonly')) !!}
                                </div>
                            </div>

                                <table class="table table-bordered table-hover table-striped middle-align">

                                <thead>
                                    <tr class="center">
                                        @if($seagMent !=2)
                                        <th class="">{!! 'Approve' !!}</th>
                                        <th class="">{!! 'Accepted with PR' !!}</th>
                                        <th class="">{!! 'Reject' !!}</th>
                                        @endif
                                        <th class="text-center">{!! 'Item Name' !!}</th>
                                        <th class="text-center">{{ 'Quantity' }}</th>
                                        <th class="text-center">{!! 'Comment' !!}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if(!empty($qyeryResutl[$ssup->id]))
                                        @foreach($qyeryResutl[$ssup->id] as $qrl)

                                            <input type="hidden" name="all_sup[]" value="{!! $qrl->dmndtosupcotId !!}">
                                            <tr>
                                                @if($seagMent !=2)
                                                <td>
                                                    <div class="form-group" ><label class="control-label" for="status" style="display: none;"></label>
                                                        <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                            <input class="activity_1 activitycell new{!! $qrl->dmndtosupcotId !!}" type="checkbox" id="" name="dmndtosupcotId[{!! $qrl->dmndtosupcotId !!}]" value="1"><label for="" style="margin-right: 20px;"></label>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="form-group" ><label class="control-label" for="status" style="display: none;"></label>
                                                        <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                            <input class="activity_1 activitycell new{!! $qrl->dmndtosupcotId !!}" type="checkbox" id="" name="dmndtosupcotId[{!! $qrl->dmndtosupcotId !!}]" value="3"><label for="" style="margin-right: 20px;"></label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group hidden pr3">
                                                        <label for="requester">Price Deduction:<span class="text-danger">*</span></label>
                                                        <input type="text" name="pr[{!! $qrl->dmndtosupcotId !!}][]" class="form-control pr3val" style="width: 50%;">
                                                    </div>
                                                    
                                                </td>

                                                <td>
                                                    <div class="form-group" ><label class="control-label" for="status" style="display: none;"></label>
                                                        <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                            <input class="activity_1 activitycell new{!! $qrl->dmndtosupcotId !!}" type="checkbox" id="cst_draft_sup_id{!! $qrl->id !!}" name="dmndtosupcotId[{!! $qrl->dmndtosupcotId !!}]" value="2" >
                                                            <label for="cst_draft_sup_id{!! $qrl->id !!}"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                @endif
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
                                                        <textarea class="form-control" name="inspection_comment[{!! $qrl->dmndtosupcotId !!}][]" rows="1">@if($seagMent ==2) {!! $qrl->inspection_comment !!} @endif</textarea>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                
                            </table>
                            
                             @endforeach <!--end ot first if condition -->
                            @endif <!--end ot first if condition -->

                                <div class="form-group">
                                <div class="col-md-12">
                                    <div>
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                <?php if( !empty(Session::get('acl')[34][24]) && $seagMent !=2 ){ ?>
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