<?php

namespace App\Http\Controllers;
use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
//use Request;
use App\Category;
use App\Zone;
use App\NsdName;
use App\Notice;
use App\SupplyCategory;
use App\ItemToTender;
use App\Supplier;
use App\SupplierBasicInfo;
use App\Tender;
use Illuminate\Support\Facades\Validator;
use Input;
use DB;
use Auth;
use Session;

class FrontRegistrationController extends Controller
{
    private $imageResizeCtrl;
    private $tableAlies;

    public function __construct() {
        $this->imageResizeCtrl = new ImageResizeController();
    }

    public function termCondi($zone=null,$nsd=null){
        // Only for admin =========================================================================
        if(empty($zone) && empty($nsd)){
            // $zoneInfo = Zone::where('status','=',1)->orderBy('id')->first();
            // $navalLocation = NsdName::where('status_id','=',1)->orderBy('id')->first();

            $navalLocation = NsdName::where('status_id','=',1)->where('default_selected','=',1)->orderBy('id')->first();
            $zoneInfo = Zone::where('id','=',$navalLocation->zones)->where('status','=',1)->orderBy('id')->first();

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->orderBy('id','desc')->get();
        }

        // Only for admin =========================================================================
        if(!empty($zone) && !empty($nsd)){
            $zoneInfo = Zone::where('alise','=',$zone)->first();
            $navalLocation = NsdName::where('alise','=',$nsd)->orderBy('id')->first();

            $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->take(5)->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->whereIn('id',$noticeIds)->orderBy('id','desc')->get();
        }
        $data['navallocation'] = $navalLocation;

        $data['zoneInfo'] = $zoneInfo;
        $data['organizations'] = NsdName::whereNotIn('id',[$navalLocation->id])->where('status_id','=',1)->get();

        $data['organizationsHead'] = [];

        foreach ($data['organizations'] as $sd){
            $exp = explode(',',$sd->zones);
            $exp = $exp[0];
            $zoneAlise = Zone::where('id','=',$exp)->first();
            $data['organizationsHead'][] = $sd->setAttribute('zoneAlise', $zoneAlise->alise);

        }

        $data['termsconditions'] = \App\TermsCondition::first();
        return view('frontend.teamsandconditions',$data);
    }

    public function postTermCondi(Request $request){

        $a = empty($request->segment(1))? '0/':$request->segment(1).'/';
        $b = empty($request->segment(2))? '0/':$request->segment(2).'/';

        if(!empty($request->agree)){
            $randnumber = rand(10,100);
            Session::put('regKey', $randnumber);
            return Redirect::to($a.$b.$randnumber.'/sign-up/');
        }else{
            return Redirect::to('/').'/'.$a.$b;
        }
    }

