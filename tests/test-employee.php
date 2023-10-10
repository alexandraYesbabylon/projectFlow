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

    '301'      => array(
        'description'       =>  'Creating employee',
        'return'            =>  ['string'],
        'test'              =>  function () {

            $company = Company::create([
                    'name'        => "Company client",
                    'direction'   => "direction test",
                    'phone'       => 123456789
                ])->first();

            $employee = Employee::create([
                    'firstname'        => "first",
                    'lastname'         => "last",
                    'direction'        => "direction client",
                    'company_id'       => $company['id'],
                    'email'            => "email@gmail.com"
                ])->first();

            $employee = Employee::id($employee['id'])->read(['name'])->first();

            return ($employee['name']);
        },
        'assert'            =>  function ($name) {
            return ($name == 'first last');
        }
    )

];