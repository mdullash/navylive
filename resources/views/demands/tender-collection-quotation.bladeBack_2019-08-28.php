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

.forNumberIconPadding{
    padding: 6px 4px !important;
}

</style>

@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    Create Collection Quotation
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
                        Create Collection Quotation
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'post-collection-quotation-info', 'files'=> true, 'class' => '', 'id'=>'item-to-tender')) }}
                            
                            <input type="hidden" name="demand_to_lpr_id" value="{!! $dem_to_lpr_id !!}">
                            <input type="hidden" name="tender_id" value="{!! $tender_id !!}">

                            <?php $sl = 0; ?>

                        
                                <div class="col-md-12 remove appendRewRow firstRow">
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date">Date:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control datapicker2" id="propose_date" name="propose_date[0][]" value="{!! date('Y-m-d') !!}" readonly="" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="deno">Supplier Name :<span class="text-danger">*</span></label>
                                            <select class="form-control selectpicker supplier_id" name="supplier_name[0][]" id="supplier_id" data-live-search="true" required>
                                                <option value="">{!! '- Select -' !!}</option>
                                                    @foreach($suppliers as $sup)
                                                        <option value="{!! $sup->id !!}" @if($sup->supplier_name==$sup->id) selected @endif>{!! $sup->company_name !!}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" class="form-control search_supplier_id" id="search_supplier_id" name="suppliernametext[{!! $sl !!}][]" value="" placeholder="Search supplier..." autocomplete="off" required>
                                    </div>
                                    
                                    <?php $totalQty = 0; ?>
                                    @if(!empty($items))
                                    
                                    <!-- for line items======================
                                    ======================================== -->
                                    @if($tenderInfoForPdf->tender_nature != 2)
                                        @foreach($items as $itn)    
                                            <div class="col-md-12 supplierUnderRows">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="deno">Item Name :<span class="text-danger">*</span></label>
                                                        <input type="hidden" class="form-control item_id" id="item_id" name="item_id[0][]" value="{!! $itn->id.'&'.$itn->item_id !!}"  >
                                                        <input type="text" class="form-control " id="" name="" value="{!! $itn->item_name !!}"  readonly="">
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label for="quantity">Qty:<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control quantity forNumberIconPadding" id="quantity" name="quantity[0][]" min="0" value="{!! $itn->unit !!}" readonly="" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label for="quoted_quantity">Q. Qty:<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control quoted_quantity forNumberIconPadding" id="quoted_quantity" name="quoted_quantity[0][]" value="{!! $itn->unit !!}" min="0" max="{!! $itn->unit !!}">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="unit_price">Unit Price:<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control unit_price" id="unit_price" name="unit_price[0][]" value="" min="0" step="any" >
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="total_price">Total Price:<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control total_price" id="total_price" name="total_price[0][]" value="" min="0" step="any" readonly="" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="discount_amount">Discount Amount:</label>
                                                        <input type="number" class="form-control discount_amount" id="discount_amount" name="discount_amount[0][]" min="0" step="any">
                                                    </div>
                                                </div>

                                            </div>
                                            <?php $totalQty +=$itn->unit ;?>
                                        @endforeach
                                    @endif <!-- line item if end -->

                                    <!-- for Lot items=======================
                                    ========================================= -->
                                    @if($tenderInfoForPdf->tender_nature == 2)
                                        @foreach($items as $key => $itns)
                                            <div class="col-md-12"><b>{!! $key !!}</b></div>

                                            @foreach ($itns as $itn)    
                                            <div class="col-md-12 supplierUnderRows">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="deno">Item Name :<span class="text-danger">*</span></label>
                                                        <input type="hidden" class="form-control item_id" id="item_id" name="item_id[0][]" value="{!! $itn->id.'&'.$itn->item_id !!}"  >
                                                        <input type="text" class="form-control " id="" name="" value="{!! $itn->item_name !!}"  readonly="">
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label for="quantity">Qty:<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control quantity forNumberIconPadding" id="quantity" name="quantity[0][]" min="0" value="{!! $itn->unit !!}" readonly="" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label for="quoted_quantity">Q. Qty:<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control quoted_quantity forNumberIconPadding" id="quoted_quantity" name="quoted_quantity[0][]" value="{!! $itn->unit !!}" min="0" max="{!! $itn->unit !!}">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="unit_price">Unit Price:<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control unit_price" id="unit_price" name="unit_price[0][]" value="" min="0" step="any" >
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="total_price">Total Price:<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control total_price" id="total_price" name="total_price[0][]" value="" min="0" step="any" readonly="" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="discount_amount">Discount Amount:</label>
                                                        <input type="number" class="form-control discount_amount" id="discount_amount" name="discount_amount[0][]" min="0" step="any">
                                                    </div>
                                                </div>

                                            </div>
                                            <?php $totalQty +=$itn->unit ;?>
                                            @endforeach
                                        @endforeach
                                    @endif <!-- lot items if end=============
                                    ========================================== -->

                                    @endif        
                                        
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="total_quantity">Total Quantity:<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control total_quantity" id="total_quantity" name="total_quantity[0][]" value="{!! $totalQty !!}" min="0" step="any" readonly="" required>
                                            </div>
                                        </div> 

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="total">Grand Total:<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control total" id="total" name="total[0][]" value="0" min="0" step="any" readonly="" required>
                                            </div>
                                        </div>

                                        <div class="col-md-2 hidden">
                                            <div class="form-group">
                                                <label for="total">Total Discount:<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control total_discount" id="total_discount" name="total_discount[0][]" value="0" min="0" step="any" readonly="" required>
                                            </div>
                                        </div>
                                    
                                    <!-- Alternative offer area==============
                                    ========================================= -->
                                    <div class="row">
                                        <div class="col-md-12 alternativeOfferDiv"></div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button class="btn btn-warning alterNativeOfferBtn" id="alterNativeOfferBtn" type="button" title="Add Alter Native Offer"><i class="icon-plus"></i></button>
                                        </div>
                                    </div>
                                    <!-- Alternative offer area end
                                    ========================================= -->
    
                                    
                                </div>


                            <div class="col-md-12">
                                <div class="col-md-3"></div><div class="col-md-3"></div><div class="col-md-3"></div>
                                <div class="col-md-3">
                                    <div class="form-group pull-right">
                                        <button class="btn btn-info" id="addNewRow" type="button" title="Add new supplier"><i class="icon-plus"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-7 col-sm-offset-5">
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                
                                <?php if(!empty(Session::get('acl')[34][18]) && !empty($demandToLpr->tender_floating) && empty($demandToLpr->cst_draft_status)){ ?>
                                    <button type="submit" class="btn btn-primary pull-right">{!!trans('english.SAVE')!!}</button>
                                <?php } ?> 

                                </div>
                            </div>
                            
                    <!-- <div class="hr-line-dashed"></div> -->
                        {!!   Form::close() !!}

                        <div class="col-md-1">
                                {{ Form::open(array('role' => 'form', 'url' => 'post-cst-retender-reject', 'files'=> true, 'class' => 'retenderForm', 'id'=>'')) }}

                                <input type="hidden" name="demandToLprId" value="{!! $dem_to_lpr_id !!}">
                                <input type="hidden" name="tenderId" value="{!! $tender_id !!}">
                                <input type="hidden" name="stateNo" value="1">
                                <input type="hidden" name="wheretoredirect" value="2">
                                <input type="hidden" name="tender_action" value="2">
                                    <div class="form-group">
                                    <?php if(!empty(Session::get('acl')[34][19]) ){ ?>
                                        <button type="submit" class="btn btn-primary" style="margin-right: 5px;">{!! 'Retender' !!}</button>
                                    <?php } ?>     
                                    </div>
                                    
                                {!!   Form::close() !!}
                            </div>

                            <div class="col-md-1">
                                {{ Form::open(array('role' => 'form', 'url' => 'post-cst-retender-reject', 'files'=> true, 'class' => 'retenderForm', 'id'=>'')) }}

                                <input type="hidden" name="demandToLprId" value="{!! $dem_to_lpr_id !!}">
                                <input type="hidden" name="tenderId" value="{!! $tender_id !!}">
                                <input type="hidden" name="stateNo" value="1">
                                <input type="hidden" name="wheretoredirect" value="2">
                                <input type="hidden" name="tender_action" value="3">
                                    <div class="form-group">
                                    <?php if(!empty(Session::get('acl')[34][19]) ){ ?>
                                        <button type="submit" class="btn btn-primary" style="margin-right: 5px;">{!! 'Reject' !!}</button>
                                    <?php } ?>     
                                    </div>
                                    
                                {!!   Form::close() !!}
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    

