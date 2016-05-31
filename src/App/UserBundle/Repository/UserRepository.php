<?php


namespace App\UserBundle\Repository;


use App\DBManagerBundle\Connection\Connection;
use App\UserBundle\Exception\NoDataFoundException;
use App\UserBundle\Model\User;

/**
 * Class UserRepository
 * This class constains all method that modify,create or delete users
 * @package App\UserBundle\Repository
 */
class UserRepository {



    protected $conn;

    function __construct($conn)
    {
        $this->conn = $conn;
    }


    /**
     * If the user exists, modify it.
     * If the user doesn't exists, create it.
     * @param User $user
     */
    public function createModifyUser(User $user){


        try{
            $user2= $this->findOneBy(array('username'=>$user->getUserName()));

            $statement =$this->conn->prepare("UPDATE user set  password= :password,roles = :roles where username = :username");
            $statement->bindParam(':username',$username);
            $statement->bindParam(':password',$password);
            $statement->bindParam(':roles',$roles);

            $username = $user->getUserName();
            $password = $user->getPassword();
            $roles = serialize($user->getRoles());

            $statement->execute();
        }catch(NoDataFoundException $ndf){

            $statement =$this->conn->prepare("INSERT INTO user (username,password,roles) VALUES (:username,:password,:roles)");
            $statement->bindParam(':username',$username);
            $statement->bindParam(':password',$password);
            $statement->bindParam(':roles',$roles);

            $username = $user->getUserName();
            $password = $user->getPassword();
            $roles = serialize($user->getRoles());
            $statement->execute();
        }

    }

    /**
     * Modifies the user roles
     * @param $username
     * @param $roles
     */
    public function modifyUserRoles($username,$roles){
        $user= $this->findOneBy(array('username'=>$username));
        $statement = $this->conn->prepare("UPDATE user set roles = :roles WHERE username = :username");
        $statement->bindParam(':username',$usernameToModify);
        $statement->bindParam(':roles',$rolesModify);
        $usernameToModify = $username;
        $rolesModify = serialize($roles);
        $statement->execute();
    }

    /**
     * Erase the user roles
     * @param $username
     */
    public function deleteUserRoles($username){
        $user= $this->findOneBy(array('username'=>$username));
        $statement = $this->conn->prepare("UPDATE user set roles = '' WHERE username = :username");
        $statement->bindParam(':username',$usernameToDelete);
        $usernameToDelete = $username;
        $statement->execute();
    }

    /**
     * Deletes a user
     * @param $username
     */
    public function delete($username){
        $user= $this->findOneBy(array('username'=>$username));
        $statement = $this->conn->prepare("DELETE FROM user WHERE username = :username");
        $statement->bindParam(':username',$usernameToDelete);
        $usernameToDelete = $username;
        $statement->execute();
    }

    /**
     * Returns all users
     * @return array
     * @throws \App\UserBundle\Exception\NoDataFoundException
     */
    public function findAll(){
        $result = $this->conn->query('SELECT * FROM  user ');
        $users=array();

        while($row = $result->fetchArray()){
            $user = new User($row['username'],$row['password'],unserialize($row['roles']));
            $users[]=$user;
        }
        if(sizeof($users)==0){
            throw new NoDataFoundException("No users");
        }
        return $users;
    }

    /**
     * Return one user filter by params
     * @param $params
     * @return User
     * @throws \App\UserBundle\Exception\NoDataFoundException
     */
    public function findOneBy($params){
        $params= $this->filterParams($params);
        $where = "where 1=1 ";
        if(array_key_exists('username',$params)){
            $where = $where.' and username="'.$params['username'].'"';
        }
        if(array_key_exists('password',$params)){
            $where = $where.' and password="'.$params['password'].'"';
        }

        $result = $this->conn->query('SELECT * FROM  user '.$where);
        $row = $result->fetchArray();
        if(!$row){
            throw new NoDataFoundException("User not found");
        }
        $user = new User($row['username'],$row['password'],unserialize($row['roles']));

        return $user;
    }

    /**
     * Return all user filter by params
     * @param $params
     * @return array
     * @throws \App\UserBundle\Exception\NoDataFoundException
     */
    public function findBy($params){
        $params= $this->filterParams($params);
        $where = "where 1=1 ";
        if(array_key_exists('username',$params)){
            $where = $where.' and username="'.$params['username'].'"';
        }
        if(array_key_exists('password',$params)){
            $where = $where.' and password="'.$params['password'].'"';
        }

        $result = $this->conn->query('SELECT * FROM  user '.$where);
        $users=array();

        while($row = $result->fetchArray()){
            $user = new User($row['username'],$row['password'],unserialize($row['roles']));
            $users[]=$user;
        }
        if(sizeof($users)==0){
            throw new NoDataFoundException("User not found");
        }
        return $users;
    }

    /**
     * Erases unnecesary elements in the filter array
     * @param $params
     * @return array
     */
    private function filterParams($params){
        $result= array();

        if(array_key_exists('username',$params)){
            $result['username'] = $params['username'];
        }
        if(array_key_exists('roles',$params)){
            $result['roles'] = $params['roles'];
        }
        if(array_key_exists('password',$params)){
            $result['password'] = $params['password'];
        }
        return $result;
    }
} 