<?php

namespace App\UserBundle\Session;


/**
 * Class SessionHandler
 * This class manages the user session and determines if the actual user session is valid
 * @package App\UserBundle\Session
 */
class SessionHandler {

    /**
     * Default timeout in seconds.
     */
    const SESSION_TIMEOUT = 300;

    /**
     * Starts a new session
     */
    public static function start(){
        if(session_status() != PHP_SESSION_ACTIVE){
            session_start();
        }
    }

    /**
     * Destroy the current session if it is active
     */
    public static function destroy(){
        if(session_status() == PHP_SESSION_ACTIVE){
            session_unset();
            session_destroy();
        }
    }

    /**
     * Determines if the current session is valid using the LAST_ACTIVITY time.
     * @return bool
     */
    public static function isValid(){

        if (!isset($_SESSION['LAST_ACTIVITY']) ||
            (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > self::SESSION_TIMEOUT))) {
            session_unset();     // unset $_SESSION variable for the run-time
            session_destroy();   // destroy session data in storage
            unset($_SESSION);

        }else if(isset($_SESSION['LAST_ACTIVITY']) && session_status() === PHP_SESSION_ACTIVE){
            $_SESSION['LAST_ACTIVITY'] = time();
        }
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Sets a new $value for the $key in the $_SESSION variable
     * @param $key
     * @param $value
     */
    public static function set($key,$value){
        $_SESSION[$key] =$value;
    }

    /**
     * Returns the value of the key $key in the $_SESSION array
     * @param $key
     * @return mixed
     */
    public static function get($key){
        if (isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
    }
} 