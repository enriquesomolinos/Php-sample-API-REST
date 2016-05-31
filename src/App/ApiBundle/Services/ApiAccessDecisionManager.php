<?php

namespace App\ApiBundle\Services;


use App\UserBundle\Model\User;

/**
 * Class ApiAccessDecisionManager
 * This class allow us to determine if a user can do an operation in the API REST
 * @package App\ApiBundle\Services
 */
class ApiAccessDecisionManager implements IApiAccessDecisionManager {


    /**
     * Admin role
     */
    const ROLE_ADMIN = "ADMIN";
    /**
     * Role for all users
     */
    const ALL_ROLES = "ALL";


    /**
     * @var : array that contains all permissions
     */
    private $actionPerimissions;

    /**
     *
     */
    function __construct()
    {
        $this->actionPerimissions['getUsersAction'] = self::ALL_ROLES;
        $this->actionPerimissions['getUserAction'] = self::ALL_ROLES;
        $this->actionPerimissions['userDeleteAction'] = self::ROLE_ADMIN;
        $this->actionPerimissions['createModifyUserAction'] = self::ROLE_ADMIN;
        $this->actionPerimissions['getUserRolesAction'] = self::ALL_ROLES;
        $this->actionPerimissions['modifyUserRolesAction'] = self::ROLE_ADMIN;
        $this->actionPerimissions['deleteUserRolesAction'] = self::ROLE_ADMIN;

    }


    /**
     * Check if the $user can access to the $action exposed by the API
     * @param User $user
     * @param $action
     * @return bool
     */
    public function hasPermission(User $user,$action){

       $role =  $this->actionPerimissions[$action];
       if($role==self::ALL_ROLES){
        return true;
       }
       else if(in_array($role,$user->getRoles())){
           return true;
       }

        return false;
    }
} 