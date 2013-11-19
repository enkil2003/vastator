<?php

class AdminController extends Zend_Controller_Action
{
    const IMAGE_WIDTH = 860;
    const IMAGE_HEIGHT = 500;
    const IMAGE_QUALITY = 75;
    
    public function init()
    {
        $this->user = new Zend_Session_Namespace('user');
        if (!isset($this->user->logged) && $this->_request->getActionName() != 'login') {
            $this->_redirect('/admin/login');
            exit;
        }
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
        $this->News = new Application_Model_DbTable_News();
        $this->Tour = new Application_Model_DbTable_Tour();
        $this->_helper->_layout->setLayout('admin');
    }
    
    public function loginAction()
    {
        $form = new Application_Form_Login();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            if ($form->user->getValue() == 'v4st4t0r' && $form->password->getValue() == 'tator771r') {
                $this->user->logged = true;
                $this->_redirect('/admin/');
                exit;
            } else {
                $this->view->message = "usuario o contraseña incorrectos";
            }
        }
        $this->view->form = $form;
    }
    
    public function logoutAction()
    {
        $this->user->unsetAll();
        $this->_redirect('/');
        exit;
    }
    
    private function _shrinkImage($pathToImage)
    {
        try {
            require_once APPLICATION_PATH . "/../library/PEAR/WideImage/WideImage.php";
            $image = WideImage::load($pathToImage);
            $image->resize(self::IMAGE_WIDTH, self::IMAGE_HEIGHT, 'outside')
                ->crop('center', 'center', self::IMAGE_WIDTH, self::IMAGE_HEIGHT)
                ->saveToFile($pathToImage, self::IMAGE_QUALITY);
        } catch (Exception $e) {
            throw new Exception("No se pudo achicar la imagen");
        }
        return true;
    }
    
    public function indexAction() {
        if ($this->_request->isPost()) {
            $post = $this->_request->getPost();
            foreach($post['delete'] as $imageToDelete) {
                unlink(APPLICATION_PATH . "/../img/slider/{$imageToDelete}");
            }
        }
        
        $dirHandler = opendir(APPLICATION_PATH . '/../img/slider');
        $photos = array();
        while($file = readdir($dirHandler)) {
            if (is_dir($file)) {
                continue;
            }
            $photos[] = $file;
        }
        $this->view->photos = $photos;
    }
    
    public function addSliderAction() {
        $form = new Application_Form_Slider();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            if (!$form->image->receive()) {
                $this->view->message = 'No se pudo guardar la imagen';
            } else {
                $this->_shrinkImage(APPLICATION_PATH . "/../img/slider/{$form->image->getValue()}");
                $form->reset();
                $this->view->message = "Imagen agregada con exito";
            }
        }
        $this->view->form = $form;
    }
    
    public function videosAction()
    {
        $form = new Application_Form_Videos();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $videosModel = new Application_Model_DbTable_Videos();
            $url = $form->youtube->getValue();
            if (strpos($url, 'v=')) {
                $url = array_pop(explode('v=', $url));
            }
            $videosModel->insert(
                array(
                    'youtube' => $url,
                )
            );
            $form->reset();
            $this->view->message = "Video agregado exito";
        }
        $this->view->form = $form;
    }
    
    public function newsAction()
    {
        $news = $this->News->fetchAll($this->News->select()->order('id'));
        $this->view->news = $news->toArray();
    }
    
    private function _deleteDir($dirPath) {
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->_deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
    
    private function _move_uploaded_file($file, $folder)
    {
        $this->_deleteDir(GALLERY_PATH . '/' . $folder);
        $this->_shrinkImage(GALLERY_PATH . "/{$file}");
        mkdir(GALLERY_PATH . '/' . $folder);
        copy(
            GALLERY_PATH . "/{$file}",
            GALLERY_PATH . "/{$folder}/{$file}"
        );
        @unlink(GALLERY_PATH . "/{$file}");
    }
    
    public function addNewsAction()
    {
        $form = new Application_Form_News();
        if ($id = $this->_request->getParam('id', null)) {
            $news = $this->News->fetchRow("id = $id");
            $form->populate($news->toArray());
        }
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            if ($form->id && $form->id->getValue()) {
                $row = $this->News->fetchRow("id = {$form->id->getValue()}");
                $created = $row->created;
                $message = "Noticia modificada con éxito";
            } else {
                $message = "Noticia creada con éxito";
                $row = $this->News->fetchNew();
                $created = date('Y-m-d h:i:s');
            }
            $row->title = $form->title->getValue();
            $row->youtube = $form->youtube->getValue();
            $row->comment = $form->comment->getValue();
            $row->created = $created;
            
            $row->save();
            
            if ($form->image->getValue()) {
                $form->image->receive();
                $this->_move_uploaded_file($form->image->getValue(), $row->id);
                $image = $form->image->getValue();
                $row->image = $image;
                $row->save();
            }
            
            $this->_helper->flashMessenger->addMessage($message);
            $this->_redirect('/admin/news');
        }
        $this->view->form = $form;
    }

    public function deleteNewsAction()
    {
        if ($id = $this->_request->getParam('id', null)) {
            $row = $this->News->fetchRow("id = $id");
            $this->_deleteDir(GALLERY_PATH . '/' . $id);
            $row->delete();
            $this->_helper->flashMessenger->addMessage("Notícia eliminada con éxito");
            $this->_redirect('/admin/news');
            exit;
        }
    }
    
    public function tourAction()
    {
        $tourData = $this->Tour->fetchAll($this->Tour->select()->order('created DESC'));
        $this->view->tour = $tourData->toArray();
    }

    public function addTourAction()
    {
        $form = new Application_Form_Tour();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $this->Tour->insert(
                array(
                    'location' => $form->location->getValue(),
                    'created' => $form->created->getValue()
                )
            );
            $this->_helper->flashMessenger->addMessage("Tour date creado con exito");
            $this->_redirect('/admin/tour');
            exit;
        }
        $this->view->form = $form;
    }

    public function deleteTourAction()
    {
        if ($id = $this->_request->getParam('id', null)) {
            $row = $this->Tour->fetchRow("id = $id");
            $row->delete();
            $this->_helper->flashMessenger->addMessage("Tour eliminado con éxito");
            $this->_redirect('/admin/tour');
            exit;
        }
    }
    
    public function theBandAction()
    {
        $form = new Application_Form_Theband();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            if (!$form->image->receive()) {
                $this->_helper->flashMessenger->addMessage('No se pudo guardar la imagen');
            } else {
                $thebandModel = new Application_Model_DbTable_Theband();
                $thebandModel->insert(
                    array(
                        'image' => $form->image->getValue(),
                        'history' => $form->history->getValue(),
                    )
                );
                $this->_helper->flashMessenger->addMessage("Historia creada con exito");
                $this->_redirect('/admin/the-band');
            }
        }
        $this->view->form = $form;
    }

    public function galleryAction()
    {
        $form = new Application_Form_Gallery();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            if (!$form->image->receive()) {
                $this->_helper->flashMessenger->addMessage('No se pudo guardar la imagen');
            } else {
                $galleryModel = new Application_Model_DbTable_Gallery();
                $galleryModel->insert(
                    array(
                        'year' => $form->year->getValue(),
                        'description' => $form->description->getValue(),
                        'image' => $form->image->getValue(),
                    )
                );
                $this->_shrinkImage(APPLICATION_PATH . '/../img/gallery/' . $form->image->getValue());
                $this->_helper->flashMessenger->addMessage("Imagen agregada con exito");
                $this->_redirect('/admin/gallery');
            }
        }
        $this->view->form = $form;
    }

}
