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
use App\GroupName;

class GroupNameController extends Controller
{

    private $moduleId = 33;

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $groupnames = GroupName::paginate(10);
       // echo "<pre>"; print_r($supplyCats); exit;
       return View::make('group-name.index')->with(compact('groupnames'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $zones = \App\Zone::select('id','name')->where('status','=',1)->get();
        return View::make('group-name.create',compact('zones'));
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
            'name' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('group_name/create')->withErrors($v->errors())->withInput();
        }else {

                $group_nam = new GroupName();

                $group_nam->name = $request->name;
                $group_nam->description = empty($request->description) ? null : $request->description;
                $group_nam->zones = empty($request->zones) ? null : implode(',',$request->zones);
                $group_nam->status = $request->status;

               if ($group_nam->save()) {
                   Session::flash('success', 'Group Created Successfully');
                    return Redirect::to('group_name');
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
        $editId = GroupName::find($id);
        $zones = \App\Zone::select('id','name')->where('status','=',1)->get();

        return View::make('group-name.edit')->with(compact('editId','zones'));

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
            'name' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('group_name/'.$id.'/'.'edit')->withErrors($v->errors())->withInput();
        }else {

                $group_nam = GroupName::find($id);

                $group_nam->name = $request->name;
                $group_nam->zones = empty($request->zones) ? null : implode(',',$request->zones);
                $group_nam->description = empty($request->description) ? null : $request->description;
                $group_nam->status = $request->status;


               if ($group_nam->save()) {
                   Session::flash('success', 'Group Updated Successfully');
                    return Redirect::to('group_name');
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
        $groupName = GroupName::find($id);
        
        if ($groupName->delete()) {
                Session::flash('success', 'Group Deleted Successfully');
                return Redirect::to('group_name');
            } else {
                Session::flash('error', 'Group Not Found');
                return Redirect::to('group_name');
            }
    }


}
