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
                    Upload file
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
                        Upload file
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'upload-file/post-file', 'files'=> true, 'class' => 'form-horizontal')) }}


                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">File Path :<span class="text-danger">*</span></label>
                            <div class="col-md-5">
                                {!!  Form::text('file_path', old('name'), array('id'=> 'file_path', 'class' => 'form-control', 'required')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-4 no-padding-right" for="stall_id">File :</label>
                            <div class="col-md-5">
                                {!!  Form::file('file_name', array('id'=> 'file_name', 'class' => 'form-control', 'required')) !!}
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

