<?php
/*
    This file is part of the eQual framework <http://www.github.com/cedricfrancoys/equal>
    Some Rights Reserved, Cedric Francoys, 2010-2021
    Licensed under GNU GPL 3 license <http://www.gnu.org/licenses/>
*/

use projectFlow\Employee;
use projectFlow\Client;
use projectFlow\Project;

$providers = eQual::inject(['context', 'orm', 'auth', 'access']);

$tests = [

    '501'      => array(
        'description'       =>  'Create project',
        'return'            =>  ['string'],
        'test'              =>  function () {

            $client = Client::create([
                'name'        => "client project ". rand(),
                'direction'   => "direction  client",
                'phone'       => 123456789,
                'isactive'    => true
            ])->first();


            $project = Project::create([
                    'name'             => "name test project",
                    'description'      => "description project",
                    'direction'        => "direction client",
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
        'description'       =>  'Search the projects by client',
        'return'            =>  ['integer'],
        'test'              =>  function () {

            $client = Client::create([
                'name'        => "client project ". rand(),
                'direction'   => "direction  client",
                'phone'       => 123456789,
                'isactive'    => true
            ])->first();

            for($i= 1; $i<=5 ; $i++) {
                Project::create([
                        'name'             => "name test project " . $i,
                        'description'      => "description project",
                        'direction'        => "direction client",
                        'client_id'        => $client['id']
                    ])->first();
            }

            $projects = Project::search(['client_id', "=", $client['id']])
                ->read(['name','client_id']);

            $countProject=0;
            foreach($projects as $project) {
                    if($project['client_id'] == $client['id']) $countProject++;
            }

            return ($countProject);
        },
        'expected'=> 5
    ),


];