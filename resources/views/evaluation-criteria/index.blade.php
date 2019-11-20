@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Evaluation Criteria</h3>
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
                        <?php if(!empty(Session::get('acl')[41][2])){ ?>
                        <div class="pull-right">
                            <a class="btn btn-info btn-effect-ripple" href="{{ URL::to('evaluation-criteria/create') }}"><i class="fa fa-plus"></i> Add Evaluation Criteria</a>
                        </div>
                        <?php } ?>
                            <h3>Evaluation Criteria</h3>
                    </div>
                        <div class="panel-body">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Title'}}</th>
                                    <th class="text-center">{{'Position'}}</th>
                                    <th class="text-center">{{'Weight'}}</th>
                                    <th class="text-center">{{'Description'}}</th>
                                    <th class="text-center">{{'Zones'}}</th>
                                    <th class="text-center">{{'Evaluation Comment'}}</th>
                                    <th class="text-center">{{trans('english.STATUS')}}</th>
                                    <?php if(!empty(Session::get('acl')[41][3]) || !empty(Session::get('acl')[41][4])){ ?>
                                    <th class="text-center"> {!!trans('english.ACTION')!!}
                                    </th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>

                                @if (!$evaluationCriterias->isEmpty())

                                    <?php
                                        $page = \Input::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page-1)*10;
                                        $l = 1;
                                    ?>
                                    @foreach($evaluationCriterias as $ctd)
                                        <tr>
                                            <td>{{++$sl}}</td>
                                            <td>{!! $ctd->title !!}</td>
                                            <td>
                                                <?php
                                                    $zoneids = explode(',',$ctd->positions);
                                                    foreach ($zoneids as $zids) {
                                                        $valsss = \App\Http\Controllers\EvaluationCriteriaController::evaluationPositionName($zids);
                                                        echo "<li>".$valsss."</li>";
                                                    }
                                                ?>
                                            </td>
                                            <td>{!! $ctd->weight !!}</td>
                                            <td>{!! $ctd->description !!}</td>
                                            <td>
                                                <?php
                                                    $zoneids = explode(',',$ctd->zones);
                                                    foreach ($zoneids as $zids) {
                                                        $valsss = \App\Http\Controllers\RegistredNsdNameController::zone_name($zids);
                                                        echo "<li>".$valsss."</li>";
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                @if($ctd->comment==1)
                                                    Yes
                                                @else
                                                    No
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($ctd->status == '1')
                                                    <span class="label label-success">{{trans('english.ACTIVE')}}</span>
                                                @else
                                                    <span class="label label-warning">{{trans('english.INACTIVE')}}</span>
                                                @endif
                                            </td>
                                            <?php if(!empty(Session::get('acl')[41][3]) || !empty(Session::get('acl')[41][4])){ ?>
                                            <td class="action-center">
                                                <div class="text-center">

                                                    <?php if(!empty(Session::get('acl')[41][4]) || !empty(Session::get('acl')[29][3])){?>
                                                        {!! Form::open(array('url' => 'evaluation-criteria/' . $ctd->id)) !!}
                                                        {!! Form::hidden('_method', 'DELETE') !!}

                                                        <?php if(!empty(Session::get('acl')[41][3])){ ?>
                                                        <a class="btn btn-primary btn-xs" href="{{ URL::to('evaluation-criteria/' . $ctd->id.'/edit' ) }}">
                                                            <i class="icon-edit"></i>
                                                        </a>
	                                                    <?php } ?>

														<?php if(!empty(Session::get('acl')[41][4])){ ?>
                                                            {{ Form::open([ 'method'  => 'delete', 'route' => [ 'evaluation-criteria.destroy', $ctd->id ] ]) }}
                                                                <button class="btn btn-danger btn-xs delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            {{ Form::close() }}
                                                        <?php } ?>

                                                        {!!   Form::close() !!}
                                                   <?php }?>

                                                </div>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                    @endforeach
                                
                                @else
                                    <tr>
                                        <td colspan="7">{{'Empty Data'}}</td>
                                    </tr>
                                @endif
                                    
                                </tbody>
                            </table><!---/table-responsive-->

                            {{ $evaluationCriterias->links()}}

                        </div>
                    </div>
                </div>
            </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function(){

            /*For Delete Department*/
            $(".exbtovdelete").click(function (e) {
                e.preventDefault();
                
                var id = this.id; 
                var url='{!! URL::to('zone/destroy') !!}'+'/'+id;
                bootbox.confirm("Are you sure?", function (result) {
                    if (result) {
                        var _url = $("#_url").val();
                        window.location.href = url;
                    }
                });
            });

        });
    </script>
@stop