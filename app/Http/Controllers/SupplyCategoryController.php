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

class SupplyCategoryController extends Controller
{

    private $moduleId = 10;
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

        $supplyCats = SupplyCategory::paginate(10);
       // echo "<pre>"; print_r($supplyCats); exit;
       return View::make('supply-category.index')->with(compact('supplyCats'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $zones = \App\Zone::select('id','name')->where('status','=',1)->get();
        return View::make('supply-category.create',compact('zones'));
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
            return redirect('sup_cat/supplier_category/create')->withErrors($v->errors())->withInput();
        }else {

                //User photo upload
                $image_upload = TRUE;
                $image_name = FALSE;
                if (Input::hasFile('image')) {
                    $file = Input::file('image');
                    $destinationPath = public_path() . '/uploads/supply_category_image/';
                    $filename = uniqid() . $file->getClientOriginalName();
                    $uploadSuccess = Input::file('image')->move($destinationPath, $filename);
                    if ($uploadSuccess) {
                        $image_name = TRUE;
                    } else {
                        $image_upload = FALSE;
                    }

                    //Create More Small Thumbnails :::::::::::: Resize Image
                    $this->imageResizeCtrl->load(public_path() . '/uploads/supply_category_image/' . $filename);
                    $this->imageResizeCtrl->resize(1000, 800);

                    //delete original image
                    unlink(public_path() . '/uploads/supply_category_image/' . $filename);

                    $this->imageResizeCtrl->save(public_path() . '/uploads/supply_category_image/' . $filename);

                }
                   
                $supplyCat = new SupplyCategory();

                $supplyCat->name = $request->name;
                $supplyCat->description = empty($request->description) ? null : $request->description;
                $supplyCat->zones = empty($request->zones) ? null : implode(',',$request->zones);
                $supplyCat->status_id = $request->status;

                if ($image_name !== FALSE) {
                    $supplyCat->image = $filename;
                }

               if ($supplyCat->save()) {
                   Session::flash('success', 'Supply Category Created Successfully');
                    return Redirect::to('sup_cat/supplier_category');
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
        $editId = SupplyCategory::find($id);
        $zones = \App\Zone::select('id','name')->where('status','=',1)->get();

        return View::make('supply-category.edit')->with(compact('editId','zones'));

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
            return redirect('sup_cat/supplier_category/'.$id.'/'.'edit')->withErrors($v->errors())->withInput();
        }else {

                //User photo upload
                $image_upload = TRUE;
                $image_name = FALSE;
                if (Input::hasFile('image')) {
                    $file = Input::file('image');
                    $destinationPath = public_path() . '/uploads/supply_category_image/';
                    $filename = uniqid() . $file->getClientOriginalName();
                    $uploadSuccess = Input::file('image')->move($destinationPath, $filename);
                    if ($uploadSuccess) {
                        $image_name = TRUE;
                    } else {
                        $image_upload = FALSE;
                    }

                    //Create More Small Thumbnails :::::::::::: Resize Image
                    $this->imageResizeCtrl->load(public_path() . '/uploads/supply_category_image/' . $filename);
                    $this->imageResizeCtrl->resize(1000, 800);

                    //delete original image
                    unlink(public_path() . '/uploads/supply_category_image/' . $filename);

                    $this->imageResizeCtrl->save(public_path() . '/uploads/supply_category_image/' . $filename);

                }

                $supplyCat = SupplyCategory::find($id);

                $supplyCat->name = $request->name;
                $supplyCat->zones = empty($request->zones) ? null : implode(',',$request->zones);
                $supplyCat->description = empty($request->description) ? null : $request->description;
                $supplyCat->status_id = $request->status;

                if ($image_name !== FALSE) {
                    if(!empty($supplyCat->image)){
                        unlink(public_path() . '/uploads/supply_category_image/' . $supplyCat->image);
                    }
                    $supplyCat->image = $filename;
                }

               if ($supplyCat->save()) {
                   Session::flash('success', 'Supply Category Updated Successfully');
                    return Redirect::to('sup_cat/supplier_category');
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
        $supplyCatDel = SupplyCategory::find($id);
        
        if ($supplyCatDel->delete()) {
                Session::flash('success', 'Supply Category Deleted Successfully');
                return Redirect::to('sup_cat/supplier_category');
            } else {
                Session::flash('error', 'Supply Category Not Found');
                return Redirect::to('sup_cat/supplier_category');
            }
    }


}
