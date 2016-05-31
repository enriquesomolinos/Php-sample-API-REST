<?php

namespace App\MainBundle\Response;


/**
 * Class HTTPResponse
 * Implements a basic HTTP Response
 * @package App\MainBundle\Response
 */
class HTTPResponse {
    /**
     * @var : contain the data to respond
     */
    private $content;

    /**
     * With a content and a response code, builds a HTTPResponse
     * @param $content
     * @param int $responseCode
     */
    function __construct($content,$responseCode = 200)
    {
        http_response_code($responseCode);
        $this->content = $content;
    }

    /**
     * Return the content
     * @return mixed
     */
    function __toString()
    {
        return $this->content;
    }
} 