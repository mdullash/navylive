@extends('layouts.default')

<style type="text/css">

.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}
.bootstrap-select.btn-group, .bootstrap-select.btn-group[class*="span"]{
    margin-bottom: 0px !important;
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
                        {{ Form::open(array('role' => 'form', 'url' => 'floating-tender/store', 'files'=> true, 'class' => 'tender', 'id'=>'tender')) }}

                        <input type="hidden" name="demandId" value="{!! $id !!}">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="requester">Tender Title/Name:<span class="text-danger">*</span></label>
                                    {!!  Form::text('tender_title', old('tender_title'), array('id'=> 'tender_title', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Tender Number :<span class="text-danger">*</span></label>
                                    {!!  Form::text('tender_number', old('tender_number',$tenderNoFor), array('id'=> 'tender_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>
                            
                            @if(!empty($demandInfo->demand_no))
                                <div class="col-md-4 ">
                                    <div class="form-group"><label class="control-label" for="stall_id">Demand Number :</label>
                                        {!!  Form::text('ref_tender_id', $demandInfo->demand_no, array('id'=> 'ref_tender_id', 'class' => 'form-control', 'readonly')) !!}
                                    </div>
                                </div>
                            @endif
                            
                            @if(empty($demandInfo->demand_no))
                                <div class="col-md-4 hidden">
                                    <div class="form-group"><label class="control-label" for="stall_id">Approval Letter Number :</label>
                                        {!!  Form::text('approval_letter_number', old('approval_letter_number'), array('id'=> 'approval_letter_number', 'class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4 hidden">
                                    <div class="form-group"><label class="control-label" for="stall_id">Approve Date :</label>
                                        {!!  Form::text('approval_letter_date', old('approval_letter_date'), array('id'=> 'approval_letter_date', 'class' => 'form-control datapicker2', 'readonly')) !!}
                                    </div>
                                </div>
                            @endif
                            

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label " for="valid_date_from">Tender Publishing Date :<span class="text-danger">*</span></label>
                                    {!!  Form::text('valid_date_from', old('valid_date_from',date('Y-m-d')), array('id'=> 'valid_date_from', 'class' => 'form-control datapicker2','readonly')) !!}
                                </div>
                            </div>

                            <!-- <div class="col-md-4">
                                <div class="form-group"><label class="control-label " for="valid_date_to">Publishing To :<span class="text-danger">*</span></label>
                                    {!!  Form::text('valid_date_to', old('valid_date_to'), array('id'=> 'valid_date_to', 'class' => 'form-control datapicker2','readonly')) !!}
                                </div>
                            </div> -->

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label " for="stall_id">Tender Opening Date :<span class="text-danger">*</span></label>
                                    {!!  Form::text('tender_opening_date', old('date_of_enrollment',date('Y-m-d')), array('id'=> 'tender_opening_date', 'class' => 'form-control datapicker2','readonly')) !!}
                                </div>
                            </div>

                            {{-- <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="purchase_type">Purchase Type :<span class="text-danger">*</span></label>
                                    {{ Form::select('purchase_type', array('1' => trans('english.ACTIVE'), '2' => trans('english.INACTIVE')), old('purchase_type'), array('class' => 'form-control selectpicker', 'id' => 'purchase_type')) }}
                                </div>
                            </div> --}}

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="tender_type">Tender Type :<span class="text-danger">*</span></label>
                                    {{ Form::select('tender_type', array('' => '- Select -', '1' => 'LTM- Limited Tender Method', '2' => 'OTM- Open Tender Method', '3' => 'RTM- Restricted Tender Method', '4' => 'Spot Tender', '5' => 'DPM- Direct Purchase Method', '6' => 'Short Tender'), old('tender_type'), array('class' => 'form-control selectpicker', 'id' => 'tender_type')) }}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="tender_priority">Tender Priority :</label>
                                    {{ Form::select('tender_priority', array('' => '- Select -', '1' => 'Normal', '2' => 'Immediate', '3' => 'OPS Immediate (Operational Immediate)'), old('tender_priority'), array('class' => 'form-control selectpicker', 'id' => 'tender_priority')) }}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="tender_nature">Tender Nature :<span class="text-danger">*</span></label>
                                    {{ Form::select('tender_nature', array('' => '- Select -', '1' => 'Line Item', '2' => 'Lot Item'), old('tender_nature'), array('class' => 'form-control selectpicker', 'id' => 'tender_nature')) }}
                                </div>
                            </div>

                            <div class="col-md-4 hidden" id="number_of_lot_itemDiv">
                                <div class="form-group"><label class="control-label" for="number_of_lot_item">Number of Lot Item :<span class="text-danger">*</span></label>
                                    {!!  Form::text('number_of_lot_item', old('number_of_lot_item'), array('id'=> 'number_of_lot_item', 'class' => 'form-control number_of_lot_item')) !!}
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="status">Tender Group :<span class="text-danger">*</span></label>
                                    <select class="form-control selectpicker" name="tender_cat_id" id="tender_cat_id"  data-live-search="true">
                                        <option value="">{!! '- Select -' !!}</option>
                                        @foreach($supplyCategories as $sc)
                                            <option value="{!! $sc->id !!}">{!! $sc->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Organization:<span class="text-danger">*</span></label>
                                    <select class="form-control selectpicker" name="nsd_id" id="nsd_id"  data-live-search="true">
                                        
                                        @foreach($nsdNames as $nn)
                                            <option value="{!! $nn->id !!}">{!! $nn->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="invitation_for">Invitation For:</label>
                                    <select class="form-control selectpicker" name="invitation_for" id="invitation_for">
                                        <option value="">{!! 'Select' !!}</option>
                                        <option value="Purchase Good">{!! 'Purchase Good' !!}</option>
                                        <option value="Repair & Upgradation">{!! 'Repair & Upgradation' !!}</option>
                                        <option value="Carry Goods">{!! 'Carry Goods' !!}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="date">Date :<span class="text-danger">*</span></label>
                                    {!!  Form::text('date', $demandInfo->when_needed, array('id'=> 'date', 'class' => 'form-control datapicker2','readonly')) !!}
                                </div>
                            </div> -->

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label " for="development_partners">Development Partners :</label>
                                    {!!  Form::text('development_partners', old('development_partners'), array('id'=> 'development_partners', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label " for="proj_prog_code">Project/Programme Code :</label>
                                    {!!  Form::text('proj_prog_code', old('proj_prog_code'), array('id'=> 'proj_prog_code', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label " for="tender_package_no">Tender Package No :</label>
                                    {!!  Form::text('tender_package_no', old('tender_package_no'), array('id'=> 'tender_package_no', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label " for="tender_package_name">Tender Package Name :</label>
                                    {!!  Form::text('tender_package_name', old('tender_package_name'), array('id'=> 'tender_package_name', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <!-- <div class="col-md-4">
                                <div class="form-group"><label class="control-label " for="valid_date_from">Tender Last Selling Date :</label>
                                    {!!  Form::text('date_line', date('Y-m-d H:i:s'), array('id'=> 'date_line', 'class' => 'form-control date_line')) !!}
                                </div>
                            </div> -->

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label " for="pre_tender_meeting">Place/Date/Time of pre-tender meeting :</label>
                                    {!!  Form::text('pre_tender_meeting', old('pre_tender_meeting'), array('id'=> 'pre_tender_meeting', 'class' => 'form-control pre_tender_meeting')) !!}
                                </div>
                            </div>

                            <div class="col-md-4 hidden">
                                <div class="form-group"><label class="control-label" for="eligibility_of_tender">Eligibility of Tender :</label>
                                    <select class="form-control selectpicker" name="eligibility_of_tender" id="eligibility_of_tender">
                                        <option value="">Select</option>
                                        <option value="NSSD Enlisted Firm">{!! 'NSSD Enlisted Firm' !!}</option>
                                        <option value="NSSD Enlisted & Unlisted Firm">{!! 'NSSD Enlisted & Unlisted Firm' !!}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label " for="name_of_offi_invit_ten">Name of Official Inviting Tender :</label>
                                    {!!  Form::text('name_of_offi_invit_ten', old('name_of_offi_invit_ten'), array('id'=> 'name_of_offi_invit_ten', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label " for="desg_of_offi_invit_ten">Designation of Official Inviting Tender :</label>
                                    {!!  Form::text('desg_of_offi_invit_ten', old('desg_of_offi_invit_ten'), array('id'=> 'desg_of_offi_invit_ten', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label " for="nhq_ltr_no">Reference Number :<span class="text-danger">*</span></label>
                                    {!!  Form::text('nhq_ltr_no', old('nhq_ltr_no'), array('id'=> 'nhq_ltr_no', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="reference_date">Reference Date:</label>
                                    {!!  Form::text('reference_date', old('reference_date',date('Y-m-d')), array('id'=> 'reference_date', 'class' => 'form-control datapicker2', 'readonly')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="delivery_date">Delivery time after issue of w/order:<span class="text-danger">*</span></label>
                                    {!!  Form::text('delivery_date', old('delivery_date'), array('id'=> 'delivery_date', 'class' => 'form-control datapicker2', 'readonly', 'required')) !!}
                                </div>
                            </div>
                            
                            <!-- Start new row =======================================
                            =========================================================== -->
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group"><label class="control-label" for="stall_id">Brief Description of Goods :</label>
                                        {!!  Form::textarea('tender_description', old('tender_description'), array('id'=> 'tender_description', 'rows' => '3', 'class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4 hidden">
                                    <div class="form-group"><label class="control-label" for="stall_id">Letter Body :</label>
                                        {!!  Form::textarea('letter_body', old('letter_body'), array('id'=> 'letter_body', 'rows' => '3', 'class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group"><label class="control-label" for="stall_id">Remarks :</label>
                                        {!!  Form::textarea('remarks', old('remarks'), array('id'=> 'remarks', 'rows' => '3', 'class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="col-md-4">
                                        <div class="form-group"><label class="control-label" for="terms_conditions">Select Terms & Conditions:</label>
                                            <select class="form-control selectpicker" name="terms_conditions" id="terms_conditions">
                                                <option value="">{!! 'Select' !!}</option>
                                                @foreach($tenderTearmsAndConditions as $ttac)
                                                    <option value="{!! $ttac->id !!}">{!! $ttac->title !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="form-group"><label class="control-label" for="terms_conditions_field">Terms & Conditions :</label>
                                            {!!  Form::textarea('terms_conditions_field', old('terms_conditions_field'), array('id'=> 'terms_conditions_field', 'rows' => '3', 'class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Specification :</label>
                                    {!!  Form::file('specification[]', array('id'=> 'specification', 'class' => 'form-control', 'multiple', 'accept' => '.pdf,.doc,.docx')) !!}
                                </div>
                            </div>
                            
                            <div class="col-md-4 hidden">
                                <div class="form-group"><label class="control-label" for="stall_id">Notice PDF :</label>
                                    {!!  Form::file('notice', array('id'=> 'notice', 'class' => 'form-control', 'accept' => '.pdf')) !!}
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="status">Status :<span class="text-danger">*</span></label>
                                    {{ Form::select('status', array('1' => 'Published', '2' => 'Unpublished'), old('status'), array('class' => 'form-control selectpicker', 'id' => 'status')) }}
                                </div>
                            </div>
                            
                            <!-- Start of group item stock and not in stock change  
                            =======================================================================
                            =======================================================================
                            -->  
                            
                            @if(!$itemtodemand->isEmpty())
                                <br>
                                <!-- <div class="row hidden" id="lotItemNameField">
                                    <div class="col-md-12">
                                        <div class="form-group col-md-4"><label class="control-label " for="parent_lot_name">Lot Item Name :</label>
                                            {!!  Form::text('parent_lot_name', old('parent_lot_name'), array('id'=> 'parent_lot_name', 'class' => 'form-control parent_lot_name')) !!}
                                            <p id="errorText"></p>
                                        </div>
                                    </div>
                                </div> -->
                                <b>Items</b>
                                <table class="table table-bordered table-hover table-striped middle-align" id="itemShowTable">
                                    <thead>
                                        <th>Assign to tender</th>
                                        <th>Item Name</th>
                                        <th>Model / Type / Mark</th>
                                        <th>Group</th>
                                        <th>Unit</th>
                                        <th>In Stock</th>
                                        <th>Not In Stock</th>
                                        <th class="hidden hideLotNameTh" id="">Lot Name</th>
                                    </thead>
                                    <tbody>

                                        @foreach($itemtodemand as $itdmap)
                                            <input type="hidden" name="item_to_demand[]" value="{!! $itdmap->id !!}">
                                            <tr>
                                                <td>
                                                    <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                        <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                            <input class="activity_1 activitycell" type="checkbox" id="item_to_tender_assing{!! $itdmap->id !!}" name="item_to_tender_assing[]" value="{!! $itdmap->id !!}">
                                                            <label for="item_to_tender_assing{!! $itdmap->id !!}">{!! $itdmap->item_name !!}</label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{!! $itdmap->item_name !!}</td>
                                                <td>{!! $itdmap->item_model !!}</td>
                                                <td>{!! $itdmap->categoryname !!}</td>
                                                <td>
                                                    {!! $itdmap->total_unit !!}
                                                    <input type="hidden" class="form-control unit" id="" name="unit[]" value="{!! $itdmap->total_unit !!}" placeholder="">
                                                </td>
                                                <td>
                                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                                        <input type="text" class="form-control in_stock" id="" name="in_stock[]" value="{!! $itdmap->in_stock !!}" placeholder="" required="" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                                        <input type="text" class="form-control not_in_stock" id="" name="not_in_stock[]" value="{!! $itdmap->not_in_stock !!}" placeholder="" readonly="" required="">
                                                    </div>
                                                </td>
                                                <td class="hidden hideLotNameTd" id="">
                                                    <input type="text" name="lot_name[]" class="form-control lot_name_class" value="">
                                                </td>
                                            </tr>

                                        @endforeach

                                    </tbody>
                                </table><!---/table-responsive-->
                            @endif
                            <div class="row hidden" id="createLotButton">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary">{!! 'Create Lot' !!}</button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- End of group item stock and not in stock change  
                                ==================================================================
                                ==================================================================
                            -->

            
                            <div class="form-group">
                                <div class="col-md-11 col-sm-offset-1">
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                    
                                    <button type="submit" class="btn btn-primary pull-right">{!! 'Tender Publish' !!}</button>
                                    
                                </div>
                            </div>
    
                    <!-- <div class="hr-line-dashed"></div> -->
                        {!!   Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
    
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
    <script type="text/javascript">

        CKEDITOR.replace( 'terms_conditions_field', {
                enterMode: CKEDITOR.ENTER_BR
            } );

        $(document).ready(function(){

            

            $(document).on('change','#tender_type',function(){
                var tender_type = $('#tender_type').val();
                if(tender_type !=''){
                    if(tender_type==2){
                        $("#eligibility_of_tender").val('NSSD Enlisted & Unlisted Firm').change();
                    }else{
                        $("#eligibility_of_tender").val('NSSD Enlisted Firm').change();
                    }
                }else{alert(2);
                    $("#eligibility_of_tender").val('').change();
                }
            });

            $('.pre_tender_meeting').datetimepicker({
                format: 'Y-MM-D HH:mm:ss',
            });

            $(document).on('change','#terms_conditions',function () {

                var id = $(this).val();
                if(id != ''){
                    var csrf = "<?php echo csrf_token(); ?>";
                    $.ajax({
                        type: 'post',
                        url: '{{ url('floating-tender-terms-con-val') }}',
                        data: { _token: csrf, id:id},
                        //dataType: 'json',
                        success: function( _response ){
                            CKEDITOR.instances.terms_conditions_field.setData(_response);
                            //alert(_response);
                        },
                        error: function(_response){
                            //$("#terms_conditions").val('');
                        }

                    });/*End Ajax*/

                }else{
                    CKEDITOR.instances.terms_conditions_field.setData('');
                }

                //alert($(this).val());
            });

            $(document).on('change','#tender_nature',function(){
                var nat_type = $(this).val();
                $("#number_of_lot_item").val('');
                if(nat_type==2){
                    $("#number_of_lot_itemDiv").removeClass('hidden');
                }else{
                    $("#number_of_lot_itemDiv").addClass('hidden');

                    $('input:checkbox.activitycell').each(function () {
                        this.checked = false;
                    });
                    $(".lot_name_class").val('');
                }
                
            });

            var natr_type = $("#tender_nature").val();
            $(document).on('mouseover','#itemShowTable',function(){
                natr_type = $("#tender_nature").val();
                if(natr_type==2){
                    $("#createLotButton").removeClass('hidden');
                    $(".hideLotNameTh").removeClass('hidden');
                    $(".hideLotNameTd").removeClass('hidden');
                }else{
                    $("#createLotButton").addClass('hidden');
                    $(".hideLotNameTh").addClass('hidden');
                    $(".hideLotNameTd").addClass('hidden');
                }
                
            });

            var numberToWord = 1;
            var newlyCheckedOrNot = 0;
            $(document).on('click','#createLotButton',function(){
                $('input:checkbox.activitycell').each(function () {
                    if(this.checked){
                        var sThisVal = (this.checked ? $(this).attr("id") : ""); 
                        var preLotNameVal = $("#"+sThisVal).closest("tr").find('.lot_name_class').val();
                        if(preLotNameVal == ''){
                            //alert($('label[for='+sThisVal+']').text());
                            var lotNameInWord = 'Lot '+inWords(numberToWord);
                            $("#"+sThisVal).closest("tr").find('.lot_name_class').val(lotNameInWord);
                            newlyCheckedOrNot = 1;
                        }
                        
                        $(this).closest("tr").addClass('hidden');

                    }
                });

                if(newlyCheckedOrNot==1){
                    bootbox.alert("Lot Name: Lot "+inWords(numberToWord), function (result) {});
                    numberToWord++;
                    newlyCheckedOrNot = 0;
                }
                
            });

            $("input:checkbox.activitycell").change(function() {
                var ischecked= $(this).is(':checked');
                if(!ischecked)
                    var sThisValId = (this.checked ? "" : $(this).attr("id"));
                    if(sThisValId!=''){
                        $("#"+sThisValId).closest("tr").find('.lot_name_class').val('');
                    }
                    //alert('uncheckd ' + $(this).val());
            }); 


            // Start number to word function =================================
            // =============================================================
            var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
            var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

            function inWords (num) {
                if ((num = num.toString()).length > 9) return 'overflow';
                n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
                if (!n) return; var str = '';
                str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
                str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
                str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
                str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
                str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) : '';
                return str;
            }

            // End number to word function =================================
            // =============================================================


        });

    </script>
@stop


