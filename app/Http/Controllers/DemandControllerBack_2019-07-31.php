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

class DemandController extends Controller
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
    public function index($prm=null)
    { 

        $demandDetailPageFromRoute = \Request::segment(1);
        $prm = $prm;

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

        if($demandDetailPageFromRoute == 'demand'){
            $demands = Demand::whereNotNull('id')->orderBy('id', 'desc');
                            if(!empty($catGrou)){
                                $demands->whereIn('id',$demandIdAcToCat);
                            }
                            if(!empty($demande)){
                                $demands->where('requester','=',$demande);
                            }
                            if(!empty($demand_no)){
                                $demands->where('demand_no','=',$demand_no);
                            }
                            if(!empty($from)){
                                $demands->where('when_needed','=<',$from);
                            }
                            if(!empty($todate)){
                                $demands->where('when_needed','<=',$todate);
                            }
            $demands = $demands->paginate(10);
        }

        if($demandDetailPageFromRoute == 'demand-pending'){
            if(\Request::segment(2)==1){
                $demands = Demand::whereNotNull('demand_entry_by')
                                ->whereNull('approved_by')
                                ->where(function($q) {
                                    $q->where('demand_type','=',1);
                                    $q->orWhereNull('demand_type');
                                })
                                ->orderBy('id', 'desc');
                                if(!empty($catGrou)){
                                    $demands->whereIn('id',$demandIdAcToCat);
                                }
                                if(!empty($demande)){
                                    $demands->where('requester','=',$demande);
                                }
                                if(!empty($demand_no)){
                                    $demands->where('demand_no','=',$demand_no);
                                }
                                if(!empty($from)){
                                    $demands->where('when_needed','=<',$from);
                                }
                                if(!empty($todate)){
                                    $demands->where('when_needed','<=',$todate);
                                }
                $demands = $demands->paginate(10);
            }
            if(\Request::segment(2)==2){
                $demands = Demand::whereNotNull('demand_entry_by')
                                ->whereNotNull('approved_by')
                                ->where('demand_appv_status','=',1)
                                ->where(function($q) {
                                    $q->where('demand_type','=',1);
                                    $q->orWhereNull('demand_type');
                                })
                                ->orderBy('id', 'desc');
                                if(!empty($catGrou)){
                                    $demands->whereIn('id',$demandIdAcToCat);
                                }
                                if(!empty($demande)){
                                    $demands->where('requester','=',$demande);
                                }
                                if(!empty($demand_no)){
                                    $demands->where('demand_no','=',$demand_no);
                                }
                                if(!empty($from)){
                                    $demands->where('when_needed','=<',$from);
                                }
                                if(!empty($todate)){
                                    $demands->where('when_needed','<=',$todate);
                                }
                $demands = $demands->paginate(10);
            }
            if(\Request::segment(2)==3){
                $demands = Demand::whereNotNull('demand_entry_by')
                                ->whereNotNull('approved_by')
                                ->where('demand_appv_status','=',2)
                                ->where(function($q) {
                                    $q->where('demand_type','=',1);
                                    $q->orWhereNull('demand_type');
                                })
                                ->orderBy('id', 'desc');
                                if(!empty($catGrou)){
                                    $demands->whereIn('id',$demandIdAcToCat);
                                }
                                if(!empty($demande)){
                                    $demands->where('requester','=',$demande);
                                }
                                if(!empty($demand_no)){
                                    $demands->where('demand_no','=',$demand_no);
                                }
                                if(!empty($from)){
                                    $demands->where('when_needed','=<',$from);
                                }
                                if(!empty($todate)){
                                    $demands->where('when_needed','<=',$todate);
                                }
                $demands = $demands->paginate(10);
            }
            if(\Request::segment(2)==4){
                $demands = Demand::whereNotNull('id')
                    //->where('demand_appv_status','=',2)
                    ->where(function($q) {
                        $q->where('demand_type','=',1);
                        $q->orWhereNull('demand_type');
                    })
                    ->orderBy('id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->where('requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->where('demand_no','=',$demand_no);
                    }
                    if(!empty($from)){
                        $demands->where('when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->where('when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);
            }
            
        }

        if($demandDetailPageFromRoute == 'group-check-acc'){
            if($prm == 1){
                $countRestItems = array_map('current',\App\ItemToDemand::select('lpr_id')->whereNull('group_status')->where('plr_status','=',3)->whereNotNull('first_group_status')->where('demand_appv_status','=',1)->get()->toArray());
                $demands = \App\DemandToLpr::whereIn('id',$countRestItems)
                                ->orderBy('id', 'desc');
                                if(!empty($catGrou)){
                                    $demands->whereIn('id',$demandIdAcToCat);
                                }
                                if(!empty($demande)){
                                    $demands->where('requester','=',$demande);
                                }
                                if(!empty($demand_no)){
                                    $demands->where('demand_no','=',$demand_no);
                                }
                                if(!empty($from)){
                                    $demands->where('when_needed','=<',$from);
                                }
                                if(!empty($todate)){
                                    $demands->where('when_needed','<=',$todate);
                                }
                $demands = $demands->paginate(10);

            }
            if($prm == 3){
                $countRestItems = array_map('current',\App\ItemToDemand::select('demand_id')->whereNull('group_status')->whereNull('first_group_status')->where('demand_appv_status','=',1)->get()->toArray());
                $demands = Demand::whereIn('id',$countRestItems)
                                ->where(function($q) {
                                    $q->where('demand_type','=',1);
                                    $q->orWhereNull('demand_type');
                                })
                                ->orderBy('id', 'desc');
                                if(!empty($catGrou)){
                                    $demands->whereIn('id',$demandIdAcToCat);
                                }
                                if(!empty($demande)){
                                    $demands->where('requester','=',$demande);
                                }
                                if(!empty($demand_no)){
                                    $demands->where('demand_no','=',$demand_no);
                                }
                                if(!empty($from)){
                                    $demands->where('when_needed','=<',$from);
                                }
                                if(!empty($todate)){
                                    $demands->where('when_needed','<=',$todate);
                                }
                $demands = $demands->get();

            }
            if($prm == 2){
                $countRestItems = array_map('current',\App\ItemToDemand::select('lpr_id')->whereNotNull('group_status')->where('demand_appv_status','=',1)->get()->toArray());
                $demands = \App\DemandToLpr::where('demand_appv_status', '=', 1)->whereIn('id', $countRestItems)
                    ->orderBy('id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
                        // $demands->where('requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->whereRaw("find_in_set('".$demande."',demand_no)");
                        //$demands->where('demand_no','=',$demand_no);
                    }
                    if(!empty($from)){
                        $demands->whereDate('when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->whereDate('when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);

            }
            
        }

        if($demandDetailPageFromRoute == 'floating-tender-acc'){
            if($prm == 1){

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
                                    $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
                                    //$demands->where('demands.demand_no','=',$demand_no);
                                }
                                if(!empty($tender_no)){
                                    $demands->where('demand_to_tender.tender_number','=',$tender_no);
                                }
                                if(!empty($from)){
                                    //$demands->where(DB::raw("DATE(demand_to_lpr.when_needed) >= '".$from."'"));
                                    $demands->whereDate('demand_to_lpr.when_needed','>=',$from);
                                }
                                if(!empty($todate)){
                                    $demands->whereDate('demand_to_lpr.when_needed','<=',$todate);
                                }
                    $demands = $demands->paginate(10);

            }
            if($prm == 2){
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
                                    $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
                                    //$demands->where('demands.demand_no','=',$demand_no);
                                }
                                if(!empty($tender_no)){
                                    $demands->where('demand_to_tender.tender_number','=',$tender_no);
                                }
                                if(!empty($from)){
                                    //$demands->where(DB::raw("DATE(demand_to_lpr.when_needed) >= '".$from."'"));
                                    $demands->whereDate('demand_to_lpr.when_needed','>=',$from);
                                }
                                if(!empty($todate)){
                                    $demands->whereDate('demand_to_lpr.when_needed','<=',$todate);
                                }
                    $demands = $demands->paginate(10);

                // $demands = Demand::whereIn('id',$demandIds)
                //                 ->orderBy('id', 'desc');
                //                 if(!empty($demande)){
                //                     $demands->where('requester','=',$demande);
                //                 }
                //                 if(!empty($demand_no)){
                //                     $demands->where('demand_no','=',$demand_no);
                //                 }
                //                 if(!empty($from)){
                //                     $demands->where('when_needed','=<',$from);
                //                 }
                //                 if(!empty($todate)){
                //                     $demands->where('when_needed','<=',$todate);
                //                 }
                // $demands = $demands->paginate(10);
            }
            if($prm == 3){

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
                        $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
                        //$demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->whereDate('demand_to_lpr.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->whereDate('demand_to_lpr.when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);
            }
            if($prm == 4){
                $demands = \App\DemandToTender::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'demand_to_tender.lpr_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
                    ->select('demand_to_lpr.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
                    ->whereNotNull('demand_to_tender.tender_floating')
                    ->whereNull('demand_to_tender.float_tender_app_status')
                    ->orderBy('demand_to_lpr.id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('demands.id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.requester)");
                        // $demands->where('demands.requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->whereRaw("find_in_set('".$demande."',demand_to_lpr.demand_no)");
                        //$demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->whereDate('demands.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->whereDate('demands.when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);
            }
        }
        if($demandDetailPageFromRoute == 'retender-view-acc'){
            if($prm == 1){
                $demands = \App\Retender::join('demands', 'demands.id', '=', 'retender.demand_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                    ->select('demands.*','retender.tender_id as tenderId','retender.tender_number','demande_name.name as demande_name','retender.retenderQty as total_unit')
                    ->where('retender.already_published','=',NULL)
                    ->orderBy('demands.id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('demands.id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->where('demands.requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('retender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->where('demands.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->where('demands.when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10); //dd($demands);

            }
        }
        if($demandDetailPageFromRoute == 'collection-quotation-acc'){
            if($prm == 1){

                $demands = \App\DemandToTender::join('demands', 'demands.id', '=', 'demand_to_tender.demand_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                    ->select('demands.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
                    ->where('demand_to_tender.float_tender_app_status','=',1)
                    //->where('demand_to_tender.current_status','=',1)
                    ->whereNull('demand_to_tender.tender_quation_collection')
                    ->orderBy('demands.id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('demands.id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->where('demands.requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->where('demands.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->where('demands.when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);

            }
            if($prm == 2){
//                $demands = Demand::where('tender_quation_collection','!=',NULL)->orderBy('id', 'desc')->paginate(10);
                $demands = \App\DemandToTender::join('demands', 'demands.id', '=', 'demand_to_tender.demand_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                    ->select('demands.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
                    ->where('demand_to_tender.float_tender_app_status','=',1)
                    ->where('demand_to_tender.coll_quat_app_status','=',1)
                    ->whereNotNull('demand_to_tender.tender_quation_collection')
                    ->orderBy('demands.id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('demands.id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->where('demands.requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->where('demands.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->where('demands.when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);
            }
            if($prm == 3){
                $demands = \App\DemandToTender::join('demands', 'demands.id', '=', 'demand_to_tender.demand_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                    ->select('demands.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
                    ->where('demand_to_tender.float_tender_app_status','=',1)
                    ->whereNotNull('demand_to_tender.tender_quation_collection')
                    ->whereNull('demand_to_tender.coll_quat_app_status')
                    ->orderBy('demands.id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('demands.id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->where('demands.requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->where('demands.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->where('demands.when_needed','<=',$todate);
                    }
                    $demands = $demands->paginate(10);
            }
            
        }
        if($demandDetailPageFromRoute == 'cst-view-acc'){
            if($prm == 1){
                $demands = \App\DemandToTender::join('demands', 'demands.id', '=', 'demand_to_tender.demand_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                    ->select('demands.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
                    ->where('demand_to_tender.coll_quat_app_status','=',1)
                    //->where('demand_to_tender.current_status','=',1)
                    ->whereNull('demand_to_tender.cst_draft_status')
                    ->whereNotNull('demand_to_tender.tender_quation_collection')
                    ->orderBy('demands.id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('demands.id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->where('demands.requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->where('demands.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->where('demands.when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);
                //$demands = Demand::where('tender_quation_collection','!=',NULL)->where('coll_quat_app_status','=',1)->where('cst_draft_status','=',NULL)->orderBy('id', 'desc')->paginate(10);
            }
            if($prm == 2){
                $demands = \App\DemandToTender::join('demands', 'demands.id', '=', 'demand_to_tender.demand_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                    ->select('demands.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
                    ->whereNotNull('demand_to_tender.cst_draft_status')
                    ->orderBy('demands.id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('demands.id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->where('demands.requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->where('demands.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->where('demands.when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);
                //$demands = Demand::where('cst_draft_status','!=',NULL)->orderBy('id', 'desc')->paginate(10);
            }
        }
        if($demandDetailPageFromRoute == 'draft-cst-view-acc'){
            if($prm == 1){
                $demands = \App\DemandToTender::join('demands', 'demands.id', '=', 'demand_to_tender.demand_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                    ->select('demands.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
                    ->where('demand_to_tender.send_to_nhq','=',1)
                    ->whereNull('demand_to_tender.cst_supplier_select')
                    ->whereNotNull('demand_to_tender.cst_draft_status')
                    ->orderBy('demands.id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('demands.id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->where('demands.requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->where('demands.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->where('demands.when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);
                //$demands = Demand::where('cst_draft_status','!=',NULL)->where('cst_supplier_select','=',NULL)->orderBy('id', 'desc')->paginate(10);
            }
            if($prm == 2){
                $demands = \App\DemandToTender::join('demands', 'demands.id', '=', 'demand_to_tender.demand_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                    ->select('demands.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name','demand_to_tender.total_quantity as total_unit')
                    //->whereNull('demand_to_tender.cst_supplier_select')
                    ->whereNotNull('demand_to_tender.cst_supplier_select')
                    ->orderBy('demands.id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('demands.id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->where('demands.requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->where('demands.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->where('demands.when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);
                //$demands = Demand::where('cst_supplier_select','!=',NULL)->orderBy('id', 'desc')->paginate(10);
            }
        }
        if($demandDetailPageFromRoute == 'hdq-approval-acc'){
            if($prm == 1){
                $demands = \App\DemandToTender::join('demands', 'demands.id', '=', 'demand_to_tender.demand_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                    ->select('demands.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name')
                    ->where('demand_to_tender.lp_section_status','=',2)
                    ->where(function($query) {
                        $query->where('demand_to_tender.head_ofc_apvl_status','=',2);
                        $query->orWhere('demand_to_tender.head_ofc_apvl_status','=',3);
                    })
                    ->orderBy('demands.id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('demands.id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->where('demands.requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->where('demands.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->where('demands.when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);
            }
            if($prm == 2){
                $demands = \App\DemandToTender::join('demands', 'demands.id', '=', 'demand_to_tender.demand_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                    ->select('demands.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name')
                    ->where('demand_to_tender.head_ofc_apvl_status','=',1)
                    ->orderBy('demands.id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('demands.id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->where('demands.requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->where('demands.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->where('demands.when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);
                //$demands = Demand::where('head_ofc_apvl_status','=',1)->orderBy('id', 'desc')->paginate(10);
            }
        }
        if($demandDetailPageFromRoute == 'po-generation-acc'){
            if($prm == 1){
                $demands = \App\DemandToTender::join('demands', 'demands.id', '=', 'demand_to_tender.demand_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                    ->select('demands.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name')
                    // ->whereNotNull('demand_to_tender.cst_supplier_select')
                    ->where(function($query) {
                        $query->whereNotNull('demand_to_tender.cst_supplier_select');
                        $query->orWhere('demand_to_tender.head_ofc_apvl_status','=',1);
                    })
                    ->where('demand_to_tender.lp_section_status','=',1)
                    ->whereNull('demand_to_tender.po_status')
                    ->where(function($query) {
                        $query->where('demand_to_tender.head_ofc_apvl_status','=',1);
                        $query->orWhere('demand_to_tender.head_ofc_apvl_status','=',null);
                    })
                    ->orderBy('demands.id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('demands.id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->where('demands.requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->where('demands.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->where('demands.when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);
//
            }
            if($prm == 2){
                $demands = \App\DemandToTender::join('demands', 'demands.id', '=', 'demand_to_tender.demand_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                    ->select('demands.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name')
                    ->whereNotNull('demand_to_tender.po_status')
                    ->orderBy('demands.id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('demands.id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->where('demands.requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->where('demands.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->where('demands.when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);
                //$demands = Demand::where('po_status','!=',NULL)->orderBy('id', 'desc')->paginate(10);
            }
        }
        if($demandDetailPageFromRoute == 'cr-view-acc'){
            if($prm == 1){
                $demands = \App\DemandToTender::join('demands', 'demands.id', '=', 'demand_to_tender.demand_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                    ->select('demands.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name')
                    ->whereNotNull('demand_to_tender.po_status')
                    ->where(function($query) {
                        $query->whereNull('demand_to_tender.cr_status');
                        $query->orWhereNotNull('demand_to_tender.cr_status');
                    })
                    ->orderBy('demands.id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('demands.id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->where('demands.requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->where('demands.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->where('demands.when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);
            }
            if($prm == 2){
                $demands = \App\DemandToTender::join('demands', 'demands.id', '=', 'demand_to_tender.demand_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                    ->select('demands.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name')
                    ->whereNotNull('demand_to_tender.cr_status')
                    ->orderBy('demands.id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('demands.id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->where('demands.requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->where('demands.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->where('demands.when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);
                //$demands = Demand::where('cr_status','!=',NULL)->orderBy('id', 'desc')->paginate(10);
            }
        }
        if($demandDetailPageFromRoute == 'inspection-view-acc'){
            if($prm == 1){
                $demands = \App\DemandToTender::join('demands', 'demands.id', '=', 'demand_to_tender.demand_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                    ->select('demands.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name')
                    ->whereNotNull('demand_to_tender.cr_status')
                    ->where(function($query) {
                        $query->whereNull('demand_to_tender.inspection_status');
                        $query->orWhereNotNull('demand_to_tender.inspection_status');
                    })
                    ->orderBy('demands.id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('demands.id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->where('demands.requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->where('demands.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->where('demands.when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);
//
            }
            if($prm == 2){
                $demands = \App\DemandToTender::join('demands', 'demands.id', '=', 'demand_to_tender.demand_id')
                    ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                    ->select('demands.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name')
                    ->whereNotNull('demand_to_tender.inspection_status')
                    ->orderBy('demands.id', 'desc');
                    if(!empty($catGrou)){
                        $demands->whereIn('demands.id',$demandIdAcToCat);
                    }
                    if(!empty($demande)){
                        $demands->where('demands.requester','=',$demande);
                    }
                    if(!empty($demand_no)){
                        $demands->where('demands.demand_no','=',$demand_no);
                    }
                    if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                    if(!empty($from)){
                        $demands->where('demands.when_needed','=<',$from);
                    }
                    if(!empty($todate)){
                        $demands->where('demands.when_needed','<=',$todate);
                    }
                $demands = $demands->paginate(10);
                //$demands = Demand::where('inspection_status','!=',NULL)->orderBy('id', 'desc')->paginate(10);
            }
        }
        if($demandDetailPageFromRoute == 'v44-voucher-view-acc'){
            $demands = \App\DemandToTender::join('demands', 'demands.id', '=', 'demand_to_tender.demand_id')
                ->leftJoin('demande_name', 'demande_name.id', '=', 'demands.requester')
                ->select('demands.*','demand_to_tender.tender_id as tenderId','demand_to_tender.tender_number','demande_name.name as demande_name')
                ->whereNotNull('demand_to_tender.inspection_status')
                ->orderBy('demands.id', 'desc');
                if(!empty($catGrou)){
                    $demands->whereIn('demands.id',$demandIdAcToCat);
                }
                if(!empty($demande)){
                    $demands->where('demands.requester','=',$demande);
                }
                if(!empty($demand_no)){
                    $demands->where('demands.demand_no','=',$demand_no);
                }
                if(!empty($tender_no)){
                        $demands->where('demand_to_tender.tender_number','=',$tender_no);
                    }
                if(!empty($from)){
                    $demands->where('demands.when_needed','=<',$from);
                }
                if(!empty($todate)){
                    $demands->where('demands.when_needed','<=',$todate);
                }
            $demands = $demands->paginate(10);
            //$demands = Demand::where('inspection_status','!=',NULL)->where('final_status','=',NUll)->orderBy('id', 'desc')->paginate(10);
        }


        $demandeNames = DemandeName::where('status','=',1)->get();

        \Session::put('demandDetailPageFromRoute', $demandDetailPageFromRoute);

        return View::make('demands.index')->with(compact('demands','demandDetailPageFromRoute','demandeNames','demande','demand_no','from','todate'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $nsdNames = NsdName::where('status_id','=',1)->get();
//        $supplyCategories = SupplyCategory::where('status_id','=',1)->get();
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

        return View::make('demands.create')->with(compact('supplyCategories','nsdNames','destinationPlaces','denos','demandeNames'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        OwnLibrary::validateAccess($this->moduleId,2);
// echo "<pre>"; print_r($request->all()); exit;
        $this->middleware('csrf', array('on' => 'post'));

        $v = \Validator::make($request->all(), [
            'requester'                     => 'required',
            'demand_no'                     => 'required|unique:demands,demand_no',
            //'priority'                      => 'required',
            //'product_detailsetc'            => 'required',
            'machinery_and_manufacturer'    => 'required',
            'serial_or_reg_number'          => 'required',
            'unit'                          => 'required',
            'price'                         => 'required',
        ]);

        if ($v->fails()) {
            return redirect('demand/create')->withErrors($v->errors())->withInput();
        }else {

            if(count($request->machinery_and_manufacturer)>0){

                $image_upload   = TRUE;
                $image_name     = FALSE;

                // Insert to demand table ============================================

                $demands                                = new Demand();

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
                        $itemToDemands              = new ItemToDemand();

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

                Session::flash('success', 'Data Created Successfully');
                return Redirect::to('demand/create');
            }else{
                Session::flash('error', 'Data can not be created.');
                return Redirect::to('demand/create');
            }

            //}
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function demandDetail($id)
    {
        $this->tableAlies = \Session::get('zoneAlise');

        $demand             = Demand::find($id);
        $itemtodemand       = '';
        $itemtodemandappv   = '';
        $catGrou            = Auth::user()->categories_id;

        $routenNameComeOfThePge = \Session::get('demandDetailPageFromRoute');

        if(!empty($demand)){

            $itemtodemand = \App\ItemToDemand::join($this->tableAlies.'_items', $this->tableAlies.'_items.id', '=', 'item_to_demand.item_id')
                ->join('supplycategories',$this->tableAlies.'_items.item_cat_id','=','supplycategories.id')
                ->leftJoin('nsdname','nsdname.id','=','item_to_demand.transfer_to')
                ->select('item_to_demand.*','item_to_demand.unit','supplycategories.name as categoryname','nsdname.name as organizationName')
                //->where('item_to_demand.demand_appv_status','=',1)
                ->where('demand_id','=',$demand->id);
                if(!empty($catGrou)){
                    $catGrou = explode(',',$catGrou);
                    $itemtodemand->whereIn('item_to_demand.group_name',$catGrou);
                }
            $itemtodemand       = $itemtodemand->get();

            $itemtodemandappv = \App\ItemToDemand::join($this->tableAlies.'_items', $this->tableAlies.'_items.id', '=', 'item_to_demand.item_id')
                ->join('supplycategories',$this->tableAlies.'_items.item_cat_id','=','supplycategories.id')
                ->select('item_to_demand.*','item_to_demand.unit','supplycategories.name as categoryname')
                ->where('item_to_demand.demand_appv_status','=',1)
                ->where('demand_id','=',$demand->id);
            if(!empty($catGrou)){
                //$catGrou = explode(',',$catGrou);
                $itemtodemandappv->whereIn('item_to_demand.group_name',$catGrou);
            }
            $itemtodemandappv       = $itemtodemandappv->get();
            //$itemtodemandappv   = $itemtodemand;

            //$itemtodemand = ItemToDemand::where('demand_id','=',$demand->id)->get();
        } 
        $destinationPlaces = array(''=> '- Select -') + NsdName::where('zones','=',Session::get('zoneId'))->where('id','!=',4)->whereNotIn('id',explode(',', Auth::user()->nsd_bsd))->pluck('name','id')->toArray();

        $inspectedItems = DemandCrToInspection::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.id', '=', 'demand_cr_to_inspection.dmd_sup_to_coll_qut_to_itm_id')
            ->where('demand_supplier_to_coll_qut_to_item.demand_id','=',$id)
            ->select('demand_supplier_to_coll_qut_to_item.*', 'demand_cr_to_inspection.*')
            ->get();
            //echo "<pre>"; print_r($inspectedItems); exit;
        

        return View::make('demands.demand-details')->with(compact('demand','itemtodemand','destinationPlaces','inspectedItems','routenNameComeOfThePge','itemtodemandappv'));
    }

    public function demandGroup($id){

        $this->tableAlies = \Session::get('zoneAlise');

        $demand         = Demand::find($id);
        $itemtodemand   = '';

        if(!empty($demand)){

            $itemtodemand = \App\ItemToDemand::join($this->tableAlies.'_items', $this->tableAlies.'_items.id', '=', 'item_to_demand.item_id')
                ->join('supplycategories',$this->tableAlies.'_items.item_cat_id','=','supplycategories.id')
                ->select('item_to_demand.*','item_to_demand.unit','supplycategories.name as categoryname')
                ->where('demand_id','=',$demand->id)
                ->get();

        }

        $updateId = $id;

        // Supplier cagetory =================================================================================
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

        $destinationPlaces = array(''=> '- Select -') + NsdName::where('zones','=',Session::get('zoneId'))->where('id','!=',4)->whereNotIn('id',explode(',', Auth::user()->nsd_bsd))->pluck('name','id')->toArray();

        $denos = \App\Deno::where('status_id','=',1)->get();

        return View::make('demands.group-approve')->with(compact('updateId','demand','itemtodemand','supplyCategories','destinationPlaces','denos'));

    }


    public function postGroupStatusChange(Request $request){

        if(count($request->machinery_and_manufacturer) > 0){

            $demandsUp                              = Demand::find($request->demand_id);
            $demandsUp->total_unit                  = $request->total_unit;
            $demandsUp->total_value                 = $request->total_value;
            $demandsUp->group_status                = $request->group_status;
            $demandsUp->current_status              = 3;
            $demandsUp->group_status_check_by       = Auth::user()->id;
            $demandsUp->group_status_check_date     = date('Y-m-d H:i:s');
            if(isset($request->transfer_to)){
                $demandsUp->transfer_to             = $request->transfer_to;
                $demandsUp->transfer_date           = date('Y-m-d H:i:s');
            }
            if(isset($request->transfer_status)){
                $demandsUp->transfer_status         = $request->transfer_status;
                if($request->transfer_status==1){
                    $demandsUp->group_status        = 1;
                    $demandsUp->plr_status          = 4;
                }
                if($request->transfer_status==2){
                    $demandsUp->plr_status          = $request->plr_status;
                    $demandsUp->plr_status_check_by = Auth::user()->id;
                    $demandsUp->plr_date            = date('Y-m-d H:i:s');
                    
                    $demandsUp->group_status        = 2;
                }
            }else{
                $demandsUp->transfer_status     = $request->transfer_status;
                $demandsUp->plr_status          = null;
            }

            $demandsUp->save();

            for($i=0; count($request->machinery_and_manufacturer)>$i; $i++){

                $presentRowsIds     = array_map('current',ItemToDemand::select('id')->where('demand_id','=',$request->demand_id)->get()->toArray());
                $updateRowsIds      = $request->itemtodemandid;

                ItemToDemand::whereIn('id', $presentRowsIds)->whereNotIn('id',$updateRowsIds)->delete();

                if(isset($request->itemtodemandid[$i])){
                    $itemToDemands              = ItemToDemand::find($request->itemtodemandid[$i]);
                }else{
                    $itemToDemands              = new ItemToDemand();
                }
                $itemToDemands->demand_id       = $request->demand_id;
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
                $itemToDemands->sub_total       = $request->sutotal_price[$i];
                //$itemToDemands->current_status  = 3;
                $itemToDemands->save();
            }



            Session::flash('success', 'Data Updated Successfully');
            return Redirect::to('demand-details/' . $request->demand_id);

        }else{
            Session::flash('error', 'Data can not be update');
            return Redirect::to('demand-details/' . $request->demand_id);
        }

    }

    public function oicGroupChange($id){

        $this->tableAlies = \Session::get('zoneAlise');

        $demandtolpr        = \App\DemandToLpr::find($id);
        $itemtodemand       = '';
        $itemtodemandappv   = '';
        $catGrou            = Auth::user()->categories_id;

        $routenNameComeOfThePge = \Session::get('demandDetailPageFromRoute');

        if(!empty($demandtolpr)){
            $demandIds = explode(',', $demandtolpr->demand_ids);
            $demandsInfo = Demand::join('demande_name', 'demande_name.id', '=', 'demands.requester')
                        ->select('demands.*','demande_name.name as demandingName')
                        ->whereIn('demands.id',$demandIds)
                        ->get();

            $itemtodemand = \App\ItemToDemand::join($this->tableAlies.'_items', $this->tableAlies.'_items.id', '=', 'item_to_demand.item_id')
                ->join('supplycategories',$this->tableAlies.'_items.item_cat_id','=','supplycategories.id')
                ->leftJoin('nsdname','nsdname.id','=','item_to_demand.transfer_to')
                ->select('item_to_demand.*','item_to_demand.unit','supplycategories.name as categoryname','nsdname.name as organizationName')
                //->where('item_to_demand.demand_appv_status','=',1)
                ->where('lpr_id','=',$demandtolpr->id);
                if(!empty($catGrou)){
                    $catGrou = explode(',',$catGrou);
                    $itemtodemand->whereIn('item_to_demand.group_name',$catGrou);
                }
            $itemtodemand       = $itemtodemand->get();

            $itemtodemandappv = \App\ItemToDemand::join($this->tableAlies.'_items', $this->tableAlies.'_items.id', '=', 'item_to_demand.item_id')
                ->join('supplycategories',$this->tableAlies.'_items.item_cat_id','=','supplycategories.id')
                ->select('item_to_demand.*','item_to_demand.unit','supplycategories.name as categoryname')
                ->where('item_to_demand.demand_appv_status','=',1)
                ->where('lpr_id','=',$demandtolpr->id);
            if(!empty($catGrou)){
                //$catGrou = explode(',',$catGrou);
                $itemtodemandappv->whereIn('item_to_demand.group_name',$catGrou);
            }
            $itemtodemandappv       = $itemtodemandappv->get();
            //$itemtodemandappv   = $itemtodemand;

            //$itemtodemand = ItemToDemand::where('demand_id','=',$demand->id)->get();
        } 
        $destinationPlaces = array(''=> '- Select -') + NsdName::where('zones','=',Session::get('zoneId'))->where('id','!=',4)->whereNotIn('id',explode(',', Auth::user()->nsd_bsd))->pluck('name','id')->toArray();

        $inspectedItems = DemandCrToInspection::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.id', '=', 'demand_cr_to_inspection.dmd_sup_to_coll_qut_to_itm_id')
            ->where('demand_supplier_to_coll_qut_to_item.demand_id','=',$id)
            ->select('demand_supplier_to_coll_qut_to_item.*', 'demand_cr_to_inspection.*')
            ->get();
            //echo "<pre>"; print_r($inspectedItems); exit;
        

        return View::make('demands.oic-approve')->with(compact('demandtolpr','itemtodemand','destinationPlaces','inspectedItems','routenNameComeOfThePge','itemtodemandappv','demandsInfo'));

    }

    public function postOnlyGroupStatusChange(Request $request){

        if($request->sigment==1){

            $demandIds   = explode(',', $request->demand_id);
            $demandtoLpr = $request->demand_to_lpr_id;

            $demandToLprInfo = \App\DemandToLpr::find($demandtoLpr);
                if(!empty($demandToLprInfo)){
                    if($request->group_status==3){
                        $demandToLprInfo->group_status                = 2;
                        $demandToLprInfo->current_status              = 3;
                        $demandToLprInfo->group_status_check_by       = Auth::user()->id;
                        $demandToLprInfo->group_status_check_date     = date('Y-m-d H:i:s');
                        $demandToLprInfo->plr_status                  = 3;
                        $demandToLprInfo->save();
                    }
                    if($request->group_status==2){
                        $demandToLprInfo->group_status                = 2;
                        $demandToLprInfo->current_status              = 3;
                        $demandToLprInfo->group_status_check_by       = Auth::user()->id;
                        $demandToLprInfo->group_status_check_date     = date('Y-m-d H:i:s');
                        $demandToLprInfo->plr_status                  = 2;
                        $demandToLprInfo->save();
                    }
                }
                
            foreach ($demandIds as $did) {
                $demandsUp = Demand::find($did);
                if($request->group_status==3){
                    $demandsUp->group_status                = 2;
                    $demandsUp->current_status              = 3;
                    $demandsUp->group_status_check_by       = Auth::user()->id;
                    $demandsUp->group_status_check_date     = date('Y-m-d H:i:s');
                    $demandsUp->plr_status                  = 3;
                    $demandsUp->save();

                    \DB::table('item_to_demand')
                    ->where('demand_id', $did)
                    ->where('lpr_id', $demandtoLpr)
                    //->where('tender_no', $tenderId)
                    ->update(['plr_status' => 3, 'group_status' =>  2, 'group_status_check_date' => date('Y-m-d H:i:s'), 'group_status_check_by' => Auth::user()->id]);
                }
                if($request->group_status==2){
                    $demandsUp->group_status                = 2;
                    $demandsUp->current_status              = 3;
                    $demandsUp->group_status_check_by       = Auth::user()->id;
                    $demandsUp->group_status_check_date     = date('Y-m-d H:i:s');
                    $demandsUp->plr_status                  = 2;
                    $demandsUp->save();

                    \DB::table('item_to_demand')
                    ->where('demand_id', $did)
                    ->where('lpr_id', $demandtoLpr)

                    ->update(['plr_status' => 2, 'group_status' =>  2, 'group_status_check_date' => date('Y-m-d H:i:s'), 'group_status_check_by' => Auth::user()->id]);
                }
            }

            
            
        }

        if($request->sigment==3){
            $sumValue = array_sum($request->not_in_stock);
            if($sumValue == 0){
                $request->group_status = 1;
            }

            $maxIdOfDemandToLpr = \App\DemandToLpr::max('id')+1;
            if($request->plr_status!=2){
                $demandtolpr        = new \App\DemandToLpr();

                $demandtolpr->lpr_number     = $request->lrp_number;
                $demandtolpr->lpr_uniq_id_for_demand     = $maxIdOfDemandToLpr;

                $demandtolpr->lrp_number     = $request->lrp_number;
                $demandtolpr->lpr_date       = empty($request->lpr_date) ? NULL : date('Y-m-d', strtotime($request->lpr_date));
                $demandtolpr->sample_number     = $request->sample_number;
                $demandtolpr->group_remarks     = $request->group_remarks;

                $demandtolpr->first_group_status                = $request->group_status;
                $demandtolpr->current_status                    = 3;
                $demandtolpr->first_group_status_check_by       = Auth::user()->id;
                $demandtolpr->first_group_status_check_date     = date('Y-m-d H:i:s');
                if(isset($request->transfer_to)){
                    $demandtolpr->transfer_to             = $request->transfer_to;
                    $demandtolpr->transfer_date           = date('Y-m-d H:i:s');
                }else{
                    $demandtolpr->transfer_to             = NULL;
                }
                if(isset($request->transfer_status)){
                    $demandtolpr->transfer_status         = $request->transfer_status;
                    if($request->transfer_status==1){
                        $demandtolpr->first_group_status  = 1;
                        $demandtolpr->plr_status          = 4;
                    }
                    if($request->transfer_status==2){
                        $demandtolpr->plr_status          = $request->plr_status;
                        $demandtolpr->plr_status_check_by = Auth::user()->id;
                        $demandtolpr->plr_date            = date('Y-m-d H:i:s');

                        $demandtolpr->first_group_status  = 2;
                    }
                }else{
                        $demandtolpr->transfer_status     = $request->transfer_status;
                        //$demandsUp->plr_status          = null;
                        $demandtolpr->plr_status          = $request->plr_status;
                    }

                if(array_sum($request->not_in_stock)==0){
                    $demandtolpr->first_group_status  = 1;
                }
                $demandtolpr->save();

            }

            $demandsUp                     = Demand::find($request->demand_id);
            $demandsUp->first_group_status = $request->group_status;
            if(!empty($demandtolpr)){
                $demandsUp->lpr_id     = $demandtolpr->id;
            }
            $demandsUp->lpr_id         = $demandtolpr->id;
            $demandsUp->lrp_number     = $request->lrp_number;
            $demandsUp->lpr_date       = empty($request->lpr_date) ? NULL : date('Y-m-d', strtotime($request->lpr_date));
            $demandsUp->sample_number     = $request->sample_number;
            $demandsUp->group_remarks     = $request->group_remarks;

            $demandsUp->current_status                    = 3;
            $demandsUp->first_group_status_check_by       = Auth::user()->id;
            $demandsUp->first_group_status_check_date     = date('Y-m-d H:i:s');
            if(isset($request->transfer_to)){
                $demandsUp->transfer_to             = $request->transfer_to;
                $demandsUp->transfer_date           = date('Y-m-d H:i:s');
            }else{
                $demandsUp->transfer_to             = NULL;
            }
            if(isset($request->transfer_status)){
                $demandsUp->transfer_status         = $request->transfer_status;
                if($request->transfer_status==1){
                    $demandsUp->first_group_status  = 1;
                    $demandsUp->plr_status          = 4;
                }
                if($request->transfer_status==2){
                    $demandsUp->plr_status          = $request->plr_status;
                    $demandsUp->plr_status_check_by = Auth::user()->id;
                    $demandsUp->plr_date            = date('Y-m-d H:i:s');

                    $demandsUp->first_group_status  = 2;
                }
            }else{
                    $demandsUp->transfer_status     = $request->transfer_status;
                    //$demandsUp->plr_status          = null;
                    $demandsUp->plr_status          = $request->plr_status;
                }

            if(array_sum($request->not_in_stock)==0){
                $demandsUp->first_group_status      = 1;
            }    

            $totalQty   = 0;
            $totalPrice = 0;
            if($demandsUp->save()){

                for($i=0; count($request->item_to_demand)>$i; $i++){
                     $itemToDem = ItemToDemand::find($request->item_to_demand[$i]);

                     if(!empty($demandtolpr)){
                        $itemToDem->lpr_id                  = $demandtolpr->id;
                     }
                     $itemToDem->in_stock                   = $request->in_stock[$i];
                     $itemToDem->not_in_stock               = $request->not_in_stock[$i];
                     $itemToDem->unit                       = $request->not_in_stock[$i];
                     $itemToDem->item_check_by              = Auth::user()->id;
                     $itemToDem->item_check_current_status  = 1;

                     $totalQty   += $request->not_in_stock[$i];
                     $totalPrice += ($request->not_in_stock[$i]*$itemToDem->unit_price);

                     $itemToDem->first_group_status                = $request->group_status;
                     $itemToDem->first_group_status_check_by       = Auth::user()->id;
                     $itemToDem->first_group_status_check_date     = date('Y-m-d H:i:s');

                    // form here ======================================================
                    if(isset($request->transfer_to)){
                        $itemToDem->transfer_to             = $request->transfer_to;
                        $itemToDem->transfer_date           = date('Y-m-d H:i:s');
                    }else{
                        $itemToDem->transfer_to             = NULL;
                    }
                    if(isset($request->transfer_status)){
                        $itemToDem->transfer_status         = $request->transfer_status;
                        if($request->transfer_status==1){
                            $itemToDem->first_group_status        = 1;
                            $itemToDem->plr_status          = 4;
                            $itemToDem->in_stock            = $itemToDem->total_unit;
                            $itemToDem->not_in_stock        = 0;
                        }
                        if($request->transfer_status==2){
                            $itemToDem->plr_status          = $request->plr_status;
                            $itemToDem->plr_status_check_by = Auth::user()->id;
                            $itemToDem->plr_date            = date('Y-m-d H:i:s');

                            $itemToDem->first_group_status        = 2;
                        }
                    }else{
                        $itemToDem->transfer_status     = $request->transfer_status;
                        //$itemToDem->plr_status          = null;
                        $itemToDem->plr_status          = $request->plr_status;
                    }
                    // End here ======================================================

                     if($request->not_in_stock[$i]==0){
                         $itemToDem->first_group_status    = 1;
                         $itemToDem->current_status  = 101;
                     }else{
                         $itemToDem->current_status  = 3;
                     }

                     $itemToDem->save();

                }

            }

            if(!empty($demandtolpr)){
                $demandtolprUpd  = \App\DemandToLpr::find($demandtolpr->id);
                $demandtolprUpd->demand_ids  = $request->demand_id;
                $demandtolprUpd->demand_no   = $demandsUp->demand_no;
                $demandtolprUpd->requester   = $demandsUp->requester;
                $demandtolprUpd->total_unit  = $totalQty;
                $demandtolprUpd->total_value = $totalPrice;
                $demandtolprUpd->save();
            }
        }

        Session::flash('success', 'Data Updated Successfully');
        //return Redirect::to('demand-details/' . $request->demand_id);
        return Redirect::to('group-check-acc/2');

    }


    static function checkErName($id=null){
        $id = $id;

        if(!empty($id)){
            $userName = \App\User::find($id);
            $name = $userName->first_name.' '.$userName->last_name;
            return $name;
        }else{
            return '';
        }

    }

    public function demandGetDemandNo(Request $request){
        $maxDemandId = Demand::max('id');
        $demandenameInfo = DemandeName::find($request->demandeNo);
        $currentYear = date('Y');
        // $maxId = $maxDemandId+1;
        $maxId = str_pad($maxDemandId+1, 4, '0', STR_PAD_LEFT);

        $demanNo = $demandenameInfo->alise.'/'.$maxId.'/'.$currentYear; 
        return $demanNo;
    }

    public function demandGetApprove($id=null){
        $val = explode('&',$id);
        $id = $val[0];
        $upd = $val[1];
        return View::make('demands.demand-approval-view')->with(compact('id','upd'));

    }

    public function demandPendingPost(Request $request){

        $demandsUp  = Demand::find($request->demandId);
        $tenderId   = $request->tenderId;

        $demandToLprUp  = \App\DemandToLpr::find($request->demandId); // update to come demandtolpr id

        if($request->updateFilelds  == 1){
            $demandsUp->demand_appv_status          = $request->demand_approval;
            $demandsUp->approved_by                 = Auth::user()->id;
            $demandsUp->approved_date               = date('Y-m-d H:i:s');
            $demandsUp->current_status              = 2;

            \DB::table('item_to_demand')
                ->where('demand_id', $request->demandId)
                ->update(['demand_appv_status'=>$request->demand_approval, 'approved_by' => Auth::user()->id, 'approved_date' => date('Y-m-d H:i:s'), 'current_status' => 2]);

            if($demandsUp->save()){
                Session::flash('success', 'Data Updated Successfully');
                return Redirect::to('demand-pending/1');
            }else{
                Session::flash('error', 'Data can not be update');
                return Redirect::to('demand-pending/1');
            }    

        }

        if($request->updateFilelds  == 2){

            // For generating pdf==========================================
            // ============================================================
            if($request->demand_approval==1){
                $tenderInfoForPdf = Tender::find($tenderId);
                $appUserInfo      = Auth::user();
                $organizationName = \App\NsdName::where('id','=',$appUserInfo->nsd_bsd)->value('name');

                $itemsInfoDesc = '';
                $lotItemArray  = array();
                if($tenderInfoForPdf->tender_nature==1){
                    $itemsInfoDesc = ItemToDemand::join('deno', 'item_to_demand.deno_id', '=', 'deno.id')
                        ->join(\Session::get('zoneAlise').'_tenders','item_to_demand.tender_no','=',\Session::get('zoneAlise').'_tenders.id')
                        ->join(\Session::get('zoneAlise').'_items','item_to_demand.item_id','=',\Session::get('zoneAlise').'_items.id')
                        ->join('supplycategories','supplycategories.id','=',\Session::get('zoneAlise').'_items.item_cat_id')
                        ->join('demand_to_lpr', 'item_to_demand.lpr_id', '=', 'demand_to_lpr.id')
                        ->leftJoin('nsdname', 'nsdname.id', '=', 'demand_to_lpr.place_to_send')
                        ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
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
                        ->join('demand_to_lpr', 'item_to_demand.lpr_id', '=', 'demand_to_lpr.id')
                        //->join('demands', 'item_to_demand.demand_id', '=', 'demands.id')
                        ->leftJoin('nsdname', 'nsdname.id', '=', 'demand_to_lpr.place_to_send')
                        ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
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
                    $pdf->save(public_path() . '/uploads/tender_spicification_notice_pdf/'.$specificationPdfFileName);
                    $tenderInfoForPdf->notice = $specificationPdfFileName;
                    $tenderInfoForPdf->save();
            }

            // End generating pdf =========================================
            // ============================================================

            $demandIds = explode(',', $demandToLprUp->demand_ids);
            foreach ($demandIds as $diss) {
                $demandUpda = Demand::find($diss);
                $demandUpda->float_tender_app_status     = $request->demand_approval;
                $demandUpda->float_tender_app_by         = Auth::user()->id;
                $demandUpda->float_tender_app_at         = date('Y-m-d H:i:s');
                $demandUpda->current_status              = 5;
                $demandUpda->save();
            }
            
            $demandToLprUp->float_tender_app_status     = $request->demand_approval;
            $demandToLprUp->float_tender_app_by         = Auth::user()->id;
            $demandToLprUp->float_tender_app_at         = date('Y-m-d H:i:s');
            $demandToLprUp->current_status              = 5;

            \DB::table('item_to_demand')
                ->where('lpr_id', $request->demandId)
                ->where('tender_no', $tenderId)
                ->update(['float_tender_app_status' => $request->demand_approval, 'float_tender_app_by' =>  Auth::user()->id, 'float_tender_app_at' => date('Y-m-d H:i:s'), 'current_status' => 5]);

            \DB::table('demand_to_tender')
                ->where('lpr_id', $request->demandId)
                ->where('tender_id', $tenderId)
                ->update(['float_tender_app_status' => $request->demand_approval, 'float_tender_app_by' =>  Auth::user()->id, 'float_tender_app_at' => date('Y-m-d H:i:s'), 'current_status' => 5]);

            if($demandToLprUp->save()){
                Session::flash('success', 'Data Updated Successfully');
                return Redirect::to('floating-tender-acc/3');
            }else{
                Session::flash('error', 'Data can not be update');
                return Redirect::to('floating-tender-acc/3');
            }    

        }

        if($request->updateFilelds  == 3){
            $demandsUp->coll_quat_app_status        = $request->demand_approval;
            $demandsUp->coll_quat_app_by            = Auth::user()->id;
            $demandsUp->coll_quat_app_at            = date('Y-m-d H:i:s');
            $demandsUp->current_status              = 7;

            \DB::table('item_to_demand')
                ->where('demand_id', $request->demandId)
                ->where('tender_no', $tenderId)
                ->update(['coll_quat_app_status' => $request->demand_approval, 'coll_quat_app_by' =>  Auth::user()->id, 'coll_quat_app_at' => date('Y-m-d H:i:s'), 'current_status' => 7]);

            \DB::table('demand_to_tender')
                ->where('demand_id', $request->demandId)
                ->where('tender_id', $tenderId)
                ->update(['coll_quat_app_status' => $request->demand_approval, 'coll_quat_app_by' =>  Auth::user()->id, 'coll_quat_app_at' => date('Y-m-d H:i:s'), 'current_status' => 7]);

            if($demandsUp->save()){
                Session::flash('success', 'Data Updated Successfully');
                return Redirect::to('collection-quotation-acc/3');
            }else{
                Session::flash('error', 'Data can not be update');
                return Redirect::to('collection-quotation-acc/3');
            }    

        }
        
        

    }

    public function directItemDmndCreate(){

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
            $supplyCategories->whereIn('id',[1,2,3,12]);
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
        $currentYear  = ( date('m') > 6) ? date('y').'-'.(date('y') + 1) : (date('y') - 1).'-'.date('y');
        $extraNum     = '23.02.2608.212.53.000.';
        $tenderNoFor  = $extraNum.$currentYear.'.'.$maxId;

        $tenderTearmsAndConditions = \App\TermsCondition::where('status','=',1)->get();

        return View::make('demands.direct-item-dmnd-create')->with(compact('supplyCategories','nsdNames','destinationPlaces','denos','demandeNames','tenderNoFor','tenderTearmsAndConditions'));

    }

    public function postDirectItemDmnd(Request $request)
    { 
        OwnLibrary::validateAccess($this->moduleId,17);
        $this->middleware('csrf', array('on' => 'post'));

            if(count($request->machinery_and_manufacturer)>0){

                // Inset in to demand_to_lpr table
                $maxIdOfDemandToLpr = \App\DemandToLpr::max('id')+1;
                $demandtolpr        = new \App\DemandToLpr();

                $demandtolpr->lpr_uniq_id_for_demand     = $maxIdOfDemandToLpr;

                $demandtolpr->total_unit                    = $request->total_unit;
                $demandtolpr->total_value                   = $request->total_value;
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

                // Insert to demand table ============================================

                $demands                                = new Demand();

                $demands->total_unit                    = $request->total_unit;
                $demands->total_value                   = $request->total_value;
                $demands->demand_entry_by               = Auth::user()->id;
                $demands->demand_entry_date             = date('Y-m-d H:i:s');
                $demands->current_status                = 3;
                $demands->demand_type                   = 2;
                $demands->group_status                  = 2;
                $demands->group_status_check_by       = Auth::user()->id;
                $demands->group_status_check_date     = date('Y-m-d H:i:s');
                $demands->plr_status                    = 3;
                $demands->lpr_id                      = $demandtolpr->id;

                if ($demands->save()) {
                    $demandsUp                       = Demand::find($demands->id);
                    $demandsUp->uniqe_for_all_org    = $demands->id;
                    $demandsUp->save();


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
                    $tender->lpr_id = $demandtolpr->id;

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
                        
                        $updateTen = Tender::find($tender->id);
                        $updateTen->all_org_tender_id = $tender->id;
                        $updateTen->demand_no         = $demands->id;
                        // $updateTen->notice            = $specificationPdfFileName;
                        $updateTen->notice            = $updateTen->notice;
                        $updateTen->save();

                        $demandups = \App\Demand::find($demands->id);

                        $demandups->demand_appv_status   = 1;
                        $demandups->approved_by          = Auth::user()->id;
                        $demandups->approved_date        = date('Y-m-d H:i:s');

                        $demandups->current_status       = 4;
                        $demandups->tender_floating      = 1;
                        $demandups->tender_floating_by   = Auth::user()->id;
                        $demandups->tender_floating_date = date('Y-m-d H:i:s');
                        $demandups->tender_id            = $tender->id;
                        $demandups->save();

                        $demandToTender = new \App\DemandToTender();

                        $demandToTender->lpr_id          = $demandtolpr->id;
                        $demandToTender->demand_id       = $demands->id;
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
                            $createLotName->demand_id = $demands->id;
                            $createLotName->lpr_id    = $demandtolpr->id;
                            $createLotName->tender_id = $tender->id;
                            $createLotName->save();

                            $lotValueFromDb[$createLotName->id] = $createLotName->lot_name;
                        }

                    } 

                    for($i=0; count($request->machinery_and_manufacturer)>$i; $i++){
                        $itemToDemands              = new ItemToDemand();

                        $itemToDemands->lpr_id          = $demandtolpr->id;
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
                        $itemToDemands->in_stock        = 0;
                        $itemToDemands->not_in_stock    = $request->unit[$i];
                        $itemToDemands->unit            = $request->unit[$i];
                        $calCulationOfUnit += $request->unit[$i];
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
                    $demandupsAg = \App\Demand::find($demands->id);
                    $demandupsAg->total_unit  = $calCulationOfUnit;
                    $demandupsAg->save();

                    $demandtolprUpdate = \App\DemandToLpr::find($demandtolpr->id);
                    $demandtolprUpdate->total_unit  = $calCulationOfUnit;
                    $demandtolprUpdate->tender_id   = $tender->id;
                    $demandtolprUpdate->demand_ids  = $demands->id;
                    $demandtolprUpdate->save();
                    
                    // \DB::table('item_to_demand')
                    // ->where('demand_id', $demands->id)
                    // ->update(['demand_appv_status'=>1, 'approved_by' => Auth::user()->id, 'approved_date' => date('Y-m-d H:i:s'), 'current_status' => 2]);

                    Session::flash('success', 'Data Created Successfully');
                    return Redirect::to('direct-item-dmnd-create');

                } // end if demand save

                
            }else{
                Session::flash('error', 'Data can not be created.');
                return Redirect::to('floating-tender-acc/1');
            }

            //}
    }

    public function demandGetPrint($id){
        $zoneAlise = \Session::get("zoneAlise");

        $demands = DB::table('item_to_demand')
                    ->leftJoin( $zoneAlise.'_items','item_to_demand.item_id','=', $zoneAlise.'_items.id')
                    ->leftJoin( 'deno','item_to_demand.deno_id','=', 'deno.id')
                    ->leftJoin( $zoneAlise.'_itemtotender',function ($itemtotender){
                        $itemtotender->on(\Session::get("zoneAlise").'_items.all_org_item_id','=', \Session::get("zoneAlise").'_itemtotender.item_id')->orderBy(\Session::get("zoneAlise").'_items.id','desc')->take(1);
                    })
                    ->leftJoin( $zoneAlise.'_tenders',$zoneAlise.'_itemtotender.tender_id','=', $zoneAlise.'_tenders.all_org_tender_id')
                    ->leftJoin( $zoneAlise.'_suppliers',$zoneAlise.'_tenders.supplier_id','=', $zoneAlise.'_suppliers.all_org_id')
                    ->leftJoin( 'demand_to_lpr','item_to_demand.lpr_id','=', 'demand_to_lpr.id')
                    ->leftJoin( 'demande_name','demand_to_lpr.requester','=', 'demande_name.id')
                    ->leftJoin( 'supplycategories','item_to_demand.group_name','=', 'supplycategories.id')
                    ->where('item_to_demand.lpr_id','=',$id)->orderBy('group_name')
                    ->select('item_to_demand.id as item_to_demand_id',
                        'item_to_demand.demand_id as item_to_demand_demand_id',
                        'item_to_demand.group_name as item_to_demand_group_name',
                        'item_to_demand.item_id as item_to_demand_item_id',
                        'item_to_demand.deno_id as item_to_demand_deno_id',
                        'item_to_demand.created_at as item_to_demand_created_at',
                        'item_to_demand.in_stock as item_to_demand_in_stock',
                        'item_to_demand.total_unit as item_to_demand_total_unit',
                        $zoneAlise.'_items.id as items_id',
                        $zoneAlise.'_items.all_org_item_id as item_all_org_item_id',
                        $zoneAlise.'_items.item_name as item_item_name',
                        $zoneAlise.'_items.model_number as item_model_number',
                        $zoneAlise.'_items.unit_price  as item_unit_price',
                        $zoneAlise.'_items.manufacturing_country  as item_manufacturing_country',
                        'deno.id  as deno_id',
                        'deno.name  as deno_name',
                        $zoneAlise.'_itemtotender.id  as itemtotender_id',
                        $zoneAlise.'_itemtotender.item_id  as itemtotender_item_id',
                        $zoneAlise.'_itemtotender.unit_price  as itemtotender_unit_price',
                        $zoneAlise.'_itemtotender.quantity  as itemtotender_quantity',
                        $zoneAlise.'_tenders.id  as tenders_id',
                        $zoneAlise.'_tenders.all_org_tender_id  as tenders_all_org_tender_id',
                        $zoneAlise.'_tenders.po_number  as tenders_po_number',
                        $zoneAlise.'_tenders.supplier_id  as tenders_supplier_id',
                        $zoneAlise.'_tenders.valid_date_from  as tenders_purchase_order_date',
                        $zoneAlise.'_suppliers.id  as suppliers_id',
                        $zoneAlise.'_suppliers.company_name  as suppliers_company_name',
                        'demand_to_lpr.id  as demands_id',
                        'demand_to_lpr.demand_no  as demands_demand_no',
                        'demand_to_lpr.when_needed  as demands_Date',
                        'demand_to_lpr.requester  as demands_requester',
                        'demand_to_lpr.pattern_or_stock_no  as demands_demand_authority',
                        'demand_to_lpr.priority  as demands_priority',
                        'demand_to_lpr.lpr_number  as demands_lpr_number',
                        'demand_to_lpr.group_remarks  as demands_group_remarks',
                        'demande_name.id  as demande_name_id',
                        'demande_name.name  as demande_name_name',
                        'supplycategories.id  as supplycategories_id',
                        'supplycategories.name  as supplycategories_name'
                    ) ->get();

                    $demandsInfo = \App\DemandToLpr::find($id);
                    $firstApprovalInfo = '';
                    $organizationName = '';
                    if(!empty($demandsInfo->first_group_status_check_by)){
                        $firstApprovalInfo = \App\User::find($demandsInfo->first_group_status_check_by);
                        $organizationName = \App\NsdName::where('id','=',$firstApprovalInfo->nsd_bsd)->value('name');
                    }
                    $secondApprovalInfo = '';
                    if(!empty($demandsInfo->group_status_check_by)){
                        $secondApprovalInfo = \App\User::find($demandsInfo->group_status_check_by);
                        $organizationName = \App\NsdName::where('id','=',$secondApprovalInfo->nsd_bsd)->value('name');
                    }


                    $demands = [
                        'demands'=>$demands,
                        'firstApprovalInfo'=>$firstApprovalInfo,
                        'secondApprovalInfo'=>$secondApprovalInfo,
                        'organizationName'=>$organizationName
                    ];

            
        $pdf = PDF::loadView('demands.demandsGetPrint',$demands,[],['format' => [215.9, 342.9],'orientation' => 'L']);
        return $pdf->stream('demandsGetPrint.pdf');
     //return view('demands.demandsGetPrint')->with('demands',$demands);
    }

    // Previous print function

    // public function storeDemandPrint($id){
    //     $demands = Demand::with('demandeNameInDemand','navalocation_name')->find($id);
    //     $approverInfo = '';

    //     if($demands->demand_appv_status==1){
    //         $approverInfo = \App\User::find($demands->approved_by);
    //     }

    //     $demands = [
    //         'demands'=>$demands,
    //         'approverInfo'=>$approverInfo
    //     ];

    //     $pdf = PDF::loadView('demands.store-demand-print',$demands,[],['format' => [215.9, 342.9]]);
    //     return $pdf->stream('store-demand-print.pdf');
    // }

    public function storeDemandPrint($id){
    	$demand_item = ItemToDemand::where('demand_id','=',$id)->get();

    	if(count($demand_item) > 1){

		    $demands = Demand::with('demandeNameInDemand','navalocation_name')->find($id);
		    $approverInfo = '';
		    $itemTable = \Session::get("zoneAlise").'_items';
		    $itemCategoryTable = 'supplycategories';
		    $itemDenoTable = 'deno';

		    if($demands->demand_appv_status==1){
			    $approverInfo = \App\User::find($demands->approved_by);
		    }
			$itemLists = ItemToDemand::where('demand_id','=',$id)
			            ->select('item_to_demand.in_stock as item_to_demand_in_stock','item_to_demand.unit_price as item_to_demand_unit_price','item_to_demand.unit as item_to_demand_unit',$itemTable.'.item_name as item_item_name',$itemTable.'.patt_number as item_patt_number',$itemTable.'.manufacturer_name as item_manufacturer_name',$itemCategoryTable.'.name as category_name',$itemDenoTable.'.name as deno_name')
			            ->leftJoin($itemTable,'item_to_demand.id' , '=',$itemTable.'.id')
			            ->leftJoin($itemCategoryTable,$itemTable.'.item_cat_id' , '=',$itemCategoryTable.'.id')
			            ->leftJoin($itemDenoTable,$itemTable.'.item_deno' , '=',$itemDenoTable.'.id')
			            ->orderBy($itemCategoryTable.'.name','ASC')->get();

//		    dd($itemList);

		    $values = [
			    'demands'=> $demands,
			    'approverInfo'=> $approverInfo,
			    'itemLists' => $itemLists
		    ];

//		    dd($demands);

			$pdf = PDF::loadView('demands.store-demand-long-print',$values,[],['format' => [215.9, 342.9],'orientation' => 'L']);
		    return $pdf->stream('store-demand-long-print.pdf');

//		    return view('demands.store-demand-long-print',$values);
	    }

        else{
	        $demands = Demand::with('demandeNameInDemand','navalocation_name')->find($id);
	        $approverInfo = '';

	        if($demands->demand_appv_status==1){
		        $approverInfo = \App\User::find($demands->approved_by);
	        }

	        $demands = [
		        'demands'=>$demands,
		        'approverInfo'=>$approverInfo
	        ];
//	        dd($demands);
	        $pdf = PDF::loadView('demands.store-demand-print',$demands,[],['format' => [215.9, 342.9]]);
	        return $pdf->stream('store-demand-print.pdf');
	    }

    }

}
