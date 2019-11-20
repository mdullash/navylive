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

class FloatingTenderController extends Controller
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
    
    public function create($id)
    { 
        $this->tableAlies = \Session::get('zoneAlise');
//        $nsdNames = NsdName::where('status_id','=',1)->get();
//        $supplyCategories = SupplyCategory::where('status_id','=',1)->get();
        $id = $id;
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
            $supplyCategories->whereIn('id',[1,2,3,12]);
            $supplyCategories->where('status_id','=',1);
        $supplyCategories = $supplyCategories->get();

        $demandInfo = \App\Demand::find($id);

        $tenderNumber = $maxDemandId = Tender::max('id');
        $maxId        = $maxDemandId+1;
        // $currentYear  = date('Y');
        $currentYear  = ( date('m') > 6) ? date('y').'-'.(date('y') + 1) : (date('y') - 1).'-'.date('y');
        $extraNum     = '23.02.2608.212.53.000.';
        $tenderNoFor  = $extraNum.$currentYear.'.'.$maxId; 

        $catGrou      = Auth::user()->categories_id;
        $itemtodemand = \App\ItemToDemand::join($this->tableAlies.'_items', $this->tableAlies.'_items.id', '=', 'item_to_demand.item_id')
                ->join('supplycategories',$this->tableAlies.'_items.item_cat_id','=','supplycategories.id')
                ->select('item_to_demand.*','item_to_demand.unit','supplycategories.name as categoryname')
                ->where('demand_id','=',$id)
                ->where('not_in_stock','>',0)
                ->where('current_status','!=',101)
                ->whereNull('tender_no')
                ->where('item_check_current_status','=',1);
                if(!empty($catGrou)){
                    $catGrou = explode(',',$catGrou);
                    $itemtodemand->whereIn('item_to_demand.group_name',$catGrou);
                }
        $itemtodemand       = $itemtodemand->get();

        $tenderTearmsAndConditions = \App\TermsCondition::where('status','=',1)->get();

        return View::make('floating-tender.create')->with(compact('supplyCategories','nsdNames','id','demandInfo','tenderNoFor','itemtodemand','tenderTearmsAndConditions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        OwnLibrary::validateAccess($this->moduleId,17);

        $this->middleware('csrf', array('on' => 'post'));

        $v = \Validator::make($request->all(), [
            'tender_title' => 'required',
            'tender_number' => 'required|unique:'.\Session::get("zoneAlise").'_tenders,tender_number',
            'tender_opening_date' => 'required',
            'tender_cat_id' => 'required',
            'nsd_id' => 'required',
            'item_to_tender_assing' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('floating-tender/create/'.$request->demandId)->withErrors($v->errors())->withInput();
        }else {
                //Notice pdf upload
                $notice_pdf_upload = TRUE;
                $notice_pdf_name = FALSE;
                if (Input::hasFile('notice')) {
                    $noticeFile = Input::file('notice');
                    $destinationPathNotice = public_path() . '/uploads/tender_spicification_notice_pdf/';
                    $noticeFilename = uniqid() . $noticeFile->getClientOriginalName();
                    $uploadSuccessNotice = Input::file('notice')->move($destinationPathNotice, $noticeFilename);
                    if ($uploadSuccessNotice) {
                        $notice_pdf_name = TRUE;
                    } else {
                        $notice_pdf_upload = FALSE;
                    }
                }
                   
                $tender = new Tender();
                $tender->tender_title = $request->tender_title;
                $tender->tender_number = $request->tender_number;
                //$tender->po_number = empty($request->po_number) ? null : $request->po_number;
                $tender->tender_opening_date = empty($request->tender_opening_date) ? null : date('Y-m-d',strtotime($request->tender_opening_date));
                $tender->tender_description = empty($request->tender_description) ? null : $request->tender_description;
                $tender->tender_cat_id = empty($request->tender_cat_id) ? null : $request->tender_cat_id;
                $tender->nsd_id = empty($request->nsd_id) ? null : $request->nsd_id;
                $tender->imc_number = empty($request->imc_number) ? null : $request->imc_number;
                //$tender->open_tender = empty($request->open_tender) ? null : $request->open_tender;
                // Newly added ====================================
                $tender->date_line = empty($request->tender_opening_date) ? null : date('Y-m-d',strtotime($request->tender_opening_date));
                $tender->approval_letter_number = empty($request->approval_letter_number) ? null : $request->approval_letter_number;
                $tender->approval_letter_date = empty($request->approval_letter_date) ? null : date('Y-m-d',strtotime($request->approval_letter_date));
                $tender->purchase_type = empty($request->purchase_type) ? null : $request->purchase_type;
                $tender->tender_type = empty($request->tender_type) ? null : $request->tender_type;
                $tender->tender_nature = empty($request->tender_nature) ? null : $request->tender_nature;
                $tender->ref_tender_id = empty($request->ref_tender_id) ? null : $request->ref_tender_id;
                $tender->tender_priority = empty($request->tender_priority) ? null : $request->tender_priority;
                $tender->letter_body = empty($request->letter_body) ? null : $request->letter_body;
                $tender->remarks = empty($request->remarks) ? null : $request->remarks;
                $tender->additionl_info = empty($request->additionl_info) ? null : $request->additionl_info;
                $tender->valid_date_from = empty($request->valid_date_from) ? null : $request->valid_date_from;
                $tender->valid_date_to = empty($request->tender_opening_date) ? null : $request->tender_opening_date;
                $tender->extend_date_to = empty($request->extend_date_to) ? null : $request->extend_date_to;
                $tender->reference = empty($request->reference) ? null : $request->reference;

                $tender->invitation_for = empty($request->invitation_for) ? null : $request->invitation_for;
                $tender->date           = empty($request->valid_date_from) ? null : date('Y-m-d',strtotime($request->valid_date_from));
                $tender->development_partners = empty($request->development_partners) ? null : $request->development_partners;
                $tender->proj_prog_code = empty($request->proj_prog_code) ? null : $request->proj_prog_code;
                $tender->tender_package_no = empty($request->tender_package_no) ? null : $request->tender_package_no;
                $tender->tender_package_name = empty($request->tender_package_name) ? null : $request->tender_package_name;
                $tender->pre_tender_meeting = empty($request->pre_tender_meeting) ? null : date('Y-m-d h:i:s',strtotime($request->pre_tender_meeting));;
                $tender->eligibility_of_tender = empty($request->eligibility_of_tender) ? null : $request->eligibility_of_tender;
                $tender->name_of_offi_invit_ten = empty($request->name_of_offi_invit_ten) ? null : $request->name_of_offi_invit_ten;
                $tender->desg_of_offi_invit_ten = empty($request->desg_of_offi_invit_ten) ? null : $request->desg_of_offi_invit_ten;
                $tender->nhq_ltr_no = empty($request->nhq_ltr_no) ? null : $request->nhq_ltr_no;
                $tender->tender_terms_conditions = empty($request->terms_conditions_field) ? null : $request->terms_conditions_field;
                $tender->number_of_lot_item = empty($request->number_of_lot_item) ? null : $request->number_of_lot_item;
                $tender->reference_date = empty($request->reference_date) ? null : date('Y-m-d',strtotime($request->reference_date));
                $tender->delivery_date = empty($request->delivery_date) ? null : $request->delivery_date;
                $tender->location = empty($request->location) ? null : $request->location;

                $tender->status_id = $request->status;

                // Newly added ====================================================
                // ================================================================
                
                    $fileExtension = '';
                    if (!empty($request->specification) && count($request->specification) > 0) {
                        for ($i = 0; count($request->specification) > $i; $i++) {
                            if (!empty($request->specification[$i])) {
                                $file = $request->specification[$i];
                                $destinationPath = public_path() . '/uploads/tender_spicification_notice_pdf/';
                                $fileExtension = $file->getClientOriginalExtension();
                                $specification = uniqid() . $file->getClientOriginalName();
                                $uploadSuccess = $file->move($destinationPath, $specification);
                                
                                if($fileExtension == 'pdf'){
                                    $tender->specification      = $specification;
                                }else{
                                    $tender->specification_doc  = $specification;
                                }
                            }
                        }
                    }
                // End newly added ================================================
                // ================================================================
                
                if ($notice_pdf_name !== FALSE) {
                    $tender->notice = $noticeFilename;
                }

               if ($tender->save()) {

                    // For generating pdf==========================================
                    // ============================================================
                    // $tenderInfoForPdf = Tender::find($tender->id);
                    // $tenderData = [
                    //     'tenderInfoForPdf' => $tenderInfoForPdf,
                    // ];

                    // if(empty($tenderInfoForPdf->notice)){
                    //     $specificationPdfFileName = 'specipication_notice_'.$tender->id.date('y-m-dhis').'.pdf';

                    //     $pdf= PDF::loadView('floating-tender.specipicationpdf',$tenderData,[],['format' => 'A4-L']);
                    //     $pdf->save(public_path() . '/uploads/tender_spicification_notice_pdf/'.$specificationPdfFileName);
                    // }
                    
                    // End generating pdf =========================================
                    // ============================================================

                   $updateTen = Tender::find($tender->id);
                   $updateTen->all_org_tender_id = $tender->id;
                   $updateTen->demand_no         = $request->demandId;
                   //$updateTen->notice               = $specificationPdfFileName;
                   $updateTen->notice            = $updateTen->notice;
                   $updateTen->save();

                   $demandups = \App\Demand::find($request->demandId);
                   $demandups->current_status       = 4;
                   $demandups->tender_floating      = 1;
                   $demandups->tender_floating_by   = Auth::user()->id;
                   $demandups->tender_floating_date = date('Y-m-d H:i:s');
                   $demandups->tender_id            = $tender->id;
                   $demandups->save();

                   $lotUnqMaxId = \App\ItemToDemand::max('lot_unq_id');
                   $lotUnqMaxId = empty($lotUnqMaxId) ? 1 : $lotUnqMaxId+1;
                   $tenderItemQty = 0;
                   $lotValueFromDb = array();

                    if($request->tender_nature==2){
                        $lotuniquenames = array_values(array_unique($request->lot_name));

                        for($m=0; count($lotuniquenames)>$m; $m++){
                            $createLotName = new \App\LotNames();
                            $createLotName->lot_name = $lotuniquenames[$m];
                            $createLotName->demand_id = $request->demandId;
                            $createLotName->tender_id = $tender->id;
                            $createLotName->save();

                            $lotValueFromDb[$createLotName->id] = $createLotName->lot_name;
                        }

                    } 

                   if(count($request->item_to_tender_assing) > 0){
                        for($i=0;count($request->item_to_tender_assing)>$i; $i++){
                            $itemToDemands = ItemToDemand::find($request->item_to_tender_assing[$i]);

                            if($itemToDemands->current_status != 101){
                                $itemToDemands->tender_no            = $tender->id;
                                $itemToDemands->tender_number        = $tender->tender_number;
                                $itemToDemands->tender_floating      = 1;
                                $itemToDemands->tender_floating_by   = Auth::user()->id;
                                $itemToDemands->tender_floating_date = date('Y-m-d H:i:s');

                                if($request->tender_nature==2){
                                    $lotUnqId = array_search($request->lot_name[$i], $lotValueFromDb);
                                    $itemToDemands->lot_name             = $request->lot_name[$i];
                                    $itemToDemands->lot_unq_id           = $lotUnqId;
                                }

                                $itemToDemands->current_status       = 4;
                                $itemToDemands->save();
                                $tenderItemQty += $itemToDemands->unit;
                            }
                            $lotUnqMaxId++;
                        }
                   }

                   $demandToTender = new \App\DemandToTender();

                   $demandToTender->demand_id       = $request->demandId;
                   $demandToTender->tender_id       = $tender->id;
                   $demandToTender->tender_number   = $tender->tender_number;
                   $demandToTender->total_quantity  = $tenderItemQty;
                   $demandToTender->tender_floating      = 1;
                   $demandToTender->tender_floating_by   = Auth::user()->id;
                   $demandToTender->tender_floating_date = date('Y-m-d H:i:s');
                   $demandToTender->current_status  = 4;
                   $demandToTender->save();

                   Session::flash('success', 'Tender Created Successfully');
                    // return Redirect::to('demand');
                    return Redirect::to('floating-tender-acc/1');
                }

            //} 

        }

    }

    public function specificationPdf($id){
        $tender = Tender::find(decrypt($id));
        return response()->file(public_path() . '/uploads/tender_spicification_notice_pdf/' . $tender->specification);
    }

    public function noticePdf($id){
        $tender = Tender::find(decrypt($id));
        return response()->file(public_path() . '/uploads/tender_spicification_notice_pdf/' . $tender->notice);
    }

    public function createCollectionQuotation($id,$tender_id=null){
        
        $demand_id  = $id;
        $demand     = \App\Demand::find($demand_id);
        $tender_id  = $tender_id;
        $tenderInfoForPdf = Tender::find($tender_id);

        /** 
         * 
         *   If line items
         *   
        */
        if($tenderInfoForPdf->tender_nature==1){
            $items = \App\ItemToDemand::where('demand_id','=',$demand_id)->where('tender_no','=',$tender_id)->where('unit','>',0)->get();
        }
        /** 
         * 
         *   If Lot items
         *   
        */
        if($tenderInfoForPdf->tender_nature==2){
            $itemsInfoDesc = ItemToDemand::select('lot_name')->where('tender_no','=',$tender_id)->groupBy('lot_name')
                        ->orderBy('id','asc')
                        ->get();

                        foreach($itemsInfoDesc as $iid){
                            $items[$iid->lot_name] = \App\ItemToDemand::where('demand_id','=',$demand_id)
                                ->where('tender_no','=',$tender_id)
                                ->where('lot_name','=',$iid->lot_name)
                                ->where('unit','>',0)
                                ->get();
                        }            
        }

        $sheCdInfo  = array_map('current',\App\TenderSchedule::select('supplier_id')
            ->where('tender_id','=',$tender_id)
            ->get()->toArray());

        $suppliers = \App\Supplier:: whereIn('id',$sheCdInfo)->get();
        
        return View::make('demands.tender-collection-quotation')->with(compact('nsdNames','denos','tender_id','items','itemAlreadyAssign','tender','currencies_names','default_currency','demand_id','demand','suppliers','tenderInfoForPdf'));
    
    }

    public function postCollectionQuotationInfo(Request $request){

        if(count($request->supplier_name) > 0){

            $indexes = array_keys($request->supplier_name);

            for($i=0; count($indexes)>$i; $i++){

                    $demand_to_coll  = new DemandToCollectionQuotation();

                    $demand_to_coll->demand_id        = $request->demand_id;
                    $demand_to_coll->tender_id        = $request->tender_id;

                    $demand_to_coll->propose_date     = date('Y-m-d',strtotime($request->propose_date[$indexes[$i]][0]));
                    $demand_to_coll->supplier_name    = $request->supplier_name[$indexes[$i]][0];
                    $demand_to_coll->suppliernametext = $request->suppliernametext[$indexes[$i]][0];
                    $demand_to_coll->total            = $request->total[$indexes[$i]][0];
                    $demand_to_coll->total_quantity   = $request->total_quantity[$indexes[$i]][0];

                    if(isset($request->alt_unit_price[$indexes[$i]])){ 
                        $demand_to_coll->alternative_total_quantity  = $request->alt_total_quantity[$indexes[$i]][0];
                        $demand_to_coll->alternative_total = $request->alt_total[$indexes[$i]][0];
                    }

                    $demand_to_coll->save();

                for($j=0; count($request->item_id[$indexes[$i]])>$j; $j++){
                    if(!empty($request->item_id[$indexes[$i]][$j])){

                        $demand_to_collection  = new DemandSuppllierToCollQuotToItem();

                        $demand_to_collection->demand_id       = $request->demand_id;
                        $demand_to_collection->tender_id       = $request->tender_id;
                        $demand_to_collection->dmn_to_cal_qut_id = $demand_to_coll->id;
                        $demand_to_collection->supplier_id     = $demand_to_coll->supplier_name;
                        
                        $itemsExp = explode('&',$request->item_id[$indexes[$i]][$j]);

                        //$demand_to_collection->item_id         = $request->item_id[$indexes[$i]][$j];
                        $demand_to_collection->item_id         = $itemsExp[0];
                        $demand_to_collection->real_item_id    = $itemsExp[1];

                        $demand_to_collection->unit_price      = $request->unit_price[$indexes[$i]][$j];
                        $demand_to_collection->discount_amount = $request->discount_amount[$indexes[$i]][$j];
                        $demand_to_collection->quantity        = $request->quantity[$indexes[$i]][$j];
                        
                        if(!empty($request->unit_price[$indexes[$i]][$j]) && $request->unit_price[$indexes[$i]][$j] >0){
                            $demand_to_collection->quoted_quantity = $request->quoted_quantity[$indexes[$i]][$j];
                        }else{
                            $demand_to_collection->quoted_quantity = 0;
                        }
                        
                        $demand_to_collection->total_price     = $request->total_price[$indexes[$i]][$j];

                        if(isset($request->alt_unit_price[$indexes[$i]])){
                        $demand_to_collection->alternative_unit_price  = $request->alt_unit_price[$indexes[$i]][$j];
                        $demand_to_collection->alternative_discount_amount = $request->alt_discount_amount[$indexes[$i]][$j];
                        $demand_to_collection->alternative_quoted_quantity = $request->alt_quoted_quantity[$indexes[$i]][$j];
                        $demand_to_collection->alternative_total_price     = $request->alt_total_price[$indexes[$i]][$j];
                        }


                        $demand_to_collection->last_unti_price = 0;
                        $demand_to_collection->save();

                        //$request->item_id[$indexes[$i]][$i];
                    }
                }

            }

            // Newly added some functions ===============================
            // ==========================================================
            $lotTabelDatas = \App\LotNames::where('demand_id','=',$request->demand_id)-> where('tender_id','=',$request->tender_id)->get();
            
            if(count($lotTabelDatas)>0){
                foreach($lotTabelDatas as $ltd){
                    $itemsUnderLotIds = array_map('current',\App\ItemToDemand::select('id')
                                        ->where('lot_unq_id','=',$ltd->id)->get()->toArray());

                    $supplierOfThisLot = array_unique(array_map('current',\App\DemandSuppllierToCollQuotToItem::
                                        select('dmn_to_cal_qut_id')
                                        ->whereIn('item_id',$itemsUnderLotIds)->get()->toArray()));


                    foreach ($supplierOfThisLot as $key => $suppOfThisLot) {
                        $lotWiseTotal = DemandSuppllierToCollQuotToItem::
                                            where('dmn_to_cal_qut_id','=',$suppOfThisLot)
                                            ->whereIn('item_id',$itemsUnderLotIds)
                                            ->sum('total_price');
                        $lotWiseTotalQty = DemandSuppllierToCollQuotToItem::
                                            where('dmn_to_cal_qut_id','=',$suppOfThisLot)
                                            ->whereIn('item_id',$itemsUnderLotIds)
                                            ->sum('quoted_quantity');
                        $lotWiseAlterNativeTotal = DemandSuppllierToCollQuotToItem::
                                            where('dmn_to_cal_qut_id','=',$suppOfThisLot)
                                            ->whereIn('item_id',$itemsUnderLotIds)
                                            ->sum('alternative_total_price');
                        $lotWiseAlterNativeTotalQty = DemandSuppllierToCollQuotToItem::
                                            where('dmn_to_cal_qut_id','=',$suppOfThisLot)
                                            ->whereIn('item_id',$itemsUnderLotIds)
                                            ->sum('alternative_quoted_quantity');                                         
                    
                    if($lotWiseTotal != 0){
                        $insertIntoLotToSupp =  new \App\LOtToSupplier();

                        $insertIntoLotToSupp->lot_name_id           = $ltd->id;
                        $insertIntoLotToSupp->mnd_to_col_qtn_id     = $suppOfThisLot;
                        $insertIntoLotToSupp->lot_wise_total_qty    = $lotWiseTotalQty;
                        $insertIntoLotToSupp->lot_wise_total        = $lotWiseTotal;
                        $insertIntoLotToSupp->altr_quoted_total_quantity = $lotWiseAlterNativeTotalQty;
                        $insertIntoLotToSupp->altr_total_price        = $lotWiseAlterNativeTotal;

                        $insertIntoLotToSupp->save();
                    }
                    
                    }
                    
                }
            }
            // End newly added============================
            // ===========================================
           
               $demandups = \App\Demand::find($request->demand_id);
               $demandups->current_status                 = 6;
               $demandups->tender_quation_collection      = 1;
               $demandups->tender_quation_collection_by   = Auth::user()->id;
               $demandups->tender_quation_collection_date = date('Y-m-d H:i:s');
               $demandups->save();

               $demandToTender = \App\DemandToTender::where('demand_id','=',$request->demand_id)->where('tender_id','=',$request->tender_id)->first();
               $demandToTender->current_status  = 6;
               $demandToTender->tender_quation_collection      = 1;
               $demandToTender->tender_quation_collection_by   = Auth::user()->id;
               $demandToTender->tender_quation_collection_date = date('Y-m-d H:i:s');
               $demandToTender->save();

            \DB::table('item_to_demand')
                ->where('demand_id', $request->demand_id)
                ->where('tender_no', $request->tender_id)
                ->update(['tender_quation_collection' => 1, 'tender_quation_collection_by' =>  Auth::user()->id, 'tender_quation_collection_date' => date('Y-m-d H:i:s'), 'current_status' => 6]);

            Session::flash('success', 'Data Updated Successfully');
            // return Redirect::to('demand-details/' . $request->demand_id);
            return Redirect::to('collection-quotation-acc/1');

        }else{
            Session::flash('error', 'Data can not be update');
            // return Redirect::to('demand-details/' . $request->demand_id);
            return Redirect::to('collection-quotation-acc/1');
        }

    }

    public function cstView($id){

        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

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
        // echo "<pre>"; print_r($targetArray); exit;
        
        return View::make('demands.cst-view')->with(compact('itemList','supplierResult','targetArray','supArray','supTotalAmountArray','demandId','supplierAllreadySelected','supWiComArray','demand','tenderId','tender','suppliersInf'));

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
        
        // echo "<pre>"; print_r($mainArray); exit;
        return View::make('lot-cst.cst-view')->with(compact('itemList','supplierResult','targetArray','supArray','supTotalAmountArray','demandId','supplierAllreadySelected','supWiComArray','demand','tenderId','tender','suppliersInf','supplierResultFir','mainArray'));
        } 

        
    }

    public function cstViewPrint($id){

        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

//        $demandId = $id;
        $demandId = $explodes[0];
        $tenderId = $explodes[1];
        $demand     = \App\Demand::find($demandId);
        $tender     = \App\DemandToTender::where('tender_id','=',$tenderId)->first();

        $itemToDemResult = ItemToDemand::where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->get();

        // Newlly added =================
        // ==============================
         $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
        $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('demand_id','!=',$demandId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
        $itemDmnArr = array_unique($itemDmnArr);
        // End newlly added =================
        // ==============================

        $supplierResult  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
        ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
        ->where('demand_to_collection_quotation.demand_id','=',$demandId)
        ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
        ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
        ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
        ->get();
        
        $supArray              =  array();
        $supTotalAmountArray   =  array();
        foreach ($supplierResult as $sr) {
            $supArray[] = $sr->suppliernametext.'<br>'.$sr->head_office_address.'?'.$sr->id;
            $supTotalAmountArray[] = $sr->total;
        }

        // Supplier with comment ===========================
        // =================================================
        $supWiComArray       =  array();
        foreach ($supplierResult as $sr) {
            $supWiComArray[] = $sr->comment_on_cst.'?'.$sr->id;
        }

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
        foreach ($itemToDemResult as $key => $value) {
            
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
                    ->select('demand_supplier_to_coll_qut_to_item.last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                    ->where('demand_supplier_to_coll_qut_to_item.demand_id','!=',$value->demand_id)
                    ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                    ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
                    //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                    ->whereIn('demand_to_collection_quotation.id',$itemDmnArr)
                    //->where('demand_to_collection_quotation.winner','=',1)
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

            foreach ($supplierResult as $sr) {
                $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('demand_id','=',$demandId)
                                                ->where('tender_id','=',$tenderId)
                                                ->where('item_id','=',$value->id)
                                                ->where('dmn_to_cal_qut_id','=',$sr->id)
                                                ->where('supplier_id','=',$sr->supplier_name)
                                                ->get();
                                                
                
            }

                $sls++;
        }
        //echo "<pre>"; print_r($targetArray); exit;
        
        // return View::make('demands.cst-view')->with(compact('itemList','supplierResult','targetArray','supArray','supTotalAmountArray','demandId','supplierAllreadySelected','supWiComArray','demand','tenderId','tender'));
        
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
                    'tenderId' => $tenderId
                ];

        $pdf= PDF::loadView('demands.cst-view-pdf',['format' => [215.9, 342.9], 'orientation' => 'L']);
        return $pdf->stream('cst-view.pdf');
    }

    public function draftCstPost(Request $request){

        $demand_id = $request->demandId;
        $tenderId  = $request->tenderId;

        if($request->tenderNature==2){
            
            $rules = array(
                'cst_draft_sup_id' => 'required'
            );

            $message = array(
                'cst_draft_sup_id.required' => 'Please, select supplier!'
            );

            $validator = Validator::make(Input::all(), $rules, $message);

            if ($validator->fails()) {
                return redirect('cst-view/'.$demand_id.'&'.$tenderId)->withErrors($validator);
            } else {

                if(count($request->cst_draft_sup_id)>0 ){

                    $suppToUpDtArray = array(); // Have to update in DemanToCollectionTable
                    $commentsArray   = array(); // Comment done in this ids in DemandSuppTocollToItem
                    $allComentInSup  = array(); // All comment to update in DemanToCollectionTable

                    \DB::table('demand_supplier_to_coll_qut_to_item')
                        ->where('demand_id', $demand_id)
                        ->where('tender_id', $tenderId)
                        ->update(['select_as_draft_cst' => NULL]);

                        \DB::table('demand_to_collection_quotation')
                        ->where('demand_id', $demand_id)
                        ->where('tender_id', $tenderId)
                        ->update(['sel_as_draft_cst' => NULL, 'comment_on_cst' => NULL]);

                        for($m=0; count($request->cst_draft_sup_id)>$m; $m++){
                            $explodedDatas = explode('&',$request->cst_draft_sup_id[$m]);

                            $demToCollUpdate = DemandToCollectionQuotation::find($explodedDatas[0]);
                            $demToCollUpdate->sel_as_draft_cst = 1;
                            $demToCollUpdate->save();

                            $lotToSupplier = \App\LotToSupplier::find($explodedDatas[2]);
                            $lotToSupplier->lot_select_as_draft_cst    = 1;
                            $lotToSupplier->save();

                            $lotImtesIds = array_map('current',ItemToDemand::where('lot_unq_id','=',$explodedDatas[1])->get()->toArray());

                            $itemsToUpdate = DemandSuppllierToCollQuotToItem::where('dmn_to_cal_qut_id','=',$explodedDatas[0])->whereIn('item_id',$lotImtesIds)->get();
                            
                            foreach ($itemsToUpdate as $key => $itu) {
                                $updateItems = DemandSuppllierToCollQuotToItem::find($itu->id);
                                $updateItems->select_as_draft_cst = 1;
                                $updateItems->save();
                            }
                                                
                        }

                        for($i=0; count($request->suppId)>$i; $i++){

                            $lotToSupp = \App\LotToSupplier::find($request->suppId[$i]);

                            $lotToSupp->comnt_on_cst_ech_lot    = $request->comment[$request->suppId[$i]];
                            $allComentInSup[$lotToSupp->mnd_to_col_qtn_id][]  = $request->comment[$request->suppId[$i]];
                            $lotToSupp->save();
                        }

                        if(count($allComentInSup)>0){
                            $commMerge = '';
                            foreach ($allComentInSup as $sup => $com) {
                                foreach ($com as $c) {
                                    if(!empty($c)){
                                       $commMerge .= $c.'<br>'; 
                                    }
                                   
                                }
                            $demToColComUpdate = DemandToCollectionQuotation::find($sup);
                            $demToColComUpdate->comment_on_cst = $commMerge; 
                            $demToColComUpdate->save();
                            $commMerge = ''; 
                            }
                        }
                    
                    $demandsUp                         = \App\Demand::find($request->demandId);
                    if($request->send_to_nhq==2){

                        $demandsUp->head_ofc_apvl_status  = 2;
                        $demandsUp->head_ofc_apvl_date    = date('Y-m-d H:i:s');
                        $demandsUp->lp_section_status     = 2;

                        \DB::table('item_to_demand')
                        ->where('demand_id', $demand_id)
                        ->where('tender_no', $tenderId)
                        ->update(['cst_draft_status' => 1, 'cst_draft_status_by' =>  Auth::user()->id, 'cst_draft_status_date' => date('Y-m-d H:i:s'), 'current_status' => 8, 'lp_section_status' => 2, 'head_ofc_apvl_status'=>2, 'head_ofc_apvl_date' => date('Y-m-d H:i:s')]);

                        \DB::table('demand_to_tender')
                            ->where('demand_id', $demand_id)
                            ->where('tender_id', $tenderId)
                            ->update(['cst_draft_status' => 1, 'cst_draft_status_by' =>  Auth::user()->id, 'cst_draft_status_date' => date('Y-m-d H:i:s'), 'current_status' => 8, 'lp_section_status' => 2, 'head_ofc_apvl_status'=>2, 'head_ofc_apvl_date' => date('Y-m-d H:i:s'), 'send_to_nhq'=>2]);

                    }else{
                        \DB::table('item_to_demand')
                        ->where('demand_id', $demand_id)
                        ->where('tender_no', $tenderId)
                        ->update(['cst_draft_status' => 1, 'cst_draft_status_by' =>  Auth::user()->id, 'cst_draft_status_date' => date('Y-m-d H:i:s'), 'current_status' => 8]);

                        \DB::table('demand_to_tender')
                            ->where('demand_id', $demand_id)
                            ->where('tender_id', $tenderId)
                            ->update(['cst_draft_status' => 1, 'cst_draft_status_by' =>  Auth::user()->id, 'cst_draft_status_date' => date('Y-m-d H:i:s'), 'current_status' => 8, 'send_to_nhq'=>1]);
                    }
                    $demandsUp->cst_draft_status       = 1;
                    $demandsUp->cst_draft_status_by    = Auth::user()->id;
                    $demandsUp->cst_draft_status_date  = date('Y-m-d H:i:s');
                    $demandsUp->current_status         = 8;
                    $demandsUp->save();

                    

                    Session::flash('success', 'Data Updated Successfully');
                    // return redirect('demand-details/'.$demand_id);
                    return redirect('cst-view-acc/1');

                }

                Session::flash('error', 'Data can not be update');
                // return redirect('cst-view/'.$demand_id);
                return redirect('cst-view-acc/1');

            } // Emd of validation if else

        }else{

            $rules = array(
            'cst_draft_sup_id' => 'required',
            'item_ids' => 'required'
            );

            $message = array(
                'cst_draft_sup_id.required' => 'Please, select supplier!',
                'item_ids.required' => 'Please, select item!',
            );

            $validator = Validator::make(Input::all(), $rules, $message);

            if ($validator->fails()) {
                return redirect('cst-view/'.$demand_id.'&'.$tenderId)->withErrors($validator);
            } else {

                if(count($request->cst_draft_sup_id)>0 && count($request->item_ids) ){

                    $suppToUpDtArray = array(); // Have to update in DemanToCollectionTable
                    $commentsArray   = array(); // Comment done in this ids in DemandSuppTocollToItem
                    $allComentInSup  = array(); // All comment to update in DemanToCollectionTable

                    \DB::table('demand_supplier_to_coll_qut_to_item')
                        ->where('demand_id', $demand_id)
                        ->where('tender_id', $tenderId)
                        ->update(['select_as_draft_cst' => NULL]);

                    for($i=0; count($request->item_ids)>$i; $i++){

                        $explodedDatas = explode('&', $request->item_ids[$i]);

                        $updateDemSupToItem = DemandSuppllierToCollQuotToItem::find($explodedDatas[0]);

                        $updateDemSupToItem->select_as_draft_cst = 1;
                        $updateDemSupToItem->comnt_on_cst_ech_itm = $request->comment[$request->item_ids[$i]];
                        $updateDemSupToItem->save();

                        $suppToUpDtArray[] =  $explodedDatas[1]; 
                        $commentsArray[]   =  $request->item_ids[$i];
                        $allComentInSup[$explodedDatas[1]][]  = $request->comment[$request->item_ids[$i]];
                    }

                    $suppToUpDtArray = array_unique($suppToUpDtArray);// Have to update in DemanToCollectionTable
                    $commentsArray   = array_unique($commentsArray);// Comment done in this ids in DemandSuppTocollToItem
                    $commentsDiff    = array_values(array_diff(array_keys($request->comment),$commentsArray)); // Update rest in DemandSuppTocollToItem

                    if(count($commentsDiff) > 0){
                        for($k=0; count($commentsDiff)>$k; $k++){

                            $explodedDatas2 = explode('&', $commentsDiff[$k]);

                            $updateDemSupToItemCom = DemandSuppllierToCollQuotToItem::find($explodedDatas2[0]);
                            $updateDemSupToItem->select_as_draft_cst = NULL;
                            $updateDemSupToItemCom->comnt_on_cst_ech_itm = $request->comment[$commentsDiff[$k]];
                            $updateDemSupToItemCom->save();
                            $allComentInSup[$explodedDatas2[1]][]  = $request->comment[$commentsDiff[$k]];
                        }
                    }

                    \DB::table('demand_to_collection_quotation')
                        ->where('demand_id', $demand_id)
                        ->where('tender_id', $tenderId)
                        ->update(['sel_as_draft_cst' => NULL]);

                    $suppIds = array_unique ($request->suppId);
                    if(count($suppIds ) > 0){
                        for($m=0; count($suppIds )>$m; $m++){ 

                            $demand_to_coll                 = DemandToCollectionQuotation::find($suppIds[$m]);

                            $commentInSingle = '';
                                foreach ($allComentInSup[$suppIds[$m]] as $value) {
                                    if(!empty($value)){
                                        $commentInSingle .= $value.'<br>';
                                    }
                                }
                            $demand_to_coll->comment_on_cst = $commentInSingle;
                            if(in_array($suppIds[$m], $suppToUpDtArray)){
                            $demand_to_coll->sel_as_draft_cst = 1;
                            }else{
                                $demand_to_coll->sel_as_draft_cst = NULL;
                            }
                    
                            $demand_to_coll->save();
                        }
                    }


                    $demandsUp                         = \App\Demand::find($request->demandId);
                    if($request->send_to_nhq==2){

                        $demandsUp->head_ofc_apvl_status  = 2;
                        $demandsUp->head_ofc_apvl_date    = date('Y-m-d H:i:s');
                        $demandsUp->lp_section_status     = 2;

                        \DB::table('item_to_demand')
                        ->where('demand_id', $demand_id)
                        ->where('tender_no', $tenderId)
                        ->update(['cst_draft_status' => 1, 'cst_draft_status_by' =>  Auth::user()->id, 'cst_draft_status_date' => date('Y-m-d H:i:s'), 'current_status' => 8, 'lp_section_status' => 2, 'head_ofc_apvl_status'=>2, 'head_ofc_apvl_date' => date('Y-m-d H:i:s')]);

                        \DB::table('demand_to_tender')
                            ->where('demand_id', $demand_id)
                            ->where('tender_id', $tenderId)
                            ->update(['cst_draft_status' => 1, 'cst_draft_status_by' =>  Auth::user()->id, 'cst_draft_status_date' => date('Y-m-d H:i:s'), 'current_status' => 8, 'lp_section_status' => 2, 'head_ofc_apvl_status'=>2, 'head_ofc_apvl_date' => date('Y-m-d H:i:s'), 'send_to_nhq'=>2]);

                    }else{
                        \DB::table('item_to_demand')
                        ->where('demand_id', $demand_id)
                        ->where('tender_no', $tenderId)
                        ->update(['cst_draft_status' => 1, 'cst_draft_status_by' =>  Auth::user()->id, 'cst_draft_status_date' => date('Y-m-d H:i:s'), 'current_status' => 8]);

                        \DB::table('demand_to_tender')
                            ->where('demand_id', $demand_id)
                            ->where('tender_id', $tenderId)
                            ->update(['cst_draft_status' => 1, 'cst_draft_status_by' =>  Auth::user()->id, 'cst_draft_status_date' => date('Y-m-d H:i:s'), 'current_status' => 8, 'send_to_nhq'=>1]);
                    }
                    $demandsUp->cst_draft_status       = 1;
                    $demandsUp->cst_draft_status_by    = Auth::user()->id;
                    $demandsUp->cst_draft_status_date  = date('Y-m-d H:i:s');
                    $demandsUp->current_status         = 8;
                    $demandsUp->save();

                    Session::flash('success', 'Data Updated Successfully');
                    // return redirect('demand-details/'.$demand_id);
                    return redirect('cst-view-acc/1');

                }

                Session::flash('error', 'Data can not be update');
                // return redirect('cst-view/'.$demand_id);
                return redirect('cst-view-acc/1');

            } // Emd of validation if else
            
        }// End of first else

        

    }

    public function draftCstView($id){

        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

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
            
            return View::make('demands.cst-draft-view')->with(compact('itemList','supplierResult','targetArray','supArray','supTotalAmountArray','demandId','supplierAllreadySelected','supWiComArray','demand','tenderId','tender','suppliersInf'));
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
            
            return View::make('lot-cst.cst-draft-view')->with(compact('itemList','supplierResult','targetArray','supArray','supTotalAmountArray','demandId','supplierAllreadySelected','supWiComArray','demand','tenderId','tender','suppliersInf','supplierResultFir','mainArray'));

        }// End of tender nature lot

    }

    public function draftCstViewPrint($id){

        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

//        $demandId = $id;
        $demandId = $explodes[0];
        $tenderId = $explodes[1];

        $demand     = \App\Demand::find($demandId);
        $tender     = \App\DemandToTender::where('tender_id','=',$tenderId)->first();

        $selectedAsDraftCstSupplier  = array_map('current',DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
        ->select('demand_to_collection_quotation.id')
        ->where('demand_to_collection_quotation.demand_id','=',$demandId)
        ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
        ->where('demand_to_collection_quotation.sel_as_draft_cst','=',1)
        ->orderBy('demand_to_collection_quotation.total','asc')
        ->get()->toArray());


        $itemToDemResult = ItemToDemand::where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->get();

        // Newlly added =================
        // ==============================
        $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
        $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('demand_id','!=',$demandId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
        $itemDmnArr = array_unique($itemDmnArr);
        // End newlly added =================
        // ==================================

        $supplierResult  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
        ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
        ->where('demand_to_collection_quotation.demand_id','=',$demandId)
        ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
        ->whereIn('demand_to_collection_quotation.id',$selectedAsDraftCstSupplier)
        ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
        ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
        ->get();
        
        $supArray              =  array();
        $supTotalAmountArray   =  array();
        foreach ($supplierResult as $sr) {
            $supArray[] = $sr->suppliernametext.'<br>'.$sr->head_office_address.'?'.$sr->id;
            $supTotalAmountArray[] = $sr->total;
        }

        // for keeping checked ===============================
        // ===================================================
        $supplierAllreadySelected  = array_map('current',DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
        ->select('demand_to_collection_quotation.id')
        ->where('demand_to_collection_quotation.demand_id','=',$demandId)
        ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
        ->where('demand_to_collection_quotation.winner','=',1)
        ->orderBy('demand_to_collection_quotation.total','asc')
        ->get()->toArray());

        // Supplier with comment ===========================
        // =================================================
        $supWiComArray       =  array();
        foreach ($supplierResult as $sr) {
            $supWiComArray[] = $sr->comment_on_cst.'?'.$sr->id;
        }
        
 
        $targetArray  = array();
        $sls          = 0;
        $arIn         = 0;
        foreach ($itemToDemResult as $key => $value) {
            
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
                    ->select('demand_supplier_to_coll_qut_to_item.last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                    ->where('demand_supplier_to_coll_qut_to_item.demand_id','!=',$value->demand_id)
                    ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                    ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
                    //->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                    ->whereIn('demand_to_collection_quotation.id',$itemDmnArr)
                    //->where('demand_to_collection_quotation.winner','=',1)
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

            foreach ($supplierResult as $sr) {
                $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('demand_id','=',$demandId)
                            ->where('item_id','=',$value->id)
                            ->where('dmn_to_cal_qut_id','=',$sr->id)
                            ->where('tender_id','=',$tenderId)
                            ->where('supplier_id','=',$sr->supplier_name)
                            ->get();

            }

                $sls++;
        }
        //echo "<pre>"; print_r($targetArray); exit;
        
        // return View::make('demands.cst-draft-view')->with(compact('itemList','supplierResult','targetArray','supArray','supTotalAmountArray','demandId','supWiComArray','supplierAllreadySelected','demand','tenderId','tender'));

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
                    'tenderId' => $tenderId
                ];

        $pdf= PDF::loadView('demands.cst-draft-view-pdf',$tenderData,[],['format' => [215.9, 342.9], 'orientation' => 'L']);
        return $pdf->stream('cst-draft-view.pdf');

         //$pdf= PDF::loadView('demands.cst-draft-view-pdf',compact('itemList','supplierResult','targetArray','supArray','supTotalAmountArray','demandId','supWiComArray','supplierAllreadySelected','demand','tenderId','tender'),['format' => 'A4-L']);
        //return $pdf->stream('cst-draft-view.pdf');
    }

    public function selectSupplierCstPost(Request $request){

        $this->tableAlies = \Session::get('zoneAlise');

        $demand_id = $request->demandId;
        $tenderId  = $request->tenderId;

        $tender = \App\DemandToTender::where('demand_id','=',$demand_id)->where('tender_id','=',$tenderId)->first();

        if($request->tenderNature==2){

            $rules = array(
                'cst_draft_sup_id' => 'required'
            );

            $message = array(
                'cst_draft_sup_id.required' => 'Please, select supplier!'
            );

            $validator = Validator::make(Input::all(), $rules, $message);

            if ($validator->fails()) {
                return redirect('draft-cst-view/'.$demand_id.'&'.$tenderId)->withErrors($validator);
            } else {

                if(count($request->cst_draft_sup_id)>0 ){

                    $suppToUpDtArray = array(); // Have to update in DemanToCollectionTable
                    $commentsArray   = array(); // Comment done in this ids in DemandSuppTocollToItem
                    $allComentInSup  = array(); // All comment to update in DemanToCollectionTable

                    \DB::table('demand_supplier_to_coll_qut_to_item')
                        ->where('demand_id', $demand_id)
                        ->where('tender_id', $tenderId)
                        ->update(['select_as_winner' => NULL]);

                        \DB::table('demand_to_collection_quotation')
                        ->where('demand_id', $demand_id)
                        ->where('tender_id', $tenderId)
                        ->update(['winner' => NULL]);

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

                        for($i=0; count($request->suppId)>$i; $i++){

                            $lotToSupp = \App\LotToSupplier::find($request->suppId[$i]);

                            $lotToSupp->comnt_on_cst_ech_lot    = $request->comment[$request->suppId[$i]];
                            $allComentInSup[$lotToSupp->mnd_to_col_qtn_id][]  = $request->comment[$request->suppId[$i]];
                            $lotToSupp->save();
                        }

                        if(count($allComentInSup)>0){
                            $commMerge = '';
                            foreach ($allComentInSup as $sup => $com) {
                                foreach ($com as $c) {
                                    if(!empty($c)){
                                       $commMerge .= $c.'<br>'; 
                                    }
                                   
                                }
                            $demToColComUpdate = DemandToCollectionQuotation::find($sup);
                            $demToColComUpdate->comment_on_cst = $commMerge; 
                            $demToColComUpdate->save();
                            $commMerge = ''; 
                            }
                        }
                    
                    $demandsUp = \App\Demand::find($request->demandId);

                    if($tender->send_to_nhq==2){

                        $demandsUp->lp_section_status         = 1;
                        $demandsUp->head_ofc_apvl_status      = 1;
                        $demandsUp->head_ofc_apvl_by          = Auth::user()->id;
                        $demandsUp->head_ofc_apvl_date        = date('Y-m-d H:i:s');
                        $demandsUp->current_status            = 10;

                        \DB::table('item_to_demand')
                            ->where('demand_id', $demand_id)
                            ->where('tender_no', $tenderId)
                            ->update(['head_ofc_apvl_status' => 1, 'head_ofc_apvl_by' =>  Auth::user()->id, 'head_ofc_apvl_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 10]);

                        \DB::table('demand_to_tender')
                            ->where('demand_id', $demand_id)
                            ->where('tender_id', $tenderId)
                            ->update(['head_ofc_apvl_status' => 1, 'head_ofc_apvl_by' =>  Auth::user()->id, 'head_ofc_apvl_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 10]);


                    }else{

                        $demandsUp->cst_supplier_select       = 1;
                        $demandsUp->cst_supplier_select_by    = Auth::user()->id;
                        $demandsUp->cst_supplier_select_date  = date('Y-m-d H:i:s');
                        $demandsUp->lp_section_status         = 1;
                        $demandsUp->current_status            = 9;

                        \DB::table('item_to_demand')
                        ->where('demand_id', $demand_id)
                        ->where('tender_no', $tenderId)
                        ->update(['cst_supplier_select' => 1, 'cst_supplier_select_by' =>  Auth::user()->id, 'cst_supplier_select_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 9]);

                        \DB::table('demand_to_tender')
                        ->where('demand_id', $demand_id)
                        ->where('tender_id', $tenderId)
                        ->update(['cst_supplier_select' => 1, 'cst_supplier_select_by' =>  Auth::user()->id, 'cst_supplier_select_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 9]);
                    }

                    $suppToUpDtArray = array_unique($suppToUpDtArray);// Have to update in 

                    if($demandsUp->save()){

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
                                                        ->get();

                                if(count($itemsUnderThisSupplier)>0){

                                    foreach ($itemsUnderThisSupplier as $val) {
                                        
                                        $itemtotender = new \App\ItemToTender();

                                        $itemtotender->tender_id = $tenderForColne->id;
                                        $itemtotender->item_id   = $val->real_item_id;
                                        $itemtotender->quantity  = $val->quoted_quantity;
                                        $itemtotender->unit_price = $val->unit_price;
                                        $itemtotender->unit_price_in_bdt = $val->unit_price;
                                        $itemtotender->currency_name = 1;
                                        $itemtotender->conversion = 1;
                                        $itemtotender->discount_price = empty($val->discount_amount) ? 0.00 : $val->discount_amount;
                                        $itemtotender->discount_price_in_bdt = $val->discount_amount;
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
                    return redirect('cst-view-acc/1');

                }

                Session::flash('error', 'Data can not be update');
                // return redirect('cst-view/'.$demand_id);
                return redirect('cst-view-acc/1');

            } // Emd of validation if else

        }else{

            $rules = array(
                'cst_draft_sup_id' => 'required',
                'item_ids' => 'required'
            );

            $message = array(
                'cst_draft_sup_id.required' => 'Please, select supplier!',
                'item_ids.required' => 'Please, select item!',
            );

            $validator = Validator::make(Input::all(), $rules, $message);

            if ($validator->fails()) {
                return redirect('draft-cst-view/'.$demand_id.'&'.$tenderId)->withErrors($validator);
            } else {

                if(count($request->cst_draft_sup_id)>0 && count($request->item_ids) ){

                    $suppToUpDtArray = array(); // Have to update in DemanToCollectionTable
                    $commentsArray   = array(); // Comment done in this ids in DemandSuppTocollToItem
                    $allComentInSup  = array(); // All comment to update in DemanToCollectionTable

                    \DB::table('demand_supplier_to_coll_qut_to_item')
                        ->where('demand_id', $demand_id)
                        ->where('tender_id', $tenderId)
                        ->update(['select_as_winner' => NULL]);

                    for($i=0; count($request->item_ids)>$i; $i++){

                        $explodedDatas = explode('&', $request->item_ids[$i]);

                        $updateDemSupToItem = DemandSuppllierToCollQuotToItem::find($explodedDatas[0]);

                        $updateDemSupToItem->select_as_winner = 1;
                        $updateDemSupToItem->comnt_on_cst_ech_itm = $request->comment[$request->item_ids[$i]];
                        $updateDemSupToItem->save();

                        $suppToUpDtArray[] =  $explodedDatas[1]; 
                        $commentsArray[]   =  $request->item_ids[$i];
                        $allComentInSup[$explodedDatas[1]][]  = $request->comment[$request->item_ids[$i]];
                    }

                    $suppToUpDtArray = array_unique($suppToUpDtArray);// Have to update in DemanToCollectionTable
                    $commentsArray   = array_unique($commentsArray);// Comment done in this ids in DemandSuppTocollToItem
                    $commentsDiff    = array_values(array_diff(array_keys($request->comment),$commentsArray)); // Update rest in DemandSuppTocollToItem

                    if(count($commentsDiff) > 0){
                        for($k=0; count($commentsDiff)>$k; $k++){

                            $explodedDatas2 = explode('&', $commentsDiff[$k]);

                            $updateDemSupToItemCom = DemandSuppllierToCollQuotToItem::find($explodedDatas2[0]);
                            $updateDemSupToItem->select_as_winner = NULL;
                            $updateDemSupToItemCom->comnt_on_cst_ech_itm = $request->comment[$commentsDiff[$k]];
                            $updateDemSupToItemCom->save();
                            $allComentInSup[$explodedDatas2[1]][]  = $request->comment[$commentsDiff[$k]];
                        }
                    }

                    \DB::table('demand_to_collection_quotation')
                        ->where('demand_id', $demand_id)
                        ->where('tender_id', $tenderId)
                        ->update(['winner' => NULL]);

                    $suppIds = array_unique ($request->suppId);
                    if(count($suppIds ) > 0){
                        for($m=0; count($suppIds )>$m; $m++){ 

                            $demand_to_coll                 = DemandToCollectionQuotation::find($suppIds[$m]);

                            $commentInSingle = '';
                                foreach ($allComentInSup[$suppIds[$m]] as $value) {
                                    if(!empty($value)){
                                        $commentInSingle .= $value.'<br>';
                                    }
                                }
                            $demand_to_coll->comment_on_cst = $commentInSingle;
                            if(in_array($suppIds[$m], $suppToUpDtArray)){
                            $demand_to_coll->winner = 1;
                            }else{
                                $demand_to_coll->winner = NULL;
                            }
                    
                            $demand_to_coll->save();
                        }
                    }

                    $demandsUp = \App\Demand::find($request->demandId);

                    if($tender->send_to_nhq==2){

                        $demandsUp->lp_section_status         = 1;
                        $demandsUp->head_ofc_apvl_status      = 1;
                        $demandsUp->head_ofc_apvl_by          = Auth::user()->id;
                        $demandsUp->head_ofc_apvl_date        = date('Y-m-d H:i:s');
                        $demandsUp->current_status            = 10;

                        \DB::table('item_to_demand')
                            ->where('demand_id', $demand_id)
                            ->where('tender_no', $tenderId)
                            ->update(['head_ofc_apvl_status' => 1, 'head_ofc_apvl_by' =>  Auth::user()->id, 'head_ofc_apvl_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 10]);

                        \DB::table('demand_to_tender')
                            ->where('demand_id', $demand_id)
                            ->where('tender_id', $tenderId)
                            ->update(['head_ofc_apvl_status' => 1, 'head_ofc_apvl_by' =>  Auth::user()->id, 'head_ofc_apvl_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 10]);


                    }else{

                        $demandsUp->cst_supplier_select       = 1;
                        $demandsUp->cst_supplier_select_by    = Auth::user()->id;
                        $demandsUp->cst_supplier_select_date  = date('Y-m-d H:i:s');
                        $demandsUp->lp_section_status         = 1;
                        $demandsUp->current_status            = 9;

                        \DB::table('item_to_demand')
                        ->where('demand_id', $demand_id)
                        ->where('tender_no', $tenderId)
                        ->update(['cst_supplier_select' => 1, 'cst_supplier_select_by' =>  Auth::user()->id, 'cst_supplier_select_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 9]);

                        \DB::table('demand_to_tender')
                        ->where('demand_id', $demand_id)
                        ->where('tender_id', $tenderId)
                        ->update(['cst_supplier_select' => 1, 'cst_supplier_select_by' =>  Auth::user()->id, 'cst_supplier_select_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 9]);
                    }

                    if($demandsUp->save()){

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
                                                        ->get();

                                if(count($itemsUnderThisSupplier)>0){

                                    foreach ($itemsUnderThisSupplier as $val) {
                                        
                                        $itemtotender = new \App\ItemToTender();

                                        $itemtotender->tender_id = $tenderForColne->id;
                                        $itemtotender->item_id   = $val->real_item_id;
                                        $itemtotender->quantity  = $val->quoted_quantity;
                                        $itemtotender->unit_price = $val->unit_price;
                                        $itemtotender->unit_price_in_bdt = $val->unit_price;
                                        $itemtotender->currency_name = 1;
                                        $itemtotender->conversion = 1;
                                        $itemtotender->discount_price = empty($val->discount_amount) ? 0.00 : $val->discount_amount;
                                        $itemtotender->discount_price_in_bdt = $val->discount_amount;
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
                    return redirect('draft-cst-view-acc/1');

                }

                Session::flash('error', 'Data can not be update');
                // return redirect('draft-cst-view/'.$demand_id);
                return redirect('draft-cst-view-acc/1');

            }
        }

    }

    public function createCollectionQuotationItemInfo(Request $request){
        $itemId     = $request->itemId;
        $itemOldId  = $request->itemOldId;

        $itemInfo = \App\ItemToDemand::find($itemId);

        $oldItemInfo = \App\ItemToTender::where('item_id','=',$itemOldId)->orderBy('id', 'desc')->first();
        if(empty($oldItemInfo)){
            $oldItemInfo = \App\Item::where('id','=',$itemOldId)->orderBy('id', 'desc')->first();
        }
        
        $data['unit_price']         = $itemInfo->unit_price;
        $data['unit']               = $itemInfo->unit;
        $data['last_unti_price']    = $oldItemInfo->unit_price;
        // return $itemInfo->unit_price;
        return $data;
    }

    public function headquarteApproval($data=null){

        $explodes = explode('&',$data);

        //$demandId = $id;
        $demandsUp = $explodes[0];
        $tenderId  = $explodes[1];

        $demandsUp = \App\Demand::find($demandsUp);

        return View::make('floating-tender.headqtr-approval-view')->with(compact('demandsUp','tenderId'));
        
    }

    public function postHeadquarteApproval(Request $request){

        $demandsUp = \App\Demand::find($request->demandId);
        $demand_id = $request->demandId;
        $tenderId  = $request->tenderId;

        $v = \Validator::make($request->all(), [
            'headqurt_approval' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('headquarte-approval/'.$request->demandId)->withErrors($v->errors())->withInput();
        }else {
        
            if($request->headqurt_approval==1){
                $demandsUp->lp_section_status         = 1;
                $demandsUp->head_ofc_apvl_status      = 1;
                $demandsUp->head_ofc_apvl_by          = Auth::user()->id;
                $demandsUp->head_ofc_apvl_date        = date('Y-m-d H:i:s');
                $demandsUp->current_status            = 10;

                \DB::table('item_to_demand')
                    ->where('demand_id', $demand_id)
                    ->where('tender_no', $tenderId)
                    ->update(['head_ofc_apvl_status' => 1, 'head_ofc_apvl_by' =>  Auth::user()->id, 'head_ofc_apvl_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 10]);

                \DB::table('demand_to_tender')
                    ->where('demand_id', $demand_id)
                    ->where('tender_id', $tenderId)
                    ->update(['head_ofc_apvl_status' => 1, 'head_ofc_apvl_by' =>  Auth::user()->id, 'head_ofc_apvl_date' => date('Y-m-d H:i:s'), 'lp_section_status' => 1, 'current_status' => 10]);


            }else{
                $demandsUp->head_ofc_apvl_status      = 3;
                $demandsUp->head_ofc_apvl_by          = Auth::user()->id;
                $demandsUp->head_ofc_apvl_date        = date('Y-m-d H:i:s');
                $demandsUp->current_status            = 10;

                \DB::table('item_to_demand')
                    ->where('demand_id', $demand_id)
                    ->where('tender_no', $tenderId)
                    ->update(['head_ofc_apvl_status' => 3, 'head_ofc_apvl_by' =>  Auth::user()->id, 'head_ofc_apvl_date' => date('Y-m-d H:i:s'), 'current_status' => 10]);

                \DB::table('demand_to_tender')
                    ->where('demand_id', $demand_id)
                    ->where('tender_id', $tenderId)
                    ->update(['head_ofc_apvl_status' => 3, 'head_ofc_apvl_by' =>  Auth::user()->id, 'head_ofc_apvl_date' => date('Y-m-d H:i:s'), 'current_status' => 10]);

            }
            
            if($demandsUp->save()){
                Session::flash('success', 'Data Updated Successfully');
                // return redirect('demand-details/'.$demandsUp->id);
                return redirect('hdq-approval-acc/1');
            }
        }

    }

    public function floatingTenderTermsConVal(Request $request){
        $result = \App\TermsCondition::find($request->id);
        echo $result->descriptions; 
    }

    public function draftCstViewExcel($id){
        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

//        $demandId = $id;
        $demandId = $explodes[0];
        $tenderId = $explodes[1];

        $demand     = \App\Demand::find($demandId);
        $tender     = \App\DemandToTender::where('tender_id','=',$tenderId)->first();

        $selectedAsDraftCstSupplier  = array_map('current',DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                                      ->select('demand_to_collection_quotation.id')
                                      ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                                      ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                                      ->where('demand_to_collection_quotation.sel_as_draft_cst','=',1)
                                      ->orderBy('demand_to_collection_quotation.total','asc')
                                      ->get()->toArray());


        $itemToDemResult = ItemToDemand::where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->get();

        // Newlly added =================
        // ==============================
        $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
        $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('demand_id','!=',$demandId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
        $itemDmnArr = array_unique($itemDmnArr);
        // End newlly added =================
        // ==================================

        $supplierResult  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                                  ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
                                  ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                                  ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                                  ->whereIn('demand_to_collection_quotation.id',$selectedAsDraftCstSupplier)
                                  ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
                                  ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
                                  ->get();

        $supArray              =  array();
        $supTotalAmountArray   =  array();
        foreach ($supplierResult as $sr) {
            $supArray[] = $sr->suppliernametext.'<br>'.$sr->head_office_address.'?'.$sr->id;
            $supTotalAmountArray[] = $sr->total;
        }

        // for keeping checked ===============================
        // ===================================================
        $supplierAllreadySelected  = array_map('current',DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                                    ->select('demand_to_collection_quotation.id')
                                    ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                                    ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                                    ->where('demand_to_collection_quotation.winner','=',1)
                                    ->orderBy('demand_to_collection_quotation.total','asc')
                                    ->get()->toArray());

        // Supplier with comment ===========================
        // =================================================
        $supWiComArray       =  array();
        foreach ($supplierResult as $sr) {
            $supWiComArray[] = $sr->comment_on_cst.'?'.$sr->id;
        }


        $targetArray  = array();
        $sls          = 0;
        $arIn         = 0;
        foreach ($itemToDemResult as $key => $value) {

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
                                      ->select('demand_supplier_to_coll_qut_to_item.last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                                      ->where('demand_supplier_to_coll_qut_to_item.demand_id','!=',$value->demand_id)
                                      ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                                      ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
//->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                                      ->whereIn('demand_to_collection_quotation.id',$itemDmnArr)
//->where('demand_to_collection_quotation.winner','=',1)
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

            foreach ($supplierResult as $sr) {
                $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('demand_id','=',$demandId)
                                              ->where('item_id','=',$value->id)
                                              ->where('dmn_to_cal_qut_id','=',$sr->id)
                                              ->where('tender_id','=',$tenderId)
                                              ->where('supplier_id','=',$sr->supplier_name)
                                              ->get();

            }

            $sls++;
        }
        //echo "<pre>"; print_r($targetArray); exit;

        // return View::make('demands.cst-draft-view')->with(compact('itemList','supplierResult','targetArray','supArray','supTotalAmountArray','demandId','supWiComArray','supplierAllreadySelected','demand','tenderId','tender'));

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
            'tenderId' => $tenderId
        ];

//      $pdf= PDF::loadView('demands.cst-draft-view-pdf',$tenderData,[],['format' => 'A4-L']);
//      return $pdf->stream('cst-draft-view.pdf');

        Excel::create('Draft Cst View'.$id, function($excel) use ($supplierResult, $targetArray, $supArray, $supTotalAmountArray, $demandId, $supWiComArray, $supplierAllreadySelected, $demand, $tenderId, $tender) {

            $excel->sheet('Excel sheet', function($sheet) use ($supplierResult, $targetArray, $supArray, $supTotalAmountArray, $demandId, $supWiComArray, $supplierAllreadySelected, $demand, $tenderId, $tender) {
                $sheet->loadView('demands.cst-draft-view-excel')->with(compact('supplierResult','targetArray','supArray','supTotalAmountArray','demandId','supWiComArray','supplierAllreadySelected','demand','tenderId','tender'));
                $sheet->setOrientation('landscape');
            });

        })->export('xlsx');
    }

    public function cstViewExcel($id){
        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

//        $demandId = $id;
        $demandId = $explodes[0];
        $tenderId = $explodes[1];
        $demand     = \App\Demand::find($demandId);
        $tender     = \App\DemandToTender::where('tender_id','=',$tenderId)->first();

        $itemToDemResult = ItemToDemand::where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->get();

        // Newlly added =================
        // ==============================
        $itemIdArray = array_map('current',ItemToDemand::select('item_id')->where('demand_id','=',$demandId)->where('tender_no','=',$tenderId)->orderBy('id','asc')->get()->toArray());
        $itemDmnArr = array_map('current',DemandSuppllierToCollQuotToItem::select('dmn_to_cal_qut_id')->where('demand_id','!=',$demandId)->where('tender_id','!=',$tenderId)->whereIn('real_item_id',$itemIdArray)->orderBy('item_id','asc')->get()->toArray());
        $itemDmnArr = array_unique($itemDmnArr);
        // End newlly added =================
        // ==============================

        $supplierResult  = DemandToCollectionQuotation::join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
                                          ->select('demand_to_collection_quotation.*',$this->tableAlies.'_suppliers.head_office_address')
                                          ->where('demand_to_collection_quotation.demand_id','=',$demandId)
                                          ->where('demand_to_collection_quotation.tender_id','=',$tenderId)
                                          ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total_quantity, DECIMAL)"),'desc')
                                          ->orderBy(\DB::raw("CONVERT(demand_to_collection_quotation.total, DECIMAL)"),'asc')
                                          ->get();

        $supArray              =  array();
        $supTotalAmountArray   =  array();
        foreach ($supplierResult as $sr) {
            $supArray[] = $sr->suppliernametext.'<br>'.$sr->head_office_address.'?'.$sr->id;
            $supTotalAmountArray[] = $sr->total;
        }

        // Supplier with comment ===========================
        // =================================================
        $supWiComArray       =  array();
        foreach ($supplierResult as $sr) {
            $supWiComArray[] = $sr->comment_on_cst.'?'.$sr->id;
        }

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
        foreach ($itemToDemResult as $key => $value) {

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
                                      ->select('demand_supplier_to_coll_qut_to_item.last_unti_price','demand_to_collection_quotation.suppliernametext','demand_to_collection_quotation.updated_at')
                                      ->where('demand_supplier_to_coll_qut_to_item.demand_id','!=',$value->demand_id)
                                      ->where('demand_supplier_to_coll_qut_to_item.tender_id','!=',$value->tender_id)
                                      ->where('demand_supplier_to_coll_qut_to_item.real_item_id','=',$value->item_id)
//->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$itemDmnArr[$arIn])
                                      ->whereIn('demand_to_collection_quotation.id',$itemDmnArr)
//->where('demand_to_collection_quotation.winner','=',1)
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

            foreach ($supplierResult as $sr) {
                $targetArray[$sls]['supi'][] = DemandSuppllierToCollQuotToItem::where('demand_id','=',$demandId)
                                              ->where('tender_id','=',$tenderId)
                                              ->where('item_id','=',$value->id)
                                              ->where('dmn_to_cal_qut_id','=',$sr->id)
                                              ->where('supplier_id','=',$sr->supplier_name)
                                              ->get();


            }

            $sls++;
        }
        //echo "<pre>"; print_r($targetArray); exit;

        // return View::make('demands.cst-view')->with(compact('itemList','supplierResult','targetArray','supArray','supTotalAmountArray','demandId','supplierAllreadySelected','supWiComArray','demand','tenderId','tender'));

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
            'tenderId' => $tenderId
        ];

//      $pdf= PDF::loadView('demands.cst-view-pdf',$tenderData,[],['format' => 'A4-L']);

        Excel::create('CST View Excel'.$id, function($excel) use ($supplierResult, $targetArray, $supArray, $supTotalAmountArray, $demandId, $supWiComArray, $supplierAllreadySelected, $demand, $tenderId, $tender) {

            $excel->sheet('Excel sheet', function($sheet) use ($supplierResult, $targetArray, $supArray, $supTotalAmountArray, $demandId, $supWiComArray, $supplierAllreadySelected, $demand, $tenderId, $tender) {
                $sheet->loadView('demands.cst-view-excel')->with(compact('supplierResult','targetArray','supArray','supTotalAmountArray','demandId','supWiComArray','supplierAllreadySelected','demand','tenderId','tender'));
                $sheet->setOrientation('landscape');
            });

        })->export('xlsx');
    }

    public function getPrientView($tenderId){

        $tenderInfoForPdf = Tender::find($tenderId);
        $demandToTenderInfo = \App\DemandToTender::where('tender_id','=',$tenderId)->first();

        $appUserInfo            = '';
        $organizationName       = '';
        if(!empty($demandToTenderInfo->float_tender_app_by)){
            $appUserInfo      = \App\User::find($demandToTenderInfo->float_tender_app_by);
            $organizationName = \App\NsdName::where('id','=',$appUserInfo->nsd_bsd)->value('name');
        }
        
        $itemsInfoDesc = '';
        $lotItemArray  = array();
        if($tenderInfoForPdf->tender_nature==1){
            $itemsInfoDesc = ItemToDemand::join('deno', 'item_to_demand.deno_id', '=', 'deno.id')
                ->join(\Session::get('zoneAlise').'_tenders','item_to_demand.tender_no','=',\Session::get('zoneAlise').'_tenders.id')
                ->join(\Session::get('zoneAlise').'_items','item_to_demand.item_id','=',\Session::get('zoneAlise').'_items.id')
                ->join('supplycategories','supplycategories.id','=',\Session::get('zoneAlise').'_items.item_cat_id')
                ->join('demands', 'item_to_demand.demand_id', '=', 'demands.id')
                ->leftJoin('nsdname', 'nsdname.id', '=', 'demands.place_to_send')
                ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                ->select('item_to_demand.item_name as item_to_demand_item_name','item_to_demand.unit as item_to_demand_unit','deno.name as deno_deno_name','demande_name.name as demande_name',\Session::get('zoneAlise').'_tenders.remarks as tender_remarks',\Session::get('zoneAlise').'_tenders.delivery_date as tender_delivery_date',\Session::get('zoneAlise').'_tenders.location as location',\Session::get('zoneAlise').'_items.*','supplycategories.name as supplycategories_name')
                ->where('item_to_demand.tender_no','=',$tenderId)
                ->get();
        }
        if($tenderInfoForPdf->tender_nature==2){
            $itemsInfoDesc = ItemToDemand::select('lot_name')->where('tender_no','=',$tenderId)->groupBy('lot_name')
                ->orderBy('id','asc')
                ->get();
            
                foreach($itemsInfoDesc as $iid){
                    $lotItemArray[$iid->lot_name] = ItemToDemand::join('deno', 'item_to_demand.deno_id', '=', 'deno.id')
                ->join(\Session::get('zoneAlise').'_tenders','item_to_demand.tender_no','=',\Session::get('zoneAlise').'_tenders.id')
                ->join(\Session::get('zoneAlise').'_items','item_to_demand.item_id','=',\Session::get('zoneAlise').'_items.id')
                ->join('supplycategories','supplycategories.id','=',\Session::get('zoneAlise').'_items.item_cat_id')
                ->join('demands', 'item_to_demand.demand_id', '=', 'demands.id')
                //->join('demands', 'item_to_demand.demand_id', '=', 'demands.id')
                ->leftJoin('nsdname', 'nsdname.id', '=', 'demands.place_to_send')
                ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                ->select('item_to_demand.item_name as item_to_demand_item_name','item_to_demand.unit as item_to_demand_unit','deno.name as deno_deno_name','demande_name.name as demande_name',\Session::get('zoneAlise').'_tenders.remarks as tender_remarks',\Session::get('zoneAlise').'_tenders.delivery_date as tender_delivery_date',\Session::get('zoneAlise').'_tenders.location as location',\Session::get('zoneAlise').'_items.*','supplycategories.name as supplycategories_name')
                ->where('item_to_demand.tender_no','=',$tenderId)
                ->where('item_to_demand.lot_name','=',$iid->lot_name)
                ->get();
                }

        }

        $tenderData = [
            'tenderInfoForPdf' => $tenderInfoForPdf,
            'itemsInfoDesc' => $itemsInfoDesc,
            'lotItemArray' => $lotItemArray,
            'appUserInfo' => $appUserInfo,
            'organizationName' => $organizationName
        ];

        $specificationPdfFileName = '';
        
            $specificationPdfFileName = 'specipication_notice_'.$tenderId.date('y-m-dhis').'.pdf';

            $pdf= PDF::loadView('floating-tender.specipicationpdf',$tenderData,[],['format' => [215.9, 342.9]]);
            return $pdf->stream('ift.pdf');
            //$pdf->save(public_path() . '/uploads/tender_spicification_notice_pdf/'.$specificationPdfFileName);
            //$tenderInfoForPdf->notice = $specificationPdfFileName;
            //$tenderInfoForPdf->save();

    }


}
