@extends('layouts.default')
@section('styles')
    <link href="{{ asset('public/chosenmultiselect/docsupport/prism.css') }}" rel="stylesheet">
    <link href="{{ asset('public/chosenmultiselect/chosen.css') }}" rel="stylesheet">
    <style type="text/css" media="screen">
        .chosen-container-multi .chosen-choices{
            width: 100% !important;
            padding: 2.5px !important;
            box-shadow: none !important;
            border: 1px solid #e2e2e2 !important;
            background-image: none !important;
        }
        .chosen-container .chosen-drop{
            width: 100% !important;
        }
        .chosen-container-multi .chosen-choices li.search-field input[type="text"] {
            padding-left: 25px !important;
            font-size: 85% !important;
        }
    </style>
@stop
@section('content')

<div class="small-header transition animated fadeIn">
    <div class="hpanel">
        <div class="panel-body">
            <h2 class="font-light m-b-xs">
                {!!trans('english.USER_MANAGEMENT')!!}
            </h2>
        </div>
    </div>
</div>

<div class="content animate-panel">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" style="">
            <div class="hpanel">
                <div class="panel-heading sub-title">
                    {!!trans('english.UPDATE_A_USER')!!}
                </div>
                <div class="panel-body">
                    {!! Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'PUT', 'files'=> true, 'class' => 'form-horizontal', 'id' => 'userId')) !!}                     
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="role_id">{!!trans('english.SELECT_ROLE')!!} :<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {{--{!! Form::select('role_id', $roleList, null, array('class' => 'js-source-states form-control', 'id' => 'ruleIdChange')) !!}--}}
                            <select class="form-control selectpicker" name="role_id" id="ruleIdChange"  data-live-search="true">
                                <option value="">{!! '- Select -' !!}</option>
                                @foreach($roleList as $rl)
                                    <option value="{!! $rl->id !!}" priority="{!! $rl->priority !!}" @if($rl->id == $user->role_id) {!! 'selected' !!} @endif>{!! $rl->name !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group"><label class="control-label col-md-3 no-padding-right" for="stall_id">Zone :{{-- <span class="text-danger">*</span> --}}</label>
                        <div class="col-md-6">
                            <select class="form-control selectpicker" name="zones[]" id="zones"  data-live-search="true" multiple="multiple" required>
                                <?php $selectedZones = explode(',',$user->zones); ?>
                                <option value="" disabled>{!! '- Select -' !!}</option>
                                @foreach($zones as $zn)
                                    <option value="{!! $zn->id !!}" @foreach($selectedZones as $szn) @if( $zn->id==$szn) {!! 'selected' !!} @endif @endforeach>{!! $zn->name !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group @if(empty($user->nsd_bsd)) hidden @endif" id="hideNSDBSD"><label class="control-label col-md-3 no-padding-right" for="stall_id">Organization :<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <select class="form-control selectpicker" name="nsd_bsd[]" id="nsd_bsd"  data-live-search="true" multiple="multiple">
                                @foreach($selectedNsdBsd as $snsdbsd)
                                    <option value="{!! $snsdbsd->id !!}" @if(!empty($user->nsd_bsd)) @foreach(explode(',',$user->nsd_bsd) as $nb) @if($snsdbsd->id == $nb) {!! 'selected' !!} @endif @endforeach @endif>{!! $snsdbsd->name !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group  @if(empty($user->nsd_bsd)) hidden @endif" id="hideCategoriesDiv"><label class="control-label col-md-3 no-padding-right" for="stall_id">Categories :{{-- <span class="text-danger">*</span> --}}</label>
                        <div class="col-md-6">
                            <select class="form-control selectpicker" name="categories_id[]" id="categories_id"  data-live-search="true" multiple="multiple">
                                @foreach($categoriesAll as $ctl)
                                    <option value="{!! $ctl->id !!}" @if(!empty($user->categories_id)) @foreach(explode(',',$user->categories_id) as $ctid) @if($ctl->id == $ctid) {!! 'selected' !!} @endif @endforeach @endif>{!! $ctl->name !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="UserFirstName">{!!trans('english.FIRST_NAME')!!} :<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {!! Form::text('first_name', old('first_name'), array('id'=> 'UserFirstName', 'class' => 'form-control', 'required' => 'true')) !!}
                        </div>
                    </div>
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="UserLastName">{!!trans('english.LAST_NAME')!!} :<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {!! Form::text('last_name', old('last_name'), array('id'=> 'UserLastName', 'class' => 'form-control', 'required' => 'true')) !!}
                        </div>
                    </div>
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="UserUsername">{!!trans('english.USERNAME')!!} :<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {!! Form::text('username', old('username'), array('id'=> 'UserUsername', 'class' => 'form-control', 'required' => 'true')) !!}
                        </div>
                    </div>
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="UserPassword">{!!trans('english.PASSWORD')!!} :<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {!! Form::password('password', array('id'=> 'UserPassword', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="UserConfirmPassword">{!!trans('english.CONFIRM_PASSWORD')!!} :<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {!! Form::password('password_confirmation', array('id'=> 'UserConfirmPassword', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="UserEmail">{!!trans('english.EMAIL')!!} :</label>
                        <div class="col-md-6">
                            {!! Form::email('email',old('email'), array('id'=> 'UserEmail', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="UserContactNo">{!!trans('english.CONTACT_NO')!!} :</label>
                        <div class="col-md-6">
                            {!! Form::text('contact_no',old('contact_no'), array('id'=> 'UserContactNo', 'class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="UserPhoto">{!!trans('english.PHOTO')!!} :</label>
                        <div class="col-md-6">
                            {!! Form::file('photo',old('photo'), array('id'=> 'UserPhoto', 'class' => 'form-control')) !!}
                            <div style="color:#B4B7B8">{!!trans('english.USER_PHOTO_HINTS')!!}</div>
                        </div>
                    </div>



                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="userStatusId">{!!trans('english.STATUS')!!} :<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            {!! Form::select('status_id', array('1' => 'Active', '2' => 'Inactive'), old('status_id'), array('class' => 'selectpicker form-control', 'id' => 'userStatusId')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="update_acl">{!!trans('english.UPDATE_ACL')!!}:</label>
                        <div class="col-md-6">
                            <div class="checkbox checkbox-success">
                                <input type="checkbox" name="update_acl" class="activity checkbox" data-column-id="1" id="update_acl" value="1" /> 
                                <label for="update_acl" style="font-weight: bold;"></label>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary" id="formSubmitBtn">{!!trans('english.UPDATE')!!}</button>
                            <button type="button" class="btn btn-default cancel">{!!trans('english.CANCEL')!!}</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

     
    $(function () {
        $('.categories_id').hide();
        $('.question_type_id').hide();
        $('.keywords').hide();
        if ($('#role_id').val() ==9 || $('#role_id').val() ==8 ){
            $('.categories_id').show();
            $('.question_type_id').show();
            $('.keywords').show();

        }else{
            $('.keywords').hide();
            $('.categories_id').hide();
            $('.question_type_id').hide();
            $('.categoryInput').attr('disabled',true);
            $('.questionInput').attr('disabled',true);
            $('.keywordInput').attr('disabled',true);

        }
        $('#userCreate').on("submit", function () {
            if ($('#role_id').val() == '') {
                alert('Select User Role');
                return false;
            }
            if ($('#UserFirstName').val() == '') {
                alert('Enter First Name');
                return false;
            }
            if ($('#UserLastName').val() == '') {
                alert('Enter Last Name');
                return false;
            }
            if ($('#UserUsername').val() == '') {
                alert('Enter Username');
                return false;
            }
            var passWord = $('#UserPassword').val();
            var confirmPassWord = $('#UserConfirmPassword').val();
            if (passWord !== '') {
                if (confirmPassWord == '') {
                    alert('Enter Confirm Password');
                    return false;
                }
                if (passWord !== confirmPassWord) {
                    alert('Password and Confirm Passowrd doesn\'t match');
                    return false;
                }
            }
        });
//        $(document).on('change','#role_id',function () {
//            if ($('#role_id').val() ==9 || $('#role_id').val() ==8 ){
//                $('.categories_id').show();
//                $('.question_type_id').show();
//                $('.keywords').show();
//                $('.keywordInput').show();
//
//            }else{
//                $('.categories_id').hide();
//                $('.question_type_id').hide();
//                $('.keywords').hide();
//                $('.categoryInput').attr('disabled',true);
//                $('.questionInput').attr('disabled',true);
//                $('.keywordInput').attr('disabled',true);
//
//            }
//        });
        
        var ifNsdOrBsdRequired = 'false';
        var zonesRequired      = 'false';
        $(document).on('change','#zones, #ruleIdChange',function () {
            var roleId = $("#ruleIdChange").val();
            if(roleId !=''){
                var rolePriority = $('#ruleIdChange option:selected').attr('priority');
                var zones = $("#zones").val();

                if(rolePriority>2 && (zones == null || zones == '')){
                    $("#zones").prop('required',true);
                    zonesRequired      = 'true';
                 }


                if(rolePriority>2 && zones != null){

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

                                // Categories
                                $("#categories_id").empty();
                                $('#categories_id').selectpicker('refresh');
                                $("#categories_id").append(_response['zonewisecat']);
                                $('#categories_id').selectpicker('refresh');
                            }

                        },
                        error: function(_response){
                            alert("error");
                        }

                    });/*End Ajax*/

                    $("#hideNSDBSD").removeClass('hidden');
                    $("#nsd_bsd").prop('required',true);

                    $("#hideCategoriesDiv").removeClass('hidden');
                    //$("#categories_id").prop('required',true);

                    ifNsdOrBsdRequired = 'true';
                }else{
                    $("#hideNSDBSD").addClass('hidden');
                    $("#nsd_bsd").prop('required',false);
                    $("#nsd_bsd").css({"border-color": "#e4e5e7"});

                    $("#hideCategoriesDiv").addClass('hidden');
                    $("#categories_id").prop('required',false);
                    $("#categories_id").css({"border-color": "#e4e5e7"});

                    ifNsdOrBsdRequired = 'false';
                }

            }else {
                alert('Select role first');
                $("#zones").val(0).selectpicker('refresh');
            }
            //alert($(this).val());
        });

        $(document).on('click','#formSubmitBtn',function () {
            if(zonesRequired=='true'){
                $("#zonesdiv .btn-default").css({"border-color": "#c0392b"});
            }

            var nsd_bsd_value  = $("#nsd_bsd").val(); 
            var category_value = $("#categories_id").val();
            if(ifNsdOrBsdRequired=='true' && nsd_bsd_value == null ){
                if(nsd_bsd_value == null){
                    $("#hideNSDBSD .btn-default").css({"border-color": "#c0392b"});
                }
                // if(category_value == null){
                //    $("#hideCategoriesDiv .btn-default").css({"border-color": "#c0392b"});
                // }
            }else{
                $("#hideNSDBSD .btn-default").css({"border-color": "#e4e5e7"});
                $("#hideCategoriesDiv .btn-default").css({"border-color": "#e4e5e7"});
            }
        });

        $(document).on('change','#zones',function () {
            var zonesValue = $(this).val();
            if(zonesValue != ''){
                $("#zonesdiv .btn-default").css({"border-color": "#e4e5e7"});
            }
        });

        $(document).on('change','#nsd_bsd',function () {
            var value = $(this).val();
            if(value != ''){
                $("#hideNSDBSD .btn-default").css({"border-color": "#e4e5e7"});
            }
        });
        $(document).on('change','#categories_id',function () {
            var value = $(this).val();
            if(value != ''){
                $("#hideCategoriesDiv .btn-default").css({"border-color": "#e4e5e7"});
            }
        });

    });
</script>
@stop

@section('js')
    <script src="{{ asset('public/chosenmultiselect/chosen.jquery.js') }}"></script>
    <script src="{{ asset('public/chosenmultiselect/docsupport/prism.js') }}"></script>
    <script src="{{ asset('public/chosenmultiselect/docsupport/init.js') }}"></script>
@stop