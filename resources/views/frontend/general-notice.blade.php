@extends('frontend.layouts.master')
@section('content')

    <!-- page-header -->
    <div id="inner-page-header" class="page-header position-relative">
    <div class="container">
        <div class="row">
            <!-- page caption -->
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                <div class="page-caption">
                    <h1 class="page-title">General Notice</h1>
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
                        <li class="breadcrumb-item active text-white" aria-current="page">General Notice</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- page breadcrumb -->
</div>
<!-- /.page-header -->
    
     <!-- General Notice-section -->
     <div id="generalNotice" class="pdb0 sectionBg">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb40">
                    <hr>
                </div>
            </div>
            
            <!-- generalNotice start -->
            <div class="generalNoticeContent content">
                <div class="container">
                    <div class="genNoticeContent">

                    <!-- noticeTableArea -->
                       <div class="noticeTableArea">
                            <table id="tenderTable" class="tenderTable table table-bordered table-striped table-hover dt-responsive" style="width:100%;">
                                <!-- thead -->
                                <thead>
                                    <tr>
                                        <th scope="col">Sl</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Short Description</th>
                                        <th scope="col">Notice</th>
                                    </tr>
                                </thead>
                                <!-- tbody -->
                                <tbody>
                                    <!-- tr -->
                                    <?php $sl = 1; ?>
                                    @if(!empty($notices))
                                        
                                        @foreach($notices as $nt)
                                        <tr>
                                            <th scope="row">{!! $sl++ !!}</th>
                                            <td>
                                                <p class="text-uppercase">{!! $nt->title !!}</p>
                                            </td>
                                            <td>
                                                <p class="text-uppercase">{!! $nt->description !!}</p>
                                            </td>
                                            <td class="text-center">
                                                <span>
                                                    @if(!empty($nt->upload_file))
                                                        <a href="{{url('front-notice-brd-pdf/'.base64_encode($nt->id))}}" target="_blank" class="pdf_icon"><i class="far fa-file-pdf"></i></a>
                                                    @endif
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                        
                                    @endif
                                </tbody>
                            </table>
                        </div><!--./noticeTableArea-->


                    </div><!--genNoticeContent-->
                </div>
            </div><!-- /.recent_tender end -->
        </div>
    </div><!-- /. General Notice-section -->

@stop