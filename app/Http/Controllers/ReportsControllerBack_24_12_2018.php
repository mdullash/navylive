<?php

namespace App\Http\Controllers;

use App\ItemToTender;
use App\Tender;
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

class ReportsController extends Controller
{

    private $tableAlies;

    public function __construct() {
//        $this->tableAlies = \Session::get('zoneAlise');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $suppliers = Supplier::paginate(10);

        return View::make('suppliers.index')->with(compact('suppliers'));

    }

    public function awardedSupplierList(){

        $this->tableAlies = \Session::get('zoneAlise');

        $nsd_id = Input::get('nsd_id');
        $sup_id = Input::get('sup_id');
        $item_id = Input::get('item_id');
        $from   = Input::get('from');
        $to     = Input::get('to');
        $budget_cd_id = Input::get('budget_cd_id');

        $grand_total_select_filter = Input::get('grand_total_select_filter');
        $range_start = Input::get('range_start');
        $range_end = Input::get('range_end');

        if(!empty($from)){
            $from   = date('Y-m-d',strtotime(Input::get('from')));
        }
        if(!empty($to)){
            $to     = date('Y-m-d',strtotime(Input::get('to')));
        }

        // Nsd name ================================================================
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

        // Supplier name ============================================================

        $AllSuppliers = Supplier::where('status_id','=',1)->get();
        $zonesRltdIdsss = array();
        foreach($AllSuppliers as $spl){
            foreach(explode(',',$spl->registered_nsd_id) as $rni){

                if(in_array($rni, $zonesRltdIds)){
                    $zonesRltdIdsss[] = $spl->id;
                }

            }
        }
        $suppliersName  = Supplier::whereIn('id',$zonesRltdIdsss)->where('status_id','=',1)->get();

        $itemList = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.id')
        ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=',$this->tableAlies.'_itemtotender.item_id')
        ->select($this->tableAlies.'_items.id',$this->tableAlies.'_items.item_name')
        ->whereIn($this->tableAlies.'_tenders.nsd_id',$zonesRltdIds)->get();


        $suppliersrep = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.id')
            ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=',$this->tableAlies.'_tenders.supplier_id')
            ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=',$this->tableAlies.'_itemtotender.item_id')
            ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
            ->select($this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_suppliers.id as supplier_id' ,$this->tableAlies.'_tenders.po_number',$this->tableAlies.'_tenders.tender_title',$this->tableAlies.'_items.imc_number',$this->tableAlies.'_items.item_name',$this->tableAlies.'_items.item_deno',$this->tableAlies.'_itemtotender.quantity',$this->tableAlies.'_itemtotender.unit_price',$this->tableAlies.'_itemtotender.discount_price',$this->tableAlies.'_itemtotender.total','deno.name as deno_name')
            ->whereIn($this->tableAlies.'_tenders.nsd_id',$zonesRltdIds)
            ->whereIn($this->tableAlies.'_tenders.supplier_id',$zonesRltdIdsss)
            ->orderBy($this->tableAlies.'_suppliers.id','ASC')
            ->paginate(50);

        if(!empty($nsd_id) || !empty($from) || !empty($to) || !empty($sup_id) || !empty($grand_total_select_filter) || !empty($item_id) || !empty($budget_cd_id)){

            $suppliersrep = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.id')
                ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=',$this->tableAlies.'_tenders.supplier_id')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=',$this->tableAlies.'_itemtotender.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select($this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_suppliers.id as supplier_id', $this->tableAlies.'_tenders.po_number', $this->tableAlies.'_tenders.tender_title', $this->tableAlies.'_items.imc_number', $this->tableAlies.'_items.item_name', $this->tableAlies.'_items.item_deno', $this->tableAlies.'_itemtotender.quantity', $this->tableAlies.'_itemtotender.unit_price', $this->tableAlies.'_itemtotender.discount_price', $this->tableAlies.'_itemtotender.total', 'deno.name as deno_name');
            if(!empty($nsd_id)){
                $suppliersrep->where($this->tableAlies.'_tenders.nsd_id','=',$nsd_id);
            }
            if(!empty($sup_id)){
                $suppliersrep->where($this->tableAlies.'_tenders.supplier_id','=',$sup_id);
            }
            if(!empty($item_id)){
                $suppliersrep->where($this->tableAlies.'_items.id','=',$item_id);
            }
            if(!empty($budget_cd_id)){
                $suppliersrep->where($this->tableAlies.'_items.budget_code','=',$budget_cd_id);
            }
            if(!empty($from)){
                $suppliersrep->where(function($query) use ($from ){
                    $query->whereDate($this->tableAlies.'_tenders.work_order_date','>=',$from);
                    $query->orWhereDate($this->tableAlies.'_tenders.tender_opening_date','>=',$from);
                });
            }
            if(!empty($to)){
                $suppliersrep->where(function($query) use ($to){
                    $query->whereDate($this->tableAlies.'_tenders.work_order_date','<=',$to);
                    $query->orWhere($this->tableAlies.'_tenders.tender_opening_date','<=',$to);
                });
            }

// start range search ====================================
            $forforeach = $suppliersrep->get();
            $grandtotalSupplierExit = array();
            if(!empty($grand_total_select_filter)){

                foreach ($forforeach as $value) {
                    $grandTotal = $forforeach->where('company_name','=',$value->company_name)->sum('total');
                    if($grand_total_select_filter==100){
                        if(!empty($range_start) || !empty($range_end)){
                            $a = empty($range_start) ? 0 : $range_start;
                            $b = empty($range_end) ? 0 : $range_end;

                            if($grandTotal >= $a && $grandTotal <=$b){
                                $grandtotalSupplierExit[] = $value['supplier_id'];
                            }
                        }
                    }else{
                        $range = explode(',',$grand_total_select_filter);
                        $a = $range[0];
                        $b = $range[1];

                        if($grandTotal >= $a && $grandTotal <=$b){
                            $grandtotalSupplierExit[] = $value['supplier_id'];
                        }
                    }

                }


                $grandtotalSupplierExit = array_unique($grandtotalSupplierExit);
                $suppliersrep->whereIn($this->tableAlies.'_suppliers.id',$grandtotalSupplierExit);

                //echo "<pre>"; print_r($suppliersrep); exit;

            }// end of grnad total range search

            $suppliersrep->orderBy($this->tableAlies.'_suppliers.id','ASC');
            $suppliersrep = $suppliersrep->paginate(50);

        }

        $search_nsd_name = '';
        if(!empty($nsd_id)){
            $search_nsd_name = NsdName::find($nsd_id);;
        }
        $search_supplier_name = '';
        if(!empty($sup_id)){
            $search_supplier_name = Supplier::find($sup_id);;
        }

        return View::make('reports.awarded-suppliers-list',compact('nsdNames','suppliersrep','nsd_id','from','to','search_nsd_name','suppliersName','sup_id','search_supplier_name','grand_total_select_filter','range_start','range_end','itemList','item_id','budget_codes','budget_cd_id'));

    }

