<?php
namespace App;


class ModuleController extends BaseController {

    public function __construct() {
        //$this->beforeFilter('admin', array('except' => array('cpself', 'editProfile')));
    }

    public function index() {
       
        $data['targetArr'] = Module::all();
        return View::make('acl.modulelist', $data);
    }
    
}