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
use App\Supplier;
use App\Item;
use App\Tender;
use App\ItemToTender;
use Illuminate\Database\Schema\Blueprint;
use Schema;
use Maatwebsite\Excel\Facades\Excel;

class ExcelDataUploadController extends Controller
{

    private $moduleId = 21;

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function supplier()
    {
       return View::make('excel-data-upload.supplier-excel-data');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function item()
    {
        return View::make('excel-data-upload.item-excel-data');

    }


    public function tender()
    {
        return View::make('excel-data-upload.tender-excel-data');

    }

    public function itemtotender()
    {
        return View::make('excel-data-upload.itemtotender-excel-data');

    }

// Suppler excel data store ======================================================================
    public function postUploadSuppliersExcel(Request $request){

        $v = \Validator::make($request->all(), [
            'suppliers' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('excel/suppliers')->withErrors($v->errors());
        }
        //Employee::truncate();
        $excelUpload = Input::file('suppliers');
        $destinationPath = public_path() . '/uploads/excelFiles/';
        $excelFilename = 'suppliers-excel-upload'.date('d-m-Y-h-i').'.'.$excelUpload->getClientOriginalExtension();
        Input::file('suppliers')->move($destinationPath, $excelFilename);

        $data = Excel::load($destinationPath.'/'.$excelFilename, function($reader) {
        })->get();

        $csvData = $data->toArray();

        $firstrow = '';
        if(count($csvData)>0){
            $firstrow = $data->first()->toArray();
        }

        for ($i = 0; $i < count($csvData); $i ++) {

            $supplierAlreadyExist = Supplier::where('company_name','=',trim($csvData[$i]['company_name']))->get();

                if(count($supplierAlreadyExist)<1){
                    $supplier = new Supplier();

                    $supplier->company_name = trim($csvData[$i]['company_name']);
                    if(isset($firstrow['company_registration_number_in_nssd_nsd'])){
                        $supplier->company_regi_number_nsd = empty(trim($csvData[$i]['company_registration_number_in_nssd_nsd'])) ? null : trim($csvData[$i]['company_registration_number_in_nssd_nsd']);
                    }
                    if(isset($firstrow['company_registration_number_in_bsd'])){
                        $supplier->company_regi_number_nsd = empty(trim($csvData[$i]['company_registration_number_in_bsd'])) ? null : trim($csvData[$i]['company_registration_number_in_bsd']);
                    }
                    $supplier->mobile_number = empty(trim($csvData[$i]['mobile_number'])) ? null : trim($csvData[$i]['mobile_number']);
                    $supplier->supply_cat_id = empty(trim($csvData[$i]['supply_category'])) ? null : trim($csvData[$i]['supply_category']);
                    $supplier->vat_registration_number = empty(trim($csvData[$i]['vat_registration_number'])) ? null : trim($csvData[$i]['vat_registration_number']);
                    $supplier->tin_number = empty(trim($csvData[$i]['tin_number'])) ? null : trim($csvData[$i]['tin_number']);
                    $supplier->nid_number = empty(trim($csvData[$i]['nid_number'])) ? null : trim($csvData[$i]['nid_number']);
                    $supplier->trade_license_number = empty(trim($csvData[$i]['trade_license_number'])) ? null : trim($csvData[$i]['trade_license_number']);
                    $supplier->trade_license_address = empty(trim($csvData[$i]['trade_license_address'])) ? null : trim($csvData[$i]['trade_license_address']);
                    $supplier->company_bank_account_name = empty(trim($csvData[$i]['company_bank_account_name'])) ? null : trim($csvData[$i]['company_bank_account_name']);
                    $supplier->bank_account_number = empty(trim($csvData[$i]['bank_account_number'])) ? null : trim($csvData[$i]['bank_account_number']);
                    $supplier->bank_name_and_branch = empty(trim($csvData[$i]['bank_name_and_branch'])) ? null : trim($csvData[$i]['bank_name_and_branch']);
                    if(isset($firstrow['registered_nsd_name'])){
                        $supplier->registered_nsd_id = empty(trim($csvData[$i]['registered_nsd_name'])) ? null : trim($csvData[$i]['registered_nsd_name']);
                    }
                    if(isset($firstrow['registered_bsd_name'])){
                        $supplier->registered_nsd_id = empty(trim($csvData[$i]['registered_bsd_name'])) ? null : trim($csvData[$i]['registered_bsd_name']);
                    }
                    $supplier->date_of_enrollment = empty(trim($csvData[$i]['date_of_enrollment'])) ? null : date('Y-m-d',strtotime(trim($csvData[$i]['date_of_enrollment'])));

                    if(isset($firstrow['bsti_certification'])){
                        $supplier->bsti_certification = empty($request->bsti_certification) ? null : trim($csvData[$i]['bsti_certification']);
                    }
                    if(isset($firstrow['iso_certification'])){
                        $supplier->iso_certification = empty($request->iso_certification) ? null : trim($csvData[$i]['iso_certification']);
                    }

                    $supplier->status_id = 1;
                    $supplier->save();
                }

        }
        if(count($csvData)>0){
            Session::flash('success', 'Supplier Data Created Successfully');
            return Redirect::to('excel/suppliers');
        }else{
            Session::flash('error', 'Supplier Data Creation Failed');
            return Redirect::to('excel/suppliers');
        }

    }

    // Item excel data store ======================================================================
    public function postUploadItemsExcel(Request $request){

        $v = \Validator::make($request->all(), [
            'items' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('excel/items')->withErrors($v->errors());
        }
        //Employee::truncate();
        $excelUpload = Input::file('items');
        $destinationPath = public_path() . '/uploads/excelFiles/';
        $excelFilename = 'items-excel-upload'.date('d-m-Y-h-i').'.'.$excelUpload->getClientOriginalExtension();
        Input::file('items')->move($destinationPath, $excelFilename);

        $data = Excel::load($destinationPath.'/'.$excelFilename, function($reader) {
        })->get();

        $csvData = $data->toArray();

        $firstrow = '';
        if(count($csvData)>0){
            $firstrow = $data->first()->toArray();
        }

        for ($i = 0; $i < count($csvData); $i ++) {

            $itemAlreadyExist = Item::where('item_name','=',trim($csvData[$i]['item_name']))->get();

            if(count($itemAlreadyExist)<1){

                $item = new Item();
                if(isset($firstrow['imc_number'])){
                    $item->imc_number =empty(trim($csvData[$i]['imc_number'])) ? null : trim($csvData[$i]['imc_number']);
                }
                $item->item_name = empty(trim($csvData[$i]['item_name'])) ? null : trim($csvData[$i]['item_name']);
                if(isset($firstrow['model_number'])){
                    $item->model_number = empty(trim($csvData[$i]['model_number'])) ? null : trim($csvData[$i]['model_number']);
                }
                $item->budget_code = empty(trim($csvData[$i]['budget_code'])) ? null : trim($csvData[$i]['budget_code']);
                $item->item_cat_id = empty(trim($csvData[$i]['item_category'])) ? null : trim($csvData[$i]['item_category']);
                $item->unit_price = empty(trim($csvData[$i]['unit_price'])) ? 0 : trim($csvData[$i]['unit_price']);
                $item->discounted_price = empty(trim($csvData[$i]['discounted_price'])) ? 0 : trim($csvData[$i]['discounted_price']);
                if(isset($firstrow['item_deno'])){
                    $item->item_deno = empty(trim($csvData[$i]['item_deno'])) ? null : trim($csvData[$i]['item_deno']);
                }
                if(isset($firstrow['deno'])){
                    $item->item_deno = empty(trim($csvData[$i]['deno'])) ? null : trim($csvData[$i]['deno']);
                }
                $item->manufacturing_country = empty(trim($csvData[$i]['manufacturing_country'])) ? null : trim($csvData[$i]['manufacturing_country']);
                $item->source_of_supply = empty(trim($csvData[$i]['source_of_supply'])) ? null : trim($csvData[$i]['source_of_supply']);
                if(isset($firstrow['nsd_name'])){
                    $item->nsd_id = empty(trim($csvData[$i]['nsd_name'])) ? null : trim($csvData[$i]['nsd_name']);
                }
                if(isset($firstrow['bsd_name'])){
                    $item->nsd_id = empty(trim($csvData[$i]['bsd_name'])) ? null : trim($csvData[$i]['bsd_name']);
                }
                $item->status_id = 1;

                $item->save();

            }

        }
        if(count($csvData)>0){
            Session::flash('success', 'Item Data Created Successfully');
            return Redirect::to('excel/items');
        }else{
            Session::flash('error', 'Item Data Creation Failed');
            return Redirect::to('excel/items');
        }// End of item insert =============================================================

    }

    // Tender excel data store ======================================================================
    public function postUploadTendersExcel(Request $request)
    {

        $v = \Validator::make($request->all(), [
            'tenders' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('excel/tenders')->withErrors($v->errors());
        }
        //Employee::truncate();
        $excelUpload = Input::file('tenders');
        $destinationPath = public_path() . '/uploads/excelFiles/';
        $excelFilename = 'tenders-excel-upload' . date('d-m-Y-h-i') . '.' . $excelUpload->getClientOriginalExtension();
        Input::file('tenders')->move($destinationPath, $excelFilename);

        $data = Excel::load($destinationPath . '/' . $excelFilename, function ($reader) {
        })->get();

        $csvData = $data->toArray();

        $firstrow = '';
        if (count($csvData) > 0) {
            $firstrow = $data->first()->toArray();
        }

        for ($i = 0; $i < count($csvData); $i++) {

            //$tenderAlreadyExist = Tender::where('tender_title','=',trim($csvData[$i]['tender_title']))->get();

            //if(count($tenderAlreadyExist)<1){

                $tender = new Tender();
                $tender->tender_title = empty(trim($csvData[$i]['tender_title'])) ? null : trim($csvData[$i]['tender_title']);
                $tender->tender_number = empty(trim($csvData[$i]['tender_number'])) ? null : trim($csvData[$i]['tender_number']);
                $tender->tender_description = empty(trim($csvData[$i]['tender_description'])) ? null : trim($csvData[$i]['tender_description']);
                $tender->tender_opening_date = empty(trim($csvData[$i]['tender_opening_date'])) ? null : date('Y-m-d',strtotime(trim($csvData[$i]['tender_opening_date'])));
                $tender->tender_cat_id = empty(trim($csvData[$i]['tender_category'])) ? null : trim($csvData[$i]['tender_category']);

                if(isset($firstrow['nsd_name'])){
                    $tender->nsd_id = empty(trim($csvData[$i]['nsd_name'])) ? null : trim($csvData[$i]['nsd_name']);
                }
                if(isset($firstrow['bsd_name'])){
                    $tender->nsd_id = empty(trim($csvData[$i]['bsd_name'])) ? null : trim($csvData[$i]['bsd_name']);
                }

                $tender->status_id = 1;
                $tender->save();

            //}

        }
        if (count($csvData) > 0) {
            Session::flash('success', 'Tender Data Created Successfully');
            return Redirect::to('excel/tenders');
        } else {
            Session::flash('error', 'Tender Data Creation Failed');
            return Redirect::to('excel/tenders');
        }// End of Tender insert =============================================================
    } // End Tenders store;

    // Tender excel data store ======================================================================
    public function postUploadItemstotendersExcel(Request $request)
    {

        $v = \Validator::make($request->all(), [
            'itemtotenders' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('excel/itemtotenders')->withErrors($v->errors());
        }
        //Employee::truncate();
        $excelUpload = Input::file('itemtotenders');
        $destinationPath = public_path() . '/uploads/excelFiles/';
        $excelFilename = 'itemtotenders-excel-upload' . date('d-m-Y-h-i') . '.' . $excelUpload->getClientOriginalExtension();
        Input::file('itemtotenders')->move($destinationPath, $excelFilename);

        $data = Excel::load($destinationPath . '/' . $excelFilename, function ($reader) {
        })->get();

        $csvData = $data->toArray();

        $firstrow = '';
        if (count($csvData) > 0) {
            $firstrow = $data->first()->toArray();
        }


        for ($i = 0; $i < count($csvData); $i++) {

            $tender_id = Tender::where('tender_title','=',trim($csvData[$i]['tender_title']))
                            //->where('tender_number','=',trim($csvData[$i]['tender_number']))
                            //->where('id','>',41440)
                            ->value('id');

            $supplier_id = Supplier::where('company_name','=',trim($csvData[$i]['supplier_name']));
            // if(isset($firstrow['supplier_registration_number_in_nssd_nsd'])){
            //     $supplier_id->where('company_regi_number_nsd','=',trim($csvData[$i]['supplier_registration_number_in_nssd_nsd']));
            // }
            // if(isset($firstrow['supplier_registration_number_in_bsd'])){
            //     $supplier_id->where('company_regi_number_nsd','=',trim($csvData[$i]['supplier_registration_number_in_bsd']));
            // }
            $supplier_id = $supplier_id->value('id');

            if(!empty($tender_id) && !empty($supplier_id)){

                $tender = Tender::find($tender_id);

                $tender->supplier_id = $supplier_id;
                if(isset($firstrow['po_number'])){
                    $tender->po_number = empty(trim($csvData[$i]['po_number'])) ? null : trim($csvData[$i]['po_number']);
                }
                if(isset($firstrow['poletter_number'])){
                    $tender->po_number = empty(trim($csvData[$i]['poletter_number'])) ? null : trim($csvData[$i]['poletter_number']);
                }
                $tender->work_order_date = empty(trim($csvData[$i]['purchase_order_date'])) ? null : date('Y-m-d',strtotime(trim($csvData[$i]['purchase_order_date'])));
                $tender->date_line = empty(trim($csvData[$i]['deadline'])) ? null : date('Y-m-d',strtotime(trim($csvData[$i]['deadline'])));
                $tender->delivery_date = empty(trim($csvData[$i]['delivery_date'])) ? null : date('Y-m-d',strtotime(trim($csvData[$i]['delivery_date'])));
                if(isset($firstrow['imc_number'])){
                    $tender->imc_number = empty(trim($csvData[$i]['imc_number'])) ? null : trim($csvData[$i]['imc_number']);
                }
                if($tender->save()){

                    $item_id = Item::where('item_name','=',trim($csvData[$i]['name_of_item']));
//                    if(isset($firstrow['imc_number'])){
//                        $item_id->where('imc_number','=',trim($csvData[$i]['imc_number']));
//                    }
                    $item_id = $item_id->value('id');

                    if(!empty($item_id)){

                        $itemtotender = new ItemToTender();

                        $itemtotender->tender_id = $tender_id;
                        $itemtotender->item_id = $item_id;
                        $itemtotender->quantity =  empty(trim($csvData[$i]['quantity'])) ? 0 : trim($csvData[$i]['quantity']);
                        $itemtotender->unit_price = empty(trim($csvData[$i]['unit_price'])) ? 0 : trim($csvData[$i]['unit_price']);
                        $itemtotender->discount_price = empty(trim($csvData[$i]['discount'])) ? 0 : trim($csvData[$i]['discount']);
                        //$itemtotender->total = empty(trim($csvData[$i]['total_amount'])) ? 0 : trim($csvData[$i]['total_amount']);
                        $itemtotender->total = trim($csvData[$i]['unit_price']) * trim($csvData[$i]['quantity']);
                        $itemtotender->currency_name = empty(trim($csvData[$i]['currency_code'])) ? 1 : trim($csvData[$i]['currency_code']);
                        $itemtotender->conversion = empty(trim($csvData[$i]['conversion_rate'])) ? 1 : trim($csvData[$i]['conversion_rate']);

                        $conversionRate = empty(trim($csvData[$i]['conversion_rate'])) ? 1 : trim($csvData[$i]['conversion_rate']);
                        $itemtotender->unit_price_in_bdt = trim($csvData[$i]['unit_price']);
                        $itemtotender->discount_price_in_bdt = trim($csvData[$i]['discount'])*$conversionRate;

                        $itemtotender->save();

                    }

                } // Save end

            }

        }

        if (count($csvData) > 0) {
            Session::flash('success', 'Supplier Based Purchase Created Successfully');
            return Redirect::to('excel/itemtotenders');
        } else {
            Session::flash('error', 'Supplier Based Purchase Creation Failed');
            return Redirect::to('excel/itemtotenders');
        }// End of Tender insert =============================================================
    } // End Tenders store;


}
