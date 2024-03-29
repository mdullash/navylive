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

                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="requester" class="col-md-3">Top Date:<span class="text-danger">*</span></label>
                                        <div class="col-md-6">
                                            {!!  Form::text('top_date', date('Y-m-d',strtotime($podataInfo->top_date)), array('id'=> 'top_date', 'class' => 'form-control datapicker2', 'required', 'readonly')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="extends_tender_number" class="col-md-3">{!! $orgInfo->name !!} tender no. {!! $orgInfo->name !!}</label>
                                        <div class="col-md-6">
                                            <input type="extends_tender_number" class="form-control col-md-4" name="extends_tender_number" id="extends_tender_number" value="{!! $podataInfo->extends_tender_number !!}" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(!empty($demandToLprInfo->head_ofc_apvl_status))
                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label for="email">{!! 'NAVAL Headquarters letter no. ' !!}</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="headquarters_letter_no" class="form-control" id="headquarters_letter_no" value="{!! $podataInfo->headquarters_letter_no !!}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label for="email">{!! 'Import Duties' !!}</label>
                                        </div>
                                        <div class="col-md-6">
                                            {{ Form::select('import_duties', array('with' => 'with', 'without' => 'without'), $podataInfo->import_duties, array('class' => 'form-control selectpicker', 'id' => 'import_duties', 'required')) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label for="email">{!! 'Supply to' !!}</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="supply_to" class="form-control" id="supply_to" value="{!! $podataInfo->supply_to !!}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label for="email">{!! 'Suppliers' !!}</label>
                                        </div>
                                        <?php $selSup = explode('.', $podataInfo->selected_supplier); ?>
                                        <div class="col-md-6">
                                            <select class="form-control selectpicker" name="selected_supplier[]" id="selected_supplier" multiple required>
                                                <option value="">{!! '- Select -' !!}</option>
                                                @foreach($winnerSuppliers as $ws)
                                                    <option value="{!! $ws->id !!}" @foreach($selSup as $sls) @if($sls==$ws->id) selected @endif @endforeach>{!! $ws->suppliernametext !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive" id="showSupplierItems">
                                @foreach($colIds as $qrr)
                                <?php $supplierInfo = \App\DemandToCollectionQuotation::find($qrr); ?>
                                <div><b>{!! $supplierInfo->suppliernametext !!}<b></div>
                                <table>
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
                                
            
                            <div class="form-group">
                                <div class="col-md-12 ">
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                    
                                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">{!!trans('english.SAVE')!!}</button>
                                    
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


