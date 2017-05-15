<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin Interface Routes
Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function()
{
    Route::group(['middleware' => ['role:Admin']], function () {
        CRUD::resource('companies', 'Admin\CompanyCrudController');
        CRUD::resource('loan_interests', 'Admin\LoanInterestCrudController');
        CRUD::resource('loan_payment_terms', 'Admin\LoanPaymentTermCrudController');
        CRUD::resource('payment_schedules', 'Admin\PaymentScheduleCrudController');
    });

    /*=============================================
            Approve/Decline Loan Application
    ===============================================*/        
    Route::get('loan_applications/pending', 'LoanApplicationController@pending_view');
    Route::get('loan_applications/declined', 'LoanApplicationController@declined_view');
    Route::post('loan_applications/process_application', 'LoanApplicationController@process_application');

    CRUD::resource('borrowers', 'Admin\BorrowerCrudController');

    /*=============================================
                    Loan Application
    ===============================================*/
    Route::get('loan_applications', 'LoanApplicationController@index');
    Route::get('loan_applications/create', 'LoanApplicationController@create');
    Route::get('loan_applications/active', 'LoanApplicationController@active');
    Route::get('loan_applications/{id}', 'LoanApplicationController@details');
    Route::get('loan_applications/details/{id}', 'LoanApplicationController@viewdetails');
    Route::post('loan_applications/save', 'LoanApplicationController@save');
        //REPORTS UNDER LOAN APPLICATION
    Route::get('loan_applications/promissory_note/{id}', 'LoanApplicationController@promissory_note');
    Route::get('loan_applications/payment_schedule/{id}', 'LoanApplicationController@payment_schedule');
    Route::get('loan_applications/generate/{id}', 'LoanApplicationController@generate_form');

    /*==============================================
                      Loan Payment
    ================================================*/
    Route::get('loan_payments', 'LoanPaymentController@index');
    Route::get('loan_payments/{id}', 'LoanPaymentController@payment_view');
    Route::post('loan_payments/process_payment', 'LoanPaymentController@process_payment');
    Route::post('loan_payments/process_due_payment', 'LoanPaymentController@process_due_payment');

    /*==============================================
                        Reports
    ================================================*/
    Route::get('reports/loan_applications', 'ReportsController@approved_loan_application_view');
    Route::get('reports/loan_collections', 'ReportsController@loan_collection_view');
    Route::get('reports/income_shares', 'ReportsController@income_share_view');
    Route::get('reports/loan_applications/pdf', 'ReportsController@approved_loan_application');
    Route::get('reports/loan_collections/pdf', 'ReportsController@loan_collection');
    Route::get('reports/income_shares/pdf', 'ReportsController@income_share');

    /*==============================================
                    AJAX-loaded Data
    ================================================*/
    Route::get('comaker1_data', 'LoanApplicationController@comaker1');
    Route::get('comaker2_data', 'LoanApplicationController@comaker2'); 
  // [...] other routes
});
 	Route::get('borrowers_data', 'LoanApplicationController@borrowers');

//Soon this will be in /admin route to protect the data from public.
Route::post('loan_applications/precompute', 'LoanApplicationController@precompute');
Route::get('loan_applications/index/data', 'LoanApplicationController@index_data');
Route::get('loan_applications/active/data', 'LoanApplicationController@active_data');
Route::get('loan_payments/applications', 'LoanPaymentController@approved_data');
Route::get('payment_collections/dates/{id}', 'PaymentCollectionController@collection_dates');