<script type="text/javascript">
    $(document).ready(function(){

        // ========================================================================================================
            // ========================================================================================================

            var d = new Date();
            var month = d.getMonth()+1;
            var day = d.getDate();
            var output = d.getFullYear() + '-' +
                ((''+month).length<2 ? '0' : '') + month + '-' +
                ((''+day).length<2 ? '0' : '') + day;

            //var sl = <?php //echo $sl; ?>;
            var sl = 0;
            
            var i = 0+sl;
            if(sl>0){
                i = i-1;
            }
            var oli = 1+sl;
            var supIdsl = 1;
            $(document).on('click','#addNewRow',function(){
                $( "body" ).find( ".firstRow" ).eq( i ).after( '<div class="col-md-12 remove appendRewRow firstRow"><hr style="border-top: 1px solid #999999;"> <div class="col-md-3"><div class="form-group"><label for="date">Date:<span class="text-danger">*</span></label><input type="text" class="form-control datapicker2" id="propose_date" name="propose_date['+oli+'][]" value="'+output+'" readonly="" required></div></div>      <div class="col-md-3"><div class="form-group"><label for="deno">Supplier Name :<span class="text-danger">*</span></label><select class="form-control selectpicker supplier_id append_supplier_id'+supIdsl+'" name="supplier_name['+oli+'][]" id="supplier_id'+supIdsl+'" data-live-search="true" required><option value="">{!! '- Select -' !!}</option></select></div><input type="hidden" class="form-control search_supplier_id append_supplier_test'+supIdsl+'" id="search_supplier_id'+supIdsl+'" name="suppliernametext['+oli+'][]" value="" placeholder="Search supplier..." autocomplete="off" required></div>    <div class="col-md-12 supplierUnderRows" id="supplierUnderRows'+oli+'"></div>           <div class="col-md-2"><div class="form-group"><label for="total_quantity">Total Quantity:<span class="text-danger">*</span></label><input type="number" class="form-control total_quantity totaQty'+oli+'" id="total_quantity" name="total_quantity['+oli+'][]" value="" min="0" step="any" readonly="" required></div></div><div class="col-md-2"><div class="form-group"><label for="total">Grand Total:<span class="text-danger">*</span></label><input type="number" class="form-control total" id="total" name="total['+oli+'][]" min="0" step="any" readonly="" required></div></div> <div class="col-md-2 hidden"><div class="form-group"><label for="total_discount">Total Discount:<span class="text-danger">*</span></label><input type="number" class="form-control total_discount" id="total_discount" name="total_discount['+oli+'][]" value="0" min="0" step="any" readonly=""></div></div> <div class="row"><div class="col-md-12 alternativeOfferDiv"></div></div><div class="col-md-12"><div class="form-group"><button class="btn btn-warning alterNativeOfferBtn" id="alterNativeOfferBtn" type="button" title="Add Alter Native Offer"><i class="icon-plus"></i></button></div></div> <div class="col-md-1" style=""><button class="btn btn-danger removeRow" id="" type="button" data-placement="top" data-rel="tooltip" data-original-title="Remove"><i class="fa fa-trash"></i></button></div> </div>' );

                var items = <?php echo json_encode($items); ?>;
                var tender_nature = <?php echo $tenderInfoForPdf->tender_nature; ?>;

                var totalQty = 0;
                if(tender_nature != 2){
                    $.each(items, function (key, value) {
                    $('#supplierUnderRows'+oli).append('<div class="row"><div class="col-md-12 supplierUnderRows"><div class="col-md-3"><div class="form-group"><label for="deno">Item Name :<span class="text-danger">*</span></label><input type="hidden" class="form-control item_id append_item_id'+oli+'" id="item_id'+i+'" name="item_id['+oli+'][]" value="'+value.id+'&'+value.item_id+'" ><input type="text" class="form-control append_item_name'+oli+'" id="" name="" value="'+value.item_name+'" readonly></div></div><div class="col-md-1"><div class="form-group"><label for="quantity">Qty:<span class="text-danger">*</span></label><input type="number" class="form-control quantity forNumberIconPadding" id="quantity" name="quantity['+oli+'][]" value="'+value.unit+'" min="0" readonly="" required></div></div> <div class="col-md-1"><div class="form-group"><label for="quoted_quantity">Q. Qty:<span class="text-danger">*</span></label><input type="number" class="form-control quoted_quantity forNumberIconPadding" id="quoted_quantity" name="quoted_quantity['+oli+'][]" min="0" value="'+value.unit+'" max="'+value.unit+'" ></div></div> <div class="col-md-2"><div class="form-group"><label for="unit_price">Unit Price:<span class="text-danger">*</span></label><input type="number" class="form-control unit_price" id="unit_price" name="unit_price['+oli+'][]" min="0" step="any" ></div></div><div class="col-md-2"><div class="form-group"><label for="total_price">Total Price:<span class="text-danger"></span></label><input type="number" class="form-control total_price" id="total_price" name="total_price['+oli+'][]" min="0" step="any" readonly="" required></div></div><div class="col-md-2"><div class="form-group"><label for="discount_amount">Discount Amount:</label><input type="number" class="form-control discount_amount" id="discount_amount" name="discount_amount['+oli+'][]" min="0" step="any"></div></div></div></div>');

                        totalQty += parseInt(value.unit);
                    });
                }

                if(tender_nature == 2){
                    $.each(items, function (keys, values) {
                        $('#supplierUnderRows'+oli).append('<div class="row"><b>'+keys+'</b></div>');
                        $.each(values, function (key, value) {
                            $('#supplierUnderRows'+oli).append('<div class="row"><div class="col-md-12 supplierUnderRows"><div class="col-md-3"><div class="form-group"><label for="deno">Item Name :<span class="text-danger">*</span></label><input type="hidden" class="form-control item_id append_item_id'+oli+'" id="item_id'+i+'" name="item_id['+oli+'][]" value="'+value.id+'&'+value.item_id+'" ><input type="text" class="form-control append_item_name'+oli+'" id="" name="" value="'+value.item_name+'" readonly></div></div><div class="col-md-1"><div class="form-group"><label for="quantity">Qty:<span class="text-danger">*</span></label><input type="number" class="form-control quantity forNumberIconPadding" id="quantity" name="quantity['+oli+'][]" value="'+value.unit+'" min="0" readonly="" required></div></div> <div class="col-md-1"><div class="form-group"><label for="quoted_quantity">Q. Qty:<span class="text-danger">*</span></label><input type="number" class="form-control quoted_quantity forNumberIconPadding" id="quoted_quantity" name="quoted_quantity['+oli+'][]" min="0" value="'+value.unit+'" max="'+value.unit+'" ></div></div> <div class="col-md-2"><div class="form-group"><label for="unit_price">Unit Price:<span class="text-danger">*</span></label><input type="number" class="form-control unit_price" id="unit_price" name="unit_price['+oli+'][]" min="0" step="any" ></div></div><div class="col-md-2"><div class="form-group"><label for="total_price">Total Price:<span class="text-danger"></span></label><input type="number" class="form-control total_price" id="total_price" name="total_price['+oli+'][]" min="0" step="any" readonly="" required></div></div><div class="col-md-2"><div class="form-group"><label for="discount_amount">Discount Amount:</label><input type="number" class="form-control discount_amount" id="discount_amount" name="discount_amount['+oli+'][]" min="0" step="any"></div></div></div></div>');
                            totalQty += parseInt(value.unit);
                        });
                    });
                }
                
                //var totalQty = 0;
                // $.each(items, function (key, value) {
                //     totalQty += parseInt(value.unit);
                // });

                 $(".totaQty"+oli).val(totalQty);

                 var aValue = new Array();
                 var filteredArr = new Array();

                $('select.supplier_id').each(function() {
                    aValue.push($(this).val());
                    filteredArr = aValue.filter(function (el) {
                                     return el != '';
                                });
                });

              

                var supplierss = <?php echo json_encode($suppliers); ?>;
                $.each(supplierss, function (key, value) {
                    
                    if($.inArray(value.id.toString(), filteredArr) == -1){

                          $('.append_supplier_id'+supIdsl).append("<option value='"+ value.id+"'>"+value.company_name+"</option>");
                    }
                    // $('.append_supplier_id'+supIdsl).append("<option value='"+ value.id+"'>"+value.company_name+"</option>");
                    //$('.append_item_id'+i).selectpicker('refresh');
                });

                $.each(supplierss, function (key, value) {
                    if($.inArray(value.id, filteredArr) < 0){
                          $('.append_supplier_id'+supIdsl).selectpicker('refresh');
                    }
                    // $('.append_supplier_id'+supIdsl).selectpicker('refresh');
                });     
                

                i++;
                sl++;
                oli++;
                supIdsl++;

            });

        // Alter native offer section start from here=======================
        // ================================================
        
        $(document).on('click','.alterNativeOfferBtn',function(){

                var name_attr_val = $(this).closest("div.firstRow").find('.item_id').attr("name");
                var nameSl = name_attr_val.substr(name_attr_val.indexOf("[") + 1,1);

                $(this).closest("div.firstRow").find('.alternativeOfferDiv').after( '<div class="col-md-12 removeAlt"> <div class="col-md-12"> <b style="border-bottom:1px black solid;">Alternative Offer:</b> </div> <div class="col-md-12 alterSupplierUnderRows" id="alterSupplierUnderRows'+nameSl+'"></div>   <div class="col-md-2"><div class="form-group"><label for="alt_total_quantity">Total Quantity:<span class="text-danger">*</span></label><input type="number" class="form-control alt_total_quantity alt_totaQty'+nameSl+'" id="alt_total_quantity" name="alt_total_quantity['+nameSl+'][]" value="" min="0" step="any" readonly="" required></div></div><div class="col-md-2"><div class="form-group"><label for="alt_total">Grand Total:<span class="text-danger">*</span></label><input type="number" class="form-control alt_total" id="alt_total" name="alt_total['+nameSl+'][]" min="0" step="any" readonly="" required></div></div>  <div class="col-md-2 hidden"><div class="form-group"><label for="alt_total_discount">Total Discount:<span class="text-danger">*</span></label><input type="number" class="form-control alt_total_discount" id="alt_total_discount" name="alt_total_discount['+nameSl+'][]" value="0" min="0" step="any" readonly=""></div></div> <div class="col-md-1" style="padding-top: 25px;"><button class="btn btn-danger altRemoveRow" id="" type="button" data-placement="top" data-rel="tooltip" data-original-title="Remove"><i class="fa fa-trash"></i></button></div></div>' );

                var items = <?php echo json_encode($items); ?>;
                var tender_nature = <?php echo $tenderInfoForPdf->tender_nature; ?>;

                if(tender_nature != 2){
                    var alt_totaQty = 0;
                    $.each(items, function (key, value) {
                    $('#alterSupplierUnderRows'+nameSl).append('<div class="row"><div class="col-md-12 alterSupplierUnderRows"><div class="col-md-3"><div class="form-group"><label for="alter_item_name">Item Name :<span class="text-danger">*</span></label><input type="hidden" class="form-control alt_item_id alt_append_item_id'+nameSl+'" id="alt_item_id'+i+'" name="alt_item_id['+nameSl+'][]" value="'+value.id+'&'+value.item_id+'" ><input type="text" class="form-control alt_append_item_name'+nameSl+'" id="" name="" value="'+value.item_name+'" readonly></div></div><div class="col-md-1"><div class="form-group"><label for="alt_quantity">Qty:<span class="text-danger">*</span></label><input type="number" class="form-control alt_quantity forNumberIconPadding" id="alt_quantity" name="alt_quantity['+nameSl+'][]" value="'+value.unit+'" min="0" readonly="" required></div></div> <div class="col-md-1"><div class="form-group"><label for="alt_quoted_quantity">Q. Qty:<span class="text-danger">*</span></label><input type="number" class="form-control alt_quoted_quantity forNumberIconPadding" id="alt_quoted_quantity" name="alt_quoted_quantity['+nameSl+'][]" min="0" value="'+value.unit+'" max="'+value.unit+'" ></div></div> <div class="col-md-2"><div class="form-group"><label for="alt_unit_price">Unit Price:<span class="text-danger">*</span></label><input type="number" class="form-control alt_unit_price" id="alt_unit_price" name="alt_unit_price['+nameSl+'][]" min="0" step="any" ></div></div><div class="col-md-2"><div class="form-group"><label for="alt_total_price">Total Price:<span class="text-danger"></span></label><input type="number" class="form-control alt_total_price" id="alt_total_price" name="alt_total_price['+nameSl+'][]" min="0" step="any" readonly="" required></div></div><div class="col-md-2"><div class="form-group"><label for="alt_discount_amount">Discount Amount:</label><input type="number" class="form-control alt_discount_amount" id="alt_discount_amount" name="alt_discount_amount['+nameSl+'][]" min="0" step="any"></div></div></div></div>');

                       alt_totaQty += parseInt(value.unit); 
                    });

                    // var alt_totaQty = 0;
                    // $.each(items, function (key, value) {
                    //     alt_totaQty += parseInt(value.unit);
                    // });
                     $(".alt_totaQty"+nameSl).val(alt_totaQty);
                }

                if(tender_nature == 2){
                    var alt_totaQty = 0;
                    $.each(items, function (keys, values) {
                        $('#alterSupplierUnderRows'+oli).append('<div class="row"><b>'+keys+'</b></div>');
                        $.each(values, function (key, value) {
                            $('#alterSupplierUnderRows'+nameSl).append('<div class="row"><div class="col-md-12 alterSupplierUnderRows"><div class="col-md-3"><div class="form-group"><label for="alter_item_name">Item Name :<span class="text-danger">*</span></label><input type="hidden" class="form-control alt_item_id alt_append_item_id'+nameSl+'" id="alt_item_id'+i+'" name="alt_item_id['+nameSl+'][]" value="'+value.id+'&'+value.item_id+'" ><input type="text" class="form-control alt_append_item_name'+nameSl+'" id="" name="" value="'+value.item_name+'" readonly></div></div><div class="col-md-1"><div class="form-group"><label for="alt_quantity">Qty:<span class="text-danger">*</span></label><input type="number" class="form-control alt_quantity forNumberIconPadding" id="alt_quantity" name="alt_quantity['+nameSl+'][]" value="'+value.unit+'" min="0" readonly="" required></div></div> <div class="col-md-1"><div class="form-group"><label for="alt_quoted_quantity">Q. Qty:<span class="text-danger">*</span></label><input type="number" class="form-control alt_quoted_quantity forNumberIconPadding" id="alt_quoted_quantity" name="alt_quoted_quantity['+nameSl+'][]" min="0" value="'+value.unit+'" max="'+value.unit+'" ></div></div> <div class="col-md-2"><div class="form-group"><label for="alt_unit_price">Unit Price:<span class="text-danger">*</span></label><input type="number" class="form-control alt_unit_price" id="alt_unit_price" name="alt_unit_price['+nameSl+'][]" min="0" step="any" ></div></div><div class="col-md-2"><div class="form-group"><label for="alt_total_price">Total Price:<span class="text-danger"></span></label><input type="number" class="form-control alt_total_price" id="alt_total_price" name="alt_total_price['+nameSl+'][]" min="0" step="any" readonly="" required></div></div><div class="col-md-2"><div class="form-group"><label for="alt_discount_amount">Discount Amount:</label><input type="number" class="form-control alt_discount_amount" id="alt_discount_amount" name="alt_discount_amount['+nameSl+'][]" min="0" step="any"></div></div></div></div>');
                            alt_totaQty += parseInt(value.unit); 
                        });
                    });
                    $(".alt_totaQty"+nameSl).val(alt_totaQty);
                }
                 
                

            });

        // End alter native offer section start from here=======================
        // ================================================        
        
         // calculation of total quantity ===========================
         $(document).on('input','.quoted_quantity',function(){

                var totalUnitsum = 0;
                totalUnitsum = 0;
                $(this).closest("div.remove").find(".quoted_quantity").each(function(){
                    totalUnitsum += +$(this).val();
                });
            $(this).closest("div.remove").find('.total_quantity').val(totalUnitsum);

         });

         // calculation total price
         $(document).on('input','.unit_price, .quoted_quantity, .discount_amount',function(){
            var unitPrice           = $(this).closest("div.supplierUnderRows").find('.unit_price').val();
            var discountAmountPrice = $(this).closest("div.supplierUnderRows").find('.discount_amount').val();
            var unit = $(this).closest("div.supplierUnderRows").find('.quoted_quantity').val();
            //var afterDiscountUnitPrice = unitPrice-discountAmountPrice;
            var price = ((unitPrice*unit)-discountAmountPrice);

            $(this).closest("div.supplierUnderRows").find('.total_price').val(price);

                var totalPricesum = 0;
                totalPricesum = 0;
                $(this).closest("div.remove").find(".total_price").each(function(){
                    totalPricesum += +$(this).val();
                });
            
            $(this).closest("div.remove").find('.total').val(totalPricesum);

            // for total discount calculation====
            var totalDiscountsum = 0;
                totalDiscountsum = 0;
            $(this).closest("div.remove").find(".discount_amount").each(function(){
                totalDiscountsum += +$(this).val();
            });
            $(this).closest("div.remove").find('.total_discount').val(totalDiscountsum);
            // End of discount calculation =========
                
         });

         //date picker ====================================
         
         $(document).on('mouseover', '.datapicker2', function(){
             $('.datapicker2').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
            });

         });

         $(document).on('change','.supplier_id',function(){
            //var optionValue = $(this).text();
            //var optionText = $('.supplier_id option[value="'+optionValue+'"]').text();
            var optionText = $(this).find('option:selected').text();
            $(this).closest("div.remove").find('.search_supplier_id').val('');
            $(this).closest("div.remove").find('.search_supplier_id').val(optionText);
        });

         $(document).on("click",".removeRow",function(){
            $(this).closest('.remove').remove();
            i = i-1;
            sl = sl-1;

         });

         // Alter native offer =======================
         // ==========================================
         $(document).on("click",".altRemoveRow",function(){
            $(this).closest('.removeAlt').remove();
         });

         $(document).on('input','.alt_quoted_quantity',function(){
                var totalUnitsum = 0;
                totalUnitsum = 0;
                $(this).closest("div.removeAlt").find(".alt_quoted_quantity").each(function(){
                    totalUnitsum += +$(this).val();
                });
            $(this).closest("div.removeAlt").find('.alt_total_quantity').val(totalUnitsum);

         });

         // calculation total price
         $(document).on('input','.alt_unit_price, .alt_quoted_quantity, .alt_discount_amount',function(){
            var unitPrice           = $(this).closest("div.alterSupplierUnderRows").find('.alt_unit_price').val();
            var discountAmountPrice = $(this).closest("div.alterSupplierUnderRows").find('.alt_discount_amount').val();
            var unit = $(this).closest("div.alterSupplierUnderRows").find('.alt_quoted_quantity').val();
            //var afterDiscountUnitPrice = unitPrice-discountAmountPrice;
            var price = ((unitPrice*unit)-discountAmountPrice);

            $(this).closest("div.alterSupplierUnderRows").find('.alt_total_price').val(price);
                var totalPricesum = 0;
                totalPricesum = 0;
                $(this).closest("div.removeAlt").find(".alt_total_price").each(function(){
                    totalPricesum += +$(this).val();
                });
            $(this).closest("div.removeAlt").find('.alt_total').val(totalPricesum);
                
            // for total discount calculation====
            var alttotalDiscountsum = 0;
                alttotalDiscountsum = 0;
            $(this).closest("div.remove").find(".alt_discount_amount").each(function(){
                alttotalDiscountsum += +$(this).val();
            });
            $(this).closest("div.remove").find('.alt_total_discount').val(alttotalDiscountsum);
            // End of discount calculation =========

         });

         $('.retenderForm').on('submit', function() {
            if(confirm('Do you really want to submit?')) {
                return true;
            }
            return false;
        });
         

    });
</script>

@stop


