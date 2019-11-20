@extends('layouts.default')
@section('content')
<div class="small-header transition animated fadeIn">
    <div class="hpanel">
        <div class="panel-body">
            <h2 class="font-light m-b-xs">
                {!!trans('english.YOUR_ACCOUNT')!!}
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
                    <h4>{!!trans('english.YOUR_ACCOUNT')!!}</h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <div class="col-md-8">      		
                            <div class="widget stacked ">
                                <div class="widget-content">
                                    <div class="tabbable">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="profile">
                                                {!! Form::open(array('role' => 'form', 'url' => 'users/editProfile', 'files'=> true, 'class' => 'form-horizontal col-md-8', 'id' => 'edit-profile')) !!}
                                                <fieldset>
                                                    <div class="form-group">											
                                                        <label for="username" class="col-md-4">{!!trans('english.USERNAME')!!}</label>
                                                        <div class="col-md-8 input text">
                                                            {!! Form::text('username', Auth::user()->username, array('id'=> 'username', 'class' => 'form-control', 'disabled' => 'true')) !!}
                                                            <p class="help-block">{!!trans('english.YOUR_USERNAME_IS_FOR_LOGGIN_IN_AND_CANNOT_BE_CHANGE')!!}</p>
                                                        </div> <!-- /controls -->				
                                                    </div> <!-- /control-group -->

                                                    <div class="form-group">											
                                                        <label for="firstname" class="col-md-4">{!!trans('english.FIRST_NAME')!!} <span class="text-danger"> *</span></label>
                                                        <div class="col-md-8 input text">
                                                            {!! Form::text('first_name', Auth::user()->first_name, array('id'=> 'firstname', 'class' => 'form-control', 'required' => 'true')) !!}
                                                        </div> <!-- /controls -->				
                                                    </div> <!-- /control-group -->


                                                    <div class="form-group">											
                                                        <label class="col-md-4" for="lastname">{!!trans('english.LAST_NAME')!!}<span class="text-danger"> *</span></label>
                                                        <div class="col-md-8 input text">
                                                            {!! Form::text('last_name', Auth::user()->last_name, array('id'=> 'lastname', 'class' => 'form-control', 'required' => 'true')) !!}
                                                        </div> <!-- /controls -->				
                                                    </div> <!-- /control-group -->
                                                    <div class="form-group">											
                                                        <label class="col-md-4" for="email">{!!trans('english.EMAIL_ADDRESS')!!}</label>
                                                        <div class="col-md-8 input text">
                                                            {!! Form::text('email', Auth::user()->email, array('id'=> 'email', 'class' => 'form-control')) !!}
                                                        </div> <!-- /controls -->				
                                                    </div> <!-- /control-group -->
                                                    <div class="form-group">											
                                                        <label class="col-md-4" for="designation">{!!trans('english.DESIGNATION')!!}</label>
                                                        <div class="col-md-8 input text">
                                                            {!! Form::text('designation', Auth::user()->designation, array('id'=> 'designation', 'class' => 'form-control')) !!}
                                                        </div> <!-- /controls -->				
                                                    </div> <!-- /control-group -->
                                                    <div class="form-group">											
                                                        <label class="col-md-4" for="contactNo">{!!trans('english.CONTACT_NO')!!}</label>
                                                        <div class="col-md-8 input text">
                                                            {!! Form::text('contact_no', Auth::user()->contact_no, array('id'=> 'contactNo', 'class' => 'form-control')) !!}
                                                        </div> <!-- /controls -->				
                                                    </div> <!-- /control-group -->
                                                    <div class="form-group">

                                                        <div class="col-md-offset-4 col-md-8">
                                                            <button class='btn btn-primary' type='submit'><i class='fa fa-save'></i> {!!trans('english.SAVE')!!}</button>
                                                            <a href="{!!URL::to('users/profile')!!}">
                                                                <span class="btn btn-default cancel">
                                                                    <i class="fa fa-arrow-left"></i>
                                                                    {!!trans('english.CANCEL')!!}
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </div> <!-- /form-actions -->
                                                </fieldset>
                                                {!!Form::file('photo', array('id' => 'photo', 'style' => 'display:none'))!!}
                                                {!! Form::close() !!}
                                            </div>

                                        </div>

                                    </div>
                                </div> <!-- /widget-content -->

                            </div> <!-- /widget -->

                        </div> <!-- /span8 -->


                        <div class="col-md-4">

                            <div class="well">

                                <h4>Profile Picture</h4>

                                <div class="form-group">
                                    @if(Auth::user()->photo != null)
                                        <a href="javascript:;" ><img width="140" id="upload_img" height="140" src="{!!URL::to('/')!!}/public/uploads/gallery/{!!Auth::user()->photo!!}" class="img-rounded" alt="" title="Edit Profile Picture"></a>
                                    @else
                                        <a href="javascript:;" ><img width="140" id="upload_img" height="140" src="{!!URL::to('/')!!}/public/img/unknown-photo.png" class="img-rounded" alt="" title="Edit Profile Picture"></a>
                                    @endif
                                </div> <!-- /control-group -->
                            </div>

                        </div> <!-- /span4 -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script>
    $('#upload_img').click(function () {
        $('#photo').click();
    });
    $('#photo').on('change', function() {

        $(".img-rounded").attr("src", URL.createObjectURL(this.files[0]));
    });
</script>
@stop

