<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Configures Zend Router settings.
     * @return Zend_Controller_Router_Interface
     */
    protected function _initRouter()
    {
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();
        $routes = array(
            // Index Controller
            'news' => new Zend_Controller_Router_Route(
                'news',
                array(
                    'controller' => 'index',
                    'action' => 'news'
                )
            ),
            'tour' => new Zend_Controller_Router_Route(
                'tour',
                array(
                    'controller' => 'index',
                    'action' => 'tour'
                )
            ),
            'gallery' => new Zend_Controller_Router_Route(
                'gallery',
                array(
                    'controller' => 'index',
                    'action' => 'gallery'
                )
            ),
            'discography' => new Zend_Controller_Router_Route(
                'discography',
                array(
                    'controller' => 'index',
                    'action' => 'discography'
                )
            ),
            'the-band' => new Zend_Controller_Router_Route(
                'the-band',
                array(
                    'controller' => 'index',
                    'action' => 'the-band'
                )
            ),
            'media' => new Zend_Controller_Router_Route(
                'media',
                array(
                    'controller' => 'index',
                    'action' => 'media'
                )
            ),
            // Error Controller
            'error' => new Zend_Controller_Router_Route(
                'error',
                    array(
                        'controller' => 'error',
                        'action' => 'template'
                    )
            ),
        );
        $router->addRoutes($routes);
        return $router;
    }
}

