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
                    <h3>CST Forwarding</h3>
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
                        <?php if(!empty(Session::get('acl')[34][2]) ){ ?>
                        <?php } ?>
                            <h3>CST Forwarding Pending</h3>
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
                                    <?php if(!empty(Session::get('acl')[34][22]) ){ ?>
                                        <li @if($segTwo=="pending")class="active" @endif><a href="{{URL::to('cst-forwarding/pending')}}"> Pending</a></li>
                                    <?php } ?>
                                    <?php if(!empty(Session::get('acl')[34][31]) ){ ?>
                                        <li @if($segTwo=="waiting-for-approved")class="active" @endif><a href="{{URL::to('/cst-forwarding/waiting-for-approved')}}"> Waiting for approve</a></li>
                                    <?php } ?>
                                    <li @if($segTwo=="approved")class="active" @endif><a href="{{URL::to('/cst-forwarding/approved')}}"> Approved</a></li>
                                </ul>
                             <div class="table-responsive">

                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Demanding'}}</th>
                                    <th class="text-center">{{'Demand No'}}</th>
                                    <th class="text-center">{{'Items & Quantity'}}</th>
                                    <th class="text-center">{{'Tender Number'}}</th>
                                    <th class="text-center">{{'Quantity'}}</th>
                                    <th class="text-center">{{'Action'}}</th>
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
		                                <?php
		                                $cstForwarding = \App\CstForwarding::where('tender_id','=',$sc->tenderId)->first();
		                                ?>

                                            <tr>
                                                <td>{{++$sl}}</td>


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

                                                <td style="word-break: break-all;">{!! $sc->demand_no !!}</td>
                                                <td>
					                                <?php
					                                if(!empty($sc->itemsToDemand) && count($sc->itemsToDemand) < 1 && isset($sc->tenderId)){
						                                $sc->itemsToDemand = \App\ItemToDemand::where('tender_no','=',$sc->tenderId)->where('lpr_id','=',$sc->id)->get();
					                                }
					                                $remComma = 1;
					                                $num_of_items = !empty($sc->itemsToDemand)?count($sc->itemsToDemand):0;
					                                ?>
                                                    @if(!empty($sc->itemsToDemand) && count($sc->itemsToDemand) > 0)
                                                        @foreach($sc->itemsToDemand as $ke => $itmsf)
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
                                                            {!! ')' !!}
                                                            @if($num_of_items > $remComma)
                                                                {!! '; ' !!}
                                                            @endif
							                                <?php $remComma++; ?>
                                                        @endforeach
                                                    @endif
                                                </td>

                                                <td>{!! $sc->tender_number !!}</td>
                                                <td>{!! $sc->total_unit !!}</td>
                                                <td>
                                                    @if(!empty(Session::get('acl')[54][9]))
                                                    <a href="{{url('/cst-forwarding/print/'.$sc->cstId)}}" target="_blank" class="btn btn-xs btn-primary">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                    @endif
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

                            {{-- {{ $demands->appends(Request::except('page'))->links()}} --}}

                        </div>
                    </div>
                </div>
            </div>

    </div>
@stop