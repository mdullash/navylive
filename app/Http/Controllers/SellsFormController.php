<?php

namespace App\Http\Controllers;

use App\SellsForm;
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
class SellsFormController extends Controller
{
    private $moduleId = 47;
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
        $mobile_number = Input::get('mobile_number');
        $from            = Input::get('from');
        $todate          = Input::get('todate');

        $suppliers = SellsForm::whereNotNull('id')->orderBy('id','desc');

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
        return View::make('suppliers.sell-form.index')->with(compact('suppliers','mobile_number','from','todate'));

    }

    public function create(){

        $previousUrl = \URL::previous();
        Session::put('preUrlFroSchedulre', $previousUrl);

        return View::make('suppliers.sell-form.create');
    }


    public function store(Request $request){

        OwnLibrary::validateAccess($this->moduleId,2);
        $this->middleware('csrf', array('on' => 'post'));

        $sellsForm=New SellsForm();
        $sellsForm->company_name=$request->company_name;
        $sellsForm->mobile_number=$request->mobile_number;
        $sellsForm->email =$request->email;
        $sellsForm->password=123456;
        $sellsForm->amount=$request->amount;
        $sellsForm->save();
        $supplier=Supplier::where('mobile_number',$request->mobile_number)->first();
        $supplier->enlistment_status='waiting-for-supplier-submit';
        if ($supplier->save()){
        Session::flash( 'success', 'Schedule Created Successfully' );
        return Redirect::to( 'suppliers/sells-from/print-sells-from/' . $sellsForm->id );
         } else {
         $message = 'Somthing went Wrong !!!';
            Session::flash( 'success',$message );
            return \redirect()->back();
        }
      }


    public function print($id=null){
        $secId = $id;

        $sheCdInfo      = SellsForm::find($secId);
       $nssdPhone=Settings::find(1)->phone;

        $formData = [
            'sheCdInfo' => $sheCdInfo,
            'nssdPhone' => $nssdPhone,
        ];

        $pdf= PDF::loadView('suppliers.sell-form.sell-form-pdf',$formData,[],['format' =>  [78, 110]]);
        return $pdf->stream('sell-form.pdf');

    }
    public function show(){}
    public function edit(){}
    public function update(){}

    public function destroy($id)
    {
        OwnLibrary::validateAccess($this->moduleId,4);
        $deno = SellsForm::find($id);

        if ($deno->delete()) {
            Session::flash('success', 'This Row Deleted Successfully');
            return Redirect::to('suppliers/sells-form');
        } else {
            Session::flash('error', 'This Row Not Found');
            return Redirect::to('suppliers/sells-form');
        }
    }


}
