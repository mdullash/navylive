<?php

namespace App\Http\Controllers;

use functions\OwnLibrary;
use Illuminate\Support\Facades\Input;
Use View;
use Illuminate\Support\Facades\Validator;
use Session;
use Illuminate\Support\Facades\Redirect;

class RoleController extends Controller {
    
    private $moduleId = 1;
    
    public function __construct() {
        
    }

    public function index() {

        OwnLibrary::validateAccess($this->moduleId,1);

        $name = Input::get('name');

        $targetArr = \App\Role::orderBy('id');

        if (!empty($name)) {
            $targetArr = $targetArr->where('name', 'LIKE', '%' . $name . '%');
        }

        $data['targetArr'] = $targetArr->paginate(trans('english.PAGINATION_COUNT'));

        return View::make('role.index', $data);
    }

    public function filter() {
        $name = Input::get('name');
        return Redirect::to('role?name=' . $name);
    }

    public function create() {
        OwnLibrary::validateAccess($this->moduleId, 2);
        return View::make('role.create');
    }

    public function store() {
        OwnLibrary::validateAccess($this->moduleId, 2);
        $this->middleware('csrf', array('on' => 'post'));

        $rules = array(
            'name' => 'required|Unique:role',
            'priority' => 'required',
        );

        $message = array(
            'name.required' => 'Please, insert Role Name!',
            'name.unique' => 'This name has already been taken!'
        );

        $validator = Validator::make(Input::all(), $rules, $message);

        if ($validator->fails()) {
            return Redirect::to('role/create')
                            ->withErrors($validator)
                            ->withInput();
        } else {

            $target = new \App\Role();
            $target->name = Input::get('name');
            $target->info = Input::get('info');
            $target->priority = Input::get('priority');
            $target->status_id = Input::get('status_id');

            if ($target->save()) {
                Session::flash('success', trans('english.DATA_INSERTED_SUCESSFULLY'));
            } else {
                Session::flash('error', trans('english.DATA_CUOLD_NOT_BE_INSERTED'));
            }

            return Redirect::to('role');
        }
    }

    public function edit($id) {
        OwnLibrary::validateAccess($this->moduleId, 3);
        $target = \App\Role::find($id);
        return View::make('role.edit')->with(compact('target'));
    }

    public function update($id) {
        OwnLibrary::validateAccess($this->moduleId, 3);
        // validate
        $rules = array(
            'name' => 'required|Unique:role,name,' . $id,
            'priority' => 'required',
        );

        $message = array(
            'name.required' => 'Please, insert Role Name!',
            'name.unique' => 'This name has already been taken!'
        );

        $validator = Validator::make(Input::all(), $rules, $message);


        // process the login
        if ($validator->fails()) {
            return Redirect::to('role/' . $id . '/edit')
                            ->withErrors($validator)
                            ->withInput();
        } else {

            $target = \App\Role::find($id);
            $target->name = Input::get('name');
            $target->info = Input::get('info');
            $target->priority = Input::get('priority');
            $target->status_id = Input::get('status_id');

            if ($target->save()) {
                Session::flash('success', trans('english.DATA_UPDATED_SUCESSFULLY'));
                return Redirect::to('role');
            } else {
                Session::flash('error', trans('english.DATA_COULD_NOT_BE_UPDATED'));
                return Redirect::to('role/' . $id . '/edit');
            }
        }
    }

    public function destroy($id) {
        OwnLibrary::validateAccess($this->moduleId, 4);
        //check depedency here....

        $target = \App\Role::find($id);

        if ($target->delete()) {
            Session::flash('error', trans('english.DATA_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', trans('english.DATA_COULD_NOT_BE_DELETED'));
        }
        return Redirect::to('role');
    }

}
