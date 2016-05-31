<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 30/05/16
 * Time: 23:03
 */

namespace App\ApiBundle\test\Factory;


use App\ApiBundle\Factory\UserFactory;
use App\UserBundle\Model\User;

class UserFactoryTest extends \PHPUnit_Framework_TestCase{
    public function testGetRoles(){
        $user = new User("name","pass",array('ADMIN','PAGE1'));
        $result = UserFactory::getUser($user);

        $this->assertTrue($result['username']=="name");
        $this->assertTrue(sizeof($result['roles'])==2);
        $this->assertFalse(array_key_exists('password',$result));
    }
}
