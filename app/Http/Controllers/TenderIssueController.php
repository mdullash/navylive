<?php

namespace App\Http\Controllers;

use App\D44BData;
use App\IssueDatas;
use App\ItemToDemand;
use App\User;
use PDF;
use Illuminate\Http\Request;

class TenderIssueController extends Controller
{
    public function view($id){

        $this->tableAlies = \Session::get('zoneAlise');

        $inspectionInfo = \App\DemandCrToInspection::find($id);
        $dmnd_po_to_cr_id = $inspectionInfo->dmnd_po_to_cr_id;
        $insId = $id;
        $valuesFi = \App\DemandPoToCr::find($dmnd_po_to_cr_id);
        $poDtsId = $valuesFi->po_id;
        $poDatasInfo = \App\PoDatas::find($poDtsId);
        $tenderId = $poDatasInfo->tender_id;

        $colQutId   = \App\DemandToCollectionQuotation::find($poDatasInfo->selected_supplier);

        $supplierNames = \App\Supplier::find($colQutId->supplier_name);
        $supplierName = '';
        if(!empty($supplierNames)){
            $supplierName = $supplierNames->company_name;
        }

        $qyeryResutl = \App\DemandCrToItem::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.id', '=', 'demand_cr_to_item.coll_qut_tim_id')

            ->join('item_to_demand','item_to_demand.id', '=', 'demand_supplier_to_coll_qut_to_item.item_id')
            ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
            ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
            ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
            ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
            ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName','item_to_demand.id as ItemId','demand_supplier_to_coll_qut_to_item.id as dmndtosupcotId','demand_cr_to_item.cr_receive_qty as demand_cr_to_item_cr_receive_qty','demand_cr_to_item.inspection_sta as demand_cr_to_item_inspection_sta','demand_cr_to_item.id as demand_cr_to_item_id','demand_cr_to_item.inspection_com as inspection_com_sksks')
            ->where('demand_cr_to_item.dmn_po_to_cr_id','=',$dmnd_po_to_cr_id);

        $qyeryResutl = $qyeryResutl->get();

        $supplyCategories = \App\SupplyCategory::where('status_id','=',1)->get();

        $demandeNames = \App\DemandeName::where('status','=',1)->get();
        $issue_datas=IssueDatas::where('d44b_datas_id',$id)->first();

        $issued_by=User::where('role_id',5)->where('status_id',1)->get();
        $recevied=User::where('role_id',5)->where('status_id',1)->get();
        $approved=User::where('role_id',5)->where('status_id',1)->get();

        $today = date("Ymd");
        $passNoStart = "0000";
        $issueCount = IssueDatas::count();

        $passNo = $today.$passNoStart+$issueCount;

        return view('issue.issue-view')->with(compact('issued_by','recevied','approved','supplyCategories','demandeNames','qyeryResutl','inspectionInfo','insId','valuesFi','poDtsId','tenderId','supplierName','id','issue_datas','passNo'));

    }



    public function waiting_for_issue(Request $request,$id){

        $request->validate([
            'gate_pass_no' => 'required',
            'date' => 'required',
            'group_id' => 'required',
            'demanding_id' => 'required',
            'issue_by' => 'required',
        ]);
        try{

            $issue_datas=IssueDatas::where('d44b_datas_id',$id)->first();
            if ($issue_datas==null){
            $issue_datas=New IssueDatas();
            }

            $issue_datas->issue_by=$request->issue_by;
            $issue_datas->gate_pass_no=$request->gate_pass_no;
            $issue_datas->date=$request->date;
            $issue_datas->group_id=$request->group_id;
            $issue_datas->demanding_id=$request->demanding_id;
            $issue_datas->d44b_datas_id=$id;



            //received_by
            $issue_datas->received_by=$request->received_by;
            $issue_datas->received_rank=$request->received_rank;
            $issue_datas->received_opno=$request->received_opno;
            $issue_datas->received_address=$request->received_address;



            $issue_datas->ref=$request->ref;
            $issue_datas->status=1;
            if($issue_datas->save()){
                $d44b=D44BData::where('id',$id)->first();
                $d44b->issue_status=1;
                $d44b->save();
            }

            return redirect('issue/2');

        }catch (\Exception $e){

            return redirect()->back();
        }


    }


