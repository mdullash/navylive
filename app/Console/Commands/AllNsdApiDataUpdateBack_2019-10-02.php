<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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

class AllNsdApiDataUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nsd-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nsd all data populate';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $branch = 1;
        $abc = 1;

        while ($branch >= $abc){

            $zn = 1;
            $org = $abc;
            $tble = 1; // Table =1 // Supp; Table =2// Item; Table =3// Tender; Table =4// itemtotender;

            $url = '';
            if($abc==1){
                $url = "http://202.74.244.138/api/update-data/";
            }
            if($abc==2){
                $url = "http://navy.isslsolutions.com/api/update-data/";
            }
            if($abc==3){
                $url = "http://navy.isslsolutions.com/api/update-data/";
            }
            if($abc==4){
                $url = "http://navy.isslsolutions.com/api/update-data/";
            }

            $live_url = $url . $zn . "/" . $org . "/";

            $uri_segments = explode('/', $live_url);

            $apiZone = $uri_segments[5];
            $apiOrganization = $uri_segments[6];

            $zoneInfo = Zone::where('id', '=', $apiZone)->first();
            $navalLocation = NsdName::where('id', '=', $apiOrganization)->orderBy('id')->first();

            $updatedIfno = ApiDataUpdateLog::where('zone_id', '=', $zoneInfo->id)
                ->where('orgtion_id', '=', $navalLocation->id)
                ->where('retrieving_type', '=', 'suppliers')
                ->orderBy('id', 'DESC')
                ->first();
            if (empty($updatedIfno)) {
                $lastUpldatedDateTime = '2015-01-21 01:01:00';
            } else {
                $lastUpldatedDateTime = $updatedIfno->created_at;
            }

            $passData = $tble . '&' . $lastUpldatedDateTime;
            //$passData['table'] = $tble;

    // Supplier and Supplier basic information fetching start here ========================================================
    // Curl request ========================================
            // $live_url = $url . $zn . "/" . $org . "/" . $passData; // For nas dhaka supplier data
            // $ch = curl_init();
            // curl_setopt_array($ch, array(
            //     CURLOPT_URL => $live_url,
            //     CURLOPT_RETURNTRANSFER => true,
            // ));

            // $parse_url = curl_exec($ch);
            // $datas = json_decode($parse_url);
            // curl_close($ch);
    // End Curl request ========================================

            $live_url = $url . $zn . "/" . $org . "/" . urlencode($passData); // For nas dhaka supplier data
            $parse_url = file_get_contents($live_url);
            $datas = json_decode($parse_url); 

            if(!empty($datas)){

                $countInThousands = ceil(count($datas->suppliers) / 1000);
                $start_range = 0;
                $end_range = 1000;

                $x = 1;

                if (!empty($datas->suppliers) && count($datas->suppliers)>0) {

                    $insertInLogTable = new ApiDataUpdateLog();

                    $insertInLogTable->zone_id = $zoneInfo->id;
                    $insertInLogTable->orgtion_id = $navalLocation->id;
                    $insertInLogTable->zone_alise = $zoneInfo->alise;
                    $insertInLogTable->orgtion_alise = $navalLocation->alise;
                    $insertInLogTable->retrieving_type = 'suppliers';
                    $insertInLogTable->save();

                    while ($countInThousands >= $x) {
                        $for_loop = array_slice($datas->suppliers, $start_range, $end_range);
                        // Foreach loop for insert==================================================

                        \Session::put('zoneAlise', strtolower($datas->zone));
                        foreach ($for_loop as $ss) {

                            $ifexist = Supplier::where('all_org_id', '=', $datas->organization . '_' . $ss->id)->first();

                            if (empty($ifexist)) {
                                $supplier = new Supplier();
                            } else {
                                $supplier = Supplier::find($ifexist->id);
                            }
                            //$supplier = new Supplier();

                            $supplier->all_org_id = $datas->organization . '_' . $ss->id;
                            $supplier->company_name = $ss->company_name;
                            $supplier->company_regi_number_nsd = $ss->company_regi_number_nsd;
                            $supplier->barcode_number = $ss->barcode_number;
                            $supplier->mobile_number = $ss->mobile_number;
                            $supplier->fax = $ss->fax;
                            $supplier->email = $ss->email;
                            $supplier->head_office_address = $ss->head_office_address;
                            $supplier->tin_number = $ss->tin_number;
                            $supplier->bank_account_number = $ss->bank_account_number;
                            $supplier->date_of_enrollment  = $ss->date_of_enrollment;
                            $supplier->bank_name_and_branch = $ss->bank_name_and_branch;
                            $supplier->rltn_w_acc_holder = $ss->rltn_w_acc_holder;
                            $supplier->branch_office_address = $ss->branch_office_address;
                            $supplier->intr_name = $ss->intr_name;
                            $supplier->intr_designation = $ss->intr_designation;
                            $supplier->intr_address = $ss->intr_address;
                            $supplier->cur_reg_supplier_name = $ss->cur_reg_supplier_name;
                            $supplier->cur_reg_supplier_address = $ss->cur_reg_supplier_address;
                            $supplier->defaulter_before = $ss->defaulter_before;
                            $supplier->company_partnership_act = $ss->company_partnership_act;
                            $supplier->registered_as = $ss->registered_as;
                            $supplier->des_of_sole_prtship = $ss->des_of_sole_prtship;
                            $supplier->partners_name = $ss->partners_name;
                            $supplier->partners_address = $ss->partners_address;
                            $supplier->auth_prsn_name = $ss->auth_prsn_name;
                            $supplier->auth_prsn_designation = $ss->auth_prsn_designation;
                            $supplier->vat_registration_number = $ss->vat_registration_number;
                            $supplier->bsti_certification = $ss->bsti_certification;
                            $supplier->nid_number = $ss->nid_number;
                            $supplier->trade_license_number = $ss->trade_license_number;
                            $supplier->trade_license_address = $ss->trade_license_address;
                            $supplier->registered_nsd_id = $ss->registered_nsd_id;
                            $supplier->iso_certification = $ss->iso_certification;
                            $supplier->supply_cat_id = $ss->supply_cat_id;
                            $supplier->status_id = $ss->status_id;
                            $supplier->profile_pic = $ss->profile_pic;
                            $supplier->tin_certificate = $ss->tin_certificate;
                            $supplier->testimonial = $ss->testimonial;
                            $supplier->bangla_signature = $ss->bangla_signature;
                            $supplier->english_signature = $ss->english_signature;
                            $supplier->attested_photo = $ss->attested_photo;
                            $supplier->attested_trade_lic = $ss->attested_trade_lic;
                            $supplier->attested_nid_photocopy = $ss->attested_nid_photocopy;
                            $supplier->attested_char_cert = $ss->attested_char_cert;
                            $supplier->att_vat_reg_cert = $ss->att_vat_reg_cert;
                            $supplier->att_vat_return_last_cert = $ss->att_vat_return_last_cert;
                            $supplier->att_edu_cert = $ss->att_edu_cert;
                            $supplier->lst_six_mnth_bnk_sttmnt = $ss->lst_six_mnth_bnk_sttmnt;
                            $supplier->bnk_solvency_certi = $ss->bnk_solvency_certi;
                            $supplier->non_judicial_stamp = $ss->non_judicial_stamp;
                            $supplier->non_judicial_stamp = $ss->non_judicial_stamp;
                            $supplier->created_by = $ss->created_by;
                            $supplier->updated_by = $ss->updated_by;
                            $supplier->updated_at = $insertInLogTable->updated_at;

                            $supplier->save();

                        } // End foreach loop end ============================================

                        $start_range = 1000 * $x + 1;
                        $end_range += 1000;
                        $x++;
                        sleep(60);
                    } // end while loop =======================================================

                } // End of supplier if

                \Log::channel('allNsd')->info("Supplier informations.  " . count($datas->suppliers) . " data updated.\r\n");

                $countInThousandsSecond = ceil(count($datas->suppliers_personal_infos) / 1000);
                $start_rangeSec = 0;
                $end_rangeSec = 1000;

                $xyz = 1;
                if (!empty($datas->suppliers_personal_infos) && count($datas->suppliers_personal_infos)>0) {

                    $insertInLogTable = new ApiDataUpdateLog();

                    $insertInLogTable->zone_id = $zoneInfo->id;
                    $insertInLogTable->orgtion_id = $navalLocation->id;
                    $insertInLogTable->zone_alise = $zoneInfo->alise;
                    $insertInLogTable->orgtion_alise = $navalLocation->alise;
                    $insertInLogTable->retrieving_type = 'suppliers';
                    $insertInLogTable->save();

                    while ($countInThousandsSecond >= $x) {
                        $for_loop = array_slice($datas->suppliers_personal_infos, $start_rangeSec, $end_rangeSec);
                        // Foreach loop for insert==================================================

                        \Session::put('zoneAlise', strtolower($datas->zone));
                        foreach ($for_loop as $ss) {

                            $ifexist = SupplierBasicInfo::where('all_org_sup_bas_info_id', '=', $datas->organization . '_' . $ss->id)->first();

                            if (empty($ifexist)) {
                                $supplier_bsc_info = new SupplierBasicInfo();
                            } else {
                                $supplier_bsc_info = SupplierBasicInfo::find($ifexist->id);
                            }
                            //$supplier_bsc_info = new SupplierBasicInfo();

                            $supplier_bsc_info->all_org_sup_bas_info_id = $datas->organization . '_' . $ss->id;
                            // $supplier_bsc_info->supplier_id = $datas->organization . '_' . $ss->supplier_id;
                            if(!empty($ss->supplier_id) && count(explode('_',$ss->supplier_id))<2){
                                $supplier_bsc_info->supplier_id = $datas->organization . '_' . $ss->supplier_id;
                            }
                            if(!empty($ss->supplier_id) && count(explode('_',$ss->supplier_id))>1){
                                $supplier_bsc_info->supplier_id = $ss->supplier_id;
                            }
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

                        } // End foreach loop end =============================================

                        $start_rangeSec = 1000 * $x + 1;
                        $end_rangeSec += 1000;
                        $xyz++;
                        sleep(60);
                    } // end while loop =======================================================

                } // End of supplier basic information if

                \Log::channel('allNsd')->info("Supplier basic informations.  " . count($datas->suppliers_personal_infos) . " data updated.\r\n");

            } // End of datas empty if ========================================================


    // End of supplier and Supplier basic information fetching ========================================================

    // Item information fetching start here =============================================================================
            $tble = 2;

            $updatedIfno = ApiDataUpdateLog::where('zone_id', '=', $zoneInfo->id)
                ->where('orgtion_id', '=', $navalLocation->id)
                ->where('retrieving_type', '=', 'items')
                ->orderBy('id', 'DESC')
                ->first();
            if (empty($updatedIfno)) {
                $lastUpldatedDateTime = '2015-01-21 01:01:00';
            } else {
                $lastUpldatedDateTime = $updatedIfno->created_at;
            }

            $passData = $tble . '&' . $lastUpldatedDateTime;
            // $live_url = $url . $zn . "/" . $org . "/" . $passData; // For nsd dhaka item data
            // $ch = curl_init();
            // curl_setopt_array($ch, array(
            //     CURLOPT_URL => $live_url,
            //     CURLOPT_RETURNTRANSFER => true,
            // ));

            // $parse_url = curl_exec($ch);
            // $datas = json_decode($parse_url);
            // curl_close($ch);

            $live_url = $url . $zn . "/" . $org . "/" . urlencode($passData); // For nas dhaka supplier data
            $parse_url = file_get_contents($live_url);
            $datas = json_decode($parse_url); 

            if(!empty($datas)){

                $countInThousands = ceil(count($datas->items) / 1000);
                $start_range = 0;
                $end_range = 1000;

                $x = 1;

                if (!empty($datas->items) && count($datas->items)>0) {

                    $insertInLogTable = new ApiDataUpdateLog();

                    $insertInLogTable->zone_id = $zoneInfo->id;
                    $insertInLogTable->orgtion_id = $navalLocation->id;
                    $insertInLogTable->zone_alise = $zoneInfo->alise;
                    $insertInLogTable->orgtion_alise = $navalLocation->alise;
                    $insertInLogTable->retrieving_type = 'items';
                    $insertInLogTable->save();

                    while ($countInThousands >= $x) {
                        $for_loop = array_slice($datas->items, $start_range, $end_range);
                        // Foreach loop for insert==================================================

                        \Session::put('zoneAlise', strtolower($datas->zone));
                        foreach ($for_loop as $ss) {

                            $ifexist = Item::where('all_org_item_id', '=', $datas->organization . '_' . $ss->id)->first();

                            if (empty($ifexist)) {
                                $item = new Item();
                            } else {
                                $item = Item::find($ifexist->id);
                            }
                            //$item = new Item();

                            $item->all_org_item_id = $datas->organization . '_' . $ss->id;
                            $item->imc_number = $ss->imc_number;
                            $item->item_name = $ss->item_name;
                            $item->model_number = $ss->model_number;
                            $item->item_cat_id = $ss->item_cat_id;
                            $item->nsd_id = $ss->nsd_id;
                            $item->unit_price = $ss->unit_price;
                            $item->unit_price_in_bdt = $ss->unit_price_in_bdt;
                            $item->currency_name = $ss->currency_name;
                            $item->conversion = $ss->conversion;
                            $item->discounted_price = $ss->discounted_price;
                            $item->discounted_price_in_bdt = $ss->discounted_price_in_bdt;
                            $item->item_deno = $ss->item_deno;
                            $item->manufacturing_country = $ss->manufacturing_country;
                            $item->source_of_supply = $ss->source_of_supply;
                            $item->other_info_about_itme = $ss->other_info_about_itme;
                            $item->budget_code = $ss->budget_code;

                            $item->item_type = $ss->item_type;
                            
                            $item->status_id = $ss->status_id;
                            $item->created_by = $ss->created_by;
                            $item->updated_by = $ss->updated_by;
                            $item->updated_at = $insertInLogTable->updated_at;

                            $item->save();

                        } // End foreach loop end ============================================

                        $start_range = 1000 * $x + 1;
                        $end_range += 1000;
                        $x++;
                        sleep(60);
                    } // end while loop =======================================================

                } // End of item if
                \Log::channel('allNsd')->info("Item informations.  " . count($datas->items) . " data updated.\r\n");

            } // end of item datas if

    // End of item information fetching ==========================================================

    // Tender information fetching start here =============================================================================
            $tble = 3;

            $updatedIfno = ApiDataUpdateLog::where('zone_id', '=', $zoneInfo->id)
                ->where('orgtion_id', '=', $navalLocation->id)
                ->where('retrieving_type', '=', 'tenders')
                ->orderBy('id', 'DESC')
                ->first();
            if (empty($updatedIfno)) {
                $lastUpldatedDateTime = '2015-01-21 01:01:00';
            } else {
                $lastUpldatedDateTime = $updatedIfno->created_at;
            }

            $passData = $tble . '&' . $lastUpldatedDateTime;
            // $live_url = $url . $zn . "/" . $org . "/" . $passData; // For nsd dhaka tender data
            // $ch = curl_init();
            // curl_setopt_array($ch, array(
            //     CURLOPT_URL => $live_url,
            //     CURLOPT_RETURNTRANSFER => true,
            // ));

            // $parse_url = curl_exec($ch);
            // $datas = json_decode($parse_url);
            // curl_close($ch);

            $live_url = $url . $zn . "/" . $org . "/" . urlencode($passData); // For nas dhaka supplier data
            $parse_url = file_get_contents($live_url);
            $datas = json_decode($parse_url); 

            if(!empty($datas)){

                $countInThousands = ceil(count($datas->tenders) / 1000);
                $start_range = 0;
                $end_range = 1000;

                $x = 1;

                if (!empty($datas->tenders) && count($datas->tenders)>0) {

                    $insertInLogTable = new ApiDataUpdateLog();

                    $insertInLogTable->zone_id = $zoneInfo->id;
                    $insertInLogTable->orgtion_id = $navalLocation->id;
                    $insertInLogTable->zone_alise = $zoneInfo->alise;
                    $insertInLogTable->orgtion_alise = $navalLocation->alise;
                    $insertInLogTable->retrieving_type = 'tenders';
                    $insertInLogTable->save();

                    while ($countInThousands >= $x) {
                        $for_loop = array_slice($datas->tenders, $start_range, $end_range);
                        // Foreach loop for insert==================================================

                        \Session::put('zoneAlise', strtolower($datas->zone));
                        foreach ($for_loop as $ss) {

                            $ifexist = Tender::where('all_org_tender_id', '=', $datas->organization . '_' . $ss->id)->first();

                            if (empty($ifexist)) {
                                $tender = new Tender();
                            } else {
                                $tender = Tender::find($ifexist->id);
                            }
                            //$tender = new Tender();

                            $tender->all_org_tender_id = $datas->organization . '_' . $ss->id;
                            $tender->demand_no = $ss->demand_no;
                            $tender->po_number = $ss->po_number;
                            $tender->tender_title = $ss->tender_title;
                            $tender->tender_number = $ss->tender_number;
                            $tender->tender_description = $ss->tender_description;
                            $tender->tender_opening_date = $ss->tender_opening_date;
                            if(!empty($ss->supplier_id) && count(explode('_',$ss->supplier_id))<2){
                                $tender->supplier_id = $datas->organization . '_' . $ss->supplier_id;
                            }
                            if(!empty($ss->supplier_id) && count(explode('_',$ss->supplier_id))>1){
                                $tender->supplier_id = $ss->supplier_id;
                            }
                            //$tender->supplier_id = $datas->organization . '_' . $ss->supplier_id;
                            $tender->work_order_date = $ss->work_order_date;
                            $tender->date_line = $ss->date_line;
                            $tender->delivery_date = $ss->delivery_date;
                            $tender->imc_number = $ss->imc_number;
                            $tender->tender_cat_id = $ss->tender_cat_id;
                            $tender->nsd_id = $ss->nsd_id;
                            $tender->other_info_about_tender = $ss->other_info_about_tender;
                            $tender->specification = $ss->specification;
                            $tender->specification_doc = $ss->specification_doc;
                            $tender->notice = $ss->notice;
                            $tender->open_tender = $ss->open_tender;
                            $tender->date_line = empty($request->tender_opening_date) ? null : date('Y-m-d',strtotime($request->tender_opening_date));

                            $tender->approval_letter_number = $ss->approval_letter_number;
                            $tender->approval_letter_date = $ss->approval_letter_date;
                            $tender->purchase_type = $ss->purchase_type;
                            $tender->tender_type = $ss->tender_type;
                            $tender->tender_nature = $ss->tender_nature;
                            $tender->ref_tender_id = $ss->ref_tender_id;
                            $tender->tender_priority = $ss->tender_priority;
                            $tender->letter_body = $ss->letter_body;
                            $tender->remarks = $ss->remarks;
                            $tender->valid_date_from = $ss->valid_date_from;
                            $tender->valid_date_to = $ss->tender_opening_date;
                            $tender->extend_date_to = $ss->extend_date_to;
                            $tender->reference = $ss->reference;
                            $tender->number_of_lot_item      = $ss->number_of_lot_item;
                            $tender->reference_date          = $ss->reference_date;

                            $tender->status_id  = $ss->status_id;
                            $tender->created_by = $ss->created_by;
                            $tender->updated_by = $ss->updated_by;
                            $tender->updated_at = $insertInLogTable->updated_at;

                            $tender->save();

                        } // End foreach loop end ============================================

                        $start_range = 1000 * $x + 1;
                        $end_range += 1000;
                        $x++;
                        sleep(60);
                    } // end while loop =======================================================

                } // End of tender if

                \Log::channel('allNsd')->info("Tender informations.  " . count($datas->tenders) . " data updated.\r\n");

            }// End of tenders datas not empty

    // End of Tender information fetching ===========================================================================

    // ItemToTender information fetching start here =================================================================
            $tble = 4;

            $updatedIfno = ApiDataUpdateLog::where('zone_id', '=', $zoneInfo->id)
                ->where('orgtion_id', '=', $navalLocation->id)
                ->where('retrieving_type', '=', 'itemtotenders')
                ->orderBy('id', 'DESC')
                ->first();
            if (empty($updatedIfno)) {
                $lastUpldatedDateTime = '2015-01-21 01:01:00';
            } else {
                $lastUpldatedDateTime = $updatedIfno->created_at;
            }

            $passData = $tble . '&' . $lastUpldatedDateTime;
            // $live_url = $url . $zn . "/" . $org . "/" . $passData; // For nsd dhaka tender data
            // $ch = curl_init();
            // curl_setopt_array($ch, array(
            //     CURLOPT_URL => $live_url,
            //     CURLOPT_RETURNTRANSFER => true,
            // ));

            // $parse_url = curl_exec($ch);
            // $datas = json_decode($parse_url);
            // curl_close($ch);

            $live_url = $url . $zn . "/" . $org . "/" . urlencode($passData); // For nas dhaka supplier data
            $parse_url = file_get_contents($live_url);
            $datas = json_decode($parse_url); 

            if(!empty($datas)){

                $countInThousands = ceil(count($datas->itemtotenders) / 1000);
                $start_range = 0;
                $end_range = 1000;

                $x = 1;

                if (!empty($datas->itemtotenders) && count($datas->itemtotenders)>0) {

                    $insertInLogTable = new ApiDataUpdateLog();

                    $insertInLogTable->zone_id = $zoneInfo->id;
                    $insertInLogTable->orgtion_id = $navalLocation->id;
                    $insertInLogTable->zone_alise = $zoneInfo->alise;
                    $insertInLogTable->orgtion_alise = $navalLocation->alise;
                    $insertInLogTable->retrieving_type = 'itemtotenders';
                    $insertInLogTable->save();

                    while ($countInThousands >= $x) {
                        $for_loop = array_slice($datas->itemtotenders, $start_range, $end_range);
                        // Foreach loop for insert==================================================

                        \Session::put('zoneAlise', strtolower($datas->zone));
                        foreach ($for_loop as $ss) {

                            $ifexist = ItemToTender::where('all_org_itmtotender_id', '=', $datas->organization . '_' . $ss->id)->first();

                            if (empty($ifexist)) {
                                $itemtotender = new ItemToTender();
                            } else {
                                $itemtotender = ItemToTender::find($ifexist->id);
                            }
                            //$itemtotender = new ItemToTender();

                            $itemtotender->all_org_itmtotender_id = $datas->organization . '_' . $ss->id;
                            //$itemtotender->tender_id = $datas->organization . '_' . $ss->tender_id;
                            //$itemtotender->item_id = $datas->organization . '_' . $ss->item_id;
                            if(!empty($ss->tender_id) && count(explode('_',$ss->tender_id))<2){
                                $itemtotender->tender_id = $datas->organization . '_' . $ss->tender_id;
                            }
                            if(!empty($ss->tender_id) && count(explode('_',$ss->tender_id))>1){
                                $itemtotender->tender_id = $ss->tender_id;
                            }
                            if(!empty($ss->item_id) && count(explode('_',$ss->item_id))<2){
                                $itemtotender->item_id = $datas->organization . '_' . $ss->item_id;
                            }
                            if(!empty($ss->item_id) && count(explode('_',$ss->item_id))>1){
                                $itemtotender->item_id = $ss->item_id;
                            }
                            $itemtotender->unit_price = $ss->unit_price;
                            $itemtotender->unit_price_in_bdt = $ss->unit_price_in_bdt;
                            $itemtotender->currency_name = $ss->currency_name;
                            $itemtotender->conversion = $ss->conversion;
                            $itemtotender->quantity = $ss->quantity;
                            $itemtotender->discount_price = $ss->discount_price;
                            $itemtotender->discount_price_in_bdt = $ss->discount_price_in_bdt;
                            $itemtotender->total = $ss->total;
                            $itemtotender->status_id = $ss->status_id;
                            $itemtotender->created_by = $ss->created_by;
                            $itemtotender->updated_by = $ss->updated_by;
                            $itemtotender->updated_at = $insertInLogTable->updated_at;

                            $itemtotender->save();

                        } // End foreach loop end ============================================

                        $start_range = 1000 * $x + 1;
                        $end_range += 1000;
                        $x++;
                        sleep(60);
                    } // end while loop =======================================================

                } // End of item to tender if

                \Log::channel('allNsd')->info("Item To Tender informations.  " . count($datas->itemtotenders) . " data updated.\r\n");

            }// End of itemtotender datas if

    // End of ItemToTender information fetching ==========================================================

            $abc++;
            sleep(60);
        } // end of first loop for all branch

    }
}
