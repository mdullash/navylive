@extends('layouts.default')

<style type="text/css">

.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}
.paddingClass{
    padding-top: 10px;
}
</style>

@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    Po Check
                </h2>
            </div>
            @include('layouts.flash')
        </div>
    </div>

    <div class="content animate-panel">
        <div class="row">
            <div class="col-sm-6 col-md-12">
                <div class="hpanel">
                    <div class="panel-heading sub-title">
                        Po Check
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'post-po-check-edit', 'files'=> true, 'class' => '', 'id'=>'')) }}
                            
                            <input type="hidden" name="podataId" value="{!! $podataId !!}">
                            <input type="hidden" name="tenderId" value="{!! $tenderId !!}">

                        <div class="row">

                            <div class="col-md-6">
                                <div class="row paddingClass">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="requester" class="col-md-12">PO/WO Date:<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                {!!  Form::text('top_date', date('Y-m-d',strtotime($podataInfo->top_date)), array('id'=> 'top_date', 'class' => 'form-control datapicker2', 'required', 'readonly')) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                           <div class="col-md-6">
                               <div class="row paddingClass">
                                   <div class="col-md-12">
                                       <div class="form-group">
                                           <label for="po_number" class="col-md-12">PO/WO Number<span class="text-danger">*</span></label>
                                           <div class="col-md-12">
                                               <input type="po_number" class="form-control col-md-4" name="po_number" id="po_number" value="{!! $podataInfo->po_number !!}" required="">
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>

                            {{--@if(!empty($demandToLprInfo->head_ofc_apvl_status))--}}
                            {{--<div class="row paddingClass">--}}
                                {{--<div class="col-md-12">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<div class="col-md-3">--}}
                                            {{--<label for="email">{!! 'NAVAL Headquarters letter no. ' !!}</label>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-6">--}}
                                            {{--<input type="text" name="headquarters_letter_no" class="form-control" id="headquarters_letter_no" value="{!! $podataInfo->headquarters_letter_no !!}">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--@endif--}}

                            <div class="col-md-6">
                                <div class="row paddingClass">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="email">{!! 'Import Duties' !!}<span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-12">
                                                {{ Form::select('import_duties', array('' => 'Nothing Selected','with' => 'with', 'without' => 'without'), $podataInfo->import_duties, array('class' => 'form-control selectpicker', 'id' => 'import_duties')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row paddingClass">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="email">{!! 'Suppliers' !!}<span class="text-danger">*</span></label>
                                            </div>
					                        <?php $selSup = explode(',', $podataInfo->selected_supplier); ?>
                                            <div class="col-md-12">
                                                <select class="form-control selectpicker" name="selected_supplier[]" id="selected_supplier" required>
                                                    <option value="">{!! '- Select -' !!}</option>
                                                    @foreach($winnerSuppliers as $ws)
                                                        <option value="{!! $ws->id !!}" @foreach($selSup as $sls) @if($sls==$ws->id) selected @endif @endforeach>{!! $ws->suppliernametext !!}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row paddingClass">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="email">{!! 'Supply to' !!}<span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-12">
                                                <input type="text" name="supply_to" class="form-control" id="supply_to" value="{!! $podataInfo->supply_to !!}" required="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row paddingClass">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="inspection_Authority">{!! 'Inspection Authority' !!}<span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-12">
                                                <input type="text" name="inspection_Authority" class="form-control" id="inspection_Authority" value="{{$podataInfo->inspection_Authority}}" required="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row paddingClass">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="requester" class="col-md-12">Supply Date:<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                {!!  Form::text('supply_date', date('Y-m-d',strtotime($podataInfo->supply_date)), array('id'=> 'supply_date', 'class' => 'form-control datapicker2', 'required', 'readonly')) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <div class="col-md-6">
                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="inclusser">{!! 'Enclosure' !!}</label>
                                        </div>
                                        <div class="col-md-12">
                                            <textarea type="text" name="inclusser" class="form-control" id="inclusser" >{{$podataInfo->inclusser}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row paddingClass">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="info">{!! 'Info' !!}</label>
                                        </div>
                                        <div class="col-md-12">
                                            <textarea type="text" name="info" class="form-control" id="info" >
                                                @if(!empty($podataInfo->info)) 
                                                    {{$podataInfo->info}}
                                                @endif
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="info"></label>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="checkbox checkbox-success" style="margin-top: 13px;">
                                                <input class="activity_1 activitycell placeorder2 lowtoheigh" type="checkbox" id="is_part_delivery" name="is_part_delivery" value="1" {{$podataInfo->is_part_delivery ? "checked":''}}>
                                                <label for="is_part_delivery" style="font-size: 14px;font-weight: 600;">Part Delivery Allowed</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="info"></label>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="checkbox checkbox-success" style="margin-top: 13px;">
                                                <input class="activity_1 activitycell placeorder2 lowtoheigh" type="checkbox" id="is_enclosure" name="is_enclosure" value="1" {{$podataInfo->is_enclosure ? "checked":''}}>
                                                <label for="is_enclosure" style="font-size: 14px;font-weight: 600;">Item List in Enclosure</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label for="info"></label>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="checkbox checkbox-success" style="margin-top: 13px;">
                                                <input class="activity_1 activitycell placeorder2 lowtoheigh" type="checkbox" id="is_contract_with" name="is_contract_with" value="1" {{$podataInfo->is_contract_with ? "checked":''}}>
                                                <label for="is_contract_with" style="font-size: 14px;font-weight: 600;">With Contract</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <div class="col-md-12" id="showSupplierItems" style="padding: 10px 35px;">
                                @foreach($colIds as $qrr)
                                <?php $supplierInfo = \App\DemandToCollectionQuotation::find($qrr); ?>
                                <div><b>{!! $supplierInfo->suppliernametext !!}</b></div>
                                <table class="table table-bordered table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Unit Price</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($qyeryResutl[$qrr] as $qr)
                                            <tr>
                                                <td>{!! $qr->item_name !!}</td>
                                                <td>{!! $qr->unit_price !!}</td>
                                                <td>{!! $qr->quantity !!}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endforeach
                            </div>
                            
                            <?php 
                                $lastCount = 1; 
                                $temrsConditon = explode('<br>', $podataInfo->terms_conditions);
                            ?>
                        <div class="row paddingClass">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label >Conditions</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                            @if(!empty($temrsConditon))
                                @foreach($temrsConditon as $tc)
                                <div class="row paddingClass remove">
                                    <div class="col-md-12">
                                        <div class="col-md-2">
                                                <div class="checkbox checkbox-success" style="; text-align: right;">
                                                    <input class="activity_1 activitycell" type="checkbox" id="term_con{!! $lastCount !!}" name="term_con[]" value="{!! $lastCount !!}" checked="">
                                                    <label for="term_con{!! $lastCount !!}"></label>
                                                </div>
                                            
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" name="term_con_text[{!! $lastCount !!}]" class="form-control" id="term_con_text" value="{!! $tc !!}">
                                        </div>
                                    </div>
                                </div>
                                <?php $lastCount++; ?>
                                @endforeach
                            @endif

                            <div class="row paddingClass remove">
                                <div class="col-md-12">
                                    <div class="col-md-2">
                                        <div class="checkbox checkbox-success" style="; text-align: right;">
                                            <input class="activity_1 activitycell" type="checkbox" id="term_con{!! $lastCount !!}" name="term_con[]" value="{!! $lastCount !!}">
                                            <label for="term_con{!! $lastCount !!}"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="term_con_text[{!! $lastCount !!}]" class="form-control" id="term_con_text" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <!-- <button class="btn btn-info" id="addNewRow" type="button" title="Add New"><i class="icon-plus"></i></button> -->
                                    </div>
                                </div>
                            </div>

                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="col-md-2">
                                        
                                    </div>
                                    <div class="col-md-8">
                                        
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-info" id="addNewRow" type="button" title="Add New"><i class="icon-plus"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label for="email">{!! 'Action' !!}</label>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control selectpicker" name="status" id="status" required>
                                                <option value="1">{!! 'Approve' !!}</option>
                                                <option value="2">{!! 'Reject' !!}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br><br>
                            
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
                                            @foreach($winnerSuppliers as $sp)
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
                                <div class="col-md-12 ">
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                    
                                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">{!!trans('english.SAVE')!!}</button>
                                    
                                </div>
                            </div>

                        </div>
                    <!-- <div class="hr-line-dashed"></div> -->
                        {!!   Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <script type="text/javascript">
        $(document).ready(function(){

            $(document).on('change','#selected_supplier',function(){
                var supplierIds = $(this).val();
                var lpr_id      = '<?php echo $demandToLprId; ?>';
                var tender_id   = '<?php echo $tenderId; ?>';
                if(supplierIds != '' && supplierIds != null){

                    var _token     = "<?php echo csrf_token(); ?>";
                    $.ajax({
                        url: '{{ url('winner-wise-items') }}',
                        method: "POST",
                        data: {supplierIds: supplierIds, tender_id: tender_id, lpr_id: lpr_id, _token: _token},
                        success: function (data) {
                            $("#showSupplierItems").empty();
                            $("#showSupplierItems").html(data);
                        }
                    });
                    
                }else{
                    $("#showSupplierItems").empty();
                }
            });

            var i  = '<?php echo $lastCount; ?>';
            i = i-1;
            var sl = '<?php echo $lastCount; ?>';
            sl ++;
            $(document).on('click','#addNewRow',function(){
                $( "body" ).find( ".remove" ).eq( i ).after( '<div class="row paddingClass remove"><div class="col-md-12"><div class="col-md-2"><div class="checkbox checkbox-success" style="; text-align: right;"><input class="activity_1 activitycell" type="checkbox" id="term_con'+sl+'" name="term_con[]" value="'+sl+'"><label for="term_con'+sl+'"></label></div></div><div class="col-md-8"><input type="text" class="form-control" name="term_con_text['+sl+']" id="term_con_text"></div><div class="col-md-2"><button class="btn btn-danger removeRow" type="button" title="Add New"><i class="fa fa-trash"></i></button></div></div></div>' );
                i++;
                sl++;
            });

            $(document).on("click",".removeRow",function(){
                $(this).closest('.remove').remove();
                i = i-1;

            });
            
        });
    </script>

@stop


