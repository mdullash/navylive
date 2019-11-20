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
                    <h3>PO Approve</h3>
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
                            <h3>PO Approve</h3>
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
                                                <label for="demand_no">Tender No: </label>
                                                {!!  Form::text('tender_no', Input::get('tender_no'), array('id'=> 'demand_no', 'class' => 'form-control', 'autocomplete'=> 'off')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="demand_no">PO No: </label>
                                                {!!  Form::text('po_no', Input::get('po_no'), array('id'=> 'demand_no', 'class' => 'form-control', 'autocomplete'=> 'off')) !!}
                                            </div>
                                        </div>
                                    </div>

                                     <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="email">From: </label>
                                                {!!  Form::text('from', Input::get('from'), array('id'=> 'from', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off','readonly')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="email">To: </label>
                                                {!!  Form::text('todate', Input::get('todate'), array('id'=> 'todate', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off','readonly')) !!}
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
                                    <?php// if(!empty(Session::get('acl')[34][22]) ){ ?>
                                        <li @if($demandDetailPageFromRoute=='po-generation-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('po-generation-acc/1')}}"> Pending</a></li>
                                    <?php //} ?>
                                    <?php// if(!empty(Session::get('acl')[34][30]) ){ ?>
                                        <li @if($demandDetailPageFromRoute=='po-generation-acc' && $segTwo==3)class="active" @endif><a href="{{URL::to('po-generation-acc/3')}}"> Waiting for check</a></li>
                                    <?php// } ?>
                                    <?php// if(!empty(Session::get('acl')[34][31]) ){ ?>
                                        <li @if($demandDetailPageFromRoute=='po-generation-acc' && $segTwo==4)class="active" @endif><a href="{{URL::to('po-generation-acc/4')}}"> Waiting for approve</a></li>
                                    <?php// } ?>
                                    <li @if($demandDetailPageFromRoute=='po-generation-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('po-generation-acc/2')}}"> Approved</a></li>
                                </ul>
                            <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Tender Number'}}</th>
                                    <th class="text-center">{{'PO Number'}}</th>
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
                                            <td>{!! $sc->tender_number !!}</td>
                                            <td>{!! $sc->po_number !!}</td>
                                            <td>{!! $sc->quantity !!}</td>
                                            <?php if(!empty(Session::get('acl')[34][31]) ){ ?>
                                            <td>
                                                <a class="btn btn-success btn-xs" href="{{ URL::to('po-approve-view/'.$sc->id.'&'.$sc->tender_id) }}" title="Check PO">
                                                    <i class="icon-check"></i>
                                                </a>
                                                 <a class="btn btn-primary btn-xs" target="_blank" href="{{ URL::to('view-po-generation/'.$sc->id.'&'.$sc->tender_id.'&'.'4') }}" title="Check PO">
                                                    <i class="fa fa-eye"></i>
                                                </a>
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
                            </div>

                            {{ $demands->appends(Request::except('page'))->links()}}

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