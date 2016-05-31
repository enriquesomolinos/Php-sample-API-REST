<?php


namespace App\UserBundle\Services;


use App\UserBundle\Model\User;

/**
 * Class AccessDecisionManager
 * Determines if a user has permission to access to a certain action
 * @package App\UserBundle\Services
 */
class AccessDecisionManager {


    /**
     * Const for actions that doesn't need authorization
     */
    const ALL_ROLES = "ALL";
    const ROLE_PAGE_1 = 'PAGE_1';
    const ROLE_PAGE_2 = 'PAGE_2';
    const ROLE_PAGE_3 = 'PAGE_3';

    /**
     * @var : array with permissions
     */
    private $actionPerimissions;

    /**
     * @param $actionPerimissions
     */
    function __construct($actionPerimissions)
    {
        $this->actionPerimissions =$actionPerimissions;
    }


    /**
     * Determines if the user $user has permission to execute the action $action
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