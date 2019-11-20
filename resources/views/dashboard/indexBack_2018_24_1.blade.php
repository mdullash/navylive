@extends('layouts.default')

<style>
    .hpanel .panel-body{
        background: rgba(255, 255, 255, 0.64) !important;
    }
    .header-link{
        padding: 23px 26px 17px 26px !important;
    }
</style>

@section('content')
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2>
                   {!! $title->site_title !!}
                </h2>

                <p class="">
                    <!--Better Customer Experience-->
                    <h3>{!! strtoupper(\Session::get('zone')) !!}</h3>
                </p>
            </div>
        </div>
    </div>
    <div class="content animate-panel">
        <div class="row">
            <div class="col-md-offset-1 col-md-10 col-xs-12 text-center">
                <?php if (!empty(Session::get('acl')[3][1])) { ?>
                <div class="col-md-4 ">
                    <a href="{{URL::to('users')}}">
                        <div class="hpanel dashboard-box">
                            <div class="panel-body file-body">
                                <i class="fa fa-user text-success" aria-hidden="true"></i><br>
                                <span style="text-align: center; color: #C20000" ><b>{!! $user !!}</b></span>
                            </div>
                            <div class="panel-footer">
                                {{trans('english.USER_MANAGEMENT')}}
                            </div>
                        </div>
                    </a>

                </div>
                <?php } ?>

                <?php if (!empty(Session::get('acl')[12][1])) { ?>
                <div class="col-md-4 ">
                    <a href="{{URL::to('suppliers/suppliers')}}">
                        <div class="hpanel dashboard-box">
                            <div class="panel-body file-body">
                                <i class="fa fa-bar-chart text-info" aria-hidden="true"></i><br>
                                <span style="text-align: center; color: #C20000" ><b>{!! $suppliers !!}</b></span>
                            </div>
                            <div class="panel-footer">
                                {{trans('english.SUPPLIERS')}}
                            </div>
                        </div>
                    </a>

                </div>
                <?php } ?>

                <?php if (!empty(Session::get('acl')[14][1])) { ?>
                <div class="col-md-4 ">
                    <a href="{{URL::to('item/view')}}">
                        <div class="hpanel dashboard-box">
                            <div class="panel-body file-body">
                                <i class="fa fa-list text-warning" aria-hidden="true"></i><br>
                                <span style="text-align: center; color: #C20000" ><b>{!! $items !!}</b></span>
                            </div>
                            <div class="panel-footer">
                                {{trans('english.ITEM')}}{{'s'}}
                            </div>
                        </div>
                    </a>

                </div>
                <?php } ?>

                <?php if (!empty(Session::get('acl')[13][1])) { ?>
                <div class="col-md-4 ">
                    <a href="{{URL::to('tender/view')}}">
                        <div class="hpanel dashboard-box">
                            <div class="panel-body file-body">
                                <i class="fa fa-usd " aria-hidden="true"></i><br>
                                <span style="text-align: center; color: #C20000" ><b>{!! $tenders !!}</b></span>
                            </div>
                            <div class="panel-footer">
                                {{trans('english.TENDER')}}{{'s'}}
                            </div>
                        </div>
                    </a>

                </div>
                <?php } ?>




            </div>

        </div>

    </div>

    <script type="text/javascript">
        function productinfo() {
            $.ajax({
                url: "{{URL::to('dashboards/productinfo')}}",
                type: "POST",
                //data: {'work_order_id': workOrderId, 'product_id': editId, 'project_id': projectId, 'site_office_id': siteOfficeId},
                dataType: 'html',
                cache: false
            }).done(function (data) {
//            var result = $.parseJSON(data);
//
            });
        }
    </script>

@stop