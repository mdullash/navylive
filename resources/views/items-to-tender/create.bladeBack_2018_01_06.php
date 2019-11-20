@extends('layouts.default')

<style type="text/css">

.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}
</style>

@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    Create Item To Tender
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
                        Create Item To Tender
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'itemtotender/store', 'files'=> true, 'class' => 'form-horizontal item-to-tender', 'id'=>'item-to-tender')) }}

                    <div class="row">
                        <div class="col-sm-6 col-md-6">

                            <input type="hidden" name="tender_id" value="{!! $tender_id !!}">

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">PO Number :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('po_number', $tender->po_number, array('id'=> 'po_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Purchase Order Date :<span class="text-danger"></span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('work_order_date', $tender->work_order_date, array('id'=> 'work_order_date', 'class' => 'form-control datapicker2')) !!}
                                </div>
                            </div>

                             <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Delivery Date :<span class="text-danger"></span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('delivery_date', $tender->delivery_date, array('id'=> 'delivery_date', 'class' => 'form-control datapicker2')) !!}
                                </div>
                            </div>


                        </div>

                        <div class="col-sm-6 col-md-6">

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Supplier Name :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {{--<select class="form-control selectpicker" name="supplier_id" id="supplier_id"  data-live-search="true">--}}
                                        {{--<option value="">{!! '- Select -' !!}</option>--}}
                                        {{--@foreach($suppliers as $sp)--}}
                                            {{--<option value="{!! $sp->id !!}" @if($sp->id==$tender->supplier_id) {{'selected'}} @endif>{!! $sp->company_name !!}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                    <?php $supplier_name = !empty($supplier_name_if_exit) ? $supplier_name_if_exit->company_name : ''; ?>
                                    {!!  Form::text('search_supplier_id', $supplier_name, array('id'=> 'search_supplier_id', 'class' => 'form-control search_supplier_id', 'autocomplete'=> 'off', 'placeholder'=>'Search Supplier ...')) !!}
                                    <input type="hidden" id="supplier_id" name="supplier_id" value="{!! $tender->supplier_id !!}">
                                    <div class="form-group col-xs-12 col-sm-12 col-md-6 search_supplier_id_div" id="search_supplier_id_div" style="display: none; display: block; position: absolute; left: 16px;"></div>
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Deadline :<span class="text-danger"></span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('date_line', $tender->date_line, array('id'=> 'date_line', 'class' => 'form-control datapicker2')) !!}
                                </div>
                            </div>

                            {{--<div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">IMC Number :<span class="text-danger"></span></label>--}}
                                {{--<div class="col-md-7">--}}
                                    {{--{!!  Form::text('imc_number', $tender->imc_number, array('id'=> 'imc_number', 'class' => 'form-control imc_number')) !!}--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            
                        </div>

                    </div>

<!-- Add new row section ==========================================================     -->
                    <br><br>
                    <div class="row" style="text-align: center;" id="theaderRow">
                        <div class="col-md-3"><b>Item Name</b></div>
                        <div class="col-md-2"><b>Unit Price</b></div>
                        <div class="col-md-2"><b>Quantity</b></div>
                        <div class="col-md-2"><b>Discount Per Unit</b></div>
                        <div class="col-md-2"><b>Total</b></div>
                        <div class="col-md-1"><b>Action</b></div>
                    </div>
                    
                    <br>
                    
                    <input type="hidden" name="existingIds" value="{!! json_encode($existingIds) !!}">
                    
                    <?php $i = 1; ?>
                    @if(count($itemAlreadyAssign)>0)
                        @foreach($itemAlreadyAssign as $iaa)
                            
                        <div class="row remove firstRow" id="firstRow">
                            
                            <input type="hidden" name="editedfield[]" value="{!! $iaa->id !!}">

                            <div class="col-md-3">
                                {{--<select class="form-control selectpicker item_id" name="item_id[]" id="item_id"  data-live-search="true">--}}
                                    {{--<option value="">{!! '- Select -' !!}</option>--}}
                                    {{--@foreach($items as $it)--}}
                                        {{--<option value="{!! $it->id !!}" att-unit-price="{!! $it->unit_price !!}" att-discount-price="{!! $it->discounted_price !!}" @if($it->id==$iaa->item_id) {{'selected'}} @endif>{!! $it->item_name !!}</option>--}}
                                    {{--@endforeach--}}
                                {{--</select>--}}
                                <?php $itemName = \App\Item::find($iaa->item_id); ?>
                                {!!  Form::text('search_item_name', $itemName->item_name, array('id'=> 'search_item_name', 'class' => 'form-control search_item_name', 'autocomplete'=> 'off', 'placeholder'=>'Search Item ...')) !!}
                                <input type="hidden" class="item_id" id="item_id" name="item_id[]" value="{!! $iaa->item_id !!}">
                                <div class="form-group col-xs-12 col-sm-12 col-md-6 search_itmem_name_div" id="search_itmem_name_div" style="display: none; display: block; position: absolute; left: 16px;"></div>
                            </div>
                            <div class="col-md-2">
                                {!!  Form::text('unit_price[]', $iaa->unit_price, array('class' => 'form-control unit_price', 'id' => 'lemon')) !!}
                            </div>
                            <div class="col-md-2">
                                {!!  Form::text('quantity[]', $iaa->quantity, array('class' => 'form-control quantity')) !!}
                            </div>
                            <div class="col-md-2">
                                {!!  Form::text('discount_price[]', $iaa->discount_price, array('class' => 'form-control discount_price')) !!}
                            </div>
                            <div class="col-md-2">
                                {!!  Form::text('total[]', $iaa->total, array('class' => 'form-control total', 'readonly' => 'readonly')) !!}
                            </div>
                            <div class="col-md-1">

                                @if($i != 1)
                                    <button class="btn btn-danger removeRow" id="" type="button" data-placement="top" data-rel="tooltip" data-original-title="Remove"><i class="fa fa-trash"></i></button> 
                                @endif
                            </div>
                            
                        </div> <br>
                        <?php $i++ ?>
                        @endforeach

                    @endif

                    @if(count($itemAlreadyAssign)<1) 
                    <div class="row remove firstRow" id="firstRow">
                        <div class="col-md-3">
                            {{--<select class="form-control selectpicker item_id" name="item_id[]" id="item_id"  data-live-search="true">--}}
                                {{--<option value="">{!! '- Select -' !!}</option>--}}
                                {{--@foreach($items as $it)--}}
                                    {{--<option value="{!! $it->id !!}" att-unit-price="{!! $it->unit_price !!}" att-discount-price="{!! $it->discounted_price !!}">{!! $it->item_name !!}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}

                            {!!  Form::text('search_item_name', '', array('id'=> 'search_item_name', 'class' => 'form-control search_item_name', 'autocomplete'=> 'off', 'placeholder'=>'Search Item ...')) !!}
                            <input type="hidden" class="item_id" id="item_id" name="item_id[]" value="">
                            <div class="form-group col-xs-12 col-sm-12 col-md-6 search_itmem_name_div" id="search_itmem_name_div" style="display: none; display: block; position: absolute; left: 16px;"></div>

                        </div>
                        <div class="col-md-2">
                            {!!  Form::text('unit_price[]', '', array('class' => 'form-control unit_price', 'id' => 'lemon')) !!}
                        </div>
                        <div class="col-md-2">
                            {!!  Form::text('quantity[]', old('quantity'), array('class' => 'form-control quantity')) !!}
                        </div>
                        <div class="col-md-2">
                            {!!  Form::text('discount_price[]', old('discount_price'), array('class' => 'form-control discount_price')) !!}
                        </div>
                        <div class="col-md-2">
                            {!!  Form::text('total[]', old('total'), array('class' => 'form-control total', 'readonly' => 'readonly')) !!}
                        </div>
                        <div class="col-md-1">
                            <!-- <button class="btn btn-danger removeRow" id="" type="button" data-placement="top" data-rel="tooltip" data-original-title="Remove"><i class="fa fa-trash"></i></button> -->
                        </div>
                        
                    </div>
                    <br>

                    @endif

                    <div class="row">
                        <div class="col-md-2">
                            <button class="btn btn-info" id="addNewRow" type="button" data-placement="top" data-rel="tooltip" data-original-title="Add New"><i class='icon-plus'></i></button>
                        </div>
                    </div>
                        
                       
                    <div class="form-group">
                        <div class="col-md-7 col-sm-offset-5">
                            <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                            
                            <button type="submit" class="btn btn-primary pull-right">{!!trans('english.ACTION')!!}</button>
                            
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


    var i = 1;
    var apn = 0;
    $(document).on("click","#addNewRow",function(){

        //$(".firstRow"+apn).

        // $("#firstRow").after( '<div class="row remove firstRow" style="padding-top : 10px;"><div class="col-md-3"><select class="form-control selectpicker item_id" name="item_id[]" id="item_id'+i+'"  data-live-search="true"><option value="">- Select -</option></select></div><div class="col-md-2"><input type="text" class="form-control unit_price" name="unit_price[]" /></div><div class="col-md-2"> <input type="text" class="form-control quantity" name="quantity[]" /></div><div class="col-md-2"><input type="text" class="form-control discount_price" name="discount_price[]" /></div><div class="col-md-2"><input type="text" class="form-control total" name="total[]" readonly ="readonly"/></div><div class="col-md-1"><button class="btn btn-danger removeRow" id="" type="button" data-placement="top" data-rel="tooltip" data-original-title="Remove"><i class="fa fa-trash"></i></button></div></div>' );

        // var items = <?php echo json_encode($items); ?>; 
        // $.each(items, function (key, value) {
        //     $('#item_id'+i).append("<option value='"+ value.id+"' att-unit-price='"+ value.unit_price+"' att-discount-price='"+ value.discounted_price+"'>"+value.item_name+"</option>"); 
        // });
 
        // $('#item_id'+i).selectpicker('refresh');

        // i++;
        
        $("#firstRow").after( '<div class="row remove firstRow" style="padding-top : 10px;"><div class="col-md-3"><input type="text" class="form-control search_item_name" id="search_item_name" name="search_item_name" autocomplete="off" placeholder="Search Item ..." /> <input type="hidden" class="form-control item_id" name="item_id[]" id="item_id" /> <div class="form-group col-xs-12 col-sm-12 col-md-6 search_itmem_name_div" id="search_itmem_name_div" style="display: none; display: block; position: absolute; left: 16px;"></div></div><div class="col-md-2"><input type="text" class="form-control unit_price" name="unit_price[]" /></div><div class="col-md-2"> <input type="text" class="form-control quantity" name="quantity[]" /></div><div class="col-md-2"><input type="text" class="form-control discount_price" name="discount_price[]" /></div><div class="col-md-2"><input type="text" class="form-control total" name="total[]" readonly ="readonly"/></div><div class="col-md-1"><button class="btn btn-danger removeRow" id="" type="button" data-placement="top" data-rel="tooltip" data-original-title="Remove"><i class="fa fa-trash"></i></button></div></div>' );

    });

    $(document).on("click",".removeRow",function(){
        $(this).closest('.remove').remove();
        
    });

    // $(document).on("change",".item_id",function(){

    //    var unitPrice = $('option:selected', this).attr('att-unit-price');
    //    $(this).closest("div.remove").find(".unit_price").val(unitPrice);

    //    $(this).closest("div.remove").find(".quantity").val(1);

    //    var discountPrice = $('option:selected', this).attr('att-discount-price');
    //    if(!discountPrice || discountPrice != ''){
    //     discountPrice = 0;
    //    }
    //    $(this).closest("div.remove").find(".discount_price").val(discountPrice);
        
    //   $(this).closest("div.remove").find(".unit_price").trigger('input');

    // });

    $(document).on("input",".quantity,.discount_price,.unit_price",function(){
        var unitPrice       = $(this).closest("div.remove").find(".unit_price").val();
        var quantity        = $(this).closest("div.remove").find(".quantity").val();
        var discountPrice  = $(this).closest("div.remove").find(".discount_price").val();
        
        var total = parseFloat((unitPrice*quantity)-(quantity*discountPrice)).toFixed(2);

        $(this).closest("div.remove").find(".total").val(total);

    });

    // Supplier Search=============================
        $('.search_supplier_id').keyup(function() {
            var query = $(this).val();

            if(query == ''){ $('#supplier_id').val(''); $('.search_supplier_id_div').fadeOut();}

            if (query != '') {
                var _token     = "<?php echo csrf_token(); ?>";
                $.ajax({
                    url: "../../awarded-rep-supplier-name-live-search",
                    method: "POST",
                    data: {query: query, _token: _token},
                    success: function (data) {
                        $('.search_supplier_id_div').fadeIn();
                        $('.search_supplier_id_div').html(data);
                    }
                });
            }
        });

        $(document).on('click', '.searchSuppName', function () {
            $('.search_supplier_id_div').fadeOut();
            $('#search_supplier_id').val('');
            $('#supplier_id').val('');
            $('#search_supplier_id').val($(this).text());
            $('#supplier_id').val($(this).attr("value"));

        });
        // End supplier search =============================================

        // Item Search======================================================
        $(document).on('keyup','.search_item_name',function(){
        //$('.search_item_name').keyup(function() {
            var query = $(this).val();

            if(query == ''){
                $(this).closest("div.remove").find(".item_id").val('');
                $(this).closest("div.remove").find('.search_itmem_name_div').fadeOut();

                $(this).closest("div.remove").find(".unit_price").val('');
                $(this).closest("div.remove").find(".quantity").val('');
                $(this).closest("div.remove").find(".discount_price").val('');
                $(this).closest("div.remove").find(".unit_price").trigger('input');
            }

            if (query != '') {
                var _token     = "<?php echo csrf_token(); ?>";
                var closestDivClass = $(this).closest("div.remove").find('.search_itmem_name_div');
                $.ajax({
                    url: "../../item-to-tender-item-name-live-search",
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
            $(this).closest("div.remove").find('.search_item_name').val($(this).text());
            $(this).closest("div.remove").find('.item_id').val($(this).attr("value"));


            var unitPrice = $(this).attr('att-unit-price');
            $(this).closest("div.remove").find(".unit_price").val(unitPrice);
            $(this).closest("div.remove").find(".quantity").val(1);
            var discountPrice = $(this).attr('att-discount-price');
            if(discountPrice==null || discountPrice == ''){
                discountPrice = 0;
            }
            $(this).closest("div.remove").find(".discount_price").val(discountPrice);
            $(this).closest("div.remove").find(".unit_price").trigger('input');

        });
        // End item search =============================================


});
</script>

@stop


