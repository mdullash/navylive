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
                    Update Item
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
                        Update Item
                    </div>
                    <div class="panel-body">

                    {{ Form::model($editId, array('route' => array('item.update', $editId->id), 'method' => 'PUT', 'files'=> true, 'class' => 'form form-horizontal validate-form items', 'id' => 'items')) }}
                    
                        <div class="row">
                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">IMC Number :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('imc_number', old('imc_number'), array('id'=> 'imc_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Item Name :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('item_name', old('item_name'), array('id'=> 'item_name', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                             <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="brand">Brand :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('brand', old('brand'), array('id'=> 'brand', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Model:</label>
                                <div class="col-md-7">
                                    {!!  Form::text('model_number', old('model_number'), array('id'=> 'model_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Manufacturer's Name :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('manufacturer_name', old('manufacturer_name'), array('id'=> 'manufacturer_name', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Manufacturing Country :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('manufacturing_country', old('manufacturing_country'), array('id'=> 'manufacturing_country', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Country of Origin :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('country_of_origin', old('country_of_origin'), array('id'=> 'country_of_origin', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Part Number :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('part_number', old('part_number'), array('id'=> 'part_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Patt Number :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('patt_number', old('patt_number'), array('id'=> 'patt_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Item Additional Info :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('addl_item_info', old('addl_item_info'), array('id'=> 'addl_item_info', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Main Equipment Name :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('main_equipment_name', old('main_equipment_name'), array('id'=> 'main_equipment_name', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Main Equipment Brand :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('main_equipment_brand', old('main_equipment_brand'), array('id'=> 'main_equipment_brand', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Main Equipment Model :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('main_equipment_model', old('main_equipment_model'), array('id'=> 'main_equipment_model', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Main Equipment Additional:</label>
                                <div class="col-md-7">
                                    {!!  Form::text('main_equipment_additional_info', old('main_equipment_additional_info'), array('id'=> 'main_equipment_additional_info', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Equivalent/Substitute Item:</label>
                                <div class="col-md-7">
                                    {!!  Form::text('substitute_item', old('substitute_item'), array('id'=> 'substitute_item', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Shelf Life:</label>
                                <div class="col-md-7">
                                    {!!  Form::text('shelf_life', old('shelf_life'), array('id'=> 'shelf_life', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                             @if(\Session::get("zoneAlise") == "bsd")
                                <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Strength:</label>
                                    <div class="col-md-7">
                                        {!!  Form::number('strength', old('strength'), array('id'=> 'strength','step' => 'any' ,'class' => 'form-control' ,'required')) !!}
                                    </div>
                                </div>
                            @endif
                            
                            <div class="form-group col-md-6 col-sm-6" style="display: none;"><label class="control-label col-md-5 no-padding-right" for="status">Currency Name :</label>
                                <div class="col-md-7">
                                    <select class="form-control selectpicker" name="currency_name" id="currency_name"  data-live-search="true">
                                        <option value="" conversionrate="0">{!! '- Select -' !!}</option>
                                        @foreach($currencies_names as $csn)
                                            <option value="{!! $csn->id !!}" @if($editId->currency_name == $csn->id) {{"selected"}} @endif conversionrate="{!! $csn->conversion !!}" @if($default_currency) @if($default_currency->id==$csn->id) {!! 'selected' !!} @endif @endif>{!! $csn->currency_name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <?php
                            $conv = empty($default_currency) ? old('conversion') : $default_currency->conversion;
                            ?>
                            <div class="form-group col-md-6 col-sm-6" style="display: none;"><label class="control-label col-md-5 no-padding-right" for="stall_id">Conversion :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('conversion', $conv, array('id'=> 'conversion', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6" style="display: none;"><label class="control-label col-md-5 no-padding-right" for="stall_id">Unit Price :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('unit_price', old('unit_price'), array('id'=> 'unit_price', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6" style="display: none;"><label class="control-label col-md-5 no-padding-right" for="stall_id">Discounted Price :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('discounted_price', old('discounted_price'), array('id'=> 'discounted_price', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Source Of Supply :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('source_of_supply', old('source_of_supply'), array('id'=> 'source_of_supply', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Item DENO :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                <!-- {!!  Form::text('item_deno', old('item_deno'), array('id'=> 'item_deno', 'class' => 'form-control')) !!} -->
                                    <select class="form-control selectpicker" name="item_deno" id="item_deno"  data-live-search="true">
                                        <option value="">{!! '- Select -' !!}</option>
                                        @foreach($denos as $dns)
                                            <option value="{!! $dns->id !!}" @if($editId->item_deno == $dns->id) {{"selected"}} @endif>{!! $dns->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="status">Item Category :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <select class="form-control selectpicker" name="item_cat_id" id="item_cat_id"  data-live-search="true">
                                        <option value="">{!! '- Select -' !!}</option>
                                        @foreach($supplyCategories as $sc)
                                        @if($sc->name != "DNS ITEMS" && $sc->name != "DNST ITEMS" && $sc->name != "DTS ITEMS" && $sc->name != "QP" && $sc->name != "General")
                                            <option value="{!! $sc->id !!}" @if($editId->item_cat_id == $sc->id) {{"selected"}} @endif>{!! $sc->name !!}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                              <div class="form-group col-md-6 col-sm-6">
                                <label for="permanent_or_waste_content1" class="control-label col-md-5 no-padding-right">Item Acct Status <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <select class="form-control selectpicker" name="item_type" id="item_acct_status">
                                        <option value="">{!! '- Select -' !!}</option>
                                        <option value="1" @if($editId->item_type == 1) {{"selected"}} @endif>Permanent</option>
                                        <option value="3" @if($editId->item_type == 3) {{"selected"}} @endif>Quasi Permanent</option>
                                        <option value="2" @if($editId->item_type == 2) {{"selected"}} @endif>Consumable</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Item Picture :</label>
                                <div class="col-md-7">
                                    {!!  Form::file('item_picture', old('item_picture'), array('id'=> 'item_picture', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Item Specification(Upload PDF/JPEG File):</label>
                            <div class="col-md-7">
                                {!!  Form::file('item_specification', old('item_specification'), array('id'=> 'item_specification', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="stall_id">Organization :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
	                                <?php $selectedNsd = explode(',',$editId->nsd_id); ?>
                                    <select class="form-control selectpicker" name="nsd_id[]" id="nsd_id"  data-live-search="true" multiple="multiple">
                                        <option value="" disabled>{!! '- Select -' !!}</option>
                                        @foreach($nsdNames as $nn)
                                            <option value="{!! $nn->id !!}" @foreach($selectedNsd as $sn) @if( $nn->id==$sn) {!! 'selected' !!} @endif @endforeach>{!! $nn->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                          

                            <div class="form-group col-md-6 col-sm-6">
                                <label for="permanent_or_waste_content1" class="control-label col-md-5 no-padding-right">Item's Type</label>
                                <div class="col-md-7">
                                    <select class="form-control selectpicker" name="item_type_r" id="item_type" required>
                                        <option value="">{!! '- Select -' !!}</option>
                                        <option value="1" @if($editId->item_type_r == 1) {{"selected"}} @endif>Spare Parts</option>
                                        <option value="2" @if($editId->item_type_r == 2) {{"selected"}} @endif>Component</option>
                                        <option value="3" @if($editId->item_type_r == 3) {{"selected"}} @endif>Assembly</option>
                                        <option value="4" @if($editId->item_type_r == 4) {{"selected"}} @endif>Main Equipment</option>
                                        <option value="5" @if($editId->item_type_r == 5) {{"selected"}} @endif>Other</option>
                                    </select>
                                </div>
                            </div>

                            


                            <div class="form-group col-md-6 col-sm-6" style="display: none;"><label class="control-label col-md-5 no-padding-right" for="stall_id">Budget Code :</label>
                                <div class="col-md-7">
                                    <select class="form-control selectpicker" name="budget_code" id="budget_code"  data-live-search="true">
                                        <option value="">{!! '- Select -' !!}</option>
                                        @foreach($budget_codes as $bc)
                                            <option value="{!! $bc->id !!}"  @if($editId->budget_code == $bc->id) {{"selected"}} @endif>{!! $bc->code !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-sm-6"><label class="control-label col-md-5 no-padding-right" for="status">Status :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {{ Form::select('status', array('1' => trans('english.ACTIVE'), '2' => trans('english.INACTIVE')), old('status'), array('class' => 'form-control selectpicker', 'id' => 'status')) }}
                                </div>
                            </div>
                        </div>
                        
                       
                        <div class="form-group">
                            <div class="col-md-7 col-sm-offset-5">
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

            $(document).on('change','#currency_name',function(){
                var value = $('option:selected', this).attr('conversionrate');
                $("#conversion").val(value);
            });

        });
    </script>


@stop


