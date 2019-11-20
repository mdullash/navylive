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
use App\Zone;
use App\TermsCondition;
use Illuminate\Database\Schema\Blueprint;
use Schema;

class TermsConditionController extends Controller
{

    private $moduleId = 29;

    public function __construct() {

    }

    public function index(){
	    OwnLibrary::validateAccess($this->moduleId,1);
		$terms = TermsCondition::with('category')->orderBy('title')->paginate(15);
		return view('terms-conditions.index',compact('terms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	    OwnLibrary::validateAccess($this->moduleId,2);
	    $tcCategorys = TermsConditionCategory::where('status_id','=',1)->get();
        return View::make('terms-conditions.create')->with(compact('tcCategorys'));
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
//            'zones' => 'required',
//            'nsd_bsd' => 'required',
            'title' => 'required',
            'descriptions' => 'required',
            'status' => 'required',
            'category_id' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('terms-conditions/create')->withErrors($v->errors())->withInput();
        }else {

        	$term = new TermsCondition();

        	$term->title = $request->title;
        	$term->descriptions = $request->descriptions;
        	$term->category_id = $request->category_id;
        	$term->status = $request->status;
               if ($term->save()) {
                   Session::flash('success', 'Terms and Condition Created Successfully');
                    return Redirect::to('terms-conditions');
                }else{
	               Session::flash('error', 'Terms and Condition Not Created');
	               return redirect('terms-conditions/create')->withInput();
               }
        }

    }

	public function edit($id)
	{
		OwnLibrary::validateAccess($this->moduleId,3);
		$term = TermsCondition::find($id);
		$tcCategorys = TermsConditionCategory::where('status_id','=',1)->get();
		return View::make('terms-conditions.edit',compact('term','tcCategorys'));
	}

	public function update(Request $request,$id){
		OwnLibrary::validateAccess($this->moduleId,3);

		$v = \Validator::make($request->all(), [
//            'zones' => 'required',
//            'nsd_bsd' => 'required',
			'title' => 'required',
			'descriptions' => 'required',
			'status' => 'required',
		]);

		if ($v->fails()) {
			return redirect()->back()->withErrors($v->errors())->withInput();
		}else {

			$term = TermsCondition::find($id);

			$term->category_id = $request->category_id;
			$term->title = $request->title;
			$term->descriptions = $request->descriptions;
			$term->status = $request->status;
			if ($term->save()) {
				Session::flash('success', 'Terms and Condition Updated Successfully');
				return Redirect::to('terms-conditions');
			}else{
				Session::flash('error', 'Terms and Condition Not Updated');
				return redirect()->back()->withInput();
			}
		}
	}

	public function destroy($id){
		$term = TermsCondition::find($id);
		if ($term->delete()) {
			Session::flash('success', 'Terms and Condition Delated Successfully');
		}else{
			Session::flash('error', 'Terms and Condition Not Updated');
		}
		return Redirect::to('terms-conditions');
	}

}
