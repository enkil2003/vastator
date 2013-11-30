<?php

class AdminController extends Zend_Controller_Action
{
    const IMAGE_WIDTH = 800;
    const IMAGE_HEIGHT = 600;
    const IMAGE_QUALITY = 75;
    
    public function init()
    {
        $this->_redirectIfNotLogged();
        $this->_initModels();
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
        $this->_helper->_layout->setLayout('admin');
    }
    
    private function _initModels()
    {
        $this->News = new Application_Model_DbTable_News();
        $this->Tour = new Application_Model_DbTable_Tour();
        $this->TheBandModel = new Application_Model_DbTable_Theband();
        $this->GalleryModel = new Application_Model_DbTable_Gallery();
    }
    
    private function _redirectIfNotLogged()
    {
        $this->user = new Zend_Session_Namespace('user');
        if (!isset($_COOKIE['logged'])) {
            if (!isset($this->user->logged) && $this->_request->getActionName() != 'login') {
                $this->_redirect('/admin/login');
                exit;
            }
        }
    }
    
    public function loginAction()
    {
        $form = new Application_Form_Login();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            if ($form->user->getValue() == 'v4st4t0r' && $form->password->getValue() == 'tator771r') {
                $this->user->logged = true;
                if ($form->remember->getValue()) {
                    setcookie('logged', true, time() + (60 * 60 * 24 * 365));
                }
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
        unset($_COOKIE['logged']);
        setcookie('logged', null, -1, '/');
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
            if (!strpos(strtolower($file), 'jpg')) {
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
                $this->_redirect("/admin/crop-image?imageName={$form->image->getValue()}");
                die;
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
        mkdir(GALLERY_PATH . '/' . $folder);
        chmod(GALLERY_PATH . '/' . $folder, 0777);
        copy(
            GALLERY_PATH . "/{$file}",
            GALLERY_PATH . "/{$folder}/{$file}"
        );
        chmod(GALLERY_PATH . "/{$folder}/{$file}", 0777);
        @unlink(GALLERY_PATH . "/{$file}");
        return GALLERY_PATH . "/{$folder}/{$file}";
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
                $file = $this->_move_uploaded_file($form->image->getValue(), $row->id);
                $image = $form->image->getValue();
                $row->image = $image;
                $row->save();
                
                if ($form->shrink->getValue()) {
                    $this->_helper->flashMessenger->addMessage("Debe recortar la imagen");
                    $this->_redirect("/admin/crop-image?imageName={$form->image->getValue()}&folder={$row->id}");
                } else {
                    $destination_folder = realpath(APPLICATION_PATH . "/../img/gallery/{$row->id}") . '/resized';
                    mkdir($destination_folder);
                    chmod($destination_folder, 0777);
                    require_once APPLICATION_PATH . "/../library/PEAR/WideImage/WideImage.php";
                    $image = WideImage::load(realpath(APPLICATION_PATH . "/../img/gallery/{$row->id}") . '/' .$form->image->getValue());
                    $image->resize(800, 600)->saveToFile("$destination_folder/{$form->image->getValue()}", self::IMAGE_QUALITY);
                    chmod("$destination_folder/{$form->image->getValue()}", 0777);
                }
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
        $this->view->galleries = $this->GalleryModel->fetchAll()->toArray();
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
        if ($this->_request->getParam('id') && $this->_request->getParam('gallery_id')) {
            $this->TourModel = new Application_Model_DbTable_Tour();
            $tour = $this->TourModel->fetchRow("id = " . $this->_request->getParam('id'));
            $tour->gallery_id = ($this->_request->getParam('gallery_id') != -1) ? $this->_request->getParam('gallery_id') : null;
            $tour->save();
            $this->_helper->flashMessenger->addMessage("Galeria agregada a Tour con exito");
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
                $this->TheBandModel->insert(
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

    public function cropImageAction()
    {
        $imgName = $this->_getParam('imageName');
        $folder = $this->_getParam('folder', '../slider');
        
        $form = new Application_Form_Crop();
        
        if($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $destination_folder = realpath(APPLICATION_PATH . "/../img/gallery/{$folder}") . '/resized';
            @mkdir($destination_folder);
            chmod($destination_folder, 0777);
            require_once APPLICATION_PATH . "/../library/PEAR/WideImage/WideImage.php";
            $image = WideImage::load(realpath(APPLICATION_PATH . "/../img/gallery/{$folder}") . '/' .$imgName);
            $image->crop($form->getValue('x'), $form->getValue('y'), $form->getValue('w'), $form->getValue('h'))
                  ->resize(800, 600)
                  ->saveToFile("$destination_folder/$imgName", self::IMAGE_QUALITY);
            chmod("$destination_folder/$imgName", 0777);
            
            if ('../slider' != $folder) {
                $this->_redirect('/admin/add-news?id=' . $folder);
            } else {
                $this->_redirect('/admin/');
            }
            die;
        }
        
        $this->view->fileName = $imgName;
        $this->view->fileOriginalPath = "/img/gallery/{$folder}/$imgName";
        $this->view->imgWidth = self::IMAGE_WIDTH;
        $this->view->imgHeight = self::IMAGE_HEIGHT;
        
        $this->view->form = $form;
    }

}
