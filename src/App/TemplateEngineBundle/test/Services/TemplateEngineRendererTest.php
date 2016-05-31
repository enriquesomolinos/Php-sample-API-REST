<?php


namespace App\TemplateEngineBundle\test\Services;


use App\TemplateEngineBundle\Services\TemplateEngineRenderer;
use App\TemplateEngineBundle\Exception\TemplateNotFoundException;
class TemplateEngineRendererTest extends \PHPUnit_Framework_TestCase {


    public function testRender(){

        $templateEngine = new TemplateEngineRenderer("src/App/TemplateEngineBundle/test/Resources/views/");
        $response = $templateEngine->render('test.html');
        $this->assertTrue($response =="this is a test");
    }

} 