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
                                    {!!  Form::text('inspection_date', date('Y-m-d'), array('id'=> 'inspection_date', 'class' => 'form-control datapicker2', 'required', 'readonly')) !!}
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
                                                        <textarea class="form-control" name="inspection_comment[{!! $qrl->dmndtosupcotId !!}][]" rows="1">@if($seagMent ==2) {!! $qrl->inspection_comment !!} @endif</textarea>
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="form-control selectpicker changeDrop" name="dmndtosupcotId[{!! $qrl->dmndtosupcotId.'&'.$qrl->demand_cr_to_item_id !!}]" id=""  data-live-search="true">
                                                        <option value="1">{!! 'Approve' !!}</option>
                                                        <option value="3">{!! 'Accepted with PR' !!}</option>
                                                        <option value="2">{!! 'Reject' !!}</option>
                                                    </select>
                                                    <div class="form-group hidden pr3">
                                                        <label for="requester">Price Deduction:<span class="text-danger">*</span></label>
                                                        <input type="text" name="pr[{!! $qrl->dmndtosupcotId !!}][]" class="form-control pr3val">
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                
                            </table>

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
            
            // $(".activitycell").change(function(){
            //      var checked = $(this).is(':checked'); // Checkbox state
            //      var lastClass = $(this).attr("class").split(' ').pop();
            //      if(checked){

            //        $('.'+lastClass).each(function() {
            //           $('input.'+lastClass+':checked').prop("checked", false);
            //        });
            //        $(this).prop("checked", true);

            //      }

            //      var valueOfSta = $(this).val(); 
            //      if($(this).is(':checked') && valueOfSta==3){
            //         $(this).closest("td").find('.pr3').removeClass('hidden');
            //      }else{
            //         $(this).closest("td").find('.pr3val').val('');
            //         $(this).closest("td").find('.pr3').addClass('hidden');
            //      }
             
            //   });

            $(document).on('change','.changeDrop',function(){
                var valueOfSta = $(this).val();
                if(valueOfSta==3){
                    $(this).closest("td").find('.pr3').removeClass('hidden');
                 }else{
                    $(this).closest("td").find('.pr3val').val('');
                    $(this).closest("td").find('.pr3').addClass('hidden');
                 }
            });

        });
    </script>
@stop