@extends('layouts.default')
@section('content')

<div class="small-header transition animated fadeIn">
    <div class="hpanel">
        <div class="panel-body">
            <h2 class="font-light m-b-xs">
               Module Management
            </h2>
        </div>
    </div>
</div>

<div class="content animate-panel">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" style="">
            <div class="hpanel">
                <div class="panel-heading sub-title">
                   Update Module
                </div>
                <div class="panel-body">
                    {!! Form::model($target, array('route' => array('module.update', $target->id), 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'userId')) !!}                     
                    
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">{!!trans('english.NAME')!!} :<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {!! Form::text('name', old('name'), array('id'=> 'name', 'class' => 'form-control', 'required' => 'true')) !!}
                            <span class="form-error text-danger">{!! $errors->first('name') !!}</span>
                        </div>
                    </div>
                     <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="description">{!!trans('english.description')!!} :</label>
                        <div class="col-md-6">
                            {!! Form::textarea('description', old('description'), array('id'=> 'description', 'rows' => '3', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Activities:<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            @foreach($activities as $activity)
                                <input type="checkbox" name="activity_id[]" value="{!! $activity->id !!}" id="activity_id{!! $activity->id !!}" @foreach($modules_activities as $modules_activity) @if($modules_activity->activity_id==$activity->id) checked @endif @endforeach  >
                                <label for="activity_id{!! $activity->id !!}">{!! $activity->name !!}</label>
                                <br>
                            @endforeach
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary">{!!trans('english.SAVE')!!}</button>
                            <button type="button" class="btn btn-default cancel">{!!trans('english.CANCEL')!!}</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@stop
