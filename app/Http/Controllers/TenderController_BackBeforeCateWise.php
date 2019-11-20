<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EventManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use View;
use Input;
use Illuminate\Support\Facades\Redirect;
use functions\OwnLibrary;
use App\SupplyCategory;
use App\NsdName;
use App\Supplier;
use App\Tender;

class TenderController extends Controller
{

    private $moduleId = 13;

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $nsd_id = Input::get('nsd_id');
        $key = Input::get('key');
        $from   = Input::get('from');
        $to     = Input::get('to');

        if(!empty($from)){
            $from   = date('Y-m-d',strtotime(Input::get('from')));
        }
        if(!empty($to)){
            $to     = date('Y-m-d',strtotime(Input::get('to')));
        }

        $zonesRltdIds = array();
        $nsdNames = NsdName::where('status_id','=',1)->get();
        foreach($nsdNames as $nn){
            if(empty(Session::get('nsdBsdEmptyOrNot'))){

                if(in_array(Session::get('zoneId'), explode(',', $nn->zones))){
                    $zonesRltdIds[] = $nn->id;
                }

            }else{
                if(in_array(Session::get('zoneId'), explode(',',$nn->zones))){
                    foreach(explode(',',Session::get('nsdBsdEmptyOrNot')) as $nbeon){
                        if($nn->id == $nbeon){
                            $zonesRltdIds[] = $nn->id;
                        }
                    }
                }
            }// esle end
        }
        $nsdNames = NsdName::whereIn('id',$zonesRltdIds)->where('status_id','=',1)->get();

        //$tenders = Tender::whereIn('nsd_id',$zonesRltdIds)->paginate(10);
        $tenders = Tender::whereIn('nsd_id',$zonesRltdIds);
                            if(!empty($nsd_id)){
                                $tenders->where('nsd_id','=',$nsd_id);
                            }
                            if(!empty($key)){
                                $tenders->where(function($query) use ($key){
                                    $query->where('tender_title', 'like', "%{$key}%");
                                    $query->orWhere('tender_number', 'like', "%{$key}%");
                                });
                            }
                            if(!empty($from)){
                                $tenders->where(function($query) use ($from ){
                                    $query->whereDate('tender_opening_date','>=',$from);
                                });
                            }
                            if(!empty($to)){
                                $tenders->where(function($query) use ($to){
                                    $query->whereDate('tender_opening_date','<=',$to);
                                });
                            }
        $tenders = $tenders->paginate(10);
    
