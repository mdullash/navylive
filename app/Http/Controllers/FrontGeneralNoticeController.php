<?php

namespace App\Http\Controllers;
use App\Settings;
use Illuminate\Http\Request;
use App\Category;
use App\Zone;
use App\NsdName;
use App\Notice;
use App\SupplyCategory;
use App\ItemToTender;
use App\Tender;
use Illuminate\Support\Facades\Validator;
use Input;
use DB;
use Auth;
use Session;

class FrontGeneralNoticeController extends Controller
{

    public function index($zone=null,$nsd=null){
        $zone = $zone;
        $nsd  = $nsd;

        // Only for admin =========================================================================
        if(empty($zone) && empty($nsd)){
            // $zoneInfo = Zone::where('status','=',1)->orderBy('id')->first();
            // $navalLocation = NsdName::where('status_id','=',1)->orderBy('id')->first();
            
            $navalLocation = NsdName::where('status_id','=',1)->where('default_selected','=',1)->orderBy('id')->first();
            $zoneInfo = Zone::where('id','=',$navalLocation->zones)->where('status','=',1)->orderBy('id')->first();

            $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->orderBy('id','desc')->get();
            $data['notices'] = Notice::whereIn('id',$noticeIds)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->where('status_id','=',1)->orderBy('id','desc')->get();

//            $data['notices'] = Notice::where('status_id','=',1)->orderBy('id','desc')->get();
//
//            $data['importantNotices'] = Notice::where('is_important','=',1)->where('status_id','=',1)->orderBy('id','desc')->get();
        }

        // Only for naval location =========================================================================
        if(!empty($zone) && !empty($nsd)){
            $zoneInfo = Zone::where('alise','=',$zone)->first();
            $navalLocation = NsdName::where('alise','=',$nsd)->orderBy('id')->first();

            $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->orderBy('id','desc')->get();
            $data['notices'] = Notice::whereIn('id',$noticeIds)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->where('status_id','=',1)->orderBy('id')->get();

        }
        $data['navallocation'] = $navalLocation;

        $data['zoneInfo'] = $zoneInfo;
        $data['organizations'] = NsdName::whereNotIn('id',[$navalLocation->id])->where('status_id','=',1)->get();

        $data['organizationsHead'] = [];

        foreach ($data['organizations'] as $sd){
            $exp = explode(',',$sd->zones);
            $exp = $exp[0];
            $zoneAlise = Zone::where('id','=',$exp)->first();
            $data['organizationsHead'][] = $sd->setAttribute('zoneAlise', $zoneAlise->alise);

        }
        
        return view('frontend.general-notice',$data);

    }

}



