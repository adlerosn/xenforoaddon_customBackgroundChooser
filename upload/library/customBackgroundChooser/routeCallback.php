<?php
class customBackgroundChooser_routeCallback implements XenForo_Route_Interface
{
    public function match($routePath, Zend_Controller_Request_Http $request, XenForo_Router $router)
    {
        $controller = 'customBackgroundChooser_routeController';

        return $router->getRouteMatch($controller, '', '');
    }
}
