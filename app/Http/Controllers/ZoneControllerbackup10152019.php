<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EventManagement;
use Illuminate\Support\Facades\Auth;
use Session;
use View;
use Input;
use Illuminate\Support\Facades\Redirect;
use functions\OwnLibrary;
use App\Zone;
use Illuminate\Database\Schema\Blueprint;
use Schema;

class ZoneController extends Controller
{

    private $moduleId = 21;

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $zones = Zone::paginate(10);
       // echo "<pre>"; print_r($supplyCats); exit;
       return View::make('zone.index')->with(compact('zones'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('zone.create');
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
            'name' => 'required|unique:zones,name',
            'alise' => 'required|unique:zones,alise',
        ]);

        if ($v->fails()) {
            return redirect('zone/create')->withErrors($v->errors())->withInput();
        }else {
                   
                $zone = new Zone();

                $zone->name = $request->name;
                $zone->alise = $request->alise;
                $zone->info = empty($request->info) ? null : $request->info;
                $zone->icon = empty($request->icon) ? null : $request->icon;
                $zone->status = $request->status;
                
                //$exbToMngt->save();

               if ($zone->save()) {

                   // Items Table===============================================================
                   Schema::create(strtolower($zone->alise).'_items', function ($table) {
                       $table->increments('id');
                       $table->string('all_org_item_id')->nullable();
                       $table->string('imc_number')->nullable();
                       $table->string('item_name')->nullable();
                       $table->string('model_number')->nullable();
                       $table->integer('item_cat_id')->nullable();
                       $table->string('nsd_id')->nullable();
                       $table->string('unit_price')->nullable();
                       $table->string('unit_price_in_bdt')->nullable();
                       $table->string('currency_name')->nullable();
                       $table->string('conversion')->nullable();
                       $table->string('discounted_price')->nullable();
                       $table->string('discounted_price_in_bdt')->nullable();
                       $table->string('item_deno')->nullable();
                       $table->string('manufacturing_country')->nullable();
                       $table->string('source_of_supply')->nullable();
                       $table->text('other_info_about_itme')->nullable();
                       $table->string('budget_code')->nullable();
                       $table->string('item_type')->nullable();
                       $table->integer('status_id')->default(1);
                       $table->integer('created_by');
                       $table->integer('updated_by');
                       $table->timestamps();
                   });

                   // Suppliers Table ===============================================================
                   Schema::create(strtolower($zone->alise).'_suppliers', function ($table) {
                       $table->increments('id');
                       $table->string('all_org_id')->nullable();
                       $table->string('company_name');
                       $table->string('company_regi_number_nsd');
                       $table->string('barcode_number')->nullable();
                       $table->string('mobile_number')->nullable();
                       $table->string('email')->nullable();
                       $table->string('password')->nullable();
                       $table->string('supply_cat_id');
                       $table->string('vat_registration_number')->nullable();
                       $table->string('tin_number')->nullable();
                       $table->string('nid_number')->nullable();
                       $table->string('trade_license_number')->nullable();
                       $table->string('trade_license_address')->nullable();
                       $table->string('company_bank_account_name')->nullable();
                       $table->string('bank_account_number')->nullable();
                       $table->string('bank_name_and_branch')->nullable();
                       $table->string('bsti_certification')->nullable();
                       $table->string('iso_certification')->nullable();
                       $table->string('registered_nsd_id')->nullable();
                       $table->string('date_of_enrollment')->nullable();
                       $table->string('profile_pic')->nullable();
                       $table->string('fax')->nullable();
                       $table->text('head_office_address')->nullable();
                       $table->string('tin_certificate')->nullable();
                       $table->text('branch_office_address')->nullable();
                       $table->string('rltn_w_acc_holder')->nullable();
                       $table->text('intr_name')->nullable();
                       $table->text('intr_designation')->nullable();
                       $table->text('intr_address')->nullable();
                       $table->string('testimonial')->nullable();
                       $table->string('cur_reg_supplier_name')->nullable();
                       $table->text('cur_reg_supplier_address')->nullable();
                       $table->string('defaulter_before')->nullable();
                       $table->string('company_partnership_act')->nullable();
                       $table->string('registered_as')->nullable();
                       $table->text('des_of_sole_prtship')->nullable();
                       $table->string('partners_name')->nullable();
                       $table->string('partners_address')->nullable();
                       $table->string('auth_prsn_name')->nullable();
                       $table->string('auth_prsn_designation')->nullable();
                       $table->string('bangla_signature')->nullable();
                       $table->string('english_signature')->nullable();
                       $table->string('attested_photo')->nullable();
                       $table->string('attested_trade_lic')->nullable();
                       $table->string('attested_nid_photocopy')->nullable();
                       $table->string('attested_char_cert')->nullable();
                       $table->string('att_vat_reg_cert')->nullable();
                       $table->string('att_vat_return_last_cert')->nullable();
                       $table->string('att_edu_cert')->nullable();
                       $table->string('lst_six_mnth_bnk_sttmnt')->nullable();
                       $table->string('bnk_solvency_certi')->nullable();
                       $table->string('non_judicial_stamp')->nullable();
                       $table->integer('status_id')->default(1);
                       $table->integer('created_by');
                       $table->integer('updated_by');
                       $table->timestamps();
                   });

                   // Tenders Table ===============================================================
                   Schema::create(strtolower($zone->alise).'_tenders', function ($table) {
                       $table->increments('id');
                       $table->string('all_org_tender_id')->nullable();
                       $table->string('demand_no')->nullable();
                       $table->string('po_number')->nullable();
                       $table->string('tender_title')->nullable();
                       $table->string('tender_number')->nullable();
                       $table->string('tender_description')->nullable();
                       $table->dateTime('tender_opening_date')->nullable();
                       $table->string('supplier_id')->nullable();
                       $table->dateTime('work_order_date')->nullable();
                       $table->dateTime('date_line')->nullable();
                       $table->dateTime('delivery_date')->nullable();
                       $table->string('imc_number')->nullable();
                       $table->integer('tender_cat_id');
                       $table->integer('nsd_id');
                       $table->string('other_info_about_tender')->nullable();
                       $table->string('specification')->nullable();
                       $table->string('notice')->nullable();
                       $table->string('open_tender')->nullable();

                       $table->string('invitation_for')->nullable();
                       $table->dateTime('date')->nullable();
                       $table->string('development_partners')->nullable();
                       $table->string('proj_prog_code')->nullable();
                       $table->string('tender_package_no')->nullable();
                       $table->string('tender_package_name')->nullable();
                       $table->dateTime('pre_tender_meeting')->nullable();
                       $table->string('eligibility_of_tender')->nullable();
                       $table->string('name_of_offi_invit_ten')->nullable();
                       $table->string('desg_of_offi_invit_ten')->nullable();
                       $table->string('nhq_ltr_no')->nullable();
                       
                       // Start newly added fields
                       $table->string('approval_letter_number')->nullable();
                       $table->string('approval_letter_date')->nullable();
                       $table->string('purchase_type')->nullable();
                       $table->string('tender_type')->nullable();
                       $table->string('tender_nature')->nullable();
                       $table->string('ref_tender_id')->nullable();
                       $table->string('tender_priority')->nullable();
                       $table->mediumText('letter_body')->nullable();
                       $table->mediumText('remarks')->nullable();
                       $table->dateTime('time_extension_upto')->nullable();
                       $table->dateTime('valid_date_from')->nullable();
                       $table->dateTime('valid_date_to')->nullable();
                       $table->dateTime('extend_date_to')->nullable();
                       $table->mediumText('reference')->nullable();
                       $table->mediumText('tender_terms_conditions')->nullable();
                       // End newly added fields
                       $table->integer('status_id')->default(1);
                       $table->integer('created_by');
                       $table->integer('updated_by');
                       $table->timestamps();
                   });

                   // Tenders Table ===============================================================
                   Schema::create(strtolower($zone->alise).'_itemtotender', function ($table) {
                       $table->increments('id');
                       $table->string('all_org_itmtotender_id')->nullable();
                       $table->string('tender_id');
                       $table->string('item_id');
                       $table->double('unit_price',10,2)->default(0);
                       $table->string('unit_price_in_bdt')->nullable();
                       $table->string('currency_name')->nullable();
                       $table->string('conversion')->nullable();
                       $table->double('quantity',10,2)->default(0);
                       $table->double('discount_price',10,2)->default(0);
                       $table->string('discount_price_in_bdt')->nullable();
                       $table->double('total',10,2)->default(0);
                       $table->integer('status_id')->default(1);
                       $table->integer('created_by');
                       $table->integer('updated_by');
                       $table->timestamps();
                   });

//                    Supplier personal informationb Table ===============================================================
                    Schema::create(strtolower($zone->alise).'_suppliers_personal_info', function ($table) {
                        $table->increments('id');
                        $table->string('all_org_sup_bas_info_id');
                        $table->string('supplier_id');
                        $table->string('full_name')->nullable();
                        $table->string('father_name')->nullable();
                        $table->string('father_nid')->nullable();
                        $table->string('mother_name')->nullable();
                        $table->string('mother_nid')->nullable();
                        $table->text('permanent_address')->nullable();
                        $table->text('present_address')->nullable();
                        $table->string('birth_place')->nullable();
                        $table->string('birth_date')->nullable();
                        $table->string('height')->nullable();
                        $table->string('weight')->nullable();
                        $table->string('color')->nullable();
                        $table->string('eye_color')->nullable();
                        $table->string('identification_mark')->nullable();
                        $table->string('religion')->nullable();
                        $table->string('nationality')->nullable();
                        $table->string('organization')->nullable();
                        $table->string('rank_in_organization')->nullable();
                        $table->string('business_start_date')->nullable();
                        $table->string('organization_name')->nullable();
                        $table->string('organization_branch')->nullable();
                        $table->string('membership_number')->nullable();
                        $table->string('membrsip_cont_date')->nullable();
                        $table->string('date_of_registry')->nullable();
                        $table->string('jn_date_of_prsnt_ocuptn')->nullable();
                        $table->text('des_of_pre_occu')->nullable();
                        $table->string('offence')->nullable();
                        $table->string('offence_date')->nullable();
                        $table->string('offence_place')->nullable();
                        $table->text('des_of_offence')->nullable();
                        $table->string('spouse_full_name')->nullable();
                        $table->string('spouse_nid')->nullable();
                        $table->string('spouse_father_name')->nullable();
                        $table->string('spouse_father_nid')->nullable();
                        $table->string('spouse_mother_name')->nullable();
                        $table->string('spouse_mother_nid')->nullable();
                        $table->text('spouse_per_address')->nullable();
                        $table->text('spouse_pre_address')->nullable();
                        $table->string('spouse_birth_place')->nullable();
                        $table->string('spouse_birth_date')->nullable();
                        $table->string('spouse_nationality')->nullable();
                        $table->string('spouse_occupation')->nullable();
                        $table->string('applicant_signature')->nullable();
                        $table->string('applicant_seal')->nullable();
                        $table->string('signature_place')->nullable();
                        $table->string('signature_date')->nullable();
                        $table->integer('status_id')->default(1);
                        $table->integer('created_by');
                        $table->integer('updated_by');
                        $table->timestamps();
                    });


                   Session::flash('success', 'Zone Created Successfully');
                    return Redirect::to('zone/view');
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
       $editId = Zone::find($id);

        return View::make('zone.edit')->with(compact('editId'));

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
            'name' => 'required|unique:zones,name,'. $id,
            'alise' => 'required|unique:zones,alise,'. $id,
        ]);

