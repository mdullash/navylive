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
                            
                            <input type="hidden" name="demand_id" value="{!! $demand_id !!}">

                            <?php $sl = 0; ?>

                            @if(count($assignedDatas)>0)
                                @foreach($assignedDatas as $asgd)
                                    
                                    <div class="col-md-12 remove appendRewRow firstRow">
                                        <input type="hidden" name="old_data_update[]" value="{!! $asgd->id !!}">
                                        
                                        @if($sl != 0)
                                            <hr style="border-top: 1px solid #999999;">
                                        @endif
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="date">Date:<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control datapicker2" id="propose_date" name="propose_date[{!! $sl !!}][]" value="{!! date('Y-m-d',strtotime($asgd->propose_date)) !!}" readonly="" required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="date">Supplier Name:<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control search_supplier_id" id="search_supplier_id" name="suppliernametext[{!! $sl !!}][]" value="{!! $asgd->suppliernametext !!}" placeholder="Search supplier..." autocomplete="off" required>
                                                <input type="hidden" class="supplier_id" id="supplier_id" name="supplier_name[{!! $sl !!}][]" value="{!! $asgd->supplier_name !!}" required>
                                                <div class="form-group col-xs-12 col-sm-12 col-md-6 search_supplier_id_div" id="search_supplier_id_div" style="display: none; display: block; position: absolute; left: 0;"></div>
                                            </div>
                                        </div>

                                        @if(!empty($asgd->itemsSelected))
                                        @foreach($asgd->itemsSelected as $selitm)    
                                            <div class="col-md-12 supplierUnderRows">
