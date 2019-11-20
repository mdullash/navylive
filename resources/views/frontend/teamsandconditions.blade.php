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
                        <h1 class="page-title">Terms and Conditions</h1>
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
                            <li class="breadcrumb-item active text-white" aria-current="page">Terms and Conditions</li>
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
                        <li class="list-inline-item position-relative stepBorderRight active">
                            <h3 class="mb-0">Step 1</h3>
                        </li>
                        <li class="list-inline-item position-relative stepBorderRight">
                            <h3 class="mb-0">Step 2</h3>
                        </li>
                        <li class="list-inline-item position-relative stepBorderRight">
                            <h3 class="mb-0">Step 3</h3>
                        </li>
                        <li class="list-inline-item position-relative stepBorderRight">
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
            <div class="row">
                
            </div><!--row-->
            <!-- generalNotice start -->
            <div class="termsConditionContent">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <div class="termsTxt">
                                <p> {!! $termsconditions->descriptions !!}</p>
                            </div>  
                        </div>
                    </div>
                    <!-- terms_btns -->
                    <div class="terms_btns mt40">
                        {{ Form::open(array('role' => 'form', 'url' => $a.$b.'front-agree-terms-conditions', 'files'=> true, 'class' => 'form-horizontal registration1', 'id'=>'registration1')) }}
                            <div class="row">
                                <!-- defaulterArea -->
                                <div class="col-lg-7 offset-lg-2 col-md-7 offset-md-2 col-sm-6 offset-sm-0 col-6">
                                    <div class="termsAgree d-flex">
                                        <div class="form-group">
                                            <label class="radio_container col-md-12">I have read and agree to the Terms and Conditions and Privacy Policy
                                                <input type="checkbox"  name="agree" required="">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div><!--form-group-->
                                    </div>
                                </div>
                                <div class="col-lg-2  col-md-2 col-sm-6  col-6 text-right">
                                    <button type="submit" name="" class="btn btn-primary btn-sm">Agree</button>
                                </div>
                            </div>
                        {!!   Form::close() !!}
                    </div> <!--/.terms_btns-->
                </div>
            </div><!-- /.termsConditionContent end -->
        </div>
    </div><!-- /. General Notice-section -->

@stop