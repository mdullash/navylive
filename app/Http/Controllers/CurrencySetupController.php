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

class CurrencySetupController extends Controller
{

    private $moduleId = 21;

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $currencies = Currency::paginate(10);
       // echo "<pre>"; print_r($supplyCats); exit;
       return View::make('currency.index')->with(compact('currencies'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('currency.create');
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
            'currency_name' => 'required|unique:multiple_currency,currency_name',
            'symbol' => 'required',
            'conversion' => 'required',
            'status' => 'required',
        ]);

        if ($v->fails()) {
            return redirect('currency/create')->withErrors($v->errors())->withInput();
        }else {
                   
                $currency = new Currency();

                $currency->currency_name = $request->currency_name;
                $currency->symbol = empty($request->symbol) ? null : $request->symbol;
                $currency->conversion = empty($request->conversion) ? null : $request->conversion;
                $currency->default_currency = empty($request->default_currency) ? null : $request->default_currency;
                $currency->status = $request->status;

               if ($currency->save()) {
                   Session::flash('success', 'Currency Created Successfully');
                    return Redirect::to('currency/view');
                }

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
       $editId = Currency::find($id);

        return View::make('currency.edit')->with(compact('editId'));

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
            'currency_name' => 'required|unique:multiple_currency,currency_name,'. $id,
            'symbol' => 'required',
            'conversion' => 'required',
            'status' => 'required',
        ]);

        if ($v->fails()) {
            return redirect('currency/edit/'.$id)->withErrors($v->errors())->withInput();
        }else {
                $currency = Currency::find($id);

                $currency->currency_name = $request->currency_name;
                $currency->symbol = empty($request->symbol) ? null : $request->symbol;
                $currency->conversion = empty($request->conversion) ? null : $request->conversion;
                $currency->default_currency = empty($request->default_currency) ? null : $request->default_currency;
                $currency->status = $request->status;

                //$exbToMngt->save();

               if ($currency->save()) {

                   Session::flash('success', 'Currency Updated Successfully');
                   return Redirect::to('currency/view');
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
        $currency = Currency::find($id);

        if ($currency->delete()) {
                Session::flash('success', 'Currency Deleted Successfully');
                return Redirect::to('currency/view');
            } else {
                Session::flash('error', 'Currency Not Found');
                return Redirect::to('currency/view');
            }
    }

    public function makeDefault($id)
    {
        OwnLibrary::validateAccess($this->moduleId,4);

        \DB::table('multiple_currency')->update(array('default_currency' => null));

        $currency = Currency::find($id);
        $currency->default_currency = 1;
        if ($currency->save()) {
            Session::flash('success', 'Currency Set As Default Successfully');
            return Redirect::to('currency/view');
        } else {
            Session::flash('error', 'Currency Not Found');
            return Redirect::to('currency/view');
        }
    }


}
