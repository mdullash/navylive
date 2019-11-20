<?php

namespace App\Http\Controllers;

use App\Item;
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
use Maatwebsite\Excel\Facades\Excel;

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

        $suppliers = Supplier::paginate(50);

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
        $search_by_cat = Input::get('search_by_cat');

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
        $budget_codes =  \App\BudgetCode::where('status_id','=',1)->orderBy('code', 'ASC')->get();

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
        $nsdNames = NsdName::whereIn('id',$zonesRltdIds)->where('status_id','=',1)->get();

            // Add new ==============================================================================================================
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $paginatorRange = Input::get('page');
        if($paginatorRange == null || $paginatorRange == 1){
            $paginatorRange = 0;
        }else{
            $paginatorRange = $paginatorRange*50-50;
        }

        $preGrandTotal = 0;
        $preQuantity = 0;
        if($paginatorRange>0){

            $supplierForGndT = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.id')
                ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=',$this->tableAlies.'_tenders.supplier_id')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=',$this->tableAlies.'_itemtotender.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno');

                if($search_by_cat!=2){
                    $supplierForGndT->select($this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_suppliers.id as supplier_id', $this->tableAlies.'_tenders.po_number', $this->tableAlies.'_tenders.tender_title', $this->tableAlies.'_items.imc_number', $this->tableAlies.'_items.item_name', $this->tableAlies.'_items.item_deno', $this->tableAlies.'_itemtotender.quantity', $this->tableAlies.'_itemtotender.unit_price', $this->tableAlies.'_itemtotender.discount_price', $this->tableAlies.'_itemtotender.total', 'deno.name as deno_name', $this->tableAlies.'_tenders.tender_opening_date');
                }else{
                    $supplierForGndT->select($this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_suppliers.id as supplier_id', $this->tableAlies.'_tenders.po_number', $this->tableAlies.'_tenders.tender_title', $this->tableAlies.'_items.imc_number', $this->tableAlies.'_items.item_name', $this->tableAlies.'_items.item_deno', $this->tableAlies.'_itemtotender.quantity', $this->tableAlies.'_itemtotender.unit_price', $this->tableAlies.'_itemtotender.discount_price', $this->tableAlies.'_itemtotender.total', 'deno.name as deno_name', $this->tableAlies.'_tenders.tender_opening_date', \DB::raw('sum('.$this->tableAlies.'_itemtotender.total) total'), \DB::raw('sum('.$this->tableAlies.'_itemtotender.discount_price) discount_price') );
                    
                }
            
            $supplierForGndT->whereIn($this->tableAlies.'_tenders.nsd_id',$zonesRltdIds);
            
            if(!empty(Auth::user()->categories_id)){
                $supplierForGndT->whereIn($this->tableAlies.'_tenders.tender_cat_id',explode(',', Auth::user()->categories_id));
            }
            if(!empty($nsd_id)){
                $supplierForGndT->where($this->tableAlies.'_tenders.nsd_id','=',$nsd_id);
            }
            if(!empty($sup_id)){
                $supplierForGndT->where($this->tableAlies.'_tenders.supplier_id','=',$sup_id);
            }
            if(!empty($item_id)){
                $supplierForGndT->where($this->tableAlies.'_items.id','=',$item_id);
            }
            if(!empty($budget_cd_id)){
                $supplierForGndT->where($this->tableAlies.'_items.budget_code','=',$budget_cd_id);
            }
            if(!empty($from)){
                $supplierForGndT->where(function($query) use ($from ){
                    $query->whereDate($this->tableAlies.'_tenders.work_order_date','>=',$from);
                    $query->orWhereDate($this->tableAlies.'_tenders.tender_opening_date','>=',$from);
                });
            }
            if(!empty($to)){
                $supplierForGndT->where(function($query) use ($to){
                    $query->whereDate($this->tableAlies.'_tenders.work_order_date','<=',$to);
                    $query->orWhere($this->tableAlies.'_tenders.tender_opening_date','<=',$to);
                });
            }

// start range search ====================================
            $forforeach = $supplierForGndT->get();
            $groupBySupplierIds = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.id')
                ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=',$this->tableAlies.'_tenders.supplier_id')
                ->select($this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_suppliers.id as supplier_id', $this->tableAlies.'_tenders.nsd_id', $this->tableAlies.'_tenders.po_number', $this->tableAlies.'_tenders.tender_title')
                ->groupBy($this->tableAlies.'_tenders.supplier_id')->get();
            $grandtotalSupplierExit = array();
            if(!empty($grand_total_select_filter)){

                foreach ($groupBySupplierIds as $value) {
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
                $supplierForGndT->whereIn($this->tableAlies.'_suppliers.id',$grandtotalSupplierExit);

                //echo "<pre>"; print_r($suppliersrep); exit;

            }// end of grnad total range search

            $supplierForGndT->orderBy($this->tableAlies.'_suppliers.id','ASC')->orderBy($this->tableAlies.'_tenders.po_number', 'ASC');

            if($search_by_cat==2){
                $supplierForGndT->groupBy($this->tableAlies.'_tenders.po_number');
            }

            $supplierForGndT = $supplierForGndT->limit($paginatorRange)->get();

            $preGrandTotal = $supplierForGndT->sum('total');
            $preQuantity = $supplierForGndT->sum('quantity'); 
        }

// End add new ===========================================================================================================
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            $suppliersrep = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.id')
                ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=',$this->tableAlies.'_tenders.supplier_id')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=',$this->tableAlies.'_itemtotender.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->join('multiple_currency',$this->tableAlies.'_itemtotender.currency_name','=','multiple_currency.id');
                if($search_by_cat!=2){
                    $suppliersrep->select($this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_suppliers.id as supplier_id', $this->tableAlies.'_tenders.nsd_id', $this->tableAlies.'_tenders.po_number', $this->tableAlies.'_tenders.tender_title', $this->tableAlies.'_items.imc_number', $this->tableAlies.'_items.item_name', $this->tableAlies.'_items.item_deno', $this->tableAlies.'_itemtotender.quantity', $this->tableAlies.'_itemtotender.unit_price', $this->tableAlies.'_itemtotender.discount_price', $this->tableAlies.'_itemtotender.total', 'deno.name as deno_name','multiple_currency.currency_name as curname', $this->tableAlies.'_tenders.tender_opening_date');
                }else{
                    $suppliersrep->select($this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_suppliers.id as supplier_id', $this->tableAlies.'_tenders.nsd_id', $this->tableAlies.'_tenders.po_number', $this->tableAlies.'_tenders.tender_title', $this->tableAlies.'_items.imc_number', $this->tableAlies.'_items.item_name', $this->tableAlies.'_items.item_deno', $this->tableAlies.'_itemtotender.quantity', $this->tableAlies.'_itemtotender.unit_price', 'deno.name as deno_name','multiple_currency.currency_name as curname', $this->tableAlies.'_tenders.tender_opening_date', \DB::raw('sum('.$this->tableAlies.'_itemtotender.total) total'), \DB::raw('sum('.$this->tableAlies.'_itemtotender.discount_price) discount_price'));
                }
            $suppliersrep->whereIn($this->tableAlies.'_tenders.nsd_id',$zonesRltdIds);
            if(!empty(Auth::user()->categories_id)){
                $suppliersrep->whereIn($this->tableAlies.'_tenders.tender_cat_id',explode(',', Auth::user()->categories_id));
            }    
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

            $groupBySupplierIds = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.id')
                ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=',$this->tableAlies.'_tenders.supplier_id')
                ->select($this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_suppliers.id as supplier_id', $this->tableAlies.'_tenders.nsd_id', $this->tableAlies.'_tenders.po_number', $this->tableAlies.'_tenders.tender_title')
                ->groupBy($this->tableAlies.'_tenders.supplier_id')->get();
                
            
            $grandtotalSupplierExit = array();
            if(!empty($grand_total_select_filter)){

                foreach ($groupBySupplierIds as $value) {
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

            $suppliersrep->orderBy($this->tableAlies.'_suppliers.id','ASC')->orderBy($this->tableAlies.'_tenders.po_number', 'ASC');

            if($search_by_cat==2){
                $suppliersrep->groupBy($this->tableAlies.'_tenders.po_number');
            }

            $suppliersrep = $suppliersrep->paginate(50);

        $search_nsd_name = '';
        if(!empty($nsd_id)){
            $search_nsd_name = NsdName::find($nsd_id);;
        }
        $search_supplier_name = '';
        if(!empty($sup_id)){
            $search_supplier_name = Supplier::find($sup_id);;
        }
        $search_item_name = '';
        if(!empty($item_id)){
            $search_item_name = \App\Item::find($item_id);;
        }

        return View::make('reports.awarded-suppliers-list',compact('nsdNames','suppliersrep','nsd_id','from','to','search_nsd_name','suppliersName','sup_id','search_supplier_name','grand_total_select_filter','range_start','range_end','itemList','item_id','budget_codes','budget_cd_id','preGrandTotal','preQuantity','search_item_name','search_by_cat'));

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

        $tenderList = array('' => 'All') + \App\Tender::where('status_id','=',1)->whereIn('nsd_id',$zonesRltdIds)->pluck('tender_title','id')->toArray();

        $tender_numbers  = array('' => 'All') + \App\Tender::where('status_id','=',1)->where('tender_number','!=',null)->whereIn('nsd_id',$zonesRltdIds)->pluck('tender_number','id')->toArray();

        $po_numbers  = array('' => 'All') + \App\Tender::where('status_id','=',1)->where('po_number','!=',null)->whereIn('nsd_id',$zonesRltdIds)->pluck('po_number','id')->toArray();

// Search ===================================================================

            // Add new ==============================================================================================================
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $paginatorRange = Input::get('page');
        if($paginatorRange == null || $paginatorRange == 1){
            $paginatorRange = 0;
        }else{
            $paginatorRange = $paginatorRange*50-50;
        }

        $preGrandTotal = 0;
        $preQuantity = 0;
        if($paginatorRange>0){

            $supplierForGndT = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.id')
                ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=',$this->tableAlies.'_tenders.supplier_id')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=',$this->tableAlies.'_itemtotender.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select($this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_suppliers.id as supplier_id',$this->tableAlies.'_tenders.po_number',$this->tableAlies.'_tenders.tender_title',$this->tableAlies.'_items.imc_number',$this->tableAlies.'_items.item_name',$this->tableAlies.'_items.item_deno',$this->tableAlies.'_itemtotender.quantity',$this->tableAlies.'_itemtotender.unit_price',$this->tableAlies.'_itemtotender.discount_price',$this->tableAlies.'_itemtotender.total','deno.name as deno_name');
            $supplierForGndT->whereIn($this->tableAlies.'_tenders.nsd_id',$zonesRltdIds);
            
            if(!empty(Auth::user()->categories_id)){
                $supplierForGndT->whereIn($this->tableAlies.'_tenders.tender_cat_id',explode(',', Auth::user()->categories_id));
            }    
            if(!empty($nsd_id)){
                $supplierForGndT->where($this->tableAlies.'_tenders.nsd_id','=',$nsd_id);
            }
            if(!empty($ten_title)){
                $supplierForGndT->where($this->tableAlies.'_tenders.id','=',$ten_title);
            }
            if(!empty($ten_number)){
                $supplierForGndT->where($this->tableAlies.'_tenders.id','=',$ten_number);
            }
            if(!empty($po_number)){
                $supplierForGndT->where($this->tableAlies.'_tenders.id','=',$po_number);
            }
            $supplierForGndT->orderBy($this->tableAlies.'_suppliers.id','ASC');
            $supplierForGndT = $supplierForGndT->limit($paginatorRange)->get();

            $preQuantity = $supplierForGndT->sum('quantity');

        }

// End add new ===========================================================================================================
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        
            $suppliersrep = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.id')
                ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=',$this->tableAlies.'_tenders.supplier_id')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=',$this->tableAlies.'_itemtotender.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select($this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_suppliers.id as supplier_id',$this->tableAlies.'_tenders.nsd_id',$this->tableAlies.'_tenders.po_number',$this->tableAlies.'_tenders.tender_title',$this->tableAlies.'_items.imc_number',$this->tableAlies.'_items.item_name',$this->tableAlies.'_items.item_deno',$this->tableAlies.'_itemtotender.quantity',$this->tableAlies.'_itemtotender.unit_price',$this->tableAlies.'_itemtotender.discount_price',$this->tableAlies.'_itemtotender.total','deno.name as deno_name');
            $suppliersrep->whereIn($this->tableAlies.'_tenders.nsd_id',$zonesRltdIds);
            if(!empty(Auth::user()->categories_id)){
                $suppliersrep->whereIn($this->tableAlies.'_tenders.tender_cat_id',explode(',', Auth::user()->categories_id));
            }
            if(!empty($nsd_id)){
                $suppliersrep->where($this->tableAlies.'_tenders.nsd_id','=',$nsd_id);
            }
            if(!empty($ten_title)){
                $suppliersrep->where($this->tableAlies.'_tenders.id','=',$ten_title);
            }
            if(!empty($ten_number)){
                $suppliersrep->where($this->tableAlies.'_tenders.id','=',$ten_number);
            }
            if(!empty($po_number)){
                $suppliersrep->where($this->tableAlies.'_tenders.id','=',$po_number);
            }
            $suppliersrep->orderBy($this->tableAlies.'_suppliers.id','ASC');
            $suppliersrep = $suppliersrep->paginate(50);


// End search ===============================================================

        $search_nsd_name = '';
        if(!empty($nsd_id)){
            $search_nsd_name = NsdName::find($nsd_id);;
        }
        $serchTenderName = '';
        if(!empty($ten_title)){
            $serchTenderName = \App\Tender::find($ten_title);;
        }
        $serchTenderNumber = '';
        if(!empty($ten_number)){
            $serchTenderNumber = \App\Tender::find($ten_number);;
        }
        $serchTenderPo = '';
        if(!empty($po_number)){
            $serchTenderPo = \App\Tender::find($po_number);;
        }


        return View::make('reports.tender-participates',compact('nsdNames','nsd_id','search_nsd_name','tenderList','ten_title','suppliersrep','ten_number','po_number','tender_numbers','po_numbers','preQuantity','serchTenderName','serchTenderNumber','serchTenderPo'));

    }

    public function catProSupplierList(Request $request){

        $this->tableAlies = \Session::get('zoneAlise');

        $cat_id   = Input::get('cat_id');
        $item_id  = Input::get('item_id');
        $nsd_id   = Input::get('nsd_id');


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

                    if(!empty(Auth::user()->categories_id)){
                        $userWiseCat = explode(',', Auth::user()->categories_id);
                        foreach ($userWiseCat as $uwc){
                            if(in_array($uwc, explode(',',$spl->supply_cat_id))){
                                $zonesRltdIdsss[] = $spl->id;
                            }
                        }
                    }else{
                        $zonesRltdIdsss[] = $spl->id;
                    }
                    //$zonesRltdIdsss[] = $spl->id;
                }
            }
        }

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
            $productsnames = \App\Item::all();
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

            $suppliers = Supplier::whereIn('id',$single_array)->whereIn('id',$zonesRltdIdsss)->paginate(50);

        }else{
            $suppliers = $suppliers->whereIn('id',$zonesRltdIdsss)->paginate(50);
        }

        $searchItemName = '';
        if(!empty($item_id)){
            $searchItemName = \App\Item::find($item_id);;
        }


        return View::make('reports.category-wise-supplier',compact('suppliercategories','productsnames','cat_id','item_id','suppliers','nsdNames','nsd_id','searchItemName'));

    }

    public function supplierReport(){

        $this->tableAlies = \Session::get('zoneAlise');

        $nsd_id = Input::get('nsd_id');
        $company_mobile = Input::get('company_mobile');
        $from   = Input::get('from');
        $to     = Input::get('to');

        if(!empty($from)){
            $from   = date('Y-m-d',strtotime(Input::get('from')));
        }
        if(!empty($to)){
            $to     = date('Y-m-d',strtotime(Input::get('to')));
        }


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

                        if(!empty(Auth::user()->categories_id)){
                            $userWiseCat = explode(',', Auth::user()->categories_id);
                            foreach ($userWiseCat as $uwc){
                                if(in_array($uwc, explode(',',$spl->supply_cat_id))){
                                    $zonesRltdIdsss[] = $spl->id;
                                }
                            }
                        }else{
                            $zonesRltdIdsss[] = $spl->id;
                        }
                        //$zonesRltdIdsss[] = $spl->id;
                    }
                }else{
                    if(in_array($rni, $zonesRltdIds)){

                        if(!empty(Auth::user()->categories_id)){
                            $userWiseCat = explode(',', Auth::user()->categories_id);
                            foreach ($userWiseCat as $uwc){
                                if(in_array($uwc, explode(',',$spl->supply_cat_id))){
                                    $zonesRltdIdsss[] = $spl->id;
                                }
                            }
                        }else{
                            $zonesRltdIdsss[] = $spl->id;
                        }
                        //$zonesRltdIdsss[] = $spl->id;
                    }
                }

            }
        }

        $suppliers = Supplier::where('status_id','=',1);

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
            if(!empty($from)){
                $suppliers->where(function($query) use ($from ){
                    $query->whereDate('date_of_enrollment','>=',$from);
                });
            }
            if(!empty($to)){
                $suppliers->where(function($query) use ($to){
                    $query->whereDate('date_of_enrollment','<=',$to);
                });
            }
        $suppliers = $suppliers->whereIn('id',$zonesRltdIdsss)->paginate(50);

        return View::make('reports.suppliers-nsd-wise')->with(compact('suppliers','nsdNames','nsd_id','company_mobile','from','to'));

    }


     // Budget code wise report ==============================================================================
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    public function budgetCodeWiseItem(){

        $this->tableAlies = \Session::get('zoneAlise');

        $nsd_id = Input::get('nsd_id');
        $item_id = Input::get('item_id');
        $from   = Input::get('from');
        $to     = Input::get('to');
        $budget_cd_id = Input::get('budget_cd_id');

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

        $itemList = \DB::table($this->tableAlies.'_tenders')->join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.id')
            ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=',$this->tableAlies.'_itemtotender.item_id')
            ->select($this->tableAlies.'_items.id',$this->tableAlies.'_items.item_name', $this->tableAlies.'_itemtotender.item_id')
            ->whereIn($this->tableAlies.'_tenders.nsd_id',$zonesRltdIds)->get();

        $itemList = $itemList->unique();


         // Add new ==============================================================================================================
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $paginatorRange = Input::get('page');
        if($paginatorRange == null || $paginatorRange == 1){
            $paginatorRange = 0;
        }else{
            $paginatorRange = $paginatorRange*50-50;
        }

        $preGrandTotal = 0;
        $preQuantity = 0;
        if($paginatorRange>0){

            $supplierForGndT = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.id')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=',$this->tableAlies.'_itemtotender.item_id')
                ->join('budget_codes','budget_codes.id','=',$this->tableAlies.'_items.budget_code')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select('budget_codes.code',$this->tableAlies.'_items.imc_number', $this->tableAlies.'_items.item_name', $this->tableAlies.'_items.item_deno', $this->tableAlies.'_items.budget_code', $this->tableAlies.'_itemtotender.quantity', $this->tableAlies.'_itemtotender.unit_price', $this->tableAlies.'_itemtotender.discount_price', $this->tableAlies.'_itemtotender.total', 'deno.name as deno_name');
            $supplierForGndT->whereIn($this->tableAlies.'_tenders.nsd_id',$zonesRltdIds);
            if(!empty(Auth::user()->categories_id)){
                $supplierForGndT->whereIn($this->tableAlies.'_tenders.tender_cat_id',explode(',', Auth::user()->categories_id));
            }    
            if(!empty($nsd_id)){
                $supplierForGndT->where($this->tableAlies.'_tenders.nsd_id','=',$nsd_id);
            }
            if(!empty($item_id)){
                $supplierForGndT->where($this->tableAlies.'_items.id','=',$item_id);
            }
            if(!empty($budget_cd_id)){
                $supplierForGndT->where($this->tableAlies.'_items.budget_code','=',$budget_cd_id);
            }
            if(!empty($from)){
                $supplierForGndT->where(function($query) use ($from ){
                    $query->whereDate($this->tableAlies.'_tenders.work_order_date','>=',$from);
                    $query->orWhereDate($this->tableAlies.'_tenders.tender_opening_date','>=',$from);
                });
            }
            if(!empty($to)){
                $supplierForGndT->where(function($query) use ($to){
                    $query->whereDate($this->tableAlies.'_tenders.work_order_date','<=',$to);
                    $query->orWhere($this->tableAlies.'_tenders.tender_opening_date','<=',$to);
                });
            }

            $supplierForGndT->orderBy('budget_codes.id','ASC');
            $supplierForGndT = $supplierForGndT->limit($paginatorRange)->get();

            $preGrandTotal = $supplierForGndT->sum('total');
            $preQuantity = $supplierForGndT->sum('quantity');

        }

