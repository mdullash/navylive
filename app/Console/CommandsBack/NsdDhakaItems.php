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

class NsdDhakaItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nsddhaka:items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch items data for nsd dhaka';

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
        $live_url = "http://navy.isslsolutions.com/api/api-items/nsd/nssd_dhaka/"; // For nsd dhaka items data

        $uri_segments = explode('/', $live_url);

        $apiZone            = $uri_segments[5];
        $apiOrganization    = $uri_segments[6];

        $zoneInfo = Zone::where('alise','=',$apiZone)->first();
        $navalLocation = NsdName::where('alise','=',$apiOrganization)->orderBy('id')->first();

        $updatedIfno = ApiDataUpdateLog::where('zone_id','=',$zoneInfo->id)
            ->where('orgtion_id','=',$navalLocation->id)
            ->where('retrieving_type','=','items')
            ->orderBy('id','DESC')
            ->first();
        if(empty($updatedIfno)){
            $lastUpldatedDateTime = '2015-01-21 01:01:00';
        }else{
            $lastUpldatedDateTime = $updatedIfno->created_at;
        }

        $live_url = "http://navy.isslsolutions.com/api/api-items/nsd/nssd_dhaka/".$lastUpldatedDateTime; // For nsd dhaka items data



        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $live_url,
            CURLOPT_RETURNTRANSFER => true,
        ));

        $parse_url = curl_exec($ch);
        $datas = json_decode($parse_url);
        curl_close($ch);

        $countInThousands = ceil(count($datas->items) / 1000);
        $start_range = 0;
        $end_range   = 1000;

        $x = 1;
//echo "<pre>"; print_r($datas->items); exit;
        if(!empty($datas->items)){

            $insertInLogTable = new ApiDataUpdateLog();

            $insertInLogTable->zone_id          = $zoneInfo->id;
            $insertInLogTable->orgtion_id       = $navalLocation->id;
            $insertInLogTable->zone_alise       = $zoneInfo->alise;
            $insertInLogTable->orgtion_alise    = $navalLocation->alise;
            $insertInLogTable->retrieving_type  = 'items';
            $insertInLogTable->save();

            while($countInThousands >= $x) {
                $for_loop = array_slice($datas->items, $start_range, $end_range);
                // Foreach loop for insert==================================================

                \Session::put('zoneAlise', strtolower($datas->zone));
                foreach ($for_loop as $ss){

                    $ifexist = Item::where('all_org_item_id','=',$datas->organization.'_'.$ss->id)->first();

                    if(empty($ifexist)){
                        $item = new Item();
                    }else{
                        $item = Item::find($ifexist->id);
                    }
                    //$item = new Item();

                    $item->all_org_item_id              = $datas->organization.'_'.$ss->id;
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

                } // End foreach loop end ============================================

                $start_range = 1000*$x+1;
                $end_range   += 1000;
                $x++;
            } // end while loop =======================================================

        } // End of if
    }
}
