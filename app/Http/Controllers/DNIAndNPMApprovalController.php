<?php

namespace App\Http\Controllers;

use App\DNINPMSupplierApproval;
use App\DNINPMSupplierApprovalInfo;
use App\SupplierApproved;
use App\SupplierInfo;
use App\SupplierMultiInfo;
use Illuminate\Http\Request;
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

class DNIAndNPMApprovalController extends Controller
{
    public function index(){

        $this->tableAlies = \Session::get('zoneAlise');


        $from   = Input::get('from');
        $to     = Input::get('to');
        $letter_no     = Input::get('letter_no');
        $supplier_id     = Input::get('supplier_id');
        $supplier_name     = Input::get('supplier_name');

        try{
            $approved_supplier=New SupplierInfo();

            if (!empty($from) && !empty($to)){
                $approved_supplier=$approved_supplier->whereBetween('created_at',[$from,$to]);
            }
            if (!empty($letter_no)){
                $approved_supplier=$approved_supplier->where('letter_no','like',"%$letter_no%");
            }

            if (!empty($supplier_id)){
                $approved_supplier=$approved_supplier->where('supplier_id',$supplier_id);
            }

            $approved_supplier=$approved_supplier
                ->join($this->tableAlies.'_supplier_approved',$this->tableAlies.'_supplier_approved.id','=',$this->tableAlies.'_supplier_approval_info.approve_id')
                ->where([$this->tableAlies.'_supplier_approved.dni_status'=>'approved',$this->tableAlies.'_supplier_approved.npm_status'=>'approved'])
                ->where([$this->tableAlies.'_supplier_approval_info.dni_approval_status'=>'pending',$this->tableAlies.'_supplier_approval_info.npm_approval_status'=>'pending'])
                ->orderBy($this->tableAlies.'_supplier_approval_info.id','DESC');

            if(!empty($letter_no)){
                $approved_supplier->where($this->tableAlies.'_supplier_approved.letter_no','like',"%$letter_no%");
            }

            $data['suppliers']=$approved_supplier->get();

            return View::make('suppliers.npmDniApproval.index',$data)->with(compact('from','to','letter_no','supplier_name'));

        }catch (\Exception $exception){

            return \redirect()->back();
        }
    }



    public function store(Request $request){

        $request->validate([
            'date' => 'required',
            'letter_no' => 'required',
            'encloser' => 'required',
            'info' => 'required',
        ]);
        try{

            $approve_supplier=New DNINPMSupplierApproval();
            $approve_supplier->date =$request->date;
            $approve_supplier->letter_no =$request->letter_no;
            $approve_supplier->info =$request->info;
            $approve_supplier->encloser =$request->encloser;
            $approve_supplier->status ='waiting-for-approve';
            if($approve_supplier->save()){
                for($i=0;$i<count($request->suppliers);$i++){
                    $approve_supplier_info=New DNINPMSupplierApprovalInfo();
                    $approve_supplier_info->supplier_approval_id=$request->approve_id[$i];
                    $approve_supplier_info->approve_id=$approve_supplier->id;
                    $approve_supplier_info->supplier_id=$request->suppliers[$i];
                    $approve_supplier_info->status=null;
                    $approve_supplier_info->save();
                    $supApInfo=SupplierInfo::where('approve_id',$request->approve_id[$i])->where('supplier_id',$request->suppliers[$i])->first();

                    $supApInfo->dni_approval_status='approved';
                    $supApInfo->npm_approval_status='approved';
                    $supApInfo->save();

                }
            }
            Session::flash('success', 'Approved Successfully');
            return Redirect::to('suppliers/dni-npm-approval/waiting-for-approve');
        }catch (\Exception $e){

            Session::flash('error', $e);

            return Redirect::to('suppliers/dni-npm-approval');

        }
    }



