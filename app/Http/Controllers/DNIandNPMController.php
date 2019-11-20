<?php

namespace App\Http\Controllers;

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

class DNIandNPMController extends Controller
{
    private $moduleId = 49;
    private $moduleId2 = 50;


    public function dni($status){
        $from   = Input::get('from');
        $to     = Input::get('to');
        $letter_no     = Input::get('letter_no');

        try{
            $approved_supplier=New SupplierApproved();

            if (!empty($from) && !empty($to)){
                $approved_supplier=$approved_supplier->whereBetween('created_at',[$from,$to]);
            }

            if (!empty($letter_no)){
                $approved_supplier=$approved_supplier->where('letter_no','like',"%$letter_no%");
            }




            $approved_supplier=$approved_supplier->orderBy('id','DESC')->where('dni_status',$status)->paginate(10);

            $data['suppliers']=$approved_supplier;

            return View::make('suppliers.dni.index',$data)->with(compact('from','to','status','letter_no'));

        }catch (\Exception $exception){
            return \redirect()->back();
        }
    }


    public function dni_create($id){
        try{
            $supplier=SupplierApproved::where('id',$id)->first();


            $data['suppliers']=$supplier;

            return View::make('suppliers.dni.clearance',$data);
        }catch (\Exception $exception){
            return \redirect()->back();
        }
    }

    public function dni_store(Request $request,$id){
      try{
          $dni_approve=SupplierApproved::find($id);
          $dni_approve->dni_status='waiting-for-approve';
          $dni_approve->save();
          $dni_store=SupplierInfo::where('approve_id',$id)->get();
          foreach ($dni_store as $store){
              $st=SupplierInfo::where('approve_id',$id)->where('supplier_id',$store->supplier_id)->first();
              $st->dni_description=$request->supplier[$store->supplier_id];
              $st->save();
          }
          Session::flash('success', 'Supplier Approved Successfully');
          return \redirect('suppliers/dni/index/waiting-for-approve');
      }catch (\Exception $e){
          Session::flash('error', $e);
          return \redirect()->back();
      }
    }

    public function dni_approved($id){
        try{
            $supplier = SupplierApproved::find($id);
            $supplier->dni_status ='approved';
            $supplier->save();
            $dni_store=SupplierInfo::where('approve_id',$supplier->id)->get();
            foreach ($dni_store as $store){
                $st=SupplierInfo::where('approve_id',$supplier->id)->where('supplier_id',$store->supplier_id)->first();
                $st->dni_approval_status='pending';
                $st->save();
            }
            Session::flash('success', 'Successfully Approved');
            return \redirect('suppliers/dni/index/approved');
        }catch (\Exception $e){
            Session::flash('error', $e);
            return \redirect('suppliers/dni/index/waiting-for-approve');
        }
    }

    public function npm($status){
        $from   = Input::get('from');
        $to     = Input::get('to');
        $letter_no     = Input::get('letter_no');

        try{

            $approved_supplier=New SupplierApproved();

            if (!empty($from) && !empty($to)){
                $approved_supplier=$approved_supplier->whereBetween('created_at',[$from,$to]);
            }

            if (!empty($letter_no)){
                $approved_supplier=$approved_supplier->where('letter_no','like',"%$letter_no%");
            }

            $approved_supplier=$approved_supplier->where('npm_status',$status)->orderBy('id','DESC')->paginate(10);

            $data['suppliers']=$approved_supplier;

            return View::make('suppliers.npm.index',$data)->with(compact('from','to','status','letter_no'));

        }catch (\Exception $exception){
            return \redirect()->back();
        }
    }


    public function npm_create($id){
        try{
            $supplier=SupplierApproved::where('id',$id)->first();


            $data['suppliers']=$supplier;

            return View::make('suppliers.npm.clearance',$data);
        }catch (\Exception $exception){
            return \redirect()->back();
        }
    }


    public function npm_store(Request $request,$id){
        try{
            $npm_approve=SupplierApproved::find($id);
            $npm_approve->npm_status='waiting-for-approve';
            $npm_approve->save();
            $npm_store=SupplierInfo::where('approve_id',$id)->get();

            foreach ($npm_store as $store){
                $st=SupplierInfo::where('approve_id',$id)->where('supplier_id',$store->supplier_id)->first();
                $st->npm_description=$request->supplier[$store->supplier_id];
                $st->save();
            }
            Session::flash('success', 'Supplier Approved Successfully');
            return \redirect('suppliers/npm/index/waiting-for-approve');
        }catch (\Exception $e){
            Session::flash('error', $e);
            return \redirect()->back();
        }
    }


    public function npm_approved($id){
        try{

            $supplier = SupplierApproved::find($id);
            $supplier->npm_status ='approved';
            $supplier->save();
            $npm_store=SupplierInfo::where('approve_id',$supplier->id)->get();
            foreach ($npm_store as $store){
                $st=SupplierInfo::where('approve_id',$supplier->id)->where('supplier_id',$store->supplier_id)->first();
                $st->npm_approval_status='pending';
                $st->save();
            }

            Session::flash('success', 'Successfully Approved');
            return \redirect('suppliers/npm/index/approved');
        }catch (\Exception $e){
            Session::flash('error', $e);

            return \redirect('suppliers/npm/index/waiting-for-approve');
        }
    }

}
