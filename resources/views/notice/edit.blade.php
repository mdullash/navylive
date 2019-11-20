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
                    Update Notice
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
                        Update Notice
                    </div>
                    <div class="panel-body">
                        {{ Form::model($editId, array('route' => array('notice.update', $editId->id), 'method' => 'PUT', 'files'=> true, 'class' => 'form form-horizontal validate-form notice', 'id' => 'supplierCategory')) }}

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Title :</label>
                            <div class="col-md-5">
                                {!!  Form::text('title', old('title'), array('id'=> 'title', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Zone :<span class="text-danger">*</span></label>
                            <div class="col-md-5">
                                <?php $selectedZones = explode(',',$editId->zones); ?>
                                <select class="form-control selectpicker" name="zones[]" id="zones"  data-live-search="true" multiple="multiple" required>
                                    <option value="" disabled>{!! '- Select -' !!}</option>
                                    @foreach($zones as $zn)
                                        <option value="{!! $zn->id !!}" @foreach($selectedZones as $szn) @if( $zn->id==$szn) {!! 'selected' !!} @endif @endforeach>{!! $zn->name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group" id="hideNSDBSD"><label class="control-label col-md-4 no-padding-right" for="stall_id">Organization:<span class="text-danger">*</span></label>
                            <div class="col-md-5">
                                <select class="form-control selectpicker" name="nsds_bsds[]" id="nsd_bsd"  data-live-search="true" multiple="multiple">
                                    @foreach($selectedNsdBsd as $snsdbsd)
                                        <option value="{!! $snsdbsd->id !!}" @if(!empty($editId->nsds_bsds)) @foreach(explode(',',$editId->nsds_bsds) as $nb) @if($snsdbsd->id == $nb) {!! 'selected' !!} @endif @endforeach @endif>{!! $snsdbsd->name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Description :<span class="text-danger">*</span></label>
                            <div class="col-md-5">
                                {!!  Form::textarea('description', old('description'), array('id'=> 'description', 'rows' => '3', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Upload PDF :</label>
                            <div class="col-md-5">
                                {!!  Form::file('upload_file', array('id'=> 'upload_file', 'class' => 'form-control', 'accept' => '.pdf')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="status"></label>
                            <div class="col-md-5">
                                <div class="checkbox checkbox-success">
                                    <input class="activity_1 activitycell" type="checkbox" id="open_tender" name="is_important" value="1" @if($editId->is_important==1) checked @endif>
                                    <label for="open_tender">Flash                                                                                                                                                                                                   </label>
                                </div>

                                <div class="checkbox checkbox-success">
                                    <input class="activity_1 activitycell" type="checkbox" id="is_general" name="is_general" value="1"  @if($editId->is_general==1) checked @endif>
                                    <label for="is_general">General                                                                                                                                                                                                   </label>
                                </div>
                            </div>
                        </div>



                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="status">Status :<span class="text-danger">*</span></label>
                            <div class="col-md-5">
                                {{ Form::select('status', array('1' => trans('english.ACTIVE'), '2' => trans('english.INACTIVE')), $editId->status_id, array('class' => 'form-control selectpicker', 'id' => 'status')) }}
                            </div>
                        </div>

                       
                        <div class="form-group">
                            <div class="col-md-7 col-sm-offset-2">
                                <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                
                                <button type="submit" class="btn btn-primary pull-right">{!!trans('english.SAVE')!!}</button>
                                
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

            $(document).on('change','#zones, #ruleIdChange',function () {

                var zones = $("#zones").val();
                if(zones != null){
                    var csrf = "<?php echo csrf_token(); ?>";
                    $.ajax({
                        type: 'post',
                        // url: '../zone-wise-nsd-bsd',
                        url: "{!!URL::to('zone-wise-nsd-bsd')!!}",
                        data: { _token: csrf, zones:zones},
                        //dataType: 'json',
                        success: function( _response ){
                            //alert(JSON.stringify(_response));
                            if(_response!==''){
                                $("#nsd_bsd").empty();
                                $('#nsd_bsd').selectpicker('refresh');
                                $("#nsd_bsd").append(_response['zonewosensbsd']);
                                $('#nsd_bsd').selectpicker('refresh');
                            }

                        },
                        error: function(_response){
                            alert("error");
                        }

                    });/*End Ajax*/

                }else{
                    $("#nsd_bsd").empty();
                    $('#nsd_bsd').selectpicker('refresh');
                }

                //alert($(this).val());
            });

        });
    </script>

@stop


