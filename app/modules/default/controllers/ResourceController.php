<?php

class ResourceController extends Zend_Controller_Action
{
    public function indexAction()
    {
        
    }
    
    public function getAction()
    {
        $config = Zend_Registry::get('config');
        $modDir = $config->resources->frontController->moduleDirectory;
        
        var_dump($this->getFrontController()->getModuleDirectory($this->_getParam('source_module')));
        
        $this->_helper->getHelper('viewRenderer')->setNoRender();
    }
}
