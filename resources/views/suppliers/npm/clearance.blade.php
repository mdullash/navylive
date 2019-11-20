@extends('layouts.default')

<style type="text/css">

    .custom-file-upload {
        border: 1px solid #ccc;
        display: inline-block;
        padding: 6px 12px;
        cursor: pointer;
    }
    .paddingClass{
        padding-top: 10px;
    }
</style>

@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    NPM Clarence
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
                        NPM Clarence
                    </div>

                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'suppliers/npm/store/'.$suppliers->id, 'files'=> true, 'class' => '', 'id'=>'')) }}


                        <div class="row">

                            <div class="approved_top">
                                <div class="col-md-6">
                                    <div class="row paddingClass ">
                                        <div class="col-md-12">
                                            <div class="form-group d-flex">
                                                <label for="requester" class="text-left mr_5">Date:</label>
                                                {!! $suppliers->date !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row paddingClass">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <label for="inclusser">{!! 'Enclosure' !!}</label>
                                                </div>
                                                <div class="col-md-12">
                                                    {!! $suppliers->encloser !!}

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row paddingClass">
                                        <div class="col-md-12">
                                            <div class="form-group d-flex">
                                                <label for="po_number" class="mr_5">Letter No:</label>
                                                {!! $suppliers->letter_no !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row paddingClass">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <label for="info">{!! 'Info' !!}</label>
                                                </div>
                                                <div class="col-md-12">

                                                    {!! $suppliers->info !!}


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!--/approved_top-->

                            <div class="col-md-4">
                                <div class="row paddingClass">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="info"></label>
                                            </div>
                                            <div class="col-md-12">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        <table class="table table-bordered table-hover table-striped middle-align" style="margin-top: 20px;">
                            <thead>
                            <tr class="center">
                                <th class="text-center" width="5%">SL#</th>
                                <th class="text-center">{{'Company Information'}}</th>
                                <th class="text-center" width="">{{'Approval Note'}}</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if(!empty($suppliers))
                                <?php $sl=0;  ?>
                                @foreach($suppliers->supplierInfos()->get() as $sc)
                                    <tr>
                                        <td>{{++$sl}}</td>
                                        <td>{{$sc->company_name}},<br>{{$sc->head_office_address}},<br>{{$sc->	email}},<br>{{$sc->mobile_number}}</td>
                                        <td> <textarea name="supplier[{!! $sc->id !!}]" class="form-control" placeholder="Description">{!! $sc['pivot']->npm_description !!}</textarea>
                                        </td>

                                    </tr>

                                @endforeach

                            @endif

                            </tbody>
                        </table><!---/table-responsive-->


                        <div class="form-group">
                                <div class="col-md-12 ">
                                    <?php if(!empty(Session::get('acl')[50][12])){ ?>

                                @if($suppliers->npm_status=='waiting-for-approve')
                                    <a class="btn btn-info  pull-left" id="{{$suppliers->id}}" href="{{ URL::to('suppliers/npm/approved/' . $suppliers->id ) }}" title="Approved" onclick="return confirm('Are you sure ?')">
                                        <i class="icon-check">Approve</i>
                                    </a>
                                   @endif
                                   <?php } ?>
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>

                                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">{!! 'Save' !!}</button>

                                </div>
                            </div>
                        </div>
                        <!-- <div class="hr-line-dashed"></div> -->
                        {!!   Form::close() !!}

                    </div>
                </div>
            </div>
        </div>


@stop

