@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Suppliers Report</h3>
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
                            <h3>Suppliers Report</h3>
                    </div>
                        <div class="panel-body">

                            <div class="row">

                                {{ Form::open(array('role' => 'form', 'url' => 'supplier-report', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'supplier-report')) }}

                                 <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="email">Organization: </label>
                                            <select class="form-control selectpicker" name="nsd_id" id="nsd_id"  data-live-search="true">
                                                <option value="">{!! '- All -' !!}</option>
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
                                            <label for="company_mobile">Com. Name / Mob. No / Reg. No: </label>
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

                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{url('supplier-report-excel-export?nsd_id='.$nsd_id.'&company_mobile='.$company_mobile.'&from='.$from.'&to='.$to.'&action=pdf')}}" class="btn btn-primary btn-xs" target="_blank"><i class="fa fa-print"></i> {{'Print'}}</a>
                                    <a href="{{url('supplier-report-excel-export?nsd_id='.$nsd_id.'&company_mobile='.$company_mobile.'&from='.$from.'&to='.$to.'&action=excel')}}" class="btn btn-success btn-xs"><i class="fa fa-download"></i> {{'Export Excel'}}</a><br>
                                </div>
                            </div><br>

                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Company Name'}}</th>
                                    <th class="text-center" width="">{{'Company Registration No.'}}</th>
                                    <th class="text-center" width="">{{'Mobile Number'}}</th>
                                    <th class="text-center" width="">{{'Supply Category'}}</th>
                                    <th class="text-center" width="">{{'TIN Number'}}</th>
                                    <th class="text-center">{{'Trade License Number'}}</th>
                                    <th class="text-center">{{'Organization'}}</th>
                                    <th class="text-center"> {!!trans('english.ACTION')!!}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                @if (!$suppliers->isEmpty())

                                    <?php
                                        $page = \Input::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page-1)*50;
                                        $l = 1;

                                        function supply_cat_name($cat_id=null){
                                            $calName = \App\SupplyCategory::where('id','=',$cat_id)->value('name');
                                            return $calName;
                                        }

                                        function supply_nsd_name($nsd_id=null){
                                            $calName = \App\NsdName::where('id','=',$nsd_id)->value('name');
                                            return $calName;
                                        }
                                    ?>
                                    @foreach($suppliers as $sc)
                                        <tr> 
                                            <td>{{++$sl}}</td>
                                            <td>{{$sc->company_name}}</td>
                                            <td>{{$sc->company_regi_number_nsd}}</td>
                                            <td>{{$sc->mobile_number}}</td>
                                            {{-- <td>{{$sc->supplyCategoryName->name}}</td> --}}
                                            <td>
                                                <?php 
                                                    $catids = explode(',',$sc->supply_cat_id);
                                                    foreach ($catids as $ctds) {
                                                        $valsss = supply_cat_name($ctds);
                                                        echo "<li>".$valsss."</li>";
                                                    }
                                                ?>
                                            </td>
                                            <td>{{$sc->tin_number}}</td>
                                            <td class="text-center">
                                                {{$sc->trade_license_number}}
                                            </td>
                                            <td>
                                                <?php
                                                $nsdids = explode(',',$sc->registered_nsd_id);
                                                foreach ($nsdids as $nsd) {
                                                    $valssss = supply_nsd_name($nsd);
                                                    echo "<li>".$valssss."</li>";
                                                }
                                                ?>
                                            </td>

                                            <td class="action-center">
                                                <div class="text-center">

                                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('/suppliers/view/'. $sc->id) }}" title="view" target="_blank">
                                                        <i class="icon-eye-open"></i>
                                                    </a>

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
                var url='{!! URL::to('suppliers/suppliers/destroy') !!}'+'/'+id;
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