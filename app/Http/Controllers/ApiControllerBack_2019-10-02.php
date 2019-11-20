<?php

namespace App\Http\Controllers;

use App\Item;
use App\ItemToTender;
use App\Tender;
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
use App\Supplier;
use App\Zone;
use App\ApiDataUpdateLog;
use App\SupplierBasicInfo;
use DB;

class ApiController extends Controller
{

    private $moduleId = 12;
    private $imageResizeCtrl;
    private $tableAlies;

    public function __construct() {
        $this->imageResizeCtrl = new ImageResizeController();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // Start for khulna api================================================
    // 
    public function nsdKhulnaNsd($date=null)
    {

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $data['suppliers'] = DB::table($zoneInfo->alise.'_suppliers')
            // ->where('status_id','=',1)
            ->whereRaw("find_in_set('".$navalLocation->id."',registered_nsd_id)")
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

            $data['suppliers_personal_infos'] = DB::table($zoneInfo->alise.'_suppliers_personal_info')
            // ->where('status_id','=',1)
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        return $data;
    }

    public function bsdKhulnaBsd($date=null)
    {

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $data['suppliers'] = DB::table($zoneInfo->alise.'_suppliers')
            // ->where('status_id','=',1)
            ->whereRaw("find_in_set('".$navalLocation->id."',registered_nsd_id)")
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        $data['suppliers_personal_infos'] = DB::table($zoneInfo->alise.'_suppliers_personal_info')
            // ->where('status_id','=',1)
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();    

        return $data;
    }

    // End for khulna =========================================================================================
    

    // Start for dhaka api================================================
    // 
    public function nsdDhakaNsd($date=null)
    {

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $data['suppliers'] = DB::table($zoneInfo->alise.'_suppliers')
            // ->where('status_id','=',1)
            ->whereRaw("find_in_set('".$navalLocation->id."',registered_nsd_id)")
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        $data['suppliers_personal_infos'] = DB::table($zoneInfo->alise.'_suppliers_personal_info')
            // ->where('status_id','=',1)
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();    

        return $data;
    }

    public function bsdDhakaBsd($date=null)
    {

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $data['suppliers'] = DB::table($zoneInfo->alise.'_suppliers')
            // ->where('status_id','=',1)
            ->whereRaw("find_in_set('".$navalLocation->id."',registered_nsd_id)")
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        $data['suppliers_personal_infos'] = DB::table($zoneInfo->alise.'_suppliers_personal_info')
            // ->where('status_id','=',1)
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();    

        return $data;
    }

// End Dtaka supplier ==================================================

// Start DGDP supplier ==================================================

    public function nsdDgdpNsd($date=null)
    {

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $data['suppliers'] = DB::table($zoneInfo->alise.'_suppliers')
            // ->where('status_id','=',1)
            ->whereRaw("find_in_set('".$navalLocation->id."',registered_nsd_id)")
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        $data['suppliers_personal_infos'] = DB::table($zoneInfo->alise.'_suppliers_personal_info')
            // ->where('status_id','=',1)
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();    

        return $data;
    }

// End dgdp supplier

    // Itema section ==================================================================
    
    // Start khulna api ===============================================================
    // 
    public function nsdKhulnaNsdItmes($date=null){

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $data['items'] = DB::table($zoneInfo->alise.'_items')
            // ->where('status_id','=',1)
            ->whereRaw("find_in_set('".$navalLocation->id."',nsd_id)")
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        return $data;

    }

    public function bsdKhulnaBsdItmes($date=null){

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $data['items'] = DB::table($zoneInfo->alise.'_items')
            // ->where('status_id','=',1)
            ->whereRaw("find_in_set('".$navalLocation->id."',nsd_id)")
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        return $data;

    }

    // End khulna api =====================================================
    // 
    // Start dhaka api =====================================================
    // 
    public function nsdDhakaNsdItems($date=null){

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $data['items'] = DB::table($zoneInfo->alise.'_items')
            // ->where('status_id','=',1)
            ->whereRaw("find_in_set('".$navalLocation->id."',nsd_id)")
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        return $data;

    }

    public function bsdDhakaBsdItems($date=null){

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $data['items'] = DB::table($zoneInfo->alise.'_items')
            // ->where('status_id','=',1)
            ->whereRaw("find_in_set('".$navalLocation->id."',nsd_id)")
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        return $data;

    }

    // End dhaka section =============================================================
    
    // Start Api DGDP ================================================================
    
    public function nsdDgdpNsdItems($date=null){

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $data['items'] = DB::table($zoneInfo->alise.'_items')
            // ->where('status_id','=',1)
            ->whereRaw("find_in_set('".$navalLocation->id."',nsd_id)")
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->limit(1)->get();

        return $data;

    }

    // Tender section ==================================================================
    
    // Start khulna api ===============================================================
    // 
    public function nsdKhulnaNsdTenders($date=null){

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $data['tenders'] = DB::table($zoneInfo->alise.'_tenders')
            // ->where('status_id','=',1)
            ->where('nsd_id','=',$navalLocation->id)
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        return $data;

    }

    public function bsdKhulnaBsdTenders($date=null){

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $data['tenders'] = DB::table($zoneInfo->alise.'_tenders')
            // ->where('status_id','=',1)
            ->where('nsd_id','=',$navalLocation->id)
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        return $data;

    }
    // End khulna api =====================================================
    // 
    // Start dhaka api =====================================================
    // 
    public function nsdDhakaNsdTenders($date=null){

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $data['tenders'] = DB::table($zoneInfo->alise.'_tenders')
            // ->where('status_id','=',1)
            ->where('nsd_id','=',$navalLocation->id)
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        return $data;

    }

    public function bsdDhakaBsdTenders($date=null){

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $data['tenders'] = DB::table($zoneInfo->alise.'_tenders')
            // ->where('status_id','=',1)
            ->where('nsd_id','=',$navalLocation->id)
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        return $data;

    }

    // End Dhaka tender api
    // 
    // Start dgdp tender api ===================================================
    public function nsdDgdpNsdTenders($date=null){

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $data['tenders'] = DB::table($zoneInfo->alise.'_tenders')
            // ->where('status_id','=',1)
            ->where('nsd_id','=',$navalLocation->id)
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        return $data;

    }

    // Item to Tender section ==================================================================
    
    // Start khulna api ===============================================================
    // 
    public function nsdKhulnaNsdItemToTenders($date=null){

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $tenderIds = array_map('current',DB::table('nsd_tenders')
            ->select('id')
            ->where('nsd_id','=',1)
            ->get()->toArray());

        $data['itemtotenders'] = DB::table($zoneInfo->alise.'_itemtotender')
            // ->where('status_id','=',1)
            ->whereIn('id',$tenderIds)
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        return $data;

    }

    public function bsdKhulnaBsdItemToTenders($date=null){

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $tenderIds = array_map('current',DB::table('nsd_tenders')
            ->select('id')
            ->where('nsd_id','=',1)
            ->get()->toArray());

        $data['itemtotenders'] = DB::table($zoneInfo->alise.'_itemtotender')
            // ->where('status_id','=',1)
            ->whereIn('id',$tenderIds)
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        return $data;

    }
    // End khulna api =====================================================
    // 
    // Start dhaka api =====================================================
    // 
    public function nsdDhakaNsdItemToTenders($date=null){

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $tenderIds = array_map('current',DB::table('nsd_tenders')
            ->select('id')
            ->where('nsd_id','=',1)
            ->get()->toArray());

        $data['itemtotenders'] = DB::table($zoneInfo->alise.'_itemtotender')
            // ->where('status_id','=',1)
            ->whereIn('id',$tenderIds)
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        return $data;

    }

    public function bsdDhakaBsdItemToTenders($date=null){

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $tenderIds = array_map('current',DB::table('nsd_tenders')
            ->select('id')
            ->where('nsd_id','=',1)
            ->get()->toArray());

        $data['itemtotenders'] = DB::table($zoneInfo->alise.'_itemtotender')
            // ->where('status_id','=',1)
            ->whereIn('id',$tenderIds)
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        return $data;

    }
    // End dhaka ItemToTender ========================================
    // 
    // Start ItemToTender dgdp api ==================================================
    public function nsdDgdpNsdItemToTenders($date=null){

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);
        $lastUpldatedDateTime   = $date;

        $zoneInfo = Zone::where('alise','=',$zone)->first();
        $navalLocation = NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
        $data['zone']           = $zone;
        $data['organization']   = $organization;
        $data['date']           = $date;

        $tenderIds = array_map('current',DB::table('nsd_tenders')
            ->select('id')
            ->where('nsd_id','=',1)
            ->get()->toArray());

        $data['itemtotenders'] = DB::table($zoneInfo->alise.'_itemtotender')
            // ->where('status_id','=',1)
            ->whereIn('id',$tenderIds)
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        return $data;

    }


