@extends('layouts.default')

<style type="text/css">
    /*/ Tab Navigation /*/
    .nav-tabs {
        margin: 0;
        padding: 0;
        border: 0;
    }
    .nav-tabs > li > a {
        background: #f2f2f2;
        border-radius: 0;
        box-shadow: inset 0 -8px 7px -9px rgba(0,0,0,.4),-2px -2px 5px -2px rgba(0,0,0,.4);
    }
    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover {
        background: #F5F5F5;
        box-shadow: inset 0 0 0 0 rgba(0,0,0,.4),-2px -3px 5px -2px rgba(0,0,0,.4);
    }

    /*/ Tab Content /*/
    .tab-pane {
        background: #F5F5F5;
        box-shadow: 0 0 4px rgba(0,0,0,.4);
        border-radius: 0;
        text-align: center;
        padding: 10px;
        clear: bottom;

    }
</style>
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Forwarding For Approval</h3>
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

                        <h3>Forwarding For Approval</h3>
                    </div>
                    <div class="panel-body">

                        <div class="row">

                            {{ Form::open(array('role' => 'form', 'url' => 'suppliers/dni-npm-approval/', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'supplier-report')) }}



                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12" id="supplier">
                                        <label for="email">Supplier Name:</label>
                                        {!!  Form::text('supplier_name', $supplier_name, array('id'=> 'tender_number', 'class' => 'form-control',  'autocomplete'=> 'off', 'placeholder'=>'Search Supplier ...')) !!}
                                        <input type="hidden" id="tender_id" name="supplier_id" value="">
                                        <div class="form-group col-xs-12 col-sm-12 col-md-12" id="search_tender_number_div" style="display: none; display: block; position: absolute; left: 0px;"></div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">Letter No:</label>
                                        {!!  Form::text('letter_no', $letter_no, array('id'=> 'letter_no', 'class' => 'form-control', 'autocomplete'=> 'off')) !!}
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">From: </label>
                                        {!!  Form::text('from', $from, array('id'=> 'from', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">To:</label>
                                        {!!  Form::text('to', $to, array('id'=> 'to', 'class' => 'form-control datapicker2', 'autocomplete'=> 'off')) !!}
                                    </div>
                                </div>
                            </div>




                            <div class="col-md-1">
                                <div class="form-group">
                                    <div class="col-md-12" style="">
                                        <label for="email"></label>
                                        <button type="submit" class="btn btn-primary">{!! 'Search' !!}</button>
                                    </div>
                                </div>
                            </div>

                            {!!   Form::close() !!}

                        </div>
                        <div class="row">
                        <ul class="nav nav-tabs" style="margin-bottom: 20px;">
                            <?php
                            $segment=Request::segment(4);
                            $segment3=Request::segment(3);
                            $segment2=Request::segment(2);

                            ?>
                            <li @if($segment2=='dni-npm-approval')class="active" @endif><a href="{{URL::to('suppliers/dni-npm-approval')}}">Pending</a></li>
                            <li @if($segment3=='waiting-for-approve') class="active" @endif><a href="{{URL::to('suppliers/dni-npm-approval/waiting-for-approve')}}">Waiting for approve</a></li>
                            <li @if($segment3=='approved') class="active" @endif><a href="{{URL::to('suppliers/dni-npm-approval/approved')}}">Approved</a></li>

                        </ul>
                        </div>

                        {{ Form::open(array('role' => 'form', 'url' => 'suppliers/dni-npm-approval/store', 'files'=> true, 'class' => '', 'id'=>'')) }}


                        <div class="row">

                            <div class="col-md-6">
                                <div class="row paddingClass">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="requester" class="col-md-12 text-left">Date:<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                {!!  Form::text('date', date('Y-m-d'), array('id'=> 'top_date', 'class' => 'form-control datapicker2', 'required', 'readonly')) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row paddingClass">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="po_number" class="col-md-12">Letter No:<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control col-md-4" name="letter_no"  id="letter_no" value="" required="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="row paddingClass">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="inclusser">{!! 'Enclosure' !!}<span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-12">
                                                <textarea type="text" name="encloser" class="form-control" id="encloser"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row paddingClass">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="info">{!! 'Info' !!}<span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-12">
                                           <textarea type="text" name="info" class="form-control" id="info"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- <div class="hr-line-dashed"></div> -->

                        <div class="section">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped middle-align" style="margin-top: 20px;">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">
                                        <div class="checkbox checkbox-success" style="margin-top: 13px;">
                                            <input  type="checkbox" id="checkAll" name="is_contract_with" value="1">
                                            <label for=""></label>
                                        </div>
                                    </th>
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Supplier Name'}}</th>
                                    <th class="text-center" width="">{{'Mobile Number'}}</th>
                                    <th class="text-center" width="">{{'Date'}}</th>
                                    <th class="text-center" width="">{{'Letter NO'}}</th>
                                    <th class="text-center">{{ 'DNI Note'}}</th>
                                    <th class="text-center">{{ 'NPM Note'  }}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if (!$suppliers->isEmpty())

                                    <?php
                                    $page = \Input::get('page');
                                    $page = empty($page) ? 1 : $page;
                                    $sl = ($page-1)*10;
                                    $l = 1;

                                    ?>
                                    @foreach($suppliers as $sc)

                                        <?php

                                        $supName = \App\Supplier::where('id',$sc->supplier_id)->select('company_name','mobile_number')->first();

                                        ?>

                                        <tr>
                                            <td>
                                                <div class="checkbox checkbox-success" style="; text-align: right;">
                                                    <input class="activity_1 activitycell" type="checkbox" id="supplier{!! $sc->supplier_id !!}" name="suppliers[]" value="{!! $sc->supplier_id !!}">
                                                    <label for="supplier{!! $sc->supplier_id !!}"></label>
                                                </div>
                                                <input class="" type="hidden"  name="approve_id[]" value="{!! $sc->approve_id !!}">

                                            </td>

                                            <td>{{++$sl}}</td>

                                            <td>{{ $supName->company_name}}</td>
                                            <td>{{ $supName->mobile_number}}</td>
                                            <td>{{ $sc->date }}</td>
                                            <td>{{ $sc->letter_no }}</td>
                                            <td>{{ $sc->dni_description}}</td>
                                            <td>{{ $sc->npm_description}}</td>

                                        </tr>

                                    @endforeach

                                @else
                                    <tr>
                                        <td colspan="8">{{'Empty Data'}}</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table><!---/table-responsive-->
                        </div>

                                <div class="col-md-12 ">
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                    <?php if(!empty(Session::get('acl')[51][2])){ ?>
                                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">{!! 'Save' !!}</button>
                                     <?php } ?>

                            </div>
                        </div>
                        {!!   Form::close() !!}


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
                var url='{!! URL::to('suppliers/enlistment/destroy') !!}'+'/'+id;
                bootbox.confirm("Are you sure?", function (result) {
                    if (result) {
                        var _url = $("#_url").val();
                        window.location.href = url;
                    }
                });
            });

            /*For Approve supplier*/
            $(".approve").click(function (e) {
                e.preventDefault();

                var id = this.id;
                var url='{!! URL::to('suppliers/enlistment/approved') !!}'+'/'+id;
                bootbox.confirm("Are you sure?", function (result) {
                    if (result) {
                        var _url = $("#_url").val();
                        window.location.href = url;
                    }
                });
            });

            /*For rejecte supplier*/
            $(".rejecte").click(function (e) {
                e.preventDefault();

                var id = this.id;
                var url='{!! URL::to('suppliers/enlistment/rejected') !!}'+'/'+id;
                bootbox.confirm("Are you sure?", function (result) {
                    if (result) {
                        var _url = $("#_url").val();
                        window.location.href = url;
                    }
                });
            });

        });



    </script>


    <script type="text/javascript">
        $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function(){


            $('#tender_number').keyup(function() {
                var query = $(this).val();

                if(query == ''){
                    $('#tender_number').val('');
                    $('#tender_id').val('');
                    $('#search_tender_number_div').fadeOut();
                }
                if (query != '') {
                    var _token     = "<?php echo csrf_token(); ?>";
                    $.ajax({
                        url: "{{ url('/supplier-list-ajax') }}",
                        method: "POST",
                        data: {query: query, _token: _token},
                        success: function (data) {
                            $('#search_tender_number_div').fadeIn();
                            $('#search_tender_number_div').html(data);
                        }
                    });
                }
            });

            $(document).on('click', '.searchTenderNumber', function () {
                $('#search_tender_number_div').fadeOut();
                $('#tender_number').val('');
                $('#tender_id').val('');
                //$('#search_tender_number').val($(this).text());
                // $('#tender_number').val($(this).attr("value"));
                $('#tender_number').val($(this).text());
                $('#tender_id').val($(this).attr("value"));

            });

            $('body').click(function(){
                $('#search_tender_number_div').fadeOut();
                $('#search_supplier_reg_no_div').fadeOut();
            });

            // $(document).on('input','#supplier .input-block-level',function(){

            //     var val = $(this).val();
            //     $.ajax({
            //         type:'POST',
            //         url:'{{ url('/supplier-list-ajax') }}',
            //         data:{
            //             "_token": "{{ csrf_token() }}",
            //             "search": val
            //             },
            //         success:function(data){
            //             $('.selectpicker').find('option').remove();
            //             $.each(data,function(key,val){
            //                 $('.selectpicker').append('<option value="'+val.id+'">'+val.company_name+'</option>');
            //             });
            //             $(".selectpicker").select2("val", "");
            //             $("#dropdown").on("change", function(e) {});
            //        }

            //     });
            // });

        });
    </script>

@stop
