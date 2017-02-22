<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanPaymentTermStoreRequest extends \Backpack\CRUD\app\Http\Requests\CrudRequest
{
	/**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize()
    {
        //only allow updates if the user is currently logged in.
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'loan_payment_term_name' => 'required|unique:loan_payment_terms,loan_payment_term_name',
            'loan_payment_term_no_of_months' => 'required',
            'loan_payment_term_collection_cycle' => 'required',
        ];
    }
}

?>