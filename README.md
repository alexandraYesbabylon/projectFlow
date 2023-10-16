
[![eQual](https://img.shields.io/badge/framework-eQualFramework-brightgreen)](https://github.com/equalframework/equal)
[![Maintainer](https://img.shields.io/badge/maintainer-alexandraYesbabylon-blue)](https://github.com/alexandraYesbabylon)


# eQual Framework and ProjectFlow Application Documentation

Welcome to the documentation for the eQual Framework and the ProjectFlow application. This document will guide you through the setup and usage of the eQual Framework, as well as the specific features and components of the ProjectFlow application.

## About ProjectFlow
ProjectFlow is an application built on top of the eQual Framework. It is designed to help manage projects, companies, employees, and more. This documentation will provide insights into the key aspects of the ProjectFlow application.

Now, let's dive into the details of the eQual Framework and ProjectFlow application.

### Model Entity relational

<img src=".\assets\img\DiagramModel.drawio.png" alt="DiagramModel.drawio" style="zoom:100%;" />


## 1.- Installation
Prerequisite

ProjectFlow requires [eQual framework](https://github.com/equalframework/equal)

Clone Project

Go to `/package` and run this command.

```
$ git clone https://github.com/alexandraYesbabylon/projectFlow.git
```

Initialization package

```
$ ./equal.run --do=init_package --package=projectFlow
```

## 2.- Application structure

An application is divided in several parts, stored in a package folder located under the `/packages` directory. For this example the name package is `/projectFlow `

Each **package** is defined as follows :

```
projectFlow
├── classes
│   └── */*.class.php
├── data
│   └── *.json
├── init
│   └── data
│   	└── *.json
├── views
│   └── *.json
├── manifest.json
```

## 3.- Data Base

### Initization data

Open`/data/init`,  you can see all the information that the database will have.

The generic filename format is: `{project_name}_{class_name}.json`

Here an example of  `projectflow_Company.json`

```json
[
  {
    "name": "projectFlow\\Company",
    "lang": "en",
    "data": [
      {
        "id": 1,
        "name": "Yesbabylon",
        "direction": "Bd du Souverain 24",
        "creationdate": "2023-06-01",
        "phone": "0486152419"
      },
      {
        "id": 2,
        "name": "Company Flee",
        "direction": "rue de la reine",
        "creationdate": "2013-06-27",
        "phone": "0485963215"
      }
    ]
  }
]
```



## 3.- Configuration

### Config file

eQual expects an optional root config file in the `/config` directory.

```
{
    "DB_DBMS": "MYSQL",
    "DB_HOST": "equal_db",
    "DB_PORT": "3306",
    "DB_USER": "root",
    "DB_PASSWORD": "test",
    "DB_NAME": "equal",
    "DEFAULT_RIGHTS": "QN_R_CREATE | QN_R_READ | QN_R_DELETE | QN_R_WRITE",
    "DEBUG_MODE": "QN_MODE_PHP | QN_MODE_ORM | QN_MODE_SQL",
    "DEBUG_LEVEL": "E_ALL | E_ALL",
    "DEFAULT_PACKAGE": "core",
    "AUTH_SECRET_KEY": "my_secret_key",
    "AUTH_ACCESS_TOKEN_VALIDITY": "5d",
    "AUTH_REFRESH_TOKEN_VALIDITY": "90d",
    "AUTH_TOKEN_HTTPS": false,
    "ROOT_APP_URL": "http://equal.local"
}
```
**Initiate your package with initial data in DB**

```
$ ./equal.run --do=init_package --package=projectFlow --import=true
```
You can see the tables created in  `equal`  data base. The names tables are `{{package_name}}_{{entity}}`
You can see the all data, open the table `projectflow_client` with your prefect DBMS.

**Consistency with Database**
Performs consistency checks between DB and class as well as syntax validation for classes (PHP), views and translation files (JSON). Typing this command.

```
$ ./equal.run --do=test_package-consistency --package=projectFlow

```
## 4.- Authentication
You need to create a account typing this command

```
$ ./equal.run --do=model_create --entity=core\\User --fields[login]='project@example.com' --fields[password]='project'
```

**Note**: User must be validated to be able to connect. To validate user type this command but you need to know your id.

```
$ ./equal.run --do=model_update --entity='core\User' --ids=3 --fields='{validated:true}'
```

Add a user as member of a given group.

```
$ ./equal.run --do=group_add-user --group=users --user=3
```

Go to http://equal.local/apps/  ,login with your user and click in `Project` application.

## 5.- Model definition

Each Model is defined in a `.class.php` file , located in the `/packages/projectFlow/classes` . All classes inherit from a common ancestor: the `Model` class, declared in the `equal\orm` namespace and defined in `/lib/equal/orm/Model.class.php`.

A class is always referred to as an **entity** and belongs to a package. Packages and their subdirectories are used as ` namespaces package_name`

The generic filename format is: `{class_name}.class.php` .

### Company.class.php

The `creationdate` is the current date by default  , so use `time() `.

```php
<?php
namespace projectFlow;
use equal\orm\Model;

class Company extends Model {

    public static function getColumns() {
        return [
            'name' => [
                'type'              => 'string',
                'description'       => "Name of the company.",
                'required'          => true,
            ],
            'direction' => [
                'type'              => 'string',
                'description'       => "Direction of the company.",
            ],
            'creationdate' => [
                'type'              => 'date',
                'description'       => "Date of creation of the company.",
                'default'           => time(),
            ],
            'phone' => [
                'type'              => 'string',
                'description'       => "Phone of the company.",
                'usage'             => 'phone',
            ],
             // Each company can have employees
            'employees_ids' => [
                'type'              => 'one2many',
                'foreign_object'    => 'projectFlow\Employee',
                'foreign_field'     => 'company_id'
            ]
        ];
    }

}
```
### Client.class.php
The `name` is mandatory and unique. The `isactive` is `true` by default

### Project.class.php
The `name` is  mandatory, the `startdate` is the current date by default , the `budget` is 1000 by default , the `status` has the options `['draft', 'approved','in_progress','cancelled','finished'] `

### Employee.class.php
The `firstname` and `lastname `are mandatories. The `salary `  is 1000 by default.
The  `name` field ,it stores by the `firstname` and `lastname`, so you can find the `calcName` function  which returns the concatenation.
Also, we need to add the  `dependencies` in the `firstname` and `lastname`

### EmployeeProject.class.php
The `hours` is mandatory,

## 6.- Views

By default view for `list` and `form` types should be defined for each entity. The cand find all the view in  `views` folder of the package `projectFlow`
The generic filename format is: `{class_name}.{view_type}.{view_name}.json` .
Here an example of a `list` and a `form` of Company.

**Company.list.default.json**

```json
{
  "name": "Companies",
  "description": "All information companies.",
  "domain": [],
  "order": "creationdate",
  "layout": {
    "items": [
      {
        "type": "field",
        "value": "id",
        "width": "25%"
      },
      {
        "type": "field",
        "value": "name",
        "width": "25%"
      },
      {
        "type": "field",
        "value": "direction",
        "width": "25%"
      },
      {
        "type": "field",
        "value": "creationdate",
        "label": "Creation",
        "width": "25%"
      },
      {
        "type": "field",
        "value": "phone",
        "width": "25%"
      }
    ]
  }
}
```

**Company.form.default.json**: The section `Employees` has been added to show the employees working in each company.

### Client
**Client.list.default.json**: The result is sorted by `name` , with a `limit` of 10 par page
**Client.form.default.json**: The section `Projects`  has been added to show the projects that each client has.

### Project
**Project.list.default.json**: The result is sorted by `startdate`  and shows the total project budgets.
**Project.form.default.json**: The section `Employees` has been added to show the employees working in each project.

### Employee
**Employee.list.default.json**: The result is sorted by `lastname` and `fistname`  and shows the total employees

### EmployeeProject
**EmployeeProject.list.default.json**: The result is group by  `employee`  and shows the total hours


## 7.- Menu

See the `menu.app.left.json` file in `/views `.

```json
{
  "name": "Project menu",
  "access": {
    "groups": [
      "project.default.projectFlow"
    ]
  },
  "layout": {
    "items": [
      {
        "id": "project.project.test",
        "label": "ProjectFlow",
        "description": "",
        "icon": "menu_book",
        "type": "parent",
        "children": [
          {
            "id": "project.project.company",
            "type": "entry",
            "label": "Companies",
            "description": "",
            "context": {
              "entity": "projectFlow\\Company",
              "view": "list.default"
            }
          }
        ]
      }
    ]
  }
}
```

## 8.- Manifest

See  `manifest.json`  file `/projectFlow`

```json
{
  "name": "Project",
  "version": "1.0",
  "author": "Yesbabylon",
  "depends_on": [
    "core"
  ],
  "apps": [
    {
      "id": "project",
      "name": "Project",
      "extends": "app",
      "description": "Applitation project flow",
      "icon": "ad_units",
      "color": "#3498DB",
      "access": {
        "groups": [
          "users"
        ]
      },
      "params": {
        "menus": {
          "left": "app.left"
        }
      }
    }
  ]
}
```

## 9.- Status project

This is the status flow of the project

<img src=".\assets\img\StatusProject.drawio.png" alt="StatusProject.drawio" style="zoom:100%;" />



See the method `getWorkflow` in the class `Project.class.php` that manages the project statuses. See the actions in the `form` call   `Project.form.default.json`.
Here is the example for the actions/

```json
 "actions": [
    {
      "id": "action.draft",
      "label": "Draft",
      "description": "Draft project.",
      "controller": "core_model_transition",
      "visible": [
        "status",
        "=",
        "approved"
      ],
      "confirm": true,
      "params": {
        "entity": "projectFlow\\Project",
        "transition": "to_draft",
        "ids": []
      }
    },
 ]
```

## 10.-  Controller View List
The `controller` property specifies the controller that is requested for fetching the `Model` collection that will show in the View.

Example:

```json
"controller": "projectFlow_project-collect"
```
For controller `project-collect` a `project-collect.php` file in `/data` and a `project-collect.search.default.json` file in `view`.
For this example, the search for projects is created by different parameters , so it is by `employee`,`status`,`min budget`,`max budget`, `client_id`, `date_from` and `date_to`.

Here `project-collect.php` :
```php
<?php
/*
    This file is part of the Discope property management software.
    Author: Yesbabylon SRL, 2020-2022
    License: GNU AGPL 3 license <http://www.gnu.org/licenses/>
*/

use equal\orm\Domain;
use projectFlow\Project;

list($params, $providers) = eQual::announce([
    'description'   => 'Advanced search for Reports: returns a collection of Reports according to extra paramaters.',
    'extends'       => 'core_model_collect',
    'params'        => [
        'entity' =>  [
            'description'   => 'name',
            'type'          => 'string',
            'default'       => 'projectFlow\Project'
        ],
        'employee_id' => [
            'type'              => 'many2one',
            'foreign_object'    => 'projectFlow\Employee',
            'description'       => 'Employee of project to which the reports relate.'
        ],
        'status' => [
            'type'              => 'string',
            'selection'         => ['all','draft', 'approved','in_progress','cancelled','finished'],
            'description'       => 'Projects with a specific status.'
        ],
        'budget_min' => [
            'type'              => 'integer',
            'description'       => 'Minimal budget for the project.'
        ],
        'budget_max' => [
            'type'              => 'integer',
            'description'       => 'Maximal budget for the project.'
        ],
        'client_id' => [
            'type'              => 'many2one',
            'foreign_object'    => 'projectFlow\Client',
            'description'       => 'client of project to which the reports relate.'
        ],
        'date_from' => [
            'type'          => 'date',
            'description'   => "First date of the time interval.",
            'default'       => strtotime("-10 Years")
        ],
        'date_to' => [
            'type'          => 'date',
            'description'   => "Last date of the time interval.",
            'default'       => time()
        ]
    ],
    'response'      => [
        'content-type'  => 'application/json',
        'charset'       => 'utf-8',
        'accept-origin' => '*'
    ],
    'providers'     => [ 'context', 'orm' ]
]);
/**
 * @var \equal\php\Context $context
 * @var \equal\orm\ObjectManager $orm
 */
list($context, $orm) = [ $providers['context'], $providers['orm'] ];



//   Add conditions to the domain to consider advanced parameters
$domain = $params['domain'];

//status
if(isset($params['status']) && strlen($params['status']) > 0 && $params['status']!= 'all') {
    $domain = Domain::conditionAdd($domain, ['status', '=', $params['status']]);
}

if(isset($params['budget_min']) && $params['budget_min'] > 0) {
    $domain = Domain::conditionAdd($domain, ['budget', '>=', $params['budget_min']]);
}

if(isset($params['budget_max']) && $params['budget_max'] > 0) {
    $domain = Domain::conditionAdd($domain, ['budget', '<=', $params['budget_max']]);
}

if(isset($params['client_id']) && $params['client_id'] > 0) {
    $domain = Domain::conditionAdd($domain, ['client_id', '=', $params['client_id']]);
}

if(isset($params['date_from']) && $params['date_from'] > 0) {
    $domain = Domain::conditionAdd($domain, ['startdate', '>=', $params['date_from']]);
}

if(isset($params['date_to']) && $params['date_to'] > 0) {
    $domain = Domain::conditionAdd($domain, ['startdate', '<=', $params['date_to']]);
}

//   employee_id : filter on Project related employe
if(isset($params['employee_id']) && $params['employee_id'] > 0) {
    $projects_ids = [];
    $projects_ids = Project::search(['employees_ids', 'contains', $params['employee_id']])->ids();
    if(count($projects_ids)) {
        $domain = Domain::conditionAdd($domain, ['id', 'in', $projects_ids]);
    }
}


$params['domain'] = $domain;
$result = eQual::run('get', 'model_collect', $params, true);

$context->httpResponse()
        ->body($result)
        ->send();

```