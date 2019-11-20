@extends('frontend.layouts.master')
@section('content')
    
    <!-- page-header -->
     <div id="inner-page-header" class="page-header position-relative">
        <div class="container">
            <div class="row">
                <!-- page caption -->
                <div class="col-12 ">
                    <div class="page-caption">
                        <h1 class="page-title">Contact Us</h1>
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
                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Home</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Contact Us</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- page breadcrumb -->
    </div>
    <!-- /.page-header -->

   <!-- Contact Us -->
   <section class="contactUs py-5 sectionBg">
        <div class="container">
            <div class="contactArea">
                <div class="row">

                    @if(!$contacts->isEmpty())
                        @foreach($contacts as $ct)
                            <!-- contact-block -->
                            <div class="col-12">
                                <div class="contact-block">
                                    <div class="contact-content">
                                        {{--<h3>Officer in Charge</h3>--}}
                                        <p>{!! $ct->descriptions !!}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /.contact-block -->
                        @endforeach
                    @endif

                </div>
            </div><!--contactArea-->
        </div>
    </section><!-- /.Contact Us -->

@stop