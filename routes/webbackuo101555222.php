<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/// Frontend
//Route::get('/', function() {
////    return Redirect::route('user', array('nick' => $username));
//    return redirect('location/?step=dates');
////    return redirect('home/dashboard?');
////    Route::get('index', 'FrontEndController@index');
//    //return View::make('frontend.index');
//});



/// dashboard
Route::get('navaladmin', function() {
    return View::make('login');
});
Route::post('login', function (\Illuminate\Http\Request $request) {
    $user = array(
        'username' => $request->username,
        'password' => $request->password,
        'status_id' => 1
    );

    if (Auth::attempt($user)) {

        $dataArr = App\ModuleToUser::where('user_id', Auth::user()->id)->get();

        $accessArr = array();
        if (!empty($dataArr)) {
            foreach ($dataArr as $item) {
                $accessArr[$item->module_id][$item->activity_id] = $item->activity_id;
            }
        }

        if(count(explode(',',Auth::user()->zones)) >1){
            Session::put('acl', $accessArr);
            Session::put('nsdBsdEmptyOrNot', Auth::user()->nsd_bsd);

            return Redirect::to('module-dashboard');
        }else{

            Session::put('acl', $accessArr);

            $zone = \App\Zone::find(Auth::user()->zones);
            Session::put('zone', strtolower($zone->name));
            Session::put('zoneAlise', strtolower($zone->alise));
            Session::put('zoneId', strtolower($zone->id));
            Session::put('nsdBsdEmptyOrNot', Auth::user()->nsd_bsd);

            return Redirect::to('dashboard');
        }

    } else {
        Session::flash('error', 'Your username or password was incorrect.!');
        return View::make('login')->withInput($request->password);
    }
});
Route::group(['middleware'=>'authCheck'],function (){
    Route::get('dashboard/{id?}','DashboardController@index');
    Route::get('module-dashboard','DashboardController@moduleDashboard');

    Route::group(array('middleware' => 'authCheck'), function() {

        Route::get('logout', array('as' => 'logout', function () {
            Auth::logout();
            Session::flush();
            return Redirect::to('dashboard');
        }));

        Route::get('dashboard','DashboardController@index');
        Route::post('users/cpself/', 'UsersController@cpself');

        Route::get('users/cpself/', function() {
            return View::make('users/change_password_self');
        });
        Route::get('users/profile/', function () {
            return View::make('users/user_profile');
        });
        Route::post('users/editProfile/', 'UsersController@editProfile');

        Route::resource('users', 'UsersController');
        Route::get('frontUsers', 'UsersController@quizePlayer');
        Route::get('frontUsers/date/{date}/{year?}', 'UsersController@quizePlayer');
        Route::get('users/activate/{id}/{param?}', 'UsersController@active');
        Route::post('users/pup/', 'UsersController@pup');
        Route::post('users/filter/', 'UsersController@filter');
        Route::post('frontUsers/filter/', 'UsersController@filterfrontUsers');
        Route::get('users/cp/{id}/{param?}', 'UsersController@change_pass');
        Route::post('users/loadProject', 'UsersController@loadProject');

        Route::post('role/filter', 'RoleController@filter');
        Route::resource('role', 'RoleController');

        Route::get('activitylist', 'ActivityController@index');
        Route::get('modulelist', 'ModuleController@index');

        Route::get('roleacl', 'RoleAclController@index');
        Route::post('roleAclSetup', 'RoleAclController@roleAclSetup');
        Route::post('roleacl', 'RoleAclController@save');

        Route::get('useritem-to-tender-item-name-live-searchacl', 'UserAclController@index');

        Route::get('useracl', 'UserAclController@index');
        Route::post('userAclSetup', 'UserAclController@userAclSetup');
        Route::post('useracl', 'UserAclController@save');

        Route::post('module/filter', 'ModuleController@filter');
        Route::resource('module', 'ModuleController');

        Route::post('activity/filter', 'ActivityController@filter');
        Route::resource('activity', 'ActivityController');
        Route::get('systemSettings', 'SystemSettingsController@edit');
        Route::put('systemSettings/update', [
            'as'=>'system.update',
            'uses'=>'SystemSettingsController@update'
        ]);

        Route::get('demand-get-print/{id}','DemandController@demandGetPrint');
        Route::resource('demand', 'DemandController');
        Route::post('demand-get-demand-no','DemandController@demandGetDemandNo');
        Route::get('demand-pending/{prm}','DemandController@index');
        Route::get('demand-get-approve/{prm}','DemandController@demandGetApprove');
        Route::post('demand-pending-post','DemandController@demandPendingPost');
        Route::get('store-demand-print/{prm}','DemandController@storeDemandPrint');

        Route::get('demand-edit/{id}','EditController@demandEdit');
        Route::get('demand-delete/{id}','EditController@demandDelete');
        Route::post('demand-update/{id}','EditController@demandUpdate');
        Route::get('demand-item-delete/{id}','EditController@demandItemDelete');

        //Tender Edit
        Route::get('direct-item-dmnd-edit/{id}','EditController@directItemDmndEdit');
        Route::put('direct-item-dmnd-update/{id}','EditController@directItemDmndupdate');
        Route::get('direct-item-dmnd-delete/{id}','EditController@demandItemDelete');
        Route::get('direct-item-dmnd-edit-with-ift/{id}','EditController@directItemDmndEditWithIft');
        Route::put('post-direct-item-dmnd-edit-with-ift/{id}','EditController@postDirectItemDmndEditWithIft');

        // Create item demand project
        Route::get('direct-item-dmnd-create','DemandController@directItemDmndCreate');
        Route::post('post-direct-item-dmnd','DemandController@postDirectItemDmnd');

        // All menus about demand ===================
        // ==========================================
        Route::get('group-check-acc/{prm}','DemandController@index');
        Route::get('floating-tender-acc/{prm}','DemandController@index');
        Route::get('collection-quotation-acc/{prm}','DemandController@index');
        Route::get('cst-view-acc/{prm}','DemandController@index');
        Route::get('draft-cst-view-acc/{prm}','DemandController@index');
        Route::get('hdq-approval-acc/{prm}','DemandController@index');
        Route::get('po-generation-acc/{prm}','DemandController@index');
        Route::get('cr-view-acc/{prm}','DemandController@index');
        Route::get('inspection-view-acc/{prm}','DemandController@index');
        Route::get('v44-voucher-view-acc/{prm}','DemandController@index');
        Route::get('retender-view-acc/{prm}', 'DemandController@index');
        //===========================================
        //===========================================
        Route::get('demand-details/{id}','DemandController@demandDetail');
        Route::get('demand-group/{id}','DemandController@demandGroup');
        Route::post('post-group-status-change','DemandController@postGroupStatusChange');
        Route::post('post-only-group-status-change','DemandController@postOnlyGroupStatusChange');
        Route::get('oic-group-status-change/{id}','DemandController@oicGroupChange');
        // Select lpr ===========================
        Route::get('post-select-as-lpr','SelectLprController@postSelectAsLpr');
        Route::post('insert-select-as-lpr','SelectLprController@insertSelectAsLpr');
        // Floating tender ======================
        Route::get('floating-tender/create/{id}', 'FloatingTenderController@create');
        Route::post('floating-tender/store', 'FloatingTenderController@store');
        Route::get('create-collection-quotation/{id}/{tenderId?}', 'FloatingTenderController@createCollectionQuotation');
        Route::get('delete-collection-quotation/{id}/{tenderId?}', 'EditController@deleteCollectionQuotation');
        Route::post('approve-multiple-tender', 'FloatingTenderController@approveMultipleTender');
        Route::post('create-collection-quotation-item-info', 'FloatingTenderController@createCollectionQuotationItemInfo');
        Route::post('post-collection-quotation-info', 'FloatingTenderController@postCollectionQuotationInfo');
        Route::post('floating-tender-terms-con-val', 'FloatingTenderController@floatingTenderTermsConVal');
        Route::get('floating-tender-get-view/{prm}','FloatingTenderController@getPrientView');
        Route::get('direct-tender-approve-reject/{prm}','FloatingTenderController@directTenderApproveReject');
        // Retender =============================
        Route::get('retender-create/{id}', 'RetenderController@create');
        Route::post('retender-post', 'RetenderController@retenderPost');
        // Nil Return =============================
        //      Send to nil return
        Route::post('post-nil-return', 'FloatingTenderController@postNilReturn');
        //      Send to nil return
        Route::get('nil-return/{prm}', 'DemandController@index');
        Route::get('nil-return-create/{id}', 'NilReturnController@create');
        Route::post('nil-return-store', 'NilReturnController@store');
        Route::get('nil-return-approved/{prm}', 'NilReturnController@approved');
        Route::get('nil-return-create-tender/{id}', 'NilReturnController@createTender');
        Route::post('nil-return-create-tender-post', 'NilReturnController@nilReturnCreateTenderPost');
        Route::get('nil-return-print/{id}', 'NilReturnController@nilReturnPrint');
        // Nil Return =============================
        // CST ==================================
        Route::get('cst-view/{id}', 'FloatingTenderController@cstView');
        Route::post('draft-cst-post', 'FloatingTenderController@draftCstPost');
        Route::get('draft-cst-view/{id}', 'FloatingTenderController@draftCstView');
        Route::post('select-supplier-cst-post', 'FloatingTenderController@selectSupplierCstPost');
        Route::post('post-cst-retender-reject', 'FloatingTenderController@postCstRetenderReject');
        // cst print ========================================
        Route::get('cst-view-print/{id}', 'NhqApprovalController@cstViewPrint');
        Route::get('cst-view-excel/{id}', 'NhqApprovalController@cstViewExcel');
        Route::get('draft-cst-view-print/{id}', 'NhqApprovalController@draftCstViewPrint');
        Route::get('draft-cst-view-excel/{id}', 'NhqApprovalController@draftCstViewExcel');
        Route::get('nhq-cst-view-print/{id}', 'NhqApprovalController@nhqCstViewPrint');
        Route::get('nhq-cst-view-excel/{id}', 'NhqApprovalController@nhqCstViewExcel');
        // Headquarter approval ===================
        Route::get('headquarte-approval/{data}', 'NhqApprovalController@headquarteApproval');
        Route::post('post-headquarte-approval', 'NhqApprovalController@postHeadquarteApproval');
        Route::post('retender-reject-from-nhq', 'NhqApprovalController@postRetenderRejectFromNhq');
        // PO Generation ===================================
        Route::get('po-generate-view/{id}', 'PoGenerationController@poGenerateView');
        Route::any('post-po-generate', 'PoGenerationController@postPoGenerate');
        Route::post('winner-wise-items', 'PoGenerationController@winnerWiseItems');
        Route::get('po-check-view/{id}', 'PoGenerationController@poCheckView');
        Route::post('post-po-check-edit', 'PoGenerationController@postPoCheckEdit');
        Route::get('po-approve-view/{id}', 'PoGenerationController@poApproveView');
        Route::post('post-po-approve-edit', 'PoGenerationController@postApproveEdit');
        Route::get('print-po-generation/{id}', 'PoGenerationController@printPoGeneration');
        Route::get('view-po-generation/{id}', 'PoGenerationController@viewPoGeneration');
        Route::get('view-po-generation-check-approved-reject', 'PoGenerationController@postPoCheckEditApprovedReject');
        Route::get('view-po-generation-approve-approved-reject', 'PoGenerationController@postPoApprovedEditApprovedReject');
        // Cr view =========================================
        Route::any('cr-section/{id}', 'PoGenerationController@crSection');
        Route::any('cr-section-two/{id}', 'PoGenerationController@crSectionTwo');
        Route::post('cr-view-post', 'PoGenerationController@crViewPost');
        Route::any('cr-pdf-print/{datas}', 'PoGenerationController@crPdfPrint');
        Route::any('cr-pdf-print-direct/{datas}', 'PoGenerationController@crPdfPrintDirect');
        // Inspection =======================================
        Route::any('inspection-section/{id}', 'PoGenerationController@inspectionSection');
        Route::post('post-inspection', 'PoGenerationController@postInspection');
        // Inspection =======================================
        Route::any('v44voucher-view/{id}', 'PoGenerationController@v44voucherView');
        Route::any('v44voucher-pdf-view/{id}', 'PoGenerationController@v44voucherPdfView');
        Route::any('v44voucher-post', 'PoGenerationController@v44voucherPost');
        Route::any('v44voucher-appprove-post', 'PoGenerationController@v44voucherApporvePost');
        // Schedule demand tender =======================================
        //Route::any('schedule-view/{id}', 'TenderScheduleController@scheduleView');
        Route::any('schedule-create', 'TenderScheduleController@scheduleCreate');
        Route::any('supplier-search-by-reg-or-bar-no', 'AjaxResponseController@supplierSearchByRegRrBarNo');
        Route::post('tender-schedule-post', 'TenderScheduleController@tenderSchedulePost');
        Route::any('schedule-all', 'TenderScheduleController@index');
        Route::any('schedule/destroy/{id}', 'TenderScheduleController@destroy');
        Route::any('print-schedule/{id}', 'TenderScheduleController@printSchedule');
        Route::any('print-schedule-red/{message}', 'TenderScheduleController@printScheduleRed');
        Route::post('supplier-name-live-search-by-schedule', 'AjaxResponseController@supplierNameLiveSearchBySchedule');

        Route::get('lp-section', 'PoGenerationController@index');

        Route::group(['prefix' => 'sup_cat'], function () {
            Route::resource('/supplier_category', 'SupplyCategoryController');
            Route::get('/supplier_category/destroy/{id}', 'SupplyCategoryController@destroy');
        });

        Route::resource('group_name', 'GroupNameController');
        Route::get('/group_name/destroy/{id}', 'GroupNameController@destroy');

        Route::group(['prefix' => 'reg_nsd'], function () {
            Route::resource('registred_nsd_name', 'RegistredNsdNameController');
            Route::get('/registred_nsd_name/destroy/{id}', 'RegistredNsdNameController@destroy');
            Route::get('/registred_nsd_name/acttive/{id}', 'RegistredNsdNameController@active');
        });

        Route::group(['prefix' => 'suppliers'], function () {
            Route::resource('suppliers', 'SuppliersController');
            Route::get('/suppliers/destroy/{id}', 'SuppliersController@destroy');
            Route::get('/view/{id}', 'SuppliersController@individulaView');
            Route::get('/suppliers/approve/{id}', 'SuppliersController@approve');
            Route::get('/suppliers/rejecte/{id}', 'SuppliersController@rejecte');
            Route::get('/suppliers-multiinfo/delete/{id}', 'SuppliersController@supplierMultiInfoDelete');
        });

        Route::group(['prefix' => 'tender'], function () {
            Route::resource('tender', 'TenderController');
            Route::get('view', 'TenderController@index');
            Route::get('create', 'TenderController@create');
            Route::post('store', 'TenderController@store');
            Route::get('/edit/{id}', 'TenderController@edit');
            Route::get('/suppliers/destroy/{id}', 'TenderController@destroy');
            Route::get('/destroy/{id}', 'TenderController@destroy');
            Route::get('/specification-pdf/{id}', 'TenderController@specificationPdf');
            Route::get('/specification-doc/{id}', 'TenderController@specificationDoc');
            Route::get('/notice-pdf/{id}', 'TenderController@noticePdf');
        });

        Route::group(['prefix' => 'item'], function () {
            Route::resource('item', 'ItemsController');
            Route::get('view', 'ItemsController@index');
            Route::get('create', 'ItemsController@create');
            Route::post('store', 'ItemsController@store');
            Route::get('/edit/{id}', 'ItemsController@edit');
            Route::get('/print/{id}', 'ItemsController@printItem');
            Route::get('/suppliers/destroy/{id}', 'ItemsController@destroy');
            Route::get('/destroy/{id}', 'ItemsController@destroy');
        });

        Route::group(['prefix' => 'deno'], function () {
            Route::resource('deno', 'DenoController');
            Route::get('view', 'DenoController@index');
            Route::get('create', 'DenoController@create');
            Route::post('store', 'DenoController@store');
            Route::get('/edit/{id}', 'DenoController@edit');
            Route::get('/suppliers/destroy/{id}', 'DenoController@destroy');
            Route::get('/destroy/{id}', 'DenoController@destroy');
        });

        Route::group(['prefix' => 'itemtotender'], function () {
            Route::resource('item-to-tender', 'ItemsToTenderController');
            Route::get('view', 'ItemsToTenderController@index');
            Route::get('create/{id}', 'ItemsToTenderController@create');
            Route::post('store', 'ItemsToTenderController@store');
            Route::get('/edit/{id}', 'ItemsToTenderController@edit');
            Route::get('/destroy/{id}', 'ItemsToTenderController@destroy');
        });

        Route::group(['prefix' => 'zone'], function () {
            Route::resource('zone', 'ZoneController');
            Route::get('view', 'ZoneController@index');
            Route::get('create', 'ZoneController@create');
            Route::post('store', 'ZoneController@store');
            Route::get('/edit/{id}', 'ZoneController@edit');
            Route::get('/destroy/{id}', 'ZoneController@destroy');
        });

        Route::group(['prefix' => 'notice'], function () {
            Route::resource('notice', 'NoticeController');
            Route::get('view', 'NoticeController@index');
            Route::get('create', 'NoticeController@create');
            Route::post('store', 'NoticeController@store');
            Route::get('/edit/{id}', 'NoticeController@edit');
            Route::get('/destroy/{id}', 'NoticeController@destroy');
            Route::get('/notice-pdf/{id}', 'NoticeController@noticePdf');
        });

        Route::group(['prefix' => 'contact'], function () {
            Route::resource('contact', 'ContactDetailsController');
            Route::get('view', 'ContactDetailsController@index');
            Route::get('create', 'ContactDetailsController@create');
            Route::post('store', 'ContactDetailsController@store');
            Route::get('/edit/{id}', 'ContactDetailsController@edit');
            Route::get('/destroy/{id}', 'ContactDetailsController@destroy');
        });

        Route::resource('terms-conditions', 'TermsConditionController');

        Route::group(['prefix' => 'budget_code'], function () {
            Route::resource('budget_code', 'BudgetCodeController');
            Route::get('view', 'BudgetCodeController@index');
            Route::get('create', 'BudgetCodeController@create');
            Route::post('store', 'BudgetCodeController@store');
            Route::get('/edit/{id}', 'BudgetCodeController@edit');
            Route::get('/destroy/{id}', 'BudgetCodeController@destroy');
        });

        Route::group(['prefix' => 'currency'], function () {
            Route::resource('currency', 'CurrencySetupController');
            Route::get('view', 'CurrencySetupController@index');
            Route::get('create', 'CurrencySetupController@create');
            Route::post('store', 'CurrencySetupController@store');
            Route::get('/edit/{id}', 'CurrencySetupController@edit');
            Route::get('/destroy/{id}', 'CurrencySetupController@destroy');
            Route::get('/make-default/{id}', 'CurrencySetupController@makeDefault');
        });

        Route::group(['prefix' => 'demande'], function () {
            Route::resource('demande', 'DemandeNameController');
            Route::get('view', 'DemandeNameController@index');
            Route::get('create', 'DemandeNameController@create');
            Route::post('store', 'DemandeNameController@store');
            Route::get('/edit/{id}', 'DemandeNameController@edit');
            Route::get('/destroy/{id}', 'DemandeNameController@destroy');
        });

        Route::group(['prefix' => 'excel'], function () {
            Route::get('suppliers', 'ExcelDataUploadController@supplier');
            Route::get('items', 'ExcelDataUploadController@item');
            Route::get('tenders', 'ExcelDataUploadController@tender');
            Route::get('itemtotenders', 'ExcelDataUploadController@itemtotender');
            Route::post('post-upload-suppliers-excel', 'ExcelDataUploadController@postUploadSuppliersExcel');
            Route::post('post-upload-items-excel', 'ExcelDataUploadController@postUploadItemsExcel');
            Route::post('post-upload-tenders-excel', 'ExcelDataUploadController@postUploadTendersExcel');
            Route::post('post-upload-itemstotenders-excel', 'ExcelDataUploadController@postUploadItemstotendersExcel');
        });

        Route::group(['prefix' => 'upload-file'], function () {
            Route::get('file', 'FileUploadController@fileUpload');
            Route::post('post-file', 'FileUploadController@postFileUpload');
        });

// Reports =================================================================
//        Route::any('tender-participates', 'ReportsController@tenderParticipate');
//        Route::any('awarded-supplier-list', 'ReportsController@awardedSupplierList');

        Route::any('tender-participates', 'ReportsController@tenderParticipate');
        Route::any('tender-participates-excel-download', 'PrintController@tenderParticipatesExcelDow');

        Route::any('awarded-supplier-list', 'ReportsController@awardedSupplierList');
        Route::any('awarded-supplier-list-excel-download', 'PrintController@awardedSupplierListExcelDow');

        Route::any('cat-pro-supplier-list', 'ReportsController@catProSupplierList');
        Route::any('cat-pro-supplier-list-excel-download', 'PrintController@catProSupplierListExcelDow');

        Route::any('supplier-report', 'ReportsController@supplierReport');
        Route::any('supplier-report-excel-export', 'PrintController@supplierReport');

        Route::any('budget-code-wise-item', 'ReportsController@budgetCodeWiseItem');
        Route::any('budget-code-wise-item-excel-download', 'PrintController@budgetCodeWiseItemExcelDow');

// Ajax request ============================================================
        Route::post('nsd-wise-supplier', 'ReportsController@nsdWiseSupplier');
        Route::post('nsd-wise-tender', 'ReportsController@nsdWiseTender');
        Route::post('category-wise-items', 'ReportsController@categoryWiseItems');
        Route::post('zone-wise-nsd-bsd', 'AjaxResponseController@zoneWiseNsdBsd');
        Route::post('single-zone-wise-nsd-bsd', 'AjaxResponseController@SingleZoneWiseNsdBsd');

// Awarder reoport search
        Route::post('awarded-rep-supplier-name-live-search', 'AjaxResponseController@awardedRepSupplierNameLiveSearch');
        Route::post('awarded-rep-item-name-live-search', 'AjaxResponseController@awardedRepItemNameLiveSearch');
// Item to tender search
        Route::post('item-to-tender-item-name-live-search', 'AjaxResponseController@itemsForItemToTender');
        Route::post('demand-item-name-live-search', 'AjaxResponseController@itemsForDemand');
// Tender participate
        Route::post('tender-perticipate-tender-name-live-search', 'AjaxResponseController@tenderPerticRepTenderNameSearch');
        Route::post('tender-perticipate-tender-number-live-search', 'AjaxResponseController@tenderPerticRepTenderNumberSearch');
        Route::post('tender-perticipate-tender-po-live-search', 'AjaxResponseController@tenderPerticRepTenderPoSearch');
// Category & Item Wise Supplier
        Route::post('category-item-wise-teport-item-live-search', 'AjaxResponseController@categoryItemRepItemSearch');                        
        Route::resource('/terms-conditions-category', 'TermsConditionCategoryController');
        Route::get('/terms-conditions-category/destroy/{id}', 'TermsConditionCategoryController@destroy');

        Route::resource('evaluation-point', 'EvaluationPointTableController');
        Route::resource('evaluation-position', 'EvaluationPositionController');
        Route::resource('evaluation-criteria', 'EvaluationCriteriaController');

        //      strength calculation
        Route::get('/strength-calculation', 'StrengthController@index');
        Route::get('/strength-calculation/details/{id}', 'StrengthController@details');
        Route::get('/strength-calculation/create', 'StrengthController@create');
        Route::post('/strength-calculation/store', 'StrengthController@store');
        Route::get('/strength-calculation/edit/{id}', 'StrengthController@edit');
        Route::post('/strength-calculation/update', 'StrengthController@update');
        Route::get('/strength-calculation/delete/{id}', 'StrengthController@destroy');
        Route::get('/strength-calculation/item/delete/{id}', 'StrengthController@itemDestroy');
        Route::get('/strength-calculation/print-pdf/{id}', 'StrengthController@printPdf');
        Route::get('/strength-calculation/print-excel/{id}', 'StrengthController@printExcel');
        Route::post('/strength-calculation/ajax', 'StrengthController@strength');

        Route::get('evaluated-tender', 'EvaluatedTenderController@index');
        Route::get('evaluated-tender-quaterly', 'EvaluatedTenderController@evaluatedTenderQuaterly');
        Route::get('yearly-performance-evaluation', 'EvaluatedTenderController@yearlyPerformanceEvaluation');

    });

});

