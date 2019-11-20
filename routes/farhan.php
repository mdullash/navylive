<?php

Route::group(['prefix' => 'suppliers'], function () {
    Route::get('enlistment/index/{status}','EnlistmentController@index');
    Route::get('enlistment/approved/{id}','EnlistmentController@approve');
    Route::get('enlistment/rejected/{id}','EnlistmentController@rejected');


    Route::get('waiting-for-clarence/index/{status}','SupplierApproveController@index');
    Route::get('waiting-for-clarence/approve/{id}','SupplierApproveController@approve');
    Route::get('waiting-for-clarence/{id}/view','SupplierApproveController@view');
    Route::get('waiting-for-clarence/{status}','SupplierApproveController@waitingForApproved');


    Route::resource('enlistment', 'EnlistmentController',['expect'=>'index']);
    Route::get('enlistment/{id}/supplier-info', 'EnlistmentController@supplier_info');
    Route::get('enlistment/{id}/supplier-info-approval', 'EnlistmentController@supplier_info_approval');
    Route::put('enlistment/supplier-info/{id}', 'EnlistmentController@updateSupplier_info')->name('supplierInfo.update');
    Route::put('enlistment/supplier-info-approval/{id}', 'EnlistmentController@updateSupplier_info_approval')->name('supplierInfo-approval.update');

   Route::resource('sells-form', 'SellsFormController');
   Route::get('sells-from/print-sells-from/{id}', 'SellsFormController@print');

   Route::get('sells-from/destroy/{id}', 'SellsFormController@destroy');

   Route::get('waiting-for-clarence/create','SupplierApproveController@create');
   Route::post('waiting-for-clarence/store','SupplierApproveController@store');


   Route::get('dni/index/{status}','DNIandNPMController@dni');
   Route::get('dni/approved/{id}','DNIandNPMController@dni_approved');
   Route::get('dni/{status}/clearance','DNIandNPMController@dni_create');
   Route::post('dni/store/{id}','DNIandNPMController@dni_store');

   Route::get('npm/index/{status}','DNIandNPMController@npm');
   Route::get('npm/approved/{id}','DNIandNPMController@npm_approved');
   Route::get('npm/{status}/clearance','DNIandNPMController@npm_create');
    Route::post('npm/store/{id}','DNIandNPMController@npm_store');


    Route::get('dni-npm-approval/','DNIAndNPMApprovalController@index');
    Route::post('dni-npm-approval/store','DNIAndNPMApprovalController@store');
    Route::get('dni-npm-approval/approve/{id}','DNIAndNPMApprovalController@approve');
    Route::get('dni-npm-approval/{id}/view','DNIAndNPMApprovalController@view');
    Route::post('dni-npm-approval/update/{id}','DNIAndNPMApprovalController@update');
    Route::get('dni-npm-approval/{status}','DNIAndNPMApprovalController@waitingForApproved');

    Route::get('supplier-approval/','SupplierApprovalInfoAfterDNSController@index');
    Route::post('supplier-approval/store','SupplierApprovalInfoAfterDNSController@store');
    Route::get('supplier-approval/approve/{id}','SupplierApprovalInfoAfterDNSController@approve');
    Route::get('supplier-approval/{id}/view','SupplierApprovalInfoAfterDNSController@view');
    Route::post('supplier-approval/update/{id}','SupplierApprovalInfoAfterDNSController@update');
    Route::get('supplier-approval/{status}','SupplierApprovalInfoAfterDNSController@waitingForApproved');




    //id card purchase
    Route::resource('id-card-purchase', 'IdCardPurchaseController');
    Route::get('id-card-purchase/print-id-card-purchase/{id}', 'IdCardPurchaseController@print');

    Route::get('id-card-purchase/destroy/{id}', 'IdCardPurchaseController@destroy');

    //renew supplier
    Route::resource('renew-supplier', 'RenewSupplierController');
    Route::get('renew-supplier/print-renew-supplier/{id}', 'RenewSupplierController@print');

    Route::get('renew-supplier/destroy/{id}', 'RenewSupplierController@destroy');


});
Route::post('supplier-mobile-number-live-search', 'AjaxResponseController@getSupplierbyMobileNumber');
Route::post('supplier-mobile-number-barcode-live-search', 'AjaxResponseController@getSupplierbyMobileNumberBarcode');
Route::post('mobile-number-live-search', 'AjaxResponseController@getSupplier');
Route::post('barcode-number-live-search', 'AjaxResponseController@getSupplierbyBarcode');

Route::get('tender-track','TenderTrackController@index');
Route::get('tender-track-download','TenderTrackController@download');
//issue
Route::get('issue/{prm}','DemandController@index');
Route::get('issue-view/{id}','TenderIssueController@view');
Route::post('waiting_for_issue/{id}','TenderIssueController@waiting_for_issue');
Route::get('issue_voucher/{id}', 'TenderIssueController@issue_voucher');
Route::any('issue-pdf-view/{id}', 'TenderIssueController@issuePdfView');
Route::get('issue_approve/{id}', 'TenderIssueController@approve_voucher');
Route::get('issue-reject/{id}', 'TenderIssueController@reject');

Route::get('bill/{prm}','DemandController@index');

Route::group(['prefix' => 'manual-tender'], function () {
    Route::get('view', 'ManualTenderController@index');
    Route::get('create', 'ManualTenderController@create');
    Route::post('store', 'ManualTenderController@store');
    Route::get('/edit/{id}', 'ManualTenderController@edit');
    Route::put('/update/{id}', 'ManualTenderController@update')->name('manual-tender.update');
    Route::get('/destroy/{id}', 'ManualTenderController@destroy');
});