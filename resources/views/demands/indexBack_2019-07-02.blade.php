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
                    <h3>
                        @if($demandDetailPageFromRoute=='demand' || $demandDetailPageFromRoute=='demand-pending')
                            Demand
                        @endif
                        @if($demandDetailPageFromRoute=='group-check-acc')
                            Group Check
                        @endif
                        @if($demandDetailPageFromRoute=='floating-tender-acc')
                            Float Tender
                        @endif
                        @if($demandDetailPageFromRoute=='collection-quotation-acc')
                            Collection Quotation
                        @endif
                        @if($demandDetailPageFromRoute=='cst-view-acc')
                            Draft CST
                        @endif
                        @if($demandDetailPageFromRoute=='draft-cst-view-acc')
                            Final CST
                        @endif
                        @if($demandDetailPageFromRoute=='hdq-approval-acc')
                            NHQ Approval
                        @endif
                        @if($demandDetailPageFromRoute=='po-generation-acc')
                            Purchase Order
                        @endif
                        @if($demandDetailPageFromRoute=='cr-view-acc')
                            CR
                        @endif
                        @if($demandDetailPageFromRoute=='inspection-view-acc')
                            Inspection
                        @endif
                        @if($demandDetailPageFromRoute=='v44-voucher-view-acc')
                            D44B voucher
                        @endif
                    </h3>
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
                        <?php if(!empty(Session::get('acl')[34][2]) && ($demandDetailPageFromRoute=='demand' || $demandDetailPageFromRoute=='demand-pending')){ 
                        //if(!empty(Session::get('acl')[34][2]))  { ?>
                        <div class="pull-right">
                            <a class="btn btn-info btn-effect-ripple" href="{{ URL::to('demand/create') }}"><i class="fa fa-plus"></i> Create Demand</a>
                        </div>
                        <?php } ?>
                        @if($demandDetailPageFromRoute=='floating-tender-acc')
                            <a class="btn btn-info btn-effect-ripple pull-right" href="{{ URL::to('direct-item-dmnd-create') }}"><i class="fa fa-plus"></i> Create Tender</a>
                        @endif
                            <h3>
                                @if($demandDetailPageFromRoute=='demand' || $demandDetailPageFromRoute=='demand-pending')
                                    Demand
                                @endif
                                @if($demandDetailPageFromRoute=='group-check-acc')
                                    Group Check
                                @endif
                                @if($demandDetailPageFromRoute=='floating-tender-acc')
                                    Float Tender
                                @endif
                                @if($demandDetailPageFromRoute=='collection-quotation-acc')
                                    Collection Quotation
                                @endif
                                @if($demandDetailPageFromRoute=='cst-view-acc')
                                    Draft CST
                                @endif
                                @if($demandDetailPageFromRoute=='draft-cst-view-acc')
                                    Final CST
                                @endif
                                @if($demandDetailPageFromRoute=='hdq-approval-acc')
                                    NHQ Approval
                                @endif
                                @if($demandDetailPageFromRoute=='po-generation-acc')
                                    Purchase Order
                                @endif
                                @if($demandDetailPageFromRoute=='cr-view-acc')
                                    CR
                                @endif
                                @if($demandDetailPageFromRoute=='inspection-view-acc')
                                    Inspection
                                @endif
                                @if($demandDetailPageFromRoute=='v44-voucher-view-acc')
                                    D44B voucher
                                @endif
                            </h3>
                    </div>
                        <div class="panel-body">
                            
                        <!-- Tab section -->
                        <ul class="nav nav-tabs">
                            <?php
                                $segOne = \Request::segment(1);
                                $segTwo = \Request::segment(2);

                                $searchFormSubUrl = $segOne.'/'.$segTwo;
                            ?>
                                {{--Search statr =======================================--}}
                                <div class="row">
                                    <div class="col-md-12">
                                    {{ Form::open(array('role' => 'form', 'url' => $searchFormSubUrl, 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'schedule-all')) }}
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="requester">Demanding: </label>
                                            <select class="form-control selectpicker requester" name="requester" id="requester"  data-live-search="true">
                                                <option value="">{!! '- Select -' !!}</option>
                                                @foreach($demandeNames as $dmdn)
                                                    <option value="{!! $dmdn->id !!}" @if($demande==$dmdn->id) selected @endif>{!! $dmdn->name !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="email">Demand No: </label>
                                                {!!  Form::text('demand_no', $demand_no, array('id'=> 'demand_no', 'class' => 'form-control', 'autocomplete'=> 'off')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    @if($demandDetailPageFromRoute != 'demand-pending' && $demandDetailPageFromRoute != 'group-check-acc')
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <label for="email">Tender No: </label>
                                                    {!!  Form::text('tender_no', '', array('id'=> 'tender_no', 'class' => 'form-control', 'autocomplete'=> 'off')) !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="email">From: </label>
                                                {!!  Form::text('from', $from, array('id'=> 'from', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off','readonly')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
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

                                @if($demandDetailPageFromRoute=='demand' || $demandDetailPageFromRoute=='demand-pending')
                                    <li @if($demandDetailPageFromRoute=='demand-pending' && $segTwo==4)class="active" @endif><a href="{{URL::to('demand-pending/4')}}">All</a></li>
                                <?php //if(!empty(Session::get('acl')[34][12])){ ?>
                                    <li @if($demandDetailPageFromRoute=='demand-pending' && $segTwo==1)class="active" @endif><a href="{{URL::to('demand-pending/1')}}">Issue Auth. Pending</a></li>
                                <?php //} ?>
                                <?php if ( !empty(Session::get('acl')[34][1]) || !empty(Session::get('acl')[34][2]) ) { ?>    
                                    <li @if($demandDetailPageFromRoute=='demand-pending' && $segTwo==2)class="active" @endif><a href="{{URL::to('demand-pending/2')}}"> Approved</a></li>
                                    <li @if($demandDetailPageFromRoute=='demand-pending' && $segTwo==3)class="active" @endif><a href="{{URL::to('demand-pending/3')}}"> Rejected</a></li>
                                <?php } ?>    
                                @endif

                                @if($demandDetailPageFromRoute=='group-check-acc')
                                    <?php if(!empty(Session::get('acl')[34][28]) && $demandDetailPageFromRoute=='group-check-acc'){ ?>
                                        <li @if($demandDetailPageFromRoute=='group-check-acc' && $segTwo==3)class="active" @endif><a href="{{URL::to('group-check-acc/3')}}"> Group In Charge Approval</a></li>
                                    <?php } ?>
                                    <?php if(!empty(Session::get('acl')[34][16]) && $demandDetailPageFromRoute=='group-check-acc'){ ?>
                                        <li @if($demandDetailPageFromRoute=='group-check-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('group-check-acc/1')}}"> OIC Approval</a></li>
                                    <?php } ?>
                                    <li @if($demandDetailPageFromRoute=='group-check-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('group-check-acc/2')}}"> Approved</a></li>
                                @endif

                                @if($demandDetailPageFromRoute=='floating-tender-acc')
                                    <?php if(!empty(Session::get('acl')[34][17])){ ?>
                                    <li @if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('floating-tender-acc/1')}}"> Pending</a></li>
                                    <li @if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('floating-tender-acc/2')}}"> Approved</a></li>
                                    <?php } ?>
                                    <?php if(!empty(Session::get('acl')[34][26])){ ?>
                                    <li @if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3)class="active" @endif><a href="{{URL::to('floating-tender-acc/3')}}"> Waiting for Approve</a></li>
                                    <?php } ?> 
                                @endif

                                @if($demandDetailPageFromRoute=='collection-quotation-acc')
                                    <?php if(!empty(Session::get('acl')[34][18])){ ?>
                                    <li @if($demandDetailPageFromRoute=='collection-quotation-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('collection-quotation-acc/1')}}"> Pending</a></li>
                                    <li @if($demandDetailPageFromRoute=='collection-quotation-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('collection-quotation-acc/2')}}"> Approved</a></li>
                                    <?php } ?> 
                                    <?php if(!empty(Session::get('acl')[34][27])){ ?>
                                    <li @if($demandDetailPageFromRoute=='collection-quotation-acc' && $segTwo==3)class="active" @endif><a href="{{URL::to('collection-quotation-acc/3')}}"> Waiting for Approve</a></li>
                                    <?php } ?> 
                                @endif

                                @if($demandDetailPageFromRoute=='cst-view-acc')
                                    <li @if($demandDetailPageFromRoute=='cst-view-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('cst-view-acc/1')}}"> Pending</a></li>
                                    <li @if($demandDetailPageFromRoute=='cst-view-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('cst-view-acc/2')}}"> Approved</a></li>
                                @endif

                                @if($demandDetailPageFromRoute=='draft-cst-view-acc')
                                    <li @if($demandDetailPageFromRoute=='draft-cst-view-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('draft-cst-view-acc/1')}}"> Pending</a></li>
                                    <li @if($demandDetailPageFromRoute=='draft-cst-view-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('draft-cst-view-acc/2')}}"> Approved</a></li>
                                @endif

                                @if($demandDetailPageFromRoute=='hdq-approval-acc')
                                    <li @if($demandDetailPageFromRoute=='hdq-approval-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('hdq-approval-acc/1')}}"> Pending</a></li>
                                    <li @if($demandDetailPageFromRoute=='hdq-approval-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('hdq-approval-acc/2')}}"> Approved</a></li>
                                @endif

                                @if($demandDetailPageFromRoute=='po-generation-acc')
                                    <li @if($demandDetailPageFromRoute=='po-generation-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('po-generation-acc/1')}}"> Pending</a></li>
                                    <li @if($demandDetailPageFromRoute=='po-generation-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('po-generation-acc/2')}}"> Generated</a></li>
                                @endif

                                @if($demandDetailPageFromRoute=='cr-view-acc')
                                    <li @if($demandDetailPageFromRoute=='cr-view-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('cr-view-acc/1')}}"> Pending</a></li>
                                    <li @if($demandDetailPageFromRoute=='cr-view-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('cr-view-acc/2')}}"> Approved</a></li>
                                @endif

                                @if($demandDetailPageFromRoute=='inspection-view-acc')
                                    <li @if($demandDetailPageFromRoute=='inspection-view-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('inspection-view-acc/1')}}"> Pending</a></li>
                                    <li @if($demandDetailPageFromRoute=='inspection-view-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('inspection-view-acc/2')}}"> Approved</a></li>
                                @endif
                                
                                
                            </ul>
                            
                            <?php //if(!empty(Session::get('acl')[34][1])){ ?>
                            <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Demanding'}}</th>
                                    <th class="text-center">{{'Demand No'}}</th>
                                    {{-- @if($demandDetailPageFromRoute=='collection-quotation-acc' || ($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3)
                                    || $demandDetailPageFromRoute=='cst-view-acc' || $demandDetailPageFromRoute=='draft-cst-view-acc'
                                      || $demandDetailPageFromRoute=='hdq-approval-acc' || $demandDetailPageFromRoute=='po-generation-acc'
                                       || $demandDetailPageFromRoute=='cr-view-acc' || $demandDetailPageFromRoute=='inspection-view-acc'
                                               || $demandDetailPageFromRoute=='v44-voucher-view-acc') --}}
                                    <th class="text-center">{{'Tender Number'}}</th>
                                    {{-- @endif --}}
                                    <!-- <th class="text-center">{{'Priority'}}</th> -->
                                    {{--<th class="text-center">{{'Segmental detection of content'}}</th>--}}
                                    <!-- <th class="text-center">{{'Authority Number'}}</th>
                                    <th class="text-center">{{'Demand Date'}}</th> -->
                                    <th class="text-center">{{'Quantity'}}</th>
                                    <!-- <th class="text-center">{{'Allowed'}}</th> -->
                                    <?php if(!empty(Session::get('acl')[34][3]) || !empty(Session::get('acl')[34][4])){ ?>
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
                                            <td>{{++$sl}}</td>
                                            @if($demandDetailPageFromRoute=='collection-quotation-acc' || ($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3)
                                             || $demandDetailPageFromRoute=='cst-view-acc' || $demandDetailPageFromRoute=='draft-cst-view-acc'
                                              || $demandDetailPageFromRoute=='hdq-approval-acc' || $demandDetailPageFromRoute=='po-generation-acc'
                                              || $demandDetailPageFromRoute=='cr-view-acc'  || $demandDetailPageFromRoute=='inspection-view-acc'
                                               || $demandDetailPageFromRoute=='v44-voucher-view-acc')
                                                <td>
                                                    @if(!empty($sc->requester)) {!! $sc->demande_name !!} @endif
                                                </td>
                                            @else
                                                <td>
                                                    @if(!empty($sc->requester))
                                                        {!! $sc->demandeNameInDemand->name !!}
                                                    @endif
                                                </td>
                                            @endif
                                            <td>{!! $sc->demand_no !!}</td>
                                            {{-- @if($demandDetailPageFromRoute=='collection-quotation-acc' || ($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3)
                                             || $demandDetailPageFromRoute=='cst-view-acc' || $demandDetailPageFromRoute=='draft-cst-view-acc'
                                              || $demandDetailPageFromRoute=='hdq-approval-acc' || $demandDetailPageFromRoute=='po-generation-acc'
                                              || $demandDetailPageFromRoute=='cr-view-acc' || $demandDetailPageFromRoute=='inspection-view-acc'
                                               || $demandDetailPageFromRoute=='v44-voucher-view-acc') --}}
                                                <td>{!! $sc->tender_number !!}</td>
                                            {{-- @endif --}}
                                            <!-- <td>{!! $sc->priority !!}</td> -->

                                            <!-- <td>{!! $sc->pattern_or_stock_no !!}</td>
                                            <td>
                                                @if(!empty($sc->when_needed)) {!! date('d-m-Y',strtotime($sc->when_needed)) !!} @endif
                                            </td> -->
                                            <td>{!! $sc->total_unit !!}</td>

                                            <td class="action-center">
                                                <div class="text-center">

                                                    <?php if(!empty(Session::get('acl')[34][1])){ ?>
                                                    <a class="btn btn-info btn-xs" href="{{ URL::to('demand-details/' . $sc->id) }}" title="View">
                                                        <i class="icon-eye-open"></i>
                                                    </a>
                                                    <?php } ?>

                                                    <!-- Newly added ===============================
                                                    =========================================== -->
                                                    <?php if(!empty(Session::get('acl')[34][12]) && $demandDetailPageFromRoute=='demand-pending' && ($segTwo == 1 || $segTwo == 3) ){  ?>
                                                        <!-- <a class="btn btn-success btn-xs" href="{{ URL::to('demand-get-approve/'.$sc->id.'&1') }}" title="Approve Demand">
                                                            <i class="icon-check"> </i>
                                                        </a> -->
                                                        <a class="btn btn-success btn-xs showModal" href="javascript:void(0)" title="Approve Demand" attr-demandid-updateflds="{!! $sc->id.'&1&'.$sc->tender_id !!}">
                                                            <i class="icon-check"> </i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][12]) && $demandDetailPageFromRoute=='demand-pending'){  ?>
                                                        <a class="btn btn-primary btn-xs" href="{{ URL::to('store-demand-print/'.$sc->id) }}" title="Store Demand Print">
                                                            <i class="icon-print"> </i>
                                                        </a>
                                                    <?php } ?>
                                                    <!-- Added newly for edit demand -->
                                                    <?php if(!empty(Session::get('acl')[34][12]) && empty($sc->group_status) && $demandDetailPageFromRoute=='demand-pending' && ($segTwo == 1 || $segTwo == 3)){ ?>
                                                        {{--<a class="btn btn-primary btn-xs" href="{{ URL::to('demand-group/' . $sc->id) }}" title="Edit Demand" >--}}
                                                            {{--<i class="icon-edit"> Edit Demand</i>--}}
                                                        {{--</a>--}}
                                                    <?php } ?>
                                                    <!-- End Added newly for edit demand -->
                                                    <?php if(!empty(Session::get('acl')[34][16]) && $demandDetailPageFromRoute=='group-check-acc' && $segTwo == 1){ ?>
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('oic-group-status-change/'.$sc->id) }}" title="Group Check">
                                                            <i class="icon-check"> </i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][28]) && $demandDetailPageFromRoute=='group-check-acc' && $segTwo == 3){ ?>
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('demand-details/'.$sc->id) }}" title="Group Check">
                                                            <i class="icon-check"> </i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if( (!empty(Session::get('acl')[34][16]) || !empty(Session::get('acl')[34][28]) ) && $demandDetailPageFromRoute=='group-check-acc'){ ?>
                                                        <a class="btn btn-primary btn-xs" href="{{ URL::to('demand-get-print/'.$sc->id) }}" title="Print">
                                                        <i class="icon-print"> </i>
                                                        </a>
                                                    <?php } ?>                                             
                                                    <?php if(!empty(Session::get('acl')[34][26]) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 3){ ?>
                                                        <!-- <a class="btn btn-success btn-xs" href="{{ URL::to('demand-get-approve/'.$sc->id.'&2') }}" title="Approve Float Tender">
                                                            <i class="icon-check"> </i>
                                                        </a> -->
                                                        <a class="btn btn-success btn-xs showModal" href="javascript:void(0)" title="Approve Tender" attr-demandid-updateflds="{!! $sc->id.'&2&'.$sc->tenderId !!}">
                                                            <i class="icon-check"> </i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][17]) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 1 ){ ?>
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('floating-tender/create/'.$sc->id) }}" title="Create Tender">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][26]) && $demandDetailPageFromRoute=='collection-quotation-acc' && $segTwo == 3){ ?>
                                                        <!-- <a class="btn btn-success btn-xs" href="{{ URL::to('demand-get-approve/'.$sc->id.'&3') }}" title="Approve Collection Quotation">
                                                            <i class="icon-check"> </i>
                                                        </a> -->
                                                        <a class="btn btn-success btn-xs showModal" href="javascript:void(0)" title="Approve Quatition" attr-demandid-updateflds="{!! $sc->id.'&3&'.$sc->tenderId !!}">
                                                            <i class="icon-check"> </i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][18]) && $demandDetailPageFromRoute=='collection-quotation-acc' && $segTwo == 1 ){ ?>
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('create-collection-quotation/'.$sc->id.'/'.$sc->tenderId) }}" title="Create Collection Quotation">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <!-- Added newly for edit Collection Quotation -->
                                                    <?php if(!empty(Session::get('acl')[34][18]) && !empty($sc->tender_floating) && $demandDetailPageFromRoute=='collection-quotation-acc' && $segTwo == 2){ ?>
                                                        <!-- <a class="btn btn-primary btn-xs" href="{{ URL::to('create-collection-quotation/'.$sc->id.'/'.$sc->tenderId) }}" title="Edit Collection Quotation">
                                                            <i class="icon-edit"></i>
                                                        </a> -->
                                                    <?php } ?>
                                                    <!-- End Added newly for edit Collection Quotation -->
                                                    <?php if(!empty(Session::get('acl')[34][19]) && $demandDetailPageFromRoute=='cst-view-acc' && $segTwo == 1){ ?>
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('cst-view/'.$sc->id.'&'.$sc->tenderId) }}" title="CST view">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <!-- Added newly for edit CST view -->
                                                    <?php if(!empty(Session::get('acl')[34][19]) && !empty($sc->tender_quation_collection) && $demandDetailPageFromRoute=='cst-view-acc' && $segTwo == 2){ ?>
                                                        <a class="btn btn-primary btn-xs" href="{{ URL::to('cst-view/'.$sc->id.'&'.$sc->tenderId) }}" title="Edit CST view">
                                                            <i class="icon-edit"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <!-- End Added newly for edit CST view -->
                                                    <?php if(!empty(Session::get('acl')[34][20]) && $demandDetailPageFromRoute=='draft-cst-view-acc' && $segTwo == 1){ ?>
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('draft-cst-view/'.$sc->id.'&'.$sc->tenderId) }}" title="CST view">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <!-- Added newly for edit draft CST view -->
                                                    <?php if(!empty(Session::get('acl')[34][20]) && !empty($sc->cst_draft_status) && $demandDetailPageFromRoute=='draft-cst-view-acc' && $segTwo == 2){ ?>
                                                        <a class="btn btn-primary btn-xs" href="{{ URL::to('draft-cst-view/'.$sc->id.'&'.$sc->tenderId) }}" title="Draft CST view">
                                                            <i class="icon-edit"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <!-- End Added newly for edit draft CST view -->
                                                    <?php if(!empty(Session::get('acl')[34][21]) && $demandDetailPageFromRoute=='hdq-approval-acc' && $segTwo == 1 ){ ?>
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('headquarte-approval/'.$sc->id.'&'.$sc->tenderId) }}" title="NHQ Approval">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][22]) && $demandDetailPageFromRoute=='po-generation-acc' && ($segTwo == 1 || $segTwo == 2) ){ ?>
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('po-generate-view/'.$sc->id.'&'.$sc->tenderId) }}" title="Purchase Order" >
                                                            @if($segTwo == 1)
                                                            <i class="icon-check"></i>
                                                            @else
                                                            <i class="fa fa-circle"></i>
                                                            @endif
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][23]) && $demandDetailPageFromRoute=='cr-view-acc' && ($segTwo == 1 || $segTwo == 2)){ ?>
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('cr-section/'.$sc->id.'&'.$sc->tenderId) }}" title="CR view">
                                                            @if($segTwo == 1)
                                                            <i class="icon-check"></i>
                                                            @else
                                                            <i class="fa fa-circle"></i>
                                                            @endif
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][24]) && $demandDetailPageFromRoute=='inspection-view-acc' &&  ($segTwo == 1 || $segTwo == 2)){ ?>
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('inspection-section/'.$sc->id.'&'.$sc->tenderId) }}" title="Inspection">
                                                            @if($segTwo == 1)
                                                            <i class="icon-check"></i>
                                                            @else
                                                            <i class="fa fa-circle"></i>
                                                            @endif
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][25]) && $demandDetailPageFromRoute=='v44-voucher-view-acc' ){ ?>
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('v44voucher-pdf-view/'.$sc->id.'&'.$sc->tenderId) }}" title="V44voucher">
                                                            <i class="icon-check"> </i>
                                                        </a>
                                                    <?php } ?>
                                                    
                                                    <!-- End Newly added ===============================
                                                    =========================================== -->

                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach

                                @else
                                    <tr>
                                        <td colspan="6">{{'Empty Data'}}</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table><!---/table-responsive-->
                            </div>
                        <?php //} ?>

                            {{ $demands->links()}}

                        </div>
                    </div>
                </div>

            </div>

    </div>


    <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4><span class="glyphicon glyphicon-lock"></span> Approved</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          {{ Form::open(array('role' => 'form', 'url' => 'demand-pending-post', 'files'=> true, 'class' => 'demand-pending-post', 'id'=>'demand-pending-post')) }}
                <input type="hidden" name="demandId" id="demandId" value="">
                <input type="hidden" name="updateFilelds" id="updateFilelds" value="">
                <input type="hidden" name="tenderId" id="tenderId" value="">


                <div class="form-group"><label class="control-label" for="status">Status :<span class="text-danger">*</span></label>
                    {{ Form::select('demand_approval', array('1' => 'Approved', '2' =>'Reject'), '', array('class' => 'form-control selectpicker', 'id' => 'demand_approval','required')) }}
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary pull-right">{!! 'Action' !!}</button>
                </div>
                            
          {!!   Form::close() !!}
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
          
        </div>
      </div>
      
    </div>
  </div> 


    <script type="text/javascript">
        $(document).ready(function(){

            $(document).on('click','.showModal',function(){
                var attrVlues = $(this).attr('attr-demandid-updateflds');
                var result = attrVlues.split('&');
                $("#demandId").val('');
                $("#updateFilelds").val('');
                $("#tenderId").val('');

                $("#demandId").val(result[0]);
                $("#updateFilelds").val(result[1]);
                $("#tenderId").val(result[2]);

                $('#myModal').modal('show');
            });
            

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