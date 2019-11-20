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
use App\Zone;
use App\contactDetail;
use Illuminate\Database\Schema\Blueprint;
use Schema;

class ContactDetailsController extends Controller
{

    private $moduleId = 27;

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $contacts = contactDetail::paginate(10);
       // echo "<pre>"; print_r($supplyCats); exit;
       return View::make('contacts.index')->with(compact('contacts'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $zones = \App\Zone::select('id','name')->where('status','=',1)->get();
        return View::make('contacts.create',compact('zones'));
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
            'zones' => 'required',
            'nsd_bsd' => 'required',
            'descriptions' => 'required',
            'status' => 'required',
        ]);

        if ($v->fails()) {
            return redirect('contact/create')->withErrors($v->errors())->withInput();
        }else {

                $ifAlreadyExit = contactDetail::where('zones','=',$request->zones)->where('nsd_bsd','=',$request->nsd_bsd)->first();

                if(!empty($ifAlreadyExit)){
                    $contact = contactDetail::find($ifAlreadyExit->id);
                }else{
                    $contact = new contactDetail();
                }

                $contact->zones = $request->zones;
                $contact->nsd_bsd = $request->nsd_bsd;
                $contact->descriptions = empty($request->descriptions) ? null : $request->descriptions;
                $contact->status = $request->status;
                
                //$exbToMngt->save();

               if ($contact->save()) {

                   Session::flash('success', 'Contact Created Successfully');
                    return Redirect::to('contact/view');
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
        $editId = contactDetail::find($id);
        $zones = \App\Zone::select('id','name')->where('status','=',1)->get();

        $zonesRltdIds = array();
        $nsdNames = \App\NsdName::where('status_id','=',1)->get();
        foreach($nsdNames as $nn){
            foreach(explode(',',$nn->zones) as $zn){
                if($zn == $editId->zones){
                    $zonesRltdIds[] = $nn->id;
                }
            }

        }
        $selectedNsdBsd = \App\NsdName::whereIn('id',$zonesRltdIds)->where('status_id','=',1)->get();

        return View::make('contacts.edit')->with(compact('editId','zones','selectedNsdBsd'));

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
            'zones' => 'required',
            'nsd_bsd' => 'required',
            'descriptions' => 'required',
            'status' => 'required',
        ]);

        if ($v->fails()) {
            return redirect('contact/edit/'.$id)->withErrors($v->errors())->withInput();
        }else {
                $contact = contactDetail::find($id);

                $contact->zones = $request->zones;
                $contact->nsd_bsd = $request->nsd_bsd;
                $contact->descriptions = empty($request->descriptions) ? null : $request->descriptions;
                $contact->status = $request->status;

                //$exbToMngt->save();

               if ($contact->save()) {
                   Session::flash('success', 'Contact Updated Successfully');
                   return Redirect::to('contact/view');
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
        $zone = Zone::find($id);

        if ($zone->delete()) {
                Session::flash('success', 'Zone Deleted Successfully');
                return Redirect::to('zone/view');
            } else {
                Session::flash('error', 'Zone Not Found');
                return Redirect::to('zone/view');
            }
    }


}
