<?php


namespace App\TemplateEngineBundle\Services;

use App\TemplateEngineBundle\Exception\TemplateNotFoundException;

/**
 * Class TemplateEngineRenderer
 * Basic template renderer
 * @package App\TemplateEngineBundle\Services
 */
class TemplateEngineRenderer {

    /**
     * @var : the directory wich contains all templates
     */
    protected $templatesPath;
    /**
     * @var array : template params to show
     */
    protected $params;

    /**
     * @param $templatesPath
     */
    public function __construct($templatesPath){
        $this->params = array();
        $this->templatesPath = $templatesPath;
    }

    /**
     * Render a template with the parameters
     * @param $template
     * @param array $params
     * @return string
     * @throws \App\TemplateEngineBundle\Exception\TemplateNotFoundException
     */
    public function render($template,$params = array()){
        extract($params);
        ob_start();

        if(file_exists($this->templatesPath . $template)){
            include($this->templatesPath .$template);
        } else{
            throw new TemplateNotFoundException();
        }
        return ob_get_clean();
    }
} 