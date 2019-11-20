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
use App\EvaluationCriteria;
use App\EvaluationPosition;
use Illuminate\Database\Schema\Blueprint;
use Schema;

class EvaluationCriteriaController extends Controller
{

    private $moduleId = 41;

    public function __construct() {

    }

    public function index(){
	    OwnLibrary::validateAccess($this->moduleId,1);
		$evaluationCriterias = EvaluationCriteria::whereNotNull('id')->paginate(10);
		return view('evaluation-criteria.index',compact('evaluationCriterias'));
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
	    $positions = \App\EvaluationPosition::select('id','title')->where('status','=',1)->get();
        return View::make('evaluation-criteria.create')->with(compact('zones','positions'));
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
            'title' 	=> 'required',
            'positions' => 'required',
            'status' 	=> 'required'
        ]);

        if ($v->fails()) {
            return redirect('evaluation-criteria/create')->withErrors($v->errors())->withInput();
        }else {

        	$evlpotbl = new EvaluationCriteria();

        	$evlpotbl->title 				= $request->title;
            $evlpotbl->weight               = $request->weight;
        	$evlpotbl->positions 			= empty($request->positions) ? null : implode(',',$request->positions);
        	$evlpotbl->description 		    = $request->description;
        	$evlpotbl->zones 				= empty($request->zones) ? null : implode(',',$request->zones);
        	$evlpotbl->status 				= $request->status;
            if(isset($request->comment)){
                $evlpotbl->comment          = 1;
            }else{
                $evlpotbl->comment          = NULL;
            }
            if ($evlpotbl->save()) {
               Session::flash('success', 'Evaluation Criteria Created Successfully');
                return Redirect::to('evaluation-criteria');
            }else{
               Session::flash('error', 'Evaluation Criteria Not Created');
               return redirect('evaluation-criteria/create')->withInput();
            }
        }

    }

	public function edit($id)
	{
		OwnLibrary::validateAccess($this->moduleId,3);
		$evlpotbl = EvaluationCriteria::find($id);
		$zones = \App\Zone::select('id','name')->where('status','=',1)->get();
		$positions = \App\EvaluationPosition::select('id','title')->where('status','=',1)->get();
		return View::make('evaluation-criteria.edit',compact('evlpotbl','zones','positions'));
	}

	public function update(Request $request,$id){
		OwnLibrary::validateAccess($this->moduleId,3);

		$v = \Validator::make($request->all(), [
			'title' => 'required',
            'positions' => 'required',
            'status' => 'required'
		]);

		if ($v->fails()) {
			return redirect('evaluation-criteria/'.$id.'/'.'edit')->withErrors($v->errors())->withInput();
		}else {

			$evlpotbl = EvaluationCriteria::find($id);

        	$evlpotbl->title 				= $request->title;
            $evlpotbl->weight               = $request->weight;
        	$evlpotbl->positions 			= empty($request->positions) ? null : implode(',',$request->positions);
        	$evlpotbl->description 		    = $request->description;
        	$evlpotbl->zones 				= empty($request->zones) ? null : implode(',',$request->zones);
        	$evlpotbl->status 				= $request->status;
            if(isset($request->comment)){
                $evlpotbl->comment          = 1;
            }else{
                $evlpotbl->comment          = NULL;
            }
            if ($evlpotbl->save()) {
               Session::flash('success', 'Evaluation Criteria Updated Successfully');
                return Redirect::to('evaluation-criteria');
            }else{
               Session::flash('error', 'Evaluation Criteria Not Updated');
               return redirect('evaluation-criteria/'.$id.'/'.'edit');
            }

		}
	}

	public function destroy($id){
		$evlpotbl = EvaluationCriteria::find($id);
		if ($evlpotbl->delete()) {
			Session::flash('success', 'Evaluation Criteria Delated Successfully');
		}else{
			Session::flash('error', 'Evaluation Criteria Not Updated');
		}
		return Redirect::to('evaluation-criteria');
	}

	static function evaluationPositionName($id=null){
        $positnName = \App\EvaluationPosition::where('id','=',$id)->value('title');
        return $positnName;
    }

}