// Route::get('databaseupdate', function() {


//         $zone                   = 'nsd';
//         $organization           = 'nssd_dhaka';
//         //$lastUpldatedDateTime   = $date;

//         $zoneInfo = \App\Zone::where('alise','=',$zone)->first();
//         $navalLocation = \App\NsdName::where('alise','=',$organization)->orderBy('id')->first();

        
//         $data['zone']           = $zone;
//         $data['organization']   = $organization;
//         ///$data['date']           = $date;

//         $data['tenders'] = DB::table($zoneInfo->alise.'_tenders')
//             ->where('supplier_id','!=',null)
//             //->where('supplier_id','!=','')
//             //->whereRaw("find_in_set('".$navalLocation->id."',nsd_id)")
//             ->get();

// //echo "<pre>"; print_r(count($data['tenders'])); exit;

//         foreach ($data['tenders'] as $key => $value) {
//             \Session::put('zoneAlise', strtolower($zone));

//             $suppInf = \App\Supplier::find($value->supplier_id);
//             if(!empty($suppInf)){ 
//                 $str = explode("_",$suppInf->all_org_id);

//                 $olt_id = $str[0];
//                 if( isset($str[1]) ){
//                     if($str[1] == 'dhaka' || $str[1] == 'chattagram' || $str[1] == 'khulna'){
//                         $olt_id .= '_'.$str[1];
//                     }
//                 }

