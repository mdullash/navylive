@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Evaluation Point Table</h3>
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
                        <?php if(!empty(Session::get('acl')[39][2])){ ?>
                        <div class="pull-right">
                            <a class="btn btn-info btn-effect-ripple" href="{{ URL::to('evaluation-point/create') }}"><i class="fa fa-plus"></i> Add Evaluation Point</a>
                        </div>
                        <?php } ?>
                            <h3>Evaluation Point Table</h3>
                    </div>
                        <div class="panel-body">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Title'}}</th>
                                    <th class="text-center">{{'Lower Point Limit'}}</th>
                                    <th class="text-center">{{'Upper Point Limit'}}</th>
                                    <th class="text-center">{{'Description'}}</th>
                                    <th class="text-center">{{'Zones'}}</th>
                                    <th class="text-center">{{trans('english.STATUS')}}</th>
                                    <?php if(!empty(Session::get('acl')[39][3]) || !empty(Session::get('acl')[39][4])){ ?>
                                    <th class="text-center"> {!!trans('english.ACTION')!!}
                                    </th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>

                                @if (!$evaluPointTable->isEmpty())

                                    <?php
                                        $page = \Input::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page-1)*10;
                                        $l = 1;
                                    ?>
                                    @foreach($evaluPointTable as $ctd)
                                        <tr>
                                            <td>{{++$sl}}</td>
                                            <td>{!! $ctd->title !!}</td>
                                            <td>{!! $ctd->lower_point_limit !!}</td>
                                            <td>{!! $ctd->higher_point_limit !!}</td>
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
                                            <td class="text-center">
                                                @if ($ctd->status == '1')
                                                    <span class="label label-success">{{trans('english.ACTIVE')}}</span>
                                                @else
                                                    <span class="label label-warning">{{trans('english.INACTIVE')}}</span>
                                                @endif
                                            </td>
                                            <?php if(!empty(Session::get('acl')[39][3]) || !empty(Session::get('acl')[39][4])){ ?>
                                            <td class="action-center">
                                                <div class="text-center">

                                                    <?php if(!empty(Session::get('acl')[39][4]) || !empty(Session::get('acl')[29][3])){?>
                                                        {!! Form::open(array('url' => 'evaluation-point/' . $ctd->id)) !!}
                                                        {!! Form::hidden('_method', 'DELETE') !!}

                                                        <?php if(!empty(Session::get('acl')[39][3])){ ?>
                                                        <a class="btn btn-primary btn-xs" href="{{ URL::to('evaluation-point/' . $ctd->id.'/edit' ) }}">
                                                            <i class="icon-edit"></i>
                                                        </a>
	                                                    <?php } ?>

														<?php if(!empty(Session::get('acl')[39][4])){ ?>
                                                            {{ Form::open([ 'method'  => 'delete', 'route' => [ 'evaluation-point.destroy', $ctd->id ] ]) }}
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
                                        <td colspan="8">{{'Empty Data'}}</td>
                                    </tr>
                                @endif
                                    
                                </tbody>
                            </table><!---/table-responsive-->

                            {{ $evaluPointTable->links()}}

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