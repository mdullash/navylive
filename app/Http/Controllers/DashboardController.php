<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class DashboardController extends Controller
{
    private $tableAlies;

    public function __construct() {
        $this->tableAlies = \Session::get('zoneAlise');
    }

    public function index($id=null)
    {
        if(!empty($id)){
            $id = base64_decode($id);
            $zone = \App\Zone::find($id);
            \Session::put('zone', strtolower($zone->name));
            \Session::put('zoneAlise', strtolower($zone->alise));
            \Session::put('zoneId', strtolower($zone->id));

        }

        if (auth()->user()!=null && auth()->user()->group_id != null){
            Auth::logout();
        }

            $user_role    = \App\Role::find(Auth::user()->role_id); 
            $user_role_id = Auth::user()->role_id; 
            $user_piority = $user_role->priority;

            $data['title']=\App\Settings::select('site_title')->first();
            
            $data['user'] = \App\User::where('id', '!=', NULL);
            if ($user_piority>=3) {
                $data['user']->where('role_id', '>=', $user_role_id);
            }
            $data['user'] = $data['user']->count();
            
            $orgIds = explode(',',auth()->user()->nsd_bsd);
            if($user_piority>=3){
                if(count($orgIds)>0){

                    $supOrgRltIds = array();
                    $itmOrgRltIds = array();
                    $tenOrgRltIds = array();
                    foreach ($orgIds as $key => $val) {

                        array_push($supOrgRltIds,array_map('current',\App\Supplier::select('id')->whereRaw("find_in_set('".$val."',registered_nsd_id)")
                        ->get()->toArray()));

                        array_push($itmOrgRltIds,array_map('current',\App\Item::select('id')->whereRaw("find_in_set('".$val."',nsd_id)")
                        ->get()->toArray()));

                        array_push($tenOrgRltIds,array_map('current',\App\Tender::select('id')->where('nsd_id','=',$val)
                        ->get()->toArray()));  

                    }

                $data['suppliers']  = count(array_unique(call_user_func_array('array_merge', $supOrgRltIds)));
                $data['items']      = count(array_unique(call_user_func_array('array_merge', $itmOrgRltIds)));
                $data['tenders']    = count(array_unique(call_user_func_array('array_merge', $tenOrgRltIds)));

                }else{
                    $data['suppliers']  = 0;
                    $data['items']      = 0;
                    $data['tenders']    = 0;
                }
            }else{
                $data['suppliers'] = \App\Supplier::count();
                $data['items'] = \App\Item::count();
                $data['tenders'] = \App\Tender::count();
            }

            // $data['user'] = \App\User::count();
            // $data['suppliers'] = \App\Supplier::count();
            // $data['items'] = \App\Item::count();
            // $data['tenders'] = \App\Tender::count();
            
        return view('dashboard.index',$data);
    }

    public function moduleDashboard(){
        $abc = explode(',',Auth::user()->zones);
        $data['zones'] = \App\Zone::where('status','=',1)->whereIn('id',$abc)->get();
        //$data['zones'] = \App\Zone::where('status','=',1)->get();
        return view('dashboard.module-dashboard',$data);
    }

}
