<?php


namespace App\MainBundle\Controller;

use App\TemplateEngineBundle\Services\TemplateEngineRenderer;

/**
 * Class AbstractController *
 * @package App\MainBundle\Controller
 */
class AbstractController {
    /**
     * Default templates path
     */
    const TEMPLATES_PATH = 'src/App/MainBundle/Resources/views/';

    /**
     * Call the default template renderer with the template and the parameters.
     * @param $page
     * @param array $params
     * @return string
     */
    public function render($page,$params=array()){
        $engine = new TemplateEngineRenderer(self::TEMPLATES_PATH);
        return $engine->render($page,$params);
    }
    /**
     * Redirects to a certain url
     * @param $url
     */
    protected function redirect($url){

        header("Location: ".$url);
    }
} 