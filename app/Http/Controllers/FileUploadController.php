<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EventManagement;
use Illuminate\Support\Facades\Auth;
use Session;
use View;
use Input;
use Illuminate\Support\Facades\Redirect;
use functions\OwnLibrary;
use App\SupplyCategory;
use App\FileUploadLogs;

class FileUploadController extends Controller
{

    private $moduleId = 32;

    public function __construct() {
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileUpload()
    {
        return View::make('file-upload.create');
    }

    public function postFileUpload(Request $request){

        $v = \Validator::make($request->all(), [
            'file_path' => 'required',
            'file_name' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('upload-file/file')->withErrors($v->errors())->withInput();
        }else {

            $filePath = $request->file_path;

            $image_upload = TRUE;
            $image_name = FALSE;
            if (Input::hasFile('file_name')) {
                $file = Input::file('file_name');
                $destinationPath = base_path() . '/'.$filePath.'/';
                $filename = $file->getClientOriginalName();
                $uploadSuccess = Input::file('file_name')->move($destinationPath, $filename);
                if ($uploadSuccess) {
                    $image_name = TRUE;
                } else {
                    $image_upload = FALSE;
                }

            }

            if($image_name){

                $ins_in_log = new FileUploadLogs();

                $ins_in_log->file_name = $filename;
                $ins_in_log->file_path = $request->file_path;

                $ins_in_log->save();

                Session::flash('success', 'File uploaded Successfully');
                return Redirect::to('upload-file/file');
            }else{
                Session::flash('error', 'File upload field');
                return Redirect::to('upload-file/file');
            }

        }

    }
    


}
