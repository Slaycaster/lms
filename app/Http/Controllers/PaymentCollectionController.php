<?php

namespace App\Http\Controllers;

use Session, DB, Validator, Input, Redirect, Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

//Model 
use App\PaymentCollection;

//Third-party
use Yajra\Datatables\Datatables;
use Barryvdh\DomPDF\Facade as PDF;

use App\User;
use Illuminate\Support\Facades\Auth;

//PHP
use DateTime;

class PaymentCollectionController extends Controller
{
	public function collection_dates($company_id)
	{
		$timestamp = time()+date("Z");
		$today = gmdate("Y/m/d",$timestamp);
		$startingDate = (new DateTime(date('Y-m-d', strtotime($today.' - 2 years'))));

		$payment_collections = PaymentCollection::where('company_id', '=', $company_id)
			->where('payment_collection_date', '>=', $startingDate)
            ->select('payment_collections.payment_collection_date')
  			->distinct()
            ->orderBy('payment_collections.payment_collection_date', 'desc');
        return Datatables::of($payment_collections)
        	->add_column('Select', '{{ Form::radio(\'payment_collection_date\', $payment_collection_date) }}')
        	->make();	
	}
}