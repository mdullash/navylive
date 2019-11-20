<?php
use functions\OwnLibrary;
use App\Http\Controllers\ImageResizeController;
?>
@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Issue view</h3>
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
                        <h3>Issue view</h3>
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'waiting_for_issue/'.$id, 'files'=> true, 'class' => '', 'id'=>'')) }}

                        <input type="hidden" name="poDtsId" value="{!! $poDtsId !!}">
                        <input type="hidden" name="tenderId" value="{!! $tenderId !!}">
                        <input type="hidden" name="dmnPoToCrid" value="{!! $valuesFi->id !!}">
                        <input type="hidden" name="inspectinId" value="{!! $insId !!}">

                        <div class="col-md-12">


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="requester"> Issue:<span class="text-danger">*</span></label>
                                    {!!  Form::select('issue_by', ['1'=>'Gate pass','2'=>'S-549 (L) F(NS)-23'],$issue_datas!=null?$issue_datas->issue_by:null , array('id'=> 'gate_pass_no', 'class' => 'form-control','required','placeholder'=>'Issued By')) !!}
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="requester">Pass no:<span class="text-danger">*</span></label>
                                    {!!  Form::text('gate_pass_no', $issue_datas!=null?$issue_datas->gate_pass_no:$passNo, array('id'=> 'gate_pass_no', 'class' => 'form-control','required')) !!}
                                </div>
                            </div>




                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="requester">Date:<span class="text-danger">*</span></label>
                                    {!!  Form::text('date',$issue_datas!=null?$issue_datas->date:date('Y-m-d') , array('id'=> 'd44b_date', 'class' => 'form-control datapicker2','required')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="requester">Group:<span class="text-danger">*</span></label>
                                    <select class="form-control selectpicker requester" name="group_id" id="requester"  data-live-search="true" >
                                        <option value="">{!! '- Select -' !!}</option>
                                        @foreach($supplyCategories as $dmdn)
                                            <option value="{!! $dmdn->id !!}" @if($issue_datas!=null && $issue_datas->group_id == $dmdn->id )  selected @endif >{!! $dmdn->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="requester">Demanding:<span class="text-danger">*</span></label>
                                    <select class="form-control selectpicker requester" name="demanding_id" id="requester"  data-live-search="true">
                                        <option value="">{!! '- Select -' !!}</option>

                                        @foreach($demandeNames as $dmdn)
                                            <option value="{!! $dmdn->id !!}"  @if($issue_datas!=null && $issue_datas->demanding_id == $dmdn->id )  selected @endif>{!! $dmdn->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>





                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="requester">Received By:</label>
                                    {!!  Form::text('received_by', $issue_datas!=null?$issue_datas->received_by:null, array('id'=> 'received_opno', 'class' => 'form-control')) !!}
                                </div>
                            </div>



                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="requester">Received Rank:</label>
                                {!!  Form::text('received_rank', $issue_datas!=null?$issue_datas->received_rank:null, array('id'=> 'received_rank', 'class' => 'form-control')) !!}
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="requester">Received O/.P/.No:</label>
                                {!!  Form::text('received_opno', $issue_datas!=null?$issue_datas->received_opno:null, array('id'=> 'received_opno', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="requester">Received Address:</label>
                                {!!  Form::textarea('received_address', $issue_datas!=null?$issue_datas->received_address:null, array('id'=> 'received_address', 'class' => 'form-control','rows'=>3)) !!}
                            </div>
                        </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="requester">Reference:</label>
                                    {!!  Form::textarea('ref', $issue_datas!=null?$issue_datas->ref:null, array('id'=> 'issued_address', 'class' => 'form-control','rows'=>3)) !!}
                                </div>
                            </div>





                    <table class="table table-bordered table-hover table-striped middle-align">

                        <thead>
                        <tr class="center">

                            <th class="text-center">{!! 'Ser' !!}</th>
                            <th class="text-center">{!! 'IMC No' !!}</th>
                            <th class="text-center">{!! 'Item Name' !!}</th>
                            <th class="text-center">{{ 'DNO' }}</th>
                            <th class="text-center">{{ 'Quantity' }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if(!empty($qyeryResutl))
                            <?php $i=1;
                            ?>
                            @foreach($qyeryResutl as $qrl)


                                <tr>

                                    <td>{!! $i++ !!}</td>
                                    <td>  {!! $qrl->serial_imc_no !!}</td>

                                    <td>
                                        {!! $qrl->item_name !!}
                                    </td>

                                    <td>
                                        {!! $qrl->denoName !!}
                                    </td>

                                    <td>
                                        {!! $qrl->quantity !!}
                                    </td>



                                </tr>
                            @endforeach
                        @endif
                        </tbody>

                    </table>


                    <div class="form-group">
                        <div class="col-md-12">
                            <div>
                                <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                <?php if( !empty(Session::get('acl')[34][24]) ){ ?>
                                <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">{!! 'Action' !!}</button>
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

            //$(":checkbox").bind("click", false);

            $(".activitycell").change(function(){
                var checked = $(this).is(':checked'); // Checkbox state
                var lastClass = $(this).attr("class").split(' ').pop();
                if(checked){

                    $('.'+lastClass).each(function() {
                        $('input.'+lastClass+':checked').prop("checked", false);
                    });
                    $(this).prop("checked", true);

                }

                var valueOfSta = $(this).val();
                if($(this).is(':checked') && valueOfSta==3){
                    $(this).closest("td").find('.pr3').removeClass('hidden');
                }else{
                    $(this).closest("td").find('.pr3val').val('');
                    $(this).closest("td").find('.pr3').addClass('hidden');
                }

            });

        });
    </script>
@stop