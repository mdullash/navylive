<!-- General Notice-section -->
<div id="generalNotice" class="pdb0 navySectionBg">
    <div class="container">
        <div class="row space-medium">
            <div class="offset-xl-2 col-xl-8 offset-lg-2 col-lg-8 col-md-12 col-sm-12 col-12">
                <!-- section title start-->
                <div class="section-title text-center">
                    <h2 class="mb10">General Notice</h2>
                    <!--   <p>Most recent importent notice for you. It may help you to make a plan for future activity.</p>-->
                  </div><!-- /.section title-->
            </div>
        </div><!--row-->
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