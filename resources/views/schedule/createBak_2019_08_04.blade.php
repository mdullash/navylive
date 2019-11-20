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
                    Create Participant
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
                        Create Participant
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'tender-schedule-post', 'files'=> true, 'class' => '', 'id'=>'')) }}

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="requester">Tender Number:<span class="text-danger">*</span></label>
                                    {!!  Form::text('tender_number', old('tender_number'), array('id'=> 'tender_number', 'class' => 'form-control', 'required', 'autocomplete'=> 'off', 'placeholder'=>'Search tender number ...')) !!}
                                    <input type="hidden" id="tender_id" name="tender_id" value="">
                                    <div class="form-group col-xs-12 col-sm-12 col-md-12" id="search_tender_number_div" style="display: none; display: block; position: absolute; left: 0px;"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group"><label class="control-label" for="stall_id">Total Page :</label>
                                    {!!  Form::text('total_page', old('total_page'), array('id'=> 'total_page', 'class' => 'form-control')) !!}
                                    
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group"><label class="control-label" for="stall_id">Amount :</label>
                                    {!!  Form::number('amount', old('amount'), array('id'=> 'amount', 'class' => 'form-control', 'min'=>0)) !!}
                                </div>
                            </div>
                            
                            
                            <div class="col-md-5">
                                <div class="form-group"><label class="control-label" for="stall_id">Supplier Barcode: <span class="text-danger">*</span></label> 
                                    {!!  Form::text('supplier_reg_no', '', array('id'=> 'supplier_reg_no', 'class' => 'form-control', 'autocomplete'=>'off')) !!}
                                    <input type="hidden" id="supplier_id" name="supplier_id" value="">
                                    <div class="form-group" id="search_supplier_reg_no_div" style="display: none; display: block; position: absolute; left: 16px;"></div>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <br>
                                    <i class="fa fa-barcode" style="font-size: 43px;"></i>
                                </div>
                            </div>
                        
                        
                            <div class="col-md-6">
                                <div class="form-group"><label class="control-label" for="stall_id">Name :<span class="text-danger">*</span></label> 
                                    {!!  Form::text('supplier_name', old('supplier_name'), array('id'=> 'supplier_name', 'class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"><label class="control-label" for="stall_id">Company Name :<span class="text-danger">*</span></label> 
                                    {!!  Form::text('company_name', old('company_name'), array('id'=> 'company_name', 'class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"><label class="control-label" for="stall_id">Address :</label> 
                                    {!!  Form::text('address', old('address'), array('id'=> 'address', 'class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>
                                
            
                            <div class="form-group">
                                <div class="col-md-12 ">
                                    <a href="{{URL::previous()}}" class="btn btn-default cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                    
                                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">{!!trans('english.SAVE')!!}</button>
                                    
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

            $('#tender_number').keyup(function() {
                var query = $(this).val();

                if(query == ''){ 
                    $('#tender_number').val('');
                    $('#tender_id').val('');
                    $('#search_tender_number_div').fadeOut();
                }
                if (query != '') {
                    var _token     = "<?php echo csrf_token(); ?>";
                    $.ajax({
                        url: "tender-perticipate-tender-number-live-search",
                        method: "POST",
                        data: {query: query, _token: _token},
                        success: function (data) {
                            $('#search_tender_number_div').fadeIn();
                            $('#search_tender_number_div').html(data);
                        }
                    });
                }
            });

            $(document).on('click', '.searchTenderNumber', function () {
                $('#search_tender_number_div').fadeOut();
                $('#tender_number').val('');
                $('#tender_id').val('');
                //$('#search_tender_number').val($(this).text());
                // $('#tender_number').val($(this).attr("value"));
                $('#tender_number').val($(this).text());
                $('#tender_id').val($(this).attr("value"));

            });

            // ===========================================================
            // End tender number search
            // ===========================================================
            
            $('#supplier_reg_no').keyup(function() {
                var query2 = $(this).val();
                if(query2 == ''){ 
                    $('#supplier_id').val('');
                    $('#supplier_reg_no').val('');
                    $('#supplier_name').val('');
                    $('#company_name').val('');
                    $('#address').val('');
                
                    $('#search_supplier_reg_no_div').fadeOut();
                }

                if (query2 != '') {
                    var _token     = "<?php echo csrf_token(); ?>";
                    $.ajax({
                        url: "supplier-search-by-reg-or-bar-no",
                        method: "POST",
                        data: {query2: query2, _token: _token},
                        success: function (data) {
                            $('#search_supplier_reg_no_div').fadeIn();
                            $('#search_supplier_reg_no_div').html(data);
                        }
                    });
                }
            });

            $(document).on('click', '.searchSuppName', function () {
                   $('#search_supplier_reg_no_div').fadeOut();
                    $('#supplier_id').val('');
                    $('#supplier_reg_no').val('');
                    $('#supplier_name').val('');
                    $('#company_name').val('');
                    $('#address').val('');

                    $('#supplier_reg_no').val($(this).text());
                    $('#supplier_id').val($(this).attr("value"));
                    $('#company_name').val($(this).attr("companyname"));
                    if($(this).attr("fullname") == '' || $(this).attr("fullname") == null){
                        $('#supplier_name').val($(this).attr("companyname"));
                    }else{
                        $('#supplier_name').val($(this).attr("fullname"));
                    }
                    $('#address').val($(this).attr("address"));

            });

            // ===========================================================
            // End supplier number search
            // ===========================================================
            

            $('body').click(function(){
                $('#search_tender_number_div').fadeOut();
                $('#search_supplier_reg_no_div').fadeOut();
            });

        });
    </script>

@stop


