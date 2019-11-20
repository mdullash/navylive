
<div class="col-md-6">
    <div class="form-group">
        <label for="requester">Barcode Number:<span class="text-danger">*</span></label>
        {!!  Form::text('barcode_number', old('barcode_number'), array('id'=> 'barcode_number', 'class' => 'form-control', 'required', 'autocomplete'=> 'off', 'placeholder'=>'Search Barcode Number ...')) !!}
        <div class="form-group col-xs-12 col-sm-12 col-md-12" id="search_barcode_number_div" style="display: none; display: block; position: absolute; left: 0px;"></div>
    </div>
</div>
<div class="col-md-1">
    <div class="form-group">
        <br>
        <i class="fa fa-barcode" style="font-size: 43px;"></i>
    </div>
</div>


<div class="col-md-6">
    <div class="form-group"><label class="control-label" for="stall_id">Company Name :<span class="text-danger">*</span></label>
        {!!  Form::text('company_name', old('company_name'), array('id'=> 'company_name', 'class' => 'form-control', 'readonly')) !!}

    </div>
</div>


<div class="col-md-6">
    <div class="form-group"><label class="control-label" for="stall_id">Supplier Name :<span class="text-danger">*</span></label>
        {!!  Form::text('supplier_name', old('supplier_name'), array('id'=> 'supplier_name', 'class' => 'form-control', 'readonly')) !!}

    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="requester">Mobile Number:<span class="text-danger">*</span></label>
        {!!  Form::text('mobile_number', old('mobile_number'), array('id'=> 'mobile_number', 'class' => 'form-control', 'required','readonly')) !!}
    </div>
</div>


<div class="col-md-6">
    <div class="form-group"><label class="control-label" for="stall_id">Email :</label>
        {!!  Form::text('email', old('email'), array('id'=> 'email', 'class' => 'form-control', 'readonly')) !!}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group"><label class="control-label" for="stall_id">Amount :</label>
        {!!  Form::text('amount', old('amount'), array('id'=> 'amount', 'class' => 'form-control')) !!}
    </div>
</div>

