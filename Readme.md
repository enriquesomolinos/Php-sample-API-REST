Technical test for Backends (PHP)
===================================
This a Web Application using PHP.

This web contains 3 private pages wich users with appropiate roles can access.

Also, there is an API REST that admin users can use to create, modify or delete users and their roles.

This project is builts as a set of bundles. I created 5 bundles:

* UserBundle : contains the basic funcionality to store, read and modify a user. Also checks user authorization.
* ApiBundle : contains the implementation of the REST API. This bundle uses the UserBundle that provides user services.
* DBManagerBundle : manages the connection to the database.
* TemplateRendererBundle: this module renders html templates.
* MainBundle : main module that contains the web. This module use the UserBundle and the TemplateRendererBundle.

![Bundles representation](/doc/modules.png "Bundles representation")



How to install
---------

I publish the file composer.lock, so it's not necesary to run a "composer update", a "composer install" is recomended,
because you will download the same version of the dependencies that I have used in devepolment.

* composer update //will recreate the file composer.lock

* composer install //will install the dependencies noted in the file composer.lock

When the composer command is about to end, I run a postInstallScript to create the initial data. 
This Script creates this user set:

| Username        | Password           | Roles  |
| ------------- |:-------------:| -----:|
| test1      | pass1 | PAGE_1 |
| test2      | pass2 | PAGE_2 |
| test3      | pass3 | PAGE_3 |
| admin      | adminpass | ADMIN |

Php.ini configuration
---------

As this project uses SQLite3 to store users, you need to enable the extension php_sqlite3 in your php.ini file.

Also, you need to enable the php_curl extension in your php.ini file because the REST API test uses curl command to
perform the requests.

Running the web application
---------

In order to start the embedded server you must run this command:

* php -S localhost:8000 app.php

And now, you can navigate to this pages using your browser:

* http://localhost:8000/index  ->this is an introductory page
* http://localhost:8000/page1  ->shows page1. Only users with role "PAGE_1" can see it.
* http://localhost:8000/page2  ->shows page1. Only users with role "PAGE_2" can see it.
* http://localhost:8000/page3  ->shows page1. Only users with role "PAGE_3" can see it.


Using the REST API
---------

The REST API provides a few services:


| Type        | Entry point           | Parameters  | Payload sample|Description | Role
| ------------- |:-------------| -----:|:-----:|-----:|-----:|
| GET      | api/v1/user | N/A | N/A | Returns al users | All
| GET      | api/v1/user/{username:\w+} | The username | N/A | Return an especific user | All
| DELETE      | api/v1/user/{username:\w+} | The username | N/A | Deletes the user | ADMIN
| POST      | api/v1/user | N/A | { "username": "testUser",  "password":"pass",  "roles": ["ADMIN"]}| Creates or modify an user | ADMIN
| GET      | api/v1/user/rol/{username:\w+} | The username| N/A | Returns the user roles | ALL
| POST      | api/v1/user/rol/{username:\w+} | The username | {"roles": ["ADMIN"]} | Replace the user roles | ADMIN
| DELETE      | api/v1/user/rol/{username:\w+} | The username | N/A | Erase the user roles | ADMIN

* Note that all request needs to have a valid headers, especifically the Accept header. All request with Accept header diferent than "application/json" return a http 415 error.
* Api authentication use HTTP basic authentication, so, if you put incorrect user credentials you will get a 401 http error.
* When requesting the REST API, the user you use need to have a vlaid role for the operation you want, otherwise you will get a 403 http error.

Running test
---------

If you want to run the tests you must run the next command from the project root:

*  "bin/phpunit" --bootstrap vendor/autoload.php



Future enchancements
---------

* Externalize to a config file things like Session timeout.
* Crypt the user password using the mcrypts functions that provides PHP
* Make the 5 bundles as a composer project, so, the main project only contains a composer.json with 2 dependencies (ApiBundle, UserBundle).
The ApiBundle and the UserBundle also will contains other dependencies.
