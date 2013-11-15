<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->selected = $this->_request->getActionName();
    }

    public function indexAction()
    {
        // action body
    }

    public function newsAction()
    {
        // action body
    }

    public function detailAction()
    {
        // action body
    }

    public function theBandAction()
    {
        // action body
    }

    public function tourAction()
    {
        // action body
    }

    public function galleryAction()
    {
        // action body
    }

    public function discographyAction()
    {
        // action body
    }


}

