        <style>    
        .general_notice_img{
            background: url({{ asset('/public/frontend/index-image/general_img_frame.png') }}) no-repeat;
            background-size: cover;
            background-position: center;
            height: 292px;
            position: relative;
        }
        .general_notice_img img {
            height: 228px;
            margin: auto;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }
        @media screen and (max-width:991px) {
            .general_notice_img {
                background-size: contain;
            }
            
            .general_notice_area .section-title {
                margin-top: 30px;
            }  

        }
        @media screen and (max-width:380px){
            .general_notice_img img {
                height: 209px;
            }
        }
    </style>
    
    <!-- General Notice-section -->
    <div id="generalNotice" class="navySectionBg space-medium content" style="background: url({{ asset('/public/frontend/index-image/neavyimagedesign1.jpg') }}) no-repeat; background-size: cover; background-position: center;">
        <div class="container">
            <div class="general_notice_area">
                <div class="row">
                    <div class="col-lg-4 pr-lg-0">
                        <div class="general_notice_img">
                            <img src="{{ asset($setting->logo) }}" alt="" class="img-responsive">
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="row ">
                            <div class="offset-xl-2 col-xl-8 offset-lg-2 col-lg-8 col-md-12 col-sm-12 col-12">
                                <!-- section title start-->
                                <div class="section-title text-center">
                                    <h2 class="mb10">General Notice</h2>
                                    <!--   <p>Most recent importent notice for you. It may help you to make a plan for future activity.</p>-->
                                   </div><!-- /.section title-->
                            </div>
                        </div>
                        <!--/.row-->
                        <!-- generalNotice start -->
                        <div class="generalNoticeContent">
                            <div class="container">
                                <div class="genNoticeContent">
                                    <!-- noticeTableArea -->
                                    <div class="noticeTableArea">
                                        <table id="tenderTable"
                                            class="tenderTable table table-bordered table-striped table-hover dt-responsive"
                                            style="width:100%;">
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
                                    </div>
                                    <!--./noticeTableArea-->
                                </div>
                                <!--genNoticeContent-->
                            </div>
                        </div><!-- /.generalNotice end -->
                    </div>
                </div>
            </div>

            
            
        </div>
    </div><!-- /. General Notice-section -->