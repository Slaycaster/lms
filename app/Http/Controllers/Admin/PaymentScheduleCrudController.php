<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

/*
----------------------------------------------------------------------------------------------
	VALIDATION: change the requests to match your own file names if you need form validation
----------------------------------------------------------------------------------------------
*/
use App\Http\Requests\PaymentScheduleStoreRequest as PaymentScheduleStoreRequest;
use App\Http\Requests\PaymentScheduleUpdateRequest as PaymentScheduleUpdateRequest;

class PaymentScheduleCrudController extends CrudController
{
    public function __construct()
    {
    	parent::__construct();

    	$this->crud->setModel('App\PaymentSchedule');
    	$this->crud->setRoute('admin/payment_schedules');
    	$this->crud->setEntityNameStrings('Payment Schedule', 'Payment Schedules');

    	$this->crud->setColumns
    	(
    		[
    			[
    				'name' => 'payment_schedule_name',
    				'label' => 'Schedule Name'
    			],

    			[
    				'name' => 'payment_schedule_days_interval',
    				'label' => 'Days Interval'
    			]
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			//Text
    			'name' => 'payment_schedule_name',
    			'label' => 'Payment Schedule Name',
    			'text' => 'text'
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			//Text
    			'name' => 'payment_schedule_days_interval',
    			'label' => 'Schedule Days Interval',
    			'type' => 'number'
    		]
    	);
    }

    public function store(PaymentScheduleStoreRequest $request)
    {
    	return parent::storeCrud();
    }

    public function update(PaymentScheduleUpdateRequest $request)
    {
    	return parent::storeCrud();
    }
}
