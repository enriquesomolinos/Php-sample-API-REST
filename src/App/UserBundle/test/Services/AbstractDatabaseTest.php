<?php

namespace App\UserBundle\test\Services;
use App\DBManagerBundle\Connection\Connection;


abstract class AbstractDatabaseTest  extends \PHPUnit_Framework_TestCase{

    public static function setUpBeforeClass()
    {
        $dbhandle =Connection::getInstance(true)->getConnection();

        $dbhandle->exec('CREATE TABLE IF NOT EXISTS user (username VARCHAR PRIMARY KEY, password VARCHAR, roles TEXT);');

        $dbhandle->exec('DELETE  FROM  user');
        $count = $dbhandle->querySingle('SELECT COUNT(*) FROM  user where username like \'test%\' ');
        if($count>0){

        }else{
            $statement = $dbhandle->prepare("INSERT INTO user (username,password,roles) VALUES (:username,:password,:roles)");

            $statement->bindParam(':username',$username);
            $statement->bindParam(':password',$password);
            $statement->bindParam(':roles',$roles);
            $username = 'test1';
            $password = 'pass1';
            $roles =serialize(array('TEST_ROLE_1'));
            $statement->execute();

            $username = 'test2';
            $password = 'pass2';
            $roles =serialize(array('TEST_ROLE_2'));
            $statement->execute();

            $username = 'test3';
            $password = 'pass3';
            $roles =serialize(array('TEST_ROLE_3'));
            $statement->execute();



        }
        $count = $dbhandle->querySingle('SELECT COUNT(*) FROM  user where username like \'admin%\' ');
        if($count>0){

        }else{
            $statement = $dbhandle->prepare("INSERT INTO user (username,password,roles) VALUES (:username,:password,:roles)");

            $statement->bindParam(':username',$username);
            $statement->bindParam(':password',$password);
            $statement->bindParam(':roles',$roles);

            $username = 'admin';
            $password = 'adminpass';
            $roles =serialize(array('TEST_ROLE_ADMIN'));
            $statement->execute();
        }
    }
} 