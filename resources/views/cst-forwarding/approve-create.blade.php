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
                    Cst Forwarding
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
                       CST Forwarding Approved
                    </div>
                    <?php
                        $cstForwardingType = [
	                        ''=>'Select CST Forwarding Type',
	                        '1'=>'CST Forwarding Line Item',
	                        '2'=>'CST Forwarding Lot Item',
	                        '3'=>'Single Quotation Forwarding'
                        ];
                    ?>
                    <div class="panel-body">
                        {{ Form::model($cstForwarding, array('url' => '/cst-forwarding/approve-store', 'files'=> true, 'class' => 'tender', 'id' => 'tender')) }}

                        <input type="hidden" value="{{$cstForwarding->id}}" name="id">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cst_forwarding_type">CST Forwarding Type:<span class="text-danger">*</span></label>
                                {!!  Form::select('cst_forwarding_type',$cstForwardingType, old('cst_forwarding_type'), array('id'=> 'cst_forwarding_type', 'class' => 'form-control','required')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cst_forwarding_number">CST Forwarding Number:<span class="text-danger">*</span></label>
                                {!!  Form::text('cst_forwarding_number', old('cst_forwarding_number'), array('id'=> 'cst_forwarding_number', 'class' => 'form-control','required')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cst_forwarding_date">CST Forwarding Date:<span class="text-danger">*</span></label>
                                {!!  Form::text('cst_forwarding_date', old('cst_forwarding_date'), array('id'=> 'cst_forwarding_date', 'class' => 'form-control datapicker2','readonly')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="enclosure">Enclosure:<span class="text-danger"></span></label>
                                <textarea cols="25" rows="5" type="text" name="enclosure" class="form-control" id="enclosure">
                                    {{ $cstForwarding->enclosure }}
                                </textarea>
                            </div>
                        </div>

                        <div class="col-md-6" style="display: none;">
                            <div class="form-group">
                                <label for="distribution">Distribution:<span class="text-danger"></span></label>
                                {!!  Form::textarea('distribution', old('distribution'), array('id'=> 'distribution', 'class' => 'form-control','cols' => 25,'rows' => 5)) !!}
                            </div>
                        </div>

                        <div class="col-md-6" style="display: none;">
                            <div class="form-group">
                                <label for="external">External:<span class="text-danger"></span></label>
                                {!!  Form::textarea('external', old('external'), array('id'=> 'external', 'class' => 'form-control','cols' => 25,'rows' => 5)) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="action">Action:<span class="text-danger"></span></label>
                                {!!  Form::textarea('action', old('action'), array('id'=> 'action', 'class' => 'form-control','cols' => 25,'rows' => 5)) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="information">Information:<span class="text-danger"></span></label>
                                {!!  Form::textarea('information', old('information'), array('id'=> 'information', 'class' => 'form-control','cols' => 25,'rows' => 5)) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-11 col-sm-offset-1">
                                <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>

                                <button type="submit" class="btn btn-primary pull-right" id="submitButton">{!! 'Submit' !!}</button>

                            </div>
                        </div>

                        {!!   Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
  
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.js"></script>
@stop


