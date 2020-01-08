<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BookCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BookCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Book');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/book');
        $this->crud->setEntityNameStrings('book', 'books');
    }

    protected function setupListOperation()
    {
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Tên sách',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'name' => 'author.name',
            'label' => 'Tên tác giả',
            'type' => 'text'
        ]);
        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => "Tên sách",
                'type' => 'text',
            ],
//            [  // Select2
//                'label' => "Tên tác giả",
//                'type' => 'select2',
//                'name' => 'author_id', // the db column for the foreign key
//                'entity' => 'author', // the method that defines the relationship in your Model
//                'attribute' => 'name', // foreign key attribute that is shown to user
//                'model' => Author::class, // foreign key model
//            ]
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
        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'name',
            'label'=> 'Tên tác giả'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('whereHas', 'author', function($query) use($value) {
                    $query->where('name','LIKE',"%$value%");
                });
            } );
        $this->crud->enableExportButtons();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(BookRequest::class);

        // TODO: remove setFromDb() and manually define Fields
        $this->crud->setFromDb();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
