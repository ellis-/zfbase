<?php

class Ellis_Controller_Action_Helper_ViewRenderer
extends Zend_Controller_Action_Helper_ViewRenderer
{
    public function getViewScript($action = null, array $vars = array())
    {
        $request = $this->getRequest();
        if ((null === $action) && (!isset($vars['action']))) {
            $action = $this->getScriptAction();
            if (null === $action) {
                $action = $request->getActionName();
            }
            $vars['action'] = $action;
        } elseif (null !== $action) {
            $vars['action'] = $action;
        }

        $inflector = $this->getInflector();
        if ($this->getNoController() || $this->getNeverController()) {
            $this->_setInflectorTarget($this->getViewScriptPathNoControllerSpec());
        } else {
            $this->_setInflectorTarget($this->getViewScriptPathSpec());
        }
        return $this->_translateSpec($vars);
    }
}