<?php


namespace App\UserBundle\Services;


interface ILoginService {
    public function handleLogin($username, $pass);
    public function handleLogout();
}