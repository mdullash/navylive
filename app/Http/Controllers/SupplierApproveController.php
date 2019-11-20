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

class SupplierApproveController extends Controller
{
    private $moduleId = 48;


    public function index($status)
    {

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

        $AllSuppliers = Supplier::whereNotNull('status_id')->get();

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

                    }
                }

            }
        }

        $suppliers = Supplier::whereIn('id',$zonesRltdIdsss)->whereNotNull('status_id');

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
                $query->whereDate('created_at','>=',$from);
            });
        }
        if(!empty($to)){
            $suppliers->where(function($query) use ($to){
                $query->whereDate('created_at','<=',$to);
            });
        }


        $suppliers = $suppliers->where('waiting_for_approve','pending')->orderBy('id','DESC')->get();

        return View::make('suppliers.waiting-for-approve.index')->with(compact('suppliers','nsdNames','nsd_id','company_mobile','from','to','status'));

    }


    public function create(){
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

        $AllSuppliers = Supplier::whereNotNull('status_id')->get();

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

                    }
                }

            }
        }

        $suppliers = Supplier::whereIn('id',$zonesRltdIdsss)->whereNotNull('status_id')->where('waiting_for_approve','pending')->get();

        return View::make('suppliers.waiting-for-approve.create')->with(compact('suppliers','nsdNames','nsd_id'));

    }


    public function store(Request $request){

        $request->validate([
            'date' => 'required',
            'letter_no' => 'required',
            'encloser' => 'required',
            'info' => 'required',
        ]);
        try{
            $approve_supplier=New SupplierApproved();
            $approve_supplier->date =$request->date;
            $approve_supplier->letter_no =$request->letter_no;
            $approve_supplier->info =$request->info;
            $approve_supplier->encloser =$request->encloser;
            $approve_supplier->status ='waiting-for-approve';
            if($approve_supplier->save()){
                for($i=0;$i<count($request->suppliers);$i++){
                    $approve_supplier_info=New SupplierInfo();
                    $approve_supplier_info->approve_id=$approve_supplier->id;
                    $approve_supplier_info->supplier_id=$request->suppliers[$i];
                    $approve_supplier_info->save();
                    $supplier=Supplier::find($request->suppliers[$i]);
                    $supplier->waiting_for_approve='approved';
                    $supplier->save();
                }
            }
            Session::flash('success', 'Supplier Approved Successfully');
            return Redirect::to('suppliers/waiting-for-clarence/waiting-for-approve');
        }catch (\Exception $e){

            Session::flash('error', $e);
            return Redirect::to('suppliers/waiting-for-clarence/index/pending');

        }
    }



    public function waitingForApproved($status){
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


            $approved_supplier=$approved_supplier->where('status',$status)->orderBy('id','DESC')->paginate(10);

            $data['suppliers']=$approved_supplier;

            return View::make('suppliers.waiting-for-approve.approved',$data)->with(compact('from','to','letter_no'));

        }catch (\Exception $exception){

            return \redirect()->back();
        }
    }


    public function approve($id){
        try{
            $supplier = SupplierApproved::find($id);
            $supplier->dni_status ='pending';
            $supplier->npm_status ='pending';
            $supplier->status ='approved';
            $supplier->save();
            Session::flash('success', 'Successfully Approved');
            return \redirect('suppliers/waiting-for-clarence/approved');
        }catch (\Exception $e){
            Session::flash('error', $e);
            return \redirect('suppliers/waiting-for-clarence/waiting-for-approve');
        }
    }


    public function view($id){

        try{
            $supplier=SupplierApproved::where('id',$id)->first();


            $data['suppliers']=$supplier;

            return View::make('suppliers.waiting-for-approve.view',$data);
        }catch (\Exception $exception){
            return \redirect()->back();
        }
    }


}
