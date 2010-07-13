<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	#stores a copy of the config object in the Registry for future references
	#!IMPORTANT: Must be runed before any other inits
	protected function _initConfig()
    {
    	Zend_Registry::set('config', new Zend_Config($this->getOptions()));
    }

	#Initializes the default timezone for the php ENV
    protected function _initDate()
    {
    	date_default_timezone_set(Zend_Registry::get('config')->settings
    														  ->application
    														  ->datetime);
    }
    
    protected function _initRoutes()
    {
        $this->bootstrap('config');
        $config = Zend_Registry::get('config');
        
        $frontController = $this->bootstrap('frontController')->getResource('frontController');
        $modDir = $config->resources->frontController->moduleDirectory;
        
        $dir = new DirectoryIterator($modDir);
        $routesConfig = new Zend_Config(array(), true);
        foreach ($dir as $fileInfo) {
            if($fileInfo->isDot()) continue;
            $routesFile = new SplFileInfo($fileInfo->getPathname() . '/etc/routes.xml');
            if($routesFile->isReadable())
            {
                $routesConfig->merge(new Zend_Config_Xml($routesFile));
            }
        }
        
        $frontController->getRouter()->addConfig($routesConfig);
    }
    
    protected function _initPostLog()
    {
        $this->bootstrap('log');
        Zend_Registry::set('log', $this->getResource('log'));
        //      
    }
    
    #stores a copy of all the database adapters in the Registry for future references
	/*protected function _initDatabases()
    {
		$this->bootstrap('multidb');
		$resource = $this->getPluginResource('multidb');
    	$databases = Zend_Registry::get('config')->resources->multidb;
	    foreach ($databases as $name => $adapter)
	    {
	    	$db_adapter = $resource->getDb($name);
	    	Zend_Registry::set($name, $db_adapter);
	    }
    }*/
}
