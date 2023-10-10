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

    '001'      => array(
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


];