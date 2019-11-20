@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Participant</h3>
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
                        <?php if(!empty(Session::get('acl')[35][2])){ ?>
                        <div class="pull-right">
                            <a class="btn btn-info btn-effect-ripple" href="{{ URL::to('schedule-create') }}"><i class="fa fa-plus"></i> Add Participant</a>
                        </div>
                        <?php } ?>
                        <h3>Participant</h3>
                    </div>
                        <div class="panel-body">

                            <div class="row">

                            {{ Form::open(array('role' => 'form', 'url' => 'schedule-all', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'schedule-all')) }}

                             <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">Participant Search: </label>
                                        {!!  Form::text('schedual_search', $schedual_search, array('id'=> 'schedual_search', 'class' => 'form-control', 'autocomplete'=> 'off','placeholder' => 'Tender No./Supplier No.')) !!}
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
                                    <div class="col-md-12" style="padding-top: 5px;">
                                        <label for="submit"></label>
                                        <button type="submit" class="form-control btn btn-primary">{!! 'Search' !!}</button>
                                    </div>
                                </div>
                            </div>
                                  

                            {!!   Form::close() !!}    

                            </div>

                            <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Tender Number'}}</th>
                                    
                                    <th class="text-center">{{'Tender Title'}}</th>
                                    <th class="text-center">{{'Company Name'}}</th>
                                    <th class="text-center">{{'Supplier Barcode'}}</th>
                                    <th class="text-center">{{'Purchase Date'}}</th>
                                    <?php if(!empty(Session::get('acl')[34][25]) || !empty(Session::get('acl')[35][1])){ ?>
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
                                            <td>@if(!empty($sc->tender_number)) {!! $sc->tender_number !!} @endif</td>
                                            <td>@if(!empty($sc->tender_id)) {!! isset($sc->tenderTitle)? $sc->tenderTitle->tender_title : null !!} @endif</td>
                                            <td>@if(!empty($sc->supplier_id)) {!! $sc->supplierName->company_name !!} @endif</td>
                                            <td>@if(!empty($sc->supplier_reg_no_ro_brc)) {!! $sc->supplier_reg_no_ro_brc !!} @endif</td>
                                            <td>@if(!empty($sc->supplier_reg_no_ro_brc)) {!! date('d-m-Y',strtotime($sc->created_at)) !!} @endif</td>
                                            <td class="action-center">
                                                <div class="text-center">

                                                    <?php if(!empty(Session::get('acl')[35][2])){ ?>
                                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('print-schedule/' . $sc->id) }}" title="Print" target="_blank">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                    <?php } ?>

                                                    <?php if(!empty(Session::get('acl')[35][4])){?>
                                                    <button class="exbtovdelete btn btn-danger btn-xs" id="{{$sc->id}}" type="button" data-placement="top" data-rel="tooltip" data-original-title="Delete" title="Delete">
                                                        <i class='fa fa-trash'></i>
                                                    </button>
                                                   <?php }?>

                                                </div>
                                            </td>
                                            
                                        </tr>
                                    @endforeach
                                
                                @else
                                    <tr>
                                        <td colspan="8">{{'Empty Data'}}</td>
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
                var url='{!! URL::to('/schedule/destroy') !!}'+'/'+id;
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