<!-- Registration Modal -->

<footer class="footer">
    <div class="footer-header">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <hr style="margin-left: 41px;">
                    <?php
                        if (isset($footer_menu)){
                        if (!empty($footer_menu)){
                            $footer_array1 = array_slice($footer_menu, 0, 4);
                            $footer_array2 = array_slice($footer_menu, -4, 4, true);
                        }
                        }

                    ?>

                    <div>
                        <ul class="footer-menu">
                            @if(isset($footer_array1))
                            @foreach($footer_array1 as $footer)
                            <li><a href="{!! $footer['url'] !!}" @if($footer['_blank'] != null) target="_blank" @endif>{!! $footer['name'] !!}</a></li>
                            @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <hr>
                    <ul class="footer-menu">
                        @if(isset($footer_array2))
                            @foreach($footer_array2 as $footer)
                                <li><a href="{!! $footer['url'] !!}" @if($footer['_blank'] != null) target="_blank" @endif>{!! $footer['name'] !!}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="col-md-4">
                    <hr>
                    @if(isset($settings))
                    <ul class="footer-menu">
                        <li>
                            <p style="position: relative;top: -18px;">
                                <i class="fas fa-map-marker-alt"></i></p>
                            <p class="address">{!! $settings->location !!}</p>
                        </li>
                        <li>
                            <p><i class="far fa-envelope"></i></p>
                            <p class="address">{!! $settings->email !!}</p>
                        </li>
                        <li>
                            <p><i class="fas fa-phone-volume"></i></p>
                            <p class="address">{!! $settings->phone !!}</p>
                        </li>
                    </ul>
                     @endif
                </div>
                <div class="col-md-3">
                    <hr>
                    <ul class="footer-menu">
                        @if(isset($blog))
                            @foreach($blog as $b)
                        <li> ><a href="{!! url('blogpage/'.$b->slug) !!}">{!! $b->name !!}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-footer">
        <div class="container">
            <div class="copyright">
                <p><i class="far fa-copyright"></i><?php echo date('Y');?> @if(isset($settings)) {!! $settings->copyRight !!} @endif</p>
            </div>
            <div class="footer-social">
                <ul>
                    @if(isset($socialicon))
                        @foreach($socialicon as $icon)
                    <li><a href="{!! $icon->url !!}"><i class="fab fa-{!! lcfirst($icon->name) !!}"></i></a></li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
</footer>

