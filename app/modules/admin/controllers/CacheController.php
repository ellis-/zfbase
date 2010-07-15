<?php

class admin_CacheController extends Zend_Controller_Action
{
    protected $_cache;
    
    public function init()
    {
        $this->_cache = Zend_Registry::get('cache');
    }
    
    public function indexAction()
    {
        $cache = $this->_cache->getCache('general');
        
        $this->view->cache = array();
        foreach($cache->getIds() as $id)
        {
            $this->view->cache[$id] = $cache->getMetadatas($id);
        }
        
    }
    
    public function clearAction()
    {
        
    }
}
