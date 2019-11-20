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
use App\NsdName;

class RegistredNsdNameController extends Controller
{

    private $moduleId = 11;
    private $imageResizeCtrl;

    public function __construct() {
        $this->imageResizeCtrl = new ImageResizeController();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nadName = NsdName::paginate(11);
       // echo "<pre>"; print_r($supplyCats); exit;
       return View::make('registred-nsd-name.index')->with(compact('nadName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $zones = \App\Zone::select('id','name')->where('status','=',1)->get();
        return View::make('registred-nsd-name.create',compact('zones'));
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
            'name' => 'required|unique:nsdname,name',
            'alise' => 'required|unique:nsdname,alise',
        ]);

        if ($v->fails()) {
            return redirect('reg_nsd/registred_nsd_name/create')->withErrors($v->errors())->withInput();
        }else {

                //User photo upload
                $image_upload = TRUE;
                $image_name = FALSE;
                if (Input::hasFile('image')) {
                    $file = Input::file('image');
                    $destinationPath = public_path() . '/uploads/nsd_bsd_image/';
                    $filename = uniqid() . $file->getClientOriginalName();
                    $uploadSuccess = Input::file('image')->move($destinationPath, $filename);
                    if ($uploadSuccess) {
                        $image_name = TRUE;
                    } else {
                        $image_upload = FALSE;
                    }

                    //Create More Small Thumbnails :::::::::::: Resize Image
                    $this->imageResizeCtrl->load(public_path() . '/uploads/nsd_bsd_image/' . $filename);
                    $this->imageResizeCtrl->resize(1000, 800);

                    //delete original image
                    unlink(public_path() . '/uploads/nsd_bsd_image/' . $filename);

                    $this->imageResizeCtrl->save(public_path() . '/uploads/nsd_bsd_image/' . $filename);

                }
                   
                $supplyCat = new NsdName();

                $supplyCat->name = $request->name;
                $supplyCat->alise = $request->alise;
                $supplyCat->purchase_limit = empty($request->purchase_limit) ? null : $request->purchase_limit;
                $supplyCat->direct_purchase_limit = empty($request->direct_purchase_limit) ? null : $request->direct_purchase_limit;
                $supplyCat->external_link = empty($request->external_link) ? null : $request->external_link;
                $supplyCat->description = empty($request->description) ? null : $request->description;
                $supplyCat->zones = empty($request->zones) ? null : implode(',',$request->zones);
                $supplyCat->status_id = $request->status;

                if ($image_name !== FALSE) {
                    $supplyCat->image = $filename;
                }

               if ($supplyCat->save()) {
                   Session::flash('success', 'NSD Name Created Successfully');
                    return Redirect::to('reg_nsd/registred_nsd_name');
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
        $editId = NsdName::find($id);
        $zones = \App\Zone::select('id','name')->where('status','=',1)->get();

        return View::make('registred-nsd-name.edit')->with(compact('editId','zones'));
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
            'name' => 'required|unique:nsdname,name,'. $id,
            'alise' => 'required|unique:nsdname,alise,'. $id,
        ]);

        if ($v->fails()) {
            return redirect('reg_nsd/registred_nsd_name/'.$id.'/'.'edit')->withErrors($v->errors())->withInput();
        }else {

                //User photo upload
                $image_upload = TRUE;
                $image_name = FALSE;
                if (Input::hasFile('image')) {
                    $file = Input::file('image');
                    $destinationPath = public_path() . '/uploads/nsd_bsd_image/';
                    $filename = uniqid() . $file->getClientOriginalName();
                    $uploadSuccess = Input::file('image')->move($destinationPath, $filename);
                    if ($uploadSuccess) {
                        $image_name = TRUE;
                    } else {
                        $image_upload = FALSE;
                    }

                    //Create More Small Thumbnails :::::::::::: Resize Image
                    $this->imageResizeCtrl->load(public_path() . '/uploads/nsd_bsd_image/' . $filename);
                    $this->imageResizeCtrl->resize(1000, 800);

                    //delete original image
                    unlink(public_path() . '/uploads/nsd_bsd_image/' . $filename);

                    $this->imageResizeCtrl->save(public_path() . '/uploads/nsd_bsd_image/' . $filename);

                }
                   
                $supplyCat = NsdName::find($id);

                $supplyCat->name = $request->name;
                $supplyCat->alise = $request->alise;
                $supplyCat->purchase_limit = empty($request->purchase_limit) ? null : $request->purchase_limit;
                $supplyCat->direct_purchase_limit = empty($request->direct_purchase_limit) ? null : $request->direct_purchase_limit;
                $supplyCat->external_link = empty($request->external_link) ? null : $request->external_link;
                $supplyCat->description = empty($request->description) ? null : $request->description;
                $supplyCat->zones = empty($request->zones) ? null : implode(',',$request->zones);
                $supplyCat->status_id = $request->status;

                if ($image_name !== FALSE) {
                    if(!empty($supplyCat->image)){
                        unlink(public_path() . '/uploads/nsd_bsd_image/' . $supplyCat->image);
                    }
                    $supplyCat->image = $filename;
                }

               if ($supplyCat->save()) {
                   Session::flash('success', 'NSD Name Updated Successfully');
                    return Redirect::to('reg_nsd/registred_nsd_name');
                }

            //} 

        }
    }

    //Registred nsd default select 
    public function active($id=null) {
        
        $nssdInfo = \App\NsdName::find($id);

        if ($nssdInfo->default_selected == '1') {
            $nssdInfo->default_selected = NUll;
            $msg_text = $nssdInfo->name . ' successfully Unselected';
            Session::flash('error', $msg_text);
        } else {
            $affected = \DB::table('nsdname')->update(array('default_selected' => NULL));
            $nssdInfo->default_selected = 1;
            $msg_text = $nssdInfo->name . ' successfully Selected';
            Session::flash('success', $msg_text);
        }
        $nssdInfo->save();
        // redirect

        return Redirect::to('reg_nsd/registred_nsd_name');
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
        $nsdNameDel = NsdName::find($id);
        
        if ($nsdNameDel->delete()) {
                Session::flash('success', 'NSD Name Deleted Successfully');
                return Redirect::to('reg_nsd/registred_nsd_name');
            } else {
                Session::flash('error', 'NSD Name Not Found');
                return Redirect::to('reg_nsd/registred_nsd_name');
            }
    }

    static function zone_name($zone_id=null){
        $zoneName = \App\Zone::where('id','=',$zone_id)->value('name');
        return $zoneName;
    }
}
