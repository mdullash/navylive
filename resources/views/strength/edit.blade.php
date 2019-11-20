@extends('layouts.default')

<style type="text/css">

    .custom-file-upload {
        border: 1px solid #ccc;
        display: inline-block;
        padding: 6px 12px;
        cursor: pointer;
    }
    form div label.control-label{
        text-align: left !important;
    }
    div.has-feedback input.form-control {
        padding-right: 5.5px !important;
    }
</style>

@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    Strength
                </h2>
            </div>
            @include('layouts.flash')
        </div>
    </div>

    <div class="content animate-panel">
        <div class="row">
            <div class="col-sm-6 col-md-12">
                <div class="hpanel">
                    <div class="panel-heading sub-title">
                        Edit Strength
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => '/strength-calculation/update', 'files'=> true, 'class' => 'form-horizontal items', 'id'=>'items')) }}

                        <input type="hidden" name="id" value="{{$id}}" />

                        <div class="row">

                               @foreach($strengths as $strength)
                                <div class="col-md-12 parentDiv" id="item-lis">
                                    <input type="hidden" name="sToItemId[]" value="{{$strength->id}}" />
                                    <div class="form-group col-md-3 col-sm-3"><label class="control-label col-md-12 no-padding-right" for="status">Item Name :<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select class="form-control selectpicker item-name" name="item_id[]" id="item_id"  data-live-search="true" required>
                                                <option value="">{!! "- Select -" !!}</option>
                                                @foreach($items as $key => $name)
                                                    <option value="{!! $key !!}" @if($strength->bsd_items_id == $key) {{"selected"}} @endif>{!! $name !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-2 col-sm-2"><label class="control-label col-md-12 no-padding-right" for="stall_id">Strength :<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            {!!  Form::number("strength[]", $strength->strength, array("id"=> "strength", "class" => "form-control strength","step"=>"any","readonly")) !!}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-2 col-sm-2"><label class="control-label col-md-12 no-padding-right" for="stall_id">Person :<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            {!!  Form::number("person[]", $strength->person, array("id"=> "person", "class" => "form-control person","required")) !!}
                                        </div>
                                    </div>


                                    <div class="form-group col-md-2 col-sm-2"><label class="control-label col-md-12 no-padding-right" for="stall_id">Days :<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            {!!  Form::number("days[]", $strength->days, array("id"=> "days", "class" => "form-control days","required")) !!}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-2 col-sm-2"><label class="control-label col-md-12 no-padding-right" for="stall_id">Total :<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            {!!  Form::text("total[]", number_format((float)$strength->total, 3, '.', ''), array("id"=> "days", "class" => "form-control total","step" => "any","readonly")) !!}
                                        </div>
                                    </div>

                                    <?php
                                        $deno = \App\Deno::find($strength->deno_id);
                                    ?>

                                    <div class="form-group col-md-1 col-sm-1">
                                        <label style="" class="control-label col-md-12 no-padding-right" for="stall_id"></label>
                                        <div class="col-md-12" style="padding-top: 26px;">
                                            <span class="deno">{{$deno->name}}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-12" style="padding-right: 102px;padding-bottom: 10px;">
                                       <a href="{{url('/strength-calculation/item/delete/'.$strength->id)}}" class="btn btn-danger pull-right" title="Delete" >
                                           <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                               @endforeach

                            <div class="" id="more-item">

                            </div>

                            <div class="col-md-12" style="padding-left: 38px;">
                                <button type="button" class="btn btn-danger" title="Add More" id="add-more">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>

                            <div class="form-group">
                                <div class="col-md-7 col-sm-offset-5">
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                    <button type="submit" class="btn btn-primary pull-right">{!!trans('english.SAVE')!!}</button>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="hr-line-dashed"></div> -->
                        {!!   Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>

        <script type="text/javascript">
            $(document).ready(function () {
                // Add new Row
                var itemList = '<div class="col-md-12 parentDiv new-item" id="item-lis">\n' +
                    '                                    <div class="form-group col-md-3 col-sm-3 has-feedback"><label class="control-label col-md-12 no-padding-right" for="status">Item Name :<span class="text-danger">*</span></label>\n' +
                    '                                        <input type="hidden" name="sToItemId[]" value="" />'+
                    '                                        <div class="col-md-12">\n' +
                    '                                            <select class="form-control selectpicker item-name" name="item_id[]" id="item_id"  data-live-search="true" required>\n' +
                    '                                                <option value="">{!! "- Select -" !!}</option>\n' +
                    '                                                @foreach($items as $key => $name)\n' +
                    '                                                    <option value="{!! $key !!}">{!! $name !!}</option>\n' +
                    '                                                @endforeach\n' +
                    '                                            </select>\n' +
                    '                                        </div>\n' +
                    '                                    </div>\n' +
                    '\n' +
                    '                                    <div class="form-group col-md-2 col-sm-2 has-feedback"><label class="control-label col-md-12 no-padding-right" for="stall_id">Strength :<span class="text-danger">*</span></label>\n' +
                    '                                        <div class="col-md-12">\n' +
                    '                                            {!!  Form::number("strength[]", old("strength[]"), array("id"=> "strength", "class" => "form-control strength","step"=>"any","readonly")) !!}\n' +
                    '                                        </div>\n' +
                    '                                    </div>\n' +
                    '\n' +
                    '                                    <div class="form-group col-md-2 col-sm-2 has-feedback"><label class="control-label col-md-12 no-padding-right" for="stall_id">Person :<span class="text-danger">*</span></label>\n' +
                    '                                        <div class="col-md-12">\n' +
                    '                                            {!!  Form::number("person[]", old("person[]"), array("id"=> "person", "class" => "form-control person","required")) !!}\n' +
                    '                                        </div>\n' +
                    '                                    </div>\n' +
                    '\n' +
                    '\n' +
                    '                                    <div class="form-group col-md-2 col-sm-2 has-feedback"><label class="control-label col-md-12 no-padding-right" for="stall_id">Days :<span class="text-danger">*</span></label>\n' +
                    '                                        <div class="col-md-12">\n' +
                    '                                            {!!  Form::number("days[]", old("days[]"), array("id"=> "days", "class" => "form-control days","required")) !!}\n' +
                    '                                        </div>\n' +
                    '                                    </div>\n' +
                    '\n' +
                    '                                    <div class="form-group col-md-2 col-sm-2 has-feedback"><label class="control-label col-md-12 no-padding-right" for="stall_id">Total :<span class="text-danger">*</span></label>\n' +
                    '                                        <div class="col-md-12">\n' +
                    '                                            {!!  Form::text("total[]", old("days[]"), array("id"=> "total", "class" => "form-control total","step" => "any","readonly")) !!}\n' +
                    '                                        </div>\n' +
                    '                                    </div>\n' +
                    '                                    <div class="form-group col-md-1 col-sm-1 has-feedback">\n' +
                    '                                        <label style="" class="control-label col-md-12 no-padding-right" for="stall_id"></label>\n' +
                    '                                        <div class="col-md-12"  style="padding-top: 26px;">\n' +
                    '                                            <span class="deno"></span>\n' +
                    '                                        </div>\n' +
                    '                                    </div>'+
                    '                                <div class="col-md-12" style="padding-right: 102px;padding-bottom: 10px;">\n' +
                    '                                    <button type="button" class="btn btn-danger pull-right remove" title="Remove" >\n' +
                    '                                        <i class="fa fa-minus"></i>\n' +
                    '                                    </button>\n' +
                    '                                </div>\n' +
                    '                                </div>';
                $('#add-more').click(function () {
                    $('#more-item').append(itemList);
                    $('.selectpicker').selectpicker('refresh');
                });
                $(document).on('click', '.remove', function(e) {
                    $(this).parents('.new-item').remove();
                });
                // Add new row

                // Strength value
                $(document).on('change', '.item-name', function(e) {
                    var findStrength = $(this).closest("div.parentDiv").find(".strength");
                    var findDeno = $(this).closest("div.parentDiv").find(".deno");
                    var itemId = $(this).val();
                    var csrf = "{{ csrf_token() }}";
                    $.ajax({
                        type:'POST',
                        url:"{{url('/strength-calculation/ajax')}}",
                        data:{_token : csrf, id: itemId},
                        success:function(data) {

                            var result = JSON.parse(data);
                            findStrength.val(result.strength);
                            findDeno.text(result.deno);
                        }
                    });

                });

                $(document).on('input','.person, .days',function(){
                    var strengthVal = $(this).closest("div.parentDiv").find(".strength").val();
                    var personVal = $(this).closest("div.parentDiv").find(".person").val();
                    var daysVal = $(this).closest("div.parentDiv").find(".days").val();


                    var totalCal = strengthVal * personVal * daysVal;
                    $(this).closest("div.parentDiv").find(".total").val(parseFloat(totalCal).toFixed(3));
                });
            });
    </script>
@stop
