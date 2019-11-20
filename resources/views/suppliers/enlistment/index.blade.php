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
                    <h3>Enlistment Management</h3>
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
                        <?php if(!empty(Session::get('acl')[46][2])){ ?>
                        <div class="pull-right">
                            <a class="btn btn-info btn-effect-ripple" href="{{ URL::to('suppliers/enlistment/create') }}"><i class="fa fa-plus"></i> Add Enlistment</a>
                        </div>
                        <?php } ?>
                        <h3>Enlistments</h3>
                    </div>
                    <div class="panel-body">

                        <div class="row">

                            {{ Form::open(array('role' => 'form', 'url' => 'suppliers/enlistment/index/'.$status, 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'supplier-report')) }}

                            <div class="col-md-3">
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

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="company_mobile">Com. Name / Mob. No </label>
                                        {!!  Form::text('company_mobile', $company_mobile, array('id'=> 'company_mobile', 'class' => 'form-control', 'autocomplete'=> 'off')) !!}
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


                            <li @if($segment=='pending')class="active" @endif><a href="{{URL::to('suppliers/enlistment/index/pending')}}">Pending</a></li>
                            <li @if($segment=='waiting-for-supplier-submit')class="active" @endif><a href="{{URL::to('suppliers/enlistment/index/waiting-for-supplier-submit')}}">Waiting for supplier submit</a></li>
                            <li @if($segment=='waiting-for-approval')class="active" @endif><a href="{{URL::to('suppliers/enlistment/index/waiting-for-approval')}}">Waiting For Approval</a></li>
                            <li @if($segment=='approved')class="active" @endif><a href="{{URL::to('suppliers/enlistment/index/approved')}}">Approved</a></li>
                            <li @if($segment=='rejected')class="active" @endif><a href="{{URL::to('suppliers/enlistment/index/rejected')}}">Rejected</a></li>
                        </ul>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Company Name'}}</th>
                                    <th class="text-center" width="">{{'Mobile Number'}}</th>
                                    <th class="text-center">{{trans('Application')}}</th>
                                    <th class="text-center">{{trans('english.STATUS')}}</th>
                                    <?php if(!empty(Session::get('acl')[46][3]) || !empty(Session::get('acl')[46][4])){ ?>
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

                                    function supply_cat_name($cat_id=null){
                                        $calName = \App\SupplyCategory::where('id','=',$cat_id)->value('name');
                                        return $calName;
                                    }
                                    ?>
                                    @foreach($suppliers as $sc)
                                        <tr>
                                            <td>{{++$sl}}</td>
                                            <td>{{$sc->company_name}}</td>
                                            <td>{{$sc->mobile_number}}</td>

                                            <td style="text-align: center"> @if($sc->attested_application != null) <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#{!! $sc->id !!}view_pdf">
                                                  <i class="fa fa-eye" title="View Document"></i>
                                                </button> @endif </td>
                                            <td class="text-center">
                                                    <span class="label label-warning">{!! ucfirst($sc->enlistment_status) !!}</span>
                                            </td>
                                            <?php if(!empty(Session::get('acl')[46][3]) || !empty(Session::get('acl')[46][4])){ ?>
                                            <td class="action-center">
                                                <div class="text-center">

                                                        <?php if(!empty(Session::get('acl')[46][1])){ ?>
                                                        <a class="btn btn-primary btn-xs" href="{{ URL::to('/suppliers/view/'. $sc->id) }}" title="view" target="_blank">
                                                            <i class="icon-eye-open"></i>
                                                        </a>
                                                        <?php } ?>


                                                        @if($sc->enlistment_status=='waiting-for-approval')
                                                            <?php if(!empty(Session::get('acl')[46][3])){ ?>
                                                            <a class="btn btn-success btn-xs" href="{{ URL::to('suppliers/enlistment/' . $sc->id . '/supplier-info-approval') }}" title="Supplier info Approval">
                                                                <i class="icon-check"></i>
                                                            </a>
                                                            <?php } ?>
                                                        @endif



                                                        @if($sc->enlistment_status=='waiting-for-supplier-submit' || $sc->enlistment_status=='approved'|| $sc->enlistment_status=='rejected')
                                                            <?php if(!empty(Session::get('acl')[46][3])){ ?>
                                                            <a class="btn btn-success btn-xs" href="{{ URL::to('suppliers/enlistment/' . $sc->id . '/supplier-info') }}" title="Update Supplier Info">
                                                                <i class="icon-check"></i>
                                                            </a>
                                                            <?php } ?>
                                                        @endif

                                                    @if($sc->enlistment_status=='pending')
                                                    <?php if(!empty(Session::get('acl')[46][3])){ ?>
                                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('suppliers/enlistment/' . $sc->id . '/edit') }}" title="Edit">
                                                        <i class="icon-edit"></i>
                                                    </a>
                                                    <?php } ?>
                                                    @endif




                                                    <?php if(!empty(Session::get('acl')[46][4])){?>
                                                    <button class="exbtovdelete btn btn-danger btn-xs" id="{{$sc->id}}" type="button" data-placement="top" data-rel="tooltip" data-original-title="Delete" title="Delete">
                                                        <i class='fa fa-trash'></i>
                                                    </button>
                                                    <?php }?>


                                                </div>
                                            </td>
                                            <?php } ?>
                                        </tr>





                                        <!-- Modal -->
                                        <div class="modal fade" id="{!! $sc->id !!}view_pdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <object data="{!! asset('public/uploads/supplier_application_file/'. $sc->attested_application)  !!}" type="application/pdf" width="100%" height="100%">

                                                        </object>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    @endforeach

                                @else
                                    <tr>
                                        <td colspan="8">{{'Empty Data'}}</td>
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

    <script type="text/javascript">
        $(document).ready(function(){

            /*For Delete Department*/
            $(".exbtovdelete").click(function (e) {
                e.preventDefault();

                var id = this.id;
                var url='{!! URL::to('suppliers/enlistment/destroy') !!}'+'/'+id;
                bootbox.confirm("Are you sure?", function (result) {
                    if (result) {
                        var _url = $("#_url").val();
                        window.location.href = url;
                    }
                });
            });

            /*For Approve supplier*/
            $(".approve").click(function (e) {
                e.preventDefault();

                var id = this.id;
                var url='{!! URL::to('suppliers/enlistment/approved') !!}'+'/'+id;
                bootbox.confirm("Are you sure?", function (result) {
                    if (result) {
                        var _url = $("#_url").val();
                        window.location.href = url;
                    }
                });
            });
            $(".reject").click(function (e) {
                e.preventDefault();

                var id = this.id;
                var url='{!! URL::to('suppliers/enlistment/rejected') !!}'+'/'+id;
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