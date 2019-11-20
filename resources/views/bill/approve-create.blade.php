@extends('layouts.default')

<style type="text/css">

.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}
.bootstrap-select.btn-group, .bootstrap-select.btn-group[class*="span"]{
    margin-bottom: 0px !important;
}
</style>

@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    Bill Forwarding
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
                       Bill Forwarding approved
                    </div>
                    <?php
                        $billForwardingType = [
                            ''=>'Select Bill Forwarding Type',
                            '1'=>'Financial Sanction',
                            '2'=>'Bill Forwarding with Financial Sanction of NSSD',
                            '3'=>'Bill Forwarding without Financial Sanction',
                            '4'=>'Bill Forwarding with Financial Sanction of NHQ',
                            '5'=>'Bill Forwarding with time extension application',
                            '6'=>'Bill Forwarding with Late Delivery',
                            '7'=>'Bill Forwarding if time Extension application already sent to NHQ',
                        ];
                    ?>
                    <div class="panel-body">
                        {{ Form::model($billForwarding, array('url' => '/bill/approve-store', 'files'=> true, 'class' => 'tender', 'id' => 'tender')) }}

                        <input type="hidden" value="{{$billForwarding->id}}" name="id">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bill_forwarding_type">Bill Forwarding Type:<span class="text-danger">*</span></label>
                                {!!  Form::select('bill_forwarding_type',$billForwardingType, old('bill_forwarding_type'), array('id'=> 'bill_forwarding_type', 'class' => 'form-control','required')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bill_forwarding_number">Bill Forwarding Number:<span class="text-danger">*</span></label>
                                {!!  Form::text('bill_forwarding_number', old('bill_forwarding_number'), array('id'=> 'bill_forwarding_number', 'class' => 'form-control','required')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bill_forwarding_date">Bill Forwarding Date:<span class="text-danger">*</span></label>
                                {!!  Form::text('bill_forwarding_date', old('bill_forwarding_date'), array('id'=> 'bill_forwarding_date', 'class' => 'form-control datapicker2','readonly')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="buget_code">Budget Code:<span class="text-danger">*</span></label>
                                {{--{!!  Form::select('bill_forwarding_type',$cstForwardingType, old('bill_forwarding_type'), array('id'=> 'bill_forwarding_type', 'class' => 'form-control','required')) !!}--}}

                                <select class="form-control basic-multiple" name="budget_code[]" id="buget_code" data-live-search="true" multiple="multiple" required>
                                    <option>Select Budget Code</option>
                                    @foreach($budgetCodeAll as $budCode)
                                        <option value="{{$budCode->id}}" @if(in_array($budCode->id,$budgetUniqueIds)) selected @endif>{{$budCode->code.'-'.$budCode->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bill_number">Supplier Bill Number:<span class="text-danger">*</span></label>
                                {!!  Form::text('bill_number', old('bill_number'), array('id'=> 'bill_number', 'class' => 'form-control','required')) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bill_forwarding_date">Bill Date:<span class="text-danger">*</span></label>
                                {!!  Form::text('bill_date', old('bill_date'), array('id'=> 'bill_date', 'class' => 'form-control datapicker2','readonly')) !!}
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="enclosure">Enclosure:<span class="text-danger"></span></label>
                                <textarea cols="25" rows="5" type="text" name="enclosure" class="form-control" id="enclosure">
                                     {!! $billForwarding->enclosure !!}
                                </textarea>
                            </div>
                        </div>

                        <div class="col-md-6" style="display: none;">
                            <div class="form-group">
                                <label for="distribution">Distribution:<span class="text-danger"></span></label>
                                {!!  Form::textarea('distribution', old('distribution'), array('id'=> 'distribution', 'class' => 'form-control','cols' => 25,'rows' => 5)) !!}
                            </div>
                        </div>

                        <div class="col-md-6" style="display: none;">
                            <div class="form-group">
                                <label for="external">External:<span class="text-danger"></span></label>
                                {!!  Form::textarea('external', old('external'), array('id'=> 'external', 'class' => 'form-control','cols' => 25,'rows' => 5)) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="action">Action:<span class="text-danger"></span></label>
                                {!!  Form::textarea('action', old('action'), array('id'=> 'action', 'class' => 'form-control','cols' => 25,'rows' => 5)) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="information">Information:<span class="text-danger"></span></label>
                                {!!  Form::textarea('information', old('information'), array('id'=> 'information', 'class' => 'form-control','cols' => 25,'rows' => 5)) !!}
                            </div>
                        </div>

                        <div class="col-md-6" id="time_ext_app_no_div" style="display: none;">
                            <div class="form-group">
                                <label for="time_ext_app_no">Time Extention Application No:<span class="text-danger">*</span></label>
                                {!!  Form::text('time_ext_app_no', old('time_ext_app_no'), array('id'=> 'time_ext_app_no', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6" id="time_ext_app_date_div" style="display: none;">
                            <div class="form-group">
                                <label for="time_ext_app_date">Time Extention Application Date:<span class="text-danger">*</span></label>
                                {!!  Form::text('time_ext_app_date', old('time_ext_app_date'), array('id'=> 'time_ext_app_date', 'class' => 'form-control datapicker2','readonly')) !!}
                            </div>
                        </div>

                        <div class="col-md-6" id="time_ext_up_to_div" style="display: none;">
                            <div class="form-group">
                                <label for="time_ext_up_to">Time Extention Up To:<span class="text-danger">*</span></label>
                                {!!  Form::text('time_ext_up_to', old('time_ext_up_to'), array('id'=> 'time_ext_up_to', 'class' => 'form-control datapicker2','readonly')) !!}
                            </div>
                        </div>

                        <div class="col-md-6" id="nssd_ltr_no_div" style="display: none;">
                            <div class="form-group">
                                <label for="nssd_ltr_no">NSSD Ltr No:<span class="text-danger">*</span></label>
                                {!!  Form::text('nssd_ltr_no', old('nssd_ltr_no'), array('id'=> 'nssd_ltr_no', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-6" id="nssd_ltr_date_div" style="display: none;">
                            <div class="form-group">
                                <label for="nssd_ltr_date">NSSD Ltr Date:<span class="text-danger">*</span></label>
                                {!!  Form::text('nssd_ltr_date', old('nssd_ltr_date'), array('id'=> 'nssd_ltr_date', 'class' => 'form-control datapicker2','readonly')) !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-11 col-sm-offset-1">
                                <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>

                                <button type="submit" class="btn btn-primary pull-right" id="submitButton">{!! 'Submit' !!}</button>

                            </div>
                        </div>

                        {!!   Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
  
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.js"></script>

    <script>
        $(document).ready(function() {
            $('.basic-multiple').select2();

            var billTypeRady = $("#bill_forwarding_type").val();
            if (billTypeRady == 5)
            {
                $("#time_ext_app_no_div").css("display","block");
                $("#time_ext_app_no_div input").attr("required", "true");
                $("#time_ext_app_date_div").css("display","block");
                $("#time_ext_app_date_div_div input").attr("required", "true");
                $("#time_ext_up_to_div").css("display","block");
                $("#time_ext_up_to_div input").attr("required", "true");

                $("#nssd_ltr_no_div").css("display","none");
                $("#nssd_ltr_no_div input").attr("required", "false");
                $("#nssd_ltr_date_div").css("display","none");
                $("#nssd_ltr_date_div input").attr("required", "false");
            }
            else if(billTypeRady == 7)
            {
                $("#time_ext_app_no_div").css("display","none");
                $("#time_ext_app_no_div input").attr("required", "false");
                $("#time_ext_app_date_div").css("display","none");
                $("#time_ext_app_date_div_div input").attr("required", "false");
                $("#time_ext_up_to_div").css("display","none");
                $("#time_ext_up_to_div input").attr("required", "false");

                $("#nssd_ltr_no_div").css("display","block");
                $("#nssd_ltr_no_div input").attr("required", "true");
                $("#nssd_ltr_date_div").css("display","block");
                $("#nssd_ltr_date_div input").attr("required", "true");
            }
            else
            {
                $("#time_ext_app_no_div").css("display","none");
                $("#time_ext_app_no_div input").attr("required", "false");
                $("#time_ext_app_date_div").css("display","none");
                $("#time_ext_app_date_div_div input").attr("required", "false");
                $("#time_ext_up_to_div").css("display","none");
                $("#time_ext_up_to_div input").attr("required", "false");

                $("#nssd_ltr_no_div").css("display","none");
                $("#nssd_ltr_no_div input").attr("required", "false");
                $("#nssd_ltr_date_div").css("display","none");
                $("#nssd_ltr_date_div input").attr("required", "false");
            }

            // bill type change

            $("#bill_forwarding_type").on("change",function () {
                var billType = $(this).val();
                if (billType == 5)
                {
                    $("#time_ext_app_no_div").css("display","block");
                    $("#time_ext_app_no_div input").attr("required", "true");
                    $("#time_ext_app_date_div").css("display","block");
                    $("#time_ext_app_date_div_div input").attr("required", "true");
                    $("#time_ext_up_to_div").css("display","block");
                    $("#time_ext_up_to_div input").attr("required", "true");

                    $("#nssd_ltr_no_div").css("display","none");
                    $("#nssd_ltr_no_div input").attr("required", "false");
                    $("#nssd_ltr_date_div").css("display","none");
                    $("#nssd_ltr_date_div input").attr("required", "false");
                }
                else if(billType == 7)
                {
                    $("#time_ext_app_no_div").css("display","none");
                    $("#time_ext_app_no_div input").attr("required", "false");
                    $("#time_ext_app_date_div").css("display","none");
                    $("#time_ext_app_date_div_div input").attr("required", "false");
                    $("#time_ext_up_to_div").css("display","none");
                    $("#time_ext_up_to_div input").attr("required", "false");

                    $("#nssd_ltr_no_div").css("display","block");
                    $("#nssd_ltr_no_div input").attr("required", "true");
                    $("#nssd_ltr_date_div").css("display","block");
                    $("#nssd_ltr_date_div input").attr("required", "true");
                }
                else
                {
                    $("#time_ext_app_no_div").css("display","none");
                    $("#time_ext_app_no_div input").attr("required", "false");
                    $("#time_ext_app_date_div").css("display","none");
                    $("#time_ext_app_date_div_div input").attr("required", "false");
                    $("#time_ext_up_to_div").css("display","none");
                    $("#time_ext_up_to_div input").attr("required", "false");

                    $("#nssd_ltr_no_div").css("display","none");
                    $("#nssd_ltr_no_div input").attr("required", "false");
                    $("#nssd_ltr_date_div").css("display","none");
                    $("#nssd_ltr_date_div input").attr("required", "false");
                }
            })
        });
    </script>
@stop


