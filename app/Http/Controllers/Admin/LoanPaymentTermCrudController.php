<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

/*
----------------------------------------------------------------------------------------------
	VALIDATION: change the requests to match your own file names if you need form validation
----------------------------------------------------------------------------------------------
*/
use App\Http\Requests\LoanPaymentTermStoreRequest as LoanPaymentTermStoreRequest;
use App\Http\Requests\LoanPaymentTermUpdateRequest as LoanPaymentTermUpdateRequest;

class LoanPaymentTermCrudController extends CrudController
{
    public function __construct()
    {
    	parent::__construct();

    	$this->crud->setModel('App\LoanPaymentTerm');
    	$this->crud->setRoute('admin/loan_payment_terms');
    	$this->crud->setEntityNameStrings('Payment Term', 'Payment Terms');

    	$this->crud->setColumns
    	(
    		[
    				[
    					'name' => 'loan_payment_term_name',
    					'label' => 'Payment Term Name'
    				],

    				[
    					'name' => 'loan_payment_term_no_of_months',
    					'label' => 'No. of Months'
    				],

                    [
                        'name' => 'loan_payment_term_collection_cycle',
                        'label' => 'Collection Cycle'
                    ]
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			//Text
    			'name' => 'loan_payment_term_name',
    			'label' => 'Payment Term Name',
    			'type' => 'text'
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			//Text
    			'name' => 'loan_payment_term_no_of_months',
    			'label' => 'No. of Months',
    			'type' => 'number'
    		]
    	);

        $this->crud->addField
        (
            [
                //Text
                'name' => 'loan_payment_term_collection_cycle',
                'label' => 'Collection Cycle(s)',
                'type' => 'number'
            ] 
        );
    }

    public function store(LoanPaymentTermStoreRequest $request)
	{
		return parent::storeCrud();
	}

	public function update(LoanPaymentTermUpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