    public function waitingForApproved($status){
        $from   = Input::get('from');
        $to     = Input::get('to');
        $letter_no     = Input::get('letter_no');

        try{

            $approved_supplier=New DNINPMSupplierApproval();
            if (!empty($from) && !empty($to)){
                $approved_supplier=$approved_supplier->whereBetween('created_at',[$from,$to]);
            }
            if (!empty($letter_no)){
                $approved_supplier=$approved_supplier->where('letter_no','like',"%$letter_no%");
            }


            $approved_supplier=$approved_supplier->where('status',$status)->orderBy('id','DESC')->paginate(10);

            $data['suppliers']=$approved_supplier;

            return View::make('suppliers.npmDniApproval.approved',$data)->with(compact('from','to','letter_no'));

        }catch (\Exception $exception){

            return \redirect()->back();
        }
    }



    public function view($id){

        $this->tableAlies = \Session::get('zoneAlise');


        $from   = Input::get('from');
        $to     = Input::get('to');

        try{
            $approved_supplier=New SupplierApproved();

            if (!empty($from) && !empty($to)){
                $approved_supplier=$approved_supplier->whereBetween('created_at',[$from,$to]);
            }

            $approved_supplier=$approved_supplier
                ->join($this->tableAlies.'_supplier_npm_dni_approval_info',$this->tableAlies.'_supplier_npm_dni_approval_info.supplier_approval_id','=',$this->tableAlies.'_supplier_approved.id')
                ->where([$this->tableAlies.'_supplier_approved.dni_status'=>'approved',$this->tableAlies.'_supplier_approved.npm_status'=>'approved'])
                ->where($this->tableAlies.'_supplier_npm_dni_approval_info.approve_id',$id)
                ->orderBy($this->tableAlies.'_supplier_npm_dni_approval_info.id','DESC');

               $data['suppliers']=$approved_supplier->get();
               $data['dni_npm_approval']=DNINPMSupplierApproval::find($id);

        return View::make('suppliers.npmDniApproval.view',$data)->with(compact('from','to'));
        }catch (\Exception $exception){

            return \redirect()->back();
        }
    }


    public function approve($id){
        try{
            $supplier = DNINPMSupplierApproval::find($id);
            $supplier->status ='approved';
            $supplier->save();
            Session::flash('success', 'Successfully Approved');
            return \redirect('suppliers/dni-npm-approval/approved');
        }catch (\Exception $e){
            Session::flash('error', $e);
            return \redirect('suppliers/dni-npm-approval/waiting-for-approve');
        }
    }


    public function update(Request $request,$id){
        $request->validate([
            'date' => 'required',
            'letter_no' => 'required',
            'encloser' => 'required',
            'info' => 'required',
        ]);
        try{
            $approve_supplier=DNINPMSupplierApproval::find($id);
            $approve_supplier->date =$request->date;
            $approve_supplier->letter_no =$request->letter_no;
            $approve_supplier->info =$request->info;
            $approve_supplier->encloser =$request->encloser;
            $approve_supplier->status ='waiting-for-approve';
            if($approve_supplier->save()){
                foreach(DNINPMSupplierApprovalInfo::where('approve_id',$id)->get() as $dniNpm) {
                    $supApInfo = SupplierInfo::where('approve_id', $dniNpm->supplier_approval_id)->where('supplier_id', $dniNpm->supplier_id)->first();
                    $supApInfo->dni_approval_status = 'pending';
                    $supApInfo->npm_approval_status = 'pending';
                    $supApInfo->save();
                }
                DNINPMSupplierApprovalInfo::where('approve_id',$id)->delete();
                for($i=0;$i<count($request->suppliers);$i++){
                    $approve_supplier_info=New DNINPMSupplierApprovalInfo();
                    $approve_supplier_info->supplier_approval_id=$request->approve_id[$i];
                    $approve_supplier_info->approve_id=$id;
                    $approve_supplier_info->supplier_id=$request->suppliers[$i];
                    $approve_supplier_info->save();
                    $supApInfo=SupplierInfo::where('approve_id',$request->approve_id[$i])->where('supplier_id',$request->suppliers[$i])->first();
                    $supApInfo->dni_approval_status='approved';
                    $supApInfo->npm_approval_status='approved';
                    $supApInfo->save();
                }
            }
            Session::flash('success', 'Approved Successfully');
            return Redirect::to('suppliers/dni-npm-approval/'.$id.'/view');
        }catch (\Exception $e){

            Session::flash('error', $e);

            return Redirect::to('suppliers/dni-npm-approval/'.$id.'/view');

        }
    }


}
