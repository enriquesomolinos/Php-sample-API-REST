<?php


namespace App\ApiBundle\Response;


/**
 * Class JsonResponse
 * Basic JSON Reponse used by de REST API
 * @package App\ApiBundle\Response
 */
class JsonResponse {

    /**
     * @var string : contains the response data
     */
    private $content;

    /**
     * @param $content
     * @param int $responseCode
     */
    function __construct($content,$responseCode = 200)
    {
        http_response_code($responseCode);
        header('Content-Type: application/json');
        $this->content = json_encode($content);
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->content;
    }


}