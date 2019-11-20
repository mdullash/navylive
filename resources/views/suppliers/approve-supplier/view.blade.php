@extends('layouts.default')

<style type="text/css">

    .custom-file-upload {
        border: 1px solid #ccc;
        display: inline-block;
        padding: 6px 12px;
        cursor: pointer;
    }

    .paddingClass {
        padding-top: 10px;
    }
</style>

@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    Supplier Approval
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
                        Supplier Approval
                    </div>
                    <div class="panel-body">

                        <div class="row">
                            {{ Form::open(array('role' => 'form', 'url' => 'suppliers/supplier-approval/update/'.$dni_npm_approval->id, 'files'=> true, 'class' => '', 'id'=>'')) }}

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row paddingClass">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="requester" class="col-md-12 text-left">Date:<span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    {!!  Form::text('date', $dni_npm_approval->date, array('id'=> 'top_date', 'class' => 'form-control datapicker2', 'required', 'readonly')) !!}
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
                                                    <input type="text" class="form-control col-md-4" name="letter_no"  id="letter_no" value="{!! $dni_npm_approval->letter_no !!}" required="">
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
                                                    <textarea type="text" name="encloser" class="form-control" id="encloser">{!! $dni_npm_approval->encloser !!}</textarea>
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
                                                    <textarea type="text" name="info" class="form-control" id="info">{!! $dni_npm_approval->info !!}</textarea>
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
                                                            <input class="activity_1 activitycell" type="checkbox" id="supplier{!! $sc->supplier_id !!}" name="suppliers[]" value="{!! $sc->supplier_id !!}" checked="checked">
                                                            <label for="supplier{!! $sc->supplier_id !!}"></label>
                                                        </div>
                                                        <input class="" type="hidden"  name="approve_id[]" value="{!! $sc->supplier_approval_id !!}">

                                                    </td>

                                                    <td>{{++$sl}}</td>

                                                    <td>{{ $supName->company_name}}</td>
                                                    <td>{{ $supName->mobile_number}}</td>
                                                    <td>{{ $sc->date }}</td>
                                                    <td>{{ $sc->letter_no }}</td>
                                                </tr>

                                            @endforeach

                                        @else
                                            <tr>
                                                <td colspan="6">{{'Empty Data'}}</td>
                                            </tr>
                                        @endif

                                        </tbody>
                                    </table><!---/table-responsive-->
                                </div>



                                <div class="col-md-12 ">
                                    <?php if(!empty(Session::get('acl')[56][12])){ ?>

                                    @if($dni_npm_approval->status=='waiting-for-approve')
                                        <a class="btn btn-info  pull-left" id="{{$dni_npm_approval->id}}" href="{{ URL::to('suppliers/supplier-approval/approve/' . $dni_npm_approval->id ) }}" title="Approve" onclick="return confirm('Are you sure ?')">
                                            <i class="icon-check">Approve</i>
                                        </a>
                                    @endif
                                    <?php } ?>

                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                    <?php if(!empty(Session::get('acl')[56][3])){ ?>
                                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">{!! 'Update' !!}</button>
                                    <?php } ?>

                                </div>
                            </div>
                            {!!   Form::close() !!}



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
@stop

