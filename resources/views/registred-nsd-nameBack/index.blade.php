<?php
//use \App\Http\Controllers\RegistredNsdNameController;
?>
@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Organization</h3>
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
                        <?php if(!empty(Session::get('acl')[11][2])){ ?>
                        <div class="pull-right">
                            <a class="btn btn-info btn-effect-ripple" href="{{ URL::to('reg_nsd/registred_nsd_name/create') }}"><i class="fa fa-plus"></i> Add Organization</a>
                        </div>
                        <?php } ?>
                            <h3>Organization</h3>
                    </div>
                        <div class="panel-body">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Organization'}}</th>
                                    <th class="text-center">{{'Alise'}}</th>
                                    <th class="text-center">{{'Purchase Limit'}}</th>
                                    <th class="text-center">{{'Direct Purchase Limit'}}</th>
                                    <th class="text-center">{{'External Link'}}</th>
                                    <th class="text-center" width="">{{'Description'}}</th>
                                    <th class="text-center" width="">{{'Zones'}}</th>
                                    <th class="text-center" width="125">{{'Image'}}</th>
                                    <th class="text-center">{{trans('english.STATUS')}}</th>
                                    <?php if(!empty(Session::get('acl')[11][3]) || !empty(Session::get('acl')[11][4])){ ?>
                                    <th class="text-center"> {!!trans('english.ACTION')!!}
                                    </th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>

                                @if (!$nadName->isEmpty())

                                    <?php
                                        $page = \Input::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page-1)*10;
                                        $l = 1;

                                    ?>
                                    @foreach($nadName as $nn)
                                        <tr>
                                            <td>{{++$sl}}</td>
                                            <td>{{$nn->name}}</td>
                                            <td>{{$nn->alise}}</td>
                                            <td>{{$nn->purchase_limit}}</td>
                                            <td>{{$nn->direct_purchase_limit}}</td>
                                            <td>{{$nn->external_link}}</td>
                                            <td>{{$nn->description}}</td>
                                            <td>
                                                <?php
                                                $zoneids = explode(',',$nn->zones);
                                                foreach ($zoneids as $zids) {
                                                    $valsss = \App\Http\Controllers\RegistredNsdNameController::zone_name($zids);
                                                    echo "<li>".$valsss."</li>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                @if(!empty($nn->image))
                                                    <img width="100" height="80" src="{{URL::to('/')}}/public/uploads/nsd_bsd_image/{{$nn->image}}">
                                                @else
                                                    <img width="100" height="80" src="{{URL::to('/')}}/public/upload/systemSettings/0AMYVmrii1fFAoa4lD8R.png" style="-webkit-filter: grayscale(100%); filter: grayscale(100%);">
                                                @endif

                                            </td>
                                            <td class="text-center">
                                                @if ($nn->status_id == '1')
                                                    <span class="label label-success">{{trans('english.ACTIVE')}}</span>
                                                @else
                                                    <span class="label label-warning">{{trans('english.INACTIVE')}}</span>
                                                @endif
                                            </td>
                                            <?php if(!empty(Session::get('acl')[10][3]) || !empty(Session::get('acl')[10][4])){ ?>
                                            <td class="action-center">
                                                <div class="text-center">

                                                    <?php if(!empty(Session::get('acl')[10][3])){ ?>
                                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('reg_nsd/registred_nsd_name/' . $nn->id . '/edit') }}">
                                                        <i class="icon-edit"></i>
                                                    </a>
                                                    <?php } ?>

                                                    <?php if(!empty(Session::get('acl')[10][4])){?>
                                                    <button class="exbtovdelete btn btn-danger btn-xs" id="{{$nn->id}}" type="button" data-placement="top" data-rel="tooltip" data-original-title="Delete">
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
                                        <td colspan="5">{{'Empty Data'}}</td>
                                    </tr>
                                @endif
                                    
                                </tbody>
                            </table><!---/table-responsive-->

                            {{ $nadName->links()}}

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
                var url='{!! URL::to('reg_nsd/registred_nsd_name/destroy') !!}'+'/'+id;
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