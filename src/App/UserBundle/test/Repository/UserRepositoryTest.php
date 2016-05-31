<?php


namespace App\UserBundle\test\Repository;


use App\DBManagerBundle\Connection\Connection;
use App\UserBundle\Exception\NoDataFoundException;
use App\UserBundle\Repository\UserRepository;
use App\UserBundle\Model\User;
use App\UserBundle\test\Services\AbstractDatabaseTest;

class UserRepositoryTest  extends AbstractDatabaseTest{

    public function testFindAll(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $users =$userRepository->findAll();
        $this->assertTrue(sizeof($users)==4);
    }



    public function testFindOneBy(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $user =$userRepository->findOneBy(array('username'=>'test2'));
        $this->assertNotNull($user);
        $this->assertTrue($user->getPassword()=='pass2');

    }

    public function testFindOneByExceptions(){
        $this->setExpectedException(NoDataFoundException::class);
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $user =$userRepository->findOneBy(array('username'=>'testError'));
    }

    public function testFindBy(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $user =$userRepository->findBy(array('username'=>'test2'));
        $this->assertNotNull($user);
        $this->assertTrue(sizeof($user)==1);
    }

    public function testFindByException(){
        $this->setExpectedException(NoDataFoundException::class);
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $user =$userRepository->findBy(array('username'=>'test2222'));

    }


    public function testCreateUser(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $userRepository->createModifyUser(new User('createduser','passuser',array('ADMIN')));
        $user =$userRepository->findOneBy(array('username'=>'createduser'));
        $this->assertTrue(sizeof($user)==1);
        $this->assertTrue($user->getUserName()=='createduser');
        $this->assertTrue($user->getPassword()=='passuser');
        $this->assertTrue(sizeof($user->getRoles())==1);
    }

    public function testModifyUser(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $user =$userRepository->findOneBy(array('username'=>'test2'));
        $userRepository->createModifyUser(new User('test2','passchanged',array('ADMIN')));
        $userModified =$userRepository->findOneBy(array('username'=>'test2'));
        $this->assertTrue(sizeof($userModified)==1);
        $this->assertTrue($user->getUserName()==$userModified->getUserName());
        $this->assertTrue($user->getPassword()!=$userModified->getUserName());
    }
    public function testDeleteuser(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $userRepository->delete('createduser');
        $this->setExpectedException(NoDataFoundException::class);
        $user =$userRepository->findOneBy(array('username'=>'createduser'));
    }
    public function testDeleteUserInvalid(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $this->setExpectedException(NoDataFoundException::class);
        $userRepository->delete('createduserInvalid');

    }


    public function testDeleteUserRolesError(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $this->setExpectedException(NoDataFoundException::class);
        $userRepository->deleteUserRoles('createduserInvalid');
    }
    public function testDeleteUserRoles(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());

        $userRepository->deleteUserRoles('test2');
        $user =$userRepository->findOneBy(array('username'=>'test2'));
        $this->assertTrue(sizeof($user->getRoles())==0);
    }

    public function testModifyUserRolesError(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $this->setExpectedException(NoDataFoundException::class);
        $userRepository->modifyUserRoles('createduserInvalid',array());
    }

    public function testModifyUserRoles(){
        $userRepository = new UserRepository(Connection::getInstance(true)->getConnection());
        $user =$userRepository->findOneBy(array('username'=>'test2'));

        $userRepository->modifyUserRoles('test2',array('ADMIN','DEMO_ROLE'));
        $userModified =$userRepository->findOneBy(array('username'=>'test2'));
        $this->assertTrue(sizeof($user->getRoles())!=sizeof($userModified->getRoles()));
    }

}