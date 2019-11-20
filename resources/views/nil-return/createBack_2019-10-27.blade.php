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
                    Nil Return
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
                        Nil Return create
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'nil-return-store', 'files'=> true, 'class' => 'tender', 'id'=>'tender')) }}

                        <input type="hidden" name="nil_id" value="{!! $nilId !!}">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="requester">Nil Return Date:<span class="text-danger">*</span></label>
                                    {!!  Form::text('nil_date', old('tender_title'), array('id'=> 'nil_date', 'class' => 'form-control datapicker2','readonly')) !!}
                                </div>
                            </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="requester">Nil Return Number:<span class="text-danger">*</span></label>
                                {!!  Form::text('nil_number', old('tender_title'), array('id'=> 'nil_number', 'class' => 'form-control','required')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="requester">Distribution:<span class="text-danger"></span></label>
                                {!!  Form::textarea('distribution', old('distribution'), array('id'=> 'distribution', 'class' => 'form-control','cols' => 25,'rows' => 5)) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="requester">Ext:<span class="text-danger"></span></label>
                                {!!  Form::textarea('ext', old('ext'), array('id'=> 'ext', 'class' => 'form-control','cols' => 25,'rows' => 5)) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="requester">Action:<span class="text-danger"></span></label>
                                {!!  Form::textarea('action', old('action'), array('id'=> 'action', 'class' => 'form-control','cols' => 25,'rows' => 5)) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="requester">Info:<span class="text-danger"></span></label>
                                {!!  Form::textarea('info', old('info'), array('id'=> 'info', 'class' => 'form-control','cols' => 25,'rows' => 5)) !!}
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


