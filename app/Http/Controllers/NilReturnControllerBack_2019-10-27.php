<?php

namespace App\Http\Controllers;

use App\NilReturn;
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

class NilReturnController extends Controller
{

    private $tableAlies;
    private $moduleId = 39;

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create($id){
	    OwnLibrary::validateAccess($this->moduleId, 2);
	    $this->tableAlies = \Session::get('zoneAlise');

	    $explodes = explode('&',$id);

	    $demand_to_lpr_id   = $explodes[0];
	    $tenderId           = $explodes[1];
	    $nilId           = $explodes[2];
	    return view('nil-return.create')->with('nilId',$nilId);
    }

	public function store(Request $request){
		OwnLibrary::validateAccess($this->moduleId, 2);
		$nilReturn = \App\NilReturn::find($request->nil_id);

		if ($nilReturn)
		{
			$nilReturn->nil_date = $request->nil_date;
			$nilReturn->nil_number = $request->nil_number;
			$nilReturn->distribution = $request->distribution;
			$nilReturn->ext = $request->ext;
			$nilReturn->action = $request->info;
			$nilReturn->info = $request->info;
			$nilReturn->status = 2;

			if ($nilReturn->save())
			{
				session()->flash('success','Data is saved');
				return redirect('nil-return/1');
			}
			else
				{
					session()->flash('error','Data is not saved');
					return redirect()->back()->withInput();
				}
		}
		else
		{
			session()->flash('error','Something Went wrong!!!. Please Try again');
			return redirect()->back()->withInput();
		}
    }

    public function approved($id)
    {
		$nilReturn = \App\NilReturn::find($id);

	    $nilReturn->status = 3;
	    $nilReturn->approved_by = Auth::id();

		if ($nilReturn->save())
		{
			Session::flash('success', 'Data approved Successfully');
			return Redirect::to('nil-return/2');
		}
		else
		{
			Session::flash('error', 'Data not approved');
			return Redirect::to('nil-return/2');
		}
    }