    public function index($zone=null,$nsd=null,$tmcn=null){

        $zone = $zone;
        $nsd  = $nsd;

        if(Session::get('regKey') != $tmcn){
            return Redirect::to($zone.'/'.$nsd.'/terms-condiition');
        }

        // Only for admin =========================================================================
        if(empty($zone) && empty($nsd)){
            // $zoneInfo = Zone::where('status','=',1)->orderBy('id')->first();
            // $navalLocation = NsdName::where('status_id','=',1)->orderBy('id')->first();

            $navalLocation = NsdName::where('status_id','=',1)->where('default_selected','=',1)->orderBy('id')->first();
            $zoneInfo = Zone::where('id','=',$navalLocation->zones)->where('status','=',1)->orderBy('id')->first();

            $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->take(5)->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->whereIn('id',$noticeIds)->orderBy('id','desc')->get();
            $categoriesId = SupplyCategory::select('id','name','zones')->where('status_id','=',1)->get();
            $categoriesIds = array();
            foreach($categoriesId as $cti){
                foreach (explode(',',$cti->zones) as $zid){
                    if($zoneInfo->id==$zid){
                        $categoriesIds[] = $cti->id;
                    }
                }
            }

            $data['categories'] = SupplyCategory::select('id','name')->whereIn('id',$categoriesIds)->where('status_id','=',1)->get();
            $data['navalLocations'] = NsdName::select('id','name')->where('id','=',$navalLocation->id)->where('status_id','=',1)->get();

            //$data['importantNotices'] = Notice::where('is_important','=',1)->where('status_id','=',1)->orderBy('id','desc')->get();
            //$data['categories'] = SupplyCategory::select('id','name')->where('status_id','=',1)->get();
            //$data['navalLocations'] = NsdName::select('id','name')->where('status_id','=',1)->get();
        }

        // Only for admin =========================================================================
        if(!empty($zone) && !empty($nsd)){
            $zoneInfo = Zone::where('alise','=',$zone)->first();
            $navalLocation = NsdName::where('alise','=',$nsd)->orderBy('id')->first();

            $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->take(5)->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->whereIn('id',$noticeIds)->orderBy('id','desc')->get();
            $categoriesId = SupplyCategory::select('id','name','zones')->where('status_id','=',1)->get();
            $categoriesIds = array();
            foreach($categoriesId as $cti){
                foreach (explode(',',$cti->zones) as $zid){
                    if($zoneInfo->id==$zid){
                        $categoriesIds[] = $cti->id;
                    }
                }
            }
            $data['categories'] = SupplyCategory::select('id','name')->whereIn('id',$categoriesIds)->where('status_id','=',1)->get();
            $data['navalLocations'] = NsdName::select('id','name')->where('id','=',$navalLocation->id)->where('status_id','=',1)->get();
            //$data['navalLocations'] = NsdName::select('id','name')->where('alise','=',$nsd)->where('status_id','=',1)->get();
        }
        $data['navallocation'] = $navalLocation;

        $data['zoneInfo'] = $zoneInfo;
        $data['organizations'] = NsdName::whereNotIn('id',[$navalLocation->id])->where('status_id','=',1)->get();

        $data['organizationsHead'] = [];

        foreach ($data['organizations'] as $sd){
            $exp = explode(',',$sd->zones);
            $exp = $exp[0];
            $zoneAlise = Zone::where('id','=',$exp)->first();
            $data['organizationsHead'][] = $sd->setAttribute('zoneAlise', $zoneAlise->alise);

        }

        return view('frontend.registration',$data);

    }

