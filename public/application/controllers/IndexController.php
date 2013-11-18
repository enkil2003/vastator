<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->selected = $this->_request->getActionName();
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
        
        $videosModel = new Application_Model_DbTable_Videos();
        $videosData = $videosModel->fetchAll();
        if ($videosData) {
            $this->view->videos = $videosData->toArray();
        }
    }

    public function newsAction()
    {
        $news = new Application_Model_DbTable_News();
        $newsData = $news->fetchAll($news->select()->order('created DESC'));
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
        $tourData = $tourModel->fetchAll($tourModel->select()->order('created ASC'));
        $this->view->tour = $tourData->toArray();
    }

    public function galleryAction()
    {
        $year = $this->_request->getParam('date', 2010);
        $endYear = $year + 10;
        $galleryModel = new Application_Model_DbTable_Gallery();
        $galleryData = $galleryModel->fetchAll($galleryModel->select()->where("year > $year AND year < $endYear"));
        $this->view->gallery = $galleryData->toArray();
        
        $this->view->year = $year;
    }

    public function discographyAction()
    {
        // action body
    }

    public function mediaAction()
    {
        // action body
    }

}
