@extends('layouts.default')
@section('content')

<div class="small-header transition animated fadeIn">
    <div class="hpanel">
        <div class="panel-body">
            <h2 class="font-light m-b-xs">
                System Settings
            </h2>
        </div>
    </div>
</div>

<div class="content animate-panel">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" style="">
            <div class="hpanel">
                <div class="panel-heading sub-title">
                  Update System Settings
                </div>
                @include('layouts.flash')

                <div class="panel-body">
                    {!! Form::model($target, array('route' => array('system.update'), 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'userId','files'=> true)) !!}
                    
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="site_title">Site Title :<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {!! Form::text('site_title', old('site_title'), array('id'=> 'site_title', 'class' => 'form-control', 'required' => 'true')) !!}
                            <span class="form-error text-danger">{!! $errors->first('site_title') !!}</span>
                        </div>
                    </div>

                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="tag_line">Tag Line :</label>
                        <div class="col-md-6">
                            {!! Form::text('tag_line',old('tag_line'), array('id'=> 'tag_line', 'class' => 'form-control', 'required' => 'true')) !!}
                            <span class="form-error text-danger">{!! $errors->first('tag_line') !!}</span>
                        </div>
                    </div>
                     <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="site_description">Meta Description:<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {!! Form::textarea('site_description', old('site_description'), array('id'=> 'site_description', 'rows' => '3', 'class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Email :<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {!! Form::email('email',old('email'), array('id'=> 'email', 'class' => 'form-control', 'required' => 'true')) !!}
                            <span class="form-error text-danger">{!! $errors->first('email') !!}</span>
                        </div>
                    </div>



                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="phone">Phone:<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {!! Form::text('phone',old('phone'), array('id'=> 'phone', 'class' => 'form-control', 'required' => 'true')) !!}
                            <span class="form-error text-danger">{!! $errors->first('phone') !!}</span>
                        </div>
                    </div>
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="phone">Location:<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {!! Form::textarea('location',old('location'), array('id'=> 'location', 'class' => 'form-control',  'rows' => '3')) !!}
                            <span class="form-error text-danger">{!! $errors->first('location') !!}</span>
                        </div>
                    </div>

                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="logo">Site Logo :<span class="text-danger">*</span></label>
                        <div class="col-md-6">

                            @if($target->logo != null)
                                <img src="{!! asset($target->logo) !!}" alt="" style="height:100px;width:100px;"><br><br>
                            @endif
                            {!! Form::file('logo',old('logo'), array('id'=> 'logo', 'class' => 'form-control', 'required' => 'true')) !!}
                            <span class="form-error text-danger">{!! $errors->first('logo') !!}</span>
                        </div>
                    </div>

                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="favicon">Favicon :<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            @if($target->favicon != null)
                                <img src="{!! asset($target->favicon) !!}" alt="" style="height:50px;width:50px;"><br><br>
                            @endif
                            {!! Form::file('favicon',old('favicon'), array('id'=> 'favicon', 'class' => 'form-control', 'required' => 'true')) !!}
                            <span class="form-error text-danger">{!! $errors->first('favicon') !!}</span>
                        </div>
                    </div>

                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="copyRight">Copy Right :<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {!! Form::text('copy_right', old('copy_right'), array('id'=> 'copyright', 'class' => 'form-control', 'required' => 'true')) !!}
                            <span class="form-error text-danger">{!! $errors->first('copy_right') !!}</span>
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

