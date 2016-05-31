<?php


namespace App\UserBundle\Services;

use App\UserBundle\Model\User;

interface IUserService {

    public function getUser($username);
    public function getAllUsers();
    public function deleteUser($username);
    public function createModifyUser(User $user);
    public function getUserRoles($username);
    public function deleteUserRoles($username);
    public function modifyUserRoles($username,$roles);
} 