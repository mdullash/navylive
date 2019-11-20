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
                    Create Suppliers
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
                        Create Suppliers
                    </div>
                    <div class="panel-body">

                    {{ Form::model($editId, array('route' => array('item.update', $editId->id), 'method' => 'PUT', 'files'=> true, 'class' => 'form form-horizontal validate-form suppliers', 'id' => 'suppliers')) }}
                    
                        <div class="row">
                        <div class="col-sm-6 col-md-6">

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">IMC Number :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('imc_number', old('imc_number'), array('id'=> 'imc_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Model Number :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('model_number', old('model_number'), array('id'=> 'model_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Unit Price :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('unit_price', old('unit_price'), array('id'=> 'unit_price', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Item DENO :</label>
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

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Source Of Supply :<span class="text-danger"></span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('source_of_supply', old('source_of_supply'), array('id'=> 'source_of_supply', 'class' => 'form-control')) !!}
                                </div>
                            </div>


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

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Discounted Price :<span class="text-danger"></span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('discounted_price', old('discounted_price'), array('id'=> 'discounted_price', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Manufacturing Country :<span class="text-danger"></span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('manufacturing_country', old('manufacturing_country'), array('id'=> 'manufacturing_country', 'class' => 'form-control')) !!}
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

            

        });
    </script>

@stop