// End add new ===========================================================================================================
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $suppliersrep = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.id')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=',$this->tableAlies.'_itemtotender.item_id')
                ->join('budget_codes','budget_codes.id','=',$this->tableAlies.'_items.budget_code')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->join('multiple_currency',$this->tableAlies.'_itemtotender.currency_name','=','multiple_currency.id')
                ->select($this->tableAlies.'_tenders.nsd_id','budget_codes.code',$this->tableAlies.'_items.imc_number', $this->tableAlies.'_items.item_name', $this->tableAlies.'_items.item_deno', $this->tableAlies.'_items.budget_code', $this->tableAlies.'_itemtotender.quantity', $this->tableAlies.'_itemtotender.unit_price', $this->tableAlies.'_itemtotender.discount_price', $this->tableAlies.'_itemtotender.total', 'deno.name as deno_name','multiple_currency.currency_name as curname');
            $suppliersrep->whereIn($this->tableAlies.'_tenders.nsd_id',$zonesRltdIds);
            if(!empty(Auth::user()->categories_id)){
                $suppliersrep->whereIn($this->tableAlies.'_tenders.tender_cat_id',explode(',', Auth::user()->categories_id));
            }    
            if(!empty($nsd_id)){
                $suppliersrep->where($this->tableAlies.'_tenders.nsd_id','=',$nsd_id);
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

            $suppliersrep->orderBy('budget_codes.id','ASC');
            $suppliersrep = $suppliersrep->paginate(50);


        $search_nsd_name = '';
        if(!empty($nsd_id)){
            $search_nsd_name = NsdName::find($nsd_id);;
        }
        $search_item_name = '';
        if(!empty($item_id)){
            $search_item_name = \App\Item::find($item_id);;
        }

        return View::make('reports.budget-colde-wise-item-list',compact('nsdNames','suppliersrep','nsd_id','from','to','search_nsd_name','search_item_name','itemList','item_id','budget_codes','budget_cd_id','preGrandTotal','preQuantity'));

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


 