        if ($v->fails()) {
            return redirect('zone/edit/'.$id)->withErrors($v->errors())->withInput();
        }else {
                $zone = Zone::find($id);
                $oldZoneName = strtolower($zone->name);

                $zone->name = $request->name;
                $zone->alise = $request->alise;
                $zone->info = empty($request->info) ? null : $request->info;
                $zone->icon = empty($request->icon) ? null : $request->icon;
                $zone->status = $request->status;

                //$exbToMngt->save();

               if ($zone->save()) {

//                   Schema::rename($oldZoneName.'_items', strtolower($request->name).'_items');
//                   Schema::rename($oldZoneName.'_suppliers', strtolower($request->name).'_suppliers');
//                   Schema::rename($oldZoneName.'_tenders', strtolower($request->name).'_tenders');
//                   Schema::rename($oldZoneName.'_itemToTender', strtolower($request->name).'_itemToTender');
//
                   Session::flash('success', 'Zone Updated Successfully');
                   return Redirect::to('zone/view');
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
        $zone = Zone::find($id);

        if ($zone->delete()) {
                Session::flash('success', 'Zone Deleted Successfully');
                return Redirect::to('zone/view');
            } else {
                Session::flash('error', 'Zone Not Found');
                return Redirect::to('zone/view');
            }
    }


}
