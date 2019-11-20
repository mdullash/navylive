<?php

namespace App\Http\Controllers;
use App\DNINPMSupplierApprovalInfo;
use App\SellsForm;
use App\Settings;
use App\Supplier;
use App\SupplierInfo;
use Illuminate\Http\Request;
use App\Category;
use App\Zone;
use App\NsdName;
use App\Notice;
use App\SupplyCategory;
use App\ItemToTender;
use App\Tender;
use App\SupplierChat;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use Session;
use Input;
use PDF;

class FrontEndController extends Controller
{
    public function index($zone=null,$nsd=null){

        $zone = $zone;
        $nsd  = $nsd;

    // Only for admin =========================================================================
        if(empty($zone) && empty($nsd)){
            $navalLocation = NsdName::where('status_id','=',1)->where('default_selected','=',1)->orderBy('id')->first();
            $zoneInfo = Zone::where('id','=',$navalLocation->zones)->where('status','=',1)->orderBy('id')->first();

            $data['recent_tenders'] = DB::table($zoneInfo->alise.'_tenders')
                                        ->whereDate('valid_date_from', '<=',date('Y-m-d'))
                                        ->whereDate('tender_opening_date', '>=',date('Y-m-d'))
                                        ->where('status_id','=',1)
                                        ->whereNotNull('notice')
                                        //->where('open_tender','=',1)
                                        ->where('nsd_id','=',$navalLocation->id)
                                        ->orderBy('id', 'desc')
                                        ->take(10)->get();

            foreach ($data['recent_tenders'] as $a){
                $a->new_tender=false;

                //identify new Tenders
                $t1 = strtotime($a->created_at);
                $t2 = strtotime( date('Y-m-d h:m:s') );
                $diff = $t2 - $t1;
                $hours = $diff / ( 60 * 60 );
               
                if ($hours > 24){
                    $a->new_tender =false;
                }else{
                    $a->new_tender =true;

                }


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

            $data['date_line_tenders'] = DB::table($zoneInfo->alise.'_tenders')
                                                ->whereDate('valid_date_from', '<=',date('Y-m-d'))
                                                ->whereDate('tender_opening_date', '>=',date('Y-m-d'))
                                                ->where('status_id','=',1)
                                                ->whereNotNull('notice')
                                                //->where('open_tender','=',1)
                                                ->where('nsd_id','=',$navalLocation->id)
                                                ->orderBy('date_line', 'asc')
                                                ->take(10)->get();

            foreach ($data['date_line_tenders'] as $ab){
                $ab->new_tender=false;

                $t1 = strtotime($a->created_at);
                $t2 = strtotime( date('Y-m-d h:m:s') );
                $diff = $t2 - $t1;
                $hours = $diff / ( 60 * 60 );
                if ($hours > 24){
                    $ab->new_tender =false;
                }else{
                    $ab->new_tender =true;

                }

                $ab->deno = '';
                if ($ab->quantity ==null){
                    $ab->quantity = '';
                    $itmToTndrInfo = DB::table('item_to_demand')->where('tender_no','=',$ab->id)->first();
                    $itmToTndrInfo_quantity = DB::table('item_to_demand')->where('tender_no','=',$ab->id)->count();
                    if(!empty($itmToTndrInfo)){
                        $itm = DB::table($zoneInfo->alise.'_items')->find($itmToTndrInfo->item_id);
                        $dno = DB::table('deno')->where('id','=',$itm->item_deno)->value('name');
                        if($itmToTndrInfo_quantity==1){
                            $ab->quantity = $itmToTndrInfo_quantity;
                            $ab->deno = $dno;

                        }else{
                            $ab->quantity = $itmToTndrInfo_quantity .' Line Item';

                        }

                    }

                }

            }
            $data['notices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->where('status_id','=',1)->take(5)->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->orderBy('id','desc')->get();
            $data['notices'] = Notice::whereIn('id',$noticeIds)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->where('status_id','=',1)->orderBy('id','desc')->take(5)->get();

//            $data['importantNotices'] = Notice::where('is_important','=',1)->where('status_id','=',1)->orderBy('id','desc')->get();
//            $data['notices'] = Notice::where('status_id','=',1)->orderBy('id','desc')->take(5)->get();

            // $categoriesId = SupplyCategory::select('id','name','zones')->where('status_id','=',1)->get();
            // $categoriesIds = array();
            // foreach($categoriesId as $cti){
            //     foreach (explode(',',$cti->zones) as $zid){
            //         if($zoneInfo->id==$zid){
            //             $categoriesIds[] = $cti->id;
            //         }
            //     }
            // }
            // $data['categories'] = SupplyCategory::select('id','name')->whereIn('id',$categoriesIds)->where('status_id','=',1)->get();
            
            $categoriesIds = array_unique(array_map('current',DB::table($zoneInfo->alise.'_tenders')->select('tender_cat_id')->where('nsd_id','=',$navalLocation->id)->where('status_id','=',1)->get()->toArray()));
            $data['categories'] = SupplyCategory::select('id','name')->whereIn('id',$categoriesIds)->where('status_id','=',1)->get();
        }

        // Only for naval location =========================================================================
        if(!empty($zone) && !empty($nsd)){
            $zoneInfo = Zone::where('alise','=',$zone)->first();
            $navalLocation = NsdName::where('alise','=',$nsd)->orderBy('id')->first();

            $data['recent_tenders'] = DB::table($zoneInfo->alise.'_tenders')
                                            ->whereDate('valid_date_from', '<=',date('Y-m-d'))
                                            ->whereDate('tender_opening_date', '>=',date('Y-m-d'))
                                            ->where('status_id','=',1)
                                            ->whereNotNull('notice')
                                            //->where('open_tender','=',1)
                                            ->where('nsd_id','=',$navalLocation->id)
                                            ->orderBy('id', 'desc')->take(10)->get();

            foreach ($data['recent_tenders'] as $a){
                $a->new_tender=false;

                //identify new Tenders
                $t1 = strtotime($a->created_at);
                $t2 = strtotime( date('Y-m-d h:m:s') );
                $diff = $t2 - $t1;
                $hours = $diff / ( 60 * 60 );
               
                if ($hours > 24){
                    $a->new_tender =false;
                }else{
                    $a->new_tender =true;

                }


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

            $curDate = date('Y-m-d');
            $data['date_line_tenders'] = DB::table($zoneInfo->alise.'_tenders')
                                            ->whereDate('valid_date_from', '<=',date('Y-m-d'))
                                            ->whereDate('tender_opening_date', '>=',date('Y-m-d'))
                                            ->where('status_id','=',1)
                                            ->whereNotNull('notice')
                                            //->where('open_tender','=',1)
                                            ->where('nsd_id','=',$navalLocation->id)
                                            ->whereDate('date_line','>=',$curDate)
                                            ->orderBy('date_line', 'asc')->take(10)->get();

            foreach ($data['date_line_tenders'] as $ab){
                //identify new Tenders
                $ab->new_tender=false;

                $t1 = strtotime($a->created_at);
                $t2 = strtotime( date('Y-m-d h:m:s') );
                $diff = $t2 - $t1;
                $hours = $diff / ( 60 * 60 );
                
                if ($hours > 24){
                    $ab->new_tender =false;
                }else{
                    $ab->new_tender =true;

                }

                $ab->deno = '';
                if ($ab->quantity ==null){
                    $ab->quantity = '';
                    $itmToTndrInfo = DB::table('item_to_demand')->where('tender_no','=',$ab->id)->first();
                    $itmToTndrInfo_quantity = DB::table('item_to_demand')->where('tender_no','=',$ab->id)->count();
                    if(!empty($itmToTndrInfo)){
                        $itm = DB::table($zoneInfo->alise.'_items')->find($itmToTndrInfo->item_id);
                        $dno = DB::table('deno')->where('id','=',$itm->item_deno)->value('name');
                        if($itmToTndrInfo_quantity==1){
                            $ab->quantity = $itmToTndrInfo_quantity;
                            $ab->deno = $dno;

                        }else{
                            $ab->quantity = $itmToTndrInfo_quantity .' Line Item';

                        }

                    }

                }
            }

            $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->take(5)->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->orderBy('id','desc')->get();
            $data['notices'] = Notice::whereIn('id',$noticeIds)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->where('status_id','=',1)->orderBy('id','desc')->take(5)->get();
            $categoriesId = SupplyCategory::select('id','name','zones')->where('status_id','=',1)->get();

            // $categoriesIds = array();
            // foreach($categoriesId as $cti){
            //     foreach (explode(',',$cti->zones) as $zid){
            //         if($zoneInfo->id==$zid){
            //             $categoriesIds[] = $cti->id;
            //         }
            //     }
            // }
            // $data['categories'] = SupplyCategory::select('id','name')->whereIn('id',$categoriesIds)->where('status_id','=',1)->get();
            
            $categoriesIds = array_unique(array_map('current',DB::table($zoneInfo->alise.'_tenders')->select('tender_cat_id')->where('nsd_id','=',$navalLocation->id)->where('status_id','=',1)->get()->toArray()));
            
            $data['categories'] = SupplyCategory::select('id','name')->whereIn('id',$categoriesIds)->where('status_id','=',1)->get();

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
        
        return view('frontend.index',$data);
   }


   public function webNewpage($zone=null,$nsd=null){

        $zone = $zone;
        $nsd  = $nsd;

    // Only for admin =========================================================================
        if(empty($zone) && empty($nsd)){
            $navalLocation = NsdName::where('status_id','=',1)->where('default_selected','=',1)->orderBy('id')->first();
            $zoneInfo = Zone::where('id','=',$navalLocation->zones)->where('status','=',1)->orderBy('id')->first();

            $data['recent_tenders'] = DB::table($zoneInfo->alise.'_tenders')
                                        ->whereDate('valid_date_from', '<=',date('Y-m-d'))
                                        ->whereDate('tender_opening_date', '>=',date('Y-m-d'))
                                        ->where('status_id','=',1)
                                        ->whereNotNull('notice')
                                        //->where('open_tender','=',1)
                                        ->where('nsd_id','=',$navalLocation->id)
                                        ->orderBy('id', 'desc')
                                        ->take(10)->get();

            foreach ($data['recent_tenders'] as $a){
                $a->new_tender=false;

                //identify new Tenders
                $t1 = strtotime($a->created_at);
                $t2 = strtotime( date('Y-m-d h:m:s') );
                $diff = $t2 - $t1;
                $hours = $diff / ( 60 * 60 );
               
                if ($hours > 24){
                    $a->new_tender =false;
                }else{
                    $a->new_tender =true;

                }


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

            $data['date_line_tenders'] = DB::table($zoneInfo->alise.'_tenders')
                                                ->whereDate('valid_date_from', '<=',date('Y-m-d'))
                                                ->whereDate('tender_opening_date', '>=',date('Y-m-d'))
                                                ->where('status_id','=',1)
                                                ->whereNotNull('notice')
                                                //->where('open_tender','=',1)
                                                ->where('nsd_id','=',$navalLocation->id)
                                                ->orderBy('date_line', 'asc')
                                                ->take(10)->get();

            foreach ($data['date_line_tenders'] as $ab){
                $ab->new_tender=false;

                $t1 = strtotime($a->created_at);
                $t2 = strtotime( date('Y-m-d h:m:s') );
                $diff = $t2 - $t1;
                $hours = $diff / ( 60 * 60 );
                if ($hours > 24){
                    $ab->new_tender =false;
                }else{
                    $ab->new_tender =true;

                }

                $ab->deno = '';
                if ($ab->quantity ==null){
                    $ab->quantity = '';
                    $itmToTndrInfo = DB::table('item_to_demand')->where('tender_no','=',$ab->id)->first();
                    $itmToTndrInfo_quantity = DB::table('item_to_demand')->where('tender_no','=',$ab->id)->count();
                    if(!empty($itmToTndrInfo)){
                        $itm = DB::table($zoneInfo->alise.'_items')->find($itmToTndrInfo->item_id);
                        $dno = DB::table('deno')->where('id','=',$itm->item_deno)->value('name');
                        if($itmToTndrInfo_quantity==1){
                            $ab->quantity = $itmToTndrInfo_quantity;
                            $ab->deno = $dno;

                        }else{
                            $ab->quantity = $itmToTndrInfo_quantity .' Line Item';

                        }

                    }

                }

            }
            $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->take(5)->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->orderBy('id','desc')->get();
            $data['notices'] = Notice::whereIn('id',$noticeIds)->where('status_id','=',1)->orderBy('id','desc')->take(5)->get();

//            $data['importantNotices'] = Notice::where('is_important','=',1)->where('status_id','=',1)->orderBy('id','desc')->get();
//            $data['notices'] = Notice::where('status_id','=',1)->orderBy('id','desc')->take(5)->get();

            // $categoriesId = SupplyCategory::select('id','name','zones')->where('status_id','=',1)->get();
            // $categoriesIds = array();
            // foreach($categoriesId as $cti){
            //     foreach (explode(',',$cti->zones) as $zid){
            //         if($zoneInfo->id==$zid){
            //             $categoriesIds[] = $cti->id;
            //         }
            //     }
            // }
            // $data['categories'] = SupplyCategory::select('id','name')->whereIn('id',$categoriesIds)->where('status_id','=',1)->get();
            
            $categoriesIds = array_unique(array_map('current',DB::table($zoneInfo->alise.'_tenders')->select('tender_cat_id')->where('nsd_id','=',$navalLocation->id)->where('status_id','=',1)->get()->toArray()));
            $data['categories'] = SupplyCategory::select('id','name')->whereIn('id',$categoriesIds)->where('status_id','=',1)->get();
        }

        // Only for naval location =========================================================================
        if(!empty($zone) && !empty($nsd)){
            $zoneInfo = Zone::where('alise','=',$zone)->first();
            $navalLocation = NsdName::where('alise','=',$nsd)->orderBy('id')->first();

            $data['recent_tenders'] = DB::table($zoneInfo->alise.'_tenders')
                                            ->whereDate('valid_date_from', '<=',date('Y-m-d'))
                                            ->whereDate('tender_opening_date', '>=',date('Y-m-d'))
                                            ->where('status_id','=',1)
                                            ->whereNotNull('notice')
                                            //->where('open_tender','=',1)
                                            ->where('nsd_id','=',$navalLocation->id)
                                            ->orderBy('id', 'desc')->take(10)->get();

            foreach ($data['recent_tenders'] as $a){
                $a->new_tender=false;

                //identify new Tenders
                $t1 = strtotime($a->created_at);
                $t2 = strtotime( date('Y-m-d h:m:s') );
                $diff = $t2 - $t1;
                $hours = $diff / ( 60 * 60 );
               
                if ($hours > 24){
                    $a->new_tender =false;
                }else{
                    $a->new_tender =true;

                }


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

            $curDate = date('Y-m-d');
            $data['date_line_tenders'] = DB::table($zoneInfo->alise.'_tenders')
                                            ->whereDate('valid_date_from', '<=',date('Y-m-d'))
                                            ->whereDate('tender_opening_date', '>=',date('Y-m-d'))
                                            ->where('status_id','=',1)
                                            ->whereNotNull('notice')
                                            //->where('open_tender','=',1)
                                            ->where('nsd_id','=',$navalLocation->id)
                                            ->whereDate('date_line','>=',$curDate)
                                            ->orderBy('date_line', 'asc')->take(10)->get();

            foreach ($data['date_line_tenders'] as $ab){
                //identify new Tenders
                $ab->new_tender=false;

                $t1 = strtotime($a->created_at);
                $t2 = strtotime( date('Y-m-d h:m:s') );
                $diff = $t2 - $t1;
                $hours = $diff / ( 60 * 60 );
                if ($hours > 24){
                    $ab->new_tender =false;
                }else{
                    $ab->new_tender =true;

                }

                $ab->deno = '';
                if ($ab->quantity ==null){
                    $ab->quantity = '';
                    $itmToTndrInfo = DB::table('item_to_demand')->where('tender_no','=',$ab->id)->first();
                    $itmToTndrInfo_quantity = DB::table('item_to_demand')->where('tender_no','=',$ab->id)->count();
                    if(!empty($itmToTndrInfo)){
                        $itm = DB::table($zoneInfo->alise.'_items')->find($itmToTndrInfo->item_id);
                        $dno = DB::table('deno')->where('id','=',$itm->item_deno)->value('name');
                        if($itmToTndrInfo_quantity==1){
                            $ab->quantity = $itmToTndrInfo_quantity;
                            $ab->deno = $dno;

                        }else{
                            $ab->quantity = $itmToTndrInfo_quantity .' Line Item';

                        }

                    }

                }
            }

            $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->take(5)->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->orderBy('id','desc')->get();
            $data['notices'] = Notice::whereIn('id',$noticeIds)->where('status_id','=',1)->orderBy('id','desc')->take(5)->get();
            $categoriesId = SupplyCategory::select('id','name','zones')->where('status_id','=',1)->get();

            // $categoriesIds = array();
            // foreach($categoriesId as $cti){
            //     foreach (explode(',',$cti->zones) as $zid){
            //         if($zoneInfo->id==$zid){
            //             $categoriesIds[] = $cti->id;
            //         }
            //     }
            // }
            // $data['categories'] = SupplyCategory::select('id','name')->whereIn('id',$categoriesIds)->where('status_id','=',1)->get();
            
            $categoriesIds = array_unique(array_map('current',DB::table($zoneInfo->alise.'_tenders')->select('tender_cat_id')->where('nsd_id','=',$navalLocation->id)->where('status_id','=',1)->get()->toArray()));
            
            $data['categories'] = SupplyCategory::select('id','name')->whereIn('id',$categoriesIds)->where('status_id','=',1)->get();

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

        $setting = Settings::find(2);
        $data['setting'] = $setting;

        return view('frontend.indexNew',$data);
   }

    public function specificationPdf($zone=null,$nsd=null,$id=null){

        $zone = $zone;
        $nsd  = $nsd;
        $id = base64_decode($id);

        if($zone == 0 && $nsd == 0){
            // $zoneInfo = Zone::where('status','=',1)->orderBy('id')->first();
            // $navalLocation = NsdName::where('status_id','=',1)->orderBy('id')->first();
            
            $navalLocation = NsdName::where('status_id','=',1)->where('default_selected','=',1)->orderBy('id')->first();
            $zoneInfo = Zone::where('id','=',$navalLocation->zones)->where('status','=',1)->orderBy('id')->first();

            $tender = DB::table($zoneInfo->alise.'_tenders')
                ->where('id','=',$id)
                ->first();
            return response()->file(public_path() . '/uploads/tender_spicification_notice_pdf/' . $tender->specification);
        }

        if($zone != 0 && $nsd != 0){
            $zoneInfo = Zone::where('alise','=',$zone)->first();
            $navalLocation = NsdName::where('alise','=',$nsd)->orderBy('id')->first();

            $tender = DB::table($zoneInfo->alise.'_tenders')
                ->where('id','=',$id)
                ->first();
            return response()->file(public_path() . '/uploads/tender_spicification_notice_pdf/' . $tender->specification);
        }

    }

    public function specificationDoc($zone=null,$nsd=null,$id=null){

        $zone = $zone;
        $nsd  = $nsd;
        $id = base64_decode($id);

        if($zone == 0 && $nsd == 0){
            // $zoneInfo = Zone::where('status','=',1)->orderBy('id')->first();
            // $navalLocation = NsdName::where('status_id','=',1)->orderBy('id')->first();

            $navalLocation = NsdName::where('status_id','=',1)->where('default_selected','=',1)->orderBy('id')->first();
            $zoneInfo = Zone::where('id','=',$navalLocation->zones)->where('status','=',1)->orderBy('id')->first();

            $tender = DB::table($zoneInfo->alise.'_tenders')
                ->where('id','=',$id)
                ->first();

            echo '<iframe src="https://docs.google.com/gview?url='.\URL::to('/').'/public/uploads/tender_spicification_notice_pdf/'.$tender->specification_doc.'&embedded=true" style="width:100%; height: 100%;"></iframe>';
                
            //return response()->file(public_path() . '/uploads/tender_spicification_notice_pdf/' . $tender->specification);
        }

        if($zone != 0 && $nsd != 0){
            $zoneInfo = Zone::where('alise','=',$zone)->first();
            $navalLocation = NsdName::where('alise','=',$nsd)->orderBy('id')->first();

            $tender = DB::table($zoneInfo->alise.'_tenders')
                ->where('id','=',$id)
                ->first();

            echo '<iframe src="https://docs.google.com/gview?url='.\URL::to('/').'/public/uploads/tender_spicification_notice_pdf/'.$tender->specification_doc.'&embedded=true" style="width:100%; height: 100%;"></iframe>';

            //return response()->file(public_path() . '/uploads/tender_spicification_notice_pdf/' . $tender->specification);
        }

    }

    public function noticePdf($zone=null,$nsd=null,$id=null){

        $zone = $zone;
        $nsd  = $nsd;
        $id = base64_decode($id);

        if($zone == 0 && $nsd == 0){
            // $zoneInfo = Zone::where('status','=',1)->orderBy('id')->first();
            // $navalLocation = NsdName::where('status_id','=',1)->orderBy('id')->first();

            $navalLocation = NsdName::where('status_id','=',1)->where('default_selected','=',1)->orderBy('id')->first();
            $zoneInfo = Zone::where('id','=',$navalLocation->zones)->where('status','=',1)->orderBy('id')->first();

            $tender = DB::table($zoneInfo->alise.'_tenders')
                ->where('id','=',$id)
                ->first();
            return response()->file(public_path() . '/uploads/tender_spicification_notice_pdf/' . $tender->notice);
        }

        if($zone != 0 && $nsd != 0){
            $zoneInfo = Zone::where('alise','=',$zone)->first();
            $navalLocation = NsdName::where('alise','=',$nsd)->orderBy('id')->first();

            $tender = DB::table($zoneInfo->alise.'_tenders')
                ->where('id','=',$id)
                ->first();
            return response()->file(public_path() . '/uploads/tender_spicification_notice_pdf/' . $tender->notice);
        }
    }

    public function noticeBrdPdf($id=null){

        $notice = Notice::find(base64_decode($id));
        return response()->file(public_path() . '/uploads/notice/' . $notice->upload_file);
    }


    public function notice(){
        $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->where('status_id','=',1)->take(5)->orderBy('id','desc')->get();
        $navalLocation = NsdName::where('status_id','=',1)->where('default_selected','=',1)->orderBy('id')->first();

        $noticeIds = array();
        foreach ($data['notices'] as $ntc){
            if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                $noticeIds[] = $ntc->id;
            }
        }

        $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->orderBy('id','desc')->get();
        $data['navallocation'] = $navalLocation;
        $zones=Zone::where('id',$navalLocation->zones)->first();
        Session::put('zoneAlise',$zones->alise);
        $zone=$zones->alise;
        $data['approved']=DB::table($zone.'_supplier_approval_info_after_dns_approval')
            ->join($zone.'_supplier_approval_after_dns_approval',$zone.'_supplier_approval_after_dns_approval.id','=',$zone.'_supplier_approval_info_after_dns_approval.approve_id')
            ->where($zone.'_supplier_approval_after_dns_approval.status','approved')
            ->where($zone.'_supplier_approval_info_after_dns_approval.supplier_id', Auth::guard('supplier')->id())
            ->count();
        $data['organizations'] = NsdName::whereNotIn('id',[$navalLocation->id])->where('status_id','=',1)->get();

        $data['organizationsHead'] = [];

        foreach ($data['organizations'] as $sd){
            $exp = explode(',',$sd->zones);
            $exp = $exp[0];
            $zoneAlise = Zone::where('id','=',$exp)->first();
            $data['organizationsHead'][] = $sd->setAttribute('zoneAlise', $zoneAlise->alise);

        }


        return $data;
    }

    public function dashboard(){
        $data=$this->notice();

        $supplierId = Auth::guard('supplier')->id();
    
        $SupplierTenderAttend = DB::table('demand_to_collection_quotation')
                                 ->select('tender_id')
                                 ->groupBy('tender_id')
                                 ->where('supplier_name', '=',$supplierId)
                                 ->get();
         $SupplierTenderAttendCount = count($SupplierTenderAttend);

         $SupplierTenderWinCount = DB::table('demand_supplier_to_coll_qut_to_item')
                                 ->select(DB::raw('count(DISTINCT tender_id) as tender_win'))
                                 ->where('supplier_id', '=',$supplierId)
                                 ->where('select_as_winner', '=',1)
                                 ->get();

        return view('frontend.supplier-dashboard',$data,compact('SupplierTenderAttendCount','SupplierTenderWinCount'));
    }


    public function enlistment_track(){


        if (Auth::guard('supplier')->check()){


        $data=$this->notice();
        $zone=\Session::get("zoneAlise");
        $data['enlistment']=DB::table($zone.'_suppliers')->where('id', Auth::guard('supplier')->id())->where('enlistment_status','approved')->count();
        $supplier_mobile=DB::table($zone.'_suppliers')->where('id', Auth::guard('supplier')->id())->first();

        $data['sell_form']=DB::table($zone.'_sells_form')->where('mobile_number', $supplier_mobile!=null?$supplier_mobile->mobile_number:null)->count();


        $data['npm_dni_approval']=DB::table($zone.'_supplier_approval_info')
            ->join($zone.'_supplier_approved',\Session::get("zoneAlise").'_supplier_approved.id','=',$zone.'_supplier_approval_info.approve_id')
            ->where($zone.'_supplier_approval_info.supplier_id', Auth::guard('supplier')->id())
            ->where([$zone.'_supplier_approved.dni_status'=>'approved',$zone.'_supplier_approved.npm_status'=>'approved'])->count();


        $data['dns_approval']=DB::table($zone.'_supplier_npm_dni_approval_info')
            ->join($zone.'_supplier_npm_dni_approved',$zone.'_supplier_npm_dni_approved.id','=',$zone.'_supplier_npm_dni_approval_info.approve_id')
            ->where($zone.'_supplier_npm_dni_approved.status','approved')
            ->where($zone.'_supplier_npm_dni_approval_info.supplier_id', Auth::guard('supplier')->id())
            ->count();


        $data['approved']=DB::table($zone.'_supplier_approval_info_after_dns_approval')
            ->join($zone.'_supplier_approval_after_dns_approval',$zone.'_supplier_approval_after_dns_approval.id','=',$zone.'_supplier_approval_info_after_dns_approval.approve_id')
            ->where($zone.'_supplier_approval_after_dns_approval.status','approved')
            ->where($zone.'_supplier_approval_info_after_dns_approval.supplier_id', Auth::guard('supplier')->id())
            ->count();


        return view('frontend.enlistment-track',$data);

        }else{
            return redirect('0/0/login');
        }
    }

    public function tender_participant_status(){

        $data=$this->notice();

         $supplierId = Auth::guard('supplier')->id();

         $zone = \Session::get("zoneAlise");

         $tenderTable = $zone.'_tenders';

         $SupplierTenderAttends = DB::table('demand_to_collection_quotation')
             ->leftJoin($tenderTable, 'demand_to_collection_quotation.tender_id', '=', $tenderTable.'.id')
             ->select(
                 $tenderTable.'.id',
                 $tenderTable.'.tender_title',
                 $tenderTable.'.tender_number',
                 $tenderTable.'.valid_date_from as publish_date',
                 $tenderTable.'.tender_opening_date as participation_date'
             )
             ->orderBy($tenderTable.'.tender_opening_date','DESC')
             ->where('supplier_name', '=',$supplierId)
             ->groupBy('demand_to_collection_quotation.tender_id')
             ->paginate(10);

                                // dd($SupplierTenderAttends);
        return view('frontend.tender-participant',$data,compact('SupplierTenderAttends','supplierId'));



    }

    public function evaluation_report(){
        $data=$this->notice();

         $supplierId = Auth::guard('supplier')->id();
         $supplierAllOrgId = Auth::guard('supplier')->user()->all_org_id;

         $zone = \Session::get("zoneAlise");

         $tenderTable = $zone.'_tenders';

         $SupplierTenderAllOrgId = DB::table('demand_to_collection_quotation')
                                ->leftJoin($tenderTable, 'demand_to_collection_quotation.tender_id', '=', $tenderTable.'.id')
                                ->select($tenderTable.'.all_org_tender_id' )
                                ->orderBy($tenderTable.'.tender_opening_date','DESC')
                                ->where('supplier_name', '=',$supplierId)
                                ->groupBy('demand_to_collection_quotation.tender_id')
                                ->get()->toArray();

        $this->tableAlies = \Session::get('zoneAlise');
        $evaluCiterias = \App\EvaluationCriteria::all();
        $supplierstn = $this->tableAlies.'_suppliers.all_org_id';
        $tenderstn = $this->tableAlies.'_tenders';

        $from       = Input::get('from');
        $todate     = Input::get('todate');
        $sup_id     = Input::get('sup_id');
        $ten_number = Input::get('ten_number'); // tender id
        $tenderIds  = array();
       
        if(!empty($from) || !empty($todate) || !empty($ten_number)){
            $tenderIds = \App\Tender::select('id')->whereNotNull('id');
                    if(!empty($ten_number)){
                        $tenderIds->where($this->tableAlies.'_tenders.id','=',$ten_number);
                    }
                    if(!empty($from)){
                        $tenderIds->whereDate($this->tableAlies.'_tenders.tender_opening_date','>=',$from);
                    }
                    if(!empty($todate)){
                        $tenderIds->whereDate($this->tableAlies.'_tenders.tender_opening_date','<=',$todate);
                    }
            $tenderIds = $tenderIds->get()->toArray(); 
            $tenderIds = array_map('current',$tenderIds);
        }

                $queryResult   = \App\EvaluatedTender::
                leftJoin('evaluation_criteria','evaluation_criteria.id','=','evaluated_tender.evalu_citeria_id')
                ->leftJoin($this->tableAlies.'_tenders','evaluated_tender.tender_id_alise','=',$this->tableAlies.'_tenders.all_org_tender_id')
                ->leftJoin($this->tableAlies.'_suppliers','evaluated_tender.supplier_id_alise','=',$this->tableAlies.'_suppliers.all_org_id')
            ->select($this->tableAlies.'_tenders.tender_number',$this->tableAlies.'_suppliers.all_org_id as supplirId',$this->tableAlies.'_suppliers.company_name',
            \DB::raw("(select SUM((evaluated_tender.marks)*evaluation_criteria.weight) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 1 ) as c1, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 1  && evaluated_tender.marks != 0 ) as c1c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 1 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 1 ) ) as c1cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 2 ) as c2, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 2  && evaluated_tender.marks != 0 ) as c2c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 2 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 2 ) ) as c2cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 3 ) as c3, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 3  && evaluated_tender.marks != 0 ) as c3c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 3 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 3 ) ) as c3cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 4 ) as c4, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 4  && evaluated_tender.marks != 0 ) as c4c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 4 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 4 ) ) as c4cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 5 ) as c5, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 5  && evaluated_tender.marks != 0 ) as c5c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 5 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 5 ) ) as c5cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 6 ) as c6, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 6  && evaluated_tender.marks != 0 ) as c6c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 6 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 6 ) ) as c6cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 7 ) as c7, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 7  && evaluated_tender.marks != 0 ) as c7c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 7 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 7 ) ) as c7cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 8 ) as c8, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 8  && evaluated_tender.marks != 0 ) as c8c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 8 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 8 ) ) as c8cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 9 ) as c9, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 9  && evaluated_tender.marks != 0 ) as c9c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 9 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 9 ) ) as c9cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 10 ) as c10, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 10  && evaluated_tender.marks != 0 ) as c10c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 10 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL  && evaluated_tender.evalu_citeria_id = 10 ) ) as c10cm")

        )->where('evaluated_tender.supplier_id_alise','=',$supplierAllOrgId);
        $queryResult->groupBy($this->tableAlies.'_tenders.all_org_tender_id');
        $queryResult = $queryResult->paginate(10);

        $pointTableDatas = \App\EvaluationPointTable::where('status','=',1)->get();

        return view('frontend.evaluation-report',$data)->with(compact('queryResult','pointTableDatas'));
    }

    public function supplierProfile(){
        $data=$this->notice();
        $id = Auth::guard('supplier')->id();
        $this->tableAlies = \Session::get('zoneAlise');
        $suppliers = \App\Supplier::find($id);

        $supplierPersonalInfo = \App\SupplierBasicInfo::where('supplier_id','=',$suppliers->id)->first();

        return view('frontend.supplier-profile',$data)->with(compact('suppliers','supplier_to_items','winning_tenders','supplierPersonalInfo'));
    }

    public function changePassword(){
        $data=$this->notice();
        if (Auth::guard('supplier')->check()) {
            return view('frontend.change-password',$data);
        }else{
            return redirect('/0/0/logout');
        }
    }

    public function changePasswordSubmit(Request $request){
        if (Auth::guard('supplier')->check()) {
                $validated = $request->validate([
                    'current_password' => 'required',
                    'new_password' => 'required',
                    'password_confirmation' => 'required|same:new_password',    
            ]);

            if (!(\Hash::check($request->get('current_password'), Auth::guard('supplier')->user()->password))) {
                // The passwords matches
                return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
            }

            if(strcmp($request->get('current_password'), $request->get('new_password')) == 0){
                //Current password and new password are same
                return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
            }

            $id = Auth::guard('supplier')->id();

            $supplier = \App\Supplier::findOrFail($id);
            $supplier->password = bcrypt($request->get('new_password'));

            if ($supplier->save()) {
                return redirect('/0/0/dashboard')->with("success","Your Password Changed Successfully");
            }else{
                 $data=$this->notice();
                view('frontend.change-password',$data)->with("error","Your Password Not Changed");
            }

        }else{
            return redirect('/0/0/logout');
        }
    }

    public function supplierChat(){
         $data=$this->notice();
        if (Auth::guard('supplier')->check()) {

            $chats = SupplierChat::where('supplier_id','=',Auth::guard('supplier')->id())->get();

            return view('frontend.chat',$data,compact('chats'));
        }else{
            return redirect('/0/0/logout');
        }
    }

    public function supplierChatPost(Request $request){

        if (Auth::guard('supplier')->check()) {
            $chat = new SupplierChat();

            $chat->supplier_id = Auth::guard('supplier')->id();
            $chat->supplier_name = Auth::guard('supplier')->user()->company_name;
            $chat->message = $request->message;

            if ($request->hasFile('file')) {
                    $inputFile = $request->file('file');

                    $file_name = str_random(20);
                    $ext = strtolower($inputFile->getClientOriginalExtension());
                    $file_full_name = $file_name . '.' . $ext;
                    $upload_path = 'public/upload/chat-file/';
                    $file_url = $upload_path . $file_full_name;
                    $inputFile->move($upload_path, $file_full_name);
                    $chat->file = $file_url;

                    $mimeType = explode('/',$inputFile->getClientMimeType());
                    $chat->file_type = $mimeType[0];
                }
                $chat->sender_type = 1;
               if ($chat->save()) {
                    return redirect('/0/0/supplier-chat');
               }else{
                    session()->flush('error','');
                    return redirect()->back()->with("error","Message uneable to send.");
               }
        }else{
            return redirect('/0/0/logout');
        }
    }


     public function nssdSupplierChatList(Request $request){

            $supplierName = $request->supplier_name;

            $chats = \App\Supplier::leftJoin('supplier_chat','nsd_suppliers.id','=','supplier_chat.supplier_id')->latest('supplier_chat.created_at')->whereNotNull('supplier_chat.message')->whereNull('supplier_chat.navy_id');

            if ($supplierName) {
                $chats->where('company_name','like',"%{$supplierName}%");
            }

            $chats = $chats->paginate(10);

            return view('chat.index',compact('chats'));
    }

    public function nssdSupplierChat($id){
            $chats = SupplierChat::where('supplier_id','=',$id)->get();
            $supplier = \App\Supplier::find($id);
            return view('chat.chat',compact('chats','supplier'));
    }

    public function nssdSupplierChatPost(Request $request){

            $chat = new SupplierChat();

            $chat->supplier_id = $request->supplier_id;
            $chat->navy_id = Auth::id();
            $chat->navy_name = Auth::user()->first_name.' '.Auth::user()->last_name;
            $chat->message = $request->message;

            if ($request->hasFile('file')) {
                    $inputFile = $request->file('file');

                    $file_name = str_random(20);
                    $ext = strtolower($inputFile->getClientOriginalExtension());
                    $file_full_name = $file_name . '.' . $ext;
                    $upload_path = 'public/upload/chat-file/';
                    $file_url = $upload_path . $file_full_name;
                    $inputFile->move($upload_path, $file_full_name);
                    $chat->file = $file_url;

                    $mimeType = explode('/',$inputFile->getClientMimeType());
                    $chat->file_type = $mimeType[0];
                }
                $chat->sender_type = 2;
               if ($chat->save()) {
                    return redirect('/supplier-chat/'.$request->supplier_id);
               }else{
                    return redirect()->back()->with("error","Message uneable to send.");
               }
           }

    public function messageToSupplier(){
            return view('chat.message-to-supplier');
    }

    public function supplierListAjax(Request $request){


        $search = $request->get('query');
        $supplier = \App\Supplier::select('company_name','id')->where('company_name','like',"%{$search}%")->limit(10)->get();
        // return $supplier;


        if(count($supplier)<1){
                $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
                $output .= '<li>No match found</li>';
                $output .= '</ul>';
                return $output;
            }

            $output = '<ul class="dropdown-menu" style="display:block; position:absolute; left: 16px;">';
            foreach($supplier as $row)
            {
                $output .= '<li class="searchTenderNumber" value="'.$row->id.'" ><a href="javascript:void(0)">'.$row->company_name.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;

        // return response()->json($supplier);
    }

    public function printPoGeneration($id){
        $this->tableAlies = \Session::get('zoneAlise');

        $explodes = explode('&',$id);

        //$demandId = $id;
        $podataId       = $explodes[0];
        $tenderId       = $explodes[1];

        $podataInfo     = \App\PoDatas::find($podataId);

        $demandToLprId   = $podataInfo->lpr_id;
        $demandToLprInfo = \App\DemandToLpr::find($podataInfo->lpr_id);
        $tenderInfo      = \App\Tender::find($tenderId);
        $tenderNumber    = Tender::find($tenderId)->value('tender_number');
        $demandToTenInfo = \App\DemandToTender::where('tender_id','=',$tenderId)->first();

        $nsdId = 1;
         if(!empty(Auth::user()->nsd_bsd)){
            $nsdId = Auth::user()->nsd_bsd;
         }
        $orgInfo  = \App\NsdName::find($nsdId);

        $dem_to_col_ids   = explode(',', $podataInfo->dem_to_col_id);
        $demandToCollQut  = \App\DemandToCollectionQuotation::whereIn('id',$dem_to_col_ids)->get();

        $selectedSupItemInfo = array();
        foreach ($dem_to_col_ids as $key => $dm_col_id) {
            $selectedSupItemInfo[$dm_col_id] = \App\DemandSuppllierToCollQuotToItem::
                                    join('item_to_demand', 'demand_supplier_to_coll_qut_to_item.item_id', '=', 'item_to_demand.id')
                                    ->join('deno', 'item_to_demand.deno_id', '=', 'deno.id')
                                    ->join($this->tableAlies.'_items', 'item_to_demand.item_id', '=', $this->tableAlies.'_items.id')
                                    ->select('demand_supplier_to_coll_qut_to_item.*','deno.name as deno_name', $this->tableAlies.'_items.item_name as item_item_name', $this->tableAlies.'_items.model_number as item_model_number',$this->tableAlies.'_items.brand as item_brand')
                                    ->where('demand_supplier_to_coll_qut_to_item.dmn_to_cal_qut_id','=',$dm_col_id)
                                    ->where('demand_supplier_to_coll_qut_to_item.select_as_winner','=',1);
                                    if(!empty($demandToTenInfo->head_ofc_apvl_status)){
                                        $selectedSupItemInfo[$dm_col_id]->where('demand_supplier_to_coll_qut_to_item.itm_to_sup_nhq_app_status','=',1);
                                    }
            $selectedSupItemInfo[$dm_col_id] = $selectedSupItemInfo[$dm_col_id]->get();
        }
        $budgetCodeS = '';

        if (!empty($tenderInfo->budget_code) || $tenderInfo->budget_code != null){
            $budgetIds = explode(',', $tenderInfo->budget_code);
            $budgetUniqueIds = array_unique($budgetIds);
            $budgetCodeS = \App\BudgetCode::select('code','description')->find($budgetUniqueIds);
        }

        $tenderData = [
                    'podataId' => $podataId,
                    'tenderId' => $tenderId,
                    'podataInfo' => $podataInfo,
                    'demandToLprId' => $demandToLprId,
                    'demandToLprInfo' => $demandToLprInfo,
                    'tenderInfo' => $tenderInfo,
                    'tenderNumber' => $tenderNumber,
                    'demandToTenInfo' => $demandToTenInfo,
                    'orgInfo' => $orgInfo,
                    'selectedSupItemInfo' => $selectedSupItemInfo,
                    'demandToCollQut' => $demandToCollQut,
                    'budgetCodeS'       => $budgetCodeS
                ];

        $pdf= PDF::loadView('po-generation.po-print-generate-pdf',$tenderData,[],['format' => [215.9, 342.9]]);
        return $pdf->stream('purchase-order.pdf');

        //return View::make('po-generation.po-print-generate-pdf')->with(compact('podataId','tenderId','podataInfo','demandToLprId','demandToLprInfo','tenderInfo','tenderNumber','demandToTenInfo','orgInfo','selectedSupItemInfo','demandToCollQut'));

    }
}



