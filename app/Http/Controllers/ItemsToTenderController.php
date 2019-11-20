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
use App\Tender;
use App\ItemToTender;

class ItemsToTenderController extends Controller
{

    private $moduleId = 16;

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $items = Item::paginate(10);
       
       return View::make('items.index')->with(compact('items'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null)
    {
        
        $tender_id = $id;

        $allItemIdsUnderNsd = '';
        $allItemIdsUnderNsdArray = array();
        $nsdids = \App\Item::select('id','nsd_id')->where('status_id','=',1)->get();

        // Newly added ======================================================================
            if (!empty(Session::get('nsdBsdEmptyOrNot')) && in_array(Session::get('zoneId'),explode(',',Auth::user()->zones))) {

                $zoneRelatedNavLoc = array_map('current',NsdName::select('id')
                                ->where('zones','=',Session::get('zoneId'))->get()->toArray());

                    
                    foreach (explode(',', Session::get('nsdBsdEmptyOrNot')) as $val) {
                        if(in_array($val, $zoneRelatedNavLoc)){

                            if(!empty(Auth::user()->categories_id)){
                                $allIds[] = array_map('current',\App\Item::select('id')
                                ->where('status_id','=',1)
                                ->whereRaw("find_in_set('".$val."',nsd_id)")
                                ->whereIn('item_cat_id',explode(',',  Auth::user()->categories_id))
                                ->get()->toArray());
                            }else{ 
                                $allIds[] = array_map('current',\App\Item::select('id')
                                ->where('status_id','=',1)
                                ->whereRaw("find_in_set('".$val."',nsd_id)")
                                ->get()->toArray());
                            }

                        }
                        
                    }

                    if(!empty($allIds)){
                        foreach ($allIds as $key => $value) {
                            foreach ($value as $val) {
                                $allItemIdsUnderNsdArray[] = $val;
                            }
                            
                        }
                    }
                    $allItemIdsUnderNsd = array_unique($allItemIdsUnderNsdArray);
            }
// End newly added =================================================================
//

        // foreach ($nsdids as $ni) {

        //     if (!empty(Session::get('nsdBsdEmptyOrNot')) && in_array(Session::get('zoneId'),explode(',',Auth::user()->zones))) {
        //         foreach (explode(',', Session::get('nsdBsdEmptyOrNot')) as $val) {
        //             if (in_array($val, explode(',', $ni->nsd_id))) {
        //                 $allItemIdsUnderNsdArray[] = $ni->id;
        //             }
        //         }
        //     } // If end
        //     $allItemIdsUnderNsd = array_unique($allItemIdsUnderNsdArray);
        // }// End foreach

        if(!empty($allItemIdsUnderNsd)){
            $items = Item::whereIn('id',$allItemIdsUnderNsd)->where('status_id','=',1)->get();
        }else{
            $items = Item::where('status_id','=',1)->get();
        }

        //$items = Item::where('status_id','=',1)->get();
        //$suppliers = Supplier::where('status_id','=',1)->get();
        $denos = Deno::where('status_id','=',1)->get();

        $itemAlreadyAssign = ItemToTender::where('tender_id','=',$id)->get();
        $tender = Tender::find($id);

        // For nsd and bsd wise supplier=========================================
        $zonesRltdIds = array();
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

        $suppliers = Supplier::where('status_id','=',1)->get();
        $zonesRltdIdsss = array();
        foreach($suppliers as $spl){
            foreach(explode(',',$spl->registered_nsd_id) as $rni){
                if(in_array($rni, $zonesRltdIds)){
                    $zonesRltdIdsss[] = $spl->id;
                }
            }
        }

        $suppliers = Supplier::whereIn('id',$zonesRltdIdsss)->where('status_id','=',1)->get();

        $existingIds = array_map('current',ItemToTender::select('id')->where('tender_id','=',$id)->get()->toArray());

        $supplier_name_if_exit = '';
        if(!empty($tender->supplier_id)){
            $supplier_name_if_exit = Supplier::find($tender->supplier_id);
        }

        // echo "<pre>"; print_r($existingIds); exit;
        
        // Get the currency list
        $currencies_names = \App\Currency::select('id','currency_name','conversion')->where('status','=',1)->get();
        $default_currency = \App\Currency::where('default_currency','=',1)->first();

        return View::make('items-to-tender.create')->with(compact('nsdNames','denos','tender_id','suppliers','items','itemAlreadyAssign','tender','existingIds','supplier_name_if_exit','currencies_names','default_currency'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 

        //OwnLibrary::validateAccess($this->moduleId,2);

        $this->middleware('csrf', array('on' => 'post'));

        $v = \Validator::make($request->all(), [
            'supplier_id' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('itemtotender/create/'.$request->tender_id)->withErrors($v->errors())->withInput();
        }else {

            
                $newlyAndExiIds = array();
                $invalieRow = 0;

                if(count($request->item_id)>0 && count($request->unit_price)>0 && count($request->total)>0){
                    
                    $tender = Tender::find($request->tender_id);

                    $tender->supplier_id = $request->supplier_id;
                    $tender->po_number = $request->po_number;
                    $tender->date_line = empty($request->date_line) ? null : date('Y-m-d',strtotime($request->date_line));
                    $tender->work_order_date = empty($request->work_order_date) ? null : date('Y-m-d',strtotime($request->work_order_date));
                    $tender->delivery_date = empty($request->delivery_date) ? null : date('Y-m-d',strtotime($request->delivery_date));

                    if($tender->save()){

                         for($i=0; count($request->item_id)>$i; $i++){

                            if(!empty($request->item_id[$i]) && !empty($request->unit_price[$i])>0 && !empty($request->total[$i])){

                                if(!empty($request->editedfield[$i])){
                                    $itemtotender = ItemToTender::find($request->editedfield[$i]);
                                }else{
                                    $itemtotender = new ItemToTender();
                                }
                                

                                $itemtotender->tender_id = $request->tender_id;
                                $itemtotender->item_id = $request->item_id[$i];
                                $itemtotender->quantity = $request->quantity[$i];
                                $itemtotender->unit_price = $request->unit_price[$i];
                                $itemtotender->unit_price_in_bdt = $request->unit_price[$i]*$request->conversion[$i];
                                $itemtotender->currency_name = $request->currency_name[$i];
                                $itemtotender->conversion = $request->conversion[$i];
                                $itemtotender->discount_price = empty($request->discount_price[$i]) ? 0 : $request->discount_price[$i];
                                $itemtotender->discount_price_in_bdt = empty($request->discount_price[$i]) ? 0 : $request->discount_price[$i]*$request->conversion[$i];
                                $itemtotender->total = $request->total[$i];

                                $itemtotender->save();

                                $newlyAndExiIds[] = $itemtotender->id;

                            }else{
                                $invalieRow++;
                            }

                         }

                    }

                    $existingIds = json_decode($request->existingIds);

                    foreach($existingIds as $eids){
                        if (!in_array($eids, $newlyAndExiIds)){
                            $dltitemtotender = ItemToTender::find($eids);
                            $dltitemtotender->delete();

                        }
                    }


                }

                Session::flash('success', 'Item To Tender Created Successfully<br> '.$invalieRow. ' Rows Can Not Be Created');
                    return Redirect::to('tender/view');
                

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

        $nsdNames = NsdName::where('status_id','=',1)->get();
        $supplyCategories = SupplyCategory::where('status_id','=',1)->get();
        $denos = Deno::where('status_id','=',1)->get();

        $editId = Item::find($id);

        return View::make('items.edit')->with(compact('editId','supplyCategories','nsdNames','denos'));

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
            'item_name' => 'required',
            'model_number' => 'required',
            'item_cat_id' => 'required',
            'unit_price' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('item/edit/'.$id)->withErrors($v->errors())->withInput();
        }else {
                   
                $item = Item::find($id);

                $item->imc_number = $request->imc_number;
                $item->item_name = empty($request->item_name) ? null : $request->item_name;
                $item->model_number = empty($request->model_number) ? null : $request->model_number;
                $item->item_cat_id = empty($request->item_cat_id) ? null : $request->item_cat_id;
                $item->unit_price = empty($request->unit_price) ? null : $request->unit_price;
                $item->item_deno = empty($request->item_deno) ? null : $request->item_deno;
                $item->source_of_supply = empty($request->source_of_supply) ? null : $request->source_of_supply;
                $item->discounted_price = empty($request->discounted_price) ? null : $request->discounted_price;
                $item->manufacturing_country = empty($request->manufacturing_country) ? null : $request->manufacturing_country;
                
                $item->status_id = $request->status;
                
                //$exbToMngt->save();

               if ($item->save()) {
                   Session::flash('success', 'Item Created Successfully');
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