    public function store(Request $request){

        $a = empty($request->segment(1))? '0/':$request->segment(1).'/';
        $b = empty($request->segment(2))? '0/':$request->segment(2).'/';

        $navalLocation = NsdName::find($request->registered_nsd_id);
        // $zoneIds = min(explode(',',$navalLocation->zones));
        $zoneIds = $navalLocation->zones;

        if(empty($request->segment(1))){
            $zone = Zone::find($zoneIds);
        }else{
            $zone = Zone::where('alise','=',$request->segment(1))->first();
        }
        \Session::put('zoneAlise', strtolower($zone->alise));


        $v = \Validator::make($request->all(), [
            'email' => 'required|unique:'.$zone->alise.'_suppliers,email',
        ]);

        if ($v->fails()) {
            //return Redirect::to($a.$b.$randnumber.'/sign-up/');
            return redirect($a.$b.Session::get('regKey').'/sign-up')->withErrors($v->errors())->withInput();
        }else {


            $supplier = new Supplier();

            $lastRegisTrationNo = Supplier::orderBy('id', 'desc')->first();

            if (!empty($lastRegisTrationNo)) {
                $lastforDigit = substr($lastRegisTrationNo->company_regi_number_nsd, -4);
                $increment = $lastforDigit + 1;
                $company_regi_number_nsd = date('Ym') . $zone->id . $request->registered_nsd_id . str_pad($increment, 4, "0", STR_PAD_LEFT);
            } else {
                $company_regi_number_nsd = date('Ym') . $zone->id . $request->registered_nsd_id . str_pad(1, 4, "0", STR_PAD_LEFT);
            }


            $supplier->company_name = $request->company_name;
            $supplier->company_regi_number_nsd = $company_regi_number_nsd;
            $supplier->mobile_number = $request->mobile_number;
            $supplier->fax = empty($request->fax) ? null : $request->fax;
            $supplier->email = $request->email;
            $supplier->head_office_address = $request->head_office_address;
            $supplier->tin_number = $request->tin_number;
            $supplier->bank_account_number = $request->bank_account_number;
            $supplier->bank_name_and_branch = $request->bank_name . ' ' . $request->address;
            $supplier->rltn_w_acc_holder = $request->rltn_w_acc_holder;
            $supplier->branch_office_address = empty($request->branch_office_address) ? null : $request->branch_office_address;
            $supplier->intr_name = json_encode($request->intr_name);
            $supplier->intr_designation = json_encode($request->intr_designation);
            $supplier->intr_address = json_encode($request->intr_address);
            $supplier->cur_reg_supplier_name = json_encode($request->cur_reg_supplier_name);
            $supplier->cur_reg_supplier_address = json_encode($request->cur_reg_supplier_address);
            $supplier->defaulter_before = $request->defaulter_before;
            $supplier->company_partnership_act = $request->company_partnership_act;
            $supplier->registered_as = $request->registered_as;
            $supplier->des_of_sole_prtship = empty($request->des_of_sole_prtship) ? null : $request->des_of_sole_prtship;
            $supplier->partners_name = empty($request->partners_name) ? null : json_encode($request->partners_name);
            $supplier->partners_address = empty($request->partners_address) ? null : json_encode($request->partners_address);
            $supplier->auth_prsn_name = json_encode($request->auth_prsn_name);
            $supplier->auth_prsn_designation = json_encode($request->auth_prsn_designation);
            $supplier->vat_registration_number = $request->vat_registration_number;
            $supplier->bsti_certification = $request->bsti_certification;
            $supplier->nid_number = $request->nid_number;
            $supplier->trade_license_number = $request->trade_license_number;
            $supplier->trade_license_address = $request->trade_license_address;
            $supplier->registered_nsd_id = $request->registered_nsd_id;
            $supplier->iso_certification = $request->iso_certification;
            $supplier->supply_cat_id = $request->supply_cat_id;
            $supplier->status_id = 3;


            if (Input::hasFile('profile_pic')) {
                $file = Input::file('profile_pic');
                $destinationPath = public_path() . '/uploads/supplier_profile/';
                $logofilename = uniqid() . $file->getClientOriginalName();
                $uploadSuccess = Input::file('profile_pic')->move($destinationPath, $logofilename);
                $supplier->profile_pic = $logofilename;
            }

            if (Input::hasFile('tin_certificate')) {
                $file = Input::file('tin_certificate');
                $destinationPath = public_path() . '/uploads/supplier_profile/';
                $tin_certificate = uniqid() . $file->getClientOriginalName();
                $uploadSuccess = Input::file('tin_certificate')->move($destinationPath, $tin_certificate);
                $supplier->tin_certificate = $tin_certificate;
            }

            $testimonialArray = array();
            if (count($request->testimonial) > 0) {
                for ($i = 0; count($request->testimonial) > $i; $i++) {
                    if (!empty($request->testimonial[$i])) {
                        $file = $request->testimonial[$i];
                        $destinationPath = public_path() . '/uploads/supplier_profile/';
                        $testimonial = uniqid() . $file->getClientOriginalName();
                        $uploadSuccess = $file->move($destinationPath, $testimonial);
                        $testimonialArray[] = $testimonial;
                    }
                }
                $supplier->testimonial = json_encode($testimonialArray);
            }
            $banglaSigArray = array();
            if (count($request->bangla_signature) > 0) {
                for ($i = 0; count($request->bangla_signature) > $i; $i++) {
                    if (!empty($request->bangla_signature[$i])) {
                        $file = $request->bangla_signature[$i];
                        $destinationPath = public_path() . '/uploads/supplier_profile/';
                        $bangla_signature = uniqid() . $file->getClientOriginalName();
                        $uploadSuccess = $file->move($destinationPath, $bangla_signature);
                        $banglaSigArray[] = $bangla_signature;
                    }
                }
                $supplier->bangla_signature = json_encode($banglaSigArray);
            }
            $englishSigArray = array();
            if (count($request->english_signature) > 0) {
                for ($i = 0; count($request->english_signature) > $i; $i++) {
                    if (!empty($request->english_signature[$i])) {
                        $file = $request->english_signature[$i];
                        $destinationPath = public_path() . '/uploads/supplier_profile/';
                        $english_signature = uniqid() . $file->getClientOriginalName();
                        $uploadSuccess = $file->move($destinationPath, $english_signature);
                        $englishSigArray[] = $english_signature;
                    }
                }
                $supplier->english_signature = json_encode($englishSigArray);
            }

            if (Input::hasFile('attested_photo')) {
                $file = Input::file('attested_photo');
                $destinationPath = public_path() . '/uploads/supplier_profile/';
                $attested_photo = uniqid() . $file->getClientOriginalName();
                $uploadSuccess = Input::file('attested_photo')->move($destinationPath, $attested_photo);
                $supplier->attested_photo = $attested_photo;
            }

            if (Input::hasFile('attested_trade_lic')) {
                $file = Input::file('attested_trade_lic');
                $destinationPath = public_path() . '/uploads/supplier_profile/';
                $attested_trade_lic = uniqid() . $file->getClientOriginalName();
                $uploadSuccess = Input::file('attested_trade_lic')->move($destinationPath, $attested_trade_lic);
                $supplier->attested_trade_lic = $attested_trade_lic;
            }

            if (Input::hasFile('attested_nid_photocopy')) {
                $file = Input::file('attested_nid_photocopy');
                $destinationPath = public_path() . '/uploads/supplier_profile/';
                $attested_nid_photocopy = uniqid() . $file->getClientOriginalName();
                $uploadSuccess = Input::file('attested_nid_photocopy')->move($destinationPath, $attested_nid_photocopy);
                $supplier->attested_nid_photocopy = $attested_nid_photocopy;
            }

            if (Input::hasFile('attested_char_cert')) {
                $file = Input::file('attested_char_cert');
                $destinationPath = public_path() . '/uploads/supplier_profile/';
                $attested_char_cert = uniqid() . $file->getClientOriginalName();
                $uploadSuccess = Input::file('attested_char_cert')->move($destinationPath, $attested_char_cert);
                $supplier->attested_char_cert = $attested_char_cert;
            }

            if (Input::hasFile('att_vat_reg_cert')) {
                $file = Input::file('att_vat_reg_cert');
                $destinationPath = public_path() . '/uploads/supplier_profile/';
                $att_vat_reg_cert = uniqid() . $file->getClientOriginalName();
                $uploadSuccess = Input::file('att_vat_reg_cert')->move($destinationPath, $att_vat_reg_cert);
                $supplier->att_vat_reg_cert = $att_vat_reg_cert;
            }

            if (Input::hasFile('att_vat_return_last_cert')) {
                $file = Input::file('att_vat_return_last_cert');
                $destinationPath = public_path() . '/uploads/supplier_profile/';
                $att_vat_return_last_cert = uniqid() . $file->getClientOriginalName();
                $uploadSuccess = Input::file('att_vat_return_last_cert')->move($destinationPath, $att_vat_return_last_cert);
                $supplier->att_vat_return_last_cert = $att_vat_return_last_cert;
            }

//        if (Input::hasFile('att_edu_cert')) {
//            $file = Input::file('att_edu_cert');
//            $destinationPath = public_path() . '/uploads/supplier_profile/';
//            $att_edu_cert = uniqid() . $file->getClientOriginalName();
//            $uploadSuccess = Input::file('att_edu_cert')->move($destinationPath, $att_edu_cert);
//            $supplier->att_edu_cert           = $att_edu_cert;
//        }

            if (Input::hasFile('att_edu_cert')) {
                $file = Input::file('att_edu_cert');
                $destinationPath = public_path() . '/uploads/supplier_profile/';
                $att_edu_cert = uniqid() . $file->getClientOriginalName();
                $uploadSuccess = Input::file('att_edu_cert')->move($destinationPath, $att_edu_cert);
                $supplier->att_edu_cert = $att_edu_cert;
            }

            if (Input::hasFile('lst_six_mnth_bnk_sttmnt')) {
                $file = Input::file('lst_six_mnth_bnk_sttmnt');
                $destinationPath = public_path() . '/uploads/supplier_profile/';
                $lst_six_mnth_bnk_sttmnt = uniqid() . $file->getClientOriginalName();
                $uploadSuccess = Input::file('lst_six_mnth_bnk_sttmnt')->move($destinationPath, $lst_six_mnth_bnk_sttmnt);
                $supplier->lst_six_mnth_bnk_sttmnt = $lst_six_mnth_bnk_sttmnt;
            }

            if (Input::hasFile('bnk_solvency_certi')) {
                $file = Input::file('bnk_solvency_certi');
                $destinationPath = public_path() . '/uploads/supplier_profile/';
                $bnk_solvency_certi = uniqid() . $file->getClientOriginalName();
                $uploadSuccess = Input::file('bnk_solvency_certi')->move($destinationPath, $bnk_solvency_certi);
                $supplier->bnk_solvency_certi = $bnk_solvency_certi;
            }

            if (Input::hasFile('non_judicial_stamp')) {
                $file = Input::file('non_judicial_stamp');
                $destinationPath = public_path() . '/uploads/supplier_profile/';
                $non_judicial_stamp = uniqid() . $file->getClientOriginalName();
                $uploadSuccess = Input::file('non_judicial_stamp')->move($destinationPath, $non_judicial_stamp);
                $supplier->non_judicial_stamp = $non_judicial_stamp;
            }

            if ($supplier->save()) {

                \Session::put('regKey', '');

                \Session::put('zoneAlise', strtolower($zone->alise));
                \Session::put('newly_created_supplier_id', strtolower($supplier->id));
                return redirect($a . $b . $supplier->id.'/front-registration2');
            }

        }

    }

