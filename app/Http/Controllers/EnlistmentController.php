<?php

namespace App\Http\Controllers;


use App\SupplierMultiInfo;
use Illuminate\Http\Request;
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

class EnlistmentController extends Controller
{
    private $moduleId = 46;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status)
    {

        $nsd_id = Input::get('nsd_id');
        $company_mobile = Input::get('company_mobile');
        $from   = Input::get('from');
        $to     = Input::get('to');

        if(!empty($from)){
            $from   = date('Y-m-d',strtotime(Input::get('from')));
        }
        if(!empty($to)){
            $to     = date('Y-m-d',strtotime(Input::get('to')));
        }

        $zonesRltdIds = array();
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

        $AllSuppliers = Supplier::whereNotNull('status_id')->get();

        $zonesRltdIdsss = array();
        foreach($AllSuppliers as $spl){
            foreach(explode(',',$spl->registered_nsd_id) as $rni){
                if(!empty($nsd_id)){
                    if(in_array($nsd_id, explode(',',$spl->registered_nsd_id))){

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

                    }
                }else{
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

                    }
                }

            }
        }

        $suppliers = Supplier::whereIn('id',$zonesRltdIdsss)->whereNotNull('status_id');

        if(!empty($nsd_id)){
            $suppliers->whereIn('id',$zonesRltdIdsss);
        }
        if(!empty($company_mobile)){
            $suppliers->where(function($query) use ($company_mobile){
                $query->where('company_name', 'like', "%{$company_mobile}%");
                $query->orWhere('mobile_number', 'like', "%{$company_mobile}%");
                $query->orWhere('company_regi_number_nsd', 'like', "%{$company_mobile}%");
            });
        }
        if(!empty($from)){
            $suppliers->where(function($query) use ($from ){
                $query->whereDate('created_at','>=',$from);
            });
        }
        if(!empty($to)){
            $suppliers->where(function($query) use ($to){
                $query->whereDate('created_at','<=',$to);
            });
        }


        $suppliers = $suppliers->where('enlistment_status',$status)->orderBy('id','DESC')->paginate(10);

        return View::make('suppliers.enlistment.index')->with(compact('suppliers','nsdNames','nsd_id','company_mobile','from','to','status'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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

        return View::make('suppliers.enlistment.create')->with(compact('supplyCategories','nsdNames'));
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

        $this->middleware('csrf', array('on' => 'post'));

        $v = \Validator::make($request->all(), [
            'company_name' => 'required|unique:'.\Session::get("zoneAlise").'_suppliers,company_name',
            'mobile_number' => 'required',
            'email' => 'required',
            'password' => 'required',
            'registered_nsd_id' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('suppliers/enlistment/create')->withErrors($v->errors())->withInput();
        }else {


            $file_name = FALSE;
            if (Input::hasFile('application_file')) {
                $file = Input::file('application_file');
                $destinationPath = public_path() . '/uploads/supplier_application_file/';
                $filename = uniqid().time().'.'.$file->getClientOriginalExtension();
                $uploadSuccess = Input::file('application_file')->move($destinationPath, $filename);
                if ($uploadSuccess) {
                    $file_name = TRUE;
                } else {
                    $file_name = FALSE;
                }



            }

            $supplier = new Supplier();

            $supplier->company_name = $request->company_name;
            $supplier->mobile_number = empty($request->mobile_number) ? null : $request->mobile_number;
            $supplier->registered_nsd_id = empty($request->registered_nsd_id) ? null : implode(',',$request->registered_nsd_id);
            $supplier->email = empty($request->email) ? null : $request->email;
            $supplier->password =Hash::make($request->password);
            $supplier->demo_password = $request->password;
            $supplier->enlistment_status  = 'pending';
            $supplier->status_id = $request->status;

            if ($file_name !== FALSE) {
                $supplier->attested_application = $filename;
            }

            $supplier->save();
            Session::flash('success', 'Supplier Created Successfully');
            return Redirect::to('suppliers/enlistment/index/pending');


        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {



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
        $supplyCategories->where('status_id','=',1);
        $supplyCategories = $supplyCategories->get();

        $enlistment  = Supplier::find($id);

        return View::make('suppliers.enlistment.edit')->with(compact('enlistment','supplyCategories','nsdNames'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        OwnLibrary::validateAccess($this->moduleId,3);

        $this->middleware('csrf', array('on' => 'post'));

        $v = \Validator::make($request->all(), [
            'company_name' => 'required|unique:'.\Session::get("zoneAlise").'_suppliers,company_name,'.$id,
            'mobile_number' => 'required',
            'email' => 'required',
            'registered_nsd_id' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('suppliers/enlistment/'.$id.'/'.'edit')->withErrors($v->errors())->withInput();
        }else {

            $file_name = FALSE;
            if (Input::hasFile('application_file')) {
                $file = Input::file('application_file');
                $destinationPath = public_path() . '/uploads/supplier_application_file/';
                $filename = uniqid().time().'.'.$file->getClientOriginalExtension();
                $uploadSuccess = Input::file('application_file')->move($destinationPath, $filename);
                if ($uploadSuccess) {
                    $file_name = TRUE;
                } else {
                    $file_name = FALSE;
                }



            }

            $supplier = Supplier::find($id);
            $supplier->company_name = $request->company_name;
            $supplier->mobile_number = empty($request->mobile_number) ? null : $request->mobile_number;
            $supplier->company_regi_number_nsd = empty($request->company_regi_number_nsd) ? null : $request->company_regi_number_nsd;
            $supplier->email = empty($request->email) ? null : $request->email;
            $supplier->password =Hash::make($request->password);
            $supplier->demo_password = $request->password;
            $supplier->status_id = $request->status;

            if ($file_name !== FALSE) {
                $supplier->attested_application = $filename;
            }

            $supplier->save();

                Session::flash('success', 'Supplier Updated Successfully');
                return Redirect::to('suppliers/enlistment/index/pending');
            }

            //}


    }






    public function supplier_info($id)
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

        $editId = Supplier::find($id);

        return View::make('suppliers.enlistment.supplier-info')->with(compact('editId','supplyCategories','nsdNames'));

    }
    public function supplier_info_approval($id)
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

        $editId = Supplier::find($id);

        return View::make('suppliers.enlistment.supplier-info')->with(compact('editId','supplyCategories','nsdNames'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSupplier_info(Request $request, $id)
    {

        $this->middleware('csrf', array('on' => 'post'));

        $v = \Validator::make($request->all(), [
            'company_name' => 'required',
            'mobile_number' => 'required',
        ]);


        if ($v->fails()) {
            return redirect('suppliers/enlistment/'.$id.'/'.'supplier-info')->withErrors($v->errors())->withInput();
        }else {


            $supplier = Supplier::find($id);
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
                return \redirect('suppliers/enlistment/index/waiting-for-supplier-submit');
            }else{
                Session::flash('error', 'Something want wrong please try again.');
                return \redirect('suppliers/enlistment/index/waiting-for-supplier-submit');

            }


            //}
        }

    }

    public function updateSupplier_info_approval(Request $request, $id)
    {

        $this->middleware('csrf', array('on' => 'post'));

        $v = \Validator::make($request->all(), [
            'company_name' => 'required',
            'mobile_number' => 'required',

        ]);

        if ($v->fails()) {
            return redirect('suppliers/enlistment/'.$id.'/'.'supplier-info-approval')->withErrors($v->errors())->withInput();
        }else {


            $supplier = Supplier::find($id);

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
                return \redirect('suppliers/enlistment/index/waiting-for-approval');
            }else{

                Session::flash('error', 'Something want wrong please try again.');
                return \redirect('suppliers/enlistment/index/waiting-for-approval');

            }



        }



            //}


    }


    public function approve($id){
       try{
           $supplier = Supplier::find($id);
           $supplier->enlistment_status ='approved';
           $supplier->waiting_for_approve ='pending';
           $supplier->save();
           Session::flash('success', 'Supplier Updated Successfully');
           return \redirect('suppliers/enlistment/index/approved');
       }catch (\Exception $e){
           Session::flash('error', $e);
           return \redirect('suppliers/enlistment/index/approved');
       }
    }

    public function rejected($id){
       try{
           $supplier = Supplier::find($id);
           $supplier->enlistment_status ='rejected';
           $supplier->save();
           Session::flash('success', 'Supplier Reject Successfully');
           return \redirect('suppliers/enlistment/index/rejected');
       }catch (\Exception $e){
           Session::flash('error', $e);
           return \redirect('suppliers/enlistment/index/rejected');
       }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $supplier = Supplier::find($id);

        if ($supplier->delete()) {
            Session::flash('success', 'Supplier Deleted Successfully');
            return Redirect::to('suppliers/enlistment/index/pending');
        } else {
            Session::flash('error', 'Supplier Not Found');
            return Redirect::to('suppliers/enlistment/index/pending');
        }
    }
}
