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

class NsdDgdpSuppliers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nsddgdp:suppliers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch supplier for dgdp';

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
        $live_url = "http://navy.isslsolutions.com/api/api-supplier/nsd/dgdp/"; // For nsd dgdp supplier data

        $uri_segments = explode('/', $live_url);

        $apiZone            = $uri_segments[5];
        $apiOrganization    = $uri_segments[6];

        $zoneInfo = Zone::where('alise','=',$apiZone)->first();
        $navalLocation = NsdName::where('alise','=',$apiOrganization)->orderBy('id')->first();

        $updatedIfno = ApiDataUpdateLog::where('zone_id','=',$zoneInfo->id)
            ->where('orgtion_id','=',$navalLocation->id)
            ->where('retrieving_type','=','suppliers')
            ->orderBy('id','DESC')
            ->first();
        if(empty($updatedIfno)){
            $lastUpldatedDateTime = '2015-01-21 01:01:00';
        }else{
            $lastUpldatedDateTime = $updatedIfno->created_at;
        }

        $live_url = "http://navy.isslsolutions.com/api/api-supplier/nsd/dgdp/".$lastUpldatedDateTime; // For nsd dgdp supplier data
        

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $live_url,
            CURLOPT_RETURNTRANSFER => true,
        ));

        $parse_url = curl_exec($ch);
        $datas = json_decode($parse_url);
        curl_close($ch);

        $countInThousands = ceil(count($datas->suppliers) / 1000);
        $start_range = 0;
        $end_range   = 1000;

        $x = 1;

        if(!empty($datas->suppliers)){

            $insertInLogTable = new ApiDataUpdateLog();

            $insertInLogTable->zone_id          = $zoneInfo->id;
            $insertInLogTable->orgtion_id       = $navalLocation->id;
            $insertInLogTable->zone_alise       = $zoneInfo->alise;
            $insertInLogTable->orgtion_alise    = $navalLocation->alise;
            $insertInLogTable->retrieving_type  = 'suppliers';
            $insertInLogTable->save();

            while($countInThousands >= $x) {
                $for_loop = array_slice($datas->suppliers, $start_range, $end_range);
                // Foreach loop for insert==================================================

                \Session::put('zoneAlise', strtolower($datas->zone));
                foreach ($for_loop as $ss){

                    $ifexist = Supplier::where('all_org_id','=',$datas->organization.'_'.$ss->id)->first();

                    if(empty($ifexist)){
                        $supplier = new Supplier();
                    }else{
                        $supplier = Supplier::find($ifexist->id);
                    }
                    //$supplier = new Supplier();

                    $supplier->all_org_id               = $datas->organization.'_'.$ss->id;
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

                } // End foreach loop end ============================================

                $start_range = 1000*$x+1;
                $end_range   += 1000;
                $x++;
            } // end while loop =======================================================

        } // End of if

        $countInThousandsSecond = ceil(count($datas->suppliers_personal_infos) / 1000);
        $start_rangeSec = 0;
        $end_rangeSec   = 1000;

        $xyz = 1;
        if(!empty($datas->suppliers_personal_infos)){

            $insertInLogTable = new ApiDataUpdateLog();

            $insertInLogTable->zone_id          = $zoneInfo->id;
            $insertInLogTable->orgtion_id       = $navalLocation->id;
            $insertInLogTable->zone_alise       = $zoneInfo->alise;
            $insertInLogTable->orgtion_alise    = $navalLocation->alise;
            $insertInLogTable->retrieving_type  = 'suppliers';
            $insertInLogTable->save();

            while($countInThousandsSecond >= $x) {
                $for_loop = array_slice($datas->suppliers_personal_infos, $start_rangeSec, $end_rangeSec);
                // Foreach loop for insert==================================================

                \Session::put('zoneAlise', strtolower($datas->zone));
                foreach ($for_loop as $ss){

                    $ifexist = SupplierBasicInfo::where('all_org_sup_bas_info_id','=',$datas->organization.'_'.$ss->id)->first();

                    if(empty($ifexist)){
                        $supplier_bsc_info = new SupplierBasicInfo();
                    }else{
                        $supplier_bsc_info = SupplierBasicInfo::find($ifexist->id);
                    }

                    //$supplier_bsc_info = new SupplierBasicInfo();

                    $supplier_bsc_info->all_org_sup_bas_info_id = $datas->organization.'_'.$ss->id;
                    $supplier_bsc_info->supplier_id = $datas->organization.'_'.$ss->supplier_id;
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

                } // End foreach loop end ============================================

                $start_rangeSec = 1000*$x+1;
                $end_rangeSec   += 1000;
                $xyz++;
            } // end while loop =======================================================

        } // End oof secodn if
    }
}
