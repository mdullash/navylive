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

        $demandId = $id;
        $demandInfo = \App\Demand::find($demandId);
        $tenderNumber = Tender::find($demandInfo->tender_id)->value('tender_number');
        $selectedSupplier  = DemandToCollectionQuotation::where('demand_id','=',$demandId)->where('winner','=',1)->first();

            $qyeryResutl = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName','item_to_demand.id as ItemId','demand_supplier_to_coll_qut_to_item.id as dmndtosupcotId')
                ->where('item_to_demand.demand_id','=',$demandId)
                ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                ->where('demand_to_collection_quotation.winner','=',1)
                ->get();

                //echo "<pre>"; print_r($qyeryResutl); exit;

            $maxDemandId = Demand::max('id');
            $maxId        = $maxDemandId+1;
            $currentYear  = date('Y');
            $year         = ( date('m') > 6) ? $currentYear.'-'.date('Y') + 1 : (date('Y')-1).'-'.$currentYear;
            $poNumberCreate  = $maxId.'('.$year.')';     

        
        return View::make('po-generation.create')->with(compact('demandId','selectedSupplier','demandInfo','qyeryResutl','poNumberCreate','tenderNumber'));

    }


    public function postPoGenerate(Request $request){
        $this->tableAlies = \Session::get('zoneAlise');

        $demand_id  = $request->demand_id;
        $demandInfo = \App\Demand::find($demand_id);
        $tenderIn   = \App\Tender::find($demandInfo->tender_id);
        $orgInfo    = \App\NsdName::find($tenderIn->nsd_id);
        $purLimit   = '';

        $finalTotal = 0;
        if(count($request->unit_price)>0){
            for ($i = 0; count($request->unit_price) > $i; $i++) {
                if(!empty($request->unit_price[$i])){
                    $dmn_to_sup_col_qut = DemandSuppllierToCollQuotToItem::find($request->dmndtosupcotId[$i]);

                    $dmn_to_sup_col_qut->final_doscount_amount = $request->discount_amount[$i];

                    $unitPriceAftDis = $request->unit_price[$i] - $request->discount_amount[$i];
                    $sumTotal = $unitPriceAftDis * $request->quantity[$i];
                    $finalTotal+=$sumTotal;
                    $dmn_to_sup_col_qut->save();
                }
            }
        }

        $update_coll_qut = DemandToCollectionQuotation::find($request->sel_sup_id);
        $update_coll_qut->final_total = $finalTotal;
        $update_coll_qut->save();

        
        $data['demand_id']     = $request->demand_id;
        $data['po_number']     = $request->po_number;
        $data['date']          = $request->date;
        $data['delivery_date'] = $request->delivery_date;
        $data['supplier_name'] = $request->supplier_name;
        $data['dorportho_no']  = $request->dorportho_no;



        $data['demandInfo'] = \App\Demand::find($data['demand_id']);
        $data['selectedSupplier']  = DemandToCollectionQuotation::where('demand_id','=',$data['demand_id'])->where('winner','=',1)->first();

            $data['qyeryResutl'] = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName','item_to_demand.id as ItemId','demand_supplier_to_coll_qut_to_item.id as dmndtosupcotId')
                ->where('item_to_demand.demand_id','=',$data['demand_id'])
                ->where('demand_to_collection_quotation.demand_id','=',$data['demand_id'])
                ->where('demand_to_collection_quotation.winner','=',1)
                ->get();

            $demandUp = Demand::find($request->demand_id);
            $demandUp->po_status        = 1;
            $demandUp->po_generate_by   = Auth::user()->id;
            $demandUp->po_generate_date = date('Y-m-d H:i:s');
            $demandUp->current_status  = 11;
            $demandUp->save();


            // =====================================================
            // Newly added for itemtotender
            //======================================================
            $demand_to_coll_supp = DemandToCollectionQuotation::where('demand_id','=',$demand_id)->where('winner','=',1)->first();
            $tender = Tender::find($tenderIn->id);

                $tender->supplier_id = $demand_to_coll_supp->supplier_name;
                //$tender->po_number   = $request->po_number;
                //$tender->po_number   = $request->po_number;
                //$tender->date_line   = empty($request->date_line) ? null : date('Y-m-d',strtotime($request->date_line));
                //$tender->work_order_date = empty($request->work_order_date) ? null : date('Y-m-d',strtotime($request->work_order_date));
                //$tender->delivery_date = empty($request->delivery_date) ? null : date('Y-m-d',strtotime($request->delivery_date));

                if($tender->save()){

                    $squeryResult = \App\ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                        ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                        ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                        ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                        ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                        ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country',$this->tableAlies.'_items.currency_name','deno.name as denoName','item_to_demand.id as ItemId','demand_supplier_to_coll_qut_to_item.id as dmndtosupcotId','item_to_demand.item_id as real_item_id','demand_to_collection_quotation.total','demand_to_collection_quotation.final_total')
                        ->where('item_to_demand.demand_id','=',$demand_id)
                        ->where('demand_to_collection_quotation.demand_id','=',$demand_id)
                        ->where('demand_to_collection_quotation.winner','=',1)
                        ->get();

                        if(!empty($squeryResult)){

                            foreach ($squeryResult as $val) {

                                $discountAmount = 0;

                                if(!empty($val->discount_amount)){
                                    $discountAmount += $val->discount_amount;
                                }
                                if(!empty($val->final_doscount_amount)){
                                    $discountAmount += $val->final_doscount_amount;
                                }

                                $checkAlreadyExits = \App\ItemToTender::where('tender_id','=',$tender->id)->where('item_id','=',$val->real_item_id)->get();
                                
                                if(count($checkAlreadyExits)<1){

                                    $v = \Validator::make($request->all(), ['po_number' => 'required|unique:'.\Session::get("zoneAlise").'_tenders,po_number',]);
                                    if ($v->fails()) {
                                        return redirect('po-generate-view/'.$request->demand_id)->withErrors($v->errors())->withInput();
                                    }

                                    $itemtotender = new \App\ItemToTender();

                                    $itemtotender->tender_id = $tender->id;
                                    $itemtotender->item_id = $val->real_item_id;
                                    $itemtotender->quantity = $val->quantity;
                                    $itemtotender->unit_price = $val->unit_price;
                                    $itemtotender->unit_price_in_bdt = $val->unit_price*$val->currency_rate;
                                    $itemtotender->currency_name = $val->currency_name;
                                    $itemtotender->conversion = $val->currency_rate;


                                    $itemtotender->discount_price = $discountAmount;
                                    $itemtotender->discount_price_in_bdt = $discountAmount*$val->currency_rate;
                                    if(!empty($val->final_total)){
                                        $itemtotender->total = $val->final_total;
                                    }else{
                                        $itemtotender->total = $val->total;
                                    }
                                    
                                    if($itemtotender->save()){
                                        $itemtotenderUpA = \App\ItemToTender::find($itemtotender->id);
                                        $itemtotenderUpA->all_org_itmtotender_id = $itemtotender->id;
                                        $itemtotenderUpA->save();
                                    }
                                } // End if =========================
                                
                                
                            } // End foreach loop ====================

                            $tenderPoUp = Tender::find($tenderIn->id);
                            $tenderPoUp->po_number   = $request->po_number;
                            $tenderPoUp->save();

                        }


                }
            // =====================================================
            // End for itemtotender
            //======================================================
            

        $pdf= PDF::loadView('po-generation.po-generator-pdf',$data);
            //$pdf->setPaper('letter', 'landscape');
            return $pdf->stream('po-generator-pdf.pdf');

    }
    

    public function crSection($id){

        $this->tableAlies = \Session::get('zoneAlise');

        $demandId = $id;
        $demandInfo = \App\Demand::find($demandId);
        $selectedSupplier  = DemandToCollectionQuotation::where('demand_id','=',$demandId)->where('winner','=',1)->first();

            $qyeryResutl = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName','item_to_demand.id as ItemId','demand_supplier_to_coll_qut_to_item.id as dmndtosupcotId')
                ->where('item_to_demand.demand_id','=',$demandId)
                ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                ->where('demand_to_collection_quotation.winner','=',1)
                ->get();

        
        return View::make('po-generation.cr-view')->with(compact('demandId','selectedSupplier','demandInfo','qyeryResutl'));

    }

    public function crViewPost(Request $request){

        //dd($request->all());
        if(isset($request->dmndtosupcotId)){

            for($i=0; count($request->dmndtosupcotId)>$i; $i++){

                $dmn_to_sup_col_qut = DemandSuppllierToCollQuotToItem::find($request->dmndtosupcotId[$i]);

                $updateVal      = $dmn_to_sup_col_qut->cr_receive_qty;
                $currInspSta    = $dmn_to_sup_col_qut->inspection_sta;


                //if( empty($currInspSta) || $currInspSta==2 ){
                    $updateVal = $request->quantity[$request->dmndtosupcotId[$i]][0];
                //}

                //if( empty($currInspSta) || $currInspSta==2){
                    $dmn_to_sup_col_qut->inspection_sta = 3;
                //}

                $dmn_to_sup_col_qut->cr_receive_qty = $updateVal;
                $dmn_to_sup_col_qut->save();

            }

            $demandUp = Demand::find($request->demand_id);
            $demandUp->cr_status        = 1;
            $demandUp->cr_check_by   = Auth::user()->id;
            $demandUp->cr_check_date = date('Y-m-d H:i:s');
            $demandUp->save();

            Session::flash('success', 'Data cupdated successfully');
                return redirect('cr-section/'.$request->demand_id);


        }else{
            Session::flash('error', 'Data could not updated');
                return redirect('cr-section/'.$request->demand_id);
        }

        
    }


    public function inspectionSection($id=null){

        $this->tableAlies = \Session::get('zoneAlise');

        $demandId = $id;
        $demandInfo = \App\Demand::find($demandId);
        $selectedSupplier  = DemandToCollectionQuotation::where('demand_id','=',$demandId)->where('winner','=',1)->first();

            $qyeryResutl = ItemToDemand::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
                ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName','item_to_demand.id as ItemId','demand_supplier_to_coll_qut_to_item.id as dmndtosupcotId')
                ->where('item_to_demand.demand_id','=',$demandId)
                ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                ->where('demand_to_collection_quotation.winner','=',1)
                ->where('demand_supplier_to_coll_qut_to_item.cr_receive_qty','!=','')
                ->whereNotIn('demand_supplier_to_coll_qut_to_item.inspection_sta',[1,2])
                //->where('demand_supplier_to_coll_qut_to_item.inspection_sta','!=',2)
                ->get();

        
        return View::make('po-generation.inspection-view')->with(compact('demandId','selectedSupplier','demandInfo','qyeryResutl'));

    }

    public function postInspection(Request $request){
        //dd($request->all());
        if(isset($request->all_sup)){
            
            for($i=0; count($request->all_sup)>$i; $i++){
                $dmn_to_sup_col_qut = DemandSuppllierToCollQuotToItem::find($request->all_sup[$i]);

                $insPectStatus = 3;
                if(isset($request->dmndtosupcotId[$i])){
                    $insPectStatus = $request->dmndtosupcotId[$i];
                }

                $curValVal      = $dmn_to_sup_col_qut->cr_receive_qty;
                $presentInspSta = $dmn_to_sup_col_qut->inspection_sta;
                $totalAppCurr   = $dmn_to_sup_col_qut->total_approved;
                $totalApproved  = '';

                if($insPectStatus==1){
                    if(empty($totalAppCurr)){
                        $totalApproved = $curValVal;

                        $dmn_to_sup_col_qut->cr_receive_qty    = NULL;
                        $dmn_to_sup_col_qut->total_approved    = $totalApproved;
                    }else{
                        $totalApproved = $totalAppCurr.','.$curValVal;

                        $dmn_to_sup_col_qut->cr_receive_qty    = NULL;
                        $dmn_to_sup_col_qut->total_approved    = $totalApproved;
                    }

                    $approveQtyTbl = new DemandCrToInspection();

                    $approveQtyTbl->dmd_sup_to_coll_qut_to_itm_id    = $request->all_sup[$i];
                    $approveQtyTbl->demand_id                        = $dmn_to_sup_col_qut->demand_id;
                    $approveQtyTbl->approve_qty                      = $curValVal;
                    $approveQtyTbl->inspection_com                   = $request->inspection_comment[$i];
                    $approveQtyTbl->approve_date                     = date('Y-m-d H:i:s');
                    $approveQtyTbl->save();

                }
                if($insPectStatus==2){
                    $dmn_to_sup_col_qut->cr_receive_qty    = NULL;
                }

                $dmn_to_sup_col_qut->inspection_sta        = $insPectStatus;
                $dmn_to_sup_col_qut->inspection_comment    = $request->inspection_comment[$i];
                $dmn_to_sup_col_qut->save();

                
            }

            $demandUp = Demand::find($request->demand_id);
            $demandUp->inspection_status  = 1;
            $demandUp->inspection_by      = Auth::user()->id;
            $demandUp->inspection_date    = date('Y-m-d H:i:s');
            $demandUp->save();

            Session::flash('success', 'Data updated successfully');
                return redirect('inspection-section/'.$request->demand_id);

        }else{
            Session::flash('error', 'Data could not updated');
                return redirect('inspection-section/'.$request->demand_id);
        }
    }

    public function v44voucherPdfView($id){

        $this->tableAlies = \Session::get('zoneAlise');

        $inspectedItems = DemandCrToInspection::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.id', '=', 'demand_cr_to_inspection.dmd_sup_to_coll_qut_to_itm_id')
            ->join('item_to_demand','demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
            ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
            ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*', 'demand_cr_to_inspection.*')
            ->where('demand_supplier_to_coll_qut_to_item.demand_id','=',$id)
            ->get();

            //echo "<pre>"; print_r($inspectedItems); exit;

        $pdf = PDF::loadView('po-generation.v44voucher-pdf',compact('inspectedItems'));
        return $pdf->stream('v44voucher-pdf.pdf');

    }


}
