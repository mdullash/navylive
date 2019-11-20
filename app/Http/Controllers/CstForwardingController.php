<?php

namespace App\Http\Controllers;

use App\CstForwarding;
use App\LotNames;
use App\Tender;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use PDF;

class CstForwardingController extends Controller
{

    private $moduleId = 15;

    public function pendingIndex(){

    	$demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
                    ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name')
                    ->whereNotNull('demand_to_tender.cst_draft_status')
                    ->where('demand_to_tender.lp_section_status','=',2)
                    ->where(function($query) {
                        $query->where('demand_to_tender.tender_status','=',1);
                        $query->orWhereNull('demand_to_tender.tender_status');
                    })
                    ->where(function($query) {
                        $query->where('demand_to_tender.head_ofc_apvl_status','=',2);
                        $query->orWhere('demand_to_tender.head_ofc_apvl_status','=',3);
                    })
                    ->orderBy('demand_to_lpr.id', 'desc');
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
                    }
                    if(!empty($from)){
                        $demands->whereDate('demand_to_lpr.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->whereDate('demand_to_lpr.when_needed','<=',$todate);
                    }

                $demands = $demands->paginate(10);

    	return view('cst-forwarding.pending',compact('demands'));
    }

    public function pendingCreate($tenderId,$tenderNumber){
		return view('cst-forwarding.pending-create',compact('tenderId','tenderNumber'));
    }

    public function pendingStore(Request $request){

	    $validator = Validator::make($request->all(), [
		    'cst_forwarding_type' => 'required',
		    'cst_forwarding_number' => 'required',
		    'cst_forwarding_date' => 'required',
	    ]);

	    if ($validator->fails()) {
		    return redirect()->back()
			    ->withErrors($validator)
			    ->withInput();
	    }else{
		    $cstForwarding = new CstForwarding();
		    $cstForwarding->tender_id = $request->tender_id;
		    $cstForwarding->tender_number = $request->tender_number;
		    $cstForwarding->cst_forwarding_type = $request->cst_forwarding_type;
		    $cstForwarding->cst_forwarding_number = $request->cst_forwarding_number;
		    $cstForwarding->enclosure = $request->enclosure;
		    $cstForwarding->distribution = $request->distribution;
		    $cstForwarding->external = $request->external;
		    $cstForwarding->action = $request->action;
		    $cstForwarding->information = $request->information;
		    $cstForwarding->cst_forwarding_date = $request->cst_forwarding_date;
		    $cstForwarding->status = 1;

		    if ($cstForwarding->save()){
			    session()->flash('success','Data store successfully');
			    return Redirect::to('/cst-forwarding/waiting-for-approved');
		    }else{
		    	session()->flash('error','Data not store');
			    return redirect()->back()->withInput();
		    }
	    }
    }

    public function waitingForApprovedIndex(){
	    $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
		                              ->join('cst_forwarding', 'cst_forwarding.tender_id', '=', 'demand_to_tender.tender_id')
	                                  ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
	                                  ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','cst_forwarding.id as cstId')
	                                  ->whereNotNull('demand_to_tender.cst_draft_status')
	                                  ->where('demand_to_tender.lp_section_status','=',2)
	                                  ->where('cst_forwarding.status','=',1)
	                                  ->where(function($query) {
		                                  $query->where('demand_to_tender.tender_status','=',1);
		                                  $query->orWhereNull('demand_to_tender.tender_status');
	                                  })
	                                  ->where(function($query) {
		                                  $query->where('demand_to_tender.head_ofc_apvl_status','=',2);
		                                  $query->orWhere('demand_to_tender.head_ofc_apvl_status','=',3);
	                                  })
	                                  ->orderBy('cst_forwarding.id', 'desc');
	    if(!empty($tender_no)){
		    $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
	    }
	    if(!empty($from)){
		    $demands->whereDate('demand_to_lpr.when_needed','=<',$from);
	    }
	    if(!empty($todate)){
		    $demands->whereDate('demand_to_lpr.when_needed','<=',$todate);
	    }

	    $demands = $demands->paginate(10);

    	return view('cst-forwarding.waiting-for-approved',compact('demands'));
    }

    public function ApprovedCreate($id){
    	$cstForwarding = CstForwarding::find($id);
	    return view('cst-forwarding.approve-create',compact('cstForwarding'));
    }

    public function ApprovedStore(Request $request){
	    $validator = Validator::make($request->all(), [
		    'cst_forwarding_type' => 'required',
		    'cst_forwarding_number' => 'required',
		    'cst_forwarding_date' => 'required',
	    ]);

	    if ($validator->fails()) {
		    return redirect()->back()
		                     ->withErrors($validator)
		                     ->withInput();
	    }else{
		    $cstForwarding = CstForwarding::find($request->id);
		    $cstForwarding->cst_forwarding_type = $request->cst_forwarding_type;
		    $cstForwarding->cst_forwarding_number = $request->cst_forwarding_number;
		    $cstForwarding->enclosure = $request->enclosure;
		    $cstForwarding->distribution = $request->distribution;
		    $cstForwarding->external = $request->external;
		    $cstForwarding->action = $request->action;
		    $cstForwarding->information = $request->information;
		    $cstForwarding->cst_forwarding_date = $request->cst_forwarding_date;
		    $cstForwarding->status = 2;

		    if ($cstForwarding->save()){
			    session::flash('success','Data store successfully');
			    return redirect('/cst-forwarding/approved');
		    }else{
			    session()->flash('error','Data not store');
			    return redirect()->back()->withInput();
		    }
	    }
    }

    public function approvedIndex(){
	    $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
	                                  ->join('cst_forwarding', 'cst_forwarding.tender_id', '=', 'demand_to_tender.tender_id')
	                                  ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
	                                  ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','cst_forwarding.id as cstId')
	                                  ->whereNotNull('demand_to_tender.cst_draft_status')
	                                  ->where('demand_to_tender.lp_section_status','=',2)
	                                  ->where('cst_forwarding.status','=',2)
	                                  ->where(function($query) {
		                                  $query->where('demand_to_tender.tender_status','=',1);
		                                  $query->orWhereNull('demand_to_tender.tender_status');
	                                  })
	                                  ->where(function($query) {
		                                  $query->where('demand_to_tender.head_ofc_apvl_status','=',2);
		                                  $query->orWhere('demand_to_tender.head_ofc_apvl_status','=',3);
	                                  })
	                                  ->orderBy('cst_forwarding.id', 'desc');
	    if(!empty($tender_no)){
		    $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
	    }
	    if(!empty($from)){
		    $demands->whereDate('demand_to_lpr.when_needed','=<',$from);
	    }
	    if(!empty($todate)){
		    $demands->whereDate('demand_to_lpr.when_needed','<=',$todate);
	    }

	    $demands = $demands->paginate(10);

    	return view('cst-forwarding.approved',compact('demands'));
    }

    public function print($id){
    	$cstForwarding = CstForwarding::find($id);

	    $nsdId = 1;
	    if(!empty(Auth::user()->nsd_bsd)){
		    $nsdId = Auth::user()->nsd_bsd;
	    }
	    $orgInfo  = \App\NsdName::find($nsdId);

	    $approverName = "";

	    if ($cstForwarding->approved_by){
		    $approverName = User::findOrFail($cstForwarding->approved_by);
	    }

	    $tenderInfo = Tender::find($cstForwarding->tender_id);

	    $qutationTender = \App\DemandToCollectionQuotation::where('tender_id','=',$cstForwarding->tender_id)->get()->toArray();
	    $qutationTenderCount = count($qutationTender);
	    $qutationTenderWinner = \App\DemandToCollectionQuotation::select('tender_id','suppliernametext','winner')->where('tender_id','=',$cstForwarding->tender_id)->where('winner','=',1)->get()->toArray();

	    $NotSelectAsDraft = \App\DemandToCollectionQuotation::select('tender_id','suppliernametext','winner')->where('tender_id','=',$cstForwarding->tender_id)->whereNull('sel_as_draft_cst')->get()->toArray();
	    $NotSelectAsDraftCount = count($NotSelectAsDraft);
	    $supplierName =  $this->arrayToString(array_column($qutationTender,'suppliernametext'));

	    $NotSelectAsDraftSupplier = $this->arrayToString(array_column($NotSelectAsDraft,'suppliernametext'));

	    $data = [
		    'orgInfo' => $orgInfo,
		    'approverName' => $approverName,
		    'cstForwarding' => $cstForwarding,
		    'tenderInfo' => $tenderInfo,
		    'qutationTender' => $qutationTender,
		    'qutationTenderCount' => $qutationTenderCount,
		    'NotSelectAsDraftCount' => $NotSelectAsDraftCount,
		    'qutationTenderWinner' => $qutationTenderWinner,
		    'supplierName' => $supplierName,
		    'NotSelectAsDraftSupplier' => $NotSelectAsDraftSupplier,
	    ];

    	if ($cstForwarding->cst_forwarding_type == 1){
		    $pdf= PDF::loadView('cst-forwarding.line-item-print',$data,[],['format' => [215.9, 342.9]]);
		    return $pdf->stream('CST Forwarding Line Item'.date('Y-m-d h:i:s').'.pdf');
	    }elseif($cstForwarding->cst_forwarding_type == 2){

    		$lot = LotNames::where('tender_id','=',$cstForwarding->tender_id)->get();
		    $lotCount = count($lot);

		    $data['lotCount'] = $lotCount;

		    $pdf= PDF::loadView('cst-forwarding.lot-item-print',$data,[],['format' => [215.9, 342.9]]);
		    return $pdf->stream('CST Forwarding Lot Item'.date('Y-m-d h:i:s').'.pdf');
	    }else{



		    $pdf= PDF::loadView('cst-forwarding.single-quotation-print',$data,[],['format' => [215.9, 342.9]]);
		    return $pdf->stream('CST Forwarding Single Quotation'.date('Y-m-d h:i:s').'.pdf');
	    }
    }

	public function arrayToString($value){
		$givenString = implode(', ',$value);
		$search = ',';
		$replace = ' and';
		return  strrev(implode(strrev($replace), explode(strrev($search), strrev($givenString), 2)));
	}
}
