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
                    Update Evaluation Position
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
                        Update Evaluation Position
                    </div>
                    <div class="panel-body">
                        {{ Form::model($evlpotbl, array('route' => array('evaluation-position.update', $evlpotbl->id), 'method' => 'PUT', 'files'=> true, 'class' => 'form form-horizontal validate-form contact', 'id' => 'contact')) }}

                        <div class="form-group"><label class="control-label col-md-3 no-padding-right" for="title">Title :<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                {!!  Form::text('title', old('title'), array('id'=> 'title', 'class' => 'form-control', 'required' => 'true')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-3 no-padding-right" for="stall_id">Zone :<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <?php $selectedZones = explode(',',$evlpotbl->zones); ?>
                                <select class="form-control selectpicker" name="zones[]" id="zones"  data-live-search="true" multiple="multiple">
                                    <option value="" disabled>{!! '- Select -' !!}</option>
                                    @foreach($zones as $zn)
                                        <option value="{!! $zn->id !!}" @foreach($selectedZones as $szn) @if( $zn->id==$szn) {!! 'selected' !!} @endif @endforeach >{!! $zn->name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-3 no-padding-right" for="stall_id">Description :</label>
                            <div class="col-md-6">
                                {!!  Form::textarea('description', old('description'), array('id'=> 'description', 'rows' => '3', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-3 no-padding-right" for="status">Status:<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                {{ Form::select('status', array('1' => trans('english.ACTIVE'), '2' => trans('english.INACTIVE')), old('status'), array('class' => 'form-control selectpicker', 'id' => 'status')) }}
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


    <script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
    <script type="text/javascript">

        //CKEDITOR.replace( 'descriptions' );
        // CKEDITOR.addCss('.cke_editable { line-height: .50; }');
        // CKEDITOR.replace('editor1');
        CKEDITOR.replace( 'descriptions', {
            enterMode: CKEDITOR.ENTER_BR
        } );

        $(document).ready(function(){

            $(document).on('change','#zones, #ruleIdChange',function () {

                var zones = $("#zones").val();
                if(zones != null){
                    var csrf = "<?php echo csrf_token(); ?>";
                    $.ajax({
                        type: 'post',
                        // url: '../single-zone-wise-nsd-bsd',
                        url: "{!!URL::to('single-zone-wise-nsd-bsd')!!}",
                        data: { _token: csrf, zones:zones},
                        //dataType: 'json',
                        success: function( _response ){
                            // alert(JSON.stringify(_response));
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


