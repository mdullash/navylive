<?php



        Route::get('front-specification-pdf/{zone?}/{nsd?}/{id}', 'FrontEndController@specificationPdf');
        Route::get('front-specification-doc/{zone?}/{nsd?}/{id}', 'FrontEndController@specificationDoc');
        Route::get('front-notice-pdf/{zone?}/{nsd?}/{id}', 'FrontEndController@noticePdf');
        Route::get('front-notice-brd-pdf/{id}', 'FrontEndController@noticeBrdPdf');

		// Front Tender
        Route::any('{zone?}/{nsd?}/front-tender', 'FrontTenderController@index');
        Route::any('{zone?}/{nsd?}/front-po-winner', 'FrontTenderController@frontPoWinner');
        // Front General notice
        Route::any('{zone?}/{nsd?}/front-general-notice', 'FrontGeneralNoticeController@index');
        //Front Supplier
        Route::any('{zone?}/{nsd?}/front-supplier', 'FrontSupplierController@index');
        //Front Contact us
        Route::any('{zone?}/{nsd?}/front-contact-us', 'FrontContactUsController@index');
        // Sign up
        Route::any('{zone?}/{nsd?}/terms-condiition', 'FrontRegistrationController@termCondi');
        Route::any('{zone?}/{nsd?}/front-agree-terms-conditions', 'FrontRegistrationController@postTermCondi');

        Route::any('{zone?}/{nsd?}/{trmcnd?}/sign-up', 'FrontRegistrationController@index');
        Route::any('{zone?}/{nsd?}/front-registration1', 'FrontRegistrationController@store');
        Route::any('{zone?}/{nsd?}/{id}/front-registration2', 'FrontRegistrationController@registrationsForm2');
        Route::any('{zone?}/{nsd?}/front-registration2', 'FrontRegistrationController@store2');
        Route::any('{zone?}/{nsd?}/reg-success', 'FrontRegistrationController@regSuccess');
        Route::get('/{zone?}/{nsd?}/login','SupplierLoginController@login')->name('supplier_login');
        Route::post('/{zone?}/{nsd?}/supplier-login','SupplierLoginController@submit');

        Route::get('/{zone?}/{nsd?}/dashboard','FrontEndController@dashboard');
        Route::get('/{zone?}/{nsd?}/enlistment-track','FrontEndController@enlistment_track');
        Route::get('/{zone?}/{nsd?}/tender-participant-status','FrontEndController@tender_participant_status');
        Route::get('/supplier-po-print/{id}','FrontEndController@printPoGeneration');
        Route::get('/{zone?}/{nsd?}/evaluation-report','FrontEndController@evaluation_report');
        Route::get('/{zone?}/{nsd?}/supplier-profile','FrontEndController@supplierProfile');
        Route::get('/{zone?}/{nsd?}/supplier-profile','FrontEndController@supplierProfile');
        Route::get('/{zone?}/{nsd?}/supplier-change-password','FrontEndController@changePassword');
        Route::post('/{zone?}/{nsd?}/supplier-change-password','FrontEndController@changePasswordSubmit');

        //supplier chat
        Route::get('/{zone?}/{nsd?}/supplier-chat','FrontEndController@supplierChat');
        Route::post('/{zone?}/{nsd?}/supplier-chat-submit','FrontEndController@supplierChatPost');

        Route::get('/{zone?}/{nsd?}/logout','SupplierLoginController@logout');

        // Route::get('/webNewpage/{zone?}/{nsd?}','FrontEndController@webNewpage');
        Route::get('/{zone?}/{nsd?}','FrontEndController@webNewpage');

        Route::get('/{zone?}/{nsd?}/supplier-form-submit','FrontSupplierController@supplier_submit');
        Route::post('/{zone?}/{nsd?}/supplier-form-update','FrontSupplierController@updateSupplier_info');
