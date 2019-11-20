@extends('frontend.layouts.master')
@section('content')

    <!-- page-header -->
    <div id="inner-page-header" class="page-header position-relative">
        <div class="container">
            <div class="row">
                <!-- page caption -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                    <div class="page-caption">
                        <h1 class="page-title">Tender List</h1>
                    </div>
                </div>
                <!-- /.page caption -->
            </div>
        </div>
        <!-- page caption -->
        <div class="page-breadcrumb position-relative">
            <div class="container">
                <div class="row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{!! URL::to('/').'/'.$a.$b !!}" class="breadcrumb-link">Home</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Tender List</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- page breadcrumb -->
    </div>
    <!-- /.page-header -->
    <!-- tender-list-content -->
    <div class="tenderListContent content sectionBg navySelect">
        <div class="container">
            <div class="row">


                <!-- sidebar-section -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="filter-form">
                        {{--<form class="form-row">--}}
                        {{ Form::open(array('role' => 'form', 'url' => $a.$b.'front-tender', 'files'=> true, 'method'=>'get', 'class' => 'form-row')) }}
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <h3 class="widget-title">filter</h3>
                        </div>
                        {{--<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">--}}
                        {{--<select class="wide">--}}
                        {{--<option value="Venue Type">Vendor Type</option>--}}
                        {{--<option value="Venue">Venue</option>--}}
                        {{--<option value="Florist">Florist</option>--}}
                        {{--<option value="Cake">Cake</option>--}}
                        {{--<option value="Photographer">Photographer</option>--}}
                        {{--<option value="Catering">Catering</option>--}}
                        {{--<option value="Dress">Dress</option>--}}
                        {{--</select>--}}
                        {{--</div>--}}
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-3">
                            <!-- select -->
                            <select class="wide nice-select" name="category">
                                <option value="">All</option>
                                @foreach($categories as $ct)
                                    <option value="{!! $ct->id !!}" @if($ct->id == Input::get('category') )) {!! 'selected' !!} @endif>{!! $ct->name !!}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-2">
                            <input type="text" name="from" id="contractDate" class="form-control mb-3 weddingdate" value="{!! Input::get('from') !!}" placeholder="From" autocomplete="off">
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-2">
                            <input type="text" name="to" id="regDate" class="form-control mb-3 weddingdate" value="{!! Input::get('to') !!}" placeholder="To"  autocomplete="off">
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-2">
                        <!-- <textarea name="key" id="" class="form-control mb-3">{!! Input::get('key') !!}</textarea> -->
                            <input type="text" name="key" id="" class="form-control mb-3" value="{!! Input::get('key') !!}" placeholder="Tender title/ Tender no.">
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-2">
                            <button class="btn btn-default btn-block" type="submit">Search</button>
                        </div>
                        {!!   Form::close() !!}
                        {{--</form>--}}
                    </div>
                </div><!---col-4-->
                <!-- /.sidebar-section -->

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <!-- tenderTableArea -->

                    <?php $sl = 1; ?>
                    @foreach($categoriess as $cts)
                        @if($cts->id != 8)
                            {{-- @if (count($recent_tenders[$cts->name])> 0) --}}
                            <div class="tenderTableArea">
                                <h4 class="text-center">{!! $cts->name." Tender" !!}</h4>

                                <table id="tenderTable" class="table table-bordered table-striped table-hover dt-responsive tenderTable" style="width:100%;">
                                    <!-- thead -->
                                    <thead>
                                    <tr>
                                        <th scope="col">Sl</th>
                                        <th scope="col">Tender No.</th>
                                        <th scope="col">Tender Title</th>
                                        <th scope="col">QTY</th>
                                        <th scope="col">Opening Date</th>
                                        <th scope="col">Spec</th>
                                        <th scope="col">Notice</th>
                                    </tr>
                                    </thead>
                                    <!-- tbody -->
                                    <tbody>
                                    <!-- tr -->
                                    @if (count($recent_tenders[$cts->name])> 0)
                                        @foreach($recent_tenders[$cts->name] as $rt)

                                            <tr style="@if(date('d.m.Y',strtotime($rt->tender_opening_date)) == date('d.m.Y')) color:red !important; @endif">
                                                <th scope="row" class="@if($rt->tender_type ==2) openBadge @endif">
                                                    {!! $sl++ !!}
                                                </th>
                                                <td class="@if($rt->open_tender==1) newBadge @endif  @if($rt->new_tender) newBadge    @endif">

                                                    {{-- <a href="@if(!empty($rt->specification)) {{url('front-specification-pdf/'.$a.$b.base64_encode($rt->id))}} @else {{'javascript:void(0)'}} @endif" @if(!empty($rt->specification)) target="_blank" @endif class="">Tender No: {!! $rt->tender_number !!}</a> --}}

                                                    {!! $rt->tender_number !!}
                                                </td>
                                                <td>
                                                    <p class="text-uppercase">{!! $rt->tender_title !!}</p>
                                                </td>
                                                <td>
                                                    <p class="text-uppercase">{!! $rt->quantity !!} {!! $rt->deno !!}</p>
                                                </td>
                                                <td class="@if($rt->re_approve) updated @endif">
                                                    <p class="text-uppercase">{!! date('d.m.Y',strtotime($rt->tender_opening_date)) !!}</p>
                                                </td>
                                                <td class="text-center">
                                            <span>
                                                @if(!empty($rt->specification))
                                                    <a href="{{url('front-specification-pdf/'.$a.$b.base64_encode($rt->id))}}" target="_blank" class="pdf_icon"><i class="far fa-file-pdf"></i></a>
                                                @endif
                                                @if(!empty($rt->specification_doc))
                                                    &nbsp;&nbsp;<a href="{{url('front-specification-doc/'.$a.$b.base64_encode($rt->id))}}" target="_blank" class="docx_icon"><i class="fa fa-file-word"></i></a>
                                                @endif
                                            </span>
                                                </td>
                                                <td class="text-center " >
                                            <span>
                                                @if(!empty($rt->notice))
                                                    <a href="{{url('front-notice-pdf/'.$a.$b.base64_encode($rt->id))}}" target="_blank" class="pdf_icon"><i class="far fa-file-pdf"></i></a>
                                                @endif
                                            </span>
                                                </td>
                                            </tr>

                                        @endforeach
                                    @endif

                                    </tbody>
                                </table>
                            </div><!--./tenderTableArea-->
                            {{-- @endif --}}

                            {!! $recent_tenders[$cts->name]->appends(\Input::except('page'))->render() !!}
                            <?php $sl = 1; ?>
                        @endif
                    @endforeach

                </div><!--/.col-8-->


            </div>  <!--./row-->
        </div>
    </div>
    <!-- /.tender-list-content -->

@stop
