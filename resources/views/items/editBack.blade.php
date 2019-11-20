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
                        <div class="col-sm-6 col-md-6">

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">IMC Number :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('imc_number', old('imc_number'), array('id'=> 'imc_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Model Number :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('model_number', old('model_number'), array('id'=> 'model_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="status">Currency Name :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <select class="form-control selectpicker" name="currency_name" id="currency_name"  data-live-search="true">
                                        <option value="" conversionrate="0">{!! '- Select -' !!}</option>
                                        @foreach($currencies_names as $csn)
                                            <option value="{!! $csn->id !!}" conversionrate="{!! $csn->conversion !!}"  @if($csn->id==$editId->currency_name) {{'selected'}} @endif @if(!empty($default_currency) && empty($editId->currency_name)) @if($default_currency->id==$csn->id) {!! 'selected' !!} @endif @endif>{!! $csn->currency_name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <?php
                                $conv = '';
                                if( !empty($editId->conversion) ){
                                    $conv = old('conversion');
                                }
                                if( !empty($default_currency) && empty($editId->currency_name) ){
                                    $conv = $default_currency->conversion;
                                }

                            ?>
                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Conversion :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('conversion', $conv, array('id'=> 'conversion', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Unit Price :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('unit_price', old('unit_price'), array('id'=> 'unit_price', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Discounted Price :<span class="text-danger"></span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('discounted_price', old('discounted_price'), array('id'=> 'discounted_price', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Source Of Supply :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('source_of_supply', old('source_of_supply'), array('id'=> 'source_of_supply', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            {{--<div class="col-md-12 sItemType">--}}
                            <div class="form-group">
                                <label for="permanent_or_waste_content1" class="control-label col-md-5 no-padding-right">Item Type</label>
                                <div class="radio radio-info" style="padding-top: 0px;">
                                    <span>
                                        <input class="activity_1 activitycell" type="radio" id="item_type1" name="item_type" value="1" @if($editId->item_type==1) {!! 'checked' !!} @endif>
                                        <label for="item_type1">Permanent</label>
                                    </span>

                                    <span style="margin-left: 25px;">
                                        <input class="activity_1 activitycell" type="radio" id="item_type2" name="item_type" value="3" @if($editId->item_type==3) {!! 'checked' !!} @endif>
                                        <label for="item_type2">Quasi Per.</label>
                                    </span>

                                    <span style="margin-left: 25px;">
                                        <input class="activity_1 activitycell" type="radio" id="item_type3" name="item_type" value="2" @if($editId->item_type==2) {!! 'checked' !!} @endif>
                                        <label for="item_type3">Consumable</label>
                                    </span>

                                </div>
                            </div>
                            {{--</div>--}}


                        </div>

                        <div class="col-sm-6 col-md-6">

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Item Name :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('item_name', old('item_name'), array('id'=> 'item_name', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="status">Item Category :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <select class="form-control selectpicker" name="item_cat_id" id="item_cat_id"  data-live-search="true">
                                        <option value="">{!! '- Select -' !!}</option>
                                        @foreach($supplyCategories as $sc)
                                            <option value="{!! $sc->id !!}" @if($editId->item_cat_id == $sc->id) {{'selected'}} @endif >{!! $sc->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Item DENO :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <!-- {!!  Form::text('item_deno', old('item_deno'), array('id'=> 'item_deno', 'class' => 'form-control')) !!} -->
                                    <select class="form-control selectpicker" name="item_deno" id="item_deno"  data-live-search="true">
                                        <option value="">{!! '- Select -' !!}</option>
                                        @foreach($denos as $dns)
                                            <option value="{!! $dns->id !!}" @if($dns->id==$editId->item_deno) {{'selected'}} @endif>{!! $dns->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Manufacturing Country :<span class="text-danger"></span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('manufacturing_country', old('manufacturing_country'), array('id'=> 'manufacturing_country', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Organization :<span class="text-danger">*</span></label>
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

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Budget Code :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <select class="form-control selectpicker" name="budget_code" id="budget_code"  data-live-search="true">
                                        <option value="">{!! '- Select -' !!}</option>
                                        @foreach($budget_codes as $bc)
                                            <option value="{!! $bc->id !!}" @if($bc->id==$editId->budget_code) {!! 'selected' !!} @endif>{!! $bc->code !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="status">Status :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {{ Form::select('status', array('1' => trans('english.ACTIVE'), '2' => trans('english.INACTIVE')), $editId->status_id, array('class' => 'form-control selectpicker', 'id' => 'status')) }}
                                </div>
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


