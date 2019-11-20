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
use App\DemandCrToInspection;
use App\DemandeName;
use App\Tender;
use DB;
use PDF;


class EditController extends Controller
{
	private $moduleId = 34;
	private $tableAlies;

    public function demandEdit($id){
	    OwnLibrary::validateAccess($this->moduleId,3);
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

	    $destinationPlaces = NsdName::where('zones','=',Session::get('zoneId'))->get();

	    $denos = \App\Deno::where('status_id','=',1)->get();

	    //$group_names = GroupName::where('status','=',1)->get();

	    $demandeNames = DemandeName::where('status','=',1)->get();

	    $value = Demand::find($id);

	    $items = ItemToDemand::where('demand_id','=',$id)->get();

	    return View::make('demands.demand-edit')->with(compact('value','items','supplyCategories','nsdNames','destinationPlaces','denos','demandeNames'));
    }

    public function demandUpdate (Request $request,$id){

//    	dd($request->all());

	    $this->middleware('csrf', array('on' => 'post'));

	    $v = \Validator::make($request->all(), [
		    'requester'                     => 'required',
		    'demand_no'                     => 'required|unique:demands,demand_no,'.$id,
		    //'priority'                      => 'required',
		    //'product_detailsetc'            => 'required',
		    'machinery_and_manufacturer'    => 'required',
		    'serial_or_reg_number'          => 'required',
		    'unit'                          => 'required',
		    'price'                         => 'required',
	    ]);

	    if ($v->fails()) {
		    return redirect()->back()->withErrors($v->errors())->withInput();
	    }else {

		    if(count($request->machinery_and_manufacturer)>0){

			    $image_upload   = TRUE;
			    $image_name     = FALSE;

			    // Insert to demand table ============================================

			    $demands                                = Demand::find($id);

			    $demands->requester                     = $request->requester;
			    $demands->recurring_casual_or_not       = $request->recurring_casual_or_not;
			    $demands->demand_no                     = $request->demand_no;
			    $demands->priority                      = $request->priority;
			    $demands->permanent_or_waste_content    = $request->permanent_or_waste_content;
			    $demands->when_needed                   = date('Y-m-d',strtotime($request->when_needed));
			    $demands->reference_date                = empty($request->reference_date) ? null : $request->reference_date;
			    $demands->place_to_send                 = $request->place_to_send;
			    $demands->for_whom                      = $request->for_whom;
			    $demands->pattern_or_stock_no           = $request->pattern_or_stock_no;
			    $demands->product_detailsetc            = $request->product_detailsetc;
			    $demands->total_unit                    = $request->total_unit;
			    $demands->total_value                   = $request->total_value;
			    $demands->demand_entry_by               = Auth::user()->id;
			    $demands->demand_entry_date             = date('Y-m-d H:i:s');
			    $demands->current_status                = 1;

			    if ($image_name !== FALSE) {
				    $demands->issue_control_stamp = $filename;
			    }

			    if ($demands->save()) {
				    $demandsUp                       = Demand::find($demands->id);
				    $demandsUp->uniqe_for_all_org    = $demands->id;
				    $demandsUp->save();

				    for($i=0; count($request->machinery_and_manufacturer)>$i; $i++){
						if (isset($request->id_item[$i]) && !empty($request->id_item[$i])){
							$itemToDemands = ItemToDemand::find($request->id_item[$i]);
						}else{
							$itemToDemands              = new ItemToDemand();
						}
					    $itemToDemands->demand_id       = $demands->id;
					    $itemToDemands->item_id         = $request->machinery_and_manufacturer_id[$i];
					    $itemToDemands->item_name       = $request->machinery_and_manufacturer[$i];
					    $itemToDemands->item_model      = $request->model_type_mark[$i];
					    $itemToDemands->serial_imc_no   = $request->serial_or_reg_number[$i];
					    $itemToDemands->group_name      = $request->publication_or_class[$i];
					    $itemToDemands->deno_id         = $request->deno[$i];
					    $itemToDemands->currency_rate   = $request->currency_rates[$i];
					    $itemToDemands->unit            = $request->unit[$i];
					    $itemToDemands->total_unit      = $request->unit[$i];
					    $itemToDemands->unit_price      = $request->price[$i];
					    $itemToDemands->current_status  = 1;
					    $itemToDemands->sub_total       = $request->sutotal_price[$i];
					    $itemToDemands->save();
				    }

			    }

			    Session::flash('success', 'Data Updated Successfully');
			    return Redirect::to('demand-pending/1');
		    }else{
			    Session::flash('error', 'Data can not be updated.');
			    return Redirect::to('demand-edit/'.$id);
		    }

		    //}
	    }

    }

    public function demandDelete($id){
    	//OwnLibrary::validateAccess($this->moduleId,4);
        $dmnddel = Demand::find($id);
        
        if ($dmnddel->delete()) {
            Session::flash('success', 'Demand Deleted Successfully');
            return Redirect::to('demand-pending/1');
        } else {
            Session::flash('error', 'Demand Not Found');
            return Redirect::to('demand-pending/1');
        }
    }

    public function demandItemDelete($id){
		if(ItemToDemand::destroy($id)){
			Session::flash('success', 'Data Delated Successfully');
		}else{
			Session::flash('error', 'Data can not be delated.');
		}

		return redirect()->back();
    }

    public function directItemDmndEdit($id){
    	$this->tableAlies = \Session::get('zoneAlise');
		//OwnLibrary::validateAccess($this->moduleId,3);
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

		$supplyCategories = SupplyCategory::whereIn('id',$zonesRltdCtgIds);
		if(!empty(Auth::user()->categories_id)){
			$userWiseCat = explode(',', Auth::user()->categories_id);
			$supplyCategories->whereIn('id',$userWiseCat);
		}
		$supplyCategories->where('status_id','=',1);
		$supplyCategories = $supplyCategories->get();

		$destinationPlaces = NsdName::where('zones','=',Session::get('zoneId'))->get();

		$denos = \App\Deno::where('status_id','=',1)->get();
		$demandeNames = DemandeName::where('status','=',1)->get();

		// For create tender ====================================
		// ======================================================
		$tenderNumber = $maxDemandId = Tender::max('id');
		$maxId        = $maxDemandId+1;
		// $currentYear  = date('Y');
//		$currentYear  = ( date('m') > 6) ? date('y').'-'.(date('y') + 1) : (date('y') - 1).'-'.date('y');
//		$extraNum     = '23.02.2508.212.53.000.';
//		$tenderNoFor  = $extraNum.$currentYear.'.'.$maxId;

		$tenderTearmsAndConditions = \App\TermsCondition::where('status','=',1)->get();

		$editId = Tender::find($id);

		$demandInfo = '';
		if(empty($editId)){
			Session::flash( 'error', 'Tender not found' );
			return redirect('floating-tender-acc/2');
		}else{
			$demandsInfo = \App\Demand::whereIn('id',explode(',', $editId->demand_no))->get();
			foreach ($demandsInfo as $value) {
				if(!empty($value->demand_no)){
					$demandInfo .= $value->demand_no.',';
				}
			}
		}
		$demandInfo = rtrim($demandInfo, ',');

		$tender_items = ItemToDemand::where('tender_no','=',$id)->get();

		$catGrou      = Auth::user()->categories_id;
        $itemtodemand = \App\ItemToDemand::join($this->tableAlies.'_items', $this->tableAlies.'_items.id', '=', 'item_to_demand.item_id')
                ->join('supplycategories',$this->tableAlies.'_items.item_cat_id','=','supplycategories.id')
                ->select('item_to_demand.*','item_to_demand.unit','supplycategories.name as categoryname', $this->tableAlies.'_items.item_name', $this->tableAlies.'_items.item_deno', $this->tableAlies.'_items.item_cat_id')
                ->where('item_to_demand.tender_no','=',$id);
                if(!empty($catGrou)){
                    $catGrou = explode(',',$catGrou);
                    $itemtodemand->whereIn('item_to_demand.group_name',$catGrou);
                }
        $itemtodemand       = $itemtodemand->get();
        $denos = \App\Deno::where('status_id','=',1)->get(); 

		return View::make('floating-tender.direct-item-dmnd-edit')->with(compact('editId','tender_items','supplyCategories','nsdNames','destinationPlaces','denos','demandeNames','tenderNoFor','tenderTearmsAndConditions','demandInfo','itemtodemand','denos'));
	}

	public function directItemDmndupdate(Request $request,$id) {

		// OwnLibrary::validateAccess($this->moduleId,3);
		$this->middleware( 'csrf', array( 'on' => 'post' ) );

			$this->middleware('csrf', array('on' => 'post'));

        $v = \Validator::make($request->all(), [
            'tender_title' => 'required',
            'tender_number' => 'required|unique:'.\Session::get("zoneAlise").'_tenders,tender_number,'.$id,
            'tender_opening_date' => 'required',
            'tender_cat_id' => 'required',
            'nsd_id' => 'required',
            //'item_to_tender_assing' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('direct-item-dmnd-edit/'.$id)->withErrors($v->errors())->withInput();
        }else{
		
				// Tender create ======================================
				// ====================================================
				$notice_pdf_upload = true;
				$notice_pdf_name   = false;
				if ( Input::hasFile( 'notice' ) ) {
					$noticeFile            = Input::file( 'notice' );
					$destinationPathNotice = public_path() . '/uploads/tender_spicification_notice_pdf/';
					$noticeFilename        = uniqid() . $noticeFile->getClientOriginalName();
					$uploadSuccessNotice   = Input::file( 'notice' )->move( $destinationPathNotice, $noticeFilename );
					if ( $uploadSuccessNotice ) {
						$notice_pdf_name = true;
					} else {
						$notice_pdf_upload = false;
					}
				}

				$tender                = Tender::find($id);
				$tender->tender_title  = $request->tender_title;
				$tender->tender_number = $request->tender_number;
				//$tender->po_number = empty($request->po_number) ? null : $request->po_number;
				$tender->tender_opening_date = empty( $request->tender_opening_date ) ? null : date( 'Y-m-d', strtotime( $request->tender_opening_date ) );
				$tender->tender_description  = empty( $request->tender_description ) ? null : $request->tender_description;
				$tender->tender_cat_id       = empty( $request->tender_cat_id ) ? null : $request->tender_cat_id;
				$tender->nsd_id              = empty( $request->nsd_id ) ? null : $request->nsd_id;
				$tender->imc_number          = empty( $request->imc_number ) ? null : $request->imc_number;
				//$tender->open_tender = empty($request->open_tender) ? null : $request->open_tender;
				// Newly added ====================================
				$tender->date_line              = empty( $request->tender_opening_date ) ? null : date( 'Y-m-d', strtotime( $request->tender_opening_date ) );
				$tender->approval_letter_number = empty( $request->approval_letter_number ) ? null : $request->approval_letter_number;
				$tender->approval_letter_date   = empty( $request->approval_letter_date ) ? null : date( 'Y-m-d', strtotime( $request->approval_letter_date ) );
				$tender->purchase_type          = empty( $request->purchase_type ) ? null : $request->purchase_type;
				$tender->tender_type            = empty( $request->tender_type ) ? null : $request->tender_type;
				$tender->tender_nature          = empty( $request->tender_nature ) ? null : $request->tender_nature;
				$tender->ref_tender_id          = empty( $request->ref_tender_id ) ? null : $request->ref_tender_id;
				$tender->tender_priority        = empty( $request->tender_priority ) ? null : $request->tender_priority;
				$tender->letter_body            = empty( $request->letter_body ) ? null : $request->letter_body;
				$tender->remarks                = empty( $request->remarks ) ? null : $request->remarks;
				$tender->additionl_info = empty($request->additionl_info) ? null : $request->additionl_info;
				$tender->valid_date_from        = empty( $request->valid_date_from ) ? null : $request->valid_date_from;
				$tender->valid_date_to          = empty( $request->tender_opening_date ) ? null : $request->tender_opening_date;
				$tender->extend_date_to         = empty( $request->extend_date_to ) ? null : $request->extend_date_to;
				$tender->reference              = empty( $request->reference ) ? null : $request->reference;

				$tender->invitation_for       = empty( $request->invitation_for ) ? null : $request->invitation_for;
				$tender->date                 = empty( $request->valid_date_from ) ? null : date( 'Y-m-d', strtotime( $request->valid_date_from ) );
				$tender->development_partners = empty( $request->development_partners ) ? null : $request->development_partners;
				$tender->proj_prog_code       = empty( $request->proj_prog_code ) ? null : $request->proj_prog_code;
				$tender->tender_package_no    = empty( $request->tender_package_no ) ? null : $request->tender_package_no;
				$tender->tender_package_name  = empty( $request->tender_package_name ) ? null : $request->tender_package_name;
				$tender->pre_tender_meeting   = empty( $request->pre_tender_meeting ) ? null : date( 'Y-m-d h:i:s', strtotime( $request->pre_tender_meeting ) );;
				$tender->eligibility_of_tender   = empty( $request->eligibility_of_tender ) ? null : $request->eligibility_of_tender;
				$tender->name_of_offi_invit_ten  = empty( $request->name_of_offi_invit_ten ) ? null : $request->name_of_offi_invit_ten;
				$tender->desg_of_offi_invit_ten  = empty( $request->desg_of_offi_invit_ten ) ? null : $request->desg_of_offi_invit_ten;
				$tender->nhq_ltr_no              = empty( $request->nhq_ltr_no ) ? null : $request->nhq_ltr_no;
				$tender->tender_terms_conditions = empty( $request->terms_conditions_field ) ? null : $request->terms_conditions_field;
				$tender->number_of_lot_item      = empty( $request->number_of_lot_item ) ? null : $request->number_of_lot_item;
				$tender->reference_date          = empty( $request->reference_date ) ? null : date( 'Y-m-d', strtotime( $request->reference_date ) );
				$tender->delivery_date           = empty( $request->delivery_date ) ? null : $request->delivery_date;
				$tender->location                = empty( $request->location ) ? null : $request->location;
                $tender->demending = empty($request->demending) ? null : $request->demending;

				$tender->status_id = $request->status;
				$tender->is_enclosure = empty($request->is_enclosure) ? 0 : $request->is_enclosure;
				// Newly added ====================================================
				// ================================================================
				$fileExtension = '';
				if ( ! empty( $request->specification ) && count( $request->specification ) > 0 ) {
					for ( $i = 0; count( $request->specification ) > $i; $i ++ ) {
						if ( ! empty( $request->specification[ $i ] ) ) {
							$file            = $request->specification[ $i ];
							$destinationPath = public_path() . '/uploads/tender_spicification_notice_pdf/';
							$fileExtension   = $file->getClientOriginalExtension();
							$specification   = uniqid() . $file->getClientOriginalName();
							$uploadSuccess   = $file->move( $destinationPath, $specification );

							if ( $fileExtension == 'pdf' ) {
								@unlink(public_path() . '/uploads/tender_spicification_notice_pdf/'.$tender->specification);
								$tender->specification = $specification;
							} else {
								@unlink(public_path() . '/uploads/tender_spicification_notice_pdf/'.$tender->specification_doc);
								$tender->specification_doc = $specification;
							}
						}
					}
				}
				// End newly added ================================================
				// ================================================================
				if ( $notice_pdf_name !== false ) {
					@unlink(public_path() . '/uploads/tender_spicification_notice_pdf/'.$tender->notice);
					$tender->notice = $noticeFilename;
				}

				for($i=0; count($request->machinery_and_manufacturer)>$i; $i++){
					$itemToDemands = \App\ItemToDemand::find($request->machinery_and_manufacturer_id[$i]);
					//$itemToDemands->item_id         = $request->machinery_and_manufacturer_id[$i];
                    $itemToDemands->item_name       = $request->machinery_and_manufacturer[$i];
                    $itemToDemands->group_name      = $request->publication_or_class[$i];
                    $itemToDemands->deno_id         = $request->deno[$i];
                    $itemToDemands->unit            = $request->unit[$i];
                    $itemToDemands->total_unit      = $request->unit[$i];
                    $itemToDemands->save();
				}

				$demandToLprUp  = \App\DemandToLpr::find($tender->lpr_id);

		        $demandIds = explode(',', $demandToLprUp->demand_ids);
		        if(!empty($demandToLprUp->demand_ids) && count($demandIds)>0){
			        foreach ($demandIds as $diss) {
				        $demandUpda = Demand::find($diss);
				        $demandUpda->float_tender_app_status     = NULL;
				        $demandUpda->float_tender_app_by         = NULL;
				        $demandUpda->float_tender_app_at         = NULL;
				        $demandUpda->current_status              = NULL;
				        $demandUpda->save();
			        }
		        }

		        $demandToLprUp->float_tender_app_status     = NULL;
		        $demandToLprUp->float_tender_app_by         = NULL;
		        $demandToLprUp->float_tender_app_at         = NULL;
		        $demandToLprUp->current_status              = NULL;

		        $demandToLprUp->save();

		        \DB::table('item_to_demand')
		           ->where('lpr_id', $demandToLprUp->id)
		           ->where('tender_no', $id)
		           ->update(['float_tender_app_status' => NULL, 'float_tender_app_by' =>  NULL, 'float_tender_app_at' => NULL, 'current_status' => NULL,'tender_number' => $request->tender_number]);

		        \DB::table('demand_to_tender')
		           ->where('lpr_id', $demandToLprUp->id)
		           ->where('tender_id', $id)
		           ->update(['float_tender_app_status' => NULL, 'float_tender_app_by' =>  NULL, 'float_tender_app_at' => NULL, 'current_status' => NULL,'tender_number' => $request->tender_number]);

				if($tender->save()){
					Session::flash( 'success', 'Tender Updated Successfully' );
					return Redirect::to('floating-tender-acc/4');
				}
			
		}

	}

	public function directItemDmndEditWithIft($id){
    	$this->tableAlies = \Session::get('zoneAlise');
		OwnLibrary::validateAccess($this->moduleId,3);
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

		$supplyCategories = SupplyCategory::whereIn('id',$zonesRltdCtgIds);
		if(!empty(Auth::user()->categories_id)){
			$userWiseCat = explode(',', Auth::user()->categories_id);
			$supplyCategories->whereIn('id',$userWiseCat);
		}
		$supplyCategories->where('status_id','=',1);
		$supplyCategories = $supplyCategories->get();

		$destinationPlaces = NsdName::where('zones','=',Session::get('zoneId'))->get();

		$denos = \App\Deno::where('status_id','=',1)->get();
		$demandeNames = DemandeName::where('status','=',1)->get();

		// For create tender ====================================
		// ======================================================
		$tenderNumber = $maxDemandId = Tender::max('id');
		$maxId        = $maxDemandId+1;
		// $currentYear  = date('Y');
//		$currentYear  = ( date('m') > 6) ? date('y').'-'.(date('y') + 1) : (date('y') - 1).'-'.date('y');
//		$extraNum     = '23.02.2508.212.53.000.';
//		$tenderNoFor  = $extraNum.$currentYear.'.'.$maxId;

		$tenderTearmsAndConditions = \App\TermsCondition::where('status','=',1)->get();

		$editId = Tender::find($id);

		$demandInfo = '';
		if(empty($editId)){
			Session::flash( 'error', 'Tender not found' );
			return redirect('floating-tender-acc/2');
		}else{
			$demandsInfo = \App\Demand::whereIn('id',explode(',', $editId->demand_no))->get();
			foreach ($demandsInfo as $value) {
				if(!empty($value->demand_no)){
					$demandInfo .= $value->demand_no.',';
				}
			}
		}
		$demandInfo = rtrim($demandInfo, ',');

		$tender_items = ItemToDemand::where('tender_no','=',$id)->get();

		$catGrou      = Auth::user()->categories_id;
        $itemtodemand = \App\ItemToDemand::join($this->tableAlies.'_items', $this->tableAlies.'_items.id', '=', 'item_to_demand.item_id')
                ->join('supplycategories',$this->tableAlies.'_items.item_cat_id','=','supplycategories.id')
                ->select('item_to_demand.*','item_to_demand.unit','supplycategories.name as categoryname', $this->tableAlies.'_items.item_name', $this->tableAlies.'_items.item_deno', $this->tableAlies.'_items.item_cat_id')
                ->where('item_to_demand.tender_no','=',$id);
                if(!empty($catGrou)){
                    $catGrou = explode(',',$catGrou);
                    $itemtodemand->whereIn('item_to_demand.group_name',$catGrou);
                }
        $itemtodemand       = $itemtodemand->get();
        $denos = \App\Deno::where('status_id','=',1)->get();  

		return View::make('floating-tender.direct-item-dmnd-edit-with-ift')->with(compact('editId','tender_items','supplyCategories','nsdNames','destinationPlaces','denos','demandeNames','tenderNoFor','tenderTearmsAndConditions','demandInfo','itemtodemand','denos'));
	}

	public function postDirectItemDmndEditWithIft(Request $request,$id) {

		OwnLibrary::validateAccess($this->moduleId,3);
		$this->middleware( 'csrf', array( 'on' => 'post' ) );

			$this->middleware('csrf', array('on' => 'post'));

        $v = \Validator::make($request->all(), [
            'tender_title' => 'required',
            'tender_number' => 'required|unique:'.\Session::get("zoneAlise").'_tenders,tender_number,'.$id,
            'tender_opening_date' => 'required',
            'tender_cat_id' => 'required',
            'nsd_id' => 'required',
            //'item_to_tender_assing' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('direct-item-dmnd-edit-with-ift/'.$id)->withErrors($v->errors())->withInput();
        }else{
		
				// Tender create ======================================
				// ====================================================
				$notice_pdf_upload = true;
				$notice_pdf_name   = false;
				if ( Input::hasFile( 'notice' ) ) {
					$noticeFile            = Input::file( 'notice' );
					$destinationPathNotice = public_path() . '/uploads/tender_spicification_notice_pdf/';
					$noticeFilename        = uniqid() . $noticeFile->getClientOriginalName();
					$uploadSuccessNotice   = Input::file( 'notice' )->move( $destinationPathNotice, $noticeFilename );
					if ( $uploadSuccessNotice ) {
						$notice_pdf_name = true;
					} else {
						$notice_pdf_upload = false;
					}
				}

				$tender                = Tender::find($id);
				$tender->tender_title  = $request->tender_title;
				$tender->tender_number = $request->tender_number;
				//$tender->po_number = empty($request->po_number) ? null : $request->po_number;
				$tender->tender_opening_date = empty( $request->tender_opening_date ) ? null : date( 'Y-m-d', strtotime( $request->tender_opening_date ) );
				$tender->tender_description  = empty( $request->tender_description ) ? null : $request->tender_description;
				$tender->tender_cat_id       = empty( $request->tender_cat_id ) ? null : $request->tender_cat_id;
				$tender->nsd_id              = empty( $request->nsd_id ) ? null : $request->nsd_id;
				$tender->imc_number          = empty( $request->imc_number ) ? null : $request->imc_number;
				//$tender->open_tender = empty($request->open_tender) ? null : $request->open_tender;
				// Newly added ====================================
				$tender->date_line              = empty( $request->tender_opening_date ) ? null : date( 'Y-m-d', strtotime( $request->tender_opening_date ) );
				$tender->approval_letter_number = empty( $request->approval_letter_number ) ? null : $request->approval_letter_number;
				$tender->approval_letter_date   = empty( $request->approval_letter_date ) ? null : date( 'Y-m-d', strtotime( $request->approval_letter_date ) );
				$tender->purchase_type          = empty( $request->purchase_type ) ? null : $request->purchase_type;
				$tender->tender_type            = empty( $request->tender_type ) ? null : $request->tender_type;
				$tender->tender_nature          = empty( $request->tender_nature ) ? null : $request->tender_nature;
				$tender->ref_tender_id          = empty( $request->ref_tender_id ) ? null : $request->ref_tender_id;
				$tender->tender_priority        = empty( $request->tender_priority ) ? null : $request->tender_priority;
				$tender->letter_body            = empty( $request->letter_body ) ? null : $request->letter_body;
				$tender->remarks                = empty( $request->remarks ) ? null : $request->remarks;
				$tender->additionl_info = empty($request->additionl_info) ? null : $request->additionl_info;
				$tender->valid_date_from        = empty( $request->valid_date_from ) ? null : $request->valid_date_from;
				$tender->valid_date_to          = empty( $request->tender_opening_date ) ? null : $request->tender_opening_date;
				$tender->extend_date_to         = empty( $request->extend_date_to ) ? null : $request->extend_date_to;
				$tender->reference              = empty( $request->reference ) ? null : $request->reference;

				$tender->invitation_for       = empty( $request->invitation_for ) ? null : $request->invitation_for;
				$tender->date                 = empty( $request->valid_date_from ) ? null : date( 'Y-m-d', strtotime( $request->valid_date_from ) );
				$tender->development_partners = empty( $request->development_partners ) ? null : $request->development_partners;
				$tender->proj_prog_code       = empty( $request->proj_prog_code ) ? null : $request->proj_prog_code;
				$tender->tender_package_no    = empty( $request->tender_package_no ) ? null : $request->tender_package_no;
				$tender->tender_package_name  = empty( $request->tender_package_name ) ? null : $request->tender_package_name;
				$tender->pre_tender_meeting   = empty( $request->pre_tender_meeting ) ? null : date( 'Y-m-d h:i:s', strtotime( $request->pre_tender_meeting ) );;
				$tender->eligibility_of_tender   = empty( $request->eligibility_of_tender ) ? null : $request->eligibility_of_tender;
				$tender->name_of_offi_invit_ten  = empty( $request->name_of_offi_invit_ten ) ? null : $request->name_of_offi_invit_ten;
				$tender->desg_of_offi_invit_ten  = empty( $request->desg_of_offi_invit_ten ) ? null : $request->desg_of_offi_invit_ten;
				$tender->nhq_ltr_no              = empty( $request->nhq_ltr_no ) ? null : $request->nhq_ltr_no;
				$tender->tender_terms_conditions = empty( $request->terms_conditions_field ) ? null : $request->terms_conditions_field;
				$tender->number_of_lot_item      = empty( $request->number_of_lot_item ) ? null : $request->number_of_lot_item;
				$tender->reference_date          = empty( $request->reference_date ) ? null : date( 'Y-m-d', strtotime( $request->reference_date ) );
				$tender->delivery_date           = empty( $request->delivery_date ) ? null : $request->delivery_date;
				$tender->location                = empty( $request->location ) ? null : $request->location;

				$tender->status_id = $request->status;
				$tender->is_enclosure = empty($request->is_enclosure) ? 0 : $request->is_enclosure;
				// Newly added ====================================================
				// ================================================================
				$fileExtension = '';
				if ( ! empty( $request->specification ) && count( $request->specification ) > 0 ) {
					for ( $i = 0; count( $request->specification ) > $i; $i ++ ) {
						if ( ! empty( $request->specification[ $i ] ) ) {
							$file            = $request->specification[ $i ];
							$destinationPath = public_path() . '/uploads/tender_spicification_notice_pdf/';
							$fileExtension   = $file->getClientOriginalExtension();
							$specification   = uniqid() . $file->getClientOriginalName();
							$uploadSuccess   = $file->move( $destinationPath, $specification );

							if ( $fileExtension == 'pdf' ) {
								@unlink(public_path() . '/uploads/tender_spicification_notice_pdf/'.$tender->specification);
								$tender->specification = $specification;
							} else {
								@unlink(public_path() . '/uploads/tender_spicification_notice_pdf/'.$tender->specification_doc);
								$tender->specification_doc = $specification;
							}
						}
					}
				}
				// End newly added ================================================
				// ================================================================
				if ( $notice_pdf_name !== false ) {
					@unlink(public_path() . '/uploads/tender_spicification_notice_pdf/'.$tender->notice);
					$tender->notice = $noticeFilename;
				}

				for($i=0; count($request->machinery_and_manufacturer)>$i; $i++){
					$itemToDemands = \App\ItemToDemand::find($request->machinery_and_manufacturer_id[$i]);
					//$itemToDemands->item_id         = $request->machinery_and_manufacturer_id[$i];
                    $itemToDemands->item_name       = $request->machinery_and_manufacturer[$i];
                    $itemToDemands->group_name      = $request->publication_or_class[$i];
                    $itemToDemands->deno_id         = $request->deno[$i];
                    $itemToDemands->unit            = $request->unit[$i];
                    $itemToDemands->total_unit      = $request->unit[$i];
                    //$itemToDemands->in_stock        = 0;
                    //$itemToDemands->not_in_stock    = $request->unit[$i];
                    $itemToDemands->save();
				}

				$demandToLprUp  = \App\DemandToLpr::find($tender->lpr_id);

		        $demandIds = explode(',', $demandToLprUp->demand_ids);
		        if(!empty($demandToLprUp->demand_ids) && count($demandIds)>0){
			        foreach ($demandIds as $diss) {
				        $demandUpda = Demand::find($diss);
				        $demandUpda->float_tender_app_status     = 1;
	                    $demandUpda->float_tender_app_by         = Auth::user()->id;
	                    $demandUpda->float_tender_app_at         = date('Y-m-d H:i:s');
	                    $demandUpda->current_status              = 5;
				        $demandUpda->save();
			        }
		        }

		        $demandToLprUp->float_tender_app_status     = 1;
	            $demandToLprUp->float_tender_app_by         = Auth::user()->id;
	            $demandToLprUp->float_tender_app_at         = date('Y-m-d H:i:s');
	            $demandToLprUp->current_status              = 5;

		        $demandToLprUp->save();

		        \DB::table('item_to_demand')
                ->where('lpr_id', $demandToLprUp->id)
                ->where('tender_no', $id)
                ->update(['float_tender_app_status' => 1, 'float_tender_app_by' =>  Auth::user()->id, 'float_tender_app_at' => date('Y-m-d H:i:s'), 'current_status' => 5]);

                \DB::table('demand_to_tender')
                ->where('lpr_id', $demandToLprUp->id)
                ->where('tender_id', $id)
                ->update(['float_tender_app_status' => 1, 'float_tender_app_by' =>  Auth::user()->id, 'float_tender_app_at' => date('Y-m-d H:i:s'), 'current_status' => 5]);

				if($tender->save()){

					$itemsInfoDesc = '';
		            $lotItemArray  = array();
		            $appUserInfo      = Auth::user();
        			$organizationName = \App\NsdName::where('id','=',$appUserInfo->nsd_bsd)->value('name');
		            if($tender->tender_nature==1){
		                $itemsInfoDesc = ItemToDemand::join('deno', 'item_to_demand.deno_id', '=', 'deno.id')
		                    ->join(\Session::get('zoneAlise').'_tenders','item_to_demand.tender_no','=',\Session::get('zoneAlise').'_tenders.id')
		                    ->join(\Session::get('zoneAlise').'_items','item_to_demand.item_id','=',\Session::get('zoneAlise').'_items.id')
		                    ->join('supplycategories','supplycategories.id','=',\Session::get('zoneAlise').'_items.item_cat_id')
		                    ->join('demand_to_lpr', 'item_to_demand.lpr_id', '=', 'demand_to_lpr.id')
		                    ->leftJoin('nsdname', 'nsdname.id', '=', 'demand_to_lpr.place_to_send')
		                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
		                    ->select('item_to_demand.item_name as item_to_demand_item_name','item_to_demand.unit as item_to_demand_unit','deno.name as deno_deno_name','demande_name.name as demande_name',\Session::get('zoneAlise').'_tenders.remarks as tender_remarks',\Session::get('zoneAlise').'_tenders.delivery_date as tender_delivery_date',\Session::get('zoneAlise').'_tenders.location as location',\Session::get('zoneAlise').'_items.*','supplycategories.name as supplycategories_name')
		                    ->where('item_to_demand.tender_no','=',$tender->id)
		                    ->get();
		            }
		            if($tender->tender_nature==2){
		                $itemsInfoDesc = ItemToDemand::select('lot_name')->where('tender_no','=',$tender->id)->groupBy('lot_name')
		                    ->orderBy('id','asc')
		                    ->get();
		                
		                    foreach($itemsInfoDesc as $iid){
		                        $lotItemArray[$iid->lot_name] = ItemToDemand::join('deno', 'item_to_demand.deno_id', '=', 'deno.id')
		                    ->join(\Session::get('zoneAlise').'_tenders','item_to_demand.tender_no','=',\Session::get('zoneAlise').'_tenders.id')
		                    ->join(\Session::get('zoneAlise').'_items','item_to_demand.item_id','=',\Session::get('zoneAlise').'_items.id')
		                    ->join('supplycategories','supplycategories.id','=',\Session::get('zoneAlise').'_items.item_cat_id')
		                    ->join('demand_to_lpr', 'item_to_demand.lpr_id', '=', 'demand_to_lpr.id')
		                    //->join('demands', 'item_to_demand.demand_id', '=', 'demands.id')
		                    ->leftJoin('nsdname', 'nsdname.id', '=', 'demand_to_lpr.place_to_send')
		                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
		                    ->select('item_to_demand.item_name as item_to_demand_item_name','item_to_demand.unit as item_to_demand_unit','deno.name as deno_deno_name','demande_name.name as demande_name',\Session::get('zoneAlise').'_tenders.remarks as tender_remarks',\Session::get('zoneAlise').'_tenders.delivery_date as tender_delivery_date',\Session::get('zoneAlise').'_tenders.location as location',\Session::get('zoneAlise').'_items.*','supplycategories.name as supplycategories_name')
		                    ->where('item_to_demand.tender_no','=',$tender->id)
		                    ->where('item_to_demand.lot_name','=',$iid->lot_name)
		                    ->get();
		                    }

		            }

		            $tenderData = [
		                'tenderInfoForPdf' => $tender,
		                'itemsInfoDesc' => $itemsInfoDesc,
		                'lotItemArray' => $lotItemArray,
		                'appUserInfo' => $appUserInfo,
		                'organizationName' => $organizationName
		            ];

		            $specificationPdfFileName = '';
		            $specificationPdfFileName = 'specipication_notice_'.$tender->id.date('y-m-dhis').'.pdf';

		            $pdf= PDF::loadView('floating-tender.specipicationpdf',$tenderData,[],['format' => [215.9, 342.9]]);
		            $pdf->save(public_path() . '/uploads/tender_spicification_notice_pdf/'.$specificationPdfFileName);
		            $tenderInfoForPdf = Tender::find($tender->id);
		            $tenderInfoForPdf->notice = $specificationPdfFileName;
		            $tenderInfoForPdf->save();

					Session::flash( 'success', 'Tender Updated Successfully' );
					return Redirect::to('floating-tender-acc/3');
				}
			
		}

	}

	public function deleteCollectionQuotation($id,$tender_id=null){
        
        $dem_to_lpr_id  = $id;
        $demandToLpr     = \App\DemandToLpr::find($dem_to_lpr_id);
        $tender_id  = $tender_id;
        $tenderInfoForPdf = Tender::find($tender_id);


        $demandToCollIds  = array_map('current',\App\DemandToCollectionQuotation::select('id')
        										->where('lpr_id','=',$dem_to_lpr_id)
        										->where('tender_id','=',$tender_id)
        										->get()->toArray());
        if($tenderInfoForPdf->tender_nature==2){
        	\DB::table('lot_to_supplier')
	        ->whereIn('mnd_to_col_qtn_id', $demandToCollIds)
	        ->delete();
        }

        \DB::table('demand_to_collection_quotation')
        ->where('lpr_id', $dem_to_lpr_id)
        ->where('tender_id', $tender_id)
        ->delete();

        \DB::table('demand_supplier_to_coll_qut_to_item')
        ->where('lpr_id', $dem_to_lpr_id)
        ->where('tender_id', $tender_id)
        ->delete();

           $demandtolprups = \App\DemandToLpr::find($dem_to_lpr_id);
	       $demandtolprups->current_status                 = 5;
	       $demandtolprups->tender_quation_collection      = NULL;
	       $demandtolprups->tender_quation_collection_by   = NULL;
	       $demandtolprups->tender_quation_collection_date = NULL;
	       $demandtolprups->save();

	       $demandIds = explode(',',$demandtolprups->demand_ids);
	       if(!empty($demandtolprups->demand_ids)){
	            foreach ($demandIds as $vl) {
	               $demandups = \App\Demand::find($vl);
	               $demandups->current_status                 = 5;
	               $demandups->tender_quation_collection      = NULL;
	               $demandups->tender_quation_collection_by   = NULL;
	               $demandups->tender_quation_collection_date = NULL;
	               $demandups->save();
	            }
	       }
	       

	       $demandToTender = \App\DemandToTender::where('lpr_id','=',$dem_to_lpr_id)->where('tender_id','=',$tender_id)->first();
	       $demandToTender->current_status  = 5;
	       $demandToTender->tender_quation_collection      = NULL;
	       $demandToTender->tender_quation_collection_by   = NULL;
	       $demandToTender->tender_quation_collection_date = NULL;
	       $demandToTender->save();

        \DB::table('item_to_demand')
            ->where('lpr_id', $dem_to_lpr_id)
            ->where('tender_no', $tender_id)
            ->update(['tender_quation_collection' => NULL, 'tender_quation_collection_by' =>  NULL, 'tender_quation_collection_date' => NULL, 'current_status' => 5]);

        Session::flash('success', 'Data Deleted Successfully');
        return Redirect::to('collection-quotation-acc/3');

    }
	

}
