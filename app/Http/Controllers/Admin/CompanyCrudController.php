<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

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

    	$this->crud->setColumns
    	(
    		[
    			$this->crud->addColumn
    			(
    				[
    					'name' => 'company_name',
    					'label' => 'Company Name'
    				]
    			),

    			$this->crud->addColumn
    			(
    				[
    					'name' => 'company_code',
    					'label' => 'Code'
    				]
    			),

    			$this->crud->addColumn
    			(
    				[
    					'name' => 'company_address',
    					'label' => 'Address'
    				]
    			)
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
    			//Address
    			'name' => 'company_address',
    			'label' => 'Address',
    			'type' => 'address'
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
