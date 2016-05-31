<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 30/05/16
 * Time: 23:01
 */

namespace App\DBManagerBundle\test\Connection;


use App\DBManagerBundle\Connection\Connection;

class ConnectionTest extends \PHPUnit_Framework_TestCase{

    public function testGetInstance() {
        $this->assertNotNull(Connection::getInstance());
        $this->assertNotNull(Connection::getInstance(true));
    }

    public function testGetConnection() {
        $this->assertNotNull(Connection::getInstance()->getConnection());
        $this->assertNotNull(Connection::getInstance(true)->getConnection());

    }

} 