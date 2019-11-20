@extends('frontend.layouts.master')
@section('content')
<style>
    table th,table td{
        text-align: left !important;
        padding-left: 5px;
        vertical-align: middle !important;
    }
</style>
    @include('layouts.flash')

    <!-- page-header -->
    <div id="inner-page-header" class="page-header position-relative">
        <div class="container">
            <div class="row">
                <!-- page caption -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                    <div class="page-caption">
                        <h1 class="page-title">Inbox</h1>
                    </div>
                </div>
                <!-- /.page caption -->
            </div>
        </div>
        <!-- page caption -->
        <div class="page-breadcrumb position-relative">
            <div class="container">
                <div class="row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{!! URL::to($a.$b.'login') !!}" class="breadcrumb-link">Supplier Login</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Inbox</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- page breadcrumb -->
    </div>
    <!-- /.page-header -->

    <!-- couple-sign in -->
    <section class="couple-bg-image pb-5 sectionBg">
        <div class="couple-form">
            <div class="container">
                <div class="row ">

                    @if (Auth::guard('supplier')->check())

                        <div class="col-lg-3 col-md-3 col-3">
                            @include('frontend/homeinc/menu')
                        </div>
                    @endif

                    <div class="col-lg-9 col-md-9 col-sm-12 col-9">
                        <!--st-tab-->
                        <div class="st-tab">
                            <div class="container">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                                            <!-- form-heading-title -->
                                    @if (Auth::guard('supplier')->check())
                            <div class="chat_area">
                            <div class="chatbox">
                            <div class="chat_header">
                                <a href="#"  class="close btn close_btn" >
                                    {{-- <span aria-hidden="true">&times;</span> --}}
                                </a>
                                <h3>Inbox</h3>
                            </div>
                            <div class="chat_body" id="messages"><!-- chat_body start-->

                                @foreach($chats as $chat)
                                    @if($chat->sender_type == 2)
                                        @if(!empty($chat->message))
                                            <div class="d-flex justify-content-start mb-4 msg_container">
                                                <div class="msg_received">
                                                    <p>{{ $chat->message }}</p>
                                                    <span class="msg_time">{{ $chat->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div><!--msg_received-->
                                        @endif

                                         @if(!empty($chat->file))
                                            @if($chat->file_type == "image")
                                                <div class="d-flex justify-content-start mb-4 msg_container">
                                                    <div class="msg_received"style="text-align: left;background-color: transparent !important;">
                                                       <img src="{{ asset($chat->file) }}" style="width: 60%;">
                                                        <span class="msg_time">{{ $chat->created_at->diffForHumans() }}</span>                                                
                                                    </div>
                                                </div><!--msg_received-->
                                            @else
                                            <div class="d-flex justify-content-start mb-4 msg_container">
                                                    <div class="msg_received">
                                                      <a href="{{ asset(asset($chat->file)) }}" download="download">Send File (Click to Download)</a>
                                                        <span class="msg_time">{{ $chat->created_at->diffForHumans() }}</span>                 
                                                    </div>
                                                </div><!--msg_received-->
                                            @endif
                                        @endif
                                    @else
                                        @if(!empty($chat->message))
                                            <div class="d-flex justify-content-end mb-4 msg_container"> <!--msg_send-->
                                                <div class="msg_send">
                                                    <p>{{ $chat->message }}</p>
                                                    <span class="msg_time">{{ $chat->created_at->diffForHumans() }}</span>                                    
                                                </div>
                                            </div><!--msg_send-->
                                        @endif
                                         @if(!empty($chat->file))
                                            <div class="d-flex justify-content-end mb-4 msg_container"> <!--msg_send-->
                                                @if($chat->file_type == "image")
                                                    <div class="msg_send" style="text-align: right;background-color: transparent !important;">
                                                       <img src="{{ asset($chat->file) }}" style="width: 60%;">
                                                        <span class="msg_time">{{ $chat->created_at->diffForHumans() }}</span>            
                                                    </div>
                                                @else
                                                    <div class="msg_send">
                                                       <a href="{{ asset(asset($chat->file)) }}" download="download">Send File (Click to Download)</a>
                                                        <span class="msg_time">{{ $chat->created_at->diffForHumans() }}</span>            
                                                    </div>
                                                @endif
                                            </div><!--msg_send-->
                                        @endif
                                    @endif
                                @endforeach
                            </div><!--/.chat_body end-->
                            <div class="chat_footer">
                                <form action="{{ url($a.$b."supplier-chat-submit") }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group msg_textare">
                                        <div class="input-group-append">
                                            <input type="file" id="fileUpload" name="file" style="display:none"/>
                                            <span class="input-group-text attach_btn" onclick="$('#fileUpload').trigger('click'); return false;"><i class="fas fa-paperclip"></i></span>
                                        </div>
                                        <input name="message" class="form-control type_msg" placeholder="Type your message...">
                                        <div class="input-group-append">
                                            <button class="input-group-text send_btn" type="submit"><i class="fas fa-location-arrow"></i></button>
                                        </div>
                                    </div>                         
                                </form> 
                            </div><!--/.chat_footer-->
                        </div><!--/.chatbox-->
                        </div>  
                                    @endif
                                </div>
                            </div>
                        </div><!--/.st-tab-->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.couple-sign up -->
    </section>
@stop

@section('footer-js')
    <script>
        $('#messages').scrollTop($('#messages')[0].scrollHeight);
    </script>
@endsection