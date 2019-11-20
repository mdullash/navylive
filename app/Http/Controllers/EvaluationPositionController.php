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
use App\EvaluationPosition;
use Illuminate\Database\Schema\Blueprint;
use Schema;

class EvaluationPositionController extends Controller
{

    private $moduleId = 40;

    public function __construct() {

    }

    public function index(){
	    OwnLibrary::validateAccess($this->moduleId,1);
		$evaluPosition = EvaluationPosition::whereNotNull('id')->paginate(10);
		return view('evaluation-position.index',compact('evaluPosition'));
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
        return View::make('evaluation-position.create')->with(compact('zones'));
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
            'status' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('evaluation-position/create')->withErrors($v->errors())->withInput();
        }else {

        	$evlpotbl = new EvaluationPosition();

        	$evlpotbl->title 				= $request->title;
        	$evlpotbl->description 		    = $request->description;
        	$evlpotbl->zones 				= empty($request->zones) ? null : implode(',',$request->zones);
        	$evlpotbl->status 				= $request->status;
            if ($evlpotbl->save()) {
               Session::flash('success', 'Evaluation Position Created Successfully');
                return Redirect::to('evaluation-position');
            }else{
               Session::flash('error', 'Evaluation Position Not Created');
               return redirect('evaluation-position/create')->withInput();
            }
        }

    }

	public function edit($id)
	{
		OwnLibrary::validateAccess($this->moduleId,3);
		$evlpotbl = EvaluationPosition::find($id);
		$zones = \App\Zone::select('id','name')->where('status','=',1)->get();
		return View::make('evaluation-position.edit',compact('evlpotbl','zones'));
	}

	public function update(Request $request,$id){
		OwnLibrary::validateAccess($this->moduleId,3);

		$v = \Validator::make($request->all(), [
			'title' => 'required',
            'status' => 'required'
		]);

		if ($v->fails()) {
			return redirect('evaluation-position/'.$id.'/'.'edit')->withErrors($v->errors())->withInput();
		}else {

			$evlpotbl = EvaluationPosition::find($id);

        	$evlpotbl->title 				= $request->title;
        	$evlpotbl->description 		    = $request->description;
        	$evlpotbl->zones 				= empty($request->zones) ? null : implode(',',$request->zones);
        	$evlpotbl->status 				= $request->status;
            if ($evlpotbl->save()) {
               Session::flash('success', 'Evaluation Position Updated Successfully');
                return Redirect::to('evaluation-position');
            }else{
               Session::flash('error', 'Evaluation Position Not Updated');
               return redirect('evaluation-position/'.$id.'/'.'edit');
            }

		}
	}

	public function destroy($id){
		$evlpotbl = EvaluationPosition::find($id);
		if ($evlpotbl->delete()) {
			Session::flash('success', 'Evaluation Position Delated Successfully');
		}else{
			Session::flash('error', 'Evaluation Position Not Updated');
		}
		return Redirect::to('evaluation-position');
	}

}
