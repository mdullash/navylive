@extends('layouts.default')

<style type="text/css">

.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}
</style>

@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    Headquarter Approval 
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
                        Headquarter Approval
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'post-headquarte-approval', 'files'=> true, 'class' => 'tender', 'id'=>'tender')) }}

                        <input type="hidden" name="demandId" value="{!! $demandsUp->id !!}">
                        <input type="hidden" name="tenderId" value="{!! $tenderId !!}">

                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label" for="status">Status :<span class="text-danger">*</span></label>
                                    {{ Form::select('headqurt_approval', array('' => 'Select', '1' => 'Approved', '3' =>'Reject'), $demandsUp->head_ofc_apvl_status, array('class' => 'form-control selectpicker', 'id' => 'headqurt_approval')) }}
                                </div>
                            </div> 
            
                            <div class="form-group">
                                <div class="col-md-11 col-sm-offset-1">
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                    
                                    <button type="submit" class="btn btn-primary pull-right">{!! 'Action' !!}</button>
                                    
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
        $(document).ready(function(){

            

        });
    </script>

@stop