    // Registration from 2
    public function registrationsForm2($zone=null,$nsd=null,$id=null){

        $zone = $zone;
        $nsd  = $nsd;

        if(Session::get('newly_created_supplier_id') != $id){
            return Redirect::to($zone.'/'.$nsd.'/terms-condiition');
        }

        // Only for admin =========================================================================
        if(empty($zone) && empty($nsd)){
            // $zoneInfo = Zone::where('status','=',1)->orderBy('id')->first();
            // $navalLocation = NsdName::where('status_id','=',1)->orderBy('id')->first();

            $navalLocation = NsdName::where('status_id','=',1)->where('default_selected','=',1)->orderBy('id')->first();
            $zoneInfo = Zone::where('id','=',$navalLocation->zones)->where('status','=',1)->orderBy('id')->first();

            $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->take(5)->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->whereIn('id',$noticeIds)->orderBy('id','desc')->get();
            $categoriesId = SupplyCategory::select('id','name','zones')->where('status_id','=',1)->get();
            $categoriesIds = array();
            foreach($categoriesId as $cti){
                foreach (explode(',',$cti->zones) as $zid){
                    if($zoneInfo->id==$zid){
                        $categoriesIds[] = $cti->id;
                    }
                }
            }

            $data['categories'] = SupplyCategory::select('id','name')->whereIn('id',$categoriesIds)->where('status_id','=',1)->get();
            $data['navalLocations'] = NsdName::select('id','name')->where('id','=',$navalLocation->id)->where('status_id','=',1)->get();
        }

        // Only for admin =========================================================================
        if(!empty($zone) && !empty($nsd)){
            $zoneInfo = Zone::where('alise','=',$zone)->first();
            $navalLocation = NsdName::where('alise','=',$nsd)->orderBy('id')->first();

            $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->take(5)->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->orderBy('id','desc')->get();
            $categoriesId = SupplyCategory::select('id','name','zones')->where('status_id','=',1)->get();
            $categoriesIds = array();
            foreach($categoriesId as $cti){
                foreach (explode(',',$cti->zones) as $zid){
                    if($zoneInfo->id==$zid){
                        $categoriesIds[] = $cti->id;
                    }
                }
            }
            $data['categories'] = SupplyCategory::select('id','name')->whereIn('id',$categoriesIds)->where('status_id','=',1)->get();
            $data['navalLocations'] = NsdName::select('id','name')->where('id','=',$navalLocation->id)->where('status_id','=',1)->get();
            //$data['navalLocations'] = NsdName::select('id','name')->where('alise','=',$nsd)->where('status_id','=',1)->get();
        }
        $data['navallocation'] = $navalLocation;

        $data['zoneInfo'] = $zoneInfo;
        $data['organizations'] = NsdName::whereNotIn('id',[$navalLocation->id])->where('status_id','=',1)->get();

        $data['organizationsHead'] = [];

        foreach ($data['organizations'] as $sd){
            $exp = explode(',',$sd->zones);
            $exp = $exp[0];
            $zoneAlise = Zone::where('id','=',$exp)->first();
            $data['organizationsHead'][] = $sd->setAttribute('zoneAlise', $zoneAlise->alise);

        }

        return view('frontend.registration2',$data);

    }// end registration from 2

