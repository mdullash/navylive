<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EventManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use View;
use Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use functions\OwnLibrary;
use App\SupplyCategory;
use App\NsdName;
use App\Demand;
use App\ItemToDemand;
use App\GroupName;
use App\Supplier;
use App\Tender;
use App\Item;
use App\Deno;
use App\DemandToCollectionQuotation;
use App\DemandSuppllierToCollQuotToItem;
use App\DemandCrToInspection;
use App\TenderSchedule;
use PDF;


class TenderScheduleController extends Controller
{

    private $moduleId = 35;
    private $tableAlies;

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function index() 
    {   
        $schedual_search = Input::get('schedual_search');
        $from            = Input::get('from');
        $todate          = Input::get('todate');

        $demands = TenderSchedule::whereNotNull('id')->orderBy('id','desc');
                if(!empty($schedual_search)){
                    $demands->where(function($query) use ($schedual_search ){
                        $query->where('tender_number', 'like',"%{$schedual_search}%");
                        $query->orWhere('supplier_reg_no_ro_brc', 'like', "%{$schedual_search}%");
                    });
                }
                if(!empty($from)){
                    $demands->where(function($query) use ($from ){
                        $query->whereDate('created_at','>=',$from);
                    });
                }
                if(!empty($todate)){
                    $demands->where(function($query) use ($todate ){
                        $query->whereDate('created_at','<=',$todate);
                    });
                }

        $demands = $demands->paginate(10);
        return View::make('schedule.index')->with(compact('demands','schedual_search','from','todate'));

    }

    public function scheduleCreate(){

        $previousUrl = \URL::previous();
        Session::put('preUrlFroSchedulre', $previousUrl);

        //$demandId = $id;
        return View::make('schedule.create');
    }

     public function printScheduleRed($message){
      Session::flash('error',$message);
      return redirect('schedule-create');
  }


public function tenderSchedulePost(Request $request){
        $previousUrl = \Session::get('preUrlFroSchedulre');
        
        OwnLibrary::validateAccess($this->moduleId,2);

        $this->middleware('csrf', array('on' => 'post'));

        if (!isset($request->supplier_id) || empty($request->supplier_id) && $request->supplier_id == null){
            $userMultiInfo = DB::table(\Session::get("zoneAlise").'_supplier_multi_info')
                               ->join(\Session::get("zoneAlise").'_suppliers', \Session::get("zoneAlise").'_supplier_multi_info.supplier_id', '=',\Session::get("zoneAlise").'_suppliers.id')
                               ->select( \Session::get("zoneAlise").'_supplier_multi_info.*',\Session::get("zoneAlise").'_suppliers.company_name',\Session::get("zoneAlise").'_suppliers.trade_license_address')
                                ->where(\Session::get("zoneAlise").'_supplier_multi_info.barcode_number','=',$request->supplier_reg_no)
                               ->first();

            if ($userMultiInfo) {
                $checkAvailability = TenderSchedule::where('tender_id','=',$request->tender_id)
                                                   ->where('supplier_id','=',$userMultiInfo->supplier_id)
                    //->where('demand_id','=',$request->demand_id)
                                                   ->get();



                if(count($checkAvailability) > 0){
                    $message = 'This supplier already taken schedule for this tender.';
                    return Redirect::to('print-schedule-red/'.$message);
                }
                $rules = array(
                    'tender_number' => 'required',
                );

                $message = array(
                    'tender_number.required' => 'Tender number is required!',
                );

                $v = Validator::make( Input::all(), $rules, $message );
                if ( $v->fails() ) {
                    return redirect( 'schedule-create' )->withErrors( $v->errors() )->withInput( Input::except( array( 'tender_number' ) ) );
                } else {
                    $tnSche = new TenderSchedule();

                    //$tnSche->demand_id              = $request->demand_id;
                    $tnSche->tender_id              = $request->tender_id;
                    $tnSche->tender_number          = $request->tender_number;
                    $tnSche->supplier_id            = $userMultiInfo->supplier_id;
                    $tnSche->supplier_reg_no_ro_brc = $request->supplier_reg_no;
                    $tnSche->amount                 = $request->amount;
                    $tnSche->total_page             = $request->total_page;

                    if ( $tnSche->save() ) {

                        //$url = "print-schedule/".$tnSche->id;
                        //echo "<script>window.open('".$url."', '_blank')</script>";
                        Session::flash( 'success', 'Schedule Created Successfully' );

                        return Redirect::to( 'print-schedule/' . $tnSche->id );
                        //return Redirect::to('print-schedule/'.$tnSche->id);
                    } else {
                        $message = 'Somthing went Wrong !!!';
                        return Redirect::to('print-schedule-red/'.$message);
                    }
                }
                }
            else{
                $message = 'Barcode note found';
                return Redirect::to('print-schedule-red/'.$message);
                }
            }else{

            $checkValidtion = TenderSchedule::where('tender_id','=',$request->tender_id)
                                            ->where('supplier_id','=',$request->supplier_id)
                //->where('demand_id','=',$request->demand_id)
                                            ->get();
            if(count($checkValidtion)>0){
                Session::flash('error', 'This supplier already taken schedule for this tender.');
                return Redirect::to($previousUrl);
            }

            $rules =  array(
                'tender_number' => 'required',
                'supplier_reg_no' => 'required',
                'company_name' => 'required',
                'tender_id' => 'required',
                'supplier_id' => 'required',
            );

            $message = array(
                'name.tender_id' => 'Tender number is required!',
                'name.supplier_id' => 'Supplier Barcode is required!',
            );

            $v = Validator::make(Input::all(), $rules, $message);

            if ($v->fails()) {
                return redirect('schedule-create')->withErrors($v->errors())->withInput(Input::except(array('tender_number')));
            }else {

                $tnSche = new TenderSchedule();

                //$tnSche->demand_id              = $request->demand_id;
                $tnSche->tender_id              = $request->tender_id;
                $tnSche->tender_number          = $request->tender_number;
                $tnSche->supplier_id            = $request->supplier_id;
                $tnSche->supplier_reg_no_ro_brc = $request->supplier_reg_no;
                $tnSche->amount                 = $request->amount;
                $tnSche->total_page             = $request->total_page;

                if($tnSche->save()){

                    //$url = "print-schedule/".$tnSche->id;
                    //echo "<script>window.open('".$url."', '_blank')</script>";

                    Session::flash('success', 'Schedule Created Successfully');
                    return Redirect::to('print-schedule/'.$tnSche->id);
                    //return Redirect::to('print-schedule/'.$tnSche->id);
                }else{

                }
            }
        }

    }

