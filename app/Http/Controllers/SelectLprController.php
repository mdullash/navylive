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

class SelectLprController extends Controller
{

    private $moduleId = 34;
    private $tableAlies;

    public function __construct() {

    }
    
    public function postSelectAsLpr(){

        $this->tableAlies   = \Session::get('zoneAlise');

        $demandIds          = \Input::get('select_as_lpr');
        $itemtodemand       = '';
        $itemtodemandappv   = '';
        $catGrou            = Auth::user()->categories_id;
        $demand             = '';

        if(!empty($demandIds)){
            $demandsInfo = Demand::join('demande_name', 'demande_name.id', '=', 'demands.requester')
                        ->select('demands.*','demande_name.name as demandingName')
                        ->whereIn('demands.id',$demandIds)
                        ->get();

            \Session::put('demandDetailPageFromRoute', 'group-check-acc');
            $routenNameComeOfThePge = \Session::get('demandDetailPageFromRoute');

            if(!empty($demandIds)){

                $itemtodemand = \App\ItemToDemand::join($this->tableAlies.'_items', $this->tableAlies.'_items.id', '=', 'item_to_demand.item_id')
                    ->join('supplycategories',$this->tableAlies.'_items.item_cat_id','=','supplycategories.id')
                    ->leftJoin('nsdname','nsdname.id','=','item_to_demand.transfer_to')
                    ->select('item_to_demand.*','item_to_demand.unit','supplycategories.name as categoryname','nsdname.name as organizationName')
                    //->where('item_to_demand.demand_appv_status','=',1)
                    ->whereIn('demand_id',$demandIds);
                    if(!empty($catGrou)){
                        $catGrou = explode(',',$catGrou);
                        $itemtodemand->whereIn('item_to_demand.group_name',$catGrou);
                    }
                $itemtodemand       = $itemtodemand->get();

                $itemtodemandappv = \App\ItemToDemand::join($this->tableAlies.'_items', $this->tableAlies.'_items.id', '=', 'item_to_demand.item_id')
                    ->join('supplycategories',$this->tableAlies.'_items.item_cat_id','=','supplycategories.id')
                    ->select('item_to_demand.*','item_to_demand.unit','supplycategories.name as categoryname')
                    ->where('item_to_demand.demand_appv_status','=',1)
                    ->whereIn('demand_id',$demandIds);
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
                ->whereIn('demand_supplier_to_coll_qut_to_item.demand_id',$demandIds)
                ->select('demand_supplier_to_coll_qut_to_item.*', 'demand_cr_to_inspection.*')
                ->get();
                //echo "<pre>"; print_r($inspectedItems); exit;
            

            return View::make('lpr-select.demand-details')->with(compact('demand','itemtodemand','destinationPlaces','inspectedItems','routenNameComeOfThePge','itemtodemandappv','demandsInfo','demandIds'));
        }else{
            Session::flash('error', 'Select at least one demand');
            return Redirect::to('group-check-acc/3');
        }
        

    }

    public function insertSelectAsLpr(Request $request){

        if($request->sigment==1){
            $demandsUp                              = Demand::find($request->demand_id);
            if($request->group_status==3){
                $demandsUp->group_status                = 2;
                $demandsUp->current_status              = 3;
                $demandsUp->group_status_check_by       = Auth::user()->id;
                $demandsUp->group_status_check_date     = date('Y-m-d H:i:s');
                $demandsUp->plr_status                  = 3;
                $demandsUp->save();

                \DB::table('item_to_demand')
                ->where('demand_id', $request->demand_id)
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
                ->where('demand_id', $request->demand_id)
                //->where('tender_no', $tenderId)
                ->update(['plr_status' => 2, 'group_status' =>  2, 'group_status_check_date' => date('Y-m-d H:i:s'), 'group_status_check_by' => Auth::user()->id]);
            }
            
        }

        if($request->sigment==3){
            $demandIds = json_decode(htmlspecialchars_decode($request->demand_id));
            
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

                $demandtolpr->demand_appv_status                = 1;
                $demandtolpr->approved_by       = Auth::user()->id;
                $demandtolpr->approved_date     = date('Y-m-d H:i:s');
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

            $requesters = array();
            $demandnumbers = array();
            if(!empty($demandIds) && count($demandIds)>0){
                foreach ($demandIds as $vid) {
                    $demandsUp                                    = Demand::find($vid);
                    $demandsUp->first_group_status                = $request->group_status;

                    if(!empty($demandtolpr)){
                        $demandsUp->lpr_id     = $demandtolpr->id;
                    }
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
                        $demandsUp->first_group_status  = 1;
                    }    
                    if(!empty($demandsUp->requester)){
                        $requesters[] = $demandsUp->requester;
                    }
                    if(!empty($demandsUp->demand_no)){
                        $demandnumbers[] = $demandsUp->demand_no;
                    }
                    
                    $demandsUp->save();
                } // end demand table update foreach
                
            } // end demand table if

            $totalQty   = 0;
            $totalPrice = 0;
            for($i=0; count($request->item_to_demand)>$i; $i++){
                 $itemToDem = ItemToDemand::find($request->item_to_demand[$i]);

                 if(!empty($demandtolpr)){
                    $itemToDem->lpr_id                     = $demandtolpr->id;
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

            } // end of for loop
            $whenNeededs = date('Y-m-d');
            if(!empty($request->lpr_date)){
                $whenNeededs = date('Y-m-d',strtotime($request->lpr_date));
            }

            if(!empty($demandtolpr)){
                $demandtolprUpd  = \App\DemandToLpr::find($demandtolpr->id);
                $demandtolprUpd->demand_ids  = empty($requesters) ? NULL : implode(',',$demandIds);
                $demandtolprUpd->demand_no   = empty($requesters) ? NULL : implode(',',$demandnumbers);
                $demandtolprUpd->requester   = empty($requesters) ? NULL : implode(',',$requesters);
                $demandtolprUpd->total_unit  = $totalQty;
                $demandtolprUpd->total_value = $totalPrice;
                $demandtolprUpd->when_needed = $whenNeededs;
                $demandtolprUpd->save();
            }
            

        } // end of sigment == 3

        Session::flash('success', 'Data Updated Successfully');
        //return Redirect::to('demand-details/' . $request->demand_id);
        return Redirect::to('group-check-acc/2');

    }

    public static function requestename($id){
        $requeName = \App\DemandeName::where('id','=',$id)->value('name');
        return $requeName;
    }



}
