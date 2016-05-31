<?php


namespace App\UserBundle\Model;


/**
 * Class User
 * The User model.
 * The password is not encripted at this moment. It can be crypted with the php mcrypt functions
 * to enchance the secutiry
 * @package App\UserBundle\Model
 */
class User {


    private $username;
    /**
     * @var contains the user roles.
     */

    private $roles;
    private $password;

    /**
     * @param $username
     * @param $password
     * @param array $roles
     */
    function __construct($username,$password, $roles =array())
    {
        $this->password = $password;
        $this->roles = $roles;
        if($roles == null){
            $this->roles = array();
        }
        $this->username = $username;
    }


    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->username;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
} 