<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

/*
----------------------------------------------------------------------------------------------
	VALIDATION: change the requests to match your own file names if you need form validation
----------------------------------------------------------------------------------------------
*/
use App\Http\Requests\BorrowerStoreRequest as BorrowerStoreRequest;
use App\Http\Requests\BorrowerUpdateRequest as BorrowerUpdateRequest;

class BorrowerCrudController extends CrudController
{
    public function __construct()
    {
    	parent::__construct();

    	$this->crud->setModel('App\Borrower');
    	$this->crud->setRoute('admin/borrowers');
    	$this->crud->setEntityNameStrings('borrower', 'borrowers');

    	$this->crud->setColumns
    	(
    		[
    			$this->crud->addColumn
    			(
    				[
    					'name' => 'borrower_type',
    					'label' => 'Type'
    				]
    			),

    			$this->crud->addColumn
    			(
    				[
    					'name' => 'borrower_last_name',
    					'label' => 'Last Name'
    				]
    			),

    			$this->crud->addColumn
    			(
    				[
    					'name' => 'borrower_first_name',
    					'label' => 'First Name'
    				]
    			),

    			$this->crud->addColumn
    			(
    				[
    					'name' => 'borrower_middle_name',
    					'label' => 'Middle Name'
    				]
    			),

    			$this->crud->addColumn
    			(
    				[
    					'name' => 'borrower_employment_date',
    					'label' => 'Employment Date'
    				]
    			),

    			$this->crud->addColumn
    			(
    				[
    					'name' => 'borrower_assignment_date',
    					'label' => 'Assignment Date'
    				]
    			),

    			$this->crud->addColumn
    			(
    				[
    					'label' => 'Company',
    					'type' => 'select',
    					'name' => 'company_id',
    					'entity' => 'company',
    					'attribute' => 'company_code',
    					'model' => 'App\Company'
    				]
    			)
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			//Select_from_array
    			'name' => 'borrower_type',
    			'label' => 'Type',
    			'type' => 'select_from_array',
    			'options' => ['Regular' => 'Regular', 'Deployed' => 'Deployed']
    		]
    	);

        $this->crud->addField
        (
            [
                //Relationship - Company
                'label' => 'Company',
                'type' => 'select',
                'name' => 'company_id',
                'entity' => 'company',
                'attribute' => 'company_name',
                'model' => 'App\Company'
            ]
        );

    	$this->crud->addField
    	(
    		[
    			'name' => 'borrower_last_name',
    			'label' => 'Last Name',
    			'type' => 'text'
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			'name' => 'borrower_first_name',
    			'label' => 'First Name',
    			'type' => 'text'
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			'name' => 'borrower_middle_name',
    			'label' => 'Middle Name',
    			'type' => 'text'
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			'name' => 'borrower_home_address',
    			'label' => 'Home Address',
    			'type' => 'address'
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			'name' => 'borrower_email',
    			'label' => 'E-mail Address',
    			'type' => 'email'
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			'name' => 'borrower_civil_status',
    			'label' => 'Civil Status',
    			'type' => 'select_from_array',
    			'options' => ['Single' => 'Single', 'Married' => 'Married', 'Widowed' => 'Widowed']
    		]
    	);

    	$this->crud->addField
    	(
    		[   // Date
			    'name' => 'borrower_birth_date',
			    'label' => 'Birthday',
			    'type' => 'date'
			]
    	);

    	$this->crud->addField
    	(
    		[
    			//Date
    			'name' => 'borrower_employment_date',
    			'label' => 'Employment Date',
    			'type' => 'date'
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			'name' => 'borrower_assignment_date',
    			'label' => 'Assignment Date',
    			'type' => 'date'
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			'name' => 'borrower_spouse_name',
    			'label' => 'Spouse (if any)',
    			'type' => 'text'
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			'name' => 'borrower_no_of_children',
    			'label' => 'No. of children (if any)',
    			'type' => 'number'
    		]
    	);
    }

    public function store(BorrowerStoreRequest $request)
	{
		return parent::storeCrud();
	}

	public function update(BorrowerUpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
