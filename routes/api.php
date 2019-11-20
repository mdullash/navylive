<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::any('update-data/1/{org}/{data}','ApiController@allNsdDataUpdate'); // For nsd  all
Route::any('update-data/2/{org}/{data}','ApiController@allBsdDataUpdate'); // For bsd  all

Route::any('update-data/ctng','ApiController@updateChittagongData'); // For chittagong push data

// Api dhaka nsd
// Route::any('api-supplier/nsd/nssd_dhaka/{date}','ApiController@nsdDhakaNsd'); // supplier
// Route::any('api-items/nsd/nssd_dhaka/{date}','ApiController@nsdDhakaNsdItems'); // item
// Route::any('api-tenders/nsd/nssd_dhaka/{date}','ApiController@nsdDhakaNsdTenders'); // Tender
// Route::any('api-itemtotenders/nsd/nssd_dhaka/{date}','ApiController@nsdDhakaNsdItemToTenders');// Item to Tender


// Api dhaka bsd
// Route::any('api-supplier/bsd/bsd_dhaka/{date}','ApiController@bsdDhakaBsd'); // supplier
// Route::any('api-items/bsd/bsd_dhaka/{date}','ApiController@bsdDhakaBsdItems'); // item
// Route::any('api-tenders/bsd/bsd_dhaka/{date}','ApiController@bsdDhakaBsdTenders'); // Tender
// Route::any('api-itemtotenders/bsd/bsd_dhaka/{date}','ApiController@bsdDhakaBsdItemToTenders');// Item to Tender


// Api for khulna
// Route::any('api-supplier/nsd/nsd_khulna/{date}','ApiController@nsdKhulnaNsd'); // supplier
// Route::any('api-supplier/bsd/bsd_khulna/{date}','ApiController@bsdKhulnaBsd'); // supplier
// Route::any('api-items/nsd/nsd_khulna/{date}','ApiController@nsdKhulnaNsdItmes'); // item
// Route::any('api-items/bsd/bsd_khulna/{date}','ApiController@bsdKhulnaBsdItmes'); // item
// Route::any('api-tenders/nsd/nsd_khulna/{date}','ApiController@nsdKhulnaNsdTenders'); // Tender
// Route::any('api-tenders/bsd/bsd_khulna/{date}','ApiController@bsdKhulnaBsdTenders'); // Tender
// Route::any('api-itemtotenders/nsd/nsd_khulna/{date}','ApiController@nsdKhulnaNsdItemToTenders'); // Item to Tender
// Route::any('api-itemtotenders/bsd/bsd_khulna/{date}','ApiController@bsdKhulnaBsdItemToTenders'); // Item to Tender

// Api Dgdp
// Route::any('api-supplier/nsd/dgdp/{date}','ApiController@nsdDgdpNsd'); // supplier
// Route::any('api-items/nsd/dgdp/{date}','ApiController@nsdDgdpNsdItems'); // Item
// Route::any('api-tenders/nsd/dgdp/{date}','ApiController@nsdDgdpNsdTenders'); // Tender
// Route::any('api-itemtotenders/nsd/dgdp/{date}','ApiController@nsdDgdpNsdItemToTenders'); // Tender

// Api Chittagong
// Route::any('api-supplier/nsd/nsd_chattagram','ApiController@nsdChattagramNsd'); // supplier
// Route::any('api-supplier-personal-info/nsd/nsd_chattagram','ApiController@nsdChattagramSuppPersNsd'); // supplier ==========
// Route::any('api-items/nsd/nsd_chattagram','ApiController@nsdChattagramNsdItems'); // Item ===========
// Route::any('api-tenders/nsd/nsd_chattagram','ApiController@nsdChattagramNsdTenders'); // Tender ===========
// Route::any('api-itemtotenders/nsd/nsd_chattagram','ApiController@nsdChattagramNsdItemToTenders'); // Tender ===========




