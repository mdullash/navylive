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
use Illuminate\Pagination\LengthAwarePaginator;

class FrontTenderController extends Controller
{
    public function index($zone=null,$nsd=null){
        $zone = $zone;
        $nsd  = $nsd;

        $category = Input::get('category');
        $key = Input::get('key');
        $from = empty(Input::get('from')) ? '' : date('Y-m-d',strtotime(Input::get('from')));
        $to   = empty(Input::get('to')) ? '' : date('Y-m-d',strtotime(Input::get('to')));

        // Only for admin =========================================================================
        if(empty($zone) && empty($nsd)){
            // $zoneInfo = Zone::where('status','=',1)->orderBy('id')->first();
            // $navalLocation = NsdName::where('status_id','=',1)->orderBy('id')->first();

            $navalLocation = NsdName::where('status_id','=',1)->where('default_selected','=',1)->orderBy('id')->first();
            $zoneInfo = Zone::where('id','=',$navalLocation->zones)->where('status','=',1)->orderBy('id')->first();

            $categoriesIdss = array_unique(array_map('current',DB::table($zoneInfo->alise.'_tenders')->select('tender_cat_id')->where('status_id','=',1)->get()->toArray()));
            $data['categoriess'] = SupplyCategory::select('id','name');
            if(!empty($category)){
                $data['categoriess']->where('id','=',$category);
            }else{
                $data['categoriess']->whereIn('id',$categoriesIdss);
            }

            $data['categoriess']->where('status_id','=',1);
            $data['categoriess']->orderBy('id');
            $data['categoriess'] = $data['categoriess']->get();


            foreach ($data['categoriess'] as $ct){
                $data['recent_tenders'][$ct->name] = DB::table($zoneInfo->alise.'_tenders')
                    ->orderby('tender_opening_date')
                    ->whereDate('valid_date_from', '<=',date('Y-m-d'))
                    ->whereDate('tender_opening_date', '>=',date('Y-m-d'))
                    ->where('status_id','=',1)
                    ->whereNotNull('notice')
                    //->where('open_tender','=',1)
                    ->where('nsd_id','=',$navalLocation->id)
                    ->where('tender_cat_id','=',$ct->id);
                if(!empty($key)){
                    $data['recent_tenders'][$ct->name]->where(function($query) use ($key){
                        $query->where('po_number', 'like', "%{$key}%");
                        $query->orWhere('tender_title', 'like', "%{$key}%");
                        $query->orWhere('tender_number', 'like', "%{$key}%");
                    });
                }
                if(!empty($from)){
                    $data['recent_tenders'][$ct->name]->where(function($query) use ($from ){
                        $query->whereDate('tender_opening_date','>=',$from);
                    });
                }
                if(!empty($to)){
                    $data['recent_tenders'][$ct->name]->where(function($query) use ($to){
                        $query->whereDate('tender_opening_date','<=',$to);
                    });
                }
                // $data['recent_tenders'][$ct->name]->orderBy('id', 'desc');
                $data['recent_tenders'][$ct->name]->orderby('tender_opening_date');
                $data['recent_tenders'][$ct->name] = $data['recent_tenders'][$ct->name]->paginate(10);

                foreach ($data['recent_tenders'][$ct->name] as $a){

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

            }


//echo "<pre>"; print_r($data['recent_tenders']); exit;

            $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->take(5)->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->orderBy('id','desc')->get();
            $categoriesIds = array_unique(array_map('current',DB::table($zoneInfo->alise.'_tenders')->select('tender_cat_id')->where('nsd_id','=',$navalLocation->id)->where('status_id','=',1)->get()->toArray()));
            $data['categories'] = SupplyCategory::select('id','name')->whereIn('id',$categoriesIds)->where('status_id','=',1)->get();

        }

        // Only for naval location =========================================================================
        if(!empty($zone) && !empty($nsd)){
            $zoneInfo = Zone::where('alise','=',$zone)->first();
            $navalLocation = NsdName::where('alise','=',$nsd)->orderBy('id')->first();

            $categoriesIdss = array_unique(array_map('current',DB::table($zoneInfo->alise.'_tenders')->select('tender_cat_id')->where('status_id','=',1)->get()->toArray()));
            $data['categoriess'] = SupplyCategory::select('id','name');
            if(!empty($category)){
                $data['categoriess']->where('id','=',$category);
            }else{
                $data['categoriess']->whereIn('id',$categoriesIdss);
            }

            $data['categoriess']->where('status_id','=',1);
            $data['categoriess']->orderBy('id');
            $data['categoriess'] = $data['categoriess']->get();


            foreach ($data['categoriess'] as $ct){
                $data['recent_tenders'][$ct->name] = DB::table($zoneInfo->alise.'_tenders')
                    ->orderBy('tender_opening_date')
                    ->whereDate('valid_date_from', '<=',date('Y-m-d'))
                    ->whereDate('tender_opening_date', '>=',date('Y-m-d'))
                    ->where('status_id','=',1)
                    ->whereNotNull('notice')
                    //->where('open_tender','=',1)
                    ->where('nsd_id','=',$navalLocation->id)
                    ->where('tender_cat_id','=',$ct->id);
                if(!empty($key)){
                    $data['recent_tenders'][$ct->name]->where(function($query) use ($key){
                        $query->where('po_number', 'like', "%{$key}%");
                        $query->orWhere('tender_title', 'like', "%{$key}%");
                        $query->orWhere('tender_number', 'like', "%{$key}%");
                    });
                }
                if(!empty($from)){
                    $data['recent_tenders'][$ct->name]->where(function($query) use ($from ){
                        $query->whereDate('tender_opening_date','>=',$from);
                    });
                }
                if(!empty($to)){
                    $data['recent_tenders'][$ct->name]->where(function($query) use ($to){
                        $query->whereDate('tender_opening_date','<=',$to);
                    });
                }
                // $data['recent_tenders'][$ct->name]->orderBy('id', 'desc');
                $data['recent_tenders'][$ct->name]->orderBy('tender_opening_date');
                $data['recent_tenders'][$ct->name] = $data['recent_tenders'][$ct->name]->paginate(10);

                foreach ($data['recent_tenders'][$ct->name] as $a){


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

            }

//echo "<pre>"; print_r($data['recent_tenders']); exit;

//            $data['recent_tenders'] = DB::table($zoneInfo->alise.'_tenders')
//                ->where('status_id','=',1)
//                ->where('open_tender','=',1)
//                ->where('nsd_id','=',$navalLocation->id);
//                if(!empty($category)){
//                    $data['recent_tenders']->where('tender_cat_id','=',$category);
//                }
//                if(!empty($key)){
//                    $data['recent_tenders']->where(function($query) use ($key){
//                        $query->where('po_number', 'like', "%{$key}%");
//                        $query->orWhere('tender_title', 'like', "%{$key}%");
//                        $query->orWhere('tender_number', 'like', "%{$key}%");
//                    });
//                }
//                $data['recent_tenders']->orderBy('id', 'desc');
//                $data['recent_tenders'] = $data['recent_tenders']->paginate(10);
//
//            foreach ($data['recent_tenders'] as $a){
//                $a->quantity = '';
//                $a->deno = '';
//                $itmToTndrInfo = DB::table($zoneInfo->alise.'_itemtotender')->where('tender_id','=',$a->id)->first();
//                if(!empty($itmToTndrInfo)){
//                    $itm = DB::table($zoneInfo->alise.'_items')->find($itmToTndrInfo->item_id);
//                    $dno = DB::table('deno')->where('id','=',$itm->item_deno)->value('name');
//                    $a->quantity = $itmToTndrInfo->quantity;
//                    $a->deno = $dno;
//                }
//
//            }

            $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->take(5)->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->orderBy('id','desc')->get();

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

        $data['from'] = $from;
        $data['to'] = $to;

        return view('frontend.tender-list',$data);

    }

    public function frontPoWinner($zone=null,$nsd=null){

        $zone = $zone;
        $nsd  = $nsd;

        $category = Input::get('category');
        $key = Input::get('key');
        $from = empty(Input::get('from')) ? '' : date('Y-m-d',strtotime(Input::get('from')));
        $to   = empty(Input::get('to')) ? '' : date('Y-m-d',strtotime(Input::get('to')));

        // Only for admin =========================================================================
        if(empty($zone) && empty($nsd)){
            // $zoneInfo = Zone::where('status','=',1)->orderBy('id')->first();
            // $navalLocation = NsdName::where('status_id','=',1)->orderBy('id')->first();

            $navalLocation = NsdName::where('status_id','=',1)->where('default_selected','=',1)->orderBy('id')->first();
            $zoneInfo = Zone::where('id','=',$navalLocation->zones)->where('status','=',1)->orderBy('id')->first();

            $categoriesIdss = array_unique(array_map('current',DB::table($zoneInfo->alise.'_tenders')->select('tender_cat_id')->where('status_id','=',1)->get()->toArray()));
            $data['categoriess'] = SupplyCategory::select('id','name');
            if(!empty($category)){
                $data['categoriess']->where('id','=',$category);
            }else{
                $data['categoriess']->whereIn('id',$categoriesIdss);
            }

            $data['categoriess']->where('status_id','=',1);
            $data['categoriess']->orderBy('id');
            $data['categoriess'] = $data['categoriess']->get();


            foreach ($data['categoriess'] as $ct){
                $data['recent_tenders'][$ct->name] = DB::table($zoneInfo->alise.'_tenders')
                    ->whereDate('valid_date_from', '<=',date('Y-m-d'))
                    ->whereDate('tender_opening_date', '>=',date('Y-m-d'))
                    ->where('status_id','=',1)
                    ->whereNotNull('notice')
                    //->where('open_tender','=',1)
                    ->where('nsd_id','=',$navalLocation->id)
                    ->where('tender_cat_id','=',$ct->id);
                if(!empty($key)){
                    $data['recent_tenders'][$ct->name]->where(function($query) use ($key){
                        $query->where('po_number', 'like', "%{$key}%");
                        $query->orWhere('tender_title', 'like', "%{$key}%");
                        $query->orWhere('tender_number', 'like', "%{$key}%");
                    });
                }
                if(!empty($from)){
                    $data['recent_tenders'][$ct->name]->where(function($query) use ($from ){
                        $query->whereDate('tender_opening_date','>=',$from);
                    });
                }
                if(!empty($to)){
                    $data['recent_tenders'][$ct->name]->where(function($query) use ($to){
                        $query->whereDate('tender_opening_date','<=',$to);
                    });
                }
                $data['recent_tenders'][$ct->name]->orderBy('id', 'desc');
                $data['recent_tenders'][$ct->name] = $data['recent_tenders'][$ct->name]->paginate(10);

                foreach ($data['recent_tenders'][$ct->name] as $a){

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

            }


            $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->take(5)->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->orderBy('id','desc')->get();
            $categoriesIds = array_unique(array_map('current',DB::table($zoneInfo->alise.'_tenders')->select('tender_cat_id')->where('nsd_id','=',$navalLocation->id)->where('status_id','=',1)->get()->toArray()));

            $tender = $zoneInfo->alise.'_tenders';

            $data['suppliers']=\App\PoDatas::leftJoin($tender,"po_datas.tender_id",$tender.".id")
                ->leftJoin("demand_to_collection_quotation","po_datas.selected_supplier","demand_to_collection_quotation.id")
                ->leftJoin($zoneInfo->alise.'_suppliers',"demand_to_collection_quotation.supplier_name",$zoneInfo->alise."_suppliers.id")
                ->select($zoneInfo->alise.'_suppliers.id',$zoneInfo->alise.'_suppliers.company_name')
                ->where('po_datas.po_approve_status','=',1)
                ->whereNotNull('po_datas.selected_supplier')
                ->distinct($zoneInfo->alise.'_suppliers')
                ->orderby('po_datas.po_approve_date', 'desc')->get();

        }

        // Only for naval location =========================================================================
        if(!empty($zone) && !empty($nsd)){
            $zoneInfo = Zone::where('alise','=',$zone)->first();
            $navalLocation = NsdName::where('alise','=',$nsd)->orderBy('id')->first();

            $categoriesIdss = array_unique(array_map('current',DB::table($zoneInfo->alise.'_tenders')->select('tender_cat_id')->where('status_id','=',1)->get()->toArray()));
            $data['categoriess'] = SupplyCategory::select('id','name');
            if(!empty($category)){
                $data['categoriess']->where('id','=',$category);
            }else{
                $data['categoriess']->whereIn('id',$categoriesIdss);
            }

            $data['categoriess']->where('status_id','=',1);
            $data['categoriess']->orderBy('id');
            $data['categoriess'] = $data['categoriess']->get();


            foreach ($data['categoriess'] as $ct){
                $data['recent_tenders'][$ct->name] = DB::table($zoneInfo->alise.'_tenders')
                    ->whereDate('valid_date_from', '<=',date('Y-m-d'))
                    ->whereDate('tender_opening_date', '>=',date('Y-m-d'))
                    ->where('status_id','=',1)
                    ->whereNotNull('notice')
                    //->where('open_tender','=',1)
                    ->where('nsd_id','=',$navalLocation->id)
                    ->where('tender_cat_id','=',$ct->id);
                if(!empty($key)){
                    $data['recent_tenders'][$ct->name]->where(function($query) use ($key){
                        $query->where('po_number', 'like', "%{$key}%");
                        $query->orWhere('tender_title', 'like', "%{$key}%");
                        $query->orWhere('tender_number', 'like', "%{$key}%");
                    });
                }
                if(!empty($from)){
                    $data['recent_tenders'][$ct->name]->where(function($query) use ($from ){
                        $query->whereDate('tender_opening_date','>=',$from);
                    });
                }
                if(!empty($to)){
                    $data['recent_tenders'][$ct->name]->where(function($query) use ($to){
                        $query->whereDate('tender_opening_date','<=',$to);
                    });
                }
                $data['recent_tenders'][$ct->name]->orderBy('id', 'desc');
                $data['recent_tenders'][$ct->name] = $data['recent_tenders'][$ct->name]->paginate(10);

                foreach ($data['recent_tenders'][$ct->name] as $a){


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

            }

            $data['notices'] = Notice::where('status_id','=',1)->where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_general',1); })->take(5)->orderBy('id','desc')->get();
            $noticeIds = array();
            foreach ($data['notices'] as $ntc){
                if(in_array($navalLocation->id, explode(',',$ntc->nsds_bsds))){
                    $noticeIds[] = $ntc->id;
                }
            }

            $data['importantNotices'] = Notice::where(function($query){ $query->where(['is_important'=>1,'is_general'=>1])->orWhere('is_important',1); })->where('status_id','=',1)->orderBy('id','desc')->get();

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

        $data['from'] = $from;
        $data['to'] = $to;


        $tender = $zoneInfo->alise.'_tenders';
        $suppliers = Input::get('suppliers');
        $tender_number = Input::get('tender_number');

        $poLatest100= \App\PoDatas::leftJoin($tender,"po_datas.tender_id",$tender.".id")
            ->leftJoin("demand_to_collection_quotation","po_datas.selected_supplier","demand_to_collection_quotation.id")
            ->select($tender.".tender_title",$tender.".tender_number",$tender.".valid_date_from","demand_to_collection_quotation.supplier_name","demand_to_collection_quotation.suppliernametext","po_datas.po_approve_date as poApprovedDate")
            ->where('po_datas.po_approve_status','=',1)
            ->whereNotNull('po_datas.selected_supplier')
            ->orderby('po_datas.po_approve_date', 'desc');

        if(!empty($suppliers))
        {
            $poLatest100= \App\PoDatas::leftJoin($tender,"po_datas.tender_id",$tender.".id")
                ->leftJoin("demand_to_collection_quotation","po_datas.selected_supplier","demand_to_collection_quotation.id")
                ->select($tender.".tender_title",$tender.".tender_number",$tender.".valid_date_from","demand_to_collection_quotation.supplier_name","demand_to_collection_quotation.suppliernametext","po_datas.po_approve_date as poApprovedDate")
                ->where('po_datas.po_approve_status','=',1)
                ->where("demand_to_collection_quotation.supplier_name",$suppliers)
                ->whereNotNull('po_datas.selected_supplier')
                ->orderby('po_datas.po_approve_date', 'desc');
        }


        if(!empty($tender_number))
        {
            $poLatest100= \App\PoDatas::leftJoin($tender,"po_datas.tender_id",$tender.".id")
                ->leftJoin("demand_to_collection_quotation","po_datas.selected_supplier","demand_to_collection_quotation.id")
                ->select($tender.".tender_title",$tender.".tender_number",$tender.".valid_date_from","demand_to_collection_quotation.supplier_name","demand_to_collection_quotation.suppliernametext","po_datas.po_approve_date as poApprovedDate")
                ->where('po_datas.po_approve_status','=',1)
                ->where($tender.".tender_number",'like',"%$tender_number%")
                ->whereNotNull('po_datas.selected_supplier')
                ->orderby('po_datas.po_approve_date', 'desc');
        }

        $poLatest100=$poLatest100->take(100)
            ->get();


        $perPage = 10;

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        if ($currentPage == 1) {
            $start = 0;
        }
        else {
            $start = ($currentPage - 1) * $perPage;
        }

        $currentPageCollection = $poLatest100->slice($start, $perPage)->all();

        $paginatedTop100 = new LengthAwarePaginator($currentPageCollection, count($poLatest100), $perPage);

        $paginatedTop100->setPath(LengthAwarePaginator::resolveCurrentPath());

        $data['top100Pos'] = $paginatedTop100;



        return view('frontend.po-winners', $data);
    }

}



