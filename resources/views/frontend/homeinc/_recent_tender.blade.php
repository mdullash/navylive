<!-- Recent Tender-section -->
<div id="recent_tender" class="space-medium pdb0 navySectionBg">
    <div class="container">
        <div class="row">
            <div class="offset-xl-2 col-xl-8 offset-lg-2 col-lg-8 col-md-12 col-sm-12 col-12">
                <!-- section title start-->
                <div class="section-title text-center">
                    <h2 class="mb10">Recent Tender</h2>
                    <!--   <p>Most recent published tenders that may help you to perticipate.</p>-->
                   </div><!-- /.section title-->
            </div>
        </div><!--row-->
        <!-- recent_tenderContent start -->
        <div class="recent_tenderContent content">
            <div class="container">

                <div class="tenderTableArea">

                    <?php $sl = 1; ?>


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

                        @foreach($recent_tenders as $rt)

                            <tr>
                                <th scope="row" class="@if($rt->tender_type ==2) openBadge @endif">{!! $sl++ !!}</th>
                                <td class="@if($rt->open_tender==1) newBadge @endif  @if($rt->new_tender) newBadge    @endif ">
                                    <p>
                                        <a href="@if(!empty($rt->specification)) {{url('front-specification-pdf/'.$a.$b.base64_encode($rt->id))}} @else {{'javascript:void(0)'}} @endif" @if(!empty($rt->specification)) target="_blank" @endif class="title">{!! $rt->tender_number !!}</a>
                                    </p>
                                </td>
                                <td>
                                    <p class="text-uppercase">{!! $rt->tender_title !!}</p>
                                </td>
                                <td>
                                    <p class="text-uppercase">{!! $rt->quantity !!} {!! $rt->deno !!}</p>
                                </td>
                                <td>
                                    <p class="text-uppercase" class="@if($rt->re_approve) updated @endif">{!! date('d.m.Y',strtotime($rt->tender_opening_date)) !!}</p>
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
                                <td class="text-center">
                                        <span>
                                            @if(!empty($rt->notice))
                                                <a href="{{url('front-notice-pdf/'.$a.$b.base64_encode($rt->id))}}" target="_blank" class="pdf_icon"><i class="far fa-file-pdf"></i></a>
                                            @endif
                                        </span>
                                </td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>
                </div><!--./tenderTableArea-->

            </div>
        </div><!-- /.recent_tender end -->
    </div>
</div>
<!-- /.Recent Tender-section -->
