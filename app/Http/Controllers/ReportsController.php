<?php

namespace App\Http\Controllers;

use Request, Session, DB, Validator, Input, Redirect;

use App\Http\Requests;
use Barryvdh\DomPDF\Facade as PDF;

use App\Company;

class ReportsController extends Controller
{
	/*==========================================================
						Laravel Views
	==========================================================*/
    public function approved_loan_application_view()
    {
    	$companies = Company::pluck('company_name', 'id');
    	return view('reports.approved.blade.php')
            ->with('companies', $companies);
    }

    public function loan_collection_view()
    {
    	$companies = Company::pluck('company_name', 'id');
    	return view('reports.loan_collection.blade.php')
            ->with('companies', $companies);
    }

    public function income_share_view()
    {
    	$companies = Company::pluck('company_name', 'id');
    	return view('reports.income_share.blade.php')
            ->with('companies', $companies);
    }

    /*=========================================================
    					DOMPDF Views
    =========================================================*/
    public function approved_loan_application()
    {
        Session::put('company_id', Request::input('company_id'));
        Session::put('date', Request::input('date'));
        $pdf = PDF::loadView('reports.approved-pdf')->setPaper('Letter');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(808, 580, "Moo Loans Inc. - Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->stream();
    }

    public function loan_collection_view()
    {
        Session::put('company_id', Request::input('company_id'));
        Session::put('date', Request::input('date'));
        $pdf = PDF::loadView('reports.loan_collection-pdf')->setPaper('Letter');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(808, 580, "Moo Loans Inc. - Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->stream();
    }

    public function income_share()
    {
    	Session::put('company_id', Request::input('company_id'));
        Session::put('date', Request::input('date'));
        $pdf = PDF::loadView('reports.income_share-pdf')->setPaper('Letter');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(808, 580, "Moo Loans Inc. - Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->stream();
    }

}
