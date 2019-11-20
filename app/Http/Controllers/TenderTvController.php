<?php

namespace App\Http\Controllers;

use App\NsdName;
use App\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenderTvController extends Controller
{
   public function index(){

    try{
        $data=[];
        $navalLocation = NsdName::where('status_id','=',1)->where('default_selected','=',1)->orderBy('id')->first();
        $zoneInfo = Zone::where('id','=',$navalLocation->zones)->where('status','=',1)->orderBy('id')->first();
        $table_alise = $zoneInfo->alise;
        $data['opening_tenders'] = DB::table($table_alise.'_tenders')
            ->whereDate('valid_date_from', '<=',date('Y-m-d'))
            ->whereDate('tender_opening_date', '=',date('Y-m-d'))
            ->where('status_id','=',1)
            ->whereNotNull('notice')
            ->orderBy('id', 'desc')->get();
        foreach ($data['opening_tenders'] as $a){
            $a->deno = '';
            if ($a->quantity ==null){
                $a->quantity = '';
                $itmToTndrInfo = DB::table('item_to_demand')->where('tender_no','=',$a->id)->first();
                $itmToTndrInfo_quantity = DB::table('item_to_demand')->where('tender_no','=',$a->id)->count();
                if(!empty($itmToTndrInfo)){
                    $itm = DB::table($zoneInfo->alise.'_items')->find($itmToTndrInfo->item_id);
                    $dno = DB::table('deno')->where('id','=',$itm->item_deno)->value('name');
                    if($itmToTndrInfo_quantity==1){
                        $a->quantity = $itmToTndrInfo_quantity;
                        $a->deno = $dno;

                    }else{
                        $a->quantity = $itmToTndrInfo_quantity .' Line Item';

                    }

                }

            }
        }

        $data['po'] = \App\PoDatas::join('demand_to_lpr', 'demand_to_lpr.id', '=', 'po_datas.lpr_id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->select('po_datas.*')
            ->where('po_datas.po_approve_status','=',1)
            ->whereRaw('DATE(po_datas.po_approve_date) = DATE_SUB(CURDATE(), INTERVAL 7 DAY)')
            ->orderBy('po_datas.id', 'desc')
            ->limit(7)->get();
        foreach ($data['po'] as $po){
            $tender=DB::table($table_alise.'_tenders')->where('id',$po->tender_id)->first();
            $po->tender_title=$tender->tender_title;
        }



        $data['billing']  =  DB::table('d44b_datas')->join('demand_cr_to_inspection', 'demand_cr_to_inspection.id', '=', 'd44b_datas.inspecttion_id')
            ->join('bill_forwarding', 'bill_forwarding.tender_id', '=', 'demand_cr_to_inspection.tender_id')
            ->leftJoin('demand_po_to_cr', 'demand_po_to_cr.id', '=', 'demand_cr_to_inspection.dmnd_po_to_cr_id')
            ->leftJoin('po_datas', 'po_datas.id', '=', 'demand_po_to_cr.po_id')
            ->leftJoin('demand_to_lpr', 'po_datas.lpr_id', '=', 'demand_to_lpr.id')
            ->leftJoin('demande_name', 'demande_name.id', '=', 'demand_to_lpr.requester')
            ->leftJoin($table_alise.'_tenders',$table_alise.'_tenders.id', '=', 'demand_to_lpr.tender_id')
            ->leftjoin('demand_to_collection_quotation','demand_to_collection_quotation.id', '=', 'po_datas.selected_supplier')
            ->leftjoin($table_alise.'_suppliers',$table_alise.'_suppliers.id', '=', 'demand_to_collection_quotation.supplier_name')
            ->select('po_datas.*',$table_alise.'_suppliers.company_name',$table_alise.'_tenders.tender_title', 'd44b_datas.id', 'demand_po_to_cr.cr_receive_qty', 'demand_po_to_cr.cr_number','bill_forwarding.id as billId')
            ->whereNotNull('d44b_datas.status')
            ->where('bill_forwarding.status','=',2)
            ->whereRaw('DATE(bill_forwarding.bill_forwarding_date) = DATE_SUB(CURDATE(), INTERVAL 7 DAY)')
            ->orderBy('demand_po_to_cr.id', 'desc')
            ->orderBy('bill_forwarding.id', 'desc')->limit(7)->get();
        return view('tv.index',$data);
    }catch (\Exception $e){
         
        echo 'Something went wrong please contact with your software Engineer.';
    }

   }
}
