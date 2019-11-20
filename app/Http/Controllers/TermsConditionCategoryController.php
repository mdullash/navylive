<?php

namespace App\Http\Controllers;

use App\TermsConditionCategory;
use Illuminate\Http\Request;
use App\EventManagement;
use Illuminate\Support\Facades\Auth;
use Session;
use View;
use Input;
use Illuminate\Support\Facades\Redirect;
use functions\OwnLibrary;
use App\SupplyCategory;

class TermsConditionCategoryController extends Controller
{
	private $moduleId = 37;
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
		$termConCats = TermsConditionCategory::paginate(10);
		return View::make('terms-condition-category.index')->with(compact('termConCats'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$zones = \App\Zone::select('id','name')->where('status','=',1)->get();
		return View::make('terms-condition-category.create',compact('zones'));
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
			return redirect('terms-conditions-category/create')->withErrors($v->errors())->withInput();
		}else {

			$tcCategory = new TermsConditionCategory();

			$tcCategory->name = $request->name;
			$tcCategory->description = empty($request->description) ? null : $request->description;
			$tcCategory->zones = empty($request->zones) ? null : implode(',',$request->zones);
			$tcCategory->status_id = $request->status;

			if ($tcCategory->save()) {
				Session::flash('success', 'Terms and Condition Category Created Successfully');
				return Redirect::to('terms-conditions-category');
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
		$editId = TermsConditionCategory::find($id);
		$zones = \App\Zone::select('id','name')->where('status','=',1)->get();

		return View::make('terms-condition-category.edit')->with(compact('editId','zones'));

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
			return redirect('terms-conditions-category/'.$id.'/'.'edit')->withErrors($v->errors())->withInput();
		}else {

			$supplyCat = TermsConditionCategory::find($id);

			$supplyCat->name = $request->name;
			$supplyCat->zones = empty($request->zones) ? null : implode(',',$request->zones);
			$supplyCat->description = empty($request->description) ? null : $request->description;
			$supplyCat->status_id = $request->status;

			if ($supplyCat->save()) {
				Session::flash('success', 'Terms and Condition Category Updated Successfully');
				return Redirect::to('terms-conditions-category');
			}

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
		$supplyCatDel = TermsConditionCategory::find($id);

		if ($supplyCatDel->delete()) {
			Session::flash('success', 'Terms and Condition Category Deleted Successfully');
			return Redirect::to('terms-conditions-category');
		} else {
			Session::flash('error', 'Terms and Condition Category Not Found');
			return Redirect::to('terms-conditions-category');
		}
	}
}
