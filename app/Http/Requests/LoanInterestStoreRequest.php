<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanInterestStoreRequest extends \Backpack\CRUD\app\Http\Requests\CrudRequest
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
            'loan_interest_name' => 'required|unique:loan_interests,loan_interest_name',
            'loan_interest_rate' => 'required'
        ];
    }
}

?>