@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Item</h3>
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
                        <?php if(!empty(Session::get('acl')[14][2])){ ?>
                        <div class="pull-right">
                            <a class="btn btn-info btn-effect-ripple" href="{{ URL::to('item/create') }}"><i class="fa fa-plus"></i> Add Item</a>
                        </div>
                        <?php } ?>
                            <h3>Item</h3>
                    </div>
                        <div class="panel-body">

                            <div class="row">

                                {{ Form::open(array('role' => 'form', 'url' => 'item/view', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'supplier-report')) }}

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="email">Organization:</label>
                                            <select class="form-control selectpicker" name="nsd_id" id="nsd_id"  data-live-search="true">
                                                <option value="">{!! '- Select -' !!}</option>
                                                @foreach($nsdNames as $nn)
                                                    <option value="{!! $nn->id !!}" @if($nn->id==$nsd_id) {{'selected'}} @endif>{!! $nn->name !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4" style="display: none;">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="email">Budget Code:</label>
                                            <select class="form-control selectpicker" name="budget_code" id="budget_code"  data-live-search="true">
                                                <option value="">{!! '- All -' !!}</option>
                                                @foreach($budget_codes as $bc)
                                                    <option value="{!! $bc->id !!}" @if($bc->id==$b_code) {{'selected'}} @endif>{!! $bc->code !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="email">Categories:</label>
                                            <select class="form-control selectpicker" name="ct_name" id="ct_name"  data-live-search="true">
                                                <option value="">{!! '- All -' !!}</option>
                                                @foreach($suppliercategories as $cts)
                                                @if($cts->name != "DNS ITEMS" && $cts->name != "DNST ITEMS" && $cts->name != "DTS ITEMS" && $cts->name != "QP" && $cts->name != "General")
                                                    <option value="{!! $cts->id !!}" @if($cts->id==$ct_name) {{'selected'}} @endif>{!! $cts->name !!}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="company_mobile">Item Name/ IMC Number: </label>
                                            {!!  Form::text('key', $key, array('id'=> 'key', 'class' => 'form-control', 'autocomplete'=> 'off')) !!}
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
                                    <th class="text-center" width="5%">Ser</th>
                                    <th class="text-center" width="5%">Item Picture</th>
                                    <th class="text-center">{{'IMC Number'}}</th>
                                    <th class="text-center" width="">{{'Item Name'}}</th>
                                    <th class="text-center" width="">{{'Brand'}}</th>
                                     @if(\Session::get("zoneAlise") == "bsd")
                                        <th class="text-center" width="">{{'Strength'}}</th>
                                    @endif
                                    <th class="text-center" width="">{{'Model'}}</th>
                                    <th class="text-center" width="">{{'Manufacturing Country'}}</th>
                                    <th class="text-center" width="">{{'Country of Origin'}}</th>
                                    <th class="text-center" width="">{{'Main Equipment Name'}}</th>
                                    <th class="text-center" width="">{{'Item DENO'}}</th>
                                    <th class="text-center" width="">{{'Item Category'}}</th>
                                    <th class="text-center" width="">{{'Item Type'}}</th>
                                    <th class="text-center" width="">{{'Currency'}}</th>
                                    <th class="text-center" width="">{{'Unit Price'}}</th>
                                    <th class="text-center" width="">{{'Discounted Price'}}</th>
                                    
                                    
                                    {{-- <th class="text-center" width="">{{'Source Of Supply'}}</th> --}}
                                    <th class="text-center" width="">{{'Organization'}}</th>
                                    {{-- <th class="text-center" width="">{{'Budget Code'}}</th> --}}
                                    
                                    <th class="text-center">{{trans('english.STATUS')}}</th>
                                    <th>Item Specification</th>
                                    <?php if(!empty(Session::get('acl')[14][3]) || !empty(Session::get('acl')[14][4])){ ?>
                                    <th class="text-center"> {!!trans('english.ACTION')!!}
                                    </th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>

                                @if (!$items->isEmpty())

                                    <?php
                                        $page = \Input::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page-1)*10;
                                        $l = 1;

                                        function nsd_name($nsd_id=null){
                                            $calName = \App\NsdName::where('id','=',$nsd_id)->value('name');
                                            return $calName;
                                        }
                                    ?>
                                    @foreach($items as $sc)
                                        <tr>
                                            <td>{{++$sl}}</td>
                                            <td>
                                                @if(!empty($sc->item_picture))
                                                    <img src="{{ asset($sc->item_picture) }}" width="100px;">
                                                @endif
                                            </td>
                                            <td>{{$sc->imc_number}}</td>
                                            <td>{{$sc->item_name}}</td>
                                            @if(\Session::get("zoneAlise") == "bsd")
                                                <td>{{$sc->strength}}</td>
                                            @endif
                                            <td>{{$sc->brand}}</td>
                                            <td>{{$sc->model_number}}</td>
                                            <td>{{$sc->manufacturing_country}}</td>
                                            <td>{{$sc->country_of_origin}}</td>
                                            <td>{{$sc->main_equipment_name}}</td>
                                            <td>{{$sc->denoName->name}}</td>
                                            <td>{{$sc->supplyCategoryName->name}}</td>
                                            <td>@if($sc->item_type == 1){!! 'Permanent Content' !!} @elseif($sc->item_type == 2) {!! 'Waste Content' !!}  @elseif($sc->item_type == 3) {!! 'Quasi Permanent Content' !!} @endif</td>
                                            <td>
                                                @if(!empty($sc->currency_name))
                                                    {!! $sc->currencyName->currency_name !!}
                                                @else
                                                    @if(!empty($default_currency))
                                                        {!! $default_currency->currency_name !!}
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{$sc->unit_price}}</td>
                                            <td>{{$sc->discounted_price}}</td>
                                            
                                            
                                            {{-- <td>{{$sc->source_of_supply}}</td> --}}
                                            <td>
                                                <?php
                                                $nsdids = explode(',',$sc->nsd_id);
                                                foreach ($nsdids as $nsd) {
                                                    $vals = nsd_name($nsd);
                                                    echo "<li>".$vals."</li>";
                                                }
                                                ?>
                                            </td>
                                            {{-- <td>@if(!empty($sc->budget_code)){{$sc->budgetCodeName->code}} @endif</td> --}}
                                            
                                            <td class="text-center">
                                                @if ($sc->status_id == '1')
                                                    <span class="label label-success">{{trans('english.ACTIVE')}}</span>
                                                @else
                                                    <span class="label label-warning">{{trans('english.INACTIVE')}}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(!empty($sc->item_specification))
                                                    <a href="{{ asset($sc->item_specification) }}" class="text-danger" target="_blank" style="font-size: 16px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                                                @endif
                                            </td>
                                            <?php if(!empty(Session::get('acl')[14][3]) || !empty(Session::get('acl')[14][4])){ ?>
                                            <td class="action-center">
                                                <div class="text-center">

                                                    <?php if(!empty(Session::get('acl')[14][3])){ ?>
                                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('item/edit/' . $sc->id) }}">
                                                        <i class="icon-edit"></i>
                                                    </a>
                                                    <a class="btn btn-primary btn-xs" target="_blank" href="{{ URL::to('item/print/' . $sc->id) }}">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                    <?php } ?>

                                                    <?php if(!empty(Session::get('acl')[14][4])){?>
                                                    <button class="exbtovdelete btn btn-danger btn-xs" id="{{$sc->id}}" type="button" data-placement="top" data-rel="tooltip" data-original-title="Delete">
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
                                        <td colspan="13">{{'Empty Data'}}</td>
                                    </tr>
                                @endif
                                    
                                </tbody>
                            </table><!---/table-responsive-->
                            </div>

                            {{ $items->appends(Request::except('page'))->links()}}

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