    // Only for Chittatong section all ===========================================
    //
    public function nsdChattagramNsd(Request $request){
        
    	$zoneInfo = Zone::find($request->zoneAlise);
    	$orgInfo  = NsdName::find($request->organization);
        $tbl      = $request->tbl;

        //$zone           = $request->zoneAlise; // 'nsd'
        //$organization   = $request->organization; // 'nsd_chattagram'

        $zone           = $zoneInfo->alise; // 'nsd'
        $organization   = $orgInfo->alise; // 'nsd_chattagram'

        $ss = $request;

        \Session::put('zoneAlise', strtolower($zone));

        $ifexist = Supplier::where('all_org_id','=',$organization.'_'.$ss->id)->first();

        if(empty($ifexist)){
            $supplier = new Supplier();
        }else{
            $supplier = Supplier::find($ifexist->id);
        }

        $supplier->all_org_id               = $organization.'_'.$ss->id;
        $supplier->company_name             = $ss->company_name;
        $supplier->company_regi_number_nsd  = $ss->company_regi_number_nsd;
        $supplier->mobile_number            = $ss->mobile_number;
        $supplier->fax                      = $ss->fax;
        $supplier->email                    = $ss->email;
        $supplier->head_office_address      = $ss->head_office_address;
        $supplier->tin_number               = $ss->tin_number;
        $supplier->bank_account_number      = $ss->bank_account_number;
        $supplier->bank_name_and_branch     = $ss->bank_name_and_branch;
        $supplier->rltn_w_acc_holder        = $ss->rltn_w_acc_holder;
        $supplier->branch_office_address    = $ss->branch_office_address;
        $supplier->intr_name                = $ss->intr_name;
        $supplier->intr_designation         = $ss->intr_designation;
        $supplier->intr_address             = $ss->intr_address;
        $supplier->cur_reg_supplier_name    = $ss->cur_reg_supplier_name;
        $supplier->cur_reg_supplier_address = $ss->cur_reg_supplier_address;
        $supplier->defaulter_before         = $ss->defaulter_before;
        $supplier->company_partnership_act  = $ss->company_partnership_act;
        $supplier->registered_as            = $ss->registered_as;
        $supplier->des_of_sole_prtship      = $ss->des_of_sole_prtship;
        $supplier->partners_name            = $ss->partners_name;
        $supplier->partners_address         = $ss->partners_address;
        $supplier->auth_prsn_name           = $ss->auth_prsn_name;
        $supplier->auth_prsn_designation    = $ss->auth_prsn_designation;
        $supplier->vat_registration_number  = $ss->vat_registration_number;
        $supplier->bsti_certification       = $ss->bsti_certification;
        $supplier->nid_number               = $ss->nid_number;
        $supplier->trade_license_number     = $ss->trade_license_number;
        $supplier->trade_license_address    = $ss->trade_license_address;
        $supplier->registered_nsd_id        = $ss->registered_nsd_id;
        $supplier->iso_certification        = $ss->iso_certification;
        $supplier->supply_cat_id            = $ss->supply_cat_id;
        $supplier->status_id                = $ss->status_id;
        $supplier->profile_pic              = $ss->profile_pic;
        $supplier->tin_certificate          = $ss->tin_certificate;
        $supplier->testimonial              = $ss->testimonial;
        $supplier->bangla_signature         = $ss->bangla_signature;
        $supplier->english_signature        = $ss->english_signature;
        $supplier->attested_photo           = $ss->attested_photo;
        $supplier->attested_trade_lic       = $ss->attested_trade_lic;
        $supplier->attested_nid_photocopy   = $ss->attested_nid_photocopy;
        $supplier->attested_char_cert       = $ss->attested_char_cert;
        $supplier->att_vat_reg_cert         = $ss->att_vat_reg_cert;
        $supplier->att_vat_return_last_cert = $ss->att_vat_return_last_cert;
        $supplier->att_edu_cert             = $ss->att_edu_cert;
        $supplier->lst_six_mnth_bnk_sttmnt  = $ss->lst_six_mnth_bnk_sttmnt;
        $supplier->bnk_solvency_certi       = $ss->bnk_solvency_certi;
        $supplier->non_judicial_stamp       = $ss->non_judicial_stamp;
        $supplier->non_judicial_stamp       = $ss->non_judicial_stamp;

        $supplier->save();
        
        // $data['zone']           = $zone;
        // $data['organization']   = $organization;
        // $data['lemon']          = $datas->id;

        // return $data;
    }

