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
                    Create Evaluation Criteria
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
                        Create Evaluation Criteria
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'evaluation-criteria', 'files'=> true, 'class' => 'form-horizontal evaluation-criteria', 'id'=>'evaluation-criteria')) }}

                        <div class="form-group"><label class="control-label col-md-3 no-padding-right" for="title">Title :<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                {!!  Form::text('title', old('title'), array('id'=> 'title', 'class' => 'form-control', 'required' => 'true')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-3 no-padding-right" for="stall_id">Positions :<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <select class="form-control selectpicker" name="positions[]" id="positions"  data-live-search="true" multiple="multiple" required="">
                                    <option value="" disabled>{!! '- Select -' !!}</option>
                                    @foreach($positions as $pnt)
                                        <option value="{!! $pnt->id !!}">{!! $pnt->title !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-3 no-padding-right" for="weight">Weight :<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                {!!  Form::text('weight', old('weight'), array('id'=> 'weight', 'class' => 'form-control', 'required' => 'true')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-3 no-padding-right" for="stall_id">Zone :<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <select class="form-control selectpicker" name="zones[]" id="zones"  data-live-search="true" multiple="multiple">
                                    <option value="" disabled>{!! '- Select -' !!}</option>
                                    @foreach($zones as $zn)
                                        <option value="{!! $zn->id !!}" selected="">{!! $zn->name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-3 no-padding-right" for="stall_id">Description :</label>
                            <div class="col-md-6">
                                {!!  Form::textarea('description', old('description'), array('id'=> 'description', 'rows' => '3', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-3 no-padding-right" for="status">Status:<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                {{ Form::select('status', array('1' => trans('english.ACTIVE'), '2' => trans('english.INACTIVE')), old('status'), array('class' => 'form-control selectpicker', 'id' => 'status')) }}
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-md-3 no-padding-right" style="color: white;">.</label>
                            <br />
                            <div class="col-md-6">
                                <div class="checkbox checkbox-success" style="">
                                    <input class="activity_1" type="checkbox" id="comment" name="comment" value="1">
                                    <label for="comment" style="">Evaluation Comment (Yes/No)</label>
                                </div>
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
        
    </script>

@stop