    public function issuePdfView($id){
        $expdata  = explode('&', $id);
        $id = $expdata[0];
        $conditio = $expdata[1];

        $this->tableAlies = \Session::get('zoneAlise');

        $inspectionInfo = \App\DemandCrToInspection::find($id);
        $dmnd_po_to_cr_id = $inspectionInfo->dmnd_po_to_cr_id;
        $insId = $id;
        $valuesFi = \App\DemandPoToCr::find($dmnd_po_to_cr_id);
        $poDtsId = $valuesFi->po_id;
        $poDatasInfo = \App\PoDatas::find($poDtsId);
        $tenderId = $poDatasInfo->tender_id;

        $colQutId   = \App\DemandToCollectionQuotation::find($poDatasInfo->selected_supplier);

        $supplierNames = \App\Supplier::find($colQutId->supplier_name);
        $supplierName = '';
        if(!empty($supplierNames)){
            $supplierName = $supplierNames->company_name;
        }

        $qyeryResutl = \App\DemandCrToItem::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.id', '=', 'demand_cr_to_item.coll_qut_tim_id')

            ->join('item_to_demand','item_to_demand.id', '=', 'demand_supplier_to_coll_qut_to_item.item_id')
            ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
            ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
            ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
            ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
            ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName','item_to_demand.id as ItemId','demand_supplier_to_coll_qut_to_item.id as dmndtosupcotId','demand_cr_to_item.cr_receive_qty as demand_cr_to_item_cr_receive_qty','demand_cr_to_item.inspection_sta as demand_cr_to_item_inspection_sta','demand_cr_to_item.id as demand_cr_to_item_id','demand_cr_to_item.inspection_com as inspection_com_sksks')
            ->where('demand_cr_to_item.dmn_po_to_cr_id','=',$dmnd_po_to_cr_id);

        $qyeryResutl = $qyeryResutl->get();

        $supplyCategories = \App\SupplyCategory::where('status_id','=',1)->get();

        $demandeNames = \App\DemandeName::where('status','=',1)->get();
        $issue_datas = \App\IssueDatas::where('d44b_datas_id',$id)->with('issuedName','approvedName','groupName','demanding')->first();
        if($conditio==1){
            if ($issue_datas->issue_by==1){
                return view('issue.issue-specification-view')->with(compact('supplyCategories','demandeNames','qyeryResutl','inspectionInfo','insId','valuesFi','poDtsId','tenderId','supplierName','id','issue_datas'));

            }else{
                return view('issue.issue-specification-view-fns')->with(compact('supplyCategories','demandeNames','qyeryResutl','inspectionInfo','insId','valuesFi','poDtsId','tenderId','supplierName','id','issue_datas'));


            }

        }


        if ($issue_datas->issue_by==1){
            $pdf= PDF::loadView('issue.issue-specification-pdf',compact('supplyCategories','demandeNames','qyeryResutl','inspectionInfo','insId','valuesFi','poDtsId','tenderId','supplierName','id','issue_datas'),['format' => [215.9, 342.9]]);
            return $pdf->stream('ift.pdf');
        }else{
            $pdf= PDF::loadView('issue.issue-specification-pdf-fns',compact('supplyCategories','demandeNames','qyeryResutl','inspectionInfo','insId','valuesFi','poDtsId','tenderId','supplierName','id','issue_datas'),['format' => [215.9, 342.9]]);
            return $pdf->stream('ift.pdf');
        }

    }



