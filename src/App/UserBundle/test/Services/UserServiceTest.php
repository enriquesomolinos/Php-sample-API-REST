<?php


namespace App\UserBundle\test\Services;


use App\UserBundle\Services\UserService;
use App\UserBundle\Repository\UserRepository;
use App\DBManagerBundle\Connection\Connection;
use App\UserBundle\Exception\NoDataFoundException;
use App\UserBundle\Model\User;

class UserServiceTest extends AbstractDatabaseTest{
    public function testGetUser(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $userService = new UserService($userRepository);
        $user = $userService->getUser('test2');
        $this->assertNotNull($user);
    }

    public function testGetUserError(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $userService = new UserService($userRepository);

        $this->setExpectedException(NoDataFoundException::class);
        $user = $userService->getUser('usernotexists');
    }


    public function testGetAllUsers(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $userService = new UserService($userRepository);
        $users =$userService->getAllUsers();
        $this->assertTrue(sizeof($users)==4);

    }


    public function testUserRoles(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $userService = new UserService($userRepository);
        $roles = $userService->getUserRoles('test2');
        $this->assertTrue(sizeof($roles)==1);
    }

    public function testUserRolesError(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $userService = new UserService($userRepository);
        $this->setExpectedException(NoDataFoundException::class);
        $roles = $userService->getUserRoles('testError');
    }

    public function testCreateUser(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $userService = new UserService($userRepository);
        $userService->createModifyUser(new User('createduser','passuser',array('ADMIN')));
        $user =$userRepository->findOneBy(array('username'=>'createduser'));
        $this->assertTrue(sizeof($user)==1);
        $this->assertTrue($user->getUserName()=='createduser');
        $this->assertTrue($user->getPassword()=='passuser');
        $this->assertTrue(sizeof($user->getRoles())==1);
    }

    public function testModifyUser(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $userService = new UserService($userRepository);
        $user =$userRepository->findOneBy(array('username'=>'test2'));
        $userService->createModifyUser(new User('test2','passchanged',array('ADMIN')));
        $userModified =$userRepository->findOneBy(array('username'=>'test2'));
        $this->assertTrue(sizeof($userModified)==1);
        $this->assertTrue($user->getUserName()==$userModified->getUserName());
        $this->assertTrue($user->getPassword()!=$userModified->getUserName());
    }

    public function testDeleteuser(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $userService = new UserService($userRepository);
        $userService->deleteUser('createduser');
        $this->setExpectedException(NoDataFoundException::class);
        $user =$userRepository->findOneBy(array('username'=>'createduser'));
    }
    public function testDeleteUserInvalid(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $userService = new UserService($userRepository);
        $this->setExpectedException(NoDataFoundException::class);
        $userService->deleteUser('createduserInvalid');

    }

    public function testDeleteUserRolesError(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $userService = new UserService($userRepository);
        $this->setExpectedException(NoDataFoundException::class);
        $userService->deleteUserRoles('createduserInvalid');
    }
    public function testDeleteUserRoles(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $userService = new UserService($userRepository);
        $userService->deleteUserRoles('test2');
        $user =$userRepository->findOneBy(array('username'=>'test2'));
        $this->assertTrue(sizeof($user->getRoles())==0);
    }

    public function testModifyUserRolesError(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $userService = new UserService($userRepository);
        $this->setExpectedException(NoDataFoundException::class);
        $userService->modifyUserRoles('createduserInvalid',array());
    }

    public function testModifyUserRoles(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $userService = new UserService($userRepository);
        $user =$userRepository->findOneBy(array('username'=>'test2'));

        $userService->modifyUserRoles('test2',array('ADMIN','DEMO_ROLE'));
        $userModified =$userRepository->findOneBy(array('username'=>'test2'));
        $this->assertTrue(sizeof($user->getRoles())!=sizeof($userModified->getRoles()));
    }


} 