    public function tenderParticipate(){ 
        $this->tableAlies = \Session::get('zoneAlise');

        $nsd_id = Input::get('nsd_id');
        $ten_title = Input::get('ten_title');
        $ten_number = Input::get('ten_number');
        $po_number = Input::get('po_number');

        $nsdNames       = NsdName::where('status_id','=',1)->get();
        $zonesRltdIds = array();
        // Nsd name ================================================================
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


        $tenderList  = \App\Tender::whereIn('nsd_id',$zonesRltdIds)->where('status_id','=',1)->get();

        $tender_numbers  = \App\Tender::whereIn('nsd_id',$zonesRltdIds)->where('status_id','=',1)->where('tender_number','!=',null)->get();
        $po_numbers  = \App\Tender::whereIn('nsd_id',$zonesRltdIds)->where('status_id','=',1)->where('po_number','!=',null)->get();

// Search ===================================================================

        $suppliersrep = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.id')
            ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=',$this->tableAlies.'_tenders.supplier_id')
            ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=',$this->tableAlies.'_itemtotender.item_id')
            ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
            ->select($this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_suppliers.id as supplier_id' ,$this->tableAlies.'_tenders.po_number',$this->tableAlies.'_tenders.tender_title',$this->tableAlies.'_items.imc_number',$this->tableAlies.'_items.item_name',$this->tableAlies.'_items.item_deno',$this->tableAlies.'_itemtotender.quantity',$this->tableAlies.'_itemtotender.unit_price',$this->tableAlies.'_itemtotender.discount_price',$this->tableAlies.'_itemtotender.total','deno.name as deno_name')
            ->whereIn($this->tableAlies.'_tenders.nsd_id',$zonesRltdIds)
            ->orderBy($this->tableAlies.'_suppliers.id','ASC')
            ->paginate(20);

        if(!empty($nsd_id) || !empty($ten_title) || !empty($ten_number) || !empty($po_number) ){

            $suppliersrep = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.id')
                ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=',$this->tableAlies.'_tenders.supplier_id')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=',$this->tableAlies.'_itemtotender.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select($this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_suppliers.id as supplier_id',$this->tableAlies.'_tenders.po_number',$this->tableAlies.'_tenders.tender_title',$this->tableAlies.'_items.imc_number',$this->tableAlies.'_items.item_name',$this->tableAlies.'_items.item_deno',$this->tableAlies.'_itemtotender.quantity',$this->tableAlies.'_itemtotender.unit_price',$this->tableAlies.'_itemtotender.discount_price',$this->tableAlies.'_itemtotender.total','deno.name as deno_name');
            if(!empty($nsd_id)){
                $suppliersrep->where($this->tableAlies.'_tenders.nsd_id','=',$nsd_id);
            }
            if(!empty($ten_title)){
                $suppliersrep->where($this->tableAlies.'_tenders.tender_title','=',$ten_title);
            }
            if(!empty($ten_number)){
                $suppliersrep->where($this->tableAlies.'_tenders.tender_number','=',$ten_number);
            }
            if(!empty($po_number)){
                $suppliersrep->where($this->tableAlies.'_tenders.po_number','=',$po_number);
            }
            $suppliersrep->orderBy($this->tableAlies.'_suppliers.id','ASC');
            $suppliersrep = $suppliersrep->paginate(20);

        }

// End search ===============================================================

        $search_nsd_name = '';
        if(!empty($nsd_id)){
            $search_nsd_name = NsdName::find($nsd_id);;
        }

        return View::make('reports.tender-participates',compact('nsdNames','nsd_id','search_nsd_name','tenderList','ten_title','suppliersrep','ten_number','po_number','tender_numbers','po_numbers'));

    }