    public function nsdChattagramSuppPersNsd(Request $request){
        
        $zoneInfo = Zone::find($request->zoneAlise);
    	$orgInfo  = NsdName::find($request->organization);

        //$zone           = $request->zoneAlise; // 'nsd'
        //$organization   = $request->organization; // 'nsd_chattagram'

        $zone           = $zoneInfo->alise; // 'nsd'
        $organization   = $orgInfo->alise; // 'nsd_chattagram'

        $ss = $request;

        \Session::put('zoneAlise', strtolower($zone));

        $ifexist = SupplierBasicInfo::where('all_org_sup_bas_info_id','=',$organization.'_'.$ss->id)->first();

        if(empty($ifexist)){
            $supplier_bsc_info = new SupplierBasicInfo();
        }else{
            $supplier_bsc_info = SupplierBasicInfo::find($ifexist->id);
        }

        //$supplier_bsc_info = new SupplierBasicInfo();

        $supplier_bsc_info->all_org_sup_bas_info_id = $ss->organization.'_'.$ss->id;
        $supplier_bsc_info->supplier_id = $ss->organization.'_'.$ss->supplier_id;
        $supplier_bsc_info->full_name = $ss->full_name;
        $supplier_bsc_info->father_name = $ss->father_name;
        $supplier_bsc_info->father_nid = $ss->father_nid;
        $supplier_bsc_info->mother_name = $ss->mother_name;
        $supplier_bsc_info->mother_nid = $ss->mother_nid;
        $supplier_bsc_info->permanent_address = $ss->permanent_address;
        $supplier_bsc_info->present_address = $ss->present_address;
        $supplier_bsc_info->birth_place = $ss->birth_place;
        $supplier_bsc_info->birth_date = $ss->birth_date;
        $supplier_bsc_info->height = $ss->height;
        $supplier_bsc_info->weight = $ss->weight;
        $supplier_bsc_info->color = $ss->color;
        $supplier_bsc_info->eye_color = $ss->eye_color;
        $supplier_bsc_info->identification_mark = $ss->identification_mark;
        $supplier_bsc_info->religion = $ss->religion;
        $supplier_bsc_info->nationality = $ss->nationality;
        $supplier_bsc_info->organization = $ss->organization;
        $supplier_bsc_info->rank_in_organization = $ss->rank_in_organization;
        $supplier_bsc_info->business_start_date = $ss->business_start_date;
        $supplier_bsc_info->organization_name = $ss->organization_name;
        $supplier_bsc_info->organization_branch = $ss->organization_branch;
        $supplier_bsc_info->membership_number = $ss->membership_number;
        $supplier_bsc_info->membrsip_cont_date = $ss->membrsip_cont_date;
        $supplier_bsc_info->date_of_registry = $ss->date_of_registry;
        $supplier_bsc_info->jn_date_of_prsnt_ocuptn = $ss->jn_date_of_prsnt_ocuptn;
        $supplier_bsc_info->des_of_pre_occu = $ss->des_of_pre_occu;
        $supplier_bsc_info->offence = $ss->offence;
        $supplier_bsc_info->offence_date = $ss->offence_date;
        $supplier_bsc_info->offence_place = $ss->offence_place;
        $supplier_bsc_info->des_of_offence = $ss->des_of_offence;
        $supplier_bsc_info->spouse_full_name = $ss->spouse_full_name;
        $supplier_bsc_info->spouse_nid = $ss->spouse_nid;
        $supplier_bsc_info->spouse_father_name = $ss->spouse_father_name;
        $supplier_bsc_info->spouse_father_nid = $ss->spouse_father_nid;
        $supplier_bsc_info->spouse_mother_name = $ss->spouse_mother_name;
        $supplier_bsc_info->spouse_mother_nid = $ss->spouse_mother_nid;
        $supplier_bsc_info->spouse_per_address = $ss->spouse_per_address;
        $supplier_bsc_info->spouse_pre_address = $ss->spouse_pre_address;
        $supplier_bsc_info->spouse_birth_place = $ss->spouse_birth_place;
        $supplier_bsc_info->spouse_birth_date = $ss->spouse_birth_date;
        $supplier_bsc_info->spouse_nationality = $ss->spouse_nationality;
        $supplier_bsc_info->spouse_occupation = $ss->spouse_occupation;
        $supplier_bsc_info->applicant_signature = $ss->applicant_signature;
        $supplier_bsc_info->applicant_seal = $ss->applicant_seal;
        $supplier_bsc_info->signature_place = $ss->signature_place;
        $supplier_bsc_info->signature_date = $ss->signature_date;

        $supplier_bsc_info->save();
        
        // $data['zone']           = $zone;
        // $data['organization']   = $organization;
        // $data['lemon']          = $datas->id;

        // return $data;
    }

