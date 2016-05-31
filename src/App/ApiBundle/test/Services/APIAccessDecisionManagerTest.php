<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 30/05/16
 * Time: 23:23
 */

namespace App\ApiBundle\test\Services;


use App\ApiBundle\Services\ApiAccessDecisionManager;
use App\UserBundle\Model\User;

class APIAccessDecisionManagerTest extends \PHPUnit_Framework_TestCase {

    public function testHasPermission(){

        $user = new User('demo','pass',array('ADMIN'));

        $manager = new ApiAccessDecisionManager();
        $this->assertTrue($manager->hasPermission($user,'getUsersAction'));
        $this->assertTrue($manager->hasPermission($user,'getUserAction'));
        $this->assertTrue($manager->hasPermission($user,'userDeleteAction'));
        $this->assertTrue($manager->hasPermission($user,'userDeleteAction'));
        $this->assertTrue($manager->hasPermission($user,'createModifyUserAction'));
        $this->assertTrue($manager->hasPermission($user,'getUserRolesAction'));
        $this->assertTrue($manager->hasPermission($user,'modifyUserRolesAction'));
        $this->assertTrue($manager->hasPermission($user,'deleteUserRolesAction'));
    }

    public function testHasPermissionPublic(){

        $user = new User('demo','pass',array('PAGE_1'));

        $manager = new ApiAccessDecisionManager();
        $this->assertTrue($manager->hasPermission($user,'getUsersAction'));
        $this->assertTrue($manager->hasPermission($user,'getUserAction'));
        $this->assertFalse($manager->hasPermission($user,'userDeleteAction'));
        $this->assertFalse($manager->hasPermission($user,'userDeleteAction'));
        $this->assertFalse($manager->hasPermission($user,'createModifyUserAction'));
        $this->assertTrue($manager->hasPermission($user,'getUserRolesAction'));
        $this->assertFalse($manager->hasPermission($user,'modifyUserRolesAction'));
        $this->assertFalse($manager->hasPermission($user,'deleteUserRolesAction'));
    }
    public function testHasPermissionError(){

        $user = new User('demo','pass',array('PAGE_1'));

        $manager = new ApiAccessDecisionManager();
        $this->assertFalse($manager->hasPermission($user,'modifyUserRolesAction'));
        $this->assertFalse($manager->hasPermission($user,'userDeleteAction'));
        $this->assertFalse($manager->hasPermission($user,'createModifyUserAction'));
        $this->assertFalse($manager->hasPermission($user,'modifyUserRolesAction'));
        $this->assertFalse($manager->hasPermission($user,'deleteUserRolesAction'));
    }


} 