@extends('layouts.default')

<style type="text/css">

    .custom-file-upload {
        border: 1px solid #ccc;
        display: inline-block;
        padding: 6px 12px;
        cursor: pointer;
    }

    .paddingClass {
        padding-top: 10px;
    }
</style>

@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                  Approved Suppliers
                </h2>
            </div>
            @include('layouts.flash')
        </div>
    </div>

    <div class="content animate-panel">
        <div class="row">
            <div class="col-sm-6 col-md-12">
                <div class="hpanel">
                    <div class="panel-heading sub-title">
                        Approved Suppliers
                    </div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="approved_top">
                                <div class="col-md-6">
                                    <div class="row paddingClass ">
                                        <div class="col-md-12">
                                            <div class="form-group d-flex">
                                                <label for="requester" class="text-left mr_5">Date:</label>
                                                {!! $suppliers->date !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row paddingClass">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <label for="inclusser">{!! 'Enclosure' !!}</label>
                                                </div>
                                                <div class="col-md-12">
                                                    {!! $suppliers->encloser !!}

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row paddingClass">
                                        <div class="col-md-12">
                                            <div class="form-group d-flex">
                                                <label for="po_number" class="mr_5">Letter No:</label>
                                                {!! $suppliers->letter_no !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row paddingClass">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <label for="info">{!! 'Info' !!}</label>
                                                </div>
                                                <div class="col-md-12">

                                                    {!! $suppliers->info !!}


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!--/approved_top-->



                            <div class="col-md-4">
                                <div class="row paddingClass">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="info"></label>
                                            </div>
                                            <div class="col-md-12">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped middle-align">
                                    <thead>
                                    <tr class="center">
                                        <th class="text-center" width="5%">SL#</th>
                                        <th class="text-center">{{'Suppliers'}}</th>
                                        <th class="text-center" width="">{{'DNI Description '}}</th>
                                        <th class="text-center" width="">{{' NPM Description '}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if (!empty($suppliers->supplierInfos()->get()))
                                        <?php
                                        $sl = 0;
                                        ?>
                                        @foreach($suppliers->supplierInfos()->get() as $sc)
                                            <tr>
                                                <td>{{++$sl}}</td>
                                                <td>{{$sc->company_name}},<br>{{$sc->head_office_address}},<br>{{$sc->	email}},<br>{{$sc->mobile_number}}</td>
                                                <td>{{ $sc['pivot']->dni_description }}</td>
                                                <td>{{ $sc['pivot']->npm_description }}</td>
                                            </tr>

                                        @endforeach

                                    @else
                                        <tr>
                                            <td colspan="3">{{'Empty Data'}}</td>
                                        </tr>
                                    @endif

                                    </tbody>
                                </table><!---/table-responsive-->
                            </div>
                            <a href="{{URL::previous()}}" class="btn btn-danger cancel pull-left">{!! 'Back' !!}</a>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



@stop