    public function nsdChattagramNsdItems(Request $request){

        $zoneInfo = Zone::find($request->zoneAlise);
    	$orgInfo  = NsdName::find($request->organization);

        //$zone           = $request->zoneAlise; // 'nsd'
        //$organization   = $request->organization; // 'nsd_chattagram'

        $zone           = $zoneInfo->alise; // 'nsd'
        $organization   = $orgInfo->alise; // 'nsd_chattagram'

        $ss = $request;

        \Session::put('zoneAlise', strtolower($zone));

        $ifexist = Item::where('all_org_item_id','=',$organization.'_'.$ss->id)->first();

        if(empty($ifexist)){
            $item = new Item();
        }else{
            $item = Item::find($ifexist->id);
        }

        //$item = new Item();

        $item->all_org_item_id              = $ss->organization.'_'.$ss->id;
        $item->imc_number                   = $ss->imc_number;
        $item->item_name                    = $ss->item_name;
        $item->model_number                 = $ss->model_number;
        $item->item_cat_id                  = $ss->item_cat_id;
        $item->nsd_id                       = $ss->nsd_id;
        $item->unit_price                   = $ss->unit_price;
        $item->unit_price_in_bdt            = $ss->unit_price_in_bdt;
        $item->currency_name                = $ss->currency_name;
        $item->conversion                   = $ss->conversion;
        $item->discounted_price             = $ss->discounted_price;
        $item->discounted_price_in_bdt      = $ss->discounted_price_in_bdt;
        $item->item_deno                    = $ss->item_deno;
        $item->manufacturing_country        = $ss->manufacturing_country;
        $item->source_of_supply             = $ss->source_of_supply;
        $item->other_info_about_itme        = $ss->other_info_about_itme;
        $item->budget_code                  = $ss->budget_code;
        $item->status_id                    = $ss->status_id;
        $item->created_by                   = $ss->created_by;
        $item->updated_by                   = $ss->updated_by;

        $item->save();

    }

    public function nsdChattagramNsdTenders(Request $request){

        $zoneInfo = Zone::find($request->zoneAlise);
    	$orgInfo  = NsdName::find($request->organization);

        //$zone           = $request->zoneAlise; // 'nsd'
        //$organization   = $request->organization; // 'nsd_chattagram'

        $zone           = $zoneInfo->alise; // 'nsd'
        $organization   = $orgInfo->alise; // 'nsd_chattagram'

        $ss = $request;

        \Session::put('zoneAlise', strtolower($zone));

        $ifexist = Tender::where('all_org_tender_id','=',$organization.'_'.$ss->id)->first();

        if(empty($ifexist)){
            $tender = new Tender();
        }else{
            $tender = Tender::find($ifexist->id);
        }

        //$tender = new Tender();

        $tender->all_org_tender_id       = $ss->organization.'_'.$ss->id;
        $tender->po_number               = $ss->po_number;
        $tender->tender_title            = $ss->tender_title;
        $tender->tender_number           = $ss->tender_number;
        $tender->tender_description      = $ss->tender_description;
        $tender->tender_opening_date     = $ss->tender_opening_date;
        $tender->supplier_id             = $ss->supplier_id;
        $tender->work_order_date         = $ss->work_order_date;
        $tender->date_line               = $ss->date_line;
        $tender->delivery_date           = $ss->delivery_date;
        $tender->imc_number              = $ss->imc_number;
        $tender->tender_cat_id           = $ss->tender_cat_id;
        $tender->nsd_id                  = $ss->nsd_id;
        $tender->other_info_about_tender = $ss->other_info_about_tender;
        $tender->specification           = $ss->specification;
        $tender->notice                  = $ss->notice;
        $tender->open_tender             = $ss->open_tender;
        $tender->status_id               = $ss->status_id;
        $tender->created_by              = $ss->created_by;
        $tender->updated_by              = $ss->updated_by;

        $tender->save();

    }

