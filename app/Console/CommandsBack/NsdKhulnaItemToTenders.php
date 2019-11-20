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

class NsdKhulnaItemToTenders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nsdkhulna:itemtotenders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Item to Tender Data for Nsd khulna';

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
        
        $live_url = "http://navy.isslsolutions.com/api/api-itemtotenders/nsd/nsd_khulna/"; // For nsd khulna itemtotenders data

        $uri_segments = explode('/', $live_url);

        $apiZone            = $uri_segments[5];
        $apiOrganization    = $uri_segments[6];

        $zoneInfo = Zone::where('alise','=',$apiZone)->first();
        $navalLocation = NsdName::where('alise','=',$apiOrganization)->orderBy('id')->first();

        $updatedIfno = ApiDataUpdateLog::where('zone_id','=',$zoneInfo->id)
            ->where('orgtion_id','=',$navalLocation->id)
            ->where('retrieving_type','=','itemtotenders')
            ->orderBy('id','DESC')
            ->first();
        if(empty($updatedIfno)){
            $lastUpldatedDateTime = '2015-01-21 01:01:00';
        }else{
            $lastUpldatedDateTime = $updatedIfno->created_at;
        }

        $live_url = "http://navy.isslsolutions.com/api/api-itemtotenders/nsd/nsd_khulna/".$lastUpldatedDateTime; // For nsd khulna itemtotenders data

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $live_url,
            CURLOPT_RETURNTRANSFER => true,
        ));

        $parse_url = curl_exec($ch);
        $datas = json_decode($parse_url);
        curl_close($ch);

        $countInThousands = ceil(count($datas->itemtotenders) / 1000);
        $start_range = 0;
        $end_range   = 1000;

        $x = 1;
//echo "<pre>"; print_r($datas->itemtotenders); exit;
        if(!empty($datas->itemtotenders)){

            $insertInLogTable = new ApiDataUpdateLog();

            $insertInLogTable->zone_id          = $zoneInfo->id;
            $insertInLogTable->orgtion_id       = $navalLocation->id;
            $insertInLogTable->zone_alise       = $zoneInfo->alise;
            $insertInLogTable->orgtion_alise    = $navalLocation->alise;
            $insertInLogTable->retrieving_type  = 'itemtotenders';
            $insertInLogTable->save();

            while($countInThousands >= $x) {
                $for_loop = array_slice($datas->itemtotenders, $start_range, $end_range);
                // Foreach loop for insert==================================================

                \Session::put('zoneAlise', strtolower($datas->zone));
                foreach ($for_loop as $ss){

                    $ifexist = ItemToTender::where('all_org_itmtotender_id','=',$datas->organization.'_'.$ss->id)->first();

                    if(empty($ifexist)){
                        $itemtotender = new ItemToTender();
                    }else{
                        $itemtotender = ItemToTender::find($ifexist->id);
                    }

                    //$itemtotender = new ItemToTender();

                    $itemtotender->all_org_itmtotender_id            = $datas->organization.'_'.$ss->id;
                    $itemtotender->tender_id                         = $datas->organization.'_'.$ss->tender_id;
                    $itemtotender->item_id                           = $datas->organization.'_'.$ss->item_id;
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

                } // End foreach loop end ============================================

                $start_range = 1000*$x+1;
                $end_range   += 1000;
                $x++;
            } // end while loop =======================================================

        } // End of if


    }
}