//                 $tender = \App\Tender::find($value->id);
//                 $tender->supplier_id = $olt_id.'_'.$value->supplier_id;
//                 //$itemToten->tender_id = $olt_id.'_'.$value->tender_id;
//                 $tender->save();

//             }

//             // $tenderInf = \App\Tender::find($value->tender_id);
//             // if(!empty($tenderInf)){ 
//             //     $str = explode("_",$tenderInf->all_org_tender_id);

//             //     $olt_id = $str[0];
//             //     if( isset($str[1]) ){
//             //         if($str[1] == 'dhaka' || $str[1] == 'chattagram' || $str[1] == 'khulna'){
//             //             $olt_id .= '_'.$str[1];
//             //         }
//             //     }

//             //     $itemToten = \App\ItemToTender::find($value->id);
//             //     $itemToten->all_org_itmtotender_id = $olt_id.'_'.$value->id;
//             //     $itemToten->tender_id = $olt_id.'_'.$value->tender_id;
//             //     $itemToten->save();

//             // }

//             // $itemInf = \App\Item::find($value->item_id);
//             // if(!empty($itemInf)){ 
//             //     $str2 = explode("_",$itemInf->all_org_item_id);

//             //     $olt_id2 = $str2[0];
//             //     if( isset($str2[1]) ){
//             //         if($str2[1] == 'dhaka' || $str2[1] == 'chattagram' || $str2[1] == 'khulna'){
//             //             $olt_id2 .= '_'.$str2[1];
//             //         }
//             //     }

//             //     $itemTen = \App\ItemToTender::find($value->id);
//             //     $itemTen->item_id = $olt_id2.'_'.$value->item_id;
//             //     $itemTen->save();

//             // }

//         }

// });

include ('frontend.php');
