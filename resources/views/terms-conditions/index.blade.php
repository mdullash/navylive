@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Terms and Condition</h3>
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
                        <?php if(!empty(Session::get('acl')[29][2])){ ?>
                        <div class="pull-right">
                            <a class="btn btn-info btn-effect-ripple" href="{{ URL::to('terms-conditions/create') }}"><i class="fa fa-plus"></i> Add Terms and Condition</a>
                        </div>
                        <?php } ?>
                            <h3>Terms and Condition List</h3>
                    </div>
                        <div class="panel-body">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Title'}}</th>
                                    <th class="text-center">{{'Category Name'}}</th>
                                    {{--<th class="text-center">{{'Organization'}}</th>--}}
                                    {{--<th class="text-center" width="">{{'Description'}}</th>--}}
                                    <th class="text-center">{{trans('english.STATUS')}}</th>
                                    <?php if(!empty(Session::get('acl')[29][3]) || !empty(Session::get('acl')[29][4])){ ?>
                                    <th class="text-center"> {!!trans('english.ACTION')!!}
                                    </th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>

                                @if (!$terms->isEmpty())

                                    <?php
                                        $page = \Input::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page-1)*10;
                                        $l = 1;
                                    ?>
                                    @foreach($terms as $ctd)
                                        <tr>
                                            <td>{{++$sl}}</td>
                                            {{--<td>{{$ctd->zoneName->name}}</td>--}}
                                            {{--<td>{{$ctd->navalLOcationName->name}}</td>--}}
                                            <td>{!! $ctd->title !!}</td>
                                            <td>
                                                @if(!empty($ctd->category->name))
                                                {!! $ctd->category->name !!}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($ctd->status == '1')
                                                    <span class="label label-success">{{trans('english.ACTIVE')}}</span>
                                                @else
                                                    <span class="label label-warning">{{trans('english.INACTIVE')}}</span>
                                                @endif
                                            </td>
                                            <?php if(!empty(Session::get('acl')[29][3]) || !empty(Session::get('acl')[29][4])){ ?>
                                            <td class="action-center">
                                                <div class="text-center">

                                                    <?php if(!empty(Session::get('acl')[29][4]) || !empty(Session::get('acl')[29][3])){?>
                                                        {!! Form::open(array('url' => 'terms-conditions/' . $ctd->id)) !!}
                                                        {!! Form::hidden('_method', 'DELETE') !!}

                                                        <?php if(!empty(Session::get('acl')[29][3])){ ?>
                                                        <a class="btn btn-primary btn-xs" href="{{ URL::to('terms-conditions/' . $ctd->id.'/edit' ) }}">
                                                            <i class="icon-edit"></i>
                                                        </a>
	                                                    <?php } ?>

														<?php if(!empty(Session::get('acl')[29][4])){ ?>
                                                        <button class="btn btn-danger btn-xs delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
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
                                        <td colspan="6">{{'Empty Data'}}</td>
                                    </tr>
                                @endif
                                    
                                </tbody>
                            </table><!---/table-responsive-->

                            {{ $terms->links()}}

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