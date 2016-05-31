<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 30/05/16
 * Time: 23:15
 */

namespace App\ApiBundle\test\Services;


use App\ApiBundle\Services\APIContentValidator;
use App\ApiBundle\Exceptions\InvalidContentTypeException;
class APIContentValidatorTest extends \PHPUnit_Framework_TestCase{

    public function testValidateRequestFormat(){

        $_SERVER["HTTP_ACCEPT"] ="application/json";

        $validator = new APIContentValidator();
        $this->assertTrue($validator->validateRequestFormat());
    }

    public function testInvalidRequestFormat(){

        $_SERVER["HTTP_ACCEPT"] ="application/hal+json";
        $this->setExpectedException(InvalidContentTypeException::class);
        $validator = new APIContentValidator();
        $validator->validateRequestFormat();
    }
} 