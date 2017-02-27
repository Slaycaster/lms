<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;
/*
----------------------------------------------------------------------------------------------
	VALIDATION: change the requests to match your own file names if you need form validation
----------------------------------------------------------------------------------------------
*/
use App\Http\Requests\BorrowerStoreRequest as BorrowerStoreRequest;
use App\Http\Requests\BorrowerUpdateRequest as BorrowerUpdateRequest;
use App\User;
use Illuminate\Support\Facades\Auth;
class BorrowerCrudController extends CrudController
{


    public function setup()
    {
        parent::setup();

        $this->crud->setModel('App\Borrower');
        $this->crud->setRoute('admin/borrowers');
        $this->crud->setEntityNameStrings('borrower', 'borrowers');
        if (!(Auth::user()->company->id == 1))
        {
            //$this->crud->query = $this->crud->query->where('company_id', Auth::user()->company->id); 
            $this->crud->addClause('where', 'company_id', '=', Auth::user()->company->id);
        }
        
        $this->crud->enableExportButtons();

    	$this->crud->setColumns
    	(
    		[
    			
    				[
    					'name' => 'borrower_type',
    					'label' => 'Type'
    				],
    			

    				[
    					'name' => 'borrower_last_name',
    					'label' => 'Last Name'
    				],

    				[
    					'name' => 'borrower_first_name',
    					'label' => 'First Name'
    				],

    				[
    					'name' => 'borrower_middle_name',
    					'label' => 'Middle Name'
    				],
    			
    				[
    					'name' => 'borrower_employment_date',
    					'label' => 'Employment Date'
    				],

    				[
    					'name' => 'borrower_assignment_date',
    					'label' => 'Assignment Date'
    				],

                    [
                        'name' => 'borrower_salary_gross_pay',
                        'label' => 'Salary Gross Pay'
                    ],

                    [
                        'name' => 'borrower_monthly_expenses',
                        'label' => 'Monthly Expenses'
                    ],

    				[
    					'label' => 'Company',
    					'type' => 'select',
    					'name' => 'company_id',
    					'entity' => 'company',
    					'attribute' => 'company_code',
    					'model' => 'App\Company'
    				]
    			
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
                //Select_from_array
                'name' => 'borrower_status',
                'label' => 'Type',
                'type' => 'select_from_array',
                'options' => ['Active' => 'Active', 'Resigned' => 'Resigned']
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
    			'type' => 'text'
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
			    'attributes' => [
                    'placeholder' => 'yyyy-mm-dd'
                ]
			]
    	);

    	$this->crud->addField
    	(
    		[
    			//Date
    			'name' => 'borrower_employment_date',
    			'label' => 'Employment Date',
    			'type' => 'text',
                'attributes' => [
                    'placeholder' => 'yyyy-mm-dd'
                ]
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			'name' => 'borrower_assignment_date',
    			'label' => 'Assignment Date',
    			'type' => 'text',
                'attributes' => [
                    'placeholder' => 'yyyy-mm-dd'
                ]
    		]
    	);

        $this->crud->addField
        (
            [
                'name' => 'borrower_salary_gross_pay',
                'label' => 'Salary Gross Pay',
                'type' => 'text'
            ]
        );

        $this->crud->addField
        (
            [
                'name' => 'borrower_monthly_expenses',
                'label' => 'Monthly Expenses',
                'type' => 'text'
            ]
        );

        $this->crud->addField
        (
            [
                'name' => 'borrower_resignation_date',
                'label' => 'Resignation Date',
                'attributes' => [
                    'placeholder' => 'yyyy-mm-dd'
                ]
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
