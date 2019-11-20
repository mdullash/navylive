<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EventManagement;
use Illuminate\Support\Facades\Auth;
use Session;
use View;
use Input;
use Illuminate\Support\Facades\Redirect;
use functions\OwnLibrary;
use App\Currency;
use Illuminate\Database\Schema\Blueprint;
use Schema;

class EvaluatedTenderController extends Controller
{

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
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
        
        $queryResult   = \App\Supplier::orderBy($this->tableAlies.'_suppliers.company_name')->leftJoin('evaluated_tender','evaluated_tender.supplier_id_alise','=',$this->tableAlies.'_suppliers.all_org_id')
            ->leftJoin('evaluation_criteria','evaluation_criteria.id','=','evaluated_tender.evalu_citeria_id')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.all_org_tender_id','=','evaluated_tender.tender_id_alise')
            ->select($this->tableAlies.'_suppliers.all_org_id as supplirId',$this->tableAlies.'_suppliers.company_name',
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

        );
        if(!empty($sup_id)){
            $queryResult->where($this->tableAlies.'_suppliers.all_org_id','=',$sup_id);
        }
        if(!empty($from) || !empty($todate) || !empty($ten_number)){
            $queryResult->whereIn('evaluated_tender.tender_id_alise',$tenderIds);
        }
        if(!empty(\Auth::user()->nsd_bsd)){
            $queryResult->whereRaw("find_in_set('".\Auth::user()->nsd_bsd."', nsd_suppliers.registered_nsd_id)");
        }
        //->where($this->tableAlies.'_suppliers.all_org_id','=',371)
        $queryResult->groupBy($this->tableAlies.'_suppliers.all_org_id');
        $queryResult = $queryResult->paginate(25);
        // echo "<pre>"; print_r($queryResult); exit;
        $search_supplier_name = '';
        if(!empty($sup_id)){
            $search_supplier_name = \App\Supplier::find($sup_id);;
        }
        $serchTenderNumber = '';
        if(!empty($ten_number)){
            $serchTenderNumber = \App\Tender::find($ten_number);;
        }

