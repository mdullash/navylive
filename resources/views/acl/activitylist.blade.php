@extends('layouts.default')
@section('content')

<div class="small-header transition animated fadeIn">
    <div class="hpanel">
        <div class="panel-body">
            <h2 class="font-light m-b-xs">
                {!!trans('english.ACTIVITY_LIST')!!}
            </h2>
        </div>
    </div>
</div>

<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading hbuilt">                    
                    <h4>{!!trans('english.ACTIVITY_LIST')!!}</h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped middle-align">
                            <thead>
                                <tr class="center">
                                    <th>{!!trans('english.ACTIVITY_ID')!!} #</th>
                                    <th>{!!trans('english.NAME')!!}</th>
                                    <th>{!!trans('english.INFO')!!}</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach($targetArr as $value)

                                <tr class="contain-center">
                                    <td>{!! $value->id !!}</td>
                                    <td>{!! $value->name !!}</td>
                                    <td>{!! $value->description !!}</td>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table><!---/table-responsive-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop