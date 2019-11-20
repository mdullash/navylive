<?php

namespace App\Http\Controllers;

use App\BillForwarding;
use App\BudgetCode;
use App\CstForwarding;
use App\DemandCrToInspection;
use App\DemandPoToCr;
use App\DemandToCollectionQuotation;
use App\LotNames;
use App\PoDatas;
use App\Supplier;
use App\Tender;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Support\Facades\Input;
use phpDocumentor\Reflection\Types\Null_;

class BillController extends Controller
{

	private $moduleId = 15;

	public function pendingIndex(){

		$cr_no = Input::get('cr_no');
		$from = Input::get('from');
		$todate = Input::get('todate');

		$this->tableAlies = \Session::get('zoneAlise');

		$cr_no = Input::get('cr_no');
		$demands = \App\D44BData::join('demand_cr_to_inspection', 'demand_cr_to_inspection.id', '=', 'd44b_datas.inspecttion_id')
		                        ->leftJoin('demand_po_to_cr', 'demand_po_to_cr.id', '=', 'demand_cr_to_inspection.dmnd_po_to_cr_id')
		                        ->leftJoin('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
		                        ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
		                        ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
		                        ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
		                        ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
		                        ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
		                        ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title' ,'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','d44b_datas.id as d44b_id','po_datas.id as po_id','demand_po_to_cr.id as demand_po_to_cr_id','demand_cr_to_inspection.id as demand_cr_to_inspection_id')
		                        ->whereNotNull('d44b_datas.status')
		                        ->orderBy('demand_po_to_cr.id', 'desc');
		if(!empty($cr_no)){
			$demands->where('demand_po_to_cr.cr_number','Like',"%$cr_no%");
		}
		if(!empty($from)){
			$demands->whereDate('demand_po_to_cr.item_receive_date','>=',$from);
		}
		if(!empty($todate)){
			$demands->whereDate('demand_po_to_cr.item_receive_date','<=',$todate);
		}

		$demands = $demands->paginate(10);

		return view('bill.pending')->with(compact('demands','demand_no','cr_no','from','todate'));
	}

	public function pendingCreate($tenderId,$tenderNumber,$poId,$crId,$inspectionId){

		$tenderInfo = Tender::find($tenderId);

		$budgetUniqueIds = array();

		$budgetCodeAll = BudgetCode::all();

		if (!empty($tenderInfo->budget_code) || $tenderInfo->budget_code != null){
			$budgetIds = json_decode($tenderInfo->budget_code);
			$budgetUniqueIds = array_unique($budgetIds);
		}



		return view('bill.pending-create',compact('tenderId','tenderNumber','budgetUniqueIds','budgetCodeAll','poId','crId','inspectionId'));
	}

	public function pendingStore(Request $request){

		$requestValidate = [
			'bill_forwarding_type' => 'required',
			'bill_forwarding_number' => 'required',
			'bill_forwarding_date' => 'required',
			'budget_code' => 'required',
			'bill_number' => 'required',
			'bill_date' => 'required',
		];

		if ($request->bill_forwarding_type == 5){
			$requestValidate['time_ext_app_no'] = "required";
			$requestValidate['time_ext_app_date'] = "required";
			$requestValidate['time_ext_up_to'] = "required";
		}

		if ($request->bill_forwarding_type == 7){
			$requestValidate['nssd_ltr_no'] = "required";
			$requestValidate['nssd_ltr_date'] = "required";
		}

		$requestValidateMessage = [
			'time_ext_app_no.required' => "Time Extension Number Is Required",
			'time_ext_app_date.required' => "Time Extension Date Is Required",
			'time_ext_up_to.required' => "Time Extension Up To Is Required",
			'nssd_ltr_no.required' => "NSSD Ltr No Is Required",
			'nssd_ltr_date.required' => "NSSD Ltr Date Is Required",
		];

		$validator = Validator::make($request->all(), $requestValidate,$requestValidateMessage);

		if ($validator->fails()) {
			return redirect()->back()
			                 ->withErrors($validator)
			                 ->withInput();
		}else{
			$billForwarding = new BillForwarding();
			$billForwarding->tender_id = $request->tender_id;
			$billForwarding->tender_number = $request->tender_number;
			$billForwarding->po_id = $request->po_id;
			$billForwarding->cr_id = $request->cr_id;
			$billForwarding->inspection_id = $request->inspection_id;
			$billForwarding->bill_forwarding_type = $request->bill_forwarding_type;
			$billForwarding->bill_forwarding_number = $request->bill_forwarding_number;
			$billForwarding->time_ext_app_no = !empty($request->time_ext_app_no) ? $request->time_ext_app_no : Null;
			$billForwarding->time_ext_app_date = !empty($request->time_ext_app_date) ? $request->time_ext_app_date : Null;
			$billForwarding->time_ext_up_to = !empty($request->time_ext_up_to) ? $request->time_ext_up_to : Null;
			$billForwarding->nssd_ltr_no = !empty($request->nssd_ltr_no) ? $request->nssd_ltr_no : Null;
			$billForwarding->nssd_ltr_date = !empty($request->nssd_ltr_date) ? $request->nssd_ltr_date : Null;
			$billForwarding->enclosure = $request->enclosure;
			$billForwarding->distribution = $request->distribution;
			$billForwarding->external = $request->external;
			$billForwarding->action = $request->action;
			$billForwarding->information = $request->information;
			$billForwarding->bill_forwarding_date = $request->bill_forwarding_date;
			$billForwarding->budget_code = json_encode($request->budget_code);
			$billForwarding->bill_number = $request->bill_number;
			$billForwarding->bill_date = $request->bill_date;
			$billForwarding->status = 1;

			if ($billForwarding->save()){
				session()->flash('success','Data store successfully');
				return Redirect::to('/bill/waiting-for-approved');
			}else{
				session()->flash('error','Data not store');
				return redirect()->back()->withInput();
			}
		}
	}

	public function waitingForApprovedIndex(){
		$cr_no = Input::get('cr_no');
		$from = Input::get('from');
		$todate = Input::get('todate');

		$this->tableAlies = \Session::get('zoneAlise');

		$cr_no = Input::get('cr_no');
		$demands = \App\D44BData::join('demand_cr_to_inspection', 'demand_cr_to_inspection.id', '=', 'd44b_datas.inspecttion_id')
		                        ->join('bill_forwarding', 'bill_forwarding.tender_id', '=', 'demand_cr_to_inspection.tender_id')
		                        ->leftJoin('demand_po_to_cr', 'demand_po_to_cr.id', '=', 'demand_cr_to_inspection.dmnd_po_to_cr_id')
		                        ->leftJoin('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
		                        ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
		                        ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
		                        ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
		                        ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
		                        ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
		                        ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title', 'd44b_datas.id', 'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','bill_forwarding.id as billId')
		                        ->whereNotNull('d44b_datas.status')
		                        ->where('bill_forwarding.status','=',1)
		                        ->orderBy('demand_po_to_cr.id', 'desc')
		                        ->orderBy('bill_forwarding.id', 'desc');

		if(!empty($cr_no)){
			$demands->where('demand_po_to_cr.cr_number','Like',"%$cr_no%");
		}
		if(!empty($from)){
			$demands->whereDate('demand_po_to_cr.item_receive_date','>=',$from);
		}
		if(!empty($todate)){
			$demands->whereDate('demand_po_to_cr.item_receive_date','<=',$todate);
		}

		$demands = $demands->paginate(10);

		return view('bill.waiting-for-approved')->with(compact('demands','demand_no','cr_no','from','todate'));
	}

	public function ApprovedCreate($id){
		$billForwarding = BillForwarding::find($id);

		$budgetUniqueIds = array();

		$budgetCodeAll = BudgetCode::all();
		$budgetIds = '';

		if (!empty($billForwarding->budget_code) || $billForwarding->budget_code != null){
			$budgetIds =  json_decode($billForwarding->budget_code);
			$budgetUniqueIds = array_unique($budgetIds);
		}

		return view('bill.approve-create',compact('billForwarding','budgetUniqueIds','budgetCodeAll'));
	}

	public function ApprovedStore(Request $request){
		$requestValidate = [
			'bill_forwarding_type' => 'required',
			'bill_forwarding_number' => 'required',
			'bill_forwarding_date' => 'required',
			'budget_code' => 'required',
			'bill_number' => 'required',
			'bill_date' => 'required',
		];

		if ($request->bill_forwarding_type == 5){
			$requestValidate['time_ext_app_no'] = "required";
			$requestValidate['time_ext_app_date'] = "required";
			$requestValidate['time_ext_up_to'] = "required";
		}

		if ($request->bill_forwarding_type == 7){
			$requestValidate['nssd_ltr_no'] = "required";
			$requestValidate['nssd_ltr_date'] = "required";
		}

		$requestValidateMessage = [
			'time_ext_app_no.required' => "Time Extension Number Is Required",
			'time_ext_app_date.required' => "Time Extension Date Is Required",
			'time_ext_up_to.required' => "Time Extension Up To Is Required",
			'nssd_ltr_no.required' => "NSSD Ltr No Is Required",
			'nssd_ltr_date.required' => "NSSD Ltr Date Is Required",
		];

		$validator = Validator::make($request->all(), $requestValidate,$requestValidateMessage);


		if ($validator->fails()) {
			return redirect()->back()
			                 ->withErrors($validator)
			                 ->withInput();
		}else{
			$billForwarding = BillForwarding::find($request->id);
			$billForwarding->bill_forwarding_type = $request->bill_forwarding_type;
			$billForwarding->bill_forwarding_number = $request->bill_forwarding_number;
			$billForwarding->time_ext_app_no = !empty($request->time_ext_app_no) ? $request->time_ext_app_no : Null;
			$billForwarding->time_ext_app_date = !empty($request->time_ext_app_date) ? $request->time_ext_app_date : Null;
			$billForwarding->time_ext_up_to = !empty($request->time_ext_up_to) ? $request->time_ext_up_to : Null;
			$billForwarding->nssd_ltr_no = !empty($request->nssd_ltr_no) ? $request->nssd_ltr_no : Null;
			$billForwarding->nssd_ltr_date = !empty($request->nssd_ltr_date) ? $request->nssd_ltr_date : Null;
			$billForwarding->enclosure = $request->enclosure;
			$billForwarding->distribution = $request->distribution;
			$billForwarding->external = $request->external;
			$billForwarding->action = $request->action;
			$billForwarding->information = $request->information;
			$billForwarding->bill_forwarding_date = $request->bill_forwarding_date;
			$billForwarding->budget_code = json_encode($request->budget_code);
			$billForwarding->bill_number = $request->bill_number;
			$billForwarding->bill_date = $request->bill_date;
			$billForwarding->status = 2;

			if ($billForwarding->save()){
				session::flash('success','Data store successfully');
				return redirect('/bill/approved');
			}else{
				session()->flash('error','Data not store');
				return redirect()->back()->withInput();
			}
		}
	}

	public function approvedIndex(){
		$cr_no = Input::get('cr_no');
		$from = Input::get('from');
		$todate = Input::get('todate');

		$this->tableAlies = \Session::get('zoneAlise');

		$cr_no = Input::get('cr_no');
		$demands = \App\D44BData::join('demand_cr_to_inspection', 'demand_cr_to_inspection.id', '=', 'd44b_datas.inspecttion_id')
		                        ->join('bill_forwarding', 'bill_forwarding.tender_id', '=', 'demand_cr_to_inspection.tender_id')
		                        ->leftJoin('demand_po_to_cr', 'demand_po_to_cr.id', '=', 'demand_cr_to_inspection.dmnd_po_to_cr_id')
		                        ->leftJoin('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
		                        ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
		                        ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
		                        ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
		                        ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
		                        ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
		                        ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title', 'd44b_datas.id', 'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','bill_forwarding.id as billId')
		                        ->whereNotNull('d44b_datas.status')
		                        ->where('bill_forwarding.status','=',2)
		                        ->orderBy('demand_po_to_cr.id', 'desc')
		                        ->orderBy('bill_forwarding.id', 'desc');

		if(!empty($cr_no)){
			$demands->where('demand_po_to_cr.cr_number','Like',"%$cr_no%");
		}
		if(!empty($from)){
			$demands->whereDate('demand_po_to_cr.item_receive_date','>=',$from);
		}
		if(!empty($todate)){
			$demands->whereDate('demand_po_to_cr.item_receive_date','<=',$todate);
		}

		$demands = $demands->paginate(10);

		return view('bill.approved')->with(compact('demands','demand_no','cr_no','from','todate'));
	}

	public function print($id){
		$billForwarding = BillForwarding::find($id);

		$nsdId = 1;
		if(!empty(Auth::user()->nsd_bsd)){
			$nsdId = Auth::user()->nsd_bsd;
		}
		$orgInfo  = \App\NsdName::find($nsdId);

		$approverName = "";

		if ($billForwarding->approved_by){
			$approverName = User::findOrFail($billForwarding->approved_by);
		}

		$tenderInfo = Tender::find($billForwarding->tender_id);

		$podata = PoDatas::find($billForwarding->po_id);

		$demandToColQut = DemandToCollectionQuotation::select('suppliernametext','total')->find($podata->selected_supplier);

		$budgetCodeS = '';

		if (!empty($billForwarding->budget_code) || $billForwarding->budget_code != null){
			$budgetIds =  json_decode($billForwarding->budget_code);
			$budgetUniqueIds = array_unique($budgetIds);
			$budgetCodeS = \App\BudgetCode::select('code','description')->find($budgetUniqueIds);
		}

		$currentYear  = ( date('m') > 6) ? date('Y').'-'.(date('Y') + 1) : (date('Y') - 1).'-'.date('Y');

		$deliveryDays = !empty($tenderInfo->delivery_date) ? (int) $tenderInfo->delivery_date : 0;
		$deliveryDate = strtotime($podata->top_date. ' + '.$deliveryDays.' days');



		$cr = DemandPoToCr::find($billForwarding->cr_id);
		$deliveredOn = strtotime($cr->item_receive_date);

		$delayDelivery = "Not Applicable";

		if (!empty($deliveryDate) && !empty($deliveredOn) && $deliveredOn > $deliveryDate){

			$delayDelivery = round(abs($deliveredOn - $deliveryDate)/86400)." Days";
		}

		$inspection = DemandCrToInspection::find($billForwarding->inspection_id);
		$acceptanceDate = !empty($inspection->approve_date) ? date("d F Y",strtotime($inspection->approve_date)) : "";
		$data = [
			'orgInfo' => $orgInfo,
			'approverName' => $approverName,
			'billForwarding' => $billForwarding,
			'tenderInfo' => $tenderInfo,
			'podata' => $podata,
			'demandToColQut' => $demandToColQut,
			'budgetCodeS' => $budgetCodeS,
			'currentYear' => $currentYear,
			'deliveryDate' => $deliveryDate,
			'deliveredOn' => $deliveredOn,
			'acceptanceDate' => $acceptanceDate,
			'delayDelivery' => $delayDelivery,
		];

		if ($billForwarding->bill_forwarding_type == 1)
		{
			$pdf= PDF::loadView('bill.financial-sanction',$data,[],['format' => [215.9, 342.9]]);
			return $pdf->stream('Bill Forwarding Financial-Sanction-'.$billForwarding->tender_number.'-'.date('Y-m-d h:i:s').'.pdf');
		}
		elseif ($billForwarding->bill_forwarding_type == 2)
		{
			$pdf= PDF::loadView('bill.financial-sanction-nssd',$data,[],['format' => [215.9, 342.9]]);
			return $pdf->stream('Bill Forwarding With Financial-Sanction of NSSD-'.$billForwarding->tender_number.'-'.date('Y-m-d h:i:s').'.pdf');
		}
		elseif ($billForwarding->bill_forwarding_type == 3)
		{
			$pdf= PDF::loadView('bill.without-financial-sanction',$data,[],['format' => [215.9, 342.9]]);
			return $pdf->stream('Bill Forwarding Without Financial-Sanction-'.$billForwarding->tender_number.'-'.date('Y-m-d h:i:s').'.pdf');
		}
		elseif ($billForwarding->bill_forwarding_type == 4)
		{
			$pdf= PDF::loadView('bill.financial-sanction-nhq',$data,[],['format' => [215.9, 342.9]]);
			return $pdf->stream('Bill Forwarding Wit Financial-Sanction NHQ-'.$billForwarding->tender_number.'-'.date('Y-m-d h:i:s').'.pdf');
		}
		elseif ($billForwarding->bill_forwarding_type == 5)
		{
			$pdf= PDF::loadView('bill.with-time-extension-application',$data,[],['format' => [215.9, 342.9]]);
			return $pdf->stream('Bill Forwarding With Time Extension Application -'.$billForwarding->tender_number.'-'.date('Y-m-d h:i:s').'.pdf');
		}
		elseif ($billForwarding->bill_forwarding_type == 6)
		{
			$pdf= PDF::loadView('bill.with-late-delivery',$data,[],['format' => [215.9, 342.9]]);
			return $pdf->stream('Bill Forwarding with-late-delivery -'.$billForwarding->tender_number.'-'.date('Y-m-d h:i:s').'.pdf');
		}
		else
		{
			$pdf= PDF::loadView('bill.with-time-extension-send-nhq',$data,[],['format' => [215.9, 342.9]]);
			return $pdf->stream('Bill Forwarding with-time-extension-send-nhq -'.$billForwarding->tender_number.'-'.date('Y-m-d h:i:s').'.pdf');
		}
	}

	public function arrayToString($value){
		$givenString = implode(', ',$value);
		$search = ',';
		$replace = ' and';
		return  strrev(implode(strrev($replace), explode(strrev($search), strrev($givenString), 2)));
	}
}
