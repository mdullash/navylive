<!-- hero-section -->
<div class="hero-section position-relative">
    <div class="container">
        <div class="row">
            <div class="offset-xl-1 col-xl-10 offset-lg-1 col-lg-10 col-md-12 col-sm-12 col-12">
                <!-- search-block -->
                <div class="">
                    <div class="text-center search-head">
                        <h1 class="search-head-title">{!! $navallocation->name !!}</h1>
{{--                    <!--  <p class="d-none d-xl-block d-lg-block d-sm-block text-white">Welcome to {!! $navallocation->name !!}, Bangladesh Navy. Here you can find our tenders.</p> -->--}}
                    </div> <!-- /.search-block -->
                    <!-- search-form -->
                    <div class="search-form">
                        {{--<form class="form-row">--}}
                        {{ Form::open(array('role' => 'form', 'url' => $a.$b.'front-tender', 'files'=> true, 'method'=>'get', 'class' => 'form-row')) }}
                            {{--<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 mt-4">--}}
                                {{--<!-- select -->--}}
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
                            
                            <div class="col-xl-4 offset-xl-1 offset-md-1 col-md-4 col-sm-12 col-12 mt-4">
                                <!-- select -->
                                <select class="wide nice-select" name="category">
                                    <option value="">All</option>
                                    @foreach($categories as $ct)
                                        <option value="{!! $ct->id !!}">{!! $ct->name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- textarea -->
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 mt-4">
                                <!-- <textarea name="key" id="" class="form-control"></textarea> -->
                                <input type="text" name="key" class="form-control" placeholder="Tender Name">
                            </div>
                            <!-- button -->
                            <div class="col-xl-2 col-lg-3 col-md-3 col-sm-12 col-12 mt-4">
                                <button class="btn btn-default btn-block" type="submit">Search</button>
                            </div>
                        {!!   Form::close() !!}
                        {{--</form>--}}
                    </div>
                    <!-- /.search-form -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.hero-section -->