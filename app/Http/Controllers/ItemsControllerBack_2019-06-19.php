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

class ItemsController extends Controller
{

    private $moduleId = 12;

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

       return View::make('items.index')->with(compact('items','nsdNames','nsd_id','budget_codes','b_code','key','suppliercategories','ct_name','default_currency'));

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

        $supplyCategories = SupplyCategory::where('status_id','=',1)->get();
        foreach($supplyCategories as $ctg){
            if(in_array(Session::get('zoneId'), explode(',', $ctg->zones))){
                $zonesRltdCtgIds[] = $ctg->id;
            }
        }
        //$supplyCategories = SupplyCategory::whereIn('id',$zonesRltdCtgIds)->where('status_id','=',1)->get();
        
        $supplyCategories = SupplyCategory::whereIn('id',$zonesRltdCtgIds);
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

        return View::make('items.create')->with(compact('supplyCategories','nsdNames','denos','budget_codes','currencies_names','default_currency'));
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
            'imc_number' => 'required',
            'item_name' => 'required|unique:'.\Session::get("zoneAlise").'_items,item_name',
            'model_number' => 'required',
            'item_cat_id' => 'required',
            'unit_price' => 'required',
            'currency_name' => 'required',
            'conversion' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('item/create')->withErrors($v->errors())->withInput();
        }else {
                   
                $item = new Item();

                $item->imc_number = $request->imc_number;
                $item->item_name = empty($request->item_name) ? null : $request->item_name;
                $item->model_number = empty($request->model_number) ? null : $request->model_number;
                $item->item_cat_id = empty($request->item_cat_id) ? null : $request->item_cat_id;
                $item->nsd_id = empty($request->nsd_id) ? null : implode(',',$request->nsd_id);
                $item->currency_name = empty($request->currency_name) ? null : $request->currency_name;
                $item->conversion = empty($request->conversion) ? null : $request->conversion;
                $item->unit_price = empty($request->unit_price) ? null : $request->unit_price;
                $item->unit_price_in_bdt = empty($request->unit_price) ? null : $request->unit_price*$request->conversion;
                $item->item_deno = empty($request->item_deno) ? null : $request->item_deno;
                $item->source_of_supply = empty($request->source_of_supply) ? null : $request->source_of_supply;
                $item->discounted_price = empty($request->discounted_price) ? null : $request->discounted_price;
                $item->discounted_price_in_bdt = empty($request->discounted_price) ? null : $request->discounted_price*$request->conversion;
                $item->manufacturing_country = empty($request->manufacturing_country) ? null : $request->manufacturing_country;
                $item->budget_code = empty($request->budget_code) ? null : $request->budget_code;
                $item->item_type = empty($request->item_type) ? null : $request->item_type;
                
                $item->status_id = $request->status;
                
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

        $supplyCategories = SupplyCategory::whereIn('id',$zonesRltdCtgIds);
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
            'imc_number' => 'required',
            'item_name' => 'required|unique:'.\Session::get("zoneAlise").'_items,item_name,'.$id,
            'model_number' => 'required',
            'item_cat_id' => 'required',
            'unit_price' => 'required',
            'currency_name' => 'required',
            'conversion' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('item/edit/'.$id)->withErrors($v->errors())->withInput();
        }else {
                   
                $item = Item::find($id);

                $item->imc_number = $request->imc_number;
                $item->item_name = empty($request->item_name) ? null : $request->item_name;
                $item->model_number = empty($request->model_number) ? null : $request->model_number;
                $item->item_cat_id = empty($request->item_cat_id) ? null : $request->item_cat_id;
                $item->nsd_id = empty($request->nsd_id) ? null : implode(',',$request->nsd_id);
                $item->currency_name = empty($request->currency_name) ? null : $request->currency_name;
                $item->conversion = empty($request->conversion) ? null : $request->conversion;
                $item->unit_price = empty($request->unit_price) ? null : $request->unit_price;
                $item->unit_price_in_bdt = empty($request->unit_price) ? null : $request->unit_price*$request->conversion;
                $item->item_deno = empty($request->item_deno) ? null : $request->item_deno;
                $item->source_of_supply = empty($request->source_of_supply) ? null : $request->source_of_supply;
                $item->discounted_price = empty($request->discounted_price) ? null : $request->discounted_price;
                $item->discounted_price_in_bdt = empty($request->discounted_price) ? null : $request->discounted_price*$request->conversion;
                $item->manufacturing_country = empty($request->manufacturing_country) ? null : $request->manufacturing_country;
                $item->budget_code = empty($request->budget_code) ? null : $request->budget_code;
                $item->item_type = empty($request->item_type) ? null : $request->item_type;
                
                $item->status_id = $request->status;
                
                //$exbToMngt->save();

               if ($item->save()) {
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


}
