<?php
/*
    This file is part of the eQual framework <http://www.github.com/cedricfrancoys/equal>
    Some Rights Reserved, Cedric Francoys, 2010-2021
    Licensed under GNU GPL 3 license <http://www.gnu.org/licenses/>
*/

use projectFlow\Employee;
use projectFlow\Company;
use projectFlow\Project;

$providers = eQual::inject(['context', 'orm', 'auth', 'access']);

$tests = [

    '301'      => array(
        'description'       =>  'Creating employee',
        'return'            =>  ['string'],
        'test'              =>  function () {

            $company = Company::create([
                    'name'        => 'Company test',
                    'direction'   => 'direction test',
                    'phone'       => 123456789
                ])->first(true);

            if($company && isset($company['id'])){
                $employee = Employee::create([
                        'firstname'        => 'first test',
                        'lastname'         => 'last test',
                        'direction'        => 'direction client test',
                        'company_id'       => $company['id'],
                        'email'            => 'email@gmail.com'
                    ])->read('name')->first();
            }
            return ($employee['name']);
        },
        'assert'            =>  function ($name) {
                return ($name == 'first test last test');
        }
    ),
    '302'      => array(
        'description'       => 'Calculate the total the budget of the projects by the employee',
        'return'            =>  ['integer'],
        'test'              => function () {

            $employee = Employee::search(['name' , 'like' , '%'. 'Marie Grand'. '%'])
                ->read('projects_ids')
                ->first(true);

            $projects=$employee['projects_ids'];

            $budget=0;
            foreach($projects as $id => $project) {
                $project= Project::id($project['id'])->read('budget')->first(true);
                $budget += $project['budget'];

            }

            return((int)$budget);

        },
        'expected' => 62000
    ),
    '303'      => array(
        'description'       => 'Delete all employees test',
        'return'            => ['boolean'],
        'test'              => function () {

            $employees_ids = Employee::search(['name' , 'like' , '%'. 'test'. '%'])->ids();

            if($employees_ids){
                Employee::ids($employees_ids)->delete(true);
            }

            $isDeleted = Employee::ids($employees_ids)->first(true);

            return (empty($isDeleted));
        },
        'expected' => true
    )

];