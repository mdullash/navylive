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
use App\Deno;

class DenoController extends Controller
{

    private $moduleId = 15;

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $denos = Deno::paginate(10);
       // echo "<pre>"; print_r($supplyCats); exit;
       return View::make('deno.index')->with(compact('denos'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('deno.create');
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
            return redirect('deno/create')->withErrors($v->errors())->withInput();
        }else {
                   
                $deno = new Deno();

                $deno->name = $request->name;
                $deno->description = empty($request->description) ? null : $request->description;
                $deno->status_id = $request->status;
                
                //$exbToMngt->save();

               if ($deno->save()) {
                   Session::flash('success', 'Deno Category Created Successfully');
                    return Redirect::to('deno/view');
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
       $editId = Deno::find($id);

        return View::make('deno.edit')->with(compact('editId'));

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
            return redirect('deno/edit/'.$id)->withErrors($v->errors())->withInput();
        }else {
                   
                $denos = Deno::find($id);

                $denos->name = $request->name;
                $denos->description = empty($request->description) ? null : $request->description;
                $denos->status_id = $request->status;
                
                //$exbToMngt->save();

               if ($denos->save()) {
                   Session::flash('success', 'Deno Updated Successfully');
                    return Redirect::to('deno/view');
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
        $deno = Deno::find($id);
        
        if ($deno->delete()) {
                Session::flash('success', 'Deno Deleted Successfully');
                return Redirect::to('deno/view');
            } else {
                Session::flash('error', 'Supply Category Not Found');
                return Redirect::to('deno/view');
            }
    }


}
