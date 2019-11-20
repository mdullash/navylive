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
                    Organization
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
                        Create Organization
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'reg_nsd/registred_nsd_name', 'files'=> true, 'class' => 'form-horizontal registred_nsd_name', 'id'=>'registred_nsd_name')) }}


                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Organization:<span class="text-danger">*</span></label>
                            <div class="col-md-5">
                                {!!  Form::text('name', old('name'), array('id'=> 'name', 'class' => 'form-control', 'required')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Alise :<span class="text-danger">*</span></label>
                            <div class="col-md-5">
                                {!!  Form::text('alise', old('alise'), array('id'=> 'alise', 'class' => 'form-control','readonly')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Zone :<span class="text-danger">*</span></label>
                            <div class="col-md-5">
                                <select class="form-control selectpicker" name="zones[]" id="zones"  data-live-search="true" multiple="multiple">
                                    <option value="" disabled>{!! '- Select -' !!}</option>
                                    @foreach($zones as $zn)
                                        <option value="{!! $zn->id !!}">{!! $zn->name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Purchase Limit :</label>
                            <div class="col-md-5">
                                {!!  Form::number('purchase_limit', old('purchase_limit'), array('id'=> 'purchase_limit', 'class' => 'form-control', 'min'=>0)) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Direct Purchase Limit :</label>
                            <div class="col-md-5">
                                {!!  Form::number('direct_purchase_limit', old('direct_purchase_limit'), array('id'=> 'direct_purchase_limit', 'class' => 'form-control', 'min'=>0)) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">External Link :</label>
                            <div class="col-md-5">
                                {!!  Form::url('external_link', old('external_link'), array('id'=> 'external_link', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Description :</label>
                            <div class="col-md-5">
                                {!!  Form::textarea('description', old('description'), array('id'=> 'description', 'rows' => '3', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Image :</label>
                            <div class="col-md-5">
                                {!!  Form::file('image', array('id'=> 'image', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="status">Status :<span class="text-danger">*</span></label>
                            <div class="col-md-5">
                                {{ Form::select('status', array('1' => trans('english.ACTIVE'), '2' => trans('english.INACTIVE')), old('status'), array('class' => 'form-control selectpicker', 'id' => 'status')) }}
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <div class="col-md-7 col-sm-offset-2">
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

            $(document).on('input','#name',function(){
                var values = $('#name').val();
                    values = values.replace(/\s+/g, '_').toLowerCase();
                $('#alise').val(values);
            })

        });
    </script>

@stop


