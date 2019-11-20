@extends('layouts.default')
@section('content')

<div class="small-header transition animated fadeIn">
    <div class="hpanel">
        <div class="panel-body">
            <h2 class="font-light m-b-xs">
                {!!trans('english.ROLE_ACCESS_CONTROL')!!}
            </h2>
        </div>
        @include('layouts.flash')
    </div>
</div>

<div class="content animate-panel">
    <div class="row">
        <div class="col-sm-12 col-sm-offset-0 col-md-12 col-md-offset-0" style="">
            <div class="hpanel">
                <div class="panel-heading sub-title">
                    {!!trans('english.SETUP_ROLE_ACCESS_CONTROL')!!}
                </div>
                <div class="panel-body">
                    {!! Form::open(array('role' => 'form', 'url' => 'roleacl', 'class' => 'form-horizontal')) !!}
                    {{ csrf_field() }}
                    <div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right">{!!trans('english.SELECT_ROLE')!!}<span class="text-danger">*</span></label>
                        <div class="col-md-4">
                            {!!Form::select('role_id', $roleList, null, array('class' => 'form-control js-source-states', 'id' => 'role_id'))!!}
                        </div>
                    </div>
                    <div id="access-control-setup">
                        <!--AJAX content will be loaded here-->
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" lang="javascript">
    $(document).ready(function () {

        $("#role_id").change(function () {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                type: "POST",
                url: "{!! URL::to('roleAclSetup') !!}",
                data: {role_id: $("#role_id").val(), _token : _token},
                dataType: "text",
                cache: false,
                success:
                        function (data) {
                            $("#access-control-setup").html(data);
                            $(".cancel").click(function () {
                                history.go(-1);
                            });
                        }
            });
            return false;
        });

        //check/uncheck all
        $(document).on("change", '#access_table .m_activity', function () {
            var columnId = $(this).data('column-id');
            if ($(this).prop('checked')) {
                $('.activity_' + columnId).prop('checked', true);
            } else {
                $('.activity_' + columnId).prop('checked', false);
            }
        });

        $(document).on("change", '.activitycell', function () {
            var columnId = $(this).data('column-id');
            if ($('.activity_' + columnId + ':checked').length == $('.activity_' + columnId).length) {
                $('#activity_header_' + columnId).prop('checked', true);
            } else {
                $('#activity_header_' + columnId).prop('checked', false);
            }
        });

    });
</script>

@stop