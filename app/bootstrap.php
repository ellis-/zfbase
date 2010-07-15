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
        
        $this->bootstrap('config')
             ->bootstrap('zfdebug')
             ->bootstrap('CacheManager');
        
        Zend_Registry::get('timer')->mark(__METHOD__);
        {
            $cache = $this->getResource('CacheManager')->getCache('general');
            $frontController = $this->bootstrap('frontController')->getResource('frontController');
            
            if(!$routesConfig = $cache->load('routes')) 
            {
                $config = Zend_Registry::get('config');
                
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
                $cache->save($routesConfig, 'routes');
            }
            
            $frontController->getRouter()->addConfig($routesConfig);
        } Zend_Registry::get('timer')->mark(__METHOD__);
        
    }
    
    protected function _initPostLog()
    {
        $this->bootstrap('log');
        Zend_Registry::set('log', $this->getResource('log'));
        //      
    }
    
	protected function _initZFDebug()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('ZFDebug');

        // Ensure the front controller is initialized
        $this->bootstrap('FrontController');

        // Retrieve the front controller from the bootstrap registry
        $front = $this->getResource('FrontController');

        if($this->hasOption("debugbar"))
        {
            $options = $this->getOption("debugbar");
            foreach($options['plugins'] as $plugin => $opt)
            {
                
                if($plugin == "Cache")
                {
                    $this->bootstrap('CacheManager');
                    
                    $cache = $this->getResource('CacheManager');
                    $options['plugins']['Cache']['backend'] = $cache->getCache($options['plugins']['Cache']['backend'])->getBackend();     
                }
                elseif($plugin == "Database")
                {
                    $this->bootstrap('Db');
                    
                    $cache = $this->getResource('Db');
                    $options['plugins']['Database']['adapter'] = Zend_Db_Table::getDefaultAdapter();
                }
                
            }
            $zfdebug = new ZFDebug_Controller_Plugin_Debug($options);
            $front->registerPlugin($zfdebug);
            
            Zend_Registry::set('debugbar', $zfdebug);
            Zend_Registry::set('timer', $zfdebug->getPlugin('Time'));
        }
    }
    
    protected function _initPostCache()
    {
        $this->bootstrap('CacheManager');
        Zend_Registry::set('cache', $this->getResource('CacheManager'));
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
