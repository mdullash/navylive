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
            $data['title']=\App\Settings::select('site_title')->first();
            $data['user'] = \App\User::count();
            $data['suppliers'] = \App\Supplier::count();
            $data['items'] = \App\Item::count();
            $data['tenders'] = \App\Tender::count();

        return view('dashboard.index',$data);
    }

    public function moduleDashboard(){
        $abc = explode(',',Auth::user()->zones);
        $data['zones'] = \App\Zone::where('status','=',1)->whereIn('id',$abc)->get();
        //$data['zones'] = \App\Zone::where('status','=',1)->get();
        return view('dashboard.module-dashboard',$data);
    }

}