    public function createTender($id)
    {
	    OwnLibrary::validateAccess($this->moduleId, 2);
        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

        $retednderId        = \App\NilReturn::where('lpr_id','=',$explodes[0])->value('id');
        $retednderInfo      = \App\NilReturn::find($retednderId);

        $demand_to_lpr_id   = $explodes[0];
        $tenderId           = $explodes[1];

        $demandToLprInfo = \App\DemandToLpr::find($demand_to_lpr_id);
        $tenderInfo      = \App\Tender::find($tenderId);

        $tenderNumber           = $tenderInfo->tender_number;
        $countRetender          = \App\NilReturn::where('tender_number', 'LIKE', "%$tenderNumber%")->count();
        $expTenNumber           = explode('.', $tenderNumber);

        $couReTen = 0;
        if(isset($expTenNumber[8])){
            $couReTen = 1+$expTenNumber[8];
            $tenderNumber     = $expTenNumber[0].$expTenNumber[1].$expTenNumber[2].$expTenNumber[3].$expTenNumber[4].$expTenNumber[5].$expTenNumber[6].$expTenNumber[7];
        }else{
            $couReTen = 1;
        }
        $tenderNoFor  = $tenderNumber.'.'.$couReTen;

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

        $itemtodemand = \App\ItemToDemand::join($this->tableAlies.'_items', $this->tableAlies.'_items.id', '=', 'item_to_demand.item_id')
                ->join('supplycategories',$this->tableAlies.'_items.item_cat_id','=','supplycategories.id')
                ->select('item_to_demand.*','item_to_demand.unit','supplycategories.name as categoryname')
                ->where('lpr_id','=',$demand_to_lpr_id)
                ->where('tender_no','=',$tenderId)
                ->where('nhq_app_status','=',4);
                
        $itemtodemand       = $itemtodemand->get();

        $tenderTearmsAndConditions = \App\TermsCondition::where('status','=',1)->get();

        return View::make('nil-return.createTender')->with(compact('supplyCategories','nsdNames','id','demandToLprInfo','tenderNoFor','itemtodemand','tenderTearmsAndConditions','tenderInfo','demand_to_lpr_id','tenderId','retednderId','retednderInfo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function nilReturnCreateTenderPost(Request $request)
    {
	    OwnLibrary::validateAccess($this->moduleId, 2);

        $demand_to_lpr_id = $request->demandToLprId;
        $tenderId = $request->tenderId;
        $this->middleware('csrf', array('on' => 'post'));

            if(count($request->item_to_tender_assing)>0){

                $retednderInfo      = \App\NilReturn::find($request->retednderId);
                // Inset in to demand_to_lpr table
                $maxIdOfDemandToLpr = \App\DemandToLpr::max('id')+1;
                $demandtolpr        = new \App\DemandToLpr();

                $demandtolpr->lpr_uniq_id_for_demand     = $maxIdOfDemandToLpr;

                $demandtolpr->total_unit                    = $retednderInfo->nil_return_qty;
                $demandtolpr->total_value                   = 0;
                $demandtolpr->demand_entry_by               = Auth::user()->id;
                $demandtolpr->demand_entry_date             = date('Y-m-d H:i:s');
                $demandtolpr->current_status                = 3;
                $demandtolpr->demand_type                   = 2;
                $demandtolpr->group_status                  = 2;
                $demandtolpr->group_status_check_by         = Auth::user()->id;
                $demandtolpr->group_status_check_date       = date('Y-m-d H:i:s');
                $demandtolpr->plr_status                    = 3;  

                $demandtolpr->demand_appv_status   = 1;
                $demandtolpr->approved_by          = Auth::user()->id;
                $demandtolpr->approved_date        = date('Y-m-d H:i:s');

                $demandtolpr->current_status       = 4;
                $demandtolpr->tender_floating      = 1;
                $demandtolpr->tender_floating_by   = Auth::user()->id;
                $demandtolpr->tender_floating_date = date('Y-m-d H:i:s');
                //$demandtolpr->tender_id            = $tender->id;
                $demandtolpr->save();  


                    // Tender create ======================================
                    // ====================================================
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
                    $tender->lpr_id = $demandtolpr->id;
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
                        // $specificationPdfFileName = 'specipication_notice_'.$tender->id.date('y-m-dhis').'.pdf';
                        // $pdf= PDF::loadView('floating-tender.specipicationpdf',$tenderData,[],['format' => 'A4-L']);
                        // $pdf->save(public_path() . '/uploads/tender_spicification_notice_pdf/'.$specificationPdfFileName);
                        // End generating pdf =========================================
                        // ============================================================
                        
                        $updateTen = Tender::find($tender->id);
                        $updateTen->all_org_tender_id = $tender->id;
                        $updateTen->demand_no         = NULL;
                        // $updateTen->notice            = $specificationPdfFileName;
                        $updateTen->notice            = $updateTen->notice;
                        $updateTen->save();

                        // $demandups = \App\Demand::find($demands->id);

                        // $demandups->demand_appv_status   = 1;
                        // $demandups->approved_by          = Auth::user()->id;
                        // $demandups->approved_date        = date('Y-m-d H:i:s');

                        // $demandups->current_status       = 4;
                        // $demandups->tender_floating      = 1;
                        // $demandups->tender_floating_by   = Auth::user()->id;
                        // $demandups->tender_floating_date = date('Y-m-d H:i:s');
                        // $demandups->tender_id            = $tender->id;
                        // $demandups->save();

                        $demandToTender = new \App\DemandToTender();

                        $demandToTender->demand_id       = NULL;
                        $demandToTender->lpr_id          = $demandtolpr->id;
                        $demandToTender->tender_id       = $tender->id;
                        $demandToTender->tender_number   = $tender->tender_number;
                        $demandToTender->total_quantity  = array_sum($request->unit);
                        $demandToTender->tender_floating      = 1;
                        $demandToTender->tender_floating_by   = Auth::user()->id;
                        $demandToTender->tender_floating_date = date('Y-m-d H:i:s');
                        $demandToTender->current_status  = 4;
                        $demandToTender->save();
                        
                    }

                    // End tender create ===========================================
                    // =========================================================
                    $calCulationOfUnit = 0;
                    $lotUnqMaxId = \App\ItemToDemand::max('lot_unq_id');
                    $lotUnqMaxId = empty($lotUnqMaxId) ? 1 : $lotUnqMaxId+1;
                    $lotValueFromDb = array();

                    if($request->tender_nature==2){
                        $lotuniquenames = array_values(array_unique($request->lot_name));

                        for($m=0; count($lotuniquenames)>$m; $m++){
                            $createLotName = new \App\LotNames();
                            $createLotName->lot_name = $lotuniquenames[$m];
                            $createLotName->demand_id = NULL;
                            $createLotName->lpr_id = $demandtolpr->id;
                            $createLotName->tender_id = $tender->id;
                            $createLotName->save();

                            $lotValueFromDb[$createLotName->id] = $createLotName->lot_name;
                        }

                    } 

                    for($i=0; count($request->item_to_tender_assing)>$i; $i++){

                        $itemToDemandItemInfo       = ItemToDemand::find($request->item_to_tender_assing[$i]);
                        $itemToDemands              = new ItemToDemand();

                        $itemToDemands->demand_id       = NULL;
                        $itemToDemands->lpr_id          = $demandtolpr->id;
                        $itemToDemands->item_id         = $itemToDemandItemInfo['item_id'];
                        $itemToDemands->item_name       = $itemToDemandItemInfo['item_name'];
                        $itemToDemands->item_model      = $itemToDemandItemInfo['item_model'];
                        $itemToDemands->serial_imc_no   = $itemToDemandItemInfo['serial_imc_no'];
                        $itemToDemands->group_name      = $itemToDemandItemInfo['group_name'];
                        $itemToDemands->deno_id         = $itemToDemandItemInfo['deno_id'];
                        $itemToDemands->currency_rate   = $itemToDemandItemInfo['currency_rate'];
                        $itemToDemands->unit            = $itemToDemandItemInfo['nhq_app_qty'];
                        $itemToDemands->total_unit      = $itemToDemandItemInfo['nhq_app_qty'];
                        $itemToDemands->unit_price      = $itemToDemandItemInfo['unit_price'];
                        $itemToDemands->current_status  = 1;
                        $itemToDemands->sub_total       = $itemToDemandItemInfo['nhq_app_qty']*$itemToDemandItemInfo['unit_price'];
                        $itemToDemands->in_stock        = $itemToDemandItemInfo['in_stock'];
                        $itemToDemands->not_in_stock    = $itemToDemandItemInfo['not_in_stock'];
                        //$itemToDemands->unit            = $request->unit[$i];
                        $calCulationOfUnit += $itemToDemandItemInfo['nhq_app_qty'];
                        if($request->tender_nature==2){
                            $lotUnqId = array_search($request->lot_name[$i], $lotValueFromDb);
                            $itemToDemands->lot_name    = $request->lot_name[$i];
                            $itemToDemands->lot_unq_id  = $lotUnqId;
                        }
                        // $itemToDemands->lot_name        = $request->lot_name[$i];
                        // $itemToDemands->lot_unq_id      = $lotUnqMaxId;

                        $itemToDemands->demand_appv_status  = 1;
                        $itemToDemands->approved_by         = Auth::user()->id;
                        $itemToDemands->approved_date       = date('Y-m-d H:i:s');

                        $itemToDemands->current_status      = 2;

                        $itemToDemands->group_status                = 2;
                        $itemToDemands->group_status_check_by       = Auth::user()->id;
                        $itemToDemands->group_status_check_date     = date('Y-m-d H:i:s');

                        $itemToDemands->tender_no= $tender->id;
                        $itemToDemands->tender_number        = $tender->tender_number;
                        $itemToDemands->tender_floating      = 1;
                        $itemToDemands->tender_floating_by   = Auth::user()->id;
                        $itemToDemands->tender_floating_date = date('Y-m-d H:i:s');
                        $itemToDemands->current_status       = 4;

                        $itemToDemands->save();

                        $lotUnqMaxId++;
                    }
                    // $demandupsAg = \App\Demand::find($demands->id);
                    // $demandupsAg->total_unit  = $calCulationOfUnit;
                    // $demandupsAg->save();
                    
                    // \DB::table('item_to_demand')
                    // ->where('demand_id', $demands->id)
                    // ->update(['demand_appv_status'=>1, 'approved_by' => Auth::user()->id, 'approved_date' => date('Y-m-d H:i:s'), 'current_status' => 2]);

                    $updateRetender = \App\NilReturn::where('id','=',$request->retednderId)->where('tender_id','=',$tenderId)->first();
                    $updateRetender->already_published = 1;
                    $updateRetender->save();

                    Session::flash('success', 'Data Created Successfully');
                    return Redirect::to('nil-return/3');

                //} // end if demand save

                
            }else{
                Session::flash('error', 'Data can not be created.');
                return Redirect::to('nil-return-create-tender/'.$request->retednderId.'&'.$tenderId);
            }

    }

    public function nilReturnPrint($id){
	    OwnLibrary::validateAccess($this->moduleId, 1);
	    $this->tableAlies = \Session::get('zoneAlise');

	    $explodes = explode('&',$id);

	    $demand_to_lpr_id   = $explodes[0];
	    $tenderId           = $explodes[1];
	    $nilId           = $explodes[2];

	    $nsdId = 1;
	    if(!empty(Auth::user()->nsd_bsd)){
		    $nsdId = Auth::user()->nsd_bsd;
	    }
	    $orgInfo  = \App\NsdName::find($nsdId);

	    $nilReturn = \App\NilReturn::find($nilId);

	    $approverName = '';

	    if (!empty($nilReturn->approved_by))
	    {
	    	$approverName = \App\User::find($nilReturn->approved_by);
	    }

	    $tenderInfo      = \App\Tender::find($tenderId);

	    $tenderNumberExplode = explode('.',$tenderInfo->tender_number);

	    $tenderFirst = '';
	    $tenderNumberFirst= '';
	    $tenderFirstId = '';
	    $tendertLatest = '';
	    $tenderNumberLatest = '';
	    $tenderLatestId = '';

	    if (!array_key_exists(8,$tenderNumberExplode))
	    {
		    $tenderFirst = $tenderInfo;
		    $tenderNumberFirst = $tenderFirst->tender_number;
		    $tenderFirstId = $tenderFirst->id;

			//		    find latest tander
		    $tendertLatest = \App\Tender::where('tender_number','Like',"%$tenderNumberFirst%")->latest()->first();
		    $tenderNumberLatest = $tendertLatest->tender_number;
		    $tenderLatestId = $tendertLatest->id;
	    }
	    else
	    {
		    $tendertLatest = $tenderInfo;;
		    $tenderNumberLatest = $tendertLatest->tender_number;
		    $tenderLatestId = $tendertLatest->id;

		    //		    find old tander
	    	array_pop($tenderNumberExplode);
		    $tenderNumberFirst = implode('.',$tenderNumberExplode);
		    $tenderFirst = \App\Tender::where('tender_number','Like',"%$tenderNumberFirst%")->oldest()->first();
		    $tenderFirstId = $tenderFirst->id;
	    }

	    $qutationFirstTender = \App\DemandToCollectionQuotation::select('tender_id','suppliernametext')->where('tender_id','=',$tenderFirstId)->get()->toArray();
	    $qutationFirstTenderCount = count($qutationFirstTender);

	    $qutationLatestTender = \App\DemandToCollectionQuotation::select('tender_id','suppliernametext')->where('tender_id','=',$tenderLatestId)->get()->toArray();
	    $qutationLatestTenderCount = count($qutationLatestTender);

	    $supplierNameFirst =  $this->arrayToString(array_column($qutationFirstTender,'suppliernametext'));
	    $supplierNameLatest =  $this->arrayToString(array_column($qutationLatestTender,'suppliernametext'));

	    $allTender = \App\Tender::where('tender_number','Like',"%$tenderNumberFirst%")->latest()->get()->toArray();

	    if (count($allTender) > 1)
	    {
		    array_pop($allTender);
	    }

	    $tenderDate = array_column($allTender,'tender_opening_date');

	    $retenderDates = $this->arrayToString($this->arrayDateFormat($tenderDate));
	    $tenderData = [
	    	'orgInfo' => $orgInfo,
	    	'nilReturn' => $nilReturn,
	    	'approverName' => $approverName,
	    	'tenderFirst' => $tenderFirst,
	    	'tendertLatest' => $tendertLatest,
	    	'qutationFirstTenderCount' => $qutationFirstTenderCount,
	    	'qutationLatestTenderCount' => $qutationLatestTenderCount,
	    	'supplierNameFirst' => $supplierNameFirst,
	    	'supplierNameLatest' => $supplierNameLatest,
	    	'retenderDates' => $retenderDates,
	    ];
	    $pdf= PDF::loadView('nil-return.nil-return-pdf',$tenderData,[],['format' => [215.9, 342.9]]);
	    return $pdf->stream('nil-return'.date('Y-m-d h:i:s').'.pdf');
    }


    public function arrayToString($value){
	    $givenString = implode(', ',$value);
	    $search = ',';
	    $replace = ' and';
	    return  strrev(implode(strrev($replace), explode(strrev($search), strrev($givenString), 2)));
    }


	function arrayDateFormat($value) {
		return array_map(function($val) { return date('d F Y', strtotime($val)); }, $value);
	}
}
