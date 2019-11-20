@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Tender</h3>
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
                        <?php if(!empty(Session::get('acl')[13][2])){ ?>
                        <div class="pull-right">
                         <a class="btn btn-info btn-effect-ripple" href="{{ URL::to('tender/create') }}"><i class="fa fa-plus"></i> Create Direct Tender</a>
                        </div>
                        <?php } ?>
                            <h3>Tender</h3>
                    </div>
                        <div class="panel-body">

                            <div class="row">

                                {{ Form::open(array('role' => 'form', 'url' => 'tender/view', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'supplier-report')) }}

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

                            <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center" width="10%">{{'Tender Title/Name'}}</th>
                                    <th class="text-center">{{'Tender Number'}}</th>
                                    <th class="text-center">{{'Demand Number'}}</th>
                                    <th class="text-center" width="">{{'Tender Opening Date'}}</th>
                                    <th class="text-center" width="">{{'Tender Group'}}</th>
                                    <th class="text-center" width="">{{'Organization'}}</th>
                                    <!-- <th class="text-center" width="">{{'Tender Open'}}</th> -->
                                    <th class="text-center" width="">{{'Valid From'}}</th>
                                    <th class="text-center" width="">{{'Valid To'}}</th>
                                    <th class="text-center" width="">{{'Tender Type'}}</th>
                                    <th class="text-center" width="">{{'Tender Priority'}}</th>
                                    <th class="text-center" width="">{{'Tender Nature'}}</th>
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
                                            <td>{{$sc->ref_tender_id}}</td>
                                            <td>{{date('d-m-Y',strtotime($sc->tender_opening_date))}}</td>
                                            <td>{{$sc->supplyCategoryName->name}}</td>
                                            <td>{{$sc->nsdName->name}}</td>
                                            <!-- <td>
                                                @if(!empty($sc->open_tender))
                                                    {!! 'Yes' !!}
                                                @else
                                                    {!! 'No' !!}
                                                @endif
                                            </td> -->
                                            <td>
                                                @if(!empty($sc->valid_date_from)) {!! date('d-m-Y', strtotime($sc->valid_date_from)) !!} @endif
                                            </td>
                                            <td>
                                                @if(!empty($sc->valid_date_to)) {!! date('d-m-Y', strtotime($sc->valid_date_to)) !!} @endif
                                            </td>
                                            <td>
                                                @if($sc->tender_type == 1) {!! 'LTM- Limited Tender Method' !!}  
                                                @elseif($sc->tender_type == 2) {!! 'OTM- Open Tender Method' !!}  
                                                @elseif($sc->tender_type == 3) {!! 'RTM- Restricted Tender Method' !!}
                                                @elseif($sc->tender_type == 4) {!! 'Spot Tender' !!} 
                                                @elseif($sc->tender_type == 5) {!! 'DPM- Direct Purchase Method' !!}  
                                                @endif
                                            </td>
                                            <td>
                                                @if($sc->tender_priority == 1) {!! 'Normal' !!}  
                                                @elseif($sc->tender_priority == 2) {!! 'Immediate' !!}  
                                                @elseif($sc->tender_priority == 3) {!! 'OPS Immediate (Operational Immediate)' !!}  
                                                @endif
                                            </td>
                                            <td>
                                                @if($sc->tender_nature == 1) {!! 'Line Item' !!}  
                                                @elseif($sc->tender_nature == 2) {!! 'Lot Item' !!}   
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
                                                    
                                                    <a class="btn btn-info btn-xs" href="{{ URL::to('itemtotender/create/' . $sc->id) }}" id="{{$sc->id}}" type="button" data-placement="top" title="Item To Tender">
                                                        <i class='icon-cog'></i>
                                                    </a>

                                                    <?php if(!empty(Session::get('acl')[13][3])){ ?>
                                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('tender/edit/' . $sc->id) }}" title="Edit">
                                                        <i class="icon-edit"></i>
                                                    </a>
                                                    <?php } ?>

                                                    <?php if(!empty(Session::get('acl')[13][4])){?>
                                                    <button class="exbtovdelete btn btn-danger btn-xs" id="{{$sc->id}}" type="button" data-placement="top" data-rel="tooltip" data-original-title="Delete" title="Delete">
                                                        <i class='fa fa-trash'></i>
                                                    </button>
                                                   <?php }?>

                                                   <?php if(!empty(Session::get('acl')[34][25]) && !empty($sc->demand_no)){ ?>
                                                    <a class="btn btn-success btn-xs" href="{{ URL::to('schedule-create') }}" title="Schedule">
                                                        <i class="fa fa-shopping-cart"></i>
                                                    </a>
                                                    <?php } ?>

                                                   <?php if(!empty(Session::get('acl')[34][18]) && !empty($sc->demand_no)){ ?>
                                                        <a class="btn btn-primary btn-xs" href="{{ URL::to('demand-details/'.$sc->demand_no) }}" title="Add Collection Quotation" target="_blank">
                                                            <i class="icon-edit"> </i>
                                                        </a>
                                                    <?php } ?> 

                                                </div>
                                            </td>
                                            <?php } ?>
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
                var url='{!! URL::to('tender/destroy') !!}'+'/'+id;
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