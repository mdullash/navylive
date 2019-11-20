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
use App\Demand;
use App\ItemToDemand;
use App\GroupName;
use App\Supplier;
use App\Tender;
use App\Item;
use App\Deno;
use App\DemandToCollectionQuotation;
use App\DemandSuppllierToCollQuotToItem;
use App\DemandCrToInspection;
use PDF;
use App\TermsCondition;


class PoGenerationController extends Controller
{

    private $moduleId = 34;
    private $tableAlies;

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $demands = Demand::where('cst_supplier_select','=',1)->paginate(10);
        return View::make('lp-section.index')->with(compact('demands'));

    }

    public function poGenerateView($id=null){

        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

        //$demandId = $id;
        $demandToLprId = $explodes[0];
        $tenderId      = $explodes[1];

        $demandToLprInfo = \App\DemandToLpr::find($demandToLprId);
        $tenderInfo      = \App\Tender::find($tenderId);
        $tenderNumber    = Tender::find($tenderId)->value('tender_number');

        $nsdId = 1;
         if(!empty(Auth::user()->nsd_bsd)){
            $nsdId = Auth::user()->nsd_bsd;
         }
        $orgInfo  = \App\NsdName::find($nsdId);

        //$selectedSupplier  = DemandToCollectionQuotation::where('lpr_id','=',$demandToLprId)->where('tender_id','=',$tenderId)->where('winner','=',1)->first();

            $qyeryResutl = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName','item_to_demand.id as ItemId','demand_supplier_to_coll_qut_to_item.id as dmndtosupcotId')
                ->where('item_to_demand.lpr_id','=',$demandToLprId)
                ->where('item_to_demand.tender_no','=',$tenderId)
                ->where('demand_to_collection_quotation.lpr_id','=',$demandToLprId)
                ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                ->where('demand_to_collection_quotation.winner','=',1)
                ->get();

                //echo "<pre>"; print_r($qyeryResutl); exit;

            $maxDemandId = \App\ItemToTender::max('id');
            $maxId        = $maxDemandId+1;
            $currentYear  = date('Y');
            $year         = ( date('m') > 6) ? $currentYear.'-'.(date('Y') + 1) : (date('Y')-1).'-'.$currentYear;
            $poNumberCreate  = $maxId.'('.$year.')';   

            $allOrganiZation  = \App\NsdName::where('status_id','=',1)->get();

            $winnerSuppliers = \App\DemandToCollectionQuotation::where('winner','=',1)->where('tender_id','=',$tenderId)->whereNull('po_status')->get();

            $temrsConditon = \App\TermsCondition::where('status','=',1)->where('category_id','=',2)->get();
        
        return View::make('po-generation.create')->with(compact('demandToLprId','demandToLprInfo','qyeryResutl','poNumberCreate','tenderNumber','tenderId','tenderInfo','orgInfo','allOrganiZation','winnerSuppliers','temrsConditon'));

    }


    public function postPoGenerate(Request $request){
        $this->tableAlies = \Session::get('zoneAlise');

        $demandToLprId = $request->demandToLprId;
        $tenderId      = $request->tenderId;
        $demandToTenInfo = \App\DemandToTender::where('tender_id','=',$tenderId)->first();

        $demndToCollQutIds = $request->selected_supplier;
        $supplyTo          = $request->supply_to;

        $terms_con = array();

        if(!empty($request->term_con) && count($request->term_con) > 0){
            for($i=0; $i<count($request->term_con); $i++){
                if(!empty($request->term_con_text[$request->term_con[$i]])){
                    $terms_con[] = $request->term_con_text[$request->term_con[$i]];
                }
            }
        }

        $totalQuantity = \App\DemandSuppllierToCollQuotToItem::where('lpr_id','=',$demandToLprId)
                            ->where('select_as_winner','=',1)
                            ->whereIn('dmn_to_cal_qut_id',$demndToCollQutIds)
                            ->where('tender_id','=',$tenderId);
                            if(!empty($demandToTenInfo->head_ofc_apvl_status)){
                                $totalQuantity->where('itm_to_sup_nhq_app_status','=',1);
                                $totalQuantity = $totalQuantity->sum('itm_to_sup_nhq_app_qty');
                            }else{
                                $totalQuantity = $totalQuantity->sum('quoted_quantity');
                            }


        $podatas           = new \App\PoDatas();

//                            dd($request->all());

        $podatas->lpr_id                    = $demandToLprId;
        $podatas->tender_id                 = $tenderId;
        $podatas->dem_to_ten_id             = $demandToTenInfo->id;
        $podatas->dem_to_col_id             = !empty($demndToCollQutIds) ? implode(',', $demndToCollQutIds) : NULL;
        $podatas->tender_number             = $demandToTenInfo->tender_number;
        $podatas->top_date                  = date('Y-m-d',strtotime($request->top_date));
        $podatas->po_number                 = $request->po_number;
        $podatas->headquarters_letter_no    = $request->headquarters_letter_no;
        $podatas->import_duties             = $request->import_duties;
        $podatas->supply_to                 = !empty($supplyTo) ? $supplyTo : NULL;
        $podatas->selected_supplier         = !empty($demndToCollQutIds) ? implode(',', $demndToCollQutIds) : NULL;
	    $podatas->inclusser                 = !empty($request->inclusser) ? $request->inclusser : NULL;
	    $podatas->info                      = !empty($request->info) ? $request->info : NULL;
        $podatas->terms_conditions          = !empty($terms_con) ? implode('<br>', $terms_con) : NULL;
        $podatas->quantity                  = $totalQuantity;
        $podatas->save();

        $itemsToUpdate = array_map('current',\App\DemandSuppllierToCollQuotToItem::
            select('item_id')
            ->where('lpr_id','=',$demandToLprId)
            ->where('tender_id','=',$tenderId)
            ->where('select_as_winner','=',1)
            ->whereIn('dmn_to_cal_qut_id',$demndToCollQutIds)
            ->get()->toArray());
        if(!empty($itemsToUpdate)){
            $itemsToUpdate = array_unique($itemsToUpdate);
        }

        foreach($itemsToUpdate as $itu) {
            $itemToUpdate = \App\ItemToDemand::find($itu);
            $itemToUpdate->po_status        = 1;
            $itemToUpdate->po_generate_by   = Auth::user()->id;
            $itemToUpdate->po_generate_date = date('Y-m-d H:i:s');
            $itemToUpdate->current_status   = 11;
            $itemToUpdate->save();
        }

        foreach($demndToCollQutIds as $dcq) {
            $demToCollQutUpdate = \App\DemandToCollectionQuotation::find($dcq);
            $demToCollQutUpdate->po_status        = 1;
            $demToCollQutUpdate->po_created_by   = Auth::user()->id;
            $demToCollQutUpdate->po_created_date = date('Y-m-d H:i:s');
            $demToCollQutUpdate->save();
        }

        \DB::table('demand_to_tender')
            ->where('lpr_id', $demandToLprId)
            ->where('tender_id', $tenderId)
            ->update(['po_status' => 1, 'po_generate_by' =>  Auth::user()->id, 'po_generate_date' => date('Y-m-d H:i:s'), 'current_status' => 11]);

        \DB::table('demand_to_lpr')
            ->where('id', $demandToLprId)
            ->update(['po_status' => 1, 'po_generate_by' =>  Auth::user()->id, 'po_generate_date' => date('Y-m-d H:i:s'), 'current_status' => 11]);

        Session::flash('success', 'Data Updated Successfully');
        return redirect('po-generation-acc/1');

    }

    public function poCheckView($id=null){

        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

        //$demandId = $id;
        $podataId       = $explodes[0];
        $tenderId       = $explodes[1];

        $podataInfo     = \App\PoDatas::find($podataId);

        $demandToLprId   = $podataInfo->lpr_id;
        $demandToLprInfo = \App\DemandToLpr::find($podataInfo->lpr_id);
        $tenderInfo      = \App\Tender::find($tenderId);
        $tenderNumber    = Tender::find($tenderId)->value('tender_number');
        $demandToTenInfo = \App\DemandToTender::where('tender_id','=',$tenderId)->first();

        $nsdId = 1;
         if(!empty(Auth::user()->nsd_bsd)){
            $nsdId = Auth::user()->nsd_bsd;
         }
        $orgInfo  = \App\NsdName::find($nsdId);

            $allOrganiZation  = \App\NsdName::where('status_id','=',1)->get();

            $demtoolcon = explode(',', $podataInfo->dem_to_col_id);

            $winnerSuppliers = \App\DemandToCollectionQuotation::whereIn('id',$demtoolcon)->get();

            $temrsConditon = \App\TermsCondition::where('status','=',1)->get();
            $colIds = explode(',', $podataInfo->selected_supplier);

            foreach ($colIds as $value) {
                $qyeryResutl[$value] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName','item_to_demand.id as ItemId','demand_supplier_to_coll_qut_to_item.id as dmndtosupcotId')
                ->where('item_to_demand.lpr_id','=',$demandToLprId)
                ->where('demand_supplier_to_coll_qut_to_item.select_as_winner','=',1)
                ->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$value)
                ->where('demand_to_collection_quotation.lpr_id','=',$demandToLprId)
                ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                ->where('demand_to_collection_quotation.winner','=',1);
                if(!empty($demandToTenInfo->head_ofc_apvl_status)){
                    $qyeryResutl[$value]->where('demand_supplier_to_coll_qut_to_item.itm_to_sup_nhq_app_status','=',1);
                }
                $qyeryResutl[$value] = $qyeryResutl[$value]->get();
            }
        
        return View::make('po-generation.po-check-edit')->with(compact('demandToLprInfo','tenderNumber','tenderId','tenderInfo','orgInfo','allOrganiZation','winnerSuppliers','temrsConditon','podataId','podataInfo','demandToLprId','qyeryResutl','colIds'));

    }

    public function postPoCheckEdit(Request $request){
        $this->tableAlies = \Session::get('zoneAlise');

        $podataId       = $request->podataId;
        $tenderId       = $request->tenderId;
        $podataInfo     = \App\PoDatas::find($podataId);

        $demandToLprId   = $podataInfo->lpr_id;

        $demandToTenInfo = \App\DemandToTender::where('tender_id','=',$tenderId)->first();

        $demndToCollQutIds = $request->selected_supplier;
        $supplyTo          = $request->supply_to;

        $terms_con = array();

        if(!empty($request->term_con) && count($request->term_con) > 0){
            for($i=0; $i<count($request->term_con); $i++){
                if(!empty($request->term_con_text[$request->term_con[$i]])){
                    $terms_con[] = $request->term_con_text[$request->term_con[$i]];
                }
            }
        }

        $podatas           =\App\PoDatas::find($podataId);

        $podatas->lpr_id                    = $demandToLprId;
        $podatas->tender_id                 = $tenderId;
        $podatas->dem_to_ten_id             = $demandToTenInfo->id;
        $podatas->dem_to_col_id             = !empty($demndToCollQutIds) ? implode(',', $demndToCollQutIds) : NULL;
        $podatas->tender_number             = $demandToTenInfo->tender_number;
        $podatas->top_date                  = date('Y-m-d',strtotime($request->top_date));
        $podatas->po_number                 = $request->po_number;
        $podatas->headquarters_letter_no    = $request->headquarters_letter_no;
        $podatas->import_duties             = $request->import_duties;
        $podatas->supply_to                 = !empty($supplyTo) ? $supplyTo : NULL;
        $podatas->selected_supplier         = !empty($demndToCollQutIds) ? implode(',', $demndToCollQutIds) : NULL;
	    $podatas->inclusser                 = !empty($request->inclusser) ? $request->inclusser : NULL;
	    $podatas->info                      = !empty($request->info) ? $request->info : NULL;
        $podatas->terms_conditions          = !empty($terms_con) ? implode('<br>', $terms_con) : NULL;
        $podatas->po_check_status           = $request->status;
        $podatas->po_check_by               = Auth::user()->id;
        $podatas->po_check_date             = date('Y-m-d H:i:s');
        $podatas->save();
 
        $itemsToUpdate = array_map('current',\App\DemandSuppllierToCollQuotToItem::
            select('item_id')
            ->where('lpr_id','=',$demandToLprId)
            ->where('tender_id','=',$tenderId)
            ->where('select_as_winner','=',1)
            ->whereIn('dmn_to_cal_qut_id',$demndToCollQutIds)
            ->get()->toArray());
        if(!empty($itemsToUpdate)){
            $itemsToUpdate = array_unique($itemsToUpdate);
        }

        foreach($itemsToUpdate as $itu) {
            $itemToUpdate = \App\ItemToDemand::find($itu);
            $itemToUpdate->po_check_status           = $request->status;
            $itemToUpdate->po_check_by               = Auth::user()->id;
            $itemToUpdate->po_check_date             = date('Y-m-d H:i:s');
            $itemToUpdate->save();
        }

        foreach($demndToCollQutIds as $dcq) {
            $demToCollQutUpdate = \App\DemandToCollectionQuotation::find($dcq);
            $demToCollQutUpdate->po_check_status     = $request->status;
            $demToCollQutUpdate->po_check_by         = Auth::user()->id;
            $demToCollQutUpdate->po_check_date       = date('Y-m-d H:i:s');
            $demToCollQutUpdate->save();
        }

        \DB::table('demand_to_tender')
            ->where('lpr_id', $demandToLprId)
            ->where('tender_id', $tenderId)
            ->update(['po_check_status' => $request->status, 'po_check_by' =>  Auth::user()->id, 'po_check_date' => date('Y-m-d H:i:s')]);

        \DB::table('demand_to_lpr')
            ->where('id', $demandToLprId)
            ->update(['po_check_status' => $request->status, 'po_check_by' =>  Auth::user()->id, 'po_check_date' => date('Y-m-d H:i:s')]);

        Session::flash('success', 'Data Updated Successfully');
        return redirect('po-generation-acc/3');

    }

    public function poApproveView($id=null){

        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

        //$demandId = $id;
        $podataId       = $explodes[0];
        $tenderId       = $explodes[1];

        $podataInfo     = \App\PoDatas::find($podataId);

        $demandToLprId   = $podataInfo->lpr_id;
        $demandToLprInfo = \App\DemandToLpr::find($podataInfo->lpr_id);
        $tenderInfo      = \App\Tender::find($tenderId);
        $tenderNumber    = Tender::find($tenderId)->value('tender_number');
        $demandToTenInfo = \App\DemandToTender::where('tender_id','=',$tenderId)->first();

        $nsdId = 1;
         if(!empty(Auth::user()->nsd_bsd)){
            $nsdId = Auth::user()->nsd_bsd;
         }
        $orgInfo  = \App\NsdName::find($nsdId);

            $allOrganiZation  = \App\NsdName::where('status_id','=',1)->get();

            $demtoolcon = explode(',', $podataInfo->dem_to_col_id);

            $winnerSuppliers = \App\DemandToCollectionQuotation::whereIn('id',$demtoolcon)->get();

            $temrsConditon = \App\TermsCondition::where('status','=',1)->get();
            $colIds = explode(',', $podataInfo->selected_supplier);

            foreach ($colIds as $value) {
                $qyeryResutl[$value] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName','item_to_demand.id as ItemId','demand_supplier_to_coll_qut_to_item.id as dmndtosupcotId')
                ->where('item_to_demand.lpr_id','=',$demandToLprId)
                ->where('demand_supplier_to_coll_qut_to_item.select_as_winner','=',1)
                ->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$value)
                ->where('demand_to_collection_quotation.lpr_id','=',$demandToLprId)
                ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                ->where('demand_to_collection_quotation.winner','=',1);
                if(!empty($demandToTenInfo->head_ofc_apvl_status)){
                    $qyeryResutl[$value]->where('demand_supplier_to_coll_qut_to_item.itm_to_sup_nhq_app_status','=',1);
                }
                $qyeryResutl[$value] = $qyeryResutl[$value]->get();
            }
            
        
        return View::make('po-generation.po-approve-edit')->with(compact('demandToLprInfo','tenderNumber','tenderId','tenderInfo','orgInfo','allOrganiZation','winnerSuppliers','temrsConditon','podataId','podataInfo','demandToLprId','qyeryResutl','colIds'));

    }

    public function postApproveEdit(Request $request){
        $this->tableAlies = \Session::get('zoneAlise');

        $podataId       = $request->podataId;
        $tenderId       = $request->tenderId;
        $podataInfo     = \App\PoDatas::find($podataId);

        $demandToLprId   = $podataInfo->lpr_id;

        $demandToTenInfo = \App\DemandToTender::where('tender_id','=',$tenderId)->first();

        $demndToCollQutIds = $request->selected_supplier;
        $supplyTo          = $request->supply_to;

        $terms_con = array();

        if(!empty($request->term_con) && count($request->term_con) > 0){
            for($i=0; $i<count($request->term_con); $i++){
                if(!empty($request->term_con_text[$request->term_con[$i]])){
                    $terms_con[] = $request->term_con_text[$request->term_con[$i]];
                }
            }
        }

        $podatas           =\App\PoDatas::find($podataId);

        $podatas->lpr_id                    = $demandToLprId;
        $podatas->tender_id                 = $tenderId;
        $podatas->dem_to_ten_id             = $demandToTenInfo->id;
        $podatas->dem_to_col_id             = !empty($demndToCollQutIds) ? implode(',', $demndToCollQutIds) : NULL;
        $podatas->tender_number             = $demandToTenInfo->tender_number;
        $podatas->top_date                  = date('Y-m-d',strtotime($request->top_date));
        $podatas->po_number                 = $request->po_number;
        $podatas->headquarters_letter_no    = $request->headquarters_letter_no;
        $podatas->import_duties             = $request->import_duties;
        $podatas->supply_to                 = !empty($supplyTo) ? $supplyTo : NULL;
        $podatas->selected_supplier         = !empty($demndToCollQutIds) ? implode(',', $demndToCollQutIds) : NULL;
	    $podatas->inclusser                 = !empty($request->inclusser) ? $request->inclusser : NULL;
	    $podatas->info                      = !empty($request->info) ? $request->info : NULL;
        $podatas->terms_conditions          = !empty($terms_con) ? implode('<br>', $terms_con) : NULL;
        $podatas->po_approve_status         = $request->status;
        $podatas->po_approve_by             = Auth::user()->id;
        $podatas->po_approve_date           = date('Y-m-d H:i:s');
        $podatas->save();
 
        $itemsToUpdate = array_map('current',\App\DemandSuppllierToCollQuotToItem::
            select('item_id')
            ->where('lpr_id','=',$demandToLprId)
            ->where('tender_id','=',$tenderId)
            ->where('select_as_winner','=',1)
            ->whereIn('dmn_to_cal_qut_id',$demndToCollQutIds)
            ->get()->toArray());
        if(!empty($itemsToUpdate)){
            $itemsToUpdate = array_unique($itemsToUpdate);
        }

        foreach($itemsToUpdate as $itu) {
            $itemToUpdate = \App\ItemToDemand::find($itu);
            $itemToUpdate->po_approve_status         = $request->status;
            $itemToUpdate->po_approve_by             = Auth::user()->id;
            $itemToUpdate->po_approve_date           = date('Y-m-d H:i:s');
            $itemToUpdate->save();
        }

        foreach($demndToCollQutIds as $dcq) {
            $demToCollQutUpdate = \App\DemandToCollectionQuotation::find($dcq);
            $demToCollQutUpdate->po_approve_status         = $request->status;
            $demToCollQutUpdate->po_approve_by             = Auth::user()->id;
            $demToCollQutUpdate->po_approve_date           = date('Y-m-d H:i:s');
            $demToCollQutUpdate->save();
        }

        \DB::table('demand_to_tender')
            ->where('lpr_id', $demandToLprId)
            ->where('tender_id', $tenderId)
            ->update(['po_approve_status' => $request->status, 'po_approve_by' =>  Auth::user()->id, 'po_approve_date' => date('Y-m-d H:i:s')]);

        \DB::table('demand_to_lpr')
            ->where('id', $demandToLprId)
            ->update(['po_approve_status' => $request->status, 'po_approve_by' =>  Auth::user()->id, 'po_approve_date' => date('Y-m-d H:i:s')]);

        if(!empty($demndToCollQutIds) && $request->status==1){
            foreach ($demndToCollQutIds as $key => $supToColCot) {

                $demandSupplierToCollection = DemandToCollectionQuotation::find($supToColCot);
                $tenderForColneExi = Tender::find($tenderId);

                $tenderForColne = new Tender();

                $tenderForColne->demand_no = $tenderForColneExi->demand_no;
                $tenderForColne->lpr_id     = $tenderForColneExi->lpr_id;
                $tenderForColne->po_number = $tenderForColneExi->po_number;
                $tenderForColne->tender_title = $tenderForColneExi->tender_title;
                $tenderForColne->tender_number = $tenderForColneExi->tender_number;
                $tenderForColne->tender_description = $tenderForColneExi->tender_description;
                $tenderForColne->tender_opening_date = $tenderForColneExi->tender_opening_date;
                $tenderForColne->supplier_id = $demandSupplierToCollection->supplier_name;
                $tenderForColne->work_order_date = $tenderForColneExi->work_order_date;
                $tenderForColne->date_line = $tenderForColneExi->date_line;
                $tenderForColne->delivery_date = $tenderForColneExi->delivery_date;
                $tenderForColne->imc_number = $tenderForColneExi->imc_number;
                $tenderForColne->tender_cat_id = $tenderForColneExi->tender_cat_id;
                $tenderForColne->nsd_id = $tenderForColneExi->nsd_id;
                $tenderForColne->other_info_about_tender = $tenderForColneExi->other_info_about_tender;
                $tenderForColne->specification      = NULL;
                $tenderForColne->specification_doc  = NULL;
                $tenderForColne->notice             = NULL;
                $tenderForColne->open_tender = $tenderForColneExi->open_tender;
                $tenderForColne->approval_letter_number = $tenderForColneExi->approval_letter_number;
                $tenderForColne->approval_letter_date = $tenderForColneExi->approval_letter_date;
                $tenderForColne->purchase_type = $tenderForColneExi->purchase_type;
                $tenderForColne->tender_type = $tenderForColneExi->tender_type;
                $tenderForColne->tender_nature = $tenderForColneExi->tender_nature;
                $tenderForColne->ref_tender_id = $tenderForColneExi->ref_tender_id;
                $tenderForColne->tender_priority = $tenderForColneExi->tender_priority;
                $tenderForColne->letter_body = $tenderForColneExi->letter_body;
                $tenderForColne->remarks = $tenderForColneExi->remarks;
                $tenderForColne->time_extension_upto = $tenderForColneExi->time_extension_upto;
                $tenderForColne->valid_date_from = $tenderForColneExi->valid_date_from;
                $tenderForColne->valid_date_to = $tenderForColneExi->valid_date_to;
                $tenderForColne->extend_date_to = $tenderForColneExi->extend_date_to;
                $tenderForColne->reference = $tenderForColneExi->reference;
                $tenderForColne->invitation_for = $tenderForColneExi->invitation_for;
                $tenderForColne->date = $tenderForColneExi->date;
                $tenderForColne->development_partners = $tenderForColneExi->development_partners;
                $tenderForColne->proj_prog_code = $tenderForColneExi->proj_prog_code;
                $tenderForColne->tender_package_no = $tenderForColneExi->tender_package_no;
                $tenderForColne->tender_package_name = $tenderForColneExi->tender_package_name;
                $tenderForColne->pre_tender_meeting = $tenderForColneExi->pre_tender_meeting;
                $tenderForColne->eligibility_of_tender = $tenderForColneExi->eligibility_of_tender;
                $tenderForColne->name_of_offi_invit_ten = $tenderForColneExi->name_of_offi_invit_ten;
                $tenderForColne->desg_of_offi_invit_ten = $tenderForColneExi->desg_of_offi_invit_ten;
                $tenderForColne->nhq_ltr_no = $tenderForColneExi->nhq_ltr_no;
                $tenderForColne->reference_date = $tenderForColneExi->reference_date;
                $tenderForColne->location = $tenderForColneExi->location;
                $tenderForColne->tender_terms_conditions = $tenderForColneExi->tender_terms_conditions;
                $tenderForColne->number_of_lot_item = $tenderForColneExi->number_of_lot_item;
                $tenderForColne->status_id = 2;
                $tenderForColne->created_by = $tenderForColneExi->created_by;
                $tenderForColne->updated_by = $tenderForColneExi->updated_by;
                $tenderForColne->created_at = $tenderForColneExi->created_at;
                $tenderForColne->updated_at = $tenderForColneExi->updated_at;

                if($tenderForColne->save()){
                    $tenderForClUp = \App\Tender::find($tenderForColne->id);
                    $tenderForClUp->all_org_tender_id = $tenderForColne->id;
                    $tenderForClUp->save();
                }

                $itemsUnderThisSupplier = DemandSuppllierToCollQuotToItem::
                                        where('dmn_to_cal_qut_id','=',$supToColCot)
                                        ->where('select_as_winner','=',1);
                                        if(!empty($demandToTenInfo->head_ofc_apvl_status)){
                                            $itemsUnderThisSupplier->where('itm_to_sup_nhq_app_status','=',1);
                                        }
                $itemsUnderThisSupplier = $itemsUnderThisSupplier->get();

                if(count($itemsUnderThisSupplier)>0){

                    foreach ($itemsUnderThisSupplier as $val) {
                        
                        $itemtotender = new \App\ItemToTender();

                        $itemtotender->tender_id = $tenderForColne->id;
                        $itemtotender->item_id   = $val->real_item_id;
                        if(!empty($demandToTenInfo->head_ofc_apvl_status)){
                            $itemtotender->quantity  = $val->itm_to_sup_nhq_app_qty;
                        }else{
                            if($val->select_alternavtive_offer == 1){
                                $itemtotender->quantity  = $val->alternative_quoted_quantity;
                            }else{
                                $itemtotender->quantity  = $val->quoted_quantity;
                            }
                        }
                        
                        if($val->select_alternavtive_offer == 1){
                            $itemtotender->unit_price = $val->alternative_unit_price;
                            $itemtotender->unit_price_in_bdt = $val->unit_price;
                        }else{
                            $itemtotender->unit_price = $val->unit_price;
                            $itemtotender->unit_price_in_bdt = $val->unit_price;
                        }

                        $itemtotender->currency_name = 1;
                        $itemtotender->conversion = 1;
                        $itemtotender->discount_price = $val->discount_amount+$val->final_doscount_amount;
                        $itemtotender->discount_price_in_bdt = $val->discount_amount+$val->final_doscount_amount;
                        
                        if($val->select_alternavtive_offer == 1){
                            $itemtotender->total = $val->alternative_total_price;
                        }else{
                            $itemtotender->total = $val->total_price;
                        }
                        
                        if($itemtotender->save()){
                            $itemtotenderUpA = \App\ItemToTender::find($itemtotender->id);
                            $itemtotenderUpA->all_org_itmtotender_id = $itemtotender->id;
                            $itemtotenderUpA->save();
                        }

                    }
                    
                }

            }
        }

        Session::flash('success', 'Data Updated Successfully');
        return redirect('po-generation-acc/4');

    }

    public function printPoGeneration($id){
        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

        //$demandId = $id;
        $podataId       = $explodes[0];
        $tenderId       = $explodes[1];

        $podataInfo     = \App\PoDatas::find($podataId);

        $demandToLprId   = $podataInfo->lpr_id;
        $demandToLprInfo = \App\DemandToLpr::find($podataInfo->lpr_id);
        $tenderInfo      = \App\Tender::find($tenderId);
        $tenderNumber    = Tender::find($tenderId)->value('tender_number');
        $demandToTenInfo = \App\DemandToTender::where('tender_id','=',$tenderId)->first();

        $nsdId = 1;
         if(!empty(Auth::user()->nsd_bsd)){
            $nsdId = Auth::user()->nsd_bsd;
         }
        $orgInfo  = \App\NsdName::find($nsdId);

        $dem_to_col_ids   = explode(',', $podataInfo->dem_to_col_id);
        $demandToCollQut  = \App\DemandToCollectionQuotation::whereIn('id',$dem_to_col_ids)->get();

        $selectedSupItemInfo = array();
        foreach ($dem_to_col_ids as $key => $dm_col_id) {
            $selectedSupItemInfo[$dm_col_id] = DemandSuppllierToCollQuotToItem::
                                    join('item_to_demand', 'demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                                    ->join('deno', 'item_to_demand.deno_id', '=', 'deno.id')
                                    ->join($this->tableAlies.'_items', 'item_to_demand.item_id', '=', $this->tableAlies.'_items.id')
                                    ->select('demand_supplier_to_coll_qut_to_item.*','deno.name as deno_name', $this->tableAlies.'_items.item_name as item_item_name', $this->tableAlies.'_items.model_number as item_model_number')
                                    ->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$dm_col_id)
                                    ->where('demand_supplier_to_coll_qut_to_item.select_as_winner','=',1);
                                    if(!empty($demandToTenInfo->head_ofc_apvl_status)){
                                        $selectedSupItemInfo[$dm_col_id]->where('demand_supplier_to_coll_qut_to_item.itm_to_sup_nhq_app_status','=',1);
                                    }
            $selectedSupItemInfo[$dm_col_id] = $selectedSupItemInfo[$dm_col_id]->get();
        }

        $tenderData = [
                    'podataId' => $podataId,
                    'tenderId' => $tenderId,
                    'podataInfo' => $podataInfo,
                    'demandToLprId' => $demandToLprId,
                    'demandToLprInfo' => $demandToLprInfo,
                    'tenderInfo' => $tenderInfo,
                    'tenderNumber' => $tenderNumber,
                    'demandToTenInfo' => $demandToTenInfo,
                    'orgInfo' => $orgInfo,
                    'selectedSupItemInfo' => $selectedSupItemInfo,
                    'demandToCollQut' => $demandToCollQut
                ];

        $pdf= PDF::loadView('po-generation.po-print-generate-pdf',$tenderData,[],['format' => [215.9, 342.9]]);
        return $pdf->stream('cst-view.pdf');

        //return View::make('po-generation.po-print-generate-pdf')->with(compact('podataId','tenderId','podataInfo','demandToLprId','demandToLprInfo','tenderInfo','tenderNumber','demandToTenInfo','orgInfo','selectedSupItemInfo','demandToCollQut'));

    }
    

    public function crSection($id){

        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

        $poDtsId    = $explodes[0];
        $tenderId   = $explodes[1];
        $seagMent   = $explodes[2];
        $poDatasInfo= \App\PoDatas::find($poDtsId);
        $lprId      = $poDatasInfo->lpr_id;

        $demandToTenInfo = \App\DemandToTender::where('tender_id','=',$tenderId)->first();

        $demandToColIds = explode(',',$poDatasInfo->dem_to_col_id);

        $selectedSupplier  = DemandToCollectionQuotation::where('lpr_id','=',$lprId)->where('tender_id','=',$tenderId)->where('winner','=',1)->whereIn('id',$demandToColIds)->get();

        $qyeryResutl = array();

        if($seagMent==1){
            foreach($selectedSupplier as $ssup) {
               $qyeryResutl[$ssup->id] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                    ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                    ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                    ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName','item_to_demand.id as ItemId','demand_supplier_to_coll_qut_to_item.id as dmndtosupcotId')
                    ->where('item_to_demand.lpr_id','=',$lprId)
                    ->where('item_to_demand.tender_no','=',$tenderId)
                    ->where('demand_to_collection_quotation.lpr_id','=',$lprId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->where('demand_to_collection_quotation.winner','=',1)
                    ->where('demand_supplier_to_coll_qut_to_item.select_as_winner','=',1)
                    ->where('demand_supplier_to_coll_qut_to_item.select_as_winner','=',1)
                    ->where('demand_to_collection_quotation.id','=',$ssup->id);
                    if(!empty($demandToTenInfo->head_ofc_apvl_status)){
                        $qyeryResutl[$ssup->id]->where('demand_supplier_to_coll_qut_to_item.itm_to_sup_nhq_app_status','=',1);
                        $qyeryResutl[$ssup->id]->where(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.total_approved, DECIMAL)"), '<',\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.itm_to_sup_nhq_app_qty, DECIMAL)"));
                    }else{
                        $qyeryResutl[$ssup->id]->where(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.total_approved, DECIMAL)"), '<',\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.quoted_quantity, DECIMAL)"));
                    }
                $qyeryResutl[$ssup->id] = $qyeryResutl[$ssup->id]->get();
            }
        }
        if($seagMent==2){
            foreach($selectedSupplier as $ssup) {
               $qyeryResutl[$ssup->id] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                    ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                    ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                    ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName','item_to_demand.id as ItemId','demand_supplier_to_coll_qut_to_item.id as dmndtosupcotId')
                    ->where('item_to_demand.lpr_id','=',$lprId)
                    ->where('item_to_demand.tender_no','=',$tenderId)
                    ->where('demand_to_collection_quotation.lpr_id','=',$lprId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->where('demand_to_collection_quotation.winner','=',1)
                    ->where('demand_supplier_to_coll_qut_to_item.select_as_winner','=',1)
                    ->where('demand_supplier_to_coll_qut_to_item.select_as_winner','=',1)
                    ->where('demand_to_collection_quotation.id','=',$ssup->id);
                    if(!empty($demandToTenInfo->head_ofc_apvl_status)){
                        $qyeryResutl[$ssup->id]->where('demand_supplier_to_coll_qut_to_item.itm_to_sup_nhq_app_status','=',1);
                        $qyeryResutl[$ssup->id]->where(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.total_approved, DECIMAL)"), '>=',\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.itm_to_sup_nhq_app_qty, DECIMAL)"));
                    }else{
                        $qyeryResutl[$ssup->id]->where(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.total_approved, DECIMAL)"), '>=',\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.quoted_quantity, DECIMAL)"));
                    }
                $qyeryResutl[$ssup->id] = $qyeryResutl[$ssup->id]->get();
            }
        }
            
        $demandPoToCrInfo = \App\DemandPoToCr::where('po_id','=',$poDtsId)->orderBy('id','desc')->first();

        $terms_conditions = TermsCondition::where('category_id','=',3)->get();

        return View::make('cr-view.cr-view')->with(compact('demandId','selectedSupplier','demandInfo','qyeryResutl','tenderId','demandToTenInfo','lprId','poDtsId','demandPoToCrInfo','poDatasInfo','seagMent','terms_conditions'));

    }

    public function crViewPost(Request $request){
        $po_data_id = $request->poDtsId;
        $tenderId   = $request->tenderId;
        $totalAppQty     = 0;
        $dmndtosupcotIds = array();
        if(isset($request->dmndtosupcotId)){

            $dmndtosupcotIds = implode(',',$request->dmndtosupcotId);

            for($i=0; count($request->dmndtosupcotId)>$i; $i++){

                $dmn_to_sup_col_qut = DemandSuppllierToCollQuotToItem::find($request->dmndtosupcotId[$i]);

                $itemToDemtblUp = \App\ItemToDemand::find($dmn_to_sup_col_qut->item_id);
                $itemToDemtblUp->cr_status        = 1;
                $itemToDemtblUp->cr_check_by   = Auth::user()->id;
                $itemToDemtblUp->cr_check_date = date('Y-m-d H:i:s');
                $itemToDemtblUp->save();

                $updateVal      = $dmn_to_sup_col_qut->cr_receive_qty;
                $currInspSta    = $dmn_to_sup_col_qut->inspection_sta;

                //if( empty($currInspSta) || $currInspSta==2 ){
                    $updateVal = $request->cr_receive_qty[$i];
                    // $updateVal = $request->quantity[$request->dmndtosupcotId[$i]][0];
                //}

                //if( empty($currInspSta) || $currInspSta==2){
                    $dmn_to_sup_col_qut->inspection_sta = 4;
                //}

                $dmn_to_sup_col_qut->cr_receive_qty = $updateVal;
                $dmn_to_sup_col_qut->save();

            }

            $createDemandPoToCr = new \App\DemandPoToCr();
            $createDemandPoToCr->po_id              = $po_data_id;
            $createDemandPoToCr->item_receive_date  = date("y-m-d",strtotime($request->item_receive_date));
            $createDemandPoToCr->cr_number          = $request->cr_number;
            $createDemandPoToCr->date               = date("y-m-d",strtotime($request->date));
	        $createDemandPoToCr->info          = !empty($request->info) ? $request->info : NULL;
            $createDemandPoToCr->save();

            $poDatasUp = \App\PoDatas::find($po_data_id);
            $poDatasUp->cr_qty_pending = array_sum($request->cr_receive_qty);
            $poDatasUp->cr_status = 1;
            $poDatasUp->save();

            // $demandUp = Demand::find($request->demand_id);
            // $demandUp->cr_status        = 1;
            // $demandUp->cr_check_by   = Auth::user()->id;
            // $demandUp->cr_check_date = date('Y-m-d H:i:s');
            // $demandUp->save();

            // \DB::table('item_to_demand')
            //     ->where('demand_id', $demand_id)
            //     ->where('tender_no', $tenderId)
            //     ->update(['cr_status' => 1, 'cr_check_by' =>  Auth::user()->id, 'cr_check_date' => date('Y-m-d H:i:s')]);

            // \DB::table('demand_to_tender')
            //     ->where('demand_id', $demand_id)
            //     ->where('tender_id', $tenderId)
            //     ->update(['cr_status' => 1, 'cr_check_by' =>  Auth::user()->id, 'cr_check_date' => date('Y-m-d H:i:s')]);

            return redirect::to('cr-pdf-print/'.$po_data_id.'&'.$tenderId.'&'.$dmndtosupcotIds.'&'.$createDemandPoToCr->id);
            //Session::flash('success', 'Data cupdated successfully');
                //return redirect('cr-section/'.$po_data_id.'&'.$tenderId);


        }else{ 
            Session::flash('error', 'Data could not updated');
                return redirect('cr-section/'.$po_data_id.'&'.$tenderId.'&1');
        }

        
    }

    public function crPdfPrint($datas){
        $this->tableAlies = \Session::get('zoneAlise');
        
        $nsdId = 1;
         if(!empty(Auth::user()->nsd_bsd)){
            $nsdId = Auth::user()->nsd_bsd;
         }
        $orgInfo  = \App\NsdName::find($nsdId);

        $explode = explode('&', $datas);

        $poDataId       = $explode[0];
        $tenderId       = $explode[1];
        $itemIds        = explode(',',$explode[2]);
        $lastPoToCrId   = $explode[3];

        $crNumberInfo = \App\DemandPoToCr::find($lastPoToCrId);
        $poDatasInfo  = \App\PoDatas::find($poDataId);

        $qyeryResutl = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
            ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
            ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
            ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
            ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
            ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName','item_to_demand.id as ItemId','demand_supplier_to_coll_qut_to_item.id as dmndtosupcotId')
            ->whereIn('demand_supplier_to_coll_qut_to_item.id',$itemIds)
            ->get();

        $tenderData = [
                    'qyeryResutl' => $qyeryResutl,
                    'poDataId' => $poDataId,
                    'crNumberInfo' => $crNumberInfo,
                    'orgInfo' => $orgInfo,
                    'poDatasInfo' => $poDatasInfo
                ];

        $pdf= PDF::loadView('cr-view.cr-pdf',$tenderData,[],['format' => [215.9, 342.9]]);
        return $pdf->stream('cr-view.pdf');   
        

    }


    public function inspectionSection($id=null){

        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

        $poDtsId    = $explodes[0];
        $tenderId   = $explodes[1];
        $seagMent   = $explodes[2];
        $poDatasInfo= \App\PoDatas::find($poDtsId);
        $lprId      = $poDatasInfo->lpr_id;

        $demandToTenInfo = \App\DemandToTender::where('tender_id','=',$tenderId)->first();

        $demandToColIds = explode(',',$poDatasInfo->dem_to_col_id);

        $selectedSupplier  = DemandToCollectionQuotation::where('lpr_id','=',$lprId)->where('tender_id','=',$tenderId)->where('winner','=',1)->whereIn('id',$demandToColIds)->get();

        $qyeryResutl = array();

        if($seagMent==1){
            foreach($selectedSupplier as $ssup) {
               $qyeryResutl[$ssup->id] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                    ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                    ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                    ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName','item_to_demand.id as ItemId','demand_supplier_to_coll_qut_to_item.id as dmndtosupcotId')
                    ->where('item_to_demand.lpr_id','=',$lprId)
                    ->where('item_to_demand.tender_no','=',$tenderId)
                    ->where('demand_to_collection_quotation.lpr_id','=',$lprId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->where('demand_to_collection_quotation.winner','=',1)
                    ->where('demand_supplier_to_coll_qut_to_item.select_as_winner','=',1)
                    ->where('demand_supplier_to_coll_qut_to_item.select_as_winner','=',1)
                    ->where('demand_supplier_to_coll_qut_to_item.cr_receive_qty','!=','')
                    ->where('demand_to_collection_quotation.id','=',$ssup->id);
                    if(!empty($demandToTenInfo->head_ofc_apvl_status)){
                        $qyeryResutl[$ssup->id]->where('demand_supplier_to_coll_qut_to_item.itm_to_sup_nhq_app_status','=',1);
                        $qyeryResutl[$ssup->id]->where(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.total_approved, DECIMAL)"), '<',\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.itm_to_sup_nhq_app_qty, DECIMAL)"));
                    }else{
                        $qyeryResutl[$ssup->id]->where(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.total_approved, DECIMAL)"), '<',\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.quoted_quantity, DECIMAL)"));
                    }
                $qyeryResutl[$ssup->id] = $qyeryResutl[$ssup->id]->get();
            }
        }
        if($seagMent==2){
            foreach($selectedSupplier as $ssup) {
               $qyeryResutl[$ssup->id] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                    ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                    ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                    ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName','item_to_demand.id as ItemId','demand_supplier_to_coll_qut_to_item.id as dmndtosupcotId')
                    ->where('item_to_demand.lpr_id','=',$lprId)
                    ->where('item_to_demand.tender_no','=',$tenderId)
                    ->where('demand_to_collection_quotation.lpr_id','=',$lprId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->where('demand_to_collection_quotation.winner','=',1)
                    ->where('demand_supplier_to_coll_qut_to_item.select_as_winner','=',1)
                    ->where('demand_supplier_to_coll_qut_to_item.select_as_winner','=',1)
                    ->where('demand_to_collection_quotation.id','=',$ssup->id);
                    if(!empty($demandToTenInfo->head_ofc_apvl_status)){
                        $qyeryResutl[$ssup->id]->where('demand_supplier_to_coll_qut_to_item.itm_to_sup_nhq_app_status','=',1);
                        $qyeryResutl[$ssup->id]->where(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.total_approved, DECIMAL)"), '>=',\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.itm_to_sup_nhq_app_qty, DECIMAL)"));
                    }else{
                        $qyeryResutl[$ssup->id]->where(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.total_approved, DECIMAL)"), '>=',\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.quoted_quantity, DECIMAL)"));
                    }
                $qyeryResutl[$ssup->id] = $qyeryResutl[$ssup->id]->get();
            }
        }
            
        $demandPoToCrInfo = \App\DemandPoToCr::where('po_id','=',$poDtsId)->orderBy('id','desc')->first();

        return View::make('inspection-view.inspection-view')->with(compact('demandId','selectedSupplier','demandInfo','qyeryResutl','tenderId','demandToTenInfo','lprId','poDtsId','demandPoToCrInfo','poDatasInfo','seagMent'));

    }

    public function postInspection(Request $request){
        $lpr_id   = $request->poDtsId;
        $tenderId = $request->tenderId;

        $maxId = DemandCrToInspection::max('print_seq')+1;
        $inspictedQty = 0;
        if(isset($request->dmndtosupcotId) && count($request->dmndtosupcotId)>0){
            foreach ($request->dmndtosupcotId as $key => $val) {
                # code...
            //}
            //for($i=0; count($request->dmndtosupcotId)>$i; $i++){
                $dmn_to_sup_col_qut = DemandSuppllierToCollQuotToItem::find($key);

                $itemToDemtblUp = \App\ItemToDemand::find($dmn_to_sup_col_qut->item_id);
                $itemToDemtblUp->inspection_status   = 1;
                $itemToDemtblUp->inspection_by       = Auth::user()->id;
                $itemToDemtblUp->inspection_date     = date('Y-m-d H:i:s');
                $itemToDemtblUp->save();

                $insPectStatus = '';
                if(isset($key)){
                    $insPectStatus = $val;
                }

                $curValVal      = $dmn_to_sup_col_qut->cr_receive_qty;
                $presentInspSta = $dmn_to_sup_col_qut->inspection_sta;
                $totalAppCurr   = $dmn_to_sup_col_qut->total_approved;
                $totalApproved  = '';

                if($insPectStatus==1){
                    if(empty($totalAppCurr)){
                        $totalApproved = $curValVal;
                        $dmn_to_sup_col_qut->total_approved    = $totalApproved;
                    }else{
                        $totalApproved = ($totalAppCurr+$curValVal);

                        $dmn_to_sup_col_qut->cr_receive_qty    = NULL;
                        $dmn_to_sup_col_qut->total_approved    = $totalApproved;
                    }

                }

                $dmn_to_sup_col_qut->cr_receive_qty    = NULL;
                if($insPectStatus==3){
                    $dmn_to_sup_col_qut->price_deduction    =$request->pr[$key][0];
                }
                $dmn_to_sup_col_qut->last_inspiction_approve = $curValVal;
                $dmn_to_sup_col_qut->inspection_sta        = $insPectStatus;
                $dmn_to_sup_col_qut->inspection_comment    = $request->inspection_comment[$key][0];
                $dmn_to_sup_col_qut->save();

                if($insPectStatus==1 || $insPectStatus==3){
                    $inspictedQty += $request->quantity[$key][0];
                    $approveQtyTbl = new DemandCrToInspection();

                    $approveQtyTbl->dmd_sup_to_coll_qut_to_itm_id    = $key;
                    $approveQtyTbl->lpr_id                           = $lpr_id;
                    $approveQtyTbl->tender_id                        = $tenderId;
                    $approveQtyTbl->approve_qty                      = $curValVal;
                    $approveQtyTbl->inspection_com                   = $request->inspection_comment[$key][0];
                    $approveQtyTbl->inspection_date                  = date('Y-m-d',strtotime($request->inspection_date));
                    $approveQtyTbl->approve_date                     = date('Y-m-d H:i:s');
                    if($insPectStatus==3){
                        $approveQtyTbl->price_deduction              = $request->pr[$key][0];
                    }
                    $approveQtyTbl->print_seq                        = $maxId;
                    $approveQtyTbl->save();
                }
                

                
            }

            $updatePoDts = \App\PoDatas::find($lpr_id);
            $updatePoDts->inspection_qty = $inspictedQty+$updatePoDts->inspection_qty;
            $updatePoDts->cr_status      = NULL;
            $updatePoDts->cr_qty_pending = NULL;
            $updatePoDts->save();
            // $demandUp = Demand::find($request->demand_id);
            // $demandUp->inspection_status  = 1;
            // $demandUp->inspection_by      = Auth::user()->id;
            // $demandUp->inspection_date    = date('Y-m-d H:i:s');
            // $demandUp->save();

            // \DB::table('item_to_demand')
            //     ->where('demand_id', $demand_id)
            //     ->where('tender_no', $tenderId)
            //     ->update(['inspection_status' => 1, 'inspection_by' =>  Auth::user()->id, 'inspection_date' => date('Y-m-d H:i:s')]);

            // \DB::table('demand_to_tender')
            //     ->where('demand_id', $demand_id)
            //     ->where('tender_id', $tenderId)
            //     ->update(['inspection_status' => 1, 'inspection_by' =>  Auth::user()->id, 'inspection_date' => date('Y-m-d H:i:s')]);


            Session::flash('success', 'Data updated successfully');
                // return redirect('inspection-section/'.$request->demand_id.'&'.$tenderId);
            return redirect('inspection-view-acc/1');

        }else{
            Session::flash('error', 'Data could not updated');
                return redirect('inspection-section/'.$request->poDtsId.'&'.$tenderId.'&1');
        }
    }

    public function v44voucherPdfView($id){

        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);
