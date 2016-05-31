<?php


namespace App\ApiBundle\Services;

use App\ApiBundle\Exceptions\InvalidContentTypeException;

/**
 * Class APIContentValidator
 * This class validates the request headers to avoid invalid request to the API
 * @package App\ApiBundle\Services
 */
class APIContentValidator implements IApiContentValidator {

    /**
     * This is de unique valid format
     */
    const VALID_FORMAT = 'application/json';

    /**
     * Checks if the request is correct.
     * @return bool
     * @throws \App\ApiBundle\Exceptions\InvalidContentTypeException
     */
    public function validateRequestFormat(){

        if(array_key_exists("HTTP_ACCEPT" ,$_SERVER)){
            if($_SERVER["HTTP_ACCEPT"]==self::VALID_FORMAT){
                return true;
            }
        }
        throw new InvalidContentTypeException("Unsupported Media Type. The only type valid is: ". self::VALID_FORMAT);
    }
}
