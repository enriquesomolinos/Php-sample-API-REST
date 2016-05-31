<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 30/05/16
 * Time: 23:10
 */

namespace App\ApiBundle\test\Services;


use App\UserBundle\test\Services\AbstractDatabaseTest;
use App\ApiBundle\Services\APIUserChecker;
use App\UserBundle\Exception\BadCredentialsException;

class APIUserCheckerTest extends AbstractDatabaseTest{

    public function testValidateUser(){
        $userChecker = new APIUserChecker();
        $result =$userChecker->validateUser('test2','pass2');
        $this->assertTrue($result);
    }

    public function testInvalidUser(){
        $userChecker = new APIUserChecker();
        $this->setExpectedException(BadCredentialsException::class);
        $result =$userChecker->validateUser('test2','passInvalid');
    }
} 