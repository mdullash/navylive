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
use App\Notice;

class NoticeController extends Controller
{

    private $moduleId = 11;

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notices = Notice::paginate(10);
       // echo "<pre>"; print_r($supplyCats); exit;
       return View::make('notice.index')->with(compact('notices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $zones = \App\Zone::select('id','name')->where('status','=',1)->get();
        return View::make('notice.create',compact('zones'));
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
            'description' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('notice/create')->withErrors($v->errors())->withInput();
        }else {

                //User photo upload
                $image_upload = TRUE;
                $image_name = FALSE;
                if (Input::hasFile('upload_file')) {
                    $file = Input::file('upload_file');
                    $destinationPath = public_path() . '/uploads/notice/';
                    $filename = uniqid() . $file->getClientOriginalName();
                    $uploadSuccess = Input::file('upload_file')->move($destinationPath, $filename);
                    if ($uploadSuccess) {
                        $image_name = TRUE;
                    } else {
                        $image_upload = FALSE;
                    }
                }

                $notice = new Notice();

                $notice->title = empty($request->title) ? null : $request->title;
                $notice->zones = empty($request->zones) ? null : implode(',',$request->zones);
                $notice->nsds_bsds = empty($request->nsds_bsds) ? null : implode(',',$request->nsds_bsds);
                $notice->description = empty($request->description) ? null : $request->description;
                $notice->is_important = empty($request->is_important) ? null : $request->is_important;
                $notice->is_general = empty($request->is_general) ? null : $request->is_general;
                $notice->status_id = $request->status;

                if ($image_name !== FALSE) {
                    $notice->upload_file = $filename;
                }

               if ($notice->save()) {
                   Session::flash('success', 'Notice Created Successfully');
                    return Redirect::to('notice/view');
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
        $editId = Notice::find($id);
        $zones = \App\Zone::select('id','name')->where('status','=',1)->get();

        $zonesRltdIds = array();
        $nsdNames = \App\NsdName::where('status_id','=',1)->get();
        foreach($nsdNames as $nn){
            foreach(explode(',',$nn->zones) as $zn){
                if(in_array($zn, explode(',',$editId->zones))){
                    $zonesRltdIds[] = $nn->id;
                }
            }

        }
        //$nsdNames = NsdName::whereIn('id',$zonesRltdIds)->where('status_id','=',1)->get();
        $selectedNsdBsd = \App\NsdName::whereIn('id',$zonesRltdIds)->where('status_id','=',1)->get();

        return View::make('notice.edit')->with(compact('editId','zones','selectedNsdBsd'));
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
            'description' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('notice/notice/'.$id.'/'.'edit')->withErrors($v->errors())->withInput();
        }else {

                //User photo upload
                $image_upload = TRUE;
                $image_name = FALSE;
                if (Input::hasFile('upload_file')) {
                    $file = Input::file('upload_file');
                    $destinationPath = public_path() . '/uploads/notice/';
                    $filename = uniqid() . $file->getClientOriginalName();
                    $uploadSuccess = Input::file('upload_file')->move($destinationPath, $filename);
                    if ($uploadSuccess) {
                        $image_name = TRUE;
                    } else {
                        $image_upload = FALSE;
                    }
                }

                    $notice = Notice::find($id);

                    $notice->title = empty($request->title) ? null : $request->title;
                    $notice->zones = empty($request->zones) ? null : implode(',',$request->zones);
                    $notice->nsds_bsds = empty($request->nsds_bsds) ? null : implode(',',$request->nsds_bsds);
                    $notice->description = empty($request->description) ? null : $request->description;
                    $notice->is_important = empty($request->is_important) ? null : $request->is_important;
                    $notice->is_general = empty($request->is_general) ? null : $request->is_general;
                    $notice->status_id = $request->status;

                if ($image_name !== FALSE) {
                    if(!empty($notice->upload_file)){
                        unlink(public_path() . '/uploads/notice/' . $notice->upload_file);
                    }
                    $notice->upload_file = $filename;
                }

               if ($notice->save()) {
                   Session::flash('success', 'Notice Updated Successfully');
                    return Redirect::to('notice/view');
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
        $notice = Notice::find($id);
        
        if ($notice->delete()) {

                if(!empty($notice->upload_file)){
                    unlink(public_path() . '/uploads/notice/' . $notice->upload_file);
                }

                Session::flash('success', 'NSD Name Deleted Successfully');
                return Redirect::to('reg_nsd/registred_nsd_name');
            } else {
                Session::flash('error', 'NSD Name Not Found');
                return Redirect::to('reg_nsd/registred_nsd_name');
            }
    }

    public function noticePdf($id){
        $notice = Notice::find(decrypt($id));
        return response()->file(public_path() . '/uploads/notice/' . $notice->upload_file);
    }

    static function zone_name($zone_id=null){
        $zoneName = \App\Zone::where('id','=',$zone_id)->value('name');
        return $zoneName;
    }
}
