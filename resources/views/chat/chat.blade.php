@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Supplier Conversation</h3>
                </h2>
            </div>
            @include('layouts.flash')
        </div>
    </div>
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                   {{--  <div class="panel-heading hbuilt">
                        <div class="pull-right">
                        </div>
                            <h3></h3>
                    </div> --}}
                        <div class="panel-body">

                            <div class="row">

                            </div>

                            <div>
                            <div class="chat_area">
                            <div class="chatbox">
                            <div class="chat_header">
                                <a href="#"  class="close btn close_btn" >
                                    {{-- <span aria-hidden="true">&times;</span> --}}
                                </a>
                                <h3>Conversation With {{ $supplier->company_name }}</h3>
                            </div>
                            <div class="chat_body" id="messages"><!-- chat_body start-->

                                @foreach($chats as $chat)
                                    @if($chat->sender_type == 1)
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
                                                    <div class="msg_received"style="text-align: left;background-color: transparent !important; padding: 0;">
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
                                                    <div class="msg_send" style="text-align: right;background-color: transparent !important; padding: 0;">
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
                                <form action="{{ url("supplier-chat-submit") }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                                    <div class="input-group msg_textare">
                                        <div class="input-group-append">
                                            <input type="file" id="fileUpload" name="file" style="display:none"/>
                                            <span class="input-group-text attach_btn" onclick="$('#fileUpload').trigger('click'); return false;"><i class="fa fa-paperclip"></i></span>
                                        </div>
                                        <input name="message" class="form-control type_msg" placeholder="Type your message...">
                                        <div class="input-group-append">
                                            <button class="input-group-text send_btn" type="submit"><i class="fa fa-location-arrow"></i></button>
                                        </div>
                                    </div> 

                                                         
                                </form> 
                            </div><!--/.chat_footer-->
                        </div><!--/.chatbox-->
                        </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function(){
             $('#messages').scrollTop($('#messages')[0].scrollHeight);
        });
    </script>
@stop