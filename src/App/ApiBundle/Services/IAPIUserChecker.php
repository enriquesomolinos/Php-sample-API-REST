<?php
/**
 * Created by PhpStorm.
 * User: esomolinos
 * Date: 31/05/16
 * Time: 9:01
 */

namespace App\ApiBundle\Services;


interface IAPIUserChecker {

    public function validateUser($username,$pass);
} 