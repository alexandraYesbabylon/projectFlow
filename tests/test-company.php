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

    '101'      => array(
        'description'       =>  'Creating company',
        'return'            =>  ['string'],
        'test'              =>  function () {

            $company = Company::create([
                'name'        => 'Company test',
                'direction'   => 'direction test',
                'phone'       => 123456789
                ])
                ->read('name')
                ->first(true);

            return ($company['name']);
        },
        'assert'            =>  function ($name) {
            return ($name == 'Company test');
        }
    ),
    '102'      => array(
        'description'       =>  'Assigning employees to the company  ',
        'return'            =>  ['integer'],
        'test'              =>  function () {

            $company = Company::create([
                'name'        => 'Company test',
                'direction'   => 'direction test',
                'phone'       => 123456789
                ])->first();

            $num_employees =  ($company)?  5 : 0;

            for($i = 1; $i <= $num_employees ; $i++) {
                Employee::create([
                    'firstname'        => 'first '. $i,
                    'lastname'         => 'last '. $i,
                    'direction'        => 'direction client' . $i,
                    'company_id'       => $company['id'],
                    'email'            => $i.'email@gmail.com'
                ])->read('name');
            }

            $employees = Company::id($company['id'])->read(['employees_ids'])->first(true);

            return (count($employees['employees_ids']));
        },
        'expected'=> 5
    ),
    '103'      => array(
        'description'       => 'Delete all companies test',
        'return'            => ['boolean'],
        'test'              => function () {

            $companies_ids = Company::search(['name' , 'like' , '%'. 'test'. '%'])->ids();

            if($companies_ids){
                Company::ids($companies_ids)->delete(true);
            }

            $isDeleted = Company::ids($companies_ids)->first(true);

            return (empty($isDeleted));
        },
        'expected' => true
    )
];