    public function catProSupplierList(Request $request){

        $this->tableAlies = \Session::get('zoneAlise');

        $cat_id   = Input::get('cat_id');
        $item_id  = Input::get('item_id');
        $nsd_id   = Input::get('nsd_id');

        //$suppliercategories = \App\SupplyCategory::where('status_id','=',1)->get();
//        $suppliercategories = \App\SupplyCategory::where('status_id','=',1)->get();
//        $nsdNames = NsdName::where('status_id','=',1)->get();
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

        $AllSuppliers = Supplier::where('status_id','=',1)->get();
        $zonesRltdIdsss = array();
        foreach($AllSuppliers as $spl){
            foreach(explode(',',$spl->registered_nsd_id) as $rni){
                if(in_array($rni, $zonesRltdIds)){
                    $zonesRltdIdsss[] = $spl->id;
                }
            }
        }

        $supplyCategories = SupplyCategory::where('status_id','=',1)->get();
        foreach($supplyCategories as $ctg){
            if(in_array(Session::get('zoneId'), explode(',', $ctg->zones))){
                $zonesRltdCtgIds[] = $ctg->id;
            }
        }
        $suppliercategories = SupplyCategory::whereIn('id',$zonesRltdCtgIds)->where('status_id','=',1)->get();

        // Iteam searching ===============================
        $allItemIdsUnderNsd = '';
        $allItemIdsUnderNsdArray = array();
        $nsdids = \App\Item::select('id','nsd_id')->where('status_id','=',1)->get();
        foreach ($nsdids as $ni) {
            if (!empty(Session::get('nsdBsdEmptyOrNot')) && in_array(Session::get('zoneId'),explode(',',Auth::user()->zones))) {
                foreach (explode(',', Session::get('nsdBsdEmptyOrNot')) as $val) {
                    if (in_array($val, explode(',', $ni->nsd_id))) {
                        $allItemIdsUnderNsdArray[] = $ni->id;
                    }
                }
            } // If end
            $allItemIdsUnderNsd = array_unique($allItemIdsUnderNsdArray);
        }// End foreach
        if(!empty($allItemIdsUnderNsd)){
            $productsnames = \App\Item::whereIn('id',$allItemIdsUnderNsd)->where('status_id','=',1)->get();
        }else{
            $nsdids = \App\Item::select('id','nsd_id')->where('status_id','=',1)->get();
            $productsnames = \App\Item::paginate(10);
        }


        $catsupplier = array();
        $itemsupplier= array();
        $itemsnsdupplier= array();
        $suppliers = Supplier::orderBy('id');

        if(!empty($cat_id) || !empty($item_id) || !empty($nsd_id)){

            if(!empty($cat_id)){
                $catsupplierss = Supplier::select('id','supply_cat_id')->where('status_id','=',1)->get();
                $values = [];
                foreach ($catsupplierss as $catsup) {
                    foreach(explode(',', $catsup->supply_cat_id) as $value) {
                        if($value==$cat_id){
                            $values[] = $catsup->id;
                        }

                    }
                }
                $catsupplier = array_unique($values);
                //echo "<pre>"; print_r($catsupplier); exit;
            }

            if(!empty($item_id)){
                $items = array_map('current',ItemToTender::select('tender_id')->where('item_id','=',$item_id)->get()->toArray());
                if(!empty($items)){
                    $itemss = Tender::whereIn('id',$items)->get();
                    $valuesss = [];
                    foreach ($itemss as $catsup) {
                        $valuesss[] = $catsup->supplier_id;
                    }
                    $itemsupplier = array_unique($valuesss);
                }

            }

            if(!empty($nsd_id)){
                $itemsnsdsupplier = Supplier::select('id','registered_nsd_id')->where('status_id','=',1)->get();
                $valuessss = [];
                foreach ($itemsnsdsupplier as $ins) {
                    foreach(explode(',', $ins->registered_nsd_id) as $value) {
                        if($value==$nsd_id){
                            $valuessss[] = $ins->id;
                        }
                    }
                }
                $itemsnsdupplier = array_unique($valuessss);

            }

            $single_array = array_unique(array_merge($catsupplier,$itemsupplier,$itemsnsdupplier));
            // $itemsupplier = array_unique($valuesss);

            $suppliers = Supplier::whereIn('id',$single_array)->whereIn('id',$zonesRltdIdsss)->paginate(10);

        }else{
            $suppliers = $suppliers->whereIn('id',$zonesRltdIdsss)->paginate(10);
        }


        return View::make('reports.category-wise-supplier',compact('suppliercategories','productsnames','cat_id','item_id','suppliers','nsdNames','nsd_id'));

    }

