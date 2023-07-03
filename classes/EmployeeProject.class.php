<?php
namespace projectFlow;
use equal\orm\Model;

class EmployeeProject extends Model {

    public static function getColumns() {
        return [
            'project_id' => [
                'type'           => 'many2one',
                'foreign_object' => 'projectFlow\Project',
            ],

            'employee_id' => [
                'type'           => 'many2one',
                'foreign_object' => 'projectFlow\Employee',
            ],

            'hours' => [
                'type'           => 'float',
                'required'       => true,
            ]

        ];
    }
}