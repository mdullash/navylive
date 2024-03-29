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
                    Create Suppliers
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
                        Create Suppliers
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'suppliers/suppliers', 'files'=> true, 'class' => 'form-horizontal suppliers', 'id'=>'suppliers')) }}

                    <div class="row">
                        <div class="col-sm-6 col-md-6">

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Company Name :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('company_name', old('company_name'), array('id'=> 'company_name', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Barcode Number :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('barcode_number', old('barcode_number'), array('id'=> 'barcode_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Mobile Number :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('mobile_number', old('mobile_number'), array('id'=> 'mobile_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Vat Registration Number :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('vat_registration_number', old('vat_registration_number'), array('id'=> 'vat_registration_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Email :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('email', old('email'), array('id'=> 'email', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">NID Number :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('nid_number', old('nid_number'), array('id'=> 'nid_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Trade License Address :</label>
                                <div class="col-md-7">
                                    {!!  Form::textarea('trade_license_address', old('trade_license_address'), array('id'=> 'trade_license_address', 'rows' => '1', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Bank Account Number :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('bank_account_number', old('bank_account_number'), array('id'=> 'bank_account_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">BSTI Certification :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('bsti_certification', old('bsti_certification'), array('id'=> 'bsti_certification', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">ISO Certification :</label>
                                <div class="col-md-7">
                                    {!!  Form::text('iso_certification', old('iso_certification'), array('id'=> 'iso_certification', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Date Of Enrollment :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('date_of_enrollment', old('date_of_enrollment'), array('id'=> 'date_of_enrollment', 'class' => 'form-control datapicker2')) !!}
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-6 col-md-6">

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Company Reg. Number :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('company_regi_number_nsd', old('company_regi_number_nsd'), array('id'=> 'company_regi_number_nsd', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="status">Supply Category :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <select class="form-control selectpicker" name="supply_cat_id[]" id="supply_cat_id"  data-live-search="true" multiple="">
                                        <option value="" disabled="">{!! '- Select -' !!}</option>
                                        @foreach($supplyCategories as $sc)
                                            <option value="{!! $sc->id !!}">{!! $sc->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">TIN Number :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('tin_number', old('tin_number'), array('id'=> 'tin_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Password :</label>
                                <div class="col-md-7">
                                    {!! Form::password('password', array('id'=> 'password', 'class' => 'form-control', 'autocomplete' => 'off')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Trade License Number :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('trade_license_number', old('trade_license_number'), array('id'=> 'trade_license_number', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Bank Account Name :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('company_bank_account_name', old('company_bank_account_name'), array('id'=> 'company_bank_account_name', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Bank Name & Branch:<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {!!  Form::text('bank_name_and_branch', old('bank_name_and_branch'), array('id'=> 'bank_name_and_branch', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Organization:<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <!-- {!!  Form::text('registered_nsd_id', old('registered_nsd_id'), array('id'=> 'registered_nsd_id', 'class' => 'form-control')) !!} -->
                                    <select class="form-control selectpicker" name="registered_nsd_id[]" id="registered_nsd_id"  data-live-search="true" multiple="multiple">
                                        <option value="" disabled>{!! '- Select -' !!}</option>
                                        @foreach($nsdNames as $nn)
                                            <option value="{!! $nn->id !!}">{!! $nn->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="stall_id">Logo :</label>
                                <div class="col-md-7">
                                    {!!  Form::file('profile_pic', array('id'=> 'profile_pic', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group"><label class="control-label col-md-5 no-padding-right" for="status">Status :<span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    {{ Form::select('status', array('1' => trans('english.ACTIVE'), '2' => trans('english.INACTIVE')), old('status'), array('class' => 'form-control selectpicker', 'id' => 'status')) }}
                                </div>
                            </div>

                        </div>

                    </div>    

                        
                       
                        <div class="form-group">
                            <div class="col-md-7 col-sm-offset-5">
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


