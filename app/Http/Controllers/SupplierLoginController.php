<?php

namespace App\Http\Controllers;

use App\Notice;
use App\NsdName;
use App\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SupplierLoginController extends Controller
{
    public function login(){

        $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->take(5)->orderBy('id','desc')->get();
        $navalLocation = NsdName::where('status_id','=',1)->where('default_selected','=',1)->orderBy('id')->first();

        $noticeIds = array();
        foreach ($data['notices'] as $ntc){
            if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                $noticeIds[] = $ntc->id;
            }
        }

        $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->whereIn('id',$noticeIds)->orderBy('id','desc')->get();
        $data['navallocation'] = $navalLocation;
        $zones=Zone::where('id',$navalLocation->zones)->first();
        Session::put('zoneAlise',$zones->alise);
        return view('frontend.supplier-login',$data);
    }


    public function submit(Request $request){
        // validate the data

        $this->validate($request, [
            'email'         => 'required',
            'password'      => 'required'
        ]);

        $supplier = array(
            'email' => $request->email,
            'password' => $request->password,
        );

        if (Auth::guard('supplier')->attempt($supplier)) {
            return redirect('0/0/dashboard');
        }else{
            return redirect()->back()->with("error","Invalid Credentials");;
        }

    }


    public function logout(){
        Auth::guard('supplier')->logout();
        return redirect('/');
    }
}
