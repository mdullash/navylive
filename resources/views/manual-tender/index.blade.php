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
                    <h3>Manual Tenders</h3>
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

                            <h3>Manual Tenders

                                <div class="pull-right">
                                   <a class="btn btn-info btn-effect-ripple" href="{{ URL::to('direct-item-dmnd-create') }}"><i class="fa fa-plus"></i> Create Tender</a>
                                </div>


                              <?php if(!empty(Session::get('acl')[13][2])){ ?>
                                <div class="pull-right">
                                    <a class="btn btn-warning btn-effect-ripple" href="{{ URL::to('manual-tender/create') }}"><i class="fa fa-plus"></i> Create Manual Tender</a>
                                </div>
                                <?php } ?>
                            </h3>
                    </div>
                        <div class="panel-body">

                            <div class="row">

                                {{ Form::open(array('role' => 'form', 'url' => 'manual-tender/view', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'supplier-report')) }}

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="email">Organization: </label>
                                            <select class="form-control selectpicker" name="nsd_id" id="nsd_id"  data-live-search="true">
                                                <option value="">{!! '- Select -' !!}</option>
                                                @foreach($nsdNames as $nn)
                                                    <option value="{!! $nn->id !!}" @if($nn->id==$nsd_id) {{'selected'}} @endif>{!! $nn->name !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="company_mobile">Tender title/ Tender number: </label>
                                            {!!  Form::text('key', $key, array('id'=> 'key', 'class' => 'form-control', 'autocomplete'=> 'off')) !!}
                                        </div>
                                    </div>
                                </div>

                                 <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="email">From: </label>
                                            {!!  Form::text('from', $from, array('id'=> 'from', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off')) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="email">To:</label>
                                            {!!  Form::text('to', $to, array('id'=> 'to', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off')) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="col-md-12" style="padding-top: 22px;">
                                            <label for="email"></label>
                                            <button type="submit" class="btn btn-primary">{!! 'Search' !!}</button>
                                        </div>
                                    </div>
                                </div>

                                {!!   Form::close() !!}

                            </div>

                            <ul class="nav nav-tabs">


                                <?php if(!empty(Session::get('acl')[34][17])){ ?>
                                <li ><a href="{{URL::to('floating-tender-acc/1')}}"> Pending</a></li>

                                <?php if(!empty(Session::get('acl')[34][29])){ ?>
                                <li><a href="{{URL::to('retender-view-acc/1')}}"> Retender</a></li>
                                <?php } ?>

                                <?php if(!empty(Session::get('acl')[34][26]) || !empty(Session::get('acl')[34][17])){ ?>
                                <li><a href="{{URL::to('floating-tender-acc/3')}}"> Waiting for Approve</a></li>
                                <?php } ?>

                                <li ><a href="{{URL::to('floating-tender-acc/2')}}"> Approved</a></li>

                                <li @if(\Request::segment(1)=='manual-tender')class="active" @endif><a href="{{URL::to('manual-tender/view')}}"> Manual Tender</a></li>


                                {{-- <li @if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==4)class="active" @endif><a href="{{URL::to('floating-tender-acc/4')}}"> Edit Tender</a></li> --}}
                                <?php } ?>
                            </ul>


                            <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center" width="10%">{{'Tender Title/Name'}}</th>
                                    <th class="text-center">{{'Tender Number'}}</th>

                                    <th class="text-center" width="">{{'Tender Opening Date'}}</th>
                                    <th class="text-center" width="">{{'Tender Group'}}</th>

                                    <!-- <th class="text-center" width="">{{'Tender Open'}}</th> -->

                                    <th class="text-center" width="">{{'Tender Type'}}</th>
{{--                                    <th class="text-center" width="">{{'Tender Priority'}}</th>--}}
{{--                                    <th class="text-center" width="">{{'Tender Nature'}}</th>--}}
                                    <th class="text-center" width="">{{'Specification'}}</th>
                                    <th class="text-center" width="">{{'Notice'}}</th>
                                    <th class="text-center">{{trans('english.STATUS')}}</th>
                                    <?php if(!empty(Session::get('acl')[13][3]) || !empty(Session::get('acl')[13][4])){ ?>
                                    <th class="text-center"> {!!trans('english.ACTION')!!}
                                    </th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>

                                @if (!$tenders->isEmpty())

                                    <?php
                                        $page = \Input::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page-1)*10;
                                        $l = 1;
                                    ?>
                                    @foreach($tenders as $sc)
                                        <tr>
                                            <td>{{++$sl}}</td>
                                            <td>{{$sc->tender_title}}</td>
                                            <td>{{$sc->tender_number}}</td>

                                            <td>{{date('d-m-Y',strtotime($sc->tender_opening_date))}}</td>
                                            <td>{{$sc->supplyCategoryName->name}}</td>

                                            <!-- <td>
                                                @if(!empty($sc->open_tender))
                                                    {!! 'Yes' !!}
                                                @else
                                                    {!! 'No' !!}
                                                @endif
                                            </td> -->

                                            <td>
                                                @if($sc->tender_type == 1) {!! 'LTM- Limited Tender Method' !!}
                                                @elseif($sc->tender_type == 2) {!! 'OTM- Open Tender Method' !!}
                                                @elseif($sc->tender_type == 3) {!! 'RTM- Restricted Tender Method' !!}
                                                @elseif($sc->tender_type == 4) {!! 'Spot Tender' !!}
                                                @elseif($sc->tender_type == 5) {!! 'DPM- Direct Purchase Method' !!}
                                                @endif
                                            </td>

                                            <td style="text-align: center;">
                                                @if(!empty($sc->specification))
                                                    <a href="{{url('tender/specification-pdf/'.encrypt($sc->id))}}" target="_blank"><img width="30" height="30" src="{{URL::to('/')}}/public/uploads/gallery/pdf_icon.png"></a>
                                                @endif
                                                @if(!empty($sc->specification_doc))
                                                    &nbsp;&nbsp;<a href="{{url('tender/specification-doc/'.encrypt($sc->id))}}" target="_blank"><img width="30" height="30" src="{{URL::to('/')}}/public/uploads/gallery/word-icon.png"></a>
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                @if(!empty($sc->notice))
                                                    <a href="{{url('tender/notice-pdf/'.encrypt($sc->id))}}" target="_blank"><img width="30" height="30" src="{{URL::to('/')}}/public/uploads/gallery/pdf_icon.png"></a>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($sc->status_id == '1')
                                                    <span class="label label-success">{!! 'Published' !!}</span>
                                                @else
                                                    <span class="label label-warning">{!! 'Unpublished' !!}</span>
                                                @endif
                                            </td>
                                            <?php if(!empty(Session::get('acl')[13][3]) || !empty(Session::get('acl')[13][4])){ ?>
                                            <td class="action-center">
                                                <div class="text-center">


                                                    <?php if(!empty(Session::get('acl')[13][3])){ ?>
                                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('manual-tender/edit/' . $sc->id) }}" title="Edit">
                                                        <i class="icon-edit"></i>
                                                    </a>
                                                    <?php } ?>

                                                    <?php if(!empty(Session::get('acl')[13][4])){?>
                                                    <button class="exbtovdelete btn btn-danger btn-xs" id="{{$sc->id}}" type="button" data-placement="top" data-rel="tooltip" data-original-title="Delete" title="Delete">
                                                        <i class='fa fa-trash'></i>
                                                    </button>
                                                   <?php }?>





                                                </div>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                    @endforeach

                                @else
                                    <tr>
                                        <td colspan="16">{{'Empty Data'}}</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table><!---/table-responsive-->
                            </div>
                            {{ $tenders->links()}}

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
                var url='{!! URL::to('manual-tender/destroy') !!}'+'/'+id;
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
