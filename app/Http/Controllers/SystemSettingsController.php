<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use functions\OwnLibrary;
use DB;
use Illuminate\Support\Facades\Input;
use View;
use Validator;
use Session;
use Illuminate\Support\Facades\Redirect;
class SystemSettingsController extends Controller
{
    private $moduleId = 9;


    public function __construct() {

    }

    public function edit() {
        OwnLibrary::validateAccess($this->moduleId, 3);
        $target = \App\Settings::find(1);
        return View::make('settings.edit')->with(compact('target'));
    }

    public function update(Request $request) {
        OwnLibrary::validateAccess($this->moduleId, 3);
        // validate

        $message = array(
            'site_title.required' => 'Please, insert activity site_title!',
            'tag_line.required' => 'Please, insert activity tag line!',
            'site_description.required' => 'Please, insert activity site description!',
            'email.required' => 'Please, insert activity email!',
        );

        $validator = Validator::make(Input::all(), $message);


        // process the login
        if ($validator->fails()) {
            return Redirect::to('systemSettings')
                ->withErrors($validator)
                ->withInput();
        } else {

            $target = \App\Settings::find(1);
            $target->site_title = Input::get('site_title');
            $target->tag_line = Input::get('tag_line');
            $target->site_description = Input::get('site_description');
            $target->email = Input::get('email');
            $target->phone = Input::get('phone');
            $target->location = Input::get('location');
            $target->copy_right = Input::get('copy_right');
            $target->site_description = Input::get('site_description');
            $imageLogo = $request->file('logo');

            if ($imageLogo) {
                if ($target->logo != null) {
                    @unlink($target->logo);
                }
                $image_name = str_random(20);
                $ext = strtolower($imageLogo->getClientOriginalExtension());
                $image_full_name = $image_name . '.' . $ext;
                $upload_path = 'public/upload/systemSettings/';
                $image_url = $upload_path . $image_full_name;
                $imageLogo->move($upload_path, $image_full_name);
                if ($ext=='jpg' || $ext=='png'|| $ext=='jpeg'|| $ext=='JPG' || $ext=='PNG'|| $ext=='JPEG'){
                    $target->logo = $image_url;
                }else{

                    Session::flash('warning','Image is not valid!!');
                    return redirect()->back();
                }
            }

            $favicon= $request->file('favicon');
            if ($favicon) {
                    if ($target->favicon != null) {
                        @unlink($target->favicon);
                    }
                $image_name = str_random(20);
                $ext = strtolower($favicon->getClientOriginalExtension());
                $image_full_name = $image_name . '.' . $ext;
                $upload_path = 'public/upload/systemSettings/';
                $image_url = $upload_path . $image_full_name;
                $favicon->move($upload_path, $image_full_name);
                if ($ext=='jpg' || $ext=='png'|| $ext=='jpeg'|| $ext=='JPG' || $ext=='PNG'|| $ext=='JPEG'|| $ext=='ico'){
                    $target->favicon = $image_url;
                }else{

                    Session::flash('warning','Image is not valid!!');
                    return redirect()->back();
                }
            }

            if ($target->save()) {
                Session::flash('success', trans('english.DATA_UPDATED_SUCESSFULLY'));
                return Redirect::to('systemSettings');
            } else {
                Session::flash('error', trans('english.DATA_COULD_NOT_BE_UPDATED'));
                return Redirect::to('systemSettings');
            }
        }
    }

    public function cnsImageView(){
        $setting = \App\Settings::find(2);
        return view('cns.edit',compact('setting'));
    }

    public function cnsImageUpdate(Request $request){

        $setting = \App\Settings::find(2);

        if ($request->hasFile('image')) {
            if (!empty($setting->logo)) {
                @unlink($setting->logo);
            }
            $inputFile = $request->file('image');
            $file_name = str_random(20);
            $ext = strtolower($inputFile->getClientOriginalExtension());
            $file_full_name = $file_name . '.' . $ext;
            $upload_path = 'public/upload/cns-image/';
            $file_url = $upload_path . $file_full_name;
            $inputFile->move($upload_path, $file_full_name);
            $setting->logo  = $file_url;
        }

        if ($setting->save()) {
           return Redirect::to('/cns-image-edit');
        }else{
           return Redirect::to('/cns-image-edit');
        }

    }

}
