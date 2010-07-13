<?php
require_once 'Zend/View/Helper/Abstract.php';

class Ellis_View_Helper_UrlRes extends Zend_View_Helper_Abstract
{
    /**
     * Generates an url given the name of a route.
     *
     * @access public
     *
     * @param  array $urlOptions Options passed to the assemble method of the Route object.
     * @param  mixed $name The name of a Route to use. If null it will use the current Route
     * @param  bool $reset Whether or not to reset the route defaults with those provided
     * @return string Url for the link href attribute.
     */
    public function url($file, $module = null)
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        return $router->assemble($urlOptions, $name, $reset, $encode);
    }
}
