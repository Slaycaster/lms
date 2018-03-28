<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentScheduleStoreRequest extends \Backpack\CRUD\app\Http\Requests\CrudRequest
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
            'payment_schedule_name' => 'required|unique:payment_schedules,payment_schedule_name',
            'payment_schedule_days_interval' => 'required'
        ];
    }
}

?>