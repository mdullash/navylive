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
                   Approve Suppliers
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
                        Approve Suppliers
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'suppliers/waiting-for-clarence/store', 'files'=> true, 'class' => '', 'id'=>'')) }}


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
                                            <label for="inclusser">{!! 'Enclosure' !!}</label>
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
                                           <label for="info">{!! 'Info' !!}</label>
                                       </div>
                                       <div class="col-md-12">
                                           <textarea type="text" name="info" class="form-control" id="info" >
                                           </textarea>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>

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

                            <div class="row paddingClass">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>Suppliers</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="row paddingClass">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="info"></label>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="checkbox checkbox-success" style="margin-top: 13px;">
                                                    <input  type="checkbox" id="checkAll" name="is_contract_with" value="1">
                                                    <label for="checkAll" style="font-size: 14px;font-weight: 600;">ALL</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php $lastCount = 1; ?>
                            @if(!empty($suppliers))
                                @foreach($suppliers as $sc)
                                    <div class="row paddingClass remove">
                                        <div class="col-md-12">
                                            <div class="col-md-2">
                                                <div class="checkbox checkbox-success" style="; text-align: right;">
                                                    <input class="activity_1 activitycell" type="checkbox" id="supplier{!! $lastCount !!}" name="suppliers[]" value="{!! $sc->id !!}">
                                                    <label for="supplier{!! $lastCount !!}"></label>
                                                </div>

                                            </div>
                                            <div class="col-md-10">
                                                <label for="supplier{!! $lastCount !!}" class="form-control">{!! $sc->company_name !!}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $lastCount++; ?>
                                @endforeach
                            @endif



            
                            <div class="form-group">
                                <div class="col-md-12 ">
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                    
                                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">{!! 'Send to clearance' !!}</button>
                                    
                                </div>
                            </div>
                        </div>
                    <!-- <div class="hr-line-dashed"></div> -->
                        {!!   Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>

@stop

