@extends('frontend.layouts.master')
@section('content')
    <!-- page-header -->
    <div id="inner-page-header" class="page-header position-relative">
        <div class="container">
            <div class="row">
                
            </div>
        </div>
        <!-- page caption -->
        <div class="page-breadcrumb position-relative">
            <div class="container">
                <div class="row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{!! URL::to('/').'/'.$a.$b !!}" class="breadcrumb-link">Home</a></li>
                            
                        </ol>
                    </nav>
                </div>
            </div>
        </div><!-- page breadcrumb -->
    </div>
    <!-- /.page-header -->

    <!--Step progress-->
<section id="stepProgress" class="mt40 mb40">
    <div class="container">
        <!-- stepProgress -->
        <div class="stepProgress">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <!-- stepProgressContent -->
                    <ul class="d-flex flex-wrap justify-content-between stepProgressContent">
                        <li class="list-inline-item position-relative stepBorderRight ">
                            <h3 class="mb-0">Step 1</h3>
                        </li>
                        <li class="list-inline-item position-relative stepBorderRight">
                            <h3 class="mb-0">Step 2</h3>
                        </li>
                        <li class="list-inline-item position-relative stepBorderRight">
                            <h3 class="mb-0">Step 3</h3>
                        </li>
                        <li class="list-inline-item position-relative stepBorderRight active">
                            <h3 class="mb-0">Step 4</h3>
                        </li>
                    </ul>
                </div><!--./row-->
            </div>
        </div><!--./stepProgress-->
    </div>
</section>
<!--./Step progress-->

    <!-- General Notice-section -->
    <div id="termsCondition" class="pb-4 sectionBg space-medium">
        <div class="container">
            <!-- generalNotice start -->
            <div class="termsConditionContent">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="termsTxt">
                                @include('layouts.flash')
                            </div>  
                        </div>
                    </div>
                    
                </div>
            </div><!-- /.termsConditionContent end -->
        </div>
    </div><!-- /. General Notice-section -->

@stop