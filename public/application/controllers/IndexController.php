<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->selected = $this->_request->getActionName();
        $this->Gallery = new Application_Model_DbTable_Gallery();
        $this->Images = new Application_Model_DbTable_Images();
        $this->News = new Application_Model_DbTable_News();
        $this->Videos = new Application_Model_DbTable_Videos();
    }

    public function indexAction()
    {
        $dirHandler = opendir(APPLICATION_PATH . '/../img/slider');
        $photos = array();
        while($file = readdir($dirHandler)) {
            if (is_dir($file)) {
                continue;
            }
            $photos[] = $file;
        }
        shuffle($photos);
        $this->view->photos = $photos;
        
        $this->view->news = $this->News->fetchRow()->toArray();
        
        $this->view->video = $this->Videos->fetchRow()->toArray();
    }

    public function newsAction()
    {
        $newsData = $this->News->fetchAll($this->News->select()->order('created DESC'));
        $this->view->news = $newsData->toArray();
    }

    public function detailAction()
    {
        // action body
    }

    public function theBandAction()
    {
        $theBandModel = new Application_Model_DbTable_Theband();
        $bandData = $theBandModel->fetchRow();
        if (count($bandData)) {
            $this->view->band = $bandData->toArray();
        }
    }

    public function tourAction()
    {
        $tourModel = new Application_Model_DbTable_Tour();
        $tourData = $tourModel->fetchAll($tourModel->select()->order('created DESC'));
        $this->view->tour = $tourData->toArray();
    }

    public function galleryAction()
    {
        $year = $this->_request->getParam('date', 2010);
        $endYear = $year + 10;
//         $galleryData = $galleryModel->fetchAll($galleryModel->select()->where("year > $year AND year < $endYear"));
        $galleryData = $this->Gallery->fetchAll();
//         print_r($galleryData->current()->findDependentRowset('Application_Model_DbTable_Images')->toArray());
//         die;
        $this->view->gallery = $galleryData->toArray();
        
        if ($this->_request->getParam('id', false)) {
            $this->view->images = $this->Gallery->getImages($this->_request->getParam('id'));
            $this->view->selected = $this->_request->getParam('id');
        } else {
            $this->view->images = $this->Images->fetchAll($this->Images->select()->limit(10)->order(new Zend_Db_Expr('RANDOM()')));
        }
        
        $this->view->year = $year;
    }

    public function galleryDetailAction()
    {
        $this->view->gallery = $this->Gallery->getImages($this->_request->getParam('id'));
    }
    
    public function discographyAction()
    {
        // action body
    }

    public function videosAction()
    {
        $videosData = $this->Videos->fetchAll();
        if ($videosData) {
            $this->view->videos = $videosData->toArray();
        }
    }
    
    public function insertImagesAction()
    {
        $id = 2;
        $Images = new Application_Model_DbTable_Images();
        $dh = opendir(realpath(APPLICATION_PATH . '/../img/gallery/' . $id));
        while(($file = readdir($dh)) !== false) {
            if (!is_dir($file)) {
                $Images->insert(array(
                    'gallery_id' => $id,
                    'image' => $file
                ));
            }
        }
        die;
    }

}