    public function store2(Request $request){

        $a = empty($request->segment(1))? '0/':$request->segment(1).'/';
        $b = empty($request->segment(2))? '0/':$request->segment(2).'/';
        $id = $request->id;

        $supplier = Supplier::find($id);

        $navalLocation = NsdName::find($supplier->registered_nsd_id);
        //$zoneIds = min(explode(',',$navalLocation->zones));
        $zoneIds = $navalLocation->zones;

        if(empty($request->segment(1))){
            $zone = Zone::find($zoneIds);
        }else{
            $zone = Zone::where('alise','=',$request->segment(1))->first();
        }
        \Session::put('zoneAlise', strtolower($zone->alise));

        //$supplier_bsc_info = SupplierBasicInfo::find(1);
        $supplier_bsc_info = new SupplierBasicInfo();

        $supplier_bsc_info->supplier_id = $id;
        $supplier_bsc_info->full_name = $request->full_name;
        $supplier_bsc_info->father_name = $request->father_name;
        $supplier_bsc_info->father_nid = $request->father_nid;
        $supplier_bsc_info->mother_name = $request->mother_name;
        $supplier_bsc_info->mother_nid = $request->mother_nid;
        $supplier_bsc_info->permanent_address = $request->permanent_address;
        $supplier_bsc_info->present_address = $request->present_address;
        $supplier_bsc_info->birth_place = $request->birth_place;
        $supplier_bsc_info->birth_date = date('Y-m-d',strtotime($request->birth_date));
        $supplier_bsc_info->height = $request->height;
        $supplier_bsc_info->weight = $request->weight;
        $supplier_bsc_info->color = $request->color;
        $supplier_bsc_info->eye_color = $request->eye_color;
        $supplier_bsc_info->identification_mark = $request->identification_mark;
        $supplier_bsc_info->religion = $request->religion;
        $supplier_bsc_info->nationality = $request->nationality;
        $supplier_bsc_info->organization = $request->organization;
        $supplier_bsc_info->rank_in_organization = $request->rank_in_organization;
        $supplier_bsc_info->business_start_date = date('Y-m-d',strtotime($request->business_start_date));
        $supplier_bsc_info->organization_name = $request->organization_name;
        $supplier_bsc_info->organization_branch = $request->organization_branch;
        $supplier_bsc_info->membership_number = $request->membership_number;
        $supplier_bsc_info->membrsip_cont_date = date('Y-m-d',strtotime($request->membrsip_cont_date));
        $supplier_bsc_info->date_of_registry = date('Y-m-d',strtotime($request->date_of_registry));
        $supplier_bsc_info->jn_date_of_prsnt_ocuptn = date('Y-m-d',strtotime($request->jn_date_of_prsnt_ocuptn));
        $supplier_bsc_info->des_of_pre_occu = $request->des_of_pre_occu;
        $supplier_bsc_info->offence = $request->offence;
        $supplier_bsc_info->offence_date = date('Y-m-d',strtotime($request->offence_date));
        $supplier_bsc_info->offence_place = $request->offence_place;
        $supplier_bsc_info->des_of_offence = $request->des_of_offence;
        $supplier_bsc_info->spouse_full_name = $request->spouse_full_name;
        $supplier_bsc_info->spouse_nid = $request->spouse_nid;
        $supplier_bsc_info->spouse_father_name = $request->spouse_father_name;
        $supplier_bsc_info->spouse_father_nid = $request->spouse_father_nid;
        $supplier_bsc_info->spouse_mother_name = $request->spouse_mother_name;
        $supplier_bsc_info->spouse_mother_nid = $request->spouse_mother_nid;
        $supplier_bsc_info->spouse_per_address = $request->spouse_per_address;
        $supplier_bsc_info->spouse_pre_address = $request->spouse_pre_address;
        $supplier_bsc_info->spouse_birth_place = $request->spouse_birth_place;
        $supplier_bsc_info->spouse_birth_date = date('Y-m-d',strtotime($request->spouse_birth_date));
        $supplier_bsc_info->spouse_nationality = $request->spouse_nationality;
        $supplier_bsc_info->spouse_occupation = $request->spouse_occupation;


        if (Input::hasFile('applicant_signature')) {
            $file = Input::file('applicant_signature');
            $destinationPath = public_path() . '/uploads/supplier_profile/';
            $applicant_signature = uniqid() . $file->getClientOriginalName();
            $uploadSuccess = Input::file('applicant_signature')->move($destinationPath, $applicant_signature);
            $supplier_bsc_info->applicant_signature = $applicant_signature;
        }

        if (Input::hasFile('applicant_seal')) {
            $file = Input::file('applicant_seal');
            $destinationPath = public_path() . '/uploads/supplier_profile/';
            $applicant_seal = uniqid() . $file->getClientOriginalName();
            $uploadSuccess = Input::file('applicant_seal')->move($destinationPath, $applicant_seal);
            $supplier_bsc_info->applicant_seal = $applicant_seal;
        }
        $supplier_bsc_info->signature_place = $request->signature_place;
        $supplier_bsc_info->signature_date = date('Y-m-d',strtotime($request->signature_date));

        if ($supplier_bsc_info->save()) {

            \Session::put('newly_created_supplier_id', '');

            Session::flash('success', 'Supplier registration process has been submitted successfully, Kindly submit your hard copy of papers to NSD office and get approval from NSD.');
            return Redirect::to($a.$b.'reg-success');
        }

    }

