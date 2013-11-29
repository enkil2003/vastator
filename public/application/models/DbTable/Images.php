<?php

class Application_Model_DbTable_Images extends Zend_Db_Table_Abstract
{

    protected $_name = 'images';

    protected $_referenceMap = array(
    	'Gallery' => array(
    		'columns' => array('gallery_id'),
    		'refTableClass' => 'Application_Model_DbTable_Gallery',
    		'refColumns' => array('id')
    	)
    );
}

