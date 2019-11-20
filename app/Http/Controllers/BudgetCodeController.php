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
use App\SupplyCategory;
use App\BudgetCode;

class BudgetCodeController extends Controller
{

    private $moduleId = 28;
    private $imageResizeCtrl;

    public function __construct() {
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $budget_codes = BudgetCode::paginate(10);
       // echo "<pre>"; print_r($supplyCats); exit;
       return View::make('budget-code.index')->with(compact('budget_codes'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('budget-code.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        OwnLibrary::validateAccess($this->moduleId,2);

        $this->middleware('csrf', array('on' => 'post'));

        $v = \Validator::make($request->all(), [
            'code' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('budget_code/create')->withErrors($v->errors())->withInput();
        }else {

                
                $budget_codes = new BudgetCode();

                $budget_codes->code = $request->code;
                $budget_codes->description = empty($request->description) ? null : $request->description;
                $budget_codes->status_id = $request->status;

               if ($budget_codes->save()) {
                   Session::flash('success', 'Budget Code Created Successfully');
                    return Redirect::to('budget_code/view');
                }

            //} 

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $editId = BudgetCode::find($id);

        return View::make('budget-code.edit')->with(compact('editId'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        OwnLibrary::validateAccess($this->moduleId,3);

        $this->middleware('csrf', array('on' => 'post'));

        $v = \Validator::make($request->all(), [
            'code' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('budget_code/edit/'.$id)->withErrors($v->errors())->withInput();
        }else {

                $budget_codes = BudgetCode::find($id);

                $budget_codes->code = $request->code;
                $budget_codes->description = empty($request->description) ? null : $request->description;
                $budget_codes->status_id = $request->status;

               if ($budget_codes->save()) {
                   Session::flash('success', 'Budget Code Updated Successfully');
                    return Redirect::to('budget_code/view');
                }

            //} 

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        OwnLibrary::validateAccess($this->moduleId,4);
        $budget_codes = BudgetCode::find($id);
        
        if ($budget_codes->delete()) {
                Session::flash('success', 'Budget Code Deleted Successfully');
                return Redirect::to('budget_code/view');
            } else {
                Session::flash('error', 'Budget Code Not Found');
                return Redirect::to('budget_code/view');
            }
    }


}
