<?php


namespace App\ApiBundle\Services;

use App\UserBundle\Model\User;

interface IApiAccessDecisionManager {
    public function hasPermission(User $user,$action);
} 