<?php
/*
    This file is part of the eQual framework <http://www.github.com/cedricfrancoys/equal>
    Some Rights Reserved, Cedric Francoys, 2010-2021
    Licensed under GNU GPL 3 license <http://www.gnu.org/licenses/>
*/

use projectFlow\Employee;
use projectFlow\Company;

$providers = eQual::inject(['context', 'orm', 'auth', 'access']);

$tests = [

    '201'      => array(
        'description'       =>  'Creating company',
        'return'            =>  ['string'],
        'test'              =>  function () {

            $company = Company::create([
                'name'        => "Company test",
                'direction'   => "direction test",
                'phone'       => 123456789
                ])
                ->read(['id','name'])
                ->first(true);

            return ($company['name']);
        },
        'assert'            =>  function ($name) {
            return ($name == 'Company test');
        }
    ),
    '202'      => array(
        'description'       =>  'Assigning employees to the company  ',
        'return'            =>  ['integer'],
        'test'              =>  function () {

            $company = Company::create([
                'name'        => "Company test",
                'direction'   => "direction test",
                'phone'       => 123456789
                ])
                ->read(['id','name'])
                ->first(true);

            for($i= 1; $i<=5 ; $i++) {
                Employee::create([
                    'firstname'        => "first",
                    'lastname'         => "last",
                    'direction'        => "direction client" . $i,
                    'company_id'       => $company['id'],
                    'email'            => $i."email@gmail.com"
                ])->read(['name'])->first();
            }

            $employees = Company::search(['id', "=", $company['id']])->read(['employees_ids'])->first();

            return (count($employees['employees_ids']));
        },
        'expected'=> 5
    )

];