@extends('layouts.default')
@section('content')
    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <h2 class="font-light m-b-xs">
                    <h3>Category & Item Wise Supplier</h3>
                </h2>
            </div>
            @include('layouts.flash')
        </div>
    </div>
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading hbuilt">
                        <h3>Category & Item Wise Supplier</h3>
                    </div>
                        <div class="panel-body">
                            
                            <div class="row">

                            {{ Form::open(array('role' => 'form', 'url' => 'cat-pro-supplier-list', 'files'=> true, 'method'=>'get', 'class' => 'form-horizontal', 'id'=>'cat-pro-supplier-list')) }}

                             <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">Category:</label>
                                        <select class="form-control selectpicker" name="cat_id" id="cat_id"  data-live-search="true">
                                            <option value="">{!! 'All' !!}</option>
                                            @foreach($suppliercategories as $sc)
                                                <option value="{!! $sc->id !!}" @if($sc->id==$cat_id) {{'selected'}} @endif>{!! $sc->name !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">Item:</label>
                                        {{--<select class="form-control selectpicker" name="item_id" id="item_id" value="{!! old('item_id') !!}" data-live-search="true">--}}
                                            {{--<option value="">{!! 'All' !!}</option>--}}
                                            {{--@foreach($productsnames as $imn)--}}
                                                {{--<option value="{!! $imn->id !!}" @if($imn->id==$item_id) {{'selected'}} @endif>{!! $imn->item_name !!}</option>--}}
                                            {{--@endforeach--}}
                                        {{--</select>--}}
                                        <?php $searchItemName = !empty($searchItemName) ? $searchItemName->item_name : ''; ?>
                                        {!!  Form::text('search_item_name', $searchItemName, array('id'=> 'search_item_name', 'class' => 'form-control search_item_name', 'autocomplete'=> 'off', 'placeholder'=>'Search item name ...')) !!}
                                        <input type="hidden" id="item_id" name="item_id" value="{!! $item_id !!}">
                                        <div class="form-group col-xs-12 col-sm-12 col-md-6 search_item_name_div" id="search_item_name_div" style="display: none; display: block; position: absolute; left: 16px;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="email">Organization:</label>
                                        <select class="form-control selectpicker" name="nsd_id" id="nsd_id"  data-live-search="true">
                                            <option value="">{!! 'All' !!}</option>
                                            @foreach($nsdNames as $nn)
                                                <option value="{!! $nn->id !!}" @if($nn->id==$nsd_id) {{'selected'}} @endif>{!! $nn->name !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="col-md-12" style="padding-top: 22px;">
                                        <label for="email"></label>
                                        <button type="submit" class="btn btn-primary">{!! 'Search' !!}</button>
                                    </div>
                                </div>
                            </div>

                            {!!   Form::close() !!}    

                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{url('cat-pro-supplier-list-excel-download?cat_id='.$cat_id.'&search_item_name='.$searchItemName.'&item_id='.$item_id.'&nsd_id='.$nsd_id.'&action=pdf')}}" class="btn btn-primary btn-xs" target="_blank"><i class="fa fa-print"></i> {{'Print'}}</a>
                                    <a href="{{url('cat-pro-supplier-list-excel-download?cat_id='.$cat_id.'&search_item_name='.$searchItemName.'&item_id='.$item_id.'&nsd_id='.$nsd_id.'&action=excel')}}" class="btn btn-success btn-xs"><i class="fa fa-download"></i> {{'Export Excel'}}</a><br>
                                </div>
                            </div>

                            <div class="row">
                                <div class="text-center">
                                    <b>
                                        {{-- STATISTIC OF SUPPLIERS PO 
                                        @if(empty($nsd_id)) {{'(All NSD) '}}
                                        @else 
                                        {{'('.$search_nsd_name->name.')'}}
                                        @endif

                                        @if(empty($sup_id)) {{'(All Suppliers) '}}
                                        @else 
                                        {{'('.$search_supplier_name->company_name.')'}}
                                        @endif

                                        @if(!empty($from)) {!! ' '. date('d M y',strtotime($from)) !!} 
                                        @else
                                        {{'Beginning'}}
                                        @endif  

                                        @if(!empty($to)) TO {!! date('d M y',strtotime($to)) !!} 
                                        @else TO {!! ' '. date('d M y') !!} 
                                        @endif --}}
                                    </b>
                                </div>
                            </div><br>

                            <table class="table table-bordered table-hover table-striped middle-align">
                                <thead>
                                <tr class="center">
                                    <th class="text-center" width="5%">SL#</th>
                                    <th class="text-center">{{'Company Name'}}</th>
                                    <th class="text-center" width="">{{'Company Registration No.'}}</th>
                                    <th class="text-center" width="">{{'Mobile Number'}}</th>
                                    <th class="text-center" width="">{{'Supply Category'}}</th>
                                    <th class="text-center" width="">{{'Organization'}}</th>
                                    <th class="text-center" width="">{{'TIN Number'}}</th>
                                    
                                    <th class="text-center"> {!! 'View' !!}
                                    </th>
                                    
                                </tr>
                                </thead>
                                <tbody>

                                    @if (!$suppliers->isEmpty())

                                    <?php
                                        $page = \Input::get('page');
                                        $page = empty($page) ? 1 : $page;
                                        $sl = ($page-1)*50;
                                        $l = 1;

                                        function supply_cat_name($cat_id=null){
                                            $calName = \App\SupplyCategory::where('id','=',$cat_id)->value('name');
                                            return $calName;
                                        }

                                        function supply_nsd_name($nsd_id=null){
                                            $calName = \App\NsdName::where('id','=',$nsd_id)->value('name');
                                            return $calName;
                                        }
                                    ?>
                                    @foreach($suppliers as $sc)
                                        <tr> 
                                            <td>{{++$sl}}</td>
                                            <td>{{$sc->company_name}}</td>
                                            <td>{{$sc->company_regi_number_nsd}}</td>
                                            <td>{{$sc->mobile_number}}</td>
                                            {{-- <td>{{$sc->supplyCategoryName->name}}</td> --}}
                                            <td>
                                                <?php 
                                                    $catids = explode(',',$sc->supply_cat_id);
                                                    foreach ($catids as $ctds) {
                                                        $valsss = supply_cat_name($ctds);
                                                        echo "<li>".$valsss."</li>";
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $nsdids = explode(',',$sc->registered_nsd_id);
                                                    foreach ($nsdids as $nsd) {
                                                        $valssss = supply_nsd_name($nsd);
                                                        echo "<li>".$valssss."</li>";
                                                    }
                                                ?>
                                            </td>
                                            <td>{{$sc->tin_number}}</td>
                                            
                                            <td class="action-center">
                                                <div class="text-center">
                                                    <a class="btn btn-primary btn-xs" href="{{ URL::to('/suppliers/view/'. $sc->id) }}" title="view" target="_blank">
                                                        <i class="icon-eye-open"></i>
                                                    </a>
                                                    

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                
                                @else
                                    <tr>
                                        <td colspan="8">{{'Empty Data'}}</td>
                                    </tr>
                                @endif
                                    
                                </tbody>
                            </table><!---/table-responsive-->
                           
                            {!! $suppliers->appends(\Input::except('page'))->render() !!}
                        </div>
                    </div>
                </div>
            </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function(){

            $(document).on('change','#cat_id',function(){
                var cat_id = this.value;
                var csrf     = "<?php echo csrf_token(); ?>";

                $.ajax({
                    type: 'post',
                    url: 'category-wise-items',
                    data: { _token: csrf, cat_id:cat_id},
                    //dataType: 'json',
                    success: function( _response ){
                        //alert(JSON.stringify(_response));
                        if(_response!==''){
                            $("#item_id").empty();
                            $('#item_id').selectpicker('refresh');
                            $("#item_id").append(_response['catwiseitems']);
                            $('#item_id').selectpicker('refresh');
                        }

                    },
                    error: function(_response){
                        alert("error");
                    }

                });/*End Ajax*/

            });


            // Item Search=============================
            $('.search_item_name').keyup(function() {
                var query = $(this).val();

                if(query == ''){ $('#item_id').val(''); $('.search_item_name_div').fadeOut();}

                if (query != '') {
                    var _token     = "<?php echo csrf_token(); ?>";
                    $.ajax({
                        url: "category-item-wise-teport-item-live-search",
                        method: "POST",
                        data: {query: query, _token: _token},
                        success: function (data) {
                            $('.search_item_name_div').fadeIn();
                            $('.search_item_name_div').html(data);
                        }
                    });
                }
            });

            $(document).on('click', '.searchItemName', function () {
                $('.search_item_name_div').fadeOut();
                $('#search_item_name').val('');
                $('#item_id').val('');
                $('#search_item_name').val($(this).text());
                $('#item_id').val($(this).attr("value"));

            });
            // End item search =============================================
            // 

        });

    </script>
@stop