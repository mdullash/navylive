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
                   ID Card Purchase
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
                        ID Card Purchase
                    </div>


                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'suppliers/id-card-purchase', 'files'=> true, 'class' => '', 'id'=>'participent-form')) }}

                        @include('suppliers.id-card-purchase._form')

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


            $('#barcode_number').keyup(function() {
                var query = $(this).val();

                if(query == ''){
                    $('#barcode_number').val('');
                    $('#_id').val('');
                    $('#search_barcode_number_div').fadeOut();
                }

                if (query != '') {
                    var _token     = "<?php echo csrf_token(); ?>";
                    $.ajax({
                        url: "{!! url('supplier-mobile-number-barcode-live-search') !!}",
                        method: "POST",
                        data: {query: query, _token: _token},
                        success: function (data) {
                            $('#search_barcode_number_div').fadeIn();
                            $('#search_barcode_number_div').html(data);
                        }
                    });
                }
            });

            $(document).on('click', '.searchMobileNumber', function () {
                $('#search_barcode_number_div').fadeOut();
                $('#barcode_number').val('');
                $('#_id').val('');
                $('#barcode_number').val($(this).text());
                $('#_id').val($(this).attr("value"));
                insert($(this).text());
            });


            function insert(query){
                var _token     = "<?php echo csrf_token(); ?>";
                $.ajax({
                    url: "{!! url('barcode-number-live-search') !!}",
                    method: "POST",
                    data: {barcode_number: query, _token: _token},
                    success: function (data) {
                        $('#mobile_number').val(data.mobile_number);
                        $('#company_name').val(data.company_name);
                        $('#supplier_name').val(data.name);
                        $('#company_reg_number').val(data.company_regi_number_nsd);
                        $('#email').val(data.email);
                    }
                });
            }


            $('body').click(function(){
                $('#search_barcode_number_div').fadeOut();
            });

        });
    </script>

@stop


