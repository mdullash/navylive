<?php

namespace App\Http\Controllers;

use App\RenewSupplier;
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

class RenewSupplierController extends Controller
{
    private $moduleId = 57;
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

        $suppliers = RenewSupplier::whereNotNull('id')->orderBy('id','desc');

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
        return View::make('suppliers.renew-supplier.index')->with(compact('suppliers','barcode_number','from','todate'));

    }

    public function create(){

        $previousUrl = \URL::previous();
        Session::put('preUrlFroSchedulre', $previousUrl);

        return View::make('suppliers.renew-supplier.create');
    }


    public function store(Request $request){

        OwnLibrary::validateAccess($this->moduleId,2);
        $this->middleware('csrf', array('on' => 'post'));

        $renewSupplier=New RenewSupplier();
        $renewSupplier->company_name=$request->company_name;
        $renewSupplier->barcode_number=$request->barcode_number;
        $renewSupplier->supplier_name=$request->supplier_name;
        $renewSupplier->mobile_number=$request->mobile_number;
        $renewSupplier->email =$request->email;
        $renewSupplier->amount=$request->amount;
        $renewSupplier->save();
        $supplier=Supplier::where('mobile_number',$request->mobile_number)->first();
        if (date('m')>=6){
            $expired_date=date('Y-06-31', strtotime('+1 years'));;
        }else{
            $expired_date=date('Y-06-31');

        }
        $supplier->expired_date = $expired_date;

        if ($supplier->save()){
            Session::flash( 'success', 'Schedule Created Successfully' );
            return Redirect::to( 'suppliers/renew-supplier/print-renew-supplier/' . $renewSupplier->id );
        } else {
            $message = 'Somthing went Wrong !!!';
            Session::flash( 'success',$message );
            return \redirect()->back();
        }
    }


    public function print($id=null){
        $secId = $id;

        $sheCdInfo      = RenewSupplier::find($secId);
        $nssdPhone=Settings::find(1)->phone;

        $formData = [
            'sheCdInfo' => $sheCdInfo,
            'nssdPhone' => $nssdPhone,
        ];

        $pdf= PDF::loadView('suppliers.renew-supplier.renew-supplier-pdf',$formData,[],['format' =>  [78, 110]]);
        return $pdf->stream('renew-supplier.pdf');

    }
    public function show(){}
    public function edit(){}
    public function update(){}

    public function destroy($id)
    {
        OwnLibrary::validateAccess($this->moduleId,4);
        $deno = RenewSupplier::find($id);

        if ($deno->delete()) {
            Session::flash('success', 'This Row Deleted Successfully');
            return Redirect::to('suppliers/renew-supplier');
        } else {
            Session::flash('error', 'This Row Not Found');
            return Redirect::to('suppliers/renew-supplier');
        }
    }

}
