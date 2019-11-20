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
                    Create Contact
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
                        Create Contact
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'terms-conditions', 'files'=> true, 'class' => 'form-horizontal contact', 'id'=>'contact')) }}

                        <div class="form-group"><label class="control-label col-md-3 no-padding-right" for="title">Title :<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                {!!  Form::text('title', old('title'), array('id'=> 'title', 'class' => 'form-control', 'required' => 'true')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-3 no-padding-right" for="title">Terms and Condition Category :<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                               <select name="category_id" required style="width: 100%;padding: 5px;border-radius: 5px;">
                                   <option selected disabled>Select Category</option>
                                   <@foreach($tcCategorys as $tcCategory)
                                        <option value="{{$tcCategory->id}}" class="form-control">{{$tcCategory->name}}</option>
                                        @endforeach
                               </select>
                            </div>
                        </div>


                        <div class="form-group"><label class="control-label col-md-3 no-padding-right" for="stall_id">Description :<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                    {!!  Form::textarea('descriptions', old('descriptions'), array('id'=> 'descriptions', 'rows' => '3', 'class' => 'form-control', 'required' => 'true')) !!}
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
        
        CKEDITOR.replace( 'descriptions', {
            enterMode: CKEDITOR.ENTER_BR
        } );
        
        //CKEDITOR.replace( 'descriptions' );
        // CKEDITOR.addCss('.cke_editable p { margin: 0 !important; }');
        // CKEDITOR.replace('editor1');

        $(document).ready(function(){

            $(document).on('change','#zones, #ruleIdChange',function () {

                var zones = $("#zones").val();
                if(zones != null){
                    var csrf = "<?php echo csrf_token(); ?>";
                    $.ajax({
                        type: 'post',
                        url: '../single-zone-wise-nsd-bsd',
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


