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
                   Sell Form Management
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
                        Create Sell Form
                    </div>


                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'suppliers/sells-form', 'files'=> true, 'class' => '', 'id'=>'participent-form')) }}

                        @include('suppliers.sell-form._form')

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


            $('#mobile_number').keyup(function() {
                var query = $(this).val();

                if(query == ''){
                    $('#mobile_number').val('');
                    $('#_id').val('');
                    $('#search_mobile_number_div').fadeOut();
                }

                if (query != '') {
                    var _token     = "<?php echo csrf_token(); ?>";
                    $.ajax({
                        url: "{!! url('supplier-mobile-number-live-search') !!}",
                        method: "POST",
                        data: {query: query, _token: _token},
                        success: function (data) {
                            $('#search_mobile_number_div').fadeIn();
                            $('#search_mobile_number_div').html(data);
                        }
                    });
                }
            });

            $(document).on('click', '.searchMobileNumber', function () {
                $('#search_mobile_number_div').fadeOut();
                $('#mobile_number').val('');
                $('#_id').val('');
                $('#mobile_number').val($(this).text());
                $('#_id').val($(this).attr("value"));
                 insert($(this).text());
            });


          function insert(query){
              var _token     = "<?php echo csrf_token(); ?>";
              $.ajax({
                  url: "{!! url('mobile-number-live-search') !!}",
                  method: "POST",
                  data: {mobile_number: query, _token: _token},
                  success: function (data) {
                      $('#company_name').val(data.company_name);
                      $('#company_reg_number').val(data.company_regi_number_nsd);
                      $('#email').val(data.email);
                  }
              });
          }


            $('body').click(function(){
                $('#search_mobile_number_div').fadeOut();
            });

        });
    </script>

@stop


