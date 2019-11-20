<div class="row">
    <div class="col-sm-6 col-md-6">

        <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Company Name :<span class="text-danger">*</span></label>
            <div class="col-md-7">
                {!!  Form::text('company_name', old('company_name'), array('id'=> 'company_name', 'class' => 'form-control')) !!}
            </div>
        </div>



        <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Mobile Number :<span class="text-danger">*</span></label>
            <div class="col-md-7">
                {!!  Form::text('mobile_number', old('mobile_number'), array('id'=> 'mobile_number', 'class' => 'form-control')) !!}
            </div>
        </div>



        <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Email :<span class="text-danger">*</span></label>
            <div class="col-md-7">
                {!!  Form::text('email', old('email'), array('id'=> 'email', 'class' => 'form-control','required')) !!}
            </div>
        </div>










    </div>

    <div class="col-sm-6 col-md-6">

        <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Organization :<span class="text-danger">*</span></label>
            <div class="col-md-7">
                <?php $selectedNsd = isset($enlistment) ? explode(',',$enlistment->registered_nsd_id):null ?>
                <select class="form-control selectpicker" name="registered_nsd_id[]" id="registered_nsd_id"  data-live-search="true" multiple="multiple">
                    <option value="" disabled="">{!! '- Select -' !!}</option>
                    @foreach($nsdNames as $nn)
                        <option value="{!! $nn->id !!}" @if( $nn->id==1) {!! 'selected' !!} @endif @if(isset($enlistment)) @foreach($selectedNsd as $sn) @if( $nn->id==$sn) {!! 'selected' !!} @endif @endforeach @endif> {!! $nn->name !!}</option>
                    @endforeach
                </select>
            </div>
        </div>



        <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Password :<span class="text-danger">*</span></label>
            <div class="col-md-7">
                {!! Form::password('password', array('id'=> 'password', 'class' => 'form-control', 'autocomplete' => 'off','required')) !!}
            </div>
        </div>

        <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Application File :</label>
            <div class="col-md-7">
                {!!  Form::file('application_file', array('id'=> 'application_file', 'class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group" style="display: none;"><label class="control-label col-md-5 no-padding-right" for="status">Status :<span class="text-danger">*</span></label>
            <div class="col-md-7">
                {{ Form::select('status', array('2' => trans('english.INACTIVE'), '1' => trans('english.ACTIVE')), old('status'), array('class' => 'form-control selectpicker', 'id' => 'status')) }}
            </div>
        </div>

    </div>

</div>