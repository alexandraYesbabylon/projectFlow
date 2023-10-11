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
        'test'               =>  function () {

            $client = Client::create([
                    'name'        => "create client". rand(),
                    'direction'   => "direction  tests",
                    'phone'       => 123456789,
                    'isactive'    => true
                ])->first();

            $client = Client::id($client['id'])->read(['phone'])->first();

            return ($client['phone']);
        },
        'assert'            =>  function ($phone) {
            return ($phone == 123456789);
        }
    ),


    '003'      => [
        'description'       => 'Update direction of the client',
        'return'            => ['string'],
        'test'               => function () {

            $nameClient = "test update client" .rand();
            $client = Client::create([
                    'name'        => $nameClient ,
                    'direction'   => "direction test",
                    'phone'       => 123456789,
                    'isactive'    => true
                ])->update(['direction'   => "New direction test"])->first();

            $client = Client::id($client['id'])->read(['direction'])->first(true);
            return ($client['direction']);
        },
        'expected'=>"New direction test",
        'assert'            =>  function ($direction) {
            return ($direction == "New direction test");
        }
    ],

    '004'      => [
        'description'       => 'Archive client',
        'return'            => ['boolean'],
        'test'               => function () {

            $nameClient = "test archive client" .rand();
            $client = Client::create([
                    'name'        => $nameClient ,
                    'direction'   => "direction test",
                    'phone'       => 123456789,
                    'isactive'    => true,
                ])->update(['deleted'   => true])->first(true);

            $client = Client::id($client['id'])->read(['id','created','deleted','phone','isactive'])->first(true);
            return ($client['deleted']);
        },
        'expected'=>true
    ],

    '005'      => array(
        'description'       => 'Delete client',
        'return'            => ['boolean'],
        'test'              => function () {

            $nameClient = "delete client" .rand();
            $client = Client::create([
                    'name'        => $nameClient ,
                    'direction'   => "direction test",
                    'phone'       => 123456789,
                    'isactive'    => true
                ])->first();

            Client::ids($client['id'])->delete(true);

            $client = Client::id($client['id'])->read(['id'])->first(true);
            $result= (!$client) ? true : false;

            return ($result);

        },
        'expected'=>true
    ),

    '006'      => array(
        'description'       => 'Search the project of the client',
        'return'            => ['integer'],
        'test'              => function () {

            $client = Client::search(["name" , "like" , "%". 'Jean Duran'. "%"])->read(["id", "name"])->first(true);

            $projects= Client::search(['id', '=', $client['id']])->read(['projects_ids'])->first(true);

            return (count($projects['projects_ids']));

        },
        'expected'=>3
    )
];