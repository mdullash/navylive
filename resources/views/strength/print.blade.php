<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Strength Calculation}}</title>
    <style>
        table,th,td{
            border: 1px solid black;
        }
        table{
            border-collapse: collapse;
        }
        th{
            text-align: center;
        }
    </style>
</head>
<body>
<table class="table table-bordered table-hover table-striped middle-align">
    <tr>
        <th colspan="6" style="border-bottom: none;"> Strength Calculation</th>
    </tr>
    <tr>
        <th colspan="6" style="border: none;">{{date('d-m-Y')}}</th>
    </tr>
    <tr class="center">
        <th class="text-center" width="5%">SL#</th>
        <th>Item Name</th>
        <th>Strength</th>
        <th>Person</th>
        <th>Days</th>
        <th>Total</th>
    </tr>
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
</table>
</body>
</html>