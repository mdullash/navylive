@extends('layouts.default')

<style type="text/css">

.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}

.insise_table {
    border: none !important;
}
.insise_table th, td{
    border: none !important;
}

</style>

@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    Create Demand
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
                        Create Demand
                    </div>
                    <div class="panel-body">

                        {{ Form::open(array('role' => 'form', 'url' => 'post-group-status-change', 'files'=> true, 'class' => '', 'id'=>'')) }}

                        <input type="hidden" name="demand_id" value="{!! $demand->id !!}">

                        @if(!empty($itemtodemand))
                            <?php $sl = 1; ?>
                            @foreach($itemtodemand as $itd)
                                {{--<input type="hidden" value="{!! $demand->id !!}">--}}

                                <div class="col-md-12 remove appendRewRow firstRow" id="firstRow" {{--style="border: 1px solid black; padding: 5px;"--}}>
                                    <b></b><br>
                                    <input type="hidden" name="itemtodemandid[]" value="{!! $itd->id !!}">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="machinery_and_manufacturer">Machinery / Manufacturer: <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control search_item_name" id="machinery_and_manufacturer" name="machinery_and_manufacturer[]" value="{!! $itd->item_name !!}" placeholder="Machinery / Manufacturer">
                                            <input type="hidden" class="form-control item_id" id="" name="machinery_and_manufacturer_id[]" value="{!! $itd->item_id !!}" placeholder="" required>
                                        </div>
                                        <div class="form-group col-xs-12 col-sm-12 col-md-3 search_itmem_name_div" id="search_itmem_name_div" style="display: none; display: block; position: absolute; left: 0px; top: 55px;"></div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="model_type_mark">Model / Type / Mark:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control model_number" id="model_type_mark" name="model_type_mark[]" value="{!! $itd->item_model !!}" placeholder="Model / Type / Mark">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="serial_or_reg_number">Serial / Registration:<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control imc_number" id="serial_or_reg_number" name="serial_or_reg_number[]" value="{!! $itd->serial_imc_no !!}" placeholder="Serial / Registration" readonly="">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="publication_or_class">Group :<span class="text-danger">*</span></label>
                                            <select class="form-control selectpicker item_cat_id" name="publication_or_class[]" id="publication_or_class"  data-live-search="true">
                                                <option value="">{!! '- Select -' !!}</option>
                                                @foreach($supplyCategories as $gpn)
                                                    <option value="{!! $gpn->id !!}" @if($itd->group_name == $gpn->id) {!! 'selected' !!} @endif>{!! $gpn->name !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="deno">Deno :<span class="text-danger">*</span></label>
                                            <select class="form-control selectpicker item_deno_id" name="deno[]" id="deno"  data-live-search="true">
                                                <option value="">{!! '- Select -' !!}</option>
                                                @foreach($denos as $dn)
                                                    <option value="{!! $dn->id !!}" @if($itd->deno_id == $dn->id) {!! 'selected' !!} @endif>{!! $dn->name !!}</option>
                                                @endforeach 
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="unit">Unit:<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control unit" id="unit" name="unit[]" value="{!! $itd->unit !!}" placeholder="Unit" min="0" required>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="currency_rates">Currency Rates</label>
                                            <input type="number" class="form-control conversion" id="currency_rates" name="currency_rates[]" value="{!! $itd->currency_rate !!}" placeholder="Currency Rates" min="1" readonly="">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="price">Estimated Price:<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control unit_price" id="price" name="price[]" value="{!! $itd->unit_price !!}" placeholder="Estimated Price" min="0" required>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="sutotal_price">Subtotal Price</label>
                                            <input type="number" class="form-control sutotal_price" id="sutotal_price" name="sutotal_price[]" value="{!! $itd->sub_total !!}" placeholder="Subtotal Price" min="0" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-1" style="padding-top: 25px;"><button class="btn btn-danger removeRow" id="" type="button" data-placement="top" data-rel="tooltip" data-original-title="Remove"><i class="fa fa-trash"></i></button></div>

                                    <div style="clear: both !important;"><hr style="border-top: 1px solid #999999;"></div>

                                </div>
                                <?php $sl++; ?>
                            @endforeach
                        @endif {{-- end of empty function--}}

                        <div class="col-md-12">
                            <div class="col-md-3"></div><div class="col-md-3"></div><div class="col-md-3"></div>
                            <div class="col-md-3">
                                <div class="form-group pull-right">
                                    <button class="btn btn-info" id="addNewRow" type="button" title="Add New"><i class="icon-plus"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="total_value">Total Unit</label>
                                    <input type="number" class="form-control" id="total_unit" name="total_unit" value="{!! $demand->total_unit !!}" placeholder="Total" min="0" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="total_value">Total Price</label>
                                    <input type="number" class="form-control" id="total_value" name="total_value" value="{!! $demand->total_value !!}" placeholder="Total" min="0" readonly>
                                </div>
                            </div>

                        </div>

                        {{--End of item to demand ================================================================ --}}

                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sutotal_price">Approve Demand :</label>
                                    {{ Form::select('group_status', array('1' => 'In Stock', '2' => 'Not In Stock'), $demand->group_status , array('class' => 'form-control selectpicker', 'id' => 'group_status')) }}
                                </div>
                            </div>

                            {{-- @if(!empty(Auth::user()->nsd_bsd) && in_array(3,explode(',', Auth::user()->nsd_bsd))) --}}

                                <div class="col-md-3 @if(empty($demand->transfer_to)) hidden @endif" id="transfer_to_div">
                                    <div class="form-group">
                                        <label for="transfer_to">Transfer To :</label>
                                        {{ Form::select('transfer_to', $destinationPlaces, $demand->transfer_to, array('class' => 'form-control selectpicker', 'id' => 'transfer_to')) }}
                                    </div>
                                </div>

                                <div class="col-md-3 @if(empty($demand->transfer_to)) hidden @endif" id="transfer_status_div">
                                    <div class="form-group">
                                        <label for="transfer_to">Transfer Status :</label>
                                        {{ Form::select('transfer_status', array('' => 'Waiting for approve','1' => 'In Stock', '2' => 'Not In Stock'), $demand->transfer_status, array('class' => 'form-control selectpicker', 'id' => 'transfer_status')) }}
                                    </div>
                                </div>

                                <div class="col-md-3 @if($demand->transfer_status !=2 || empty($demand->plr_status)) hidden @endif" id="plr_div">
                                    <div class="form-group">
                                        <label for="plr_status">Local Purchase Requisition :</label>
                                        {{ Form::select('plr_status', array(1 => 'Hold','2' => 'Cancel', '3' => 'Send To LP'), $demand->plr_status, array('class' => 'form-control selectpicker', 'id' => 'plr_status')) }}
                                    </div>
                                </div>

                            {{-- @endif --}}

                        </div>

                        <div class="form-group">
                            <div class="col-md-11 col-sm-offset-1">
                                <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>

                                <button type="submit" class="btn btn-primary pull-right">{!!trans('english.SAVE')!!}</button>

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

            $(document).on('change','#group_status',function(){
                var thisVal = $(this).val();

                if(thisVal==2){
                    $('#transfer_to').val('').selectpicker('refresh');
                    $('#transfer_status').val('').selectpicker('refresh');

                    $("#transfer_to_div").removeClass('hidden');
                }else{
                    $('#transfer_to').val('').selectpicker('refresh');
                    $("#transfer_to_div").addClass('hidden');
                    $('#transfer_status').val('').selectpicker('refresh');
                    $("#transfer_status_div").addClass('hidden');
                }
            });

            $(document).on('change','#transfer_status',function(){
                var thisValC = $(this).val();
                if(thisValC==2){
                    $("#plr_div").removeClass('hidden');
                }else{
                    $("#plr_div").addClass('hidden');
                }
            });

            $(document).on('change','#transfer_to',function(){
                var thisVal = $(this).val();

                if(thisVal!=''){
                    $('#transfer_status').val('').selectpicker('refresh');

                    $("#transfer_status_div").removeClass('hidden');
                }else{
                    $('#transfer_status').val('').selectpicker('refresh');
                    $("#transfer_status_div").addClass('hidden');
                }

            });

            // ========================================================================================================
            // ========================================================================================================

            var sl = <?php echo $sl; ?>;
            var i = 0+sl;
            $(document).on('click','#addNewRow',function(){
                $( "body" ).find( ".firstRow" ).eq( i-2 ).after( '<div class="col-md-12 remove firstRow"><span><b></b></span><br> <div class="col-md-3"><div class="form-group"><label for="machinery_and_manufacturer">Machinery: <span class="text-danger">*</span></label><input type="text" class="form-control search_item_name" id="machinery_and_manufacturer" name="machinery_and_manufacturer[]" placeholder="Machinery"><input type="hidden" class="form-control item_id" id="" name="machinery_and_manufacturer_id[]" placeholder="" required></div><div class="form-group col-xs-12 col-sm-12 col-md-3 search_itmem_name_div" id="search_itmem_name_div" style="display: none; display: block; position: absolute; left: 0px; top: 55px;"></div></div><div class="col-md-3"><div class="form-group"><label for="model_type_mark">Model / Type / Mark</label><input type="text" class="form-control model_number" id="model_type_mark" name="model_type_mark[]" placeholder="Model / Type / Mark"></div></div><div class="col-md-3"><div class="form-group"><label for="serial_or_reg_number">Serial / Registration:<span class="text-danger">*</span></label><input type="text" class="form-control imc_number" id="serial_or_reg_number" name="serial_or_reg_number[]" placeholder="Serial / Registration" readonly=""></div></div><div class="col-md-3"><div class="form-group"><label for="publication_or_class">Group :<span class="text-danger">*</span></label><select class="form-control selectpicker item_cat_id" name="publication_or_class[]" id="publication_or_class'+i+'" data-live-search="true"><option value="">- Select - </option></select></div></div><div class="col-md-3"><div class="form-group"><label for="deno">Deno :<span class="text-danger">*</span></label><select class="form-control selectpicker item_deno_id" name="deno[]" id="deno'+i+'" data-live-search="true"><option value="">- Select - </option></select></div></div><div class="col-md-2"><div class="form-group"><label for="unit">Unit:<span class="text-danger">*</span></label><input type="number" class="form-control unit" id="unit" name="unit[]" placeholder="Unit" min="0" required></div></div><div class="col-md-2"><div class="form-group"><label for="currency_rates">Currency Rates</label><input type="number" class="form-control conversion" id="currency_rates" name="currency_rates[]" placeholder="Currency Rates" min="1" readonly=""></div></div><div class="col-md-2"><div class="form-group"><label for="price">Estimated Price:<span class="text-danger">*</span></label><input type="number" class="form-control unit_price" id="price" name="price[]" placeholder="Estimated Price" min="0" required></div></div><div class="col-md-2"><div class="form-group"><label for="sutotal_price">Subtotal Price</label><input type="number" class="form-control sutotal_price" id="sutotal_price" name="sutotal_price[]" placeholder="Subtotal Price" min="0" readonly></div></div><div class="col-md-1" style="padding-top: 25px;"><button class="btn btn-danger removeRow" id="" type="button" data-placement="top" data-rel="tooltip" data-original-title="Remove"><i class="fa fa-trash"></i></button></div><div style="clear: both !important;"><hr style="border-top: 1px solid #999999;"></div></div>' );

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
                        url: "../demand-item-name-live-search",
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


        });
    </script>

@stop


