<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;
/*
----------------------------------------------------------------------------------------------
	VALIDATION: change the requests to match your own file names if you need form validation
----------------------------------------------------------------------------------------------
*/
use App\Http\Requests\CompanyStoreRequest as CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest as CompanyUpdateRequest;

class CompanyCrudController extends CrudController
{
    public function __construct()
    {
    	parent::__construct();

    	$this->crud->setModel('App\Company');
    	$this->crud->setRoute('admin/companies');
    	$this->crud->setEntityNameStrings('company', 'companies');
        $this->crud->removeButton('delete');
        $this->crud->removeButton('edit');
            
    	$this->crud->setColumns
    	(
    		[
    			
    				[
    					'name' => 'company_name',
    					'label' => 'Company Name'
    				],
    			
    			
    				[
    					'name' => 'company_code',
    					'label' => 'Code'
    				],

                    [
                        'name' => 'company_income_share',
                        'label' => 'Income Share'
                    ],
    			

    				[
    					'name' => 'company_address',
    					'label' => 'Address'
    				],

                    [
                        'name' => 'company_contact_no',
                        'label' => 'Contact #'
                    ],
            
                    [
                        'name' => 'company_email',
                        'label' => 'E-mail'
                    ],
                
                    [
                        'name' => 'company_website',
                        'label' => 'Website'
                    ],

                    [
                        'name' => 'company_notes',
                        'label' => 'Notes'
                    ]
                
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			//Text
    			'name' => 'company_name',
    			'label' => 'Name',
    			'type' => 'text'
    		]
    	);

    	$this->crud->addField
    	(
    		[
    			//Text
    			'name' => 'company_code',
    			'label' => 'Code',
    			'type' => 'text'
    		]
    	);

        $this->crud->addField
        (
            [
                //Text
                'name' => 'company_income_share',
                'label' => 'Income Share',
                'type' => 'text'
            ]
        );

    	$this->crud->addField
    	(
    		[
    			//Address
    			'name' => 'company_address',
    			'label' => 'Address',
    			'type' => 'address'
    		]
    	);

        $this->crud->addField
        (
            [
                //Text
                'name' => 'company_contact_no',
                'label' => 'Contact #',
                'type' => 'text'
            ]
        );

        $this->crud->addField
        (
            [
                //Text
                'name' => 'company_email',
                'label' => 'E-mail',
                'type' => 'text'
            ]
        );

        $this->crud->addField
        (
            [
                //Text
                'name' => 'company_website',
                'label' => 'Website',
                'type' => 'text'
            ]
        );

        $this->crud->addField
        (
            [
                //Text
                'name' => 'company_notes',
                'label' => 'Notes',
                'type' => 'text'
            ]
        );
    }

    public function store(CompanyStoreRequest $request)
	{
		return parent::storeCrud();
	}

	public function update(CompanyUpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
