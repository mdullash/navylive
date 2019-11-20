@extends('layouts.default')
@section('content')
<div class="small-header transition animated fadeIn">
    <div class="hpanel">
        <div class="panel-body">
            <h2 class="font-light m-b-xs">
                {!!trans('english.ROLE_MANAGEMENT')!!}
            </h2>
        </div>
        @include('layouts.flash')
    </div>
</div>
<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading hbuilt">
                    <?php if(!empty(Session::get('acl')[1][2])){ ?>
                    <div class="pull-right">
                        <a class="btn btn-info btn-effect-ripple" href="{!! URL::to('role/create') !!}"><i class="fa fa-plus"></i> {!!trans('english.CREATE_NEW_ROLE')!!}</a>
                    </div>
                    <?php } ?>
                    <h4>{!!trans('english.ROLE_MANAGEMENT')!!}</h4>
                </div>
                <div class="panel-body">
                    {!!  Form::open(array('role' => 'form', 'url' => 'role/filter', 'class' => 'form-horizontal'))  !!}
                    <div class="form-group col-md-6">
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right">{!!trans('english.NAME')!!} :</label>
                            <div class="col-md-6">
                                {!!   Form::text('name', old('name'), array('id'=> 'name', 'class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-5">
                            <button type="submit" class="btn btn-primary"> {!!trans('english.FILTER')!!}</button>
                        </div>
                    </div>

                    {!! Form::close() !!}

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped middle-align">
                            <thead>
                                <tr class="center">
                                    <th>{!!trans('english.ID')!!}</th>
                                    <th>{!!trans('english.NAME')!!}</th>
                                    <th>{!!trans('english.INFO')!!}</th>
                                    <th>{!! 'Priority' !!}</th>
                                    <th class="text-center">{!!trans('english.STATUS')!!}</th>
                                     <?php if(!empty(Session::get('acl')[1][3]) || !empty(Session::get('acl')[1][4])){ ?>
                                    <th class="text-center">{!!trans('english.ACTION')!!}</th>
                                     <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$targetArr->isEmpty())

                                @foreach($targetArr as $value)

                                <tr class="contain-center">
                                    <td>{!! $value->id !!}</td>
                                    <td>{!! $value->name !!}</td>
                                    <td>{!! $value->info !!}</td>
                                    <td>{!! $value->priority !!}</td>
                                    <td class="text-center">
                                        @if ($value->status_id == '1')
                                        <span class="label label-success">{!!trans('english.ACTIVE')!!}</span>
                                        @else
                                        <span class="label label-warning">{!!trans('english.INACTIVE')!!}</span>
                                        @endif
                                    </td>
                                    <?php if(!empty(Session::get('acl')[1][3]) || !empty(Session::get('acl')[1][4])){ ?>
                                    <td class="action-center">
                                        <div class="text-center">
                                            {!! Form::open(array('url' => 'role/' . $value->id)) !!}
                                            {!! Form::hidden('_method', 'DELETE') !!}


                                            <?php if(!empty(Session::get('acl')[1][3])){ ?>
                                            <a class="btn btn-primary btn-xs" href="{!! URL::to('role/' . $value->id . '/edit') !!}">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <?php } ?>
                                            <?php if(!empty(Session::get('acl')[1][4])){ ?>
                                            <button class="btn btn-danger btn-xs delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <?php } ?>
                                            {!!   Form::close() !!}
                                        </div>
                                    </td>
                                    <?php } ?>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5">{!!trans('english.EMPTY_DATA')!!}</td>
                                </tr>
                                @endif 
                            </tbody>
                        </table><!---/table-responsive-->
                        {!! $targetArr->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop