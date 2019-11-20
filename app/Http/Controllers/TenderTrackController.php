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
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;

class TenderTrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {



        // Search parms =========================================
        $demande    = Input::get('requester');
        $demand_no  = Input::get('demand_no');
        $tender_no  = Input::get('tender_no');
        $from       = Input::get('from');
        $todate     = Input::get('todate');

        $catGrou    = Auth::user()->categories_id;
        $demandIdAcToCat  = array();
        $demandToLprIdAcToCat  = array();
        if(!empty($catGrou)){
            $catGrou    = explode(',',$catGrou);
            $demandIdAcToCat  = array_unique(array_map('current',\App\ItemToDemand::select('demand_id')->whereIn('group_name',$catGrou)->get()->toArray()));
            $demandToLprIdAcToCat  = array_unique(array_map('current',\App\ItemToDemand::select('lpr_id')->whereIn('group_name',$catGrou)->get()->toArray()));
        }
        $demandDetailPageFromRoute=[];

//        floating-tender-acc'

        //pending

        $demandIds = array_map('current',\App\ItemToDemand::select('lpr_id')
            ->where('tender_floating','=',NULL)
            ->where('group_status','=',2)
            ->where('current_status','!=',101)
            ->whereNull('float_tender_app_status')
            ->where(function($q) {
                $q->whereNull('transfer_to');
                $q->orWhereNotNull('transfer_to');
            })
            ->where(function($qu) {
                $qu->whereNull('transfer_status');
                $qu->orWhere('transfer_status','=',2);
            })
            ->where(function($que) {
                $que->whereNull('plr_status');
                $que->orWhere('plr_status','=',3);
            })
            ->get()->toArray());

        $demands = \App\DemandToLpr::leftJoin('demand_to_tender', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->select('demand_to_lpr.*','demand_to_tender.tender_number')
            ->whereIn('demand_to_lpr.id',$demandIds)
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
          
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='floating-tender-acc';
            $a->tender_stage=' Tender';
            $a->stage='Pending';
        }

        $demandDetailPageFromRoute['floating-tender-acc']['pending']=$demands;

        //approve
        $demandIds = array_map('current',\App\ItemToDemand::select('lpr_id')->whereNotNull('tender_floating')->get()->toArray());

        $demands = \App\DemandToLpr::join('demand_to_tender', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->select('demand_to_lpr.*','demand_to_tender.tender_number','demand_to_tender.total_quantity as total_unit','demand_to_tender.tender_id as tenderId')
            ->where('demand_to_tender.float_tender_app_status','=',1)
            ->whereIn('demand_to_lpr.id',$demandIds)
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demands.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
        
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_stage=' Tender';
            $a->tender_positon_stage='floating-tender-acc';
            $a->stage='Approved';
        }
        $demandDetailPageFromRoute['floating-tender-acc']['approved']=$demands;


//            waiting_for_approve

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->whereNotNull('demand_to_tender.tender_floating')
            ->whereNull('demand_to_tender.float_tender_app_status')
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='floating-tender-acc';
            $a->tender_stage=' Tender';
            $a->stage='Waiting For Approved';
        }
        $demandDetailPageFromRoute['floating-tender-acc']['waiting_for_approved']=$demands;




//            retender-view-acc
//            pending

        $demands = \App\Retender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'retender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','retender.tender_id as tenderId','retender.tender_number','demande_name.name as demande_name','retender.retenderQty as total_unit')
            ->where('retender.already_published','=',NULL)
            ->orderBy('demand_to_lpr.id', 'desc');

        if(!empty($catGrou)){
            $demands->whereIn('demands.id',$demandIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            // $demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('retender.tender_number','Like',"%$tender_no%");
        }
        
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        
        $demands = $demands->get();
        
       
        foreach ($demands as $a){
            $a->tender_positon_stage='retender-view-acc';
            $a->tender_stage='Re-Tender';
            $a->stage='Pending';
        }
        $demandDetailPageFromRoute['retender-view-acc']['pending']=$demands;


        //pending
        $demands = \App\NilReturn::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'nil_return.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','nil_return.tender_id as tenderId','nil_return.tender_number','demande_name.name as demande_name','nil_return.id as nil_id','nil_return.nil_return_qty as total_unit')
            ->where('nil_return.already_published','=',NULL)
            ->where('nil_return.status','=',1)
            ->orderBy('demand_to_lpr.id', 'desc');

        if(!empty($catGrou)){
            $demands->whereIn('demands.id',$demandIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            // $demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
        if(!empty($tender_no)){
            // $demands->whereRaw("find_in_set('".$tender_no."',retender.tender_number)");
            $demands->where('nil_return.tender_number','Like',"%$tender_no%");
        }


        if(!empty($from)){
            //$demands->whereBetween('demand_to_lpr.when_needed','=<',$from);
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);

        }
        $demands = $demands->get(); //dd($demands);

        foreach ($demands as $a){
            $a->tender_positon_stage='nil-return';
            $a->tender_stage='Nill-Return';
            $a->stage='Pending';
        }
        $demandDetailPageFromRoute['nil-return']['pending']=$demands;


//           approved
        $demands = \App\NilReturn::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'nil_return.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','nil_return.tender_id as tenderId','nil_return.tender_number','demande_name.name as demande_name','nil_return.id as nil_id','nil_return.nil_return_qty as total_unit')
            ->where('nil_return.already_published','=',NULL)
            ->where('nil_return.status','=',2)
            ->orderBy('demand_to_lpr.id', 'desc');


        if(!empty($catGrou)){
            $demands->whereIn('demands.id',$demandIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            // $demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
        if(!empty($tender_no)){
            // $demands->whereRaw("find_in_set('".$tender_no."',retender.tender_number)");
            $demands->where('nil_return.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            //$demands->whereBetween('demand_to_lpr.when_needed','=<',$from);
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);

        }
        $demands = $demands->get(); //dd($demands);
        foreach ($demands as $a){
            $a->tender_positon_stage='nil-return';

            $a->tender_stage='Nill-Return';
            $a->stage='Approved';
        }
        $demandDetailPageFromRoute['nil-return']['approved']=$demands;

//            waiting for approve
        $demands = \App\NilReturn::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'nil_return.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','nil_return.tender_id as tenderId','nil_return.tender_number','demande_name.name as demande_name','nil_return.id as nil_id','nil_return.nil_return_qty as total_unit')
            ->where('nil_return.already_published','=',NULL)
            ->where('nil_return.status','=',3)
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demands.id',$demandIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            // $demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
        if(!empty($tender_no)){
            // $demands->whereRaw("find_in_set('".$tender_no."',retender.tender_number)");
            $demands->where('nil_return.tender_number','Like',"%$tender_no%");
        }


        if(!empty($from)){
            //$demands->whereBetween('demand_to_lpr.when_needed','=<',$from);
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);

        }
        $demands = $demands->get(); //dd($demands);
        foreach ($demands as $a){

            $a->tender_positon_stage='nil-return';
            $a->tender_stage='Nill-Return';
            $a->stage='Waiting For Approved';
        }
        $demandDetailPageFromRoute['nil-return']['waiting-for-approved']=$demands;


        //pending

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->where('demand_to_tender.float_tender_app_status','=',1)
            //->where('demand_to_tender.current_status','=',1)
            ->whereNull('demand_to_tender.tender_quation_collection')
            ->where(function($q) {
                $q->whereNull('demand_to_tender.tender_status');
                $q->orWhere('demand_to_tender.tender_status','=',1);
            })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
            // $demands->where('demand_to_tender.tender_number','=',$tender_no);
        }
        if(!empty($from)){
            
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);

        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='collection-quotation-acc';

            $a->tender_stage='Collection Quotation';
            $a->stage='Pending';
        }
        $demandDetailPageFromRoute['collection-quotation-acc']['pending']=$demands;

//            Approved
//                $demands = Demand::where('tender_quation_collection','!=',NULL)->orderBy('id', 'desc')->get();

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->where('demand_to_tender.float_tender_app_status','=',1)
            ->where('demand_to_tender.coll_quat_app_status','=',1)
            ->whereNotNull('demand_to_tender.tender_quation_collection')
            ->where(function($q) {
                $q->whereNull('demand_to_tender.tender_status');
                $q->orWhere('demand_to_tender.tender_status','=',1);
            })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            
        }
        if(!empty($from)&&!empty($todate)){

            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);

        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='collection-quotation-acc';

            $a->tender_stage='Collection Quotation';
            $a->stage='Approved';
        }
        $demandDetailPageFromRoute['collection-quotation-acc']['approved']=$demands;

//          waiting-for-appproved
        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->where('demand_to_tender.float_tender_app_status','=',1)
            ->whereNotNull('demand_to_tender.tender_quation_collection')
            ->whereNull('demand_to_tender.coll_quat_app_status')
            ->where(function($q) {
                $q->whereNull('demand_to_tender.tender_status');
                $q->orWhere('demand_to_tender.tender_status','=',1);
            })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            
        }
        if(!empty($from)&&!empty($todate)){

            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);

        }
        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='collection-quotation-acc';
            $a->tender_stage='Waiting For Approved';
            $a->stage='Pending';
        }
        $demandDetailPageFromRoute['collection-quotation-acc']['waiting-for-approved']=$demands;



//            cst-view-acc
//          pending

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->where('demand_to_tender.coll_quat_app_status','=',1)
            //->where('demand_to_tender.current_status','=',1)
            ->whereNull('demand_to_tender.cst_pending_sta')
            ->whereNull('demand_to_tender.first_cst_app_status')
            ->whereNotNull('demand_to_tender.tender_quation_collection')
            ->where(function($q) {
                $q->whereNull('demand_to_tender.tender_status');
                $q->orWhere('demand_to_tender.tender_status','=',1);
            })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            
        }
        if(!empty($from)&&!empty($todate)){

            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);

        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='cst-view-acc';

            $a->tender_stage='CST';
            $a->stage='Pending';
        }

        $demandDetailPageFromRoute['cst-view-acc']['pending']=$demands;


//          First Member

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->where('demand_to_tender.cst_pending_sta','=',1)
            //->where('demand_to_tender.current_status','=',1)
            ->whereNull('demand_to_tender.first_cst_app_status')
            ->whereNotNull('demand_to_tender.cst_pending_sta')
            ->whereNotNull('demand_to_tender.tender_quation_collection')
            ->where(function($q) {
                $q->whereNull('demand_to_tender.tender_status');
                $q->orWhere('demand_to_tender.tender_status','=',1);
            })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            
        }
        if(!empty($from)&&!empty($todate)){

            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);

        }
        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='cst-view-acc';
            $a->tender_stage='CST';
            $a->stage='First Member';
        }
        $demandDetailPageFromRoute['cst-view-acc']['first_member']=$demands;

        //          Second Member

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->where('demand_to_tender.coll_quat_app_status','=',1)
            //->where('demand_to_tender.current_status','=',1)
            ->whereNull('demand_to_tender.second_cst_app_status')
            ->whereNotNull('demand_to_tender.first_cst_app_status')
            ->where(function($q) {
                $q->whereNull('demand_to_tender.tender_status');
                $q->orWhere('demand_to_tender.tender_status','=',1);
            })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);


        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='cst-view-acc';

            $a->tender_stage='CST';
            $a->stage='Second Member';
        }
        $demandDetailPageFromRoute['cst-view-acc']['second_member']=$demands;


//                 President Member

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->where('demand_to_tender.coll_quat_app_status','=',1)
            //->where('demand_to_tender.current_status','=',1)
            ->whereNull('demand_to_tender.cst_draft_status')
            ->whereNotNull('demand_to_tender.second_cst_app_status')
            ->where(function($q) {
                $q->whereNull('demand_to_tender.tender_status');
                $q->orWhere('demand_to_tender.tender_status','=',1);
            })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='cst-view-acc';

            $a->tender_stage='CST';
            $a->stage='President Member';
        }
        $demandDetailPageFromRoute['cst-view-acc']['president_member']=$demands;

//           Approved

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->whereNotNull('demand_to_tender.cst_draft_status')
            ->where(function($q) {
                $q->whereNull('demand_to_tender.tender_status');
                $q->orWhere('demand_to_tender.tender_status','=',1);
            })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='cst-view-acc';

            $a->tender_stage='CST';
            $a->stage='Approved';
        }
        $demandDetailPageFromRoute['cst-view-acc']['approved']=$demands;




//           Pending
        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->where('demand_to_tender.send_to_nhq','=',1)
            ->whereNull('demand_to_tender.cst_supplier_select')
            ->whereNotNull('demand_to_tender.cst_draft_status')
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='draft-cst-view-acc';

            $a->tender_stage='Draft CST';
            $a->stage='Pending';
        }
        $demandDetailPageFromRoute['draft-cst-view-acc']['pending']=$demands;

//             Approved

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            //->whereNull('demand_to_tender.cst_supplier_select')
            ->whereNotNull('demand_to_tender.cst_supplier_select')
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='draft-cst-view-acc';

            $a->tender_stage='Draft CST';
            $a->stage='Approved';
        }
        $demandDetailPageFromRoute['draft-cst-view-acc']['approved']=$demands;

//            Pending

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
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='hdq-approval-acc';

            $a->tender_stage='HDQ Approval';
            $a->stage='Pending';
        }

        $demandDetailPageFromRoute['hdq-approval-acc']['pending']=$demands;


//            Approvred
        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name')
            ->whereNotNull('demand_to_tender.cst_draft_status')
            ->where('demand_to_tender.head_ofc_apvl_status','=',1)
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='hdq-approval-acc';

            $a->tender_stage='HDQ Approval';
            $a->stage='Approved';
        }
        $demandDetailPageFromRoute['hdq-approval-acc']['approved']=$demands;



        $po_no = Input::get('po_no');
//           pending
        $toShowInPo = array_map('current',\App\DemandToCollectionQuotation::select('tender_id')->whereNotNull('winner')->whereNull('po_status')->get()->toArray());
        if(!empty($toShowInPo)){
            $toShowInPo = array_unique($toShowInPo);
        }
        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name')
            ->whereIn('demand_to_tender.tender_id',$toShowInPo)
            // ->whereNotNull('demand_to_tender.cst_supplier_select')
            // ->where(function($query) {
            //     $query->whereNotNull('demand_to_tender.cst_supplier_select');
            //     $query->orWhere('demand_to_tender.head_ofc_apvl_status','=',1);
            // })
            // ->where('demand_to_tender.lp_section_status','=',1)
            // ->whereNull('demand_to_tender.po_status')
            // ->where(function($query) {
            //     $query->where('demand_to_tender.head_ofc_apvl_status','=',1);
            //     $query->orWhere('demand_to_tender.head_ofc_apvl_status','=',null);
            // })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='po-generation-acc';

            $a->tender_stage='PO Generation';
            $a->stage='Pending';
        }

        $demandDetailPageFromRoute['po-generation-acc']['pending']=$demands;

//
//            Waiting-for-Check
        $demands = \App\PoDatas::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'po_datas.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('po_datas.*','demand_to_lpr.*')
            ->whereNull('po_datas.po_check_status')
            ->orderBy('po_datas.id', 'desc');
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }

        $demands = $demands->get();

        foreach ($demands as $a){
            $a->tender_positon_stage='po-generation-acc';

            $a->tender_stage='PO Generation';
            $a->stage='Waiting For Check';
        }
        $demandDetailPageFromRoute['po-generation-acc']['waiting-for-check']=$demands;


        //            Waiting-for-Approve

        $demands = \App\PoDatas::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'po_datas.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('po_datas.*','demand_to_lpr.*')
            ->where('po_datas.po_check_status','=',1)
            ->whereNull('po_datas.po_approve_status')
            ->orderBy('po_datas.id', 'desc');
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }

        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='po-generation-acc';

            $a->tender_stage='PO Generation';
            $a->stage='Waiting For Approved';
        }
        $demandDetailPageFromRoute['po-generation-acc']['waiting-for-approve']=$demands;

//
//            Approved

        $demands = \App\PoDatas::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'po_datas.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('po_datas.*','demand_to_lpr.*')
            ->where('po_datas.po_approve_status','=',1)
            //->whereNull('po_datas.po_approve_status')
            ->orderBy('po_datas.id', 'desc');

        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }

        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='po-generation-acc';

            $a->tender_stage='PO Generation';
            $a->stage='Approved';
        }

        $demandDetailPageFromRoute['po-generation-acc']['approved']=$demands;


        $this->tableAlies = \Session::get('zoneAlise');
//           pending
        $po_no = Input::get('po_no');
        $demands = \App\PoDatas::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'po_datas.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
            ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
            ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
            ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title','demand_to_lpr.*')
            ->where('po_datas.po_approve_status','=',1)
            ->where(\DB::raw("CONVERT(po_datas.inspection_qty, DECIMAL)"), '<',\DB::raw("CONVERT(po_datas.quantity, DECIMAL)"))
            //->where('po_datas.inspection_qty','<','po_datas.quantity')
            //->whereNull('po_datas.po_approve_status')
            ->orderBy('po_datas.id', 'desc');
        if(!empty($po_no)){
            $demands->where('po_datas.po_number','Like',"%$po_no%");
        }

        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }

        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }

        if(!empty($from)){
           // $demands->whereBetween('po_datas.top_date','>=',$from);
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('po_datas.top_date',[$from,$todate]);
        }
        $demands = $demands->get();

        foreach ($demands as $a){
            $a->tender_positon_stage='cr-view-acc';

            $a->tender_stage='CR View';
            $a->stage='Pending';
        }
        $demandDetailPageFromRoute['cr-view-acc']['pending']=$demands;

//           approved

        $cr_no = Input::get('cr_no');
        $demands = \App\DemandPoToCr::join('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
            ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
            ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
            ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
            ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title', 'demand_po_to_cr.id', 'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','demand_to_lpr.*')
            ->whereNull('demand_po_to_cr.status')
            ->orderBy('demand_po_to_cr.id', 'desc');
        if(!empty($cr_no)){
            $demands->where('demand_po_to_cr.cr_number','Like',"%$cr_no%");
        }

        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
        
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_po_to_cr.item_receive_date',[$from,$todate]);
        }

        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='cr-view-acc';

            $a->tender_stage='CR View';
            $a->stage='Approved';
        }



        $demandDetailPageFromRoute['cr-view-acc']['approved']=$demands;

//            Waiting for approve

        $cr_no = Input::get('cr_no');
        $demands = \App\DemandPoToCr::join('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
            ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
            ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
            ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
            ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title', 'demand_po_to_cr.id', 'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','demand_to_lpr.*')
            ->where('demand_po_to_cr.status','=',1)
            ->orderBy('demand_po_to_cr.id', 'desc');
        if(!empty($cr_no)){
            $demands->where('demand_po_to_cr.cr_number','Like',"%$cr_no%");
        }

        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }

        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }

      
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_po_to_cr.item_receive_date',[$from,$todate]);
        }

        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_stage='CR View';
            $a->stage='Waiting For Approved';
        }
        $demandDetailPageFromRoute['cr-view-acc']['waiting_for-approved']=$demands;


        $this->tableAlies = \Session::get('zoneAlise');

//           Pending

        $cr_no = Input::get('cr_no');
        $demands = \App\DemandPoToCr::join('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
            ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
            ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
            ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
            ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title', 'demand_po_to_cr.id', 'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','demand_to_lpr.*')
            ->whereNull('demand_po_to_cr.status')
            ->orderBy('demand_po_to_cr.id', 'desc');
        if(!empty($cr_no)){
            $demands->where('demand_po_to_cr.cr_number','Like',"%$cr_no%");
        }

        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
       
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_po_to_cr.item_receive_date',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='inspection-view-acc';

            $a->tender_stage='Inspection';
            $a->stage='Pending';
        }
        $demandDetailPageFromRoute['inspection-view-acc']['pending']=$demands;


//            Approved


        $cr_no = Input::get('cr_no');
        $demands = \App\DemandPoToCr::join('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
            ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
            ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
            ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
            ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title', 'demand_po_to_cr.id', 'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','demand_to_lpr.*')
            ->where('demand_po_to_cr.status','=',1)
            ->orderBy('demand_po_to_cr.id', 'desc');
        if(!empty($cr_no)){
            $demands->where('demand_po_to_cr.cr_number','Like',"%$cr_no%");
        }

        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
      
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_po_to_cr.item_receive_date',[$from,$todate]);
        }

        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_stage='Inspection';
            $a->stage='Inspected';
        }
        $demandDetailPageFromRoute['inspection-view-acc']['approved']=$demands;


        $this->tableAlies = \Session::get('zoneAlise');
//           Pending
        $cr_no = Input::get('cr_no');
        $demands = \App\DemandCrToInspection::join('demand_po_to_cr', 'demand_po_to_cr.id', '=', 'demand_cr_to_inspection.dmnd_po_to_cr_id')
            ->leftJoin('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
            ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
            ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
            ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
            ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title', 'demand_cr_to_inspection.id', 'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','demand_to_lpr.*')
            ->where('demand_po_to_cr.status','=',1)
            ->whereNull('demand_cr_to_inspection.d44b_status')
            ->orderBy('demand_po_to_cr.id', 'desc');
        if(!empty($cr_no)){
            $demands->where('demand_po_to_cr.cr_number','Like',"%$cr_no%");
        }

        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
       
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_po_to_cr.item_receive_date',[$from,$todate]);
        }

        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='v44-voucher-view-acc';

            $a->tender_stage='Dv44 Voucher';
            $a->stage='Pending';
        }
        $demandDetailPageFromRoute['v44-voucher-view-acc']['Pending']=$demands;

//            Waiting for Approved

        $cr_no = Input::get('cr_no');
        $demands = \App\D44BData::join('demand_cr_to_inspection', 'demand_cr_to_inspection.id', '=', 'd44b_datas.inspecttion_id')
            ->leftJoin('demand_po_to_cr', 'demand_po_to_cr.id', '=', 'demand_cr_to_inspection.dmnd_po_to_cr_id')
            ->leftJoin('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
            ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
            ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
            ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
            ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title', 'd44b_datas.id', 'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','demand_to_lpr.*')
            ->whereNull('d44b_datas.status')
            ->orderBy('demand_po_to_cr.id', 'desc');
        if(!empty($cr_no)){
            $demands->where('demand_po_to_cr.cr_number','Like',"%$cr_no%");
        }

        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
        if(!empty($from)){
            //$demands->whereBetween('demand_po_to_cr.item_receive_date','>=',$from);;
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_po_to_cr.item_receive_date',[$from,$todate]);
        }

        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='v44-voucher-view-acc';

            $a->tender_stage='Dv44 Voucher';
            $a->stage='Waiting For approved';
        }
        $demandDetailPageFromRoute['v44-voucher-view-acc']['waiting-for-approved']=$demands;

//                Approved

        $cr_no = Input::get('cr_no');
        $demands = \App\D44BData::join('demand_cr_to_inspection', 'demand_cr_to_inspection.id', '=', 'd44b_datas.inspecttion_id')
            ->leftJoin('demand_po_to_cr', 'demand_po_to_cr.id', '=', 'demand_cr_to_inspection.dmnd_po_to_cr_id')
            ->leftJoin('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
            ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
            ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
            ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
            ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title', 'd44b_datas.id', 'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','demand_to_lpr.*')
            ->whereNotNull('d44b_datas.status')
            ->orderBy('demand_po_to_cr.id', 'desc');
        if(!empty($cr_no)){
            $demands->where('demand_po_to_cr.cr_number','Like',"%$cr_no%");
        }

        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
        if(!empty($from)){
           // $demands->whereBetween('demand_po_to_cr.item_receive_date','>=',$from);
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_po_to_cr.item_receive_date',[$from,$todate]);
        }

        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='v44-voucher-view-acc';

            $a->tender_stage='Dv44 Voucher';
            $a->stage='Approved';
        }
        $demandDetailPageFromRoute['v44-voucher-view-acc']['approved']=$demands;

        $demandeNames = DemandeName::where('status','=',1)->get();

        $demands_arr = array();
        foreach ($demandDetailPageFromRoute as $key => $value) {
            if (is_array($value)) {
                $demands_arr = array_merge($demands_arr, array_flatten($value));
            }
            else {
                $demands_arr[$key] = $value;
            }
        }

        //Make paginate an array =========================================///

        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Create a new Laravel collection from the array data
        $itemCollection = collect($demands_arr);
        // Define how many items we want to be visible in each page
        $perPage = 10;

        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();


        // Create our paginator and pass it to the view
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);

        // set url path for generted links
        $paginatedItems->setPath($request->url());

        //============================================================End Make Pagination ///
        $demands=$paginatedItems;

        return View::make('reports.tender_track')->with(compact('demands','demandeNames','demande','demand_no','tender_no','from','todate'));

    }


    public function download(Request $request){

        // Search parms =========================================
        $demande    = Input::get('requester');
        $demand_no  = Input::get('demand_no');
        $tender_no  = Input::get('tender_no');
        $from       = Input::get('from');
        $todate     = Input::get('todate');

        $catGrou    = Auth::user()->categories_id;
        $demandIdAcToCat  = array();
        $demandToLprIdAcToCat  = array();
        if(!empty($catGrou)){
            $catGrou    = explode(',',$catGrou);
            $demandIdAcToCat  = array_unique(array_map('current',\App\ItemToDemand::select('demand_id')->whereIn('group_name',$catGrou)->get()->toArray()));
            $demandToLprIdAcToCat  = array_unique(array_map('current',\App\ItemToDemand::select('lpr_id')->whereIn('group_name',$catGrou)->get()->toArray()));
        }
        $demandDetailPageFromRoute=[];

//        floating-tender-acc'

        //pending

        $demandIds = array_map('current',\App\ItemToDemand::select('lpr_id')
            ->where('tender_floating','=',NULL)
            ->where('group_status','=',2)
            ->where('current_status','!=',101)
            ->whereNull('float_tender_app_status')
            ->where(function($q) {
                $q->whereNull('transfer_to');
                $q->orWhereNotNull('transfer_to');
            })
            ->where(function($qu) {
                $qu->whereNull('transfer_status');
                $qu->orWhere('transfer_status','=',2);
            })
            ->where(function($que) {
                $que->whereNull('plr_status');
                $que->orWhere('plr_status','=',3);
            })
            ->get()->toArray());

        $demands = \App\DemandToLpr::leftJoin('demand_to_tender', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->select('demand_to_lpr.*','demand_to_tender.tender_number')
            ->whereIn('demand_to_lpr.id',$demandIds)
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='floating-tender-acc';
            $a->tender_stage=' Tender';
            $a->stage='Pending';
        }

        $demandDetailPageFromRoute['floating-tender-acc']['pending']=$demands;

        //approve
        $demandIds = array_map('current',\App\ItemToDemand::select('lpr_id')->whereNotNull('tender_floating')->get()->toArray());

        $demands = \App\DemandToLpr::join('demand_to_tender', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->select('demand_to_lpr.*','demand_to_tender.tender_number','demand_to_tender.total_quantity as total_unit','demand_to_tender.tender_id as tenderId')
            ->where('demand_to_tender.float_tender_app_status','=',1)
            ->whereIn('demand_to_lpr.id',$demandIds)
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demands.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_stage=' Tender';
            $a->tender_positon_stage='floating-tender-acc';
            $a->stage='Approved';
        }
        $demandDetailPageFromRoute['floating-tender-acc']['approved']=$demands;


//            waiting_for_approve

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->whereNotNull('demand_to_tender.tender_floating')
            ->whereNull('demand_to_tender.float_tender_app_status')
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='floating-tender-acc';
            $a->tender_stage=' Tender';
            $a->stage='Waiting For Approved';
        }
        $demandDetailPageFromRoute['floating-tender-acc']['waiting_for_approved']=$demands;