    public function nsdChattagramNsdItemToTenders(Request $request){

        $zoneInfo = Zone::find($request->zoneAlise);
    	$orgInfo  = NsdName::find($request->organization);

        //$zone           = $request->zoneAlise; // 'nsd'
        //$organization   = $request->organization; // 'nsd_chattagram'

        $zone           = $zoneInfo->alise; // 'nsd'
        $organization   = $orgInfo->alise; // 'nsd_chattagram'

        $ss = $request;

        \Session::put('zoneAlise', strtolower($zone));

        $ifexist = ItemToTender::where('all_org_itmtotender_id','=',$organization.'_'.$ss->id)->first();

        if(empty($ifexist)){
            $itemtotender = new ItemToTender();
        }else{
            $itemtotender = ItemToTender::find($ifexist->id);
        }

        //$itemtotender = new ItemToTender();

        $itemtotender->all_org_itmtotender_id            = $ss->organization.'_'.$ss->id;
        $itemtotender->tender_id                         = $ss->organization.'_'.$ss->tender_id;
        $itemtotender->item_id                           = $ss->organization.'_'.$ss->item_id;
        $itemtotender->unit_price                        = $ss->unit_price;
        $itemtotender->unit_price_in_bdt                 = $ss->unit_price_in_bdt;
        $itemtotender->currency_name                     = $ss->currency_name;
        $itemtotender->conversion                        = $ss->conversion;
        $itemtotender->quantity                          = $ss->quantity;
        $itemtotender->discount_price                    = $ss->discount_price;
        $itemtotender->discount_price_in_bdt             = $ss->discount_price_in_bdt;
        $itemtotender->total                             = $ss->total;
        $itemtotender->status_id                         = $ss->status_id;
        $itemtotender->created_by                        = $ss->created_by;
        $itemtotender->updated_by                        = $ss->updated_by;

        $itemtotender->save();

    }


    // For new testing api =====================================================
    // =========================================================================
    // =========================================================================
    
    public function allNsdDataUpdate($argudata=null,$passdata=null)
    {

        $dataExplode = explode('&', $passdata);

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);

        $tableData              = $dataExplode[0];
        $lastUpldatedDateTime   = str_replace("+"," ",$dataExplode[1]); 

