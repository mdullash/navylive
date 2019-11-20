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

class NsdKhulnaTenders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nsdkhulna:tenders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        
        $live_url = "http://navy.isslsolutions.com/api/api-tenders/nsd/nsd_khulna/"; // For nsd khulna tender data

        $uri_segments = explode('/', $live_url);

        $apiZone            = $uri_segments[5];
        $apiOrganization    = $uri_segments[6];

        $zoneInfo = Zone::where('alise','=',$apiZone)->first();
        $navalLocation = NsdName::where('alise','=',$apiOrganization)->orderBy('id')->first();

        $updatedIfno = ApiDataUpdateLog::where('zone_id','=',$zoneInfo->id)
            ->where('orgtion_id','=',$navalLocation->id)
            ->where('retrieving_type','=','tenders')
            ->orderBy('id','DESC')
            ->first();
        if(empty($updatedIfno)){
            $lastUpldatedDateTime = '2015-01-21 01:01:00';
        }else{
            $lastUpldatedDateTime = $updatedIfno->created_at;
        }

        $live_url = "http://navy.isslsolutions.com/api/api-tenders/nsd/nsd_khulna/".$lastUpldatedDateTime; // For nsd khulna tender data


        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $live_url,
            CURLOPT_RETURNTRANSFER => true,
        ));

        $parse_url = curl_exec($ch);
        $datas = json_decode($parse_url);
        curl_close($ch);

        $countInThousands = ceil(count($datas->tenders) / 1000);
        $start_range = 0;
        $end_range   = 1000;

        $x = 1;
//echo "<pre>"; print_r($datas->tenders); exit;
        if(!empty($datas->tenders)){

            $insertInLogTable = new ApiDataUpdateLog();

            $insertInLogTable->zone_id          = $zoneInfo->id;
            $insertInLogTable->orgtion_id       = $navalLocation->id;
            $insertInLogTable->zone_alise       = $zoneInfo->alise;
            $insertInLogTable->orgtion_alise    = $navalLocation->alise;
            $insertInLogTable->retrieving_type  = 'tenders';
            $insertInLogTable->save();

            while($countInThousands >= $x) {
                $for_loop = array_slice($datas->tenders, $start_range, $end_range);
                // Foreach loop for insert==================================================

                \Session::put('zoneAlise', strtolower($datas->zone));
                foreach ($for_loop as $ss){

                    $ifexist = Tender::where('all_org_tender_id','=',$datas->organization.'_'.$ss->id)->first();

                    if(empty($ifexist)){
                        $tender = new Tender();
                    }else{
                        $tender = Tender::find($ifexist->id);
                    }

                    //$tender = new Tender();

                    $tender->all_org_tender_id       = $datas->organization.'_'.$ss->id;
                    $tender->po_number               = $ss->po_number;
                    $tender->tender_title            = $ss->tender_title;
                    $tender->tender_number           = $ss->tender_number;
                    $tender->tender_description      = $ss->tender_description;
                    $tender->tender_opening_date     = $ss->tender_opening_date;
                    $tender->supplier_id             = $datas->organization.'_'.$ss->supplier_id;
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

                } // End foreach loop end ============================================

                $start_range = 1000*$x+1;
                $end_range   += 1000;
                $x++;
            } // end while loop =======================================================

        } // End of if


    }
}
