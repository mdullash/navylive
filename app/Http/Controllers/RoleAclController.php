<?php
namespace App\Http\Controllers;

use functions\OwnLibrary;
use View;
use Illuminate\Support\Facades\Input;
use Redirect;
use Session;
use DB;

class RoleAclController extends Controller {

    private $moduleId = 2;
    private $name = 'roleacl';

    public function __construct() {
        
    }

    public function index() {
        OwnLibrary::validateAccess($this->moduleId, 1);
//        $this->custom->check_acl($this->moduleId,1);
        $roleAcList = \App\Role::where('status_id', 1)->orderBy('id')->pluck('name', 'id')->toArray();

        $data['roleList'] = array('0' => trans('english.SELECT_ROLE_OPT')) + $roleAcList ;
        return View::make('acl.roleacl', $data);
    }

    public function save() {

        //$this->custom->check_acl($this->moduleId, 3);
        $updateExist = Input::get('update_exist');

        $moduleActivity = Input::get('module_activity');
        $roleId = Input::get('role_id');
        $target = \App\Role::find($roleId);

        if (empty($target)) {
            show_404();
        }

        //if module to activity empty
        if (empty($moduleActivity)) {
            Session::flash('error', trans('english.NO_ACTIVITY_WAS_SELECTED'));
            return Redirect::to($this->name);
        }

        if (!empty($roleId)) {
            $i = 0;
            $saveField = array();
            if (!empty($moduleActivity)) {
                foreach ($moduleActivity as $moduleId => $modActivity) {
                    foreach ($modActivity as $activityId => $val) {
                        $saveField[$i]['role_id'] = $roleId;
                        $saveField[$i]['module_id'] = $moduleId;
                        $saveField[$i]['activity_id'] = $activityId;
                        $i++;
                    }
                }

                //Delete old access
                \App\ModuleToRole::where('role_id', $roleId)->delete();

                if (\App\ModuleToRole::insert($saveField)) {
                    if (!empty($updateExist)) {
                        //users previous activity list remove
                        $userList = \App\User::where('role_id', $roleId)->select('id', 'role_id')->get();
                        if (!empty($userList)) {
                            foreach ($userList as $user) {
                                \App\ModuleToUser::where('user_id', $user->id)->delete();
                            }
                        }

                        //update new activity list
                        $roleActivityList = \App\ModuleToRole::where('role_id', $roleId)->get();
                        $data = array();
                        if (!empty($roleActivityList)) {
                            $i = 0;
                            foreach ($roleActivityList as $rActivity) {
                                foreach ($userList as $user) {
                                    $data[$i]['user_id'] = $user->id;
                                    $data[$i]['module_id'] = $rActivity->module_id;
                                    $data[$i]['activity_id'] = $rActivity->activity_id;
                                    $i++;
                                }
                            }
                        }
                        if (!empty($data)) {
                            \App\ModuleToUser::insert($data);
                        }
                    }
                    Session::flash('success', trans('english.ROLE_WISE_ACL_UPDATED_SUCCESSFULLY'));
                } else {
                    Session::flash('error', trans('english.ROLE_WISE_ACL_COULD_NOT_BE_UPDATED'));
                }
                return Redirect::to($this->name);
            }//if
        }
    }

    public function roleAclSetup() {

        $roleId = Input::get('role_id');

        $data = array();
        if (!empty($roleId)) {

//            $module_activity = DB::table('module_to_activity as ma')
//                    ->leftJoin('module as m', DB::raw('m.id'), '=', DB::raw('ma.module_id'))
//                    ->leftJoin('module_to_role as mr', function($join) use ($roleId) {
//                        $join->on(DB::raw('mr.activity_id'), '=', DB::raw('ma.activity_id'))
//                        ->on(DB::raw('mr.module_id'), '=', DB::raw('m.id'))
//                        ->where(DB::raw('mr.role_id'), '=', $roleId);
//                    })
//                    ->orderBy(DB::raw('m.id'), 'asc')
//                    ->orderBy(DB::raw('ma.activity_id'), 'asc')
//                    ->get(array(DB::raw('m.id, m.name, ma.activity_id, mr.activity_id as mr_activity')));


            $module_activity = DB::table('moduletoactivity')
                    ->leftJoin('module', 'module.id', '=', 'moduletoactivity.module_id')
                    ->leftJoin('moduletorole', function($join) use ($roleId) {
                        $join->on('moduletorole.activity_id', '=','moduletoactivity.activity_id')
                        ->on('moduletorole.module_id', '=', 'module.id')
                        ->where('moduletorole.role_id', '=', $roleId);
                    })
                    ->orderBy('module.id', 'asc')
                    ->orderBy('moduletoactivity.activity_id', 'asc')
                    ->select('module.id', 'module.name','moduletoactivity.activity_id','moduletorole.activity_id as mr_activity')
                    ->get();

                 $m_activity = array();

            $tmp_module = '';
            if (!empty($module_activity)) {
                foreach ($module_activity as $ma) {
                    if ($tmp_module != $ma->id) {
                        $tmp_module = $ma->id;
                    }
                    $m_activity[$tmp_module][$ma->activity_id] = 1;
                    if (!empty($ma->mr_activity)) {
                        $m_activity[$tmp_module][$ma->activity_id] = 2;
                    }
                }
            }

            $data['m_activity'] = $m_activity;
            $data['all_activity'] = \App\Activity::orderBy('id', 'asc')->get();
            $data['modules'] = \App\Module::orderBy('id', 'asc')->get();
            $data['role_id'] = $roleId;
        }

        return View::make('acl.roleaclsetup', $data);
    }

}