    public function regSuccess($zone=null,$nsd=null){
        
        // Only for admin =========================================================================
        if(empty($zone) && empty($nsd)){
            // $zoneInfo = Zone::where('status','=',1)->orderBy('id')->first();
            // $navalLocation = NsdName::where('status_id','=',1)->orderBy('id')->first();

            $navalLocation = NsdName::where('status_id','=',1)->where('default_selected','=',1)->orderBy('id')->first();
            $zoneInfo = Zone::where('id','=',$navalLocation->zones)->where('status','=',1)->orderBy('id')->first();

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->orderBy('id','desc')->get();
        }

        // Only for admin =========================================================================
        if(!empty($zone) && !empty($nsd)){
            $zoneInfo = Zone::where('alise','=',$zone)->first();
            $navalLocation = NsdName::where('alise','=',$nsd)->orderBy('id')->first();

            $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->take(5)->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->orderBy('id','desc')->get();
        }
        $data['navallocation'] = $navalLocation;

        $data['zoneInfo'] = $zoneInfo;
        $data['organizations'] = NsdName::whereNotIn('id',[$navalLocation->id])->where('status_id','=',1)->get();

        $data['organizationsHead'] = [];

        foreach ($data['organizations'] as $sd){
            $exp = explode(',',$sd->zones);
            $exp = $exp[0];
            $zoneAlise = Zone::where('id','=',$exp)->first();
            $data['organizationsHead'][] = $sd->setAttribute('zoneAlise', $zoneAlise->alise);

        }

        return view('frontend.reg-success',$data);
    }

}