        $pointTableDatas = \App\EvaluationPointTable::where('status','=',1)->get();
       return View::make('evaluated-tender.index')->with(compact('queryResult','pointTableDatas','sup_id','search_supplier_name','ten_number','serchTenderNumber'));

    }

    public function evaluatedTenderQuaterly()
    {   
        $this->tableAlies = \Session::get('zoneAlise');
        $evaluCiterias = \App\EvaluationCriteria::all();
        $supplierstn = $this->tableAlies.'_suppliers.all_org_id';

        $from       = '';
        $todate     = '';
        $sup_id     = Input::get('sup_id');
        $ten_number = Input::get('ten_number'); // tender id
        $quater     = Input::get('quater');
        $year       = !empty(Input::get('year')) ? Input::get('year') : date('Y');
        if(!empty($quater) && !empty($year)){
            if($quater==1){
                $from      = $year.'-01-01';
                $todate    = $year.'-03-31';
            }
            if($quater==2){
                $from      = $year.'-04-01';
                $todate    = $year.'-06-30';
            }
            if($quater==3){
                $from      = $year.'-06-01';
                $todate    = $year.'-09-30';
            }
            if($quater==4){
                $from      = $year.'-10-01';
                $todate    = $year.'-12-31';
            }
        }
        
        $tenderIds = array();
       
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

        $totalTender = \App\Tender::whereNotNull('id');
        if(!empty($from) || !empty($todate)){ 
                    if(!empty($from)){
                        $totalTender->whereDate($this->tableAlies.'_tenders.tender_opening_date','>=',$from);
                    }
                    if(!empty($todate)){
                        $totalTender->whereDate($this->tableAlies.'_tenders.tender_opening_date','<=',$todate);
                    }
        }
        if(!empty(\Auth::user()->nsd_bsd)){
            $totalTender->whereRaw("find_in_set('".\Auth::user()->nsd_bsd."', nsd_id)");
        }
        $totalTender = $totalTender->distinct('tender_number')->count();
        
        $queryResult   = \App\Supplier::leftJoin('evaluated_tender','evaluated_tender.supplier_id_alise','=',$this->tableAlies.'_suppliers.all_org_id')
            ->leftJoin('evaluation_criteria','evaluation_criteria.id','=','evaluated_tender.evalu_citeria_id')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.all_org_tender_id','=','evaluated_tender.tender_id_alise')
            ->leftJoin('demand_to_collection_quotation','demand_to_collection_quotation.supplier_name','=',$this->tableAlies.'_suppliers.all_org_id') // newly added
            ->select($this->tableAlies.'_suppliers.all_org_id as supplirId',$this->tableAlies.'_suppliers.company_name',
            \DB::raw("(select SUM((evaluated_tender.marks)*evaluation_criteria.weight) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 1 ) as c1, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 1  && evaluated_tender.marks != 0 ) as c1c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 1 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 1) ) as c1cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 2 ) as c2, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 2  && evaluated_tender.marks != 0 ) as c2c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 2 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 2 ) ) as c2cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 3 ) as c3, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 3  && evaluated_tender.marks != 0 ) as c3c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 3 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 3 ) ) as c3cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 4 ) as c4, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 4  && evaluated_tender.marks != 0 ) as c4c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 4 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 4 ) ) as c4cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 5 ) as c5, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 5  && evaluated_tender.marks != 0 ) as c5c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 5 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 5 ) ) as c5cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 6 ) as c6, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 6  && evaluated_tender.marks != 0 ) as c6c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 6 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 6 ) ) as c6cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 7 ) as c7, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 7  && evaluated_tender.marks != 0 ) as c7c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 7 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 7 ) ) as c7cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 8 ) as c8, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 8  && evaluated_tender.marks != 0 ) as c8c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 8 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 8 ) ) as c8cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 9 ) as c9, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 9  && evaluated_tender.marks != 0 ) as c9c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 9 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 9 ) ) as c9cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 10 ) as c10, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 10  && evaluated_tender.marks != 0 ) as c10c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 10 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 10 ) ) as c10cm")

            // \DB::raw("(select count(DISTINCT (demand_to_collection_quotation.tender_id_alise)) FROM demand_to_collection_quotation WHERE ".$supplierstn." = demand_to_collection_quotation.supplier_name ) as nop")
        );
        if(!empty($sup_id)){
            $queryResult->where($this->tableAlies.'_suppliers.all_org_id','=',$sup_id);
        }
        if(!empty($from) || !empty($todate) || !empty($ten_number)){
            $queryResult->whereIn('evaluated_tender.tender_id_alise',$tenderIds);
        }
        if(!empty(\Auth::user()->nsd_bsd)){
            $queryResult->whereRaw("find_in_set('".\Auth::user()->nsd_bsd."', nsd_suppliers.registered_nsd_id)");
        }
        //->where($this->tableAlies.'_suppliers.all_org_id','=',371)
        $queryResult->groupBy($this->tableAlies.'_suppliers.all_org_id');
        $queryResult = $queryResult->paginate(25);
        //echo "<pre>"; print_r($queryResult); exit;
        $pointTableDatas = \App\EvaluationPointTable::where('status','=',1)->get();

        if(empty($quater)){
            $curMonth    = date('m');
            if($curMonth<4 && $curMonth>0){
                $quater    = 1;
            }
            if($curMonth<7 && $curMonth>3){
                $quater    = 2;
            }
            if($curMonth<10 && $curMonth>6){
                $quater    = 3;
            }
            if($curMonth<13 && $curMonth>9){
                $quater    = 4;
            }
        }
        $search_supplier_name = '';
        if(!empty($sup_id)){
            $search_supplier_name = \App\Supplier::find($sup_id);;
        }
        $serchTenderNumber = '';
        if(!empty($ten_number)){
            $serchTenderNumber = \App\Tender::find($ten_number);;
        }
       return View::make('evaluated-tender.evaluated-tender-quaterly')->with(compact('queryResult','pointTableDatas','year','quater','sup_id','search_supplier_name','ten_number','serchTenderNumber','totalTender'));

    }

    public function yearlyPerformanceEvaluation()
    {   
        $this->tableAlies = \Session::get('zoneAlise');
        $evaluCiterias = \App\EvaluationCriteria::all();
        $supplierstn = $this->tableAlies.'_suppliers.all_org_id';

        $year       = '';
        $sup_id     = Input::get('sup_id');
        $ten_number = Input::get('ten_number'); // tender id
        $tenderIds  = array();
       
        if(!empty(Input::get('year')) || !empty($ten_number)){
            $year      = Input::get('year');
            $tenderIds = \App\Tender::select('id')->whereNotNull('id');
                            if(!empty($ten_number)){
                                $tenderIds->where($this->tableAlies.'_tenders.id','=',$ten_number);
                            }
                            if(!empty(Input::get('year'))){
                                $tenderIds->whereYear($this->tableAlies.'_tenders.tender_opening_date','>=',$year);
                            }
            $tenderIds = $tenderIds->get()->toArray(); 
            $tenderIds = array_map('current',$tenderIds);
        }
        if(empty(Input::get('year'))){
            $year      = date('Y');
        }

        $totalTender = \App\Tender::whereNotNull('id');
        if(!empty(Input::get('year'))){ 
                    if(!empty(Input::get('year'))){
                        $totalTender->whereYear($this->tableAlies.'_tenders.tender_opening_date','>=',$year);
                    }
        }
        if(!empty(\Auth::user()->nsd_bsd)){
            $totalTender->whereRaw("find_in_set('".\Auth::user()->nsd_bsd."', nsd_id)");
        }
        $totalTender = $totalTender->distinct('tender_number')->count();
        
        $queryResult   = \App\Supplier::leftJoin('evaluated_tender','evaluated_tender.supplier_id_alise','=',$this->tableAlies.'_suppliers.all_org_id')
            ->leftJoin('evaluation_criteria','evaluation_criteria.id','=','evaluated_tender.evalu_citeria_id')
            ->leftJoin($this->tableAlies.'_tenders',$this->tableAlies.'_tenders.all_org_tender_id','=','evaluated_tender.tender_id_alise')
            ->leftJoin('demand_to_collection_quotation','demand_to_collection_quotation.supplier_name','=',$this->tableAlies.'_suppliers.all_org_id') // newly added
            ->select($this->tableAlies.'_suppliers.all_org_id as supplirId',$this->tableAlies.'_suppliers.company_name',
            \DB::raw("(select SUM((evaluated_tender.marks)*evaluation_criteria.weight) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 1 ) as c1, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 1  && evaluated_tender.marks != 0 ) as c1c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 1 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 1) ) as c1cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 2 ) as c2, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 2  && evaluated_tender.marks != 0 ) as c2c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 2 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 2 ) ) as c2cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 3 ) as c3, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 3  && evaluated_tender.marks != 0 ) as c3c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 3 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 3 ) ) as c3cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 4 ) as c4, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 4  && evaluated_tender.marks != 0 ) as c4c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 4 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 4 ) ) as c4cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 5 ) as c5, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 5  && evaluated_tender.marks != 0 ) as c5c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 5 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 5 ) ) as c5cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 6 ) as c6, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 6  && evaluated_tender.marks != 0 ) as c6c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 6 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 6 ) ) as c6cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 7 ) as c7, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 7  && evaluated_tender.marks != 0 ) as c7c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 7 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 7 ) ) as c7cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 8 ) as c8, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 8  && evaluated_tender.marks != 0 ) as c8c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 8 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 8 ) ) as c8cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 9 ) as c9, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 9  && evaluated_tender.marks != 0 ) as c9c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 9 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 9 ) ) as c9cm"),

            \DB::raw("(select SUM(evaluated_tender.marks) FROM evaluated_tender WHERE  ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 10 ) as c10, (select count((evaluated_tender.marks)) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 10  && evaluated_tender.marks != 0 ) as c10c, (select evaluated_tender.citeria_comment FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.evalu_citeria_id = 10 && evaluated_tender.id = ( SELECT MAX(evaluated_tender.id) FROM evaluated_tender WHERE ".$supplierstn." = evaluated_tender.supplier_id_alise && evaluated_tender.citeria_comment IS NOT NULL && evaluated_tender.evalu_citeria_id = 10 ) ) as c10cm")

            // \DB::raw("(select count(DISTINCT (demand_to_collection_quotation.tender_id_alise)) FROM demand_to_collection_quotation WHERE ".$supplierstn." = demand_to_collection_quotation.supplier_name ) as nop")
        );
        if(!empty($sup_id)){
            $queryResult->where($this->tableAlies.'_suppliers.all_org_id','=',$sup_id);
        }
        if(!empty(Input::get('year')) || !empty($ten_number)){
            $queryResult->whereIn('evaluated_tender.tender_id_alise',$tenderIds);
        }
        if(!empty(\Auth::user()->nsd_bsd)){
            $queryResult->whereRaw("find_in_set('".\Auth::user()->nsd_bsd."', nsd_suppliers.registered_nsd_id)");
        }
        //->where($this->tableAlies.'_suppliers.all_org_id','=',371)
        $queryResult->groupBy($this->tableAlies.'_suppliers.all_org_id');
        $queryResult = $queryResult->paginate(25);
       // echo "<pre>"; print_r($queryResult); exit;
        $pointTableDatas = \App\EvaluationPointTable::where('status','=',1)->get();

        $search_supplier_name = '';
        if(!empty($sup_id)){
            $search_supplier_name = \App\Supplier::find($sup_id);;
        }
        $serchTenderNumber = '';
        if(!empty($ten_number)){
            $serchTenderNumber = \App\Tender::find($ten_number);;
        }
        
       return View::make('evaluated-tender.evaluated-tender-yearly')->with(compact('queryResult','pointTableDatas','year','sup_id','search_supplier_name','ten_number','serchTenderNumber','totalTender'));

    }


}
