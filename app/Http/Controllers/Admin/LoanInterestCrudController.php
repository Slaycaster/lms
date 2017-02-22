<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

/*
----------------------------------------------------------------------------------------------
	VALIDATION: change the requests to match your own file names if you need form validation
----------------------------------------------------------------------------------------------
*/
use App\Http\Requests\LoanInterestStoreRequest as LoanInterestStoreRequest;
use App\Http\Requests\LoanInterestUpdateRequest as LoanInterestUpdateRequest;

class LoanInterestCrudController extends CrudController
{
    public function __construct()
    {
    	parent::__construct();

    	$this->crud->setModel('App\LoanInterest');
    	$this->crud->setRoute('admin/loan_interests');
    	$this->crud->setEntityNameStrings('Loan Interest', 'Loan Interests');
        $this->crud->removeButton('delete');
        $this->crud->removeButton('edit');
    	$this->crud->setColumns
    	(
    		[
    				[
    					'name' => 'loan_interest_name',
    					'label' => 'Loan Interest Name'
    				],
    			
    				[
    					'name' => 'loan_interest_rate',
    					'label' => 'Rate'
    				]
    			
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			//Text
    			'name' => 'loan_interest_name',
    			'label' => 'Loan Interest Name',
    			'type' => 'text'
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			//Text
    			'name' => 'loan_interest_rate',
    			'label' => 'Rate',
    			'type' => 'number'
    		]
    	);
    }

    public function store(LoanInterestStoreRequest $request)
	{
		return parent::storeCrud();
	}

	public function update(LoanInterestUpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
