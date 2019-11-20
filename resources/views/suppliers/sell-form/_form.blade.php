<div class="col-md-6">
    <div class="form-group">
        <label for="requester">Mobile Number:<span class="text-danger">*</span></label>
        {!!  Form::text('mobile_number', old('mobile_number'), array('id'=> 'mobile_number', 'class' => 'form-control', 'required', 'autocomplete'=> 'off', 'placeholder'=>'Search Mobile Number ...')) !!}
        <div class="form-group col-xs-12 col-sm-12 col-md-12" id="search_mobile_number_div" style="display: none; display: block; position: absolute; left: 0px;"></div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group"><label class="control-label" for="stall_id">Company Name :<span class="text-danger">*</span></label>
        {!!  Form::text('company_name', old('company_name'), array('id'=> 'company_name', 'class' => 'form-control', 'readonly')) !!}

    </div>
</div>


<div class="col-md-6">
    <div class="form-group"><label class="control-label" for="stall_id">Email :<span class="text-danger">*</span></label>
        {!!  Form::text('email', old('email'), array('id'=> 'email', 'class' => 'form-control', 'readonly')) !!}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group"><label class="control-label" for="stall_id">Amount :</label>
        {!!  Form::text('amount', old('amount'), array('id'=> 'amount', 'class' => 'form-control')) !!}
    </div>
</div>

