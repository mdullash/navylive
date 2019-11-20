<?php
namespace App\Http\Controllers;

use functions\OwnLibrary;
use DB;
use View;
use Illuminate\Support\Facades\Validator;
use Session;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Redirect;
class ModuleController extends Controller {

    private $moduleId = 7;

    public function __construct() {

    }

    public function index() {

        OwnLibrary::validateAccess($this->moduleId,1);

        $name = Input::get('name');

        $targetArr = \App\Module::orderBy('id');

        if (!empty($name)) {
            $targetArr = $targetArr->where('name', 'LIKE', '%' . $name . '%');
        }

        $data['targetArr'] = $targetArr->paginate(trans('english.PAGINATION_COUNT'));

        return View::make('module.index', $data);
    }

    public function filter() {
        $name = Input::get('name');
        return Redirect::to('module?name=' . $name);
    }

    public function create() {
        OwnLibrary::validateAccess($this->moduleId, 2);
        $data['activities']=\App\Activity::all();
        return View::make('module.create',$data);
    }

    public function store() {
        OwnLibrary::validateAccess($this->moduleId, 2);
        $this->middleware('csrf', array('on' => 'post'));

        $rules = array(
            'name' => 'required|Unique:module',
        );

        $message = array(
            'name.required' => 'Please, insert module Name!',
            'name.unique' => 'This name has already been taken!'
        );

        $validator = Validator::make(Input::all(), $rules, $message);

        if ($validator->fails()) {
            return Redirect::to('module/create')
                ->withErrors($validator)
                ->withInput();
        } else {

            $target = new \App\Module();
            $target->name = Input::get('name');
            $target->description = Input::get('description');
            $target->save();
            if (Input::get('activity_id') != null) {
                foreach (Input::get('activity_id') as $activity_id) {

                    $moduleToactivity = New \App\ModuleToActivity();
                    $moduleToactivity->module_id = $target->id;
                    $moduleToactivity->activity_id = $activity_id;
                    $moduleToactivity->save();
                }
            }

                Session::flash('success', trans('english.DATA_INSERTED_SUCESSFULLY'));

            return Redirect::to('module');
        }
    }

    public function edit($id) {
        OwnLibrary::validateAccess($this->moduleId, 3);
        $target = \App\Module::find($id);
        $activities=\App\Activity::all();
        $modules_activities=\App\ModuleToActivity::where('module_id',$id)->get();
        return View::make('module.edit')->with(compact('target','activities','modules_activities'));
    }

    public function update($id) {
        OwnLibrary::validateAccess($this->moduleId, 3);
        // validate
        $rules = array(
            'name' => 'required|Unique:module,name,' . $id,
        );

        $message = array(
            'name.required' => 'Please, insert module Name!',
            'name.unique' => 'This name has already been taken!'
        );

        $validator = Validator::make(Input::all(), $rules, $message);


        // process the login
        if ($validator->fails()) {
            return Redirect::to('module/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {

            $target = \App\Module::find($id);
            $target->name = Input::get('name');
            $target->description = Input::get('description');
            $target->save();
            \App\ModuleToActivity::where('module_id',$id)->delete();
            if (Input::get('activity_id') != null){
            foreach (Input::get('activity_id') as $activity_id){

                $moduleToactivity=New \App\ModuleToActivity();
                $moduleToactivity->module_id=$target->id;
                $moduleToactivity->activity_id=$activity_id;
                $moduleToactivity->save();
            }
            }
                Session::flash('success', trans('english.DATA_UPDATED_SUCESSFULLY'));
                return Redirect::to('module');
        }
    }

    public function destroy($id) {
        OwnLibrary::validateAccess($this->moduleId, 4);
        //check depedency here....

        $target = \App\Module::find($id);
        \App\ModuleToActivity::where('module_id',$id)->delete();
        if ($target->delete()) {
            Session::flash('error', trans('english.DATA_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', trans('english.DATA_COULD_NOT_BE_DELETED'));
        }
        return Redirect::to('module');
    }

}