    public function issue_voucher($id){
        try{
            $issue_voucher=IssueDatas::find($id);
            $issue_voucher->issue_date=date('Y-m-d');
            //issue
            $issue_voucher->issued_by=auth()->user()->id;
            $issue_voucher->issued_rank=auth()->user()->rank;
            $issue_voucher->status=2;
            $issue_voucher->save();
            return redirect('issue/2');
        }catch (\Exception $e){

            return redirect()->back();
        }
    }

    public function approve_voucher($id){
        try{
            $issue_voucher=IssueDatas::find($id);
            $issue_voucher->approve_date=date('Y-m-d');
            //approve_by
            $issue_voucher->approve_by=auth()->user()->id;
            $issue_voucher->approved_rank=auth()->user()->rank;

            $issue_voucher->status=3;
            $issue_voucher->save();

            foreach ($this->tender_items($issue_voucher->d44b_datas_id) as $item){
                $item_to_demand=ItemToDemand::where(['tender_no'=>$item->tender_id,'item_id'=>$item->item_id])->first();

                $item_to_demand->po_approved_quantity=$item->quantity;
                $item_to_demand->isuue_id=$id;
               $item_to_demand->save();
            }

            return redirect('issue/3');
        }catch (\Exception $e){

            return redirect()->back();
        }
    }


    public function reject($id){
        try{
            $issue_voucher=IssueDatas::find($id);
            $issue_voucher->status=4;
            $issue_voucher->save();
            return redirect('issue/3');
        }catch (\Exception $e){

            return redirect()->back();
        }
    }



    public function tender_items($id){
        $this->tableAlies = \Session::get('zoneAlise');

        $inspectionInfo = \App\DemandCrToInspection::find($id);
        $dmnd_po_to_cr_id = $inspectionInfo->dmnd_po_to_cr_id;
        $insId = $id;
        $valuesFi = \App\DemandPoToCr::find($dmnd_po_to_cr_id);
        $poDtsId = $valuesFi->po_id;
        $poDatasInfo = \App\PoDatas::find($poDtsId);
        $tenderId = $poDatasInfo->tender_id;

        $colQutId   = \App\DemandToCollectionQuotation::find($poDatasInfo->selected_supplier);

        $supplierNames = \App\Supplier::find($colQutId->supplier_name);
        $supplierName = '';
        if(!empty($supplierNames)){
            $supplierName = $supplierNames->company_name;
        }

        $qyeryResutl = \App\DemandCrToItem::join('demand_supplier_to_coll_qut_to_item','demand_supplier_to_coll_qut_to_item.id', '=', 'demand_cr_to_item.coll_qut_tim_id')

            ->join('item_to_demand','item_to_demand.id', '=', 'demand_supplier_to_coll_qut_to_item.item_id')
            ->join('demand_to_collection_quotation','demand_to_collection_quotation.id','=','demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id')
            ->join($this->tableAlies.'_suppliers',$this->tableAlies.'_suppliers.id','=','demand_to_collection_quotation.supplier_name')
            ->join($this->tableAlies.'_items',$this->tableAlies.'_items.id','=','item_to_demand.item_id')
            ->join('deno','deno.id','=',$this->tableAlies.'_items.item_deno')
            ->select('item_to_demand.*','demand_supplier_to_coll_qut_to_item.*','demand_to_collection_quotation.suppliernametext',$this->tableAlies.'_items.manufacturing_country','deno.name as denoName','item_to_demand.id as ItemId','demand_supplier_to_coll_qut_to_item.id as dmndtosupcotId','demand_cr_to_item.cr_receive_qty as demand_cr_to_item_cr_receive_qty','demand_cr_to_item.inspection_sta as demand_cr_to_item_inspection_sta','demand_cr_to_item.id as demand_cr_to_item_id','demand_cr_to_item.inspection_com as inspection_com_sksks','demand_supplier_to_coll_qut_to_item.tender_id as tender_id')
            ->where('demand_cr_to_item.dmn_po_to_cr_id','=',$dmnd_po_to_cr_id);

        $qyeryResutl = $qyeryResutl->get();

        return $qyeryResutl;
    }
}
