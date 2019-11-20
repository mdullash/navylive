<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EventManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use View;
use Input;
use Illuminate\Support\Facades\Redirect;
use functions\OwnLibrary;
use App\SupplyCategory;
use App\NsdName;
use App\Supplier;
use App\Item;
use App\Deno;
use PDF;

class ItemsController extends Controller
{

    private $moduleId = 14;

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nsd_id = Input::get('nsd_id');
        $b_code = Input::get('budget_code');
        $key    = Input::get('key');
        $ct_name    = Input::get('ct_name');

        $nsdNames = NsdName::where('status_id','=',1)->get();
        $zonesRltdIds = array();
        foreach($nsdNames as $nn){
            if(empty(Session::get('nsdBsdEmptyOrNot'))){

                if(in_array(Session::get('zoneId'), explode(',', $nn->zones))){
                    $zonesRltdIds[] = $nn->id;
                }

            }else{
                if(in_array(Session::get('zoneId'), explode(',',$nn->zones))){
                    foreach(explode(',',Session::get('nsdBsdEmptyOrNot')) as $nbeon){
                        if($nn->id == $nbeon){
                            $zonesRltdIds[] = $nn->id;
                        }
                    }
                }
            }// esle end
        }
        $nsdNames = NsdName::whereIn('id',$zonesRltdIds)->where('status_id','=',1)->get();
        $budget_codes =  \App\BudgetCode::where('status_id','=',1)->get();

        $supplyCategories = SupplyCategory::where('status_id','=',1)->get();
        foreach($supplyCategories as $ctg){
            if(in_array(Session::get('zoneId'), explode(',', $ctg->zones))){
                $zonesRltdCtgIds[] = $ctg->id;
            }
        }
        //$suppliercategories = SupplyCategory::whereIn('id',$zonesRltdCtgIds)->where('status_id','=',1)->get();
        
        $suppliercategories = SupplyCategory::orderBy('name')->whereIn('id',$zonesRltdCtgIds);
            if(!empty(Auth::user()->categories_id)){
                $userWiseCat = explode(',', Auth::user()->categories_id);
                $suppliercategories->whereIn('id',$userWiseCat);
            }
            $suppliercategories->where('status_id','=',1);
        $suppliercategories = $suppliercategories->get();

        $nsds =  '';
        
        $allItemIdsUnderNsd = '';
        $allItemIdsUnderNsdArray = array();
        $nsdids = Item::select('id','nsd_id','item_cat_id')->where('status_id','=',1)->get();

            foreach ($nsdids as $ni) {
                foreach(explode(',',$ni->nsd_id) as $rni){
                    if(!empty($nsd_id)){
                        if(in_array($nsd_id, explode(',',$ni->nsd_id))){

                            if(!empty(Auth::user()->categories_id)){
                                $userWiseCat = explode(',', Auth::user()->categories_id);
                                if(in_array($ni->item_cat_id, $userWiseCat)){
                                    $allItemIdsUnderNsdArray[] = $ni->id;
                                }
                            }else{
                                $allItemIdsUnderNsdArray[] = $ni->id;
                            }
                        }
                    }else {
                        if (in_array($rni, $zonesRltdIds)) {

                            if (!empty(Auth::user()->categories_id)) {
                                $userWiseCat = explode(',', Auth::user()->categories_id);
                                    if (in_array($ni->item_cat_id, $userWiseCat)) {
                                        $allItemIdsUnderNsdArray[] = $ni->id;
                                    }
                            } else {
                                $allItemIdsUnderNsdArray[] = $ni->id;
                            }

                        }
                    }
                }

            }// End foreach

            $allItemIdsUnderNsd = array_unique($allItemIdsUnderNsdArray);

