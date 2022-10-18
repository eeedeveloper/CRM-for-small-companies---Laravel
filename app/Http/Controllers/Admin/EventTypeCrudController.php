<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EventTypeRequest;

class EventTypeCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel('App\Models\EventType');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/event_type');
        $this->crud->setEntityNameStrings(trans('fields.event_type'), trans('fields.event_type'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->removeButton('delete');

        $this->crud->addButtonFromView('line', 'delete-mandatory', 'delete-mandatory', 'beginning');

        $this->crud->setColumns([
            [
                'name'  => 'name',
                'label' => trans('fields.name'),
                'type'  => 'text',
            ],
//            [
//                'name' => 'has_file',
//                'label' => trans('fields.has_file'),
//                'type' => 'boolean',
////                'options' => [0 => 'No', 1 => 'Yes'],
//                'allows_null' => false,
//                'default' => 0
//            ],
//            [
//                'name' => 'has_comment',
//                'label' => trans('fields.has_comment'),
//                'type' => 'boolean',
////                'options' => [0 => 'Approved', 1 => 'Disabled'],
//                'allows_null' => false,
//                'default' => 0,
//            ],
//            [
//                'name' => 'has_amount',
//                'label' => trans('fields.has_amount'),
//                'type' => 'boolean',
////                'options' => [0 => 'No', 1 => 'Yes'],
//                'allows_null' => false,
//                'default' => 0
//            ],
            [
                'name' => 'has_to_appear',
                'label' => trans('fields.has_to_appear'),
                'type' => 'boolean',
                'allows_null' => false,
                'default' => 0
            ],
            [
                'name' => 'is_working_day',
                'label' => trans('fields.is_working_day'),
                'type' => 'boolean',
                'allows_null' => false,
                'default' => 0
            ],
            [   // ColorPicker
                'name' => 'color',
                'label' => 'Color',
                'type' => 'color',
                'color_picker_options' => ['customClass' => 'custom-class']
            ],
        ]);

        // Fields
        $this->crud->addFields([
            [
                'name'  => 'name',
                'label' => trans('fields.name'),
                'type'  => 'text',
            ],
//            [
//                'name' => 'has_file',
//                'label' => trans('fields.has_file'),
//                'type' => 'select_from_array',
//                'options' => [0 => 'No', 1 => 'Yes'],
//                'allows_null' => false,
//                'default' => 0
//            ],
//            [
//                'name' => 'has_comment',
//                'label' => trans('fields.has_comment'),
//                'type' => 'select_from_array',
//                'options' => [0 => 'No', 1 => 'Yes'],
//                'allows_null' => false,
//                'default' => 0
//            ],
//            [
//                'name' => 'has_amount',
//                'label' => trans('fields.has_amount'),
//                'type' => 'select_from_array',
//                'options' => [0 => 'No', 1 => 'Yes'],
//                'allows_null' => false,
//                'default' => 0
//            ],
            [
                'name' => 'has_to_appear',
                'label' => trans('fields.has_to_appear'),
                'type' => 'select_from_array',
                'options' => [0 => 'No', 1 => 'Yes'],
                'allows_null' => false,
                'default' => 0
            ],
            [
                'name' => 'is_working_day',
                'label' => trans('fields.is_working_day'),
                'type' => 'select_from_array',
                'options' => [0 => 'No', 1 => 'Yes'],
                'allows_null' => false,
                'default' => 0
            ],
            [   // ColorPicker
                'name' => 'color',
                'label' => 'Color',
                'type' => 'color',
                'color_picker_options' => ['customClass' => 'custom-class']
            ],
        ]);

        // add asterisk for fields that are required in EventRequest

        $this->crud->setRequiredFields(EventTypeRequest::class, 'create');
        $this->crud->setRequiredFields(EventTypeRequest::class, 'edit');
    }

    public function store(EventTypeRequest $request)
    {
        // your additional operations before save here
        $request->request->set('company_id', backpack_user()->company->id);
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(EventTypeRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        if(!$this->crud->model->find($id)->mandatory) {
            // get entry ID from Request (makes sure its the last ID for nested resources)
            $id = $this->crud->getCurrentEntryId() ?? $id;

            return $this->crud->delete($id);
        }
    }
}
