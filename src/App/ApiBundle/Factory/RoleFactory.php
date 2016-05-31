<?php


namespace App\ApiBundle\Factory;


/**
 * Class RoleFactory
 * This class gets the user roles and generate an array with the data that must be shown by the API
 * @package App\ApiBundle\Factory
 */
class RoleFactory {

    /**
     * Returns an array with the user roles
     * @param $roles
     * @return array
     */
    public static function getRoles($roles){
        $result = array();
        foreach($roles as $role){
            $result[]=$role;
        }
        return $result;
    }

} 