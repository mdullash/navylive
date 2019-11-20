@extends('layouts.default')

<style type="text/css">

.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}

/*.insise_table {
    border: none !important;
}
.insise_table th, td{
    border: none !important;
}*/
.sRecurring .form-group {
    margin-bottom: 20px;
}
/*.sItemType .form-group {
    margin-bottom: 30px;
}*/
.bootstrap-select.btn-group, .bootstrap-select.btn-group[class*="span"]{
    margin-bottom: 0px !important;
}
</style>

@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    Edit Tender
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
                        Edit Tender
                    </div>
                    <div class="panel-body">


                        {{ Form::model($editId, array('url' => 'direct-item-dmnd-update/'.$editId->id, 'method' => 'PUT', 'files'=> true, 'class' => 'tender', 'id' => 'tender')) }}
                        <!-- Tender area ============================================
                        ============================================================= -->

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="requester">Tender Title/Name:<span class="text-danger">*</span></label>
                                {!!  Form::text('tender_title', old('tender_title'), array('id'=> 'tender_title', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="stall_id">Tender Number :<span class="text-danger">*</span></label>
                                {!!  Form::text('tender_number', old('tender_number'), array('id'=> 'tender_number', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        @if(!empty($demandInfo))
                            <div class="col-md-4 ">
                                <div class="form-group"><label class="control-label" for="stall_id">Demand Number :</label>
                                    {!!  Form::text('ref_tender_id', $demandInfo, array('id'=> 'ref_tender_id', 'class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                        @endif
                        
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

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label " for="valid_date_from">Tender Publishing Date :<span class="text-danger">*</span></label>
                                {!!  Form::text('valid_date_from', old('valid_date_from',date('Y-m-d',strtotime($editId->valid_date_from))), array('id'=> 'valid_date_from', 'class' => 'form-control datapicker2','readonly')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label " for="stall_id">Tender Opening Date :<span class="text-danger">*</span></label>
                                {!!  Form::text('tender_opening_date', old('tender_opening_date',date('Y-m-d',strtotime($editId->tender_opening_date))), array('id'=> 'tender_opening_date', 'class' => 'form-control datapicker2','readonly')) !!}
                            </div>
                        </div>

                        {{-- <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="purchase_type">Purchase Type :<span class="text-danger">*</span></label>
                                {{ Form::select('purchase_type', array('1' => trans('english.ACTIVE'), '2' => trans('english.INACTIVE')), old('purchase_type'), array('class' => 'form-control selectpicker', 'id' => 'purchase_type')) }}
                            </div>
                        </div> --}}

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="tender_type">Tender Type :<span class="text-danger">*</span></label>
                                {{ Form::select('tender_type', array('' => '- Select -', '1' => 'LTM- Limited Tender Method', '2' => 'OTM- Open Tender Method', '4' => 'Spot Tender', '5' => 'DPM- Direct Purchase Method', '6' => 'Short Tender'), old('tender_type'), array('class' => 'form-control selectpicker', 'id' => 'tender_type')) }}
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
                                        <option value="{!! $sc->id !!}" @if($editId->tender_cat_id == $sc->id) selected @endif>{!! $sc->name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="stall_id">Organization:<span class="text-danger">*</span></label>
                                <select class="form-control selectpicker" name="nsd_id" id="nsd_id"  data-live-search="true">
                                    
                                    @foreach($nsdNames as $nn)
                                        <option value="{!! $nn->id !!}" @if($editId->nsd_id == $nn->id) selected @endif>{!! $nn->name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="invitation_for">Invitation For:<span class="text-danger">*</span></label>
                                <select class="form-control selectpicker" name="invitation_for" id="invitation_for">
                                    <option value="">{!! 'Select' !!}</option>
                                    <option value="Purchase of Goods" @if($editId->invitation_for == "Purchase of Goods") selected @endif>{!! 'Purchase of Goods' !!}</option>
                                    <option value="Repair & Upgradation" @if($editId->invitation_for == "Repair & Upgradation") selected @endif>{!! 'Repair & Upgradation' !!}</option>
                                    <option value="Maintenance" @if($editId->invitation_for == "Maintenance") selected @endif>{!! 'Maintenance' !!}</option>
                                    <option value="Overhauling" @if($editId->invitation_for == "Overhauling") selected @endif>{!! 'Overhauling' !!}</option>
                                    <option value="Exchange" @if($editId->invitation_for == "Exchange") selected @endif>{!! 'Exchange' !!}</option>
                                    <option value="Repair" @if($editId->invitation_for == "Repair") selected @endif>{!! 'Repair' !!}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label " for="development_partners">Development Partners (If Applicable) :</label>
                                {!!  Form::text('development_partners', old('development_partners'), array('id'=> 'development_partners', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label " for="proj_prog_code">Project/Programme Code (If Applicable) :</label>
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

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label " for="pre_tender_meeting">Place/Date/Time of pre-tender meeting :</label>
                                {!!  Form::text('pre_tender_meeting', old('pre_tender_meeting'), array('id'=> 'pre_tender_meeting', 'class' => 'form-control pre_tender_meeting')) !!}
                            </div>
                        </div>

                        <div class="col-md-4 hidden">
                            <div class="form-group"><label class="control-label" for="eligibility_of_tender">Eligibility of Tender :</label>
                                <select class="form-control selectpicker" name="eligibility_of_tender" id="eligibility_of_tender">
                                    <option value="">Select</option>
                                    <option value="NSSD Enlisted Firm" @if($editId->eligibility_of_tender == "NSSD Enlisted Firm") selected @endif>{!! 'NSSD Enlisted Firm' !!}</option>
                                    <option value="NSSD Enlisted & Unlisted Firm" @if($editId->eligibility_of_tender == "NSSD Enlisted & Unlisted Firm") selected @endif>{!! 'NSSD Enlisted & Unlisted Firm' !!}</option>
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
                                {!!  Form::text('reference_date', old('reference_date',date('Y-m-d',strtotime($editId->reference_date))), array('id'=> 'reference_date', 'class' => 'form-control datapicker2', 'readonly')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="delivery_date">Delivery time after issue of w/order:<span class="text-danger">*</span></label>
                                {!!  Form::text('delivery_date', old('delivery_date'), array('id'=> 'delivery_date', 'class' => 'form-control', 'required')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="delivery_date">Location:<span class="text-danger">*</span></label>
                                {!!  Form::text('location', old('location'), array('id'=> 'location', 'class' => 'form-control', 'required')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="demending">Demanding:</label>
                                    {!!  Form::text('demending', old('demending'), array('id'=> 'demending', 'class' => 'form-control')) !!}
                                </div>
                            </div>
                        
                        <!-- Start new row =======================================
                        =========================================================== -->
                        <div class="row">
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
                                
                                <div class="col-md-4">
                                    <div class="form-group"><label class="control-label" for="additionl_info">Additional Info :</label>
                                        {!!  Form::textarea('additionl_info', old('additionl_info'), array('id'=> 'additionl_info', 'rows' => '3', 'class' => 'form-control')) !!}
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <!-- Start item area ======================== -->
                        @if(!$itemtodemand->isEmpty())
                                <br>
                                
                                <b>Items</b>
                                <table class="table table-bordered table-hover table-striped middle-align" id="
                                ">
                                    <thead>
                                        <th class="text-center" width="50%">Item Name</th>
                                        <th class="text-center">Group</th>
                                        <th class="text-center" width="20%">Quantity</th>
                                    </thead>
                                    <tbody>

                                        @foreach($itemtodemand as $itdmap)
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="machinery_and_manufacturer" name="machinery_and_manufacturer[]" value="{{ $itdmap->item_name}}" autocomplete="off" readonly="" required>
                                                        <input type="hidden" class="form-control item_id" id="" name="machinery_and_manufacturer_id[]" value="{!! $itdmap->id !!}" readonly="">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <label for="deno">Deno :<span class="text-danger">*</span></label>
                                                        <select class="form-control selectpicker item_deno_id" name="deno[]" id="deno"  data-live-search="true">
                                                            <option value="">{!! '- Select -' !!}</option>
                                                            @foreach($denos as $dn)
                                                                <option value="{!! $dn->id !!}" @if($dn->id==$itdmap->item_deno) selected @endif>{!! $dn->name !!}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" class="form-control publication_or_class" id="" name="publication_or_class[]" value="{!! $itdmap->item_cat_id !!}" readonly="">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                                        <input type="number" class="form-control unit" id="" name="unit[]" value="{!! $itdmap->unit !!}" placeholder="" step="any">
                                                    </div>
                                                </td>
                                                
                                            </tr>

                                        @endforeach

                                    </tbody>
                                </table><!---/table-responsive-->
                                <hr>
                            @endif

                            
                            <!-- End item area ===================================
                                ===================================================-->
                        
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
                                        {!!  Form::textarea('terms_conditions_field', $editId->tender_terms_conditions, array('id'=> 'terms_conditions_field', 'rows' => '3', 'class' => 'form-control')) !!}
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

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="status"></label>
                                <br />
                                <div class="checkbox checkbox-success" style="margin-top: 13px;">
                                    <input class="activity_1 activitycell placeorder2 lowtoheigh" type="checkbox" id="is_enclosure" name="is_enclosure" value="1">
                                    <label for="placeorder2" style="font-size: 14px;font-weight: 600;">Item List in Enclosure</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- <div class="form-group">
                            <div class="col-md-11 col-sm-offset-1">
                                <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                
                                <button type="submit" class="btn btn-primary pull-right">{!! 'Tender Publish' !!}</button>
                                
                            </div>
                        </div> -->

                        <!-- End Tender area ===========================================
                        ================================================================ -->

                        <div class="form-group">
                            <div class="col-md-7 col-sm-offset-5">
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
    

    <script type="text/javascript">
        $(document).ready(function(){
            

        });
    </script>

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

            $("#tender_nature").attr('disabled',true);
            $("form").submit(function(){
                $("#tender_nature").attr('disabled',false);
            });

            var i = 1;
            var sl = i+2;
            $(document).on('click','#addNewRow',function(){
                $( "body" ).find( ".firstRow" ).eq( i ).after( '<div class="col-md-12 remove firstRow"><span><b></b></span><br> <div class="col-md-1 hidden checkForHideShow" style="width: 2% !important; padding-left: 0px;"><div class="form-group " style=""><label class="control-label" for="status" style="display: none;"></label><div class="checkbox checkbox-success" style="margin-top: 30px;"><input class="activity_1 activitycell" type="checkbox" id="item_to_tender_assing1" name="item_to_tender_assing[]" value=""><label for="item_to_tender_assing1"></label></div></div></div> <div class="col-md-2"style="width: 22% !important;"><div class="form-group"><label for="machinery_and_manufacturer">Item Name: <span class="text-danger">*</span></label><input type="text" class="form-control search_item_name" id="machinery_and_manufacturer" name="machinery_and_manufacturer[]" placeholder="Search...." autocomplete="off"><input type="hidden" class="form-control item_id" id="" name="machinery_and_manufacturer_id[]" placeholder="" required></div><div class="form-group col-xs-12 col-sm-12 col-md-3 search_itmem_name_div" id="search_itmem_name_div" style="display: none; display: block; position: absolute; left: 0px; top: 55px;"></div></div><div class="col-md-2"><div class="form-group"><label for="model_type_mark">Model / Type / Mark</label><input type="text" class="form-control model_number" id="model_type_mark" name="model_type_mark[]" placeholder="Model / Type / Mark"></div></div><div class="col-md-2"><div class="form-group"><label for="serial_or_reg_number">Serial/Reg./IMC No:<span class="text-danger">*</span></label><input type="text" class="form-control imc_number" id="serial_or_reg_number" name="serial_or_reg_number[]" placeholder="Serial/Registration/IMC No" readonly=""></div></div><div class="col-md-2" style="display:none;"><div class="form-group"><label for="item_type">Item Type :</label><input type="text" class="form-control item_type" id="item_type" name="item_type[]" placeholder="Item Type" readonly=""></div></div> <div class="col-md-3" style="display:none;"><div class="form-group"><label for="publication_or_class">Group :<span class="text-danger">*</span></label><select class="form-control selectpicker item_cat_id" name="publication_or_class[]" id="publication_or_class'+i+'" data-live-search="true"><option value="">- Select - </option></select></div></div> <div class="col-md-1" style="width: 2% !important; padding-left: 0px;"></div> <div class="col-md-1" style="width: 15% !important;"><div class="form-group"><label for="deno">Deno :<span class="text-danger">*</span></label><select class="form-control selectpicker item_deno_id" name="deno[]" id="deno'+i+'" data-live-search="true" disabled><option value="">- Select - </option></select></div></div><div class="col-md-2"><div class="form-group"><label for="unit">Quantity:<span class="text-danger">*</span></label><input type="number" class="form-control unit" id="unit" name="unit[]" placeholder="Quantity" min="0" required></div></div><div class="col-md-2 hidden"><div class="form-group"><label for="currency_rates">Currency Rate</label><input type="number" class="form-control conversion" id="currency_rates" name="currency_rates[]" placeholder="Currency Rate" min="1" readonly=""></div></div><div class="col-md-2" style="display:none;"><div class="form-group"><label for="price">Estimated Price:<span class="text-danger">*</span></label><input type="number" class="form-control unit_price" id="price" name="price[]" placeholder="Estimated Price" min="0" required></div></div><div class="col-md-2" style="display:none;"><div class="form-group"><label for="sutotal_price">Estimated Subtotal</label><input type="number" class="form-control sutotal_price" id="sutotal_price" name="sutotal_price[]" placeholder="Estimated Subtotal" min="0" readonly></div></div><div class="col-md-2 hidden"><div class="form-group"><label for="sutotal_price">Lot Name</label><input type="text" name="lot_name[]" class="form-control lot_name_class" value=""></div></div> <div class="col-md-1" style="padding-top: 25px;"><button class="btn btn-danger removeRow" id="" type="button" data-placement="top" data-rel="tooltip" data-original-title="Remove"><i class="fa fa-trash"></i></button></div><div style="clear: both !important;"><hr style="border-top: 1px solid #999999;"></div></div>' );

                var supplyCategories = <?php echo json_encode($supplyCategories); ?>;
                $.each(supplyCategories, function (key, value) {
                    $('#publication_or_class'+i).append("<option value='"+ value.id+"' >"+value.name+"</option>");
                });
                $('#publication_or_class'+i).selectpicker('refresh');

                // for denos
                var denos = <?php echo json_encode($denos); ?>;
                $.each(denos, function (key, value) {
                    $('#deno'+i).append("<option value='"+ value.id+"' >"+value.name+"</option>");
                });
                $('#deno'+i).selectpicker('refresh');

                i++;
                sl++;

            });

            // Item Search======================================================
            $(document).on('keyup','.search_item_name',function(){
                //$('.search_item_name').keyup(function() {
                var query = $(this).val();

                if(query == ''){
                    $(this).closest("div.remove").find('.search_item_name').val('');
                    $(this).closest("div.remove").find('.item_id').val('');
                    $(this).closest("div.remove").find('.model_number').val('');
                    $(this).closest("div.remove").find('.imc_number').val('');
                    $(this).closest("div.remove").find('.item_cat_id').val('').selectpicker('refresh');
                    $(this).closest("div.remove").find('.item_deno_id').val('').selectpicker('refresh');
                    $(this).closest("div.remove").find('.unit').val('');
                    $(this).closest("div.remove").find('.conversion').val('');
                    $(this).closest("div.remove").find('.unit_price').val('');
                    $(this).closest("div.remove").find('.sutotal_price').val('');
                }

                if (query != '') {
                    var _token     = "<?php echo csrf_token(); ?>";
                    var closestDivClass = $(this).closest("div.remove").find('.search_itmem_name_div');
                    $.ajax({
                        url: "{{ url("demand-item-name-live-search") }}",
                        method: "POST",
                        data: {query: query, _token: _token},
                        success: function (data) {
                            closestDivClass.fadeIn();
                            closestDivClass.html(data);
                            // $('.search_itmem_name_div').fadeIn();
                            // $('.search_itmem_name_div').html(data);
                        }
                    });
                }
            });

            $(document).on('click', '.searchItemName', function () {
                $(this).closest("div.remove").find('.search_itmem_name_div').fadeOut();
                $(this).closest("div.remove").find('.search_item_name').val('');
                $(this).closest("div.remove").find('.item_id').val('');
                $(this).closest("div.remove").find('.model_number').val('');
                $(this).closest("div.remove").find('.imc_number').val('');
                $(this).closest("div.remove").find('.item_cat_id').val('').selectpicker('refresh');
                $(this).closest("div.remove").find('.item_deno_id').val('').selectpicker('refresh');
                $(this).closest("div.remove").find('.unit').val('');
                $(this).closest("div.remove").find('.conversion').val('');
                $(this).closest("div.remove").find('.unit_price').val('');
                $(this).closest("div.remove").find('.sutotal_price').val('');
                $(this).closest("div.remove").find('.item_type').val('');

                $(this).closest("div.remove").find('.search_item_name').val($(this).text());
                $(this).closest("div.remove").find('.item_id').val($(this).attr("value"));
                $(this).closest("div.remove").find('.unit').val(1);


                var model_number    = $(this).attr('att-model-number');
                var imc_number      = $(this).attr('att-imc-number');
                var itm_cat_id      = $(this).attr('att-item-cat-id');
                var item_deno_id    = $(this).attr('att-item-deno-id'); 
                var unitPrice       = $(this).attr('att-unit-price');
                //var curCurrency     = $(this).attr('att-currency-id');
                var curConversion   = $(this).attr('att-conversion');

                if(unitPrice=='' || unitPrice<0){
                    unitPrice = 0;
                }

                $(this).closest("div.remove").find(".model_number").val(model_number);
                $(this).closest("div.remove").find(".imc_number").val(imc_number);
                $(this).closest("div.remove").find('.item_cat_id').val(itm_cat_id).selectpicker('refresh');
                $(this).closest("div.remove").find('.item_deno_id').val(item_deno_id).selectpicker('refresh');
                $(this).closest("div.remove").find(".unit_price").val(unitPrice);
                $(this).closest("div.remove").find(".conversion").val(curConversion);
                $(this).closest("div.remove").find(".sutotal_price").val(unitPrice);
                $(this).closest("div.remove").find(".item_type").val(itemType);
                //$(this).closest("div.remove").find(".unit_price").trigger('input');

                $("#total_value").val(sumOfTotalPrice());
                $("#total_unit").val(sumOfTotalUnit());

            });

            $(document).on("click",".removeRow",function(){
                $(this).closest('.remove').remove();
                i = i-1;

                $("#total_value").val(sumOfTotalPrice());
                $("#total_unit").val(sumOfTotalUnit());

            });

            $(document).on("input",".unit_price,.unit,.conversion",function(){
                var thisitemunit        = $(this).closest("div.remove").find(".unit").val();
                var thisitemunitPrice   = $(this).closest("div.remove").find(".unit_price").val();
                var curencyrate         = $(this).closest("div.remove").find(".conversion").val();
                if(curencyrate=='' || curencyrate==0){
                    curencyrate=1;
                }

                $(this).closest("div.remove").find(".sutotal_price").val(thisitemunit*thisitemunitPrice*curencyrate);

                $("#total_value").val(sumOfTotalPrice());
                $("#total_unit").val(sumOfTotalUnit());

            });

            // sum totalPrice calculation =======================================================================
            var totalPricesum = 0;
            function sumOfTotalPrice(){
                totalPricesum = 0;
                $("input[class *= 'sutotal_price']").each(function(){
                    totalPricesum += +$(this).val();
                });
                return totalPricesum
            }

            // sum totalUnit calculation =======================================================================
            var totalUnitSum = 0;
            function sumOfTotalUnit(){
                totalUnitSum = 0;
                $(".unit").each(function(){
                    totalUnitSum += +$(this).val();
                });
                return totalUnitSum;
            }

            $(document).on("change","#requester",function(){
                var demandeNo = $(this).val();
                if(demandeNo != ''){
                    var _token     = "<?php echo csrf_token(); ?>";
                    $.ajax({
                        url: "../demand-get-demand-no",
                        method: "POST",
                        data: {demandeNo: demandeNo, _token: _token},
                        success: function (data) {
                            $("#demand_no").val(data);
                        }
                    });
                }else{
                    $("#demand_no").val('');
                }
            });

            

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

            // Lota item  ==================================
            // =============================================
            
            $(document).on('change','#tender_nature',function(){
                var nat_type = $(this).val();
                $("#number_of_lot_item").val('');
                if(nat_type==2){
                    $("#number_of_lot_itemDiv").removeClass('hidden');
                    $(".checkForHideShow").removeClass('hidden');
                }else{
                    $("#number_of_lot_itemDiv").addClass('hidden');
                    $(".checkForHideShow").addClass('hidden');

                    $('input:checkbox.activitycell').each(function () {
                        this.checked = false;
                    });
                    $(".lot_name_class").val('');
                }
            });

            var natr_type = $("#tender_nature").val();
            $(document).on('mouseover','.firstRow',function(){
                natr_type = $("#tender_nature").val();
                if(natr_type==2){
                    $("#createLotButton").removeClass('hidden');
                    $(".checkForHideShow").removeClass('hidden');
                }else{
                    $("#createLotButton").addClass('hidden');
                    $(".checkForHideShow").addClass('hidden');
                }
                
            });

            var numberToWord = 1;
            var newlyCheckedOrNot = 0;
            $(document).on('click','#createLotButton',function(){
                $('input:checkbox.activitycell').each(function () {
                    if(this.checked){
                        var sThisVal = (this.checked ? $(this).attr("class") : "");
                        var preLotNameVal = $(this).closest("div.remove").find('.lot_name_class').val();
                        if(preLotNameVal == ''){
                            //alert($('label[for='+sThisVal+']').text());
                            var lotNameInWord = 'Lot '+inWords(numberToWord);
                            $(this).closest("div.remove").find('.lot_name_class').val(lotNameInWord);
                            newlyCheckedOrNot = 1;
                        }
                        
                        $(this).closest("div.remove").addClass('hidden');

                    }
                });

                if(newlyCheckedOrNot==1){
                    bootbox.alert("Lot Name: Lot "+inWords(numberToWord), function (result) {});
                    numberToWord++;
                    newlyCheckedOrNot = 0;
                }
                
            });

            // $("input:checkbox.activitycell").change(function() {
            //     var ischecked= $(this).is(':checked');
            //     if(!ischecked)
            //         var sThisValId = (this.checked ? "" : $(this).attr("id"));
            //         if(sThisValId!=''){
            //             $("."+sThisValId).closest("div.firstRow").find('.lot_name_class').val('');
            //         }
            //         //alert('uncheckd ' + $(this).val());
            // }); 


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

            $('.item_deno_id').prop('disabled', true);

            $('#tender').on('submit', function() {
                $('.item_deno_id').prop('disabled', false);
            });
        });

    </script>
@stop