<div class="modal fade registration" id="login">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row logo-area">
                    <!--                    <button type="button" class="close" data-dismiss="modal">&times;</button>-->
                    <div class="col-md-5">
                        <hr>
                    </div>
                    <div class="col-md-2 text-center">
                        <img src="{!! asset($settings->logo) !!}">
                    </div>
                    <div class="col-md-5">
                        <hr>
                    </div>
                </div>

                <h2 class="title">Log In</h2>
                <div class="auth-social">
                    <div>
                        <a href="#"><img src="{!! asset('public/frontend/images/google.png') !!}"></a>
                    </div>
                    <div>
                        <a href="#"><img src="{!! asset('public/frontend/images/facebook.png') !!}"></a>
                    </div>
                </div>
                <p class="or">
                    OR
                </p>

                {{ Form::open(array('url' => 'frontlogin', 'name'=>'form','files' => true,  'autocomplete'=>'off')) }}

                <div class="reg-form">
                    @include('layouts.flash')

                    {{ Form::email('email', Input::get('email'), array('id'=> 'email','class' => 'reg-form','placeholder'=>'Email','required'=>true)) }}
                    <div class="email" style="width:100%;"></div>
                    <div class="emailMessage" style="width:100%;"></div>
                    {{ Form::password('password', array('id'=> 'password','class' => 'reg-form','autocomplete'=>'off','placeholder'=>'Password','required'=>true)) }}
                    <div class="password" style="width:100%;"></div>

                    <input type="submit" class="submit" value="SUBMIT">
                    {{Form::close()}}
                    <a href="#"  data-toggle="modal" data-target="#forgetpassword" id="forgetpass"> Forget Password?</a>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-orange" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade registration" id="registration">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row logo-area">
                    <!--                    <button type="button" class="close" data-dismiss="modal">&times;</button>-->
                    <div class="col-md-5">
                        <hr>
                    </div>
                    <div class="col-md-2 text-center">
                        <img src="{!! asset($settings->logo) !!}">
                    </div>
                    <div class="col-md-5">
                        <hr>
                    </div>
                </div>

                <h2 class="title">create an account</h2>
                <div class="auth-social">
                    <div>
                        <a href="{!! url('google') !!}"><img src="{!! asset('public/frontend/images/google.png') !!}"></a>
                    </div>
                    <div>
                        <a href="{!! url('facebook') !!}"><img src="{!! asset('public/frontend/images/facebook.png') !!}"></a>
                    </div>
                </div>
                <p class="or">
                    OR
                </p>

                {{ Form::open(array('url' => 'registration', 'name'=>'form','files' => true,  'autocomplete'=>'off')) }}

                <div class="reg-form">
                    @include('layouts.flash')

                    {{ Form::text('name',Input::get('name'), array('id'=> 'name', 'class' => 'reg-form', 'placeholder'=>'Name','required'=>true)) }}
                    <div class="name" style="width:100%;"></div>
                    {{ Form::email('email', Input::get('email'), array('id'=> 'email','class' => 'reg-form','placeholder'=>'Email','required'=>true)) }}
                    <div class="email" style="width:100%;"></div>
                    <div class="emailMessage" style="width:100%;"></div>
                    <select name="district_id" id="district_id" class="reg-form" required>
                        <option value="0">Select Location</option>
                        @foreach($districtList as $dlist)
                            <option value="{!! $dlist->id !!}">{!! $dlist->name !!}</option>
                        @endforeach
                    </select>
                    {{ Form::text('phone',Input::get('phone'), array('id'=> 'mobile', 'placeholder'=>'01xxxxxxxxxxx','maxlength'=>'13','class' => 'query reg-form integer-only','required'=>true)) }}
                    <div class="mob" style="width:100%;"></div>
                    {{ Form::password('password', array('id'=> 'password','class' => 'reg-form','autocomplete'=>'off','placeholder'=>'Password','required'=>true)) }}
                    <div class="password" style="width:100%;"></div>

                    <input type="submit" class="submit" value="SUBMIT">
                    {{Form::close()}}
                    <a href="#" id="alreadySignup"> Already Signed Up?</a>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-orange" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade registration" id="forgetpassword">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row logo-area">
                    <!--                    <button type="button" class="close" data-dismiss="modal">&times;</button>-->
                    <div class="col-md-5">
                        <hr>
                    </div>
                    <div class="col-md-2 text-center">
                        <img src="{!! asset($settings->logo) !!}">
                    </div>
                    <div class="col-md-5">
                        <hr>
                    </div>
                </div>

                <h2 class="title">Password Recovery</h2>

                {{ Form::open(array('url' => 'forgot-password', 'name'=>'form','files' => true,  'autocomplete'=>'off')) }}

                    @include('layouts.flash')
                <div class="reg-form">

                    {{ Form::text('phone',Input::get('phone'), array('id'=> 'mobile', 'placeholder'=>'01xxxxxxxxxxx','maxlength'=>'13','class' => 'query reg-form integer-only','required'=>true)) }}
                     <div class="mob" style="width:100%;"></div>

                    <input type="submit" class="submit" value="SUBMIT">
                    {{Form::close()}}

                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-orange" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade registration" id="code">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row logo-area">
                    <!--                    <button type="button" class="close" data-dismiss="modal">&times;</button>-->
                    <div class="col-md-5">
                        <hr>
                    </div>
                    <div class="col-md-2 text-center">
                        <img src="{!! asset($settings->logo) !!}">
                    </div>
                    <div class="col-md-5">
                        <hr>
                    </div>
                </div>

                <h2 class="title">Verification Code</h2>

                {{ Form::open(array('url' => 'mobileVerifyPasswordChange', 'name'=>'form','files' => true,  'autocomplete'=>'off')) }}

                    @include('layouts.flash')
                <div class="reg-form">

                    {{ Form::number('code',Input::get('code'), array('id'=> 'code', 'placeholder'=>'####','maxlength'=>'4','class' => 'query reg-form integer-only','required'=>true)) }}
                    <div class="mob" style="width:100%;"></div>

                    <input type="submit" class="submit" value="SUBMIT">
                    {{Form::close()}}

                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-orange" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade registration" id="accountVerifycode">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row logo-area">
                    <!--                    <button type="button" class="close" data-dismiss="modal">&times;</button>-->
                    <div class="col-md-5">
                        <hr>
                    </div>
                    <div class="col-md-2 text-center">
                        <img src="{!! asset($settings->logo) !!}">
                    </div>
                    <div class="col-md-5">
                        <hr>
                    </div>
                </div>

                <h2 class="title">Verification Code</h2>

                {{ Form::open(array('url' => 'accountVerify', 'name'=>'form','files' => true,  'autocomplete'=>'off')) }}

                @include('layouts.flash')
                <div class="reg-form">

                    {{ Form::number('code',Input::get('code'), array('id'=> 'code', 'placeholder'=>'####','maxlength'=>'4','class' => 'query reg-form integer-only','required'=>true)) }}
                    <div class="mob" style="width:100%;"></div>

                    <input type="submit" class="submit" value="SUBMIT">
                    {{Form::close()}}

                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-orange" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade registration" id="recoverPassword">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row logo-area">
                    <!--                    <button type="button" class="close" data-dismiss="modal">&times;</button>-->
                    <div class="col-md-5">
                        <hr>
                    </div>
                    <div class="col-md-2 text-center">
                        <img src="{!! asset($settings->logo) !!}">
                    </div>
                    <div class="col-md-5">
                        <hr>
                    </div>
                </div>

                <h2 class="title">Recover Password</h2>

                {{ Form::open(array('url' => 'recoverpass', 'name'=>'form','files' => true,  'autocomplete'=>'off')) }}

                @include('layouts.flash')
                <div class="reg-form">

                    {{ Form::password('password', array('id'=> 'password','class' => 'reg-form','autocomplete'=>'off','placeholder'=>'New Password','required'=>true)) }}
                     <div class="password" style="width:100%;"></div>

                    {{ Form::password('confirm_password', array('id'=> 'confirm_password','class' => 'reg-form','autocomplete'=>'off','placeholder'=>'Confirm Password','required'=>true)) }}
                     <div class="password" style="width:100%;"></div>

                    <input type="submit" class="submit" value="SUBMIT">
                    {{Form::close()}}

                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-orange" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

@if(($errors->any()))
    <script>
        $('#registration').modal('show');
    </script>
@endif

@if((Session::get('success')!=null || Session::get('accountVerifycode')!=null))
    <script>
        $('#accountVerifycode').modal('show');
    </script>
@endif

@if(Session::get('error')!=null &&  Session::get('login'))
    <script>
        $('#login').modal('show');
    </script>
@endif

@if(Session::get('code')!=null)
    <script>
        $('#code').modal('show');
    </script>
@endif

@if(Session::get('forgetpassword')!=null)
    <script>
        $('#forgetpassword').modal('show');
    </script>
@endif

@if(Session::get('recoverPassword')!=null)
    <script>
        $('#recoverPassword').modal('show');
    </script>
@endif

@if(Session::get('recoverPassword')!=null && Session::get('success')!=null )
    <script>
        $('#recoverPassword').modal('show');
    </script>
@endif

@if(Session::get('recoverPassword')!=null && Session::get('error')!=null )
    <script>
        $('#recoverPassword').modal('show');
    </script>
@endif

@if(Session::get('accountVerifycode')!=null && Session::get('warning')!=null )
    <script>
        $('#accountVerifycode').modal('show');
    </script>
@endif