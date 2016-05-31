<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 30/05/16
 * Time: 23:03
 */

namespace App\ApiBundle\test\Factory;


use App\ApiBundle\Factory\RoleFactory;

class RoleFactoryTest extends \PHPUnit_Framework_TestCase{
    public function testGetRoles(){
        $roles = array('ADMIN','PAGE1');
        $result = RoleFactory::getRoles($roles);
        $this->assertTrue(sizeof($roles)==sizeof($result));
    }
} 