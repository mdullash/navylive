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
use App\DemandeName;
use Illuminate\Database\Schema\Blueprint;
use Schema;

class DemandeNameController extends Controller
{

    private $moduleId = 36;

    public function __construct() {
  
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        $sesZoneId = Session::get('zoneId');
        $demandes = DemandeName::where('zone_id','=',$sesZoneId)->paginate(10);
       // echo "<pre>"; print_r($supplyCats); exit;
       return View::make('demande-name.index')->with(compact('demandes'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('demande-name.create');
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
            'name' => 'required|unique:demande_name,name',
            'alise' => 'required',
        ]);

        if ($v->fails()) {
            return redirect('demande/create')->withErrors($v->errors())->withInput();
        }else {
                   
                $demandeName = new DemandeName();

                $demandeName->name    = $request->name;
                $demandeName->alise   = $request->alise;
                $demandeName->zone_id = Session::get('zoneId');
                $demandeName->status  = $request->status;

                if ($demandeName->save()) {

                   Session::flash('success', 'Demande Created Successfully');
                    return Redirect::to('demande/view');
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
       $editId = DemandeName::find($id);

        return View::make('demande-name.edit')->with(compact('editId'));

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
            'name' => 'required|unique:demande_name,name,'. $id,
            'alise' => 'required',
        ]);

        if ($v->fails()) {
            return redirect('demande/edit/'.$id)->withErrors($v->errors())->withInput();
        }else {

                $demandeName = DemandeName::find($id);

                $demandeName->name    = $request->name;
                $demandeName->alise   = $request->alise;
                $demandeName->zone_id = Session::get('zoneId');
                $demandeName->status  = $request->status;

                //$exbToMngt->save();

               if ($demandeName->save()) {
                   Session::flash('success', 'Demande Updated Successfully');
                   return Redirect::to('demande/view');
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
        $demandeName = DemandeName::find($id);

        if ($demandeName->delete()) {
                Session::flash('success', 'Demande Deleted Successfully');
                return Redirect::to('demande/view');
            } else {
                Session::flash('error', 'Demande Not Found');
                return Redirect::to('demande/view');
            }
    }


}
