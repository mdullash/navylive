@extends('layouts.default')
@section('content')
<div class="small-header transition animated fadeIn">
    <div class="hpanel">
        <div class="panel-body">
            <h2 class="font-light m-b-xs">
                 {!!trans('english.ROLE_MANAGEMENT')!!}
            </h2>
        </div>
    </div>
</div>

<div class="content animate-panel">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" style="">
            <div class="hpanel">
                <div class="panel-heading sub-title">
                    {!!trans('english.CREATE_NEW_ROLE')!!}
                </div>
                <div class="panel-body">
                    {!!   Form::open(array('role' => 'form', 'url' => 'role', 'files'=> true, 'class' => 'form-horizontal')) !!}
                   
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">{!!trans('english.NAME')!!} :<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {!!  Form::text('name', old('name'), array('id'=> 'name', 'class' => 'form-control', 'required' => 'true')) !!}
                        </div>
                    </div>
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="info">{!!trans('english.INFO')!!} :</label>
                        <div class="col-md-6">
                            {!!  Form::textarea('info', old('info'), array('id'=> 'info', 'rows' => '3', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">{!! 'Priority' !!} :<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {!!  Form::text('priority', old('priority'), array('id'=> 'priority', 'class' => 'form-control', 'required' => 'true')) !!}
                        </div>
                    </div>
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="status-id">{!!trans('english.STATUS')!!} :</label>
                        <div class="col-md-6">
                            {!!  Form::select('status_id', array('1' => 'Active', '2' => 'Inactive'), old('status_id'), array('class' => 'selectpicker form-control', 'id' => 'status-id'))!!}
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary">{!!trans('english.SAVE')!!}</button>
                            <button type="button" class="btn btn-default cancel">{!!trans('english.CANCEL')!!}</button>
                        </div>
                    </div>
                    {!!   Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@stop

