<?php


namespace App\ApiBundle\test;

use App\ApiBundle\Controller\ApiController;
use App\ApiBundle\Services\ApiService;
use App\DBManagerBundle\Connection\Connection;
use App\UserBundle\Repository\UserRepository;
use App\UserBundle\Services\UserService;
use App\UserBundle\test\Services\AbstractDatabaseTest;
use App\UserBundle\Exception\NoDataFoundException;


class APITest extends  AbstractDatabaseTest{

    public function testGetAllUsers(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/'.ApiController::API_VERSION.'/user');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_USERPWD, 'admin:adminpass');
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $this->assertTrue(sizeof(json_decode($response))>0);
        $this->assertTrue($http_code==200);

    }

    public function testGetAllUsersIncorrectUser(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/'.ApiController::API_VERSION.'/user');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: application/json'));

        curl_setopt($ch, CURLOPT_USERPWD, 'admin2:adminpass2');
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $this->assertTrue($http_code==401);

    }
    public function testGetAllUsersNoHeader(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/'.ApiController::API_VERSION.'/user');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


        curl_setopt($ch, CURLOPT_USERPWD, 'admin:adminpass');
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $this->assertTrue($http_code==415);

    }


    public function testGetUser(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/'.ApiController::API_VERSION.'/user/admin');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_USERPWD, 'admin:adminpass');
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->assertTrue(sizeof(json_decode($response))>0);
        $this->assertTrue($http_code==200);

    }

    public function testGetUserNotExist(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/'.ApiController::API_VERSION.'/user/adminNotFound');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_USERPWD, 'admin:adminpass');
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $this->assertTrue($http_code==404);

    }


    public function testCreateUser(){
        $ch = curl_init();
        $data= '{ "username":"demoUser","password":"newpass","roles":["ADMIN","PAGE_1"]}';
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/'.ApiController::API_VERSION.'/user');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_USERPWD, 'admin:adminpass');
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $this->assertTrue($http_code==200);



    }

    public function testCreateUserNoRole(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/'.ApiController::API_VERSION.'/user');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        $data = '{ "username":"demoUser","password":"newpass","roles":["ADMIN","PAGE_1"]}';
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_USERPWD, 'test2:pass2');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))

        );
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->assertTrue($http_code==403);

    }

    public function testModifyUser(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/'.ApiController::API_VERSION.'/user');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{ "username":"demoUser","password":"anewPass","roles":["ADMIN","PAGE_1"]}');
        curl_setopt($ch, CURLOPT_USERPWD, 'admin:adminpass');
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->assertTrue($http_code==200);

    }


    public function testGetUserRoles(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/'.ApiController::API_VERSION.'/user/rol/admin');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_USERPWD, 'admin:adminpass');
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->assertTrue(sizeof(json_decode($response))>0);

        $this->assertTrue($http_code==200);
        $userService = new UserService();

        $user =$userService->getUser('test1');
        $this->assertTrue(sizeof($user->getRoles())>0);

    }

    public function testDeleteUserRole(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/'.ApiController::API_VERSION.'/user/rol/demoUser');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_USERPWD, 'admin:adminpass');
        $response = curl_exec($ch);


        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->assertTrue($http_code==200);

    }

    public function testModifyUserRole(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/'.ApiController::API_VERSION.'/user/rol/demoUser');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"roles" : ["ADMIN"]}');
        curl_setopt($ch, CURLOPT_USERPWD, 'admin:adminpass');
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->assertTrue($http_code==200);

    }

     public function testDeleteUser(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/'.ApiController::API_VERSION.'/user/demoUser');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_USERPWD, 'admin:adminpass');
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->assertTrue($http_code==200);

    }



} 