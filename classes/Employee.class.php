<?php
namespace projectFlow;
use equal\orm\Model;

class Employee extends Model {

    public static function getColumns() {
        return [
            'name' => [
                'type'              => 'computed',
                'result_type'       => 'string',
                'description'       => "Full Name of the employee.",
                'function'          => 'calcName',
                'store'             => true
            ],
            'firstname' => [
                'type'              => 'string',
                'required'          => true,
                'description'       => "First Name of the employee."
            ],
            'lastname' => [
                'type'              => 'string',
                'required'          => true,
                'description'       => "Last Name of the employee."
            ],
            'direction' => [
                'type'              => 'string',
                'description'       => "Direction of the employee."
            ],
            'salary' => [
                'type'              => 'float',
                'default'           => 1000,
                'description'       => "Gross salary of the employee."
            ],
            'email' => [
                'type'              => 'string',
                'description'       => "Email of the employee.",
                'usage'             => 'email',
            ],
            'company_id' => [
                'type'           => 'many2one',
                'foreign_object' => 'projectFlow\Company',
                'required'          => true,
            ],

            'projects_ids' => [
                'type'              => 'many2many',
                'foreign_object'    => 'projectFlow\Project',
                'foreign_field'     => 'employees_ids',
                'rel_table'         => 'projectflow_employeeproject',
                'rel_foreign_key'   => 'project_id',
                'rel_local_key'     => 'employee_id',
                'description'       => 'Project the employee is assigned to.'
            ]

        ];
    }

    public static function calcName($self) {
        $result = [];
        $self->read(['firstname', 'lastname']);
        foreach($self as $id  => $employee) {
            $result[$id] = $employee['firstname'].' '.$employee['lastname'];
        }
        return $result;
    }

}