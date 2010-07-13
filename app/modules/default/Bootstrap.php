<?php

class Default_Bootstrap extends Zend_Application_Module_Bootstrap
{
    protected $_moduleName = "default";
    
	protected function _initDefaultModule()
    {
        /*Mod_Resource::override("testmod/test.png", "index/test.png");
        Mod_Resource::getPath("testmod/test.png");*/
    }
    
}
