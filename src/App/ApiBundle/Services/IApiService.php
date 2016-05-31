<?php


namespace App\ApiBundle\Services;
use App\UserBundle\Model\User;


interface IApiService {
    public function getUser($username);
    public function getAllUsers();
    public function deleteUser($username);
    public function createModifyUser(User $user);
    public function getUserRoles($username);
    public function deleteUserRoles($username);
    public function modifyUserRoles($username,$roles);
} 