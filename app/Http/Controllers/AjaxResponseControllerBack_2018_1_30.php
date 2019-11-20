<?php

namespace App\Http\Controllers;

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

        $nsdsBsdsAll = \App\NsdName::whereIn('id',$nsdBsdIds)->get();

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
                        $zonesRltdIdsss[] = $spl->id;
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

            $itemList = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.id')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=',$this->tableAlies.'_itemtotender.item_id')
                ->select($this->tableAlies.'_items.id',$this->tableAlies.'_items.item_name')
                ->whereIn($this->tableAlies.'_tenders.nsd_id',$zonesRltdIds)
                ->where($this->tableAlies.'_items.item_name', 'LIKE', "%{$query}%")->limit(20)
                ->get();

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
            $nsdids = \App\Item::select('id','nsd_id')->where('status_id','=',1)->get();
            foreach ($nsdids as $ni) {

                if (!empty(Session::get('nsdBsdEmptyOrNot')) && in_array(Session::get('zoneId'),explode(',',Auth::user()->zones))) {
                    foreach (explode(',', Session::get('nsdBsdEmptyOrNot')) as $val) {
                        if (in_array($val, explode(',', $ni->nsd_id))) {
                            $allItemIdsUnderNsdArray[] = $ni->id;
                        }
                    }
                } // If end
                $allItemIdsUnderNsd = array_unique($allItemIdsUnderNsdArray);
            }// End foreach

            if(!empty($allItemIdsUnderNsd)){
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
                            ->whereIn('nsd_id',$zonesRltdIds)
                            ->where('tender_title', 'LIKE', "%{$query}%")->limit(20)
                            ->get();

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
                ->where('status_id','=',1)
                ->where('tender_number','!=',null)
                ->whereIn('nsd_id',$zonesRltdIds)
                ->where('tender_number', 'LIKE', "%{$query}%")->limit(20)
                ->get();

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
            $nsdids = \App\Item::select('id','nsd_id')->where('status_id','=',1)->get();
            foreach ($nsdids as $ni) {
                if (!empty(Session::get('nsdBsdEmptyOrNot')) && in_array(Session::get('zoneId'),explode(',',Auth::user()->zones))) {
                    foreach (explode(',', Session::get('nsdBsdEmptyOrNot')) as $val) {
                        if (in_array($val, explode(',', $ni->nsd_id))) {
                            $allItemIdsUnderNsdArray[] = $ni->id;
                        }
                    }
                } // If end
                $allItemIdsUnderNsd = array_unique($allItemIdsUnderNsdArray);
            }// End foreach
            if(!empty($allItemIdsUnderNsd)){
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


}
