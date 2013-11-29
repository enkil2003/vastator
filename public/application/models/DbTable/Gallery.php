<?php

class Application_Model_DbTable_Gallery extends Zend_Db_Table_Abstract
{
    
    protected $_name = 'gallery';
    
    protected $_dependentTables = array('Application_Model_DbTable_Images');
    
    
    public function getImages($id) {
        $result = $this->fetchRow(
            $this->select()->where("id = ?", $id)
        )->findDependentRowset('Application_Model_DbTable_Images');
        if ($result) {
            $array = $result->toArray();
            shuffle($array);
            return $array;
        }
    }
}

