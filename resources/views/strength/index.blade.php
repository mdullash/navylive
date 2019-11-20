@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Strength</h3>
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
						<?php if(!empty(Session::get('acl')[38][2])){ ?>
                        <div class="pull-right">
                            <a class="btn btn-info btn-effect-ripple" href="{{ URL::to('/strength-calculation/create') }}"><i class="fa fa-plus"></i>Strength Calculation</a>
                        </div>
						<?php } ?>
                        <h3>Strength List</h3>
                    </div>
                    <div class="panel-body">

                        <div class="row">

                            {{ Form::open(array('role' => 'form', 'url' => 'strength-calculation', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'supplier-report')) }}
                            <div class="col-md-5">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">From: </label>
                                        <input id="from" class="form-control datapicker2" autocomplete="off" readonly="" name="from" type="text" value="{{Input::get('from')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">To: </label>
                                        <input id="todate" class="form-control datapicker2" autocomplete="off" readonly="" name="todate" type="text" value="{{Input::get('todate')}}">
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
                            <table class="table table-bordered table-hover table-striped middle-align" style="color: black;">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th style="width: 60%;">Item Name</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
									<?php if(!empty(Session::get('acl')[38][3]) || !empty(Session::get('acl')[38][4]) || !empty(Session::get('acl')[38][1]) || !empty(Session::get('acl')[38][9]) || !empty(Session::get('acl')[38][15])){ ?>
                                    <th class="text-center"> {!!trans('english.ACTION')!!} </th>
									<?php } ?>
                                </tr>
                                </thead>
                                <tbody>
                                @if (!empty($strengths))

									<?php
									$page = \Input::get('page');
									$page = empty($page) ? 1 : $page;
									$sl = ($page-1)*10;
									$l = 1;
									?>
                                    @foreach($strengths as $strength)
                                        <tr>
                                            <td>{{++$sl}}</td>
                                            <td>
                                                @foreach($strength->strengthToItems as $strengthToItem)
                                                    <?php
                                                    $itemFind = \App\Item::find($strengthToItem->bsd_items_id);
                                                    ?>
                                                   <strong> {{$itemFind->item_name}} </strong>

                                                        ( S-{{$strengthToItem->strength}},P-{{$strengthToItem->person}},
                                                        D-{{$strengthToItem->days}},T-{{$strengthToItem->total}})
                                                        @if($loop->iteration >= 1 && $loop->last != true ) , @endif
                                                @endforeach
                                            </td>
                                            <td>{{date('d M, Y',strtotime($strength->created_at))}}</td>
                                            <td>{{date('d M, Y',strtotime($strength->created_at))}}</td>
											<?php if(!empty(Session::get('acl')[38][3]) || !empty(Session::get('acl')[38][4]) || !empty(Session::get('acl')[38][15]) || !empty(Session::get('acl')[38][9])){ ?>
                                            <td class="action-center">
                                                <div class="text-center">

	                                                <?php if(!empty(Session::get('acl')[38][15])){ ?>
                                                    <a class="btn btn-info btn-xs" href="{{ URL::to('/strength-calculation/details/'.$strength->id) }}" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
	                                                <?php } ?>

													<?php if(!empty(Session::get('acl')[38][3])){ ?>
                                                    <a class="btn btn-success btn-xs" href="{{ URL::to('/strength-calculation/edit/'.$strength->id) }}">
                                                        <i class="icon-edit"></i>
                                                    </a>
													<?php } ?>

													<?php if(!empty(Session::get('acl')[38][4])){?>
                                                    <a href="{{URL::to('/strength-calculation/delete/'.$strength->id)}}" class="btn btn-danger btn-xs"  type="button" data-placement="top" data-rel="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this item?');">
                                                        <i class='fa fa-trash'></i>
                                                    </a>
													<?php }?>

                                                    <?php if(!empty(Session::get('acl')[38][9])){?>
                                                    <a href="{{URL::to('/strength-calculation/print-pdf/'.$strength->id)}}" class="btn btn-primary btn-xs" target="_blank" title="Print Pdf">
                                                        <i class='fa fa-print'></i>
                                                    </a>
                                                    <?php }?>

                                                    <?php if(!empty(Session::get('acl')[38][9])){?>
                                                    <a class="btn btn-xs" href="{{URL::to('/strength-calculation/print-excel/'.$strength->id)}}" title="PRint Excel">
                                                        <i class="fa fa-file-excel-o" style="color: #19816f;font-size: 18px;" aria-hidden="true"></i>
                                                    </a>
                                                    <?php }?>

                                                </div>
                                            </td>
											<?php } ?>
                                        </tr>
                                    @endforeach

                                @else
                                    <tr>
                                        <td colspan="5">{{'Empty Data'}}</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table><!---/table-responsive-->
                        </div>

                        {{--{{ $items->links()}}--}}

                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        function deleteConfirm() {
            confirm("Are You sure!, You want to delete this data");
        }
    </script>
@stop
