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
                    <div class="panel-heading hbuilt">
                        <div class="pull-right">
                            <a class="btn btn-info btn-effect-ripple" href="{{ URL::to('message-to-supplier') }}"><i class="fa fa-plus"></i>Message To Supplier</a>
                        </div>
                            <h3>Supplier Conversation List</h3>
                    </div>
                        <div class="panel-body">

                            <div class="row">

                                {{ Form::open(array('role' => 'form', 'url' => '/supplier-chat-list', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'supplier-report')) }}

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="company_mobile">Supplier Name: </label>
                                            {!!  Form::text('supplier_name',Input::get('supplier_name'), array('id'=> 'supplier_name', 'class' => 'form-control', 'autocomplete'=> 'off','required')) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="col-md-12" style="padding-top: 22px;">
                                            <label for="email"></label>
                                            <button type="submit" class="btn btn-primary">{!! 'Search' !!}</button>
                                        </div>
                                    </div>
                                </div>

                                {!!   Form::close() !!}

                            </div>

                            <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                    <tr>
                                        <th>Ser</th>
                                        <th>Supplier Name</th>
                                        <th>Phone</th>
                                        <th>Last Message</th>
                                        <th>Send Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $supplierId = '';
                                    ?>
                                    @foreach($chats as $chat)
                                        @if($supplierId != $chat->id)
                                        <?php $supplierId = $chat->id  ?>
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $chat->company_name }}</td>
                                                <td>{{ $chat->mobile_number }}</td>
                                                <td>{{ $chat->message }}</td>
                                                <td>{{ $chat->created_at->diffForHumans() }}</td>
                                                <td><a href="{{ url('/supplier-chat/'.$chat->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-comments" aria-hidden="true"></i></a></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table><!---/table-responsive-->
                            {{ $chats->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function(){

            /*For Delete Department*/
            $(".exbtovdelete").click(function (e) {
                e.preventDefault();
                
                var id = this.id; 
                var url='{!! URL::to('/item/destroy') !!}'+'/'+id;
                bootbox.confirm("Are you sure?", function (result) {
                    if (result) {
                        var _url = $("#_url").val();
                        window.location.href = url;
                    }
                });
            });

        });
    </script>
@stop