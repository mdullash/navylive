<div id="header">
    <div class="color-line">
    </div>
    <div id="logo" class="light-version">
        <?php
        $mobishop=\App\Settings::find(1);

        ?>
        <span>
            <b class="mm-group"><a href="{!! url('dashboard') !!}"><img src="{!! asset($mobishop->logo) !!}" alt="" style="height:60px; margin-top: -22px;"></a></b>
        </span>
    </div>
    <nav role="navigation">
        <div class="header-link hide-menu"><i class="fa fa-bars"></i></div>
        <div class="small-logo">
            <!--<span class="text-primary">HOMER APP</span>-->
        </div>
<!--        <form role="search" class="navbar-form-custom">
            <div class="form-group">
                <input type="text" class="form-control" name="search">
            </div>
        </form>-->
<!--        <div class="mobile-menu">
            <button type="button" class="navbar-toggle mobile-menu-toggle" data-toggle="collapse" data-target="#mobile-collapse">
                <i class="fa fa-chevron-down"></i>
            </button>
            <div class="collapse mobile-navbar" id="mobile-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <a class="" href="login.html">Login</a>
                    </li>
                    <li>
                        <a class="" href="login.html">Logout</a>
                    </li>
                    <li>
                        <a class="" href="profile.html">Profile</a>
                    </li>
                </ul>
            </div>
        </div>-->
        <div class="navbar-right">
            <ul class="nav navbar-nav no-borders">
                @if(count(explode(',',Auth::user()->zones)) >1)
                    @foreach($zonesFhead as $zf)
                        <li class="dropdown">
                            <a href="{{URL::to('dashboard/'.base64_encode($zf->id))}}" class="torlink" style="color: white; @if(\Session::get('zoneId') == $zf->id) {!! 'pointer-events: none;' !!} @endif">
                                {!! $zf->name !!}
                            </a>
                        </li>
                    @endforeach
                @endif
                <li class="dropdown">
                    <a href="{{ URL::to('logout') }}">
                        <i class="pe-7s-upload pe-rotate-90"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<script type="text/javascript">

    var safeColors = ['11','33','66','99','cc','ee'];//["#00e64d","#ff80aa","#990099","#30DDBC","#ff8533"];
    var rand = function() {
        return Math.floor(Math.random()*6);
    };
    var randomColor = function() {
        var r = safeColors[rand()];
        var g = safeColors[rand()];
        var b = safeColors[rand()];
        return "#"+r+g+b;
        //return r;
    };

    $(document).ready(function(){
        $('.torlink').each(function() {
            $(this).css('background',randomColor());
        });
    });

</script>