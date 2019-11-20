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
                    Create Zone
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
                        Create Zone
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'zone/store', 'files'=> true, 'class' => 'form-horizontal zones', 'id'=>'zones')) }}


                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Name :<span class="text-danger">*</span></label>
                            <div class="col-md-5">
                                {!!  Form::text('name', old('name'), array('id'=> 'name', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Alies :<span class="text-danger">*</span></label>
                            <div class="col-md-5">
                                {!!  Form::text('alise', old('alise'), array('id'=> 'alise', 'class' => 'form-control', 'readonly')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Info :</label>
                            <div class="col-md-5">
                                {!!  Form::textarea('info', old('info'), array('id'=> 'info', 'rows' => '3', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">Icon :</label>
                            <div class="col-md-5">
                                {!!  Form::text('icon', old('icon'), array('id'=> 'icon', 'class' => 'form-control')) !!}
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

            $(document).on('input','#name',function(){
                var values = $('#name').val();
                values = values.replace(/\s+/g, '_').toLowerCase();
                $('#alise').val(values);
            })

        });
    </script>

@stop


