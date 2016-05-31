<?php


namespace App\UserBundle\test\Services;


use App\UserBundle\Model\User;
use App\UserBundle\Services\AccessDecisionManager;

class AccessDecisionManagerTest extends \PHPUnit_Framework_TestCase {

    const ACTION_PAGE_1 ='/page1';
    const ACTION_PAGE_2 ='/page2';
    const ACTION_PAGE_3 ='/page3';
    const ACTION_LOGIN ='/login';
    const ACTION_INDEX ='/index';
    const ACTION_LOGOUT ='/logout';



    public function testValidAccess(){
        $user = new User("test","pass",array(AccessDecisionManager::ROLE_PAGE_1));
        $user2 = new User("test2","pass2",array(AccessDecisionManager::ROLE_PAGE_1,AccessDecisionManager::ROLE_PAGE_2));

        $actionPerimissions[self::ACTION_PAGE_1] = AccessDecisionManager::ROLE_PAGE_1;
        $actionPerimissions[self::ACTION_PAGE_2] = AccessDecisionManager::ROLE_PAGE_2;
        $accessDecissionManager = new AccessDecisionManager($actionPerimissions);

        $this->assertTrue($accessDecissionManager->hasPermission($user,self::ACTION_PAGE_1));
        $this->assertTrue($accessDecissionManager->hasPermission($user2,self::ACTION_PAGE_2));
        $this->assertTrue($accessDecissionManager->hasPermission($user2,self::ACTION_PAGE_1));

    }

    public function testInvalidAccess(){
        $user = new User("test","pass",array(AccessDecisionManager::ROLE_PAGE_1));
        $user2 = new User("test2","pass2",array(AccessDecisionManager::ROLE_PAGE_1,AccessDecisionManager::ROLE_PAGE_2));

        $actionPerimissions[self::ACTION_PAGE_1] = AccessDecisionManager::ROLE_PAGE_1;
        $actionPerimissions[self::ACTION_PAGE_2] = AccessDecisionManager::ROLE_PAGE_2;
        $actionPerimissions[self::ACTION_PAGE_3] = AccessDecisionManager::ROLE_PAGE_3;
        $accessDecissionManager = new AccessDecisionManager($actionPerimissions);

        $this->assertFalse($accessDecissionManager->hasPermission($user,self::ACTION_PAGE_3));
        $this->assertFalse($accessDecissionManager->hasPermission($user,self::ACTION_PAGE_2));
        $this->assertFalse($accessDecissionManager->hasPermission($user2,self::ACTION_PAGE_3));


    }


} 