<?php

namespace App\Http\Controllers;
use App\Settings;
use Illuminate\Http\Request;
use App\Category;
use App\Zone;
use App\NsdName;
use App\Notice;
use App\SupplyCategory;
use App\ItemToTender;
use App\Tender;
use App\Supplier;
use Illuminate\Support\Facades\Validator;
use Input;
use DB;
use Auth;
use Session;
use App\SupplierMultiInfo;




class FrontSupplierController extends Controller
{
    public function index($zone=null,$nsd=null){

        $zone = $zone;
        $nsd  = $nsd;

        $category = Input::get('category');
        $key = Input::get('key');

        // Only for admin =========================================================================
        if(empty($zone) && empty($nsd)){
            // $zoneInfo = Zone::where('status','=',1)->orderBy('id')->first();
            // $navalLocation = NsdName::where('status_id','=',1)->orderBy('id')->first();

            $navalLocation = NsdName::where('status_id','=',1)->where('default_selected','=',1)->orderBy('id')->first();
            $zoneInfo = Zone::where('id','=',$navalLocation->zones)->where('status','=',1)->orderBy('id')->first();

            $allSuppliers = DB::table($zoneInfo->alise.'_suppliers')->where('status_id','=',1)->get();

            $catWiseSupp = array();
            if(!empty($category)){
                foreach($allSuppliers as $splc){
                    foreach(explode(',',$splc->supply_cat_id) as $rni){
                        if($rni == $category){
                            $catWiseSupp[] = $splc->id;
                        }
                    }
                }
            }

            $zonesRltdIdsss = array();
            foreach($allSuppliers as $spl){
                foreach(explode(',',$spl->registered_nsd_id) as $rni){
                    if($rni == $navalLocation->id){
                        $zonesRltdIdsss[] = $spl->id;
                    }
                }
            }


            $data['suppliers'] = DB::table($zoneInfo->alise.'_suppliers')
                ->where('status_id','=',1);
                $data['suppliers']->whereIn('id',$zonesRltdIdsss);
                if(!empty($category)){
                    $data['suppliers']->whereIn('id',$catWiseSupp);
                }
                if(!empty($key)){
                    $data['suppliers']->where(function($query) use ($key){
                        $query->where('company_name', 'like', "%{$key}%");
                        $query->orWhere('company_regi_number_nsd', 'like', "%{$key}%");
                        $query->orWhere('mobile_number', 'like', "%{$key}%");
                    });
                }

                $data['suppliers']->orderBy('id', 'desc');
                $forforeach = $data['suppliers']->get();

                $data['suppliers'] = $data['suppliers']->paginate(20);
 

//            $data['importantNotices'] = Notice::where('is_important','=',1)->where('status_id','=',1)->orderBy('id','desc')->get();
//            $data['categories'] = SupplyCategory::select('id','name')->where('status_id','=',1)->get();
            $catIds = array();
            foreach($forforeach as $splc){
                foreach(explode(',',$splc->supply_cat_id) as $rni){
                    $catIds[] = $rni;
                }
            }

            $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->take(5)->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }
            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->orderBy('id','desc')->get();

            $data['categories'] = SupplyCategory::select('id','name')->whereIn('id',$catIds)->where('status_id','=',1)->get();
        }

        // Only for naval location =========================================================================
        if(!empty($zone) && !empty($nsd)){
            $zoneInfo = Zone::where('alise','=',$zone)->first();
            $navalLocation = NsdName::where('alise','=',$nsd)->orderBy('id')->first();
            $allSuppliers = DB::table($zoneInfo->alise.'_suppliers')->where('status_id','=',1)->get();

            $zonesRltdIdsss = array();
            foreach($allSuppliers as $spl){
                foreach(explode(',',$spl->registered_nsd_id) as $rni){
                    if($rni == $navalLocation->id){
                        $zonesRltdIdsss[] = $spl->id;
                    }
                }
            }

            $catWiseSupp = array();
            if(!empty($category)){
                foreach($allSuppliers as $splc){
                    foreach(explode(',',$splc->supply_cat_id) as $rni){
                        if($rni == $category){
                            $catWiseSupp[] = $splc->id;
                        }
                    }
                }
            }


            $data['suppliers'] = DB::table($zoneInfo->alise.'_suppliers')
                ->where('status_id','=',1)
                ->whereIn('id',$zonesRltdIdsss);
                if(!empty($category)){
                    $data['suppliers']->whereIn('id',$catWiseSupp);
                }
                if(!empty($key)){
                    $data['suppliers']->where(function($query) use ($key){
                        $query->where('company_name', 'like', "%{$key}%");
                        $query->orWhere('company_regi_number_nsd', 'like', "%{$key}%");
                        $query->orWhere('mobile_number', 'like', "%{$key}%");
                    });
                }
                $data['suppliers']->orderBy('id', 'desc');
                $forforeach = $data['suppliers']->get();
                $data['suppliers'] = $data['suppliers']->paginate(20);

            $catIds = array();
            foreach($forforeach as $splc){
                foreach(explode(',',$splc->supply_cat_id) as $rni){
                    $catIds[] = $rni;
                }
            }

            $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->take(5)->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->orderBy('id','desc')->get();

            $data['categories'] = SupplyCategory::select('id','name')->whereIn('id',$catIds)->where('status_id','=',1)->get();

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
        
        return view('frontend.supplier-list',$data);

    }



    public  function supplier_submit(){

        $editId = Supplier::find(Auth::guard('supplier')->id());
        return view('frontend.supplier-from-submit',compact('editId'));

    }


    public function updateSupplier_info(Request $request)
    {

        $this->middleware('csrf', array('on' => 'post'));

        $v = \Validator::make($request->all(), [
            'company_name' => 'required',
            'mobile_number' => 'required',
        ]);


        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors())->withInput();
        }else {

            $supplier = Supplier::find(Auth::guard('supplier')->id());
            $supplier->company_name = $request->company_name;
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
            $supplier->iso_certification = $request->iso_certification;
            $supplier->enlistment_status ='waiting-for-approval';
            $supplier->status_id = 2;


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

                for($i=0; count($request->name)>$i; $i++){
                    $supplierMultiInfo              = new SupplierMultiInfo();
                    $supplierMultiInfo->supplier_id = $supplier->id;
                    $supplierMultiInfo->name = empty($request->name[$i]) ? null : $request->name[$i];
                    $supplierMultiInfo->designation = empty($request->designation[$i]) ? null : $request->designation[$i];
                    $supplierMultiInfo->mobile_number1 = empty($request->mobile_number1[$i]) ? null : $request->mobile_number1[$i];
                    $supplierMultiInfo->barcode_number = empty($request->barcode_number1[$i]) ? null : $request->barcode_number1[$i];
                    $supplierMultiInfo->save();
                }

                $for_all_org = explode(',',$supplier->registered_nsd_id);
                $org_name = NsdName::find($for_all_org[0]);

                $updateSup = Supplier::find($supplier->id);
                $updateSup->all_org_id = $supplier->id;
                $updateSup->save();

                Session::flash('success', 'Supplier Updated Successfully');
                return \redirect('0/0/enlistment-track');
            }else{
                Session::flash('error', 'Something want wrong please try again.');
                return \redirect()->back();
            }

        }
    }

}



