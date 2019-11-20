@extends('frontend.layouts.master')
@section('content')

    @include('layouts.flash')

    <!-- page-header -->
    <div id="inner-page-header" class="page-header position-relative">
        <div class="container">
            <div class="row">
                <!-- page caption -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                    <div class="page-caption">
                        <h1 class="page-title">Tender Participant Status</h1>
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
                            <li class="breadcrumb-item"><a href="{!! URL::to($a.$b.'login') !!}" class="breadcrumb-link">Supplier Login</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Tender Participant Status</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- page breadcrumb -->
    </div>
    <!-- /.page-header -->

    <!-- couple-sign in -->
    <section class="couple-bg-image pb-5 sectionBg">
        <div class="couple-form">
            <div class="container-fluid">
                <div class="row ">

                    @if (Auth::guard('supplier')->check())
                        <div class="col-lg-3 col-md-3 col-3">
                            @include('frontend/homeinc/menu')
                        </div>
                    @endif

                    <div class="col-lg-9 col-md-9 col-sm-12 col-9">
                        <!--st-tab-->
                        <div class="st-tab">
                            <div class="container">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">



                                        <div class="">
                                            <!-- form-heading-title -->

                                            @if (Auth::guard('supplier')->check())
                                              
                                                <table class="table table-bordered table-hover" style="background-color: #fff;">
                                                      <thead>
                                                        <tr>
                                                            <th style="vertical-align: middle;">Ser</th>
                                                            <th style="vertical-align: middle;">Tender Title</th>
                                                            <th style="vertical-align: middle;">Tender Number</th>
                                                            <th style="vertical-align: middle;">Publication Date</th>
                                                            <th style="vertical-align: middle;">Participation Date</th>
                                                            <th style="vertical-align: middle;">Position Hold (Primary)</th>
                                                            <th style="vertical-align: middle;">PO Winner</th>
                                                            <th style="vertical-align: middle;">Billing Status</th>
                                                        </tr>
                                                      </thead>
                                                      <tbody>
                                                        @if(!empty($SupplierTenderAttends))
                                                            <?php 
                                                                 $i = ($SupplierTenderAttends->currentpage()-1)* $SupplierTenderAttends->perpage() + 1;
                                                            ?>
                                                            @foreach($SupplierTenderAttends as $SupplierTenderAttend)
                                                                  <tr>
                                                                      <td>{{ $i }}</td>
                                                                      <td>
                                                                           {{ 
                                                                            !empty($SupplierTenderAttend->tender_title) ?
                                                                            $SupplierTenderAttend->tender_title : ""
                                                                           }}
                                                                        </td>
                                                                      <td>
                                                                          {{ 
                                                                            !empty($SupplierTenderAttend->tender_number) ?
                                                                            $SupplierTenderAttend->tender_number : ""
                                                                           }}
                                                                      </td>
                                                                      <td>
                                                                          {{ 
                                                                            !empty($SupplierTenderAttend->publish_date) ?
                                                                            date("d F, Y",strtotime($SupplierTenderAttend->publish_date)) : ""
                                                                           }}
                                                                      </td>
                                                                      <td>
                                                                          {{ 
                                                                            !empty($SupplierTenderAttend->participation_date) ?
                                                                            date("d F, Y",strtotime($SupplierTenderAttend->participation_date)) : ""
                                                                           }}
                                                                      </td>
                                                                      <td>
                                                                          <?php
                                                                            $tenders = \DB::table('demand_to_collection_quotation')
                                                                                ->select('supplier_name','suppliernametext','total')
                                                                                ->where('tender_id','=',$SupplierTenderAttend->id)
                                                                                ->orderBy('total')->get()->toArray();

                                                                                //dd($tenders);

                                                                                $positionHold = array_search($supplierId, array_column($tenders, 'supplier_name'));
                                                                                //dd($positionHold);
                                                                          ?>
                                                                          {!! ($positionHold != 0) ? \functions\OwnLibrary::numToOrdinalWord($positionHold) : \functions\OwnLibrary::numToOrdinalWord(1) !!}
                                                                      </td>
                                                                      <td>
                                                                          <?php
                                                                            $tenderWinner = \DB::table('demand_to_collection_quotation')
                                                                                ->select('supplier_name','suppliernametext','total','id','tender_id')
                                                                                ->where('tender_id','=',$SupplierTenderAttend->id)
                                                                                ->where('winner','=',1)->first();
                                                                          ?>
                                                                          @if(!empty($tenderWinner))

                                                                          @if($supplierId == $tenderWinner->supplier_name)

                                                                          <?php
                                                                            $poInfo = \App\PoDatas::where("selected_supplier",'=',$tenderWinner->id)->first();
                                                                          ?>
                                                                          <a style="color: #319daf;" href="{{ url('/supplier-po-print/'.$poInfo->id.'&'.$tenderWinner->tender_id) }}" target="_blank">
                                                                          {{ $tenderWinner->suppliernametext }}
                                                                          </a>


                                                                          @else
                                                                            {{ 
                                                                              $tenderWinner->suppliernametext
                                                                               }}
                                                                          @endif
                                                                          @endif
                                                                      </td>
                                                                      <td></td>
                                                                  </tr>
                                                                  <?php $i++ ?>
                                                              @endforeach
                                                        @endif
                                                      </tbody>
                                                    </table>

                                                    {{ $SupplierTenderAttends->links() }}

                                               @endif
                                                </div><!--row-->

                                            <!--/.form -->
                                        </div><!--/.loginArea-->

                                </div>
                            </div>
                        </div><!--/.st-tab-->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.couple-sign up -->
    </section>
@stop