//      $demandId = $id;
        $demandId = $explodes[0];
        $tenderId = $explodes[1];

        $inspectedItems = DemandCrToInspection::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.id', '=', 'demand_cr_to_inspection.dmd_sup_to_coll_qut_to_itm_id')
            ->join('item_to_demand','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
            ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
            ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*', 'demand_cr_to_inspection.*')
            ->where('demand_supplier_to_coll_qut_to_item.demand_id','=',$demandId)
            ->where('demand_supplier_to_coll_qut_to_item.tender_id','=',$tenderId)
            ->get();

            //echo "<pre>"; print_r($inspectedItems); exit;

        $pdf = PDF::loadView('po-generation.v44voucher-pdf',compact('inspectedItems'));
        return $pdf->stream('v44voucher-pdf.pdf');

    }

    public function winnerWiseItems(Request $request){
        $this->tableAlies = \Session::get('zoneAlise');

        $supplerIds = $request->supplierIds;
        $tender_id  = $request->tender_id;
        $lpr_id     = $request->lpr_id;
        $demandToTenInfo = \App\DemandToTender::where('tender_id','=',$tender_id)->first();

        $returnval  = "";

        foreach ($supplerIds as $vsl) {
            $supplierInfo = \App\DemandToCollectionQuotation::find($vsl);

            $qyeryResutl = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName','item_to_demand.id as ItemId','demand_supplier_to_coll_qut_to_item.id as dmndtosupcotId')
                ->where('item_to_demand.lpr_id','=',$lpr_id)
                ->where('demand_supplier_to_coll_qut_to_item.select_as_winner','=',1)
                ->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$vsl)
                ->where('demand_to_collection_quotation.lpr_id','=',$lpr_id)
                ->where('demand_to_collection_quotation.tender_id','=',$tender_id)
                ->where('demand_to_collection_quotation.winner','=',1);
                if(!empty($demandToTenInfo->head_ofc_apvl_status)){
                    $qyeryResutl->where('demand_supplier_to_coll_qut_to_item.itm_to_sup_nhq_app_status','=',1);
                }
                $qyeryResutl = $qyeryResutl->get();

                $returnval .= "<div><b>".$supplierInfo->suppliernametext."<b></div>";
                $returnval .= "<table><thead>";
                $returnval .= " <tr>
                    <td style='text-align: center;vertical-align: middle;'>Ser</td>
                    <td style='text-align: center;vertical-align: middle;'>Description</td>
                    <td style='text-align: center;vertical-align: middle;'>Deno</td>
                    <td style='text-align: center;vertical-align: middle;'>Qty</td>
                    <td style='text-align: center;vertical-align: middle;'>Unit Price(TK)</td>
                    <td style='text-align: center;vertical-align: middle;'>T/Price (TK)</td>
                </tr>";
                $returnval .= "<tbody>";
	        $sl = 1;
	        $totalAmount = 0;
	        $subTotal = 0;
                foreach ($qyeryResutl as $qr) {
                    $returnval .= "<tr>";
                    $returnval .= "<td style='text-align: center;vertical-align: middle;'>".$sl++."</td>";
                    $returnval .= "<td>".$qr->item_name."</td>";
                    $returnval .= "<td style='text-align: center;vertical-align: middle;'>".$qr->denoName."</td>";
	                $returnval .= "<td style='text-align: center;vertical-align: middle;'>".$qr->quantity."</td>";
                    $returnval .= "<td style='text-align: right;vertical-align: middle;'>".$qr->unit_price."</td>";
                    $returnval .= "<td style='text-align: right;vertical-align: middle;'>".$qr->unit_price*$qr->quantity."/00</td>";
                    $returnval .= "</tr>";
                    $subTotal   = $subTotal + ($qr->unit_price*$qr->quantity);
                }

	            $returnval .= "<tr>";
	            $returnval .= "<td colspan='5' style='text-align: right;vertical-align: middle;'>".ucwords(OwnLibrary::numberTowords($subTotal))."</td>";
	            $returnval .= "<td style='text-align: right;vertical-align: middle;'>".$subTotal."/00</td> ";
	            $returnval .= "</tr>";

				if(!empty($supplierInfo->discount_amount)){
			        $returnval .= "<tr>";
			        $returnval .= "<td colspan='5' style='text-align: right;vertical-align: middle;'>Price Reduction/Discount (-)</td>";
			        $returnval .= "<td style='text-align: right;vertical-align: middle;'>".$supplierInfo->discount_amount."/00</td> ";
			        $returnval .= "</tr>";
				}

		        $returnval .= "<tr>";
		        $returnval .= "<td colspan='5' style='text-align: right;vertical-align: middle;'>Grand Total ".ucwords(OwnLibrary::numberTowords($subTotal-$supplierInfo->discount_amount))."</td>";
		        $returnval .= "<td style='text-align: right;vertical-align: middle;'>".$subTotal."/00</td> ";
		        $returnval .= "</tr>";

                $returnval .= "</tbody>";
                $returnval .= "</table>";
        }


        return $returnval;
    }


}