<?php //dd($asgd->itemsSelected); exit; ?>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="deno">Item Name :<span class="text-danger">*</span></label>
                                                <select class="form-control selectpicker item_id" name="item_id[{!! $sl !!}][]" id="item_id" data-live-search="true" required>
                                                    <option value="">{!! '- Select -' !!}</option>
                                                        @foreach($items as $itn)
                                                            <option value="{!! $itn->id !!}" attrolditemid="{!! $itn->item_id !!}" @if($itn->id==$selitm->item_id) selected @endif>{!! $itn->item_name !!}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="unit_price">Unit Price:<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control unit_price" id="unit_price" name="unit_price[{!! $sl !!}][]" value="{!! $selitm->unit_price !!}" min="0" required>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="discount_amount">Discount Amount:<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control discount_amount" id="discount_amount" name="discount_amount[{!! $sl !!}][]" value="{!! $selitm->discount_amount !!}" min="0">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="quantity">Quantity:<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control quantity" id="quantity" name="quantity[{!! $sl !!}][]" value="{!! $selitm->quantity !!}" min="0" readonly="" required>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="total_price">Total Price:<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control total_price" id="total_price" name="total_price[{!! $sl !!}][]" value="{!! $selitm->total_price !!}" min="0" readonly="" required>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="last_unti_price">Last Purchase Price:<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control last_unti_price" id="last_unti_price" name="last_unti_price[{!! $sl !!}][]" value="{!! $selitm->last_unti_price !!}" min="0" readonly="" required>
                                            </div>
                                        </div>

                                        </div>
                                        @endforeach
                                    @endif
                                    
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="total_quantity">Total Quantity:<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control total_quantity" id="total_quantity" name="total_quantity[{!! $sl !!}][]" value="{!! $asgd->total_quantity !!}" min="0" readonly="" required>
                                        </div>
                                    </div> 

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="total">Total Price:<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control total" id="total" name="total[{!! $sl !!}][]" value="{!! $asgd->total !!}" min="0" readonly="" required>
                                        </div>
                                    </div>        

                                    	@if($sl != 0)
                                        <div class="col-md-1" style="padding-top: 25px;">
                                            <button class="btn btn-danger removeRow" id="" type="button" data-placement="top" data-rel="tooltip" data-original-title="Remove"><i class="fa fa-trash"></i></button>
                                        </div>
                                        @endif


                                    </div>
                                    <?php $sl++; ?>
                                @endforeach
                            @else

                                <div class="col-md-12 remove appendRewRow firstRow">
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date">Date:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control datapicker2" id="propose_date" name="propose_date[0][]" value="{!! date('Y-m-d') !!}" readonly="" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date">Supplier Name:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control search_supplier_id" id="search_supplier_id" name="suppliernametext[0][]" value="" placeholder="Search supplier..." required>
                                            <input type="hidden" class="supplier_id" id="supplier_id" name="supplier_name[0][]" required>
                                            <div class="form-group col-xs-12 col-sm-12 col-md-6 search_supplier_id_div" id="search_supplier_id_div" style="display: none; display: block; position: absolute; left: 0;"></div>
                                        </div>
                                    </div>
                                    
                                    @if(!empty($items))
                                        @foreach($items as $itn)    
                                            <div class="col-md-12 supplierUnderRows">
                                                
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="deno">Item Name :<span class="text-danger">*</span></label>
                                                        <select class="form-control selectpicker item_id" name="item_id[0][]" id="item_id" data-live-search="true" required>
                                                            <option value="">{!! '- Select -' !!}</option>
                                                                @foreach($items as $itn)
                                                                    <option value="{!! $itn->id !!}" attrolditemid="{!! $itn->item_id !!}" attritemqty="{!! $itn->unit !!}">{!! $itn->item_name !!}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="unit_price">Unit Price:<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control unit_price" id="unit_price" name="unit_price[0][]" min="0" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="discount_amount">Discount Amount:<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control discount_amount" id="discount_amount" name="discount_amount[0][]" min="0">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="quantity">Quantity:<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control quantity" id="quantity" name="quantity[0][]" min="0" readonly="" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="total_price">Price:<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control total_price" id="total_price" name="total_price[0][]" min="0" readonly="" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="last_unti_price">Last Purchase Price:<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control last_unti_price" id="last_unti_price" name="last_unti_price[0][]" min="0" readonly="" required>
                                                    </div>
                                                </div>


                                            </div>
                                        @endforeach
                                    @endif        
                                        
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="total_quantity">Total Quantity:<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control total_quantity" id="total_quantity" name="total_quantity[0][]" value="" min="0" readonly="" required>
                                            </div>
                                        </div> 

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="total">Total Price:<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control total" id="total" name="total[0][]" min="0" readonly="" required>
                                            </div>
                                        </div>

                                </div>

                            @endif    

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
                                
                                <?php if(!empty(Session::get('acl')[34][18]) && !empty($demand->tender_floating) && empty($demand->cst_draft_status)){ ?>
                                    <button type="submit" class="btn btn-primary pull-right">{!!trans('english.SAVE')!!}</button>
                                <?php } ?> 

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

        // ========================================================================================================
            // ========================================================================================================

            var d = new Date();
			var month = d.getMonth()+1;
			var day = d.getDate();
			var output = d.getFullYear() + '-' +
			    ((''+month).length<2 ? '0' : '') + month + '-' +
			    ((''+day).length<2 ? '0' : '') + day;

            //var sl = <?php //echo $sl; ?>;
            var sl = <?php echo count($assignedDatas); ?>;
            
            var i = 0+sl;
            if(sl>0){
                i = i-1;
            }
            var oli = 1+sl;
            $(document).on('click','#addNewRow',function(){
                $( "body" ).find( ".firstRow" ).eq( i ).after( '<div class="col-md-12 remove appendRewRow firstRow"><hr style="border-top: 1px solid #999999;"> <div class="col-md-3"><div class="form-group"><label for="date">Date:<span class="text-danger">*</span></label><input type="text" class="form-control datapicker2" id="propose_date" name="propose_date['+oli+'][]" value="'+output+'" readonly="" required></div></div><div class="col-md-3"><div class="form-group"><label for="date">Supplier Name:<span class="text-danger">*</span></label><input type="text" class="form-control search_supplier_id" id="search_supplier_id" name="suppliernametext['+oli+'][]" value="" placeholder="Search supplier..." required><input type="hidden" class="supplier_id" id="supplier_id" name="supplier_name['+oli+'][]" autocomplete="off" required><div class="form-group col-xs-12 col-sm-12 col-md-6 search_supplier_id_div" id="search_supplier_id_div" style="display: none; display: block; position: absolute; left: 0;"></div></div></div>        <div class="col-md-12 supplierUnderRows" id="supplierUnderRows'+oli+'"></div>           <div class="col-md-2"><div class="form-group"><label for="total_quantity">Total Quantity:<span class="text-danger">*</span></label><input type="number" class="form-control total_quantity" id="total_quantity" name="total_quantity['+oli+'][]" value="" min="0" readonly="" required></div></div><div class="col-md-2"><div class="form-group"><label for="total">Total Price:<span class="text-danger">*</span></label><input type="number" class="form-control total" id="total" name="total['+oli+'][]" min="0" readonly="" required></div></div><div class="col-md-1" style="padding-top: 25px;"><button class="btn btn-danger removeRow" id="" type="button" data-placement="top" data-rel="tooltip" data-original-title="Remove"><i class="fa fa-trash"></i></button></div></div>' );

                var items = <?php echo json_encode($items); ?>;
                $.each(items, function (key, value) {
                    $('#supplierUnderRows'+oli).append('<div class="col-md-12 supplierUnderRows"><div class="col-md-2"><div class="form-group"><label for="deno">Item Name :<span class="text-danger">*</span></label><select class="form-control selectpicker item_id append_item_id'+oli+'" name="item_id['+oli+'][]" id="item_id'+i+'" data-live-search="true" required><option value="">- Select -</option></select></div></div><div class="col-md-2"><div class="form-group"><label for="unit_price">Unit Price:<span class="text-danger">*</span></label><input type="number" class="form-control unit_price" id="unit_price" name="unit_price['+oli+'][]" min="0" required></div></div><div class="col-md-2"><div class="form-group"><label for="discount_amount">Discount Amount:<span class="text-danger">*</span></label><input type="number" class="form-control discount_amount" id="discount_amount" name="discount_amount['+oli+'][]" min="0"></div></div><div class="col-md-2"><div class="form-group"><label for="quantity">Quantity:<span class="text-danger">*</span></label><input type="number" class="form-control quantity" id="quantity" name="quantity['+oli+'][]" min="0" readonly="" required></div></div><div class="col-md-2"><div class="form-group"><label for="total_price">Price:<span class="text-danger"></span></label><input type="number" class="form-control total_price" id="total_price" name="total_price['+oli+'][]" min="0" readonly="" required></div></div><div class="col-md-2"><div class="form-group"><label for="last_unti_price">Last Purchase Price:<span class="text-danger">*</span></label><input type="number" class="form-control last_unti_price" id="last_unti_price" name="last_unti_price['+oli+'][]" min="0" readonly="" required></div></div></div>');

                });

                $.each(items, function (key, value) {
                    $('.append_item_id'+oli).append("<option value='"+ value.id+"' attrolditemid='"+ value.item_id+"' attritemqty='"+ value.unit+"'>"+value.item_name+"</option>");
                    //$('.append_item_id'+i).selectpicker('refresh');
                });
                
                $.each(items, function (key, value) {
                    $('.append_item_id'+oli).selectpicker('refresh');
                });     
                

                i++;
                sl++;
                oli++;

            });

        // Supplier name search ============================================
        // =================================================================
        
        // Supplier Search=============================
        //$('.search_supplier_id').keyup(function() { 
        $(document).on('keyup','.search_supplier_id',function() { 
            var query = $(this).val();
            // var demandId = "<?php echo $demand_id; ?>"; alert(demandId);

            if(query == ''){ 
                //$('#supplier_id').val(''); 
                //$('.search_supplier_id_div').fadeOut();
                $(this).closest("div.remove").find('.supplier_id').val('');
                $(this).closest("div.remove").find('.search_supplier_id_div').fadeOut();
            }

            if (query != '') {
                var _token     = "<?php echo csrf_token(); ?>";

                var closestDivClass = $(this).closest("div.remove").find('.search_supplier_id_div');

                $.ajax({
                    url: "../../awarded-rep-supplier-name-live-search",
                    // url: "../../supplier-name-live-search-by-schedule",
                    method: "POST",
                    data: {query: query, _token: _token},
                    // data: {query: query, demandId:demandId, _token: _token},
                    success: function (data) {
                        //$('.search_supplier_id_div').fadeIn();
                        //$('.search_supplier_id_div').html(data);
                        closestDivClass.fadeIn();
                        closestDivClass.html(data);
                    }
                });
            }
        });

        $(document).on('click', '.searchSuppName', function () {
             $(this).closest("div.remove").find('.search_supplier_id_div').fadeOut();
             $(this).closest("div.remove").find('.search_supplier_id').val('');
             $(this).closest("div.remove").find('.supplier_id').val('');
             $(this).closest("div.remove").find('.search_supplier_id').val($(this).text());
             $(this).closest("div.remove").find('.supplier_id').val($(this).attr("value"));

        });

        // End supplier name search =======================
        // =============================================

        // Item change function ============================
        // =================================================
         $(document).on('change','.item_id',function(){

            var itemId = $(this).val();
            var _token     = "<?php echo csrf_token(); ?>";

            var itemOldId = $(this).find(':selected').attr('attrolditemid');

            $(this).closest("div.supplierUnderRows").find('.unit_price').val('');
            $(this).closest("div.supplierUnderRows").find('.discount_price').val('');
            $(this).closest("div.supplierUnderRows").find('.discount_amount').val('');
            $(this).closest("div.supplierUnderRows").find('.total_price').val('');
            $(this).closest("div.supplierUnderRows").find('.last_unti_price').val('');

            var uniPriceAjax        = $(this).closest("div.supplierUnderRows").find(".unit_price");
            var discountPriceAjx    = $(this).closest("div.supplierUnderRows").find(".discount_price");
            var discountAmountAjx   = $(this).closest("div.supplierUnderRows").find(".discount_amount");
            var totalAmountAjx      = $(this).closest("div.supplierUnderRows").find(".total_price");
            var quantityAjx         = $(this).closest("div.supplierUnderRows").find(".quantity");
            var lastUnitAmountAjx   = $(this).closest("div.supplierUnderRows").find(".last_unti_price");

            $.ajax({
                url: "../create-collection-quotation-item-info",
                method: "POST",
                data: {itemId: itemId, itemOldId: itemOldId,  _token: _token},
                success: function (data) {
                    uniPriceAjax.val(data.unit_price);
                    lastUnitAmountAjx.val(data.last_unti_price);
                    discountPriceAjx.val(0);
                    discountAmountAjx.val(0);
                    quantityAjx.val(data.unit);
                    totalAmountAjx.val(data.unit_price);

                    uniPriceAjax.trigger( "input" );
                    quantityAjx.trigger("input");
                }
            });

         });
         // End item search=================================
         // ============================================
         
         // calculation of total quantity ===========================
         $(document).on('input','.quantity',function(){

                var totalUnitsum = 0;
                totalUnitsum = 0;
                $(this).closest("div.remove").find(".quantity").each(function(){
                    totalUnitsum += +$(this).val();
                });
            $(this).closest("div.remove").find('.total_quantity').val(totalUnitsum);

         });


         // calculation total price
         $(document).on('input','.unit_price, .discount_amount',function(){
            var unitPrice           = $(this).closest("div.supplierUnderRows").find('.unit_price').val();
            var discountAmountPrice = $(this).closest("div.supplierUnderRows").find('.discount_amount').val();
            var unit = $(this).closest("div.supplierUnderRows").find('.quantity').val();
            var afterDiscountUnitPrice = unitPrice-discountAmountPrice;
            var price = afterDiscountUnitPrice*unit;

            $(this).closest("div.supplierUnderRows").find('.total_price').val(price);

                var totalPricesum = 0;
                totalPricesum = 0;
                $(this).closest("div.remove").find(".total_price").each(function(){
                    totalPricesum += +$(this).val();
                });
            
            $(this).closest("div.remove").find('.total').val(totalPricesum);
                

         });

         //date picker ====================================
         
         $(document).on('mouseover', '.datapicker2', function(){
             $('.datapicker2').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
            });

         });



         $(document).on("click",".removeRow",function(){
            $(this).closest('.remove').remove();
            i = i-1;
            sl = sl-1;

         });
         

    });
</script>

@stop


