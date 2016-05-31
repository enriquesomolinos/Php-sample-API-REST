<?php


namespace App\UserBundle\Services;

use App\DBManagerBundle\Connection\Connection;
use App\UserBundle\Model\User;
use App\UserBundle\Repository\UserRepository;

/**
 * Class UserService
 * This service contains user operations
 * @package App\UserBundle\Services
 */
class UserService implements IUserService {

    /**
     * The user repository
     * @var \App\UserBundle\Repository\UserRepository
     */
    private $userRepo;

    /**
     * Service constructor
     */
    public function __construct(UserRepository $userRepository=null){
        $this->userRepo = $userRepository;
        if($userRepository==null){
            $this->userRepo = new UserRepository(Connection::getInstance()->getConnection());
        }
    }

    /**
     * With a username returns a valid user.
     * @param $username
     * @return User
     */
    public function getUser($username){

        $user =$this->userRepo->findOneBy(array('username'=>$username));

        return $user;
    }

    /**
     * Returns all users in the system
     * @return array
     */
    public function getAllUsers(){
        $users = $this->userRepo->findAll();

        return $users;
    }

    /**
     * Deletes the user with usernam $username
     * @param $username
     */
    public function deleteUser($username){
        $this->userRepo->delete($username);

    }

    /**
     * If the user doesn't exists, create it.
     * If the user exists, modify his password and roles.
     * @param User $user
     */
    public function createModifyUser(User $user){
        $this->userRepo->createModifyUser($user);
    }

    /**
     * Returns de roles of the user determined by the $username
     * @param $username
     * @return array
     */
    public function getUserRoles($username){
        $user = $this->userRepo->findOneBy(array('username'=>$username));
        return $user->getRoles();
    }

    /**
     * Deletes the roles of the user determined by the $username
     * @param $username
     */
    public function deleteUserRoles($username){
        $this->userRepo->deleteUserRoles($username);
    }

    /**
     * Replaces the user roles with $roles
     * @param $username
     * @param $roles
     */
    public function modifyUserRoles($username,$roles){
        $this->userRepo->modifyUserRoles($username,$roles);
    }
} 