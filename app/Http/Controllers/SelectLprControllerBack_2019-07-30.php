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
    
    public function postSelectAsLpr(Request $request){

        $this->tableAlies   = \Session::get('zoneAlise');

        $demandIds          = $request->select_as_lpr;
        $itemtodemand       = '';
        $itemtodemandappv   = '';
        $catGrou            = Auth::user()->categories_id;
        $demand             = '';

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
        

        return View::make('lpr-select.demand-details')->with(compact('demand','itemtodemand','destinationPlaces','inspectedItems','routenNameComeOfThePge','itemtodemandappv','demandsInfo'));

    }



}
