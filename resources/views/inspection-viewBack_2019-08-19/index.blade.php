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
                    <h3>Inspection</h3>
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
                            <h3>Inspection</h3>
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

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="demand_no">PO No: </label>
                                                {!!  Form::text('demand_no', $demand_no, array('id'=> 'demand_no', 'class' => 'form-control', 'autocomplete'=> 'off')) !!}
                                            </div>
                                        </div>
                                    </div>

                                   {{--  <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                               <label for="email">From: </label>
                                                {!!  Form::text('from',Input::get('from'), array('id'=> 'from', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off','readonly')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                               <label for="email">To: </label>
                                                {!!  Form::text('to',Input::get('to'), array('id'=> 'from', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off','readonly')) !!}
                                            </div>
                                        </div>
                                    </div> --}}

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
                                    <li @if($demandDetailPageFromRoute=='inspection-view-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('inspection-view-acc/1')}}"> Pending</a></li>
                                    <li @if($demandDetailPageFromRoute=='inspection-view-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('inspection-view-acc/2')}}"> Approved</a></li>
                                </ul>
                            <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'PO Number'}}</th>
                                    <th class="text-center">{{'Quantity'}}</th>
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
                                            <td>{!! $sc->po_number !!}</td>
                                            <td>{!! $sc->cr_qty_pending !!}</td>
                                            <?php if(!empty(Session::get('acl')[34][24]) ){ ?>
                                            <td>
                                                <a class="btn btn-success btn-xs" href="{{ URL::to('inspection-section/'.$sc->id.'&'.$sc->tender_id.'&'.$segTwo) }}" title="CR view">
                                                    @if($segTwo == 1)
                                                    <i class="icon-check"></i>
                                                    @else
                                                    <i class="fa fa-circle"></i>
                                                    @endif
                                                </a>
                                                <!-- <a class="btn btn-primary btn-xs" href="{{ URL::to('print-po-generation/'.$sc->id.'&'.$sc->tender_id) }}" title="Check PO" target="_blank">
                                                    <i class="icon-print"> </i>
                                                </a> -->
                                            </td>
                                            <?php } ?>
                                        </tr>
                                    @endforeach
                                
                                @else
                                    <tr>
                                        <td colspan="4">{{'Empty Data'}}</td>
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