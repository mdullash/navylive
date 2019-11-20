<?php

namespace App\Http\Controllers;

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
use App\Http\Controllers\ImageResizeController;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

ini_set("pcre.backtrack_limit", "500000000000");
ini_set('max_execution_time', 0);
ini_set('memory_limit', -1);
error_reporting(0); 

class PrintController extends Controller
{

    private $tableAlies;
    private $printTitle;

    public function __construct() {
          $this->printTitle = \App\Settings::select('site_title')->first();
//        $this->tableAlies = \Session::get('zoneAlise');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // ===========================================================================================================================
    //===========================================================================================================================
    public function awardedSupplierListExcelDow(){

        $this->tableAlies = \Session::get('zoneAlise');

        $action = Input::get('action');

        $nsd_id = Input::get('nsd_id');
        $sup_id = Input::get('sup_id');
        $item_id = Input::get('item_id');
        $from   = Input::get('from');
        $to     = Input::get('to');
        $budget_cd_id = Input::get('budget_cd_id');
        $search_by_cat = Input::get('search_by_cat');

        $grand_total_select_filter = Input::get('grand_total_select_filter');
        $range_start = Input::get('range_start');
        $range_end = Input::get('range_end');

        if(!empty($from)){
            $from   = date('Y-m-d',strtotime(Input::get('from')));
        }
        if(!empty($to)){
            $to     = date('Y-m-d',strtotime(Input::get('to')));
        }

        // Nsd name ================================================================
        $nsdNames = NsdName::where('status_id','=',1)->get();
        $zonesRltdIds = array();
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
        $budget_codes =  \App\BudgetCode::where('status_id','=',1)->get();

        // Supplier name ============================================================

        $AllSuppliers = Supplier::where('status_id','=',1)->get();
        $zonesRltdIdsss = array();
        foreach($AllSuppliers as $spl){
            foreach(explode(',',$spl->registered_nsd_id) as $rni){

                if(in_array($rni, $zonesRltdIds)){
                    $zonesRltdIdsss[] = $spl->id;
                }

            }
        }
        $suppliersName  = Supplier::whereIn('id',$zonesRltdIdsss)->where('status_id','=',1)->get();

        $itemList = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.id')
            ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=',$this->tableAlies.'_itemtotender.item_id')
            ->select($this->tableAlies.'_items.id',$this->tableAlies.'_items.item_name')
            ->whereIn($this->tableAlies.'_tenders.nsd_id',$zonesRltdIds)->get();

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
            
        $suppliersrep = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.all_org_tender_id')
            ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.all_org_id','=',$this->tableAlies.'_tenders.supplier_id')
            ->join($this->tableAlies.'_items',$this->tableAlies.'_items.all_org_item_id','=',$this->tableAlies.'_itemtotender.item_id')
            ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
            ->join('multiple_currency',$this->tableAlies.'_itemtotender.currency_name','=','multiple_currency.id');
            if($search_by_cat!=2){
                    $suppliersrep->select($this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_suppliers.id as supplier_id', $this->tableAlies.'_tenders.nsd_id', $this->tableAlies.'_tenders.po_number', $this->tableAlies.'_tenders.tender_title', $this->tableAlies.'_items.imc_number', $this->tableAlies.'_items.item_name', $this->tableAlies.'_items.item_deno', $this->tableAlies.'_itemtotender.quantity', $this->tableAlies.'_itemtotender.unit_price', $this->tableAlies.'_itemtotender.discount_price', $this->tableAlies.'_itemtotender.total', 'deno.name as deno_name','multiple_currency.currency_name as curname', $this->tableAlies.'_tenders.tender_opening_date');
                }else{
                    $suppliersrep->select($this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_suppliers.id as supplier_id', $this->tableAlies.'_tenders.nsd_id', $this->tableAlies.'_tenders.po_number', $this->tableAlies.'_tenders.tender_title', $this->tableAlies.'_items.imc_number', $this->tableAlies.'_items.item_name', $this->tableAlies.'_items.item_deno', $this->tableAlies.'_itemtotender.quantity', $this->tableAlies.'_itemtotender.unit_price', 'deno.name as deno_name','multiple_currency.currency_name as curname', $this->tableAlies.'_tenders.tender_opening_date', \DB::raw('sum('.$this->tableAlies.'_itemtotender.total) total'), \DB::raw('sum('.$this->tableAlies.'_itemtotender.discount_price) discount_price'));
                }
        $suppliersrep->whereIn($this->tableAlies.'_tenders.nsd_id',$zonesRltdIds);
        if(!empty(Auth::user()->categories_id)){
            $suppliersrep->whereIn($this->tableAlies.'_tenders.tender_cat_id',explode(',', Auth::user()->categories_id));
        }        
        if(!empty($nsd_id)){
            $suppliersrep->where($this->tableAlies.'_tenders.nsd_id','=',$nsd_id);
        }
        if(!empty($sup_id)){
            // $suppliersrep->where($this->tableAlies.'_tenders.supplier_id','=',$sup_id);
            $suppliersrep->where($this->tableAlies.'_suppliers.id','=',$sup_id);
        }
        if(!empty($item_id)){
            $suppliersrep->where($this->tableAlies.'_items.id','=',$item_id);
        }
        if(!empty($budget_cd_id)){
            $suppliersrep->where($this->tableAlies.'_items.budget_code','=',$budget_cd_id);
        }
        if(!empty($from)){
            $suppliersrep->where(function($query) use ($from ){
                // $query->whereDate($this->tableAlies.'_tenders.work_order_date','>=',$from);
                $query->whereDate($this->tableAlies.'_tenders.tender_opening_date','>=',$from);
            });
        }
        if(!empty($to)){
            $suppliersrep->where(function($query) use ($to){
                // $query->whereDate($this->tableAlies.'_tenders.work_order_date','<=',$to);
                $query->whereDate($this->tableAlies.'_tenders.tender_opening_date','<=',$to);
            });
        }

// start range search ====================================
        $forforeach = $suppliersrep->get();
        $grandtotalSupplierExit ='';
        if(!empty($grand_total_select_filter)){

            // foreach ($forforeach as $value) {
            //     $grandTotal = $forforeach->where('company_name','=',$value->company_name)->sum('total');
                if($grand_total_select_filter==100){
                    if(!empty($range_start) || !empty($range_end)){
                        $a = empty($range_start) ? 0 : $range_start;
                        $b = empty($range_end) ? 0 : $range_end;

                        $grandtotalSupplierExit = array_map('current',\App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.all_org_tender_id')
                            ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.all_org_id','=',$this->tableAlies.'_tenders.supplier_id')
                            ->select($this->tableAlies.'_suppliers.id')
                            ->groupBy($this->tableAlies.'_tenders.supplier_id')
                            ->havingRaw('SUM(total) >= ?', [$a])
                            ->havingRaw('SUM(total) <= ?', [$b])
                            ->get()->toArray());

                        // if($grandTotal >= $a && $grandTotal <=$b){
                        //     $grandtotalSupplierExit[] = $value['supplier_id'];
                        // }
                    }
                }else{
                    $range = explode(',',$grand_total_select_filter);
                    $a = $range[0];
                    $b = $range[1];

                    $grandtotalSupplierExit = array_map('current',\App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.all_org_tender_id')
                            ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.all_org_id','=',$this->tableAlies.'_tenders.supplier_id')
                            ->select($this->tableAlies.'_suppliers.id')
                            ->groupBy($this->tableAlies.'_tenders.supplier_id')
                            ->havingRaw('SUM(total) >= ?', [$a])
                            ->havingRaw('SUM(total) <= ?', [$b])
                            ->get()->toArray());

                    // if($grandTotal >= $a && $grandTotal <=$b){
                    //     $grandtotalSupplierExit[] = $value['supplier_id'];
                    // }
                }

            //}

            $grandtotalSupplierExit = array_unique($grandtotalSupplierExit);
            $suppliersrep->whereIn($this->tableAlies.'_suppliers.id',$grandtotalSupplierExit);

            //echo "<pre>"; print_r($suppliersrep); exit;

        }// end of grnad total range search

        $suppliersrep->orderBy($this->tableAlies.'_suppliers.id','ASC')->orderBy($this->tableAlies.'_tenders.po_number', 'ASC');

        if($search_by_cat==2){
            $suppliersrep->groupBy($this->tableAlies.'_tenders.po_number');
        }
        $suppliersrep = $suppliersrep->get();

        $search_nsd_name = '';
        if(!empty($nsd_id)){
            $search_nsd_name = NsdName::find($nsd_id);;
        }
        $search_supplier_name = '';
        if(!empty($sup_id)){
            $search_supplier_name = Supplier::find($sup_id);;
        }
        $search_item_name = '';
        if(!empty($item_id)){
            $search_item_name = \App\Item::find($item_id);;
        }

        $data = [
            'suppliersrep' => $suppliersrep,
            'nsdNames' => $nsdNames,
            'nsd_id' => $nsd_id,
            'company_mobile' => $company_mobile,
            'from' => $from,
            'to' => $to,
            'search_nsd_name' => $search_nsd_name,
            'suppliersName' => $suppliersName,
            'search_supplier_name' => $search_supplier_name,
            'grand_total_select_filter' => $grand_total_select_filter,
            'range_start' => $range_start,
            'range_end' => $range_end,
            'itemList' => $itemList,
            'item_id' => $item_id,
            'budget_codes' => $budget_codes,
            'budget_cd_id' => $budget_cd_id,
            'search_item_name' => $search_item_name,
            'search_by_cat' => $search_by_cat
        ];

        
        if($action=='pdf'){
            //$pdf= PDF::loadView('reports.awarded-suppliers-list-pdf',compact('nsdNames','suppliersrep','nsd_id','from','to','search_nsd_name','suppliersName','sup_id','search_supplier_name','grand_total_select_filter','range_start','range_end','itemList','item_id','budget_codes','budget_cd_id','search_item_name','search_by_cat'));
            // $pdf->setPaper('letter', 'landscape');
            $pdf= PDF::loadView('reports.awarded-suppliers-list-pdf',$data,[],['format' => 'A4-L']);
            
            return $pdf->stream('awarded-suppliers-list.pdf');
            // return $pdf->download('requisition.pdf');

        }
// excel code=============================================================================================================
        //////////////////////////////////////////////////////////////////////////////

        Excel::create('Awarded-suppliers-list - ' . date("d-m-Y H:i"), function ($excel) use ($nsdNames, $suppliersrep, $nsd_id, $from, $to, $search_nsd_name, $suppliersName, $sup_id, $search_supplier_name, $grand_total_select_filter, $range_start, $range_end, $itemList, $item_id, $budget_codes, $budget_cd_id, $search_item_name, $search_by_cat ) {
            $excel->sheet('First Sheet', function ($sheet) use ($nsdNames, $suppliersrep, $nsd_id, $from, $to, $search_nsd_name, $suppliersName, $sup_id, $search_supplier_name, $grand_total_select_filter, $range_start, $range_end, $itemList, $item_id, $budget_codes, $budget_cd_id, $search_item_name, $search_by_cat) {
                $row = 0;

                //header Part Start
                $headerTxt = trim($this->printTitle->site_title);
                $row++;
                if($search_by_cat!=2){
                    $sheet->mergeCells('A' . $row . ':N' . $row);
                    $sheet->cells('A' . $row . ':N' . $row, function ($cell) {
                    $cell->setFontWeight('bold');
                    });
                }else{
                    $sheet->mergeCells('A' . $row . ':I' . $row);
                    $sheet->cells('A' . $row . ':I' . $row, function ($cell) {
                    $cell->setFontWeight('bold');
                    });
                }
                
                $sheet->cells('A' . $row, function ($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->row($row, array($headerTxt));

                $headerTxt2 = "STATISTIC OF SUPPLIERS PO ";
                 if(empty($nsd_id)){
                     $headerTxt2 .= "  (All Organizations)";
                 }else{
                     $headerTxt2 .= "(".$search_nsd_name->name.")";
                 }
                 if(empty($sup_id)){
                     $headerTxt2 .= " (All Suppliers)";
                 }else{
                     $headerTxt2 .= " (".$search_supplier_name->company_name.")";
                 }
                 if(!empty($from)){
                     $headerTxt2 .= " ".date('d M y',strtotime($from))."";
                 }else{
                     $headerTxt2 .= " Beginning";
                 }
                 if(!empty($to)){
                     $headerTxt2 .= " To "."".date('d M y',strtotime($to))."";
                 }else{
                     $headerTxt2 .= " To "."".date('d M y')."";
                 }

                $row++;
                $row++;
                if($search_by_cat!=2){
                    $sheet->mergeCells('A' . $row . ':N' . $row);
                    $sheet->cells('A' . $row . ':N' . $row, function ($cell) {
                        $cell->setFontWeight('bold');
                    });
                }else{
                    $sheet->mergeCells('A' . $row . ':I' . $row);
                    $sheet->cells('A' . $row . ':I' . $row, function ($cell) {
                        $cell->setFontWeight('bold');
                    });
                }
                
                $sheet->cells('A' . $row, function ($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->row($row, array($headerTxt2));

                $row++;
                $row++;
                //Report Name End

                if($search_by_cat!=2){
                    $sheet->cells('A' . $row . ':N' . $row, function ($cell) {
                    $cell->setFontWeight('bold')->setBackground('#AAAAFF');
                    });
                }else{
                    $sheet->cells('A' . $row . ':I' . $row, function ($cell) {
                    $cell->setFontWeight('bold')->setBackground('#AAAAFF');
                    });
                }
                
                //Table Header Start
                
                if($search_by_cat!=2){
                    $sheet->row($row, array(
                    'SL', 'NAME OF SUPPLIER', 'Tender Title', 'Date', 'PO / Contract No', 'Organization', 'IMC NO', 'ITEM NAME', 'DENO', 'QTY', 'UNIT PRICE', 'DISCOUNT AMOUNT', 'TOTAL AMOUNT', 'GRAND TOTAL'
                    ));
                }else{
                    $sheet->row($row, array(
                    'SL', 'NAME OF SUPPLIER', 'Tender Title', 'Date', 'PO / Contract No', 'Organization','DISCOUNT AMOUNT', 'TOTAL AMOUNT', 'GRAND TOTAL'
                    ));
                }
                


                //Table Header End
                if (!empty($suppliersrep)) {

                    $sl = 0;
                    $quantity = 0;
                    $total = 0;
                    $GrandtotalAll = 0;
                    $a = null;
                    $b = null;
                    $incb = 0;
                    $discount_in_type = 0;
                    $total_in_type = 0;

                    function supply_nsd_name($nsd_id=null){
                        $calName = \App\NsdName::where('id','=',$nsd_id)->value('name');
                        return $calName;
                    }

                    foreach ($suppliersrep as $sc) {

                        if($a != $sc->company_name){
                            $a = $sc->company_name;

                            $ab = $suppliersrep->where('company_name','=',$sc->company_name)->count();
                            $inc = 1;
                            $grandTotal = $suppliersrep->where('company_name','=',$sc->company_name)->sum('total');
                            $GrandtotalAll+=$grandTotal;

                        }



                        if($inc==1){
                            $fromRow = $row+1;
                            $toRow = $row+$ab;

                            $sheet->mergeCells('B' .$fromRow . ':B'.$toRow );
                            $sheet->cell('B' .$fromRow . ':B'.$toRow , function($cell) {
                                $cell->setValignment('center');
                            });
                            $sheet->mergeCells('A' .$fromRow . ':A'.$toRow );
                            $sheet->cell('A' .$fromRow . ':A'.$toRow , function($cell) {
                                $cell->setValignment('center');
                            });
                            $sheet->mergeCells('N' .$fromRow . ':N'.$toRow );
                            $sheet->cell('N' .$fromRow . ':N'.$toRow , function($cell) {
                                $cell->setValignment('center');
                            });

                        }

                        $row++;

                        $quantity += $sc->quantity;
                        $total += $sc->total;

                        if($search_by_cat!=2){
                            $sheet->row($row, array(
                                ($inc==1) ?  ++$sl : '', ($inc==1) ? $sc->company_name : '', $sc->tender_title,
                                date('d-m-Y',strtotime($sc->tender_opening_date)), $sc->po_number,
                                supply_nsd_name($sc->nsd_id), $sc->imc_number, $sc->item_name, $sc->deno_name,
                                ImageResizeController::custom_format($sc->quantity),
                                (!empty($sc->curname)) ? '('.$sc->curname.') '. ImageResizeController::custom_format($sc->unit_price) : '',
                                (!empty($sc->curname)) ? '('.$sc->curname.') '. ImageResizeController::custom_format($sc->discount_price) : '',
                                '(BDT) '.ImageResizeController::custom_format($sc->total),
                                ($inc==1) ?  '(BDT) '.ImageResizeController::custom_format($grandTotal) : '',

                            ));
                        }
                        
                        if($search_by_cat==2){
                            $sheet->row($row, array(
                                ($inc==1) ?  ++$sl : '', ($inc==1) ? $sc->company_name : '', $sc->tender_title,
                                date('d-m-Y',strtotime($sc->tender_opening_date)), $sc->po_number,
                                supply_nsd_name($sc->nsd_id),
                                (!empty($sc->curname)) ? '('.$sc->curname.') '. ImageResizeController::custom_format($sc->discount_price) : '',
                                '(BDT) '.ImageResizeController::custom_format($sc->total),
                                ($inc==1) ?  '(BDT) '.ImageResizeController::custom_format($grandTotal) : '',

                            ));
                        }    


                        if($inc==1){
                            $inc++ ;
                        }

                    }//foreach

                    $row++;
                    $row++;

                    if($search_by_cat!=2){

                        $sheet->mergeCells('A' .$row . ':I'.$row );
                        $sheet->cell('A' .$row . ':I'.$row , function($cell) {
                            $cell->setAlignment('center');
                        });

                        $sheet->cells('A' . $row . ':N' . $row, function ($cell) {
                            $cell->setFontWeight('bold');
                        });

                        $sheet->row($row, array(
                            'Total'
                        ));

                        $sheet->cell('J' .$row, function($cell) use($quantity) {
                            $cell->setValue($quantity);
                        });
                        $sheet->cell('M' .$row, function($cell) use($total) {
                            $cell->setValue('(BDT) '. ImageResizeController::custom_format($total));
                        });
                        $sheet->cell('N' .$row, function($cell) use($GrandtotalAll) {
                            $cell->setValue('(BDT) '.ImageResizeController::custom_format($GrandtotalAll));
                        });
                    }else{

                        $sheet->mergeCells('A' .$row . ':F'.$row );
                        $sheet->cell('A' .$row . ':F'.$row , function($cell) {
                            $cell->setAlignment('center');
                        });

                        $sheet->cells('A' . $row . ':I' . $row, function ($cell) {
                            $cell->setFontWeight('bold');
                        });

                        $sheet->row($row, array(
                            'Total'
                        ));

                        
                        $sheet->cell('H' .$row, function($cell) use($total) {
                            $cell->setValue('(BDT) '. ImageResizeController::custom_format($total));
                        });
                        $sheet->cell('I' .$row, function($cell) use($GrandtotalAll) {
                            $cell->setValue('(BDT) '.ImageResizeController::custom_format($GrandtotalAll));
                        });

                    }// end search_by_cat



                }// end if

            });
        })->export('xlsx');


// end excel ==============================================================================================
/////////////////////////////////////////////////////////////////////////////////////////

    }

    public function tenderParticipatesExcelDow(){
        $this->tableAlies = \Session::get('zoneAlise');

        $action = Input::get('action');

        $nsd_id = Input::get('nsd_id');
        $ten_title = Input::get('ten_title');
        $ten_number = Input::get('ten_number');
        $po_number = Input::get('po_number');

        $tenderTitlIds = array();
        $tenderNumIds  = array();

        if(!empty($ten_title)){
            $tenderInf = \App\Tender::find($ten_title);
            $tenderTitlIds = array_map('current',\App\Tender::select('id')->where('tender_number','=',$tenderInf->tender_number)->get()->toArray());
        }

        if(!empty($ten_number)){
            $tenderInf = \App\Tender::find($ten_number);
            $tenderNumIds = array_map('current',\App\Tender::select('id')->where('tender_number','=',$tenderInf->tender_number)->get()->toArray());
        }

        $nsdNames       = NsdName::where('status_id','=',1)->get();
        $zonesRltdIds = array();
        // Nsd name ================================================================
        $nsdNames = NsdName::where('status_id','=',1)->get();
        $zonesRltdIds = array();
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

        $tenderList = array('' => 'All') + \App\Tender::where('status_id','=',1)->whereIn('nsd_id',$zonesRltdIds)->pluck('tender_title','id')->toArray();
        $tender_numbers  = array('' => 'All') + \App\Tender::where('status_id','=',1)->where('tender_number','!=',null)->whereIn('nsd_id',$zonesRltdIds)->pluck('tender_number','id')->toArray();
        $po_numbers  = array('' => 'All') + \App\Tender::where('status_id','=',1)->where('po_number','!=',null)->whereIn('nsd_id',$zonesRltdIds)->pluck('po_number','id')->toArray();
// Search ===================================================================

            $suppliersrep = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.all_org_tender_id')
                ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.all_org_id','=',$this->tableAlies.'_tenders.supplier_id')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.all_org_item_id','=',$this->tableAlies.'_itemtotender.item_id')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->select($this->tableAlies.'_suppliers.company_name',$this->tableAlies.'_suppliers.id as supplier_id',$this->tableAlies.'_tenders.nsd_id',$this->tableAlies.'_tenders.po_number',$this->tableAlies.'_tenders.tender_title',$this->tableAlies.'_items.imc_number',$this->tableAlies.'_items.item_name',$this->tableAlies.'_items.item_deno',$this->tableAlies.'_itemtotender.quantity',$this->tableAlies.'_itemtotender.unit_price',$this->tableAlies.'_itemtotender.discount_price',$this->tableAlies.'_itemtotender.total','deno.name as deno_name');
            $suppliersrep->whereIn($this->tableAlies.'_tenders.nsd_id',$zonesRltdIds);
            if(!empty(Auth::user()->categories_id)){
                $suppliersrep->whereIn($this->tableAlies.'_tenders.tender_cat_id',explode(',', Auth::user()->categories_id));
            }    
            if(!empty($nsd_id)){
                $suppliersrep->where($this->tableAlies.'_tenders.nsd_id','=',$nsd_id);
            }
            if(!empty($ten_title)){
                $suppliersrep->whereIn($this->tableAlies.'_tenders.id',$tenderTitlIds);
            }
            if(!empty($ten_number)){
                $suppliersrep->whereIn($this->tableAlies.'_tenders.id',$tenderNumIds);
            }
            if(!empty($po_number)){
                $suppliersrep->where($this->tableAlies.'_tenders.id','=',$po_number);
            }
            $suppliersrep->orderBy($this->tableAlies.'_suppliers.id','ASC');
            $suppliersrep = $suppliersrep->get();



// End search ===============================================================

        $search_nsd_name = '';
        if(!empty($nsd_id)){
            $search_nsd_name = NsdName::find($nsd_id);;
        }
        $serchTenderName = '';
        if(!empty($ten_title)){
            $serchTenderName = \App\Tender::find($ten_title);;
        }
        $serchTenderNumber = '';
        if(!empty($ten_number)){
            $serchTenderNumber = \App\Tender::find($ten_number);;
        }
        $serchTenderPo = '';
        if(!empty($po_number)){
            $serchTenderPo = \App\Tender::find($po_number);;
        }

        $data = [
            'nsdNames' => $nsdNames,
            'nsd_id' => $nsd_id,
            'search_nsd_name' => $search_nsd_name,
            'tenderList' => $tenderList,
            'ten_title' => $ten_title,
            'suppliersrep' => $suppliersrep,
            'ten_number' => $ten_number,
            'po_number' => $po_number,
            'tender_numbers' => $tender_numbers,
            'po_numbers' => $po_numbers,
            'serchTenderName' => $serchTenderName,
            'serchTenderNumber' => $serchTenderNumber,
            'serchTenderPo' => $serchTenderPo
        ];

        if($action=='pdf'){
            //$pdf= PDF::loadView('reports.tender-participates-pdf',compact('nsdNames','nsd_id','search_nsd_name','tenderList','ten_title','suppliersrep','ten_number','po_number','tender_numbers','po_numbers','serchTenderName','serchTenderNumber','serchTenderPo'));
            //$pdf->setPaper('letter', 'landscape');

            $pdf= PDF::loadView('reports.tender-participates-pdf',$data,[],['format' => 'A4-L']);
            
            return $pdf->stream('tender-participates-list-pdf.pdf');
            // return $pdf->download('requisition.pdf');

        }

        // excel code=============================================================================================================
        //////////////////////////////////////////////////////////////////////////////

        Excel::create('Tender-participates-list - ' . date("d-m-Y H:i"), function ($excel) use ($nsdNames, $nsd_id, $search_nsd_name, $tenderList, $ten_title, $suppliersrep,$ten_number,$po_number,$tender_numbers,$po_numbers,$serchTenderName,$serchTenderNumber,$serchTenderPo) {
            $excel->sheet('First Sheet', function ($sheet) use ($nsdNames, $nsd_id, $search_nsd_name, $tenderList, $ten_title, $suppliersrep,$ten_number,$po_number,$tender_numbers,$po_numbers,$serchTenderName,$serchTenderNumber,$serchTenderPo) {
                $row = 0;

                //header Part Start
                $headerTxt = trim($this->printTitle->site_title);
                $row++;
                $sheet->mergeCells('A' . $row . ':H' . $row);
                $sheet->cells('A' . $row . ':H' . $row, function ($cell) {
                    $cell->setFontWeight('bold');
                });
                $sheet->cells('A' . $row, function ($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->row($row, array($headerTxt));
                $headerTxt2 = "AWARDED SUPPLIERS LIST ";
                if(empty($nsd_id)){
                    $headerTxt2 .= "(All Organizations)";
                }else{
                    $headerTxt2 .= "(".$search_nsd_name->name.")";
                }
                if(!empty($ten_title)){
                    $headerTxt2 .= "Tender Name (".$ten_title.")";
                }else{
                    $headerTxt2 .= "(All Tender)";
                }

                $row++;
                $row++;
                $sheet->mergeCells('A' . $row . ':H' . $row);
                $sheet->cells('A' . $row . ':H' . $row, function ($cell) {
                    $cell->setFontWeight('bold');
                });
                $sheet->cells('A' . $row, function ($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->row($row, array($headerTxt2));

                $row++;
                $row++;
                //Report Name End



                $sheet->cells('A' . $row . ':H' . $row, function ($cell) {
                    $cell->setFontWeight('bold')->setBackground('#AAAAFF');
                });
                //Table Header Start
                $sheet->row($row, array(
                    'SL', 'Name Of Supplier', 'Tender Title', 'Organization', 'IMC No', 'Item Name',
                    'DENO', 'Quantity'
                ));


                //Table Header End
                //Table Header End
                if (count($suppliersrep)>0) {

                    $sl = 0;
                    $quantity = 0;
                    $total = 0;
                    $GrandtotalAll = 0;
                    $a = null;

                    function supply_nsd_name($nsd_id=null){
                        $calName = \App\NsdName::where('id','=',$nsd_id)->value('name');
                        return $calName;
                    }

                    foreach ($suppliersrep as $sc) {

                        if($a != $sc->company_name){
                            $a = $sc->company_name;

                            $ab = $suppliersrep->where('company_name','=',$sc->company_name)->count();
                            $inc = 1;
                            $grandTotal = $suppliersrep->where('company_name','=',$sc->company_name)->sum('total');
                            $GrandtotalAll+=$grandTotal;

                        }

                        if($inc==1){
                            $fromRow = $row+1;
                            $toRow = $row+$ab;

                            $sheet->mergeCells('B' .$fromRow . ':B'.$toRow );
                            $sheet->cell('B' .$fromRow . ':B'.$toRow , function($cell) {
                                $cell->setValignment('center');
                            });
                            $sheet->mergeCells('A' .$fromRow . ':A'.$toRow );
                            $sheet->cell('A' .$fromRow . ':A'.$toRow , function($cell) {
                                $cell->setValignment('center');
                            });

                        }

                        $row++;

                        $quantity += $sc->quantity;
                        $total += $sc->total;

                        $sheet->row($row, array(
                            ($inc==1) ?  ++$sl : '', ($inc==1) ? $sc->company_name : '', $sc->tender_title,supply_nsd_name($sc->nsd_id),
                            $sc->imc_number, $sc->item_name, $sc->deno_name,
                            ImageResizeController::custom_format($sc->quantity),

                        ));

                        if($inc==1){
                            $inc++ ;
                        }

                    }//foreach

                    $row++;
                    $row++;
                    $sheet->mergeCells('A' .$row . ':G'.$row );
                    $sheet->cell('A' .$row . ':G'.$row , function($cell) {
                        $cell->setAlignment('center');
                    });

                    $sheet->cells('A' . $row . ':H' . $row, function ($cell) {
                        $cell->setFontWeight('bold');
                    });

                    $sheet->row($row, array(
                        'Total'
                    ));

                    $sheet->cell('H' .$row, function($cell) use($quantity) {
                        $cell->setValue($quantity);
                    });


                }// end if



            });
        })->export('xlsx');


// end excel ==============================================================================================
/////////////////////////////////////////////////////////////////////////////////////////

    }

    public function catProSupplierListExcelDow(Request $request){

        $this->tableAlies = \Session::get('zoneAlise');

        $action = Input::get('action');

        $cat_id   = Input::get('cat_id');
        $item_id  = Input::get('item_id');
        $nsd_id   = Input::get('nsd_id');

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

        $AllSuppliers = Supplier::where('status_id','=',1)->get();
        $zonesRltdIdsss = array();
        foreach($AllSuppliers as $spl){
            foreach(explode(',',$spl->registered_nsd_id) as $rni){
                if(in_array($rni, $zonesRltdIds)){
                    $zonesRltdIdsss[] = $spl->id;
                }
            }
        }

        $supplyCategories = SupplyCategory::where('status_id','=',1)->get();
        foreach($supplyCategories as $ctg){
            if(in_array(Session::get('zoneId'), explode(',', $ctg->zones))){
                $zonesRltdCtgIds[] = $ctg->id;
            }
        }
        $suppliercategories = SupplyCategory::whereIn('id',$zonesRltdCtgIds)->where('status_id','=',1)->get();

        // Iteam searching ===============================
        $allItemIdsUnderNsd = '';
        $allItemIdsUnderNsdArray = array();
        $nsdids = \App\Item::select('id','nsd_id')->where('status_id','=',1)->get();
        foreach ($nsdids as $ni) {
            if (!empty(Session::get('nsdBsdEmptyOrNot')) && in_array(Session::get('zoneId'),explode(',',Auth::user()->zones))) {
                foreach (explode(',', Session::get('nsdBsdEmptyOrNot')) as $val) {
                    if (in_array($val, explode(',', $ni->nsd_id))) {
                        $allItemIdsUnderNsdArray[] = $ni->id;
                    }
                }
            } // If end
            $allItemIdsUnderNsd = array_unique($allItemIdsUnderNsdArray);
        }// End foreach
        if(!empty($allItemIdsUnderNsd)){
            $productsnames = \App\Item::whereIn('id',$allItemIdsUnderNsd)->where('status_id','=',1)->get();
        }else{
            $nsdids = \App\Item::select('id','nsd_id')->where('status_id','=',1)->get();
            $productsnames = \App\Item::all();
        }


        $catsupplier = array();
        $itemsupplier= array();
        $itemsnsdupplier= array();
        $suppliers = Supplier::orderBy('id');

        if(!empty($cat_id) || !empty($item_id) || !empty($nsd_id)){

            if(!empty($cat_id)){
                $catsupplierss = Supplier::select('id','supply_cat_id')->where('status_id','=',1)->get();
                $values = [];
                foreach ($catsupplierss as $catsup) {
                    foreach(explode(',', $catsup->supply_cat_id) as $value) {
                        if($value==$cat_id){
                            $values[] = $catsup->id;
                        }

                    }
                }
                $catsupplier = array_unique($values);
                //echo "<pre>"; print_r($catsupplier); exit;
            }

            if(!empty($item_id)){
                // add newly 
                $extraWork = Item::find($item_id);
                $item_idss = '';
                if(!empty($extraWork)){
                    $item_idss = $extraWork->all_org_item_id;
                } // end add newly
                //$items = array_map('current',ItemToTender::select('tender_id')->where('item_id','=',$item_id)->get()->toArray());
                $items = array_map('current',ItemToTender::select('tender_id')->where('item_id','=',$item_idss)->get()->toArray()); // add newly
                if(!empty($items)){
                    $itemss = Tender::whereIn('id',$items)->get();
                    $valuesss = [];
                    foreach ($itemss as $catsup) {
                        // $valuesss[] = $catsup->supplier_id;
                        // add newly
                        $s = explode("_",$catsup->supplier_id);
                        $valuesss[] = end($s); // end add newly
                    }
                    $itemsupplier = array_unique($valuesss);
                }

            }

            if(!empty($nsd_id)){
                $itemsnsdsupplier = Supplier::select('id','registered_nsd_id')->where('status_id','=',1)->get();
                $valuessss = [];
                foreach ($itemsnsdsupplier as $ins) {
                    foreach(explode(',', $ins->registered_nsd_id) as $value) {
                        if($value==$nsd_id){
                            $valuessss[] = $ins->id;
                        }
                    }
                }
                $itemsnsdupplier = array_unique($valuessss);

            }

            $single_array = array_unique(array_merge($catsupplier,$itemsupplier,$itemsnsdupplier));
            // $itemsupplier = array_unique($valuesss);

            $suppliers = Supplier::whereIn('id',$single_array)->whereIn('id',$zonesRltdIdsss)->get();

        }else{ 
            $suppliers = $suppliers->whereIn('id',$zonesRltdIdsss)->get();
        }

        $searchItemName = '';
        if(!empty($item_id)){
            $searchItemName = \App\Item::find($item_id);;
        }

        //return View::make('reports.category-wise-supplier',compact('suppliercategories','productsnames','cat_id','item_id','suppliers','nsdNames','nsd_id','searchItemName'));
        
        $data = [
            'suppliercategories' => $suppliercategories,
            'productsnames' => $productsnames,
            'cat_id' => $cat_id,
            'item_id' => $item_id,
            'suppliers' => $suppliers,
            'nsdNames' => $nsdNames,
            'nsd_id' => $nsd_id,
            'searchItemName' => $searchItemName
        ];

        if($action=='pdf'){
            //$pdf= PDF::loadView('reports.category-wise-supplier-pdf',compact('suppliercategories','productsnames','cat_id','item_id','suppliers','nsdNames','nsd_id','searchItemName'));
            //$pdf->setPaper('letter', 'landscape');
            
            $pdf= PDF::loadView('reports.category-wise-supplier-pdf',$data,[],['format' => 'A4-L']);
            return $pdf->stream('category-wise-supplier-pdf.pdf');
            // return $pdf->download('requisition.pdf');

        }

        // excel code=============================================================================================================
        //////////////////////////////////////////////////////////////////////////////

        Excel::create('Category-and-item-wise-supplier-report - ' . date("d-m-Y H:i"), function ($excel) use ($suppliercategories, $productsnames, $cat_id, $item_id, $suppliers, $nsdNames,$nsd_id, $searchItemName ) {
            $excel->sheet('First Sheet', function ($sheet) use ($suppliercategories, $productsnames, $cat_id, $item_id, $suppliers, $nsdNames,$nsd_id, $searchItemName) {
                $row = 0;

                //header Part Start
                $headerTxt = trim($this->printTitle->site_title);
                $row++;
                $sheet->mergeCells('A' . $row . ':G' . $row);
                $sheet->cells('A' . $row . ':G' . $row, function ($cell) {
                    $cell->setFontWeight('bold');
                });
                $sheet->cells('A' . $row, function ($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->row($row, array($headerTxt));
                $headerTxt2 = "Category & Item Wise Supplier (".date('d M y').')';

                $row++;
                $row++;
                $sheet->mergeCells('A' . $row . ':G' . $row);
                $sheet->cells('A' . $row . ':G' . $row, function ($cell) {
                    $cell->setFontWeight('bold');
                });
                $sheet->cells('A' . $row, function ($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->row($row, array($headerTxt2));

                $row++;
                $row++;
                //Report Name End

                $sheet->cells('A' . $row . ':G' . $row, function ($cell) {
                    $cell->setFontWeight('bold')->setBackground('#AAAAFF');
                });
                //Table Header Start
                $sheet->row($row, array(
                    'SL', 'Company Name', 'Company Registration No.', 'Mobile Number', 'Supply Category	',
                    'Organization', 'TIN Number'
                ));


                //Table Header End
                if (!empty($suppliers)) {
                    $sl = 0;

                    function supply_cat_name($cat_id=null){
                        $calName = \App\SupplyCategory::where('id','=',$cat_id)->value('name');
                        return $calName;
                    }

                    function supply_nsd_name($nsd_id=null){
                        $calName = \App\NsdName::where('id','=',$nsd_id)->value('name');
                        return $calName;
                    }

                    foreach ($suppliers as $sc) {

                        $calList = '';
                        $catids = explode(',',$sc->supply_cat_id);
                        foreach ($catids as $ctds) {
                            $valsss = supply_cat_name($ctds);
                            $calList .= $valsss."\n";
                        }

                        $nsdList = '';
                        $nsdids = explode(',',$sc->registered_nsd_id);
                        foreach ($nsdids as $nsd) {
                            $valssss = supply_nsd_name($nsd);
                            $nsdList .= $valssss."\n";
                        }

                        $row++;
                        $sheet->row($row, array(
                            ++$sl, $sc->company_name, $sc->company_regi_number_nsd, $sc->mobile_number, $calList,
                            $nsdList, $sc->tin_number
                        ));
                        $calList = '';
                        $nsdList = '';


                    }//foreach


                }// end if

            });
        })->export('xlsx');


// end excel ==============================================================================================
/////////////////////////////////////////////////////////////////////////////////////////

    }

    public function supplierReport(){

        $this->tableAlies = \Session::get('zoneAlise');

        $action = Input::get('action');

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

        $nsdNames = NsdName::where('status_id','=',1)->get();
        $zonesRltdIds = array();
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

        $AllSuppliers = Supplier::where('status_id','=',1)->get();
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
                        //$zonesRltdIdsss[] = $spl->id;
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
                        // $zonesRltdIdsss[] = $spl->id;
                    }
                }

            }
        }

        $suppliers = Supplier::where('status_id','=',1);

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
        $suppliers = $suppliers->whereIn('id',$zonesRltdIdsss)->get();

        $data = [
            'suppliers' => $suppliers,
            'nsdNames' => $nsdNames,
            'nsd_id' => $nsd_id,
            'company_mobile' => $company_mobile,
            'from' => $from,
            'to' => $to
        ];

        if($action=='pdf'){
            //$pdf= PDF::loadView('reports.suppliers-nsd-wise-pdf',compact('suppliers','nsdNames','nsd_id','company_mobile','from','to'));
            $pdf= PDF::loadView('reports.suppliers-nsd-wise-pdf', $data);
            return $pdf->stream('suppliers-list-pdf.pdf');
            // return $pdf->download('requisition.pdf');

        }
        // excel code=============================================================================================================
        //////////////////////////////////////////////////////////////////////////////

        Excel::create('Suppliers-list - ' . date("d-m-Y H:i"), function ($excel) use ($suppliers, $nsdNames, $nsd_id, $company_mobile, $from, $to) {
            $excel->sheet('First Sheet', function ($sheet) use ($suppliers, $nsdNames, $nsd_id, $company_mobile, $from, $to) {
                $row = 0;

                //header Part Start
                $headerTxt = trim($this->printTitle->site_title);
                $row++;
                $sheet->mergeCells('A' . $row . ':H' . $row);
                $sheet->cells('A' . $row . ':H' . $row, function ($cell) {
                    $cell->setFontWeight('bold');
                });
                $sheet->cells('A' . $row, function ($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->row($row, array($headerTxt));
                $headerTxt2 = "Supplier List ( ".date('d M y h:i A').' )';

                $row++;
                $row++;
                $sheet->mergeCells('A' . $row . ':H' . $row);
                $sheet->cells('A' . $row . ':H' . $row, function ($cell) {
                    $cell->setFontWeight('bold');
                });
                $sheet->cells('A' . $row, function ($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->row($row, array($headerTxt2));

                $row++;
                $row++;
                //Report Name End

                $sheet->cells('A' . $row . ':H' . $row, function ($cell) {
                    $cell->setFontWeight('bold')->setBackground('#AAAAFF');
                });
                //Table Header Start
                $sheet->row($row, array(
                    'SL', 'Company Name', 'Company Registration No.', 'Mobile Number', 'Supply Category	', 'TIN Number',
                    'Trade License Number', 'Organization'
                ));


                //Table Header End
                if (!empty($suppliers)) {
                    $sl = 0;

                    function supply_cat_name($cat_id=null){
                        $calName = \App\SupplyCategory::where('id','=',$cat_id)->value('name');
                        return $calName;
                    }

                    function supply_nsd_name($nsd_id=null){
                        $calName = \App\NsdName::where('id','=',$nsd_id)->value('name');
                        return $calName;
                    }

                    foreach ($suppliers as $sc) {

                        $calList = '';
                        $catids = explode(',',$sc->supply_cat_id);
                        foreach ($catids as $ctds) {
                            $valsss = supply_cat_name($ctds);
                            $calList .= $valsss."\n";
                        }

                        $nsdList = '';
                        $nsdids = explode(',',$sc->registered_nsd_id);
                        foreach ($nsdids as $nsd) {
                            $valssss = supply_nsd_name($nsd);
                            $nsdList .= $valssss."\n";
                        }

                        $row++;
                        $sheet->row($row, array(
                            ++$sl, $sc->company_name, $sc->company_regi_number_nsd, $sc->mobile_number, $calList,
                            $sc->tin_number, $sc->trade_license_number, $nsdList
                        ));
                        $calList = '';
                        $nsdList = '';


                    }//foreach


                }// end if

            });
        })->export('xlsx');


// end excel ==============================================================================================
/////////////////////////////////////////////////////////////////////////////////////////

    }

    // Budget code wise report ==============================================================================
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    public function budgetCodeWiseItemExcelDow(){ 

        $this->tableAlies = \Session::get('zoneAlise');

        $action = Input::get('action');

        $nsd_id = Input::get('nsd_id');
        $item_id = Input::get('item_id');
        $from   = Input::get('from');
        $to     = Input::get('to');
        $budget_cd_id = Input::get('budget_cd_id');

        if(!empty($from)){
            $from   = date('Y-m-d',strtotime(Input::get('from')));
        }
        if(!empty($to)){
            $to     = date('Y-m-d',strtotime(Input::get('to')));
        }

        // Nsd name ================================================================
        $nsdNames = NsdName::where('status_id','=',1)->get();
        $zonesRltdIds = array();
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
        $budget_codes =  \App\BudgetCode::where('status_id','=',1)->get();

        // Supplier name ============================================================

        $AllSuppliers = Supplier::where('status_id','=',1)->get();
        $zonesRltdIdsss = array();
        foreach($AllSuppliers as $spl){
            foreach(explode(',',$spl->registered_nsd_id) as $rni){

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
                    // $zonesRltdIdsss[] = $spl->id;
                }

            }
        }

        $itemList = \DB::table($this->tableAlies.'_tenders')->join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.all_org_tender_id')
            ->join($this->tableAlies.'_items',$this->tableAlies.'_items.all_org_item_id','=',$this->tableAlies.'_itemtotender.item_id')
            ->select($this->tableAlies.'_items.id',$this->tableAlies.'_items.item_name', $this->tableAlies.'_itemtotender.item_id')
            ->whereIn($this->tableAlies.'_tenders.nsd_id',$zonesRltdIds)->get();

        $itemList = $itemList->unique();

        $suppliersrep = \App\Tender::join($this->tableAlies.'_itemtotender', $this->tableAlies.'_itemtotender.tender_id', '=', $this->tableAlies.'_tenders.all_org_tender_id')
                ->join($this->tableAlies.'_items',$this->tableAlies.'_items.all_org_item_id','=',$this->tableAlies.'_itemtotender.item_id')
                ->join('budget_codes','budget_codes.id','=',$this->tableAlies.'_items.budget_code')
                ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
                ->join('multiple_currency',$this->tableAlies.'_itemtotender.currency_name','=','multiple_currency.id')
                ->select($this->tableAlies.'_tenders.nsd_id','budget_codes.code',$this->tableAlies.'_items.imc_number', $this->tableAlies.'_items.item_name', $this->tableAlies.'_items.item_deno', $this->tableAlies.'_items.budget_code', $this->tableAlies.'_itemtotender.quantity', $this->tableAlies.'_itemtotender.unit_price', $this->tableAlies.'_itemtotender.discount_price', $this->tableAlies.'_itemtotender.total', 'deno.name as deno_name','multiple_currency.currency_name as curname');
            $suppliersrep->whereIn($this->tableAlies.'_tenders.nsd_id',$zonesRltdIds);
            if(!empty(Auth::user()->categories_id)){
                $suppliersrep->whereIn($this->tableAlies.'_tenders.tender_cat_id',explode(',', Auth::user()->categories_id));
            }    
            if(!empty($nsd_id)){
                $suppliersrep->where($this->tableAlies.'_tenders.nsd_id','=',$nsd_id);
            }
            if(!empty($item_id)){
                $suppliersrep->where($this->tableAlies.'_items.id','=',$item_id);
            }
            if(!empty($budget_cd_id)){
                $suppliersrep->where($this->tableAlies.'_items.budget_code','=',$budget_cd_id);
            }
            if(!empty($from)){
                $suppliersrep->where(function($query) use ($from ){
                    //$query->whereDate($this->tableAlies.'_tenders.work_order_date','>=',$from);
                    $query->whereDate($this->tableAlies.'_tenders.tender_opening_date','>=',$from);
                });
            }
            if(!empty($to)){
                $suppliersrep->where(function($query) use ($to){
                    //$query->whereDate($this->tableAlies.'_tenders.work_order_date','<=',$to);
                    $query->whereDate($this->tableAlies.'_tenders.tender_opening_date','<=',$to);
                });
            }

            $suppliersrep->orderBy('budget_codes.id','ASC');
            $suppliersrep = $suppliersrep->get();


        $search_nsd_name = '';
        if(!empty($nsd_id)){
            $search_nsd_name = NsdName::find($nsd_id);;
        }
        $search_item_name = '';
        if(!empty($item_id)){
            $search_item_name = \App\Item::find($item_id);;
        }

        //return View::make('reports.budget-colde-wise-item-list',compact('nsdNames','suppliersrep','nsd_id','from','to','search_nsd_name','search_item_name','itemList','item_id','budget_codes','budget_cd_id','preGrandTotal','preQuantity'));

        $data = [
            'nsdNames' => $nsdNames,
            'suppliersrep' => $suppliersrep,
            'nsd_id' => $nsd_id,
            'from' => $from,
            'to' => $to,
            'search_nsd_name' => $search_nsd_name,
            'search_item_name' => $search_item_name,
            'itemList' => $itemList,
            'item_id' => $item_id,
            'budget_codes' => $budget_codes,
            'budget_cd_id' => $budget_cd_id,
            'preGrandTotal' => $preGrandTotal,
            'preQuantity' => $preQuantity
        ];

        if($action=='pdf'){ 
            // $pdf= PDF::loadView('reports.budget-colde-wise-item-list-pdf',compact('nsdNames','suppliersrep','nsd_id','from','to','search_nsd_name','search_item_name','itemList','item_id','budget_codes','budget_cd_id','preGrandTotal','preQuantity'));
            // $pdf->setPaper('letter', 'landscape'); 

            $pdf= PDF::loadView('reports.budget-colde-wise-item-list-pdf',$data,[],['format' => 'A4-L']);
            return $pdf->stream('suppliers-list-pdf.pdf');
            // return $pdf->download('requisition.pdf');

        }

        // excel code=============================================================================================================
        //////////////////////////////////////////////////////////////////////////////

        Excel::create('Budget-code-wise-items - ' . date("d-m-Y H:i"), function ($excel) use ($nsdNames, $suppliersrep, $nsd_id, $from, $to, $search_nsd_name, $itemList, $item_id, $budget_codes, $budget_cd_id, $search_item_name) {
            $excel->sheet('First Sheet', function ($sheet) use ($nsdNames, $suppliersrep, $nsd_id, $from, $to, $search_nsd_name, $itemList, $item_id, $budget_codes, $budget_cd_id, $search_item_name) {
                $row = 0;

                //header Part Start
                $headerTxt = trim($this->printTitle->site_title);
                $row++;
                $sheet->mergeCells('A' . $row . ':K' . $row);
                $sheet->cells('A' . $row . ':K' . $row, function ($cell) {
                    $cell->setFontWeight('bold');
                });
                $sheet->cells('A' . $row, function ($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->row($row, array($headerTxt));

                $headerTxt2 = "STATISTIC OF SUPPLIERS PO ";
                if(empty($nsd_id)){
                    $headerTxt2 .= "  (All Organizations)";
                }else{
                    $headerTxt2 .= "(".$search_nsd_name->name.")";
                }
                if(empty($item_id)){
                    $headerTxt2 .= " (All Item)";
                }else{
                    $headerTxt2 .= " (".$search_item_name->item_name.")";
                }
                if(!empty($from)){
                    $headerTxt2 .= " ".date('d M y',strtotime($from))."";
                }else{
                    $headerTxt2 .= " Beginning";
                }
                if(!empty($to)){
                    $headerTxt2 .= " To "."".date('d M y',strtotime($to))."";
                }else{
                    $headerTxt2 .= " To "."".date('d M y')."";
                }

                $row++;
                $row++;
                $sheet->mergeCells('A' . $row . ':K' . $row);
                $sheet->cells('A' . $row . ':K' . $row, function ($cell) {
                    $cell->setFontWeight('bold');
                });
                $sheet->cells('A' . $row, function ($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->row($row, array($headerTxt2));

                $row++;
                $row++;
                //Report Name End

                $sheet->cells('A' . $row . ':K' . $row, function ($cell) {
                    $cell->setFontWeight('bold')->setBackground('#AAAAFF');
                });
                //Table Header Start
                $sheet->row($row, array(
                    'SL', 'Budget Code', 'Organization', 'IMC NO', 'ITEM NAME', 'DENO', 'QTY', 'UNIT PRICE', 'DISCOUNT AMOUNT','TOTAL AMOUNT', 'GRAND TOTAL'
                ));


                //Table Header End
                if (count($suppliersrep)>0) {

                    $sl = 0;
                    $quantity = 0;
                    $total = 0;
                    $GrandtotalAll = 0;
                    $a = null;

                    function supply_nsd_name($nsd_id=null){
                        $calName = \App\NsdName::where('id','=',$nsd_id)->value('name');
                        return $calName;
                    }

                    foreach ($suppliersrep as $sc) {

                        if($a != $sc->budget_code){
                            $a = $sc->budget_code;

                            $ab = $suppliersrep->where('budget_code','=',$sc->budget_code)->count();
                            $inc = 1;
                            $grandTotal = $suppliersrep->where('budget_code','=',$sc->budget_code)->sum('total');
                            $GrandtotalAll+=$grandTotal;

                        }

                        if($inc==1){
                            $fromRow = $row+1;
                            $toRow = $row+$ab;

                            $sheet->mergeCells('B' .$fromRow . ':B'.$toRow );
                            $sheet->cell('B' .$fromRow . ':B'.$toRow , function($cell) {
                                $cell->setValignment('center');
                            });
                            $sheet->mergeCells('A' .$fromRow . ':A'.$toRow );
                            $sheet->cell('A' .$fromRow . ':A'.$toRow , function($cell) {
                                $cell->setValignment('center');
                            });
                            $sheet->mergeCells('K' .$fromRow . ':K'.$toRow );
                            $sheet->cell('K' .$fromRow . ':K'.$toRow , function($cell) {
                                $cell->setValignment('center');
                            });

                        }

                        $row++;
//                        $sheet->cells('H' . $row . ':K' . $row, function ($cell) {
//                            $cell->setAlignment('right');
//                        });

                        $quantity += $sc->quantity;
                        $total += $sc->total;

                        $sheet->row($row, array(
                            ($inc==1) ?  ++$sl : '', ($inc==1) ? $sc->code : '', supply_nsd_name($sc->nsd_id),
                            $sc->imc_number, $sc->item_name, $sc->deno_name,
                            ImageResizeController::custom_format($sc->quantity),
                            (!empty($sc->curname)) ? '('.$sc->curname.') '. ImageResizeController::custom_format($sc->unit_price) : '',
                            (!empty($sc->curname)) ? '('.$sc->curname.') '. ImageResizeController::custom_format($sc->discount_price) : '',
                            '(BDT) '.ImageResizeController::custom_format($sc->total),
                            ($inc==1) ?  '(BDT) '.ImageResizeController::custom_format($grandTotal) : '',

                        ));

                        if($inc==1){
                            $inc++ ;
                        }

                    }//foreach

                    $row++;
                    $row++;
                    $sheet->mergeCells('A' .$row . ':F'.$row );
                    $sheet->cell('A' .$row . ':F'.$row , function($cell) {
                        $cell->setAlignment('center');
                    });

                    $sheet->cells('A' . $row . ':K' . $row, function ($cell) {
                        $cell->setFontWeight('bold');
                    });

                    $sheet->row($row, array(
                        'Total'
                    ));

                    $sheet->cell('G' .$row, function($cell) use($quantity) {
                        $cell->setValue($quantity);
                    });
                    $sheet->cell('J' .$row, function($cell) use($total) {
                        $cell->setValue('(BDT) '. ImageResizeController::custom_format($total));
                    });
                    $sheet->cell('K' .$row, function($cell) use($GrandtotalAll) {
                        $cell->setValue('(BDT) '.ImageResizeController::custom_format($GrandtotalAll));
                    });

                }// end if

            });
        })->export('xlsx');


// end excel ==============================================================================================
/////////////////////////////////////////////////////////////////////////////////////////

    }


    public function nsdWiseSupplier(Request $request){

        $nsdwisesupplier = \App\Supplier::select('company_name','id')->where('registered_nsd_id', $request->nsd_id)->where('status_id','=',1)->get();

        $nsdwisesupplierAppend = '<option value="">All</option>';

        foreach($nsdwisesupplier as $ae){
            $nsdwisesupplierAppend .='<option value="'.$ae->id.'">'.$ae->company_name.'</option>';
        }

        $data['nsdwisesupplier'] = $nsdwisesupplierAppend;

        return $data;

    }

    public function nsdWiseTender(Request $request){

        $nsdwisetender = \App\Tender::select('tender_title','id')->where('nsd_id', $request->nsd_id)->where('status_id','=',1)->get();

        $nsdwiseTenderAppend = '<option value="">All</option>';

        foreach($nsdwisetender as $ae){
            $nsdwiseTenderAppend .='<option value="'.$ae->id.'">'.$ae->tender_title.'</option>';
        }

        $data['nsdwisetender'] = $nsdwiseTenderAppend;

        return $data;

    }

    public function categoryWiseItems(Request $request){

        $catwiseitem = \App\Item::select('item_name','id')->where('item_cat_id', $request->cat_id)->where('status_id','=',1)->get();

        $nsdwiseTenderAppend = '<option value="">All</option>';

        foreach($catwiseitem as $ae){
            $nsdwiseTenderAppend .='<option value="'.$ae->id.'">'.$ae->item_name.'</option>';
        }

        $data['catwiseitems'] = $nsdwiseTenderAppend;

        return $data;

    }



}


 