<?php

namespace App\ApiBundle\Controller;

use App\ApiBundle\Exceptions\InvalidContentTypeException;
use App\ApiBundle\Exceptions\InvalidPayloadException;
use App\ApiBundle\Exceptions\NoPayLoadFound;
use App\ApiBundle\Response\JsonResponse;
use App\ApiBundle\Services\ApiAccessDecisionManager;
use App\ApiBundle\Services\APIContentValidator;
use App\ApiBundle\Services\ApiService;
use App\ApiBundle\Services\APIUserChecker;
use App\UserBundle\Exception\BadCredentialsException;
use App\UserBundle\Exception\NoDataFoundException;
use App\UserBundle\Model\User;
use App\UserBundle\Services\UserService;

/**
 * Class ApiController
 * This controller manage de API REST requests.
 * @package App\ApiBundle\Controller
 */
class ApiController {

    /**
     * Api version
     */
    const API_VERSION ="v1";

    /**
     * THis is the API service
     * @var \App\ApiBundle\Services\ApiService
     */
    private $apiService;


    function __construct()
    {
        $this->apiService = new ApiService();
    }


    /**
     * Checks the request for invalid content header.
     * Checks the request user data.
     * Checks the user authorization
     * @return JsonResponse|mixed
     */
    public function handle(){

        $apiContentValidator = new APIContentValidator();
        try{
                $apiContentValidator->validateRequestFormat();
        }catch(InvalidContentTypeException $ict){
            return new JsonResponse($ict->getMessage(),415);

        }
        // Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $this->getDispatcher()->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                return new JsonResponse("Not Found",404);
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                return new JsonResponse("Method Not Allowed",405);
                break;
            case \FastRoute\Dispatcher::FOUND:
               $apiUserChecker = new APIUserChecker();
               try{
                   if(array_key_exists('PHP_AUTH_USER',$_SERVER) &&
                       array_key_exists('PHP_AUTH_PW',$_SERVER) &&
                       $apiUserChecker->validateUser($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW'])){

                        $accessDecissionManager = new ApiAccessDecisionManager();
                        if(!$accessDecissionManager->hasPermission($this->getUser(), $routeInfo[1])){
                            return new JsonResponse("Forbidden.You don't have enought permissions to do  this operation ",403);
                        }else{
                            $handler = $routeInfo[1];
                            $vars = $routeInfo[2];

                            $response = call_user_func(array($this, $handler),$vars);
                            return $response;
                        }
                    }else{
                       return new JsonResponse("Unauthorized",401);
                   }
                }catch (BadCredentialsException $bc){
                   return new JsonResponse("Unauthorized",401);
               }catch (InvalidPayloadException $ip){
                   return new JsonResponse("Bad request. The payload is not complete",400);
               }catch (NoPayLoadFound $np){
                   return new JsonResponse("Bad request. There is no payload or the fotmat is not correct",400);
               }
                break;
        }
    }

    private function getDispatcher(){
        $dispatcher = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
            $r->addRoute('GET', '/api/'.self::API_VERSION.'/user', 'getUsersAction');
            $r->addRoute('GET', '/api/'.self::API_VERSION.'/user/{username:\w+}', 'getUserAction');
            $r->addRoute('DELETE', '/api/'.self::API_VERSION.'/user/{username:\w+}', 'userDeleteAction');
            $r->addRoute('POST', '/api/'.self::API_VERSION.'/user', 'createModifyUserAction');
            $r->addRoute('GET', '/api/'.self::API_VERSION.'/user/rol/{username:\w+}', 'getUserRolesAction');
            $r->addRoute('POST', '/api/'.self::API_VERSION.'/user/rol/{username:\w+}', 'modifyUserRolesAction');
            $r->addRoute('DELETE', '/api/'.self::API_VERSION.'/user/rol/{username:\w+}', 'deleteUserRolesAction');
        });
        return $dispatcher;
    }

    /**
     * Returns the user stored in the request
     * @return User
     */
    private function getUser(){

        $userService = new UserService();
        return $userService->getUser($_SERVER['PHP_AUTH_USER']);
    }

    /**
     * Returns all users in json format
     * @return JsonResponse
     */
    private function getUsersAction(){
            $users = $this->apiService->getAllUsers();
            return new JsonResponse($users);
    }

    /**
     * Returns a users in json format
     * @param $vars
     * @return JsonResponse
     */
    private function getUserAction($vars){
        try{
            $users = $this->apiService->getUser($user = $vars["username"]);
            return new JsonResponse($users);
        }catch(NoDataFoundException $ndf){
            return new JsonResponse("User not found",404);
        }
    }

    /**
     * Deletes a user.
     * @param $vars
     * @return JsonResponse
     */
    private function userDeleteAction($vars){
        try{
            $users = $this->apiService->deleteUser($user = $vars["username"]);
        }catch(NoDataFoundException $ndf){
            return new JsonResponse("User not found",404);
        }
        return new JsonResponse("User ".$vars["username"]. " deleted sucesfully",200);

    }

    /**
     * Create or modify a user.
     * If no payload is present return an error.
     * @throws \App\ApiBundle\Exceptions\NoPayLoadFound
     */
    private function createModifyUserAction(){

        $vars = file_get_contents("php://input");
        $obj = json_decode($vars,TRUE);

        if(!$obj){
            throw new NoPayLoadFound();
        }

        if(!array_key_exists('username',$obj) || !array_key_exists('password',$obj) || !array_key_exists('roles',$obj)){
            throw new InvalidPayloadException("The payload is not correct");
        }
        $roles = array();

        foreach($obj['roles'] as $rol) {

            $roles[] = $rol;
        }

        $user = new User($obj['username'],$obj['password'],$roles);

        $this->apiService->createModifyUser($user);
    }

    /**
     * Returns the roles of the user.
     * @param $vars
     * @return JsonResponse
     */
    private function getUserRolesAction($vars){
        try{

            $roles = $this->apiService->getUserRoles($vars["username"]);
            return new JsonResponse($roles);
        }catch(NoDataFoundException $ndf){
            return new JsonResponse("User doesn't exists",404);
        }
    }

    /**
     * Create or modify the user roles.
     * @param $vars
     * @return JsonResponse
     * @throws \App\ApiBundle\Exceptions\NoPayLoadFound
     */
    private function modifyUserRolesAction($vars){
        $input = file_get_contents("php://input");
        $obj = json_decode($input,TRUE);

        if(!$obj){
            throw new NoPayLoadFound();
        }
        if(!array_key_exists('roles',$obj)){
            throw new InvalidPayloadException("The payload is not correct");
        }

        $roles = array();
        foreach($obj['roles'] as $rol) {
            $roles[] = $rol;
        }
        try{
            $this->apiService->modifyUserRoles($vars["username"],$roles);
        }catch(NoDataFoundException $ndf){
            return new JsonResponse("User not found",404);
        }
        return new JsonResponse("Roles of user ".$vars["username"]. " modified sucesfully",200);
    }

    /**
     * Erases the user roles
     * @param $vars
     * @return JsonResponse
     */
    private function deleteUserRolesAction($vars){
        try{
            $users = $this->apiService->deleteUserRoles($user = $vars["username"]);
        }catch(NoDataFoundException $ndf){
            return new JsonResponse("User not found",404);
        }
        return new JsonResponse("Roles of user ".$vars["username"]. " deleted sucesfully",200);
    }


} 