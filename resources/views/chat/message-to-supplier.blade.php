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
                    Supplier Conversation
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
                        Supplier Conversation
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('role' => 'form', 'url' => 'supplier-chat-submit', 'files'=> true, 'class' => 'form-horizontal items', 'id'=>'items')) }}
                    <div class="row">

                        <div class="form-group col-md-8 col-sm-8 col-md-offset-3" style="margin-left: 8%;">
                            <label for="supplier_id" class="control-label col-md-5 no-padding-right">Supplier <span class="text-danger">*</span></label>
                            <div class="col-md-7" id="supplier">
                                {{-- <select class="form-control selectpicker" name="supplier_id" id="supplier_id" data-live-search="true" required>                                
                                </select> --}}
                                 {!!  Form::text('tender_number', old('tender_number'), array('id'=> 'tender_number', 'class' => 'form-control', 'required', 'autocomplete'=> 'off', 'placeholder'=>'Search Supplier ...','required')) !!}
                                    <input type="hidden" id="tender_id" name="supplier_id" value="">
                                    <div class="form-group col-xs-12 col-sm-12 col-md-12" id="search_tender_number_div" style="display: none; display: block; position: absolute; left: 0px;"></div>
                            </div>
                        </div>

                        <div class="form-group col-md-8 col-sm-8 col-md-offset-3" style="margin-left: 8%;">
                            <label class="control-label col-md-5 no-padding-right" for="message">Message:</label>
                            <div class="col-md-7">
                                {!!  Form::text('message', old('message'), array('id'=> 'message', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group col-md-8 col-sm-8 col-md-offset-3 col-sm-offset-3" style="margin-left: 8%;">
                            <label class="control-label col-md-5 no-padding-right" for="file">File:</label>
                            <div class="col-md-7">
                                {!!  Form::file('file', old('file'), array('id'=> 'file', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                            <div class="form-group col-md-7" style="margin-left: 2%;">
                                <a href="{{URL::previous()}}" class="btn btn-danger cancel pull-right" >{!!trans('english.CANCEL')!!}</a>
                                
                                <button type="submit" class="btn btn-success pull-right">{!! "Send" !!}</button>
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
                        url: "{{ url('/supplier-list-ajax') }}",
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

            $('body').click(function(){
                $('#search_tender_number_div').fadeOut();
                $('#search_supplier_reg_no_div').fadeOut();
            });

            // $(document).on('input','#supplier .input-block-level',function(){

            //     var val = $(this).val();
            //     $.ajax({
            //         type:'POST',
            //         url:'{{ url('/supplier-list-ajax') }}',
            //         data:{
            //             "_token": "{{ csrf_token() }}",
            //             "search": val
            //             },
            //         success:function(data){
            //             $('.selectpicker').find('option').remove();
            //             $.each(data,function(key,val){
            //                 $('.selectpicker').append('<option value="'+val.id+'">'+val.company_name+'</option>');
            //             });
            //             $(".selectpicker").select2("val", "");
            //             $("#dropdown").on("change", function(e) {});
            //        }

            //     });
            // });

        });
    </script>

@stop


