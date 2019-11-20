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
                    <h3>
                        Float Tender
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
                            Float Tender


                            @if($demandDetailPageFromRoute=='floating-tender-acc')
                                &nbsp; &nbsp; &nbsp;    <a class="btn btn-info btn-effect-ripple pull-right" href="{{ URL::to('direct-item-dmnd-create') }}"><i class="fa fa-plus"></i> Create Tender</a>
                            @endif

                            <?php if(!empty(Session::get('acl')[13][2])){ ?>
                            <div class="pull-right">
                                <a class="btn btn-warning btn-effect-ripple" href="{{ URL::to('manual-tender/create') }}"><i class="fa fa-plus"></i> Create Manual Tender</a>
                            </div>
                            <?php } ?>



                        </h3>
                    </div>
                        <div class="panel-body">
                            
                        <!-- Tab section -->
                        <ul class="nav nav-tabs">
                            <?php
                                $segOne = \Request::segment(1);
                                $segTwo = \Request::segment(2);

                                $searchFormSubUrl = $segOne.'/'.$segTwo;
                            ?>
                                {{--Search statr =======================================--}}
                                <div class="row">
                                    <div class="col-md-12">
                                    {{ Form::open(array('role' => 'form', 'url' => $searchFormSubUrl, 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'schedule-all')) }}
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="requester">Demanding: </label>
                                            <select class="form-control selectpicker requester" name="requester" id="requester"  data-live-search="true">
                                                <option value="">{!! '- Select -' !!}</option>
                                                @foreach($demandeNames as $dmdn)
                                                    <option value="{!! $dmdn->id !!}" @if($demande==$dmdn->id) selected @endif>{!! $dmdn->name !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="email">Demand No: </label>
                                                {!!  Form::text('demand_no', $demand_no, array('id'=> 'demand_no', 'class' => 'form-control', 'autocomplete'=> 'off')) !!}
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
                                {{--Search End =======================================--}}

                                    <?php if(!empty(Session::get('acl')[34][17])){ ?>
                                    <li @if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('floating-tender-acc/1')}}"> Pending</a></li>

                                    <?php if(!empty(Session::get('acl')[34][29])){ ?>
                                    <li @if($demandDetailPageFromRoute=='retender-view-acc' && $segTwo==1)class="active" @endif><a href="{{URL::to('retender-view-acc/1')}}"> Retender</a></li>
                                    <?php } ?>

                                    <?php if(!empty(Session::get('acl')[34][26]) || !empty(Session::get('acl')[34][17])){ ?>
                                    <li @if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3)class="active" @endif><a href="{{URL::to('floating-tender-acc/3')}}"> Waiting for Approve</a></li>
                                    <?php } ?>

                                    <li @if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==2)class="active" @endif><a href="{{URL::to('floating-tender-acc/2')}}"> Approved</a></li>

                                    <li @if($segOne=='manual-tender')class="active" @endif><a href="{{URL::to('manual-tender/view')}}"> Manual Tender</a></li>


                                {{-- <li @if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==4)class="active" @endif><a href="{{URL::to('floating-tender-acc/4')}}"> Edit Tender</a></li> --}}
                                    <?php } ?>
                            </ul>
                            
                            <?php //if(!empty(Session::get('acl')[34][1])){ ?>
                            <div class="table-responsive">
                                @if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3 )
                                    {{ Form::open(array('role' => 'form', 'url' => 'approve-multiple-tender', 'files'=> true, 'class' => '', 'id'=>'demands')) }}
                                @endif
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    @if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3 )
                                        <th class="text-center">
                                            <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                    <input class="activity_1 activitycell" type="checkbox" id="all_check" name="" value="">
                                                    <label for=""></label>
                                                </div>
                                            </div>
                                        </th>
                                    @endif
                                    <th class="text-center">{{'Demanding'}}</th>
                                    <th class="text-center">{{'Demand No'}}</th>
                                    <th class="text-center">{{'Items & Quantity'}}</th>
                                    <th class="text-center">{{'Tender Number'}}</th>
                                    <th class="text-center">{{'Total Quantity'}}</th>
                                    <?php if(!empty(Session::get('acl')[34][3]) || !empty(Session::get('acl')[34][4])){ ?>
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

                                            @if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3 )
                                                <td>
                                                    <div class="form-group" style="margin-bottom: 0px;"><label class="control-label" for="status" style="display: none;"></label>
                                                        <div class="checkbox checkbox-success" style="margin-bottom: 0px; margin-top: 0px;">
                                                            <input class="activity_1 activitycell selectedTender" type="checkbox" id="" name="selectedTender[]" value="{!! $sc->id.'&'.$sc->tenderId !!}">
                                                            <label for=""></label>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif

                                            @if($demandDetailPageFromRoute=='collection-quotation-acc' || ($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3 || $segTwo==4)
                                             || $demandDetailPageFromRoute=='cst-view-acc' || $demandDetailPageFromRoute=='draft-cst-view-acc'
                                              || $demandDetailPageFromRoute=='hdq-approval-acc' || $demandDetailPageFromRoute=='po-generation-acc'
                                              || $demandDetailPageFromRoute=='cr-view-acc'  || $demandDetailPageFromRoute=='inspection-view-acc'
                                               || $demandDetailPageFromRoute=='v44-voucher-view-acc' || $demandDetailPageFromRoute=='retender-view-acc')
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
                                                    if(count($sc->itemsToDemand)<1 && isset($sc->tenderId)){
                                                        $sc->itemsToDemand = \App\ItemToDemand::where('tender_no','=',$sc->tenderId)->where('lpr_id','=',$sc->id)->get();
                                                    }
                                                    $remComma = 1;
                                                    $num_of_items = count($sc->itemsToDemand->unique('item_name'));         
                                                ?>
                                                @if(!empty($sc->itemsToDemand) && count($sc->itemsToDemand->unique('item_name')) > 0)
                                                    @foreach($sc->itemsToDemand->unique('item_name') as $ke => $itmsf)
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
                                                           )
                                                        @if($num_of_items > $remComma)
                                                            {!! '; ' !!}
                                                        @endif
                                                        <?php $remComma++; ?>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>{!! $sc->tender_number !!}</td>
                                            <td>{!! $sc->total_unit !!}</td>

                                            <td class="action-center">
                                                <div class="text-center">

                                                    <?php if(!empty(Session::get('acl')[34][26]) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 3){ ?>
                                                        
                                                        <a class="btn btn-success btn-xs showModal" href="javascript:void(0)" title="Approve Tender" attr-demandid-updateflds="{!! $sc->id.'&2&'.$sc->tenderId !!}">
                                                            <i class="icon-check"> </i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][17]) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 2 && empty($sc->tender_quation_collection)){ ?>
                                                        <a class="btn btn-warning btn-xs" href="{{ URL::to('direct-item-dmnd-edit/' . $sc->tenderId) }}" title="Edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                     <?php } ?>
                                                     <?php if(!empty(Session::get('acl')[34][17]) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 3){ ?>
                                                        <a class="btn btn-warning btn-xs" href="{{ URL::to('direct-item-dmnd-edit/' . $sc->tenderId) }}" title="Edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][17]) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 4){ ?>
                                                        {{-- <a class="btn btn-warning btn-xs" href="{{ URL::to('direct-item-dmnd-edit/' . $sc->tenderId) }}" title="Edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </a> --}}
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][17]) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 1 ){ ?>
                                                        <a class="btn btn-success btn-xs" href="{{ URL::to('floating-tender/create/'.$sc->id) }}" title="Create Tender">
                                                            <i class="icon-check"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if((!empty(Session::get('acl')[34][26]) || !empty(Session::get('acl')[34][17])) && $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 3 ){ ?>
                                                            <a class="btn btn-primary btn-xs" href="{{ URL::to('floating-tender-get-view/'.$sc->tenderId.'&1') }}" title="View Tender" target="_blank">
                                                                    <i class="icon-eye-open"></i>
                                                                </a>
                                                    <?php } ?>
                                                    <?php if( $demandDetailPageFromRoute=='floating-tender-acc' && $segTwo == 2 ){ ?>
                                                                <a class="btn btn-primary btn-xs" href="{{ URL::to('floating-tender-get-view/'.$sc->tenderId.'&2') }}" title="View Tender" target="_blank">
                                                                    <i class="icon-print"> </i>
                                                                </a>
                                                    <?php } ?>
                                                    
                                                    <!-- End Newly added ===============================
                                                    =========================================== -->

                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach

                                @else
                                    <tr>
                                        <td colspan="7">{{'Empty Data'}}</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table><!---/table-responsive-->
                            @if($demandDetailPageFromRoute=='floating-tender-acc' && $segTwo==3 )
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">{!! 'Approve Tenders' !!}</button>
                                </div>
                                {!!   Form::close() !!}
                            @endif
                            </div>
                            
                        <?php 
                            $segments = \Request::segments();
                            $routeName = $segments[0].(isset($segments[1]) ? '/'.$segments[1] : '');

                        ?>
                        @if($routeName !='group-check-acc/3' )
                            {{ $demands->appends(Request::except('page'))->links()}}
                        @endif 

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
          {{ Form::open(array('role' => 'form', 'url' => 'demand-pending-post', 'files'=> true, 'class' => 'demand-pending-post', 'id'=>'demand-pending-post')) }}
                <input type="hidden" name="demandId" id="demandId" value="">
                <input type="hidden" name="updateFilelds" id="updateFilelds" value="">
                <input type="hidden" name="tenderId" id="tenderId" value="">


                <div class="form-group"><label class="control-label" for="status">Status :<span class="text-danger">*</span></label>
                    {{ Form::select('demand_approval', array('1' => 'Approved', '2' =>'Reject'), '', array('class' => 'form-control selectpicker', 'id' => 'demand_approval','required')) }}
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

        $(document).on('click','#all_check',function(){
            if(this.checked){
                $('.selectedTender').each(function(){
                    this.checked = true;
                });
            }else{
                 $('.selectedTender').each(function(){
                    this.checked = false;
                });
            }
        });

        $(document).on('click','.showModal',function(){
            var attrVlues = $(this).attr('attr-demandid-updateflds');
            var result = attrVlues.split('&');
            $("#demandId").val('');
            $("#updateFilelds").val('');
            $("#tenderId").val('');

            $("#demandId").val(result[0]);
            $("#updateFilelds").val(result[1]);
            $("#tenderId").val(result[2]);

            $('#myModal').modal('show');
        });
        

        /*For Delete Department*/
        $(".exbtovdelete").click(function (e) {
            e.preventDefault();
            
            var id = this.id; 
            var url='{!! URL::to('demand-delete') !!}'+'/'+id;
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