//            retender-view-acc
//            pending

        $demands = \App\Retender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'retender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','retender.tender_id as tenderId','retender.tender_number','demande_name.name as demande_name','retender.retenderQty as total_unit')
            ->where('retender.already_published','=',NULL)
            ->orderBy('demand_to_lpr.id', 'desc');

        if(!empty($catGrou)){
            $demands->whereIn('demands.id',$demandIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            // $demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('retender.tender_number','Like',"%$tender_no%");
        }

        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }

        $demands = $demands->get();


        foreach ($demands as $a){
            $a->tender_positon_stage='retender-view-acc';
            $a->tender_stage='Re-Tender';
            $a->stage='Pending';
        }
        $demandDetailPageFromRoute['retender-view-acc']['pending']=$demands;


        //pending
        $demands = \App\NilReturn::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'nil_return.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','nil_return.tender_id as tenderId','nil_return.tender_number','demande_name.name as demande_name','nil_return.id as nil_id','nil_return.nil_return_qty as total_unit')
            ->where('nil_return.already_published','=',NULL)
            ->where('nil_return.status','=',1)
            ->orderBy('demand_to_lpr.id', 'desc');

        if(!empty($catGrou)){
            $demands->whereIn('demands.id',$demandIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            // $demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
        if(!empty($tender_no)){
            // $demands->whereRaw("find_in_set('".$tender_no."',retender.tender_number)");
            $demands->where('nil_return.tender_number','Like',"%$tender_no%");
        }


        if(!empty($from)){
            //$demands->whereBetween('demand_to_lpr.when_needed','=<',$from);
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);

        }
        $demands = $demands->get(); //dd($demands);

        foreach ($demands as $a){
            $a->tender_positon_stage='nil-return';
            $a->tender_stage='Nill-Return';
            $a->stage='Pending';
        }
        $demandDetailPageFromRoute['nil-return']['pending']=$demands;


//           approved
        $demands = \App\NilReturn::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'nil_return.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','nil_return.tender_id as tenderId','nil_return.tender_number','demande_name.name as demande_name','nil_return.id as nil_id','nil_return.nil_return_qty as total_unit')
            ->where('nil_return.already_published','=',NULL)
            ->where('nil_return.status','=',2)
            ->orderBy('demand_to_lpr.id', 'desc');


        if(!empty($catGrou)){
            $demands->whereIn('demands.id',$demandIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            // $demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
        if(!empty($tender_no)){
            // $demands->whereRaw("find_in_set('".$tender_no."',retender.tender_number)");
            $demands->where('nil_return.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){
            //$demands->whereBetween('demand_to_lpr.when_needed','=<',$from);
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);

        }
        $demands = $demands->get(); //dd($demands);
        foreach ($demands as $a){
            $a->tender_positon_stage='nil-return';

            $a->tender_stage='Nill-Return';
            $a->stage='Approved';
        }
        $demandDetailPageFromRoute['nil-return']['approved']=$demands;

//            waiting for approve
        $demands = \App\NilReturn::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'nil_return.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','nil_return.tender_id as tenderId','nil_return.tender_number','demande_name.name as demande_name','nil_return.id as nil_id','nil_return.nil_return_qty as total_unit')
            ->where('nil_return.already_published','=',NULL)
            ->where('nil_return.status','=',3)
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demands.id',$demandIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            // $demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
        if(!empty($tender_no)){
            // $demands->whereRaw("find_in_set('".$tender_no."',retender.tender_number)");
            $demands->where('nil_return.tender_number','Like',"%$tender_no%");
        }


        if(!empty($from)){
            //$demands->whereBetween('demand_to_lpr.when_needed','=<',$from);
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);

        }
        $demands = $demands->get(); //dd($demands);
        foreach ($demands as $a){

            $a->tender_positon_stage='nil-return';
            $a->tender_stage='Nill-Return';
            $a->stage='Waiting For Approved';
        }
        $demandDetailPageFromRoute['nil-return']['waiting-for-approved']=$demands;


        //pending

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->where('demand_to_tender.float_tender_app_status','=',1)
            //->where('demand_to_tender.current_status','=',1)
            ->whereNull('demand_to_tender.tender_quation_collection')
            ->where(function($q) {
                $q->whereNull('demand_to_tender.tender_status');
                $q->orWhere('demand_to_tender.tender_status','=',1);
            })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
            // $demands->where('demand_to_tender.tender_number','=',$tender_no);
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);

        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='collection-quotation-acc';

            $a->tender_stage='Collection Quotation';
            $a->stage='Pending';
        }
        $demandDetailPageFromRoute['collection-quotation-acc']['pending']=$demands;

//            Approved
//                $demands = Demand::where('tender_quation_collection','!=',NULL)->orderBy('id', 'desc')->get();

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->where('demand_to_tender.float_tender_app_status','=',1)
            ->where('demand_to_tender.coll_quat_app_status','=',1)
            ->whereNotNull('demand_to_tender.tender_quation_collection')
            ->where(function($q) {
                $q->whereNull('demand_to_tender.tender_status');
                $q->orWhere('demand_to_tender.tender_status','=',1);
            })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){

            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);

        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='collection-quotation-acc';

            $a->tender_stage='Collection Quotation';
            $a->stage='Approved';
        }
        $demandDetailPageFromRoute['collection-quotation-acc']['approved']=$demands;

