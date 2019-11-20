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
                        @if($demandDetailPageFromRoute=='retender-view-acc')
                            Retender
                        @endif
                        @if($demandDetailPageFromRoute=='collection-quotation-acc')
                            Quotation Collection
                        @endif
                        @if($demandDetailPageFromRoute=='cst-view-acc')
                            CST
                        @endif
                        @if($demandDetailPageFromRoute=='draft-cst-view-acc')
                            NSSD Approval
                        @endif
                        @if($demandDetailPageFromRoute=='nil-return')
                            Nil Return
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
                                @if($demandDetailPageFromRoute=='retender-view-acc')
                                    Retender
                                @endif
                                @if($demandDetailPageFromRoute=='collection-quotation-acc')
                                    Quotation Collection
                                @endif
                                @if($demandDetailPageFromRoute=='cst-view-acc')
                                    CST
                                @endif
                                @if($demandDetailPageFromRoute=='draft-cst-view-acc')
                                    NSSD Approval
                                @endif
                                @if($demandDetailPageFromRoute=='nil-return')
                                    Nil Return
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

                                @if($demandDetailPageFromRoute=='floating-tender-acc' || $demandDetailPageFromRoute=='retender-view-acc')

                                    <?php if(!empty(Session::get('acl')[34][17])){ ?>
                                    <li @if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('floating-tender-acc/1')}}"> Pending</a></li>

                                    <?php if(!empty(Session::get('acl')[34][29])){ ?>
                                    <li @if($demandDetailPageFromRoute=='retender-view-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('retender-view-acc/1')}}"> Retender</a></li>
                                    <?php } ?>

                                    <?php if(!empty(Session::get('acl')[34][26])){ ?>
                                    <li @if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3)class="active" @endif><a href="{{URL::to('floating-tender-acc/3')}}"> Waiting for Approve</a></li>
                                    <?php } ?>

                                    <li @if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('floating-tender-acc/2')}}"> Approved</a></li>
                                    {{-- <li @if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==4)class="active" @endif><a href="{{URL::to('floating-tender-acc/4')}}"> Edit Tender</a></li> --}}
                                    <?php } ?>

                                @endif
                                {{-- 
                                @if($demandDetailPageFromRoute=='retender-view-acc')
                                    <li @if($demandDetailPageFromRoute=='retender-view-acc')class="active" @endif><a href="{{URL::to('retender-view-acc/1')}}">Retender</a></li>
                                @endif --}}

                                @if($demandDetailPageFromRoute=='collection-quotation-acc')
                                    <?php if(!empty(Session::get('acl')[34][18])){ ?>
                                    <li @if($demandDetailPageFromRoute=='collection-quotation-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('collection-quotation-acc/1')}}"> Pending</a></li>
                                    <?php } ?>

                                     <?php // if(!empty(Session::get('acl')[34][27])){ ?>
                                    <?php if(!empty(Session::get('acl')[34][18])){ ?>
                                    <li @if($demandDetailPageFromRoute=='collection-quotation-acc' && $segTwo==3)class="active" @endif><a href="{{URL::to('collection-quotation-acc/3')}}"> Waiting for Approve</a></li>
                                    <?php } ?>

                                    <?php if(!empty(Session::get('acl')[34][18])){ ?>
                                    <li @if($demandDetailPageFromRoute=='collection-quotation-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('collection-quotation-acc/2')}}"> Approved</a></li>
                                    <?php } ?>

                                @endif

                                @if($demandDetailPageFromRoute=='cst-view-acc')
                                    <li @if($demandDetailPageFromRoute=='cst-view-acc' && $segTwo==5)class="active" @endif><a href="{{URL::to('cst-view-acc/5')}}">Pending</a></li>
                                    <?php// if(!empty(Session::get('acl')[34][32])){ ?>
                                    <li @if($demandDetailPageFromRoute=='cst-view-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('cst-view-acc/1')}}">First Member</a></li>
                                <?php //} ?>
                                <?php// if(!empty(Session::get('acl')[34][33])){ ?>
                                    <li @if($demandDetailPageFromRoute=='cst-view-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('cst-view-acc/2')}}">Second Member</a></li>
                                <?php //} ?>
                                <?php// if(!empty(Session::get('acl')[34][34])){ ?>
                                    <li @if($demandDetailPageFromRoute=='cst-view-acc' && $segTwo==3)class="active" @endif><a href="{{URL::to('cst-view-acc/3')}}"> President</a></li>
                                <?php// } ?>
                                    <li @if($demandDetailPageFromRoute=='cst-view-acc' && $segTwo==4)class="active" @endif><a href="{{URL::to('cst-view-acc/4')}}"> Approved</a></li>
                                @endif

                                @if($demandDetailPageFromRoute=='draft-cst-view-acc')
                                    <li @if($demandDetailPageFromRoute=='draft-cst-view-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('draft-cst-view-acc/1')}}"> Pending</a></li>
                                    <li @if($demandDetailPageFromRoute=='draft-cst-view-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('draft-cst-view-acc/2')}}"> Approved</a></li>
                                @endif

                                @if($demandDetailPageFromRoute=='nil-return')
                                    <li @if($demandDetailPageFromRoute=='nil-return' && $segTwo==1)class="active" @endif><a href="{{URL::to('nil-return/1')}}">Pending</a></li>
                                    <li @if($demandDetailPageFromRoute=='nil-return' && $segTwo==2)class="active" @endif><a href="{{URL::to('nil-return/2')}}">Waiting for Approved</a></li>
                                    <li @if($demandDetailPageFromRoute=='nil-return' && $segTwo==3)class="active" @endif><a href="{{URL::to('nil-return/3')}}">Approved</a></li>
                                @endif

                                @if($demandDetailPageFromRoute=='hdq-approval-acc')
                                    <li @if($demandDetailPageFromRoute=='hdq-approval-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('hdq-approval-acc/1')}}"> Pending</a></li>
                                    <li @if($demandDetailPageFromRoute=='hdq-approval-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('hdq-approval-acc/2')}}"> Approved</a></li>
                                @endif

                                @if($demandDetailPageFromRoute=='po-generation-acc')
                                    <?php // if(!empty(Session::get('acl')[34][22]) ){ ?>
                                        <li @if($demandDetailPageFromRoute=='po-generation-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('po-generation-acc/1')}}"> Pending</a></li>
                                    <?php //} ?>
                                    <?php //if(!empty(Session::get('acl')[34][30]) ){ ?>
                                        <li @if($demandDetailPageFromRoute=='po-generation-acc' && $segTwo==3)class="active" @endif><a href="{{URL::to('po-generation-acc/3')}}"> Waiting for check</a></li>
                                    <?php //} ?>
                                    <?php// if(!empty(Session::get('acl')[34][31]) ){ ?>
                                        <li @if($demandDetailPageFromRoute=='po-generation-acc' && $segTwo==4)class="active" @endif><a href="{{URL::to('po-generation-acc/4')}}"> Waiting for approve</a></li>
                                    <?php// } ?>
                                    <li @if($demandDetailPageFromRoute=='po-generation-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('po-generation-acc/2')}}"> Approved</a></li>
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
                                @if($demandDetailPageFromRoute=='group-check-acc' && $segTwo==3 )
                                    {{ Form::open(array('method'=>'get', 'role' => 'form', 'url' => 'post-select-as-lpr', 'files'=> true, 'class' => '', 'id'=>'demands')) }}
                                @endif
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    @if($demandDetailPageFromRoute=='group-check-acc' && $segTwo==3 )
                                        <th class="text-center">
                                            <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                    <input class="activity_1 activitycell" type="checkbox" id="all_check" name="select_as_lpr[]" value="">
                                                    <label for=""></label>
                                                </div>
                                            </div>
                                        </th>
                                    @endif
                                    <th class="text-center">{{'Demanding'}}</th>
                                    <th class="text-center">{{'Demand No'}}</th>
                                    <th class="text-center">{{'Items & Quantity'}}</th>
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
                                    <th class="text-center">{{'Total Quantity'}}</th>
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

                                            @if($demandDetailPageFromRoute=='group-check-acc' && $segTwo==3 )
                                                <td>
                                                    <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                        <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                            <input class="activity_1 activitycell selectlpr" type="checkbox" id="" name="select_as_lpr[]" value="{!! $sc->id !!}">
                                                            <label for=""></label>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif

                                            @if($demandDetailPageFromRoute=='collection-quotation-acc' || ($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3 || $segTwo==4)
                                             || $demandDetailPageFromRoute=='cst-view-acc' || $demandDetailPageFromRoute=='draft-cst-view-acc'
                                              || $demandDetailPageFromRoute=='hdq-approval-acc' || $demandDetailPageFromRoute=='po-generation-acc'
                                              || $demandDetailPageFromRoute=='cr-view-acc'  || $demandDetailPageFromRoute=='inspection-view-acc'
                                               || $demandDetailPageFromRoute=='v44-voucher-view-acc' || $demandDetailPageFromRoute=='retender-view-acc'  || $demandDetailPageFromRoute=='nil-return')
                                                <td>
                                                    @if(!empty($sc->requester))

                                                        <?php 
                                                            $reuisters = explode(',', $sc->requester); 
                                                            $reuisters = array_unique($reuisters);
                                                        ?>
                                                        @foreach($reuisters as $req)
                                                            {!! \App\Http\Controllers\SelectLprController::requestename($req).'; ' !!}
                                                        @endforeach

                                                    @endif
                                                </td>
                                            @else
                                                <td>
                                                    @if(!empty($sc->requester))

                                                        <?php 
                                                            $reuisters = explode(',', $sc->requester); 
                                                            $reuisters = array_unique($reuisters);
                                                        ?>
                                                        @foreach($reuisters as $req)
                                                            {!! \App\Http\Controllers\SelectLprController::requestename($req).'; ' !!}
                                                        @endforeach

                                                    @endif
                                                </td>
                                            @endif
                                            <td style="word-break: break-all;">{!! $sc->demand_no !!}</td>
                                            <td>
                                                <?php 
                                                    if(count($sc->itemsToDemand)<1 && isset($sc->tenderId)){
                                                        $sc->itemsToDemand = \App\ItemToDemand::where('tender_no','=',$sc->tenderId)->where('lpr_id','=',$sc->id)->get();
                                                    }
                                                    $remComma = 1;
                                                    $num_of_items = count($sc->itemsToDemand->unique('item_name'));         
                                                ?>
                                                @if(!empty($sc->itemsToDemand) && count($sc->itemsToDemand->unique('item_name')) > 0)
                                                    @foreach($sc->itemsToDemand->unique('item_name') as $ke => $itmsf)
                                                    	<?php
                                                            $deno = \App\Deno::find($itmsf->deno_id);

                                                        ?>
                                                        {!! $itmsf->item_name !!}
														(
														@if(!empty($deno->name))
                                                         	{{ $deno->name }}
                                                         @endif
                                                         
                                                        @if(!empty($itmsf->unit))
                                                        	{!! $itmsf->unit !!}
                                                        @endif
														)
                                                        @if($num_of_items > $remComma)
                                                            {!! '; ' !!}
                                                        @endif
                                                        <?php $remComma++; ?>
                                                    @endforeach
                                                @endif
                                            </td>
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

                                                    <?php if($demandDetailPageFromRoute=='demand-pending' || $demandDetailPageFromRoute=='group-check-acc'){ ?>
                                                    <a class="btn btn-info btn-xs" href="{{ URL::to('demand-details/' . $sc->id) }} @if($demandDetailPageFromRoute=='group-check-acc' && ($segTwo==1 || $segTwo==2))&1 @endif" title="View">
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
                                                    <!-- For edit demand ==================
                                                     -->
                                                    <?php if(!empty(Session::get('acl')[34][12]) && $demandDetailPageFromRoute=='demand-pending' && ($segTwo == 1) && empty($sc->demand_appv_status) ){  ?>
                                                        <a class="btn btn-primary btn-xs" href="{{ URL::to('demand-edit/' . $sc->id) }}" title="Edit Demand">
                                                            <i class="fa fa-pencil"> </i>
                                                        </a>
                                                        <button class="exbtovdelete btn btn-danger btn-xs" id="{{$sc->id}}" type="button" data-placement="top" data-rel="tooltip" data-original-title="Delete" title="Delete Demand">
                                                        <i class='fa fa-trash'></i>
                                                        </button>
                                                    <?php } ?>
                                                    <!-- End for edit demand ==================
                                                     -->
                                                    <?php if(!empty(Session::get('acl')[34][12]) && $demandDetailPageFromRoute=='demand-pending'){  ?>
                                                        <a class="btn btn-primary btn-xs" href="{{ URL::to('store-demand-print/'.$sc->id) }}" title="Store Demand Print" target="_blank">
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
                                                        <a class="btn btn-primary btn-xs" href="{{ URL::to('demand-get-print/'.$sc->id) }}" title="Print" target="_blank">
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
                                                    <?php if(!empty(Session::get('acl')[34][17]) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 2 && empty($sc->tender_quation_collection)){ ?>
                                                        <a class="btn btn-warning btn-xs" href="{{ URL::to('direct-item-dmnd-edit/' . $sc->tenderId) }}" title="Edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
	                                                 <?php } ?>
	                                                 <?php if(!empty(Session::get('acl')[34][17]) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 3){ ?>
                                                        <a class="btn btn-warning btn-xs" href="{{ URL::to('direct-item-dmnd-edit/' . $sc->tenderId) }}" title="Edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][17]) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 4){ ?>
                                                        {{-- <a class="btn btn-warning btn-xs" href="{{ URL::to('direct-item-dmnd-edit/' . $sc->tenderId) }}" title="Edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </a> --}}
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][17]) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 1 ){ ?>
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('floating-tender/create/'.$sc->id) }}" title="Create Tender">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][26]) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 3 ){ ?>
                                                        <a class="btn btn-primary btn-xs" href="{{ URL::to('floating-tender-get-view/'.$sc->tenderId) }}" title="View Tender" target="_blank">
                                                            <i class="icon-print"> </i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if( $demandDetailPageFromRoute=='retender-view-acc' && ($segTwo == 1 ) ){ ?>
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('retender-create/'.$sc->id.'&'.$sc->tenderId) }}" title="Retender">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                    <?php } ?>

                                                    {{--nil return action--}}
                                                    <?php if( $demandDetailPageFromRoute=='nil-return' && ($segTwo == 1 ) ){ ?>
                                                    @if(!empty(Session::get('acl')[42][2]))
                                                        <a class="btn btn-primary btn-xs" href="{{ URL::to('nil-return-create/'.$sc->id.'&'.$sc->tenderId.'&'.$sc->nil_id) }}" title="Retender">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                        @endif
                                                    <?php } ?>

                                                    <?php if( $demandDetailPageFromRoute=='nil-return' && ($segTwo == 2 ) ){ ?>
                                                    @if(!empty(Session::get('acl')[42][2]))
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('nil-return-approved/'.$sc->nil_id) }}" onclick="return confirm('Are you sure you want to approved this nil return?');" title="Approved">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                    @endif
                                                    @if(!empty(Session::get('acl')[42][1]))
                                                        <a class="btn btn-primary btn-xs" target="_blank" href="{{ URL::to('nil-return-print/'.$sc->id.'&'.$sc->tenderId.'&'.$sc->nil_id) }}" title="Print">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                    @endif
                                                    <?php } ?>

                                                    <?php if( $demandDetailPageFromRoute=='nil-return' && ($segTwo == 3 ) ){ ?>
                                                    @if(!empty(Session::get('acl')[42][3]))
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('nil-return-create-tender/'.$sc->id.'&'.$sc->tenderId) }}" title="Retender">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                    @endif
                                                    @if(!empty(Session::get('acl')[42][1]))
                                                        <a class="btn btn-primary btn-xs" target="_blank" href="{{ URL::to('nil-return-print/'.$sc->id.'&'.$sc->tenderId.'&'.$sc->nil_id) }}" title="Print">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                    @endif
                                                    <?php } ?>
                                                        {{--nil return action--}}

                                                    <?php// if(!empty(Session::get('acl')[34][26]) && $?>
                                                    <?php if(!empty(Session::get('acl')[34][27]) && $demandDetailPageFromRoute=='collection-quotation-acc' && $segTwo == 3){ ?>
                                                        {{-- <a class="btn btn-success btn-xs" href="{{ URL::to('demand-get-approve/'.$sc->id.'&3') }}" title="Approve Collection Quotation">
                                                            <i class="icon-check"> </i>
                                                        </a> --}}
                                                        <a class="btn btn-success btn-xs showModal" href="javascript:void(0)" title="Approve Quatition" attr-demandid-updateflds="{!! $sc->id.'&3&'.$sc->tenderId !!}">
                                                            <i class="icon-check"> </i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][18]) && $demandDetailPageFromRoute=='collection-quotation-acc' && $segTwo == 1 ){ ?>
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('create-collection-quotation/'.$sc->id.'/'.$sc->tenderId) }}" title="Create Quotation Collection">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][18])  && $demandDetailPageFromRoute=='collection-quotation-acc' && $segTwo == 3 ){ ?>
                                                        <!-- <a class="btn btn-danger btn-xs" href="{{ URL::to('delete-collection-quotation/'.$sc->id.'/'.$sc->tenderId) }}" title="Edit Collection Quotation">
                                                            <i class="fa fa-trash"></i>
                                                        </a> -->
                                                        <button class="qutationColDelete btn btn-danger btn-xs" id="{{$sc->id}}" attr-tenderId="{!! $sc->tenderId !!}" type="button" data-placement="top" data-rel="tooltip" data-original-title="Delete" title="Delete Quotation Collection">
                                                        <i class='fa fa-trash'></i>
                                                        </button>
                                                    <?php } ?>
                                                    <!-- Added newly for edit Collection Quotation -->
                                                    <?php if(!empty(Session::get('acl')[34][18]) && !empty($sc->tender_floating) && $demandDetailPageFromRoute=='collection-quotation-acc' && ($segTwo == 2 ||  $segTwo == 3)){ ?>
                                                        <!-- <a class="btn btn-primary btn-xs" href="{{ URL::to('create-collection-quotation/'.$sc->id.'/'.$sc->tenderId) }}" title="Edit Collection Quotation">
                                                            <i class="icon-edit"></i>
                                                        </a> -->
                                                        <a class="btn btn-primary btn-xs" target="_blank" href="{{ URL::to('cst-view-print/'.$sc->id.'&'.$sc->tenderId) }}" title="CST Print">
                                                            <i class="icon-print"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <!-- End Added newly for edit Collection Quotation -->
                                                    <?php if( (!empty(Session::get('acl')[34][32]) || !empty(Session::get('acl')[34][33]) || !empty(Session::get('acl')[34][34]) || !empty(Session::get('acl')[34][19]) ) && $demandDetailPageFromRoute=='cst-view-acc' && ($segTwo == 1 || $segTwo == 2 || $segTwo == 3 || $segTwo == 4 || $segTwo == 5) ){ ?>
                                                        @if(!empty(Session::get('acl')[34][32]) && $segTwo == 1)
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('cst-view/'.$sc->id.'&'.$sc->tenderId.'&'.$segTwo) }}" title="CST view">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                        @endif
                                                        @if(!empty(Session::get('acl')[34][33]) && $segTwo == 2)
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('cst-view/'.$sc->id.'&'.$sc->tenderId.'&'.$segTwo) }}" title="CST view">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                        @endif
                                                        @if(!empty(Session::get('acl')[34][34]) && $segTwo == 3)
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('cst-view/'.$sc->id.'&'.$sc->tenderId.'&'.$segTwo) }}" title="CST view">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                        @endif
                                                        @if( !empty(Session::get('acl')[34][37]) && $segTwo == 5 )
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('cst-view/'.$sc->id.'&'.$sc->tenderId.'&'.$segTwo) }}" title="CST View" style="padding-right: 5px;">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                        @endif
                                                        @if( (!empty(Session::get('acl')[34][34]) || !empty(Session::get('acl')[34][33]) || !empty(Session::get('acl')[34][32]) ) && $segTwo == 4)
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('cst-view/'.$sc->id.'&'.$sc->tenderId.'&'.$segTwo) }}" title="Edit CST" style="padding-right: 5px;">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                        @endif
                                                    <?php } ?>
                                                    <?php if( (!empty(Session::get('acl')[34][32]) || !empty(Session::get('acl')[34][33]) || !empty(Session::get('acl')[34][34]) || !empty(Session::get('acl')[34][19]) ) && $demandDetailPageFromRoute=='cst-view-acc'){ ?>
                                                        <a class="btn btn-primary btn-xs" target="_blank" href="{{ URL::to('cst-view-print/'.$sc->id.'&'.$sc->tenderId) }}" title="CST Print">
                                                            <i class="icon-print"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if( (!empty(Session::get('acl')[34][32]) || !empty(Session::get('acl')[34][33]) || !empty(Session::get('acl')[34][34]) || !empty(Session::get('acl')[34][19]) ) && $demandDetailPageFromRoute=='cst-view-acc'){ ?>
                                                        <a class="btn btn-xs" href="{{ URL::to('cst-view-excel/'.$sc->id.'&'.$sc->tenderId) }}" title="CST Excel">
                                                            <i class="fa fa-file-excel-o" style="color: #19816f;font-size: 18px;" aria-hidden="true"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if( (!empty(Session::get('acl')[34][32]) || !empty(Session::get('acl')[34][33]) || !empty(Session::get('acl')[34][34]) || !empty(Session::get('acl')[34][19]) ) && $demandDetailPageFromRoute=='cst-view-acc' && $segTwo == 4){ ?>
                                                        /<br><a class="btn btn-warning btn-xs" target="_blank" href="{{ URL::to('draft-cst-view-print/'.$sc->id.'&'.$sc->tenderId) }}" title="NSSD CST Print">
                                                            <i class="icon-print"></i>
                                                        </a>
                                                        <a class="btn btn-xs" href="{{ URL::to('draft-cst-view-excel/'.$sc->id.'&'.$sc->tenderId) }}" title="NSSD CST Excel">
                                                            <i class="fa fa-file-excel-o" style="color: #19816f;font-size: 18px;" aria-hidden="true"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <!-- Added newly for edit CST view -->
                                                    <?php if(!empty(Session::get('acl')[34][19]) && !empty($sc->tender_quation_collection) && $demandDetailPageFromRoute=='cst-view-acc' && $segTwo == 2){ ?>
                                                        <!-- <a class="btn btn-primary btn-xs" href="{{ URL::to('cst-view/'.$sc->id.'&'.$sc->tenderId.'&'.$segTwo) }}" title="Edit CST view">
                                                            <i class="icon-edit"></i>
                                                        </a> -->
                                                    <?php } ?>
                                                    <!-- End Added newly for edit CST view -->
                                                    <?php if(!empty(Session::get('acl')[34][20]) && $demandDetailPageFromRoute=='draft-cst-view-acc' && $segTwo == 1){ ?>
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('draft-cst-view/'.$sc->id.'&'.$sc->tenderId.'&1') }}" title="NSSD CST view">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                        <a class="btn btn-primary btn-xs" target="_blank" href="{{ URL::to('draft-cst-view-print/'.$sc->id.'&'.$sc->tenderId) }}" title="NSSD CST Print">
                                                            <i class="icon-print"></i>
                                                        </a>
                                                        <a class="btn btn-xs" href="{{ URL::to('draft-cst-view-excel/'.$sc->id.'&'.$sc->tenderId) }}" title="CST Excel">
                                                            <i class="fa fa-file-excel-o" style="color: #19816f;font-size: 18px;" aria-hidden="true"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <!-- Added newly for edit draft CST view -->
                                                    <?php if(!empty(Session::get('acl')[34][20]) && !empty($sc->cst_draft_status) && $demandDetailPageFromRoute=='draft-cst-view-acc' && $segTwo == 2){ ?>
                                                        <a class="btn btn-primary btn-xs" href="{{ URL::to('draft-cst-view/'.$sc->id.'&'.$sc->tenderId.'&2') }}" title="NSSD CST">
                                                            <i class="icon-edit"></i>
                                                        </a>
                                                        <a class="btn btn-primary btn-xs" target="_blank" href="{{ URL::to('draft-cst-view-print/'.$sc->id.'&'.$sc->tenderId) }}" title="NSSD CST Print">
                                                            <i class="icon-print"></i>
                                                        </a>
                                                         <a class="btn btn-xs" href="{{ URL::to('draft-cst-view-excel/'.$sc->id.'&'.$sc->tenderId) }}" title="NSSD CST Excel">
                                                            <i class="fa fa-file-excel-o" style="color: #19816f;font-size: 18px;" aria-hidden="true"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <!-- End Added newly for edit draft CST view -->
                                                    <?php if(!empty(Session::get('acl')[34][21]) && $demandDetailPageFromRoute=='hdq-approval-acc' && ($segTwo == 1 || $segTwo == 2) ){ ?>
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('headquarte-approval/'.$sc->id.'&'.$sc->tenderId.'&'.$segTwo) }}" title="NHQ Approval">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                        <a class="btn btn-primary btn-xs" target="_blank" href="{{ URL::to('nhq-cst-view-print/'.$sc->id.'&'.$sc->tenderId) }}" title="NHQ CST Print">
                                                            <i class="icon-print"></i>
                                                        </a>
                                                         <a class="btn btn-xs" href="{{ URL::to('draft-cst-view-excel/'.$sc->id.'&'.$sc->tenderId) }}" title="NHQ CST Excel">
                                                            <i class="fa fa-file-excel-o" style="color: #19816f;font-size: 18px;" aria-hidden="true"></i>
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
                                        <td colspan="7">{{'Empty Data'}}</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table><!---/table-responsive-->
                            @if($demandDetailPageFromRoute=='group-check-acc' && $segTwo==3 )
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary pull-right">{!! 'Create LPR' !!}</button>
                                </div>
                                {!!   Form::close() !!}
                            @endif
                            </div>
                            
                        <?php 
                            $segments = \Request::segments();
                            $routeName = $segments[0].(isset($segments[1]) ? '/'.$segments[1] : '');

                        ?>
                        @if($routeName !='group-check-acc/3' )
                            {{ $demands->appends(Request::except('page'))->links()}}
                        @endif 

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

            $(document).on('click','#all_check',function(){
                if(this.checked){
                    $('.selectlpr').each(function(){
                        this.checked = true;
                    });
                }else{
                     $('.selectlpr').each(function(){
                        this.checked = false;
                    });
                }
            });

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
                var url='{!! URL::to('demand-delete') !!}'+'/'+id;
                bootbox.confirm("Are you sure?", function (result) {
                    if (result) {
                        var _url = $("#_url").val();
                        window.location.href = url;
                    }
                });
            });

            $(".qutationColDelete").click(function (e) {
                e.preventDefault();
                
                var id = this.id;
                var lprId = $(this).attr('attr-tenderId');
                var url='{!! URL::to('delete-collection-quotation') !!}'+'/'+id+'/'+lprId;
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