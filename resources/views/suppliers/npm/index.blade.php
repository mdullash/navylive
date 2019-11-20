@extends('layouts.default')

<style type="text/css">
    /*/ Tab Navigation /*/
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
                    <h3>NPM</h3>
                </h2>
            </div>

        </div>
    </div>
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading hbuilt">

                        <h3>NPM</h3>
                    </div>
                    <div class="panel-body">

                        <div class="row">

                            {{ Form::open(array('role' => 'form', 'url' => 'suppliers/npm/index/'.$status, 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'supplier-report')) }}



                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">Letter No:</label>
                                        {!!  Form::text('letter_no', $letter_no, array('id'=> 'letter_no', 'class' => 'form-control', 'autocomplete'=> 'off')) !!}
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">From: </label>
                                        {!!  Form::text('from', $from, array('id'=> 'from', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">To:</label>
                                        {!!  Form::text('to', $to, array('id'=> 'to', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off')) !!}
                                    </div>
                                </div>
                            </div>



                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="col-md-12" style="">
                                        <label for="email"></label>
                                        <button type="submit" class="btn btn-primary">{!! 'Search' !!}</button>
                                    </div>
                                </div>
                            </div>

                            {!!   Form::close() !!}

                        </div>
                        <ul class="nav nav-tabs">
                            <?php
                            $segment=Request::segment(4);
                            $segment2=Request::segment(2);

                            ?>
                                <li @if($segment=='pending') class="active" @endif><a href="{{URL::to('suppliers/npm/index/pending')}}">Pending</a></li>
                                <li @if($segment=='waiting-for-approve') class="active" @endif><a href="{{URL::to('suppliers/npm/index/waiting-for-approve')}}">Waiting for approve</a></li>
                                <li @if($segment=='approved') class="active" @endif><a href="{{URL::to('suppliers/npm/index/approved')}}">Approved</a></li>

                        </ul>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Date'}}</th>
                                    <th class="text-center" width="">{{'Letter No'}}</th>
                                    <th class="text-center" width="">{{'Info'}}</th>
                                    <th class="text-center" width="">{{'Encloser'}}</th>
                                    <th class="text-center" width="">{{'NPM Status'}}</th>
                                    <?php if(!empty(Session::get('acl')[46][1]) || !empty(Session::get('acl')[46][1])){ ?>
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
                                            <td>{{ $sc->date }}</td>
                                            <td>{{ $sc->letter_no }}</td>
                                            <td>{{ $sc->info}}</td>
                                            <td>{{ $sc->encloser }}</td>
                                            <td><span class="label label-warning">{{ ucfirst($sc->npm_status)  }}</span></td>

                                            <?php if(!empty(Session::get('acl')[50][2]) || !empty(Session::get('acl')[50][12])){ ?>
                                            <td class="action-center">
                                                <div class="text-center">



                                                    <?php if(!empty(Session::get('acl')[50][2])){ ?>


                                                    <a class="btn btn-success btn-xs" href="{{ URL::to('suppliers/npm/' . $sc->id . '/clearance') }}" title="Clarence">
                                                        <i class="icon-check"></i>
                                                    </a>

                                                    <?php } ?>

                                                </div>
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
                        {{-- {{ $suppliers->links()}} --}}
                        {!! $suppliers->appends(\Input::except('page'))->render() !!}

                    </div>
                </div>
            </div>
        </div>

    </div>





@stop