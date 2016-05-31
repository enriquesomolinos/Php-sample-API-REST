<?php

namespace App\UserBundle\Services;


use App\UserBundle\Repository\UserRepository;
use App\UserBundle\Session\SessionHandler;
use App\DBManagerBundle\Connection\Connection;


/**
 * Class LoginService
 * This class manages the login and logout operations
 * @package App\UserBundle\Services
 */
class LoginService extends AbstractLoginService implements ILoginService{
    function __construct(UserRepository $userRepository=null)
    {
        $this->userRepo = $userRepository;
        if($userRepository==null){
            $this->userRepo = new UserRepository(Connection::getInstance()->getConnection());
        }
    }

    /**
     * Check if the user is correct and create a new session
     * @param $username
     * @param $pass
     */
    public function handleLogin($username, $pass){
        SessionHandler::start();
        SessionHandler::set('user',$username);
        SessionHandler::set('LAST_ACTIVITY',time());
        $this->checkUser($username, $pass);
    }


    /**
     * Destroy the user session
     */
    public function handleLogout(){
        SessionHandler::destroy();
    }
} 