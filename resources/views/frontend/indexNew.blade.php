@extends('frontend.layouts.master')
@section('content')

    @include('frontend.homeinc._hero_section')
	@include('frontend.homeinc._new_notice')
    @include('frontend.homeinc._recent_tender')
    @include('frontend.homeinc._upcoming_deadline')

    {{-- @include('frontend.homeinc._notice') --}}

    {{--<script type="text/javascript">--}}
        {{--$(document).ready(function(){--}}

            {{--alert();--}}

        {{--});--}}
    {{--</script>--}}

@stop