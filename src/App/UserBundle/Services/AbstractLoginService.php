<?php


namespace App\UserBundle\Services;

use App\UserBundle\Repository\UserRepository;
use App\UserBundle\Exception\BadCredentialsException;
use App\DBManagerBundle\Connection\Connection;

/**
 * Class AbstractLoginService
 * This class must be used in case that you want to validate a user.
 * For example, a login form or an Api Rest user validation
 * @package App\UserBundle\Services
 */
abstract class AbstractLoginService {
    protected $userRepo;

    function __construct(UserRepository $userRepository=null)
    {
        $this->userRepo = $userRepository;
        if($userRepository==null){
            $this->userRepo = new UserRepository(Connection::getInstance()->getConnection());
        }
    }
    /**
     * Determines if the current username and password corresponds to a valid user.     *
     * @param $username
     * @param $pass
     * @return bool
     * @throws \App\UserBundle\Exception\BadCredentialsException
     */
    protected function checkUser($username, $pass){

        $user =$this->userRepo->findOneBy(array('username'=>$username));

        if($user==null || $user->getPassword()!=$pass ){
            throw new BadCredentialsException('Invalid user or incorrect password');
        }
        return true;
    }

} 