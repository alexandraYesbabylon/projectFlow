<?php
/*
    This file is part of the eQual framework <http://www.github.com/cedricfrancoys/equal>
    Some Rights Reserved, Cedric Francoys, 2010-2021
    Licensed under GNU GPL 3 license <http://www.gnu.org/licenses/>
*/

use projectFlow\Client;

$providers = eQual::inject(['context', 'orm', 'auth', 'access']);

$tests = [

    '001'      => array(
        'description'       =>  'Creating client',
        'return'            =>  ['integer'],
        'test'              =>  function () {

            $client = Client::create([
                    'name'        => 'test client '. rand(),
                    'direction'   => 'direction  tests',
                    'phone'       => 123456789,
                    'isactive'    => true
                ])->read('phone')->first(true);

            return ($client['phone']);
        },
        'assert'            =>  function ($phone) {
            return ($phone == 123456789);
        }
    ),

    '002'      => [
        'description'       => 'Update direction of the client',
        'return'            => ['string'],
        'test'              => function () {

            $nameClient = 'test client update ' .rand();
            $client = Client::create([
                    'name'        => $nameClient ,
                    'direction'   => 'direction test',
                    'phone'       => 123456789,
                    'isactive'    => true
                ])->first();

            if ($client){
                $clientModified = Client::id($client['id'])->update(['direction'   => 'New direction test'])->first();
            }

            return ($clientModified['direction']);
        },
        'assert'            =>  function ($direction) {
            return ($direction == 'New direction test');
        }
    ],
    '003'      => array(
        'description'       => 'Delete client',
        'return'            => ['boolean'],
        'test'              => function () {

            $nameClient = 'delete client test' .rand();
            $client = Client::create([
                    'name'        => $nameClient ,
                    'direction'   => 'direction test',
                    'phone'       => 123456789,
                    'isactive'    => true
                ])->first();

            if ($client) {
                Client::id($client['id'])->delete(true);
            }
            $isDeleted = Client::id($client['id'])->first(true);

            return (empty($isDeleted));

        },
        'expected' => true
    ),
    '004'      => array(
        'description'       => 'Search the project of the client',
        'return'            => ['integer'],
        'test'              => function () {

            $clientId = Client::search(['name' , 'like' , '%'. 'Jean Duran'. '%'])->ids();

            if ($clientId) {
                $projects = Client::search(['id', '=', $clientId])->read(['projects_ids'])->first(true);
            }

            if ($projects && isset($projects['projects_ids'])){
                $projectCount = count($projects['projects_ids']);
            }
            return $projectCount;

        },
        'expected' => 3
    ),
    '005'      => array(
        'description'       => 'Delete all clients test',
        'return'            => ['boolean'],
        'test'              => function () {

            $clients_ids = Client::search(['name' , 'like' , '%'. 'test'. '%'])->ids();

            if($clients_ids){
                Client::ids($clients_ids)->delete(true);
            }

            $isDeleted = Client::ids($clients_ids)->first(true);

            return (empty($isDeleted));
        },
        'expected' => true
    )
];