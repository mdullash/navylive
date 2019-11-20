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
                    Update Tender
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
                        Update Tender
                    </div>
                    <div class="panel-body">

                    {{ Form::model($editId, array('route' => array('tender.update', $editId->id), 'method' => 'PUT', 'files'=> true, 'class' => 'form validate-form tender', 'id' => 'tender')) }}

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Tender Title :<span class="text-danger">*</span></label>
                                    {!!  Form::text('tender_title', old('tender_title'), array('id'=> 'tender_title', 'class' => 'form-control')) !!}
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Tender Number :<span class="text-danger">*</span></label>
                                    {!!  Form::text('tender_number', old('tender_number'), array('id'=> 'tender_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Demand Number :<span class="text-danger">*</span></label>
                                    {!!  Form::text('ref_tender_id', old('ref_tender_id'), array('id'=> 'ref_tender_id', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Approval Letter Number :</label>
                                    {!!  Form::text('approval_letter_number', old('approval_letter_number'), array('id'=> 'approval_letter_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label " for="valid_date_from">Tender Publishing Date :<span class="text-danger">*</span></label>
                                    {!!  Form::text('valid_date_from', old('valid_date_from'), array('id'=> 'valid_date_from', 'class' => 'form-control datapicker2','readonly')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Tender Opening Date :<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control datapicker2" name="tender_opening_date" id="tender_opening_date" value="{!! date('Y-m-d',strtotime($editId->tender_opening_date)) !!}">
                                </div>
                            </div>

                            <!-- <div class="col-md-4">
                                <div class="form-group"><label class="control-label " for="valid_date_to">Valid Date To :<span class="text-danger">*</span></label>
                                    {!!  Form::text('valid_date_to', old('valid_date_to'), array('id'=> 'valid_date_to', 'class' => 'form-control datapicker2','readonly')) !!}
                                </div>
                            </div> -->

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="tender_type">Tender Type :<span class="text-danger">*</span></label>
                                    {{ Form::select('tender_type', array('' => '- Select -', '1' => 'LTM- Limited Tender Method', '2' => 'OTM- Open Tender Method', '3' => 'RTM- Restricted Tender Method'), old('tender_type'), array('class' => 'form-control selectpicker', 'id' => 'tender_type')) }}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="tender_priority">Tender Priority :<span class="text-danger">*</span></label>
                                    {{ Form::select('tender_priority', array('' => '- Select -', '1' => 'Normal', '2' => 'Immediate', '3' => 'OPS Immediate (Operational Immediate)'), old('tender_priority'), array('class' => 'form-control selectpicker', 'id' => 'tender_priority')) }}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="tender_nature">Tender Nature :<span class="text-danger">*</span></label>
                                    {{ Form::select('tender_nature', array('' => '- Select -', '1' => 'Line Item', '2' => 'Lot Item'), old('tender_nature'), array('class' => 'form-control selectpicker', 'id' => 'tender_nature')) }}
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="status">Tender Category :<span class="text-danger">*</span></label>
                                    <select class="form-control selectpicker" name="tender_cat_id" id="tender_cat_id"  data-live-search="true">
                                        <option value="">{!! '- Select -' !!}</option>
                                        @foreach($supplyCategories as $sc)
                                            <option value="{!! $sc->id !!}" @if($editId->tender_cat_id==$sc->id) {{'selected'}} @endif>{!! $sc->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Organization:<span class="text-danger">*</span></label>
                                    <select class="form-control selectpicker" name="nsd_id" id="nsd_id"  data-live-search="true">
                                        <option value="">{!! '- Select -' !!}</option>
                                        @foreach($nsdNames as $nn)
                                            <option value="{!! $nn->id !!}" @if($editId->nsd_id==$nn->id) {{'selected'}} @endif>{!! $nn->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                                
                            <div class="col-md-12">    
                                <div class="col-md-4">
                                    <div class="form-group"><label class="control-label" for="stall_id">Tender Description :</label>
                                        {!!  Form::textarea('tender_description', old('tender_description'), array('id'=> 'tender_description', 'rows' => '3', 'class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group"><label class="control-label" for="stall_id">Letter Body :</label>
                                        {!!  Form::textarea('letter_body', old('letter_body'), array('id'=> 'letter_body', 'rows' => '3', 'class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group"><label class="control-label" for="stall_id">Remarks :</label>
                                        {!!  Form::textarea('remarks', old('remarks'), array('id'=> 'remarks', 'rows' => '3', 'class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Specification :</label>
                                    {!!  Form::file('specification[]', array('id'=> 'specification', 'class' => 'form-control', 'multiple', 'accept' => '.pdf,.doc,.docx')) !!}
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Notice PDF :</label>
                                    {!!  Form::file('notice', array('id'=> 'notice', 'class' => 'form-control', 'accept' => '.pdf')) !!}
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="status">Status :<span class="text-danger">*</span></label>
                                    {{ Form::select('status', array('1' => 'Published', '2' => 'Unpublished'), $editId->status_id, array('class' => 'form-control selectpicker', 'id' => 'status')) }}
                                </div>
                            </div>

                            <!-- <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="open_tender"></label>
                                    <div class="checkbox checkbox-success">
                                        <input class="activity_1 activitycell" type="checkbox" id="open_tender" name="open_tender" value="1" @if($editId->open_tender==1) checked="true" @endif>
                                        <label for="open_tender">Open Tender</label>
                                    </div>
                                </div>
                            </div> -->
                        
                       
                            <div class="form-group">
                                <div class="col-md-11 col-sm-offset-1">
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" style="padding-right: 5px;">{!!trans('english.CANCEL')!!}</a>
                                    
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


