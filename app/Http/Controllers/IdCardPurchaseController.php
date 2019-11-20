<?php

namespace App\Http\Controllers;

use App\IdCardPurchase;
use App\Settings;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use View;
use Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use functions\OwnLibrary;
use PDF;

class IdCardPurchaseController extends Controller
{
    private $moduleId = 58;
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
        $barcode_number = Input::get('barcode_number');
        $from            = Input::get('from');
        $todate          = Input::get('todate');

        $suppliers = IdCardPurchase::whereNotNull('id')->orderBy('id','desc');

        if(!empty($from)){
            $suppliers->where(function($query) use ($from ){
                $query->whereDate('created_at','>=',$from);
            });
        }
        if(!empty($todate)){
            $suppliers->where(function($query) use ($todate ){
                $query->whereDate('created_at','<=',$todate);
            });
        }

        $suppliers = $suppliers->orderBy('id','DESC')->paginate(10);
        return View::make('suppliers.id-card-purchase.index')->with(compact('suppliers','barcode_number','from','todate'));

    }

    public function create(){

        $previousUrl = \URL::previous();
        Session::put('preUrlFroSchedulre', $previousUrl);

        return View::make('suppliers.id-card-purchase.create');
    }


    public function store(Request $request){

        OwnLibrary::validateAccess($this->moduleId,2);
        $this->middleware('csrf', array('on' => 'post'));

        $idCardPurchase=New IdCardPurchase();
        $idCardPurchase->company_name=$request->company_name;
        $idCardPurchase->barcode_number=$request->barcode_number;
        $idCardPurchase->supplier_name=$request->supplier_name;
        $idCardPurchase->mobile_number=$request->mobile_number;
        $idCardPurchase->email =$request->email;
        $idCardPurchase->amount=$request->amount;
        if ($idCardPurchase->save()){
            Session::flash( 'success', 'Schedule Created Successfully' );
            return Redirect::to( 'suppliers/id-card-purchase/print-id-card-purchase/' . $idCardPurchase->id );
        } else {
            $message = 'Somthing went Wrong !!!';
            Session::flash( 'success',$message );
            return \redirect()->back();
        }
    }


    public function print($id=null){
        $secId = $id;

        $sheCdInfo      = IdCardPurchase::find($secId);
        $nssdPhone=Settings::find(1)->phone;

        $formData = [
            'sheCdInfo' => $sheCdInfo,
            'nssdPhone' => $nssdPhone,
        ];

        $pdf= PDF::loadView('suppliers.id-card-purchase.id-card-purchase-pdf',$formData,[],['format' =>  [78, 110]]);
        return $pdf->stream('id-card-purchase.pdf');

    }
    public function show(){}
    public function edit(){}
    public function update(){}

    public function destroy($id)
    {
        OwnLibrary::validateAccess($this->moduleId,4);
        $deno = IdCardPurchase::find($id);

        if ($deno->delete()) {
            Session::flash('success', 'This Row Deleted Successfully');
            return Redirect::to('suppliers/id-card-purchase');
        } else {
            Session::flash('error', 'This Row Not Found');
            return Redirect::to('suppliers/id-card-purchase');
        }
    }

}
