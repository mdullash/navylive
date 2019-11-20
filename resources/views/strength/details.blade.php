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
                        <h3>Strength Details</h3>
                    </div>
                    <div class="panel-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th>Item Name</th>
                                    <th>Strength</th>
                                    <th>Person</th>
                                    <th>Days</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (!empty($strengthToItems))

                                    @foreach($strengthToItems as $strengthToItem)
	                                    <?php
	                                    $itemFind = \App\Item::find($strengthToItem->bsd_items_id);
	                                    $deno = \App\Deno::find($itemFind->	item_deno);
	                                    ?>
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td> {{$itemFind->item_name}} ({{$deno->name}})</td>
                                            <td>{{$strengthToItem->strength}}</td>
                                            <td>{{$strengthToItem->person}}</td>
                                            <td>{{$strengthToItem->days}}</td>
                                            <td>{{$strengthToItem->total}}  {{$deno->name}}</td>
                                        </tr>
                                    @endforeach
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
@stop