<ul class="list-group">
    <li class="list-group-item @if(Request::segment(3)=='dashboard')active @endif" >
        <a href="{!! URL::to($a.$b.'dashboard') !!}" @if(Request::segment(3)=='dashboard') style="color:#fff;" @endif>Dashboard</a>
    </li>
    <li class="list-group-item @if(Request::segment(3)=='enlistment-track')active @endif" >
        <a href="{!! URL::to($a.$b.'enlistment-track') !!}" @if(Request::segment(3)=='enlistment-track') style="color:#fff;" @endif>Enlistment Track</a>
    </li>

    <li class="list-group-item @if(Request::segment(3)=='supplier-form-submit')active @endif" >
        <a href="{!! URL::to($a.$b.'supplier-form-submit') !!}" @if(Request::segment(3)=='supplier-form-submit') style="color:#fff;" @endif>Form Submit</a>
    </li>

    @if($approved !=0)
    <li class="list-group-item @if(Request::segment(3)=='tender-participant-status')active @endif">
        <a href="{!! URL::to($a.$b.'tender-participant-status') !!}" @if(Request::segment(3)=='tender-participant-status') style="color:#fff;" @endif>Tender Participant Status</a>
    </li>
    <li class="list-group-item @if(Request::segment(3)=='evaluation-report')active @endif" >
        <a href="{!! URL::to($a.$b.'evaluation-report') !!}" @if(Request::segment(3)=='evaluation-report') style="color:#fff;" @endif>Evaluation Report</a>
    </li>
    <li class="list-group-item @if(Request::segment(3)=='supplier-chat')active @endif" >
        <a href="{!! URL::to($a.$b.'supplier-chat') !!}" @if(Request::segment(3)=='supplier-chat') style="color:#fff;" @endif>Inbox</a>
    </li>
    @endif
    <li class="list-group-item @if(Request::segment(3)=='supplier-profile')active @endif" >
        <a href="{!! URL::to($a.$b.'supplier-profile') !!}" @if(Request::segment(3)=='supplier-profile') style="color:#fff;" @endif>Profile</a>
    </li>
    <li class="list-group-item @if(Request::segment(3)=='supplier-change-password')active @endif" >
        <a href="{!! URL::to($a.$b.'supplier-change-password') !!}" @if(Request::segment(3)=='supplier-change-password') style="color:#fff;" @endif>Change Password</a>
    </li>
    <li class="list-group-item">
        <a href="{!! url($a.$b.'logout') !!}">Logout</a>
    </li>
</ul>