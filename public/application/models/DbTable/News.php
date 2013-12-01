<?php

class Application_Model_DbTable_News extends Zend_Db_Table_Abstract
{

    protected $_name = 'news';
    public function getLatestNews()
    {
        if ($result = $this->fetchRow($this->select()->order('id DESC'))) {
            return $result->toArray();
        }
    }
}

