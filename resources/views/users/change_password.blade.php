@extends('layouts.default')
@section('content')
<div class="small-header transition animated fadeIn">
    <div class="hpanel">
        <div class="panel-body">
            <h2 class="font-light m-b-xs">
                {!!trans('english.CHANGE_PASSWORD')!!}
            </h2>
        </div>
    </div>
</div>

<div class="content animate-panel">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" style="">
            <div class="hpanel">
                <div class="panel-heading sub-title">
                    {!!trans('english.CHANGE_PASSWORD')!!}
                </div>
                <div class="panel-body">
                    {!! Form::open(array('role' => 'form', 'url' => 'users/pup', 'files'=> true, 'class' => 'form-horizontal validate-form')) !!}	                   
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="UserPassword">{!!trans('english.PASSWORD')!!} :<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {!! Form::password('password', array('id'=> 'UserPassword', 'class' => 'form-control', 'required' => 'true')) !!}
                        </div>
                    </div>
                    
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="UserConfirmPassword">{!!trans('english.CONFIRM_PASSWORD')!!} :<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {!! Form::password('password_confirmation', array('id'=> 'UserConfirmPassword', 'class' => 'form-control','required' => 'true')) !!}
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary">{!!trans('english.SAVE')!!}</button>
                            <button type="button" class="btn btn-default cancel">{!!trans('english.CANCEL')!!}</button>
                        </div>
                    </div>
                    {!! Form::hidden('user_id', $user_id) !!}
                    {!! Form::hidden('next_url', $next_url) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