    // Old Function
    // public function tenderSchedulePost(Request $request){

    //     $previousUrl = \Session::get('preUrlFroSchedulre');
        
    //     OwnLibrary::validateAccess($this->moduleId,2);

    //     $this->middleware('csrf', array('on' => 'post'));

    //     $checkValidtion = TenderSchedule::where('tender_id','=',$request->tender_id)
    //                                     ->where('supplier_id','=',$request->supplier_id)
    //                                     //->where('demand_id','=',$request->demand_id)
    //                                     ->get();
    //     if(count($checkValidtion)>0){
    //         Session::flash('error', 'This supplier already taken schedule for this tender.');
    //         return Redirect::to($previousUrl);
    //     }                                

    //     $rules =  array(
    //         'tender_number' => 'required',
    //         'supplier_reg_no' => 'required',
    //         'company_name' => 'required',
    //         'tender_id' => 'required',
    //         'supplier_id' => 'required',
    //     );

    //     $message = array(
    //         'name.tender_id' => 'Tender number is required!',
    //         'name.supplier_id' => 'Supplier Barcode is required!',
    //     );

    //     $v = Validator::make(Input::all(), $rules, $message);

    //     if ($v->fails()) {
    //         return redirect('schedule-create')->withErrors($v->errors())->withInput(Input::except(array('tender_number')));
    //     }else {

    //         $tnSche = new TenderSchedule();

    //         //$tnSche->demand_id              = $request->demand_id;
    //         $tnSche->tender_id              = $request->tender_id;
    //         $tnSche->tender_number          = $request->tender_number;
    //         $tnSche->supplier_id            = $request->supplier_id;
    //         $tnSche->supplier_reg_no_ro_brc = $request->supplier_reg_no;
    //         $tnSche->amount                 = $request->amount;
    //         $tnSche->total_page             = $request->total_page;

    //         if($tnSche->save()){

    //             Session::flash('success', 'Schedule Created Successfully');
    //             return Redirect::to('print-schedule/'.$tnSche->id);
    //         }else{

    //         }
            

    //     }

    // }

    public function printSchedule($id=null){
        $secId = $id;

        $sheCdInfo      = TenderSchedule::find($secId);
        $supplierInfo   = Supplier::find($sheCdInfo->supplier_id);
        $tenderInfo     = Tender::find($sheCdInfo->tender_id);
        
        $tenderData = [
                'sheCdInfo' => $sheCdInfo,
                'supplierInfo' => $supplierInfo,
                'tenderInfo' => $tenderInfo
            ];
        
        $pdf= PDF::loadView('schedule.schedule-pdf',$tenderData,[],['format' =>  [78, 110]]);
        return $pdf->stream('schedule.pdf');

    }

    public function destroy($id)
    { 
        OwnLibrary::validateAccess($this->moduleId,4);
        $deno = TenderSchedule::find($id);
        
        if ($deno->delete()) {
                Session::flash('success', 'Tender Schedule Deleted Successfully');
                return Redirect::to('schedule-all');
            } else {
                Session::flash('error', 'Tender Schedule Not Found');
                return Redirect::to('schedule-all');
            }
    }


}
