<?php
use App\MainBundle\Controller\MainController;
use App\ApiBundle\Controller\ApiController;


require_once __DIR__.'/vendor/autoload.php';

$controller = new MainController();

$uri = $_SERVER['REQUEST_URI'];

if (strpos($uri, 'api/')==1) {
    $controller = new ApiController();
}
$response = $controller->handle();

echo $response;