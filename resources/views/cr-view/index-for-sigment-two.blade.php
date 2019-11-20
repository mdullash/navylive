@extends('layouts.default')
<style type="text/css">
    / Tab Navigation /
    .nav-tabs {
        margin: 0;
        padding: 0;
        border: 0;
    }
    .nav-tabs > li > a {
        background: #f2f2f2;
        border-radius: 0;
        box-shadow: inset 0 -8px 7px -9px rgba(0,0,0,.4),-2px -2px 5px -2px rgba(0,0,0,.4);
    }
    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover {
        background: #F5F5F5;
        box-shadow: inset 0 0 0 0 rgba(0,0,0,.4),-2px -3px 5px -2px rgba(0,0,0,.4);
    }

    / Tab Content /
    .tab-pane {
        background: #F5F5F5;
        box-shadow: 0 0 4px rgba(0,0,0,.4);
        border-radius: 0;
        text-align: center;
        padding: 10px;
        clear: bottom;

    }
</style>
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>CR</h3>
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
                            <h3>CR</h3>
                            <?php
                                $segOne = \Request::segment(1);
                                $segTwo = \Request::segment(2);

                                $searchFormSubUrl = $segOne.'/'.$segTwo;
                            ?>
                    </div>
                        <div class="panel-body">

                            <div class="row">
                                    <div class="col-md-12">
                                    {{ Form::open(array('role' => 'form', 'url' => $searchFormSubUrl, 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'schedule-all')) }}

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="demand_no">CR No: </label>
                                                {!!  Form::text('cr_no', $cr_no, array('id'=> 'cr_no', 'class' => 'form-control', 'autocomplete'=> 'off')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="email">From: </label>
                                                {!!  Form::text('from', $from, array('id'=> 'from', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off','readonly')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="email">To: </label>
                                                {!!  Form::text('todate', $todate, array('id'=> 'todate', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off','readonly')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-md-12" style="padding-top: 18px;">
                                                <label for="submit"></label>
                                                <button type="submit" class="form-control btn btn-primary">{!! 'Search' !!}</button>
                                            </div>
                                        </div>
                                    </div>
                                    {!!   Form::close() !!}
                                    </div>
                                </div>
                                {{--Search End =======================================--}}

                                <ul class="nav nav-tabs">
                                    <li @if($demandDetailPageFromRoute=='cr-view-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('cr-view-acc/1')}}"> Pending</a></li>
                                    <li @if($demandDetailPageFromRoute=='cr-view-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('cr-view-acc/2')}}"> In Inspection</a></li>
                                    <li @if($demandDetailPageFromRoute=='cr-view-acc' && $segTwo==3)class="active" @endif><a href="{{URL::to('cr-view-acc/3')}}"> Inspected</a></li>
                                </ul>
                            <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'CR Number'}}</th>
                                    <th class="text-center">{{'Tender Title'}}</th>
                                    <th class="text-center">{{'Supplier Name'}}</th>
                                    <th class="text-center">{{'Generated Date'}}</th>
                                    <th class="text-center">{{'Total Quantity'}}</th>
                                    <?php if(!empty(Session::get('acl')[34][31]) ){ ?>
                                    <th class="text-center"> {!!trans('english.ACTION')!!}
                                    </th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>

                                @if (!$demands->isEmpty())

                                    <?php
                                        $page = \Input::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page-1)*10;
                                        $l = 1;

                                    ?>
                                    @foreach($demands as $sc)
                                        <tr>
                                            <td>{!! $l++ !!}</td>
                                            <td>{!! $sc->cr_number !!}</td>
                                            <td>{!! $sc->tender_title !!}</td>
                                            <td>{!! $sc->company_name !!}</td>
                                            <td>@if(!empty($sc->top_date)) {!! date('Y-m-d', strtotime($sc->top_date)) !!} @endif</td>
                                            <td>{!! $sc->cr_receive_qty !!}</td>
                                            <?php if(!empty(Session::get('acl')[34][23]) ){ ?>
                                            <td>
                                                <a class="btn btn-success btn-xs" href="{{ URL::to('cr-section-two/'.$sc->id) }}" title="CR view">
                                                    @if($segTwo == 1)
                                                    <i class="icon-check"></i>
                                                    @else
                                                    <i class="fa fa-circle"></i>
                                                    @endif
                                                </a>
                                                <a class="btn btn-primary btn-xs" href="{{ URL::to('cr-pdf-print-direct/'.$sc->id) }}" title="Print" target="_blank">
                                                    <i class="icon-print"> </i>
                                                </a>
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
                            </div>

                            {{ $demands->links()}}

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
                var url='{!! URL::to('/item/destroy') !!}'+'/'+id;
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