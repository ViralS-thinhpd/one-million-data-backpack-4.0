<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CustomerRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CustomerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CustomerCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Customer');
        $this->crud->orderBy('id','DESC');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/customer');
        $this->crud->setEntityNameStrings('customer', 'customers');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name' => 'name',
                'label' => 'Tên',
                'type' => 'text'
            ],
            [
                'name' => 'age',
                'label' => 'Tuổi',
                'type' => 'text'
            ],
            [
                'name' => 'address',
                'label' => 'Địa chỉ',
                'type' => 'text'
            ],
            [
                // n-n relationship (with pivot table)
                'label' => "Tên sách", // Table column heading
                'type' => "select_multiple",
                'name' => 'books', // the method that defines the relationship in your Model
                'entity' => 'books', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
            ],
        ]);
        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => "Tên khách hàng",
                'type' => 'text',
            ],
            [
                'name' => 'age',
                'label' => "Tuổi",
                'type' => 'number',
            ],
            [
                'name' => 'address',
                'label' => "Địa chỉ",
                'type' => 'text',
            ],
        ]);
        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> 'Date range'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'created_at', '>=', $dates->from);
                $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
            });
        $this->crud->enableExportButtons();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(CustomerRequest::class);

        // TODO: remove setFromDb() and manually define Fields
        $this->crud->setFromDb();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
