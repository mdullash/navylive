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
                    Edit Demand
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
                        Edit Demand
                    </div>
                    <div class="panel-body">


                        <form action="{{url('demand-update/'.$value->id)}}" method="post" class="demands" enctype="multipart/form-data">

                            @csrf

                            <div class="col-md-12" {{--style="border: 1px solid black; padding: 5px;"--}}>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="requester">1. Demanding:<span class="text-danger">*</span></label>
                                        <!-- <input type="text" class="form-control " id="requester" name="requester" placeholder="Enter demander name" required=""> -->
                                        <select class="form-control selectpicker requester" name="requester" id="requester"  data-live-search="true">
                                            <option value="">{!! '- Select -' !!}</option>
                                            @foreach($demandeNames as $dmdn)
                                                <option value="{!! $dmdn->id !!}" @if($value->requester == $dmdn->id) selected @endif>{!! $dmdn->name !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="issue_control_stamp">2. Issue control stamp</label>
                                        <input type="file" class="form-control " id="issue_control_stamp" name="issue_control_stamp" placeholder="Issue control stamp">
                                    </div>
                                </div> --}}

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="demand_no">2. Demand No:<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control " id="demand_no" name="demand_no" placeholder="Demand no" value="{{$value->demand_no}}">
                                    </div>
                                </div>

                                <div class="col-md-4 sRecurring">
                                    <div class="form-group">
                                        <label for="recurring_casual_or_not1">3. Recurring</label>
                                        <div class="radio radio-info">

                                            <span>
                                                <input class="activity_1 activitycell" type="radio" id="recurring_casual_or_not1" name="recurring_casual_or_not" value="1" @if($value->recurring_casual_or_not == 1) checked @endif>
                                                <label for="recurring_casual_or_not1">Casual</label>
                                            </span>
                                            
                                            <span style="margin-left: 25px;">
                                                <input class="activity_1 activitycell" type="radio" id="recurring_casual_or_not2" name="recurring_casual_or_not" value="2" @if($value->recurring_casual_or_not == 2) checked @endif>
                                                <label for="recurring_casual_or_not2">Formal</label>
                                            </span>

                                        </div>

                                    </div>
                                </div>

                                {{--<div class="col-md-4">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="priority">Priority</label>--}}
                                        {{--<input type="text" class="form-control " id="priority" name="priority" placeholder="Priority">--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="priority">4. Priority:</label>
                                        <select class="form-control selectpicker" name="priority" id="priority">
                                            <option value="">{!! '- Select -' !!}</option>
                                            <option value="Normal" @if($value->priority == "Normal") selected @endif>{!! 'Normal' !!}</option>
                                            <option value="Immediate" @if($value->priority == "Immediate") selected @endif>{!! 'Immediate' !!}</option>
                                            <option value="OPS Immediate (Operational Immediate)" @if($value->priority == "OPS Immediate (Operational Immediate)") selected @endif>{!! 'OPS Immediate (Operational Immediate)' !!}</option>
                                        </select>
                                    </div>
                                </div>

                                {{--<div class="col-md-4 sItemType">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="permanent_or_waste_content1">5. Item Type</label>--}}
                                        {{--<div class="radio radio-info">--}}
                                            {{----}}
                                            {{--<span>--}}
                                                {{--<input class="activity_1 activitycell" type="radio" id="permanent_or_waste_content1" name="permanent_or_waste_content" value="1">--}}
                                                {{--<label for="permanent_or_waste_content1">Permanent</label>--}}
                                            {{--</span>--}}

                                            {{--<span style="margin-left: 25px;">--}}
                                                {{--<input class="activity_1 activitycell" type="radio" id="permanent_or_waste_content3" name="permanent_or_waste_content" value="3">--}}
                                                {{--<label for="permanent_or_waste_content3">Quasi Per.</label>--}}
                                            {{--</span>--}}

                                            {{--<span style="margin-left: 25px;">--}}
                                                {{--<input class="activity_1 activitycell" type="radio" id="permanent_or_waste_content2" name="permanent_or_waste_content" value="2">--}}
                                                {{--<label for="permanent_or_waste_content2">Consumable</label>--}}
                                            {{--</span>--}}
                                            {{----}}
                                        {{--</div>--}}
                                        {{-- <div class="radio radio-info">--}}
                                            {{--<input class="activity_1 activitycell" type="radio" id="permanent_or_waste_content2" name="permanent_or_waste_content" value="2">--}}
                                            {{--<label for="permanent_or_waste_content2">Waste Content</label>--}}
                                        {{--</div> --}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="when_needed">5. Demand Date<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control datapicker2" id="when_needed" name="when_needed" placeholder="Demand Date" value="{!! $value->when_needed !!}" readonly>
                                    </div>
                                </div>

                                {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="posted_date">8. Posted Date</label>
                                        <input type="text" class="form-control datapicker2" id="posted_date" name="posted_date" placeholder="Posted date" value="{!! date('Y-m-d') !!}" readonly>
                                    </div>
                                </div> --}}

                                {{--<div class="col-md-4">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="posted_by_sign">Posted By Sign</label>--}}
                                        {{--<input type="file" class="form-control " id="posted_by_sign" name="posted_by_sign">--}}
                                    {{--</div>--}}
                                {{--</div>--}}



                                {{--<div class="col-md-4">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="provided_by_sign">Approved By Sign</label>--}}
                                        {{--<input type="file" class="form-control " id="provided_by_sign" name="provided_by_sign">--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="provided_date">Provided Date</label>
                                        <input type="text" class="form-control datapicker2" id="provided_date" name="provided_date" placeholder="Approved date" value="{!! date('Y-m-d') !!}" readonly>
                                    </div>
                                </div>--}}

                                {{--<div class="col-md-4">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="application_to">Application To</label>--}}
                                        {{--<input type="text" class="form-control " id="application_to" name="application_to" placeholder="Application to">--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <div class="col-md-4" style="display: none;">
                                    <div class="form-group">
                                        <label for="place_to_send">6. Delivery Place</label>
                                        <select class="form-control selectpicker place_to_send" name="place_to_send" id="place_to_send"  data-live-search="true">
                                            <option value="">{!! '- Select -' !!}</option>
                                            @foreach($destinationPlaces as $dst)
                                                <option value="{!! $dst->id !!}" @if($value->place_to_send == $dst->id) selected @endif>{!! $dst->name !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="for_whom">6. For Whom</label>
                                        <input type="text" class="form-control" id="for_whom" name="for_whom" placeholder="For whom" value="{{$value->for_whom}}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="pattern_or_stock_no">7. Authority Number<!-- Patt/Part Number --><span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="pattern_or_stock_no" name="pattern_or_stock_no" placeholder="Authority Number" required value="{{$value->pattern_or_stock_no}}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="pattern_or_stock_no">8. Authority Date<span class="text-danger"></span></label>
                                        <input type="text" class="form-control datapicker2" id="reference_date" name="reference_date" value="{{date('Y-m-d',strtotime($value->reference_date))}}" placeholder="Reference Date" autocomplete="off" readonly="">
                                    </div>
                                </div>

                                {{--<div class="col-md-12">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="pattern_or_stock_no">10. Item Details:</label>--}}
                                        {{--<textarea class="form-control" id="product_detailsetc" name="product_detailsetc" placeholder="Item Details" rows="3"></textarea>--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                            </div> {{-- end of col-md-12 --}}

                            {{--<div class="col-md-12" --}}{{--style="border: 1px solid black; padding: 5px;"--}}{{-->--}}

                                {{--<div class="col-md-4">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="necessary_amount">Necessary Amount</label>--}}
                                        {{--<input type="number" class="form-control" id="necessary_amount" name="necessary_amount" placeholder="Necessary Amount">--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="col-md-4">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="allowed">Allowed:<span class="text-danger">*</span></label>--}}
                                        {{--<input type="number" class="form-control" id="allowed" name="allowed" placeholder="Allowed">--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="col-md-4">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="rest_amount">Rest Amount</label>--}}
                                        {{--<input type="number" class="form-control" id="rest_amount" name="rest_amount" placeholder="Rest Amount">--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="col-md-4">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="given_quantity">Given Quantity</label>--}}
                                        {{--<input type="number" class="form-control" id="given_quantity" name="given_quantity" placeholder="Given Quantity">--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                            {{--</div>--}}
                            <?php
                            $item_count = count($items);
                            ?>
                            @foreach($items as $item)
                        <div class="col-md-12 remove appendRewRow firstRow" id="firstRow" {{--style="border: 1px solid black; padding: 5px;"--}}>
                            @if($loop->iteration < 2)
                            <b style="padding-left: 12px;">Segmental Detection of Content:</b><br><br>
                            @endif
                            <span><b></b></span><br>
                            {{--<div class="col-md-4">--}}
                            {{--<div class="form-group">--}}
                            {{--<label for="rate">Rate</label>--}}
                            {{--<input type="number" class="form-control" id="rate" name="rate" placeholder="Rate">--}}
                            {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="col-md-4">--}}
                            {{--<div class="form-group">--}}
                            {{--<label for="publication_or_class">Publication / Class</label>--}}
                            {{--<input type="text" class="form-control" id="publication_or_class" name="publication_or_class" placeholder="Publication / Class">--}}
                            {{--</div>--}}
                            {{--</div>--}}

                                <?php
                                $itemValue = \App\Item::find($item->item_id);
                                ?>
                                <input type="hidden" value="{{$item->id}}" name="id_item[]">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="machinery_and_manufacturer">Item Name: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control search_item_name" id="machinery_and_manufacturer" name="machinery_and_manufacturer[]" placeholder="Search...." autocomplete="off" value="{{$itemValue->item_name}}">
                                    <input type="hidden" class="form-control item_id" id="" name="machinery_and_manufacturer_id[]" placeholder="" required value="{{$item->item_id}}">
                                </div>
                                <div class="form-group col-xs-12 col-sm-12 col-md-3 search_itmem_name_div" id="search_itmem_name_div" style="display: none; display: block; position: absolute; left: 0px; top: 55px;"></div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="model_type_mark">Model / Type / Mark:</label>
                                    <input type="text" class="form-control model_number" id="model_type_mark" name="model_type_mark[]" placeholder="Model / Type / Mark" value="{{$item->item_model}}">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="serial_or_reg_number">Serial/Reg./IMC No:<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control imc_number" id="serial_or_reg_number" name="serial_or_reg_number[]" placeholder="Serial/Registration/IMC No" readonly="" value="{{$item->serial_imc_no}}">
                                </div>
                            </div>

                            <div class="col-md-2" style="display: none;">
                                <div class="form-group">
                                    <label for="item_type">Item Type :</label>
                                    <input type="text" class="form-control item_type" id="item_type" name="item_type[]" placeholder="Item Type" readonly="" value="">
                                </div>
                            </div>

                            <div class="col-md-3" style="display: none;">
                                <div class="form-group">
                                    <label for="publication_or_class">Group :<span class="text-danger">*</span></label>
                                    <select class="form-control selectpicker item_cat_id" name="publication_or_class[]" id="publication_or_class"  data-live-search="true">
                                        <option value="">{!! '- Select -' !!}</option>
                                        @foreach($supplyCategories as $gpn)
                                            <option value="{!! $gpn->id !!}" @if($item->group_name == $gpn->id) selected @endif>{!! $gpn->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="deno">Deno :<span class="text-danger">*</span></label>
                                    <select class="form-control selectpicker item_deno_id" name="deno[]" id="deno"  data-live-search="true">
                                        @foreach($denos as $dn)
                                            <option value="{!! $dn->id !!}" @if($item->deno_id == $dn->id) selected @endif>{!! $dn->name!!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="unit">Quantity:<span class="text-danger">*</span></label>
                                    <input type="number" step="any" class="form-control unit" id="unit" name="unit[]" placeholder="Quantity" min="0" required value="{{$item->unit}}">
                                </div>
                            </div>

                            <div class="col-md-2 hidden">
                                <div class="form-group">
                                    <label for="currency_rates">Currency Rate</label>
                                    <input type="number" class="form-control conversion" id="currency_rates" name="currency_rates[]" placeholder="Currency Rate" min="1" readonly="" value="{{$item->currency_rate}}">
                                </div>
                            </div>

                            <div class="col-md-2" style="display: none;">
                                <div class="form-group">
                                    <label for="price">Estimated Price:<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control unit_price" id="price" name="price[]" placeholder="Estimated Price" min="0" required value="{{$item->unit_price}}">
                                </div>
                            </div>

                            <div class="col-md-2" style="display: none;">
                                <div class="form-group">
                                    <label for="sutotal_price">Estimated Subtotal</label>
                                    <input type="number" class="form-control sutotal_price" id="sutotal_price" name="sutotal_price[]" placeholder="Subtotal Price" min="0" readonly value="{{$item->sub_total}}">
                                </div>
                            </div>
                                @if($loop->iteration > 1)
                                    <div class="col-md-1" style="padding-top: 25px;">
                                        <a href="{{url('demand-item-delete/'.$item->id)}}" class="btn btn-danger" id="" title="Delete"><i class="fa fa-trash"></i></a>
                                    </div>
                                    @endif

                            <div style="clear: both !important;"><hr style="border-top: 1px solid #999999;"></div>

                        </div>
                            @endforeach
                        <div class="col-md-12">
                            <div class="col-md-3"></div><div class="col-md-3"></div><div class="col-md-3"></div>
                            <div class="col-md-3">
                                <div class="form-group pull-right">
                                    <button class="btn btn-info" id="addNewRow" type="button" title="Add New"><i class="icon-plus"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" {{--style="border: 1px solid black; padding: 5px;"--}}>
                            {{--<b style="padding-left: 12px;">Total Value:</b><br><br>--}}

                            <div class="col-md-3 hidden">
                                <div class="form-group">
                                    <label for="total_value">Total Unit</label>
                                    <input type="number" class="form-control" id="total_unit" name="total_unit" placeholder="Total" min="0" readonly>
                                </div>
                            </div>

                            <div class="col-md-3 hidden">
                                <div class="form-group">
                                    <label for="total_value">Total Estimated Price</label>
                                    <input type="number" class="form-control" id="total_value" name="total_value" placeholder="Total" min="0" readonly>
                                </div>
                            </div>
                            {{--<div class="col-md-4">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label for="price">Custom</label>--}}
                                    {{--<input type="number" class="form-control" id="custom" name="custom" placeholder="Custom">--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="col-md-4">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label for="vat">Vat</label>--}}
                                    {{--<input type="number" class="form-control" id="vat" name="vat" placeholder="Vat">--}}
                                {{--</div>--}}
                            {{--</div>--}}

                        </div>



                        <div class="form-group">
                            <div class="col-md-7 col-sm-offset-5">
                                <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>

                                <button type="submit" class="btn btn-primary pull-right">{!!trans('english.SAVE')!!}</button>

                            </div>
                        </div>
    
                    <!-- <div class="hr-line-dashed"></div> -->
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <script type="text/javascript">
        $(document).ready(function(){
            var i = {{$item_count-1}};
            var sl = i+2;
            $(document).on('click','#addNewRow',function(){
                $( "body" ).find( ".firstRow" ).eq( i ).after( '<div class="col-md-12 remove firstRow"><span><b></b></span><br> <div class="col-md-3"><div class="form-group"><label for="machinery_and_manufacturer">Item Name: <span class="text-danger">*</span></label><input type="text" class="form-control search_item_name" id="machinery_and_manufacturer" name="machinery_and_manufacturer[]" placeholder="Search...." autocomplete="off"><input type="hidden" class="form-control item_id" id="" name="machinery_and_manufacturer_id[]" placeholder="" required></div><div class="form-group col-xs-12 col-sm-12 col-md-3 search_itmem_name_div" id="search_itmem_name_div" style="display: none; display: block; position: absolute; left: 0px; top: 55px;"></div></div><div class="col-md-2"><div class="form-group"><label for="model_type_mark">Model / Type / Mark</label><input type="text" class="form-control model_number" id="model_type_mark" name="model_type_mark[]" placeholder="Model / Type / Mark"></div></div><div class="col-md-2"><div class="form-group"><label for="serial_or_reg_number">Serial/Reg./IMC No:<span class="text-danger">*</span></label><input type="text" class="form-control imc_number" id="serial_or_reg_number" name="serial_or_reg_number[]" placeholder="Serial/Registration/IMC No" readonly=""></div></div><div class="col-md-2" style="display: none;"><div class="form-group"><label for="item_type">Item Type :</label><input type="text" class="form-control item_type" id="item_type" name="item_type[]" placeholder="Item Type" readonly=""></div></div><div class="col-md-3" style="display: none;"><div class="form-group"><label for="publication_or_class">Group :<span class="text-danger">*</span></label><select class="form-control selectpicker item_cat_id" name="publication_or_class[]" id="publication_or_class'+i+'" data-live-search="true"><option value="">- Select - </option></select></div></div><div class="col-md-2"><div class="form-group"><label for="deno">Deno :<span class="text-danger">*</span></label><select class="form-control selectpicker item_deno_id" name="deno[]" id="deno'+i+'" data-live-search="true" disabled><option value="">- Select - </option></select></div></div><div class="col-md-2"><div class="form-group"><label for="unit">Quantity:<span class="text-danger">*</span></label><input type="number" class="form-control unit" id="unit" name="unit[]" step="any" placeholder="Quantity" min="0" required></div></div><div class="col-md-2 hidden"><div class="form-group"><label for="currency_rates">Currency Rate</label><input type="number" class="form-control conversion" id="currency_rates" name="currency_rates[]" placeholder="Currency Rate" min="1" readonly=""></div></div><div class="col-md-2" style="display: none;"><div class="form-group"><label for="price">Estimated Price:<span class="text-danger">*</span></label><input type="number" class="form-control unit_price" id="price" name="price[]" placeholder="Estimated Price" min="0" required></div></div><div class="col-md-2" style="display: none;"><div class="form-group"><label for="sutotal_price">Subtotal Price</label><input type="number" class="form-control sutotal_price" id="sutotal_price" name="sutotal_price[]" placeholder="Subtotal Price" min="0" readonly></div></div><div class="col-md-1" style="padding-top: 25px;"><button class="btn btn-danger removeRow" id="" type="button" data-placement="top" data-rel="tooltip" data-original-title="Remove"><i class="fa fa-trash"></i></button></div><div style="clear: both !important;"><hr style="border-top: 1px solid #999999;"></div></div>' );

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
                var itemType        = $(this).attr('att-item-type-val');

                if(unitPrice=='' || unitPrice<0){
                    unitPrice = 0;
                }

                if(itemType==1){
                    itemType = 'Permanent Content';
                }
                if(itemType==2){
                    itemType = 'Waste Content';
                }
                if(itemType==3){
                    itemType = 'Quasi Permanent Content';
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

            $('.item_deno_id').prop('disabled', true);

            $('#demands').on('submit', function() {
                $('.item_deno_id').prop('disabled', false);
            });

        });
    </script>

@stop


