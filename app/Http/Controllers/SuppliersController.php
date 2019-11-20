<?php

namespace App\Http\Controllers;

use App\Item;
use App\ItemToTender;
use App\SupplierMultiInfo;
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

class SuppliersController extends Controller
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
    public function index()
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
                    $query->whereDate('date_of_enrollment','>=',$from);
                });
            }
            if(!empty($to)){
                $suppliers->where(function($query) use ($to){
                    $query->whereDate('date_of_enrollment','<=',$to);
                });
            }
        
       $suppliers = $suppliers->paginate(10);

       return View::make('suppliers.index')->with(compact('suppliers','nsdNames','nsd_id','company_mobile','from','to'));

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

        return View::make('suppliers.create')->with(compact('supplyCategories','nsdNames'));
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
            'vat_registration_number' => 'required',
            'nid_number' => 'required',
            'bank_account_number' => 'required',
            'date_of_enrollment' => 'required',
            'company_regi_number_nsd' => 'required',
            'supply_cat_id' => 'required|array',
            'tin_number' => 'required',
            'trade_license_number' => 'required',
            'company_bank_account_name' => 'required',
            'bank_name_and_branch' => 'required',
            // 'bsti_certification' => 'required',
            // 'iso_certification' => 'required',
            'registered_nsd_id' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('suppliers/suppliers/create')->withErrors($v->errors())->withInput();
        }else {

                //User photo upload
                $image_upload = TRUE;
                $image_name = FALSE;
                if (Input::hasFile('profile_pic')) {
                    $file = Input::file('profile_pic');
                    $destinationPath = public_path() . '/uploads/supplier_profile/';
                    $filename = uniqid() . $file->getClientOriginalName();
                    $uploadSuccess = Input::file('profile_pic')->move($destinationPath, $filename);
                    if ($uploadSuccess) {
                        $image_name = TRUE;
                    } else {
                        $image_upload = FALSE;
                    }
                    //Create More Small Thumbnails :::::::::::: Resize Image
                    $this->imageResizeCtrl->load(public_path() . '/uploads/supplier_profile/' . $filename);
                    $this->imageResizeCtrl->resize(1000, 800);
                    //delete original image
                    unlink(public_path() . '/uploads/supplier_profile/' . $filename);

                    $this->imageResizeCtrl->save(public_path() . '/uploads/supplier_profile/' . $filename);
                }
                   
                $supplier = new Supplier();

                $supplier->company_name = $request->company_name;
                $supplier->mobile_number = empty($request->mobile_number) ? null : $request->mobile_number;
                $supplier->vat_registration_number = empty($request->vat_registration_number) ? null : $request->vat_registration_number;
                $supplier->nid_number = empty($request->nid_number) ? null : $request->nid_number;
                $supplier->bank_account_number = empty($request->bank_account_number) ? null : $request->bank_account_number;
                $supplier->date_of_enrollment = empty($request->date_of_enrollment) ? null : date('Y-m-d',strtotime($request->date_of_enrollment));
                $supplier->company_regi_number_nsd = empty($request->company_regi_number_nsd) ? null : $request->company_regi_number_nsd;
                $supplier->barcode_number = empty($request->barcode_number) ? null : $request->barcode_number;
                $supplier->supply_cat_id = empty($request->supply_cat_id) ? null : implode(',',$request->supply_cat_id);
                $supplier->tin_number = empty($request->tin_number) ? null : $request->tin_number;
                $supplier->trade_license_number = empty($request->trade_license_number) ? null : $request->trade_license_number;
                $supplier->company_bank_account_name = empty($request->company_bank_account_name) ? null : $request->company_bank_account_name;
                $supplier->bank_name_and_branch = empty($request->bank_name_and_branch) ? null : $request->bank_name_and_branch;
                $supplier->bsti_certification = empty($request->bsti_certification) ? null : $request->bsti_certification;
                $supplier->iso_certification = empty($request->iso_certification) ? null : $request->iso_certification;
                $supplier->registered_nsd_id = empty($request->registered_nsd_id) ? null : implode(',',$request->registered_nsd_id);
                $supplier->trade_license_address = empty($request->trade_license_address) ? null : $request->trade_license_address;
                $supplier->email = empty($request->email) ? null : $request->email;
                $supplier->password = empty($request->password) ? null : Hash::make($request->password);
                $supplier->status_id = $request->status;

                if ($image_name !== FALSE) {
                    $supplier->profile_pic = $filename;
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

                   Session::flash('success', 'Supplier Created Successfully');
                    return Redirect::to('suppliers/suppliers');
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

        return View::make('suppliers.edit')->with(compact('editId','supplyCategories','nsdNames'));

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
            'vat_registration_number' => 'required',
            'nid_number' => 'required',
            'bank_account_number' => 'required',
            'date_of_enrollment' => 'required',
            'company_regi_number_nsd' => 'required',
            'supply_cat_id' => 'required|array',
            'tin_number' => 'required',
            'trade_license_number' => 'required',
            'company_bank_account_name' => 'required',
            'bank_name_and_branch' => 'required',
            // 'bsti_certification' => 'required',
            // 'iso_certification' => 'required',
            'registered_nsd_id' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('suppliers/suppliers/'.$id.'/'.'edit')->withErrors($v->errors())->withInput();
        }else {

                //User photo upload
                $image_upload = TRUE;
                $image_name = FALSE;
                if (Input::hasFile('profile_pic')) {
                    $file = Input::file('profile_pic');
                    $destinationPath = public_path() . '/uploads/supplier_profile/';
                    $filename = uniqid() . $file->getClientOriginalName();
                    $uploadSuccess = Input::file('profile_pic')->move($destinationPath, $filename);
                    if ($uploadSuccess) {
                        $image_name = TRUE;
                    } else {
                        $image_upload = FALSE;
                    }
                    //Create More Small Thumbnails :::::::::::: Resize Image
                    $this->imageResizeCtrl->load(public_path() . '/uploads/supplier_profile/' . $filename);
                    $this->imageResizeCtrl->resize(1000, 800);
                    //delete original image
                    unlink(public_path() . '/uploads/supplier_profile/' . $filename);

                    $this->imageResizeCtrl->save(public_path() . '/uploads/supplier_profile/' . $filename);
                }
                   
                $supplier = Supplier::find($id);

                $supplier->company_name = $request->company_name;
                $supplier->mobile_number = empty($request->mobile_number) ? null : $request->mobile_number;
                $supplier->vat_registration_number = empty($request->vat_registration_number) ? null : $request->vat_registration_number;
                $supplier->nid_number = empty($request->nid_number) ? null : $request->nid_number;
                $supplier->bank_account_number = empty($request->bank_account_number) ? null : $request->bank_account_number;
                $supplier->date_of_enrollment = empty($request->date_of_enrollment) ? null : date('Y-m-d',strtotime($request->date_of_enrollment));
                $supplier->company_regi_number_nsd = empty($request->company_regi_number_nsd) ? null : $request->company_regi_number_nsd;
                $supplier->barcode_number = empty($request->barcode_number) ? null : $request->barcode_number;
                $supplier->supply_cat_id = empty($request->supply_cat_id) ? null : implode(',',$request->supply_cat_id);
                $supplier->tin_number = empty($request->tin_number) ? null : $request->tin_number;
                $supplier->trade_license_number = empty($request->trade_license_number) ? null : $request->trade_license_number;
                $supplier->company_bank_account_name = empty($request->company_bank_account_name) ? null : $request->company_bank_account_name;
                $supplier->bank_name_and_branch = empty($request->bank_name_and_branch) ? null : $request->bank_name_and_branch;
                $supplier->bsti_certification = empty($request->bsti_certification) ? null : $request->bsti_certification;
                $supplier->iso_certification = empty($request->iso_certification) ? null : $request->iso_certification;
                $supplier->registered_nsd_id = empty($request->registered_nsd_id) ? null : implode(',',$request->registered_nsd_id);
                $supplier->trade_license_address = empty($request->trade_license_address) ? null : $request->trade_license_address;
                $supplier->email = empty($request->email) ? null : $request->email;
                $supplier->password = empty($request->password) ? null : Hash::make($request->password);
                $supplier->status_id = $request->status;

                if ($image_name !== FALSE) {
                    if(!empty($supplier->profile_pic)){
                        unlink(public_path() . '/uploads/supplier_profile/' . $supplier->profile_pic);
                    }
                    $supplier->profile_pic = $filename;
                }

               if ($supplier->save()) {

                    for($i=0; count($request->name)>$i; $i++){
                       if (isset($request->supplier_info_id1[$i]) && !empty($request->supplier_info_id1[$i])){
                           $supplierMultiInfo = SupplierMultiInfo::find($request->supplier_info_id1[$i]);
                       }else{
                           $supplierMultiInfo              = new SupplierMultiInfo();
                       }
                       $supplierMultiInfo->supplier_id = $supplier->id;
                       $supplierMultiInfo->name = empty($request->name[$i]) ? null : $request->name[$i];
                       $supplierMultiInfo->designation = empty($request->designation[$i]) ? null : $request->designation[$i];
                       $supplierMultiInfo->mobile_number1 = empty($request->mobile_number1[$i]) ? null : $request->mobile_number1[$i];
                       $supplierMultiInfo->barcode_number = empty($request->barcode_number1[$i]) ? null : $request->barcode_number1[$i];
                       $supplierMultiInfo->save();
                   }

                   Session::flash('success', 'Supplier Updated Successfully');
                    return Redirect::to('suppliers/suppliers');
                }

            //} 
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
        OwnLibrary::validateAccess($this->moduleId,4);
        $supplier = Supplier::find($id);
        
        if ($supplier->delete()) {
                Session::flash('success', 'Supplier Deleted Successfully');
                return Redirect::to('suppliers/suppliers');
            } else {
                Session::flash('error', 'Supplier Not Found');
                return Redirect::to('suppliers/suppliers');
            }
    }

    public function individulaView($id){
        $this->tableAlies = \Session::get('zoneAlise');
        $suppliers = Supplier::find($id);

        $supplierPersonalInfo = \App\SupplierBasicInfo::where('supplier_id','=',$suppliers->id)->first();

        $tenderssss = array_map('current',Tender::select('id')
                        ->where('supplier_id','=',$suppliers->id)
                        ->where('status_id','=',1)
                        ->get()->toArray());

        $winning_tenders = Tender::where('supplier_id','=',$suppliers->id)
            ->where('status_id','=',1)
            ->paginate(10);
        //$items = ItemToTender::whereIn('tender_id',$tenders)->get();

        $supplier_to_items = \App\ItemToTender::join($this->tableAlies.'_tenders', $this->tableAlies.'_tenders.id', '=', $this->tableAlies.'_itemtotender.tender_id')
            ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=',$this->tableAlies.'_tenders.supplier_id')
            ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=',$this->tableAlies.'_itemtotender.item_id')
            ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
            ->select($this->tableAlies.'_suppliers.company_name', $this->tableAlies.'_tenders.po_number',$this->tableAlies.'_tenders.tender_title',$this->tableAlies.'_tenders.work_order_date as purchase_date', $this->tableAlies.'_tenders.delivery_date', $this->tableAlies.'_items.imc_number',$this->tableAlies.'_items.item_name','deno.name as itme_deno',$this->tableAlies.'_itemtotender.quantity',$this->tableAlies.'_itemtotender.unit_price',$this->tableAlies.'_itemtotender.discount_price',$this->tableAlies.'_itemtotender.total','deno.name as deno_name')
            ->whereIn($this->tableAlies.'_tenders.id',$tenderssss)
            ->orderBy($this->tableAlies.'_suppliers.id','ASC')
            ->paginate(10);

//        echo "<pre>"; print_r($supplier_to_items); exit;

        return View::make('suppliers.individula-view')->with(compact('suppliers','supplier_to_items','winning_tenders','supplierPersonalInfo'));
    }

    public function approve($id)
    {
        $supplier = Supplier::find($id);

        $supplier->status_id = 1;
        if ($supplier->save()) {
            Session::flash('success', 'Supplier Approved Successfully');
            return Redirect::to('suppliers/suppliers');
        } else {
            Session::flash('error', 'Supplier Not Found');
            return Redirect::to('suppliers/suppliers');
        }
    }

    public function rejecte($id)
    {
        $supplier = Supplier::find($id);

        $supplier->status_id = 4;
        if ($supplier->save()) {
            Session::flash('success', 'Supplier Rejected Successfully');
            return Redirect::to('suppliers/suppliers');
        } else {
            Session::flash('error', 'Supplier Not Found');
            return Redirect::to('suppliers/suppliers');
        }
    }

    public function supplierMultiInfoDelete($id){
        if(SupplierMultiInfo::destroy($id)){
            Session::flash('success', 'Data Delated Successfully');
        }else{
            Session::flash('error', 'Data can not be delated.');
        }
        return redirect()->back();
    }


}
