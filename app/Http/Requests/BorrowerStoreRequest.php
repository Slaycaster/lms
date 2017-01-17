<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BorrowerStoreRequest extends \Backpack\CRUD\app\Http\Requests\CrudRequest
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
            'borrower_type' => 'required',
            'borrower_last_name' => 'required',
            'borrower_first_name' => 'required',
            'borrower_home_address' => 'required',
            'borrower_email' => 'required',
            'borrower_civil_status' => 'required',
            'borrower_birth_date' => 'required',
            'borrower_employment_date' => 'required',
            'borrower_assignment_date' => 'required'
        ];
    }
}

?>