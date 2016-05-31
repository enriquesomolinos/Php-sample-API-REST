<?php

namespace App\MainBundle\Controller;

use App\MainBundle\Response\HTTPResponse;
use App\UserBundle\Exception\NoDataFoundException;
use App\UserBundle\Services\AccessDecisionManager;
use App\UserBundle\Services\LoginService;
use App\TemplateEngineBundle\Services\TemplateEngineRenderer;
use App\TemplateEngineBundle\Exception\TemplateNotFoundException;
use App\UserBundle\Exception\BadCredentialsException;
use App\UserBundle\Services\UserService;
use App\UserBundle\Session\SessionHandler;


class MainController extends AbstractController{


    const ACTION_PAGE_1 ='/page1';
    const ACTION_PAGE_2 ='/page2';
    const ACTION_PAGE_3 ='/page3';
    const ACTION_LOGIN ='/login';
    const ACTION_INDEX ='/index';
    const ACTION_LOGOUT ='/logout';
    /**
     * Handles user request and generates the response
     * Checks if the user has an active session if not, redirect to the login page
     * Checks if the user has enought permission to access to this action
     * @return HTTPResponse|mixed|string
     */
    public function handle(){
        SessionHandler::start();

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $this->getDispatcher()->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                return new HTTPResponse("Route Not Found",404);
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                return new HTTPResponse("Method Not Allowed",405);
                break;
            case \FastRoute\Dispatcher::FOUND:

                if(SessionHandler::isValid() || $routeInfo[1]=='loginAction' || $routeInfo[1]=='getIndexAction') {

                    if($routeInfo[1]!='getIndexAction' && $routeInfo[1]!='loginAction' && !$this->getDecissionManager()->hasPermission($this->getUser(),$uri)){
                        return new HTTPResponse("Forbidden.You don't have enought permissions to do this operation ",403);
                    }else{
                        $handler = $routeInfo[1];
                        $vars = $routeInfo[2];
                        try{
                            $response = call_user_func(array($this, $handler));
                            return $response;
                        } catch (TemplateNotFoundException $ex){
                            return new HTTPResponse("Template Not Found",500);
                        }
                    }
                }else{
                    return $this->render('login.html',array('prevUrl'=>urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),
                        'msg'=>'Please login first'));
                }
                break;
        }
    }

    /**
     * Returns the class that decide the permissions
     * @return AccessDecisionManager
     */
    private function getDecissionManager(){
        $actionPerimissions[self::ACTION_PAGE_1] = AccessDecisionManager::ROLE_PAGE_1;
        $actionPerimissions[self::ACTION_PAGE_2] = AccessDecisionManager::ROLE_PAGE_2;
        $actionPerimissions[self::ACTION_PAGE_3] = AccessDecisionManager::ROLE_PAGE_3;
        $actionPerimissions[self::ACTION_LOGIN] = AccessDecisionManager::ALL_ROLES;
        $actionPerimissions[self::ACTION_INDEX] = AccessDecisionManager::ALL_ROLES;
        $actionPerimissions[self::ACTION_LOGOUT] = AccessDecisionManager::ALL_ROLES;

        $accessDecissionManager = new AccessDecisionManager($actionPerimissions);
        return $accessDecissionManager;
    }

    /**
     * Returns the dispatcher
     * @return \FastRoute\Dispatcher
     */
    private function getDispatcher(){
        $dispatcher = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
            $r->addRoute('GET', self::ACTION_PAGE_1, 'getPage1Action');
            $r->addRoute('GET', self::ACTION_PAGE_2, 'getPage2Action');

            $r->addRoute('GET',self::ACTION_PAGE_3, 'getPage3Action');
            $r->addRoute('GET', self::ACTION_INDEX, 'getIndexAction');
            $r->addRoute('POST', self::ACTION_LOGIN, 'loginAction');
            $r->addRoute('POST', self::ACTION_LOGOUT, 'logoutAction');
        });
        return $dispatcher;
    }
    /**
     * Return the session user
     * @return \App\UserBundle\Model\User|null
     */
    private function getUser(){
        $userService = new UserService();
        try{
            return $userService->getUser(SessionHandler::get('user'));
        }catch(NoDataFoundException $ndf){
            return null;
        }
    }

    /**
     * /page1 action
     * @return string
     */
    private function getPage1Action(){
        return $this->render('page1.html',array('name'=>SessionHandler::get('user')));
    }

    /**
     * /page2 action
     * @return string
     */
    private function getPage2Action(){
        return $this->render('page2.html',array('name'=>SessionHandler::get('user')));
    }

    /**
     * page3 action
     * @return string
     */
    private function getPage3Action(){
        return $this->render('page3.html',array('name'=>SessionHandler::get('user')));
    }

    /**
     * index action
     * @return string
     */
    private function getIndexAction(){
        return $this->render('index.html');
    }

    /**
     * LoginAction that determines if the user enter his user and password correctly
     * @return string
     */
    private function loginAction(){

        $user = $_POST["user"];
        $pass = $_POST["pass"];
        $loginService = new LoginService();
        try{
            $loginService->handleLogin($user,$pass);
        }catch(BadCredentialsException $bce){
            return $this->render('login.html',array('prevUrl'=>$_POST["prevUrl"],
                'msg'=>'User doesn\'t exist or the password is incorrect'));
        }catch(NoDataFoundException $ndf){
            return $this->render('login.html',array('prevUrl'=>$_POST["prevUrl"],
                'msg'=>'User doesn\'t exist or the password is incorrect'));
        }
        //redirect to previous page
        if(isset($_POST["prevUrl"])){
            $this->redirect(urldecode($_POST["prevUrl"]));
        }

    }

    /**
     * Logout action. Destroy de session
     * @return string
     */
    private function logoutAction(){
        $loginService = new LoginService();
        $loginService->handleLogout();
        return $this->render('index.html');
    }


}