       return View::make('tender.index')->with(compact('tenders','nsdNames','nsd_id','key','from','to'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $nsdNames = NsdName::where('status_id','=',1)->get();
//        $supplyCategories = SupplyCategory::where('status_id','=',1)->get();
        $zonesRltdIds = array();
        $zonesRltdCtgIds = array();

        $nsdNames = NsdName::where('status_id','=',1)->get();
        foreach($nsdNames as $nn){
            if(empty(Session::get('nsdBsdEmptyOrNot'))){

                if(in_array(Session::get('zoneId'), explode(',', $nn->zones))){
                    $zonesRltdIds[] = $nn->id;
                }

            }else{
                if(in_array(Session::get('zoneId'), explode(',',$nn->zones))){
                    foreach(explode(',',Session::get('nsdBsdEmptyOrNot')) as $nbeon){
                        if($nn->id == $nbeon){
                            $zonesRltdIds[] = $nn->id;
                        }
                    }
                }
            }// esle end
        }
        $nsdNames = NsdName::whereIn('id',$zonesRltdIds)->where('status_id','=',1)->get();

        $supplyCategories = SupplyCategory::where('status_id','=',1)->get();
        foreach($supplyCategories as $ctg){
            if(in_array(Session::get('zoneId'), explode(',', $ctg->zones))){
                $zonesRltdCtgIds[] = $ctg->id;
            }
        }
        $supplyCategories = SupplyCategory::whereIn('id',$zonesRltdCtgIds)->where('status_id','=',1)->get();

        return View::make('tender.create')->with(compact('supplyCategories','nsdNames'));
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
            'tender_title' => 'required|unique:'.\Session::get("zoneAlise").'_tenders,tender_title',
            'tender_number' => 'required|unique:'.\Session::get("zoneAlise").'_tenders,tender_number',
            'tender_opening_date' => 'required',
            'tender_cat_id' => 'required',
            'nsd_id' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('tender/create')->withErrors($v->errors())->withInput();
        }else {

                //Specification pdf upload
                $image_upload = TRUE;
                $image_name = FALSE;
                if (Input::hasFile('specification')) {
                    $file = Input::file('specification');
                    $destinationPath = public_path() . '/uploads/tender_spicification_notice_pdf/';
                    $filename = uniqid() . $file->getClientOriginalName();
                    $uploadSuccess = Input::file('specification')->move($destinationPath, $filename);
                    if ($uploadSuccess) {
                        $image_name = TRUE;
                    } else {
                        $image_upload = FALSE;
                    }
                }

                //Notice pdf upload
                $notice_pdf_upload = TRUE;
                $notice_pdf_name = FALSE;
                if (Input::hasFile('notice')) {
                    $noticeFile = Input::file('notice');
                    $destinationPathNotice = public_path() . '/uploads/tender_spicification_notice_pdf/';
                    $noticeFilename = uniqid() . $noticeFile->getClientOriginalName();
                    $uploadSuccessNotice = Input::file('notice')->move($destinationPathNotice, $noticeFilename);
                    if ($uploadSuccessNotice) {
                        $notice_pdf_name = TRUE;
                    } else {
                        $notice_pdf_upload = FALSE;
                    }
                }
                   
                $tender = new Tender();
                $tender->tender_title = $request->tender_title;
                $tender->tender_number = $request->tender_number;
                //$tender->po_number = empty($request->po_number) ? null : $request->po_number;
                $tender->tender_opening_date = empty($request->tender_opening_date) ? null : date('Y-m-d',strtotime($request->tender_opening_date));
                $tender->tender_description = empty($request->tender_description) ? null : $request->tender_description;
                $tender->tender_cat_id = empty($request->tender_cat_id) ? null : $request->tender_cat_id;
                $tender->nsd_id = empty($request->nsd_id) ? null : $request->nsd_id;
                $tender->imc_number = empty($request->imc_number) ? null : $request->imc_number;
                $tender->status_id = $request->status;

                $tender->open_tender = empty($request->open_tender) ? null : $request->open_tender;
                if ($image_name !== FALSE) {
                    $tender->specification = $filename;
                }
                if ($notice_pdf_name !== FALSE) {
                    $tender->notice = $noticeFilename;
                }

               if ($tender->save()) {
                   Session::flash('success', 'Tender Created Successfully');
                    return Redirect::to('tender/view');
                }

            //} 

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

//        $nsdNames = NsdName::where('status_id','=',1)->get();
//        $supplyCategories = Tender::where('status_id','=',1)->get();
        $zonesRltdIds = array();
        $zonesRltdCtgIds = array();

        $nsdNames = NsdName::where('status_id','=',1)->get();
        foreach($nsdNames as $nn){
            if(empty(Session::get('nsdBsdEmptyOrNot'))){

                if(in_array(Session::get('zoneId'), explode(',', $nn->zones))){
                    $zonesRltdIds[] = $nn->id;
                }

            }else{
                if(in_array(Session::get('zoneId'), explode(',',$nn->zones))){
                    foreach(explode(',',Session::get('nsdBsdEmptyOrNot')) as $nbeon){
                        if($nn->id == $nbeon){
                            $zonesRltdIds[] = $nn->id;
                        }
                    }
                }
            }// esle end
        }
        $nsdNames = NsdName::whereIn('id',$zonesRltdIds)->where('status_id','=',1)->get();

        $supplyCategories = SupplyCategory::where('status_id','=',1)->get();
        foreach($supplyCategories as $ctg){
            if(in_array(Session::get('zoneId'), explode(',', $ctg->zones))){
                $zonesRltdCtgIds[] = $ctg->id;
            }
        }
        $supplyCategories = SupplyCategory::whereIn('id',$zonesRltdCtgIds)->where('status_id','=',1)->get();

        $editId = Tender::find($id);

        return View::make('tender.edit')->with(compact('editId','supplyCategories','nsdNames'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        OwnLibrary::validateAccess($this->moduleId,3);

        $this->middleware('csrf', array('on' => 'post'));

        $v = \Validator::make($request->all(), [
            'tender_title' => 'required|unique:'.\Session::get("zoneAlise").'_tenders,tender_title,'.$id,
            'tender_number' => 'required',
            'tender_opening_date' => 'required',
            'tender_cat_id' => 'required',
            'nsd_id' => 'required'
        ]);

        if ($v->fails()) {
            return redirect('tender/edit/'.$id)->withErrors($v->errors())->withInput();
        }else {

                //Specification pdf upload
                $image_upload = TRUE;
                $image_name = FALSE;
                if (Input::hasFile('specification')) {
                    $file = Input::file('specification');
                    $destinationPath = public_path() . '/uploads/tender_spicification_notice_pdf/';
                    $filename = uniqid() . $file->getClientOriginalName();
                    $uploadSuccess = Input::file('specification')->move($destinationPath, $filename);
                    if ($uploadSuccess) {
                        $image_name = TRUE;
                    } else {
                        $image_upload = FALSE;
                    }
                }

                //Notice pdf upload
                $notice_pdf_upload = TRUE;
                $notice_pdf_name = FALSE;
                if (Input::hasFile('notice')) {
                    $noticeFile = Input::file('notice');
                    $destinationPathNotice = public_path() . '/uploads/tender_spicification_notice_pdf/';
                    $noticeFilename = uniqid() . $noticeFile->getClientOriginalName();
                    $uploadSuccessNotice = Input::file('notice')->move($destinationPathNotice, $noticeFilename);
                    if ($uploadSuccessNotice) {
                        $notice_pdf_name = TRUE;
                    } else {
                        $notice_pdf_upload = FALSE;
                    }
                }

                $tender = Tender::find($id);
                $tender->tender_title = $request->tender_title;
                $tender->tender_number = $request->tender_number;
                $tender->tender_opening_date = empty($request->tender_opening_date) ? null : date('Y-m-d',strtotime($request->tender_opening_date));
                $tender->tender_description = empty($request->tender_description) ? null : $request->tender_description;
                $tender->tender_cat_id = empty($request->tender_cat_id) ? null : $request->tender_cat_id;
                $tender->nsd_id = empty($request->nsd_id) ? null : $request->nsd_id;
                $tender->imc_number = empty($request->imc_number) ? null : $request->imc_number;
                $tender->status_id = $request->status;

                $tender->open_tender = empty($request->open_tender) ? null : $request->open_tender;
                if ($image_name !== FALSE) {
                    if(!empty($tender->specification)){
                        unlink(public_path() . '/uploads/tender_spicification_notice_pdf/' . $tender->specification);
                    }
                    $tender->specification = $filename;
                }
                if ($notice_pdf_name !== FALSE) {
                    if(!empty($tender->notice)){
                        unlink(public_path() . '/uploads/tender_spicification_notice_pdf/' . $tender->notice);
                    }
                    $tender->notice = $noticeFilename;
                }

               if ($tender->save()) {
                   Session::flash('success', 'Tender Updated Successfully');
                    return Redirect::to('tender/view');
                }

            //} 

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        OwnLibrary::validateAccess($this->moduleId,4);
        $tender = Tender::find($id);
        
        if ($tender->delete()) {
                if(!empty($tender->specification)){
                    unlink(public_path() . '/uploads/tender_spicification_notice_pdf/' . $tender->specification);
                }
                if(!empty($tender->notice)){
                    unlink(public_path() . '/uploads/tender_spicification_notice_pdf/' . $tender->notice);
                }

                Session::flash('success', 'Tender Deleted Successfully');
                return Redirect::to('tender/view');
            } else {
                Session::flash('error', 'Tender Not Found');
                return Redirect::to('tender/view');
            }
    }

    public function specificationPdf($id){
        $tender = Tender::find(decrypt($id));
        return response()->file(public_path() . '/uploads/tender_spicification_notice_pdf/' . $tender->specification);
    }

    public function noticePdf($id){
        $tender = Tender::find(decrypt($id));
        return response()->file(public_path() . '/uploads/tender_spicification_notice_pdf/' . $tender->notice);
    }


}
