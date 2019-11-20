<div class="right col-md-6">
    <div id="tabs">
        <ul>
            <li class="active"><a href="#tabs-1">MOST RATED</a></li>
            <li class="custom-tab"><a href="#tabs-2">POPOLAR</a></li>
            <li class="custom-tab"><a href="#tabs-3">RANDOM</a></li>
        </ul>
        <div id="tabs-1">
            <div class="row">
                @if(isset($favourites))
                @foreach($favourites as $product)
                    <?php
                     $product_cuisines=\App\Cuisine::where('id',$product->cuisine)->select('name')->first();
                     $product_foodUnits=\App\FoodMeasurment::where('id',$product->foodMeasurment)->select('name')->first();
                    ?>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4 img-div">
                            <a href="{!! url('singlepage/'.$product->id) !!}"> <img src="{!! asset($product->image)!!}"></a>
                            <hr>
                        </div>
                        <div class="col-md-8 right-right pt-1">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4> <a href="{!! url('singlepage/'.$product->id) !!}">{!! $product->name !!}</a></h4>
                                    <p class="title">
                                        <span> <i class="fas fa-circle"></i></span>
                                        <span>{!! $product_cuisines->name !!}</span>
                                    </p>
                                    <p class="price">
                                        <span>৳{!! $product->price !!}/{!! $product_foodUnits->name !!}</span>
                                    </p>
                                </div>
                                <div class="col-md-6 button">
                                    <form action="{!! url('addtocart') !!}" method="get">
                                        <input type="hidden" name="product_id" value="{!! $product->id !!}">
                                        <input type="hidden" name="image" value="{!! $product->image !!}">
                                        <input type="hidden" name="name" value="{!! $product->name !!}">
                                        <input type="hidden" name="food_unit" value="{!!  $product_foodUnits->name !!}">
                                        <input type="hidden" name="qty" value="1">
                                        <input type="hidden" name="price" value="{!! $product->price !!}">
                                        <input type="hidden" name="order_type" value="{!! $product->order_type !!}">
                                        @if($product->order_type ==1 ) <button class="btn"> Add to cart </button> @else  <a class="btn btn-orange" id="{!! $product->id !!}" data-toggle="modal" data-target="#preOrder{!! $product->id !!}">Pre-Order </a>@endif
                                    </form>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>

        <div id="tabs-2">
            <div class="row">
                @if(isset($mostOrdered))
                 @foreach($mostOrdered as $product)
                    <?php
                    $most_ordered_cuisines=\App\Cuisine::where('id',$product->cuisine)->select('name')->first();
                    $most_ordered_product_foodUnits=\App\FoodMeasurment::where('id',$product->foodMeasurment)->select('name')->first();
                    ?>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4 img-div">
                                    <a href="{!! url('singlepage/'.$product->id) !!}">  <img src="{!! asset($product->image)!!}"></a>
                                    <hr>
                                </div>
                                <div class="col-md-8 right-right pt-1">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4> <a href="{!! url('singlepage/'.$product->id) !!}"> {!! $product->name !!} </a></h4>
                                            <p class="title">
                                                <span> <i class="fas fa-circle"></i></span>
                                                <span>{!! $product_cuisines->name !!}</span>
                                            </p>
                                            <p class="price">
                                                <span>৳{!! $product->price !!}/{!! $product_foodUnits->name !!}</span>
                                            </p>
                                        </div>
                                        <div class="col-md-6 button">
                                            <form action="{!! url('addtocart') !!}" method="get">
                                                <input type="hidden" name="product_id" value="{!! $product->id !!}">
                                                <input type="hidden" name="image" value="{!! $product->image !!}">
                                                <input type="hidden" name="name" value="{!! $product->name !!}">
                                                <input type="hidden" name="food_unit" value="{!!  $most_ordered_product_foodUnits->name !!}">
                                                <input type="hidden" name="qty" value="1">
                                                <input type="hidden" name="price" value="{!! $product->price !!}">
                                                <input type="hidden" name="order_type" value="{!! $product->order_type !!}">
                                                @if($product->order_type ==1 ) <button class="btn"> Add to cart </button> @else  <a class="btn btn-orange" id="{!! $product->id !!}" data-toggle="modal" data-target="#preOrder{!! $product->id !!}">Pre-Order </a>@endif
                                            </form>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </div>
                     @endforeach
                @endif
            </div>
        </div>
        <div id="tabs-3">
            <div class="row">
                  @if(isset($random))
                      @foreach($random as $rp)
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4 img-div">
                            <a href="{!! url('singlepage/'.$rp['id']) !!}"> <img src="{!! asset($rp['image'])!!}"></a>
                            <hr>
                        </div>

                        <div class="col-md-8 right-right pt-1">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4><a href="{!! url('singlepage/'.$rp['id']) !!}">{!! $rp['name'] !!}</a></h4>
                                    <p class="title">
                                        <span> <i class="fas fa-circle"></i></span>
                                        <span>@foreach($cuisine as $c) @if($c->id == $rp['cuisine']) {!! $c->name !!} @endif @endforeach</span>
                                    </p>
                                    <p class="price">
                                        <span>৳{!! $rp['price'] !!}/@foreach($foodUnit as $unit) @if($unit->id == $rp['foodMeasurment']) {!! $unit->name !!} @endif @endforeach</span>
                                    </p>
                                </div>
                                <div class="col-md-6 button">
                                    <form action="{!! url('addtocart') !!}" method="get">
                                        <input type="hidden" name="product_id" value="{!! $rp['id'] !!}">
                                        <input type="hidden" name="image" value="{!! $rp['image'] !!}">
                                        <input type="hidden" name="name" value="{!! $rp['name'] !!}">
                                        <input type="hidden" name="food_unit" value="@foreach($foodUnit as $unit) @if($unit->id == $rp['foodMeasurment']) {!! $unit->name !!} @endif @endforeach">
                                        <input type="hidden" name="qty" value="1">
                                        <input type="hidden" name="price" value="{!! $rp['price'] !!}">
                                        <input type="hidden" name="order_type" value="{!! $rp['order_type'] !!}">
                                        @if($rp['order_type'] ==1) <button class="btn"> Add to cart </button> @else  <a class="btn btn-orange" id="{!!  $rp['id'] !!}" data-toggle="modal" data-target="#preOrder{!!  $rp['id'] !!}">Pre-Order </a>@endif
                                    </form>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
                    @endforeach
                 @endif
            </div>
        </div>
    </div>
</div>