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
                    Create Tender
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
                        Create Tender
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'post-po-generate', 'files'=> true, 'class' => '', 'id'=>'')) }}
                            
                            <input type="hidden" name="demandToLprId" value="{!! $demandToLprId !!}">
                            <input type="hidden" name="tenderId" value="{!! $tenderId !!}">

                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="requester" class="col-md-2">Top Date:<span class="text-danger">*</span></label>
                                        <div class="col-md-6">
                                            {!!  Form::text('top_date', date('Y-m-d'), array('id'=> 'top_date', 'class' => 'form-control datapicker2', 'required', 'readonly')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="extends_tender_number" class="col-md-2">{!! $orgInfo->name !!} tender no. {!! $orgInfo->name !!}</label>
                                        <div class="col-md-6">
                                            <input type="extends_tender_number" class="form-control col-md-4" name="extends_tender_number" id="extends_tender_number" value="{!! $tenderInfo->tender_number !!}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <label for="email">{!! 'NAVAL Headquarters letter no. ' !!}</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="headquarters_letter_no" class="form-control" id="headquarters_letter_no" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <label for="email">{!! 'Import Duties' !!}</label>
                                        </div>
                                        <div class="col-md-6">
                                            {{ Form::select('import_duties', array('with' => 'with', 'without' => 'without'), old('import_duties'), array('class' => 'form-control selectpicker', 'id' => 'import_duties')) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <label for="email">{!! 'Supply to' !!}</label>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control selectpicker" name="supply_to" id="supply_to" required>
                                                <option value="">{!! '- Select -' !!}</option>
                                                @foreach($allOrganiZation as $zn)
                                                    <option value="{!! $zn->id !!}">{!! $zn->name !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <label for="email">{!! 'Suppliers' !!}</label>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control selectpicker" name="selected_supplier" id="selected_supplier" multiple required>
                                                <option value="">{!! '- Select -' !!}</option>
                                                @foreach($winnerSuppliers as $ws)
                                                    <option value="{!! $ws->id !!}">{!! $ws->suppliernametext !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive" id="showSupplierItems">
                            
                            </div>
                            
                            <?php $lastCount = 1; ?>
                            @if(!empty($temrsConditon))
                                @foreach($temrsConditon as $tc)
                                <div class="row paddingClass remove">
                                    <div class="col-md-12">
                                        <div class="col-md-2">
                                                <div class="checkbox checkbox-success" style="; text-align: right;">
                                                    <input class="activity_1 activitycell" type="checkbox" id="term_con{!! $lastCount !!}" name="term_con[]" value="{!! $lastCount !!}">
                                                    <label for="term_con{!! $lastCount !!}"></label>
                                                </div>
                                            
                                        </div>
                                        <div class="col-md-10">
                                            {!!  Form::text('term_con_text[]', old('term_con_text', $tc->descriptions), array('id'=> 'term_con_text', 'class' => 'form-control')) !!}
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
                                            <label for="term_con{!! $tc->id !!}"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        {!!  Form::text('term_con_text[]', old('term_con_text'), array('id'=> 'term_con_text', 'class' => 'form-control')) !!}
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-info" id="addNewRow" type="button" title="Add New"><i class="icon-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                                
            
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
            $(document).on('click','#addNewRow',function(){
                $( "body" ).find( ".remove" ).eq( i ).after( '<div class="row paddingClass remove"><div class="col-md-12"><div class="col-md-2"><div class="checkbox checkbox-success" style="; text-align: right;"><input class="activity_1 activitycell" type="checkbox" id="term_con'+sl+'" name="term_con[]" value="'+sl+'"><label for="term_con{!! $tc->id !!}"></label></div></div><div class="col-md-8"><input type="text" class="form-control" name="term_con_text[]" id="term_con_text"></div><div class="col-md-2"><button class="btn btn-danger removeRow" type="button" title="Add New"><i class="fa fa-trash"></i></button></div></div></div>' );
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

