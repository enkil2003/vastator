<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_layout->setLayout('admin');
    }
    
    public function indexAction() {
        $this->_redirect('/admin/news');
        die;
    }

    public function newsAction()
    {
        $form = new Application_Form_News();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            if (!$form->image->receive()) {
                $this->view->message = 'No se pudo guardar la imagen';
            } else {
                $newsModel = new Application_Model_DbTable_News();
                $newsModel->insert(
                    array(
                        'title' => $form->title->getValue(),
                        'image' => $form->image->getValue(),
                        'youtube' => $form->youtube->getValue(),
                        'comment' => $form->comment->getValue(),
                        'created' => date('Y-m-d h:i:s')
                    )
                );
                $form->reset();
                $this->view->message = "Noticia creada con exito";
            }
        }
        $this->view->form = $form;
    }

    public function tourAction()
    {
        $form = new Application_Form_Tour();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $tourModel = new Application_Model_DbTable_Tour();
            $tourModel->insert(
                array(
                    'location' => $form->location->getValue(),
                    'created' => $form->created->getValue()
                )
            );
            $form->reset();
            $this->view->message = "Tour date creada con exito";
        }
        $this->view->form = $form;
    }

    public function theBandAction()
    {
        $form = new Application_Form_Theband();
            if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            if (!$form->image->receive()) {
                $this->view->message = 'No se pudo guardar la imagen';
            } else {
                $thebandModel = new Application_Model_DbTable_Theband();
                $thebandModel->insert(
                    array(
                        'image' => $form->image->getValue(),
                        'history' => $form->history->getValue(),
                    )
                );
                $form->reset();
                $this->view->message = "Historia creada con exito";
            }
        }
        $this->view->form = $form;
    }

    public function galleryAction()
    {
        $form = new Application_Form_Gallery();
            if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            if (!$form->image->receive()) {
                $this->view->message = 'No se pudo guardar la imagen';
            } else {
                $galleryModel = new Application_Model_DbTable_Gallery();
                $galleryModel->insert(
                    array(
                        'year' => $form->year->getValue(),
                        'description' => $form->description->getValue(),
                        'image' => $form->image->getValue(),
                    )
                );
                $form->reset();
                $this->view->message = "Imagen agregada con exito";
            }
        }
        $this->view->form = $form;
    }

}
