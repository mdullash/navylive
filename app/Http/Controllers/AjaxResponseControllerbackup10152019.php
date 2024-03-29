<?php

namespace App\Http\Controllers;

use App\SupplierMultiInfo;
use Illuminate\Http\Request;
use functions\OwnLibrary;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use View;
use DB;
use Auth;
use Session;
use App\NsdName;
use App\Supplier;
use App\Item;
use App\SupplierBasicInfo;
use Illuminate\Support\Facades\Validator;

class AjaxResponseController extends Controller  {

    private $tableAlies;

    public function zoneWiseNsdBsd(Request $request){

        $nsdBsd = \App\NsdName::where('status_id','=',1)->get();

        $selectedZones = $request->zones;

        $nsdBsdIds = array();

        foreach($nsdBsd as $nb){
            $abcdef = explode(',', $nb->zones);

            foreach($abcdef as $abc){
                if(in_array($abc, $selectedZones)){
                    $nsdBsdIds[] = $nb->id;
                }
            }

        }

        // Newly added ===================================
         
        $user_role    = \App\Role::find(Auth::user()->role_id); 
        $user_role_id = Auth::user()->role_id; 
        $user_piority = $user_role->priority;

        $userSelOrg = '';
        if($user_piority>=3){
            $userSelOrg = explode(',',Auth::user()->nsd_bsd);
        }
        //$nsdsBsdsAll = \App\NsdName::whereIn('id',$nsdBsdIds)->get();

        //  End newly added ===========================
        $nsdsBsdsAll = \App\NsdName::where('id','!=',null);
                        if(!empty($userSelOrg) && $user_piority>=3){
                            $nsdsBsdsAll->whereIn('id',$userSelOrg);
                        }else{
                            $nsdsBsdsAll->whereIn('id',$nsdBsdIds);
                        }
        $nsdsBsdsAll = $nsdsBsdsAll->get();
        // End nely added

        $zoneWiseNsdBsd = '<option value="" disabled="">- Select -</option>';

        foreach($nsdsBsdsAll as $nsbs){
            $zoneWiseNsdBsd .='<option value="'.$nsbs->id.'">'.$nsbs->name.'</option>';
        }

        $data['zonewosensbsd'] = $zoneWiseNsdBsd;

        // Categories section ==========================
        $categories = \App\SupplyCategory::where('status_id','=',1)->get();
        $categoriesIds = array();

        foreach($categories as $ct){
            $abcdefgh = explode(',', $ct->zones);
            foreach($abcdefgh as $abcd){
                if(in_array($abcd, $selectedZones)){
                    $categoriesIds[] = $ct->id;
                }
            }
        }

        $categoriesAll = \App\SupplyCategory::whereIn('id',$categoriesIds)->get();

        $zoneWiseCat = '<option value="" disabled="">- Select -</option>';
        foreach($categoriesAll as $ctl){
            $zoneWiseCat .='<option value="'.$ctl->id.'">'.$ctl->name.'</option>';
        }
        $data['zonewisecat'] = $zoneWiseCat;

        return $data;

    }

    public function SingleZoneWiseNsdBsd(Request $request){

        $selectedZones = $request->zones;
        $nsdBsd = \App\NsdName::where('status_id','=',1)->get();
        $nsdBsdIds = array();

        foreach($nsdBsd as $nb){
            $abcdef = explode(',', $nb->zones);
            foreach($abcdef as $abc){
                if($abc == $selectedZones){
                    $nsdBsdIds[] = $nb->id;
                }
            }
        }

        $nsdsBsdsAll = \App\NsdName::whereIn('id',$nsdBsdIds)->get();
        $zoneWiseNsdBsd = '<option value="">- Select -</option>';
        foreach($nsdsBsdsAll as $nsbs){
            $zoneWiseNsdBsd .='<option value="'.$nsbs->id.'">'.$nsbs->name.'</option>';
        }

        $data['zonewosensbsd'] = $zoneWiseNsdBsd;
        return $data;

    }

