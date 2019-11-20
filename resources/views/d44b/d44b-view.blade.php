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
                    <h3>D44B view</h3>
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
                        <h3>D44B view</h3>
                    </div>
                        <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'v44voucher-post', 'files'=> true, 'class' => '', 'id'=>'')) }}

                            <input type="hidden" name="poDtsId" value="{!! $poDtsId !!}">
                            <input type="hidden" name="tenderId" value="{!! $tenderId !!}">
                            <input type="hidden" name="dmnPoToCrid" value="{!! $valuesFi->id !!}">
                            <input type="hidden" name="inspectinId" value="{!! $insId !!}">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="requester">D44B No:<span class="text-danger">*</span></label>
                                    {!!  Form::text('d44b_no', '', array('id'=> 'd44b_no', 'class' => 'form-control', 'required')) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="requester">D44B Date:<span class="text-danger">*</span></label>
                                    {!!  Form::text('d44b_date', date('Y-m-d'), array('id'=> 'd44b_date', 'class' => 'form-control datapicker2', 'required', 'readonly')) !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="requester">Supplier Name:<span class="text-danger">*</span></label>
                                    {!!  Form::text('supplier_name', $supplierName, array('id'=> 'supplier_name', 'class' => 'form-control', 'required', 'readonly')) !!}
                                </div>
                            </div>

                                <table class="table table-bordered table-hover table-striped middle-align">

                                <thead>
                                    <tr class="center">
                                        
                                        <th class="text-center">{!! 'Item Name' !!}</th>
                                        <th class="text-center">{{ 'Quantity' }}</th>
                                        <th class="text-center">{!! 'Comment' !!}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if(!empty($qyeryResutl))
                                        @foreach($qyeryResutl as $qrl)

                                            <input type="hidden" name="all_sup[]" value="{!! $qrl->dmndtosupcotId !!}">
                                            <tr>
                                                
                                                <td>
                                                    {!! $qrl->item_name !!}
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" class="form-control quantity" id="quantity" name="quantity[{!! $qrl->dmndtosupcotId !!}][]" value="{!! $qrl->demand_cr_to_item_cr_receive_qty !!}" min="0" readonly>

                                                        <input type="hidden" name="dmndtosupcotId[]" value="{!! $qrl->dmndtosupcotId.'&'.$qrl->demand_cr_to_item_id !!}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <textarea class="form-control" name="d44b_comment[]" rows="1"></textarea>
                                                    </div>
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