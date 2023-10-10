<?php
/*
    This file is part of the eQual framework <http://www.github.com/cedricfrancoys/equal>
    Some Rights Reserved, Cedric Francoys, 2010-2021
    Licensed under GNU GPL 3 license <http://www.gnu.org/licenses/>
*/
use projectFlow\Employee;
use projectFlow\Company;
use projectFlow\Client;
use projectFlow\EmployeeProject;
use projectFlow\Project;

$providers = eQual::inject(['context', 'orm', 'auth', 'access']);

$tests = [

    '401'      => array(
        'description'       =>  'Create Employee Project',
        'return'            =>  ['string'],
        'test'              =>  function () {

            $company = Company::create([
                    'name'        => "Company test Employee Project",
                    'direction'   => "direction test",
                    'phone'       => 123456789
                ])->first();

            $employee = Employee::create([
                    'firstname'        => "Juan",
                    'lastname'         => "Pierre " .rand(),
                    'direction'        => "direction client",
                    'company_id'       => $company['id'],
                    'email'            => "email@gmail.com"
                ])->first();

            $client = Client::create([
                    'name'        => "client project ". rand(),
                    'direction'   => "direction  client",
                    'phone'       => 123456789,
                    'isactive'    => true
                ])->first();

            $project = Project::create([
                    'name'             => "name test Employee Project",
                    'description'      => "description project",
                    'direction'        => "direction client",
                    'client_id'        => $client['id']
                ])->first();

            $employeeProject = EmployeeProject::create([
                    'project_id'       => $project['id'],
                    'employee_id'      => $employee['id'],
                    'hours'            => 50
                ])
                ->read(['project_id' => 'name', 'employee_id' => "name", 'hours'])
                ->first(true);

            return ($employeeProject['project_id']['name']);
        },
        'assert'            =>  function ($project) {
            return ($project == 'name test Employee Project');
        }
    ),
    '402'      => array(
        'description'       =>  'Search the projects by employee',
        'return'            =>  ['integer'],
        'test'              =>  function () {


            $employee = Employee::search(["name", "like", "%". "Daniel Petit" ."%" ])->read('name')->first();

            $employeeProject = EmployeeProject::search(['employee_id', '=', $employee['id']])->ids();

            return (count($employeeProject));
        },
        'expected'=> 3
    ),
];