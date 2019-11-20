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
use App\EvaluationPointTable;
use App\TermsCondition;
use Illuminate\Database\Schema\Blueprint;
use Schema;

class EvaluationPointTableController extends Controller
{

    private $moduleId = 39;

    public function __construct() {

    }

    public function index(){
	    OwnLibrary::validateAccess($this->moduleId,1);
		$evaluPointTable = EvaluationPointTable::whereNotNull('id')->paginate(10);
		return view('evaluation-point-table.index',compact('evaluPointTable'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	    OwnLibrary::validateAccess($this->moduleId,2);
	    $zones = \App\Zone::select('id','name')->where('status','=',1)->get();
        return View::make('evaluation-point-table.create')->with(compact('zones'));
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
            'title' => 'required',
            'lower_point_limit' => 'required',
            'higher_point_limit' => 'required',
            'status' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('evaluation-point/create')->withErrors($v->errors())->withInput();
        }else {

        	$evlpotbl = new EvaluationPointTable();

        	$evlpotbl->title 				= $request->title;
        	$evlpotbl->lower_point_limit 	= $request->lower_point_limit;
        	$evlpotbl->higher_point_limit   = $request->higher_point_limit;
        	$evlpotbl->description 		    = $request->description;
        	$evlpotbl->zones 				= empty($request->zones) ? null : implode(',',$request->zones);
        	$evlpotbl->status 				= $request->status;
            if ($evlpotbl->save()) {
               Session::flash('success', 'Evaluation Point Table Created Successfully');
                return Redirect::to('evaluation-point');
            }else{
               Session::flash('error', 'Evaluation Point Table Not Created');
               return redirect('evaluation-point/create')->withInput();
            }
        }

    }

	public function edit($id)
	{
		OwnLibrary::validateAccess($this->moduleId,3);
		$evlpotbl = EvaluationPointTable::find($id);
		$zones = \App\Zone::select('id','name')->where('status','=',1)->get();
		return View::make('evaluation-point-table.edit',compact('evlpotbl','zones'));
	}

	public function update(Request $request,$id){
		OwnLibrary::validateAccess($this->moduleId,3);

		$v = \Validator::make($request->all(), [
			'title' => 'required',
            'lower_point_limit' => 'required',
            'higher_point_limit' => 'required',
            'status' => 'required'
		]);

		if ($v->fails()) {
			return redirect('evaluation-point/'.$id.'/'.'edit')->withErrors($v->errors())->withInput();
		}else {

			$evlpotbl = EvaluationPointTable::find($id);

        	$evlpotbl->title 				= $request->title;
        	$evlpotbl->lower_point_limit 	= $request->lower_point_limit;
        	$evlpotbl->higher_point_limit   = $request->higher_point_limit;
        	$evlpotbl->description 		    = $request->description;
        	$evlpotbl->zones 				= empty($request->zones) ? null : implode(',',$request->zones);
        	$evlpotbl->status 				= $request->status;
            if ($evlpotbl->save()) {
               Session::flash('success', 'Evaluation Point Table Updated Successfully');
                return Redirect::to('evaluation-point');
            }else{
               Session::flash('error', 'Evaluation Point Table Not Updated');
               return redirect('evaluation-point/'.$id.'/'.'edit');
            }

		}
	}

	public function destroy($id){
		$evlpotbl = EvaluationPointTable::find($id);
		if ($evlpotbl->delete()) {
			Session::flash('success', 'Evaluation Point Table Delated Successfully');
		}else{
			Session::flash('error', 'Evaluation Point Table Not Updated');
		}
		return Redirect::to('evaluation-point');
	}

}