//          waiting-for-appproved
        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->where('demand_to_tender.float_tender_app_status','=',1)
            ->whereNotNull('demand_to_tender.tender_quation_collection')
            ->whereNull('demand_to_tender.coll_quat_app_status')
            ->where(function($q) {
                $q->whereNull('demand_to_tender.tender_status');
                $q->orWhere('demand_to_tender.tender_status','=',1);
            })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){

            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);

        }
        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='collection-quotation-acc';
            $a->tender_stage='Waiting For Approved';
            $a->stage='Pending';
        }
        $demandDetailPageFromRoute['collection-quotation-acc']['waiting-for-approved']=$demands;



//            cst-view-acc
//          pending

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->where('demand_to_tender.coll_quat_app_status','=',1)
            //->where('demand_to_tender.current_status','=',1)
            ->whereNull('demand_to_tender.cst_pending_sta')
            ->whereNull('demand_to_tender.first_cst_app_status')
            ->whereNotNull('demand_to_tender.tender_quation_collection')
            ->where(function($q) {
                $q->whereNull('demand_to_tender.tender_status');
                $q->orWhere('demand_to_tender.tender_status','=',1);
            })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){

            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);

        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='cst-view-acc';

            $a->tender_stage='CST';
            $a->stage='Pending';
        }

        $demandDetailPageFromRoute['cst-view-acc']['pending']=$demands;


//          First Member

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->where('demand_to_tender.cst_pending_sta','=',1)
            //->where('demand_to_tender.current_status','=',1)
            ->whereNull('demand_to_tender.first_cst_app_status')
            ->whereNotNull('demand_to_tender.cst_pending_sta')
            ->whereNotNull('demand_to_tender.tender_quation_collection')
            ->where(function($q) {
                $q->whereNull('demand_to_tender.tender_status');
                $q->orWhere('demand_to_tender.tender_status','=',1);
            })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){

            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);

        }
        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='cst-view-acc';
            $a->tender_stage='CST';
            $a->stage='First Member';
        }
        $demandDetailPageFromRoute['cst-view-acc']['first_member']=$demands;

        //          Second Member

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->where('demand_to_tender.coll_quat_app_status','=',1)
            //->where('demand_to_tender.current_status','=',1)
            ->whereNull('demand_to_tender.second_cst_app_status')
            ->whereNotNull('demand_to_tender.first_cst_app_status')
            ->where(function($q) {
                $q->whereNull('demand_to_tender.tender_status');
                $q->orWhere('demand_to_tender.tender_status','=',1);
            })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);


        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='cst-view-acc';

            $a->tender_stage='CST';
            $a->stage='Second Member';
        }
        $demandDetailPageFromRoute['cst-view-acc']['second_member']=$demands;


//                 President Member

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->where('demand_to_tender.coll_quat_app_status','=',1)
            //->where('demand_to_tender.current_status','=',1)
            ->whereNull('demand_to_tender.cst_draft_status')
            ->whereNotNull('demand_to_tender.second_cst_app_status')
            ->where(function($q) {
                $q->whereNull('demand_to_tender.tender_status');
                $q->orWhere('demand_to_tender.tender_status','=',1);
            })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='cst-view-acc';

            $a->tender_stage='CST';
            $a->stage='President Member';
        }
        $demandDetailPageFromRoute['cst-view-acc']['president_member']=$demands;

//           Approved

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->whereNotNull('demand_to_tender.cst_draft_status')
            ->where(function($q) {
                $q->whereNull('demand_to_tender.tender_status');
                $q->orWhere('demand_to_tender.tender_status','=',1);
            })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='cst-view-acc';

            $a->tender_stage='CST';
            $a->stage='Approved';
        }
        $demandDetailPageFromRoute['cst-view-acc']['approved']=$demands;




//           Pending
        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            ->where('demand_to_tender.send_to_nhq','=',1)
            ->whereNull('demand_to_tender.cst_supplier_select')
            ->whereNotNull('demand_to_tender.cst_draft_status')
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='draft-cst-view-acc';

            $a->tender_stage='Draft CST';
            $a->stage='Pending';
        }
        $demandDetailPageFromRoute['draft-cst-view-acc']['pending']=$demands;

//             Approved

        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
            //->whereNull('demand_to_tender.cst_supplier_select')
            ->whereNotNull('demand_to_tender.cst_supplier_select')
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='draft-cst-view-acc';

            $a->tender_stage='Draft CST';
            $a->stage='Approved';
        }
        $demandDetailPageFromRoute['draft-cst-view-acc']['approved']=$demands;

//            Pending

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
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='hdq-approval-acc';

            $a->tender_stage='HDQ Approval';
            $a->stage='Pending';
        }

        $demandDetailPageFromRoute['hdq-approval-acc']['pending']=$demands;


//            Approvred
        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name')
            ->whereNotNull('demand_to_tender.cst_draft_status')
            ->where('demand_to_tender.head_ofc_apvl_status','=',1)
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='hdq-approval-acc';

            $a->tender_stage='HDQ Approval';
            $a->stage='Approved';
        }
        $demandDetailPageFromRoute['hdq-approval-acc']['approved']=$demands;



        $po_no = Input::get('po_no');
//           pending
        $toShowInPo = array_map('current',\App\DemandToCollectionQuotation::select('tender_id')->whereNotNull('winner')->whereNull('po_status')->get()->toArray());
        if(!empty($toShowInPo)){
            $toShowInPo = array_unique($toShowInPo);
        }
        $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name')
            ->whereIn('demand_to_tender.tender_id',$toShowInPo)
            // ->whereNotNull('demand_to_tender.cst_supplier_select')
            // ->where(function($query) {
            //     $query->whereNotNull('demand_to_tender.cst_supplier_select');
            //     $query->orWhere('demand_to_tender.head_ofc_apvl_status','=',1);
            // })
            // ->where('demand_to_tender.lp_section_status','=',1)
            // ->whereNull('demand_to_tender.po_status')
            // ->where(function($query) {
            //     $query->where('demand_to_tender.head_ofc_apvl_status','=',1);
            //     $query->orWhere('demand_to_tender.head_ofc_apvl_status','=',null);
            // })
            ->orderBy('demand_to_lpr.id', 'desc');
        if(!empty($catGrou)){
            $demands->whereIn('demand_to_lpr.id',$demandToLprIdAcToCat);
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('demand_to_tender.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='po-generation-acc';

            $a->tender_stage='PO Generation';
            $a->stage='Pending';
        }

        $demandDetailPageFromRoute['po-generation-acc']['pending']=$demands;

//
//            Waiting-for-Check
        $demands = \App\PoDatas::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'po_datas.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('po_datas.*','demand_to_lpr.*')
            ->whereNull('po_datas.po_check_status')
            ->orderBy('po_datas.id', 'desc');
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }

        $demands = $demands->get();

        foreach ($demands as $a){
            $a->tender_positon_stage='po-generation-acc';

            $a->tender_stage='PO Generation';
            $a->stage='Waiting For Check';
        }
        $demandDetailPageFromRoute['po-generation-acc']['waiting-for-check']=$demands;


        //            Waiting-for-Approve

        $demands = \App\PoDatas::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'po_datas.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('po_datas.*','demand_to_lpr.*')
            ->where('po_datas.po_check_status','=',1)
            ->whereNull('po_datas.po_approve_status')
            ->orderBy('po_datas.id', 'desc');
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }

        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='po-generation-acc';

            $a->tender_stage='PO Generation';
            $a->stage='Waiting For Approved';
        }
        $demandDetailPageFromRoute['po-generation-acc']['waiting-for-approve']=$demands;