    public function awardedRepSupplierNameLiveSearch(Request $request){

        $this->tableAlies = \Session::get('zoneAlise');

        if($request->get('query'))
        {
            $query = $request->get('query');

            // Nsd name ================================================================
            $nsdNames = NsdName::where('status_id','=',1)->get();
            $zonesRltdIds = array();
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

            // Supplier name ============================================================

            $AllSuppliers = Supplier::where('status_id','=',1)->get();
            $zonesRltdIdsss = array();
            foreach($AllSuppliers as $spl){
                foreach(explode(',',$spl->registered_nsd_id) as $rni){

                    if(in_array($rni, $zonesRltdIds)){
                        if(!empty(Auth::user()->categories_id)){
                            $userWiseCat = explode(',', Auth::user()->categories_id);
                            foreach ($userWiseCat as $uwc){
                                if(in_array($uwc, explode(',',$spl->supply_cat_id))){
                                    $zonesRltdIdsss[] = $spl->id;
                                }
                            }
                        }else{
                            $zonesRltdIdsss[] = $spl->id;
                        }
                        //$zonesRltdIdsss[] = $spl->id;
                    }

                }
            }

            $suppliersName  = Supplier::select('id','company_name')->whereIn('id',$zonesRltdIdsss)
                ->where('company_name', 'LIKE', "%{$query}%")->limit(20)->get();

            //$data = \App\Supplier::select('id','company_name')->where('company_name', 'LIKE', "%{$query}%")
                //->limit(20)->get();
                //
            
            if(count($suppliersName)<1){
                $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
                $output .= '<li>No match found</li>';
                $output .= '</ul>';
                return $output;
            }

            $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
            foreach($suppliersName as $row)
            {
                $output .= '<li class="searchSuppName" value="'.$row->id.'"><a href="javascript:void(0)">'.$row->company_name.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    public function awardedRepItemNameLiveSearch(Request $request){

        $this->tableAlies = \Session::get('zoneAlise');

        if($request->get('query'))
        {
            $query = $request->get('query');

            // Nsd name ================================================================
            $nsdNames = NsdName::where('status_id','=',1)->get();
            $zonesRltdIds = array();
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

            // Supplier name ============================================================

            $AllSuppliers = Supplier::where('status_id','=',1)->get();
            $zonesRltdIdsss = array();
            foreach($AllSuppliers as $spl){
                foreach(explode(',',$spl->registered_nsd_id) as $rni){

                    if(in_array($rni, $zonesRltdIds)){
                        $zonesRltdIdsss[] = $spl->id;
                    }

                }
            }

            $itemList = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.all_org_tender_id')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.all_org_item_id','=',$this->tableAlies.'_itemtotender.item_id')
                ->select($this->tableAlies.'_items.id',$this->tableAlies.'_items.item_name')
                ->whereIn($this->tableAlies.'_tenders.nsd_id',$zonesRltdIds);
                if(!empty(Auth::user()->categories_id)){
                    $itemList->whereIn($this->tableAlies.'_tenders.tender_cat_id',explode(',', Auth::user()->categories_id));
                }
                $itemList->where($this->tableAlies.'_items.item_name', 'LIKE', "%{$query}%")->limit(20);
                $itemList = $itemList->get();

            //$suppliersName  = Supplier::select('id','company_name')->whereIn('id',$zonesRltdIdsss)
                //->where('company_name', 'LIKE', "%{$query}%")->limit(20)->get();

            //$data = \App\Supplier::select('id','company_name')->where('company_name', 'LIKE', "%{$query}%")
            //->limit(20)->get();
            
            if(count($itemList)<1){
                $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
                $output .= '<li>No match found</li>';
                $output .= '</ul>';
                return $output;
            }
            $itemList = $itemList->unique();
            $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
            foreach($itemList as $row)
            {
                $output .= '<li class="searchItemName" value="'.$row->id.'"><a href="javascript:void(0)">'.$row->item_name.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }

    }

    public function itemsForItemToTender(Request $request){
        $this->tableAlies = \Session::get('zoneAlise');

        if($request->get('query'))
        {

            $query = $request->get('query');

            $allItemIdsUnderNsd = '';
            $allItemIdsUnderNsdArray = array();
            $nsdids = \App\Item::select('id','nsd_id','item_cat_id')->where('status_id','=',1)->get();
            foreach ($nsdids as $ni) {

                if (!empty(Session::get('nsdBsdEmptyOrNot')) && in_array(Session::get('zoneId'),explode(',',Auth::user()->zones))) {
                    foreach (explode(',', Session::get('nsdBsdEmptyOrNot')) as $val) {
                        if (in_array($val, explode(',', $ni->nsd_id))) {

                            if(!empty(Auth::user()->categories_id)){
                                $userWiseCat = explode(',', Auth::user()->categories_id);
                                if(in_array($ni->item_cat_id, $userWiseCat)){ 
                                    $allItemIdsUnderNsdArray[] = $ni->id;
                                }
                            }else{
                                $allItemIdsUnderNsdArray[] = $ni->id;
                            }
                            //$allItemIdsUnderNsdArray[] = $ni->id;
                        }
                    }
                } // If end
                $allItemIdsUnderNsd = array_unique($allItemIdsUnderNsdArray);
            }// End foreach

            if(!empty($allItemIdsUnderNsd) || !empty(Auth::user()->categories_id)){
                $items = Item::whereIn('id',$allItemIdsUnderNsd)->where('status_id','=',1)
                    ->where('item_name', 'LIKE', "%{$query}%")->limit(20)
                    ->get();
            }else{
                $items = Item::where('status_id','=',1)
                    ->where('item_name', 'LIKE', "%{$query}%")->limit(20)
                    ->get();
            }

            if(count($items)<1){
                $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
                $output .= '<li>No match found</li>';
                $output .= '</ul>';
                return $output;
            }

            $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
            foreach($items as $row)
            {
                $output .= '<li class="searchItemName" value="'.$row->id.'" att-unit-price="'.$row->unit_price.'" att-discount-price="'.$row->discounted_price.'" att-currency-id="'.$row->currency_name.'" att-conversion="'.$row->conversion.'" ><a href="javascript:void(0)">'.$row->item_name.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;

        }


    }

    public function tenderPerticRepTenderNameSearch(Request $request){

        $this->tableAlies = \Session::get('zoneAlise');

        if($request->get('query')) {

            $query = $request->get('query');

            $nsdNames = NsdName::where('status_id','=',1)->get();
            $zonesRltdIds = array();
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

            $tenderList = \App\Tender::select('tender_title','id')->where('status_id','=',1)
                            ->whereIn('nsd_id',$zonesRltdIds);
                            if(!empty(Auth::user()->categories_id)){
                                $tenderList->whereIn('tender_cat_id',explode(',', Auth::user()->categories_id));
                            }
                            $tenderList->where('tender_title', 'LIKE', "%{$query}%")->limit(20);
            $tenderList = $tenderList->get();

            if(count($tenderList)<1){
                $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
                $output .= '<li>No match found</li>';
                $output .= '</ul>';
                return $output;
            }

            $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
            foreach($tenderList as $row)
            {
                $output .= '<li class="searchTenderName" value="'.$row->id.'" ><a href="javascript:void(0)">'.$row->tender_title.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;


        }

    }// end function === tenderPerticRepTenderNameSearch

    public function tenderPerticRepTenderNumberSearch(Request $request){

        $this->tableAlies = \Session::get('zoneAlise');

        if($request->get('query')) {

            $query = $request->get('query');

            $nsdNames = NsdName::where('status_id','=',1)->get();
            $zonesRltdIds = array();
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

            $tender_numbers  = \App\Tender::select('tender_number','id')
                //->where('status_id','=',1)
                ->where('tender_number','!=',null)
                ->whereIn('nsd_id',$zonesRltdIds);
                if(!empty(Auth::user()->categories_id)){
                    $tender_numbers->whereIn('tender_cat_id',explode(',', Auth::user()->categories_id));
                }
                $tender_numbers->where('tender_number', 'LIKE', "%{$query}%")->limit(20);
            $tender_numbers = $tender_numbers->get();

            if(count($tender_numbers)<1){
                $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
                $output .= '<li>No match found</li>';
                $output .= '</ul>';
                return $output;
            }

            $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
            foreach($tender_numbers as $row)
            {
                $output .= '<li class="searchTenderNumber" value="'.$row->id.'" ><a href="javascript:void(0)">'.$row->tender_number.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;


        }

    }// end function === tenderPerticRepTenderNumberSearch


    public function tenderPerticRepTenderPoSearch(Request $request){

        $this->tableAlies = \Session::get('zoneAlise');

        if($request->get('query')) {

            $query = $request->get('query');

            $nsdNames = NsdName::where('status_id','=',1)->get();
            $zonesRltdIds = array();
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
            $po_numbers  = \App\Tender::select('po_number','id')
                    ->where('status_id','=',1)
                    ->where('po_number','!=',null)
                    ->whereIn('nsd_id',$zonesRltdIds)
                    ->where('po_number', 'LIKE', "%{$query}%")->limit(20)
                    ->get();

            if(count($po_numbers)<1){
                $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
                $output .= '<li>No match found</li>';
                $output .= '</ul>';
                return $output;
            }

            $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
            foreach($po_numbers as $row)
            {
                $output .= '<li class="searchTenderPo" value="'.$row->id.'" ><a href="javascript:void(0)">'.$row->po_number.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;


        }

    }// end function === tenderPerticRepTenderPoSearch

    public function categoryItemRepItemSearch(Request $request){

        $this->tableAlies = \Session::get('zoneAlise');

        if($request->get('query')) {

            $query = $request->get('query');

            $allItemIdsUnderNsd = '';
            $allItemIdsUnderNsdArray = array();
            $nsdids = \App\Item::select('id','nsd_id','item_cat_id')->where('status_id','=',1)->get();
            foreach ($nsdids as $ni) {
                if (!empty(Session::get('nsdBsdEmptyOrNot')) && in_array(Session::get('zoneId'),explode(',',Auth::user()->zones))) {
                    foreach (explode(',', Session::get('nsdBsdEmptyOrNot')) as $val) {
                        if (in_array($val, explode(',', $ni->nsd_id))) {

                            if(!empty(Auth::user()->categories_id)){
                                $userWiseCat = explode(',', Auth::user()->categories_id);
                                if(in_array($ni->item_cat_id, $userWiseCat)){ 
                                    $allItemIdsUnderNsdArray[] = $ni->id;
                                }
                            }else{
                                $allItemIdsUnderNsdArray[] = $ni->id;
                            }
                            //$allItemIdsUnderNsdArray[] = $ni->id;
                        }
                    }
                } // If end
                $allItemIdsUnderNsd = array_unique($allItemIdsUnderNsdArray);
            }// End foreach
            if(!empty($allItemIdsUnderNsd) || !empty(Auth::user()->categories_id)){
                $productsnames = \App\Item::select('id','item_name')
                                            ->whereIn('id',$allItemIdsUnderNsd)
                                            ->where('item_name', 'LIKE', "%{$query}%")->limit(20)
                                            ->where('status_id','=',1)
                                            ->get();
            }else{
                $productsnames = \App\Item::select('id','item_name')
                                            ->where('item_name', 'LIKE', "%{$query}%")->limit(20)
                                            ->where('status_id','=',1)
                                            ->get();
            }

            if(count($productsnames)<1){
                $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
                $output .= '<li>No match found</li>';
                $output .= '</ul>';
                return $output;
            }

            $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
            foreach($productsnames as $row)
            {
                $output .= '<li class="searchItemName" value="'.$row->id.'" ><a href="javascript:void(0)">'.$row->item_name.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;

        }

    }// end function === categoryItemRepItemSearch

    public function itemsForDemand(Request $request){
        $this->tableAlies = \Session::get('zoneAlise');

        if($request->get('query'))
        {

            $query = $request->get('query');

            $allItemIdsUnderNsd = '';
            $allItemIdsUnderNsdArray = array();
            $nsdids = \App\Item::select('id','nsd_id','item_cat_id')->where('status_id','=',1)->get();

// Newly added ======================================================================
            if (!empty(Session::get('nsdBsdEmptyOrNot')) && in_array(Session::get('zoneId'),explode(',',Auth::user()->zones))) {

                $zoneRelatedNavLoc = array_map('current',NsdName::select('id')
                                ->where('zones','=',Session::get('zoneId'))->get()->toArray());

                    
                    foreach (explode(',', Session::get('nsdBsdEmptyOrNot')) as $val) {
                        if(in_array($val, $zoneRelatedNavLoc)){

                            if(!empty(Auth::user()->categories_id)){
                                $allIds[] = array_map('current',\App\Item::select('id')
                                ->where('status_id','=',1)
                                ->whereRaw("find_in_set('".$val."',nsd_id)")
                                ->whereIn('item_cat_id',explode(',',  Auth::user()->categories_id))
                                ->get()->toArray());
                            }else{ 
                                $allIds[] = array_map('current',\App\Item::select('id')
                                ->where('status_id','=',1)
                                ->whereRaw("find_in_set('".$val."',nsd_id)")
                                ->get()->toArray());
                            }

                        }
                        
                    }

                    if(!empty($allIds)){
                        foreach ($allIds as $key => $value) {
                            foreach ($value as $val) {
                                $allItemIdsUnderNsdArray[] = $val;
                            }
                            
                        }
                    }
                    $allItemIdsUnderNsd = array_unique($allItemIdsUnderNsdArray);
            }
// End newly added =================================================================
// 
            // foreach ($nsdids as $ni) {

            //     if (!empty(Session::get('nsdBsdEmptyOrNot')) && in_array(Session::get('zoneId'),explode(',',Auth::user()->zones))) {
            //         foreach (explode(',', Session::get('nsdBsdEmptyOrNot')) as $val) {
            //             if (in_array($val, explode(',', $ni->nsd_id))) {

            //                 if(!empty(Auth::user()->categories_id)){
            //                     $userWiseCat = explode(',', Auth::user()->categories_id);
            //                     if(in_array($ni->item_cat_id, $userWiseCat)){
            //                         $allItemIdsUnderNsdArray[] = $ni->id;
            //                     }
            //                 }else{
            //                     $allItemIdsUnderNsdArray[] = $ni->id;
            //                 }
            //                 //$allItemIdsUnderNsdArray[] = $ni->id;
            //             }
            //         }
            //     } // If end
            //     $allItemIdsUnderNsd = array_unique($allItemIdsUnderNsdArray);
            // }// End foreach

            if(!empty($allItemIdsUnderNsd) || !empty(Auth::user()->categories_id)){
                $items = Item::whereIn('id',$allItemIdsUnderNsd)->where('status_id','=',1)
                    ->where('item_name', 'LIKE', "%{$query}%")->limit(20)
                    ->get();
            }else{
                $items = Item::where('status_id','=',1)
                    ->where('item_name', 'LIKE', "%{$query}%")->limit(20)
                    ->get();
            }

            if(count($items)<1){
                $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
                $output .= '<li>No match found</li>';
                $output .= '</ul>';
                return $output;
            }

            $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
            foreach($items as $row)
            {
                $output .= '<li class="searchItemName" value="'.$row->id.'" att-unit-price="'.$row->unit_price.'" att-discount-price="'.$row->discounted_price.'" att-currency-id="'.$row->currency_name.'" att-conversion="'.$row->conversion.'" att-model-number="'.$row->model_number.'" att-imc-number="'.$row->imc_number.'" att-item-cat-id="'.$row->item_cat_id.'" att-item-deno-id="'.$row->item_deno.'" att-item-type-val="'.$row->item_type.'"><a href="javascript:void(0)">'.$row->item_name.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;

        }

    }

    public function supplierSearchByRegRrBarNo(Request $request){

        $this->tableAlies = \Session::get('zoneAlise');

        if($request->get('query2'))
        {
            $query = $request->get('query2');

            // Nsd name ================================================================
            $nsdNames = NsdName::where('status_id','=',1)->get();
            $zonesRltdIds = array();
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

            // Supplier name ============================================================

            $AllSuppliers = Supplier::where('status_id','=',1)->get();
            $zonesRltdIdsss = array();
            foreach($AllSuppliers as $spl){
                foreach(explode(',',$spl->registered_nsd_id) as $rni){

                    if(in_array($rni, $zonesRltdIds)){
                        if(!empty(Auth::user()->categories_id)){
                            $userWiseCat = explode(',', Auth::user()->categories_id);
                            foreach ($userWiseCat as $uwc){
                                if(in_array($uwc, explode(',',$spl->supply_cat_id))){
                                    $zonesRltdIdsss[] = $spl->id;
                                }
                            }
                        }else{
                            $zonesRltdIdsss[] = $spl->id;
                        }
                        //$zonesRltdIdsss[] = $spl->id;
                    }

                }
            }

            //$suppliersName  = Supplier::select('id','company_name')->whereIn('id',$zonesRltdIdsss)
                //->where('company_name', 'LIKE', "%{$query}%")->limit(20)->get();

            // $suppliersName  = Supplier::leftJoin($this->tableAlies.'_suppliers_personal_info', $this->tableAlies.'_suppliers_personal_info.supplier_id', '=', $this->tableAlies.'_suppliers.id')
            //     ->select($this->tableAlies.'_suppliers.id', $this->tableAlies.'_suppliers.company_name', $this->tableAlies.'_suppliers.company_regi_number_nsd', $this->tableAlies.'_suppliers.barcode_number', $this->tableAlies.'_suppliers.trade_license_address' ,$this->tableAlies.'_suppliers_personal_info.full_name')
            //     ->where('company_regi_number_nsd', 'LIKE', "%{$query}%")
            //     ->orWhere('barcode_number', 'LIKE', "%{$query}%")
            //     ->limit(20)
            //     ->get();

            
            $suppliersName = SupplierMultiInfo::leftJoin($this->tableAlies.'_suppliers', $this->tableAlies.'_supplier_multi_info.supplier_id', '=', $this->tableAlies.'_suppliers.id')
                                  ->select($this->tableAlies.'_suppliers.id', $this->tableAlies.'_suppliers.company_name', $this->tableAlies.'_suppliers.company_regi_number_nsd', $this->tableAlies.'_suppliers.trade_license_address',$this->tableAlies.'_supplier_multi_info.barcode_number',$this->tableAlies.'_supplier_multi_info.name')
                                  ->where( $this->tableAlies.'_supplier_multi_info.barcode_number', 'LIKE', "%{$query}%")
                                  ->limit(20)
                                  ->get();
            
            if(count($suppliersName)<1){
                $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 0px;">';
                $output .= '<li>No match found</li>';
                $output .= '</ul>';
                return $output;
            }

            $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 0px;">';
            foreach($suppliersName as $row)
            {
                $output .= '<li class="searchSuppName" value="'.$row->id.'" fullname="'.$row->name.'" companyname="'.$row->company_name.'" address="'.$row->trade_license_address.'"><a href="javascript:void(0)">'.$row->barcode_number.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    public function supplierNameLiveSearchBySchedule(Request $request){

        $this->tableAlies = \Session::get('zoneAlise');

        if($request->get('query'))
        {
            $query = $request->get('query');

            // Nsd name ================================================================
            $nsdNames = NsdName::where('status_id','=',1)->get();
            $zonesRltdIds = array();
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

            // Supplier name ============================================================

            // Newly added ==============================================================
            // ==========================================================================

            $demand     = \App\Demand::find($request->demandId);
            $sheCdInfo  = array_map('current',\App\TenderSchedule::select('supplier_id')
            ->where('tender_id','=',$demand->tender_id)
            ->get()->toArray());


            $AllSuppliers = Supplier::where('status_id','=',1)->get();
            $zonesRltdIdsss = array();

            if(empty($sheCdInfo)){
                foreach($AllSuppliers as $spl){
                    foreach(explode(',',$spl->registered_nsd_id) as $rni){

                        if(in_array($rni, $zonesRltdIds)){
                            if(!empty(Auth::user()->categories_id)){
                                $userWiseCat = explode(',', Auth::user()->categories_id);
                                foreach ($userWiseCat as $uwc){
                                    if(in_array($uwc, explode(',',$spl->supply_cat_id))){
                                        $zonesRltdIdsss[] = $spl->id;
                                    }
                                }
                            }else{
                                $zonesRltdIdsss[] = $spl->id;
                            }
                            //$zonesRltdIdsss[] = $spl->id;
                        }

                    }
                }
            }

            // $suppliersName  = Supplier::select('id','company_name');
            //                 if(!empty($sheCdInfo)){
            //                   $suppliersName->whereIn('id',$sheCdInfo);
            //                 }else{
            //                     $suppliersName->whereIn('id',$zonesRltdIdsss);
            //                 }
            
            $suppliersName  = Supplier::select('id','company_name');
                $suppliersName->where('company_name', 'LIKE', "%{$query}%");
                $suppliersName->whereIn('id',$sheCdInfo);
            $suppliersName = $suppliersName->limit(20)->get();
            
            
            if(count($suppliersName)<1){
                $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
                $output .= '<li>No match found</li>';
                $output .= '</ul>';
                return $output;
            }

            $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
            foreach($suppliersName as $row)
            {
                $output .= '<li class="searchSuppName" value="'.$row->id.'"><a href="javascript:void(0)">'.$row->company_name.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }


}
