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
                    Create Currency
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
                        Create Currency
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'currency/store', 'files'=> true, 'class' => 'form-horizontal currency', 'id'=>'currency')) }}


                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Currency Name :<span class="text-danger">*</span></label>
                            <div class="col-md-5">
                                {!!  Form::text('currency_name', old('currency_name'), array('id'=> 'currency_name', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Symbol :<span class="text-danger">*</span></label>
                            <div class="col-md-5">
                                {!!  Form::text('symbol', old('symbol'), array('id'=> 'symbol', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Conversion Rate:</label>
                            <div class="col-md-5">
                                {!!  Form::number('conversion', old('conversion'), array('id'=> 'conversion', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="status">Status :<span class="text-danger">*</span></label>
                            <div class="col-md-5">
                                {{ Form::select('status', array('1' => trans('english.ACTIVE'), '2' => trans('english.INACTIVE')), old('status'), array('class' => 'form-control selectpicker', 'id' => 'status')) }}
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <div class="col-md-7 col-sm-offset-2">
                                <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                
                                <button type="submit" class="btn btn-primary pull-right">{!!trans('english.SAVE')!!}</button>
                                
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


