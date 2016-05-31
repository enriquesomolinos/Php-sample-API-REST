<?php


namespace App\ApiBundle\Services;

use App\ApiBundle\Factory\RoleFactory;
use App\UserBundle\Services\UserService;
use App\ApiBundle\Factory\UserFactory;
use App\UserBundle\Model\User;

/**
 * Class ApiService
 * This service contain all method exposed by the REST API
 * @package App\ApiBundle\Services
 */
class ApiService implements IApiService{

    /**
     * User Service. Belongs to the UserBundle
     * @var \App\UserBundle\Services\UserService
     */
    private $userService;

    /**
     * Class Constructor
     */
    public function __construct(){
        $this->userService = new UserService();
    }

    /**
     * Return the user determined by the $username
     * @param $username
     * @return array
     */
    public function getUser($username){
        $user =$this->userService->getUser($username);

        return UserFactory::getUser($user);
    }

    /**
     * Returns all users
     * @return array
     */
    public function getAllUsers(){
        $users =$this->userService->getAllUsers();
        $result = array();
        foreach($users as $user){
            $result[] =UserFactory::getUser($user);
        }
        return $result;
    }

    /**
     * Deletes a user by his username
     * @param $username
     */
    public function deleteUser($username){
        $this->userService->deleteUser($username);
    }

    /**
     * Create or modify the user
     * @param User $user
     */
    public function createModifyUser(User $user){
        $this->userService->createModifyUser($user);
    }

    /**
     * Returns the user roles
     * @param $username
     * @return array
     */
    public function getUserRoles($username){
        $roles = $this->userService->getUserRoles($username);
        return RoleFactory::getRoles($roles);
    }

    /**
     * Erase the user roles
     * @param $username
     */
    public function deleteUserRoles($username){
        $this->userService->deleteUserRoles($username);
    }

    /**
     * Create or modify the user roles
     * @param $username
     * @param $roles
     */
    public function modifyUserRoles($username,$roles){
        $this->userService->modifyUserRoles($username,$roles);
    }



} 