@extends('layouts.default')

<style type="text/css">

    .custom-file-upload {
        border: 1px solid #ccc;
        display: inline-block;
        padding: 6px 12px;
        cursor: pointer;
    }
</style>

@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    Manual Tender
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
                        Create Manual Tender
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'manual-tender/store', 'files'=> true, 'class' => 'tender', 'id'=>'tender')) }}

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="requester">Tender Title:<span class="text-danger">*</span></label>
                                {!!  Form::text('tender_title', old('tender_title'), array('id'=> 'tender_title', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="stall_id">Tender Number :<span class="text-danger">*</span></label>
                                {!!  Form::text('tender_number', old('tender_number'), array('id'=> 'tender_number', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                    <!--  <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="stall_id">Demand Number :<span class="text-danger">*</span></label>
                                    {!!  Form::text('ref_tender_id', old('ref_tender_id'), array('id'=> 'ref_tender_id', 'class' => 'form-control')) !!}
                        </div>
                    </div> -->

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="stall_id">Approval Letter Number :</label>
                                {!!  Form::text('approval_letter_number', old('approval_letter_number'), array('id'=> 'approval_letter_number', 'class' => 'form-control')) !!}
                            </div>
                        </div>


                    <!-- <div class="col-md-4">
                                <div class="form-group"><label class="control-label " for="valid_date_to">Publishing To :<span class="text-danger">*</span></label>
                                    {!!  Form::text('valid_date_to', old('valid_date_to'), array('id'=> 'valid_date_to', 'class' => 'form-control datapicker2','readonly')) !!}
                        </div>
                    </div> -->

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label " for="stall_id">Tender Opening Date :<span class="text-danger">*</span></label>
                                {!!  Form::text('tender_opening_date', old('date_of_enrollment'), array('id'=> 'tender_opening_date', 'class' => 'form-control datapicker2','readonly')) !!}
                            </div>
                        </div>



                        {{-- <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="purchase_type">Purchase Type :<span class="text-danger">*</span></label>
                                {{ Form::select('purchase_type', array('1' => trans('english.ACTIVE'), '2' => trans('english.INACTIVE')), old('purchase_type'), array('class' => 'form-control selectpicker', 'id' => 'purchase_type')) }}
                            </div>
                        </div> --}}

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="tender_type">Tender Type :<span class="text-danger">*</span></label>
                                {{ Form::select('tender_type', array('' => '- Select -', '1' => 'LTM- Limited Tender Method', '2' => 'OTM- Open Tender Method', '3' => 'RTM- Restricted Tender Method', '4' => 'Spot Tender', '5' => 'DPM- Direct Purchase Method'), old('tender_type'), array('class' => 'form-control selectpicker', 'id' => 'tender_type')) }}
                            </div>
                        </div>


                        {{--<div class="col-md-4">--}}
                        {{--<div class="form-group"><label class="control-label" for="tender_nature">Tender Nature :<span class="text-danger">*</span></label>--}}
                        {{--{{ Form::select('tender_nature', array('' => '- Select -', '1' => 'Line Item', '2' => 'Lot Item'), old('tender_nature'), array('class' => 'form-control selectpicker', 'id' => 'tender_nature')) }}--}}
                        {{--</div>--}}
                        {{--</div>--}}

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="status">Tender Group :<span class="text-danger">*</span></label>
                                <select class="form-control selectpicker" name="tender_cat_id" id="tender_cat_id"  data-live-search="true">
                                    <option value="">{!! '- Select -' !!}</option>
                                    @foreach($supplyCategories as $sc)
                                        <option value="{!! $sc->id !!}">{!! $sc->name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="stall_id">Organization:<span class="text-danger">*</span></label>
                                <select class="form-control selectpicker" name="nsd_id" id="nsd_id"  data-live-search="true">
                                    <option value="">{!! '- Select -' !!}</option>
                                    @foreach($nsdNames as $nn)
                                        <option value="{!! $nn->id !!}">{!! $nn->name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>




                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label " for="stall_id">Quantity:</label>
                                {!!  Form::text('quantity', old('quantity'), array('id'=> 'quantity', 'class' => 'form-control')) !!}
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="stall_id">Specification :</label>
                                {!!  Form::file('specification[]', array('id'=> 'specification', 'class' => 'form-control', 'multiple', 'accept' => '.pdf,.doc,.docx')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="stall_id">Notice PDF :<span class="text-danger">*</span></label>
                                {!!  Form::file('notice', array('id'=> 'notice', 'class' => 'form-control', 'accept' => '.pdf','required'=>'required')) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="status">Status :<span class="text-danger">*</span></label>
                                {{ Form::select('status', array('1' => 'Published', '2' => 'Unpublished'), old('status'), array('class' => 'form-control selectpicker', 'id' => 'status')) }}
                            </div>
                        </div>

                        <!-- <div class="col-md-4">
                            <div class="form-group"><label class="control-label" for="status"></label>
                                <div class="checkbox checkbox-success">
                                    <input class="activity_1 activitycell" type="checkbox" id="open_tender" name="open_tender" value="1">
                                    <label for="open_tender">Open Tender</label>
                                </div>
                            </div>
                        </div> -->


                        <div class="form-group">
                            <div class="col-md-11 col-sm-offset-1">
                                <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>

                                <button type="submit" class="btn btn-primary pull-right">{!! 'Tender Publish' !!}</button>

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
        $(document).ready(function(){



        });
    </script>

@stop