//
//            Approved

        $demands = \App\PoDatas::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'po_datas.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('po_datas.*','demand_to_lpr.*')
            ->where('po_datas.po_approve_status','=',1)
            //->whereNull('po_datas.po_approve_status')
            ->orderBy('po_datas.id', 'desc');

        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
        }
        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($from)){

        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_to_lpr.when_needed',[$from,$todate]);
        }

        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='po-generation-acc';

            $a->tender_stage='PO Generation';
            $a->stage='Approved';
        }

        $demandDetailPageFromRoute['po-generation-acc']['approved']=$demands;


        $this->tableAlies = \Session::get('zoneAlise');
//           pending
        $po_no = Input::get('po_no');
        $demands = \App\PoDatas::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'po_datas.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
            ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
            ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
            ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title','demand_to_lpr.*')
            ->where('po_datas.po_approve_status','=',1)
            ->where(\DB::raw("CONVERT(po_datas.inspection_qty, DECIMAL)"), '<',\DB::raw("CONVERT(po_datas.quantity, DECIMAL)"))
            //->where('po_datas.inspection_qty','<','po_datas.quantity')
            //->whereNull('po_datas.po_approve_status')
            ->orderBy('po_datas.id', 'desc');
        if(!empty($po_no)){
            $demands->where('po_datas.po_number','Like',"%$po_no%");
        }

        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }

        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }

        if(!empty($from)){
            // $demands->whereBetween('po_datas.top_date','>=',$from);
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('po_datas.top_date',[$from,$todate]);
        }
        $demands = $demands->get();

        foreach ($demands as $a){
            $a->tender_positon_stage='cr-view-acc';

            $a->tender_stage='CR View';
            $a->stage='Pending';
        }
        $demandDetailPageFromRoute['cr-view-acc']['pending']=$demands;

//           approved

        $cr_no = Input::get('cr_no');
        $demands = \App\DemandPoToCr::join('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
            ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
            ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
            ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
            ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title', 'demand_po_to_cr.id', 'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','demand_to_lpr.*')
            ->whereNull('demand_po_to_cr.status')
            ->orderBy('demand_po_to_cr.id', 'desc');
        if(!empty($cr_no)){
            $demands->where('demand_po_to_cr.cr_number','Like',"%$cr_no%");
        }

        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }

        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_po_to_cr.item_receive_date',[$from,$todate]);
        }

        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='cr-view-acc';

            $a->tender_stage='CR View';
            $a->stage='Approved';
        }



        $demandDetailPageFromRoute['cr-view-acc']['approved']=$demands;

//            Waiting for approve

        $cr_no = Input::get('cr_no');
        $demands = \App\DemandPoToCr::join('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
            ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
            ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
            ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
            ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title', 'demand_po_to_cr.id', 'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','demand_to_lpr.*')
            ->where('demand_po_to_cr.status','=',1)
            ->orderBy('demand_po_to_cr.id', 'desc');
        if(!empty($cr_no)){
            $demands->where('demand_po_to_cr.cr_number','Like',"%$cr_no%");
        }

        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }

        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }


        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_po_to_cr.item_receive_date',[$from,$todate]);
        }

        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_stage='CR View';
            $a->stage='Waiting For Approved';
        }
        $demandDetailPageFromRoute['cr-view-acc']['waiting_for-approved']=$demands;


        $this->tableAlies = \Session::get('zoneAlise');

//           Pending

        $cr_no = Input::get('cr_no');
        $demands = \App\DemandPoToCr::join('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
            ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
            ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
            ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
            ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title', 'demand_po_to_cr.id', 'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','demand_to_lpr.*')
            ->whereNull('demand_po_to_cr.status')
            ->orderBy('demand_po_to_cr.id', 'desc');
        if(!empty($cr_no)){
            $demands->where('demand_po_to_cr.cr_number','Like',"%$cr_no%");
        }

        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }

        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_po_to_cr.item_receive_date',[$from,$todate]);
        }
        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='inspection-view-acc';

            $a->tender_stage='Inspection';
            $a->stage='Pending';
        }
        $demandDetailPageFromRoute['inspection-view-acc']['pending']=$demands;


//            Approved


        $cr_no = Input::get('cr_no');
        $demands = \App\DemandPoToCr::join('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
            ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
            ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
            ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
            ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title', 'demand_po_to_cr.id', 'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','demand_to_lpr.*')
            ->where('demand_po_to_cr.status','=',1)
            ->orderBy('demand_po_to_cr.id', 'desc');
        if(!empty($cr_no)){
            $demands->where('demand_po_to_cr.cr_number','Like',"%$cr_no%");
        }

        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }

        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_po_to_cr.item_receive_date',[$from,$todate]);
        }

        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_stage='Inspection';
            $a->stage='Inspected';
        }
        $demandDetailPageFromRoute['inspection-view-acc']['approved']=$demands;


        $this->tableAlies = \Session::get('zoneAlise');
//           Pending
        $cr_no = Input::get('cr_no');
        $demands = \App\DemandCrToInspection::join('demand_po_to_cr', 'demand_po_to_cr.id', '=', 'demand_cr_to_inspection.dmnd_po_to_cr_id')
            ->leftJoin('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
            ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
            ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
            ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
            ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title', 'demand_cr_to_inspection.id', 'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','demand_to_lpr.*')
            ->where('demand_po_to_cr.status','=',1)
            ->whereNull('demand_cr_to_inspection.d44b_status')
            ->orderBy('demand_po_to_cr.id', 'desc');
        if(!empty($cr_no)){
            $demands->where('demand_po_to_cr.cr_number','Like',"%$cr_no%");
        }

        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }

        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_po_to_cr.item_receive_date',[$from,$todate]);
        }

        $demands = $demands->get();
        foreach ($demands as $a){
            $a->tender_positon_stage='v44-voucher-view-acc';

            $a->tender_stage='Dv44 Voucher';
            $a->stage='Pending';
        }
        $demandDetailPageFromRoute['v44-voucher-view-acc']['Pending']=$demands;

