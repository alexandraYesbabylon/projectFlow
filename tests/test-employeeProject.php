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

            $companyData = [
                'name'          => 'Company test'. rand(),
                'direction'     => 'direction test',
                'phone'         => 123456789
            ];

            $employeeData = [
                'firstname'     => 'first test' . rand(),
                'lastname'      => 'last test ' . rand(),
                'direction'     => 'direction test',
                'email'         => 'email@gmail.com'
            ];

            $clientData = [
                'name' => 'client test ' . rand(),
                'direction' => 'direction test',
                'phone' => 123456789,
                'isactive' => true
            ];
            $projectData = [
                'name' => 'project test',
                'description' => 'description test',
                'direction' => 'direction test'
            ];
            $employeeProjectData = [
                'hours' => 50
            ];

            $company =  Company::create($companyData)->first();

            if($company){
                $employeeData['company_id'] = $company['id'];
            }

            $employee = Employee::create($employeeData)->read('name')->first();

            $client = Client::create($clientData)->first();

            if($client){
                $projectData['client_id'] = $client['id'];
            }

            $project= Project::create($projectData)->first();

            if($project && $employee){
                $employeeProjectData['project_id'] = $project['id'];
                $employeeProjectData['employee_id'] = $employee['id'];
            }

            $employeeProject=EmployeeProject::create($employeeProjectData)->first();

            if($employeeProject){
                $employeeProject =  EmployeeProject::id($employeeProject['id'])->read(['id','project_id' => 'name'])->first();
            }

            return ($employeeProject['project_id']['name']);
        },
        'assert'            =>  function ($project) {
            return ($project == 'project test');
        }
    ),
    '402'      => array(
        'description'       =>  'Search the projects by employee',
        'return'            =>  ['integer'],
        'test'              =>  function () {


            $employee_id = Employee::search(['name', 'like', '%'. 'Daniel Petit' .'%' ])->ids();

            $employeeProject = EmployeeProject::search(['employee_id', '=', $employee_id])->ids();

            return (count($employeeProject));
        },
        'expected' => 3
    ),
    /* '403'      => array(
        'description'       => 'Delete all employeeProjects test',
        'return'            => ['boolean'],
        'test'              => function () {

            $projects_ids = Project::search(['name' , 'like' , '%'. 'test'. '%'])->ids();

            $employeeProjects= EmployeeProject::search(['project_id', 'in', $projects_ids])->ids();

            if($employeeProjects){
                EmployeeProject::ids($employeeProjects)->delete(true);
            }

            $isDeleted = EmployeeProject::ids($employeeProjects)->first(true);

            return (empty($isDeleted));
        },
        'expected' => true
    ) */

];