        $zoneInfo = Zone::where('id','=',$zone)->first();
        $navalLocation = NsdName::where('id','=',$organization)->first();

        
        $data['zone']           = $zoneInfo->alise;
        $data['organization']   = $navalLocation->alise;
        $data['date']           = $lastUpldatedDateTime;

// Supplier and Supplier basic information data ====================================        
        if($tableData==1){
            $data['suppliers'] = DB::table($zoneInfo->alise.'_suppliers')
            // ->where('status_id','=',1)
            ->whereRaw("find_in_set('".$navalLocation->id."',registered_nsd_id)")
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

            $data['suppliers_personal_infos'] = DB::table($zoneInfo->alise.'_suppliers_personal_info')
                // ->where('status_id','=',1)
                ->where(function($query) use ($lastUpldatedDateTime){
                    $query->whereDate('created_at','>',$lastUpldatedDateTime);
                    $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
                })->get();    

            return $data;
        }
        if($tableData==2){
            $data['items'] = DB::table($zoneInfo->alise.'_items')
            // ->where('status_id','=',1)
            ->whereRaw("find_in_set('".$navalLocation->id."',nsd_id)")
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

            return $data;
        }
        if($tableData==3){
            $data['tenders'] = DB::table($zoneInfo->alise.'_tenders')
            // ->where('status_id','=',1)
            ->where('nsd_id','=',$navalLocation->id)
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        return $data;
        }
        if($tableData==4){
            $tenderIds = array_map('current',DB::table($zoneInfo->alise.'_tenders')
            ->select('all_org_tender_id')
            ->where('nsd_id','=',$navalLocation->id)
            ->get()->toArray());

            $data['itemtotenders'] = DB::table($zoneInfo->alise.'_itemtotender')
                // ->where('status_id','=',1)
                ->whereIn('tender_id',$tenderIds)
                ->where(function($query) use ($lastUpldatedDateTime){
                    $query->whereDate('created_at','>',$lastUpldatedDateTime);
                    $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
                })->get();

            return $data;
        }
// End of supplier and supplier basic information data ================================
        
    }

    public function allBsdDataUpdate($argudata=null,$passdata=null)
    {

        $dataExplode = explode('&', $passdata);

        $zone                   = \Request::segment(3);
        $organization           = \Request::segment(4);

        $tableData              = $dataExplode[0];
        $lastUpldatedDateTime   = str_replace("+"," ",$dataExplode[1]);

        $zoneInfo = Zone::where('id','=',$zone)->first();
        $navalLocation = NsdName::where('id','=',$organization)->first();

        
        $data['zone']           = $zoneInfo->alise;
        $data['organization']   = $navalLocation->alise;
        $data['date']           = $lastUpldatedDateTime;

// Supplier and Supplier basic information data ====================================        
        if($tableData==1){
            $data['suppliers'] = DB::table($zoneInfo->alise.'_suppliers')
            // ->where('status_id','=',1)
            ->whereRaw("find_in_set('".$navalLocation->id."',registered_nsd_id)")
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

            $data['suppliers_personal_infos'] = DB::table($zoneInfo->alise.'_suppliers_personal_info')
                // ->where('status_id','=',1)
                ->where(function($query) use ($lastUpldatedDateTime){
                    $query->whereDate('created_at','>',$lastUpldatedDateTime);
                    $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
                })->get();    

            return $data;
        }
        if($tableData==2){
            $data['items'] = DB::table($zoneInfo->alise.'_items')
            // ->where('status_id','=',1)
            ->whereRaw("find_in_set('".$navalLocation->id."',nsd_id)")
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

            return $data;
        }
        if($tableData==3){
            $data['tenders'] = DB::table($zoneInfo->alise.'_tenders')
            // ->where('status_id','=',1)
            ->where('nsd_id','=',$navalLocation->id)
            ->where(function($query) use ($lastUpldatedDateTime){
                $query->whereDate('created_at','>',$lastUpldatedDateTime);
                $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
            })->get();

        return $data;
        }
        if($tableData==4){
            $tenderIds = array_map('current',DB::table($zoneInfo->alise.'_tenders')
            ->select('all_org_tender_id')
            ->where('nsd_id','=',$navalLocation->id)
            ->get()->toArray());

            $data['itemtotenders'] = DB::table($zoneInfo->alise.'_itemtotender')
                // ->where('status_id','=',1)
                ->whereIn('tender_id',$tenderIds)
                ->where(function($query) use ($lastUpldatedDateTime){
                    $query->whereDate('created_at','>',$lastUpldatedDateTime);
                    $query->orWhereDate('updated_at', '>', $lastUpldatedDateTime);
                })->get();

            return $data;
        }
// End of supplier and supplier basic information data ================================
        
    }


    // For chittagong push data ===============================================
    
    public function updateChittagongData(Request $request){
        
        $zoneInfo = Zone::find($request->zoneAlise);
        $orgInfo  = NsdName::find($request->organization);
        $tbl      = $request->tbl;

        //$zone           = $request->zoneAlise; // 'nsd'
        //$organization   = $request->organization; // 'nsd_chattagram'
        $zone           = $zoneInfo->alise; // 'nsd'
        $organization   = $orgInfo->alise; // 'nsd_chattagram'

        $ss = $request;

// Supplier information ======================================================
        if($tbl==1){ 
            \Session::put('zoneAlise', strtolower($zone));

            $ifexist = Supplier::where('all_org_id','=',$organization.'_'.$ss->id)->first();

            if(empty($ifexist)){
                $supplier = new Supplier();
            }else{
                $supplier = Supplier::find($ifexist->id);
            }
                $supplier->all_org_id               = $organization.'_'.$ss->id;
                $supplier->company_name             = $ss->company_name;
                $supplier->company_regi_number_nsd  = $ss->company_regi_number_nsd;
                $supplier->mobile_number            = $ss->mobile_number;
                $supplier->fax                      = $ss->fax;
                $supplier->email                    = $ss->email;
                $supplier->head_office_address      = $ss->head_office_address;
                $supplier->tin_number               = $ss->tin_number;
                $supplier->bank_account_number      = $ss->bank_account_number;
                $supplier->date_of_enrollment       = $ss->date_of_enrollment;
                $supplier->bank_name_and_branch     = $ss->bank_name_and_branch;
                $supplier->rltn_w_acc_holder        = $ss->rltn_w_acc_holder;
                $supplier->branch_office_address    = $ss->branch_office_address;
                $supplier->intr_name                = $ss->intr_name;
                $supplier->intr_designation         = $ss->intr_designation;
                $supplier->intr_address             = $ss->intr_address;
                $supplier->cur_reg_supplier_name    = $ss->cur_reg_supplier_name;
                $supplier->cur_reg_supplier_address = $ss->cur_reg_supplier_address;
                $supplier->defaulter_before         = $ss->defaulter_before;
                $supplier->company_partnership_act  = $ss->company_partnership_act;
                $supplier->registered_as            = $ss->registered_as;
                $supplier->des_of_sole_prtship      = $ss->des_of_sole_prtship;
                $supplier->partners_name            = $ss->partners_name;
                $supplier->partners_address         = $ss->partners_address;
                $supplier->auth_prsn_name           = $ss->auth_prsn_name;
                $supplier->auth_prsn_designation    = $ss->auth_prsn_designation;
                $supplier->vat_registration_number  = $ss->vat_registration_number;
                $supplier->bsti_certification       = $ss->bsti_certification;
                $supplier->nid_number               = $ss->nid_number;
                $supplier->trade_license_number     = $ss->trade_license_number;
                $supplier->trade_license_address    = $ss->trade_license_address;
                $supplier->registered_nsd_id        = $ss->registered_nsd_id;
                $supplier->iso_certification        = $ss->iso_certification;
                $supplier->supply_cat_id            = $ss->supply_cat_id;
                $supplier->status_id                = $ss->status_id;
                $supplier->profile_pic              = $ss->profile_pic;
                $supplier->tin_certificate          = $ss->tin_certificate;
                $supplier->testimonial              = $ss->testimonial;
                $supplier->bangla_signature         = $ss->bangla_signature;
                $supplier->english_signature        = $ss->english_signature;
                $supplier->attested_photo           = $ss->attested_photo;
                $supplier->attested_trade_lic       = $ss->attested_trade_lic;
                $supplier->attested_nid_photocopy   = $ss->attested_nid_photocopy;
                $supplier->attested_char_cert       = $ss->attested_char_cert;
                $supplier->att_vat_reg_cert         = $ss->att_vat_reg_cert;
                $supplier->att_vat_return_last_cert = $ss->att_vat_return_last_cert;
                $supplier->att_edu_cert             = $ss->att_edu_cert;
                $supplier->lst_six_mnth_bnk_sttmnt  = $ss->lst_six_mnth_bnk_sttmnt;
                $supplier->bnk_solvency_certi       = $ss->bnk_solvency_certi;
                $supplier->non_judicial_stamp       = $ss->non_judicial_stamp;
                $supplier->non_judicial_stamp       = $ss->non_judicial_stamp;
                $supplier->created_by = $ss->created_by;
                $supplier->updated_by = $ss->updated_by;
                $supplier->updated_at = $insertInLogTable->updated_at;

                $supplier->save();
        }// End if tbl == 1 =========================

        // Supplier basic information ======================================================
        if($tbl==5){
            \Session::put('zoneAlise', strtolower($zone));

            $ifexist = SupplierBasicInfo::where('all_org_sup_bas_info_id','=',$organization.'_'.$ss->id)->first();

            if(empty($ifexist)){
                $supplier_bsc_info = new SupplierBasicInfo();
            }else{
                $supplier_bsc_info = SupplierBasicInfo::find($ifexist->id);
            }

            //$supplier_bsc_info = new SupplierBasicInfo();

            $supplier_bsc_info->all_org_sup_bas_info_id = $ss->organization.'_'.$ss->id;
            $supplier_bsc_info->supplier_id = $ss->organization.'_'.$ss->supplier_id;
            $supplier_bsc_info->full_name = $ss->full_name;
            $supplier_bsc_info->father_name = $ss->father_name;
            $supplier_bsc_info->father_nid = $ss->father_nid;
            $supplier_bsc_info->mother_name = $ss->mother_name;
            $supplier_bsc_info->mother_nid = $ss->mother_nid;
            $supplier_bsc_info->permanent_address = $ss->permanent_address;
            $supplier_bsc_info->present_address = $ss->present_address;
            $supplier_bsc_info->birth_place = $ss->birth_place;
            $supplier_bsc_info->birth_date = $ss->birth_date;
            $supplier_bsc_info->height = $ss->height;
            $supplier_bsc_info->weight = $ss->weight;
            $supplier_bsc_info->color = $ss->color;
            $supplier_bsc_info->eye_color = $ss->eye_color;
            $supplier_bsc_info->identification_mark = $ss->identification_mark;
            $supplier_bsc_info->religion = $ss->religion;
            $supplier_bsc_info->nationality = $ss->nationality;
            $supplier_bsc_info->organization = $ss->organization;
            $supplier_bsc_info->rank_in_organization = $ss->rank_in_organization;
            $supplier_bsc_info->business_start_date = $ss->business_start_date;
            $supplier_bsc_info->organization_name = $ss->organization_name;
            $supplier_bsc_info->organization_branch = $ss->organization_branch;
            $supplier_bsc_info->membership_number = $ss->membership_number;
            $supplier_bsc_info->membrsip_cont_date = $ss->membrsip_cont_date;
            $supplier_bsc_info->date_of_registry = $ss->date_of_registry;
            $supplier_bsc_info->jn_date_of_prsnt_ocuptn = $ss->jn_date_of_prsnt_ocuptn;
            $supplier_bsc_info->des_of_pre_occu = $ss->des_of_pre_occu;
            $supplier_bsc_info->offence = $ss->offence;
            $supplier_bsc_info->offence_date = $ss->offence_date;
            $supplier_bsc_info->offence_place = $ss->offence_place;
            $supplier_bsc_info->des_of_offence = $ss->des_of_offence;
            $supplier_bsc_info->spouse_full_name = $ss->spouse_full_name;
            $supplier_bsc_info->spouse_nid = $ss->spouse_nid;
            $supplier_bsc_info->spouse_father_name = $ss->spouse_father_name;
            $supplier_bsc_info->spouse_father_nid = $ss->spouse_father_nid;
            $supplier_bsc_info->spouse_mother_name = $ss->spouse_mother_name;
            $supplier_bsc_info->spouse_mother_nid = $ss->spouse_mother_nid;
            $supplier_bsc_info->spouse_per_address = $ss->spouse_per_address;
            $supplier_bsc_info->spouse_pre_address = $ss->spouse_pre_address;
            $supplier_bsc_info->spouse_birth_place = $ss->spouse_birth_place;
            $supplier_bsc_info->spouse_birth_date = $ss->spouse_birth_date;
            $supplier_bsc_info->spouse_nationality = $ss->spouse_nationality;
            $supplier_bsc_info->spouse_occupation = $ss->spouse_occupation;
            $supplier_bsc_info->applicant_signature = $ss->applicant_signature;
            $supplier_bsc_info->applicant_seal = $ss->applicant_seal;
            $supplier_bsc_info->signature_place = $ss->signature_place;
            $supplier_bsc_info->signature_date = $ss->signature_date;
            $supplier_bsc_info->created_by = $ss->created_by;
            $supplier_bsc_info->updated_by = $ss->updated_by;
            $supplier_bsc_info->updated_at = $insertInLogTable->updated_at;

            $supplier_bsc_info->save();
        }// End if tbl == 5 =========================

        // Item information ======================================================
        if($tbl==2){
            \Session::put('zoneAlise', strtolower($zone));

            $ifexist = Item::where('all_org_item_id','=',$organization.'_'.$ss->id)->first();

            if(empty($ifexist)){
                $item = new Item();
            }else{
                $item = Item::find($ifexist->id);
            }

            //$item = new Item();

            $item->all_org_item_id              = $ss->organization.'_'.$ss->id;
            $item->imc_number                   = $ss->imc_number;
            $item->item_name                    = $ss->item_name;
            $item->model_number                 = $ss->model_number;
            $item->item_cat_id                  = $ss->item_cat_id;
            $item->nsd_id                       = $ss->nsd_id;
            $item->unit_price                   = $ss->unit_price;
            $item->unit_price_in_bdt            = $ss->unit_price_in_bdt;
            $item->currency_name                = $ss->currency_name;
            $item->conversion                   = $ss->conversion;
            $item->discounted_price             = $ss->discounted_price;
            $item->discounted_price_in_bdt      = $ss->discounted_price_in_bdt;
            $item->item_deno                    = $ss->item_deno;
            $item->manufacturing_country        = $ss->manufacturing_country;
            $item->source_of_supply             = $ss->source_of_supply;
            $item->other_info_about_itme        = $ss->other_info_about_itme;
            $item->budget_code                  = $ss->budget_code;
            $item->status_id                    = $ss->status_id;
            $item->created_by                   = $ss->created_by;
            $item->updated_by                   = $ss->updated_by;
            $item->updated_at = $insertInLogTable->updated_at;

            $item->save();
        }// End if tbl == 2 =========================

        // Tender information ======================================================
        if($tbl==3){
            \Session::put('zoneAlise', strtolower($zone));

            $ifexist = Tender::where('all_org_tender_id','=',$organization.'_'.$ss->id)->first();

            if(empty($ifexist)){
                $tender = new Tender();
            }else{
                $tender = Tender::find($ifexist->id);
            }
            $tender->all_org_tender_id       = $ss->organization.'_'.$ss->id;
            $tender->po_number               = $ss->po_number;
            $tender->tender_title            = $ss->tender_title;
            $tender->tender_number           = $ss->tender_number;
            $tender->tender_description      = $ss->tender_description;
            $tender->tender_opening_date     = $ss->tender_opening_date;
            $tender->supplier_id             = $ss->supplier_id;
            $tender->work_order_date         = $ss->work_order_date;
            $tender->date_line               = $ss->date_line;


            $tender->delivery_date           = $ss->delivery_date;
            $tender->imc_number              = $ss->imc_number;
            $tender->tender_cat_id           = $ss->tender_cat_id;
            $tender->nsd_id                  = $ss->nsd_id;
            $tender->other_info_about_tender = $ss->other_info_about_tender;
            $tender->specification           = $ss->specification;
            $tender->notice                  = $ss->notice;
            $tender->open_tender             = $ss->open_tender;
            $tender->number_of_lot_item      = $ss->number_of_lot_item;
            $tender->status_id               = $ss->status_id;
            $tender->created_by              = $ss->created_by;
            $tender->updated_by              = $ss->updated_by;
            $tender->updated_at = $insertInLogTable->updated_at;

            $tender->save();
        }// End if tbl == 3 =========================

        // Item To Tender information ======================================================
        if($tbl==4){
            \Session::put('zoneAlise', strtolower($zone));

            $ifexist = ItemToTender::where('all_org_itmtotender_id','=',$organization.'_'.$ss->id)->first();

            if(empty($ifexist)){
                $itemtotender = new ItemToTender();
            }else{
                $itemtotender = ItemToTender::find($ifexist->id);
            }

            $itemtotender->all_org_itmtotender_id            = $ss->organization.'_'.$ss->id;
            $itemtotender->tender_id                         = $ss->organization.'_'.$ss->tender_id;
            $itemtotender->item_id                           = $ss->organization.'_'.$ss->item_id;
            $itemtotender->unit_price                        = $ss->unit_price;
            $itemtotender->unit_price_in_bdt                 = $ss->unit_price_in_bdt;
            $itemtotender->currency_name                     = $ss->currency_name;
            $itemtotender->conversion                        = $ss->conversion;
            $itemtotender->quantity                          = $ss->quantity;
            $itemtotender->discount_price                    = $ss->discount_price;
            $itemtotender->discount_price_in_bdt             = $ss->discount_price_in_bdt;
            $itemtotender->total                             = $ss->total;
            $itemtotender->status_id                         = $ss->status_id;
            $itemtotender->created_by                        = $ss->created_by;
            $itemtotender->updated_by                        = $ss->updated_by;

            $itemtotender->save();
        }// End if tbl == 4 =========================
        
    
    } // End chittagong update function

    // End for new testing api =====================================================
    // =========================================================================
    // =========================================================================



}
