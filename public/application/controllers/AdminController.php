<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_layout->setLayout('admin');
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
                        'title' => $this->_request->getPost('title'),
                        'image' => $form->image->getValue(),
                        'youtube' => $this->_request->getPost('youtube', ''),
                        'comment' => $this->_request->getPost('comment'),
                        'created' => date('Y-m-d h:i:s')
                    )
                );
                $form->reset();
                $this->view->message = $form->image->getValue();
            }
        }
        $this->view->form = $form;
    }

}
