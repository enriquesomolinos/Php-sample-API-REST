<?php


namespace App\ApiBundle\Factory;


use App\UserBundle\Model\User;

/**
 * Class UserFactory
 * This class gets a User object and generate an array with the data that must be shown by the API
 * @package App\ApiBundle\Factory
 */
class UserFactory {

    /**
     * Return the user data
     * @param User $user
     * @return array
     */
    public static function getUser(User $user){
        return array('username'=>$user->getUserName(),'roles'=>$user->getRoles());
    }
} 