<?php

class Application_Model_DbTable_News extends Zend_Db_Table_Abstract
{

    protected $_name = 'news';
    public function getLatestNews()
    {
        if ($result = $this->fetchAll($this->select()->order('id DESC')->limit(3))) {
            $result = $result->toArray();
            return $result;
        }
    }
}

