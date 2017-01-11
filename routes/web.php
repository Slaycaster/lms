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
  // Backpack\CRUD: Define the resources for the entities you want to CRUD.
    CRUD::resource('companies', 'Admin\CompanyCrudController');
    CRUD::resource('borrowers', 'Admin\BorrowerCrudController');
    CRUD::resource('loan_interests', 'Admin\LoanInterestCrudController');
    CRUD::resource('loan_payment_terms', 'Admin\LoanPaymentTermCrudController');

    /*=============================================
                    Loan Application
    ===============================================*/
    Route::get('loan_applications', 'LoanApplicationController@index');
    Route::get('loan_applications/details/{id}', 'LoanApplicationController@details');
    Route::get('loan_applications/pending', 'LoanApplicationController@pending_view');
    Route::get('loan_applications/declined', 'LoanApplicationController@declined_view');
    Route::post('loan_applications/save', 'LoanApplicationController@save');
    Route::post('loan_applications/process_application', 'LoanApplicationController@process_application');

    /*==============================================
                      Loan Payment
    ================================================*/
    Route::get('loan_payments', 'LoanPaymentController@index');
    Route::get('loan_payments/{id}', 'LoanPaymentController@payment_view');

    /*==============================================
                    AJAX-loaded Data
    ================================================*/
 	Route::get('borrowers_data', 'LoanApplicationController@borrowers');
 	Route::get('comaker1_data', 'LoanApplicationController@comaker1');
 	Route::get('comaker2_data', 'LoanApplicationController@comaker2'); 
  // [...] other routes
});

Route::get('loan_applications/pending/data', 'LoanApplicationController@pending_data');
Route::get('loan_applications/declined/data', 'LoanApplicationController@declined_data');
Route::get('loan_payments/applications', 'LoanPaymentController@approved_data');