@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Demand</h3>
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
                        <?php if(!empty(Session::get('acl')[34][2]) ){ ?>
                        <div class="pull-right">
                            <a class="btn btn-info btn-effect-ripple" href="{{ URL::to('demand/create') }}"><i class="fa fa-plus"></i> Create Demand</a>
                        </div>
                        <?php } ?>
                            <h3>Demand</h3>
                    </div>
                        <div class="panel-body">

                            <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Demand No'}}</th>
                                    {{--<th class="text-center">{{'Segmental detection of content'}}</th>--}}
                                    <th class="text-center">{{'Pattern ‍Stock No'}}</th>
                                    <th class="text-center">{{'Product Details'}}</th>
                                    <th class="text-center">{{'Unit'}}</th>
                                    <th class="text-center">{{'Allowed'}}</th>
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
                                            <td>{!! $sc->demand_no !!}</td>
                                            {{--<td>--}}
                                                {{--<li><b>{!! 'Machinery' !!}:</b> {!! $sc->machinery_and_manufacturer !!}</li>--}}
                                                {{--<li><b>{!! 'Model' !!}:</b> {!! $sc->model_type_mark !!}</li>--}}
                                                {{--<li><b>{!! 'Serial' !!}:</b> {!! $sc->serial_or_reg_number !!}</li>--}}
                                                {{--<li><b>{!! 'Group' !!}:</b> {!! $sc->group_name->name !!}</li>--}}
                                            {{--</td>--}}
                                            <td>{!! $sc->pattern_or_stock_no !!}</td>
                                            <td>{!! $sc->product_detailsetc !!}</td>
                                            <td>{!! $sc->total_unit !!}</td>
                                            <td>{!! $sc->allowed !!}</td>
                                            <td class="action-center">
                                                <div class="text-center">

                                                    <?php if(!empty(Session::get('acl')[34][1])){ ?>
                                                    <a class="btn btn-info btn-xs" href="{{ URL::to('demand-details/' . $sc->id) }}" title="View">
                                                        <i class="icon-eye-open"></i>
                                                    </a>
                                                    <?php } ?>
                                                    <?php if(!empty(Session::get('acl')[34][25])){ ?>
                                                    <a class="btn btn-success btn-xs" href="{{ URL::to('schedule-create') }}" title="Schedule">
                                                        <i class="fa fa-shopping-cart"></i>
                                                    </a>
                                                    <?php } ?>
                                                   <!--  @if(empty($sc->group_status) && empty($sc->group_status_check_by))
                                                        <button class="btn btn-success btn-xs">Waiting for checking</button>

                                                        @elseif(!empty($sc->group_status) && !empty($sc->group_status_check_by) && empty($sc->lp_section_status))
                                                        <button class="btn btn-success btn-xs">Waiting for floating</button>
                                                    @endif -->
                                                    
                                                    @if(empty($sc->group_status))

                                                        <span class="btn-primary btn-xs">Waiting for checking</span>

                                                        @elseif($sc->group_status == 1)
                                                        <span class=" btn-success btn-xs">Product in stock</span>

                                                        @elseif($sc->group_status == 2 && empty($sc->tender_floating))

                                                            @if(!empty($sc->transfer_to) && $sc->transfer_status==2 && $sc->plr_status==2 )
                                                                <span class=" btn-warning btn-xs">Canceled By {!! $sc->transferOrgName->name !!}</span>
                                                            @endif    
                                                        
                                                            @if(!empty($sc->transfer_to) && empty($sc->transfer_status) )
                                                                <span class=" btn-warning btn-xs">Waiting for {!! $sc->transferOrgName->name."'s response" !!}</span>

                                                                @elseif(!empty($sc->transfer_to) && $sc->transfer_status==2 && $sc->plr_status==1)
                                                                <span class=" btn-warning btn-xs">Hold By {!! $sc->transferOrgName->name !!}</span>

                                                                @elseif(!empty($sc->transfer_to) && $sc->transfer_status==2 && $sc->plr_status==3)
                                                                <span class=" btn-warning btn-xs"> {!! "Waiting for floating" !!}</span>
                                                            @endif

                                                        @elseif(!empty($sc->tender_floating) && empty($sc->tender_quation_collection))
                                                            <span class=" btn-warning btn-xs"> {!! "Waiting for collection quotation" !!}</span>

                                                        @elseif(!empty($sc->tender_quation_collection) && empty($sc->cst_draft_status))
                                                            <span class=" btn-warning btn-xs"> {!! "Waiting for draft CST" !!}</span>

                                                        @elseif(!empty($sc->cst_draft_status) && empty($sc->cst_supplier_select))
                                                            <span class=" btn-warning btn-xs"> {!! "Waiting for select winner" !!}</span>
                                                            
                                                        @elseif(!empty($sc->cst_supplier_select) && empty($sc->lp_section_status))
                                                            <span class=" btn-warning btn-xs"> {!! "In LP section" !!}</span>

                                                        @elseif( !empty($sc->lp_section_status) && $sc->lp_section_status ==2 && $sc->head_ofc_apvl_status == 2 )
                                                        <span class=" btn-warning btn-xs"> {!! "Waiting for HQ Approval" !!}</span>

                                                        @elseif(!empty($sc->cst_supplier_select) && $sc->lp_section_status==1 && empty($sc->po_status) && ($sc->head_ofc_apvl_status==1 || $sc->head_ofc_apvl_status=='')  ) 
                                                        <span class=" btn-warning btn-xs"> {!! "Generate PO" !!}</span>

                                                        @elseif(!empty($sc->po_status) && empty($sc->cr_status))
                                                            <span class=" btn-warning btn-xs"> {!! "In CR section" !!}</span>

                                                        @elseif(!empty($sc->cr_status) && empty($sc->inspection_status))
                                                            <span class=" btn-warning btn-xs"> {!! "Waiting for inspection" !!}</span>

                                                        @elseif(!empty($sc->inspection_status) && empty($sc->final_status))
                                                            <span class=" btn-warning btn-xs"> {!! "V44 voucher" !!}</span>                    
                                                            
                                                            
                                                    @endif

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

                            {{ $demands->links()}}

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