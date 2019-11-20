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
use Illuminate\Support\Facades\Validator;
use functions\OwnLibrary;
use App\SupplyCategory;
use App\NsdName;
use App\Supplier;
use App\Tender;
use App\Item;
use App\Deno;
use App\DemandToCollectionQuotation;
use App\DemandSuppllierToCollQuotToItem;
use App\ItemToDemand;
use PDF;
use Excel;

class NhqApprovalController extends Controller
{

    private $tableAlies;
    private $moduleId = 34;

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function headquarteApproval($id){

        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

        $nsdId = 1;
         if(!empty(Auth::user()->nsd_bsd)){
            $nsdId = Auth::user()->nsd_bsd;
         }
        $orgInfo  = \App\NsdName::find($nsdId);

        $demandToLprId = $explodes[0];
        $tenderId = $explodes[1];
        $demandtolpr     = \App\demandToLpr::find($demandToLprId);
        $tender     = \App\DemandToTender::where('lpr_id','=',$demandToLprId)->where('tender_id','=',$tenderId)->first();

        $mainTenderInfo = \App\Tender::find($tenderId);

        if($mainTenderInfo->tender_nature != 2){

            $selectedAsDraftCst = array_map('current',DemandSuppllierToCollQuotToItem::select('item_id')
                                            ->where('lpr_id','=',$demandToLprId)
                                            ->where('tender_id','=',$tenderId)
                                            ->where('select_as_draft_cst','=',1)
                                            ->get()->toArray());

             $itemToDemResult = ItemToDemand::where('lpr_id','=',$demandToLprId)
                                            ->where('tender_no','=',$tenderId)
                                            ->whereIn('id',$selectedAsDraftCst)
                                            ->orderBy('id','asc')->get();

            // Newlly added =================
            // ==============================
            $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('lpr_id','=',$demandToLprId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
            $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('lpr_id','!=',$demandToLprId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
            $itemDmnArr = array_unique($itemDmnArr);
            // End newlly added =================
            // ==================================

            // for keeping checked ===============================
            // ===================================================
            $supplierAllreadySelected  = array_map('current',DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
            ->select('demand_to_collection_quotation.id')
            ->where('demand_to_collection_quotation.lpr_id','=',$demandToLprId)
            ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
            ->where('demand_to_collection_quotation.sel_as_draft_cst','=',1)
            ->orderBy('demand_to_collection_quotation.total','asc')
            ->get()->toArray());
     
            $targetArray  = array();
            $sls          = 0;
            $arIn         = 0;

            $supArray              =  array();
            $supTotalAmountArray   =  array();
            $supWiComArray         =  array();

            $suppliersInf  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
                    ->where('demand_to_collection_quotation.lpr_id','=',$demandToLprId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->where('demand_to_collection_quotation.sel_as_draft_cst','=',1)
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
                    ->get();

            foreach ($itemToDemResult as $key => $value) {

                $itemWiseSupp = array_map('current',DemandSuppllierToCollQuotToItem::
                                            select('dmn_to_cal_qut_id')
                                            ->where('item_id','=',$value->id)
                                            ->where('select_as_draft_cst','=',1)
                                            ->where('quoted_quantity','>',0)
                                            ->get()->toArray());
                $forJonPerfect = array_map('current',DemandSuppllierToCollQuotToItem::
                                            select('id')
                                            ->where('item_id','=',$value->id)
                                            ->where('quoted_quantity','>',0)
                                            ->get()->toArray());
                
                $supplierResult[$sls]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','demand_supplier_to_coll_qut_to_item.total_price','demand_supplier_to_coll_qut_to_item.discount_amount','demand_supplier_to_coll_qut_to_item.alternative_unit_price','demand_supplier_to_coll_qut_to_item.alternative_total_price','demand_supplier_to_coll_qut_to_item.comnt_on_cst_ech_itm','demand_supplier_to_coll_qut_to_item.id as cmnt_item_id','demand_supplier_to_coll_qut_to_item.select_as_draft_cst as item_select_as_draft_cst','demand_supplier_to_coll_qut_to_item.select_as_winner as select_as_winner')
                    ->where('demand_to_collection_quotation.lpr_id','=',$demandToLprId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                    ->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                    ->orderBy(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.quoted_quantity, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.total_price, DECIMAL)"),'asc')
                    ->get();
                
                    foreach ($supplierResult[$sls] as $srs) {
                        $supArray[$sls][] = $srs->suppliernametext.'<br>'.$srs->head_office_address.'?'.$srs->id.'?'.$srs->alternative_unit_price.'?'.$srs->item_select_as_draft_cst.'?'.$srs->select_as_winner;
                        $supTotalAmountArray[$sls][] = $srs->total_price.'?'.$srs->alternative_total_price;

                        $supWiComArray[$sls][] = $srs->comnt_on_cst_ech_itm.'?'.$srs->cmnt_item_id.'?'.$srs->alternative_unit_price.'?'.$srs->id;
                    }

                $targetArray[$sls]['items'] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                    ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                    ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                    ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName', 'item_to_demand.id as itm_to_dn_id')
                    ->where('item_to_demand.lpr_id','=',$demandToLprId)
                    ->where('item_to_demand.tender_no','=',$tenderId)
                    ->where('demand_supplier_to_coll_qut_to_item.item_id','=',$value->id)
                    //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                    ->orderBy('item_to_demand.id','asc')
                    ->orderBy('demand_supplier_to_coll_qut_to_item.total_price','asc')
                    ->groupBy('demand_supplier_to_coll_qut_to_item.item_id')
                    ->get();

                    // Newly added =====================================================
                    // =================================================================
                    
                    $winnerInfOfThisItem = DemandToCollectionQuotation::join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id', '=', 'demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->select('demand_supplier_to_coll_qut_to_item.unit_price as last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                        ->where('demand_supplier_to_coll_qut_to_item.lpr_id','!=',$value->lpr_id)
                        ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                        ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
                        //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                        ->where('demand_supplier_to_coll_qut_to_item.select_as_winner','=',1)
                        // ->where('demand_to_collection_quotation.winner','=',1)
                        ->orderBy('demand_supplier_to_coll_qut_to_item.id','DESC')
                        ->get();
               
                    if(count($winnerInfOfThisItem) > 0){
                        foreach ($winnerInfOfThisItem as $key => $winnerIns) {
                            $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', $winnerIns->suppliernametext);
                            $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', $winnerIns->last_unti_price);
                            $targetArray[$sls]['items'][0]->setAttribute('previousDates', date('d-m-Y',strtotime($winnerIns->updated_at)));
                        }
                        
                    }else{
                        $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', '');
                        $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', '');
                        $targetArray[$sls]['items'][0]->setAttribute('previousDates', '');
                    }                        
                    // End newly added =================================================
                    // =================================================================

                foreach ($supplierResult[$sls] as $sr) {
                   $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('lpr_id','=',$demandToLprId)
                                                    ->where('tender_id','=',$tenderId)
                                                    ->where('item_id','=',$value->id)
                                                    ->where('dmn_to_cal_qut_id','=',$sr->id)
                                                    ->where('supplier_id','=',$sr->supplier_name)
                                                    ->get();
                                                    
                    //echo "<pre>"; print_r($sr->supplier_name); exit;
                }

                    $sls++;
                    $arIn++;
            }
            // echo "<pre>"; print_r($targetArray); exit;
            
            return View::make('nhq-approval.cst-draft-view-item')->with(compact('itemList','supplierResult','targetArray','supArray','supTotalAmountArray','demandToLprId','supplierAllreadySelected','supWiComArray','demandtolpr','tenderId','tender','suppliersInf','orgInfo'));
        }else{

            $selectedIds = array_map('current',\App\LotToSupplier::select('lot_name_id')
                                                ->where('lot_select_as_draft_cst','=',1)
                                                ->get()
                                                ->toArray());

            $lotNames = \App\LotNames::where('lpr_id','=',$demandToLprId)
                                    ->where('tender_id','=',$tenderId)
                                    ->whereIn('id',$selectedIds)
                                    ->orderBy('id','asc')
                                    ->get();                        

             // Newlly added =================
            // ==============================
            $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('lpr_id','=',$demandToLprId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
            $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('lpr_id','!=',$demandToLprId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
            $itemDmnArr = array_unique($itemDmnArr);
            // End newlly added =================
            // ==================================

            $suppliersInf  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
                    ->where('demand_to_collection_quotation.lpr_id','=',$demandToLprId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
                    ->get();                        

            $mainArray = array();

            $supArray              =  array();
            $supTotalAmountArray   =  array();
            $supWiComArray         =  array();
            
            foreach ($lotNames as $lotkey => $lotval) {
                    
                $selectSuppLower = array_map('current',\App\LotToSupplier::select('mnd_to_col_qtn_id')
                                                    ->where('lot_name_id','=',$lotval->id)
                                                    ->where('lot_select_as_draft_cst','=',1)
                                                    ->orderBy('lot_wise_total','asc')
                                                    ->get()->toArray());

                $itemToDemResult = ItemToDemand::where('lpr_id','=',$demandToLprId)
                                                ->where('tender_no','=',$tenderId)
                                                ->where('lot_unq_id','=',$lotval->id)
                                                ->orderBy('id','asc')
                                                ->get();                           

                $lotNameInIndex = $lotval->lot_name;
                $targetArray  = array();
                $sls          = 0;
                $arIn         = 0;

                $itemToDemResultIds = array_map('current',ItemToDemand::select('id')
                                                ->where('lpr_id','=',$demandToLprId)
                                                ->where('tender_no','=',$tenderId)
                                                ->where('lot_unq_id','=',$lotval->id)
                                                ->orderBy('id','asc')
                                                ->get()->toArray());     

                $itemWiseSupp = array_unique(array_map('current',DemandSuppllierToCollQuotToItem::
                                                select('dmn_to_cal_qut_id')
                                                ->where('select_as_draft_cst','=',1)
                                                ->whereIn('item_id',$itemToDemResultIds)
                                                ->where('quoted_quantity','>',0)
                                                ->get()->toArray()));
                
                    $supplierResultFir[$lotNameInIndex]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                        ->join('lot_to_supplier','demand_to_collection_quotation.id','=','lot_to_supplier.mnd_to_col_qtn_id')
                        ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','lot_to_supplier.id as lot_to_sup_id','lot_to_supplier.lot_name_id','lot_to_supplier.mnd_to_col_qtn_id','lot_to_supplier.mnd_to_col_qtn_id','lot_to_supplier.lot_wise_total_qty','lot_to_supplier.lot_wise_total','lot_to_supplier.altr_quoted_total_quantity','lot_to_supplier.altr_total_price','lot_to_supplier.lot_select_as_draft_cst','lot_to_supplier.comnt_on_cst_ech_lot','lot_to_supplier.lot_select_as_winner')
                        ->where('demand_to_collection_quotation.lpr_id','=',$demandToLprId)
                        ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                        ->where('lot_to_supplier.lot_name_id','=',$lotval->id)
                        ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                        //->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total_qty, DECIMAL)"),'desc')
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total, DECIMAL)"),'asc')
                        ->get();

                        foreach ($supplierResultFir[$lotNameInIndex] as $srs) {
                            $supArray[$lotNameInIndex][] = $srs->suppliernametext.'<br>'.$srs->head_office_address.'?'.$srs->id.'?'.$srs->altr_total_price.'?'.$srs->lot_select_as_winner.'?'.$srs->lot_name_id.'?'.$srs->lot_to_sup_id;

                            $supTotalAmountArray[$lotNameInIndex][] = $srs->lot_wise_total.'?'.$srs->altr_total_price;

                            $supWiComArray[$lotNameInIndex][] = $srs->comnt_on_cst_ech_lot.'?'.$srs->lot_to_sup_id.'?'.$srs->altr_total_price.'?'.$srs->id;
                        }
                
                foreach ($itemToDemResult as $key => $value) {
                    $itemWiseSupp = array_unique(array_map('current',DemandSuppllierToCollQuotToItem::
                                                select('dmn_to_cal_qut_id')
                                                ->where('item_id','=',$value->id)
                                                ->where('quoted_quantity','>',0)
                                                ->get()->toArray()));
                    
                    $forJonPerfect = array_map('current',DemandSuppllierToCollQuotToItem::
                                                select('id')
                                                ->where('item_id','=',$value->id)
                                                ->whereIn('dmn_to_cal_qut_id',$itemWiseSupp)
                                                ->where('quoted_quantity','>',0)
                                                ->get()->toArray());

                    $supplierResult[$sls]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                        ->join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->join('lot_to_supplier','demand_to_collection_quotation.id','=','lot_to_supplier.mnd_to_col_qtn_id')
                        ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','demand_supplier_to_coll_qut_to_item.total_price','demand_supplier_to_coll_qut_to_item.discount_amount','demand_supplier_to_coll_qut_to_item.alternative_unit_price','demand_supplier_to_coll_qut_to_item.alternative_total_price','demand_supplier_to_coll_qut_to_item.comnt_on_cst_ech_itm','demand_supplier_to_coll_qut_to_item.id as cmnt_item_id','demand_supplier_to_coll_qut_to_item.select_as_draft_cst as item_select_as_draft_cst')
                        ->where('demand_to_collection_quotation.lpr_id','=',$demandToLprId)
                        ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                        ->where('lot_to_supplier.lot_name_id','=',$lotval->id)
                        ->where('demand_supplier_to_coll_qut_to_item.select_as_draft_cst','=',1)
                        ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                        ->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total_qty, DECIMAL)"),'desc')
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total, DECIMAL)"),'asc')
                        ->get();
                    
                    $targetArray[$sls]['items'] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                        ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                        ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                        ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName', 'item_to_demand.id as itm_to_dn_id')
                        ->where('item_to_demand.lpr_id','=',$demandToLprId)
                        ->where('item_to_demand.tender_no','=',$tenderId)
                        ->where('demand_supplier_to_coll_qut_to_item.item_id','=',$value->id)
                        //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                        ->orderBy('item_to_demand.id','asc')
                        ->orderBy('demand_supplier_to_coll_qut_to_item.total_price','asc')
                        ->groupBy('demand_supplier_to_coll_qut_to_item.item_id')
                        ->get();

                        // Newly added =====================================================
                        // =================================================================
                        
                        $winnerInfOfThisItem = DemandToCollectionQuotation::join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id', '=', 'demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                            ->select('demand_supplier_to_coll_qut_to_item.unit_price as last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                            ->where('demand_supplier_to_coll_qut_to_item.lpr_id','!=',$value->lpr_id)
                            ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                            ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
                            //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                            ->whereIn('demand_to_collection_quotation.id',$itemDmnArr)
                            // ->where('demand_to_collection_quotation.winner','=',1)
                            ->orderBy('demand_supplier_to_coll_qut_to_item.id','DESC')
                            ->get();
                   
                        if(count($winnerInfOfThisItem) > 0){
                            foreach ($winnerInfOfThisItem as $key => $winnerIns) {
                                $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', $winnerIns->suppliernametext);
                                $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', $winnerIns->last_unti_price);
                                $targetArray[$sls]['items'][0]->setAttribute('previousDates', date('d-m-Y',strtotime($winnerIns->updated_at)));
                            }
                            
                        }else{
                            $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', '');
                            $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', '');
                            $targetArray[$sls]['items'][0]->setAttribute('previousDates', '');
                        }                        
                        // End newly added =================================================
                        // =================================================================
                    foreach ($supplierResult[$sls] as $sr) {
                        $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('lpr_id','=',$demandToLprId)
                                                        ->where('tender_id','=',$tenderId)
                                                        ->where('item_id','=',$value->id)
                                                        ->where('dmn_to_cal_qut_id','=',$sr->id)
                                                        ->where('supplier_id','=',$sr->supplier_name)
                                                        ->get();                          
                    }
                        $sls++;
                        $arIn++;
                }

                $mainArray[$lotNameInIndex] = $targetArray;

            }        
            
            return View::make('nhq-approval.cst-draft-view-lot')->with(compact('itemList','supplierResult','targetArray','supArray','supTotalAmountArray','demandToLprId','supplierAllreadySelected','supWiComArray','demandtolpr','tenderId','tender','suppliersInf','supplierResultFir','mainArray','orgInfo'));

        }// End of tender nature lot

        //return View::make('floating-tender.headqtr-approval-view')->with(compact('demandsUp','tenderId'));
        
    }

    public function postHeadquarteApproval(Request $request){

        $this->tableAlies = \Session::get('zoneAlise');

        $demand_to_lpr_id = $request->demandToLprId;
        $tenderId  = $request->tenderId;

        $tender = \App\DemandToTender::where('lpr_id','=',$demand_to_lpr_id)->where('tender_id','=',$tenderId)->first();

        if($request->tenderNature==2){

            $rules = array(
                'cst_draft_sup_id' => 'required'
            );

            $message = array(
                'cst_draft_sup_id.required' => 'Please, select supplier!'
            );

            $validator = Validator::make(Input::all(), $rules, $message);

            if ($validator->fails()) {
                return redirect('headquarte-approval/'.$demand_to_lpr_id.'&'.$tenderId)->withErrors($validator);
            } else {

                if(count($request->cst_draft_sup_id)>0 ){

                    $suppToUpDtArray = array(); // Have to update in DemanToCollectionTable
                    $commentsArray   = array(); // Comment done in this ids in DemandSuppTocollToItem
                    $allComentInSup  = array(); // All comment to update in DemanToCollectionTable

                    \DB::table('demand_supplier_to_coll_qut_to_item')
                        ->where('lpr_id', $demand_to_lpr_id)
                        ->where('tender_id', $tenderId)
                        ->update(['select_as_winner' => NULL]);

                        \DB::table('demand_to_collection_quotation')
                        ->where('lpr_id', $demand_to_lpr_id)
                        ->where('tender_id', $tenderId)
                        ->update(['winner' => NULL]);

                        $retenderQty = 0;
                        for($i=0; $i < count($request->item_to_dmn_id); $i++) {

                            $itdi = $request->item_to_dmn_id[$i];
                            $naq  = $request->nhq_app_qty[$request->item_to_dmn_id[$i]];
                            $nas  = $request->nhq_app_status[$i];

                            if($nas==2){
                                $retenderQty += $naq;
                            }
                            // Update item_to_demand table
                            $updateItemToDemand = \App\ItemToDemand::find($itdi);
                            $updateItemToDemand->nhq_app_qty    = $naq;
                            $updateItemToDemand->nhq_app_status = $nas;
                            $updateItemToDemand->save();

                            \DB::table('demand_supplier_to_coll_qut_to_item')
                            ->where('lpr_id', $demand_to_lpr_id)
                            ->where('tender_id', $tenderId)
                            ->where('item_id', $itdi)
                            ->update(['itm_to_sup_nhq_app_qty' => $naq, 'itm_to_sup_nhq_app_status' => $nas]);

                        }

                        $retenderAlreadyExist = \App\Retender::where('tender_id','=',$tenderId)->where('tender_number','=',$tender->tender_number)->where('lpr_id','=',$demand_to_lpr_id)->first();
                        if(in_array(2,$request->nhq_app_status)){
                            $demandToTen = \App\DemandToTender::where('lpr_id','=',$demand_to_lpr_id)->where('tender_id', $tenderId)->first();
                            if(!empty($updateDemandToTen)){
                                $updateDemandToTen = \App\DemandToTender::find($demandToTen->id);
                                $updateDemandToTen->retender = 1;
                                $updateDemandToTen->save();
                            }

                            if(empty($retenderAlreadyExist)){
                                $createRetender = new \App\Retender();
                            }else{
                                $createRetender = \App\Retender::find($retenderAlreadyExist->id);
                            }
                            $createRetender->lpr_id      = $demand_to_lpr_id;
                            $createRetender->tender_id      = $tenderId;
                            $createRetender->tender_number  = $tender->tender_number;
                            $createRetender->dmn_to_ten_id  = $demandToTen->id;
                            $createRetender->retenderQty    = $retenderQty;
                            $createRetender->save();
                        }else{
                            $deleteRetender = \App\Retender::find($retenderAlreadyExist->id);
                            $deleteRetender->delete();
                        }

                        for($m=0; count($request->cst_draft_sup_id)>$m; $m++){
                            $explodedDatas = explode('&',$request->cst_draft_sup_id[$m]);

                            $demToCollUpdate = DemandToCollectionQuotation::find($explodedDatas[0]);
                            $demToCollUpdate->winner = 1;
                            $demToCollUpdate->save();

                            $suppToUpDtArray[] =  $explodedDatas[0]; 

                            $lotToSupplier = \App\LotToSupplier::find($explodedDatas[2]);
                            $lotToSupplier->lot_select_as_winner    = 1;
                            $lotToSupplier->save();

                            $lotImtesIds = array_map('current',ItemToDemand::where('lot_unq_id','=',$explodedDatas[1])->get()->toArray());

                            $itemsToUpdate = DemandSuppllierToCollQuotToItem::where('dmn_to_cal_qut_id','=',$explodedDatas[0])->whereIn('item_id',$lotImtesIds)->get();
                            
                            foreach ($itemsToUpdate as $key => $itu) {
                                $updateItems = DemandSuppllierToCollQuotToItem::find($itu->id);
                                $updateItems->select_as_winner = 1;
                                $updateItems->save();
                            }
                                                
                        }

                        if(count($request->dem_to_col_quo_id ) > 0){
                            for($xy=0; count($request->dem_to_col_quo_id )>$xy; $xy++){ 

                                $demand_to_coll = DemandToCollectionQuotation::find($request->dem_to_col_quo_id[$xy]);
                                $demand_to_coll->discount_amount  = $request->discount[$xy];
                                $demand_to_coll->comment_on_cst   = $request->comment[$xy];
                                $demand_to_coll->save();
                            }
                        }

                        if(count($request->dem_to_col_quo_id ) > 0){
                            for($xy=0; count($request->dem_to_col_quo_id )>$xy; $xy++){ 

                                $demand_to_coll = DemandToCollectionQuotation::find($request->dem_to_col_quo_id[$xy]);

                                $itemsUnderThisSupp = DemandSuppllierToCollQuotToItem::
                                                            where('dmn_to_cal_qut_id','=',$request->dem_to_col_quo_id[$xy])
                                                            ->where('select_as_winner','=',1)
                                                            ->where('itm_to_sup_nhq_app_status','=',1)
                                                            ->get();
                                $totalItemQtyOfThisSupp = DemandSuppllierToCollQuotToItem::
                                                            where('dmn_to_cal_qut_id','=',$request->dem_to_col_quo_id[$xy])
                                                            ->where('select_as_draft_cst','=',1)
                                                            ->where('itm_to_sup_nhq_app_status','=',1)
                                                            ->sum('itm_to_sup_nhq_app_qty');

                                if(count($itemsUnderThisSupp)>0){
                                    foreach ($itemsUnderThisSupp as $val) {
                                        
                                        $discountAllowedAmount = $demand_to_coll->discount_amount;
                                        $discounRate = '';

                                        if(!empty($discountAllowedAmount) && !empty($totalItemQtyOfThisSupp)){
                                            $discounRate = $discountAllowedAmount/$totalItemQtyOfThisSupp;
                                        }

                                        $updateDisc = \App\DemandSuppllierToCollQuotToItem::find($val->id);

                                        $updateDisc->final_doscount_amount = empty($discounRate) ? NULL : $val->itm_to_sup_nhq_app_qty*$discounRate;

                                        $updateDisc->save();

                                    }
                                    
                                }// End if


                            }
                        }

                    $demandtoLpr = \App\DemandToLpr::find($demand_to_lpr_id);
                    $demandIds = explode(',', $demandtoLpr->demand_ids);

                    if($tender->send_to_nhq==2){

                        $demandtoLpr->lp_section_status       = 1;
                        $demandtoLpr->head_ofc_apvl_status    = 1;
                        $demandtoLpr->head_ofc_apvl_by        = Auth::user()->id;
                        $demandtoLpr->head_ofc_apvl_date      = date('Y-m-d H:i:s');
                        $demandtoLpr->current_status            = 10;
                        if(!empty($demandtoLpr->demand_ids)){
                            foreach ($demandIds as $key => $vd) {
                                $demandsUp   = \App\Demand::find($vd);

                                $demandsUp->lp_section_status     = 1;
                                $demandsUp->head_ofc_apvl_status  = 1;
                                $demandsUp->head_ofc_apvl_by      = Auth::user()->id;
                                $demandsUp->head_ofc_apvl_date    = date('Y-m-d H:i:s');
                                $demandsUp->current_status        = 10;
                                $demandsUp->save();
                            }
                        }
                        

                        \DB::table('item_to_demand')
                            ->where('lpr_id', $demand_to_lpr_id)
                            ->where('tender_no', $tenderId)
                            ->update(['head_ofc_apvl_status' => 1, 'head_ofc_apvl_by' =>  Auth::user()->id, 'head_ofc_apvl_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 10]);

                        \DB::table('demand_to_tender')
                            ->where('lpr_id', $demand_to_lpr_id)
                            ->where('tender_id', $tenderId)
                            ->update(['head_ofc_apvl_status' => 1, 'head_ofc_apvl_by' =>  Auth::user()->id, 'head_ofc_apvl_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 10]);


                    }else{

                        $demandtoLpr->cst_supplier_select      = 1;
                        $demandtoLpr->cst_supplier_select_by   = Auth::user()->id;
                        $demandtoLpr->cst_supplier_select_date = date('Y-m-d H:i:s');
                        $demandtoLpr->lp_section_status         = 1;
                        $demandtoLpr->current_status            = 9;
                        if(!empty($demandtoLpr->demand_ids)){
                            foreach ($demandIds as $key => $vd) {
                                $demandsUp   = \App\Demand::find($vd);
                                $demandsUp->cst_supplier_select      = 1;
                                $demandsUp->cst_supplier_select_by   = Auth::user()->id;
                                $demandsUp->cst_supplier_select_date = date('Y-m-d H:i:s');
                                $demandsUp->lp_section_status         = 1;
                                $demandsUp->current_status            = 9;
                                $demandsUp->save();
                            }
                        }
                        

                        \DB::table('item_to_demand')
                        ->where('lpr_id', $demand_to_lpr_id)
                        ->where('tender_no', $tenderId)
                        ->update(['cst_supplier_select' => 1, 'cst_supplier_select_by' =>  Auth::user()->id, 'cst_supplier_select_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 9]);

                        \DB::table('demand_to_tender')
                        ->where('lpr_id', $demand_to_lpr_id)
                        ->where('tender_id', $tenderId)
                        ->update(['cst_supplier_select' => 1, 'cst_supplier_select_by' =>  Auth::user()->id, 'cst_supplier_select_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 9]);
                    }

                    $suppToUpDtArray = array_unique($suppToUpDtArray);// Have to update in 

                    if($demandtoLpr->save()){

                        if(!empty($suppToUpDtArray)){
                            foreach ($suppToUpDtArray as $key => $supToColCot) {

                                $demandSupplierToCollection = DemandToCollectionQuotation::find($supToColCot);
                                $tenderForColneExi = Tender::find($tenderId);

                                $tenderForColne = new Tender();

                                $tenderForColne->demand_no = $tenderForColneExi->demand_no;
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
                                                        ->where('select_as_winner','=',1)
                                                        ->where('itm_to_sup_nhq_app_status','=',1)
                                                        ->get();

                                if(count($itemsUnderThisSupplier)>0){

                                    foreach ($itemsUnderThisSupplier as $val) {
                                        
                                        $itemtotender = new \App\ItemToTender();

                                        $itemtotender->tender_id = $tenderForColne->id;
                                        $itemtotender->item_id   = $val->real_item_id;
                                        $itemtotender->quantity  = $val->itm_to_sup_nhq_app_qty;
                                        $itemtotender->unit_price = $val->unit_price;
                                        $itemtotender->unit_price_in_bdt = $val->unit_price;
                                        $itemtotender->currency_name = 1;
                                        $itemtotender->conversion = 1;
                                        $itemtotender->discount_price = $val->discount_amount+$val->final_doscount_amount;
                                        $itemtotender->discount_price_in_bdt = $val->discount_amount+$val->final_doscount_amount;
                                        $itemtotender->total = $val->total_price;
                                        
                                        if($itemtotender->save()){
                                            $itemtotenderUpA = \App\ItemToTender::find($itemtotender->id);
                                            $itemtotenderUpA->all_org_itmtotender_id = $itemtotender->id;
                                            $itemtotenderUpA->save();
                                        }

                                    }
                                    
                                }

                            }
                        }   

                    }// End of save functions =============
                     // ===================================

                    Session::flash('success', 'Data Updated Successfully');
                    // return redirect('demand-details/'.$demand_id);
                    return redirect('hdq-approval-acc/1');

                }

                Session::flash('error', 'Data can not be update');
                // return redirect('cst-view/'.$demand_id);
                return redirect('hdq-approval-acc/1');

            } // Emd of validation if else

        }else{

            $rules = array(
                //'cst_draft_sup_id' => 'required',
                'item_ids' => 'required'
            );

            $message = array(
                //'cst_draft_sup_id.required' => 'Please, select supplier!',
                'item_ids.required' => 'Please, select item!',
            );

            $validator = Validator::make(Input::all(), $rules, $message);

            if ($validator->fails()) {
                 return redirect('headquarte-approval/'.$demand_to_lpr_id.'&'.$tenderId)->withErrors($validator);
            } else {

                if(count($request->item_ids) ){

                    $suppToUpDtArray = array(); // Have to update in DemanToCollectionTable

                    \DB::table('demand_supplier_to_coll_qut_to_item')
                        ->where('lpr_id', $demand_to_lpr_id)
                        ->where('tender_id', $tenderId)
                        ->update(['select_as_winner' => NULL]);

                        $retenderQty = 0;
                        for($i=0; $i < count($request->item_to_dmn_id); $i++) {

                            $itdi = $request->item_to_dmn_id[$i];
                            $naq  = $request->nhq_app_qty[$request->item_to_dmn_id[$i]];
                            $nas  = $request->nhq_app_status[$i];

                            if($nas==2){
                                $retenderQty += $naq;
                            }
                            // Update item_to_demand table
                            $updateItemToDemand = \App\ItemToDemand::find($itdi);
                            $updateItemToDemand->nhq_app_qty    = $naq;
                            $updateItemToDemand->nhq_app_status = $nas;
                            $updateItemToDemand->save();

                            \DB::table('demand_supplier_to_coll_qut_to_item')
                            ->where('lpr_id', $demand_to_lpr_id)
                            ->where('tender_id', $tenderId)
                            ->where('item_id', $itdi)
                            ->update(['itm_to_sup_nhq_app_qty' => $naq, 'itm_to_sup_nhq_app_status' => $nas]);

                        }

                        $retenderAlreadyExist = \App\Retender::where('tender_id','=',$tenderId)->where('tender_number','=',$tender->tender_number)->where('lpr_id','=',$demand_to_lpr_id)->first();
                        if(in_array(2,$request->nhq_app_status)){
                            $demandToTen = \App\DemandToTender::where('lpr_id','=',$demand_to_lpr_id)->where('tender_id', $tenderId)->first();
                            if(!empty($updateDemandToTen)){
                                $updateDemandToTen = \App\DemandToTender::find($demandToTen->id);
                                $updateDemandToTen->retender = 1;
                                $updateDemandToTen->save();
                            }

                            if(empty($retenderAlreadyExist)){
                                $createRetender = new \App\Retender();
                            }else{
                                $createRetender = \App\Retender::find($retenderAlreadyExist->id);
                            }
                            $createRetender->lpr_id      = $demand_to_lpr_id;
                            $createRetender->tender_id      = $tenderId;
                            $createRetender->tender_number  = $tender->tender_number;
                            $createRetender->dmn_to_ten_id  = $demandToTen->id;
                            $createRetender->retenderQty    = $retenderQty;
                            $createRetender->save();
                        }else{
                            $deleteRetender = \App\Retender::find($retenderAlreadyExist->id);
                            $deleteRetender->delete();
                        }

                    for($i=0; count($request->item_ids)>$i; $i++){

                        $explodedDatas = explode('&', $request->item_ids[$i]);

                        $updateDemSupToItem = DemandSuppllierToCollQuotToItem::find($explodedDatas[0]);

                        $updateDemSupToItem->select_as_winner = 1;
                        $updateDemSupToItem->save();

                        $suppToUpDtArray[] =  $explodedDatas[1]; 
                        
                    }

                    \DB::table('demand_to_collection_quotation')
                        ->where('lpr_id', $demand_to_lpr_id)
                        ->where('tender_id', $tenderId)
                        ->update(['winner' => NULL]);

                    $suppIds = array_unique ($suppToUpDtArray);
                    if(count($suppIds ) > 0){
                        for($m=0; count($suppIds )>$m; $m++){ 

                            $demand_to_coll = DemandToCollectionQuotation::find($suppIds[$m]);
                            $demand_to_coll->winner = 1;
                            $demand_to_coll->save();
                        }
                    }

                    if(count($request->dem_to_col_quo_id ) > 0){
                        for($xy=0; count($request->dem_to_col_quo_id )>$xy; $xy++){ 

                            $demand_to_coll = DemandToCollectionQuotation::find($request->dem_to_col_quo_id[$xy]);
                            $demand_to_coll->discount_amount  = $request->discount[$xy];
                            $demand_to_coll->comment_on_cst   = $request->comment[$xy];
                            $demand_to_coll->save();
                        }
                    }

                    if(count($request->dem_to_col_quo_id ) > 0){
                        for($xy=0; count($request->dem_to_col_quo_id )>$xy; $xy++){ 

                            $demand_to_coll = DemandToCollectionQuotation::find($request->dem_to_col_quo_id[$xy]);

                            $itemsUnderThisSupp = DemandSuppllierToCollQuotToItem::
                                                        where('dmn_to_cal_qut_id','=',$request->dem_to_col_quo_id[$xy])
                                                        ->where('select_as_winner','=',1)
                                                        ->where('itm_to_sup_nhq_app_status','=',1)
                                                        ->get();
                            $totalItemQtyOfThisSupp = DemandSuppllierToCollQuotToItem::
                                                        where('dmn_to_cal_qut_id','=',$request->dem_to_col_quo_id[$xy])
                                                        ->where('select_as_draft_cst','=',1)
                                                        ->where('itm_to_sup_nhq_app_status','=',1)
                                                        ->sum('itm_to_sup_nhq_app_qty');

                            if(count($itemsUnderThisSupp)>0){
                                foreach ($itemsUnderThisSupp as $val) {
                                    
                                    $discountAllowedAmount = $demand_to_coll->discount_amount;
                                    $discounRate = '';

                                    if(!empty($discountAllowedAmount) && !empty($totalItemQtyOfThisSupp)){
                                        $discounRate = $discountAllowedAmount/$totalItemQtyOfThisSupp;
                                    }

                                    $updateDisc = \App\DemandSuppllierToCollQuotToItem::find($val->id);

                                    $updateDisc->final_doscount_amount = empty($discounRate) ? NULL : $val->itm_to_sup_nhq_app_qty*$discounRate;

                                    $updateDisc->save();

                                }
                                
                            }// End if


                        }
                    }

                    $demandtoLpr = \App\DemandToLpr::find($demand_to_lpr_id);
                    $demandIds = explode(',', $demandtoLpr->demand_ids);

                    if($tender->send_to_nhq==2){

                        $demandtoLpr->lp_section_status       = 1;
                        $demandtoLpr->head_ofc_apvl_status    = 1;
                        $demandtoLpr->head_ofc_apvl_by        = Auth::user()->id;
                        $demandtoLpr->head_ofc_apvl_date      = date('Y-m-d H:i:s');
                        $demandtoLpr->current_status            = 10;
                        if(!empty($demandtoLpr->demand_ids)){
                            foreach ($demandIds as $key => $vd) {
                                $demandsUp   = \App\Demand::find($vd);

                                $demandsUp->lp_section_status     = 1;
                                $demandsUp->head_ofc_apvl_status  = 1;
                                $demandsUp->head_ofc_apvl_by      = Auth::user()->id;
                                $demandsUp->head_ofc_apvl_date    = date('Y-m-d H:i:s');
                                $demandsUp->current_status        = 10;
                                $demandsUp->save();
                            }
                        }

                        \DB::table('item_to_demand')
                            ->where('lpr_id', $demand_to_lpr_id)
                            ->where('tender_no', $tenderId)
                            ->update(['head_ofc_apvl_status' => 1, 'head_ofc_apvl_by' =>  Auth::user()->id, 'head_ofc_apvl_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 10]);

                        \DB::table('demand_to_tender')
                            ->where('lpr_id', $demand_to_lpr_id)
                            ->where('tender_id', $tenderId)
                            ->update(['head_ofc_apvl_status' => 1, 'head_ofc_apvl_by' =>  Auth::user()->id, 'head_ofc_apvl_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 10]);


                    }else{
                        $demandtoLpr->cst_supplier_select      = 1;
                        $demandtoLpr->cst_supplier_select_by   = Auth::user()->id;
                        $demandtoLpr->cst_supplier_select_date = date('Y-m-d H:i:s');
                        $demandtoLpr->lp_section_status         = 1;
                        $demandtoLpr->current_status            = 9;
                        
                        foreach ($demandIds as $key => $vd) {
                            $demandsUp   = \App\Demand::find($vd);
                            $demandsUp->cst_supplier_select      = 1;
                            $demandsUp->cst_supplier_select_by   = Auth::user()->id;
                            $demandsUp->cst_supplier_select_date = date('Y-m-d H:i:s');
                            $demandsUp->lp_section_status         = 1;
                            $demandsUp->current_status            = 9;
                            $demandsUp->save();
                        }

                        \DB::table('item_to_demand')
                        ->where('lpr_id', $demand_to_lpr_id)
                        ->where('tender_no', $tenderId)
                        ->update(['cst_supplier_select' => 1, 'cst_supplier_select_by' =>  Auth::user()->id, 'cst_supplier_select_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 9]);

                        \DB::table('demand_to_tender')
                        ->where('lpr_id', $demand_to_lpr_id)
                        ->where('tender_id', $tenderId)
                        ->update(['cst_supplier_select' => 1, 'cst_supplier_select_by' =>  Auth::user()->id, 'cst_supplier_select_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 9]);
                    }

                    if($demandtoLpr->save()){

                        if(!empty($suppToUpDtArray)){
                            foreach ($suppToUpDtArray as $key => $supToColCot) {

                                $demandSupplierToCollection = DemandToCollectionQuotation::find($supToColCot);
                                $tenderForColneExi = Tender::find($tenderId);

                                $tenderForColne = new Tender();

                                $tenderForColne->demand_no = $tenderForColneExi->demand_no;
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
                                                        ->where('select_as_winner','=',1)
                                                        ->where('itm_to_sup_nhq_app_status','=',1)
                                                        ->get();

                                if(count($itemsUnderThisSupplier)>0){

                                    foreach ($itemsUnderThisSupplier as $val) {
                                        
                                        $itemtotender = new \App\ItemToTender();

                                        $itemtotender->tender_id = $tenderForColne->id;
                                        $itemtotender->item_id   = $val->real_item_id;
                                        $itemtotender->quantity  = $val->itm_to_sup_nhq_app_qty;
                                        $itemtotender->unit_price = $val->unit_price;
                                        $itemtotender->unit_price_in_bdt = $val->unit_price;
                                        $itemtotender->currency_name = 1;
                                        $itemtotender->conversion = 1;
                                        $itemtotender->discount_price = empty($val->discount_amount) ? 0.00 : $val->discount_amount+$val->final_doscount_amount;
                                        $itemtotender->discount_price_in_bdt = $val->discount_amount+$val->final_doscount_amount;
                                        $itemtotender->total = $val->total_price;
                                        
                                        if($itemtotender->save()){
                                            $itemtotenderUpA = \App\ItemToTender::find($itemtotender->id);
                                            $itemtotenderUpA->all_org_itmtotender_id = $itemtotender->id;
                                            $itemtotenderUpA->save();
                                        }

                                    }
                                    
                                }

                            }
                        }   

                    }// End of save functions =============
                     // ===================================

                    Session::flash('success', 'Data Updated Successfully');
                    //return redirect('demand-details/'.$demand_id);
                    return redirect('hdq-approval-acc/1');

                }

                Session::flash('error', 'Data can not be update');
                // return redirect('draft-cst-view/'.$demand_id);
                return redirect('hdq-approval-acc/1');

            }
        }


    }

    // Floating Tender Print ================================
    public function cstViewPrint($id){

        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

        $nsdId = 1;
         if(!empty(Auth::user()->nsd_bsd)){
            $nsdId = Auth::user()->nsd_bsd;
         }
        $orgInfo  = \App\NsdName::find($nsdId);

//        $demandId = $id;
        $demandId = $explodes[0];
        $tenderId = $explodes[1];
        $demand     = \App\Demand::find($demandId);
        $tender     = \App\DemandToTender::where('demand_id','=',$demandId)->where('tender_id','=',$tenderId)->first();

        $mainTenderInfo = \App\Tender::find($tenderId);

        if($mainTenderInfo->tender_nature != 2){ // If tender type is line items

        $itemToDemResult = ItemToDemand::where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get();

        // Newlly added =================
        // ==============================
        $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
        $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('demand_id','!=',$demandId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
        $itemDmnArr = array_unique($itemDmnArr);
        // End newlly added =================
        // ==================================

        // for keeping checked ===============================
        // ===================================================
        $supplierAllreadySelected  = array_map('current',DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
        ->select('demand_to_collection_quotation.id')
        ->where('demand_to_collection_quotation.demand_id','=',$demandId)
        ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
        ->where('demand_to_collection_quotation.sel_as_draft_cst','=',1)
        ->orderBy('demand_to_collection_quotation.total','asc')
        ->get()->toArray());
 
        $targetArray  = array();
        $sls          = 0;
        $arIn         = 0;

        $supArray              =  array();
        $supTotalAmountArray   =  array();
        $supWiComArray         =  array();

        $suppliersInf  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
                ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
                ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
                ->get();

        foreach ($itemToDemResult as $key => $value) {

            $itemWiseSupp = array_map('current',DemandSuppllierToCollQuotToItem::
                                        select('dmn_to_cal_qut_id')
                                        ->where('item_id','=',$value->id)
                                        ->where('quoted_quantity','>',0)
                                        ->get()->toArray());
            $forJonPerfect = array_map('current',DemandSuppllierToCollQuotToItem::
                                        select('id')
                                        ->where('item_id','=',$value->id)
                                        ->where('quoted_quantity','>',0)
                                        ->get()->toArray());
            
            $supplierResult[$sls]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                ->join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','demand_supplier_to_coll_qut_to_item.total_price','demand_supplier_to_coll_qut_to_item.discount_amount','demand_supplier_to_coll_qut_to_item.alternative_unit_price','demand_supplier_to_coll_qut_to_item.alternative_total_price','demand_supplier_to_coll_qut_to_item.comnt_on_cst_ech_itm','demand_supplier_to_coll_qut_to_item.id as cmnt_item_id','demand_supplier_to_coll_qut_to_item.select_as_draft_cst as item_select_as_draft_cst')
                ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                ->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                ->orderBy(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.quoted_quantity, DECIMAL)"),'desc')
                ->orderBy(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.total_price, DECIMAL)"),'asc')
                ->get();
            
                foreach ($supplierResult[$sls] as $srs) {
                    $supArray[$sls][] = $srs->suppliernametext.'<br>'.$srs->head_office_address.'?'.$srs->id.'?'.$srs->alternative_unit_price.'?'.$srs->item_select_as_draft_cst;
                    $supTotalAmountArray[$sls][] = $srs->total_price.'?'.$srs->alternative_total_price;

                    $supWiComArray[$sls][] = $srs->comnt_on_cst_ech_itm.'?'.$srs->cmnt_item_id.'?'.$srs->alternative_unit_price.'?'.$srs->id;
                }

            $targetArray[$sls]['items'] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName')
                ->where('item_to_demand.demand_id','=',$demandId)
                ->where('item_to_demand.tender_no','=',$tenderId)
                ->where('demand_supplier_to_coll_qut_to_item.item_id','=',$value->id)
                //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                ->orderBy('item_to_demand.id','asc')
                ->orderBy('demand_supplier_to_coll_qut_to_item.total_price','asc')
                ->groupBy('demand_supplier_to_coll_qut_to_item.item_id')
                ->get();

                // Newly added =====================================================
                // =================================================================
                
                $winnerInfOfThisItem = DemandToCollectionQuotation::join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id', '=', 'demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->select('demand_supplier_to_coll_qut_to_item.unit_price as last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                    ->where('demand_supplier_to_coll_qut_to_item.demand_id','!=',$value->demand_id)
                    ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                    ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
                    //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                    ->whereIn('demand_to_collection_quotation.id',$itemDmnArr)
                    // ->where('demand_to_collection_quotation.winner','=',1)
                    ->orderBy('demand_supplier_to_coll_qut_to_item.id','DESC')
                    ->get();
           
                if(count($winnerInfOfThisItem) > 0){
                    foreach ($winnerInfOfThisItem as $key => $winnerIns) {
                        $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', $winnerIns->suppliernametext);
                        $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', $winnerIns->last_unti_price);
                        $targetArray[$sls]['items'][0]->setAttribute('previousDates', date('d-m-Y',strtotime($winnerIns->updated_at)));
                    }
                    
                }else{
                    $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', '');
                    $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', '');
                    $targetArray[$sls]['items'][0]->setAttribute('previousDates', '');
                }                        
                // End newly added =================================================
                // =================================================================

            foreach ($supplierResult[$sls] as $sr) {
                $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('demand_id','=',$demandId)
                                                ->where('tender_id','=',$tenderId)
                                                ->where('item_id','=',$value->id)
                                                ->where('dmn_to_cal_qut_id','=',$sr->id)
                                                ->where('supplier_id','=',$sr->supplier_name)
                                                ->get();
                                                
                //echo "<pre>"; print_r($sr->supplier_name); exit;
            }

                $sls++;
                $arIn++;
        }
       
        $tenderData = [
                    //'itemList' => $itemList,
                    'supplierResult' => $supplierResult,
                    'targetArray' => $targetArray,
                    'supArray' => $supArray,
                    'supTotalAmountArray' => $supTotalAmountArray,
                    'demandId' => $demandId,
                    'supplierAllreadySelected' => $supplierAllreadySelected,
                    'supWiComArray' => $supWiComArray,
                    'tender' => $tender,
                    'demand' => $demand,
                    'tenderId' => $tenderId,
                    'suppliersInf' => $suppliersInf,
                    'orgInfo' => $orgInfo,
                    //'supplierResultFir' => $supplierResultFir,
                    //'mainArray' => $mainArray
                ];

        $pdf= PDF::loadView('cst-print.cst-line-view-pdf',$tenderData,[],['format' => [215.9, 342.9], 'orientation' => 'L']);
        return $pdf->stream('cst-view.pdf');
        
        //return View::make('demands.cst-view')->with(compact('itemList','supplierResult','targetArray','supArray','supTotalAmountArray','demandId','supplierAllreadySelected','supWiComArray','demand','tenderId','tender','suppliersInf'));

        } else{ // End if tender type line items==================================

        // $lotNames = ItemToDemand::where('demand_id','=',$demandId)
        //                         ->where('tender_no','=',$tenderId)
        //                         ->groupBy('lot_name')
        //                         ->orderBy('id','asc')
        //                         ->get();
        $lotNames = \App\LotNames::where('demand_id','=',$demandId)
                                ->where('tender_id','=',$tenderId)
                                //->groupBy('lot_name')
                                ->orderBy('id','asc')
                                ->get();                        

         // Newlly added =================
        // ==============================
        $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
        $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('demand_id','!=',$demandId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
        $itemDmnArr = array_unique($itemDmnArr);
        // End newlly added =================
        // ==================================

        $suppliersInf  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
                ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
                ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
                ->get();                        

        $mainArray = array();

        $supArray              =  array();
        $supTotalAmountArray   =  array();
        $supWiComArray         =  array();
        
        foreach ($lotNames as $lotkey => $lotval) {
                
            $selectSuppLower = array_map('current',\App\LotToSupplier::select('mnd_to_col_qtn_id')
                                                ->where('lot_name_id','=',$lotval->id)
                                                ->orderBy('lot_wise_total','asc')
                                                ->get()->toArray());

            $itemToDemResult = ItemToDemand::where('demand_id','=',$demandId)
                                            ->where('tender_no','=',$tenderId)
                                            //->where('lot_name','=',$lotval->lot_name)
                                            ->where('lot_unq_id','=',$lotval->id)
                                            ->orderBy('id','asc')
                                            ->get();                           

            $lotNameInIndex = $lotval->lot_name;

            $targetArray  = array();
            $sls          = 0;
            $arIn         = 0;

            $itemToDemResultIds = array_map('current',ItemToDemand::select('id')
                                            ->where('demand_id','=',$demandId)
                                            ->where('tender_no','=',$tenderId)
                                            //->where('lot_name','=',$lotval->lot_name)
                                            ->where('lot_unq_id','=',$lotval->id)
                                            ->orderBy('id','asc')
                                            ->get()->toArray());     

            $itemWiseSupp = array_unique(array_map('current',DemandSuppllierToCollQuotToItem::
                                            select('dmn_to_cal_qut_id')
                                            ->whereIn('item_id',$itemToDemResultIds)
                                            ->where('quoted_quantity','>',0)
                                            ->get()->toArray()));
            
                
                $supplierResultFir[$lotNameInIndex]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->join('lot_to_supplier','demand_to_collection_quotation.id','=','lot_to_supplier.mnd_to_col_qtn_id')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','lot_to_supplier.id as lot_to_sup_id','lot_to_supplier.lot_name_id','lot_to_supplier.mnd_to_col_qtn_id','lot_to_supplier.mnd_to_col_qtn_id','lot_to_supplier.lot_wise_total_qty','lot_to_supplier.lot_wise_total','lot_to_supplier.altr_quoted_total_quantity','lot_to_supplier.altr_total_price','lot_to_supplier.lot_select_as_draft_cst','lot_to_supplier.comnt_on_cst_ech_lot')
                    ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->where('lot_to_supplier.lot_name_id','=',$lotval->id)
                    ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                    //->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                    ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total_qty, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total, DECIMAL)"),'asc')
                    ->get();

                    foreach ($supplierResultFir[$lotNameInIndex] as $srs) {
                        $supArray[$lotNameInIndex][] = $srs->suppliernametext.'<br>'.$srs->head_office_address.'?'.$srs->id.'?'.$srs->altr_total_price.'?'.$srs->lot_select_as_draft_cst.'?'.$srs->lot_name_id.'?'.$srs->lot_to_sup_id;

                        $supTotalAmountArray[$lotNameInIndex][] = $srs->lot_wise_total.'?'.$srs->altr_total_price;

                        $supWiComArray[$lotNameInIndex][] = $srs->comnt_on_cst_ech_lot.'?'.$srs->lot_to_sup_id.'?'.$srs->altr_total_price.'?'.$srs->id;
                    }
            
            foreach ($itemToDemResult as $key => $value) {

                $itemWiseSupp = array_unique(array_map('current',DemandSuppllierToCollQuotToItem::
                                            select('dmn_to_cal_qut_id')
                                            ->where('item_id','=',$value->id)
                                            ->where('quoted_quantity','>',0)
                                            ->get()->toArray()));
                
                $forJonPerfect = array_map('current',DemandSuppllierToCollQuotToItem::
                                            select('id')
                                            ->where('item_id','=',$value->id)
                                            ->whereIn('dmn_to_cal_qut_id',$itemWiseSupp)
                                            ->where('quoted_quantity','>',0)
                                            ->get()->toArray());

                $supplierResult[$sls]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->join('lot_to_supplier','demand_to_collection_quotation.id','=','lot_to_supplier.mnd_to_col_qtn_id')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','demand_supplier_to_coll_qut_to_item.total_price','demand_supplier_to_coll_qut_to_item.discount_amount','demand_supplier_to_coll_qut_to_item.alternative_unit_price','demand_supplier_to_coll_qut_to_item.alternative_total_price','demand_supplier_to_coll_qut_to_item.comnt_on_cst_ech_itm','demand_supplier_to_coll_qut_to_item.id as cmnt_item_id','demand_supplier_to_coll_qut_to_item.select_as_draft_cst as item_select_as_draft_cst')
                    ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->where('lot_to_supplier.lot_name_id','=',$lotval->id)
                    ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                    ->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                    ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total_qty, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total, DECIMAL)"),'asc')
                    ->get();
                
                $targetArray[$sls]['items'] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                    ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                    ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                    ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName')
                    ->where('item_to_demand.demand_id','=',$demandId)
                    ->where('item_to_demand.tender_no','=',$tenderId)
                    ->where('demand_supplier_to_coll_qut_to_item.item_id','=',$value->id)
                    //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                    ->orderBy('item_to_demand.id','asc')
                    ->orderBy('demand_supplier_to_coll_qut_to_item.total_price','asc')
                    ->groupBy('demand_supplier_to_coll_qut_to_item.item_id')
                    ->get();

                    // Newly added =====================================================
                    // =================================================================
                    
                    $winnerInfOfThisItem = DemandToCollectionQuotation::join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id', '=', 'demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->select('demand_supplier_to_coll_qut_to_item.unit_price as last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                        ->where('demand_supplier_to_coll_qut_to_item.demand_id','!=',$value->demand_id)
                        ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                        ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
                        //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                        ->whereIn('demand_to_collection_quotation.id',$itemDmnArr)
                        // ->where('demand_to_collection_quotation.winner','=',1)
                        ->orderBy('demand_supplier_to_coll_qut_to_item.id','DESC')
                        ->get();
               
                    if(count($winnerInfOfThisItem) > 0){
                        foreach ($winnerInfOfThisItem as $key => $winnerIns) {
                            $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', $winnerIns->suppliernametext);
                            $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', $winnerIns->last_unti_price);
                            $targetArray[$sls]['items'][0]->setAttribute('previousDates', date('d-m-Y',strtotime($winnerIns->updated_at)));
                        }
                        
                    }else{
                        $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', '');
                        $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', '');
                        $targetArray[$sls]['items'][0]->setAttribute('previousDates', '');
                    }                        
                    // End newly added =================================================
                    // =================================================================

                foreach ($supplierResult[$sls] as $sr) {
                    $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('demand_id','=',$demandId)
                                                    ->where('tender_id','=',$tenderId)
                                                    ->where('item_id','=',$value->id)
                                                    ->where('dmn_to_cal_qut_id','=',$sr->id)
                                                    ->where('supplier_id','=',$sr->supplier_name)
                                                    ->get();
                                                    
                }

                    $sls++;
                    $arIn++;

            }

            $mainArray[$lotNameInIndex] = $targetArray;

        }

        $tenderData = [
                    //'itemList' => $itemList,
                    'supplierResult' => $supplierResult,
                    'targetArray' => $targetArray,
                    'supArray' => $supArray,
                    'supTotalAmountArray' => $supTotalAmountArray,
                    'demandId' => $demandId,
                    //'supplierAllreadySelected' => $supplierAllreadySelected,
                    'supWiComArray' => $supWiComArray,
                    'tender' => $tender,
                    'demand' => $demand,
                    'tenderId' => $tenderId,
                    'suppliersInf' => $suppliersInf,
                    'supplierResultFir' => $supplierResultFir,
                    'orgInfo' => $orgInfo,
                    'mainArray' => $mainArray
                ];

        $pdf= PDF::loadView('cst-print.cst-lot-view-pdf',$tenderData,[],['format' => [215.9, 342.9], 'orientation' => 'L']);
        return $pdf->stream('cst-view.pdf');        
        
        // echo "<pre>"; print_r($mainArray); exit;
        //return View::make('lot-cst.cst-view')->with(compact('itemList','supplierResult','targetArray','supArray','supTotalAmountArray','demandId','supplierAllreadySelected','supWiComArray','demand','tenderId','tender','suppliersInf','supplierResultFir','mainArray'));
        }
        
    }// End cst view print ================

    public function draftCstViewPrint($id){

        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

        $nsdId = 1;
         if(!empty(Auth::user()->nsd_bsd)){
            $nsdId = Auth::user()->nsd_bsd;
         }
        $orgInfo  = \App\NsdName::find($nsdId);

        $demandId = $explodes[0];
        $tenderId = $explodes[1];
        $demand     = \App\Demand::find($demandId);
        $tender     = \App\DemandToTender::where('demand_id','=',$demandId)->where('tender_id','=',$tenderId)->first();

        $mainTenderInfo = \App\Tender::find($tenderId);

        if($mainTenderInfo->tender_nature != 2){

            $selectedAsDraftCst = array_map('current',DemandSuppllierToCollQuotToItem::select('item_id')
                                            ->where('demand_id','=',$demandId)
                                            ->where('tender_id','=',$tenderId)
                                            ->where('select_as_draft_cst','=',1)
                                            ->get()->toArray());

            $itemToDemResult = ItemToDemand::where('demand_id','=',$demandId)
                                            ->where('tender_no','=',$tenderId)
                                            ->whereIn('id',$selectedAsDraftCst)
                                            ->orderBy('id','asc')->get();

            // Newlly added =================
            // ==============================
            $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
            $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('demand_id','!=',$demandId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
            $itemDmnArr = array_unique($itemDmnArr);
            // End newlly added =================
            // ==================================

            // for keeping checked ===============================
            // ===================================================
            $supplierAllreadySelected  = array_map('current',DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
            ->select('demand_to_collection_quotation.id')
            ->where('demand_to_collection_quotation.demand_id','=',$demandId)
            ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
            ->where('demand_to_collection_quotation.sel_as_draft_cst','=',1)
            ->orderBy('demand_to_collection_quotation.total','asc')
            ->get()->toArray());
     
            $targetArray  = array();
            $sls          = 0;
            $arIn         = 0;

            $supArray              =  array();
            $supTotalAmountArray   =  array();
            $supWiComArray         =  array();

            $suppliersInf  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
                    ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->where('demand_to_collection_quotation.sel_as_draft_cst','=',1)
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
                    ->get();

            foreach ($itemToDemResult as $key => $value) {

                $itemWiseSupp = array_map('current',DemandSuppllierToCollQuotToItem::
                                            select('dmn_to_cal_qut_id')
                                            ->where('item_id','=',$value->id)
                                            ->where('select_as_draft_cst','=',1)
                                            ->where('quoted_quantity','>',0)
                                            ->get()->toArray());
                $forJonPerfect = array_map('current',DemandSuppllierToCollQuotToItem::
                                            select('id')
                                            ->where('item_id','=',$value->id)
                                            ->where('quoted_quantity','>',0)
                                            ->get()->toArray());
                
                $supplierResult[$sls]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','demand_supplier_to_coll_qut_to_item.total_price','demand_supplier_to_coll_qut_to_item.discount_amount','demand_supplier_to_coll_qut_to_item.alternative_unit_price','demand_supplier_to_coll_qut_to_item.alternative_total_price','demand_supplier_to_coll_qut_to_item.comnt_on_cst_ech_itm','demand_supplier_to_coll_qut_to_item.id as cmnt_item_id','demand_supplier_to_coll_qut_to_item.select_as_draft_cst as item_select_as_draft_cst','demand_supplier_to_coll_qut_to_item.select_as_winner as select_as_winner')
                    ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                    ->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                    ->orderBy(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.quoted_quantity, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.total_price, DECIMAL)"),'asc')
                    ->get();
                
                    foreach ($supplierResult[$sls] as $srs) {
                        $supArray[$sls][] = $srs->suppliernametext.'<br>'.$srs->head_office_address.'?'.$srs->id.'?'.$srs->alternative_unit_price.'?'.$srs->item_select_as_draft_cst.'?'.$srs->select_as_winner;
                        $supTotalAmountArray[$sls][] = $srs->total_price.'?'.$srs->alternative_total_price;

                        $supWiComArray[$sls][] = $srs->comnt_on_cst_ech_itm.'?'.$srs->cmnt_item_id.'?'.$srs->alternative_unit_price.'?'.$srs->id;
                    }

                $targetArray[$sls]['items'] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                    ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                    ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                    ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName')
                    ->where('item_to_demand.demand_id','=',$demandId)
                    ->where('item_to_demand.tender_no','=',$tenderId)
                    ->where('demand_supplier_to_coll_qut_to_item.item_id','=',$value->id)
                    //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                    ->orderBy('item_to_demand.id','asc')
                    ->orderBy('demand_supplier_to_coll_qut_to_item.total_price','asc')
                    ->groupBy('demand_supplier_to_coll_qut_to_item.item_id')
                    ->get();

                    // Newly added =====================================================
                    // =================================================================
                    
                    $winnerInfOfThisItem = DemandToCollectionQuotation::join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id', '=', 'demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->select('demand_supplier_to_coll_qut_to_item.unit_price as last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                        ->where('demand_supplier_to_coll_qut_to_item.demand_id','!=',$value->demand_id)
                        ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                        ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
                        //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                        ->whereIn('demand_to_collection_quotation.id',$itemDmnArr)
                        // ->where('demand_to_collection_quotation.winner','=',1)
                        ->orderBy('demand_supplier_to_coll_qut_to_item.id','DESC')
                        ->get();
               
                    if(count($winnerInfOfThisItem) > 0){
                        foreach ($winnerInfOfThisItem as $key => $winnerIns) {
                            $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', $winnerIns->suppliernametext);
                            $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', $winnerIns->last_unti_price);
                            $targetArray[$sls]['items'][0]->setAttribute('previousDates', date('d-m-Y',strtotime($winnerIns->updated_at)));
                        }
                        
                    }else{
                        $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', '');
                        $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', '');
                        $targetArray[$sls]['items'][0]->setAttribute('previousDates', '');
                    }                        
                    // End newly added =================================================
                    // =================================================================

                foreach ($supplierResult[$sls] as $sr) {
                    $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('demand_id','=',$demandId)
                                                    ->where('tender_id','=',$tenderId)
                                                    ->where('item_id','=',$value->id)
                                                    ->where('dmn_to_cal_qut_id','=',$sr->id)
                                                    ->where('supplier_id','=',$sr->supplier_name)
                                                    ->get();
                                                    
                    //echo "<pre>"; print_r($sr->supplier_name); exit;
                }

                    $sls++;
                    $arIn++;
            }
            // echo "<pre>"; print_r($targetArray); exit;
            
            $tenderData = [
                    //'itemList' => $itemList,
                    'supplierResult' => $supplierResult,
                    'targetArray' => $targetArray,
                    'supArray' => $supArray,
                    'supTotalAmountArray' => $supTotalAmountArray,
                    'demandId' => $demandId,
                    'supplierAllreadySelected' => $supplierAllreadySelected,
                    'supWiComArray' => $supWiComArray,
                    'tender' => $tender,
                    'demand' => $demand,
                    'tenderId' => $tenderId,
                    'suppliersInf' => $suppliersInf,
                    'orgInfo' => $orgInfo,
                    //'supplierResultFir' => $supplierResultFir
                    //'mainArray' => $mainArray
                ];

                $pdf= PDF::loadView('cst-print.final-cst-line-view-pdf',$tenderData,[],['format' => [215.9, 342.9], 'orientation' => 'L']);
                return $pdf->stream('cst-view.pdf');

            //return View::make('demands.cst-draft-view')->with(compact('itemList','supplierResult','targetArray','supArray','supTotalAmountArray','demandId','supplierAllreadySelected','supWiComArray','demand','tenderId','tender','suppliersInf'));
        }else{

            $selectedIds = array_map('current',\App\LotToSupplier::select('lot_name_id')
                                                ->where('lot_select_as_draft_cst','=',1)
                                                ->get()
                                                ->toArray());

            $lotNames = \App\LotNames::where('demand_id','=',$demandId)
                                    ->where('tender_id','=',$tenderId)
                                    ->whereIn('id',$selectedIds)
                                    ->orderBy('id','asc')
                                    ->get();                        

             // Newlly added =================
            // ==============================
            $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
            $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('demand_id','!=',$demandId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
            $itemDmnArr = array_unique($itemDmnArr);
            // End newlly added =================
            // ==================================

            $suppliersInf  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
                    ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
                    ->get();                        

            $mainArray = array();

            $supArray              =  array();
            $supTotalAmountArray   =  array();
            $supWiComArray         =  array();
            
            foreach ($lotNames as $lotkey => $lotval) {
                    
                $selectSuppLower = array_map('current',\App\LotToSupplier::select('mnd_to_col_qtn_id')
                                                    ->where('lot_name_id','=',$lotval->id)
                                                    ->where('lot_select_as_draft_cst','=',1)
                                                    ->orderBy('lot_wise_total','asc')
                                                    ->get()->toArray());

                $itemToDemResult = ItemToDemand::where('demand_id','=',$demandId)
                                                ->where('tender_no','=',$tenderId)
                                                ->where('lot_unq_id','=',$lotval->id)
                                                ->orderBy('id','asc')
                                                ->get();                           

                $lotNameInIndex = $lotval->lot_name;
                $targetArray  = array();
                $sls          = 0;
                $arIn         = 0;

                $itemToDemResultIds = array_map('current',ItemToDemand::select('id')
                                                ->where('demand_id','=',$demandId)
                                                ->where('tender_no','=',$tenderId)
                                                ->where('lot_unq_id','=',$lotval->id)
                                                ->orderBy('id','asc')
                                                ->get()->toArray());     

                $itemWiseSupp = array_unique(array_map('current',DemandSuppllierToCollQuotToItem::
                                                select('dmn_to_cal_qut_id')
                                                ->where('select_as_draft_cst','=',1)
                                                ->whereIn('item_id',$itemToDemResultIds)
                                                ->where('quoted_quantity','>',0)
                                                ->get()->toArray()));
                
                    $supplierResultFir[$lotNameInIndex]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                        ->join('lot_to_supplier','demand_to_collection_quotation.id','=','lot_to_supplier.mnd_to_col_qtn_id')
                        ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','lot_to_supplier.id as lot_to_sup_id','lot_to_supplier.lot_name_id','lot_to_supplier.mnd_to_col_qtn_id','lot_to_supplier.mnd_to_col_qtn_id','lot_to_supplier.lot_wise_total_qty','lot_to_supplier.lot_wise_total','lot_to_supplier.altr_quoted_total_quantity','lot_to_supplier.altr_total_price','lot_to_supplier.lot_select_as_draft_cst','lot_to_supplier.comnt_on_cst_ech_lot','lot_to_supplier.lot_select_as_winner')
                        ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                        ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                        ->where('lot_to_supplier.lot_name_id','=',$lotval->id)
                        ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                        //->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total_qty, DECIMAL)"),'desc')
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total, DECIMAL)"),'asc')
                        ->get();

                        foreach ($supplierResultFir[$lotNameInIndex] as $srs) {
                            $supArray[$lotNameInIndex][] = $srs->suppliernametext.'<br>'.$srs->head_office_address.'?'.$srs->id.'?'.$srs->altr_total_price.'?'.$srs->lot_select_as_winner.'?'.$srs->lot_name_id.'?'.$srs->lot_to_sup_id;

                            $supTotalAmountArray[$lotNameInIndex][] = $srs->lot_wise_total.'?'.$srs->altr_total_price;

                            $supWiComArray[$lotNameInIndex][] = $srs->comnt_on_cst_ech_lot.'?'.$srs->lot_to_sup_id.'?'.$srs->altr_total_price.'?'.$srs->id;
                        }
                
                foreach ($itemToDemResult as $key => $value) {
                    $itemWiseSupp = array_unique(array_map('current',DemandSuppllierToCollQuotToItem::
                                                select('dmn_to_cal_qut_id')
                                                ->where('item_id','=',$value->id)
                                                ->where('quoted_quantity','>',0)
                                                ->get()->toArray()));
                    
                    $forJonPerfect = array_map('current',DemandSuppllierToCollQuotToItem::
                                                select('id')
                                                ->where('item_id','=',$value->id)
                                                ->whereIn('dmn_to_cal_qut_id',$itemWiseSupp)
                                                ->where('quoted_quantity','>',0)
                                                ->get()->toArray());

                    $supplierResult[$sls]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                        ->join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->join('lot_to_supplier','demand_to_collection_quotation.id','=','lot_to_supplier.mnd_to_col_qtn_id')
                        ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','demand_supplier_to_coll_qut_to_item.total_price','demand_supplier_to_coll_qut_to_item.discount_amount','demand_supplier_to_coll_qut_to_item.alternative_unit_price','demand_supplier_to_coll_qut_to_item.alternative_total_price','demand_supplier_to_coll_qut_to_item.comnt_on_cst_ech_itm','demand_supplier_to_coll_qut_to_item.id as cmnt_item_id','demand_supplier_to_coll_qut_to_item.select_as_draft_cst as item_select_as_draft_cst')
                        ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                        ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                        ->where('lot_to_supplier.lot_name_id','=',$lotval->id)
                        ->where('demand_supplier_to_coll_qut_to_item.select_as_draft_cst','=',1)
                        ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                        ->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total_qty, DECIMAL)"),'desc')
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total, DECIMAL)"),'asc')
                        ->get();
                    
                    $targetArray[$sls]['items'] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                        ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                        ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                        ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName')
                        ->where('item_to_demand.demand_id','=',$demandId)
                        ->where('item_to_demand.tender_no','=',$tenderId)
                        ->where('demand_supplier_to_coll_qut_to_item.item_id','=',$value->id)
                        //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                        ->orderBy('item_to_demand.id','asc')
                        ->orderBy('demand_supplier_to_coll_qut_to_item.total_price','asc')
                        ->groupBy('demand_supplier_to_coll_qut_to_item.item_id')
                        ->get();

                        // Newly added =====================================================
                        // =================================================================
                        
                        $winnerInfOfThisItem = DemandToCollectionQuotation::join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id', '=', 'demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                            ->select('demand_supplier_to_coll_qut_to_item.unit_price as last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                            ->where('demand_supplier_to_coll_qut_to_item.demand_id','!=',$value->demand_id)
                            ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                            ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
                            //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                            ->whereIn('demand_to_collection_quotation.id',$itemDmnArr)
                            // ->where('demand_to_collection_quotation.winner','=',1)
                            ->orderBy('demand_supplier_to_coll_qut_to_item.id','DESC')
                            ->get();
                   
                        if(count($winnerInfOfThisItem) > 0){
                            foreach ($winnerInfOfThisItem as $key => $winnerIns) {
                                $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', $winnerIns->suppliernametext);
                                $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', $winnerIns->last_unti_price);
                                $targetArray[$sls]['items'][0]->setAttribute('previousDates', date('d-m-Y',strtotime($winnerIns->updated_at)));
                            }
                            
                        }else{
                            $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', '');
                            $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', '');
                            $targetArray[$sls]['items'][0]->setAttribute('previousDates', '');
                        }                        
                        // End newly added =================================================
                        // =================================================================
                    foreach ($supplierResult[$sls] as $sr) {
                        $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('demand_id','=',$demandId)
                                                        ->where('tender_id','=',$tenderId)
                                                        ->where('item_id','=',$value->id)
                                                        ->where('dmn_to_cal_qut_id','=',$sr->id)
                                                        ->where('supplier_id','=',$sr->supplier_name)
                                                        ->get();                          
                    }
                        $sls++;
                        $arIn++;
                }

                $mainArray[$lotNameInIndex] = $targetArray;

            }        
            
            $tenderData = [
                    //'itemList' => $itemList,
                    'supplierResult' => $supplierResult,
                    'targetArray' => $targetArray,
                    'supArray' => $supArray,
                    'supTotalAmountArray' => $supTotalAmountArray,
                    'demandId' => $demandId,
                    //'supplierAllreadySelected' => $supplierAllreadySelected,
                    'supWiComArray' => $supWiComArray,
                    'tender' => $tender,
                    'demand' => $demand,
                    'tenderId' => $tenderId,
                    'suppliersInf' => $suppliersInf,
                    'supplierResultFir' => $supplierResultFir,
                    'orgInfo' => $orgInfo,
                    'mainArray' => $mainArray
                ];

                $pdf= PDF::loadView('cst-print.final-cst-lot-view-pdf',$tenderData,[],['format' => [215.9, 342.9], 'orientation' => 'L']);
                return $pdf->stream('cst-view.pdf');

            //return View::make('lot-cst.cst-draft-view')->with(compact('itemList','supplierResult','targetArray','supArray','supTotalAmountArray','demandId','supplierAllreadySelected','supWiComArray','demand','tenderId','tender','suppliersInf','supplierResultFir','mainArray'));

        }// End of tender nature lot
    }

    public function nhqCstViewPrint($id){

        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

        $nsdId = 1;
         if(!empty(Auth::user()->nsd_bsd)){
            $nsdId = Auth::user()->nsd_bsd;
         }
        $orgInfo  = \App\NsdName::find($nsdId);

        $demandId = $explodes[0];
        $tenderId = $explodes[1];
        $demand     = \App\Demand::find($demandId);
        $tender     = \App\DemandToTender::where('demand_id','=',$demandId)->where('tender_id','=',$tenderId)->first();

        $mainTenderInfo = \App\Tender::find($tenderId);

        if($mainTenderInfo->tender_nature != 2){

            $selectedAsDraftCst = array_map('current',DemandSuppllierToCollQuotToItem::select('item_id')
                                            ->where('demand_id','=',$demandId)
                                            ->where('tender_id','=',$tenderId)
                                            ->where('select_as_draft_cst','=',1)
                                            ->get()->toArray());

            $itemToDemResult = ItemToDemand::where('demand_id','=',$demandId)
                                            ->where('tender_no','=',$tenderId)
                                            ->whereIn('id',$selectedAsDraftCst)
                                            ->orderBy('id','asc')->get();

            // Newlly added =================
            // ==============================
            $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
            $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('demand_id','!=',$demandId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
            $itemDmnArr = array_unique($itemDmnArr);
            // End newlly added =================
            // ==================================

            // for keeping checked ===============================
            // ===================================================
            $supplierAllreadySelected  = array_map('current',DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
            ->select('demand_to_collection_quotation.id')
            ->where('demand_to_collection_quotation.demand_id','=',$demandId)
            ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
            ->where('demand_to_collection_quotation.sel_as_draft_cst','=',1)
            ->orderBy('demand_to_collection_quotation.total','asc')
            ->get()->toArray());
     
            $targetArray  = array();
            $sls          = 0;
            $arIn         = 0;

            $supArray              =  array();
            $supTotalAmountArray   =  array();
            $supWiComArray         =  array();

            $suppliersInf  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
                    ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->where('demand_to_collection_quotation.sel_as_draft_cst','=',1)
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
                    ->get();

            foreach ($itemToDemResult as $key => $value) {

                $itemWiseSupp = array_map('current',DemandSuppllierToCollQuotToItem::
                                            select('dmn_to_cal_qut_id')
                                            ->where('item_id','=',$value->id)
                                            ->where('select_as_draft_cst','=',1)
                                            ->where('quoted_quantity','>',0)
                                            ->get()->toArray());
                $forJonPerfect = array_map('current',DemandSuppllierToCollQuotToItem::
                                            select('id')
                                            ->where('item_id','=',$value->id)
                                            ->where('quoted_quantity','>',0)
                                            ->get()->toArray());
                
                $supplierResult[$sls]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','demand_supplier_to_coll_qut_to_item.total_price','demand_supplier_to_coll_qut_to_item.discount_amount','demand_supplier_to_coll_qut_to_item.alternative_unit_price','demand_supplier_to_coll_qut_to_item.alternative_total_price','demand_supplier_to_coll_qut_to_item.comnt_on_cst_ech_itm','demand_supplier_to_coll_qut_to_item.id as cmnt_item_id','demand_supplier_to_coll_qut_to_item.select_as_draft_cst as item_select_as_draft_cst','demand_supplier_to_coll_qut_to_item.select_as_winner as select_as_winner')
                    ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                    ->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                    ->orderBy(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.quoted_quantity, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.total_price, DECIMAL)"),'asc')
                    ->get();
                
                    foreach ($supplierResult[$sls] as $srs) {
                        $supArray[$sls][] = $srs->suppliernametext.'<br>'.$srs->head_office_address.'?'.$srs->id.'?'.$srs->alternative_unit_price.'?'.$srs->item_select_as_draft_cst.'?'.$srs->select_as_winner;
                        $supTotalAmountArray[$sls][] = $srs->total_price.'?'.$srs->alternative_total_price;

                        $supWiComArray[$sls][] = $srs->comnt_on_cst_ech_itm.'?'.$srs->cmnt_item_id.'?'.$srs->alternative_unit_price.'?'.$srs->id;
                    }

                $targetArray[$sls]['items'] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                    ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                    ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                    ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName', 'item_to_demand.id as itm_to_dn_id')
                    ->where('item_to_demand.demand_id','=',$demandId)
                    ->where('item_to_demand.tender_no','=',$tenderId)
                    ->where('demand_supplier_to_coll_qut_to_item.item_id','=',$value->id)
                    //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                    ->orderBy('item_to_demand.id','asc')
                    ->orderBy('demand_supplier_to_coll_qut_to_item.total_price','asc')
                    ->groupBy('demand_supplier_to_coll_qut_to_item.item_id')
                    ->get();

                    // Newly added =====================================================
                    // =================================================================
                    
                    $winnerInfOfThisItem = DemandToCollectionQuotation::join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id', '=', 'demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->select('demand_supplier_to_coll_qut_to_item.unit_price as last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                        ->where('demand_supplier_to_coll_qut_to_item.demand_id','!=',$value->demand_id)
                        ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                        ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
                        //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                        ->where('demand_supplier_to_coll_qut_to_item.select_as_winner','=',1)
                        // ->where('demand_to_collection_quotation.winner','=',1)
                        ->orderBy('demand_supplier_to_coll_qut_to_item.id','DESC')
                        ->get();
               
                    if(count($winnerInfOfThisItem) > 0){
                        foreach ($winnerInfOfThisItem as $key => $winnerIns) {
                            $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', $winnerIns->suppliernametext);
                            $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', $winnerIns->last_unti_price);
                            $targetArray[$sls]['items'][0]->setAttribute('previousDates', date('d-m-Y',strtotime($winnerIns->updated_at)));
                        }
                        
                    }else{
                        $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', '');
                        $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', '');
                        $targetArray[$sls]['items'][0]->setAttribute('previousDates', '');
                    }                        
                    // End newly added =================================================
                    // =================================================================

                foreach ($supplierResult[$sls] as $sr) {
                    $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('demand_id','=',$demandId)
                                                    ->where('tender_id','=',$tenderId)
                                                    ->where('item_id','=',$value->id)
                                                    ->where('dmn_to_cal_qut_id','=',$sr->id)
                                                    ->where('supplier_id','=',$sr->supplier_name)
                                                    ->get();
                                                    
                    //echo "<pre>"; print_r($sr->supplier_name); exit;
                }

                    $sls++;
                    $arIn++;
            }
            // echo "<pre>"; print_r($targetArray); exit;

            $tenderData = [
                    //'itemList' => $itemList,
                    'supplierResult' => $supplierResult,
                    'targetArray' => $targetArray,
                    'supArray' => $supArray,
                    'supTotalAmountArray' => $supTotalAmountArray,
                    'demandId' => $demandId,
                    'supplierAllreadySelected' => $supplierAllreadySelected,
                    'supWiComArray' => $supWiComArray,
                    'tender' => $tender,
                    'demand' => $demand,
                    'tenderId' => $tenderId,
                    'suppliersInf' => $suppliersInf,
                    'orgInfo' => $orgInfo,
                    //'supplierResultFir' => $supplierResultFir
                    //'mainArray' => $mainArray
                ];

                $pdf= PDF::loadView('cst-print.nhq-cst-line-view-pdf',$tenderData,[],['format' => [215.9, 342.9], 'orientation' => 'L']);
                return $pdf->stream('cst-view.pdf');
            
            //return View::make('nhq-approval.cst-draft-view-item')->with(compact('itemList','supplierResult','targetArray','supArray','supTotalAmountArray','demandId','supplierAllreadySelected','supWiComArray','demand','tenderId','tender','suppliersInf'));
        }else{

            $selectedIds = array_map('current',\App\LotToSupplier::select('lot_name_id')
                                                ->where('lot_select_as_draft_cst','=',1)
                                                ->get()
                                                ->toArray());

            $lotNames = \App\LotNames::where('demand_id','=',$demandId)
                                    ->where('tender_id','=',$tenderId)
                                    ->whereIn('id',$selectedIds)
                                    ->orderBy('id','asc')
                                    ->get();                        

             // Newlly added =================
            // ==============================
            $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
            $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('demand_id','!=',$demandId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
            $itemDmnArr = array_unique($itemDmnArr);
            // End newlly added =================
            // ==================================

            $suppliersInf  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
                    ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
                    ->get();                        

            $mainArray = array();

            $supArray              =  array();
            $supTotalAmountArray   =  array();
            $supWiComArray         =  array();
            
            foreach ($lotNames as $lotkey => $lotval) {
                    
                $selectSuppLower = array_map('current',\App\LotToSupplier::select('mnd_to_col_qtn_id')
                                                    ->where('lot_name_id','=',$lotval->id)
                                                    ->where('lot_select_as_draft_cst','=',1)
                                                    ->orderBy('lot_wise_total','asc')
                                                    ->get()->toArray());

                $itemToDemResult = ItemToDemand::where('demand_id','=',$demandId)
                                                ->where('tender_no','=',$tenderId)
                                                ->where('lot_unq_id','=',$lotval->id)
                                                ->orderBy('id','asc')
                                                ->get();                           

                $lotNameInIndex = $lotval->lot_name;
                $targetArray  = array();
                $sls          = 0;
                $arIn         = 0;

                $itemToDemResultIds = array_map('current',ItemToDemand::select('id')
                                                ->where('demand_id','=',$demandId)
                                                ->where('tender_no','=',$tenderId)
                                                ->where('lot_unq_id','=',$lotval->id)
                                                ->orderBy('id','asc')
                                                ->get()->toArray());     

                $itemWiseSupp = array_unique(array_map('current',DemandSuppllierToCollQuotToItem::
                                                select('dmn_to_cal_qut_id')
                                                ->where('select_as_draft_cst','=',1)
                                                ->whereIn('item_id',$itemToDemResultIds)
                                                ->where('quoted_quantity','>',0)
                                                ->get()->toArray()));
                
                    $supplierResultFir[$lotNameInIndex]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                        ->join('lot_to_supplier','demand_to_collection_quotation.id','=','lot_to_supplier.mnd_to_col_qtn_id')
                        ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','lot_to_supplier.id as lot_to_sup_id','lot_to_supplier.lot_name_id','lot_to_supplier.mnd_to_col_qtn_id','lot_to_supplier.mnd_to_col_qtn_id','lot_to_supplier.lot_wise_total_qty','lot_to_supplier.lot_wise_total','lot_to_supplier.altr_quoted_total_quantity','lot_to_supplier.altr_total_price','lot_to_supplier.lot_select_as_draft_cst','lot_to_supplier.comnt_on_cst_ech_lot','lot_to_supplier.lot_select_as_winner')
                        ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                        ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                        ->where('lot_to_supplier.lot_name_id','=',$lotval->id)
                        ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                        //->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total_qty, DECIMAL)"),'desc')
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total, DECIMAL)"),'asc')
                        ->get();

                        foreach ($supplierResultFir[$lotNameInIndex] as $srs) {
                            $supArray[$lotNameInIndex][] = $srs->suppliernametext.'<br>'.$srs->head_office_address.'?'.$srs->id.'?'.$srs->altr_total_price.'?'.$srs->lot_select_as_winner.'?'.$srs->lot_name_id.'?'.$srs->lot_to_sup_id;

                            $supTotalAmountArray[$lotNameInIndex][] = $srs->lot_wise_total.'?'.$srs->altr_total_price;

                            $supWiComArray[$lotNameInIndex][] = $srs->comnt_on_cst_ech_lot.'?'.$srs->lot_to_sup_id.'?'.$srs->altr_total_price.'?'.$srs->id;
                        }
                
                foreach ($itemToDemResult as $key => $value) {
                    $itemWiseSupp = array_unique(array_map('current',DemandSuppllierToCollQuotToItem::
                                                select('dmn_to_cal_qut_id')
                                                ->where('item_id','=',$value->id)
                                                ->where('quoted_quantity','>',0)
                                                ->get()->toArray()));
                    
                    $forJonPerfect = array_map('current',DemandSuppllierToCollQuotToItem::
                                                select('id')
                                                ->where('item_id','=',$value->id)
                                                ->whereIn('dmn_to_cal_qut_id',$itemWiseSupp)
                                                ->where('quoted_quantity','>',0)
                                                ->get()->toArray());

                    $supplierResult[$sls]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                        ->join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->join('lot_to_supplier','demand_to_collection_quotation.id','=','lot_to_supplier.mnd_to_col_qtn_id')
                        ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','demand_supplier_to_coll_qut_to_item.total_price','demand_supplier_to_coll_qut_to_item.discount_amount','demand_supplier_to_coll_qut_to_item.alternative_unit_price','demand_supplier_to_coll_qut_to_item.alternative_total_price','demand_supplier_to_coll_qut_to_item.comnt_on_cst_ech_itm','demand_supplier_to_coll_qut_to_item.id as cmnt_item_id','demand_supplier_to_coll_qut_to_item.select_as_draft_cst as item_select_as_draft_cst')
                        ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                        ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                        ->where('lot_to_supplier.lot_name_id','=',$lotval->id)
                        ->where('demand_supplier_to_coll_qut_to_item.select_as_draft_cst','=',1)
                        ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                        ->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total_qty, DECIMAL)"),'desc')
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total, DECIMAL)"),'asc')
                        ->get();
                    
                    $targetArray[$sls]['items'] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                        ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                        ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                        ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName', 'item_to_demand.id as itm_to_dn_id')
                        ->where('item_to_demand.demand_id','=',$demandId)
                        ->where('item_to_demand.tender_no','=',$tenderId)
                        ->where('demand_supplier_to_coll_qut_to_item.item_id','=',$value->id)
                        //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                        ->orderBy('item_to_demand.id','asc')
                        ->orderBy('demand_supplier_to_coll_qut_to_item.total_price','asc')
                        ->groupBy('demand_supplier_to_coll_qut_to_item.item_id')
                        ->get();

                        // Newly added =====================================================
                        // =================================================================
                        
                        $winnerInfOfThisItem = DemandToCollectionQuotation::join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id', '=', 'demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                            ->select('demand_supplier_to_coll_qut_to_item.unit_price as last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                            ->where('demand_supplier_to_coll_qut_to_item.demand_id','!=',$value->demand_id)
                            ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                            ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
                            //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                            ->whereIn('demand_to_collection_quotation.id',$itemDmnArr)
                            // ->where('demand_to_collection_quotation.winner','=',1)
                            ->orderBy('demand_supplier_to_coll_qut_to_item.id','DESC')
                            ->get();
                   
                        if(count($winnerInfOfThisItem) > 0){
                            foreach ($winnerInfOfThisItem as $key => $winnerIns) {
                                $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', $winnerIns->suppliernametext);
                                $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', $winnerIns->last_unti_price);
                                $targetArray[$sls]['items'][0]->setAttribute('previousDates', date('d-m-Y',strtotime($winnerIns->updated_at)));
                            }
                            
                        }else{
                            $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', '');
                            $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', '');
                            $targetArray[$sls]['items'][0]->setAttribute('previousDates', '');
                        }                        
                        // End newly added =================================================
                        // =================================================================
                    foreach ($supplierResult[$sls] as $sr) {
                        $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('demand_id','=',$demandId)
                                                        ->where('tender_id','=',$tenderId)
                                                        ->where('item_id','=',$value->id)
                                                        ->where('dmn_to_cal_qut_id','=',$sr->id)
                                                        ->where('supplier_id','=',$sr->supplier_name)
                                                        ->get();                          
                    }
                        $sls++;
                        $arIn++;
                }

                $mainArray[$lotNameInIndex] = $targetArray;

            }

            $tenderData = [
                    //'itemList' => $itemList,
                    'supplierResult' => $supplierResult,
                    'targetArray' => $targetArray,
                    'supArray' => $supArray,
                    'supTotalAmountArray' => $supTotalAmountArray,
                    'demandId' => $demandId,
                    //'supplierAllreadySelected' => $supplierAllreadySelected,
                    'supWiComArray' => $supWiComArray,
                    'tender' => $tender,
                    'demand' => $demand,
                    'tenderId' => $tenderId,
                    'suppliersInf' => $suppliersInf,
                    'supplierResultFir' => $supplierResultFir,
                    'orgInfo' => $orgInfo,
                    'mainArray' => $mainArray
                ];

                $pdf= PDF::loadView('cst-print.nhq-cst-lot-view-pdf',$tenderData,[],['format' => [215.9, 342.9], 'orientation' => 'L']);
                return $pdf->stream('cst-view.pdf');        
            
            //return View::make('nhq-approval.cst-draft-view-lot')->with(compact('itemList','supplierResult','targetArray','supArray','supTotalAmountArray','demandId','supplierAllreadySelected','supWiComArray','demand','tenderId','tender','suppliersInf','supplierResultFir','mainArray'));

        }// End of tender nature lot

        //return View::make('floating-tender.headqtr-approval-view')->with(compact('demandsUp','tenderId'));
        
    }

    public function cstViewExcel($id){ 
        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);
        $nsdId = 1;
         if(!empty(Auth::user()->nsd_bsd)){
            $nsdId = Auth::user()->nsd_bsd;
         }
        $orgInfo  = \App\NsdName::find($nsdId);

//        $demandId = $id;
        $demandId = $explodes[0];
        $tenderId = $explodes[1];
        $demand     = \App\Demand::find($demandId);
        $tender     = \App\DemandToTender::where('demand_id','=',$demandId)->where('tender_id','=',$tenderId)->first();

        $mainTenderInfo = \App\Tender::find($tenderId);

        if($mainTenderInfo->tender_nature != 2){ // If tender type is line items

        $itemToDemResult = ItemToDemand::where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get();

        // Newlly added =================
        // ==============================
        $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
        $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('demand_id','!=',$demandId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
        $itemDmnArr = array_unique($itemDmnArr);
        // End newlly added =================
        // ==================================

        // for keeping checked ===============================
        // ===================================================
        $supplierAllreadySelected  = array_map('current',DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
        ->select('demand_to_collection_quotation.id')
        ->where('demand_to_collection_quotation.demand_id','=',$demandId)
        ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
        ->where('demand_to_collection_quotation.sel_as_draft_cst','=',1)
        ->orderBy('demand_to_collection_quotation.total','asc')
        ->get()->toArray());
 
        $targetArray  = array();
        $sls          = 0;
        $arIn         = 0;

        $supArray              =  array();
        $supTotalAmountArray   =  array();
        $supWiComArray         =  array();

        $suppliersInf  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
                ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
                ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
                ->get();

        foreach ($itemToDemResult as $key => $value) {

            $itemWiseSupp = array_map('current',DemandSuppllierToCollQuotToItem::
                                        select('dmn_to_cal_qut_id')
                                        ->where('item_id','=',$value->id)
                                        ->where('quoted_quantity','>',0)
                                        ->get()->toArray());
            $forJonPerfect = array_map('current',DemandSuppllierToCollQuotToItem::
                                        select('id')
                                        ->where('item_id','=',$value->id)
                                        ->where('quoted_quantity','>',0)
                                        ->get()->toArray());
            
            $supplierResult[$sls]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                ->join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','demand_supplier_to_coll_qut_to_item.total_price','demand_supplier_to_coll_qut_to_item.discount_amount','demand_supplier_to_coll_qut_to_item.alternative_unit_price','demand_supplier_to_coll_qut_to_item.alternative_total_price','demand_supplier_to_coll_qut_to_item.comnt_on_cst_ech_itm','demand_supplier_to_coll_qut_to_item.id as cmnt_item_id','demand_supplier_to_coll_qut_to_item.select_as_draft_cst as item_select_as_draft_cst')
                ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                ->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                ->orderBy(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.quoted_quantity, DECIMAL)"),'desc')
                ->orderBy(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.total_price, DECIMAL)"),'asc')
                ->get();
            
                foreach ($supplierResult[$sls] as $srs) {
                    $supArray[$sls][] = $srs->suppliernametext.'<br>'.$srs->head_office_address.'?'.$srs->id.'?'.$srs->alternative_unit_price.'?'.$srs->item_select_as_draft_cst;
                    $supTotalAmountArray[$sls][] = $srs->total_price.'?'.$srs->alternative_total_price;

                    $supWiComArray[$sls][] = $srs->comnt_on_cst_ech_itm.'?'.$srs->cmnt_item_id.'?'.$srs->alternative_unit_price.'?'.$srs->id;
                }

            $targetArray[$sls]['items'] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName')
                ->where('item_to_demand.demand_id','=',$demandId)
                ->where('item_to_demand.tender_no','=',$tenderId)
                ->where('demand_supplier_to_coll_qut_to_item.item_id','=',$value->id)
                //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                ->orderBy('item_to_demand.id','asc')
                ->orderBy('demand_supplier_to_coll_qut_to_item.total_price','asc')
                ->groupBy('demand_supplier_to_coll_qut_to_item.item_id')
                ->get();

                // Newly added =====================================================
                // =================================================================
                
                $winnerInfOfThisItem = DemandToCollectionQuotation::join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id', '=', 'demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->select('demand_supplier_to_coll_qut_to_item.unit_price as last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                    ->where('demand_supplier_to_coll_qut_to_item.demand_id','!=',$value->demand_id)
                    ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                    ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
                    //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                    ->whereIn('demand_to_collection_quotation.id',$itemDmnArr)
                    // ->where('demand_to_collection_quotation.winner','=',1)
                    ->orderBy('demand_supplier_to_coll_qut_to_item.id','DESC')
                    ->get();
           
                if(count($winnerInfOfThisItem) > 0){
                    foreach ($winnerInfOfThisItem as $key => $winnerIns) {
                        $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', $winnerIns->suppliernametext);
                        $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', $winnerIns->last_unti_price);
                        $targetArray[$sls]['items'][0]->setAttribute('previousDates', date('d-m-Y',strtotime($winnerIns->updated_at)));
                    }
                    
                }else{
                    $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', '');
                    $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', '');
                    $targetArray[$sls]['items'][0]->setAttribute('previousDates', '');
                }                        
                // End newly added =================================================
                // =================================================================

            foreach ($supplierResult[$sls] as $sr) {
                $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('demand_id','=',$demandId)
                                                ->where('tender_id','=',$tenderId)
                                                ->where('item_id','=',$value->id)
                                                ->where('dmn_to_cal_qut_id','=',$sr->id)
                                                ->where('supplier_id','=',$sr->supplier_name)
                                                ->get();
                                                
                //echo "<pre>"; print_r($sr->supplier_name); exit;
            }

                $sls++;
                $arIn++;
        }

        Excel::create('draft-cst-view - ' . date("d-m-Y H:i"), function ($excel) use ($supplierResult, $targetArray, $supArray, $supTotalAmountArray, $demandId, $supWiComArray, $supplierAllreadySelected, $demand, $tenderId, $tender,$orgInfo,$suppliersInf) {
            $excel->sheet('First Sheet', function ($sheet) use ($supplierResult, $targetArray, $supArray, $supTotalAmountArray, $demandId, $supWiComArray, $supplierAllreadySelected, $demand, $tenderId, $tender,$orgInfo,$suppliersInf) {
                $row = 0;

                //header Part Start
                $hedtxt = (!empty($suppliersInf)) ? ($suppliersInf[0]->total > $orgInfo->purchase_limit) ? 'PRELIMINARY COMPARATIVE STATEMENT- NSSD DHAKA' : 'COMPARATIVE STATEMENT- NSSD DHAKA' :'';
                $headerTxt = trim($hedtxt);
                $row++;
                $sheet->mergeCells('A' . $row . ':J' . $row);
                $sheet->cells('A' . $row . ':J' . $row, function ($cell) {
                    $cell->setFontWeight('bold');
                });
                $sheet->cells('A' . $row, function ($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->row($row, array($headerTxt));
                $headerTxt2 = !empty($tender->created_at) ? $tender->tender_number. " Date:".date('d F Y', strtotime($tender->created_at)): $tender->tender_number;
                $row++;
                $sheet->mergeCells('A' . $row . ':J' . $row);
                $sheet->cells('A' . $row . ':J' . $row, function ($cell) {
                    $cell->setFontWeight('bold');
                });
                $sheet->cells('A' . $row, function ($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->row($row, array($headerTxt2));

                $row++;
                $row++;
                //Report Name End

                function cellsToMergeByColsRow($start = NULL, $end = NULL, $row = NULL){
                    $merge = 'A1:A1';
                    if($start && $end && $row){
                        $start = \PHPExcel_Cell::stringFromColumnIndex($start);
                        $end = \PHPExcel_Cell::stringFromColumnIndex($end);
                        $merge = "$start{$row}:$end{$row}";

                    }

                    return $merge;
                }

                $sl=1;
                if(!empty($targetArray)){
                    foreach($targetArray as $key => $ta){

                        $totalSuppCount = count($supplierResult[$key]);
                        $inEachTable    = 5;
                        $totalTable     = ceil($totalSuppCount/$inEachTable);
                        $xyz            = 1;
                        $startRange     = 0;
                        $endRange       = $inEachTable;
                        $sls = 0;

                        $hedSuppName = array('SL', 'Items Details', 'Deno', 'Quantity', 'Remark');
                        $onlyHed     = array();
                        $row++;
                        
                        while($totalTable >= $xyz){
                            $row++;
                            if($xyz<2){
                                $sheet->cell('A'.$row.':'.'E'.$row, function($cell) {
                                    $cell->setFontWeight('bold');
                                });
                                $sheet->row($row, array('SL', 'Items Details', 'Deno', 'Quantity', 'Remark'));
                            }else{
                                $sheet->mergeCells('A' . $row . ':E' . $row);
                            }

                            $col = 5;
                            if(!empty($supplierResult[$key])){
                                foreach($supplierResult[$key]->slice($startRange, $endRange) as $sr){
                                    ++$sls;
                                    $placeName = OwnLibrary::numToOrdinalWord($sls) .' Lowest';
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    if(empty($sr->alternative_unit_price)){
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                        $sheet->cell($cellNo, function($cell)use($placeName) {
                                            $cell->setAlignment('center');
                                            $cell->setFontWeight('bold');
                                            $cell->setValue($placeName);
                                        });
                                    }else{
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 3, $row));
                                        $sheet->cell($cellNo, function($cell)use($placeName) {
                                            $cell->setAlignment('center');
                                            $cell->setFontWeight('bold');
                                            $cell->setValue($placeName);
                                        });
                                    }
                                    $col++;
                                }
                                
                            }

                        $col = 5;
                        if(!empty($supArray[$key])){ 
                            $row++;
                            if($xyz<2){ 
                                $sheet->mergeCells('A' . $row . ':E' . $row);
                            }else{
                                $sheet->mergeCells('A' . $row . ':E' . $row);
                            }
                            foreach(array_slice($supArray[$key], $startRange, $endRange) as $sui) {
                                $devideData = explode('?',$sui);
                                $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                if(empty($devideData[2])){
                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                    $sheet->cell($cellNo, function($cell)use($devideData) {
                                        $cell->setAlignment('center');
                                        $cell->setFontWeight('bold');
                                        $cell->setValue(str_replace('<br>','',$devideData[0]));
                                    });
                                }else{
                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 3, $row));
                                    $sheet->cell($cellNo, function($cell)use($devideData) {
                                        $cell->setAlignment('center');
                                        $cell->setFontWeight('bold');
                                        $cell->setValue(str_replace('<br>','',$devideData[0]));
                                    });
                                
                                }
                                $col++;
                            }
                        }// End supplier if

                        $col = 5;
                        if(!empty($supArray[$key])) {
                            $row++;
                            if($xyz<2){
                                $sheet->mergeCells('A' . $row . ':E' . $row);
                            }else{
                                $sheet->mergeCells('A' . $row . ':E' . $row);
                            }
                            foreach(array_slice($supArray[$key], $startRange, $endRange) as $suiii){
                                $devideData4 = explode('?',$suiii);
                                $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                if(empty($devideData4[2])){
                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                    $sheet->cell($cellNo, function($cell)use($devideData4) {
                                        $cell->setAlignment('center');
                                        $cell->setValue('');
                                    });
                                }else{
                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                    $sheet->cell($cellNo, function($cell)use($devideData4) {
                                        $cell->setAlignment('center');
                                        $cell->setValue('Main Offer');
                                    });
                                    $col++;

                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                    $sheet->cell(\PHPExcel_Cell::stringFromColumnIndex($col-1).$row, function($cell)use($devideData4) {
                                        $cell->setAlignment('center');
                                        $cell->setValue('Alternative Offer');
                                    });
                                    
                                }
                                $col++;
                            }
                        } // End main and alternative offer

                        $col = 5;
                        if(!empty($supArray[$key])) {
                            $row++;
                            if($xyz<2){
                                $sheet->mergeCells('A' . $row . ':E' . $row);
                            }else{
                                $sheet->mergeCells('A' . $row . ':E' . $row);
                            }
                            foreach(array_slice($supArray[$key], $startRange, $endRange) as $suittt){
                                $devideDataddd = explode('?',$suittt);
                                $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                
                                    $sheet->cell($cellNo, function($cell)use($devideData4) {
                                        $cell->setAlignment('center');
                                        $cell->setValue('Unit Price');
                                    });
                                    $col++;
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->cell($cellNo, function($cell)use($devideData4) {
                                        $cell->setAlignment('center');
                                        $cell->setValue('Total Price');
                                    });

                                if(!empty($devideDataddd[2])){
                                    $col++;
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->cell($cellNo, function($cell)use($devideData4) {
                                        $cell->setAlignment('center');
                                        $cell->setValue('Unit Price');
                                    });

                                    $col++;
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->cell($cellNo, function($cell)use($devideData4) {
                                        $cell->setAlignment('center');
                                        $cell->setValue('Total Price');
                                    });
                                    
                                }
                                $col++;
                            }
                        } // End total price and alternative price

                        // Item section start here========
                        foreach($ta['items'] as $itm){
                            $row++;
                            $remarks = !empty($itm->previsouSuppName) ? 'Sup: '.$itm->previsouSuppName: ''.''.!empty($itm->previsouUnitPrice)?' UP: '.$itm->previsouUnitPrice : ''.''.!empty($itm->previousDates)? $itm->previousDates : '';
                            if($xyz<2){
                                $sheet->row($row, array($sl++, $itm->item_name, $itm->denoName, $itm->unit, $remarks));
                            }else{
                                $sheet->mergeCells('A' . $row . ':E' . $row);
                            }
                        }// End item foreach

                        $col = 5;
                        foreach(array_slice($ta['supi'], $startRange, $endRange) as $sp){
                            if(count($sp)>0){
                                $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                $sheet->cell($cellNo, function($cell)use($sp) {
                                    $cell->setAlignment('right');
                                    $cell->setValue(ImageResizeController::custom_format($sp[0]->unit_price));
                                });
                                $col++;
                                $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                $sheet->cell($cellNo, function($cell)use($sp) {
                                    $cell->setAlignment('right');
                                    $cell->setValue(ImageResizeController::custom_format($sp[0]->unit_price*$sp[0]->quoted_quantity));
                                });

                                if(!empty($sp[0]->alternative_unit_price)){
                                    $col++;
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->cell($cellNo, function($cell)use($sp) {
                                        $cell->setAlignment('right');
                                        $cell->setValue(ImageResizeController::custom_format($sp[0]->alternative_unit_price));
                                    });
                                    $col++;
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->cell($cellNo, function($cell)use($sp) {
                                        $cell->setAlignment('right');
                                        $cell->setValue(ImageResizeController::custom_format($sp[0]->alternative_unit_price*$sp[0]->alternative_quoted_quantity));
                                    });
                                }
                            }else{
                                $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                $sheet->cell($cellNo, function($cell)use($devideData4) {
                                    $cell->setAlignment('center');
                                    $cell->setValue('Not participated');
                                });
                            }
                        $col++;    
                        } // End item price foreach

                        $col = 5;
                        if(!empty($supTotalAmountArray)){
                            $row++;
                            if($xyz<2){
                                $sheet->mergeCells('A' . $row . ':E' . $row);
                            }else{
                                $sheet->mergeCells('A' . $row . ':E' . $row);
                            }
                            foreach(array_slice($supTotalAmountArray[$key],$startRange, $endRange) as $sta){
                                $devideData3 = explode('?',$sta);
                                $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                $sheet->cell($cellNo, function($cell)use($devideData3) {
                                    $cell->setAlignment('right');
                                    $cell->setValue(ImageResizeController::custom_format($devideData3[0]));
                                });
                                if(!empty($devideData3[1])){
                                    $col++;
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                    $sheet->cell($cellNo, function($cell)use($devideData3) {
                                    $cell->setAlignment('right');
                                    $cell->setValue(ImageResizeController::custom_format($devideData3[0]));
                                    });
                                }
                                $col++;
                            }
                            
                        } // End of subtotla amoutn

                        $xyz++; $startRange += $endRange;
                        }// End of while
                        
                    } // endo fo foreach targetArray
                    $row++;
                    $row++;
                    $row++;
                    $sheet->mergeCells("A".$row.':M'.$row);
                    $sheet->row($row,array("A.  Certified that the quotations for the item have been invited from the enlisted suppliers/workshops and ".count($suppliersInf)." in no firms submitted quotations."));
                    $row++;
                    if(!empty($suppliersInf) && $suppliersInf[0]->total <= $orgInfo->purchase_limit ){
                        $sheet->mergeCells("A".$row.':M'.$row);
                        $sheet->row($row,array("B. The offer of ".$suppliersInf[0]->suppliernametext." lowest bidder is recommended for purchase order." ));
                    }
                    
                }// End of $targetArray     

            });
        })->export('xlsx');

        } else{ // End if tender type line items==================================
        $lotNames = \App\LotNames::where('demand_id','=',$demandId)
                                ->where('tender_id','=',$tenderId)
                                //->groupBy('lot_name')
                                ->orderBy('id','asc')
                                ->get();                        

         // Newlly added =================
        // ==============================
        $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
        $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('demand_id','!=',$demandId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
        $itemDmnArr = array_unique($itemDmnArr);
        // End newlly added =================
        // ==================================

        $suppliersInf  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
                ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
                ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
                ->get();                        

        $mainArray = array();

        $supArray              =  array();
        $supTotalAmountArray   =  array();
        $supWiComArray         =  array();
        
        foreach ($lotNames as $lotkey => $lotval) {
                
            $selectSuppLower = array_map('current',\App\LotToSupplier::select('mnd_to_col_qtn_id')
                                                ->where('lot_name_id','=',$lotval->id)
                                                ->orderBy('lot_wise_total','asc')
                                                ->get()->toArray());

            $itemToDemResult = ItemToDemand::where('demand_id','=',$demandId)
                                            ->where('tender_no','=',$tenderId)
                                            //->where('lot_name','=',$lotval->lot_name)
                                            ->where('lot_unq_id','=',$lotval->id)
                                            ->orderBy('id','asc')
                                            ->get();                           

            $lotNameInIndex = $lotval->lot_name;

            $targetArray  = array();
            $sls          = 0;
            $arIn         = 0;

            $itemToDemResultIds = array_map('current',ItemToDemand::select('id')
                                            ->where('demand_id','=',$demandId)
                                            ->where('tender_no','=',$tenderId)
                                            //->where('lot_name','=',$lotval->lot_name)
                                            ->where('lot_unq_id','=',$lotval->id)
                                            ->orderBy('id','asc')
                                            ->get()->toArray());     

            $itemWiseSupp = array_unique(array_map('current',DemandSuppllierToCollQuotToItem::
                                            select('dmn_to_cal_qut_id')
                                            ->whereIn('item_id',$itemToDemResultIds)
                                            ->where('quoted_quantity','>',0)
                                            ->get()->toArray()));
            
                
                $supplierResultFir[$lotNameInIndex]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->join('lot_to_supplier','demand_to_collection_quotation.id','=','lot_to_supplier.mnd_to_col_qtn_id')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','lot_to_supplier.id as lot_to_sup_id','lot_to_supplier.lot_name_id','lot_to_supplier.mnd_to_col_qtn_id','lot_to_supplier.mnd_to_col_qtn_id','lot_to_supplier.lot_wise_total_qty','lot_to_supplier.lot_wise_total','lot_to_supplier.altr_quoted_total_quantity','lot_to_supplier.altr_total_price','lot_to_supplier.lot_select_as_draft_cst','lot_to_supplier.comnt_on_cst_ech_lot')
                    ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->where('lot_to_supplier.lot_name_id','=',$lotval->id)
                    ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                    //->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                    ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total_qty, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total, DECIMAL)"),'asc')
                    ->get();

                    foreach ($supplierResultFir[$lotNameInIndex] as $srs) {
                        $supArray[$lotNameInIndex][] = $srs->suppliernametext.'<br>'.$srs->head_office_address.'?'.$srs->id.'?'.$srs->altr_total_price.'?'.$srs->lot_select_as_draft_cst.'?'.$srs->lot_name_id.'?'.$srs->lot_to_sup_id;

                        $supTotalAmountArray[$lotNameInIndex][] = $srs->lot_wise_total.'?'.$srs->altr_total_price;

                        $supWiComArray[$lotNameInIndex][] = $srs->comnt_on_cst_ech_lot.'?'.$srs->lot_to_sup_id.'?'.$srs->altr_total_price.'?'.$srs->id;
                    }
            
            foreach ($itemToDemResult as $key => $value) {

                $itemWiseSupp = array_unique(array_map('current',DemandSuppllierToCollQuotToItem::
                                            select('dmn_to_cal_qut_id')
                                            ->where('item_id','=',$value->id)
                                            ->where('quoted_quantity','>',0)
                                            ->get()->toArray()));
                
                $forJonPerfect = array_map('current',DemandSuppllierToCollQuotToItem::
                                            select('id')
                                            ->where('item_id','=',$value->id)
                                            ->whereIn('dmn_to_cal_qut_id',$itemWiseSupp)
                                            ->where('quoted_quantity','>',0)
                                            ->get()->toArray());

                $supplierResult[$sls]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->join('lot_to_supplier','demand_to_collection_quotation.id','=','lot_to_supplier.mnd_to_col_qtn_id')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','demand_supplier_to_coll_qut_to_item.total_price','demand_supplier_to_coll_qut_to_item.discount_amount','demand_supplier_to_coll_qut_to_item.alternative_unit_price','demand_supplier_to_coll_qut_to_item.alternative_total_price','demand_supplier_to_coll_qut_to_item.comnt_on_cst_ech_itm','demand_supplier_to_coll_qut_to_item.id as cmnt_item_id','demand_supplier_to_coll_qut_to_item.select_as_draft_cst as item_select_as_draft_cst')
                    ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->where('lot_to_supplier.lot_name_id','=',$lotval->id)
                    ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                    ->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                    ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total_qty, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total, DECIMAL)"),'asc')
                    ->get();
                
                $targetArray[$sls]['items'] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                    ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                    ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                    ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName')
                    ->where('item_to_demand.demand_id','=',$demandId)
                    ->where('item_to_demand.tender_no','=',$tenderId)
                    ->where('demand_supplier_to_coll_qut_to_item.item_id','=',$value->id)
                    //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                    ->orderBy('item_to_demand.id','asc')
                    ->orderBy('demand_supplier_to_coll_qut_to_item.total_price','asc')
                    ->groupBy('demand_supplier_to_coll_qut_to_item.item_id')
                    ->get();

                    // Newly added =====================================================
                    // =================================================================
                    
                    $winnerInfOfThisItem = DemandToCollectionQuotation::join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id', '=', 'demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->select('demand_supplier_to_coll_qut_to_item.unit_price as last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                        ->where('demand_supplier_to_coll_qut_to_item.demand_id','!=',$value->demand_id)
                        ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                        ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
                        //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                        ->whereIn('demand_to_collection_quotation.id',$itemDmnArr)
                        // ->where('demand_to_collection_quotation.winner','=',1)
                        ->orderBy('demand_supplier_to_coll_qut_to_item.id','DESC')
                        ->get();
               
                    if(count($winnerInfOfThisItem) > 0){
                        foreach ($winnerInfOfThisItem as $key => $winnerIns) {
                            $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', $winnerIns->suppliernametext);
                            $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', $winnerIns->last_unti_price);
                            $targetArray[$sls]['items'][0]->setAttribute('previousDates', date('d-m-Y',strtotime($winnerIns->updated_at)));
                        }
                        
                    }else{
                        $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', '');
                        $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', '');
                        $targetArray[$sls]['items'][0]->setAttribute('previousDates', '');
                    }                        
                    // End newly added =================================================
                    // =================================================================

                foreach ($supplierResult[$sls] as $sr) {
                    $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('demand_id','=',$demandId)
                                                    ->where('tender_id','=',$tenderId)
                                                    ->where('item_id','=',$value->id)
                                                    ->where('dmn_to_cal_qut_id','=',$sr->id)
                                                    ->where('supplier_id','=',$sr->supplier_name)
                                                    ->get();
                                                    
                }

                    $sls++;
                    $arIn++;

            }

            $mainArray[$lotNameInIndex] = $targetArray;

        }

        Excel::create('draft-cst-view-' . date("d-m-Y H:i"), function ($excel) use ($supplierResult, $targetArray, $supArray, $supTotalAmountArray, $demandId, $supWiComArray, $demand, $tenderId, $tender,$suppliersInf,$supplierResultFir,$mainArray,$orgInfo) {
            $excel->sheet('First Sheet', function ($sheet) use ($supplierResult, $targetArray, $supArray, $supTotalAmountArray, $demandId, $supWiComArray, $demand, $tenderId, $tender,$suppliersInf,$supplierResultFir,$mainArray,$orgInfo) {
                $row = 0;

                //header Part Start
                $hedtxt = (!empty($suppliersInf)) ? ($suppliersInf[0]->total > $orgInfo->purchase_limit) ? 'PRELIMINARY COMPARATIVE STATEMENT- NSSD DHAKA' : 'COMPARATIVE STATEMENT- NSSD DHAKA' :'';
                $headerTxt = trim($hedtxt);
                $row++;
                $sheet->mergeCells('A' . $row . ':J' . $row);
                $sheet->cells('A' . $row . ':J' . $row, function ($cell) {
                    $cell->setFontWeight('bold');
                });
                $sheet->cells('A' . $row, function ($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->row($row, array($headerTxt));
                $headerTxt2 = !empty($tender->created_at) ? $tender->tender_number. " Date:".date('d F Y', strtotime($tender->created_at)): $tender->tender_number;
                $row++;
                $sheet->mergeCells('A' . $row . ':J' . $row);
                $sheet->cells('A' . $row . ':J' . $row, function ($cell) {
                    $cell->setFontWeight('bold');
                });
                $sheet->cells('A' . $row, function ($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->row($row, array($headerTxt2));

                $row++;
                $row++;
                //Report Name End

                function cellsToMergeByColsRow($start = NULL, $end = NULL, $row = NULL){
                    $merge = 'A1:A1';
                    if($start && $end && $row){
                        $start = \PHPExcel_Cell::stringFromColumnIndex($start);
                        $end = \PHPExcel_Cell::stringFromColumnIndex($end);
                        $merge = "$start{$row}:$end{$row}";

                    }

                    return $merge;
                }

                $arSlmen=1;
                if(!empty($mainArray)){
                    foreach($mainArray as $key => $ta){

                        $totalSuppCount = count($supplierResultFir[$key]);
                        $inEachTable    = 5;
                        $totalTable     = ceil($totalSuppCount/$inEachTable);
                        $xyz            = 1;
                        $startRange     = 0;
                        $endRange       = $inEachTable;
                        $sls = 0;

                        $hedSuppName = array('SL','Lot Name', 'Items Details', 'Deno', 'Quantity', 'Remark');
                        $onlyHed     = array();
                        $row++;
                        
                        while($totalTable >= $xyz){
                            $row++;
                            if($xyz<2){
                                $sheet->cell('A'.$row.':'.'F'.$row, function($cell) {
                                    $cell->setFontWeight('bold');
                                });
                                $sheet->row($row, array('SL','Lot Name', 'Items Details', 'Deno', 'Quantity', 'Remark'));
                            }else{
                                $sheet->mergeCells('A' . $row . ':F' . $row);
                            }

                            $col = 6;
                            if(!empty($supplierResultFir[$key])){
                                foreach($supplierResultFir[$key]->slice($startRange, $endRange) as $sr){
                                    ++$sls;
                                    $placeName = OwnLibrary::numToOrdinalWord($sls) .' Lowest';
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    if(empty($sr->alternative_unit_price)){
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                        $sheet->cell($cellNo, function($cell)use($placeName) {
                                            $cell->setAlignment('center');
                                            $cell->setFontWeight('bold');
                                            $cell->setValue($placeName);
                                        });
                                    }else{
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 3, $row));
                                        $sheet->cell($cellNo, function($cell)use($placeName) {
                                            $cell->setAlignment('center');
                                            $cell->setFontWeight('bold');
                                            $cell->setValue($placeName);
                                        });
                                    }
                                    $col++;
                                }
                                
                            }

                        $col = 6;
                        $sl=1;
                        if(!empty($supArray[$key])){ 
                            $row++;
                            if($xyz<2){ 
                                $sheet->mergeCells('A' . $row . ':F' . $row);
                            }else{
                                $sheet->mergeCells('A' . $row . ':F' . $row);
                            }
                            foreach(array_slice($supArray[$key], $startRange, $endRange) as $sui){
                                $devideData = explode('?',$sui);
                                $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                if(empty($devideData[2])){
                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                    $sheet->cell($cellNo, function($cell)use($devideData) {
                                        $cell->setAlignment('center');
                                        $cell->setFontWeight('bold');
                                        $cell->setValue(str_replace('<br>','',$devideData[0]));
                                    });
                                }else{
                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 3, $row));
                                    $sheet->cell($cellNo, function($cell)use($devideData) {
                                        $cell->setAlignment('center');
                                        $cell->setFontWeight('bold');
                                        $cell->setValue(str_replace('<br>','',$devideData[0]));
                                    });
                                
                                }
                                $col++;
                            }
                        }// End supplier if

                        $col = 6;
                        if(!empty($supArray[$key])) {
                            $row++;
                            if($xyz<2){
                                $sheet->mergeCells('A' . $row . ':E' . $row);
                            }else{
                                $sheet->mergeCells('A' . $row . ':E' . $row);
                            }
                            foreach(array_slice($supArray[$key], $startRange, $endRange) as $suiii){
                                $devideData4 = explode('?',$suiii);
                                $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                if(empty($devideData4[2])){
                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                    $sheet->cell($cellNo, function($cell)use($devideData4) {
                                        $cell->setAlignment('center');
                                        $cell->setValue('');
                                    });
                                }else{
                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                    $sheet->cell($cellNo, function($cell)use($devideData4) {
                                        $cell->setAlignment('center');
                                        $cell->setValue('Main Offer');
                                    });
                                    $col++;

                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                    $sheet->cell(\PHPExcel_Cell::stringFromColumnIndex($col-1).$row, function($cell)use($devideData4) {
                                        $cell->setAlignment('center');
                                        $cell->setValue('Alternative Offer');
                                    });
                                    
                                }
                                $col++;
                            }
                        } // End main and alternative offer

                        $col = 6;
                        if(!empty($supArray[$key])) {
                            $row++;
                            if($xyz<2){
                                $sheet->mergeCells('A' . $row . ':F' . $row);
                            }else{
                                $sheet->mergeCells('A' . $row . ':F' . $row);
                            }
                            foreach(array_slice($supArray[$key], $startRange, $endRange) as $suittt){
                                $devideDataddd = explode('?',$suittt);
                                $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                
                                    $sheet->cell($cellNo, function($cell)use($devideData4) {
                                        $cell->setAlignment('center');
                                        $cell->setValue('Unit Price');
                                    });
                                    $col++;
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->cell($cellNo, function($cell)use($devideData4) {
                                        $cell->setAlignment('center');
                                        $cell->setValue('Total Price');
                                    });

                                if(!empty($devideDataddd[2])){
                                    $col++;
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->cell($cellNo, function($cell)use($devideData4) {
                                        $cell->setAlignment('center');
                                        $cell->setValue('Unit Price');
                                    });

                                    $col++;
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->cell($cellNo, function($cell)use($devideData4) {
                                        $cell->setAlignment('center');
                                        $cell->setValue('Total Price');
                                    });
                                    
                                }
                                $col++;
                            }
                        } // End total price and alternative price

                        // Item section start here========
                        //$col = 1;
                        $mn = 0; 
                        foreach($ta as $tas){ 
                            $row++;
                            if($xyz<2){
                                if($mn == 0){
                                    $sheet->mergeCells('A'.$row.':A'.(count($mainArray[$key])+($row-1)));
                                        
                                    $sheet->cell('A'.$row, function($cell) use($sl) {
                                        $cell->setValignment('center');
                                        $cell->setValue($sl);
                                    });

                                    $sheet->mergeCells('B'.$row.':B'.(count($mainArray[$key])+($row-1)));
                                    $sheet->cell('B'.$row, function($cell) use($key) {
                                        $cell->setValignment('center');
                                        $cell->setValue($key);
                                    });

                                }

                                foreach($tas['items'] as $itm){
                                    $remarks = !empty($itm->previsouSuppName) ? 'Sup: '.$itm->previsouSuppName: ''.''.!empty($itm->previsouUnitPrice)?' UP: '.$itm->previsouUnitPrice : ''.''.!empty($itm->previousDates)? $itm->previousDates : '';
                                    if($xyz<2){
                                        $sheet->cell('C'.$row, function($cell) use($itm) {
                                            $cell->setValue($itm->item_name);
                                        });
                                        $sheet->cell('D'.$row, function($cell) use($itm) {
                                            $cell->setValue($itm->denoName);
                                        });
                                        $sheet->cell('E'.$row, function($cell) use($itm) {
                                            $cell->setValue($itm->unit);
                                        });
                                        $sheet->cell('F'.$row, function($cell) use($remarks) {
                                            $cell->setValue($remarks);
                                        });
                                        //$sheet->row($row, array($sl++, , , , ));
                                    }
                                }
                            }else{
                                $sheet->mergeCells('A' . $row . ':E' . $row);
                            }

                            $col = 6;
                            foreach(array_slice($tas['supi'], $startRange, $endRange) as $sp){
                                if(count($sp)>0){
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->cell($cellNo, function($cell)use($sp) {
                                        $cell->setAlignment('right');
                                        $cell->setValue(ImageResizeController::custom_format($sp[0]->unit_price));
                                    });
                                    $col++;
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->cell($cellNo, function($cell)use($sp) {
                                        $cell->setAlignment('right');
                                        $cell->setValue(ImageResizeController::custom_format($sp[0]->unit_price*$sp[0]->quoted_quantity));
                                    });

                                    if(!empty($sp[0]->alternative_unit_price)){
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($sp) {
                                            $cell->setAlignment('right');
                                            $cell->setValue(ImageResizeController::custom_format($sp[0]->alternative_unit_price));
                                        });
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($sp) {
                                            $cell->setAlignment('right');
                                            $cell->setValue(ImageResizeController::custom_format($sp[0]->alternative_unit_price*$sp[0]->alternative_quoted_quantity));
                                        });
                                    }
                                }else{
                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                    $sheet->cell($cellNo, function($cell)use($devideData4) {
                                        $cell->setAlignment('center');
                                        $cell->setValue('Not participated');
                                    });
                                }
                            $col++;    
                            } // End item price foreach

                        }// End item foreach

                        

                        $col = 6;
                        if(!empty($supTotalAmountArray)){
                            $row++;
                            if($xyz<2){
                                $sheet->mergeCells('A' . $row . ':F' . $row);
                            }else{
                                $sheet->mergeCells('A' . $row . ':F' . $row);
                            }
                            foreach(array_slice($supTotalAmountArray[$key],$startRange, $endRange) as $sta){
                                $devideData3 = explode('?',$sta);
                                $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                $sheet->cell($cellNo, function($cell)use($devideData3) {
                                    $cell->setAlignment('right');
                                    $cell->setValue(ImageResizeController::custom_format($devideData3[0]));
                                });
                                if(!empty($devideData3[1])){
                                    $col++;
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                    $sheet->cell($cellNo, function($cell)use($devideData3) {
                                    $cell->setAlignment('right');
                                    $cell->setValue(ImageResizeController::custom_format($devideData3[0]));
                                    });
                                }
                                $col++;
                            }
                            
                        } // End of subtotla amoutn

                        $xyz++; $startRange += $endRange;
                        }// End of while
                        $arSlmen = 0; $sl++;
                    } // endo fo foreach targetArray
                    $row++;
                    $row++;
                    $row++;
                    $sheet->mergeCells("A".$row.':M'.$row);
                    $sheet->row($row,array("A.  Certified that the quotations for the item have been invited from the enlisted suppliers/workshops and ".count($suppliersInf)." in no firms submitted quotations."));
                    $row++;
                    if(!empty($suppliersInf) && $suppliersInf[0]->total <= $orgInfo->purchase_limit ){
                        $sheet->mergeCells("A".$row.':M'.$row);
                        $sheet->row($row,array("B. The offer of ".$suppliersInf[0]->suppliernametext." lowest bidder is recommended for purchase order." ));
                    }
                }// End of $targetArray     

            });
        })->export('xlsx');      
        
        }
    }

    public function draftCstViewExcel($id){
        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

        $nsdId = 1;
         if(!empty(Auth::user()->nsd_bsd)){
            $nsdId = Auth::user()->nsd_bsd;
         }
        $orgInfo  = \App\NsdName::find($nsdId);

        $demandId = $explodes[0];
        $tenderId = $explodes[1];
        $demand     = \App\Demand::find($demandId);
        $tender     = \App\DemandToTender::where('demand_id','=',$demandId)->where('tender_id','=',$tenderId)->first();

        $mainTenderInfo = \App\Tender::find($tenderId);

        if($mainTenderInfo->tender_nature != 2){

            $selectedAsDraftCst = array_map('current',DemandSuppllierToCollQuotToItem::select('item_id')
                                            ->where('demand_id','=',$demandId)
                                            ->where('tender_id','=',$tenderId)
                                            ->where('select_as_draft_cst','=',1)
                                            ->get()->toArray());

            $itemToDemResult = ItemToDemand::where('demand_id','=',$demandId)
                                            ->where('tender_no','=',$tenderId)
                                            ->whereIn('id',$selectedAsDraftCst)
                                            ->orderBy('id','asc')->get();

            // Newlly added =================
            // ==============================
            $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
            $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('demand_id','!=',$demandId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
            $itemDmnArr = array_unique($itemDmnArr);
            // End newlly added =================
            // ==================================

            // for keeping checked ===============================
            // ===================================================
            $supplierAllreadySelected  = array_map('current',DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
            ->select('demand_to_collection_quotation.id')
            ->where('demand_to_collection_quotation.demand_id','=',$demandId)
            ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
            ->where('demand_to_collection_quotation.sel_as_draft_cst','=',1)
            ->orderBy('demand_to_collection_quotation.total','asc')
            ->get()->toArray());
     
            $targetArray  = array();
            $sls          = 0;
            $arIn         = 0;

            $supArray              =  array();
            $supTotalAmountArray   =  array();
            $supWiComArray         =  array();

            $suppliersInf  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
                    ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->where('demand_to_collection_quotation.sel_as_draft_cst','=',1)
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
                    ->get();

            foreach ($itemToDemResult as $key => $value) {

                $itemWiseSupp = array_map('current',DemandSuppllierToCollQuotToItem::
                                            select('dmn_to_cal_qut_id')
                                            ->where('item_id','=',$value->id)
                                            ->where('select_as_draft_cst','=',1)
                                            ->where('quoted_quantity','>',0)
                                            ->get()->toArray());
                $forJonPerfect = array_map('current',DemandSuppllierToCollQuotToItem::
                                            select('id')
                                            ->where('item_id','=',$value->id)
                                            ->where('quoted_quantity','>',0)
                                            ->get()->toArray());
                
                $supplierResult[$sls]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','demand_supplier_to_coll_qut_to_item.total_price','demand_supplier_to_coll_qut_to_item.discount_amount','demand_supplier_to_coll_qut_to_item.alternative_unit_price','demand_supplier_to_coll_qut_to_item.alternative_total_price','demand_supplier_to_coll_qut_to_item.comnt_on_cst_ech_itm','demand_supplier_to_coll_qut_to_item.id as cmnt_item_id','demand_supplier_to_coll_qut_to_item.select_as_draft_cst as item_select_as_draft_cst','demand_supplier_to_coll_qut_to_item.select_as_winner as select_as_winner')
                    ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                    ->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                    ->orderBy(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.quoted_quantity, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.total_price, DECIMAL)"),'asc')
                    ->get();
                
                    foreach ($supplierResult[$sls] as $srs) {
                        $supArray[$sls][] = $srs->suppliernametext.'<br>'.$srs->head_office_address.'?'.$srs->id.'?'.$srs->alternative_unit_price.'?'.$srs->item_select_as_draft_cst.'?'.$srs->select_as_winner;
                        $supTotalAmountArray[$sls][] = $srs->total_price.'?'.$srs->alternative_total_price;

                        $supWiComArray[$sls][] = $srs->comnt_on_cst_ech_itm.'?'.$srs->cmnt_item_id.'?'.$srs->alternative_unit_price.'?'.$srs->id;
                    }

                $targetArray[$sls]['items'] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                    ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                    ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                    ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName')
                    ->where('item_to_demand.demand_id','=',$demandId)
                    ->where('item_to_demand.tender_no','=',$tenderId)
                    ->where('demand_supplier_to_coll_qut_to_item.item_id','=',$value->id)
                    //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                    ->orderBy('item_to_demand.id','asc')
                    ->orderBy('demand_supplier_to_coll_qut_to_item.total_price','asc')
                    ->groupBy('demand_supplier_to_coll_qut_to_item.item_id')
                    ->get();

                    // Newly added =====================================================
                    // =================================================================
                    
                    $winnerInfOfThisItem = DemandToCollectionQuotation::join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id', '=', 'demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->select('demand_supplier_to_coll_qut_to_item.unit_price as last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                        ->where('demand_supplier_to_coll_qut_to_item.demand_id','!=',$value->demand_id)
                        ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                        ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
                        //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                        ->whereIn('demand_to_collection_quotation.id',$itemDmnArr)
                        // ->where('demand_to_collection_quotation.winner','=',1)
                        ->orderBy('demand_supplier_to_coll_qut_to_item.id','DESC')
                        ->get();
               
                    if(count($winnerInfOfThisItem) > 0){
                        foreach ($winnerInfOfThisItem as $key => $winnerIns) {
                            $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', $winnerIns->suppliernametext);
                            $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', $winnerIns->last_unti_price);
                            $targetArray[$sls]['items'][0]->setAttribute('previousDates', date('d-m-Y',strtotime($winnerIns->updated_at)));
                        }
                        
                    }else{
                        $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', '');
                        $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', '');
                        $targetArray[$sls]['items'][0]->setAttribute('previousDates', '');
                    }                        
                    // End newly added =================================================
                    // =================================================================

                foreach ($supplierResult[$sls] as $sr) {
                    $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('demand_id','=',$demandId)
                                                    ->where('tender_id','=',$tenderId)
                                                    ->where('item_id','=',$value->id)
                                                    ->where('dmn_to_cal_qut_id','=',$sr->id)
                                                    ->where('supplier_id','=',$sr->supplier_name)
                                                    ->get();
                                                    
                    //echo "<pre>"; print_r($sr->supplier_name); exit;
                }

                    $sls++;
                    $arIn++;
            }
            // echo "<pre>"; print_r($targetArray); exit;

                Excel::create('draft-cst-view - ' . date("d-m-Y H:i"), function ($excel) use ($supplierResult, $targetArray, $supArray, $supTotalAmountArray, $demandId, $supWiComArray, $supplierAllreadySelected, $demand, $tenderId, $tender,$orgInfo,$suppliersInf) {
                $excel->sheet('First Sheet', function ($sheet) use ($supplierResult, $targetArray, $supArray, $supTotalAmountArray, $demandId, $supWiComArray, $supplierAllreadySelected, $demand, $tenderId, $tender,$orgInfo,$suppliersInf) {
                    $row = 0;

                    //header Part Start
                    $hedtxt = $hedtxt = (!empty($suppliersInf)) ? ($suppliersInf[0]->total > $orgInfo->purchase_limit) ? 'PRELIMINARY COMPARATIVE STATEMENT- NSSD DHAKA' : 'COMPARATIVE STATEMENT- NSSD DHAKA' :'';;
                    $headerTxt = trim($hedtxt);
                    $row++;
                    $sheet->mergeCells('A' . $row . ':J' . $row);
                    $sheet->cells('A' . $row . ':J' . $row, function ($cell) {
                        $cell->setFontWeight('bold');
                    });
                    $sheet->cells('A' . $row, function ($cell) {
                        $cell->setAlignment('center');
                    });
                    $sheet->row($row, array($headerTxt));
                    $headerTxt2 = !empty($tender->created_at) ? $tender->tender_number. " Date:".date('d F Y', strtotime($tender->created_at)): $tender->tender_number;
                    $row++;
                    $sheet->mergeCells('A' . $row . ':J' . $row);
                    $sheet->cells('A' . $row . ':J' . $row, function ($cell) {
                        $cell->setFontWeight('bold');
                    });
                    $sheet->cells('A' . $row, function ($cell) {
                        $cell->setAlignment('center');
                    });
                    $sheet->row($row, array($headerTxt2));

                    $row++;
                    $row++;
                    //Report Name End

                    function cellsToMergeByColsRow($start = NULL, $end = NULL, $row = NULL){
                        $merge = 'A1:A1';
                        if($start && $end && $row){
                            $start = \PHPExcel_Cell::stringFromColumnIndex($start);
                            $end = \PHPExcel_Cell::stringFromColumnIndex($end);
                            $merge = "$start{$row}:$end{$row}";

                        }

                        return $merge;
                    }

                    $sl=1;
                    if(!empty($targetArray)){
                        foreach($targetArray as $key => $ta){

                            $totalSuppCount = count($supplierResult[$key]);
                            $inEachTable    = 5;
                            $totalTable     = ceil($totalSuppCount/$inEachTable);
                            $xyz            = 1;
                            $startRange     = 0;
                            $endRange       = $inEachTable;
                            $sls = 0;

                            $hedSuppName = array('SL', 'Items Details', 'Deno', 'Quantity', 'Remark');
                            $onlyHed     = array();
                            $row++;
                            
                            while($totalTable >= $xyz){
                                $row++;
                                if($xyz<2){
                                    $sheet->cell('A'.$row.':'.'E'.$row, function($cell) {
                                        $cell->setFontWeight('bold');
                                    });
                                    $sheet->row($row, array('SL', 'Items Details', 'Deno', 'Quantity', 'Remark'));
                                }else{
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }

                                $col = 5;
                                if(!empty($supplierResult[$key])){
                                    foreach($supplierResult[$key]->slice($startRange, $endRange) as $sr){
                                        ++$sls;
                                        $placeName = OwnLibrary::numToOrdinalWord($sls) .' Lowest';
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        if(empty($sr->alternative_unit_price)){
                                            $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                            $sheet->cell($cellNo, function($cell)use($placeName) {
                                                $cell->setAlignment('center');
                                                $cell->setFontWeight('bold');
                                                $cell->setValue($placeName);
                                            });
                                        }else{
                                            $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 3, $row));
                                            $sheet->cell($cellNo, function($cell)use($placeName) {
                                                $cell->setAlignment('center');
                                                $cell->setFontWeight('bold');
                                                $cell->setValue($placeName);
                                            });
                                        }
                                        $col++;
                                    }
                                    
                                }

                            $col = 5;
                            if(!empty($supArray[$key])){ 
                                $row++;
                                if($xyz<2){ 
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }else{
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }
                                foreach(array_slice($supArray[$key], $startRange, $endRange) as $sui) {
                                    $devideData = explode('?',$sui);
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    if(empty($devideData[2])){
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                        $sheet->cell($cellNo, function($cell)use($devideData) {
                                            $cell->setAlignment('center');
                                            $cell->setFontWeight('bold');
                                            $cell->setValue(str_replace('<br>','',$devideData[0]));
                                        });
                                    }else{
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 3, $row));
                                        $sheet->cell($cellNo, function($cell)use($devideData) {
                                            $cell->setAlignment('center');
                                            $cell->setFontWeight('bold');
                                            $cell->setValue(str_replace('<br>','',$devideData[0]));
                                        });
                                    
                                    }
                                    $col++;
                                }
                            }// End supplier if

                            $col = 5;
                            if(!empty($supArray[$key])) {
                                $row++;
                                if($xyz<2){
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }else{
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }
                                foreach(array_slice($supArray[$key], $startRange, $endRange) as $suiii){
                                    $devideData4 = explode('?',$suiii);
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    if(empty($devideData4[2])){
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('');
                                        });
                                    }else{
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Main Offer');
                                        });
                                        $col++;

                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                        $sheet->cell(\PHPExcel_Cell::stringFromColumnIndex($col-1).$row, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Alternative Offer');
                                        });
                                        
                                    }
                                    $col++;
                                }
                            } // End main and alternative offer

                            $col = 5;
                            if(!empty($supArray[$key])) {
                                $row++;
                                if($xyz<2){
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }else{
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }
                                foreach(array_slice($supArray[$key], $startRange, $endRange) as $suittt){
                                    $devideDataddd = explode('?',$suittt);
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Unit Price');
                                        });
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Total Price');
                                        });

                                    if(!empty($devideDataddd[2])){
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Unit Price');
                                        });

                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Total Price');
                                        });
                                        
                                    }
                                    $col++;
                                }
                            } // End total price and alternative price

                            // Item section start here========
                            foreach($ta['items'] as $itm){
                                $row++;
                                $remarks = !empty($itm->previsouSuppName) ? 'Sup: '.$itm->previsouSuppName: ''.''.!empty($itm->previsouUnitPrice)?' UP: '.$itm->previsouUnitPrice : ''.''.!empty($itm->previousDates)? $itm->previousDates : '';
                                if($xyz<2){
                                    $sheet->row($row, array($sl++, $itm->item_name, $itm->denoName, $itm->unit, $remarks));
                                }else{
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }
                            }// End item foreach

                            $col = 5;
                            foreach(array_slice($ta['supi'], $startRange, $endRange) as $sp){
                                if(count($sp)>0){
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->cell($cellNo, function($cell)use($sp) {
                                        $cell->setAlignment('right');
                                        $cell->setValue(ImageResizeController::custom_format($sp[0]->unit_price));
                                    });
                                    $col++;
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->cell($cellNo, function($cell)use($sp) {
                                        $cell->setAlignment('right');
                                        $cell->setValue(ImageResizeController::custom_format($sp[0]->unit_price*$sp[0]->quoted_quantity));
                                    });

                                    if(!empty($sp[0]->alternative_unit_price)){
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($sp) {
                                            $cell->setAlignment('right');
                                            $cell->setValue(ImageResizeController::custom_format($sp[0]->alternative_unit_price));
                                        });
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($sp) {
                                            $cell->setAlignment('right');
                                            $cell->setValue(ImageResizeController::custom_format($sp[0]->alternative_unit_price*$sp[0]->alternative_quoted_quantity));
                                        });
                                    }
                                }else{
                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                    $sheet->cell($cellNo, function($cell)use($devideData4) {
                                        $cell->setAlignment('center');
                                        $cell->setValue('Not participated');
                                    });
                                }
                            $col++;    
                            } // End item price foreach

                            $col = 5;
                            if(!empty($supTotalAmountArray)){
                                $row++;
                                if($xyz<2){
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }else{
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }
                                foreach(array_slice($supTotalAmountArray[$key],$startRange, $endRange) as $sta){
                                    $devideData3 = explode('?',$sta);
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                    $sheet->cell($cellNo, function($cell)use($devideData3) {
                                        $cell->setAlignment('right');
                                        $cell->setValue(ImageResizeController::custom_format($devideData3[0]));
                                    });
                                    if(!empty($devideData3[1])){
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                        $sheet->cell($cellNo, function($cell)use($devideData3) {
                                        $cell->setAlignment('right');
                                        $cell->setValue(ImageResizeController::custom_format($devideData3[0]));
                                        });
                                    }
                                    $col++;
                                }
                                
                            } // End of subtotla amoutn

                            $xyz++; $startRange += $endRange;
                            }// End of while
                            
                        } // endo fo foreach targetArray

                        $row++;
                        $row++;
                        $row++;
                        $sheet->mergeCells("A".$row.':M'.$row);
                        $sheet->row($row,array("A.  Certified that the quotations for the item have been invited from the enlisted suppliers/workshops and ".count($suppliersInf)." in no firms submitted quotations."));
                        $row++;
                        if(!empty($suppliersInf) && $suppliersInf[0]->total <= $orgInfo->purchase_limit ){
                            $sheet->mergeCells("A".$row.':M'.$row);
                            $sheet->row($row,array("B. The offer of ".$suppliersInf[0]->suppliernametext." lowest bidder is recommended for purchase order." ));
                        }
                    }// End of $targetArray     

                });
            })->export('xlsx');

        }else{

            $selectedIds = array_map('current',\App\LotToSupplier::select('lot_name_id')
                                                ->where('lot_select_as_draft_cst','=',1)
                                                ->get()
                                                ->toArray());

            $lotNames = \App\LotNames::where('demand_id','=',$demandId)
                                    ->where('tender_id','=',$tenderId)
                                    ->whereIn('id',$selectedIds)
                                    ->orderBy('id','asc')
                                    ->get();                        

             // Newlly added =================
            // ==============================
            $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
            $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('demand_id','!=',$demandId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
            $itemDmnArr = array_unique($itemDmnArr);
            // End newlly added =================
            // ==================================

            $suppliersInf  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
                    ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
                    ->get();                        

            $mainArray = array();

            $supArray              =  array();
            $supTotalAmountArray   =  array();
            $supWiComArray         =  array();
            
            foreach ($lotNames as $lotkey => $lotval) {
                    
                $selectSuppLower = array_map('current',\App\LotToSupplier::select('mnd_to_col_qtn_id')
                                                    ->where('lot_name_id','=',$lotval->id)
                                                    ->where('lot_select_as_draft_cst','=',1)
                                                    ->orderBy('lot_wise_total','asc')
                                                    ->get()->toArray());

                $itemToDemResult = ItemToDemand::where('demand_id','=',$demandId)
                                                ->where('tender_no','=',$tenderId)
                                                ->where('lot_unq_id','=',$lotval->id)
                                                ->orderBy('id','asc')
                                                ->get();                           

                $lotNameInIndex = $lotval->lot_name;
                $targetArray  = array();
                $sls          = 0;
                $arIn         = 0;

                $itemToDemResultIds = array_map('current',ItemToDemand::select('id')
                                                ->where('demand_id','=',$demandId)
                                                ->where('tender_no','=',$tenderId)
                                                ->where('lot_unq_id','=',$lotval->id)
                                                ->orderBy('id','asc')
                                                ->get()->toArray());     

                $itemWiseSupp = array_unique(array_map('current',DemandSuppllierToCollQuotToItem::
                                                select('dmn_to_cal_qut_id')
                                                ->where('select_as_draft_cst','=',1)
                                                ->whereIn('item_id',$itemToDemResultIds)
                                                ->where('quoted_quantity','>',0)
                                                ->get()->toArray()));
                
                    $supplierResultFir[$lotNameInIndex]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                        ->join('lot_to_supplier','demand_to_collection_quotation.id','=','lot_to_supplier.mnd_to_col_qtn_id')
                        ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','lot_to_supplier.id as lot_to_sup_id','lot_to_supplier.lot_name_id','lot_to_supplier.mnd_to_col_qtn_id','lot_to_supplier.mnd_to_col_qtn_id','lot_to_supplier.lot_wise_total_qty','lot_to_supplier.lot_wise_total','lot_to_supplier.altr_quoted_total_quantity','lot_to_supplier.altr_total_price','lot_to_supplier.lot_select_as_draft_cst','lot_to_supplier.comnt_on_cst_ech_lot','lot_to_supplier.lot_select_as_winner')
                        ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                        ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                        ->where('lot_to_supplier.lot_name_id','=',$lotval->id)
                        ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                        //->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total_qty, DECIMAL)"),'desc')
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total, DECIMAL)"),'asc')
                        ->get();

                        foreach ($supplierResultFir[$lotNameInIndex] as $srs) {
                            $supArray[$lotNameInIndex][] = $srs->suppliernametext.'<br>'.$srs->head_office_address.'?'.$srs->id.'?'.$srs->altr_total_price.'?'.$srs->lot_select_as_winner.'?'.$srs->lot_name_id.'?'.$srs->lot_to_sup_id;

                            $supTotalAmountArray[$lotNameInIndex][] = $srs->lot_wise_total.'?'.$srs->altr_total_price;

                            $supWiComArray[$lotNameInIndex][] = $srs->comnt_on_cst_ech_lot.'?'.$srs->lot_to_sup_id.'?'.$srs->altr_total_price.'?'.$srs->id;
                        }
                
                foreach ($itemToDemResult as $key => $value) {
                    $itemWiseSupp = array_unique(array_map('current',DemandSuppllierToCollQuotToItem::
                                                select('dmn_to_cal_qut_id')
                                                ->where('item_id','=',$value->id)
                                                ->where('quoted_quantity','>',0)
                                                ->get()->toArray()));
                    
                    $forJonPerfect = array_map('current',DemandSuppllierToCollQuotToItem::
                                                select('id')
                                                ->where('item_id','=',$value->id)
                                                ->whereIn('dmn_to_cal_qut_id',$itemWiseSupp)
                                                ->where('quoted_quantity','>',0)
                                                ->get()->toArray());

                    $supplierResult[$sls]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                        ->join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->join('lot_to_supplier','demand_to_collection_quotation.id','=','lot_to_supplier.mnd_to_col_qtn_id')
                        ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','demand_supplier_to_coll_qut_to_item.total_price','demand_supplier_to_coll_qut_to_item.discount_amount','demand_supplier_to_coll_qut_to_item.alternative_unit_price','demand_supplier_to_coll_qut_to_item.alternative_total_price','demand_supplier_to_coll_qut_to_item.comnt_on_cst_ech_itm','demand_supplier_to_coll_qut_to_item.id as cmnt_item_id','demand_supplier_to_coll_qut_to_item.select_as_draft_cst as item_select_as_draft_cst')
                        ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                        ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                        ->where('lot_to_supplier.lot_name_id','=',$lotval->id)
                        ->where('demand_supplier_to_coll_qut_to_item.select_as_draft_cst','=',1)
                        ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                        ->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total_qty, DECIMAL)"),'desc')
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total, DECIMAL)"),'asc')
                        ->get();
                    
                    $targetArray[$sls]['items'] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                        ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                        ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                        ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName')
                        ->where('item_to_demand.demand_id','=',$demandId)
                        ->where('item_to_demand.tender_no','=',$tenderId)
                        ->where('demand_supplier_to_coll_qut_to_item.item_id','=',$value->id)
                        //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                        ->orderBy('item_to_demand.id','asc')
                        ->orderBy('demand_supplier_to_coll_qut_to_item.total_price','asc')
                        ->groupBy('demand_supplier_to_coll_qut_to_item.item_id')
                        ->get();

                        // Newly added =====================================================
                        // =================================================================
                        
                        $winnerInfOfThisItem = DemandToCollectionQuotation::join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id', '=', 'demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                            ->select('demand_supplier_to_coll_qut_to_item.unit_price as last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                            ->where('demand_supplier_to_coll_qut_to_item.demand_id','!=',$value->demand_id)
                            ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                            ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
                            //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                            ->whereIn('demand_to_collection_quotation.id',$itemDmnArr)
                            // ->where('demand_to_collection_quotation.winner','=',1)
                            ->orderBy('demand_supplier_to_coll_qut_to_item.id','DESC')
                            ->get();
                   
                        if(count($winnerInfOfThisItem) > 0){
                            foreach ($winnerInfOfThisItem as $key => $winnerIns) {
                                $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', $winnerIns->suppliernametext);
                                $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', $winnerIns->last_unti_price);
                                $targetArray[$sls]['items'][0]->setAttribute('previousDates', date('d-m-Y',strtotime($winnerIns->updated_at)));
                            }
                            
                        }else{
                            $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', '');
                            $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', '');
                            $targetArray[$sls]['items'][0]->setAttribute('previousDates', '');
                        }                        
                        // End newly added =================================================
                        // =================================================================
                    foreach ($supplierResult[$sls] as $sr) {
                        $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('demand_id','=',$demandId)
                                                        ->where('tender_id','=',$tenderId)
                                                        ->where('item_id','=',$value->id)
                                                        ->where('dmn_to_cal_qut_id','=',$sr->id)
                                                        ->where('supplier_id','=',$sr->supplier_name)
                                                        ->get();                          
                    }
                        $sls++;
                        $arIn++;
                }

                $mainArray[$lotNameInIndex] = $targetArray;

            }        
            
                Excel::create('draft-cst-view-' . date("d-m-Y H:i"), function ($excel) use ($supplierResult, $targetArray, $supArray, $supTotalAmountArray, $demandId, $supWiComArray, $demand, $tenderId, $tender,$suppliersInf,$supplierResultFir,$mainArray,$orgInfo) {
                $excel->sheet('First Sheet', function ($sheet) use ($supplierResult, $targetArray, $supArray, $supTotalAmountArray, $demandId, $supWiComArray, $demand, $tenderId, $tender,$suppliersInf,$supplierResultFir,$mainArray,$orgInfo) {
                    $row = 0;

                    //header Part Start
                    $hedtxt = $hedtxt = (!empty($suppliersInf)) ? ($suppliersInf[0]->total > $orgInfo->purchase_limit) ? 'PRELIMINARY COMPARATIVE STATEMENT- NSSD DHAKA' : 'COMPARATIVE STATEMENT- NSSD DHAKA' :'';;
                    $headerTxt = trim($hedtxt);
                    $row++;
                    $sheet->mergeCells('A' . $row . ':J' . $row);
                    $sheet->cells('A' . $row . ':J' . $row, function ($cell) {
                        $cell->setFontWeight('bold');
                    });
                    $sheet->cells('A' . $row, function ($cell) {
                        $cell->setAlignment('center');
                    });
                    $sheet->row($row, array($headerTxt));
                    $headerTxt2 = !empty($tender->created_at) ? $tender->tender_number. " Date:".date('d F Y', strtotime($tender->created_at)): $tender->tender_number;
                    $row++;
                    $sheet->mergeCells('A' . $row . ':J' . $row);
                    $sheet->cells('A' . $row . ':J' . $row, function ($cell) {
                        $cell->setFontWeight('bold');
                    });
                    $sheet->cells('A' . $row, function ($cell) {
                        $cell->setAlignment('center');
                    });
                    $sheet->row($row, array($headerTxt2));

                    $row++;
                    $row++;
                    //Report Name End

                    function cellsToMergeByColsRow($start = NULL, $end = NULL, $row = NULL){
                        $merge = 'A1:A1';
                        if($start && $end && $row){
                            $start = \PHPExcel_Cell::stringFromColumnIndex($start);
                            $end = \PHPExcel_Cell::stringFromColumnIndex($end);
                            $merge = "$start{$row}:$end{$row}";

                        }

                        return $merge;
                    }

                    $arSlmen=1;
                    if(!empty($mainArray)){
                        foreach($mainArray as $key => $ta){

                            $totalSuppCount = count($supplierResultFir[$key]);
                            $inEachTable    = 2;
                            $totalTable     = ceil($totalSuppCount/$inEachTable);
                            $xyz            = 1;
                            $startRange     = 0;
                            $endRange       = $inEachTable;
                            $sls = 0;

                            $hedSuppName = array('SL','Lot Name', 'Items Details', 'Deno', 'Quantity', 'Remark');
                            $onlyHed     = array();
                            $row++;
                            
                            while($totalTable >= $xyz){
                                $row++;
                                if($xyz<2){
                                    $sheet->cell('A'.$row.':'.'F'.$row, function($cell) {
                                        $cell->setFontWeight('bold');
                                    });
                                    $sheet->row($row, array('SL','Lot Name', 'Items Details', 'Deno', 'Quantity', 'Remark'));
                                }else{
                                    $sheet->mergeCells('A' . $row . ':F' . $row);
                                }

                                $col = 6;
                                if(!empty($supplierResultFir[$key])){
                                    foreach($supplierResultFir[$key]->slice($startRange, $endRange) as $sr){
                                        ++$sls;
                                        $placeName = OwnLibrary::numToOrdinalWord($sls) .' Lowest';
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        if(empty($sr->alternative_unit_price)){
                                            $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                            $sheet->cell($cellNo, function($cell)use($placeName) {
                                                $cell->setAlignment('center');
                                                $cell->setFontWeight('bold');
                                                $cell->setValue($placeName);
                                            });
                                        }else{
                                            $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 3, $row));
                                            $sheet->cell($cellNo, function($cell)use($placeName) {
                                                $cell->setAlignment('center');
                                                $cell->setFontWeight('bold');
                                                $cell->setValue($placeName);
                                            });
                                        }
                                        $col++;
                                    }
                                    
                                }

                            $col = 6;
                            $sl=1;
                            if(!empty($supArray[$key])){ 
                                $row++;
                                if($xyz<2){ 
                                    $sheet->mergeCells('A' . $row . ':F' . $row);
                                }else{
                                    $sheet->mergeCells('A' . $row . ':F' . $row);
                                }
                                foreach(array_slice($supArray[$key], $startRange, $endRange) as $sui){
                                    $devideData = explode('?',$sui);
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    if(empty($devideData[2])){
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                        $sheet->cell($cellNo, function($cell)use($devideData) {
                                            $cell->setAlignment('center');
                                            $cell->setFontWeight('bold');
                                            $cell->setValue(str_replace('<br>','',$devideData[0]));
                                        });
                                    }else{
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 3, $row));
                                        $sheet->cell($cellNo, function($cell)use($devideData) {
                                            $cell->setAlignment('center');
                                            $cell->setFontWeight('bold');
                                            $cell->setValue(str_replace('<br>','',$devideData[0]));
                                        });
                                    
                                    }
                                    $col++;
                                }
                            }// End supplier if

                            $col = 6;
                            if(!empty($supArray[$key])) {
                                $row++;
                                if($xyz<2){
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }else{
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }
                                foreach(array_slice($supArray[$key], $startRange, $endRange) as $suiii){
                                    $devideData4 = explode('?',$suiii);
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    if(empty($devideData4[2])){
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('');
                                        });
                                    }else{
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Main Offer');
                                        });
                                        $col++;

                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                        $sheet->cell(\PHPExcel_Cell::stringFromColumnIndex($col-1).$row, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Alternative Offer');
                                        });
                                        
                                    }
                                    $col++;
                                }
                            } // End main and alternative offer

                            $col = 6;
                            if(!empty($supArray[$key])) {
                                $row++;
                                if($xyz<2){
                                    $sheet->mergeCells('A' . $row . ':F' . $row);
                                }else{
                                    $sheet->mergeCells('A' . $row . ':F' . $row);
                                }
                                foreach(array_slice($supArray[$key], $startRange, $endRange) as $suittt){
                                    $devideDataddd = explode('?',$suittt);
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Unit Price');
                                        });
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Total Price');
                                        });

                                    if(!empty($devideDataddd[2])){
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Unit Price');
                                        });

                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Total Price');
                                        });
                                        
                                    }
                                    $col++;
                                }
                            } // End total price and alternative price

                            // Item section start here========
                            //$col = 1;
                            $mn = 0; 
                            foreach($ta as $tas){ 
                                $row++;
                                if($xyz<2){
                                    if($mn == 0){
                                        $sheet->mergeCells('A'.$row.':A'.(count($mainArray[$key])+($row-1)));
                                            
                                        $sheet->cell('A'.$row, function($cell) use($sl) {
                                            $cell->setValignment('center');
                                            $cell->setValue($sl);
                                        });

                                        $sheet->mergeCells('B'.$row.':B'.(count($mainArray[$key])+($row-1)));
                                        $sheet->cell('B'.$row, function($cell) use($key) {
                                            $cell->setValignment('center');
                                            $cell->setValue($key);
                                        });

                                    }

                                    foreach($tas['items'] as $itm){
                                        $remarks = !empty($itm->previsouSuppName) ? 'Sup: '.$itm->previsouSuppName: ''.''.!empty($itm->previsouUnitPrice)?' UP: '.$itm->previsouUnitPrice : ''.''.!empty($itm->previousDates)? $itm->previousDates : '';
                                        if($xyz<2){
                                            $sheet->cell('C'.$row, function($cell) use($itm) {
                                                $cell->setValue($itm->item_name);
                                            });
                                            $sheet->cell('D'.$row, function($cell) use($itm) {
                                                $cell->setValue($itm->denoName);
                                            });
                                            $sheet->cell('E'.$row, function($cell) use($itm) {
                                                $cell->setValue($itm->unit);
                                            });
                                            $sheet->cell('F'.$row, function($cell) use($remarks) {
                                                $cell->setValue($remarks);
                                            });
                                            //$sheet->row($row, array($sl++, , , , ));
                                        }
                                    }
                                }else{
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }

                                $col = 6;
                                foreach(array_slice($tas['supi'], $startRange, $endRange) as $sp){
                                    if(count($sp)>0){
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($sp) {
                                            $cell->setAlignment('right');
                                            $cell->setValue(ImageResizeController::custom_format($sp[0]->unit_price));
                                        });
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($sp) {
                                            $cell->setAlignment('right');
                                            $cell->setValue(ImageResizeController::custom_format($sp[0]->unit_price*$sp[0]->quoted_quantity));
                                        });

                                        if(!empty($sp[0]->alternative_unit_price)){
                                            $col++;
                                            $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                            $sheet->cell($cellNo, function($cell)use($sp) {
                                                $cell->setAlignment('right');
                                                $cell->setValue(ImageResizeController::custom_format($sp[0]->alternative_unit_price));
                                            });
                                            $col++;
                                            $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                            $sheet->cell($cellNo, function($cell)use($sp) {
                                                $cell->setAlignment('right');
                                                $cell->setValue(ImageResizeController::custom_format($sp[0]->alternative_unit_price*$sp[0]->alternative_quoted_quantity));
                                            });
                                        }
                                    }else{
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Not participated');
                                        });
                                    }
                                $col++;    
                                } // End item price foreach

                            }// End item foreach

                            

                            $col = 6;
                            if(!empty($supTotalAmountArray)){
                                $row++;
                                if($xyz<2){
                                    $sheet->mergeCells('A' . $row . ':F' . $row);
                                }else{
                                    $sheet->mergeCells('A' . $row . ':F' . $row);
                                }
                                foreach(array_slice($supTotalAmountArray[$key],$startRange, $endRange) as $sta){
                                    $devideData3 = explode('?',$sta);
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                    $sheet->cell($cellNo, function($cell)use($devideData3) {
                                        $cell->setAlignment('right');
                                        $cell->setValue(ImageResizeController::custom_format($devideData3[0]));
                                    });
                                    if(!empty($devideData3[1])){
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                        $sheet->cell($cellNo, function($cell)use($devideData3) {
                                        $cell->setAlignment('right');
                                        $cell->setValue(ImageResizeController::custom_format($devideData3[0]));
                                        });
                                    }
                                    $col++;
                                }
                                
                            } // End of subtotla amoutn

                            $xyz++; $startRange += $endRange;
                            }// End of while
                            $arSlmen = 0; $sl++;
                        } // endo fo foreach targetArray
                        $row++;
                        $row++;
                        $row++;
                        $sheet->mergeCells("A".$row.':M'.$row);
                        $sheet->row($row,array("A.  Certified that the quotations for the item have been invited from the enlisted suppliers/workshops and ".count($suppliersInf)." in no firms submitted quotations."));
                        $row++;
                        if(!empty($suppliersInf) && $suppliersInf[0]->total <= $orgInfo->purchase_limit ){
                            $sheet->mergeCells("A".$row.':M'.$row);
                            $sheet->row($row,array("B. The offer of ".$suppliersInf[0]->suppliernametext." lowest bidder is recommended for purchase order." ));
                        }
                    }// End of $targetArray     

                });
            })->export('xlsx');

        }// End of tender nature lot
    }

    public function nhqCstViewExcel($id){

        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

        $nsdId = 1;
         if(!empty(Auth::user()->nsd_bsd)){
            $nsdId = Auth::user()->nsd_bsd;
         }
        $orgInfo  = \App\NsdName::find($nsdId);

        $demandId = $explodes[0];
        $tenderId = $explodes[1];
        $demand     = \App\Demand::find($demandId);
        $tender     = \App\DemandToTender::where('demand_id','=',$demandId)->where('tender_id','=',$tenderId)->first();

        $mainTenderInfo = \App\Tender::find($tenderId);

        if($mainTenderInfo->tender_nature != 2){

            $selectedAsDraftCst = array_map('current',DemandSuppllierToCollQuotToItem::select('item_id')
                                            ->where('demand_id','=',$demandId)
                                            ->where('tender_id','=',$tenderId)
                                            ->where('select_as_draft_cst','=',1)
                                            ->get()->toArray());

            $itemToDemResult = ItemToDemand::where('demand_id','=',$demandId)
                                            ->where('tender_no','=',$tenderId)
                                            ->whereIn('id',$selectedAsDraftCst)
                                            ->orderBy('id','asc')->get();

            // Newlly added =================
            // ==============================
            $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
            $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('demand_id','!=',$demandId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
            $itemDmnArr = array_unique($itemDmnArr);
            // End newlly added =================
            // ==================================

            // for keeping checked ===============================
            // ===================================================
            $supplierAllreadySelected  = array_map('current',DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
            ->select('demand_to_collection_quotation.id')
            ->where('demand_to_collection_quotation.demand_id','=',$demandId)
            ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
            ->where('demand_to_collection_quotation.sel_as_draft_cst','=',1)
            ->orderBy('demand_to_collection_quotation.total','asc')
            ->get()->toArray());
     
            $targetArray  = array();
            $sls          = 0;
            $arIn         = 0;

            $supArray              =  array();
            $supTotalAmountArray   =  array();
            $supWiComArray         =  array();

            $suppliersInf  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
                    ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->where('demand_to_collection_quotation.sel_as_draft_cst','=',1)
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
                    ->get();

            foreach ($itemToDemResult as $key => $value) {

                $itemWiseSupp = array_map('current',DemandSuppllierToCollQuotToItem::
                                            select('dmn_to_cal_qut_id')
                                            ->where('item_id','=',$value->id)
                                            ->where('select_as_draft_cst','=',1)
                                            ->where('quoted_quantity','>',0)
                                            ->get()->toArray());
                $forJonPerfect = array_map('current',DemandSuppllierToCollQuotToItem::
                                            select('id')
                                            ->where('item_id','=',$value->id)
                                            ->where('quoted_quantity','>',0)
                                            ->get()->toArray());
                
                $supplierResult[$sls]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','demand_supplier_to_coll_qut_to_item.total_price','demand_supplier_to_coll_qut_to_item.discount_amount','demand_supplier_to_coll_qut_to_item.alternative_unit_price','demand_supplier_to_coll_qut_to_item.alternative_total_price','demand_supplier_to_coll_qut_to_item.comnt_on_cst_ech_itm','demand_supplier_to_coll_qut_to_item.id as cmnt_item_id','demand_supplier_to_coll_qut_to_item.select_as_draft_cst as item_select_as_draft_cst','demand_supplier_to_coll_qut_to_item.select_as_winner as select_as_winner')
                    ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                    ->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                    ->orderBy(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.quoted_quantity, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(demand_supplier_to_coll_qut_to_item.total_price, DECIMAL)"),'asc')
                    ->get();
                
                    foreach ($supplierResult[$sls] as $srs) {
                        $supArray[$sls][] = $srs->suppliernametext.'<br>'.$srs->head_office_address.'?'.$srs->id.'?'.$srs->alternative_unit_price.'?'.$srs->item_select_as_draft_cst.'?'.$srs->select_as_winner;
                        $supTotalAmountArray[$sls][] = $srs->total_price.'?'.$srs->alternative_total_price;

                        $supWiComArray[$sls][] = $srs->comnt_on_cst_ech_itm.'?'.$srs->cmnt_item_id.'?'.$srs->alternative_unit_price.'?'.$srs->id;
                    }

                $targetArray[$sls]['items'] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                    ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                    ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                    ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                    ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName', 'item_to_demand.id as itm_to_dn_id')
                    ->where('item_to_demand.demand_id','=',$demandId)
                    ->where('item_to_demand.tender_no','=',$tenderId)
                    ->where('demand_supplier_to_coll_qut_to_item.item_id','=',$value->id)
                    //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                    ->orderBy('item_to_demand.id','asc')
                    ->orderBy('demand_supplier_to_coll_qut_to_item.total_price','asc')
                    ->groupBy('demand_supplier_to_coll_qut_to_item.item_id')
                    ->get();

                    // Newly added =====================================================
                    // =================================================================
                    
                    $winnerInfOfThisItem = DemandToCollectionQuotation::join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id', '=', 'demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->select('demand_supplier_to_coll_qut_to_item.unit_price as last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                        ->where('demand_supplier_to_coll_qut_to_item.demand_id','!=',$value->demand_id)
                        ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                        ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
                        //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                        ->where('demand_supplier_to_coll_qut_to_item.select_as_winner','=',1)
                        // ->where('demand_to_collection_quotation.winner','=',1)
                        ->orderBy('demand_supplier_to_coll_qut_to_item.id','DESC')
                        ->get();
               
                    if(count($winnerInfOfThisItem) > 0){
                        foreach ($winnerInfOfThisItem as $key => $winnerIns) {
                            $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', $winnerIns->suppliernametext);
                            $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', $winnerIns->last_unti_price);
                            $targetArray[$sls]['items'][0]->setAttribute('previousDates', date('d-m-Y',strtotime($winnerIns->updated_at)));
                        }
                        
                    }else{
                        $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', '');
                        $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', '');
                        $targetArray[$sls]['items'][0]->setAttribute('previousDates', '');
                    }                        
                    // End newly added =================================================
                    // =================================================================

                foreach ($supplierResult[$sls] as $sr) {
                    $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('demand_id','=',$demandId)
                                                    ->where('tender_id','=',$tenderId)
                                                    ->where('item_id','=',$value->id)
                                                    ->where('dmn_to_cal_qut_id','=',$sr->id)
                                                    ->where('supplier_id','=',$sr->supplier_name)
                                                    ->get();
                                                    
                    //echo "<pre>"; print_r($sr->supplier_name); exit;
                }

                    $sls++;
                    $arIn++;
            }
            // echo "<pre>"; print_r($targetArray); exit;

                Excel::create('draft-cst-view - ' . date("d-m-Y H:i"), function ($excel) use ($supplierResult, $targetArray, $supArray, $supTotalAmountArray, $demandId, $supWiComArray, $supplierAllreadySelected, $demand, $tenderId, $tender, $suppliersInf,$orgInfo) {
                $excel->sheet('First Sheet', function ($sheet) use ($supplierResult, $targetArray, $supArray, $supTotalAmountArray, $demandId, $supWiComArray, $supplierAllreadySelected, $demand, $tenderId, $tender, $suppliersInf,$orgInfo) {
                    $row = 0;

                    //header Part Start
                    $hedtxt = $hedtxt = (!empty($suppliersInf)) ? ($suppliersInf[0]->total > $orgInfo->purchase_limit) ? 'PRELIMINARY COMPARATIVE STATEMENT- NSSD DHAKA' : 'COMPARATIVE STATEMENT- NSSD DHAKA' :'';
                    $headerTxt = trim($hedtxt);
                    $row++;
                    $sheet->mergeCells('A' . $row . ':J' . $row);
                    $sheet->cells('A' . $row . ':J' . $row, function ($cell) {
                        $cell->setFontWeight('bold');
                    });
                    $sheet->cells('A' . $row, function ($cell) {
                        $cell->setAlignment('center');
                    });
                    $sheet->row($row, array($headerTxt));
                    $headerTxt2 = !empty($tender->created_at) ? $tender->tender_number. " Date:".date('d F Y', strtotime($tender->created_at)): $tender->tender_number;
                    $row++;
                    $sheet->mergeCells('A' . $row . ':J' . $row);
                    $sheet->cells('A' . $row . ':J' . $row, function ($cell) {
                        $cell->setFontWeight('bold');
                    });
                    $sheet->cells('A' . $row, function ($cell) {
                        $cell->setAlignment('center');
                    });
                    $sheet->row($row, array($headerTxt2));

                    $row++;
                    $row++;
                    //Report Name End

                    function cellsToMergeByColsRow($start = NULL, $end = NULL, $row = NULL){
                        $merge = 'A1:A1';
                        if($start && $end && $row){
                            $start = \PHPExcel_Cell::stringFromColumnIndex($start);
                            $end = \PHPExcel_Cell::stringFromColumnIndex($end);
                            $merge = "$start{$row}:$end{$row}";

                        }

                        return $merge;
                    }

                    $sl=1;
                    if(!empty($targetArray)){
                        foreach($targetArray as $key => $ta){

                            $totalSuppCount = count($supplierResult[$key]);
                            $inEachTable    = 5;
                            $totalTable     = ceil($totalSuppCount/$inEachTable);
                            $xyz            = 1;
                            $startRange     = 0;
                            $endRange       = $inEachTable;
                            $sls = 0;

                            $hedSuppName = array('SL', 'Items Details', 'Deno', 'Quantity', 'Remark');
                            $onlyHed     = array();
                            $row++;
                            
                            while($totalTable >= $xyz){
                                $row++;
                                if($xyz<2){
                                    $sheet->cell('A'.$row.':'.'E'.$row, function($cell) {
                                        $cell->setFontWeight('bold');
                                    });
                                    $sheet->row($row, array('SL', 'Items Details', 'Deno', 'Quantity', 'Remark'));
                                }else{
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }

                                $col = 5;
                                if(!empty($supplierResult[$key])){
                                    foreach($supplierResult[$key]->slice($startRange, $endRange) as $sr){
                                        ++$sls;
                                        $placeName = OwnLibrary::numToOrdinalWord($sls) .' Lowest';
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        if(empty($sr->alternative_unit_price)){
                                            $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                            $sheet->cell($cellNo, function($cell)use($placeName) {
                                                $cell->setAlignment('center');
                                                $cell->setFontWeight('bold');
                                                $cell->setValue($placeName);
                                            });
                                        }else{
                                            $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 3, $row));
                                            $sheet->cell($cellNo, function($cell)use($placeName) {
                                                $cell->setAlignment('center');
                                                $cell->setFontWeight('bold');
                                                $cell->setValue($placeName);
                                            });
                                        }
                                        $col++;
                                    }
                                    
                                }

                            $col = 5;
                            if(!empty($supArray[$key])){ 
                                $row++;
                                if($xyz<2){ 
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }else{
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }
                                foreach(array_slice($supArray[$key], $startRange, $endRange) as $sui) {
                                    $devideData = explode('?',$sui);
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    if(empty($devideData[2])){
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                        $sheet->cell($cellNo, function($cell)use($devideData) {
                                            $cell->setAlignment('center');
                                            $cell->setFontWeight('bold');
                                            $cell->setValue(str_replace('<br>','',$devideData[0]));
                                        });
                                    }else{
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 3, $row));
                                        $sheet->cell($cellNo, function($cell)use($devideData) {
                                            $cell->setAlignment('center');
                                            $cell->setFontWeight('bold');
                                            $cell->setValue(str_replace('<br>','',$devideData[0]));
                                        });
                                    
                                    }
                                    $col++;
                                }
                            }// End supplier if

                            $col = 5;
                            if(!empty($supArray[$key])) {
                                $row++;
                                if($xyz<2){
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }else{
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }
                                foreach(array_slice($supArray[$key], $startRange, $endRange) as $suiii){
                                    $devideData4 = explode('?',$suiii);
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    if(empty($devideData4[2])){
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('');
                                        });
                                    }else{
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Main Offer');
                                        });
                                        $col++;

                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                        $sheet->cell(\PHPExcel_Cell::stringFromColumnIndex($col-1).$row, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Alternative Offer');
                                        });
                                        
                                    }
                                    $col++;
                                }
                            } // End main and alternative offer

                            $col = 5;
                            if(!empty($supArray[$key])) {
                                $row++;
                                if($xyz<2){
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }else{
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }
                                foreach(array_slice($supArray[$key], $startRange, $endRange) as $suittt){
                                    $devideDataddd = explode('?',$suittt);
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Unit Price');
                                        });
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Total Price');
                                        });

                                    if(!empty($devideDataddd[2])){
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Unit Price');
                                        });

                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Total Price');
                                        });
                                        
                                    }
                                    $col++;
                                }
                            } // End total price and alternative price

                            // Item section start here========
                            foreach($ta['items'] as $itm){
                                $row++;
                                $remarks = !empty($itm->previsouSuppName) ? 'Sup: '.$itm->previsouSuppName: ''.''.!empty($itm->previsouUnitPrice)?' UP: '.$itm->previsouUnitPrice : ''.''.!empty($itm->previousDates)? $itm->previousDates : '';
                                if($xyz<2){
                                    $sheet->row($row, array($sl++, $itm->item_name, $itm->denoName, $itm->unit, $remarks));
                                }else{
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }
                            }// End item foreach

                            $col = 5;
                            foreach(array_slice($ta['supi'], $startRange, $endRange) as $sp){
                                if(count($sp)>0){
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->cell($cellNo, function($cell)use($sp) {
                                        $cell->setAlignment('right');
                                        $cell->setValue(ImageResizeController::custom_format($sp[0]->unit_price));
                                    });
                                    $col++;
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->cell($cellNo, function($cell)use($sp) {
                                        $cell->setAlignment('right');
                                        $cell->setValue(ImageResizeController::custom_format($sp[0]->unit_price*$sp[0]->quoted_quantity));
                                    });

                                    if(!empty($sp[0]->alternative_unit_price)){
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($sp) {
                                            $cell->setAlignment('right');
                                            $cell->setValue(ImageResizeController::custom_format($sp[0]->alternative_unit_price));
                                        });
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($sp) {
                                            $cell->setAlignment('right');
                                            $cell->setValue(ImageResizeController::custom_format($sp[0]->alternative_unit_price*$sp[0]->alternative_quoted_quantity));
                                        });
                                    }
                                }else{
                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                    $sheet->cell($cellNo, function($cell)use($devideData4) {
                                        $cell->setAlignment('center');
                                        $cell->setValue('Not participated');
                                    });
                                }
                            $col++;    
                            } // End item price foreach

                            $col = 5;
                            if(!empty($supTotalAmountArray)){
                                $row++;
                                if($xyz<2){
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }else{
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }
                                foreach(array_slice($supTotalAmountArray[$key],$startRange, $endRange) as $sta){
                                    $devideData3 = explode('?',$sta);
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                    $sheet->cell($cellNo, function($cell)use($devideData3) {
                                        $cell->setAlignment('right');
                                        $cell->setValue(ImageResizeController::custom_format($devideData3[0]));
                                    });
                                    if(!empty($devideData3[1])){
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                        $sheet->cell($cellNo, function($cell)use($devideData3) {
                                        $cell->setAlignment('right');
                                        $cell->setValue(ImageResizeController::custom_format($devideData3[0]));
                                        });
                                    }
                                    $col++;
                                }
                                
                            } // End of subtotla amoutn

                            $xyz++; $startRange += $endRange;
                            }// End of while
                            
                        } // endo fo foreach targetArray

                        $row++;
                        $row++;
                        $row++;
                        $sheet->mergeCells("A".$row.':M'.$row);
                        $sheet->row($row,array("A.  Certified that the quotations for the item have been invited from the enlisted suppliers/workshops and ".count($suppliersInf)." in no firms submitted quotations."));
                        $row++;
                        if(!empty($suppliersInf) && $suppliersInf[0]->total <= $orgInfo->purchase_limit ){
                            $sheet->mergeCells("A".$row.':M'.$row);
                            $sheet->row($row,array("B. The offer of ".$suppliersInf[0]->suppliernametext." lowest bidder is recommended for purchase order." ));
                        }

                    }// End of $targetArray     

                });
            })->export('xlsx');
        }else{

            $selectedIds = array_map('current',\App\LotToSupplier::select('lot_name_id')
                                                ->where('lot_select_as_draft_cst','=',1)
                                                ->get()
                                                ->toArray());

            $lotNames = \App\LotNames::where('demand_id','=',$demandId)
                                    ->where('tender_id','=',$tenderId)
                                    ->whereIn('id',$selectedIds)
                                    ->orderBy('id','asc')
                                    ->get();                        

             // Newlly added =================
            // ==============================
            $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
            $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('demand_id','!=',$demandId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
            $itemDmnArr = array_unique($itemDmnArr);
            // End newlly added =================
            // ==================================

            $suppliersInf  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                    ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
                    ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
                    ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
                    ->get();                        

            $mainArray = array();

            $supArray              =  array();
            $supTotalAmountArray   =  array();
            $supWiComArray         =  array();
            
            foreach ($lotNames as $lotkey => $lotval) {
                    
                $selectSuppLower = array_map('current',\App\LotToSupplier::select('mnd_to_col_qtn_id')
                                                    ->where('lot_name_id','=',$lotval->id)
                                                    ->where('lot_select_as_draft_cst','=',1)
                                                    ->orderBy('lot_wise_total','asc')
                                                    ->get()->toArray());

                $itemToDemResult = ItemToDemand::where('demand_id','=',$demandId)
                                                ->where('tender_no','=',$tenderId)
                                                ->where('lot_unq_id','=',$lotval->id)
                                                ->orderBy('id','asc')
                                                ->get();                           

                $lotNameInIndex = $lotval->lot_name;
                $targetArray  = array();
                $sls          = 0;
                $arIn         = 0;

                $itemToDemResultIds = array_map('current',ItemToDemand::select('id')
                                                ->where('demand_id','=',$demandId)
                                                ->where('tender_no','=',$tenderId)
                                                ->where('lot_unq_id','=',$lotval->id)
                                                ->orderBy('id','asc')
                                                ->get()->toArray());     

                $itemWiseSupp = array_unique(array_map('current',DemandSuppllierToCollQuotToItem::
                                                select('dmn_to_cal_qut_id')
                                                ->where('select_as_draft_cst','=',1)
                                                ->whereIn('item_id',$itemToDemResultIds)
                                                ->where('quoted_quantity','>',0)
                                                ->get()->toArray()));
                
                    $supplierResultFir[$lotNameInIndex]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                        ->join('lot_to_supplier','demand_to_collection_quotation.id','=','lot_to_supplier.mnd_to_col_qtn_id')
                        ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','lot_to_supplier.id as lot_to_sup_id','lot_to_supplier.lot_name_id','lot_to_supplier.mnd_to_col_qtn_id','lot_to_supplier.mnd_to_col_qtn_id','lot_to_supplier.lot_wise_total_qty','lot_to_supplier.lot_wise_total','lot_to_supplier.altr_quoted_total_quantity','lot_to_supplier.altr_total_price','lot_to_supplier.lot_select_as_draft_cst','lot_to_supplier.comnt_on_cst_ech_lot','lot_to_supplier.lot_select_as_winner')
                        ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                        ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                        ->where('lot_to_supplier.lot_name_id','=',$lotval->id)
                        ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                        //->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total_qty, DECIMAL)"),'desc')
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total, DECIMAL)"),'asc')
                        ->get();

                        foreach ($supplierResultFir[$lotNameInIndex] as $srs) {
                            $supArray[$lotNameInIndex][] = $srs->suppliernametext.'<br>'.$srs->head_office_address.'?'.$srs->id.'?'.$srs->altr_total_price.'?'.$srs->lot_select_as_winner.'?'.$srs->lot_name_id.'?'.$srs->lot_to_sup_id;

                            $supTotalAmountArray[$lotNameInIndex][] = $srs->lot_wise_total.'?'.$srs->altr_total_price;

                            $supWiComArray[$lotNameInIndex][] = $srs->comnt_on_cst_ech_lot.'?'.$srs->lot_to_sup_id.'?'.$srs->altr_total_price.'?'.$srs->id;
                        }
                
                foreach ($itemToDemResult as $key => $value) {
                    $itemWiseSupp = array_unique(array_map('current',DemandSuppllierToCollQuotToItem::
                                                select('dmn_to_cal_qut_id')
                                                ->where('item_id','=',$value->id)
                                                ->where('quoted_quantity','>',0)
                                                ->get()->toArray()));
                    
                    $forJonPerfect = array_map('current',DemandSuppllierToCollQuotToItem::
                                                select('id')
                                                ->where('item_id','=',$value->id)
                                                ->whereIn('dmn_to_cal_qut_id',$itemWiseSupp)
                                                ->where('quoted_quantity','>',0)
                                                ->get()->toArray());

                    $supplierResult[$sls]  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                        ->join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->join('lot_to_supplier','demand_to_collection_quotation.id','=','lot_to_supplier.mnd_to_col_qtn_id')
                        ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address','demand_supplier_to_coll_qut_to_item.total_price','demand_supplier_to_coll_qut_to_item.discount_amount','demand_supplier_to_coll_qut_to_item.alternative_unit_price','demand_supplier_to_coll_qut_to_item.alternative_total_price','demand_supplier_to_coll_qut_to_item.comnt_on_cst_ech_itm','demand_supplier_to_coll_qut_to_item.id as cmnt_item_id','demand_supplier_to_coll_qut_to_item.select_as_draft_cst as item_select_as_draft_cst')
                        ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                        ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                        ->where('lot_to_supplier.lot_name_id','=',$lotval->id)
                        ->where('demand_supplier_to_coll_qut_to_item.select_as_draft_cst','=',1)
                        ->whereIn('demand_to_collection_quotation.id',$itemWiseSupp)
                        ->whereIn('demand_supplier_to_coll_qut_to_item.id',$forJonPerfect)
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total_qty, DECIMAL)"),'desc')
                        ->orderBy(\DB::raw("CONVERT(lot_to_supplier.lot_wise_total, DECIMAL)"),'asc')
                        ->get();
                    
                    $targetArray[$sls]['items'] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                        ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                        ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                        ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName', 'item_to_demand.id as itm_to_dn_id')
                        ->where('item_to_demand.demand_id','=',$demandId)
                        ->where('item_to_demand.tender_no','=',$tenderId)
                        ->where('demand_supplier_to_coll_qut_to_item.item_id','=',$value->id)
                        //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                        ->orderBy('item_to_demand.id','asc')
                        ->orderBy('demand_supplier_to_coll_qut_to_item.total_price','asc')
                        ->groupBy('demand_supplier_to_coll_qut_to_item.item_id')
                        ->get();

                        // Newly added =====================================================
                        // =================================================================
                        
                        $winnerInfOfThisItem = DemandToCollectionQuotation::join('demand_supplier_to_coll_qut_to_item','demand_to_collection_quotation.id', '=', 'demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                            ->select('demand_supplier_to_coll_qut_to_item.unit_price as last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                            ->where('demand_supplier_to_coll_qut_to_item.demand_id','!=',$value->demand_id)
                            ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                            ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
                            //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                            ->whereIn('demand_to_collection_quotation.id',$itemDmnArr)
                            // ->where('demand_to_collection_quotation.winner','=',1)
                            ->orderBy('demand_supplier_to_coll_qut_to_item.id','DESC')
                            ->get();
                   
                        if(count($winnerInfOfThisItem) > 0){
                            foreach ($winnerInfOfThisItem as $key => $winnerIns) {
                                $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', $winnerIns->suppliernametext);
                                $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', $winnerIns->last_unti_price);
                                $targetArray[$sls]['items'][0]->setAttribute('previousDates', date('d-m-Y',strtotime($winnerIns->updated_at)));
                            }
                            
                        }else{
                            $targetArray[$sls]['items'][0]->setAttribute('previsouSuppName', '');
                            $targetArray[$sls]['items'][0]->setAttribute('previsouUnitPrice', '');
                            $targetArray[$sls]['items'][0]->setAttribute('previousDates', '');
                        }                        
                        // End newly added =================================================
                        // =================================================================
                    foreach ($supplierResult[$sls] as $sr) {
                        $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('demand_id','=',$demandId)
                                                        ->where('tender_id','=',$tenderId)
                                                        ->where('item_id','=',$value->id)
                                                        ->where('dmn_to_cal_qut_id','=',$sr->id)
                                                        ->where('supplier_id','=',$sr->supplier_name)
                                                        ->get();                          
                    }
                        $sls++;
                        $arIn++;
                }

                $mainArray[$lotNameInIndex] = $targetArray;

            }

                Excel::create('draft-cst-view-' . date("d-m-Y H:i"), function ($excel) use ($supplierResult, $targetArray, $supArray, $supTotalAmountArray, $demandId, $supWiComArray, $demand, $tenderId, $tender,$suppliersInf,$supplierResultFir,$mainArray,$orgInfo) {
                $excel->sheet('First Sheet', function ($sheet) use ($supplierResult, $targetArray, $supArray, $supTotalAmountArray, $demandId, $supWiComArray, $demand, $tenderId, $tender,$suppliersInf,$supplierResultFir,$mainArray,$orgInfo) {
                    $row = 0;

                    //header Part Start
                    $hedtxt = $hedtxt = (!empty($suppliersInf)) ? ($suppliersInf[0]->total > $orgInfo->purchase_limit) ? 'PRELIMINARY COMPARATIVE STATEMENT- NSSD DHAKA' : 'COMPARATIVE STATEMENT- NSSD DHAKA' :'';
                    $headerTxt = trim($hedtxt);
                    $row++;
                    $sheet->mergeCells('A' . $row . ':J' . $row);
                    $sheet->cells('A' . $row . ':J' . $row, function ($cell) {
                        $cell->setFontWeight('bold');
                    });
                    $sheet->cells('A' . $row, function ($cell) {
                        $cell->setAlignment('center');
                    });
                    $sheet->row($row, array($headerTxt));
                    $headerTxt2 = !empty($tender->created_at) ? $tender->tender_number. " Date:".date('d F Y', strtotime($tender->created_at)): $tender->tender_number;
                    $row++;
                    $sheet->mergeCells('A' . $row . ':J' . $row);
                    $sheet->cells('A' . $row . ':J' . $row, function ($cell) {
                        $cell->setFontWeight('bold');
                    });
                    $sheet->cells('A' . $row, function ($cell) {
                        $cell->setAlignment('center');
                    });
                    $sheet->row($row, array($headerTxt2));

                    $row++;
                    $row++;
                    //Report Name End

                    function cellsToMergeByColsRow($start = NULL, $end = NULL, $row = NULL){
                        $merge = 'A1:A1';
                        if($start && $end && $row){
                            $start = \PHPExcel_Cell::stringFromColumnIndex($start);
                            $end = \PHPExcel_Cell::stringFromColumnIndex($end);
                            $merge = "$start{$row}:$end{$row}";

                        }

                        return $merge;
                    }

                    $arSlmen=1;
                    if(!empty($mainArray)){
                        foreach($mainArray as $key => $ta){

                            $totalSuppCount = count($supplierResultFir[$key]);
                            $inEachTable    = 2;
                            $totalTable     = ceil($totalSuppCount/$inEachTable);
                            $xyz            = 1;
                            $startRange     = 0;
                            $endRange       = $inEachTable;
                            $sls = 0;

                            $hedSuppName = array('SL','Lot Name', 'Items Details', 'Deno', 'Quantity', 'Remark');
                            $onlyHed     = array();
                            $row++;
                            
                            while($totalTable >= $xyz){
                                $row++;
                                if($xyz<2){
                                    $sheet->cell('A'.$row.':'.'F'.$row, function($cell) {
                                        $cell->setFontWeight('bold');
                                    });
                                    $sheet->row($row, array('SL','Lot Name', 'Items Details', 'Deno', 'Quantity', 'Remark'));
                                }else{
                                    $sheet->mergeCells('A' . $row . ':F' . $row);
                                }

                                $col = 6;
                                if(!empty($supplierResultFir[$key])){
                                    foreach($supplierResultFir[$key]->slice($startRange, $endRange) as $sr){
                                        ++$sls;
                                        $placeName = OwnLibrary::numToOrdinalWord($sls) .' Lowest';
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        if(empty($sr->alternative_unit_price)){
                                            $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                            $sheet->cell($cellNo, function($cell)use($placeName) {
                                                $cell->setAlignment('center');
                                                $cell->setFontWeight('bold');
                                                $cell->setValue($placeName);
                                            });
                                        }else{
                                            $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 3, $row));
                                            $sheet->cell($cellNo, function($cell)use($placeName) {
                                                $cell->setAlignment('center');
                                                $cell->setFontWeight('bold');
                                                $cell->setValue($placeName);
                                            });
                                        }
                                        $col++;
                                    }
                                    
                                }

                            $col = 6;
                            $sl=1;
                            if(!empty($supArray[$key])){ 
                                $row++;
                                if($xyz<2){ 
                                    $sheet->mergeCells('A' . $row . ':F' . $row);
                                }else{
                                    $sheet->mergeCells('A' . $row . ':F' . $row);
                                }
                                foreach(array_slice($supArray[$key], $startRange, $endRange) as $sui){
                                    $devideData = explode('?',$sui);
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    if(empty($devideData[2])){
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                        $sheet->cell($cellNo, function($cell)use($devideData) {
                                            $cell->setAlignment('center');
                                            $cell->setFontWeight('bold');
                                            $cell->setValue(str_replace('<br>','',$devideData[0]));
                                        });
                                    }else{
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 3, $row));
                                        $sheet->cell($cellNo, function($cell)use($devideData) {
                                            $cell->setAlignment('center');
                                            $cell->setFontWeight('bold');
                                            $cell->setValue(str_replace('<br>','',$devideData[0]));
                                        });
                                    
                                    }
                                    $col++;
                                }
                            }// End supplier if

                            $col = 6;
                            if(!empty($supArray[$key])) {
                                $row++;
                                if($xyz<2){
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }else{
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }
                                foreach(array_slice($supArray[$key], $startRange, $endRange) as $suiii){
                                    $devideData4 = explode('?',$suiii);
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    if(empty($devideData4[2])){
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('');
                                        });
                                    }else{
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Main Offer');
                                        });
                                        $col++;

                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                        $sheet->cell(\PHPExcel_Cell::stringFromColumnIndex($col-1).$row, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Alternative Offer');
                                        });
                                        
                                    }
                                    $col++;
                                }
                            } // End main and alternative offer

                            $col = 6;
                            if(!empty($supArray[$key])) {
                                $row++;
                                if($xyz<2){
                                    $sheet->mergeCells('A' . $row . ':F' . $row);
                                }else{
                                    $sheet->mergeCells('A' . $row . ':F' . $row);
                                }
                                foreach(array_slice($supArray[$key], $startRange, $endRange) as $suittt){
                                    $devideDataddd = explode('?',$suittt);
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Unit Price');
                                        });
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Total Price');
                                        });

                                    if(!empty($devideDataddd[2])){
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Unit Price');
                                        });

                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Total Price');
                                        });
                                        
                                    }
                                    $col++;
                                }
                            } // End total price and alternative price

                            // Item section start here========
                            //$col = 1;
                            $mn = 0; 
                            foreach($ta as $tas){ 
                                $row++;
                                if($xyz<2){
                                    if($mn == 0){
                                        $sheet->mergeCells('A'.$row.':A'.(count($mainArray[$key])+($row-1)));
                                            
                                        $sheet->cell('A'.$row, function($cell) use($sl) {
                                            $cell->setValignment('center');
                                            $cell->setValue($sl);
                                        });

                                        $sheet->mergeCells('B'.$row.':B'.(count($mainArray[$key])+($row-1)));
                                        $sheet->cell('B'.$row, function($cell) use($key) {
                                            $cell->setValignment('center');
                                            $cell->setValue($key);
                                        });

                                    }

                                    foreach($tas['items'] as $itm){
                                        $remarks = !empty($itm->previsouSuppName) ? 'Sup: '.$itm->previsouSuppName: ''.''.!empty($itm->previsouUnitPrice)?' UP: '.$itm->previsouUnitPrice : ''.''.!empty($itm->previousDates)? $itm->previousDates : '';
                                        if($xyz<2){
                                            $sheet->cell('C'.$row, function($cell) use($itm) {
                                                $cell->setValue($itm->item_name);
                                            });
                                            $sheet->cell('D'.$row, function($cell) use($itm) {
                                                $cell->setValue($itm->denoName);
                                            });
                                            $sheet->cell('E'.$row, function($cell) use($itm) {
                                                $cell->setValue($itm->unit);
                                            });
                                            $sheet->cell('F'.$row, function($cell) use($remarks) {
                                                $cell->setValue($remarks);
                                            });
                                            //$sheet->row($row, array($sl++, , , , ));
                                        }
                                    }
                                }else{
                                    $sheet->mergeCells('A' . $row . ':E' . $row);
                                }

                                $col = 6;
                                foreach(array_slice($tas['supi'], $startRange, $endRange) as $sp){
                                    if(count($sp)>0){
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($sp) {
                                            $cell->setAlignment('right');
                                            $cell->setValue(ImageResizeController::custom_format($sp[0]->unit_price));
                                        });
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->cell($cellNo, function($cell)use($sp) {
                                            $cell->setAlignment('right');
                                            $cell->setValue(ImageResizeController::custom_format($sp[0]->unit_price*$sp[0]->quoted_quantity));
                                        });

                                        if(!empty($sp[0]->alternative_unit_price)){
                                            $col++;
                                            $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                            $sheet->cell($cellNo, function($cell)use($sp) {
                                                $cell->setAlignment('right');
                                                $cell->setValue(ImageResizeController::custom_format($sp[0]->alternative_unit_price));
                                            });
                                            $col++;
                                            $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                            $sheet->cell($cellNo, function($cell)use($sp) {
                                                $cell->setAlignment('right');
                                                $cell->setValue(ImageResizeController::custom_format($sp[0]->alternative_unit_price*$sp[0]->alternative_quoted_quantity));
                                            });
                                        }
                                    }else{
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));
                                        $sheet->cell($cellNo, function($cell)use($devideData4) {
                                            $cell->setAlignment('center');
                                            $cell->setValue('Not participated');
                                        });
                                    }
                                $col++;    
                                } // End item price foreach

                            }// End item foreach

                            

                            $col = 6;
                            if(!empty($supTotalAmountArray)){
                                $row++;
                                if($xyz<2){
                                    $sheet->mergeCells('A' . $row . ':F' . $row);
                                }else{
                                    $sheet->mergeCells('A' . $row . ':F' . $row);
                                }
                                foreach(array_slice($supTotalAmountArray[$key],$startRange, $endRange) as $sta){
                                    $devideData3 = explode('?',$sta);
                                    $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                    $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                    $sheet->cell($cellNo, function($cell)use($devideData3) {
                                        $cell->setAlignment('right');
                                        $cell->setValue(ImageResizeController::custom_format($devideData3[0]));
                                    });
                                    if(!empty($devideData3[1])){
                                        $col++;
                                        $cellNo = \PHPExcel_Cell::stringFromColumnIndex($col).$row;
                                        $sheet->mergeCells(cellsToMergeByColsRow($col, $col += 1, $row));

                                        $sheet->cell($cellNo, function($cell)use($devideData3) {
                                        $cell->setAlignment('right');
                                        $cell->setValue(ImageResizeController::custom_format($devideData3[0]));
                                        });
                                    }
                                    $col++;
                                }
                                
                            } // End of subtotla amoutn

                            $xyz++; $startRange += $endRange;
                            }// End of while
                            $arSlmen = 0; $sl++;
                        } // endo fo foreach targetArray

                        $row++;
                        $row++;
                        $row++;
                        $sheet->mergeCells("A".$row.':M'.$row);
                        $sheet->row($row,array("A.  Certified that the quotations for the item have been invited from the enlisted suppliers/workshops and ".count($suppliersInf)." in no firms submitted quotations."));
                        $row++;
                        if(!empty($suppliersInf) && $suppliersInf[0]->total <= $orgInfo->purchase_limit ){
                            $sheet->mergeCells("A".$row.':M'.$row);
                            $sheet->row($row,array("B. The offer of ".$suppliersInf[0]->suppliernametext." lowest bidder is recommended for purchase order." ));
                        }
                    }// End of $targetArray     

                });
            })->export('xlsx');

        }// End of tender nature lot

        //return View::make('floating-tender.headqtr-approval-view')->with(compact('demandsUp','tenderId'));
        
    }


}
