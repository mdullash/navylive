@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>ID card Purchase</h3>
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
                        <?php if(!empty(Session::get('acl')[58][2])){ ?>
                        <div class="pull-right">
                            <a class="btn btn-info btn-effect-ripple" href="{{ URL::to('suppliers/id-card-purchase/create') }}"><i class="fa fa-plus"></i> Add New</a>
                        </div>
                        <?php } ?>
                        <h3>ID card Purchase</h3>
                    </div>
                    <div class="panel-body">

                        <div class="row">

                            {{ Form::open(array('role' => 'form', 'url' => 'suppliers/id-card-purchase', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'schedule-all')) }}

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">Barcode: </label>
                                        {!!  Form::text('barcode_number', $barcode_number, array('id'=> 'barcode_number', 'class' => 'form-control', 'autocomplete'=> 'off','placeholder' => 'Barcode Number.')) !!}
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
                                    <th class="text-center">{{'Company Name'}}</th>
                                    <th class="text-center">{{'Supplier Name'}}</th>
                                    <th class="text-center">{{'Mobile Number'}}</th>
                                    <th class="text-center">{{'Barcode'}}</th>
                                    <th class="text-center">{{'Email'}}</th>
                                    <th class="text-center">{{'Amount'}}</th>
                                    <?php if(!empty(Session::get('acl')[58][2]) || !empty(Session::get('acl')[58][1])){ ?>
                                    <th class="text-center"> {!!trans('english.ACTION')!!}
                                    </th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>

                                @if (!$suppliers->isEmpty())

                                    <?php
                                    $page = \Input::get('page');
                                    $page = empty($page) ? 1 : $page;
                                    $sl = ($page-1)*10;
                                    $l = 1;

                                    ?>
                                    @foreach($suppliers as $sc)
                                        <tr>
                                            <td>{{++$sl}}</td>
                                            <td>@if(!empty($sc->company_name)) {!! $sc->company_name !!} @endif</td>
                                            <td>@if(!empty($sc->supplier_name)) {!! $sc->supplier_name !!} @endif</td>
                                            <td>@if(!empty($sc->mobile_number)) {!! $sc->mobile_number !!} @endif</td>
                                            <td>@if(!empty($sc->barcode_number)) {!! $sc->barcode_number !!} @endif</td>
                                            <td>@if(!empty($sc->email )) {!! $sc->email  !!} @endif</td>
                                            <td>@if(!empty($sc->amount )) {!! $sc->amount  !!} @endif</td>
                                            <td class="action-center">
                                                <div class="text-center">

                                                    <?php if(!empty(Session::get('acl')[58][2])){ ?>
                                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('suppliers/id-card-purchase/print-id-card-purchase/' . $sc->id) }}" title="Print" target="_blank">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                    <?php } ?>

                                                    <?php if(!empty(Session::get('acl')[58][4])){?>
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

                        {{ $suppliers->links()}}

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
                var url='{!! URL::to('/suppliers/id-card-purchase/destroy') !!}'+'/'+id;
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