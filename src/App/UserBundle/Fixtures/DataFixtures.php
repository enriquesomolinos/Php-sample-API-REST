<?php


namespace App\UserBundle\Fixtures;



use App\DBManagerBundle\Connection\Connection;
use App\MainBundle\Controller\MainController;
use App\UserBundle\Services\AccessDecisionManager;
/**
 * Class DataFixtures
 * Create the initial data
 * @package App\UserBundle\Fixtures
 */
class DataFixtures {

    /**
     * Create the four initial users and de database in case that don't exist.
     */
    public static function createData(){

        $dbhandle = Connection::getInstance()->getConnection();


        if ($dbhandle == false)
        {
            die ('Unable to open database');
        } else {

            $dbhandle->exec('CREATE TABLE IF NOT EXISTS user (username VARCHAR PRIMARY KEY, password VARCHAR, roles TEXT);');
            print("User table created created.\n");

            $count = $dbhandle->querySingle('SELECT COUNT(*) FROM  user where username like \'test%\' ');
            if($count>0){
                print("Table has users, don't insert anyone.\n");
            }else{
                $statement = $dbhandle->prepare("INSERT INTO user (username,password,roles) VALUES (:username,:password,:roles)");

                $statement->bindParam(':username',$username);
                $statement->bindParam(':password',$password);
                $statement->bindParam(':roles',$roles);

                $username = 'test1';
                $password = 'pass1';
                $roles =serialize(array(AccessDecisionManager::ROLE_PAGE_1));
                $statement->execute();

                $username = 'test2';
                $password = 'pass2';
                $roles =serialize(array(AccessDecisionManager::ROLE_PAGE_2));
                $statement->execute();

                $username = 'test3';
                $password = 'pass3';
                $roles =serialize(array(AccessDecisionManager::ROLE_PAGE_3));
                $statement->execute();

                print("User test1 with password pass1 created.\n");
                print("User test2 with password pass2 created.\n");
                print("User test3 with password pass3 created.\n");
            }

            $count = $dbhandle->querySingle('SELECT COUNT(*) FROM  user where username like \'admin%\' ');
            if($count>0){
                print("Table has admin users, don't insert anyone.\n");
            }else{
                $statement = $dbhandle->prepare("INSERT INTO user (username,password,roles) VALUES (:username,:password,:roles)");

                $statement->bindParam(':username',$username);
                $statement->bindParam(':password',$password);
                $statement->bindParam(':roles',$roles);

                $username = 'admin';
                $password = 'adminpass';
                $roles =serialize(array('ADMIN'));
                $statement->execute();

                print("User admin with password adminpass created.\n");
            }
        }
    }
} 