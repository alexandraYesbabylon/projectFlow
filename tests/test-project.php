<?php
/*
    This file is part of the eQual framework <http://www.github.com/cedricfrancoys/equal>
    Some Rights Reserved, Cedric Francoys, 2010-2021
    Licensed under GNU GPL 3 license <http://www.gnu.org/licenses/>
*/

use projectFlow\Employee;
use projectFlow\Client;
use projectFlow\Project;
use projectFlow\Company;
use SebastianBergmann\Type\TrueType;

$providers = eQual::inject(['context', 'orm', 'auth', 'access']);

$tests = [

    '501'      => array(
        'description'       =>  'Create project',
        'return'            =>  ['string'],
        'test'              =>  function () {

            $client = Client::create([
                    'name'        => 'client test '. rand(),
                    'direction'   => 'direction test',
                    'phone'       => 123456789,
                    'isactive'    => true
                ])->first();

            $project = Project::create([
                    'name'             => 'project test',
                    'description'      => 'description test',
                    'direction'        => 'direction test',
                    'client_id'        => $client['id']
                ])->first();

            $project = Project::id($project['id'])->read(['status'])->first();

            return ($project['status']);
        },
        'assert'            =>  function ($status) {
            return ($status == 'draft');
        }
    ),
    '502'      => array(
        'description'       =>  'Create five projects for a client',
        'return'            =>  ['integer'],
        'test'              =>  function () {

            $client = Client::create([
                'name'        => 'client test',
                'direction'   => 'direction  test',
                'phone'       => 123456789,
                'isactive'    => true

                ])->first();

            $num_projects =  ($client)?  5 : 0;

            for($i = 1; $i <= $num_projects ; $i++) {
                Project::create([
                        'name'             => 'project test ' . $i,
                        'description'      => 'description test',
                        'direction'        => 'direction test',
                        'client_id'        => $client['id']
                ]);
            }

            $projects = Project::search(['client_id', '=', $client['id']])->ids();

            return (count($projects));
        },
        'expected'=> 5
    ),
    '503'      => array(
        'description'       =>  'Search the projects by client',
        'return'            =>  ['string'],
        'test'              =>  function () {

            $client_id = Client::search(['name', 'like', '%'. 'Pierre Lopez' .'%' ])->ids();

            if($client_id){
                $projects = Project::search(['client_id', '=', $client_id])->read('name')->first(true);
            }

            return ($projects['name']);
        },
        'assert'            =>  function ($project) {
            return ($project == 'flight reservations');
        }
    ),

    '504'      => array(
        'description'       =>  'Search the projects that works the employees of the the company',
        'return'            =>  ['integer'],
        'test'              =>  function () {


            $company_id = Company::search(['name', 'like', '%'. 'Company Flee' .'%' ])->ids();
            if($company_id){
                $employees = Employee::search(['company_id' , '=' , $company_id])->ids();
            }
            if($employees){
                $projects = Project::search(['employees_ids', 'contains', $employees])->ids();
            }

            return (count($projects));
        },
        'expected' => 3
    ),

    '505'      => array(
        'description'       =>  'Search the project by a budget range',
        'return'            =>  ['string'],
        'test'              =>  function () {
            $budget_min = 1000;
            $budget_max = 2000;

            $projects = Project::search([
                ['budget', '>=', $budget_min],
                ['budget', '<=', $budget_max]
            ])->read('name')->first(true);

            return ($projects['name']);
        },
        'assert'            =>  function ($project) {
            return ($project == 'client management');
        }
    ),
    '506'      => array(
        'description'       => 'Delete all projects test',
        'return'            => ['boolean'],
        'test'              => function () {

            $projects_ids = Project::search(['name' , 'like' , '%'. 'test'. '%'])->ids();

            if($projects_ids){
                Project::ids($projects_ids)->delete(true);
            }

            $isDeleted = Project::ids($projects_ids)->first(true);

            return (empty($isDeleted));
        },
        'expected' => true
    )


];