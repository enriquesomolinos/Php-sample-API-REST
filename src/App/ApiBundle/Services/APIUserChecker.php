<?php


namespace App\ApiBundle\Services;


use App\UserBundle\Exception\NoDataFoundException;
use App\UserBundle\Services\AbstractLoginService;
use App\UserBundle\Exception\BadCredentialsException;
use App\UserBundle\Services\UserRepository;
use App\UserBundle\Services\UserService;
use App\UserBundle\Session\SessionHandler;

/**
 * Class APIUserChecker
 * Checks the parameters of the request refering to the HTTP basic Auth
 * @package App\ApiBundle\Services
 */
class APIUserChecker extends AbstractLoginService implements IAPIUserChecker {


    /**
     * Uses de AbstractLoginService to validate the user.
     * @param $username
     * @param $pass
     * @return bool
     */
    public function validateUser($username,$pass){
        try{
            $response = $this->checkUser($username, $pass);
            return $response;
        }catch(NoDataFoundException $ndf){
            throw new BadCredentialsException();
        }

    }


} 