//            Waiting for Approved

        $cr_no = Input::get('cr_no');
        $demands = \App\D44BData::join('demand_cr_to_inspection', 'demand_cr_to_inspection.id', '=', 'd44b_datas.inspecttion_id')
            ->leftJoin('demand_po_to_cr', 'demand_po_to_cr.id', '=', 'demand_cr_to_inspection.dmnd_po_to_cr_id')
            ->leftJoin('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
            ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
            ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
            ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
            ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title', 'd44b_datas.id', 'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','demand_to_lpr.*')
            ->whereNull('d44b_datas.status')
            ->orderBy('demand_po_to_cr.id', 'desc');
        if(!empty($cr_no)){
            $demands->where('demand_po_to_cr.cr_number','Like',"%$cr_no%");
        }

        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
        if(!empty($from)){
            //$demands->whereBetween('demand_po_to_cr.item_receive_date','>=',$from);;
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_po_to_cr.item_receive_date',[$from,$todate]);
        }

        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='v44-voucher-view-acc';

            $a->tender_stage='Dv44 Voucher';
            $a->stage='Waiting For approved';
        }
        $demandDetailPageFromRoute['v44-voucher-view-acc']['waiting-for-approved']=$demands;

//                Approved

        $cr_no = Input::get('cr_no');
        $demands = \App\D44BData::join('demand_cr_to_inspection', 'demand_cr_to_inspection.id', '=', 'd44b_datas.inspecttion_id')
            ->leftJoin('demand_po_to_cr', 'demand_po_to_cr.id', '=', 'demand_cr_to_inspection.dmnd_po_to_cr_id')
            ->leftJoin('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
            ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.id', '=', 'demand_to_lpr.tender_id')
            ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
            ->leftjoin($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
            ->select('po_datas.*',$this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_tenders.tender_title', 'd44b_datas.id', 'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','demand_to_lpr.*')
            ->whereNotNull('d44b_datas.status')
            ->orderBy('demand_po_to_cr.id', 'desc');
        if(!empty($cr_no)){
            $demands->where('demand_po_to_cr.cr_number','Like',"%$cr_no%");
        }

        if(!empty($tender_no)){
            $demands->where('po_datas.tender_number','Like',"%$tender_no%");
        }
        if(!empty($demande)){
            $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
            //$demands->where('demands.requester','=',$demande);
        }
        if(!empty($demand_no)){
            $demands->where('demand_to_lpr.demand_no','Like',"%$demand_no%");
            // $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
            //$demands->where('demands.demand_no','=',$demand_no);
        }
        if(!empty($from)){
            // $demands->whereBetween('demand_po_to_cr.item_receive_date','>=',$from);
        }
        if(!empty($from)&&!empty($todate)){
            $demands->whereBetween('demand_po_to_cr.item_receive_date',[$from,$todate]);
        }

        $demands = $demands->get();
        foreach ($demands as $a){

            $a->tender_positon_stage='v44-voucher-view-acc';

            $a->tender_stage='Dv44 Voucher';
            $a->stage='Approved';
        }
        $demandDetailPageFromRoute['v44-voucher-view-acc']['approved']=$demands;

        $demandeNames = DemandeName::where('status','=',1)->get();

        $demands_arr = array();
        foreach ($demandDetailPageFromRoute as $key => $value) {
            if (is_array($value)) {
                $demands_arr = array_merge($demands_arr, array_flatten($value));
            }
            else {
                $demands_arr[$key] = $value;
            }
        }



        $d_val=[];

        $sl=1;

        foreach ($demands_arr as $sc){
            if($sc->tender_positon_stage=='collection-quotation-acc' || ($sc->tender_positon_stage=='floating-tender-acc')
                || $sc->tender_positon_stage=='cst-view-acc' || $sc->tender_positon_stage=='draft-cst-view-acc'
                || $sc->tender_positon_stage=='hdq-approval-acc' || $sc->tender_positon_stage=='po-generation-acc'
                || $sc->tender_positon_stage=='cr-view-acc'  || $sc->tender_positon_stage=='inspection-view-acc'
                || $sc->tender_positon_stage=='v44-voucher-view-acc' || $sc->tender_positon_stage=='retender-view-acc'  || $sc->tender_positon_stage=='nil-return')
                {
                if(!empty($sc->requester)) {
                    $reuisters = explode(',', $sc->requester);

                    $reuisters = array_unique($reuisters);
                    $req_arr=[];
                    foreach ($reuisters as $req){
                        $req_arr[]= \App\Http\Controllers\SelectLprController::requestename($req);

                    }

                }

            }else{

                if(!empty($sc->requester)) {
                    $reuisters = explode(',', $sc->requester);

                    $reuisters = array_unique($reuisters);
                    $req_arr=[];
                    foreach ($reuisters as $req){
                        $req_arr[]= \App\Http\Controllers\SelectLprController::requestename($req);

                    }
                }

            }


            if(!empty($sc->itemsToDemand) && count($sc->itemsToDemand)<1 && isset($sc->tenderId)){
                $sc->itemsToDemand = \App\ItemToDemand::where('tender_no','=',$sc->tenderId)->where('lpr_id','=',$sc->id)->get();
            }

            $remComma = 1;
            $num_of_items = !empty($sc->itemsToDemand)?count($sc->itemsToDemand):0;
            if(!empty($sc->itemsToDemand) && count($sc->itemsToDemand) > 0){

                $item_name_arr=[];
                foreach($sc->itemsToDemand as $ke => $itmsf){

                    $deno = \App\Deno::find($itmsf->deno_id);

                    $i_name= $itmsf->item_name. '(';

                    if(!empty($deno->name)){
                        $dno_name=  $deno->name;
                    }else{
                        $dno_name=' ';
                    }

                    if(!empty($itmsf->unit)){
                        $itmsf= $itmsf->unit;
                    }else{
                        $itmsf=' ';
                    }
                    $st= ')';

                    if($num_of_items > $remComma){
                        $semi=  ';';
                    }else{
                        $semi=  ' ';

                    }
                    $remComma++;

                    $item_name_arr[]=$i_name.$dno_name.$itmsf.$st.$semi;
                }
            }else{
                $item_name_arr=null;
            }

            $d_val[]=[
                'Sr#'=>$sl++,
                'Demanding'=>$sc->requester!=null?implode(',',$req_arr):null,
                'Demand No'=>$sc->demand_no,
                'Item'=>$item_name_arr!=null?implode(',',$item_name_arr):null,
                'Tender number'=>$sc->tender_number,
                'Tender Stage'=>$sc->tender_stage.':-'.$sc->stage,
            ];

        }

        return Excel::create(ucfirst($request->operator).' Tender Track Report', function($excel) use ($d_val) {
            $excel->sheet('Sheet1', function($sheet) use($d_val) {
                $sheet->fromArray($d_val);
            });


        })->export('xls');

    }

    }