    public function supplierReport(){

        $this->tableAlies = \Session::get('zoneAlise');

        $nsd_id = Input::get('nsd_id');
        $company_mobile = Input::get('company_mobile');

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

        $AllSuppliers = Supplier::where('status_id','=',1)->get();
        $zonesRltdIdsss = array();
        foreach($AllSuppliers as $spl){
            foreach(explode(',',$spl->registered_nsd_id) as $rni){
                if(!empty($nsd_id)){
                    if(in_array($nsd_id, explode(',',$spl->registered_nsd_id))){
                        $zonesRltdIdsss[] = $spl->id;
                    }
                }else{
                    if(in_array($rni, $zonesRltdIds)){
                        $zonesRltdIdsss[] = $spl->id;
                    }
                }

            }
        }

        $suppliers = Supplier::where('status_id','=',1);

        if(!empty($nsd_id) || !empty($company_mobile)){

            if(!empty($nsd_id)){
                $suppliers->whereIn('id',$zonesRltdIdsss);
            }
            if(!empty($company_mobile)){
                $suppliers->where(function($query) use ($company_mobile){
                    $query->where('company_name', 'like', "%{$company_mobile}%");
                    $query->orWhere('mobile_number', 'like', "%{$company_mobile}%");
                    $query->orWhere('company_regi_number_nsd', 'like', "%{$company_mobile}%");
                });
            }

        }
        $suppliers = $suppliers->whereIn('id',$zonesRltdIdsss)->paginate(10);

        return View::make('reports.suppliers-nsd-wise')->with(compact('suppliers','nsdNames','nsd_id','company_mobile'));

    }

    public function nsdWiseSupplier(Request $request){

        $nsdwisesupplier = \App\Supplier::select('company_name','id')->where('registered_nsd_id', $request->nsd_id)->where('status_id','=',1)->get();

        $nsdwisesupplierAppend = '<option value="">All</option>';

        foreach($nsdwisesupplier as $ae){
            $nsdwisesupplierAppend .='<option value="'.$ae->id.'">'.$ae->company_name.'</option>';
        }

        $data['nsdwisesupplier'] = $nsdwisesupplierAppend;

        return $data;

    }

    public function nsdWiseTender(Request $request){

        $nsdwisetender = \App\Tender::select('tender_title','id')->where('nsd_id', $request->nsd_id)->where('status_id','=',1)->get();

        $nsdwiseTenderAppend = '<option value="">All</option>';

        foreach($nsdwisetender as $ae){
            $nsdwiseTenderAppend .='<option value="'.$ae->id.'">'.$ae->tender_title.'</option>';
        }

        $data['nsdwisetender'] = $nsdwiseTenderAppend;

        return $data;

    }

    public function categoryWiseItems(Request $request){

        $catwiseitem = \App\Item::select('item_name','id')->where('item_cat_id', $request->cat_id)->where('status_id','=',1)->get();

        $nsdwiseTenderAppend = '<option value="">All</option>';

        foreach($catwiseitem as $ae){
            $nsdwiseTenderAppend .='<option value="'.$ae->id.'">'.$ae->item_name.'</option>';
        }

        $data['catwiseitems'] = $nsdwiseTenderAppend;

        return $data;

    }



}


 