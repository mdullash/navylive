@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Currency Setup</h3>
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
                        <?php if(!empty(Session::get('acl')[31][2])){ ?>
                        <div class="pull-right">
                            <a class="btn btn-info btn-effect-ripple" href="{{ URL::to('currency/create') }}"><i class="fa fa-plus"></i> Add Currency</a>
                        </div>
                        <?php } ?>
                            <h3>Currency Setup</h3>
                    </div>
                        <div class="panel-body">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Name'}}</th>
                                    <th class="text-center">{{'Symbol'}}</th>
                                    <th class="text-center" width="">{{'Conversion'}}</th>
                                    <th class="text-center" width="">{{'Default'}}</th>
                                    <th class="text-center">{{trans('english.STATUS')}}</th>
                                    <?php if(!empty(Session::get('acl')[31][3]) || !empty(Session::get('acl')[32][4])){ ?>
                                    <th class="text-center"> {!!trans('english.ACTION')!!}
                                    </th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>

                                @if (!$currencies->isEmpty())

                                    <?php
                                        $page = \Input::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page-1)*10;
                                        $l = 1;
                                    ?>
                                    @foreach($currencies as $cvs)
                                        <tr>
                                            <td>{{++$sl}}</td>
                                            <td>{{$cvs->currency_name}}</td>
                                            <td>{{$cvs->symbol}}</td>
                                            <td>{{$cvs->conversion}}</td>
                                            <td>
                                                @if($cvs->default_currency==1)
                                                    {!! 'Yes' !!}
                                                @else
                                                    {!! 'No' !!}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($cvs->status == '1')
                                                    <span class="label label-success">{{trans('english.ACTIVE')}}</span>
                                                @else
                                                    <span class="label label-warning">{{trans('english.INACTIVE')}}</span>
                                                @endif
                                            </td>
                                            <?php if(!empty(Session::get('acl')[31][3]) || !empty(Session::get('acl')[31][4])){ ?>
                                            <td class="action-center">
                                                <div class="text-center">
                                                    @if($cvs->default_currency==null)
                                                        <a class="btn btn-info btn-xs" href="{{ URL::to('currency/make-default/' . $cvs->id ) }}" title="Make it default">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                    @else
                                                        <a class="btn btn-warning btn-xs" href="javascript:void(0)" title="Default currency">
                                                            <i class="fa fa-ban"></i>
                                                        </a>
                                                    @endif

                                                    <?php if(!empty(Session::get('acl')[31][3])){ ?>
                                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('currency/edit/' . $cvs->id ) }}">
                                                        <i class="icon-edit"></i>
                                                    </a>
                                                    <?php } ?>

                                                    <?php if(!empty(Session::get('acl')[31][4])){?>
                                                    <button class="exbtovdelete btn btn-danger btn-xs" id="{{$cvs->id}}" type="button" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                                        <i class='fa fa-trash'></i>
                                                    </button>
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

                            {{ $currencies->links()}}

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
                var url='{!! URL::to('currency/destroy') !!}'+'/'+id;
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