            $items = Item::whereNotNull('id');
            if(!empty($allItemIdsUnderNsd) || !empty(Auth::user()->categories_id)){
                $items->whereIn('id',$allItemIdsUnderNsd);
            }
            if( !empty($nsd_id)){ 
                $items->whereIn('id',$allItemIdsUnderNsd);
            }
            if(!empty($b_code)){
                $items->where('budget_code','=',$b_code);
            }
            if(!empty($ct_name)){
                $items->where('item_cat_id','=',$ct_name);
            }
            if(!empty($key)){
                $items->where(function($query) use ($key){
                    $query->where('imc_number', 'like', "%{$key}%");
                    $query->orWhere('item_name', 'like', "%{$key}%");
                });
            }
            $items = $items->paginate(10);
       
       $default_currency = \App\Currency::where('default_currency','=',1)->first();

        $nsdId = 1;
             if(!empty(Auth::user()->nsd_bsd)){
                $nsdId = Auth::user()->nsd_bsd;
             }
            $orgInfo  = \App\NsdName::find($nsdId);

       return View::make('items.index')->with(compact('items','nsdNames','nsd_id','budget_codes','b_code','key','suppliercategories','ct_name','default_currency','orgInfo'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $zonesRltdIds = array();
        $zonesRltdCtgIds = array();

        $nsdNames = NsdName::where('status_id','=',1)->get();
        foreach($nsdNames as $nn){
            if(empty(Session::get('nsdBsdEmptyOrNot'))){

                if(in_array(Session::get('zoneId'), explode(',', $nn->zones))){
                    $zonesRltdIds[] = $nn->id;
                }

            }else{
                if(in_array(Session::get('zoneId'), explode(',',$nn->zones))){
                    foreach(explode(',',Session::get('nsdBsdEmptyOrNot')) as $nbeon){
                        if($nn->id == $nbeon){
                            $zonesRltdIds[] = $nn->id;
                        }
                    }
                }
            }// esle end
        }
        $nsdNames = NsdName::whereIn('id',$zonesRltdIds)->where('status_id','=',1)->get();

        $supplyCategories = SupplyCategory::orderBy('name','ASC')->where('status_id','=',1)->get();
        foreach($supplyCategories as $ctg){
            if(in_array(Session::get('zoneId'), explode(',', $ctg->zones))){
                $zonesRltdCtgIds[] = $ctg->id;
            }
        }
        //$supplyCategories = SupplyCategory::whereIn('id',$zonesRltdCtgIds)->where('status_id','=',1)->get();
        
        $supplyCategories = SupplyCategory::orderBy('name','ASC')->whereIn('id',$zonesRltdCtgIds);
            if(!empty(Auth::user()->categories_id)){
                $userWiseCat = explode(',', Auth::user()->categories_id);
                $supplyCategories->whereIn('id',$userWiseCat);
            }
            $supplyCategories->where('status_id','=',1);
        $supplyCategories = $supplyCategories->get();

        $denos = Deno::where('status_id','=',1)->get();
        $budget_codes = \App\BudgetCode::where('status_id','=',1)->get();

        // Get the currency list
        $currencies_names = \App\Currency::select('id','currency_name','conversion')->where('status','=',1)->get();
        $default_currency = \App\Currency::where('default_currency','=',1)->first();

        $nsdId = 1;
             if(!empty(Auth::user()->nsd_bsd)){
                $nsdId = Auth::user()->nsd_bsd;
             }
            $orgInfo  = \App\NsdName::find($nsdId);

        return View::make('items.create')->with(compact('supplyCategories','nsdNames','denos','budget_codes','currencies_names','default_currency','orgInfo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        OwnLibrary::validateAccess($this->moduleId,2);

        $this->middleware('csrf', array('on' => 'post'));

        $v = \Validator::make($request->all(), [
	        'item_name' => 'required',
	        'item_cat_id' => 'required',
	        'nsd_id' => 'required',
	        'item_deno' => 'required',
	        'item_type' => 'required',
//            'imc_number' => 'required',

//            'model_number' => 'required',

//            'unit_price' => 'required',
//            'currency_name' => 'required',
//            'conversion' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('item/create')->withErrors($v->errors())->withInput();
        }else {

                $checkItem = Item::where('imc_number','=',empty($request->imc_number) ? null : $request->imc_number)
                              ->where('item_name','=',empty($request->item_name) ? null : $request->item_name)
                              ->where('model_number','=',empty($request->model_number) ? null : $request->model_number)
                              ->where('brand','=',empty($request->brand) ? null : $request->brand)
                              ->where('part_number','=',empty($request->part_number) ? null : $request->part_number)
                              ->where('patt_number','=',empty($request->patt_number) ? null : $request->patt_number)->get();

                if (count($checkItem) >= 1){
                    session()->flash('error','Item already exist');
                    return redirect('item/create')->withInput();
                }
                   
                $item = new Item();

                $item->imc_number = $request->imc_number;
                $item->item_name = empty($request->item_name) ? null : $request->item_name;
                $item->manufacturer_name = empty($request->manufacturer_name) ? null : $request->manufacturer_name;
	            $item->manufacturing_country = empty($request->manufacturing_country) ? null : $request->manufacturing_country;
	            $item->country_of_origin = empty($request->country_of_origin) ? null : $request->country_of_origin;
                $item->brand = empty($request->brand) ? null : $request->brand;
                $item->model_number = empty($request->model_number) ? null : $request->model_number;
                $item->part_number = empty($request->part_number) ? null : $request->part_number;
                $item->patt_number = empty($request->patt_number) ? null : $request->patt_number;
                $item->item_cat_id = empty($request->item_cat_id) ? null : $request->item_cat_id;
                $item->nsd_id = empty($request->nsd_id) ? null : implode(',',$request->nsd_id);
                $item->addl_item_info = empty($request->addl_item_info) ? null : $request->addl_item_info;
	            $item->item_deno = empty($request->item_deno) ? null : $request->item_deno;
	            $item->item_type = empty($request->item_type) ? null : $request->item_type;
	            $item->item_type_r = empty($request->item_type_r) ? null : $request->item_type_r;
	            $item->main_equipment_name = empty($request->main_equipment_name) ? null : $request->main_equipment_name;
	            $item->main_equipment_brand = empty($request->main_equipment_brand) ? null : $request->main_equipment_brand;
	            $item->main_equipment_model = empty($request->main_equipment_model) ? null : $request->main_equipment_model;
	            $item->main_equipment_additional_info = empty($request->main_equipment_additional_info) ? null : $request->main_equipment_additional_info;
	            $item->substitute_item = empty($request->substitute_item) ? null : $request->substitute_item;
	            $item->shelf_life = empty($request->shelf_life) ? null : $request->shelf_life;

                if(\Session::get("zoneAlise") == "bsd"){
                    $item->strength = empty($request->strength) ? 1 : $request->strength;
                }

                $item->currency_name = empty($request->currency_name) ? null : $request->currency_name;
                $item->conversion = empty($request->conversion) ? null : $request->conversion;
                $item->unit_price = empty($request->unit_price) ? null : $request->unit_price;
                $item->unit_price_in_bdt = empty($request->unit_price) ? null : $request->unit_price*$request->conversion;
                $item->source_of_supply = empty($request->source_of_supply) ? null : $request->source_of_supply;
                $item->discounted_price = empty($request->discounted_price) ? null : $request->discounted_price;
                $item->discounted_price_in_bdt = empty($request->discounted_price) ? null : $request->discounted_price*$request->conversion;
                $item->budget_code = empty($request->budget_code) ? null : $request->budget_code;
                $item->status_id = $request->status;

		        if (Input::hasFile('item_picture')) {
			        $image = Input::file('item_picture');

			        $image_name = str_random(20);
			        $ext = strtolower($image->getClientOriginalExtension());
			        $image_full_name = $image_name . '.' . $ext;
			        $upload_path = 'public/upload/item_picture/';
			        $image_url = $upload_path . $image_full_name;
			        $image->move($upload_path, $image_full_name);
			        $item->item_picture = $image_url;
		        }

                if (Input::hasFile('item_specification')) {
                    $image = Input::file('item_specification');

                    $image_name = str_random(20);
                    $ext = strtolower($image->getClientOriginalExtension());
                    $image_full_name = $image_name . '.' . $ext;
                    $upload_path = 'public/upload/item_specification/';
                    $image_url = $upload_path . $image_full_name;
                    $image->move($upload_path, $image_full_name);
                    $item->item_specification = $image_url;
                }

                //$exbToMngt->save();

               if ($item->save()) {
                   //$for_all_org_itm = explode(',',$item->nsd_id);
                   //$org_name = \App\NsdName::find($for_all_org_itm[0]);

                   $updateItm = Item::find($item->id);
                   $updateItm->all_org_item_id = $item->id;
                   $updateItm->save();

                   Session::flash('success', 'Item Created Successfully');
                    return Redirect::to('item/view');
                }

            //} 

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//        $nsdNames = NsdName::where('status_id','=',1)->get();
//        $supplyCategories = SupplyCategory::where('status_id','=',1)->get();

        $zonesRltdIds = array();
        $zonesRltdCtgIds = array();

        $nsdNames = NsdName::where('status_id','=',1)->get();
        foreach($nsdNames as $nn){
            if(empty(Session::get('nsdBsdEmptyOrNot'))){

                if(in_array(Session::get('zoneId'), explode(',', $nn->zones))){
                    $zonesRltdIds[] = $nn->id;
                }

            }else{
                if(in_array(Session::get('zoneId'), explode(',',$nn->zones))){
                    foreach(explode(',',Session::get('nsdBsdEmptyOrNot')) as $nbeon){
                        if($nn->id == $nbeon){
                            $zonesRltdIds[] = $nn->id;
                        }
                    }
                }
            }// esle end
        }
        $nsdNames = NsdName::whereIn('id',$zonesRltdIds)->where('status_id','=',1)->get();

        $supplyCategories = SupplyCategory::where('status_id','=',1)->get();
        foreach($supplyCategories as $ctg){
            if(in_array(Session::get('zoneId'), explode(',', $ctg->zones))){
                $zonesRltdCtgIds[] = $ctg->id;
            }
        }
        //$supplyCategories = SupplyCategory::whereIn('id',$zonesRltdCtgIds)->where('status_id','=',1)->get();

        $supplyCategories = SupplyCategory::orderBy('name')->whereIn('id',$zonesRltdCtgIds);
            if(!empty(Auth::user()->categories_id)){
                $userWiseCat = explode(',', Auth::user()->categories_id);
                $supplyCategories->whereIn('id',$userWiseCat);
            }
            $supplyCategories->where('status_id','=',1);
        $supplyCategories = $supplyCategories->get();

        $denos = Deno::where('status_id','=',1)->get();
        $budget_codes = \App\BudgetCode::where('status_id','=',1)->get();

        $editId = Item::find($id);

        // Get the currency list
        $currencies_names = \App\Currency::select('id','currency_name','conversion')->where('status','=',1)->get();
        $default_currency = \App\Currency::where('default_currency','=',1)->first();

        return View::make('items.edit')->with(compact('editId','supplyCategories','nsdNames','denos','budget_codes','currencies_names','default_currency'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        OwnLibrary::validateAccess($this->moduleId,3);

        $this->middleware('csrf', array('on' => 'post'));

        $v = \Validator::make($request->all(), [
	        'item_name' => 'required',
	        'item_cat_id' => 'required',
	        'nsd_id' => 'required',
	        'item_deno' => 'required',
	        'item_type' => 'required',
        ]);

        if ($v->fails()) {
            return redirect('item/edit/'.$id)->withErrors($v->errors())->withInput();
        }else {

             $checkItem = Item::where('imc_number','=',empty($request->imc_number) ? null : $request->imc_number)
                             ->where('item_name','=',empty($request->item_name) ? null : $request->item_name)
                             ->where('model_number','=',empty($request->model_number) ? null : $request->model_number)
                             ->where('brand','=',empty($request->brand) ? null : $request->brand)
                             ->where('part_number','=',empty($request->part_number) ? null : $request->part_number)
                             ->where('patt_number','=',empty($request->patt_number) ? null : $request->patt_number)
                             ->where('manufacturer_name','=',empty($request->manufacturer_name) ? null : $request->manufacturer_name)
                             ->where('country_of_origin','=',empty($request->country_of_origin) ? null : $request->country_of_origin)
                             ->where('brand','=',empty($request->brand) ? null : $request->brand)
                             ->where('model_number','=',empty($request->model_number) ? null : $request->model_number)
                             ->where('item_cat_id','=',empty($request->item_cat_id) ? null : $request->item_cat_id)
                             ->where('nsd_id','=', empty($request->nsd_id) ? null : implode(',',$request->nsd_id))
                             ->where('addl_item_info','=',empty($request->addl_item_info) ? null : $request->addl_item_info)
                             ->where('item_deno','=',empty($request->item_deno) ? null : $request->item_deno)
                             ->where('item_type','=',empty($request->item_type) ? null : $request->item_type)
                             ->where('item_type_r','=',empty($request->item_type_r) ? null : $request->item_type_r)
                             ->where('main_equipment_name','=',empty($request->main_equipment_name) ? null : $request->main_equipment_name)
                             ->where('main_equipment_brand','=',empty($request->main_equipment_brand) ? null : $request->main_equipment_brand)
                             ->where('main_equipment_model','=',empty($request->main_equipment_model) ? null : $request->main_equipment_model)
                             ->where('main_equipment_additional_info','=',empty($request->main_equipment_additional_info) ? null : $request->main_equipment_additional_info)
                             ->where('substitute_item','=',empty($request->substitute_item) ? null : $request->substitute_item)
                             ->where('shelf_life','=',empty($request->shelf_life) ? null : $request->shelf_life)
                             ->where('currency_name','=',empty($request->currency_name) ? null : $request->currency_name)
                             ->where('conversion','=',empty($request->conversion) ? null : $request->conversion)
                             ->where('unit_price','=',empty($request->unit_price) ? null : $request->unit_price)
                             ->where('unit_price_in_bdt','=',empty($request->unit_price) ? null : $request->unit_price*$request->conversion)
                             ->where('source_of_supply','=',empty($request->source_of_supply) ? null : $request->source_of_supply)
                             ->where('discounted_price','=',empty($request->discounted_price) ? null : $request->discounted_price)
                             ->where('discounted_price_in_bdt','=',empty($request->discounted_price) ? null : $request->discounted_price*$request->conversion)
                             ->where('budget_code','=',empty($request->budget_code) ? null : $request->budget_code)
                             ->where('status_id','=',$request->status)
                             ->get();

            if (count($checkItem) >= 1){
                session()->flash('error','Item already exist');
                return redirect()->back()->withInput();
            }
                   
                $item = Item::find($id);

	        $item->imc_number = $request->imc_number;
	        $item->item_name = empty($request->item_name) ? null : $request->item_name;
	        $item->manufacturer_name = empty($request->manufacturer_name) ? null : $request->manufacturer_name;
	        $item->manufacturing_country = empty($request->manufacturing_country) ? null : $request->manufacturing_country;
	        $item->country_of_origin = empty($request->country_of_origin) ? null : $request->country_of_origin;
            $item->brand = empty($request->brand) ? null : $request->brand;
	        $item->model_number = empty($request->model_number) ? null : $request->model_number;
	        $item->part_number = empty($request->part_number) ? null : $request->part_number;
	        $item->patt_number = empty($request->patt_number) ? null : $request->patt_number;
	        $item->item_cat_id = empty($request->item_cat_id) ? null : $request->item_cat_id;
	        $item->nsd_id = empty($request->nsd_id) ? null : implode(',',$request->nsd_id);
	        $item->addl_item_info = empty($request->addl_item_info) ? null : $request->addl_item_info;
	        $item->item_deno = empty($request->item_deno) ? null : $request->item_deno;
	        $item->item_type = empty($request->item_type) ? null : $request->item_type;
	        $item->item_type_r = empty($request->item_type_r) ? null : $request->item_type_r;
	        $item->main_equipment_name = empty($request->main_equipment_name) ? null : $request->main_equipment_name;
	        $item->main_equipment_brand = empty($request->main_equipment_brand) ? null : $request->main_equipment_brand;
	        $item->main_equipment_model = empty($request->main_equipment_model) ? null : $request->main_equipment_model;
	        $item->main_equipment_additional_info = empty($request->main_equipment_additional_info) ? null : $request->main_equipment_additional_info;
	        $item->substitute_item = empty($request->substitute_item) ? null : $request->substitute_item;
	        $item->shelf_life = empty($request->shelf_life) ? null : $request->shelf_life;
            if(\Session::get("zoneAlise") == "bsd"){
                $item->strength = empty($request->strength) ? 1 : $request->strength;
            }
	        $item->currency_name = empty($request->currency_name) ? null : $request->currency_name;
	        $item->conversion = empty($request->conversion) ? null : $request->conversion;
	        $item->unit_price = empty($request->unit_price) ? null : $request->unit_price;
	        $item->unit_price_in_bdt = empty($request->unit_price) ? null : $request->unit_price*$request->conversion;
	        $item->source_of_supply = empty($request->source_of_supply) ? null : $request->source_of_supply;
	        $item->discounted_price = empty($request->discounted_price) ? null : $request->discounted_price;
	        $item->discounted_price_in_bdt = empty($request->discounted_price) ? null : $request->discounted_price*$request->conversion;
	        $item->budget_code = empty($request->budget_code) ? null : $request->budget_code;
	        $item->status_id = $request->status;

	        if (Input::hasFile('item_picture')) {

	        	@unlink( $item->item_picture);
		        $image = Input::file('item_picture');

		        $image_name = str_random(20);
		        $ext = strtolower($image->getClientOriginalExtension());
		        $image_full_name = $image_name . '.' . $ext;
		        $upload_path = 'public/upload/item_picture/';
		        $image_url = $upload_path . $image_full_name;
		        $image->move($upload_path, $image_full_name);
		        $item->item_picture = $image_url;
	        }

            if (Input::hasFile('item_specification')) {

                @unlink( $item->item_specification);
                $image = Input::file('item_specification');

                $image_name = str_random(20);
                $ext = strtolower($image->getClientOriginalExtension());
                $image_full_name = $image_name . '.' . $ext;
                $upload_path = 'public/upload/item_specification/';
                $image_url = $upload_path . $image_full_name;
                $image->move($upload_path, $image_full_name);
                $item->item_picture = $image_url;
            }
                
                //$exbToMngt->save();

               if ($item->save()) {

                \DB::table('item_to_demand')
                    ->where('item_id', $id)
                    ->update(['item_name' => $item->item_name, 'group_name' =>  $item->item_cat_id, 'deno_id' => $item->item_deno]);

                   Session::flash('success', 'Item Updated Successfully');
                    return Redirect::to('item/view');
                }

            }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        OwnLibrary::validateAccess($this->moduleId,4);
        $item = Item::find($id);
        
        if ($item->delete()) {
                Session::flash('success', 'Item Deleted Successfully');
                return Redirect::to('item/view');
            } else {
                Session::flash('error', 'Item Not Found');
                return Redirect::to('item/view');
            }
    }

    public function printItem($id)
    {
        $nsd_id = Input::get('nsd_id');
        $b_code = Input::get('budget_code');
        $key    = Input::get('key');
        $ct_name    = Input::get('ct_name');

        $nsdNames = NsdName::where('status_id','=',1)->get();
        $zonesRltdIds = array();
        foreach($nsdNames as $nn){
            if(empty(Session::get('nsdBsdEmptyOrNot'))){

                if(in_array(Session::get('zoneId'), explode(',', $nn->zones))){
                    $zonesRltdIds[] = $nn->id;
                }

            }else{
                if(in_array(Session::get('zoneId'), explode(',',$nn->zones))){
                    foreach(explode(',',Session::get('nsdBsdEmptyOrNot')) as $nbeon){
                        if($nn->id == $nbeon){
                            $zonesRltdIds[] = $nn->id;
                        }
                    }
                }
            }// esle end
        }
        $nsdNames = NsdName::whereIn('id',$zonesRltdIds)->where('status_id','=',1)->get();
        $budget_codes =  \App\BudgetCode::where('status_id','=',1)->get();

        $supplyCategories = SupplyCategory::where('status_id','=',1)->get();
        foreach($supplyCategories as $ctg){
            if(in_array(Session::get('zoneId'), explode(',', $ctg->zones))){
                $zonesRltdCtgIds[] = $ctg->id;
            }
        }
        //$suppliercategories = SupplyCategory::whereIn('id',$zonesRltdCtgIds)->where('status_id','=',1)->get();
        
        $suppliercategories = SupplyCategory::whereIn('id',$zonesRltdCtgIds);
            if(!empty(Auth::user()->categories_id)){
                $userWiseCat = explode(',', Auth::user()->categories_id);
                $suppliercategories->whereIn('id',$userWiseCat);
            }
            $suppliercategories->where('status_id','=',1);
        $suppliercategories = $suppliercategories->get();

        $nsds =  '';
        
        $allItemIdsUnderNsd = '';
        $allItemIdsUnderNsdArray = array();
        $nsdids = Item::select('id','nsd_id','item_cat_id')->where('status_id','=',1)->get();

            foreach ($nsdids as $ni) {
                foreach(explode(',',$ni->nsd_id) as $rni){
                    if(!empty($nsd_id)){
                        if(in_array($nsd_id, explode(',',$ni->nsd_id))){

                            if(!empty(Auth::user()->categories_id)){
                                $userWiseCat = explode(',', Auth::user()->categories_id);
                                if(in_array($ni->item_cat_id, $userWiseCat)){
                                    $allItemIdsUnderNsdArray[] = $ni->id;
                                }
                            }else{
                                $allItemIdsUnderNsdArray[] = $ni->id;
                            }
                        }
                    }else {
                        if (in_array($rni, $zonesRltdIds)) {

                            if (!empty(Auth::user()->categories_id)) {
                                $userWiseCat = explode(',', Auth::user()->categories_id);
                                    if (in_array($ni->item_cat_id, $userWiseCat)) {
                                        $allItemIdsUnderNsdArray[] = $ni->id;
                                    }
                            } else {
                                $allItemIdsUnderNsdArray[] = $ni->id;
                            }

                        }
                    }
                }

            }// End foreach

            $nsdId = 1;
             if(!empty(Auth::user()->nsd_bsd)){
                $nsdId = Auth::user()->nsd_bsd;
             }
            $orgInfo  = \App\NsdName::find($nsdId);

                $items = Item::find($id);
           
           $default_currency = \App\Currency::where('default_currency','=',1)->first();

        $data = [
           'items'                  => $items,
           'nsdNames'               => $nsdNames,
           'nsd_id'                 => $nsd_id,
           'budget_codes'           => $budget_codes,
           'b_code'                 => $b_code,
           'key'                    => $key,
           'suppliercategories'     => $suppliercategories,
           'ct_name'                => $ct_name,
           'default_currency'       => $default_currency,
           'orgInfo'                => $orgInfo
        ];


        $pdf= PDF::loadView('items.print',$data,[],['format' => [215.9, 342.9]]);
        return $pdf->stream('item-details'.$items->item_name.'.pdf');

       // return View::make('items.index')->with(compact('items','nsdNames','nsd_id','budget_codes','b_code','key','suppliercategories','ct_name','default_currency'));

    }


}
