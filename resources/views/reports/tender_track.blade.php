@extends('layouts.default')

<style type="text/css">
    /*/ /Tab Navigation /*/
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

    /*/ Tab Content /*/
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
                       Tender Tracking Report
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

                        <h3>
                            Tender Tracking
                        </h3>
                    </div>
                    <div class="panel-body">

                        <!-- Tab section -->

                            {{--Search statr =======================================--}}
                            <div class="row">
                                <div class="col-md-12">
                                    {{ Form::open(array('role' => 'form', 'url' => 'tender-track', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'schedule-all')) }}
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="requester">Demanding: </label>
                                            <select class="form-control selectpicker requester" name="requester" id="requester"  data-live-search="true">
                                                <option value="">{!! '- Select -' !!}</option>
                                                @foreach($demandeNames as $dmdn)
                                                    <option value="{!! $dmdn->id !!}">{!! $dmdn->name !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="email">Demand No: </label>
                                                {!!  Form::text('demand_no', null, array('id'=> 'demand_no', 'class' => 'form-control', 'autocomplete'=> 'off')) !!}
                                            </div>
                                        </div>
                                    </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <label for="email">Tender No: </label>
                                                    {!!  Form::text('tender_no', '', array('id'=> 'tender_no', 'class' => 'form-control', 'autocomplete'=> 'off')) !!}
                                                </div>
                                            </div>
                                        </div>

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
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{url("tender-track-download?requester=$demande&demand_no=$demand_no&tender_no=$tender_no&from=$from&todate=$todate")}}" class="btn btn-success btn-xs"><i class="fa fa-download"></i> {{'Export Excel'}}</a><br>
                            </div>
                        </div>
                        <br>

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Demanding'}}</th>
                                    <th class="text-center">{{'Demand No'}}</th>
                                    <th class="text-center">{{'Items'}}</th>
                                    <th class="text-center">{{'Tender Number'}}</th>
                                    <th class="text-center">{{'Tender Stage'}}</th>

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

                                            @if($sc->tender_positon_stage=='collection-quotation-acc' || ($sc->tender_positon_stage=='floating-tender-acc')
                                             || $sc->tender_positon_stage=='cst-view-acc' || $sc->tender_positon_stage=='draft-cst-view-acc'
                                              || $sc->tender_positon_stage=='hdq-approval-acc' || $sc->tender_positon_stage=='po-generation-acc'
                                              || $sc->tender_positon_stage=='cr-view-acc'  || $sc->tender_positon_stage=='inspection-view-acc'
                                               || $sc->tender_positon_stage=='v44-voucher-view-acc' || $sc->tender_positon_stage=='retender-view-acc'  || $sc->tender_positon_stage=='nil-return')
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
                                            <td>{!! $sc->tender_stage !!} :- {!! $sc->stage !!} </td>

                                        </tr>
                                    @endforeach

                                @else
                                    <tr>
                                        <td colspan="6">{{'Empty Data'}}</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table><!---/table-responsive-->
                            {!! $demands->appends(\Input::except('page'))->render() !!}

                        </div>



                    </div>
                </div>
            </div>

        </div>

    </div>






@stop