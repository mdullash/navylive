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
                    <h3>Issue</h3>
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
                            <h3>Issue</h3>
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
                                                <label for="demand_no">CR No: </label>
                                                {!!  Form::text('cr_no', $cr_no, array('id'=> 'cr_no', 'class' => 'form-control', 'autocomplete'=> 'off')) !!}
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
                                    <?php if($demandDetailPageFromRoute=='issue'){ ?>
                                        <li @if($demandDetailPageFromRoute=='issue' && $segTwo==1)class="active" @endif><a href="{{URL::to('issue/1')}}"> Pending</a></li>
                                    <?php } ?>

                                        <?php if($demandDetailPageFromRoute=='issue'){ ?>
                                        <li @if($demandDetailPageFromRoute=='issue' && $segTwo==2)class="active" @endif><a href="{{URL::to('issue/2')}}"> Waiting for Issue</a></li>
                                        <?php } ?>
                                            
                                    <?php if($demandDetailPageFromRoute=='issue'){ ?>
                                    <li @if($demandDetailPageFromRoute=='issue' && $segTwo==3)class="active" @endif><a href="{{URL::to('issue/3')}}"> Waiting for approve</a></li>
                                    <?php } ?>
                                    <li @if($demandDetailPageFromRoute=='issue' && $segTwo==4)class="active" @endif><a href="{{URL::to('issue/4')}}"> Approved</a></li>
                                    <li @if($demandDetailPageFromRoute=='issue' && $segTwo==5)class="active" @endif><a href="{{URL::to('issue/5')}}"> Rejected </a></li>

                                </ul>
                            <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'CR Number'}}</th>
                                    <th class="text-center">{{'Tender Title'}}</th>
                                    <th class="text-center">{{'Supplier Name'}}</th>
                                    <th class="text-center">{{'Generated Date'}}</th>
                                    <th class="text-center">{{'Total Quantity'}}</th>
                                    <?php if(!empty(Session::get('acl')[53][31]) ){ ?>
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
                                            <td>{!! $l++ !!}</td>
                                            <td>{!! $sc->cr_number !!}</td>
                                            <td>{!! $sc->tender_title !!}</td>
                                            <td>{!! $sc->company_name !!}</td>
                                            <td>@if(!empty($sc->top_date)) {!! date('Y-m-d', strtotime($sc->top_date)) !!} @endif</td>
                                            <td>{!! $sc->cr_receive_qty !!}</td>
                                            <?php if(!empty(Session::get('acl')[53][1]) || !empty(Session::get('acl')[53][2])){ ?>
                                            <td>
                                                @if($segTwo==1 )
                                                    @if(!empty(Session::get('acl')[53][2]))
                                                    <a class="btn btn-success btn-xs" href="{{ URL::to('issue-view/'.$sc->id) }}" title="Issue view">
                                                        <i class="icon-check"></i>
                                                    </a>
                                                @endif
                                                @endif

                                                @if($segTwo==2 || $segTwo==3 || $segTwo==4)
                                                        @if(!empty(Session::get('acl')[53][2]))
                                                        <a class="btn btn-primary btn-xs" href="{{ URL::to('issue-view/'.$sc->id) }}" title="Issue view">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                        @endif
                                                     @if(!empty(Session::get('acl')[53][1]))
                                                    <a class="btn btn-success btn-xs" href="{{ URL::to('issue-pdf-view/'.$sc->id.'&1') }}" title="Issue Check" target="_blank">
                                                            <i class="icon-eye-open"> </i>
                                                    </a>

                                                @endif
                                                @endif

                                                    @if($segTwo==4)

                                                    <a class="btn btn-xs btn-warning" href="{{ URL::to('issue-pdf-view/'.$sc->id.'&2') }}" title="Print" target="_blank">
                                                        <i class="glyphicon glyphicon-print"></i>
                                                    </a>
                                                    @endif


                                                    @if($segTwo==5)
                                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('issue-view/'.$sc->id) }}" title="Issue view">
                                                        <i class="icon-check"></i>
                                                    </a>
                                                   @endif

                                            </td>
                                            <?php } ?>
                                        </tr>
                                    @endforeach
                                
                                @else
                                    <tr>
                                        <td colspan="6">{{'Empty Data'}}</td>
                                    </tr>
                                @endif
                                    
                                </tbody>
                            </table><!---/table-responsive-->
                            </div>

                            {{ $demands->links()}}

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
          {{ Form::open(array('role' => 'form', 'url' => 'v44voucher-appprove-post', 'files'=> true, 'class' => 'demand-pending-post', 'id'=>'demand-pending-post')) }}
                <input type="hidden" name="d44bid" id="d44bid" value="">
                

                <div class="form-group"><label class="control-label" for="status">Status :<span class="text-danger">*</span></label>
                    {{ Form::select('d44b_app', array('1' => 'Approved', '2' =>'Reject'), '', array('class' => 'form-control selectpicker', 'id' => 'd44b_app','required')) }}
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

            $(document).on('click','.showModal',function(){
                var attrVlues = $(this).attr('attr-demandid-updateflds');
                
                $("#d44bid").val('');
                

                $("#d44bid").val(attrVlues);

                $('#myModal').modal('show');
            });

            /*For Delete Department*/
            $(".exbtovdelete").click(function (e) {
                e.preventDefault();
                
                var id = this.id; 
                var url='{!! URL::to('/item/destroy') !!}'+'/'+id;
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